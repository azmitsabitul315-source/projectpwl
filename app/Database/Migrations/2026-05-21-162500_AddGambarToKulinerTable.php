<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGambarToKulinerTable extends Migration
{
    public function up()
    {
        $fields = [
            'gambar' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'deskripsi',
            ],
        ];

        $this->forge->addColumn('kuliner', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('kuliner', 'gambar');
    }
}
