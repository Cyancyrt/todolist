<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserDevices extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'auto_increment' => true],
            'user_id'     => ['type' => 'INT', 'null' => false],
            'device_type' => ['type' => 'ENUM', 'constraint' => ['web', 'android', 'ios'], 'default' => 'web'],
            'token'       => ['type' => 'TEXT', 'null' => false],
            'user_agent'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'last_active' => ['type' => 'DATETIME', 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => false],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_devices');
    }

    public function down()
    {
        $this->forge->dropTable('user_devices', true);
    }
}