<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class CalendarController extends BaseController
{
    protected $activitiesModel, $tasksModel;
    public function __construct()
    {
        helper(['form', 'security']);
        $this->activitiesModel = new \App\Models\ActivitiesModel();
        $this->tasksModel = new \App\Models\TasksModel();
    }
    public function index()
    {
        return view('dashboard/calendar/index');
    }
    public function fetchTasks()
    {
        // Validasi input agar aman
        $year = $this->request->getGet('year');
        $month = $this->request->getGet('month');

        if (!is_numeric($year) || !is_numeric($month) || $month < 1 || $month > 12) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                                  ->setJSON(['error' => 'Invalid year or month']);
        }

        // Ambil data tasks berdasarkan bulan & tahun
        $tasks = $this->tasksModel->getTasksByMonth($year, $month);

        // Kelompokkan berdasarkan tanggal
        $grouped = [];
        foreach ($tasks as $task) {
            $dateKey = date('Y-m-d', strtotime($task['due_time']));
            $grouped[$dateKey][] = [
                'id'       => $task['id'],
                'name'     => esc($task['title']),
                'priority' => esc($task['priority']),
                'status'   => esc($task['status']),
                'time'     => date('H:i', strtotime($task['due_time'])),
            ];
        }

        return $this->response->setJSON($grouped);
    }
}
