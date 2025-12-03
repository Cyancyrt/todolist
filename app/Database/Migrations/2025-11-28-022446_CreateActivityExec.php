<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateActivityExec extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'activity_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
            ],
            'started_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'finished_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['running','done', 'missed'],
                'default' => 'done'
            ],
            'log' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('activity_id', 'activities', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('activity_exec');
    }

    public function down()
    {
        $this->forge->dropTable('activity_exec');
    }
}
