<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ActivityController extends BaseController
{
    protected $activityModel, $scheduleModel, $user, $db;

    public function __construct(){
        helper(['form']);
        $this->user = session()->get('user');
        $this->activityModel = new \App\Models\ActivitiesModel();
        $this->scheduleModel = new \App\Models\ActivityScheduleModel();
        $this->db = \Config\Database::connect();
        if (!$this->db) {
            log_message('error', 'Gagal terhubung ke database');
            throw new \RuntimeException('Gagal terhubung ke database');
        }
    }
    public function index()
    {
        return view('dashboard/activity/index');
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
            'schedule_date' => 'required', // because bisa multiple
            'recurrence' => 'required|in_list[none,daily,weekly,monthly]',
            'created_by' => 'required|integer'
        ];
        if (!$this->validate($rules)) {
            dd($this->validator->getErrors());
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $data = [
            'name' => $this->request->getPost('name'),
            'type' => $this->request->getPost('type'),
            'description' => $this->request->getPost('description'),
            'schedule_date' => json_encode($this->request->getPost('schedule_date')), // simpan array sebagai JSON
            'recurrence' => $this->request->getPost('recurrence'),
            'created_by' => $this->request->getPost('created_by'),
            'created_at' => date('Y-m-d H:i:s')
        ];
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
            $dates = $this->request->getPost('schedule_date'); // array dari form
            if (!is_array($dates)) {
                $dates = [$dates];
            }
            foreach ($dates as $date) {
                try {
                    $this->scheduleModel->insert([
                        'activity_id' => $activityId,
                        'schedule_date' => date('Y-m-d H:i:s', strtotime($date)),
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
}
