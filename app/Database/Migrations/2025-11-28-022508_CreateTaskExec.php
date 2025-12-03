<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTaskExec extends Migration
{
    public function up()
    {
         $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'task_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
            ],
            'activity_exec_id' => [
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
                'constraint' => ['upcoming','done', 'missed'],
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

        $this->forge->addForeignKey('task_id', 'tasks', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('activity_exec_id', 'activity_exec', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('task_exec');
    }

    public function down()
    {
        $this->forge->dropTable('task_exec');
    }
}
