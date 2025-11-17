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
        if ($this->request->getMethod(true) !== 'PUT') {
            return redirect()->back()->with('error', 'Invalid request method');
        }
        
        
        $task = $this->taskModel->find($taskId);
        $activity = $this->activityModel->find($task['activity_id']);
        if (!$activity || $activity['created_by'] != session()->get('user_id')) {
            $errors['activity_id'] = 'Activity tidak ditemukan atau tidak boleh diakses.';
        }
        
        
        $title      = $this->request->getPost('titles');
        $content    = $this->request->getPost('contents'); // JSON dari EditorJS
        $dueTime    = $this->request->getPost('due_time');
        $priority   = $this->request->getPost('priority');
        
        // Validasi dasar
        $errors = [];
        if (empty($title)) {
            $errors['title'] = 'Judul task tidak boleh kosong.';
        }
        if (empty($dueTime) || !preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}(:\d{2})?$/', $dueTime)) {
            $errors['due_time'] = 'Tanggal & waktu tidak valid.';
        }
        if (!in_array($priority, ['low','medium','high'])) {
            $errors['priority'] = 'Prioritas tidak valid.';
        }
        
        if (!empty($errors)) {
            dd($errors);
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        try {
            $test = $this->taskModel->update($taskId, [
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
    public function getSubtask($id)
    {
        $task = $this->taskModel->find($id);
        // dd(session()->get());
        if (!$task) {
            return $this->response->setStatusCode(404)
                ->setJSON(['error' => 'Subtask not found']);
        }
        // validasi agar user tidak bisa akses milik orang lain
        $activity = $this->activityModel->find($task['activity_id']);
        if (!$activity || $activity['created_by'] != session()->get('user_id')) {
            $errors['activity_id'] = 'Activity tidak ditemukan atau tidak boleh diakses.';
        }


        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'id' => $task['id'],
                'title' => $task['title'],
                'description' => json_decode($task['description'], true),
                'deadline' => $task['due_time'],
                'status' => $task['status'],
            ]
        ]);
    }

    public function updateChecklist($id)
    {
        $json = $this->request->getJSON(true);

        if (!$json || !isset($json['checklist'])) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Invalid request payload']);
        }

        $checklist = $json['checklist']['blocks'] ?? null;

        // Jika checklist kosong → jangan hapus database, kembalikan warning
        if (!$checklist || count($checklist) === 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Cannot delete the last checklist item. Add at least one.'
            ]);
        }

        // Validasi ID Task
        $task = $this->taskModel->find($id);
        if (!$task) {
            return $this->response->setStatusCode(404)
                ->setJSON(['success' => false, 'message' => 'Task not found']);
        }

        // Update
        $this->taskModel->update($id, [
            'description' => json_encode($json['checklist'])
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Checklist updated successfully'
        ]);
    }


    public function completedTask($id)
    {
        $id = (int) $id; // sanitasi
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'You must log in first');
        }
        $task = $this->taskModel->find($id);
        if (!$task) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        if ($this->taskModel->getActivitybyTask($id)['created_by'] !== $userId) {
            return redirect()->back()->with('error', 'Unauthorized access to task');
        }

        $this->taskModel->update($id, [
            'status' => 'done',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Task marked as complete');
    }

}
