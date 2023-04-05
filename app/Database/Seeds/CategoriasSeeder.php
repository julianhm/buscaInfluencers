<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategoriasSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('categorias')->where('idcategoria >',0)->delete();
        
        $categorias=['Gastronomia','Farandula','Estilo de vida',
        'Humanidades','De todo', 'Tecnología', 'Entretenimiento',
        'Belleza', 'Transporte','Esoterismo','Ciencia', 'Cine',
        'Talentos especiales','Sexo', 'Astrología', 'Identidad',
        'Arte y Diseño', 'Política y Sociedad','Nutrición','Musica',
        'Superación', 'Religión','Moda', 'Juegos', 'Salud',
        'Turismo y viajes', 'Finanzas', 'Educación','Deportes', 
        'Amor', 'Aventura','Entrenamiento','Opiníon'];

        $imagen=['cat1.png','cat2.png','cat3.png','cat4.png','cat5.png',
        'cat1.png','cat2.png','cat3.png','cat4.png','cat5.png','cat1.png','cat2.png','cat3.png','cat4.png','cat5.png',
        'cat1.png','cat2.png','cat3.png','cat4.png','cat5.png','cat1.png','cat2.png','cat3.png','cat4.png','cat5.png','cat1.png','cat2.png','cat3.png'];

        for ($i=0; $i <count($categorias) ; $i++) { 
            $data = [
                'nombrecat' => $categorias[$i], 'mostradas'=>0,'imagen'=>$imagen[$i]
            ];
            $this->db->table('categorias')->insert($data);
        }
        
    }
}