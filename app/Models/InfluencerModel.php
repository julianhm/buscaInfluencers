<?php

namespace App\Models;

use CodeIgniter\Model;

class InfluencerModel extends Model
{
    
    protected $table      = 'influencers';
    protected $primaryKey = 'idinfluencer';

    //protected $useAutoIncrement = true;

    //protected $returnType     = 'array';
    //protected $useSoftDeletes = true;

    protected $allowedFields = ['nombreinflu', 'alias','password', 'correo', 'foto_perfil','resenia','usuario','video','reputacion','oferta','idciudad','created_at','tokens','validado'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [
        'nombre'=>'required|min_length[4]|max_length[20]',
    'alias'=>'required|min_length[2]|max_length[20]',
    'password'=>'required|min_length[8]',
    'correo'=>'required|valid_email',
    'pais'=>'required',
    'ciudades'=>'required',
    'resenia'=>'required|min_length[10]|max_length[5000]',
];
    protected $validationMessages = [
          // Errors
          'nombre' => [
            'required' => 'El nombre es requerido',
            'min_length' => 'El nombre debe tener como mínimo 4 caracteres',
            'max_length' => 'El nombre NO puede tener mas de 20 caracteres',
        ],
        'alias' => [
            'required' => 'El alias es requerido',
            'min_length' => 'El alias debe tener como mínimo 4 caracteres',
            'max_length' => 'El alias NO puede tener mas de 20 caracteres',
        ],'password' => [
            'required' => 'El password es requerido',
            'min_length' => 'El password debe tener como mínimo 8 caracteres',
        ], 
        'correo' => [
            'required' => 'El email es requerido',
            'valid_email' => 'El email no tiene el formato de un correo',
        ],
        'pais' => [
            'required' => 'El pais es requerido',
            
        ],
        'ciudades' => [
            'required' => 'La ciudad es requerida',
            
        ],
        'resenia' => [
            'required' => 'La reseña es requerida',
            'min_length' => 'La reseña debe tener como mínimo 10 caracteres',
        ],
    ];
    //protected $skipValidation     = false;

    
    public function get($id = null)
    {
        if ($id === null) {
            return $this->findAll();
        }

        return $this->where(['id' => $id])->first();
    }
}