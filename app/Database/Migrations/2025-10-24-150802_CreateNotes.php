<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'auto_increment' => true],
            'user_id'     => ['type' => 'INT'],
            'activity_id' => ['type' => 'INT', 'null' => true],
            'title'       => ['type' => 'VARCHAR', 'constraint' => 150],
            'content'     => ['type' => 'TEXT', 'null' => true],
            'created_at'  => ['type' => 'DATETIME'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('activity_id', 'activities', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('notes');
    }

    public function down()
    {
        $this->forge->dropTable('notes');
    }
}
