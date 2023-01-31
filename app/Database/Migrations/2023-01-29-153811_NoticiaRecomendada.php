<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NoticiaRecomendada extends Migration
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
                
            ], 'otroidnoticia' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'null' => true,
                
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('idnoticia', 'noticias', 'idnoticia', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('otroidnoticia', 'noticias', 'idnoticia', 'CASCADE', 'SET NULL');

        $this->forge->createTable('noticia_recomendada');
    }

public function down()
{
    $this->forge->dropTable('noticia_recomendada');
}
}


