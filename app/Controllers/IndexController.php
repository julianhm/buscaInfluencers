<?php

namespace App\Controllers;

use App\Models\NoticiasModel;
use App\Models\CategoriasModel;
use App\Models\InfluencerModel;
use App\Models\ComentariosModel;
use App\Models\FuenteNoticiasModel;
use App\Models\InfluencerCategoriaModel;
use App\Models\NoticiasRecomendadaModel;
//use App\Models\curl.class;

class IndexController extends BaseController
{
    public function index()
    {
        //$this->logout();
        $influencer = new InfluencerModel();
        $misinfluencer= $influencer-> findAll();

        $categorias = new InfluencerCategoriaModel();
        $miscategorias = $categorias->findAll(); 

        $cat = new CategoriasModel();
        $miscat = $cat->where('mostradas',1)->findAll();

        

        $db      = \Config\Database::connect();

        $arregloMostrarMayorPorCategoria=[];
        $item=0;
        
        foreach ($miscat as $key => $cat) { 
           
            $builder = $db->table('influencers_redes');
            $builder->select('*');
            //$builder->selectMax('influencers_redes.cant_seguidores');
            $builder->where('categorias.mostradas', 1);
            $builder->groupStart()->where('influencers_categorias.idcategoria',$cat['idcategoria'] )->groupEnd();
            
            $builder->join('influencers', 'influencers_redes.idinfluencer = influencers.idinfluencer');
            $builder->join('redes_sociales', 'redes_sociales.idredes = influencers_redes.idredes');
            $builder->join('influencers_categorias', 'influencers_categorias.idinfluencer = influencers.idinfluencer');
            $builder->join('categorias', 'categorias.idcategoria = influencers_categorias.idcategoria');
            //$builder->orderBy('influencers_redes.cant_seguidores', 'DESC');
            $builder->distinct('idcategoria');
            //$builder->orderBy('categorias.nombrecat', 'DESC');
           
            $query = $builder->get();
            $informacion =$query->getResultArray();
            $mayor=0;
             
            if(count($informacion)>0){
                $item++;
                foreach ($informacion as $key => $m) {
                    if($m['cant_seguidores']>$mayor){
                        $mayor=$m['cant_seguidores'];
                        $arregloMostrarMayorPorCategoria[$item]=$m;
                    }
                }
            }
            

            
       }
        //var_dump($arregloMostrarMayorPorCategoria);

        $builder="";
        $builder = $db->table('influencers');
        //$builder->select('influencers_categorias.idinfluencer')->distinct();
        $builder->select(['influencers_categorias.idinfluencer','influencers.nombreinflu','influencers.alias','categorias.nombrecat','influencers.foto_perfil']);
        $builder->join('influencers_categorias', 'influencers_categorias.idinfluencer = influencers.idinfluencer');
        $builder->join('categorias', 'categorias.idcategoria = influencers_categorias.idcategoria');
        //$builder->orderBy('created_at', 'DESC');
        $q = $builder->get();
        $informacioncate =$q->getResultArray();


        $arregloPerfiles=[];


        for ($i=0; $i < count($informacioncate) ; $i++) {
            if(count($arregloPerfiles)>0) {
                $cont=0;
                for ($k=0; $k < count($arregloPerfiles); $k++) { 
                    if($arregloPerfiles[$k]['idinfluencer']==$informacioncate[$i]['idinfluencer']){
                        $cont++;
                        
                    }

                }      
                if($cont==0){
                    array_push($arregloPerfiles,$informacioncate[$i]);  
                }
            }else{
                array_push($arregloPerfiles,$informacioncate[$i]);
            }
            
        }


        $arregloPerfilesReverse = array_reverse($arregloPerfiles);
        $arregloPerfilesReverse12 = array_slice($arregloPerfilesReverse,0,8);


        //var_dump($informacioncate);
        $validacion = \Config\Services::validation();
        session();

        $noticias=new NoticiasModel();

        $misNoticiasAsc = $noticias->where('favorito',1)->findAll();

        /*
        echo '<pre>';
        var_dump($misNoticias);
        echo '</pre>';
        */

        shuffle($miscat);

        $misNoticias = array_reverse($misNoticiasAsc);

        $data= ['validation'=>$validacion,'datos'=>$arregloPerfilesReverse12,
                'noticias'=>$misNoticias,'informacion'=>$arregloMostrarMayorPorCategoria,
                'categorias'=>$miscat];

        $dataHeader =['titulo' => 'Busca Influencer',
                'mensaje'=>"",];

       // var_dump(password_hash("julian123",PASSWORD_DEFAULT));
        $this-> _loadDefaultView($dataHeader,$data,'index');

    }

    private function _loadDefaultView($dataHeader,$data,$view){

        echo view("influencer/templates/headerindex",$dataHeader);
        echo view("influencer/influencers/$view",$data);
        echo view("influencer/templates/footerindex");
       

    }


    // FOOTER STATEMENT LINK
    public function statement(){
        session();
        $dataHeader =['titulo' => 'Nuestro “statement”',
                'mensaje'=>"",];
        echo view("influencer/templates/header",$dataHeader);
        echo view("influencer/usuarios/nosotros-2");
        echo view("influencer/templates/footerindex");
    }

    // FOOTER STATEMENT LINK
    public function nosotros(){
        session();
        $dataHeader =['titulo' => 'Nuestro “statement”',
                'mensaje'=>"",];
        echo view("influencer/templates/header",$dataHeader);
        echo view("influencer/usuarios/nosotros-1");
        echo view("influencer/templates/footerindex");
    }

    // FOOTER AVISO DE PRIVACIDAD LINK
    public function privacidad(){
        session();
        $dataHeader =['titulo' => 'Aviso privacidad',
                'mensaje'=>"",];
        echo view("influencer/templates/header",$dataHeader);
        echo view("influencer/usuarios/aviso-de-privacidad");
        echo view("influencer/templates/footerindex");
    }

    // FOOTER POLITICA DE TRATAMIENTO DE DATOS LINK
    public function politica(){
        session();
        $dataHeader =['titulo' => 'Política de tratamiento de datos',
                'mensaje'=>"",];
        echo view("influencer/templates/header",$dataHeader);
        echo view("influencer/usuarios/politica-de-tratamiento-de-datos");
        echo view("influencer/templates/footerindex");
    }

    // FOOTER POLITICA DE TRATAMIENTO DE DATOS LINK
    public function terminos(){
        session();
        $dataHeader =['titulo' => 'Términos y condiciones',
                'mensaje'=>"",];
        echo view("influencer/templates/header",$dataHeader);
        echo view("influencer/usuarios/terminos-y-condiciones");
        echo view("influencer/templates/footerindex");
    }

    // FOOTER CONTACTO
    public function contacto(){
        session();
        $dataHeader =['titulo' => 'Contacto',
                'mensaje'=>"",];
        echo view("influencer/templates/header",$dataHeader);
        echo view("influencer/usuarios/contacto");
        echo view("influencer/templates/footerindex");
    }


    public function buscarNoticia($id=null)
    {
        session();
        //var_dump($id);
        $noticias=new NoticiasModel();
        $recomendada=new NoticiasRecomendadaModel();
        $fuente = new FuenteNoticiasModel();

        $misNoticias=$noticias->find($id);
        $misFuentes=$fuente->where('idnoticia',$id)->findAll();

        $misRecomendadas = $recomendada->where('idnoticia',$id)->findAll();
        $misNoticiasRecomendadas=[];
        foreach ($misRecomendadas as $key => $m) {
           array_push($misNoticiasRecomendadas,$noticias->find($m['otroidnoticia'])); 
        }
        
        //var_dump($misNoticias);)
        $data= ['noticias'=>$misNoticias,
            'recomendada'=>$misNoticiasRecomendadas,
        'fuente'=> $misFuentes];
        
        $dataHeader =['titulo' => 'Noticias',
                      'titulo_meta' => $misNoticias['titulo'],
                      'descripcion' => $misNoticias['descripcion'],
                      'url_foto' => base_url('fotosnoticias')."/".$misNoticias['url_foto'],
                'mensaje'=>"",];

        //var_dump($informacioncate);
        echo view("influencer/templates/header",$dataHeader);
        echo view("influencer/usuarios/noticia",$data);
        echo view("influencer/templates/footer");

        //var_dump($misNoticias['url_foto']);

    }


     //Realiza el login de la pagina
     public function login()
     {
       
        $correo= $this->request->getPost('emaillogin');
         $password= $this->request->getPost('passwordlogin');
 
         $miInfluencer= new InfluencerModel();

 
         $inf=$miInfluencer->select('idinfluencer,correo,alias,password,validado')->where('correo',$correo)->first();
         if($inf==null){
            return redirect()->back()->with('mensaje','Correo y/o contraseña incorrecta');  
        } else{

            if($inf['validado']==0){
                return redirect()->back()->with('mensaje','Debes validar tu correo electronico');  
            }else{

            
                $pass=$inf['password'];
                if(password_verify($password,$inf['password'])){
                    $id=$inf['idinfluencer'];
                    $alias=$inf['alias'];
                    session()->set('idinfluencer',$id);
                    session()->set('alias',$alias);
                    session()->set('time',time());
                    return redirect()->to(base_url()."/influencer/edit/$alias")->with('mensaje', 'Tu inicio de sesión fue correcto');
                }
            }
        }
        
         return redirect()->back()->with('mensaje','Correo y/o contraseña incorrecta'); 
 
     }

     //Solicita representante en la pagina
     public function solicitarRepresentante()
     {
         
         $correo= $this->request->getPost('representanteInputEmail');
         $nombre= $this->request->getPost('represenatnteInputNombre');
 
 //var_dump(date("Y-m-d h:i:s")."");
 
        if($correo!="" && $nombre!=""){

         
            $comentar=new ComentariosModel();
            
            
            $datainsertar = [
                'nombre' => $nombre,
                'correo' => $correo,
                'created_at'=>date("Y-m-d h:i:s").""
            ];

            //SE CREA EL INFLUENCER
            $id=$comentar->insert($datainsertar);

            

           if($id>0){
                return redirect()->to(base_url())->with('mensaje','Tu solicitud fué enviada'); 
            }else{
                return redirect()->to(base_url())->with('mensaje','Ocurrió un error al enviar tu solicitud'); 
            }
        }
        return redirect()->to(base_url())->with('mensaje','Debes completar todos los campos'); 
        
 
     }
 
 
     //Realiza la salida de la cuenta
     public function logout(){
       
         session()->destroy();
         return redirect()->to(base_url());
     }
 
 
    

    
}