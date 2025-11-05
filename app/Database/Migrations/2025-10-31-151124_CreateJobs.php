<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJobs extends Migration
{
    public function up()
    {
        // Buat field
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true
            ],
            'payload' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending','processing','done','failed'],
                'default'    => 'pending',
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => false,
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'on update' => 'CURRENT_TIMESTAMP'
            ]
        ]);

        // Set primary key
        $this->forge->addKey('id', true);

        // Buat tabel
        $this->forge->createTable('jobs', true);
    }

    public function down()
    {
        // Drop tabel jika rollback
        $this->forge->dropTable('jobs', true);
    }
}
