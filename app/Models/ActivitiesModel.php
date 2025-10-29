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
                activity_schedule.schedule_date,
            ')
            ->join('users', 'users.id = activities.created_by', 'left')
            ->join('activity_schedule', 'activity_schedule.activity_id = activities.id', 'left')
            ->orderBy('activities.id', 'ASC')
            ->findAll();
    }

}
