<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class SummaryController extends BaseController
{
    protected $activityExecModel;
    protected $taskExecModel;
    protected $activityModel;
    protected $taskModel;
    protected $scheduleModel;

    public function __construct()
    {
        helper(['form', 'security']);

        $this->activityExecModel = new \App\Models\ActivityExecModel();
        $this->taskExecModel     = new \App\Models\TaskExecModel();
        $this->activityModel     = new \App\Models\ActivitiesModel();
        $this->taskModel         = new \App\Models\TasksModel();
        $this->scheduleModel     = new \App\Models\ActivityScheduleModel();
    }

    public function index()
    {
        $period = $this->request->getGet('period') ?? 'month';
        $type   = $this->request->getGet('type') ?? 'all';
        $userId = session()->get('user_id');

        // 1. Dapatkan Rentang Waktu
        [$start, $end] = $this->getDateRange($period);

        // =========================================================================
        // 1. HITUNG PENDING (SUMBER: MASTER & SCHEDULE)
        // Fokus: Masa Depan (Upcoming)
        // =========================================================================

        // A. Pending Activities
        $builderPendingAct = $this->scheduleModel
            ->join('activities', 'activities.id = activity_schedule.activity_id')
            ->where('activities.created_by', $userId)
            ->where('activities.status NOT IN (\'done\', \'missed\')')
            ->where('activity_schedule.next_run_at >=', $start)
            ->where('activity_schedule.next_run_at <=', $end);

        if ($type !== 'all') {
            $builderPendingAct->where('activities.type', $type);
        }
        $countPendingAct = $builderPendingAct->countAllResults();

        // B. Pending Tasks
        $builderPendingTask = $this->taskModel
            ->join('activities', 'activities.id = tasks.activity_id')
            ->where('tasks.user_id', $userId)
            ->where('tasks.status', 'upcoming') // Hanya Upcoming
            ->where('tasks.due_time >=', $start)
            ->where('tasks.due_time <=', $end);

        if ($type !== 'all') {
            $builderPendingTask->where('activities.type', $type);
        }
        $countPendingTask = $builderPendingTask->countAllResults();

        $totalPending = $countPendingAct + $countPendingTask;


        // =========================================================================
        // 2. HITUNG DONE & MISSED (SUMBER: EXEC / RIWAYAT)
        // Fokus: Masa Lalu (History)
        // =========================================================================

        // A. Activity Execution History
        $builderExecAct = $this->activityExecModel
            ->select('activity_exec.status, COUNT(*) as total') // Fix: Prefix Table
            ->join('activities', 'activities.id = activity_exec.activity_id')
            ->where('activities.created_by', $userId)
            ->where('activity_exec.created_at >=', $start)
            ->where('activity_exec.created_at <=', $end)
            ->whereIn('activity_exec.status', ['done', 'missed']); 

        if ($type !== 'all') {
            $builderExecAct->where('activities.type', $type);
        }
        $execActStats = $builderExecAct->groupBy('activity_exec.status')->findAll(); // Fix: Prefix Table

        // B. Task Execution History
        $builderExecTask = $this->taskExecModel
            ->select('task_exec.status, COUNT(*) as total') // Fix: Prefix Table
            ->join('tasks', 'tasks.id = task_exec.task_id')
            ->join('activities', 'activities.id = tasks.activity_id')
            ->where('activities.created_by', $userId)
            ->where('task_exec.created_at >=', $start)
            ->where('task_exec.created_at <=', $end)
            ->whereIn('task_exec.status', ['done', 'missed']);

        if ($type !== 'all') {
            $builderExecTask->where('activities.type', $type);
        }
        $execTaskStats = $builderExecTask->groupBy('task_exec.status')->findAll(); // Fix: Prefix Table

        // =========================================================================
        // 3. AGGREGASI DATA
        // =========================================================================

        $totalDone   = 0;
        $totalMissed = 0;

        $sumStats = function($stats) use (&$totalDone, &$totalMissed) {
            foreach ($stats as $row) {
                if ($row['status'] === 'done') {
                    $totalDone += $row['total'];
                } elseif ($row['status'] === 'missed') {
                    $totalMissed += $row['total'];
                }
            }
        };

        $sumStats($execActStats);
        $sumStats($execTaskStats);
        $regiteredTask = $this->taskModel->join('activities', 'activities.id = tasks.activity_id')->where('tasks.user_id', $userId);
        $registeredAct = $this->activityModel->where('created_by', $userId);

        if ($type !== 'all') {
            $registeredAct->where('type', $type);
        }
        $totalWorkload = $regiteredTask->countAllResults() + $registeredAct->countAllResults();



        // =========================================================================
        // 4. INSIGHTS
        // =========================================================================

        $totalFinishedHistory = $totalDone + $totalMissed;
        $insightCompleted = ($totalFinishedHistory > 0) 
            ? round(($totalDone / $totalFinishedHistory) * 100) 
            : 0;

        if ($totalDone > $totalMissed) {
            $insightOverall = 'Good';
        } elseif ($totalDone == 0 && $totalMissed > 0) {
            $insightOverall = 'Poor';
        } elseif ($totalPending > 0 && $totalFinishedHistory == 0) {
            $insightOverall = 'Keep Going'; 
        } else {
            $insightOverall = 'Needs Improvement';
        }

        // =========================================================================
        // 5. RETURN VIEW
        // =========================================================================
        return view('dashboard/summary/index', [
            'period'           => $period,
            'type'             => $type,
            'totalTasks'       => $totalWorkload,
            'pending'          => $totalPending,
            'completed'        => $totalDone,
            'missed'           => $totalMissed,
            'insightCompleted' => $insightCompleted,
            'insightMissed'    => $totalMissed,
            'insightPending'   => $totalPending,
            'insightOverall'   => $insightOverall,
        ]);
    }

    private function getDateRange($period)
    {
        switch ($period) {
            case 'day':
                return [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')];
            case 'week':
                return [
                    date('Y-m-d 00:00:00', strtotime('monday this week')),
                    date('Y-m-d 23:59:59', strtotime('sunday this week'))
                ];
            case 'year':
                return [date('Y-01-01 00:00:00'), date('Y-12-31 23:59:59')];
            case 'month':
            default:
                return [date('Y-m-01 00:00:00'), date('Y-m-t 23:59:59')];
        }
    }
}