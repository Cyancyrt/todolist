<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateActivities extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'auto_increment' => true],
            'name'           => ['type' => 'VARCHAR', 'constraint' => 150],
            'type'           => ['type' => 'ENUM', 'constraint' => ['personal', 'social'], 'default' => 'personal'],
            'description'    => ['type' => 'TEXT', 'null' => true],
            'recurrence'     => ['type' => 'ENUM', 'constraint' => ['none', 'daily', 'weekly', 'monthly'], 'default' => 'none'],
            'created_by'     => ['type' => 'INT'],
            'status'         => ['type' => 'ENUM', 'constraint' => ['upcoming', 'done', 'missed'], 'default' => 'upcoming'],
            'notify_enabled' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'     => ['type' => 'DATETIME'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addKey('type');
        $this->forge->createTable('activities');

        $this->forge->addField([
            'id'             => ['type' => 'INT', 'auto_increment' => true],
            'activity_id'    => ['type' => 'INT'],
            'next_run_at'    => ['type' => 'DATETIME', 'null' => true], 
            'last_run_at'    => ['type' => 'DATETIME', 'null' => true], // opsional tapi bagus untuk log
            'run_count'      => ['type' => 'INT', 'default' => 0], 
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('activity_id', 'activities', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('activity_schedule');


    }

    public function down()
    {
        $this->forge->dropTable('activity_schedule', true);
        $this->forge->dropTable('activities', true);
    }
}
