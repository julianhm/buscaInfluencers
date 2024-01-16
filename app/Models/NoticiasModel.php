<?php

namespace App\Models;

use CodeIgniter\Model;

class NoticiasModel extends Model
{
    
    protected $table      = 'noticias';
    protected $primaryKey = 'idnoticia';

    //protected $useAutoIncrement = true;

    //protected $returnType     = 'array';
    //protected $useSoftDeletes = true;

    protected $allowedFields = ['titulo','descripcion','cuerpo','url_foto','favorito','created_at'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    //protected $validationRules    = [];
    //protected $validationMessages = [];
    //protected $skipValidation     = false;

    
    }