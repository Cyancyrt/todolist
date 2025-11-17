<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class SummaryController extends BaseController
{
    protected $activitiesModel;
    public function __construct()
    {
        helper(['form', 'security']);
        $this->activitiesModel = new \App\Models\ActivitiesModel();
    }
    public function index()
    {
        // Ambil filter dari request (default: bulan ini, semua jenis)
        $period = $this->request->getGet('period') ?? 'month';
        $type   = $this->request->getGet('type') ?? 'all';
        // dd($period, $type);
        // Tentukan rentang waktu berdasarkan filter
        $now = date('Y-m-d');
        switch ($period) {
            case 'day':
                $start = date('Y-m-d 00:00:00');
                $end = date('Y-m-d 23:59:59');
                break;
            case 'week':
                $start = date('Y-m-d 00:00:00', strtotime('monday this week', strtotime('today')));
                $end   = date('Y-m-d 23:59:59', strtotime('sunday this week', strtotime('today')));
                break;
            case 'year':
                $start = date('Y-01-01 00:00:00');
                $end = date('Y-12-31 23:59:59');
                break;
            default:
                $start = date('Y-m-01 00:00:00');
                $end = date('Y-m-t 23:59:59');
                break;
        }

        // Filter query dinamis
        $builder = $this->activitiesModel->where('created_at >=', $start)
                                 ->where('created_at <=', $end);

        if ($type !== 'all') {
            $builder->where('type', $type);
        }

        $activities = $builder->findAll();

        // Hitung statistik berdasarkan status
        $totalTasks = count($activities);
        $completed  = count(array_filter($activities, fn($a) => $a['status'] === 'done'));
        $missed     = count(array_filter($activities, fn($a) => $a['status'] === 'missed'));
        $pending    = count(array_filter($activities, fn($a) => in_array($a['status'], ['upcoming', 'postponed', 'extended'])));

        // Buat insight sederhana
        $insightCompleted = $totalTasks > 0 ? round(($completed / $totalTasks) * 100) : 0;
        $insightOverall = $completed > $missed ? 'Good' : ($completed == 0 ? 'Poor' : 'Needs Improvement');

        // Kirim data ke view
        $data = [
            'period' => $period,
            'type' => $type,
            'totalTasks' => $totalTasks,
            'completed' => $completed,
            'missed' => $missed,
            'pending' => $pending,
            'insightCompleted' => $insightCompleted,
            'insightMissed' => $missed,
            'insightPending' => $pending,
            'insightOverall' => $insightOverall
        ];
        return view('dashboard/summary/index', $data);
    }
}
