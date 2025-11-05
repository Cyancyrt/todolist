<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ActivityController extends BaseController
{
    protected $activityModel, $scheduleModel,$taskModel, $user, $db;

    public function __construct(){
        helper(['form']);
        $this->user = session()->get('user');
        $this->activityModel = new \App\Models\ActivitiesModel();
        $this->scheduleModel = new \App\Models\ActivityScheduleModel();
        $this->taskModel = new \App\Models\TasksModel();
        $this->db = \Config\Database::connect();
        if (!$this->db) {
            log_message('error', 'Gagal terhubung ke database');
            throw new \RuntimeException('Gagal terhubung ke database');
        }
    }
    public function index()
    {
        $data = $this->activityModel->getAllWithRelations();
        foreach ($data as &$activity) {
            $activity['tasks'] = $this->taskModel->getTasksByActivity($activity['id']);
        }
        return view('dashboard/activity/index', ['data' => $data]);
    }

    public function create()
    {
        return view('dashboard/activity/create');
    }

    public function save(){
        $db =  \Config\Database::connect();
        $rules = [
            'name' => 'required|min_length[3]|max_length[150]',
            'type' => 'required|in_list[personal,social]',
            'description' => 'permit_empty|max_length[500]',
            'next_run_at' => 'required', // because bisa multiple
            'recurrence' => 'required|in_list[none,daily,weekly,monthly]',
            'created_by' => 'required|integer'
        ];
        if (!$this->validate($rules)) {
            dd($this->validator->getErrors());
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        try {
            $db->transStart();
            $activityId = $this->activityModel->insert([
                'name' => $this->request->getPost('name'),
                'type' => $this->request->getPost('type'),
                'description' => $this->request->getPost('description'),
                'recurrence' => $this->request->getPost('recurrence'),
                'created_by' => session()->get('user_id'),
                'status' => 'upcoming',
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            // Insert schedule dates
            $dates = $this->request->getPost('next_run_at'); // array dari form
            if (!is_array($dates)) {
                $dates = [$dates];
            }
            foreach ($dates as $date) {
                try {
                    $this->scheduleModel->insert([
                        'activity_id' => $activityId,
                        'next_run_at' => date('Y-m-d H:i:s', strtotime($date)),
                    ]);
                } catch (\Throwable $th) {
                    log_message('error', 'Gagal menyimpan jadwal aktivitas. Error: ' . $th->getMessage());
                    throw new \Exception('Gagal menyimpan jadwal aktivitas.');
                }
            }

           $db->transComplete();

            if ($db->transStatus() === false) {
                log_message('error', 'Gagal menyimpan aktivitas. Error: ' . $this->db->error());
                throw new \Exception('Gagal menyimpan aktivitas.');
            }

            return redirect()->to('/dashboard/activity')->with('success', 'Aktivitas berhasil dibuat!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
    public function edit($id)
    {
        $activity = $this->activityModel->find($id);
        if (!$activity) {
            return redirect()->to('/dashboard/activity')->with('error', 'Aktivitas tidak ditemukan.');
        }
        $schedules = $this->scheduleModel->where('activity_id', $id)->findAll();
        return view('dashboard/activity/edit', [
            'activity' => $activity,
            'schedules' => $schedules
        ]);
    }
    public function update($activityId){
        $db =  \Config\Database::connect();
        $rules = [
            'name' => 'required|min_length[3]|max_length[150]',
            'type' => 'required|in_list[personal,social]',
            'description' => 'permit_empty|max_length[500]',
            'next_run_at' => 'required', // because bisa multiple
            'recurrence' => 'required|in_list[none,daily,weekly,monthly]',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        try {
            $db->transStart();
            $dates = $this->request->getPost('schedule_date'); // array dari form
            if (!is_array($dates)) {
                $dates = [$dates];
            }
            try {
                $this->scheduleModel->where('activity_id', $activityId)->delete();
                $newSchedules = [];
                foreach ($dates as $date) {
                    $newSchedules[] = [
                        'activity_id'   => $activityId,
                        'next_run_at' => date('Y-m-d H:i:s', strtotime($date))
                    ];
                }
                if (!empty($newSchedules)) {
                    $this->scheduleModel->insertBatch($newSchedules);
                }
            } catch (\Throwable $th) {
                log_message('error', 'Gagal menyimpan jadwal aktivitas. Error: ' . $th->getMessage());
                throw new \Exception('Gagal menyimpan jadwal aktivitas.');
            }

            $activityData = [
                'name' => $this->request->getPost('name'),
                'type' => $this->request->getPost('type'),
                'description' => $this->request->getPost('description'),
                'recurrence' => $this->request->getPost('recurrence'),
                'created_by' => session()->get('user_id'),
                'status' => 'upcoming',
            ];

            $this->activityModel->update($activityId, $activityData);

           $db->transComplete();

            if ($db->transStatus() === false) {
                $db->transRollback();
                log_message('error', 'Gagal menyimpan aktivitas. Error: ' . $this->db->error());
                throw new \Exception('Gagal menyimpan aktivitas.');
            }

            return redirect()->to('/dashboard/activity')->with('success', 'Aktivitas berhasil diubah!');
        } catch (\Throwable $th) {
            if ($db->transStatus() === false) {
                $db->transRollback();
            }
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }
    public function delete($activityId = null)
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->back()->with('error', 'Invalid request method.');
        }
        if (empty($activityId) || !is_numeric($activityId)) {
            return redirect()->back()->with('error', 'Invalid activity ID.');
        }
        $userId = session()->get('user_id');

        try {
            $db = \Config\Database::connect();
            $db->transStart();

            $activity = $this->activityModel->find($activityId);
            if (!$activity) {
                return redirect()->back()->with('error', 'Activity not found.');
            }

            if ($activity['created_by'] != $userId && !session()->get('is_admin')) {
                return redirect()->back()->with('error', 'You do not have permission to delete this activity.');
            }

            $this->scheduleModel->where('activity_id', $activityId)->delete();

            $this->activityModel->delete($activityId);

            $db->transComplete();

            if ($db->transStatus() === false) {
                $db->transRollback();
                log_message('error', 'Failed to delete activity ID ' . $activityId);
                throw new \Exception('Failed to delete activity due to database error.');
            }

            return redirect()->to('/dashboard/activity')->with('success', 'Activity successfully deleted!');

        } catch (\Throwable $th) {
            if (isset($db) && $db->transStatus() === false) {
                $db->transRollback();
            }
            log_message('error', 'Error deleting activity ID ' . $activityId . ': ' . $th->getMessage());
            return redirect()->back()->with('error', 'Failed to delete activity. Please try again later.');
        }
    }

}
