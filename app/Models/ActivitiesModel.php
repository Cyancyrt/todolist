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
    public function getAllWithRelations($userId)
    {
        return $this->select('
                activities.*, 
                users.name AS creator_name, 
                next_schedule.next_run_at AS next_run_at
            ')
            ->join('users', 'users.id = activities.created_by', 'left')

            // Subquery schedule terdekat
            ->join('(
                SELECT 
                    activity_id, 
                    MIN(next_run_at) AS next_run_at
                FROM 
                    activity_schedule
                WHERE 
                    next_run_at >= NOW()
                GROUP BY 
                    activity_id
            ) AS next_schedule', 'next_schedule.activity_id = activities.id', 'left')

            // Filter berdasarkan user yang login
            ->where('activities.created_by', $userId)

            ->orderBy('activities.id', 'ASC')
            ->findAll();
    }

    public function getActivitiesByMonth($year, $month, $userId)
    {
        // Hitung tanggal pertama & terakhir bulan ini dengan aman
        $start = date("$year-$month-01 00:00:00");
        $daysInMonth = date('t', strtotime($start));
        $end = date("$year-$month-$daysInMonth 23:59:59");

        return $this->select('
                activities.*, 
                users.name AS creator_name, 
                next_schedule.next_run_at AS next_run_at
            ')
            ->join('users', 'users.id = activities.created_by', 'left')

            // Subquery schedule terdekat di bulan ini
            ->join('(
                SELECT 
                    activity_id, 
                    MIN(next_run_at) AS next_run_at
                FROM 
                    activity_schedule
                WHERE 
                    next_run_at BETWEEN "'.$start.'" AND "'.$end.'"
                GROUP BY 
                    activity_id
            ) AS next_schedule', 'next_schedule.activity_id = activities.id', 'left')

            // Filter berdasarkan user yang login
            ->where('activities.created_by', $userId)

            // Optional: hanya ambil activity yang punya schedule di bulan ini
            ->where('next_schedule.next_run_at IS NOT NULL')

            ->orderBy('activities.id', 'ASC')
            ->findAll();
    }
    public function getAllSchedulesByMonth($year, $month, $userId)
    {
        $start = date("$year-$month-01 00:00:00");
        $daysInMonth = date('t', strtotime($start));
        $end = date("$year-$month-$daysInMonth 23:59:59");

        return $this->db->table('activity_schedule AS s')
            ->select('
                s.id AS schedule_id,
                s.activity_id,
                s.next_run_at,
                a.name AS activity_name,
                a.type AS activity_type,
                a.status AS activity_status,
                a.created_by
            ')
            ->join('activities AS a', 'a.id = s.activity_id')
            ->where('a.created_by', $userId)
            ->where('s.next_run_at >=', $start)
            ->where('s.next_run_at <=', $end)
            ->orderBy('s.next_run_at', 'ASC')
            ->get()
            ->getResultArray();
    }

}
