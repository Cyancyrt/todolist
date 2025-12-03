<?php

namespace App\Models;

use CodeIgniter\Model;

class TasksModel extends Model
{
    protected $table            = 'tasks';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'activity_id',
        'title',
        'user_id',
        'description',
        'due_time',
        'priority',
        'status',
        'created_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
    public function getTasksByActivity($activityId)
    {
        return $this->where('activity_id', $activityId)->findAll();
    }
    public function getActivitybyTask($taskId)
    {
        $task = $this->find($taskId);
        if ($task) {
            $activityModel = new \App\Models\ActivitiesModel();
            return $activityModel->find($task['activity_id']);
        }
        return null;
    }
    public function getTasksByMonth($year, $month, $userId)
    {
        $start = date("$year-$month-01 00:00:00");
        $end = date("$year-$month-t 23:59:59");

        return $this->where('user_id', $userId)
                    ->where('due_time >=', $start)
                    ->where('due_time <=', $end)
                    ->orderBy('due_time', 'ASC')
                    ->findAll();
    }
}
