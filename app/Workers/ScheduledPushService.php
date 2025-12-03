<?php

namespace App\Workers;

use App\Models\ActivitiesModel;
use App\Models\ActivityParticipantsModel;
use App\Models\ActivityScheduleModel;
use App\Models\ActivityExecModel;
use App\Models\TaskExecModel;
use App\Models\UserDeviceModel;
use App\Models\TasksModel;
use CodeIgniter\CLI\CLI;
use Firebase\JWT\JWT;

class ScheduledPushService
{
    protected $scheduleModel;
    protected $activityModel;
    protected $taskModel;
    protected $participantModel;
    protected $deviceModel;
    protected $activityExecModel;
    protected $taskExecModel;

    private $accessToken = null;
    private $tokenExpiresAt = 0;
    private $chFCM = null; 
    private $MAX_SCHEDULES = 20;

    public function __construct()
    {
        $this->scheduleModel    = new ActivityScheduleModel();
        $this->activityModel    = new ActivitiesModel();
        $this->taskModel        = new TasksModel();
        $this->participantModel = new ActivityParticipantsModel();
        $this->deviceModel      = new UserDeviceModel();
        $this->activityExecModel = new ActivityExecModel();
        $this->taskExecModel    = new TaskExecModel();
    }

    public function __destruct()
    {
        if ($this->chFCM) {
            curl_close($this->chFCM);
        }
    }

    private function calculateNextRun(string $current, string $recurrence): ?string
    {
        switch ($recurrence) {
            case 'daily':   return date('Y-m-d H:i:s', strtotime('+1 day', strtotime($current)));
            case 'weekly':  return date('Y-m-d H:i:s', strtotime('+1 week', strtotime($current)));
            case 'monthly': return date('Y-m-d H:i:s', strtotime('+1 month', strtotime($current)));
            default:        return null;
        }
    }

    // ------------------------ TOKEN & HTTP ------------------------
    
    private function getAccessToken(): ?string
    {
        if ($this->accessToken && time() < $this->tokenExpiresAt) {
            return $this->accessToken;
        }

        $path = APPPATH . 'Config/service-account-firebase.json';
        if (!file_exists($path)) return null;

        $data = json_decode(file_get_contents($path), true);
        if (!$data) return null;

        $now = time();
        $payload = [
            "iss" => $data['client_email'],
            "scope" => "https://www.googleapis.com/auth/firebase.messaging",
            "aud" => $data['token_uri'],
            "iat" => $now,
            "exp" => $now + 3600
        ];

        try {
            $jwt = JWT::encode($payload, $data['private_key'], 'RS256');
        } catch (\Throwable $e) {
            return null;
        }

        $ch = curl_init($data['token_uri']);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
            CURLOPT_POSTFIELDS => http_build_query([
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'  => $jwt
            ]),
            CURLOPT_TIMEOUT => 10
        ]);

        $raw = curl_exec($ch);
        $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http !== 200) {
            log_message('error', 'FCM token request failed');
            return null;
        }

        $res = json_decode($raw, true);
        $this->accessToken = $res['access_token'] ?? null;
        $this->tokenExpiresAt = time() + 3500;

        return $this->accessToken;
    }

    private function sendFCMSingle($token, $title, $body, $data = [])
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) return false;

        $projectId = env('FIREBASE_PROJECT_ID');
        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        if (!$this->chFCM) {
            $this->chFCM = curl_init();
            curl_setopt_array($this->chFCM, [
                CURLOPT_URL => $url,
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_TIMEOUT => 5,
                CURLOPT_TCP_KEEPALIVE => 1,
            ]);
        }

        $payload = [
            'message' => [
                'token' => $token,
                'notification' => ['title' => $title, 'body' => $body],
                'data' => $data
            ]
        ];

        curl_setopt($this->chFCM, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$accessToken}",
            "Content-Type: application/json"
        ]);
        curl_setopt($this->chFCM, CURLOPT_POSTFIELDS, json_encode($payload));

        $resp = curl_exec($this->chFCM);
        $http = curl_getinfo($this->chFCM, CURLINFO_HTTP_CODE);

        if (curl_errno($this->chFCM)) {
            log_message('error', 'FCM Curl Error: ' . curl_error($this->chFCM));
            curl_close($this->chFCM);
            $this->chFCM = null; 
            return false;
        }

        return ($http >= 200 && $http < 300);
    }

    // ------------------------ DATA FETCHING ------------------------
    
    private function fetchParticipantsAndDevicesForActivities(array $activityIds)
    {
        if (empty($activityIds)) return [[], []];

        $parts = $this->participantModel
            ->select('activity_id, user_id')
            ->whereIn('activity_id', $activityIds)
            ->findAll();

        $usersByActivity = [];
        $userIds = [];
        foreach ($parts as $p) {
            $usersByActivity[$p['activity_id']][] = $p['user_id'];
            $userIds[] = $p['user_id'];
        }
        $userIds = array_values(array_unique($userIds));

        $devices = [];
        if (!empty($userIds)) {
            $rows = $this->deviceModel
                ->select('id,user_id,token')
                ->whereIn('user_id', $userIds)
                ->findAll();

            foreach ($rows as $r) {
                $devices[$r['user_id']][] = $r;
            }
        }

        return [$usersByActivity, $devices];
    }

    // ------------------------ REMINDERS ------------------------
    
    private function sendActivityRemindersBatch()
    {
        $hourStart = date('Y-m-d H:i:s', strtotime('+59 minutes'));
        $hourEnd   = date('Y-m-d H:i:s', strtotime('+61 minutes'));
        $halfStart = date('Y-m-d H:i:s', strtotime('+29 minutes'));
        $halfEnd   = date('Y-m-d H:i:s', strtotime('+31 minutes'));

        $schedules = $this->scheduleModel
            ->where("(next_run_at BETWEEN '{$hourStart}' AND '{$hourEnd}') OR (next_run_at BETWEEN '{$halfStart}' AND '{$halfEnd}')", null, false)
            ->findAll();

        if (empty($schedules)) return;

        $activityIds = array_unique(array_column($schedules, 'activity_id'));
        [$usersByActivity, $devicesByUser] = $this->fetchParticipantsAndDevicesForActivities($activityIds);

        foreach ($schedules as $s) {
            $diff = strtotime($s['next_run_at']) - time();
            $msg = ($diff > 3000) 
                ? "Pengingat: 1 jam lagi aktivitas akan dimulai!" 
                : "Pengingat: 30 menit lagi aktivitas akan dimulai!";

            $aId = $s['activity_id'];
            $userList = $usersByActivity[$aId] ?? [];
            
            foreach ($userList as $uid) {
                $devs = $devicesByUser[$uid] ?? [];
                foreach ($devs as $d) {
                    $this->sendFCMSingle($d['token'], 'Activity Reminder', $msg, ['activity_id' => (string)$aId]);
                }
            }
        }
    }

    private function sendTaskRemindersBatch()
    {
        $hourStart = date('Y-m-d H:i:s', strtotime('+59 minutes'));
        $hourEnd   = date('Y-m-d H:i:s', strtotime('+61 minutes'));
        $halfStart = date('Y-m-d H:i:s', strtotime('+29 minutes'));
        $halfEnd   = date('Y-m-d H:i:s', strtotime('+31 minutes'));

        $tasks = $this->taskModel
            ->where("(due_time BETWEEN '{$hourStart}' AND '{$hourEnd}') OR (due_time BETWEEN '{$halfStart}' AND '{$halfEnd}')", null, false)
            ->where('status !=', 'done')
            ->findAll();

        if (empty($tasks)) return;

        $uids = array_unique(array_column($tasks, 'user_id'));
        $devRows = $this->deviceModel->whereIn('user_id', $uids)->findAll();
        $devByUser = [];
        foreach ($devRows as $r) $devByUser[$r['user_id']][] = $r;

        foreach ($tasks as $t) {
            $diff = strtotime($t['due_time']) - time();
            $msg = ($diff > 3000) 
                ? "Pengingat: 1 jam lagi deadline tugas {$t['title']}!" 
                : "Pengingat: 30 menit lagi deadline tugas {$t['title']}!";
                
            $devs = $devByUser[$t['user_id']] ?? [];
            foreach ($devs as $d) {
                $this->sendFCMSingle($d['token'], 'Task Reminder', $msg, ['task_id' => (string)$t['id']]);
            }
        }
    }

    // ------------------------ LOGIC MISSED & UPDATE ------------------------
    
    private function updateTaskDueTimes($activityId, $recurrence)
    {
        if (!$recurrence || $recurrence === 'none') return;
        
        $tasks = $this->taskModel
            ->where('activity_id', $activityId)
            ->whereNotIn('status', ['done', 'missed']) 
            ->findAll();

        foreach ($tasks as $task) {
            if (!$task['due_time']) continue;
            
            $next = $this->calculateNextRun($task['due_time'], $recurrence);
            if ($next) {
                $this->taskModel->update($task['id'], ['due_time' => $next]);
            }
        }
    }

    /**
     * LOGIC UTAMA PENGELOLAAN "MISSED"
     */
    private function autoMissOverdueOptimized()
    {
        $now = date('Y-m-d H:i:s');
        
        $rows = $this->scheduleModel
            ->select('activity_schedule.*, activities.recurrence, activities.id AS act_real_id')
            ->join('activities', 'activities.id = activity_schedule.activity_id')
            ->where('activity_schedule.next_run_at <=', $now)
            ->where('activities.status !=', 'done')
            ->limit(50) 
            ->findAll();

        if (empty($rows)) return;

        $activityIds = array_column($rows, 'activity_id');
        [$usersByActivity, $devicesByUser] = $this->fetchParticipantsAndDevicesForActivities($activityIds);

        foreach ($rows as $r) {
            try {
                $this->activityModel->db->transStart();

                // 1. TENTUKAN STATUS MASTER
                // Jika daily: tetap 'upcoming' (agar user tidak melihat merah, langsung jadwal besok)
                // Jika weekly/lainnya: ubah ke 'missed' (nanti ada fungsi lain yg reset setelah 24 jam)
                $newStatus = ($r['recurrence'] === 'daily') ? 'upcoming' : 'done';

                // Update Status Activity
                $this->activityModel->update($r['activity_id'], ['status' => $newStatus]);

                // 2. Insert Log Exec (TETAP DICATAT MISSED untuk History)
                $execId = $this->activityExecModel->insert([
                    'activity_id' => $r['activity_id'],
                    'status'      => 'done',
                    'started_at'  => $r['next_run_at'],
                    'finished_at' => $now,
                    'created_at'  => $now
                ]);

                // 3. Handle Tasks (TETAP DICATAT MISSED untuk History tugas hari itu)
                $tasks = $this->taskModel
                    ->where('activity_id', $r['activity_id'])
                    ->where('status !=', 'done')
                    ->findAll();
                
                $taskExecBatch = [];
                foreach ($tasks as $t) {
                    $this->taskModel->update($t['id'], ['status' => 'missed']);
                    $taskExecBatch[] = [
                        'task_id' => $t['id'],
                        'activity_exec_id' => $execId,
                        'status' => 'missed',
                        'created_at' => $now
                    ];
                }
                if (!empty($taskExecBatch)) {
                    $this->taskExecModel->insertBatch($taskExecBatch);
                }

                // 4. Update Schedule Recurrence (Geser jadwal ke depan)
                $nextRun = $this->calculateNextRun($r['next_run_at'], $r['recurrence']);
                $this->scheduleModel->update($r['id'], [
                    'next_run_at' => $nextRun,
                    'last_run_at' => $now, // Penting: Ini jadi penanda kapan missed terjadi
                    'run_count'   => ($r['run_count'] ?? 0) + 1
                ]);

                // 5. Update Task Due Dates (Geser deadline task ke depan)
                $this->updateTaskDueTimes($r['activity_id'], $r['recurrence']);
                
                // Jika Daily, reset status tasks yg tadinya dimissed agar siap untuk besok
                if ($r['recurrence'] === 'daily') {
                     $this->taskModel
                        ->where('activity_id', $r['activity_id'])
                        ->whereIn('status', ['missed', 'done']) // Ambil yg baru saja dimissed dan yg sudah done
                        ->set(['status' => 'upcoming'])
                        ->update();
                }

                $this->activityModel->db->transComplete();

                // 6. Notify
                if ($this->activityModel->db->transStatus()) {
                    $userList = $usersByActivity[$r['activity_id']] ?? [];
                    foreach ($userList as $uid) {
                        $devs = $devicesByUser[$uid] ?? [];
                        foreach ($devs as $d) {
                            $this->sendFCMSingle($d['token'], 'Activity Missed', "Aktivitas terlewat.", ['activity_id' => (string)$r['activity_id']]);
                        }
                    }
                }

            } catch (\Throwable $e) {
                log_message('error', 'AutoMiss Error: ' . $e->getMessage());
            }
        }
    }

    /**
     * BARU: Fungsi untuk mengembalikan status 'missed' menjadi 'upcoming'
     * khusus untuk recurrence Weekly setelah 24 jam.
     */
    private function checkAndResetMissedWeekly()
    {
        // 1. Cari Activity yang statusnya 'missed' DAN recurrence 'weekly'
        // Join dengan schedule untuk melihat kapan 'last_run_at' (waktu kejadian missed)
        $rows = $this->activityModel
            ->select('activities.id, activities.recurrence, activity_schedule.last_run_at')
            ->join('activity_schedule', 'activity_schedule.activity_id = activities.id')
            ->where('activities.status', 'missed')
            ->where('activities.recurrence', 'weekly')
            ->findAll();

        if (empty($rows)) return;

        $oneDayAgo = time() - (24 * 60 * 60); // 24 jam yang lalu

        foreach ($rows as $r) {
            // Jika last_run_at (waktu missed) lebih tua dari 24 jam yang lalu
            if (strtotime($r['last_run_at']) <= $oneDayAgo) {
                
                // Kembalikan status activity jadi upcoming
                $this->activityModel->update($r['id'], ['status' => 'upcoming']);

                // Opsional: Kembalikan status task yang missed jadi upcoming juga
                // Agar di list tugas user terlihat aktif kembali untuk minggu depan
                $this->taskModel
                    ->where('activity_id', $r['id'])
                    ->where('status', 'missed')
                    ->set(['status' => 'upcoming'])
                    ->update();
                
                CLI::write("Reset Activity ID {$r['id']} (Weekly) from Missed to Upcoming");
            }
        }
    }

    // ------------------------ MAIN RUN ------------------------

/*************  ✨ Windsurf Command ⭐  *************/
/**
 * Main run function for the worker.
 * This function will run the worker every minute to check for upcoming activities and tasks.
 * It will also handle missed activities and tasks by resetting their status and notifying the users.
 */
/*******  12978736-0833-4b74-a347-929a2e01fd95  *******/    public function run()
    {
        $now = date('Y-m-d H:i:s');
        CLI::write("Worker running at {$now}");

        // 1. Kirim Reminder & Handle Missed/Reset
        $this->sendActivityRemindersBatch();
        $this->sendTaskRemindersBatch();
        $this->autoMissOverdueOptimized();
        $this->checkAndResetMissedWeekly();

        // 2. Handle RUNNING Schedule
        $schedules = $this->scheduleModel
            ->where('next_run_at <=', $now)
            ->orderBy('next_run_at', 'ASC')
            ->limit($this->MAX_SCHEDULES)
            ->findAll();

        if (empty($schedules)) {
            CLI::write("No active schedules.");
            return;
        }

        $activityIds = array_unique(array_column($schedules, 'activity_id'));
        [$usersByActivity, $devicesByUser] = $this->fetchParticipantsAndDevicesForActivities($activityIds);

        foreach ($schedules as $s) {
            $activity = $this->activityModel->find($s['activity_id']);
            if (!$activity) continue;

            // Lock Schedule
            $locked = $this->scheduleModel
                ->where('id', $s['id'])
                ->where('next_run_at', $s['next_run_at'])
                ->set(['last_run_at' => $now])
                ->update();

            if (!$locked) continue;

            // Notify
            $userList = $usersByActivity[$s['activity_id']] ?? [];
            foreach ($userList as $uid) {
                $devs = $devicesByUser[$uid] ?? [];
                foreach ($devs as $d) $this->sendFCMSingle($d['token'], 'Activity Started', "Waktunya: {$activity['name']}");
            }

            try {
                $this->activityModel->db->transStart();
                
                // Log History (Exec)
                $execId = $this->activityExecModel->insert([
                    'activity_id' => $s['activity_id'],
                    'status'      => 'done',
                    'started_at'  => $now,
                    'finished_at' => $now,
                    'created_at'  => $now
                ]);

                // Log Tasks
                $tasks = $this->taskModel->where('activity_id', $s['activity_id'])->findAll();
                $taskExecBatch = [];
                foreach ($tasks as $t) {
                    $taskExecBatch[] = [
                        'task_id'          => $t['id'],
                        'activity_exec_id' => $execId,
                        'status'           => 'upcoming',
                        'created_at'       => $now
                    ];
                }
                if (!empty($taskExecBatch)) {
                    $this->taskExecModel->insertBatch($taskExecBatch);
                }

                // ========================================================
                // UPDATE RECURRENCE / SELESAIKAN SINGLE RUN
                // ========================================================
                
                $nextRun = $this->calculateNextRun($s['next_run_at'], $activity['recurrence'] ?? 'none');

                if ($nextRun) {
                    // --- KASUS 1: ADA PENGULANGAN (Daily/Weekly/Monthly) ---
                    $this->scheduleModel->update($s['id'], [
                        'next_run_at' => $nextRun,
                        'run_count'   => ($s['run_count'] ?? 0) + 1
                    ]);
                    
                    $this->updateTaskDueTimes($s['activity_id'], $activity['recurrence'] ?? null);

                    // Pastikan status tetap upcoming
                    if ($activity['status'] !== 'upcoming') {
                         $this->activityModel->update($s['activity_id'], ['status' => 'upcoming']);
                    }
                    
                    // Log sukses recurrence
                    CLI::write("Processed ID: {$s['id']} (Recurring) -> Next: {$nextRun}");

                } else {
                    // --- KASUS 2: TIDAK ADA PENGULANGAN ('none') ---
                    // 1. Hapus jadwal
                    $this->scheduleModel->delete($s['id']);

                    // 2. Update status Activity jadi DONE
                    $this->activityModel->update($s['activity_id'], ['status' => 'done']);

                    // 3. Update semua Task jadi DONE
                    $this->taskModel
                        ->where('activity_id', $s['activity_id'])
                        ->set(['status' => 'done'])
                        ->update();

                    // Log sukses one-time
                    CLI::write("Processed ID: {$s['id']} (One-time) -> Finished/Done.");
                }

                $this->activityModel->db->transComplete();
                

            } catch (\Throwable $e) {
                log_message('error', 'Run Error: ' . $e->getMessage());
            }
        }
        
        CLI::write("Worker finished.");
    }
}