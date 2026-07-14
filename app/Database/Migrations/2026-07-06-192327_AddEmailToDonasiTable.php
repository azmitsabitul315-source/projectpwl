<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEmailToDonasiTable extends Migration
{
    public function up()
    {
        $fields = [
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'after'      => 'nama_donatur'
            ]
        ];
        $this->forge->addColumn('donasi', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('donasi', 'email');
    }
}
