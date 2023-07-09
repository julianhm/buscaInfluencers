<?php



namespace App\Models;



use CodeIgniter\Model;



class AdministradorModel extends Model

{

    

    protected $table      = 'administradores';

    protected $primaryKey = 'idadministrador';



    //protected $useAutoIncrement = true;



    //protected $returnType     = 'array';

    //protected $useSoftDeletes = true;



    protected $allowedFields = ['nombre','correo','password','url_foto'];



    //protected $useTimestamps = false;

    //protected $createdField  = 'created_at';

    //protected $updatedField  = 'updated_at';

    //protected $deletedField  = 'deleted_at';



    //protected $validationRules    = [];

    //protected $validationMessages = [];

    //protected $skipValidation     = false;



    

   

}