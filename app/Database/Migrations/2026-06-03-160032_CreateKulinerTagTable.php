<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKulinerTagTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'kuliner_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'tag_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ]);
        $this->forge->addKey(['kuliner_id', 'tag_id'], true);
        $this->forge->createTable('kuliner_tag');
    }

    public function down()
    {
        $this->forge->dropTable('kuliner_tag');
    }
}
