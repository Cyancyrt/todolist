<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsersModel;
use CodeIgniter\HTTP\ResponseInterface;

class DashboardController extends BaseController
{
    protected $tasksModel, $notesModel, $activitiesModel, $userModel;
    public function __construct()
    {
        helper('form');
        $this->userModel = new UsersModel();
        $this->tasksModel = new \App\Models\TasksModel();
        $this->notesModel = new \App\Models\NotesModel();
        $this->activitiesModel = new \App\Models\ActivitiesModel();
    }
    public function profile()
    {
        $userId = session()->get('user_id');

        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        return view('dashboard/profile/index', [
            'user' => $user
        ]);
    }

    public function index()
    {
        $userId = session()->get('user_id'); // Pastikan session login aktif
        $today  = date('Y-m-d');

        // ===============================
        // 1️⃣ SUMMARY SECTION
        // ===============================
        $completedToday = $this->tasksModel
            ->select('tasks.id')
            ->join('activities', 'activities.id = tasks.activity_id')
            ->where('activities.created_by', $userId)
            ->where('DATE(tasks.updated_at)', $today)
            ->where('tasks.status', 'completed')
            ->countAllResults();

        $plannedToday = $this->tasksModel
            ->select('tasks.id')
            ->join('activities', 'activities.id = tasks.activity_id')
            ->where('activities.created_by', $userId)
            ->where('DATE(tasks.due_time)', $today)
            ->countAllResults();

        // Activities — Weekly social type (e.g. gotong royong)
        $socialWeek = $this->activitiesModel
            ->where('created_by', $userId)
            ->where('type', 'social') // atau sesuaikan nama tipe kamu
            ->where('YEARWEEK(created_at, 1)', date('oW'))
            ->countAllResults();

        // Activities — Automation (notify_enabled = 1 berarti aktif)
        $automationPending = $this->activitiesModel
            ->where('created_by', $userId)
            ->where('notify_enabled', 1)
            ->countAllResults();

        // ===============================
        // 2️⃣ REAL-TIME TASK LIST
        // ===============================
        $tasks = $this->tasksModel
            ->select('tasks.id, tasks.title, tasks.description, tasks.due_time, tasks.status')
            ->join('activities', 'activities.id = tasks.activity_id')
            ->where('activities.created_by', $userId)
            ->orderBy('tasks.due_time', 'ASC')
            ->limit(10)
            ->findAll();

        // ===============================
        // 3️⃣ NOTES SECTION
        // ===============================
        // Misal notes disimpan dalam kolom "description"
        $notes = array_filter($tasks, function ($task) {
            return !empty($task['content']);
        });

        // ===============================
        // 4️⃣ CALENDAR DATA (untuk JS)
        // ===============================
        $calendarTasks = $this->tasksModel
            ->select('tasks.id, tasks.title, tasks.due_time, tasks.status')
            ->join('activities', 'activities.id = tasks.activity_id')
            ->where('activities.created_by', $userId)
            ->where('MONTH(tasks.due_time)', date('m'))
            ->findAll();


        // ===============================
        // 5️⃣ PROTEKSI INPUT / VALIDASI
        // ===============================
        helper('text');
        foreach ($tasks as &$task) {
            $task['title'] = esc($task['title'] ?? '');
            $task['description'] = esc($task['description'] ?? '');
            // Jika description berbentuk JSON Editor.js, ekstrak teks-nya
            $decoded = json_decode($task['description'], true);
            if (json_last_error() === JSON_ERROR_NONE && isset($decoded['blocks'])) {
                $texts = [];

                foreach ($decoded['blocks'] as $block) {
                    // Tangani berbagai tipe block (checklist, paragraph, dll)
                    if ($block['type'] === 'checklist' && isset($block['data']['items'])) {
                        foreach ($block['data']['items'] as $item) {
                            $texts[] = $item['text'] ?? '';
                        }
                    } elseif ($block['type'] === 'paragraph' && isset($block['data']['text'])) {
                        $texts[] = strip_tags($block['data']['text']);
                    }
                }

                // Gabungkan teks hasil ekstraksi menjadi satu string
                $task['description_text'] = implode(', ', array_filter($texts));
            } else {
                // Kalau bukan JSON atau kosong, fallback ke teks biasa
                $task['description_text'] = $task['description'];
            }
        }

        // ===============================
        // 6️⃣ KIRIM KE VIEW
        // ===============================
        $data = [
            'completedToday'    => $completedToday,
            'plannedToday'      => $plannedToday,
            'socialWeek'        => $socialWeek,
            'automationPending' => $automationPending,
            'tasks'             => $tasks,
            'notes'             => $notes,
            'calendarTasks'     => json_encode($calendarTasks), // untuk JS calendar.js
        ];

        return view('dashboard/index', $data);
    }

    public function task()
    {
        return view('dashboard/task/index');
    }
}
