<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityExecModel extends Model
{
    protected $table            = 'activity_exec';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'activity_id',
        'started_at',
        'finished_at',
        'status',
        'log',
        'created_at',
        'updated_at'
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

    public function getTaskExecs($execId)
    {
        return model('TaskExecModel')
            ->where('activity_exec_id', $execId)
            ->orderBy('id', 'ASC')
            ->findAll();
    }
    public function getWithTasks($execId)
    {
        $activityExec = $this->find($execId);
        if (!$activityExec) return null;

        $activityExec['tasks'] = $this->getTaskExecs($execId);

        return $activityExec;
    }

    /**
     * Membuat log tambahan (auto append)
     */
    public function appendLog($execId, $message)
    {
        $exec = $this->find($execId);
        if (!$exec) return false;

        $log = ($exec['log'] ?? '') . "\n[" . date('Y-m-d H:i:s') . "] " . $message;

        return $this->update($execId, ['log' => $log]);
    }
}
