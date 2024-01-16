<?php

namespace App\Models;

use CodeIgniter\Model;

class MensajeAdministradoresModel extends Model
{
    
    protected $table      = 'mensajes_administradores';
    protected $primaryKey = 'idmensaje';

    //protected $useAutoIncrement = true;

    //protected $returnType     = 'array';
    //protected $useSoftDeletes = true;

    protected $allowedFields = ['nombre','correo','cuerpo','leido'];

    //protected $useTimestamps = false;
    //protected $createdField  = 'created_at';
    //protected $updatedField  = 'updated_at';
    //protected $deletedField  = 'deleted_at';

    //protected $validationRules    = [];
    //protected $validationMessages = [];
    //protected $skipValidation     = false;

    
    }