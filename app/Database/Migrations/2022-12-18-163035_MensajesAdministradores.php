<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MensajesAdministradores extends Migration
{
    public function up(){

        $this->forge->addField([
            'idmensaje' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ],'correo' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'null' => false,
            ],'leido' => [
                'type'       => 'INT',
                'constraint' => 5,
                'null' => false,
            ],'cuerpo' => [
                'type'       => 'VARCHAR',
                'constraint' => '5000',
                'null' => false,
            ],
        ]);
        $this->forge->addKey('idmensaje', true);
        $this->forge->addForeignKey('idadministrador', 'administradores', 'idadministrador', 'CASCADE', 'SET NULL');
        $this->forge->createTable('mensajes_administradores');
    }

public function down()
{
    $this->forge->dropTable('mensajes_administradores');
}
}
