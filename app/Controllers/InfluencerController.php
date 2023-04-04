<?php

namespace App\Controllers;

use Config\Services;

use App\Models\PagoModel;
use App\Models\PaisModel;
use App\Models\MarcaModel;
use App\Models\RedesModel;
use App\Models\CiudadModel;
use App\Models\IdiomaModel;
use App\Models\GaleriaModel;
use App\Models\CategoriasModel;
use App\Models\InfluencerModel;
use App\Models\MensajeCorreoModel;
use App\Models\InfluencerPagoModel;
use App\Models\InfluencerMarcaModel;
use App\Models\IdiomaInfluencerModel;
use App\Models\InfluencersRedesModel;
use App\Models\InfluencerCategoriaModel;

class InfluencerController extends BaseController
{
    
    //CARGA LA PAGINA DEL PERFIL DEL INFLUENCER
    public function index()
    {
        $this->logout();
        $dataHeader =['titulo' => 'Busca Influencer',
                'mensaje'=>"",];

        $this-> _loadDefaultView($dataHeader,['validation'=> \Config\Services::validation(),],'index');

    }



    //CARGA LA PAGINA DE POLITICA DE PRIVACIDAD
    public function privacidad()
    {
        $dataHeader =['titulo' => 'Politica de Privacidad',
                'mensaje'=>"",];

        $this-> _loadDefaultView($dataHeader,[],'privacidad');

    }


    //METODO QUE ME PERMITE CARGAR PAGINAS
    private function _loadDefaultView($dataHeader,$data,$view){

        echo view("influencer/templates/header",$dataHeader);
        echo view("influencer/influencers/$view",$data);
        echo view("influencer/templates/footer");
       

    }



    //METODO QUE CARGA LA VISTA DE REGISTRO DE INFLUENCER
    public function new(){
        //CARGAR LOS ARREGLOS DESDE LAS VISTAS
        $paises = new PaisModel();
        $idioma = new IdiomaModel();
       
        
        $misIdiomas=$idioma->findAll();
        $misPaises=$paises->findAll();
        
        $ciudades=$this->_cargarCiudades($misPaises);
        session();
        
        $validation =  \Config\Services::validation();
        
        $dataHeader =['titulo' => 'Crear Influencer',
                'mensaje'=>"",];
        $data=['validation'=>$validation, 
                'paises'=> $misPaises,
                'ciudades'=> $ciudades,];

        //CARGAR LA VISTA
        $this->_loadDefaultView($dataHeader,$data,'new');

    }



    //CREA EL NUEVO INFLUENCER
    public function create(){
        //SE DEFINEN LOS MODELOS
        $influencerModel=new InfluencerModel();
        $mensajes="";
       
        //SE CREAN LAS REGLAS DE VALIDACION PARA LOS CAMPOS
        $rules=[
            'nombre'=>'required|min_length[4]|max_length[20]',
            'alias'=>'required|min_length[2]|max_length[20]',
            'password'=>'required|min_length[8]|max_length[300]',
            'correo'=>'required|valid_email',
            'pais'=>'required|max_length[50]',
            'ciudades'=>'required|max_length[50]',
            'resenia'=>'required|min_length[10]|max_length[5000]',
            
        ];

        $imagefile = $this->request->getFiles();

        $password= $this->request->getPost('password');
        $passwordotro= $this->request->getPost('passwordver');

        //SI SE VALIDAN LAS REGLAS
        if($this->validate($rules)){
            if($password==$passwordotro){

                // Opciones de contraseña:
                $newpass= password_hash($password, PASSWORD_DEFAULT);

                $nombreinflu= $this->request->getPost('nombre');
                $alias= $this->request->getPost('alias');
                
                $ciudad= $this->request->getPost('ciudades');
                $correo= $this->request->getPost('correo');
                $resenia= $this->request->getPost('resenia');
                $archivofoto=$this->_upload2('fotoperfil');
                $tokens=bin2hex(openssl_random_pseudo_bytes(16));
                
                $datainsertar = [
                    'nombreinflu' => $nombreinflu,
                    'alias' => $alias,
                    'password' => $newpass,
                    'correo' => $correo,
                    'resenia' => $resenia,
                    'usuario' => $correo,
                    'foto_perfil'=>$archivofoto,
                    'idciudad'=>$ciudad,
                    'tokens'=>$tokens,
                    'validado'=>0, 
                ];

                //SE CREA EL INFLUENCER
                $id=$influencerModel->insert($datainsertar);

                $cuerpo = "<!DOCTYPE html>
                <html>
                <body marginheight='0' topmargin='0' marginwidth='0' style='margin: 0px; background-color: #f2f3f8;' leftmargin='0'>
                    <!--100% body table-->
                    <table cellspacing='0' border='0' cellpadding='0' width='100%' bgcolor='#f2f3f8'
                        style='@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: 'Open Sans', sans-serif;'>
                        <tr>
                            <td>
                                <table style='background-color: #f2f3f8; max-width:670px;  margin:0 auto;' width='100%' border='0'
                                    align='center' cellpadding='0' cellspacing='0'>
                                    <tr>
                                        <td style='height:80px;'>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td style='text-align:center;'>
                                          <a href='www.buscoinfluencers.com' title='logo' target='_blank'>
                                            <img width='60' src='https://www.buscoinfluencers.com/wp-content/uploads/2022/06/Logo-BINF.png' title='logo' alt='logo'>
                                          </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style='height:20px;'>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table width='95%' border='0' align='center' cellpadding='0' cellspacing='0'
                                                style='max-width:670px;background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);'>
                                                <tr>
                                                    <td style='height:40px;'>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td style='padding:0 35px;'>
                                                        <h1 style='color:#1e1e2d; font-weight:500; margin:0;font-size:32px;font-family:'Rubik',sans-serif;'>
                                                        Bienvenido a Buscoinfluencers.com</h1>
                                                        <h1 style='color:#1e1e2d; font-weight:500; margin:0;font-size:32px;font-family:'Rubik',sans-serif;'>
                                                        Confirma tu dirección de correo</h1>
                                                        <span
                                                            style='display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;'></span>
                                                        <p style='color:#455056; font-size:15px;line-height:24px; margin:0;'>
                                                        Toque el botón de abajo para confirmar su dirección de correo electrónico. Si no creó una cuenta con Buscoinfluencers.com, puede eliminar este correo electrónico de manera segura.
                                                        </p>
                                                        <a href='".base_url()."/validarCorreo/".$tokens."/".$id."'
                                                            style='background:#00ffff;text-decoration:none !important; font-weight:500; margin-top:35px; color:#000;text-transform:uppercase; font-size:14px;padding:10px 24px;display:inline-block;border-radius:50px;font-weight: 700;'>
                                                            Confirmar Correo</a>
                                                        <p style='color:#455056; font-size:15px;line-height:24px; margin:0; margin-top:35px;'>
                                                        Si el boton anterior no te funciona, copie y pegue el siguiente enlace en su navegador:
                                                        </p>
                                                        <a href='#'>".base_url()."/validarCorreo/".$tokens."/".$id."</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style='height:40px;'>&nbsp;</td>
                                                </tr>
                                            </table>
                                        </td>
                                    <tr>
                                        <td style='height:20px;'>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td style='text-align:center;'>
                                            <p style='font-size:14px; color:rgba(69, 80, 86, 0.7411764705882353); line-height:18px; margin:0 0 0;'>&copy; <strong>www.buscoinfluencers.com</strong></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style='height:80px;'>&nbsp;</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    
                </body>
                
                </html>";
                $asunto="Valida Tu correo y Activa tu cuenta";
                $this->_enviarCorreo($correo,$cuerpo,$asunto);
                
                session()->set('idinfluencer',$id);

                return redirect()->to(base_url()."/influencer/new2/$id")->with('mensaje', 'Tu cuenta se creo con exito, antes de ingresar debes validarla desde tu correo electrónico');

            }else{
                $mensaje="Los password son diferentes";
                session();
                $_SESSION['mensaje'] = $mensaje;
                return redirect()->back()->withinput();
            }
            
        }
        $validation =  \Config\Services::validation();
        $mensaje=$validation->listErrors();
                session();
                $_SESSION['mensaje'] = $mensaje;
      return redirect()->back()->withinput();
   }

   public function validarCorreo($token=null,$id=null){
      
    $influencer = new InfluencerModel();
    $inf=$influencer->find($id);

  if($inf['tokens']==$token){
    
    $datos=['tokens'=>"",
            'validado'=>1];
            $influencer->update($id,$datos);

            return redirect()->to("/")->with('mensaje', 'Correo Validado');

  }else{
    return redirect()->to("/")->with('mensaje', 'Tokens Invalido');

  }

}



   //CARGA LA VISTA DE REGISTRO DE REDES SOCIALES
    public function registro($id=null){
 
         $influencer=new InfluencerModel();
                  
         $miInfluencer=$influencer->find($id);
         $misRedes=new RedesModel();

         
         session();
         $validation =  \Config\Services::validation();
         
         $dataHeader =['titulo' => 'Crear Influencer',
                'mensaje'=>"",];

         //CARGAR LA VISTA
         $this->_loadDefaultView($dataHeader,['validation'=>$validation, 
         'influencer'=>$miInfluencer,'misredes'=>$misRedes->where('activa',1)->findAll()],'new2');

    }



   
//SE CARGA LA VISTA FINAL DEL REGISTRO
    public function registrofinal($id=null){

        $influencer=new InfluencerModel();
        $idioma=new IdiomaModel();

        $miInfluencer=$influencer->find($id);
        $misIdiomas=$idioma->findAll();

        //CARGAR LOS ARREGLOS DESDE LAS VISTAS
        $categorias = new CategoriasModel();
        $misCategorias=$categorias->where('mostradas',1)->findAll();
        session();
        
        $validation =  \Config\Services::validation();

        $dataHeader =['titulo' => 'Crear Influencer',
                'mensaje'=>"",];
        
        //CARGAR LA VISTA
        $this->_loadDefaultView($dataHeader,['validation'=>$validation,
        'categorias'=>$misCategorias, 'influencer'=>$miInfluencer, 'idiomas'=>$misIdiomas],'new3');
    }


    //SE GUARDAN LAS MARCAS; LAS CATEGORIAS; LOS VIDEOS
    public function continuarregistro(){

        $influencerModel=new InfluencerModel();
        $galeriamodel=new GaleriaModel();
        $marca = new MarcaModel();
        $mensajes="";
        
        $id=$this->request->getPost('influencerid30');
       
        //SE CREA EL INFLUENCER
        $ide=$influencerModel->find($id);
        $imagefile = $this->request->getFiles();

        
        
            $archivofoto=$this->_upload2('fotoperfil');
            $archivovideo=$this->_uploadvideo('videoperfil');
            $tipoMarca= $this->request->getPost('tipoMarca');
            $marcanuevaempresa =$this->request->getPost('marcatxt');
            $idioma=new IdiomaInfluencerModel();
            $miIdioma=$this->request->getPost('langSelect');
        
        if($miIdioma!=null || $miIdioma!=""){
            $influencerModel->UPDATE($ide,['video'=>$archivovideo]);
             
            //SE VALIDAN LAS CATEGORIAS            
            $this->_guardarCategorias($id);

                
            
                $idioma->insert(['ididioma'=>$miIdioma,'idinfluencer'=>$id]);
        }else{
                return redirect()->back()->with('mensaje', "No se agregó ningún idioma");
         }
            
            

            //SE CARGA LA GALERIA
            $galeria=$this->_upload('galeriaperfil',$imagefile);
            if(count($galeria)>5){
                for ($i=0; $i < 5 ; $i++) { 
                    $galeriamodel->insert(['idinfluencer'=>$id,'url'=>$galeria[$i]]);
                }
            }else{
                for ($i=0; $i < count($galeria) ; $i++) { 
                    $galeriamodel->insert(['idinfluencer'=>$id,'url'=>$galeria[$i]]);
                }
            }
            
        
            //SE CARGAN LAS EMPRESAS O MARCAS
            if(!($marcanuevaempresa==""||$marcanuevaempresa==null)){
                $idmarca=$marca->insert(['nombre'=>$marcanuevaempresa,'tipo'=>$tipoMarca,'idinfluencer'=>$id]); 
            }else{

            }

            //SE CARGAN LOS PAGOS
            $this->_guardarPago($id);


        return redirect()->to("/perfil/$id")->with('mensaje', 'Tu cuenta se creo con exito');

    
       
        

    }

    public function edit($id = null){

        $influencer = new InfluencerModel();
        $pais= new PaisModel();
        $ciudad= new CiudadModel();
        $redes= new RedesModel();
        $categoria= new CategoriasModel();
        $galeria= new GaleriaModel();
        $idioma=new IdiomaModel();
        $pago=new PagoModel();
        $marca=new MarcaModel();
        $influcatefori=new InfluencerCategoriaModel();
        
        $influpago=new InfluencerPagoModel();
        $influredes=new InfluencersRedesModel();
        $influidioma=new IdiomaInfluencerModel();

        if ($influencer->find($id) != null)
        {
            $inf=$influencer->find($id);
            
            $ciu=$ciudad->find($inf['idciudad']);
            $pai=$pais->find($ciu['idpais']);

            $idcateg=$influcatefori->where('idinfluencer', $id)->findAll();
            $idmarca=$marca->where('idinfluencer', $id)->findAll();
            $idpago=$influpago->where('idinfluencer', $id)->findAll();
            $idredes=$influredes->where('idinfluencer', $id)->findAll();
            $ididioma= $influidioma->where('idinfluencer', $id)->findAll();

            $misCategorias=[];
            $categoriasNoUso= $categoria->findAll();
            //var_dump($categoriasNoUso);
            foreach ($idcateg as $key => $m) {
                array_push($misCategorias,$categoria->find($m['idcategoria']));
                unset($categoriasNoUso[$m['idcategoria']-1]);
            }
            //var_dump($misCategorias);

            

            $misPagos=[];
            $misPagosNoUsados=$pago->findAll();
            foreach ($idpago as $key => $m) {
                array_push($misPagos,$pago->find($m['idpago']));
                unset($misPagosNoUsados[$m['idpago']-1]);
            }

            $misRedes=[];
            $misRedesNoUsadas=$redes->findAll();
            $misUsuariosRedes=[];
            $cont=0;
            foreach ($idredes as $key => $m) {
                
                array_push($misRedes,$redes->find($m['idredes']));
                array_push($misUsuariosRedes,$m['user']);
                //var_dump($misRedesNoUsadas);
                unset($misRedesNoUsadas[$m['idredes']-1]);
                
            }
            $redesNOUSADAS=[];
            foreach ($misRedesNoUsadas as $key => $m) {
                if($m['activa']==1){
                    array_push($redesNOUSADAS,$m);  
                }
                
                
            }

            

            $misIdiomas=[];
            $misIdiomasNoUsados= $idioma->findAll();
            foreach ($ididioma as $key => $m) {
                array_push($misIdiomas,$idioma->find($m['ididioma']));
                unset($misIdiomasNoUsados[$m['ididioma']-1]);
            }
           

           $migaleria= $galeria->where('idinfluencer', $id)->findAll();

            //var_dump($misCategorias);
            $validation =  \Config\Services::validation();
    
            $datos=['validation'=>$validation,
            'influencer'=>$inf,
            'pais'=>$pai,
            'ciudad'=>$ciu,
            'categorias'=>$misCategorias,
            'categoriasNoUsadas'=>$categoriasNoUso,
            'influencermarca'=>$idmarca,
            'pagos'=>$misPagos,
            'pagosNoUsados'=>$misPagosNoUsados,
            'influencerPagos'=>$idpago,
            'redes'=>$misRedes,
            'redesNoUsadas'=>$redesNOUSADAS,
            'redesUsuarios'=>$misUsuariosRedes,
            'redesInfluencer'=>$idredes,
            'idiomas'=>$misIdiomas,
            'galeria' => $migaleria,
            'categoriainfluencer'=>$idcateg,
            'idiomainfluencer'=>$ididioma,
            'idimanousado'=>$misIdiomasNoUsados ];

            $dataHeader=['titulo'=>'Crear influencer','mensaje'=>""];
    
            
            $this->_loadDefaultView($dataHeader,$datos,'edit');

            
        } else {
            
            throw PageNotFoundException::forPageNotFound();
        }  

        
    }



    public function delete($id=null)
    {

       

        $influencerModel=new InfluencerModel();

        if ($influencerModel->find($id) == null)
        {
            throw PageNotFoundException::forPageNotFound();
        }

        $influencerModel->delete($id);
          // echo $id;
             return redirect()->to('/influencer')->with('mensaje', 'Tu cuanta se elimino correctamente');
    }


    public function update($id=null)
    {

        

        return redirect()->to('/influencer')->with('mensaje', 'Tu cuanta se actualizó correctamente');

    
        
        //return redirect()->back()->withInput();
    
 
    }

    public function show($id = null){
        
        $influencerModel = new InfluencerModel();

        if ($influencerModel->find($id) == null)
        {
            throw PageNotFoundException::forPageNotFound();
        }   

        var_dump($influencerModel->asObject()->find($id)->id);

    }


        //PERMITE CARGAR LA LAS CIUDADES DESDE LA BASE DE DATOS
        private function _cargarCiudades($misPaises){
            $ciudades = new CiudadModel();
            $mis_ciudades=$ciudades->findAll();
            $arregloCiudades=[];
    
            for ($i=0; $i <count($misPaises) ; $i++) { 
              $arregloPorPais=[];
                
                foreach ($mis_ciudades as $key => $m) {
                    if($m['idpais']==$misPaises[$i]['idpais']){
                        array_push($arregloPorPais,$m);
                    }
                }
                $arregloCiudades[$misPaises[$i]['idpais']]=$arregloPorPais;
            }
            return $arregloCiudades;
        }



    private function _upload($name,$imagefile){

        $arraygaleria=[];
        if ($imagefile = $this->request->getFiles()) {
            
            foreach ($imagefile[$name] as $img) {
                if ($img->isValid() && ! $img->hasMoved()) {
                    $newName = $img->getRandomName();
                    $img->move(ROOTPATH.'public/uploads', $newName);
                    array_push($arraygaleria,$newName);
                }
            }
        }
        return $arraygaleria;
    }
   

    private function _upload2($name){

        if($imagefile = $this->request->getFile($name)){
            
            if ($imagefile->isValid() && ! $imagefile->hasMoved())
            {
               
                    $newName = $imagefile->getRandomName();
                    $imagefile->move(ROOTPATH.'public/uploads', $newName);
                    return $newName;
              

            }
        }
        return null;
    }

    private function _uploadvideo($name){

        if($imagefile = $this->request->getFile($name)){
            
            if ($imagefile->isValid() && ! $imagefile->hasMoved())
            {
               
                    $newName = $imagefile->getRandomName();
                    $imagefile->move(ROOTPATH.'public/video', $newName);
                    return $newName;
              

            }
        }
        return null;
    }
    


    

    private function _guardarCategorias($id){
        $categoriasmodel= new CategoriasModel();
        $influencercategoriamodel= new InfluencerCategoriaModel();
        $array=[];
        $cate=$categoriasmodel->findAll(); 
        
        foreach($cate as $key=>$m){
            if (isset($_REQUEST[$m['nombrecat']])) {
                array_push($array,$m['nombrecat']); 
             }
        }
                  
 
         for ($i=0; $i < count($array) ; $i++) { 
             for ($j=0; $j < count($cate) ; $j++) { 
                 if($array[$i]==$cate[$j]['nombrecat']){
                     $influencercategoriamodel->insert([
                         'idinfluencer'=>$id,
                         'idcategoria'=>$cate[$j]['idcategoria']
                     ]);
                 }
             }
         }
         $otro=$this->request->getPost('agregar');
         if(!($otro == ""|| $otro == null)){
             $idecat=$categoriasmodel->insert(['nombrecat'=> $otro,'mostradas'=>0]);
             $influencercategoriamodel->insert([
                 'idinfluencer'=>$id,
                 'idcategoria'=>$idecat
             ]);
         }
    }

    private function _guardarPago($id){
        $infleuncerpago= new InfluencerPagoModel();
        $pagos=new PagoModel();
        

        $arraypago=[];
 
         if (isset($_REQUEST['canje'])) {
             array_push($arraypago,'Canje por Producto'); 
         }
         if (isset($_REQUEST['dinero'])) {
             array_push($arraypago,'Dinero'); 
         }
         if (isset($_REQUEST['servicios'])) {
             array_push($arraypago,'Servicio'); 
         }
         if (isset($_REQUEST['patrocinio'])) {
             array_push($arraypago,'Patrocinio'); 
         }
 
         $cate=$pagos->findAll(); 
          
 
         for ($i=0; $i < count($arraypago) ; $i++) { 
             for ($j=0; $j < count($cate) ; $j++) { 
                 if($arraypago[$i]==$cate[$j]['nombre']){
                     $infleuncerpago->insert([
                         'idinfluencer'=>$id,
                         'idpago'=>$cate[$j]['idpago']
                     ]);
                 }
             }
         }
         
    }

    public function cambiarFoto(){
        
        var_dump("LLEGUE ");
        $influencer=new InfluencerModel();
        $id= $this->request->getPost('picIdd');
        

        
        if($imagefile = $this->request->getFile('newfotoo')){
            
            if ($imagefile->isValid() && ! $imagefile->hasMoved())
            {
               
                    $newName = $imagefile->getRandomName();
                    $imagefile->move(ROOTPATH.'public/uploads', $newName);
                    $influencer->update($id,['foto_perfil'=>$newName]);
                    return redirect()->to("/influencer/edit/$id")->with('mensaje', 'Tu foto se actualizó con exito');


            }
        }else{
            
            return redirect()->to("/influencer/edit/$id")->with('mensaje', 'ocurrió un error al actualizar tu foto');

        }

        
    }

   

    public function agregarCantidadSeguidores($cant, $redsocial){
        $redinfluencer=new InfluencersRedesModel();
            if($redinfluencer->update($redsocial,['cant_seguidores'=>$cant])!=null){
                return true;
    
            }
        return false;
    }

    

    public function elminarRedes(){

        $redinfluencer=new InfluencersRedesModel();
        $ide=$this->request->getPost('redeseliminar');
        
        $id=$this->request->getPost('influencerid2');

        if($redinfluencer->where('id', $ide)->delete()!=null){
            return redirect()->to("/influencer/edit/$id")->with('mensaje', 'Tus Red se elimino con exito con exito');
            
        }
    
        return redirect()->to("/influencer/edit/$id")->with('mensaje', 'ocurrió un error al eliminiar tu red social');

           
    }

    public function actualizarPerfil(){

        $influencer=new InfluencerModel();
        $nombre=$this->request->getPost('nombredit');
        $alias=$this->request->getPost('aliasedit');
        $usuario=$this->request->getPost('usuarioedit');
        
        $id=$this->request->getPost('influencerid3');
        $data=[
            'nombreinflu'=>$nombre,
            'alias'=>$alias,
            'usuario'=>$usuario
        ];

        if($influencer->update($id,$data)!=null){

            return redirect()->to("/influencer/edit/$id")->with('mensaje', 'Tus datos se actualizaron con exito');
            
        }
    
        return redirect()->to("/influencer/edit/$id")->with('mensaje', 'ocurrió un error al actualizar tus datos');

           
    }

    public function elminarCategoria(){

        $categoriaInfluencer=new InfluencerCategoriaModel();
        $ide=$this->request->getPost('categoriaeliminar');
        
        $id=$this->request->getPost('influencerid3');

        if($categoriaInfluencer->where('id', $ide)->delete()!=null){
            return redirect()->to("/influencer/edit/$id")->with('mensaje', 'Tu Categoria se elimino con exito con exito');
            
        }
    
        return redirect()->to("/influencer/edit/$id")->with('mensaje', 'ocurrió un error al eliminiar tu categoria');

           
    }
    
    public function adicionarCategoria(){

        $categoriaInfluencer=new InfluencerCategoriaModel();
        
        $id= $this->request->getPost('influencerid4');

        $idcatagoria= $this->request->getPost('categorianew');

        if($idcatagoria!=null){
        
            if($categoriaInfluencer->insert(['idinfluencer'=>$id,'idcategoria'=>$idcatagoria])!=null){

                return redirect()->to("/influencer/edit/$id")->with('mensaje', 'Tu Categoria se actualizó con exito');
    
            }
        }
    
        return redirect()->to("/influencer/edit/$id")->with('mensaje', 'ocurrió un error al actualizar tu categoria');
    }

    public function eliminarLenguaje(){


        $lenguajeInfluencer=new IdiomaInfluencerModel();

        $ide=$this->request->getPost('idiomaeliminar');
        
        $id=$this->request->getPost('influencerid5');

        if($lenguajeInfluencer->where('id', $ide)->delete()!=null){
            return redirect()->to("/influencer/edit/$id")->with('mensaje', 'Tu Idioma se elimino con exito con exito');
            
        }
    
        return redirect()->to("/influencer/edit/$id")->with('mensaje', 'ocurrió un error al eliminiar tu Idioma');

           
    }

    public function adicionarIdioma(){

        $idiomaInfluencer=new IdiomaInfluencerModel();
        
        $id= $this->request->getPost('influencerid6');

        $ididioma= $this->request->getPost('idiomanew');
        
        if($idiomaInfluencer->insert(['idinfluencer'=>$id,'ididioma'=>$ididioma])!=null){

            return redirect()->to("/influencer/edit/$id")->with('mensaje', 'Tu Idioma se actualizó con exito');
  
        }
    
        return redirect()->to("/influencer/edit/$id")->with('mensaje', 'ocurrió un error al actualizar tu idioma');
    }

    public function eliminarVideo(){

        $influencer=new InfluencerModel();
        $id= $this->request->getPost('influencerid7');
        

        
        if($influencer->update($id,['video'=>null])!=null){
            
        
            return redirect()->to("/influencer/edit/$id")->with('mensaje', 'Tu video se elimino con exito');


            
        }else{
            
            return redirect()->to("/influencer/edit/$id")->with('mensaje', 'ocurrió un error al eliminat tu video');

        }

        
    }

    public function cambiarVideo(){

        $influencer=new InfluencerModel();
        $id= $this->request->getPost('influencer9');
        

        
        if($imagefile = $this->request->getFile('newvideo')){
            
            if ($imagefile->isValid() && ! $imagefile->hasMoved())
            {
               
                    $newName = $imagefile->getRandomName();
                    $imagefile->move(ROOTPATH.'public/video', $newName);
                    $influencer->update($id,['video'=>$newName]);
                    return redirect()->to("/influencer/edit/$id")->with('mensaje', 'Tu video se actualizó con exito');


            }
        }else{
            
            return redirect()->to("/influencer/edit/$id")->with('mensaje', 'ocurrió un error al actualizar tu video');

        }

        
    }

    public function eliminarFotoGaleria(){

        $galeria=new GaleriaModel();
        $id= $this->request->getPost('influencerid10');
        $idgaleria= $this->request->getPost('fotoGaeliminar');

        if($galeria->where('idfoto', $idgaleria)->delete()!=null){
            return redirect()->to("/influencer/edit/$id")->with('mensaje', 'Tu foto se elimino con exito');
        }else{
            
            return redirect()->to("/influencer/edit/$id")->with('mensaje', 'ocurrió un error al eliminar tu foto');

        }

        
    }

    public function agregarFotoGaleria(){

        $galeria=new GaleriaModel();

        $id= $this->request->getPost('influencer11');

        $fotos=$galeria->where('idinfluencer',$id)->findAll();
        $cantidadFotos=count($fotos);
        $contador=0;
        
        if ($imagefile = $this->request->getFiles()) {
            
                foreach ($imagefile['newfotoGaleria'] as $img) {
                    if ($cantidadFotos < 5) {
                        if ($img->isValid() && ! $img->hasMoved()) {
                            $newName = $img->getRandomName();
                            $img->move(ROOTPATH.'public/uploads', $newName);
                            $galeria->insert(['url'=>$newName,'idinfluencer'=>$id]);
                            $cantidadFotos++;
                        }
                    }else if($contador<5){
                        if ($img->isValid() && ! $img->hasMoved()) {
                            $newName = $img->getRandomName();
                            $img->move(ROOTPATH.'public/uploads', $newName);
                            //var_dump($fotos[$contador]['idfoto']);
                            $galeria->update($fotos[$contador]['idfoto'],['url'=>$newName]);
                            $contador++;
                        }
                    }
                } 
                return redirect()->to("/influencer/edit/$id")->with('mensaje', 'Tu fotos se agregaron con exito');
        }else{
            
            return redirect()->to("/influencer/edit/$id")->with('mensaje', 'ocurrió un error al agregar sus fotos');

        }

        
    }

    public function editarResenia(){

        $influencer=new InfluencerModel();
        $resenia=$this->request->getPost('reseniaInfluencer');
        $id=$this->request->getPost('influencerid12');

        $data=[
            'resenia'=>$resenia
        ];

        if($influencer->update($id,$data)!=null){

            return redirect()->to("/influencer/edit/$id")->with('mensaje', 'Tus reseña se actualizó con exito');
            
        }
    
        return redirect()->to("/influencer/edit/$id")->with('mensaje', 'ocurrió un error al actualizar tu reseña');
    }

    public function eliminarMarcas(){


        $marca=new MarcaModel();

        $ide=$this->request->getPost('marcaeliminada');
        
        $id=$this->request->getPost('influencerid13');

        if($marca->where('idmarca', $ide)->delete()!=null){
            return redirect()->to("/influencer/edit/$id")->with('mensaje', 'Tu Marca se elimino con exito con exito');
            
        }
    
        return redirect()->to("/influencer/edit/$id")->with('mensaje', 'ocurrió un error al eliminiar tu Marca');

           
    }

    public function eliminarPagos(){


        $pagoInfluencer=new InfluencerPagoModel();

        $ide=$this->request->getPost('pagoeliminada');
        
        $id=$this->request->getPost('influencerid15');

        if($pagoInfluencer->where('id', $ide)->delete()!=null){
            return redirect()->to("/influencer/edit/$id")->with('mensaje', 'Tu PAgo se elimino con exito con exito');
            
        }
    
        return redirect()->to("/influencer/edit/$id")->with('mensaje', 'ocurrió un error al eliminiar tu Pago');

           
    }

    public function adicionarEmpresa(){

        
        $marca=new MarcaModel();
        
        $id= $this->request->getPost('influencerid16');
        $tipo= $this->request->getPost('tipoempres');
        $marcaText= $this->request->getPost('empresanewtxt');

        if(!($marcaText==null || $marcaText=="")){
            $marca->insert(['nombre'=>$marcaText,'idinfluencer'=>$id, 'tipo'=>$tipo]);
            return redirect()->to("/influencer/edit/$id")->with('mensaje', 'Tu marca se actualizó con exito');
      
            
        }
        
        
    
        return redirect()->to("/influencer/$id/edit")->with('mensaje', 'ocurrió un error al actualizar tu idioma');
    }

    public function adicionarPago(){

        $pagosInfluencer=new InfluencerPagoModel();
        
        $id= $this->request->getPost('influencerid17');

        $idpago= $this->request->getPost('pagonew');
        
        if($pagosInfluencer->insert(['idinfluencer'=>$id,'idpago'=>$idpago])!=null){

            return redirect()->to("/influencer/edit/$id")->with('mensaje', 'Tu Pago se actualizó con exito');
  
        }
    
        return redirect()->to("/influencer/edit/$id")->with('mensaje', 'ocurrió un error al actualizar tu pago');
    }

    public function editarOferta(){

        $influencer=new InfluencerModel();

        $oferta=$this->request->getPost('promocion');
        $id=$this->request->getPost('influencerid19');

        $data=[
            'oferta'=>$oferta
        ];

        if($influencer->update($id,$data)!=null){

            return redirect()->to("/influencer/edit/$id")->with('mensaje', 'Tus oferta se actualizó con exito');
            
        }
    
        return redirect()->to("/influencer/edit/$id")->with('mensaje', 'ocurrió un error al actualizar tu oferta');
    }

    function _validar_clave($clave,&$error_clave){
        if(strlen($clave) < 6){
           $error_clave = "La clave debe tener al menos 6 caracteres";
           return false;
        }
        if(strlen($clave) > 16){
           $error_clave = "La clave no puede tener más de 16 caracteres";
           return false;
        }
        if (!preg_match('`[a-z]`',$clave)){
           $error_clave = "La clave debe tener al menos una letra minúscula";
           return false;
        }
        if (!preg_match('`[A-Z]`',$clave)){
           $error_clave = "La clave debe tener al menos una letra mayúscula";
           return false;
        }
        if (!preg_match('`[0-9]`',$clave)){
           $error_clave = "La clave debe tener al menos un caracter numérico";
           return false;
        }
        $error_clave = "";
        return true;
     }

      //CARGA LA VISTA DE REGISTRO DE REDES SOCIALES
    public function mensajesInfluencer($id=null){
 
        $influencer=new InfluencerModel();
        $correos=new MensajeCorreoModel();
                 
        $miInfluencer=$influencer->find($id);
        $misCorreos=$correos->where(['idinfluencer'=>$id])->OrderBy('created_at','DESC')->findAll();
        //var_dump($misCorreos);
        
        session();
        $validation =  \Config\Services::validation();
        $data=['validation'=>$validation, 
        'influencer'=>$miInfluencer,
        'mensaje'=>"",'correos'=>$misCorreos];
        $dataHeader=['titulo'=>'Mis Mensajes','mensaje'=>""];
        
        
        //CARGAR LA VISTA
        $this->_loadDefaultView($dataHeader,$data,'mensajes');

   }

      //CARGA LA VISTA DE REGISTRO DE REDES SOCIALES
      public function eliminarMensajes($idinfluencer=null,$id=null){
 
        
        //echo $idinfluencer." ".$id;

        $influencer=new InfluencerModel();
        $correos=new MensajeCorreoModel();
        $miInfluencer=$influencer->find($idinfluencer);
        $correos->delete($id);
        $misCorreos=$correos->where(['idinfluencer'=>$idinfluencer])->OrderBy('created_at','DESC')->findAll();
        //var_dump($misCorreos);
        
        session();
        $validation =  \Config\Services::validation();
        
        $data=['validation'=>$validation, 
        'influencer'=>$miInfluencer,
        'mensaje'=>"",'correos'=>$misCorreos];
        $dataHeader=['titulo'=>'Mis Mensajes','mensaje'=>""];
        
        
        //CARGAR LA VISTA
        $this->_loadDefaultView($dataHeader,$data,'mensajes');

   }


   /*************************************************************************/
   /** ACTUALIZACION DE PASSWORD */
   /*************************************************************************/

     //CARGA LA VISTA DE OLVIDO CLAVE
     public function olvidoClave(){
        
        $data=[];
        $dataHeader=['titulo'=>'Olvido Clave','mensaje'=>""];
        
        
        //CARGAR LA VISTA
        $this->_loadDefaultView($dataHeader,$data,'olvidoClave');

   }

    //GUARDA EN BASE DE DATOS UN TOKENS Y ENVIA UN EMAIL
    public function enviarTokens(){
        
        $mail=$this->request->getPost('correorestaurar');
        //var_dump($mail);
        $influencer=new InfluencerModel();
        $mensaje="";

        $influ= $influencer->where('correo',$mail)->first();

        if($influ!=null){
            $tokens=bin2hex(openssl_random_pseudo_bytes(16));
            $data=['tokens'=>$tokens];
            //var_dump($tokens);
            $id=$influ['idinfluencer'];
            $paso=$influencer->update($id,$data);

            $cuerpo = "<!DOCTYPE html>
            <html>
            <body marginheight='0' topmargin='0' marginwidth='0' style='margin: 0px; background-color: #f2f3f8;' leftmargin='0'>
                <!--100% body table-->
                <table cellspacing='0' border='0' cellpadding='0' width='100%' bgcolor='#f2f3f8'
                    style='@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: 'Open Sans', sans-serif;'>
                    <tr>
                        <td>
                            <table style='background-color: #f2f3f8; max-width:670px;  margin:0 auto;' width='100%' border='0'
                                align='center' cellpadding='0' cellspacing='0'>
                                <tr>
                                    <td style='height:80px;'>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style='text-align:center;'>
                                      <a href='www.buscoinfluencers.com' title='logo' target='_blank'>
                                        <img width='60' src='https://www.buscoinfluencers.com/wp-content/uploads/2022/06/Logo-BINF.png' title='logo' alt='logo'>
                                      </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='height:20px;'>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>
                                        <table width='95%' border='0' align='center' cellpadding='0' cellspacing='0'
                                            style='max-width:670px;background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);'>
                                            <tr>
                                                <td style='height:40px;'>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td style='padding:0 35px;'>
                                                    <h1 style='color:#1e1e2d; font-weight:500; margin:0;font-size:32px;font-family:'Rubik',sans-serif;'>
                                                    Solicitud de recuperación de contraseña</h1>
                                                    <span
                                                        style='display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;'></span>
                                                    <p style='color:#455056; font-size:15px;line-height:24px; margin:0;'>
                                                    Recibimos una solicitud para restablecer tu contraseña, para continuar de click en el siguiente enlace.
                                                    </p>
                                                    <a href='".base_url()."/respassword/".$tokens."/".$influ['idinfluencer']."'
                                                        style='background:#00ffff;text-decoration:none !important; font-weight:500; margin-top:35px; color:#000;text-transform:uppercase; font-size:14px;padding:10px 24px;display:inline-block;border-radius:50px;font-weight: 700;'>
                                                        Recuperar contraseña</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style='height:40px;'>&nbsp;</td>
                                            </tr>
                                        </table>
                                    </td>
                                <tr>
                                    <td style='height:20px;'>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style='text-align:center;'>
                                        <p style='font-size:14px; color:rgba(69, 80, 86, 0.7411764705882353); line-height:18px; margin:0 0 0;'>&copy; <strong>www.buscoinfluencers.com</strong></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='height:80px;'>&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <!--/100% body table-->
            </body>
            
            </html>";
            $asunto="Restablecimiento de Contraseña";
            $this->_enviarCorreo($influ['correo'],$cuerpo);
            
            return redirect()->to("/")->with('mensaje', 'Revisa la bandeja de entrada de tu correo.');
  
        }else{
            
            return redirect()->back()->with('mensaje', 'El correo no se encontró en la base de datos.');
  
        }


   }


   private function _enviarCorreo($correo,$cuerp,$asunto){
    
   $cuerpo=$cuerp;

    $email = \Config\Services::email();

    $email->setFrom("noreply@buscoinfluencer.com", 'WD STUDIO CORP');
    $email->setTo($correo);
    

    $email->setSubject($asunto);
    
    $email->setMessage($cuerpo);

    $email->send();
   
    
}

    public function restablecerClave($token=null,$id=null){
      
        $influencer = new InfluencerModel();
        $inf=$influencer->find($id);

      if($inf['tokens']==$token){
        $dataHeader=['titulo'=>'Restaurar tu Contraseña','mensaje'=>""];
        $data=['influencer'=>$inf];

          //CARGAR LA VISTA
          $this->_loadDefaultView($dataHeader,$data,'restaurarpass');

      }else{
        return redirect()->to("/")->with('mensaje', 'Tokens Invalido');
  
      }

    }


    public function actualizarClave(){
      
        $influencer = new InfluencerModel();

        $pass=$this->request->getPost('passwordrestaurado');
        $newpass=$this->request->getPost('passwordrestauradover');
        $id=$this->request->getPost('idinflu');
        var_dump($pass);
        if($pass==$newpass){
            
            $inf=$influencer->find($id);
            $mipass= password_hash($pass, PASSWORD_DEFAULT);
            $datos=['tokens'=>"",
            'password'=>$mipass];
            $influencer->update($id,$datos);
            return redirect()->to("/")->with('mensaje', 'La clave se restauró correctamente');

        }else{
            return redirect()->back()->with('mensaje', 'Las claves no son iguales');
        }


    }

   




   /*************************************************************************/
   /** BUSQUEDA EN REDES USANDO APIS */
   /*************************************************************************/
   public function agregarRedSocial(){

        $redinfluencer=new InfluencersRedesModel();
        
        $id= $this->request->getPost('influencerid1');
        $redsocial= $this->request->getPost('redessocialesagregar');
        $usuarioredsocial= $this->request->getPost('textousuariored');

        $array=['idinfluencer'=>$id,'idredes'=>$redsocial];
        $buscar=$redinfluencer->where($array)->findAll();

        if(!($usuarioredsocial==null || $usuarioredsocial=="")){
            $r=$this->buscarSeguidoresAPI($redsocial,$usuarioredsocial);
                if($r!=0){
                    if($buscar==null){
                        $redinfluencer->insert(['idinfluencer'=>$id,'idredes'=>$redsocial,'user'=>$usuarioredsocial,'cant_seguidores'=>$r]);
                        return redirect()->to("/influencer/edit/$id")->with('mensaje', 'Tus Red social se creo con exito');
                    } else{
                        $redinfluencer->update($buscar[0]['id'],['cant_seguidores'=>$r]);
                        return redirect()->to("/influencer/edit/$id")->with('mensaje', 'Tus Red se actualizó con exito');
                    }
                }else{
                    return redirect()->to("/influencer/edit/$id")->with('mensaje', 'No se encontro el usuario de la red social');
                    
                }
                
        }    
        return redirect()->to("/influencer/edit/$id")->with('mensaje', 'Debes escribir un usuario para poder actualizar tu red social');
    }


     //SE GUARDAN LAS REDES SOCIALES
     public function guardarRedesSociales(){
        
        $id=$this->request->getPost('influencerid3');

        $influencerModel=new InfluencerModel();

        //SE BUSCA EL INFLUENCER en BD
        $ide=$influencerModel->find($id);
        

        //CREACION DE REDES SOCIALES EN LA BASE DE DATOS
        $mensaje=$this->_crearRedesSociales($id);
        //echo $mensaje;

        if($mensaje=="Su red social se actualizó correctamente" || $mensaje=="Su red social se actualizó correctamente"){
            //CARGAR LA VISTA
            return redirect()->to("/influencer/new3/$id")->with('mensaje', $mensaje);

        }else{
            return redirect()->back()->with('mensaje', $mensaje);
        }
   
        
    }

   


   private function _crearRedesSociales($id){
    
    $redesmodel=new RedesModel();
    $influencerredesmodel=new InfluencersRedesModel();
    $redesSociales= $redesmodel->where('activa',1)->findAll();
    $cont=0;    
    foreach ($redesSociales as $key => $m) {
        
        $nombre= $this->request->getPost($m['idredes']);
        

        if(!($nombre=="" || $nombre==null)){
            $cont++;
            $miArray=['idinfluencer'=>$id,'idredes'=>$m['idredes']];
            $obj=$influencerredesmodel->where($miArray)->findAll();
            
            $r=$this->buscarSeguidoresAPI($m['idredes'],$nombre);
            
            if($r!=0){
                if($obj==null){
                    $influencerredesmodel->insert(['idinfluencer'=>$id,'idredes'=>$m['idredes'],'user'=>$nombre,'cant_seguidores'=>$r]);
                    return "Su red social se agrego correctamente";
                } else{
                    $influencerredesmodel->update($obj[0]['id'],['cant_seguidores'=>$r]);
                    return "Su red social se actualizó correctamente";
                }
            }else{
                
                return "No se encontro el usuario de la red social";
            }
            
        }


    }
    if($cont==0){
        return "No se agregó ningun usuario";
    }
    
    
}




   public function buscarSeguidoresAPI($idredes,$nombre){

        
        $misRedes= new RedesModel();
        $red=$misRedes->find($idredes);
        $cantSeguidores=0;

        if($red['nombre']=="Twitter"){
            $cantSeguidores=$this->twitter($nombre);
            return $cantSeguidores;
        }
        if($red['nombre']=="Instagram"){
            $cantSeguidores=$this->instagram($nombre);
            return $cantSeguidores;
        }
        if($red['nombre']=="Snapchat"){
            $cantSeguidores=$this->snapchat($nombre);
            return $cantSeguidores;
        }
        if($red['nombre']=="TikTok"){
            $cantSeguidores=$this->tiktok($nombre);
            return $cantSeguidores;
        }
        if($red['nombre']=="Twitch"){
            $cantSeguidores=$this->twitch($nombre);
            return $cantSeguidores;
        }


   }



   public function twitter($twitter){
        
    $url = "https://api.twitter.com/2/users/by/username/".$twitter."?user.fields=public_metrics&expansions=pinned_tweet_id";
        $authorization = 'Authorization: Bearer AAAAAAAAAAAAAAAAAAAAAOxtlgEAAAAA3fOKvy7k3FDtO9g73Bt85ctj%2Bow%3DYBG4inA8Sy7lgTDkXeyowhlI0aIOomaIMfBKi8XS3fOmsSR3EU';
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);

        $r= (array)json_decode($result,true);
        curl_close($ch);
        $retorno=0;
        try{
            $retorno=$r['data']['public_metrics']['followers_count'];
        }catch(\Exception $e){
            var_dump($e->getMessage());
        }

        return $retorno;

        
   }

   public function instagram($instagram){
   // var_dump($instagram);
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://instagram130.p.rapidapi.com/account-info?username=".$instagram,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: instagram130.p.rapidapi.com",
            "X-RapidAPI-Key: c7c42e5e34msh4d68a181971610dp1e8e34jsn435b150476f6"
        ],
    ]);

    $result=curl_exec($curl);
    
    $r= (array)json_decode($result,true);
    //var_dump($r['edge_followed_by']['count']);
    $err = curl_error($curl);

    curl_close($curl);

    $retorno=0;
    try{
        $retorno=$r['edge_followed_by']['count'];
    }catch(\Exception $e){
        var_dump($e->getMessage());
    }
    
    return $retorno;

        
   

        
   }

   public function snapchat($snapchat){
   
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://snapchat-profile-scraper-api-at-lowest-price.p.rapidapi.com/get-snapchat-details/".$snapchat,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: snapchat-profile-scraper-api-at-lowest-price.p.rapidapi.com",
            "X-RapidAPI-Key: c7c42e5e34msh4d68a181971610dp1e8e34jsn435b150476f6"
        ],
    ]);

    $result = curl_exec($curl);
    $r= (array)json_decode($result,true);
    //var_dump($r['subscriberCount']);
    $err = curl_error($curl);

    curl_close($curl);

    $retorno=0;
    try{
        $retorno=$r['subscriberCount'];
    }catch(\Exception $e){
        var_dump($e->getMessage());
    }
    
    return $retorno;

        
   }

   public function tiktok($tiktok){
   
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://tiktok-scraper2.p.rapidapi.com/user/info?user_id=107955&user_name=".$tiktok,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: tiktok-scraper2.p.rapidapi.com",
            "X-RapidAPI-Key: c7c42e5e34msh4d68a181971610dp1e8e34jsn435b150476f6"
        ],
    ]);

    $result = curl_exec($curl);
    $r= (array)json_decode($result,true);
    //var_dump($r['userInfo']['stats']['followerCount']);
    $err = curl_error($curl);

    curl_close($curl);

    $retorno=0;
    try{
        $retorno=$r['userInfo']['stats']['followerCount'];
    }catch(\Exception $e){
        var_dump($e->getMessage());
    }
    
    return $retorno;


    

        
   }

   public function twitch($twitch){

   
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://gwyo-twitch.p.rapidapi.com/followerscount/".$twitch,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: gwyo-twitch.p.rapidapi.com",
            "X-RapidAPI-Key: c7c42e5e34msh4d68a181971610dp1e8e34jsn435b150476f6"
        ],
    ]);
    
    $result = curl_exec($curl);
    $r= (array)json_decode($result,true);
   // var_dump($r);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    $retorno=0;
    try{
        $retorno=$r['followers_count'];
    }catch(\Exception $e){
        var_dump($e->getMessage());
    }
    
    return $retorno;
    

        
   }


   

}