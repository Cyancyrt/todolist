<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use DateTime;
use DateTimeZone;

class ActivityController extends BaseController
{
    protected $activityModel, $scheduleModel,$taskModel, $user, $db, $session, $activityParticipantsModel;

    public function __construct(){
        helper(['form', 'session']);
        $this->activityModel = new \App\Models\ActivitiesModel();
        $this->scheduleModel = new \App\Models\ActivityScheduleModel();
        $this->taskModel = new \App\Models\TasksModel();
        $this->activityParticipantsModel = new \App\Models\ActivityParticipantsModel();
        $this->db = \Config\Database::connect();
        if (!$this->db) {
            log_message('error', 'Gagal terhubung ke database');
            throw new \RuntimeException('Gagal terhubung ke database');
        }
    }
    public function index()
    {
        $userId = session('user_id');
        
        // Ambil parameter dari URL (GET)
        $filter = $this->request->getGet('filter') ?? 'all'; // today, week, month, all
        $sort   = $this->request->getGet('sort') ?? 'due_time'; // due_time, priority, status
        $order  = $this->request->getGet('order') ?? 'asc';     // asc, desc

        // Panggil model dengan filter
        // Catatan: Kita perlu memodifikasi model agar mendukung filtering ini
        // Atau lakukan filtering manual di sini (kurang efisien untuk data besar)
        
        // --- LOGIC FILTERING ---
        // Karena getAllWithRelations() Anda mungkin hardcoded, 
        // mari kita bangun query manual atau update modelnya.
        // Di sini saya asumsikan kita pakai query builder langsung agar fleksibel.
        
        $builder = $this->activityModel->select('activities.*, activity_schedule.next_run_at')
            ->join('activity_schedule', 'activity_schedule.activity_id = activities.id', 'left') // Join schedule untuk filter waktu
            ->where('activities.created_by', $userId);

        // A. FILTER WAKTU
        $today = date('Y-m-d');
        switch ($filter) {
            case 'today':
                $builder->where('DATE(activity_schedule.next_run_at)', $today);
                break;
            case 'week':
                $builder->where('YEARWEEK(activity_schedule.next_run_at, 1)', date('oW'));
                break;
            case 'month':
                $builder->where('MONTH(activity_schedule.next_run_at)', date('m'))
                        ->where('YEAR(activity_schedule.next_run_at)', date('Y'));
                break;
            case 'all':
            default:
                // Tidak ada filter waktu tambahan
                break;
        }

        // B. SORTING
        switch ($sort) {
            case 'priority':
                // Asumsi ada kolom priority di activity atau schedule? 
                // Jika tidak ada di activity, kita skip atau default ke created_at
                // $builder->orderBy('activities.priority', $order); 
                break;
            case 'status':
                $builder->orderBy('activities.status', $order);
                break;
            case 'due_time':
            default:
                $builder->orderBy('activity_schedule.next_run_at', $order);
                break;
        }

        // Eksekusi Query
        // Kita gunakan groupBy untuk menghindari duplikat jika 1 activity punya banyak schedule (walaupun logic kita 1 activity = 1 schedule next_run)
        $data = $builder->groupBy('activities.id')->findAll();

        // Ambil Tasks (Sub-activities)
        foreach ($data as &$activity) {
            $activity['tasks'] = $this->taskModel->getTasksByActivity($activity['id']);
        }

        return view('dashboard/activity/index', [
            'data'   => $data,
            'filter' => $filter, // Kirim balik ke view untuk set class 'active'
            'sort'   => $sort
        ]);
    }

    public function create()
    {
        return view('dashboard/activity/create');
    }

    public function save(){
        $db =  \Config\Database::connect();
        $rules = [
            'name' => [
                'rules' => 'required|min_length[3]|max_length[150]',
                'errors' => [
                    'required'   => 'Nama wajib diisi.',
                    'min_length' => 'Nama minimal harus 3 karakter.',
                    'max_length' => 'Nama maksimal 150 karakter.'
                ]
            ],

            'type' => [
                'rules' => 'required|in_list[personal,social]',
                'errors' => [
                    'required' => 'Tipe aktivitas wajib dipilih.',
                    'in_list'  => 'Tipe aktivitas tidak valid.'
                ]
            ],

            'description' => [
                'rules' => 'permit_empty|max_length[500]',
                'errors' => [
                    'max_length' => 'Deskripsi maksimal 500 karakter.'
                ]
            ],

            'next_run_at' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tanggal jadwal pertama wajib diisi.'
                ]
            ],

            'recurrence' => [
                'rules' => 'required|in_list[none,daily,weekly,monthly]',
                'errors' => [
                    'required' => 'Pattern pengulangan wajib dipilih.',
                    'in_list'  => 'Pattern pengulangan tidak valid.'
                ]
            ],

            'created_by' => [
                'rules' => 'required|integer',
                'errors' => [
                    'required' => 'ID pembuat aktivitas tidak ditemukan.',
                ]
            ],
        ];
        if (!$this->validate($rules)) {
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
            $activtyPart = $this->activityParticipantsModel->insert([
                                'activity_id' => $activityId,
                                'user_id'     => session()->get('user_id'),
                                'role'        => 'owner',
                                'joined_at'   => date('Y-m-d H:i:s')
                            ]);
            // Insert schedule dates
            $dates = $this->request->getPost('next_run_at'); // array dari form
            if (!is_array($dates)) {
                $dates = [$dates];
            }
            $now = new \DateTime('now', new DateTimeZone('Asia/Jakarta'));
            foreach ($dates as $date) {
                $schedule = new DateTime($date);
            
                if ($schedule < $now) {
                    return redirect()->back()->withInput()->with('error', 'Tanggal tidak boleh waktu lampau.');
                }
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
    public function update($activityId)
    {
        $db = \Config\Database::connect();
        
        $rules = [
            'name' => 'required|min_length[3]|max_length[150]',
            'type' => 'required|in_list[personal,social]',
            'description' => 'permit_empty|max_length[500]',
            'next_run_at' => 'required',
            'recurrence' => 'required|in_list[none,daily,weekly,monthly]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $db->transStart();

            // 1. Proses Jadwal (Schedule)
            $dates = $this->request->getPost('next_run_at');
            if (!is_array($dates)) {
                $dates = [$dates];
            }

            try {
                // Hapus jadwal lama
                $this->scheduleModel->where('activity_id', $activityId)->delete();
                
                $newSchedules = [];
                $now = new \DateTime('now', new DateTimeZone('Asia/Jakarta'));
                
                foreach ($dates as $date) {
                    if (empty($date) || trim($date) === '') continue;
                    $date = str_replace('T', ' ', $date);

                    try {
                        $schedule = new DateTime($date);
                    } catch (\Exception $e) {
                        return redirect()->back()->withInput()->with('error', 'Format tanggal tidak valid.');
                    }

                    // Validasi waktu lampau (opsional, sesuaikan kebutuhan)
                    if ($schedule < $now) {
                        return redirect()->back()->withInput()->with('error', 'Tanggal tidak boleh waktu lampau.');
                    }

                    $newSchedules[] = [
                        'activity_id' => $activityId,
                        'next_run_at' => $schedule->format('Y-m-d H:i:s')
                    ];
                }

                if (!empty($newSchedules)) {
                    $this->scheduleModel->insertBatch($newSchedules);

                    // ============================================================
                    // TAMBAHAN LOGIKA: UPDATE DUE TIME PADA TASKS
                    // ============================================================
                    
                    // 1. Cari tanggal paling awal dari jadwal baru
                    // Kita urutkan array schedule berdasarkan next_run_at
                    usort($newSchedules, function($a, $b) {
                        return strtotime($a['next_run_at']) - strtotime($b['next_run_at']);
                    });
                    
                    // Ambil tanggal pertama (tercepat)
                    $primaryDate = $newSchedules[0]['next_run_at'];

                    // 2. Update Task
                    $this->taskModel
                        ->where('activity_id', $activityId)
                        ->set(['due_time' => $primaryDate,'status'   => 'upcoming'])
                        ->update();
                    
                    // ============================================================
                }

            } catch (\Throwable $th) {
                log_message('error', 'Gagal update jadwal/task. Error: ' . $th->getMessage());
                throw new \Exception('Gagal menyimpan jadwal aktivitas.');
            }

            // 2. Update Data Activity Utama
            $activityData = [
                'name' => $this->request->getPost('name'),
                'type' => $this->request->getPost('type'),
                'description' => $this->request->getPost('description'),
                'recurrence' => $this->request->getPost('recurrence'),
                'status'      => 'upcoming',
                // created_by tidak perlu diupdate
                // status biarkan apa adanya atau reset ke upcoming jika perlu
            ];

            // Cek participants (Safety check)
            $participants = $this->activityParticipantsModel->where('activity_id', $activityId)->countAllResults();
            if ($participants == 0) {
                $this->activityParticipantsModel->insert([
                    'activity_id' => $activityId,
                    'user_id'     => session()->get('user_id'),
                    'role'        => 'owner',
                    'joined_at'   => date('Y-m-d H:i:s')
                ]);
            }

            $this->activityModel->update($activityId, $activityData);

            $db->transComplete();

            if ($db->transStatus() === false) {
                $db->transRollback();
                log_message('error', 'DB Error: ' . json_encode($this->db->error()));
                throw new \Exception('Gagal menyimpan aktivitas.');
            }

            return redirect()->to('/dashboard/activity')->with('success', 'Aktivitas dan Task berhasil diperbarui!');

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
            $this->taskModel->where('activity_id', $activityId)->delete();
            $this->activityParticipantsModel->where('activity_id', $activityId)->delete();
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
   public function bulkDelete()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid request method.'
            ])->setStatusCode(405);
        }

        $data = $this->request->getJSON();

        if (!$data || empty($data->ids)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No items selected.'
            ])->setStatusCode(400);
        }

        $ids = array_filter(array_map(fn($v) => (int) trim($v), (array) $data->ids));

        $userId = session()->get('user_id');
        $isAdmin = session()->get('is_admin') ?? false;

        try {
            $db = \Config\Database::connect();
            $db->transStart();

            // Validate permissions
            $builder = $db->table('activities')->whereIn('id', $ids);

            if (!$isAdmin) {
                $builder->where('created_by', $userId);
            }

            $activities = $builder->get()->getResultArray();

            if (empty($activities)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'No valid activities found.',
                    'debug_ids' => $ids
                ])->setStatusCode(404);
            }

            $activityIds = array_column($activities, 'id');

            // Hapus schedules
            $this->scheduleModel->whereIn('activity_id', $activityIds)->delete();

            // Hapus tasks
            $this->taskModel->whereIn('activity_id', $activityIds)->delete();

            // Hapus participants
            $this->activityParticipantsModel->whereIn('activity_id', $activityIds)->delete();

            // Hapus activity
            $this->activityModel->whereIn('id', $activityIds)->delete();

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception("Transaction failed");
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => count($activityIds) . ' activities deleted successfully.'
            ]);

        } catch (\Throwable $e) {

            if (isset($db) && $db->transStatus() === false) {
                $db->transRollback();
            }

            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to delete items.',
                'debug'  => $e->getMessage()
            ])->setStatusCode(500);
        }
    }


}
