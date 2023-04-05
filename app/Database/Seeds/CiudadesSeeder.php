<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CiudadesSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('ciudades')->where('idciudad >',0)->delete();

        

        // Simple Queries
       // $this->db->query('INSERT INTO influencers (nombre, seudonimo) VALUES(:nombre:, :seudonimo:)', $data);

        // Using Query Builder
        $ciudacolom=[
            'Amazonas',
            'Antioqia',
            'Arauca',
            'Archipielago de san andres',
            'Atlántico',
            'Bogotá',
            'Bolivar',
            'Boyacá',
            'Caldas',
            'Caqueta',
            'Casanare',
            'Cauca',
            'Cesar',
            'Choco',
            'Cordoba',
            'Cundinamarca',
            'Guainia',
            'Guaviare',
            'Huila',
            'La guajira',
            'Magdalena',
            'Meta',
            'Nariño',
            'Norte de santander',
            'Putumayo',
            'Quindío',
            'Risaralda',
            'Santander',
            'Sucre',
            'Tolima',
            'Valle del cauca',
            'Vaupes',
            'Vichada',
            

        ];

        for ($i=1; $i <count($ciudacolom) ; $i++) { 
            
            $data = [
                'nombre' => $ciudacolom[$i],
                'idpais' => 1,    
            ];
            $this->db->table('ciudades')->insert($data);
        
    }

    $provivenezuela=[
        'Amazonas',
'Anzoátegui',
'Apure',
'Aragua',
'Barinas',
'Bolívar',
'Carabobo',
'Cojedes',
'Delta Amacuro',
'Distrito Capital',
'Falcón',
'Guárico',
'Lara',
'Mérida',
'Miranda',
'Monagas',
'Nueva Esparta',
'Portuguesa',
'Sucre',
'Táchira',
'Trujillo',
'Vargas',
'Yaracuy',
'Zulia',
'Dependencias Federales',


    ];

    for ($i=1; $i <count($provivenezuela) ; $i++) { 
            
        $data = [
            'nombre' => $provivenezuela[$i],
            'idpais' => 2,    
        ];
        $this->db->table('ciudades')->insert($data);
    
}

    $provinecuador=[
        'Azuay',
'Bolívar',
'Cañar',
'Carchi',
'Chimborazo',
'Cotopaxi',
'El Oro',
'Esmeraldas',
'Galápagos',
'Guayas',
'Imbabura',
'Loja',
'Los Ríos',
'Manabí',
'Morona-Santiago',
'Napo',
'Orellana',
'Pastaza',
'Pichincha',
'Santa Elena',
'Santo Domingo de los Tsáchilas',
'Sucumbíos',
'Tungurahua',
'Zamora-Chinchipe',


    ];

    for ($i=1; $i <count($provinecuador) ; $i++) { 
            
        $data = [
            'nombre' => $provinecuador[$i],
            'idpais' => 3,    
        ];
        $this->db->table('ciudades')->insert($data);
    
}

$ciudadesmexico=[
    'Aguascalientes',
    'Baja California',
    'Baja California Sur',
    'Campeche',
    'Chiapas',
    'Chihuahua',
    'Ciudad de México',
    'Coahuila',
    'Colima',
    'Durango',
    'Guanajuato',
    'Guerrero',
    'Hidalgo',
    'Jalisco',
    'México',
    'Michoacán',
    'Morelos',
    'Nayarit',
    'Nuevo León',
    'Oaxaca',
    'Puebla',
    'Querétaro',
    'Quintana Roo',
    'San Luis Potosí',
    'Sinaloa',
    'Sonora',
    'Tabasco',
    'Tamaulipas',
    'Tlaxcala',
    'Veracruz',
    'Yucatán',
    'Zacatecas',
    

];

for ($i=1; $i <count($ciudadesmexico) ; $i++) { 
            
    $data = [
        'nombre' => $ciudadesmexico[$i],
        'idpais' => 4,    
    ];
    $this->db->table('ciudades')->insert($data);

}

$ciudadpanama=[
    'Bocas del toro',
    'Coclé',
    'Colón',
    'Chiriquí',
    'Darién',
    'Herrera',
    'Los santos',
    'Panamá',
    'Panamá oeste',
    'Veraguas',
    'Comarca ngabe buglé',
    'Comarca emberá-wounaan',
    'Comarca guna yala',
    'Comarca guna de madugandí',
    'Comarca guna de madugandí',
      
    
];

for ($i=1; $i <count($ciudadpanama) ; $i++) { 
            
    $data = [
        'nombre' => $ciudadpanama[$i],
        'idpais' => 5,    
    ];
    $this->db->table('ciudades')->insert($data);

}
        
 }
}