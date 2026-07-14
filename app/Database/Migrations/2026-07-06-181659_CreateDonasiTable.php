<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDonasiTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'order_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
            ],
            'nama_donatur' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'nominal' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'status_pembayaran' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'pending',
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
        $this->forge->createTable('donasi');
    }

    public function down()
    {
        $this->forge->dropTable('donasi');
    }
}
