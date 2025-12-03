<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTask extends Migration
{
     public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'auto_increment' => true],
            'activity_id' => ['type' => 'INT'],
            'user_id'     => ['type' => 'INT'],
            'title'       => ['type' => 'VARCHAR', 'constraint' => 150],
            'description' => ['type' => 'TEXT', 'null' => true],
            'due_time'    => ['type' => 'DATETIME', 'null' => true],
            'priority'    => ['type' => 'ENUM', 'constraint' => ['low', 'medium', 'high'], 'default' => 'medium'],
            'status'      => ['type' => 'ENUM', 'constraint' => ['upcoming', 'done', 'missed'], 'default' => 'upcoming'],
            'created_at'  => ['type' => 'DATETIME'],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('activity_id');
        $this->forge->addForeignKey('activity_id', 'activities', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addKey('status');
        $this->forge->addKey('due_time');
        $this->forge->createTable('tasks');
    }

    public function down()
    {
        $this->forge->dropTable('tasks');
    }
}
