<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFoto2Foto3ToKuliner extends Migration
{
    public function up()
    {
        $this->forge->addColumn('kuliner', [
            'foto2' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'gambar'
            ],
            'foto3' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'foto2'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('kuliner', 'foto2');
        $this->forge->dropColumn('kuliner', 'foto3');
    }
}
