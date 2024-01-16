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
use App\Models\MensajeAdministradoresModel;

class InfluencerController extends BaseController
{
    
    //CARGA LA PAGINA DEL PERFIL DEL INFLUENCER
    public function index()
    {
        //$this->logout();
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


    //CARGA LA PAGINA DE REGISTRO EXITOSO
    public function registroExitoso()
    {
        $dataHeader =['titulo' => 'Bienvenido!',
                'mensaje'=>"",];
        echo view("influencer/templates/header",$dataHeader);
        echo view("influencer/influencers/registro-exitoso");
        echo view("influencer/templates/footerindex");

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
        $validation =  \Config\Services::validation();
       
        //SE CREAN LAS REGLAS DE VALIDACION PARA LOS CAMPOS
     

        $validation->setRules(
            [
                'nombre'=>'required|min_length[4]|max_length[20]',
                'alias'=>'required|min_length[2]|max_length[20]|is_unique[influencers.alias]',
                'password'=>'required|min_length[8]|matches[passwordver]',
                'correo'=>'required|valid_email|is_unique[influencers.correo]',
                'pais'=>'is_not_unique[paises.idpais]',
                'ciudades'=>'required',
                'resenia'=>'required|min_length[10]|max_length[5000]',
            ],
            [   // Errors
                'nombre' => [
                    'required' => 'El nombre es requerido',
                    'min_length' => 'El nombre debe tener como mínimo 4 caracteres',
                    'max_length' => 'El nombre NO puede tener mas de 20 caracteres',
                ],
                'alias' => [
                    'required' => 'El nombre de usuario es requerido',
                    'min_length' => 'Tu nombre de usuario debe tener como mínimo 4 caracteres',
                    'max_length' => 'Tu nombre de usuario NO puede tener mas de 20 caracteres',
                    'is_unique'=>' Tu nombre de usuario no esta disponible',
                ],'password' => [
                    'required' => 'La contraseña es requerido',
                    'min_length' => 'La contraseña debe tener como mínimo 8 caracteres',
                    'matches'=>'las contraseñas No coinciden'
                ], 
                'correo' => [
                    'required' => 'El correo es requerido',
                    'valid_email' => 'El correo no tiene el formato de un correo',
                    'is_unique'=>'El correo ya esta registrado',

                ],
                'pais' => [
                    'is_not_unique' => 'Debes seleccionar un pais',
                    
                ],
                'ciudades' => [
                    'required' => 'La ciudad es requerida',
                    
                ],
                'resenia' => [
                    'required' => 'La reseña es requerida',
                    'min_length' => 'La reseña debe tener como mínimo 10 caracteres',
                ],
            ]
        );

       
        //SI SE VALIDAN LAS REGLAS
        if(!$validation->withRequest($this->request)->run()){
            session();
            return redirect()->back()->withinput()->with('errors',$validation->getErrors());

        }else{
            $imagefile = $this->request->getFiles();

            $password= $this->request->getPost('password');
            $passwordotro= $this->request->getPost('passwordver');

                // Opciones de contraseña:
                $newpass= password_hash($password, PASSWORD_DEFAULT);

                $nombreinflu= $this->request->getPost('nombre');
                $alias= $this->request->getPost('alias');
                
                $ciudad= $this->request->getPost('ciudades');
                $correo= $this->request->getPost('correo');
                $resenia= $this->request->getPost('resenia');
                $archivofoto=$this->_upload2('fotoperfil');

                if (!isset($archivofoto)) {
                    $archivofoto='default.png';
                }

                
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
                //Add user to mailchimp
                // let's start with some variables
                $api_key = '2e164634dba741bbe097664501727be9-us9';
                $email = $correo; // the user we are going to subscribe
                $status = 'subscribed'; // we are going to talk about it in just a little bit
                $merge_fields = array( 'FNAME' => $nombreinflu ); // FNAME, LNAME or something else
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
                        //'tags' => array( 'Coffee', 'Snowboard' ) // you can specify some tags here as well
                    ) )
                );
                
                $result = curl_exec( $connection );

                //SE CREA EL INFLUENCER
                $id=$influencerModel->insert($datainsertar);
                if($id>0){
                

                        $cuerpo = "<!DOCTYPE html>
                        <html xmlns:v='urn:schemas-microsoft-com:vml' xmlns:o='urn:schemas-microsoft-com:office:office' lang='en'>
                        
                        <head>
                            <title></title>
                            <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
                            <meta name='viewport' content='width=device-width, initial-scale=1.0'><!--[if mso]><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch><o:AllowPNG/></o:OfficeDocumentSettings></xml><![endif]--><!--[if !mso]><!-->
                            <link href='https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900' rel='stylesheet' type='text/css'><!--<![endif]-->
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

                                    .titulo {
                                        text-align: center !important;
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
                        
                                    .row-2 .column-1 .block-1.heading_block h1 {
                                        font-size: 22px !important;
                                    }
                        
                                    .row-2 .column-1 .block-4.paragraph_block td.pad>div {
                                        text-align: left !important;
                                        font-size: 15px !important;
                                    }
                        
                                    .row-2 .column-1 .block-3.heading_block h2 {
                                        font-size: 19px !important;
                                    }
                        
                                    .row-5 .column-1 .block-1.paragraph_block td.pad>div,
                                    .row-5 .column-2 .block-1.paragraph_block td.pad>div {
                                        text-align: center !important;
                                    }
                        
                                    .row-2 .column-1 {
                                        padding: 20px 5px 15px !important;
                                    }
                        
                                    .row-4 .column-1 {
                                        padding: 0 5px !important;
                                    }
                                }
                            </style>
                        </head>
                        
                        <body id='body' style='background-color: #f9f9f9; margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;'>
                            <table class='nl-container' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f9f9f9;'>
                                <tbody>
                                    <tr>
                                        <td>
                                            <table class='row row-1' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 800.00px; margin: 0 auto;' width='800.00'>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class='column column-1' width='100%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; padding-top: 25px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                                                                            <table class='image_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                                <tr>
                                                                                    <td class='pad' style='width:100%;padding-right:0px;padding-left:0px;'>
                                                                                        <div class='alignment' align='center' style='line-height:10px'>
                                                                                            <div style='max-width: 140px;'><a href='https://buscoinfluencers.com/' target='_blank' style='outline:none' tabindex='-1'><img src='".base_url()."/public/img/verificacion/logo-binf.png' style='display: block; height: auto; border: 0; width: 100%;' width='140' alt='Logo Binf' title='Logo Binf'></a></div>
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
                                            <table class='row row-2' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 800.00px; margin: 0 auto;' width='800.00'>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class='column column-1' width='100%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; padding-bottom: 15px; padding-left: 60px; padding-right: 60px; padding-top: 20px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                                                                            <table class='heading_block block-1' width='100%' border='0' cellpadding='10' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                                <tr>
                                                                                    <td class='pad'>
                                                                                        <h1 class='titulo' style='text-align: center; margin: 0; color: #000000; direction: ltr; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; font-size: 38px; font-weight: 900; letter-spacing: normal; line-height: 120%; text-align: center; margin-top: 0; margin-bottom: 0; mso-line-height-alt: 45.6px;'><em><span class='tinyMce-placeholder'><strong>Verifica tu registro</strong></span></em></h1>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <table class='divider_block block-2' width='100%' border='0' cellpadding='10' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                                <tr>
                                                                                    <td class='pad'>
                                                                                        <div class='alignment' align='center'>
                                                                                            <table border='0' cellpadding='0' cellspacing='0' role='presentation' width='80%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                                                <tr>
                                                                                                    <td class='divider_inner' style='font-size: 1px; line-height: 1px; border-top: 5px solid #000000;'><span>&#8202;</span></td>
                                                                                                </tr>
                                                                                            </table>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <table class='heading_block block-3' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                                <tr>
                                                                                    <td class='pad' style='padding-bottom:15px;padding-left:10px;padding-right:10px;padding-top:10px;text-align:center;width:100%;'>
                                                                                        <h2 style='margin: 0; color: #000000; direction: ltr; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; font-size: 24px; font-weight: 800; letter-spacing: normal; line-height: 120%; text-align: center; margin-top: 0; margin-bottom: 0; mso-line-height-alt: 28.799999999999997px;'><span class='tinyMce-placeholder'>Bienvenida/o a Busco Influencers</span></h2>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <table class='paragraph_block block-4' width='100%' border='0' cellpadding='10' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                                                                                <tr>
                                                                                    <td class='pad'>
                                                                                        <div style='text-align: center; color:#726f70;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:16px;font-weight:400;letter-spacing:0px;line-height:180%;text-align:center;mso-line-height-alt:28.8px;'>
                                                                                            <p style='margin: 0; margin-bottom: 15px;'>Te damos la bienvenida a un nuevo sitio creado para darte a conocer en el<br>universo digital. Con&nbsp;<span style='color: #010100;'><strong>BuscoInfluencers.com</strong></span>&nbsp;tienes una gran oportunidad de estar<br>en un directorio cada vez más completo de creadores de contenido en todas las<br>categorías. Más de una vez habrás escuchado a alguien preguntar por algún<br>influencer para cierto proyecto o producto; pues bien, en&nbsp;<span style='color: #010100;'><strong>buscoinfluencers.com</strong></span><br>todos tendrán esa respuesta sin buscar tanto.</p>
                                                                                            <p style='margin: 0;'>Para continuar necesitas verificar tu cuenta en nuestra plataforma. Haz clic en el siguiente botón para confirmar tu correo electrónico:</p>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <div class='spacer_block block-5' style='height:25px;line-height:25px;font-size:1px;'>&#8202;</div>
                                                                            <table class='image_block block-6' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                                <tr>
                                                                                    <td class='pad' style='width:100%;'>
                                                                                        <div class='alignment' align='center' style='line-height:10px'>
                                                                                            <div style='max-width: 202px;'><a href='".base_url()."/validarCorreo/".$tokens."/".$id."' target='_blank' style='outline:none' tabindex='-1'><img src='".base_url()."/public/img/verificacion/btn-verificar-cuenta.png' style='display: block; height: auto; border: 0; width: 100%;' width='202' alt='Verifica tu cuenta' title='Verifica tu cuenta'></a></div>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <div class='spacer_block block-7' style='height:45px;line-height:45px;font-size:1px;'>&#8202;</div>
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
                                                            <table class='row-content' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 800.00px; margin: 0 auto;' width='800.00'>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class='column column-1' width='46%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                                                                            <table class='image_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                                <tr>
                                                                                    <td class='pad' style='padding-right:10px;width:100%;'>
                                                                                        <div class='alignment' align='right' style='line-height:10px'>
                                                                                            <div style='max-width: 27px;'><a href='https://www.instagram.com/buscoinfluencers/' target='_blank' style='outline:none' tabindex='-1'><img src='".base_url()."/public/img/verificacion/binf-ig.png' style='display: block; height: auto; border: 0; width: 100%;' width='27' alt='Binf - Instagram' title='Binf - Instagram'></a></div>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                        <td class='column column-1' width='8%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                                                                            <table class='image_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                                <tr>
                                                                                    <td class='pad' style='width:100%;'>
                                                                                        <div class='alignment' align='center' style='line-height:10px'>
                                                                                            <div style='max-width: 27px;'><a href='https://wa.link/ipree3' target='_blank' style='outline:none' tabindex='-1'><img src='<?=base_url('public/img/verificacion/binf-wpp.png')?>' style='display: block; height: auto; border: 0; width: 100%;' width='27' alt='Binf - WhatsApp' title='Binf - WhatsApp'></a></div>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                        <td class='column column-2' width='46%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                                                                            <table class='image_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                                <tr>
                                                                                    <td class='pad' style='padding-left:10px;width:100%;'>
                                                                                        <div class='alignment' align='left' style='line-height:10px'>
                                                                                            <div style='max-width: 27px;'><a href='https://www.tiktok.com/@binfluencers' target='_blank' style='outline:none' tabindex='-1'><img src='".base_url()."/public/img/verificacion/binf-tik-tok.png' style='display: block; height: auto; border: 0; width: 100%;' width='27' alt='Binf - TikTok' title='Binf - TikTok'></a></div>
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
                                            <table class='row row-4' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 800.00px; margin: 0 auto;' width='800.00'>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class='column column-1' width='100%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; padding-left: 60px; padding-right: 60px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                                                                            <table class='paragraph_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                                                                                <tr>
                                                                                    <td class='pad' style='padding-bottom:10px;padding-left:10px;padding-right:10px;padding-top:15px;'>
                                                                                        <div style='text-align: center; color:#726f70;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:180%;text-align:center;mso-line-height-alt:25.2px;'>
                                                                                            <p style='margin: 0;'>Este correo electrónico fue enviado por:<br>WD Studios Corporation SAS. Cra 101 # 12A bis-70, Cali, Colombia.<br>Móvil: (318) 619-7481</p>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <table class='paragraph_block block-2' width='100%' border='0' cellpadding='10' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                                                                                <tr>
                                                                                    <td class='pad'>
                                                                                        <div style='text-align: center; color:#726f70;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:150%;text-align:center;mso-line-height-alt:21px;'>
                                                                                            <p style='margin: 0;'>Este mensaje ha sido verificado con herramientas de eliminación de virus y contenido malicioso. Si tiene algún&nbsp;inconveniente con la información recibida comuníquese con nosotros vía telefónica o respondamos este mismo correo. Recomendamos agregarnos a sus contactos para mantener una comunicación más efectiva.</p>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <table class='divider_block block-3' width='100%' border='0' cellpadding='10' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                                <tr>
                                                                                    <td class='pad'>
                                                                                        <div class='alignment' align='center'>
                                                                                            <table border='0' cellpadding='0' cellspacing='0' role='presentation' width='85%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                                                <tr>
                                                                                                    <td class='divider_inner' style='font-size: 1px; line-height: 1px; border-top: 2px solid #979CA2;'><span>&#8202;</span></td>
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
                                            <table class='row row-5' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 800.00px; margin: 0 auto;' width='800.00'>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class='column column-1' width='50%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; padding-top: 10px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                                                                            <table class='paragraph_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                                                                                <tr>
                                                                                    <td class='pad' style='padding-bottom:10px;padding-left:10px;padding-right:15px;padding-top:10px;'>
                                                                                        <div style='text-align: right; color:#726f70;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:150%;text-align:right;mso-line-height-alt:21px;'>
                                                                                            <p style='margin: 0;'><a href='https://www.buscoinfluencers.com/aviso-de-privacidad-2/' target='_blank' style='text-decoration: none; color: #726f70;' rel='noopener'>Política de privacidad.</a></p>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                        <td class='column column-2' width='50%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; padding-top: 10px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                                                                            <table class='paragraph_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                                                                                <tr>
                                                                                    <td class='pad' style='padding-bottom:10px;padding-left:15px;padding-right:10px;padding-top:10px;'>
                                                                                        <div style='color:#726f70;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:150%;text-align:left;mso-line-height-alt:21px;'>
                                                                                            <p style='margin: 0;'><a href='https://www.buscoinfluencers.com/terminos-y-condiciones-2/' target='_blank' style='text-decoration: none; color: #726f70;' rel='noopener'>Términos y condiciones.</a></p>
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
                                            <table class='row row-6' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 800.00px; margin: 0 auto;' width='800.00'>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class='column column-1' width='100%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; padding-bottom: 40px; padding-top: 15px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                                                                            <table class='image_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                                <tr>
                                                                                    <td class='pad' style='width:100%;'>
                                                                                        <div class='alignment' align='center' style='line-height:10px'>
                                                                                            <div style='max-width: 57px;'><img src='".base_url()."/public/img/verificacion/wd-studios.png' style='display: block; height: auto; border: 0; width: 100%;' width='57'></div>
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
                        $asunto="Buscoinfluencers | Activa tu cuenta 👏";
                        $this->_enviarCorreo($correo,$cuerpo,$asunto);
                        
                        //session()->set('idinfluencer',$id);
                        return redirect()->to('registro-exitoso');
                        //return redirect()->to('/')->with('mensaje', 'Tu cuenta se creo con exito, antes de ingresar debes validarla desde tu correo electrónico');
                }else{
                    return redirect()->back()->withinput()->with('mensaje','Ocurrio un error al crear su cuenta');

                }
           
            
        }
        
   
    }
   
   
    public function validarCorreo($token=null,$id=null){
      
    $influencer = new InfluencerModel();
    $inf=$influencer->find($id);
    if($inf!=null){
        if($inf['tokens']==$token){
    
            $datos=['tokens'=>"",
                    'validado'=>1];
                    $influencer->update($id,$datos);
        
                    return redirect()->to("/")->with('mensaje', 'Correo validado');
        
          }else{
            return redirect()->to("/")->with('mensaje', 'Token invalido');
        
          }
    }
    return redirect()->to("/")->with('mensaje', 'Usuario no encontrado');
  

}

// ENVIAR MENSAJE CONTACTANOS
public function enviarMensajeContactanos(){

    $correoAdministradorModel= new MensajeAdministradoresModel();
    
  
    $validation =  \Config\Services::validation();
       
    //SE CREAN LAS REGLAS DE VALIDACION PARA LOS CAMPOS
 

    $validation->setRules(
        [
            'nombrecontacto'=>'required|min_length[4]|max_length[20]',
            'correocontacto'=>'required|valid_email',
            'cuerpocontacto'=>'required|min_length[10]|max_length[5000]',
        ],
        [   // Errors
            'nombrecontacto' => [
                'required' => 'El nombre es requerido',
                'min_length' => 'El nombre debe tener como mínimo 4 caracteres',
                'max_length' => 'El nombre NO puede tener mas de 20 caracteres',
            ],
            'correocontacto' => [
                'required' => 'El email es requerido',
                'valid_email' => 'El email no tiene el formato de un correo',
            ],
            'cuerpocontacto' => [
                'required' => 'El cuerpo del correo es requerido',
                'min_length' => 'El cuerpo del correo debe tener como mínimo 10 caracteres',
                'max_length' => 'El cuerpo del correo NO puede tener mas de 5000 caracteres',
            ],
        ]
    );

   
    //SI SE VALIDAN LAS REGLAS
    if(!$validation->withRequest($this->request)->run()){
        session();
        return redirect()->back()->withinput()->with('errors',$validation->getErrors());

    }else{

        $nombre=$this->request->getPost('nombrecontacto');
        $correo=$this->request->getPost('correocontacto');
        $cuerpo=$this->request->getPost('cuerpocontacto');

        $data=['nombre'=>$nombre,'correo'=>$correo,'cuerpo'=>$cuerpo,'leido'=>0];

        if($correoAdministradorModel->insert($data)){
            return redirect()->to("/")->with('mensaje', 'Su mensaje se envió correctamente');
        }

        return redirect()->back()->withinput()->with('mensaje',"Ocurrio un error inesperado, No se pudo enviar su mensaje.");

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


        return redirect()->to("/perfil/$id")->with('mensaje', 'Tu cuenta se creo con éxito');

    
       
        

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

        if ($influencer->where('alias', $id)->findAll() != null)
        {
            $inf=$influencer->where('alias', $id)->first();
            $id=$inf['idinfluencer'];
            
            $ciu=$ciudad->find($inf['idciudad']);
            $pai=$pais->find($ciu['idpais']);

            $idcateg=$influcatefori->where('idinfluencer', $id)->findAll();
            $idmarca=$marca->where('idinfluencer', $id)->findAll();
            $idpago=$influpago->where('idinfluencer', $id)->findAll();
            $idredes=$influredes->where('idinfluencer', $id)->findAll();
            $ididioma= $influidioma->where('idinfluencer', $id)->findAll();

             //ACATUALIZAR REDES SOCIALES
            /*
            $misInfluRedes=$influredes->where("idinfluencer",$id);
            foreach ($misInfluRedes as $key => $m) {
                $this->_actualizarRedesSociales($id,$m['idredes']);
            }
            */
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
    
            session()->set('foto', $inf['foto_perfil']);
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
             return redirect()->to('/influencer')->with('mensaje', 'Tu cuenta se elimino correctamente');
    }


    public function update($id=null)
    {

        

        return redirect()->to('/influencer')->with('mensaje', 'Tu cuenta se actualizó correctamente');

    
        
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

    public function cambiarFoto() {
        
        if(isset($_POST["image"]))
        {
            $data = $_POST["image"];
            $id = $_POST["id"];

            //Procesamos la imagen y la guardamos
            $image_array_1 = explode(";", $data);
            $image_array_2 = explode(",", $image_array_1[1]);

            $data = base64_decode($image_array_2[1]);
            $imageName = "perfil_".time() . $id;

            $imagePath = ROOTPATH.'public/uploads/' . $imageName . '.png';
            file_put_contents($imagePath, $data);

            
            $influencer=new InfluencerModel();

            // borramos la foto anterior de perfil en el directorio de uploads
            $user=$influencer->find($id);
            if($user['foto_perfil']!="default.png"){
                unlink(ROOTPATH.'public/uploads/'.$user['foto_perfil']);
            }
            // actualizamos la bd con el nombre de la nueva foto de perfil
            $influencer->update($id,['foto_perfil'=>$imageName.'.png']);
            
            
            echo base_url('uploads')."/".$imageName.'.png';      

        }
       
        
    }

    /*
    public function cambiarFoto(){
        
        
        var_dump("LLEGUE ");
        $influencer=new InfluencerModel();
        $id= $this->request->getPost('picIdd');
        

        
        if($imagefile = $this->request->getFile('newfotoo')){
            
            if ($imagefile->isValid() && ! $imagefile->hasMoved())
            {
               
                    $newName = $imagefile->getRandomName();
                    $imagefile->move(ROOTPATH.'public/uploads', $newName);

                    $user=$influencer->find($id);
                    unlink(ROOTPATH.'public/uploads/'.$user['foto_perfil']);

                    $influencer->update($id,['foto_perfil'=>$newName]);
                    return redirect()->to("/influencer/edit/$id")->with('mensaje', 'Tu foto se actualizó con exito');


            }
        }else{
            
            return redirect()->to("/influencer/edit/$id")->with('mensaje', 'ocurrió un error al actualizar tu foto');

        }

        
    }
    */
   

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
        $influencer = new InfluencerModel();
        $id=$this->request->getPost('influencerid2');
        $miInflue=$influencer->find($id);        

        if($redinfluencer->where('id', $ide)->delete()!=null){

            return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Tus red se elimino con éxito con exito');
            
        }else{
            return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Ocurrió un error al eliminiar tu red social');

        }
           
    }

    public function actualizarPerfil(){

        $influencer=new InfluencerModel();
        $nombre=$this->request->getPost('nombredit');
        $alias=$this->request->getPost('aliasedit');
        $usuario=$this->request->getPost('usuarioedit');
        
        $id=$this->request->getPost('influencerid3');
        $miInflue=$influencer->find($id);

        $data=[
            'nombreinflu'=>$nombre,
            //'alias'=>$alias,
            'usuario'=>$usuario
        ];
        
        if($influencer->update($id,$data)!=null){

            return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Tus datos se actualizaron con éxito');
            
        }
    
        return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Ocurrió un error al actualizar tus datos');

           
    }

    public function elminarCategoria(){

        $influencer=new InfluencerModel();
        $categoriaInfluencer=new InfluencerCategoriaModel();
        $ide=$this->request->getPost('categoriaeliminar');
        
        $id=$this->request->getPost('influencerid3');
        $miInflue=$influencer->find($id);
        //echo $ide;
        //echo $id;

        if($categoriaInfluencer->where('id', $ide)->delete()!=null){
           return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Tu categoria se elimino con éxito con éxito');
            
        }
    
        return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Ocurrió un error al eliminiar tu categoria');
          
    }
    
    public function adicionarCategoria(){

        $categoriaInfluencer=new InfluencerCategoriaModel();
        $influencer = new InfluencerModel();
        
        $id= $this->request->getPost('influencerid4');
        $miInflue=$influencer->find($id);


        $idcatagoria= $this->request->getPost('categorianew');

        if($idcatagoria!=null){
        
            if($categoriaInfluencer->insert(['idinfluencer'=>$id,'idcategoria'=>$idcatagoria])!=null){

                return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Tu categoria se actualizó con éxito');
    
            }
        }
    
        return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Ocurrió un error al actualizar tu categoria');
    }

    public function eliminarLenguaje(){

        $influencer = new InfluencerModel();
        $lenguajeInfluencer=new IdiomaInfluencerModel();

        $ide=$this->request->getPost('idiomaeliminar');
        
        $id=$this->request->getPost('influencerid5');
        $miInflue=$influencer->find($id);

        if($lenguajeInfluencer->where('id', $ide)->delete()!=null){
            return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Tu idioma se eliminó con éxito');
            
        }
    
        return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Ocurrió un error al eliminiar tu idioma');

           
    }

    public function adicionarIdioma(){

        $idiomaInfluencer=new IdiomaInfluencerModel();
        $influencer = new InfluencerModel();
        
        $id= $this->request->getPost('influencerid6');
        $miInflue=$influencer->find($id);


        $ididioma= $this->request->getPost('idiomanew');

        if($ididioma!=null){
        
            if($idiomaInfluencer->insert(['idinfluencer'=>$id,'ididioma'=>$ididioma])!=null){

                return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Tu idioma se actualizó con éxito');
    
            }
        }
    
        return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Ocurrió un error al actualizar tu idioma');
    }

    public function eliminarVideo(){

        $influencer=new InfluencerModel();
        $id= $this->request->getPost('influencerid7');
        $miInflue=$influencer->find($id);
        /*
        $user=$influencer->find($id);
        unlink(ROOTPATH.'public/uploads/'.$user['video']);
        */

        if($influencer->update($id,['video'=>null])!=null){
            return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Tu video se eliminó con éxito');
        }else{
            return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Ocurrió un error al eliminar tu video');
        }
    
    }

    public function cambiarVideo(){

        $influencer=new InfluencerModel();
        $id= $this->request->getPost('influencer9');
        $miInflue=$influencer->find($id);


        if($imagefile = $this->request->getFile('newvideo')){
            
            if ($imagefile->isValid() && ! $imagefile->hasMoved())
            {
                $newName = $imagefile->getRandomName();
                $imagefile->move(ROOTPATH.'public/video', $newName);
                $influencer->update($id,['video'=>$newName]);
                return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Tu video se actualizó con éxito');
            }
        }else{
            
            return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Ocurrió un error al actualizar tu video');

        }
     
    }

    
    public function eliminarFotoGaleria(){

        $galeria=new GaleriaModel();
        $influencer=new InfluencerModel();
        $id= $this->request->getPost('influencerid10');
        $idgaleria= $this->request->getPost('fotoGaeliminar');
        $miInflue=$influencer->find($id);


        $galName=$galeria->find($idgaleria);
        unlink(ROOTPATH.'public/uploads/'.$galName['url']);
        

        if($galeria->where('idfoto', $idgaleria)->delete()!=null){
            return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Tu foto se elimino con éxito');
        }else{
            
            return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Ocurrió un error al eliminar tu foto');

        }

        
    }

    public function agregarFotoGaleria(){

        $galeria=new GaleriaModel();
        $influencer = new InfluencerModel();

        $id= $this->request->getPost('influencer11');
        $miInflue=$influencer->find($id);

        $fotos=$galeria->where('idinfluencer',$id)->findAll();
        $cantidadFotos=count($fotos);
        $contador=0;

        $imagefile = $this->request->getFiles();

        
        
        if (($imagefile = $this->request->getFiles()) && (count($imagefile['newfotoGaleria'])<=5)) {
            
            
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
                            unlink(ROOTPATH.'public/uploads/'.$fotos[$contador]['url']);
                            //var_dump($fotos[$contador]['idfoto']);
                            $galeria->update($fotos[$contador]['idfoto'],['url'=>$newName]);
                            $contador++;
                        }
                    }
                } 
               
                return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Tu fotos se agregaron con exito');

        }else{
            
            return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Solo se permite subir máximo 5 imagenes');

        }

        
    }

    public function editarResenia(){

        $influencer=new InfluencerModel();
        $resenia=$this->request->getPost('reseniaInfluencer');
        $id=$this->request->getPost('influencerid12');
        $miInflue=$influencer->find($id);


        $data=[
            'resenia'=>$resenia
        ];

        if($influencer->update($id,$data)!=null){

            return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Tus reseña se actualizó con éxito');
            
        }
    
        return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Ocurrió un error al actualizar tu reseña');
    }

    public function eliminarMarcas(){

        $marca=new MarcaModel();
        $influencer=new InfluencerModel();

        $ide=$this->request->getPost('marcaeliminada');
        
        $id=$this->request->getPost('influencerid13');
        $miInflue=$influencer->find($id);


        if($marca->where('idmarca', $ide)->delete()!=null){
            return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Tu marca se eliminó con exito');
            
        }
    
        return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Ocurrió un error al eliminiar tu marca');

           
    }

    public function eliminarPagos(){

        $influencer=new InfluencerModel();
        $pagoInfluencer=new InfluencerPagoModel();

        $ide=$this->request->getPost('pagoeliminada');
        
        $id=$this->request->getPost('influencerid15');
        $miInflue=$influencer->find($id);


        if($pagoInfluencer->where('id', $ide)->delete()!=null){
            return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Tu método de pago se eliminó con exito');
            
        }
    
        return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Ocurrió un error al eliminiar tu método de pago');
   
    }

    public function adicionarEmpresa(){

        $influencer=new InfluencerModel();
        $marca=new MarcaModel();
        
        $id= $this->request->getPost('influencerid16');
        $tipo= $this->request->getPost('tipoempres');
        $marcaText= $this->request->getPost('empresanewtxt');
        $miInflue=$influencer->find($id);

        
        if(!($marcaText==null || $marcaText=="")){
            
            $marca->insert(['nombre'=>$marcaText,'idinfluencer'=>$id, 'tipo'=>$tipo]);
            return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Tu marca / empresa se actualizó con éxito');
    
            
        }
    
    return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Ocurrió un error al actualizar tu marca / empresa');
}

    public function adicionarPago(){

        $influencer=new InfluencerModel();
        $pagosInfluencer=new InfluencerPagoModel();
        
        $id= $this->request->getPost('influencerid17');
        $miInflue=$influencer->find($id);

        $idpago= $this->request->getPost('pagonew');

        if($idpago!=null){
        
            if($pagosInfluencer->insert(['idinfluencer'=>$id,'idpago'=>$idpago])!=null){

                return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Tu método de pago se actualizó con éxito');
    
            }
        }
        return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'ocurrió un error al actualizar tu método de pago');
    }

    public function editarOferta(){

        $influencer=new InfluencerModel();

        $oferta=$this->request->getPost('promocion');
        $id=$this->request->getPost('influencerid19');
        $miInflue=$influencer->find($id);

        $data=[
            'oferta'=>$oferta
        ];

        if($influencer->update($id,$data)!=null){

            return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Tus oferta se actualizó con éxito');
            
        }
    
        return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Ocurrió un error al actualizar tu oferta');
    }



    public function eliminarMiCuenta()
    {
        session()->destroy();
        
        $idInfluencer= $this->request->getPost('eliminarinfluencermodal');
        
        $miInflu=new InfluencerModel();

        $miInflu->delete($idInfluencer);
        
        return redirect()->to("/cuenta-eliminada")->with('mensaje', 'Se ha eliminado tu cuenta !');
        
    }


    function cuenta_eliminada() {
        $dataHeader =['titulo' => 'Cuenta eliminada',
                'mensaje'=>"",];

        echo view("influencer/templates/header",$dataHeader);
        echo view("influencer/influencers/cuenta-eliminada");
        echo view("influencer/templates/footerindex");
    }



    function _validar_clave($clave,&$error_clave){
        if(strlen($clave) < 6){
           $error_clave = "La contraseña debe tener al menos 6 caracteres";
           return false;
        }
        if(strlen($clave) > 16){
           $error_clave = "La contraseña no puede tener más de 16 caracteres";
           return false;
        }
        if (!preg_match('`[a-z]`',$clave)){
           $error_clave = "La contraseña debe tener al menos una letra minúscula";
           return false;
        }
        if (!preg_match('`[A-Z]`',$clave)){
           $error_clave = "La contraseña debe tener al menos una letra mayúscula";
           return false;
        }
        if (!preg_match('`[0-9]`',$clave)){
           $error_clave = "La contraseña debe tener al menos un caracter numérico";
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
        $misCorreos=$correos->where(['idinfluencer'=>$id])->where(['eliminado'=>1])->OrderBy('created_at','DESC')->findAll();
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
        $correos->update($id,['eliminado'=>0]);
        //$correos->delete($id);
        $misCorreos=$correos->where(['idinfluencer'=>$idinfluencer])->where(['eliminado'=>1])->OrderBy('created_at','DESC')->findAll();
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
        $dataHeader=['titulo'=>'Restaurar contraseña','mensaje'=>""];
        
        
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
            <html xmlns:v='urn:schemas-microsoft-com:vml' xmlns:o='urn:schemas-microsoft-com:office:office' lang='en'>
            
            <head>
                <title></title>
                <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'><!--[if mso]><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch><o:AllowPNG/></o:OfficeDocumentSettings></xml><![endif]--><!--[if !mso]><!-->
                <link href='https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900' rel='stylesheet' type='text/css'><!--<![endif]-->
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
            
                        .row-2 .column-1 .block-1.heading_block h1,
                        .row-5 .column-1 .block-1.paragraph_block td.pad>div,
                        .row-5 .column-2 .block-1.paragraph_block td.pad>div {
                            text-align: center !important;
                        }
            
                        .row-2 .column-1 .block-1.heading_block h1 {
                            font-size: 22px !important;
                        }
            
                        .row-2 .column-1 .block-3.heading_block h2 {
                            font-size: 19px !important;
                        }
            
                        .row-2 .column-1 .block-4.paragraph_block td.pad>div,
                        .row-2 .column-1 .block-8.paragraph_block td.pad>div {
                            text-align: left !important;
                            font-size: 15px !important;
                        }
            
                        .row-2 .column-1 {
                            padding: 20px 5px 15px !important;
                        }
            
                        .row-4 .column-1 {
                            padding: 0 5px !important;
                        }
                    }
                </style>
            </head>
            
            <body id='body' style='background-color: #f9f9f9; margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;'>
                <table class='nl-container' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f9f9f9;'>
                    <tbody>
                        <tr>
                            <td>
                                <table class='row row-1' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 800.00px; margin: 0 auto;' width='800.00'>
                                                    <tbody>
                                                        <tr>
                                                            <td class='column column-1' width='100%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; padding-top: 25px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                                                                <table class='image_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                    <tr>
                                                                        <td class='pad' style='width:100%;padding-right:0px;padding-left:0px;'>
                                                                            <div class='alignment' align='center' style='line-height:10px'>
                                                                                <div style='max-width: 140px;'><a href='https://buscoinfluencers.com/' target='_blank' style='outline:none' tabindex='-1'><img src='".base_url()."/public/img/verificacion/logo-binf.png' style='display: block; height: auto; border: 0; width: 100%;' width='140' alt='Logo Binf' title='Logo Binf'></a></div>
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
                                <table class='row row-2' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 800.00px; margin: 0 auto;' width='800.00'>
                                                    <tbody>
                                                        <tr>
                                                            <td class='column column-1' width='100%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; padding-bottom: 15px; padding-left: 60px; padding-right: 60px; padding-top: 20px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                                                                <table class='heading_block block-1' width='100%' border='0' cellpadding='10' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                    <tr>
                                                                        <td class='pad'>
                                                                            <h1 style='margin: 0; color: #000000; direction: ltr; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; font-size: 38px; font-weight: 900; letter-spacing: normal; line-height: 120%; text-align: center; margin-top: 0; margin-bottom: 0; mso-line-height-alt: 45.6px;'><em><span class='tinyMce-placeholder'>Restablece tu contraseña</span></em></h1>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                                <table class='divider_block block-2' width='100%' border='0' cellpadding='10' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                    <tr>
                                                                        <td class='pad'>
                                                                            <div class='alignment' align='center'>
                                                                                <table border='0' cellpadding='0' cellspacing='0' role='presentation' width='80%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                                    <tr>
                                                                                        <td class='divider_inner' style='font-size: 1px; line-height: 1px; border-top: 5px solid #000000;'><span>&#8202;</span></td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                                <table class='heading_block block-3' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                    <tr>
                                                                        <td class='pad' style='padding-bottom:15px;padding-left:10px;padding-right:10px;padding-top:10px;text-align:center;width:100%;'>
                                                                            <h2 style='margin: 0; color: #000000; direction: ltr; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; font-size: 24px; font-weight: 800; letter-spacing: normal; line-height: 120%; text-align: center; margin-top: 0; margin-bottom: 0; mso-line-height-alt: 28.799999999999997px;'><span class='tinyMce-placeholder'>Hola ".$influ["nombreinflu"]."</span></h2>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                                <table class='paragraph_block block-4' width='100%' border='0' cellpadding='10' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                                                                    <tr>
                                                                        <td class='pad'>
                                                                            <div style='color:#726f70;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:16px;font-weight:400;letter-spacing:0px;line-height:180%;text-align:center;mso-line-height-alt:28.8px;'>
                                                                                <p style='margin: 0;'>Hemos recibido una solicitud para restablecer tu contraseña. Para continuar haz clic en el siguiente botón para restablecer tu contraseña:</p>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                                <div class='spacer_block block-5' style='height:25px;line-height:25px;font-size:1px;'>&#8202;</div>
                                                                <table class='image_block block-6' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                    <tr>
                                                                        <td class='pad' style='width:100%;'>
                                                                            <div class='alignment' align='center' style='line-height:10px'>
                                                                                <div style='max-width: 282px;'><a href='".base_url()."/respassword/".$tokens."/".$influ["idinfluencer"]."' target='_blank' style='outline:none' tabindex='-1'><img src='".base_url()."/public/img/verificacion/btn-restablecer-clave.png' style='display: block; height: auto; border: 0; width: 100%;' width='282' alt='Restablece tu contraseña' title='Restablece tu contraseña'></a></div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                                <div class='spacer_block block-7' style='height:45px;line-height:45px;font-size:1px;'>&#8202;</div>
                                                                <table class='paragraph_block block-8' width='100%' border='0' cellpadding='10' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                                                                    <tr>
                                                                        <td class='pad'>
                                                                            <div style='color:#726f70;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:16px;font-weight:400;letter-spacing:0px;line-height:180%;text-align:center;mso-line-height-alt:28.8px;'>
                                                                                <p style='margin: 0;'>Si no realizaste esta solicitud, no se requiere realizar ninguna otra acción.<br>En caso que necesites asistencia, escríbenos en nuestras redes sociales o contáctanos directamente.</p>
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
                                <table class='row row-3' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <table class='row-content' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 800.00px; margin: 0 auto;' width='800.00'>
                                                    <tbody>
                                                        <tr>
                                                            <td class='column column-1' width='46%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                                                                <table class='image_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                    <tr>
                                                                        <td class='pad' style='padding-right:10px;width:100%;'>
                                                                            <div class='alignment' align='right' style='line-height:10px'>
                                                                                <div style='max-width: 27px;'><a href='https://www.instagram.com/buscoinfluencers/' target='_blank' style='outline:none' tabindex='-1'><img src='".base_url()."/public/img/verificacion/binf-ig.png' style='display: block; height: auto; border: 0; width: 100%;' width='27' alt='Binf - Instagram' title='Binf - Instagram'></a></div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                            <td class='column column-1' width='8%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                                                                <table class='image_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                    <tr>
                                                                        <td class='pad' style='width:100%;'>
                                                                            <div class='alignment' align='center' style='line-height:10px'>
                                                                                <div style='max-width: 27px;'><a href='https://wa.link/ipree3' target='_blank' style='outline:none' tabindex='-1'><img src='<?=base_url('public/img/verificacion/binf-wpp.png')?>' style='display: block; height: auto; border: 0; width: 100%;' width='27' alt='Binf - WhatsApp' title='Binf - WhatsApp'></a></div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                            <td class='column column-2' width='46%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                                                                <table class='image_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                    <tr>
                                                                        <td class='pad' style='padding-left:10px;width:100%;'>
                                                                            <div class='alignment' align='left' style='line-height:10px'>
                                                                                <div style='max-width: 27px;'><a href='https://www.tiktok.com/@binfluencers' target='_blank' style='outline:none' tabindex='-1'><img src='".base_url()."/public/img/verificacion/binf-tik-tok.png' style='display: block; height: auto; border: 0; width: 100%;' width='27' alt='Binf - TikTok' title='Binf - TikTok'></a></div>
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
                                <table class='row row-4' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 800.00px; margin: 0 auto;' width='800.00'>
                                                    <tbody>
                                                        <tr>
                                                            <td class='column column-1' width='100%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; padding-left: 60px; padding-right: 60px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                                                                <table class='paragraph_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                                                                    <tr>
                                                                        <td class='pad' style='padding-bottom:10px;padding-left:10px;padding-right:10px;padding-top:15px;'>
                                                                            <div style='color:#726f70;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:180%;text-align:center;mso-line-height-alt:25.2px;'>
                                                                                <p style='margin: 0;'>Este correo electrónico fue enviado por:<br>WD Studios Corporation SAS. Cra 101 # 12A bis-70, Cali, Colombia.<br>Móvil: (318) 619-7481</p>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                                <table class='paragraph_block block-2' width='100%' border='0' cellpadding='10' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                                                                    <tr>
                                                                        <td class='pad'>
                                                                            <div style='color:#726f70;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:150%;text-align:center;mso-line-height-alt:21px;'>
                                                                                <p style='margin: 0;'>Este mensaje ha sido verificado con herramientas de eliminación de virus y contenido malicioso. Si tiene algún&nbsp;inconveniente con la información recibida comuníquese con nosotros vía telefónica o respondamos este mismo correo. Recomendamos agregarnos a sus contactos para mantener una comunicación más efectiva.</p>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                                <table class='divider_block block-3' width='100%' border='0' cellpadding='10' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                    <tr>
                                                                        <td class='pad'>
                                                                            <div class='alignment' align='center'>
                                                                                <table border='0' cellpadding='0' cellspacing='0' role='presentation' width='85%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                                    <tr>
                                                                                        <td class='divider_inner' style='font-size: 1px; line-height: 1px; border-top: 2px solid #979CA2;'><span>&#8202;</span></td>
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
                                <table class='row row-5' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 800.00px; margin: 0 auto;' width='800.00'>
                                                    <tbody>
                                                        <tr>
                                                            <td class='column column-1' width='50%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; padding-top: 10px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                                                                <table class='paragraph_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                                                                    <tr>
                                                                        <td class='pad' style='padding-bottom:10px;padding-left:10px;padding-right:15px;padding-top:10px;'>
                                                                            <div style='color:#726f70;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:150%;text-align:right;mso-line-height-alt:21px;'>
                                                                                <p style='margin: 0;'><a href='https://www.buscoinfluencers.com/aviso-de-privacidad-2/' target='_blank' style='text-decoration: none; color: #726f70;' rel='noopener'>Política de privacidad.</a></p>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                            <td class='column column-2' width='50%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; padding-top: 10px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                                                                <table class='paragraph_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                                                                    <tr>
                                                                        <td class='pad' style='padding-bottom:10px;padding-left:15px;padding-right:10px;padding-top:10px;'>
                                                                            <div style='color:#726f70;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:150%;text-align:left;mso-line-height-alt:21px;'>
                                                                                <p style='margin: 0;'><a href='https://www.buscoinfluencers.com/terminos-y-condiciones-2/' target='_blank' style='text-decoration: none; color: #726f70;' rel='noopener'>Términos y condiciones.</a></p>
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
                                <table class='row row-6' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 800.00px; margin: 0 auto;' width='800.00'>
                                                    <tbody>
                                                        <tr>
                                                            <td class='column column-1' width='100%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; padding-bottom: 40px; padding-top: 15px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                                                                <table class='image_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                    <tr>
                                                                        <td class='pad' style='width:100%;'>
                                                                            <div class='alignment' align='center' style='line-height:10px'>
                                                                                <div style='max-width: 57px;'><img src='".base_url()."/public/img/verificacion/wd-studios.png' style='display: block; height: auto; border: 0; width: 100%;' width='57'></div>
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
            $asunto="Buscoinfluencers | Nunca pierdas tu cuenta 😉";
            $this->_enviarCorreo($influ['correo'],$cuerpo,$asunto);
            
            return redirect()->to("/")->with('mensaje', 'Revisa la bandeja de entrada de tu correo.');
  
        }else{
            
            return redirect()->back()->with('mensaje', 'El correo no se encontró en la base de datos.');
  
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
            return redirect()->to("/")->with('mensaje', 'La contraseña se restauró correctamente');

        }else{
            return redirect()->back()->with('mensaje', 'Las contraseñas no son iguales');
        }


    }

   




   /*************************************************************************/
   /** BUSQUEDA EN REDES USANDO APIS */
   /*************************************************************************/
   public function agregarRedSocial(){

        $redinfluencer=new InfluencersRedesModel();
        $influencer = new InfluencerModel();
        
        $id= $this->request->getPost('influencerid1');
        $miInflue=$influencer->find($id);

        $redsocial= $this->request->getPost('redessocialesagregar');
        $usuarioredsocial= $this->request->getPost('textousuariored');

        $array=['idinfluencer'=>$id,'idredes'=>$redsocial];
        $buscar=$redinfluencer->where($array)->findAll();

        if(!($usuarioredsocial==null || $usuarioredsocial=="")){

            $first_character = substr($usuarioredsocial, 0, 1);
            
            if ($first_character != "@") {
               
                $r=$this->buscarSeguidoresAPI($redsocial,$usuarioredsocial);

                if($r!=0){
                    if($buscar==null){
                        $redinfluencer->insert(['idinfluencer'=>$id,'idredes'=>$redsocial,'user'=>$usuarioredsocial,'cant_seguidores'=>$r,'last_update'=>date('Y-m-d H:i:s')]);
                        return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Tus red social se creo con éxito');
                    } else{
                        $redinfluencer->update($buscar[0]['id'],['cant_seguidores'=>$r, 'last_update'=>date('Y-m-d H:i:s')]);
                        return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Tus red se actualizó con éxito');
                    }
                }else{
                        return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'No se encontro el usuario de la red social');
                        
                }

            } else {
                return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Debes escribir el usuario sin incluir el @');
            }    
        }    
        return redirect()->to("/influencer/edit/".$miInflue['alias'])->with('mensaje', 'Debes escribir un usuario para poder actualizar tu red social');
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
                    $influencerredesmodel->insert(['idinfluencer'=>$id,'idredes'=>$m['idredes'],'user'=>$nombre,'cant_seguidores'=>$r,'last_update'=>date('Y-m-d H:i:s')]);
                    return "Su red social se agrego correctamente";
                } else{
                    $influencerredesmodel->update($obj[0]['id'],['cant_seguidores'=>$r, 'last_update'=>date('Y-m-d H:i:s')]);
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


private function _actualizarRedesSociales($id,$idRedes){
    
    $redesmodel=new RedesModel();
    $influencerredesmodel=new InfluencersRedesModel();
    $redesSociales= $redesmodel->where('activa',1)->findAll();
    $cont=0;    
    foreach ($redesSociales as $key => $m) {
        
        
        $nombre= $idRedes;
        

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
        if($red['nombre']=="Telegram"){
            $cantSeguidores=$this->telegram($nombre);
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
    CURLOPT_URL => "https://tiktok-video-feature-summary.p.rapidapi.com/user/info?unique_id=".$tiktok,
    CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => [
		"X-RapidAPI-Host: tiktok-video-feature-summary.p.rapidapi.com",
		"X-RapidAPI-Key: c7c42e5e34msh4d68a181971610dp1e8e34jsn435b150476f6"
	],
    ]);

    $result = curl_exec($curl);
    $r= (array)json_decode($result,true);
    //var_dump($r['data']['stats']['followerCount']);
    $err = curl_error($curl);

    curl_close($curl);


    $retorno=0;
        try{
            $retorno=$r['data']['stats']['followerCount'];
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


   public function telegram($telegram){

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://telegram92.p.rapidapi.com/api/info/channel?channel=".$telegram,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: telegram92.p.rapidapi.com",
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
        $retorno=$r['subscribers'];
    }catch(\Exception $e){
        var_dump($e->getMessage());
    }

    return $retorno; 
        
   }


   

}