<?php

namespace App\Controllers\dashboard;

use App\Models\IdiomaModel;
use App\Models\MensajesModel;
use App\Models\NoticiasModel;
use App\Models\CategoriasModel;
use App\Models\InfluencerModel;
use App\Models\ComentariosModel;
use App\Models\AdministradorModel;
use App\Models\MensajeCorreoModel;
use App\Controllers\BaseController;
use App\Models\NoticiasRecomendadaModel;
use App\Models\MensajeAdministradoresModel;

class DashboardController extends BaseController
{
    public function index()
    {

        $dataheader=[ 
            'usuario'=> 'Julian Andres'
        ];

        $data=[
            'usuario2'=> 'otro julian'
        ];

         
        echo view('dashboard/LoginDashboard', $data); 
        echo view('dashboard/templates/footer'); 
    }

    public function loguinAdmin(){
        $correo= $this->request->getPost('correoAdmin');
        $password= $this->request->getPost('claveAdmin');

        $miAdministrador = new AdministradorModel();

        $miAdmin=$miAdministrador->find(145758);

        

         if($correo==$miAdmin['correo'] && $password==$miAdmin['password']){
            session()->set('idadmin',$miAdmin['idadministrador']);
            session()->set('timeadmin',time());
            return redirect()->to(base_url()."/dashboard")->with('mensaje', 'Tu login fue correcto');
         }else{
            return redirect()->back()->with('mensaje','Correo y/o contraseña incorrecta'); 
         }
    }

    public function indexAdmin()
    {

        $miAdministrador = new AdministradorModel();
        $miAdmin=$miAdministrador->find(145758);
        
        date_default_timezone_set('America/Bogota');
        $fechaActual= date("d-m-Y h:i:s");
        //var_dump($fechaActual);
        

        if(session()->get('idadmin')==$miAdmin['idadministrador']){
            if ((time() - session()->get('timeadmin')) > 3600){
                session_destroy();
                return redirect()->to(base_url()."/admin")->with('mensaje', 'La sesion se cerró por tu seguridad');
            }else{
                
                $influModel= new InfluencerModel();
                $mensajesModel = new MensajesModel();
                $noticiasModel = new NoticiasModel();
                $mensajeCorreo= new MensajeCorreoModel();
                
                $influencerDiarios = $influModel->findAll();
                $fecha_actual = date("Y-m-d H:i:s");
                $influencerRegistradoenUnDia=[];

                for($i=0; $i<count($influencerDiarios);$i++){
                    $fecha = date("Y-m-d H:i:s", strtotime($influencerDiarios[$i]['created_at']));
                    
                //sumo 1 día
                    $fechaReferida= date("Y-m-d H:i:s",strtotime($fecha_actual."- 1 days")); 
                    if($fecha>=$fechaReferida){
                    array_push($influencerRegistradoenUnDia,$influencerDiarios[$i]);
                    }
                }
                
             
                //var_dump($influencerRegistradoenUnDia);

                $cant= count($influModel->findAll());
                $cantUltimoDia=count($influencerRegistradoenUnDia);
                $cantMensajes= count($mensajesModel->findAll());
                $noticias= count($noticiasModel->findAll());
                $comentarios = $mensajeCorreo->orderBy('created_at','DESC')->find();
                $influencers=$influModel->orderBy('created_at','DESC')->find();

                $dataheader=[ 
                    'usuario'=> $miAdmin['nombre'],
                    'url_foto'=>$miAdmin['url_foto'],
                    
                ];
        
                $data=[
                    'usuario2'=> "Administrador",
                    'cantidadinfluencers'=>$cant,
                    'cantidadinfluencersUltimo'=>$cantUltimoDia,
                    'cantidadMensajes'=>$cantMensajes,
                    'cantidadNoticias'=>$noticias,
                    'comentarios'=>$comentarios,
                    'influencers'=>$influencers,
                ];
    
                echo view('dashboard/templates/header',$dataheader); 
                echo view('dashboard/Dashboard', $data); 
                echo view('dashboard/templates/footer'); 
            }

        }else{
           return redirect()->to(base_url()."/admin")->with('mensaje', 'Debes ingresar tus credenciales');
       }

    }

    public function logout(){
       
        session()->destroy();
        return redirect()->to(base_url()."/admin");
    }



    public function influencers()
    {

        $miAdministrador = new AdministradorModel();
        $miAdmin=$miAdministrador->find(145758);

        if(session()->get('idadmin')==$miAdmin['idadministrador']){
            if ((time() - session()->get('timeadmin')) > 3600){
                session_destroy();
                return redirect()->to(base_url()."/admin")->with('mensaje', 'La sesion se cerró por tu seguridad');
            }else{
       
                $influ = new InfluencerModel();
                    

                    $dataheader=[ 
                        'usuario'=> $miAdmin['nombre'],
                    'url_foto'=>$miAdmin['url_foto'],
                    ];
                    
                //var_dump($color[1]);
                //'influ'=> $influ->asObject()->paginate(5),
                //'pager' => $influ->pager,
                    $data=[
                        'influ'=> $influ->paginate(8),
                        'pager'=>$influ->pager,
                    ];

                    echo view('dashboard/templates/header',$dataheader); 
                    echo view('dashboard/influencers', $data); 
                    echo view('dashboard/templates/footer'); 
            }
        }
    }

    public function eliminarInfluencer()
    {

        $miAdministrador = new AdministradorModel();
        $miAdmin=$miAdministrador->find(145758);
        
        if(session()->get('idadmin')==$miAdmin['idadministrador']){
            if ((time() - session()->get('timeadmin')) > 3600){
                session_destroy();
                return redirect()->to(base_url()."/admin")->with('mensaje', 'La sesion se cerró por tu seguridad');
            }else{

                    $idInfluencer= $this->request->getPost('eliminarinfluencermodal');

                    //var_dump("LLEGUE ");

                    $miInflu=new InfluencerModel();

                    $miInflu->delete($idInfluencer);

                return redirect()->to(base_url()."/dashboard/influencers")->with('mensaje', 'Se eliminó correctamente el influencer');
             }

        }else{
            return redirect()->to(base_url()."/admin")->with('mensaje', 'Debes ingresar tus credenciales');
        }
        
    }
            

        public function mensajes()
    {
        $miAdministrador = new AdministradorModel();
        $miAdmin=$miAdministrador->find(145758);

        if(session()->get('idadmin')==$miAdmin['idadministrador']){
            if ((time() - session()->get('timeadmin')) > 3600){
                session_destroy();
                return redirect()->to(base_url()."/admin")->with('mensaje', 'La sesion se cerró por tu seguridad');
            }else{
                $mensajesModel=new MensajeAdministradoresModel();

                $dataheader=[ 
                    'usuario'=> $miAdmin['nombre'],
                    'url_foto'=>$miAdmin['url_foto'],
                ];

                $data=[
                    'mensajesAdmin'=> $mensajesModel->paginate(8),
                    'pager'=>$mensajesModel->pager,
                ];

                echo view('dashboard/templates/header',$dataheader); 
                echo view('dashboard/mensajes', $data); 
                echo view('dashboard/templates/footer'); 
            }
        }
    }

    public function categorias()
    {
        $miAdministrador = new AdministradorModel();
        $miAdmin=$miAdministrador->find(145758);

        if(session()->get('idadmin')==$miAdmin['idadministrador']){
            if ((time() - session()->get('timeadmin')) > 3600){
                session_destroy();
                return redirect()->to(base_url()."/admin")->with('mensaje', 'La sesion se cerró por tu seguridad');
            }else{
                $categoriasModel=new CategoriasModel();

                $dataheader=[ 
                    'usuario'=> $miAdmin['nombre'],
                    'url_foto'=>$miAdmin['url_foto'],
                ];

                $data=[
                    'categorias'=> $categoriasModel->paginate(8),
                    'pager'=>$categoriasModel->pager,
                ];

                echo view('dashboard/templates/header',$dataheader); 
                echo view('dashboard/categorias', $data); 
                echo view('dashboard/templates/footer'); 
            }
        }
    }

    public function nuevacategorias()
    {
        $miAdministrador = new AdministradorModel();
        $miAdmin=$miAdministrador->find(145758);

        if(session()->get('idadmin')==$miAdmin['idadministrador']){
            if ((time() - session()->get('timeadmin')) > 3600){
                session_destroy();
                return redirect()->to(base_url()."/admin")->with('mensaje', 'La sesion se cerró por tu seguridad');
            }else{
                $categoriasModel=new CategoriasModel();

                $dataheader=[ 
                    'usuario'=> $miAdmin['nombre'],
                    'url_foto'=>$miAdmin['url_foto'],
                ];

                

                echo view('dashboard/templates/header',$dataheader); 
                echo view('dashboard/nueva-categoria'); 
                echo view('dashboard/templates/footer'); 
            }
        }
    }

    public function crearNuevaCategoria()
    {

        $nombre= $this->request->getPost('nombrecategoria');
        //$descripcion= $this->request->getPost('descripcionnewnoticia');
        //$imagefile = $this->request->getFiles();
        $visible=0;
        if(isset($_POST["esvisible"])){
            $visible= 1;
        }
        
        $archivofoto=$this->_uploadcat('fotocategoria');

        if($nombre!=""){
            if($archivofoto=="" || $archivofoto==null){
                $archivofoto="cat1.jpg";
            }
            

            $categoria= new CategoriasModel();

            $data=[
                'nombrecat'=> $nombre,
                'imagen'=> $archivofoto,
                'mostradas'=>$visible,
            ];

            $id=$categoria->insert($data);
            
            if($id>0 ){
                
                
            

            return redirect()->to(base_url("/dashboard/categorias"))->with('mensaje', 'La categoria se creo correctamente');
        
            }else{
                return redirect()->to(base_url("/dashboard/categorias"))->with('mensaje', 'ocurrio un error al crear la categoria');
          
            }
        }else{

        return redirect()->back()->with('mensaje','Se deben llenar todos los campos'); 
        }

    }

    public function editarCategoria($id=null)
    {

        $miAdministrador = new AdministradorModel();
        $miAdmin=$miAdministrador->find(145758);
        
        if(session()->get('idadmin')==$miAdmin['idadministrador']){
            if ((time() - session()->get('timeadmin')) > 3600){
                session_destroy();
                return redirect()->to(base_url()."/admin")->with('mensaje', 'La sesion se cerró por tu seguridad');
            }else{
        
                $categoria=new CategoriasModel();
                //var_dump($noticiaModel->find($id));
                
                $dataheader=[ 
                    'usuario'=> $miAdmin['nombre'],
                    'url_foto'=>$miAdmin['url_foto'],
                ];

                $data=[
                    'categoria'=> $categoria->find($id),
                    
                ];

                echo view('dashboard/templates/header',$dataheader); 
                echo view('dashboard/editar-categoria', $data); 
                echo view('dashboard/templates/footer'); 
            }
        }
    }

    public function editarNuevaCategoria()
    {
        $miAdministrador = new AdministradorModel();
        $miAdmin=$miAdministrador->find(145758);
        
        if(session()->get('idadmin')==$miAdmin['idadministrador']){
            if ((time() - session()->get('timeadmin')) > 3600){
                session_destroy();
                return redirect()->to(base_url()."/admin")->with('mensaje', 'La sesion se cerró por tu seguridad');
            }else{
                $id= $this->request->getPost('idcategoria');
                $nombre= $this->request->getPost('nombrenewcategoria');
                $visible=0;
                if(isset($_POST["esvisible2"])){
                    $visible= 1;
                }
                $imagefile = $this->request->getFiles();
                var_dump($imagefile['fotonewcategoria']->getName());
                if($imagefile['fotonewcategoria']->getName()=="" || $imagefile['fotonewcategoria']->getName()==null){
                    
                    $data=[
                        'nombrecat'=>$nombre,
                        'mostradas'=>$visible,
                        
                    ];

                }else{
                    $archivofoto=$this->_uploadcat('fotonewcategoria');
                    $data=[
                        'nombrecat'=>$nombre,
                        'mostradas'=>$visible,
                        'imagen'=>$archivofoto
                        
                    ];
                }

                
        
                $categoria=new CategoriasModel();
               

                

                $categoria->update($id, $data);
                return redirect()->to(base_url()."/dashboard/categorias")->with('mensaje', 'Categoria actualizada');

            }
        }else{
            return redirect()->to(base_url()."/admin")->with('mensaje', 'Debes acceder con tus credenciales');

        }
    }

    public function eliminarCategoria()
    {

        $miAdministrador = new AdministradorModel();
        $miAdmin=$miAdministrador->find(145758);
        
        if(session()->get('idadmin')==$miAdmin['idadministrador']){
            if ((time() - session()->get('timeadmin')) > 3600){
                session_destroy();
                return redirect()->to(base_url()."/admin")->with('mensaje', 'La sesion se cerró por tu seguridad');
            }else{

                    $idCategoria= $this->request->getPost('eliminarcategoriamodal');


                    $categoria=new CategoriasModel();

                    $categoria->delete($idCategoria);

                return redirect()->to(base_url()."/dashboard/categorias")->with('mensaje', 'Se eliminó correctamente la categoria');
             }

        }else{
            return redirect()->to(base_url()."/admin")->with('mensaje', 'Debes ingresar tus credenciales');
        }
        
    }



    public function idiomas()
    {
        $miAdministrador = new AdministradorModel();
        $miAdmin=$miAdministrador->find(145758);

        if(session()->get('idadmin')==$miAdmin['idadministrador']){
            if ((time() - session()->get('timeadmin')) > 3600){
                session_destroy();
                return redirect()->to(base_url()."/admin")->with('mensaje', 'La sesion se cerró por tu seguridad');
            }else{
                $idiomasmodel=new IdiomaModel();

                $dataheader=[ 
                    'usuario'=> $miAdmin['nombre'],
                    'url_foto'=>$miAdmin['url_foto'],
                ];

                $data=[
                    'idiomas'=> $idiomasmodel->paginate(8),
                    'pager'=>$idiomasmodel->pager,
                ];

                echo view('dashboard/templates/header',$dataheader); 
                echo view('dashboard/idiomas', $data); 
                echo view('dashboard/templates/footer'); 
            }
        }
    }

    public function nuevoIdioma()
    {
        $miAdministrador = new AdministradorModel();
        $miAdmin=$miAdministrador->find(145758);

        if(session()->get('idadmin')==$miAdmin['idadministrador']){
            if ((time() - session()->get('timeadmin')) > 3600){
                session_destroy();
                return redirect()->to(base_url()."/admin")->with('mensaje', 'La sesion se cerró por tu seguridad');
            }else{

                $dataheader=[ 
                    'usuario'=> $miAdmin['nombre'],
                    'url_foto'=>$miAdmin['url_foto'],
                ];

                

                echo view('dashboard/templates/header',$dataheader); 
                echo view('dashboard/nuevo-idioma'); 
                echo view('dashboard/templates/footer'); 
            }
        }
    }

    public function crearNuevoIdioma()
    {

        $nombre= $this->request->getPost('nombreidioma');
        //$descripcion= $this->request->getPost('descripcionnewnoticia');
        //$imagefile = $this->request->getFiles();
       

        if($nombre!=""){
            
            $idiomamodel= new IdiomaModel();

            $data=[
                'nombre'=> $nombre,
            ];

            $id=$idiomamodel->insert($data);
            
            if($id>0 ){
                
                
            

            return redirect()->to(base_url("/dashboard/idiomas"))->with('mensaje', 'El Idioma se creo correctamente');
        
            }else{
                return redirect()->to(base_url("/dashboard/idiomas"))->with('mensaje', 'ocurrio un error al crear el Idioma');
          
            }
        }else{

        return redirect()->back()->with('mensaje','Se deben llenar todos los campos'); 
        }

    }

    
    public function eliminarIdioma()
    {

        $miAdministrador = new AdministradorModel();
        $miAdmin=$miAdministrador->find(145758);
        
        if(session()->get('idadmin')==$miAdmin['idadministrador']){
            if ((time() - session()->get('timeadmin')) > 3600){
                session_destroy();
                return redirect()->to(base_url()."/admin")->with('mensaje', 'La sesion se cerró por tu seguridad');
            }else{

                    $id= $this->request->getPost('eliminaridiomamodal');


                    $idiomamodel= new IdiomaModel();

                    $idiomamodel->delete($id);

                return redirect()->to(base_url()."/dashboard/idiomas")->with('mensaje', 'Se eliminó correctamente el idioma');
             }

        }else{
            return redirect()->to(base_url()."/admin")->with('mensaje', 'Debes ingresar tus credenciales');
        }
        
    }


    public function eliminarMensaje()
    {

        $miAdministrador = new AdministradorModel();
        $miAdmin=$miAdministrador->find(145758);
        
        if(session()->get('idadmin')==$miAdmin['idadministrador']){
            if ((time() - session()->get('timeadmin')) > 3600){
                session_destroy();
                return redirect()->to(base_url()."/admin")->with('mensaje', 'La sesion se cerró por tu seguridad');
            }else{

                    $idMensaje= $this->request->getPost('eliminarmensamodal');


                    $miMensaje=new MensajeAdministradoresModel();

                    $miMensaje->delete($idMensaje);

                return redirect()->to(base_url()."/dashboard/mensajes")->with('mensaje', 'Se eliminó correctamente el mensaje');
             }

        }else{
            return redirect()->to(base_url()."/admin")->with('mensaje', 'Debes ingresar tus credenciales');
        }
        
    }

    public function editarNoticia($id=null)
    {

        $miAdministrador = new AdministradorModel();
        $miAdmin=$miAdministrador->find(145758);
        
        if(session()->get('idadmin')==$miAdmin['idadministrador']){
            if ((time() - session()->get('timeadmin')) > 3600){
                session_destroy();
                return redirect()->to(base_url()."/admin")->with('mensaje', 'La sesion se cerró por tu seguridad');
            }else{
        
                $noticiamodel=new NoticiasModel();
                //var_dump($noticiaModel->find($id));
                $dataheader=[ 
                    'usuario'=> $miAdmin['nombre'],
                    'url_foto'=>$miAdmin['url_foto'],
                ];

                $data=[
                    'noticiaAEditar'=> $noticiamodel->find($id),
                    'noticias'=> $noticiamodel->orderBy('titulo','ASC')->find(),
                ];

                echo view('dashboard/templates/header',$dataheader); 
                echo view('dashboard/editar-noticia', $data); 
                echo view('dashboard/templates/footer'); 
            }
        }
    }

    public function actualizarNoticia()
    {
        $miAdministrador = new AdministradorModel();
        $miAdmin=$miAdministrador->find(145758);
        
        if(session()->get('idadmin')==$miAdmin['idadministrador']){
            if ((time() - session()->get('timeadmin')) > 3600){
                session_destroy();
                return redirect()->to(base_url()."/admin")->with('mensaje', 'La sesion se cerró por tu seguridad');
            }else{
                $id= $this->request->getPost('idnoticia');
                $titulo= $this->request->getPost('titulonewnoticia2');
                $favorito=0;
                if(isset($_POST["esfavorito2"])){
                    $favorito= 1;
                }
                $cuerpo= $this->request->getPost('summernote');
        
                $noticiamodel=new NoticiasModel();
               

                $data=[
                    'titulo'=>$titulo,
                    'favorito'=>$favorito,
                    'cuerpo'=>$cuerpo,
                ];

                $noticiamodel->update($id, $data);
                return redirect()->to(base_url()."/dashboard/noticias")->with('mensaje', 'Noticia actualizada');

            }
        }else{
            return redirect()->to(base_url()."/admin")->with('mensaje', 'Debes acceder con tus credenciales');

        }
    }

    
    public function mensajeLeido($id=null)
    {

        $mensajesModel=new MensajeAdministradoresModel();


        $data = [
            'leido' => 1,
        ];
        
        $mensajesModel->update($id, $data);
        return redirect()->to(base_url()."/dashboard/mensajes")->with('mensaje', 'Mensaje actualizado');

         
    }

    public function noticias()
    {

        $miAdministrador = new AdministradorModel();
        $miAdmin=$miAdministrador->find(145758);
        
        if(session()->get('idadmin')==$miAdmin['idadministrador']){
            if ((time() - session()->get('timeadmin')) > 3600){
                session_destroy();
                return redirect()->to(base_url()."/admin")->with('mensaje', 'La sesion se cerró por tu seguridad');
            }else{

                    $noticia=new NoticiasModel();


                    $dataheader=[ 
                        'usuario'=> $miAdmin['nombre'],
                        'url_foto'=>$miAdmin['url_foto'],
                    ];
                    $data=['noticias'=>$noticia->paginate(8),
                    'pager'=>$noticia->pager,
                ];

                    echo view('dashboard/templates/header',$dataheader); 
                    echo view('dashboard/noticias', $data); 
                    echo view('dashboard/templates/footer');
             }

        }else{
            return redirect()->to(base_url()."/admin")->with('mensaje', 'Debes ingresar tus credenciales');
        }
        
    }

    public function eliminarNoticia()
    {

        $miAdministrador = new AdministradorModel();
        $miAdmin=$miAdministrador->find(145758);
        
        if(session()->get('idadmin')==$miAdmin['idadministrador']){
            if ((time() - session()->get('timeadmin')) > 3600){
                session_destroy();
                return redirect()->to(base_url()."/dashboard/noticias")->with('mensaje', 'La sesion se cerró por tu seguridad');
            }else{

                    $idNoticia= $this->request->getPost('eliminarnoticiamodal');

                    var_dump("LLEGUE ".$idNoticia);

                    $noticia=new NoticiasModel();

                    $noticia->delete($idNoticia);

                return redirect()->to(base_url()."/dashboard/noticias")->with('mensaje', 'Noticia eliminada correctamente');
             }

        }else{
            return redirect()->to(base_url()."/admin")->with('mensaje', 'Debes ingresar tus credenciales');
        }
        
    }

    public function nuevaNoticia()
    {

        $miAdministrador = new AdministradorModel();
        $miAdmin=$miAdministrador->find(145758);
        
        if(session()->get('idadmin')==$miAdmin['idadministrador']){
            if ((time() - session()->get('timeadmin')) > 3600){
                session_destroy();
                return redirect()->to(base_url()."/admin")->with('mensaje', 'La sesion se cerró por tu seguridad');
            }else{
                $noticiasmodel = new NoticiasModel();

                $dataheader=[ 
                    'usuario'=> $miAdmin['nombre'],
                    'url_foto'=>$miAdmin['url_foto'],
                ];

                $data=[
                    'noticias'=> $noticiasmodel->orderBy('titulo','ASC')->find()
                ];

                echo view('dashboard/templates/header',$dataheader); 
                echo view('dashboard/nueva-noticia', $data); 
                echo view('dashboard/templates/footer'); 
            }
        }
    }


    public function crearNuevaNoticia()
    {

        $titulo= $this->request->getPost('titulonewnoticia');
        //$descripcion= $this->request->getPost('descripcionnewnoticia');
        $imagefile = $this->request->getFiles();
        $favorito=0;
        if(isset($_POST["esfavorito"])){
            $favorito= 1;
        }
        $relacionNoti= $this->request->getPost('selectNoticia');
        $cuerpo= $this->request->getPost('summernote');
        $archivofoto=$this->_upload('fotonoticia');

        if($titulo!="" && $cuerpo!=""){
            if($archivofoto=="" || $archivofoto==null){
                $archivofoto="defecto.jpg";
            }
            

            $miNoticiaModel= new NoticiasModel();

            $data=[
                'titulo'=> $titulo,
                'cuerpo'=> $cuerpo,
                'url_foto'=>$archivofoto,
                'favorito'=>$favorito,
            ];

            $id=$miNoticiaModel->insert($data);
            
            if($id>0 && $relacionNoti>0 ){
                $miRecomendada = new NoticiasRecomendadaModel();
                
                $data2=[
                    'idnoticia'=>$id,
                    'otroidnoticia'=> $relacionNoti
                ];
                $idRec=$miRecomendada->insert($data2);
                
            }

            return redirect()->to(base_url("/dashboard/noticias"))->with('mensaje', 'La noticia se creo correctamente');
        

        }else{

        return redirect()->back()->with('mensaje','Se deben llenar todos los campos'); 
        }

    }

    private function _upload($name){

        if($imagefile = $this->request->getFile($name)){
            
            if ($imagefile->isValid() && ! $imagefile->hasMoved())
            {
               
                    $newName = $imagefile->getRandomName();
                    $imagefile->move(ROOTPATH.'public/fotosnoticias', $newName);
                    return $newName;
              

            }
        }
        return null;
    }

    private function _uploadcat($name){

        if($imagefile = $this->request->getFile($name)){
            
            if ($imagefile->isValid() && ! $imagefile->hasMoved())
            {
               
                    $newName = $imagefile->getRandomName();
                    $imagefile->move(ROOTPATH.'public/img/categorias', $newName);
                    return $newName;
              

            }
        }
        return null;
    }


    public function representantes()
    {

        $miAdministrador = new AdministradorModel();
        $miAdmin=$miAdministrador->find(145758);
        
        if(session()->get('idadmin')==$miAdmin['idadministrador']){
            if ((time() - session()->get('timeadmin')) > 3600){
                session_destroy();
                return redirect()->to(base_url()."/admin")->with('mensaje', 'La sesion se cerró por tu seguridad');
            }else{

                $misComentariosModel = new ComentariosModel();

                $dataheader=[ 
                    'usuario'=> $miAdmin['nombre'],
                    'url_foto'=>$miAdmin['url_foto'],
                ];

                $data=[
                    'comentarios'=> $misComentariosModel->paginate(8),
                    'pager'=>$misComentariosModel->pager,
                ];

                echo view('dashboard/templates/header',$dataheader); 
                echo view('dashboard/representante',$data); 
                echo view('dashboard/templates/footer'); 
            }

        }else{
            return redirect()->to(base_url()."/admin")->with('mensaje', 'Debes ingresar tus credenciales');
        }
        
    }

    public function eliminarRepresentantes()
    {

        $miAdministrador = new AdministradorModel();
        $miAdmin=$miAdministrador->find(145758);
        
        if(session()->get('idadmin')==$miAdmin['idadministrador']){
            if ((time() - session()->get('timeadmin')) > 3600){
                session_destroy();
                return redirect()->to(base_url()."/admin")->with('mensaje', 'La sesion se cerró por tu seguridad');
            }else{
                $idcomentario= $this->request->getPost('eliminarcomentariomodal');
                $misComentariosModel = new ComentariosModel();
                
                $misComentariosModel->delete($idcomentario);

                return redirect()->to(base_url()."/dashboard/representantes")->with('mensaje', 'Se eliminó correctamente el comentario');
            }

        }else{
            return redirect()->to(base_url()."/admin")->with('mensaje', 'Debes ingresar tus credenciales');
        }
        
    }

    public function cuenta()
    {
        $miAdministrador = new AdministradorModel();
        $miAdmin=$miAdministrador->find(145758);
        
        if(session()->get('idadmin')==$miAdmin['idadministrador']){
            if ((time() - session()->get('timeadmin')) > 3600){
                session_destroy();
                return redirect()->to(base_url()."/admin")->with('mensaje', 'La sesion se cerró por tu seguridad');
            }else{

                $misComentariosModel = new ComentariosModel();

                $dataheader=[ 
                    'usuario'=> $miAdmin['nombre'],
                    'url_foto'=>$miAdmin['url_foto'],
                ];

                $data=[
                    'administrador'=>$miAdmin,
                ];

                echo view('dashboard/templates/header',$dataheader); 
                echo view('dashboard/cuenta', $data); 
                echo view('dashboard/templates/footer'); 
            }

        }else{
            return redirect()->to(base_url()."/admin")->with('mensaje', 'Debes ingresar tus credenciales');
        }

        

        
    }

    public function cambiarFotoAdmin()
    {
        $miAdministrador = new AdministradorModel();
        $miAdmin=$miAdministrador->find(145758);

        //var_dump("LLEGUE");

        $archivofoto=$this->_upload('fotoAdmin'); 
        var_dump($archivofoto);
        if($archivofoto!="" && $archivofoto!=null){
            var_dump("entre");
            
            $data=[
                'url_foto'=>$archivofoto
            ];

            
            $miAdministrador->update(145758 , $data);

            var_dump("entre");
            return redirect()->to(base_url()."/dashboard/cuenta")->with('mensaje', 'Tu foto se actualizó correctamente');
        
        }else{
            return redirect()->to(base_url()."/dashboard/cuenta")->with('mensaje', 'Tu foto no se pudo actualizar');
        
        }
    }

    public function cambiarNombreCorreo()
    {
        $miAdministrador = new AdministradorModel();
        $miAdmin=$miAdministrador->find(145758);

        $nuevoNombre=$miAdmin['nombre'];
        $nuevoCorreo=$miAdmin['correo'];

        $nombre= $this->request->getPost('newnombre'); 
        $correo= $this->request->getPost('newcorreo'); 
        
        if($nombre!=$miAdmin['nombre']){
            $nuevoNombre=$nombre;
            
        }
        if($correo!=$miAdmin['correo']){
            
            $nuevoCorreo=$correo;
        }

        $data=[
            "nombre"=>$nuevoNombre,
            "correo"=>$nuevoCorreo,
        ];

        $miAdministrador->update(145758, $data);

        return redirect()->to(base_url()."/dashboard/cuenta")->with('mensaje', 'Tu perfil se actualizó correctamente');
    
    }

    public function cambiarClave()
    {
        $miAdministrador = new AdministradorModel();
        $miAdmin=$miAdministrador->find(145758);

        
        $claveActual= $this->request->getPost('passadmin'); 
        $clavenew= $this->request->getPost('newpassadmin'); 


        
        if($claveActual== $miAdmin['password']){
            $data=[
                "password"=>$clavenew,
            ];
            $miAdministrador->update(145758, $data);
            return redirect()->to(base_url()."/dashboard/cuenta")->with('mensaje', 'Tu password se actualizó correctamente');
        
        }else{
            return redirect()->to(base_url()."/dashboard/cuenta")->with('mensaje', 'Password incorrecto');
        
        }
        
        

        
    }

  
    


    
   
}