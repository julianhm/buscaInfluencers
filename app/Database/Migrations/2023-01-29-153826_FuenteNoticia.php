<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FuenteNoticia extends Migration
{
    public function up(){

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],'idnoticia' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'null' => true,
            ], 'fuente' => [
                'type'           => 'VARCHAR',
                'constraint' => '10000',
                'null' => true,
                
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('idnoticia', 'noticias', 'idnoticia', 'CASCADE', 'SET NULL');
        
        $this->forge->createTable('fuente_noticia');
    }

public function down()
{
    $this->forge->dropTable('fuente_noticia');
}
}
