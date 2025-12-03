<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateActivityParticipants extends Migration
{
   public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'auto_increment' => true],
            'activity_id' => ['type' => 'INT'],
            'user_id'     => ['type' => 'INT'],
            'role'        => ['type' => 'ENUM', 'constraint' => ['member', 'leader'], 'default' => 'member'],
            'joined_at'   => ['type' => 'DATETIME'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('activity_id'); // Mempercepat pencarian peserta per aktivitas
        $this->forge->addKey('user_id');
        $this->forge->addForeignKey('activity_id', 'activities', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('activity_participants');
    }

    public function down()
    {
        $this->forge->dropTable('activity_participants');
    }
}