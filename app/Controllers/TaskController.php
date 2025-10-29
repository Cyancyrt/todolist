<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TasksModel;
use CodeIgniter\HTTP\ResponseInterface;

class TaskController extends BaseController
{
    protected $taskModel, $user, $activityModel;

    public function __construct(){
        helper(['form']);
        $this->user = session()->get('user');
        $this->taskModel = new TasksModel();
        $this->activityModel = new \App\Models\ActivitiesModel();
    }
    public function index()
    {
        return view('dashboard/task/index');
    }
    public function create($id)
    {
        return view('dashboard/task/create',  ['ActivityId' => $id]);
    }
   public function store()
    {
        $titles    = $this->request->getPost('titles');     // array
        $contents  = $this->request->getPost('contents');   // array JSON dari EditorJS
        $dueTime   = $this->request->getPost('due_time');
        $priority  = $this->request->getPost('priority');
        $activityId = $this->request->getPost('activity_id');

        // Validasi dasar
        $errors = [];
        if (!is_array($titles) || empty($titles)) {
            $errors['titles'] = 'Minimal ada 1 judul task.';
        }
        if (!is_array($contents) || empty($contents)) {
            $errors['contents'] = 'Minimal ada 1 catatan.';
        }
        if (empty($dueTime) || !preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $dueTime)) {
            $errors['due_time'] = 'Tanggal & waktu tidak valid.';
        }
        if (!in_array($priority, ['low','medium','high'])) {
            $errors['priority'] = 'Prioritas tidak valid.';
        }
        if (empty($activityId) || !is_numeric($activityId)) {
            $errors['activity_id'] = 'Activity ID tidak valid.';
        }

        // Cek activity ownership
        $activity = $this->activityModel->find($activityId);
        if (!$activity || $activity['created_by'] != session()->get('user_id')) {
            $errors['activity_id'] = 'Activity tidak ditemukan atau tidak boleh diakses.';
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        try {
            $insertData = [];
            foreach ($titles as $i => $title) {
                $insertData[] = [
                    'title'       => $title,
                    'description' => $contents[$i] ?? null, // isi catatan dari editor
                    'due_time'    => $dueTime,
                    'priority'    => $priority,
                    'activity_id' => $activityId,
                    'created_by'  => session()->get('user_id'),
                    'created_at'  => date('Y-m-d H:i:s'),
                ];
            }

            $this->taskModel->insertBatch($insertData);

            return redirect()->to(base_url('dashboard/activity'))
                            ->with('success', 'Task berhasil dibuat!');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function edit($taskId)
    {
        $task = $this->taskModel->find($taskId);
        if (!$task) {
            return redirect()->back()->with('error', 'Task tidak ditemukan atau tidak boleh diakses.');
        }
        return view('dashboard/task/edit', ['task' => $task, 'ActivityId' => $task['activity_id']]);
    }

    public function update($taskId)
    {
        $task = $this->taskModel->find($taskId);
        if (!$task || $task['created_by'] != session()->get('user_id')) {
            return redirect()->back()->with('error', 'Task tidak ditemukan atau tidak boleh diakses.');
        }

        $title      = $this->request->getPost('title');
        $content    = $this->request->getPost('content'); // JSON dari EditorJS
        $dueTime    = $this->request->getPost('due_time');
        $priority   = $this->request->getPost('priority');

        // Validasi dasar
        $errors = [];
        if (empty($title)) {
            $errors['title'] = 'Judul task tidak boleh kosong.';
        }
        if (empty($dueTime) || !preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $dueTime)) {
            $errors['due_time'] = 'Tanggal & waktu tidak valid.';
        }
        if (!in_array($priority, ['low','medium','high'])) {
            $errors['priority'] = 'Prioritas tidak valid.';
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        try {
            $this->taskModel->update($taskId, [
                'title'       => $title,
                'description' => $content,
                'due_time'    => $dueTime,
                'priority'    => $priority,
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);

            return redirect()->to(base_url('dashboard/activity'))
                            ->with('success', 'Task berhasil diperbarui!');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

}
