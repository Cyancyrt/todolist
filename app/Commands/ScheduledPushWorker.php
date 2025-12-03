<?php

namespace App\Commands;

use App\Models\ActivitiesModel;
use App\Models\ActivityParticipantsModel;
use App\Models\ActivityScheduleModel;

use App\Models\UserDeviceModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Firebase\JWT\JWT;

class ScheduledPushWorker extends BaseCommand
{
    protected $group       = 'FCM';
    protected $name        = 'worker:scheduledpush';
    protected $description = 'Worker untuk mengirim push notification FCM sesuai schedule (daily example)';

    // token cache
    private $accessToken = null;
    private $tokenExpiresAt = 0; // epoch
    private function calculateNextRun(string $current, string $recurrence): ?string
    {
        CLI::write('calculate ' . $current . ' - ' . $recurrence);
        if (!$current) {
            return null;
        }
        switch ($recurrence) {
            case 'daily':
                return date('Y-m-d H:i:s', strtotime('+1 day', strtotime($current)));

            case 'weekly':
                return date('Y-m-d H:i:s', strtotime('+1 week', strtotime($current)));

            case 'monthly':
                return date('Y-m-d H:i:s', strtotime('+1 month', strtotime($current)));

            case 'none':
            default:
                return null; // tidak ada schedule berikutnya
        }
    }


    public function run(array $params)
    {
        $scheduleModel      = new ActivityScheduleModel();
        $participantModel   = new ActivityParticipantsModel();
        $deviceModel        = new UserDeviceModel();
        $activityModel      = new ActivitiesModel();

        // optional param untuk development: php spark worker:scheduledpush --max=5
        $maxLoop = CLI::getOption('max') ? (int) CLI::getOption('max') : null;
        $loopCount = 0;

        CLI::write("ScheduledPushWorker started. maxLoop=" . ($maxLoop ?? '∞'));

        while (true) {
            $loopCount++;
            $now = date('Y-m-d H:i:s');
            CLI::write("Checking schedules at {$now}...");

            // ambil schedule yang waktunya sudah tiba
            $schedules = $scheduleModel
                ->where('next_run_at <=', $now)
                ->findAll();

            if (!empty($schedules)) {
                CLI::write('Found ' . count($schedules) . ' schedule(s) to process at ' . $now);
            }

            foreach ($schedules as $schedule) {
                $scheduleId = $schedule['id'];
                $activityId = $schedule['activity_id'];
                $activity = $activityModel->find($activityId);
                $recurrence = $activity['recurrence'] ?? 'none';

                // optional: optimistic locking - update next_run_at to avoid double processing
                // hanya lakukan update kalau row masih memiliki next_run_at yang sama
                // ini mengurangi race condition kalau ada multiple worker
                $currentNextRunAt = $schedule['next_run_at'];

                $updateOk = $scheduleModel->where('id', $scheduleId)
                    ->where('next_run_at', $currentNextRunAt)
                    ->set(['next_run_at' => $currentNextRunAt]) // no-op, but lock intent (or use status column)
                    ->set(['last_run_at' => $now]) // we will correct next_run_at after processing
                    ->update();

                // even if updateOk false, we still proceed — alternative: skip if false
                if (!$updateOk) {
                    CLI::write("Skipping schedule {$scheduleId} (possibly processed by another worker).");
                    continue;
                }

                // ambil peserta activity (activity_participants)
                $participants = $participantModel->where('activity_id', $activityId)->findAll();

               if (empty($participants)) {
                    CLI::write("No participants for activity {$activityId}, marking run and continuing.");

                    // Hitung next_run berdasarkan recurrence
                    $nextRun = $this->calculateNextRun($currentNextRunAt, $recurrence);

                    $scheduleModel->update($scheduleId, [
                        'last_run_at' => $now,
                        'next_run_at' => $nextRun,
                        'run_count'   => $schedule['run_count'] + 1
                    ]);

                    CLI::write("Updated schedule {$scheduleId} with no participants. Next run at {$nextRun}");
                    continue;
                }

                foreach ($participants as $p) {
                    $userId = $p['user_id'];

                    // ambil semua device untuk user ini
                    $devices = $deviceModel->where('user_id', $userId)->findAll();
                    if (empty($devices)) {
                        CLI::write("User {$userId} has no devices, skipping.");
                        continue;
                    }

                    foreach ($devices as $device) {
                        $token = $device['token'];

                        // siapkan payload - customizable per activity/user
                        $payload = [
                            'token' => $token,
                            'title' => 'Reminder Activity',
                            'body'  => "Waktunya aktivitas {$activity['name']}",
                        ];

                        try {
                            $ok = $this->sendFCM($payload);
                            if ($ok) {
                                CLI::write("Sent to user {$userId} device {$device['id']}");
                            } else {
                                CLI::write("Failed to send to user {$userId} device {$device['id']}");
                            }
                        } catch (\Throwable $e) {
                            CLI::error("Exception sending to device {$device['id']}: " . $e->getMessage());
                        }
                    } // end devices loop
                } // end participants loop



                $nextRun = $this->calculateNextRun($currentNextRunAt, $recurrence);

                $scheduleModel->update($scheduleId, [
                    'last_run_at' => $now,
                    'next_run_at' => $nextRun,
                    'run_count'   => $schedule['run_count'] + 1
                ]);


                CLI::write("Schedule {$scheduleId} updated: next_run_at={$nextRun}");
            } // end schedules loop

            // development: optional max loop
            if ($maxLoop && $loopCount >= $maxLoop) {
                CLI::write("Max loop reached ({$maxLoop}). Exiting.");
                break;
            }

            // sleep singkat sebelum cek lagi
            sleep(10);
        } // end while
    }
    private function getAccessToken()
    {
        $json = file_get_contents(APPPATH . 'Config/service-account-firebase.json');
        $data = json_decode($json, true);

        $now = time();
        $payload = [
            "iss" => $data['client_email'],
            "scope" => "https://www.googleapis.com/auth/firebase.messaging",
            "aud" => $data['token_uri'],
            "iat" => $now,
            "exp" => $now + 3600
        ];

        $jwt = JWT::encode($payload, $data['private_key'], 'RS256');

        // Request token ke Google OAuth
        $ch = curl_init($data['token_uri']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion'  => $jwt
        ]));
        $response = curl_exec($ch);
        curl_close($ch);

        $res = json_decode($response, true);
        return $res['access_token'] ?? null;
    }
    private function sendFCM($payload)
    {
        CLI::write("---- Sending FCM ----", 'yellow');

        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            CLI::error("Failed to generate Access Token!");
            CLI::write("Payload aborted for token: " . $payload['token']);
            return false;
        }

        // Build FCM URL
        $projectId = env('FIREBASE_PROJECT_ID');
        $fcmUrl = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        CLI::write("FCM URL: {$fcmUrl}");
        CLI::write("User Token: " . $payload['token']);
        CLI::write("Notification Title: " . $payload['title']);

        $body = [
            'message' => [
                'token' => $payload['token'],
                'notification' => [
                    'title' => $payload['title'],
                    'body'  => $payload['body']
                ],
                'data' => $payload['data'] ?? []
            ]
        ];

        CLI::write("Request Body:");
        CLI::write(json_encode($body, JSON_PRETTY_PRINT));

        $ch = curl_init($fcmUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $accessToken",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));

        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        CLI::write("HTTP Status: " . $httpCode);

        if ($curlError) {
            CLI::error("cURL Error: $curlError");
            return false;
        }

        CLI::write("FCM Response:");
        CLI::write($response);

        // Decode response to check success
        $result = json_decode($response, true);

        if (isset($result['name'])) {
            CLI::write("✅ Success: Message sent with ID: " . $result['name'], 'green');
            return true;
        }

        if (isset($result['error'])) {
            CLI::error("❌ FCM Error:");
            CLI::error(json_encode($result['error'], JSON_PRETTY_PRINT));
        } else {
            CLI::error("❌ Unknown error sending FCM");
        }

        return false;
    }
}
