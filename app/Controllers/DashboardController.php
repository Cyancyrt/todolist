<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsersModel;
use App\Models\TaskExecModel; 
use App\Models\ActivityExecModel; 
use App\Models\ActivityScheduleModel; 

class DashboardController extends BaseController
{
    protected $tasksModel, $notesModel, $activitiesModel, $userModel;
    protected $taskExecModel, $activityExecModel, $scheduleModel;

    public function __construct()
    {
        helper('form');
        $this->userModel       = new UsersModel();
        $this->tasksModel      = new \App\Models\TasksModel();
        $this->notesModel      = new \App\Models\NotesModel();
        $this->activitiesModel = new \App\Models\ActivitiesModel();
        
        $this->taskExecModel     = new TaskExecModel();
        $this->activityExecModel = new ActivityExecModel();
        $this->scheduleModel     = new ActivityScheduleModel();
    }

    public function profile()
    {
        $userId = session()->get('user_id');
        if (!$userId) return redirect()->to('/login')->with('error', 'Silakan login.');
        $user = $this->userModel->find($userId);
        if (!$user) return redirect()->back()->with('error', 'User tidak ditemukan.');
        return view('dashboard/profile/index', ['user' => $user]);
    }

    public function index()
    {
        $userId = session()->get('user_id'); 
        $today  = date('Y-m-d');

        // ============================================================
        // 1️⃣ LOGIKA "COMPLETED TODAY" (HANYA YANG DONE)
        // ============================================================
        
        // Hitung dari Exec (Riwayat Nyata)
        $doneTaskCount = $this->taskExecModel
            ->join('tasks', 'tasks.id = task_exec.task_id')
            ->join('activities', 'activities.id = tasks.activity_id')
            ->where('activities.created_by', $userId)
            ->where('DATE(task_exec.created_at)', $today)
            ->where('task_exec.status', 'done')
            ->countAllResults();

        $doneActCount = $this->activityExecModel
            ->join('activities', 'activities.id = activity_exec.activity_id')
            ->where('activities.created_by', $userId)
            ->where('DATE(activity_exec.created_at)', $today)
            ->where('activity_exec.status', 'done')
            ->countAllResults();

        $completedToday = $doneTaskCount + $doneActCount;


        // ============================================================
        // 2️⃣ LOGIKA "PLANNED TODAY" (TOTAL BEBAN KERJA HARI INI)
        // Rumus: (Sudah Terjadi Hari Ini) + (Masih Harus Terjadi Hari Ini)
        // ============================================================
        
        // A. BUCKET MASA LALU (History Exec Hari Ini: Done + Missed)
        $historyTask = $this->taskExecModel
            ->join('tasks', 'tasks.id = task_exec.task_id')
            ->join('activities', 'activities.id = tasks.activity_id')
            ->where('activities.created_by', $userId)
            ->where('DATE(task_exec.created_at)', $today)
            ->countAllResults(); // Menghitung Done + Missed

        $historyAct = $this->activityExecModel
            ->join('activities', 'activities.id = activity_exec.activity_id')
            ->where('activities.created_by', $userId)
            ->where('DATE(activity_exec.created_at)', $today)
            ->countAllResults();

        // B. BUCKET MASA DEPAN (Master/Schedule Upcoming Hari Ini)
        // Syarat Anti-Duplikat: Status harus 'upcoming' DAN Tanggal harus Hari Ini.
        // (Jika sudah done, status/tanggal pasti sudah berubah, jadi tidak terambil disini)
        
        $upcomingTask = $this->tasksModel
            ->join('activities', 'activities.id = tasks.activity_id')
            ->where('activities.created_by', $userId)
            ->where('DATE(tasks.due_time)', $today)
            ->where('tasks.status', 'upcoming') 
            ->countAllResults();

        $upcomingAct = $this->scheduleModel
            ->join('activities', 'activities.id = activity_schedule.activity_id')
            ->where('activities.created_by', $userId)
            ->where('DATE(activity_schedule.next_run_at)', $today)
            // Schedule tidak punya status, tapi next_run_at akan digeser worker jika selesai
            ->countAllResults();

        $plannedToday = ($historyTask + $historyAct) + ($upcomingTask + $upcomingAct);


        // ============================================================
        // 3️⃣ DATA PENDUKUNG LAINNYA
        // ============================================================

        $socialWeek = $this->activitiesModel
            ->where('created_by', $userId)
            ->where('type', 'social') 
            ->where('YEARWEEK(created_at, 1)', date('oW'))
            ->countAllResults();

        $automationPending = $this->activitiesModel
            ->where('created_by', $userId)
            ->where('notify_enabled', 1)
            ->countAllResults();

        // Task List (Untuk ditampilkan di tabel)
        $startWindow = date('Y-m-d 00:00:00', strtotime("$today -3 days"));
        $endWindow   = date('Y-m-d 23:59:59', strtotime("$today +3 days"));

        $tasks = $this->tasksModel
            ->select('tasks.id, tasks.title, tasks.description, tasks.due_time, tasks.status')
            ->join('activities', 'activities.id = tasks.activity_id')
            ->where('activities.created_by', $userId)
            ->where('tasks.due_time >=', $startWindow)
            ->where('tasks.due_time <=', $endWindow)
            ->orderBy('tasks.due_time', 'ASC')
            ->findAll();

        $activityDummy = $this->activitiesModel->where('created_by', $userId)->first();
        $activityName  = $activityDummy ? $activityDummy['name'] : null;

        $notes = $this->notesModel->where('user_id', $userId)->findAll();

        // 4️⃣ CALENDAR DATA (GABUNGAN TASK + ACTIVITY SCHEDULE)
        // ====================================================
        
        // A. Ambil Tasks
        $tasksData = $this->tasksModel
            ->select('tasks.id, tasks.title, tasks.due_time, tasks.status, "task" as type')
            ->join('activities', 'activities.id = tasks.activity_id')
            ->where('activities.created_by', $userId)
            ->where('MONTH(tasks.due_time)', date('m'))
            ->findAll();

        // B. Ambil Activity Schedules
        // Kita join ke activities untuk dapat nama aktivitasnya
        $activityData = $this->scheduleModel
            ->select('activities.id, activities.name as title, activity_schedule.next_run_at as due_time, activities.status, "activity" as type')
            ->join('activities', 'activities.id = activity_schedule.activity_id')
            ->where('activities.created_by', $userId)
            ->where('MONTH(activity_schedule.next_run_at)', date('m'))
            ->findAll();

        // C. Gabungkan keduanya
        $calendarTasks = array_merge($tasksData, $activityData);
        
        // Proteksi XSS / Format Output
        helper('text');
        foreach ($tasks as &$task) {
            $task['title'] = esc($task['title'] ?? '');
            
            // Logika parsing JSON description (Editor.js)
            $raw = html_entity_decode($task['description'] ?? '', ENT_QUOTES, 'UTF-8');
            $decoded = json_decode($raw, true);
            
            if (json_last_error() === JSON_ERROR_NONE && isset($decoded['blocks'])) {
                $texts = [];
                foreach ($decoded['blocks'] as $block) {
                    if ($block['type'] === 'checklist' && isset($block['data']['items'])) {
                        foreach ($block['data']['items'] as $item) $texts[] = $item['text'] ?? '';
                    } elseif ($block['type'] === 'paragraph' && isset($block['data']['text'])) {
                        $texts[] = strip_tags($block['data']['text']);
                    }
                }
                $task['description_text'] = implode(', ', array_filter($texts));
            } else {
                $task['description_text'] = strip_tags($task['description'] ?? '');
            }
        }

        // Return View
        $data = [
            'completedToday'    => $completedToday,
            'plannedToday'      => $plannedToday,
            'socialWeek'        => $socialWeek,
            'automationPending' => $automationPending,
            'tasks'             => $tasks,
            'notes'             => $notes,
            'activityDummy'     => $activityName,
            'calendarTasks'     => json_encode($calendarTasks), 
        ];

        return view('dashboard/index', $data);
    }
}