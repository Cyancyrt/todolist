<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivitiesModel extends Model
{
    protected $table            = 'activities';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'type',
        'description',
        'recurrence',
        'created_by',
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
    public function getWithCreator()
    {
        return $this->select('activities.*, users.name as creator_name')
                    ->join('users', 'users.id = activities.created_by');
    }
    public function getAllWithRelations()
    {
    return $this->select('
            activities.*, 
            users.name AS creator_name, 
            next_schedule.next_run_at AS next_run_at
        ')
        ->join('users', 'users.id = activities.created_by', 'left')
        // Join to a subquery that finds the MINIMUM next_run_at (closest to now) for each activity_id
        ->join('(
            SELECT 
                activity_id, 
                MIN(next_run_at) AS next_run_at
            FROM 
                activity_schedule
            WHERE 
                next_run_at >= NOW() -- Only consider schedules in the future or present
            GROUP BY 
                activity_id
        ) AS next_schedule', 'next_schedule.activity_id = activities.id')
        ->orderBy('activities.id', 'ASC')
        ->findAll();
    }

}
