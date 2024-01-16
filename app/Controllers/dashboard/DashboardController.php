<?php

namespace App\Controllers\dashboard;

use Config\Services;
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

use App\Models\PagoModel;
use App\Models\MarcaModel;
use App\Models\RedesModel;
use App\Models\GaleriaModel;
use App\Models\InfluencerPagoModel;
use App\Models\IdiomaInfluencerModel;
use App\Models\InfluencersRedesModel;

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

                $redesinfluencer = new InfluencersRedesModel();
                $redes = new RedesModel();
                $mensajes = new MensajesModel();
                $mensajesCorreo = new MensajeCorreoModel();
                $galeria= new GaleriaModel();
                $idiomas = new IdiomaInfluencerModel();
                $idiomasInfluencer = new IdiomaModel();
                $pago= new PagoModel();
                $influpago = new InfluencerPagoModel();
                $marcas= new MarcaModel();
  
                $allInfluencer = $influ->findAll();
                $comentariosInfluencers = $mensajes->findAll();
                $mensajesCorreoInfluencers = $mensajesCorreo->OrderBy('id','DESC')->findAll();
                /*
                echo $uniqueCount;
                echo '<pre>';
                print_r($vals);
                echo '</pre>';
                */
                foreach ($allInfluencer as $key => $value) {
                    
                    $misredes= $redesinfluencer->where(['idinfluencer'=>$value['idinfluencer']])->find();
                    $misFotos= $galeria->where(['idinfluencer'=>$value['idinfluencer']])->find();
                    $misIdiomas = $idiomas->where(['idinfluencer'=>$value['idinfluencer']])->find();
                    $mispagos = $influpago->where(['idinfluencer'=>$value['idinfluencer']])->find();
                    
                    if ((substr_compare($value['foto_perfil'],"default",0,7)) == 0 && 
                    count($misredes) == 0 && count($misFotos) == 0 && $value['video'] == null &&
                    $value['oferta'] == null && count($misIdiomas) == 0 && count($mispagos) == 0) {

                        $allInfluencer[$key] ['estado'] = 0;

                        $allInfluencer[$key] ['noPhoto'] = 'Foto de perfil';
                        $allInfluencer[$key] ['noRedes'] = 'Redes sociales';
                        $allInfluencer[$key] ['noGaleria'] = 'Galería';
                        $allInfluencer[$key] ['noVideo'] = 'Video';
                        $allInfluencer[$key] ['noOferta'] = 'Oferta';
                        $allInfluencer[$key] ['noIdiomas'] = 'Idiomas';
                        $allInfluencer[$key] ['noPagos'] = 'Sistema de pagos';

                    }else{

                        if ((substr_compare($value['foto_perfil'],"default",0,7)) == 0 || 
                        count($misredes) == 0 || count($misFotos) == 0 || $value['video'] == null ||
                        $value['oferta'] == null || count($misIdiomas) == 0 || count($mispagos) == 0 ) {

                            $allInfluencer[$key] ['estado'] = 50;
                                                       
                            if (substr($value['foto_perfil'],0,7) == 'default') {
                                $allInfluencer[$key] ['noPhoto'] = 'Foto de perfil';
                            }
                            if (count($misredes) == 0 ) {
                                $allInfluencer[$key] ['noRedes'] = 'Redes sociales';
                            }
                            if(count($misFotos) == 0){
                                $allInfluencer[$key] ['noGaleria'] = 'Galería';
                            }
                            if($value['video'] == null){
                                $allInfluencer[$key] ['noVideo'] = 'Video';
                            }
                            if($value['oferta'] == null){
                                $allInfluencer[$key] ['noOferta'] = 'Oferta';
                            }
                            if(count($misIdiomas) == 0){
                                $allInfluencer[$key] ['noIdiomas'] = 'Idiomas';
                            }
                            if(count($mispagos) == 0){
                                $allInfluencer[$key] ['noPagos'] = 'Sistema de pagos';
                            }

                        } else {
                            $allInfluencer[$key] ['estado'] = 100;
                        }

                    }

                }

                $allInfluencer = array_reverse($allInfluencer);

                $dataheader=[ 
                    'usuario'=> $miAdmin['nombre'],
                    'url_foto'=>$miAdmin['url_foto'],
                ];
                
                $data=[
                    'influencers'=> $allInfluencer,
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

    public function enviarActivacionInfluencer()
    {

        $miAdministrador = new AdministradorModel();
        $miAdmin=$miAdministrador->find(145758);
        
        if(session()->get('idadmin')==$miAdmin['idadministrador']){
            if ((time() - session()->get('timeadmin')) > 3600){
                session_destroy();
                return redirect()->to(base_url()."/admin")->with('mensaje', 'La sesion se cerró por tu seguridad');
            }else{
                    $miInflu=new InfluencerModel();

                    $correoInfluencer = $this->request->getPost('correoactivar');
                    $idInfluencer = $this->request->getPost('idactivar');
                    $tokenInfluencer = $this->request->getPost('tokenactivar');
                                        
                    $data=['tokens'=> $tokenInfluencer,
                            'id'=> $idInfluencer];

                    $cuerpo = view('correos/nuevo-usuario', $data);
                    $asunto="Buscoinfluencers | Activa tu cuenta 👏";
                    $this->_enviarCorreo($correoInfluencer,$cuerpo,$asunto);
                    
                return redirect()->to(base_url()."/dashboard/influencers")->with('mensaje', 'Se envio correctamente al influencer');
             }

        }else{
            return redirect()->to(base_url()."/admin")->with('mensaje', 'Debes ingresar tus credenciales');
        }
        
    }

    
    public function enviarInfluencerMailchimp($correoInflu, $nombreInflu, $estadoInflu)
    {
        //$name = "tu correo ".$correoInflu." tu nombre ".$nombreInflu. " tu estado ".$estadoInflu;
        //return $name;
                
        //Add user to mailchimp
        // let's start with some variables
        $api_key = '2e164634dba741bbe097664501727be9-us9';
        $email = $correoInflu; // the user we are going to subscribe
        $status = 'subscribed'; // we are going to talk about it in just a little bit
        $merge_fields = array( 'FNAME' => $nombreInflu ); // FNAME, LNAME or something else
        $list_id = 'da1fbbcad5'; // List / Audience ID

        // start our Mailchimp connection
        $connection = curl_init();
        curl_setopt( 
            $connection, 
            CURLOPT_URL, 
            'https://' . substr( $api_key, strpos( $api_key, '-' ) + 1 ) . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/' . md5( strtolower( $email ) )
        );
        curl_setopt( $connection, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json', 'Authorization: Basic '. base64_encode( 'user:'.$api_key ) ) );
        curl_setopt( $connection, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $connection, CURLOPT_CUSTOMREQUEST, 'PUT' );
        curl_setopt( $connection, CURLOPT_POST, true );
        curl_setopt( $connection, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( 
            $connection, 
            CURLOPT_POSTFIELDS, 
            json_encode( array(
                'apikey'        => $api_key,
                'email_address' => $email,
                'status'        => $status,
                'merge_fields'  => $merge_fields,
                'tags' => array( $estadoInflu ) // you can specify some tags here as well
            ) )
        );

        $result = curl_exec( $connection );

        if( ! $result = curl_exec($connection)) {
            trigger_error(curl_error($connection));
            curl_close($connection);
            return 'error';
        }

        curl_close($connection);

        return 'hecho';
           
        
    }


    public function enviarEstado_0()
    {

        $miAdministrador = new AdministradorModel();
        $miAdmin=$miAdministrador->find(145758);
        
        if(session()->get('idadmin')==$miAdmin['idadministrador']){
            if ((time() - session()->get('timeadmin')) > 3600){
                session_destroy();
                return redirect()->to(base_url()."/admin")->with('mensaje', 'La sesion se cerró por tu seguridad');
            }else{
                                   
                $correoInfluencer = $this->request->getPost('correo_0');
                $nombreInfluencer = $this->request->getPost('nombre_0');

                $retorno = $this->enviarInfluencerMailchimp($correoInfluencer, $nombreInfluencer, 'Completar_perfil_'.date("Y-m-d"));

                if ($retorno != 'hecho') {
                    return redirect()->to(base_url()."/dashboard/influencers")->with('mensaje', 'Hubo un problema en enviar el usuario a mailchimp ');
                }else {
                    return redirect()->to(base_url()."/dashboard/influencers")->with('mensaje', 'Se envio el correo a mailchimp con la etiqueta: '.'Completar_perfil_'.date("Y-m-d"));
                }
                     
             }

        }else{
            return redirect()->to(base_url()."/admin")->with('mensaje', 'Debes ingresar tus credenciales');
        }
        
    }

    public function enviarEstado_50()
    {

        $miAdministrador = new AdministradorModel();
        $miAdmin=$miAdministrador->find(145758);
        
        if(session()->get('idadmin')==$miAdmin['idadministrador']){
            if ((time() - session()->get('timeadmin')) > 3600){
                session_destroy();
                return redirect()->to(base_url()."/admin")->with('mensaje', 'La sesion se cerró por tu seguridad');
            }else{
                                   
                $correoInfluencer = $this->request->getPost('correo_50');
                $nombreInfluencer = $this->request->getPost('nombre_50');

                $retorno = $this->enviarInfluencerMailchimp($correoInfluencer, $nombreInfluencer, 'Perfil_incompleto_'.date("Y-m-d"));

                if ($retorno != 'hecho') {
                    return redirect()->to(base_url()."/dashboard/influencers")->with('mensaje', 'Hubo un problema en enviar el usuario a mailchimp ');
                }else {
                    return redirect()->to(base_url()."/dashboard/influencers")->with('mensaje', 'Se envio el correo a mailchimp con la etiqueta: '.'Perfil_incompleto_'.date("Y-m-d"));
                }
                     
             }

        }else{
            return redirect()->to(base_url()."/admin")->with('mensaje', 'Debes ingresar tus credenciales');
        }
        
    }

    public function enviarEstado_100()
    {

        $miAdministrador = new AdministradorModel();
        $miAdmin=$miAdministrador->find(145758);
        
        if(session()->get('idadmin')==$miAdmin['idadministrador']){
            if ((time() - session()->get('timeadmin')) > 3600){
                session_destroy();
                return redirect()->to(base_url()."/admin")->with('mensaje', 'La sesion se cerró por tu seguridad');
            }else{
                                   
                $correoInfluencer = $this->request->getPost('correo_100');
                $nombreInfluencer = $this->request->getPost('nombre_100');

                $retorno = $this->enviarInfluencerMailchimp($correoInfluencer, $nombreInfluencer, 'Perfil_completo_'.date("Y-m-d"));

                if ($retorno != 'hecho') {
                    return redirect()->to(base_url()."/dashboard/influencers")->with('mensaje', 'Hubo un problema en enviar el usuario a mailchimp ');
                }else {
                    return redirect()->to(base_url()."/dashboard/influencers")->with('mensaje', 'Se envio el correo a mailchimp con la etiqueta: '.'Perfil_completo_'.date("Y-m-d"));
                }
                     
             }

        }else{
            return redirect()->to(base_url()."/admin")->with('mensaje', 'Debes ingresar tus credenciales');
        }
        
    }

    public function enviarTodos($param=null) {
        echo "llega: ".$param."<br>";
        $miAdministrador = new AdministradorModel();
        $miAdmin=$miAdministrador->find(145758);
        
        if(session()->get('idadmin')==$miAdmin['idadministrador']){
            if ((time() - session()->get('timeadmin')) > 3600){
                session_destroy();
                return redirect()->to(base_url()."/admin")->with('mensaje', 'La sesion se cerró por tu seguridad');
            }else{
                                   
                $influ = new InfluencerModel();
                $redesinfluencer = new InfluencersRedesModel();
                $galeria= new GaleriaModel();
                $idiomas = new IdiomaInfluencerModel();
                $influpago = new InfluencerPagoModel();

                $allInfluencer = $influ->findAll();
               
                if ($param == 'perfil_0') {

                    foreach ($allInfluencer as $key => $value) {
                        
                        $misredes= $redesinfluencer->where(['idinfluencer'=>$value['idinfluencer']])->find();
                        $misFotos= $galeria->where(['idinfluencer'=>$value['idinfluencer']])->find();
                        $misIdiomas = $idiomas->where(['idinfluencer'=>$value['idinfluencer']])->find();
                        $mispagos = $influpago->where(['idinfluencer'=>$value['idinfluencer']])->find();
                    
                        if ((substr_compare($value['foto_perfil'],"default",0,7)) == 0 && 
                        count($misredes) == 0 && count($misFotos) == 0 && $value['video'] == null &&
                        $value['oferta'] == null && count($misIdiomas) == 0 && count($mispagos) == 0) {
                            
                            //echo $value['nombreinflu'] ." - ". $value['correo'] ." <br> ";
                            $retorno = $this->enviarInfluencerMailchimp($value['correo'], $value['nombreinflu'], 'Todos_completar_perfil_'.date("Y-m-d"));
                            if ($retorno != 'hecho') {
                                return redirect()->to(base_url()."/dashboard/influencers")->with('mensaje', 'Hubo un problema en enviar todos a mailchimp ');
                            }
                        }
                       
                    }

                    return redirect()->to(base_url()."/dashboard/influencers")->with('mensaje', 'Se envio todos a mailchimp con la etiqueta: '.'Todos_completar_perfil_'.date("Y-m-d"));

                }

                if ($param == 'perfil_50') {

                    foreach ($allInfluencer as $key => $value) {
                        
                        $misredes= $redesinfluencer->where(['idinfluencer'=>$value['idinfluencer']])->find();
                        $misFotos= $galeria->where(['idinfluencer'=>$value['idinfluencer']])->find();
                        $misIdiomas = $idiomas->where(['idinfluencer'=>$value['idinfluencer']])->find();
                        $mispagos = $influpago->where(['idinfluencer'=>$value['idinfluencer']])->find();
                    
                        if ((substr_compare($value['foto_perfil'],"default",0,7)) == 0 || 
                        count($misredes) == 0 || count($misFotos) == 0 || $value['video'] == null ||
                        $value['oferta'] == null || count($misIdiomas) == 0 || count($mispagos) == 0) {
                            //echo $value['nombreinflu'] ." - ". $value['correo'] ." <br> ";
                            $retorno = $this->enviarInfluencerMailchimp($value['correo'], $value['nombreinflu'], 'Todos_perfil_incompleto_'.date("Y-m-d"));
                            if ($retorno != 'hecho') {
                                return redirect()->to(base_url()."/dashboard/influencers")->with('mensaje', 'Hubo un problema en enviar todos a mailchimp ');
                            }
                        }
                       
                    }

                    return redirect()->to(base_url()."/dashboard/influencers")->with('mensaje', 'Se envio todos a mailchimp con la etiqueta: '.'Todos_perfil_incompleto_'.date("Y-m-d"));

                }
                
                if ($param == 'perfil_100') {

                    foreach ($allInfluencer as $key => $value) {
                        
                        $misredes= $redesinfluencer->where(['idinfluencer'=>$value['idinfluencer']])->find();
                        $misFotos= $galeria->where(['idinfluencer'=>$value['idinfluencer']])->find();
                        $misIdiomas = $idiomas->where(['idinfluencer'=>$value['idinfluencer']])->find();
                        $mispagos = $influpago->where(['idinfluencer'=>$value['idinfluencer']])->find();
                    
                        if ((substr_compare($value['foto_perfil'],"default",0,7)) != 0 && 
                        count($misredes) != 0 && count($misFotos) != 0 && $value['video'] != null &&
                        $value['oferta'] != null && count($misIdiomas) != 0 && count($mispagos) != 0) {
                           //echo $value['nombreinflu'] ." - ". $value['correo'] ." <br> ";
                            $retorno = $this->enviarInfluencerMailchimp($value['correo'], $value['nombreinflu'], 'Todos_perfil_completo_'.date("Y-m-d"));
                            if ($retorno != 'hecho') {
                                return redirect()->to(base_url()."/dashboard/influencers")->with('mensaje', 'Hubo un problema en enviar todos a mailchimp ');
                            }
                        }
                       
                    }

                    return redirect()->to(base_url()."/dashboard/influencers")->with('mensaje', 'Se envio todos a mailchimp con la etiqueta: '.'Todos_perfil_completo_'.date("Y-m-d"));

                }
                    
            }

        }else{
            return redirect()->to(base_url()."/admin")->with('mensaje', 'Debes ingresar tus credenciales');
        }
   
    }


    public function estadisticas()
    {

        $miAdministrador = new AdministradorModel();
        $miAdmin=$miAdministrador->find(145758);

        if(session()->get('idadmin')==$miAdmin['idadministrador']){
            if ((time() - session()->get('timeadmin')) > 3600){
                session_destroy();
                return redirect()->to(base_url()."/admin")->with('mensaje', 'La sesion se cerró por tu seguridad');
            }else{
       
                $influ = new InfluencerModel();

                $redesinfluencer = new InfluencersRedesModel();
                $redes = new RedesModel();
                $mensajes = new MensajesModel();
                $mensajesCorreo = new MensajeCorreoModel();
                $galeria= new GaleriaModel();
                $idiomas = new IdiomaInfluencerModel();
                $idiomasInfluencer = new IdiomaModel();
                $pago= new PagoModel();
                $influpago = new InfluencerPagoModel();
                $marcas= new MarcaModel();
  
                $allInfluencer = $influ->findAll();
                $comentariosInfluencers = $mensajes->findAll();
                $mensajesCorreoInfluencers = $mensajesCorreo->OrderBy('id','DESC')->findAll();
                
                foreach ($allInfluencer as $key => $value) {
                    
                    $misredes= $redesinfluencer->where(['idinfluencer'=>$value['idinfluencer']])->find();
                    $misFotos= $galeria->where(['idinfluencer'=>$value['idinfluencer']])->find();
                    $misIdiomas = $idiomas->where(['idinfluencer'=>$value['idinfluencer']])->find();
                    $mispagos = $influpago->where(['idinfluencer'=>$value['idinfluencer']])->find();
                    
                    if ((substr_compare($value['foto_perfil'],"default",0,7)) == 0 && 
                    count($misredes) == 0 && count($misFotos) == 0 && $value['video'] == null &&
                    $value['oferta'] == null && count($misIdiomas) == 0 && count($mispagos) == 0) {

                        $allInfluencer[$key] ['estado'] = 0;

                        $allInfluencer[$key] ['noPhoto'] = 'Foto de perfil';
                        $allInfluencer[$key] ['noRedes'] = 'Redes sociales';
                        $allInfluencer[$key] ['noGaleria'] = 'Galería';
                        $allInfluencer[$key] ['noVideo'] = 'Video';
                        $allInfluencer[$key] ['noOferta'] = 'Oferta';
                        $allInfluencer[$key] ['noIdiomas'] = 'Idiomas';
                        $allInfluencer[$key] ['noPagos'] = 'Sistema de pagos';

                    }else{

                        if ((substr_compare($value['foto_perfil'],"default",0,7)) == 0 || 
                        count($misredes) == 0 || count($misFotos) == 0 || $value['video'] == null ||
                        $value['oferta'] == null || count($misIdiomas) == 0 || count($mispagos) == 0 ) {

                            $allInfluencer[$key] ['estado'] = 50;
                            
                            
                            if (substr($value['foto_perfil'],0,7) == 'default') {
                                $allInfluencer[$key] ['noPhoto'] = 'Foto de perfil';
                            }
                            if (count($misredes) == 0 ) {
                                $allInfluencer[$key] ['noRedes'] = 'Redes sociales';
                            }
                            if(count($misFotos) == 0){
                                $allInfluencer[$key] ['noGaleria'] = 'Galería';
                            }
                            if($value['video'] == null){
                                $allInfluencer[$key] ['noVideo'] = 'Video';
                            }
                            if($value['oferta'] == null){
                                $allInfluencer[$key] ['noOferta'] = 'Oferta';
                            }
                            if(count($misIdiomas) == 0){
                                $allInfluencer[$key] ['noIdiomas'] = 'Idiomas';
                            }
                            if(count($mispagos) == 0){
                                $allInfluencer[$key] ['noPagos'] = 'Sistema de pagos';
                            }

                        } else {
                            $allInfluencer[$key] ['estado'] = 100;
                        }

                    }

                }
                /*
                echo '<pre>';
                print_r($allInfluencer);
                echo '</pre>';
                */
                $allInfluencer = array_reverse($allInfluencer);

                //Estadisticas
                $cant= count($allInfluencer);

                $cantidad_NoValidos = count($influ->where(['validado'=>0])->findAll());
                $cantidad_Validos = count($influ->where(['validado'=>1])->findAll());
                $cantidad_Estado_0 = array_count_values(array_column($allInfluencer, 'estado'))['0'];
                $cantidad_Estado_50 = array_count_values(array_column($allInfluencer, 'estado'))['50'];
                $cantidad_Estado_100 = array_count_values(array_column($allInfluencer, 'estado'))['100'];
                //Total comentarios de influencers aprobados
                $comentariosInfluencersAprobados = $mensajes->where(['aprobado'=>1])->OrderBy('idmensaje','DESC')->findAll();
                $totalComentarios = count($comentariosInfluencersAprobados);
                $influencersComentados = count(array_unique(array_column($comentariosInfluencersAprobados, 'idinfluencer')));
                //Cuantas veces ha sido contactado un influencer
                $itemsComentarios = array_count_values(array_column($comentariosInfluencersAprobados, 'idinfluencer'));
                //Info del influencer que ha sido comentado, usando los id de los influencers comentados
                $infoInfluencerComentados = array();

                foreach ($itemsComentarios as $key => $value) {
                    $aux = $influ->where(['idinfluencer'=>$key])->find(); 
                    $aux[0] ['numcomentarios'] = $value;
                    array_push($infoInfluencerComentados, $aux[0]);
                    
                }

                $totalMensajes = count($mensajesCorreoInfluencers);
                $totalInfluencersConMensajes = count(array_unique(array_column($mensajesCorreoInfluencers, 'idinfluencer')));
                $itemsMensajes = array_count_values(array_column($mensajesCorreoInfluencers, 'idinfluencer'));

                $infoInfluencersMensajeados = array();

                foreach ($itemsMensajes as $key => $value) {
                    $aux = $influ->where(['idinfluencer'=>$key])->find(); 
                    $aux[0] ['nummensajes'] = $value;
                    array_push($infoInfluencersMensajeados, $aux[0]);
                    
                }
               
                $dataheader=[ 
                    'usuario'=> $miAdmin['nombre'],
                    'url_foto'=>$miAdmin['url_foto'],
                ];
                
                $data=[
                    'influencers'=> $allInfluencer,
                    'cantidadInfluencers' => $cant,
                    'cantidadNoValidos' => $cantidad_NoValidos,
                    'cantidadValidos' => $cantidad_Validos,
                    'cantidad_0' => $cantidad_Estado_0,
                    'cantidad_50' => $cantidad_Estado_50,
                    'cantidad_100' => $cantidad_Estado_100,
                    'totalComentarios' => $totalComentarios,
                    'influencersComentados' => $influencersComentados,
                    'infoInfluencerComentados' => $infoInfluencerComentados,
                    'totalMensajes' => $totalMensajes,
                    'totalInfluencersConMensajes' => $totalInfluencersConMensajes,
                    'itemsMensajes' => $itemsMensajes,
                    'infoInfluencersMensajeados' => $infoInfluencersMensajeados
                ];

                echo view('dashboard/templates/header',$dataheader); 
                echo view('dashboard/estadisticas', $data); 
                echo view('dashboard/templates/footer'); 
            }
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


    public function comentarios()
    {
        $miAdministrador = new AdministradorModel();
        $miAdmin=$miAdministrador->find(145758);

        if(session()->get('idadmin')==$miAdmin['idadministrador']){
            if ((time() - session()->get('timeadmin')) > 3600){
                session_destroy();
                return redirect()->to(base_url()."/admin")->with('mensaje', 'La sesion se cerró por tu seguridad');
            }else{
                $mensajesModel=new MensajesModel();
                $influencerModel=new InfluencerModel();

                $dataheader=[ 
                    'usuario'=> $miAdmin['nombre'],
                    'url_foto'=>$miAdmin['url_foto'],
                ];

                $data=[
                    'mensajesInfluencer'=> $mensajesModel->where('aprobado',0)->paginate(8),
                    'pager'=>$mensajesModel->pager,
                    'mensajesInfluenceraprobados'=> $mensajesModel->where('aprobado',1)->paginate(8),
                    'influencers'=>$influencerModel->findAll(),
                ];

                echo view('dashboard/templates/header',$dataheader); 
                echo view('dashboard/comentarios', $data); 
                echo view('dashboard/templates/footer'); 
            }
        }
    }


    public function editarComentarios()
    {
        $miAdministrador = new AdministradorModel();
        $miAdmin=$miAdministrador->find(145758);

        if(session()->get('idadmin')==$miAdmin['idadministrador']){
            if ((time() - session()->get('timeadmin')) > 3600){
                session_destroy();
                return redirect()->to(base_url()."/admin")->with('mensaje', 'La sesion se cerró por tu seguridad');
            }else{
                $idcomentario= $this->request->getPost('idEditarComentario');
                $newmensaje= $this->request->getPost('cuerpomodal');

                $mensajesModel=new MensajesModel();

                if($newmensaje!=""){
                    if($mensajesModel->update($idcomentario,['cuerpo'=>$newmensaje])){
                        return redirect()->to(base_url("/dashboard/comentarios"))->with('mensaje', 'El comentario se actualizó correctamente');
                    }else{
                        return redirect()->to(base_url("/dashboard/comentarios"))->with('mensaje', 'ocurrio un error al tratar de actualizar el comentario');
                    }
                }else{
                    return redirect()->to(base_url("/dashboard/comentarios"))->with('mensaje', 'El comentario no puede estar vacio');
                }

            }
        }
    }

    public function aprobarComentarios($id=null)
    {
        $miAdministrador = new AdministradorModel();
        $miAdmin=$miAdministrador->find(145758);

        if(session()->get('idadmin')==$miAdmin['idadministrador']){
            if ((time() - session()->get('timeadmin')) > 3600){
                session_destroy();
                return redirect()->to(base_url()."/admin")->with('mensaje', 'La sesion se cerró por tu seguridad');
            }else{
                
                $mensajesModel=new MensajesModel();
                $influencer = new InfluencerModel();

                //echo $id;
                $rowComment = $mensajesModel->find($id);
                $miInfluencer = $influencer->find($rowComment['idinfluencer']);
                
                    if($mensajesModel->update($id,['aprobado'=>1])){

                        
                        $cuerpo = "<!DOCTYPE html>

                        <html lang='en' xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:v='urn:schemas-microsoft-com:vml'>
                        
                        <head>
                            <title></title>
                            <meta content='text/html; charset=utf-8' http-equiv='Content-Type' />
                            <meta content='width=device-width, initial-scale=1.0' name='viewport' />
                            <!--[if mso]><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch><o:AllowPNG/></o:OfficeDocumentSettings></xml><![endif]--><!--[if !mso]><!-->
                            <link href='https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900'
                                rel='stylesheet' type='text/css' /><!--<![endif]-->
                            <style>
                                * {
                                    box-sizing: border-box;
                                }
                        
                                body {
                                    margin: 0;
                                    padding: 0;
                                }
                        
                                a[x-apple-data-detectors] {
                                    color: inherit !important;
                                    text-decoration: inherit !important;
                                }
                        
                                #MessageViewBody a {
                                    color: inherit;
                                    text-decoration: none;
                                }
                        
                                p {
                                    line-height: inherit
                                }
                        
                                .desktop_hide,
                                .desktop_hide table {
                                    mso-hide: all;
                                    display: none;
                                    max-height: 0px;
                                    overflow: hidden;
                                }
                        
                                .image_block img+div {
                                    display: none;
                                }
                        
                                @media (max-width:768px) {
                                    .desktop_hide table.icons-inner {
                                        display: inline-block !important;
                                    }

                                    u + #body a {
                                        color: inherit !important;
                                        text-decoration: none !important;
                                        font-size: inherit !important;
                                        font-family: inherit !important;
                                        font-weight: inherit !important;
                                        line-height: inherit !important;
                                        }
                        
                                    .icons-inner {
                                        text-align: center;
                                    }
                        
                                    .icons-inner td {
                                        margin: 0 auto;
                                    }
                        
                                    .mobile_hide {
                                        display: none;
                                    }
                        
                                    .row-content {
                                        width: 100% !important;
                                    }
                        
                                    .stack .column {
                                        width: 100%;
                                        display: block;
                                    }
                        
                                    .mobile_hide {
                                        min-height: 0;
                                        max-height: 0;
                                        max-width: 0;
                                        overflow: hidden;
                                        font-size: 0px;
                                    }
                        
                                    .desktop_hide,
                                    .desktop_hide table {
                                        display: table !important;
                                        max-height: none !important;
                                    }
                        
                                    .row-2 .column-1 .block-4.paragraph_block td.pad>div,
                                    .row-3 .column-2 .block-2.paragraph_block td.pad>div,
                                    .row-4 .column-1 .block-1.paragraph_block td.pad>div {
                                        text-align: center !important;
                                        font-size: 15px !important;
                                    }
                        
                                    .row-2 .column-1 .block-2.heading_block h1,
                                    .row-7 .column-1 .block-1.paragraph_block td.pad>div,
                                    .row-7 .column-2 .block-1.paragraph_block td.pad>div {
                                        text-align: center !important;
                                    }
                        
                                    .row-2 .column-1 .block-2.heading_block h1 {
                                        font-size: 22px !important;
                                    }
                        
                                    .row-2 .column-1 .block-3.heading_block h2,
                                    .row-3 .column-2 .block-1.heading_block h2 {
                                        font-size: 19px !important;
                                    }
                        
                                    .row-2 .column-1 {
                                        padding: 20px 5px 15px !important;
                                    }
                        
                                    .row-6 .column-1 {
                                        padding: 0 5px !important;
                                    }
                                }
                            </style>
                        </head>
                        
                        <body id='body' style='background-color: #f9f9f9; margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;'>
                            <table border='0' cellpadding='0' cellspacing='0' class='nl-container' role='presentation'
                                style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f9f9f9;' width='100%'>
                                <tbody>
                                    <tr>
                                        <td>
                                            <table align='center' border='0' cellpadding='0' cellspacing='0' class='row row-1'
                                                role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;' width='100%'>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <table align='center' border='0' cellpadding='0' cellspacing='0'
                                                                class='row-content stack' role='presentation'
                                                                style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 800.00px; margin: 0 auto;'
                                                                width='800.00'>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class='column column-1'
                                                                            style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; padding-top: 25px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'
                                                                            width='100%'>
                                                                            <table border='0' cellpadding='0' cellspacing='0'
                                                                                class='image_block block-1' role='presentation'
                                                                                style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'
                                                                                width='100%'>
                                                                                <tr>
                                                                                    <td class='pad' style='width:100%;'>
                                                                                        <div align='center' class='alignment'
                                                                                            style='line-height:10px'>
                                                                                            <div style='max-width: 364px;'><a
                                                                                                    href='https://buscoinfluencers.com/'
                                                                                                    style='outline:none' tabindex='-1'
                                                                                                    target='_blank'><img alt='Logo Binf'
                                                                                                        src='".base_url()."/public/img/verificacion/logo-comentarios.png'
                                                                                                        style='display: block; height: auto; border: 0; width: 100%;'
                                                                                                        title='Logo Binf' width='364' /></a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table align='center' border='0' cellpadding='0' cellspacing='0' class='row row-2'
                                                role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;' width='100%'>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <table align='center' border='0' cellpadding='0' cellspacing='0'
                                                                class='row-content stack' role='presentation'
                                                                style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 800.00px; margin: 0 auto;'
                                                                width='800.00'>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class='column column-1'
                                                                            style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; padding-bottom: 15px; padding-left: 60px; padding-right: 60px; padding-top: 20px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'
                                                                            width='100%'>
                                                                            <table border='0' cellpadding='10' cellspacing='0'
                                                                                class='divider_block block-1' role='presentation'
                                                                                style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'
                                                                                width='100%'>
                                                                                <tr>
                                                                                    <td class='pad'>
                                                                                        <div align='center' class='alignment'>
                                                                                            <table border='0' cellpadding='0' cellspacing='0'
                                                                                                role='presentation'
                                                                                                style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'
                                                                                                width='80%'>
                                                                                                <tr>
                                                                                                    <td class='divider_inner'
                                                                                                        style='font-size: 1px; line-height: 1px; border-top: 5px solid #000000;'>
                                                                                                        <span> </span></td>
                                                                                                </tr>
                                                                                            </table>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <table border='0' cellpadding='10' cellspacing='0'
                                                                                class='heading_block block-2' role='presentation'
                                                                                style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'
                                                                                width='100%'>
                                                                                <tr>
                                                                                    <td class='pad'>
                                                                                        <h1
                                                                                            style='margin: 0; color: #000000; direction: ltr; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; font-size: 38px; font-weight: 900; letter-spacing: normal; line-height: 120%; text-align: center; margin-top: 0; margin-bottom: 0; mso-line-height-alt: 45.6px;'>
                                                                                            <em><span class='tinyMce-placeholder'>¡Recibiste una
                                                                                                    reseña!</span></em></h1>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <table border='0' cellpadding='0' cellspacing='0'
                                                                                class='heading_block block-3' role='presentation'
                                                                                style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'
                                                                                width='100%'>
                                                                                <tr>
                                                                                    <td class='pad'
                                                                                        style='padding-bottom:15px;padding-left:10px;padding-right:10px;padding-top:10px;text-align:center;width:100%;'>
                                                                                        <h2
                                                                                            style='margin: 0; color: #000000; direction: ltr; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; font-size: 24px; font-weight: 800; letter-spacing: normal; line-height: 120%; text-align: center; margin-top: 0; margin-bottom: 0; mso-line-height-alt: 28.799999999999997px;'>
                                                                                            <span class='tinyMce-placeholder'>Hola ".$miInfluencer["nombreinflu"]."</span>
                                                                                        </h2>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <table border='0' cellpadding='10' cellspacing='0'
                                                                                class='paragraph_block block-4' role='presentation'
                                                                                style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'
                                                                                width='100%'>
                                                                                <tr>
                                                                                    <td class='pad'>
                                                                                        <div
                                                                                            style='color:#726f70;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:16px;font-weight:400;letter-spacing:0px;line-height:120%;text-align:center;mso-line-height-alt:19.2px;'>
                                                                                            <p style='margin: 0;'>Alguien te ha escrito una
                                                                                                reseña. Recuerda que las reseñas<br />son el
                                                                                                voto de confianza que te ayudará con más
                                                                                                oportunidades de negocio.</p>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <div class='spacer_block block-5'
                                                                                style='height:25px;line-height:25px;font-size:1px;'> </div>
                                                                            <table border='0' cellpadding='0' cellspacing='0'
                                                                                class='image_block block-6' role='presentation'
                                                                                style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'
                                                                                width='100%'>
                                                                                <tr>
                                                                                    <td class='pad' style='width:100%;'>
                                                                                        <div align='center' class='alignment'
                                                                                            style='line-height:10px'>
                                                                                            <div style='max-width: 140px;'><a
                                                                                                    href='".base_url()."/perfil/".$miInfluencer["nombreinflu"]."'
                                                                                                    style='outline:none' tabindex='-1'
                                                                                                    target='_blank'><img alt='Inicia sesión'
                                                                                                        src='".base_url()."/public/img/verificacion/btn-perfil.png'
                                                                                                        style='display: block; height: auto; border: 0; width: 100%;'
                                                                                                        title='Inicia sesión' width='140' /></a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <div class='spacer_block block-7'
                                                                                style='height:45px;line-height:45px;font-size:1px;'> </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table class='row row-3' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 7px; color: #000000; width: 800.00px; margin: 0 auto;' width='800.00'>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class='column column-1' width='16.666666666666668%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                                                                            <div class='spacer_block block-1 mobile_hide' style='height:60px;line-height:60px;font-size:1px;'>&#8202;</div>
                                                                        </td>
                                                                        <td class='column column-2' width='66.66666666666667%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                                                                            <table class='heading_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #e8e5ec; border-top-left-radius: 15px; border-top-right-radius: 15px;'>
                                                                                <tr>
                                                                                    <td class='pad' style='padding-bottom:15px;padding-left:10px;padding-right:10px;padding-top:15px;text-align:center;width:100%;'>
                                                                                        <h2 style='margin: 0; color: #000000; direction: ltr; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; font-size: 24px; font-weight: 800; letter-spacing: normal; line-height: 120%; text-align: center; margin-top: 0; margin-bottom: 0; mso-line-height-alt: 28.799999999999997px;'><span class='tinyMce-placeholder'>¿Dudas?, ¿Aportes?, ¿Comentarios?</span></h2>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <table class='paragraph_block block-2' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word; background-color: #e8e5ec;'>
                                                                                <tr>
                                                                                    <td class='pad' style='padding-bottom:30px;padding-left:10px;padding-right:10px;padding-top:10px;'>
                                                                                        <div style='color:#726f70;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:16px;font-weight:400;letter-spacing:0px;line-height:120%;text-align:center;mso-line-height-alt:19.2px;'>
                                                                                            <p style='margin: 0;'>Todos son bienvenidos porque nos ayudarán a mejorar.</p>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <table class='image_block block-3' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #e8e5ec; border-bottom-left-radius: 15px; border-bottom-right-radius: 15px; '>
                                                                                <tr>
                                                                                    <td class='pad' style='padding-bottom:15px;width:100%;'>
                                                                                        <div class='alignment' align='center' style='line-height:10px'>
                                                                                            <div style='max-width: 172px;'><a href='https://buscoinfluencers.com/contacto' target='_blank' style='outline:none' tabindex='-1'><img src='".base_url()."/public/img/verificacion/btn-escribenos.png' style='display: block; height: auto; border: 0; width: 100%;' width='172' alt='Escribenos' title='Escribenos'></a></div>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                        <td class='column column-3' width='16.666666666666668%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                                                                            <div class='spacer_block block-1 mobile_hide' style='height:60px;line-height:60px;font-size:1px;'>&#8202;</div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table align='center' border='0' cellpadding='0' cellspacing='0' class='row row-4'
                                                role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;' width='100%'>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <table align='center' border='0' cellpadding='0' cellspacing='0'
                                                                class='row-content stack' role='presentation'
                                                                style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 800.00px; margin: 0 auto;'
                                                                width='800.00'>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class='column column-1'
                                                                            style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'
                                                                            width='100%'>
                                                                            <table border='0' cellpadding='0' cellspacing='0'
                                                                                class='paragraph_block block-1' role='presentation'
                                                                                style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'
                                                                                width='100%'>
                                                                                <tr>
                                                                                    <td class='pad'
                                                                                        style='padding-bottom:10px;padding-left:10px;padding-right:10px;padding-top:25px;'>
                                                                                        <div
                                                                                            style='color:#726f70;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:16px;font-weight:400;letter-spacing:0px;line-height:180%;text-align:center;mso-line-height-alt:28.8px;'>
                                                                                            <p style='margin: 0;'>¡Prepárate para una nueva era
                                                                                                de influencia digital con BINF!<br /><br />El
                                                                                                equipo de Busco Influencers trabaja para ti.</p>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <div class='spacer_block block-2'
                                                                                style='height:35px;line-height:35px;font-size:1px;'> </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table align='center' border='0' cellpadding='0' cellspacing='0' class='row row-5'
                                                role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;' width='100%'>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <table align='center' border='0' cellpadding='0' cellspacing='0' class='row-content'
                                                                role='presentation'
                                                                style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 800.00px; margin: 0 auto;'
                                                                width='800.00'>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class='column column-1'
                                                                            style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'
                                                                            width='50%'>
                                                                            <table border='0' cellpadding='0' cellspacing='0'
                                                                                class='image_block block-1' role='presentation'
                                                                                style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'
                                                                                width='100%'>
                                                                                <tr>
                                                                                    <td class='pad' style='padding-right:10px;width:100%;'>
                                                                                        <div align='right' class='alignment'
                                                                                            style='line-height:10px'>
                                                                                            <div style='max-width: 27px;'><a
                                                                                                    href='https://www.instagram.com/buscoinfluencers/'
                                                                                                    style='outline:none' tabindex='-1'
                                                                                                    target='_blank'><img alt='Binf - Instagram'
                                                                                                        src='".base_url()."/public/img/verificacion/binf-ig.png'
                                                                                                        style='display: block; height: auto; border: 0; width: 100%;'
                                                                                                        title='Binf - Instagram'
                                                                                                        width='27' /></a></div>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                        <td class='column column-2'
                                                                            style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'
                                                                            width='50%'>
                                                                            <table border='0' cellpadding='0' cellspacing='0'
                                                                                class='image_block block-1' role='presentation'
                                                                                style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'
                                                                                width='100%'>
                                                                                <tr>
                                                                                    <td class='pad' style='padding-left:10px;width:100%;'>
                                                                                        <div align='left' class='alignment'
                                                                                            style='line-height:10px'>
                                                                                            <div style='max-width: 27px;'><a
                                                                                                    href='https://www.tiktok.com/@binfluencers'
                                                                                                    style='outline:none' tabindex='-1'
                                                                                                    target='_blank'><img alt='Binf - TikTok'
                                                                                                        src='".base_url()."/public/img/verificacion/binf-tik-tok.png'
                                                                                                        style='display: block; height: auto; border: 0; width: 100%;'
                                                                                                        title='Binf - TikTok' width='27' /></a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table align='center' border='0' cellpadding='0' cellspacing='0' class='row row-6'
                                                role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;' width='100%'>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <table align='center' border='0' cellpadding='0' cellspacing='0'
                                                                class='row-content stack' role='presentation'
                                                                style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 800.00px; margin: 0 auto;'
                                                                width='800.00'>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class='column column-1'
                                                                            style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; padding-left: 60px; padding-right: 60px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'
                                                                            width='100%'>
                                                                            <table border='0' cellpadding='0' cellspacing='0'
                                                                                class='paragraph_block block-1' role='presentation'
                                                                                style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'
                                                                                width='100%'>
                                                                                <tr>
                                                                                    <td class='pad'
                                                                                        style='padding-bottom:10px;padding-left:10px;padding-right:10px;padding-top:15px;'>
                                                                                        <div
                                                                                            style='color:#726f70;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:180%;text-align:center;mso-line-height-alt:25.2px;'>
                                                                                            <p style='margin: 0;'>Este correo electrónico fue
                                                                                                enviado por:<br />WD Studios Corporation SAS.
                                                                                            </p>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <table border='0' cellpadding='10' cellspacing='0'
                                                                                class='paragraph_block block-2' role='presentation'
                                                                                style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'
                                                                                width='100%'>
                                                                                <tr>
                                                                                    <td class='pad'>
                                                                                        <div
                                                                                            style='color:#726f70;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:150%;text-align:center;mso-line-height-alt:21px;'>
                                                                                            <p style='margin: 0;'>Este mensaje ha sido
                                                                                                verificado con herramientas de eliminación de
                                                                                                virus y contenido malicioso. Si tiene
                                                                                                algún inconveniente con la información recibida
                                                                                                comuníquese con nosotros vía telefónica o
                                                                                                respondamos este mismo correo. Recomendamos
                                                                                                agregarnos a sus contactos para mantener una
                                                                                                comunicación más efectiva.</p>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <table border='0' cellpadding='10' cellspacing='0'
                                                                                class='divider_block block-3' role='presentation'
                                                                                style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'
                                                                                width='100%'>
                                                                                <tr>
                                                                                    <td class='pad'>
                                                                                        <div align='center' class='alignment'>
                                                                                            <table border='0' cellpadding='0' cellspacing='0'
                                                                                                role='presentation'
                                                                                                style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'
                                                                                                width='85%'>
                                                                                                <tr>
                                                                                                    <td class='divider_inner'
                                                                                                        style='font-size: 1px; line-height: 1px; border-top: 2px solid #979CA2;'>
                                                                                                        <span> </span></td>
                                                                                                </tr>
                                                                                            </table>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table align='center' border='0' cellpadding='0' cellspacing='0' class='row row-7'
                                                role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;' width='100%'>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <table align='center' border='0' cellpadding='0' cellspacing='0'
                                                                class='row-content stack' role='presentation'
                                                                style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 800.00px; margin: 0 auto;'
                                                                width='800.00'>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class='column column-1'
                                                                            style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; padding-top: 10px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'
                                                                            width='50%'>
                                                                            <table border='0' cellpadding='0' cellspacing='0'
                                                                                class='paragraph_block block-1' role='presentation'
                                                                                style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'
                                                                                width='100%'>
                                                                                <tr>
                                                                                    <td class='pad'
                                                                                        style='padding-bottom:10px;padding-left:10px;padding-right:15px;padding-top:10px;'>
                                                                                        <div
                                                                                            style='color:#726f70;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:150%;text-align:right;mso-line-height-alt:21px;'>
                                                                                            <p style='margin: 0;'><a
                                                                                                    href='https://www.buscoinfluencers.com/aviso-de-privacidad-2/'
                                                                                                    rel='noopener'
                                                                                                    style='text-decoration: none; color: #726f70;'
                                                                                                    target='_blank'>Política de privacidad.</a>
                                                                                            </p>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                        <td class='column column-2'
                                                                            style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; padding-top: 10px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'
                                                                            width='50%'>
                                                                            <table border='0' cellpadding='0' cellspacing='0'
                                                                                class='paragraph_block block-1' role='presentation'
                                                                                style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'
                                                                                width='100%'>
                                                                                <tr>
                                                                                    <td class='pad'
                                                                                        style='padding-bottom:10px;padding-left:15px;padding-right:10px;padding-top:10px;'>
                                                                                        <div
                                                                                            style='color:#726f70;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:150%;text-align:left;mso-line-height-alt:21px;'>
                                                                                            <p style='margin: 0;'><a
                                                                                                    href='https://www.buscoinfluencers.com/terminos-y-condiciones-2/'
                                                                                                    rel='noopener'
                                                                                                    style='text-decoration: none; color: #726f70;'
                                                                                                    target='_blank'>Términos y condiciones.</a>
                                                                                            </p>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table align='center' border='0' cellpadding='0' cellspacing='0' class='row row-8'
                                                role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;' width='100%'>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <table align='center' border='0' cellpadding='0' cellspacing='0'
                                                                class='row-content stack' role='presentation'
                                                                style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 800.00px; margin: 0 auto;'
                                                                width='800.00'>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class='column column-1'
                                                                            style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; padding-bottom: 40px; padding-top: 15px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'
                                                                            width='100%'>
                                                                            <table border='0' cellpadding='0' cellspacing='0'
                                                                                class='image_block block-1' role='presentation'
                                                                                style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'
                                                                                width='100%'>
                                                                                <tr>
                                                                                    <td class='pad' style='width:100%;'>
                                                                                        <div align='center' class='alignment'
                                                                                            style='line-height:10px'>
                                                                                            <div style='max-width: 57px;'><img
                                                                                                    src='".base_url()."/public/img/verificacion/wd-studios.png'
                                                                                                    style='display: block; height: auto; border: 0; width: 100%;'
                                                                                                    width='57' /></div>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table><!-- End -->
                        </body>
                        
                        </html>";

            $asunto="Buscoinfluencers | ¡Recibiste una reseña! 🤩";
            $this->_enviarCorreo($miInfluencer['correo'],$cuerpo,$asunto);

                        return redirect()->to(base_url("/dashboard/comentarios"))->with('mensaje', 'El comentario se aprobó');
                    }else{
                        return redirect()->to(base_url("/dashboard/comentarios"))->with('mensaje', 'ocurrio un error al tratar de aprobar el comentario');
                    }
                

            }
        }
    }

    private function _enviarCorreo($correo,$cuerp,$asunto){
    
        $cuerpo=$cuerp;

        $email = \Config\Services::email();

        $email->setFrom("noreply@buscoinfluencers.com", 'Busco Influencers');
        $email->setTo($correo);
    

        $email->setSubject($asunto);
        
        $email->setMessage($cuerpo);

        if ($email->send())
        {
            var_dump('Email enviado correctamente');
            
        }
        else
        {
            var_dump('Email No enviado<br />'. $email->printDebugger(['headers']));
            
        }
   
    
    }



    public function eliminarComentarios()
    {
        $miAdministrador = new AdministradorModel();
        $miAdmin=$miAdministrador->find(145758);

        if(session()->get('idadmin')==$miAdmin['idadministrador']){
            if ((time() - session()->get('timeadmin')) > 3600){
                session_destroy();
                return redirect()->to(base_url()."/admin")->with('mensaje', 'La sesion se cerró por tu seguridad');
            }else{
                
                $mensajesModel=new MensajesModel();
                $idcomentario= $this->request->getPost('eliminarcomentariomodal');
                
               // echo $id;
                    if($mensajesModel->delete($idcomentario)){
                        return redirect()->to(base_url("/dashboard/comentarios"))->with('mensaje', 'El comentario se eliminó correctamente');
                    }else{
                        return redirect()->to(base_url("/dashboard/comentarios"))->with('mensaje', 'ocurrio un error al tratar de eliminar el comentario');
                    }
                

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
                $archivofoto="categoria.jpg";
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

                
                //var_dump($data['noticiaAEditar']);
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
                $descripcion = $this->request->getPost('descripcionNoticia');
                $favorito=0;
                if(isset($_POST["esfavorito2"])){
                    $favorito= 1;
                }
                $cuerpo= $this->request->getPost('summernote');
        
                $noticiamodel=new NoticiasModel();
               

                $data=[
                    'titulo'=>$titulo,
                    'descripcion'=>$descripcion,
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
        $descripcion= $this->request->getPost('descripcionNoticia');
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
                'descripcion'=> $descripcion,
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