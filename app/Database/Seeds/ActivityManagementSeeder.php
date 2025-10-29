<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ActivityManagementSeeder extends Seeder
{
   public function run()
    {
        // === USERS ===
        $this->db->table('users')->insertBatch([
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'system',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'User Demo',
                'email' => 'user@example.com',
                'password' => password_hash('user123', PASSWORD_DEFAULT),
                'role' => 'personal',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ]);

        // === ACTIVITIES ===
        $this->db->table('activities')->insert([
            'name' => 'Gotong Royong Mingguan',
            'type' => 'social',
            'description' => 'Bersih-bersih lingkungan RT 02',
            'recurrence' => 'weekly',
            'created_by' => 1,
            'status' => 'upcoming',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // === TASKS ===
        $this->db->table('tasks')->insert([
            'activity_id' => 1,
            'title' => 'Membawa peralatan kebersihan',
            'description' => 'Sapu, cangkul, dan plastik sampah',
            'due_time' => '2025-10-27 06:30:00',
            'priority' => 'medium',
            'status' => 'upcoming',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // === NOTES ===
        $this->db->table('notes')->insert([
            'user_id' => 2,
            'activity_id' => 1,
            'title' => 'Persiapan Gotong Royong',
            'content' => 'Pastikan semua peralatan lengkap.',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // === ACTIVITY PARTICIPANTS ===
        $this->db->table('activity_participants')->insert([
            'activity_id' => 1,
            'user_id' => 2,
            'role' => 'member',
            'joined_at' => date('Y-m-d H:i:s'),
        ]);

        // === ACTIVITY LOGS ===
        $this->db->table('activity_logs')->insert([
            'user_id' => 2,
            'activity_id' => 1,
            'action' => 'activity_joined',
            'timestamp' => date('Y-m-d H:i:s'),
        ]);

        echo "✅ Seeder berhasil dijalankan: Data awal dimasukkan.\n";
    }

}
