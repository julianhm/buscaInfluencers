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
                'alias'=>'required|min_length[2]|max_length[20]',
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
                    'required' => 'El alias es requerido',
                    'min_length' => 'El alias debe tener como mínimo 4 caracteres',
                    'max_length' => 'El alias NO puede tener mas de 20 caracteres',
                ],'password' => [
                    'required' => 'El password es requerido',
                    'min_length' => 'El password debe tener como mínimo 8 caracteres',
                    'matches'=>'Los password No coinciden'
                ], 
                'correo' => [
                    'required' => 'El email es requerido',
                    'valid_email' => 'El email no tiene el formato de un correo',
                    'is_unique'=>'El email ya esta registrado en la base de datos',

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
                if($id>0){
                

                        $cuerpo = "<!DOCTYPE html>
                        <html xmlns:v='urn:schemas-microsoft-com:vml' xmlns:o='urn:schemas-microsoft-com:office:office' lang='en'>
                        
                        <head>
                            <title></title>
                            <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
                            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                            <!--[if mso]><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch><o:AllowPNG/></o:OfficeDocumentSettings></xml><![endif]-->
                            <!--[if !mso]><!-->
                            <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
                            <!--<![endif]-->
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

                                .font_mont {
                                    font-family: 'Montserrat';
                                }
                        
                                @media (max-width:820px) {
                                    .social_block.desktop_hide .social-table {
                                        display: inline-block !important;
                                    }
                        
                                    .row-content {
                                        width: 100% !important;
                                    }
                        
                                    .mobile_hide {
                                        display: none;
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
                                }
                        
                                @media (max-width:768px) {
                        
                                    .row-3 .column-1 .block-2.paragraph_block td.pad>div,
                                    .row-3 .column-1 .block-5.paragraph_block td.pad>div,
                                    .row-3 .column-1 .block-8.paragraph_block td.pad>div {
                                        text-align: justify !important;
                                        font-size: 16px !important;
                                    }
                        
                                    .row-3 .column-1 .block-2.paragraph_block td.pad,
                                    .row-3 .column-1 .block-5.paragraph_block td.pad,
                                    .row-3 .column-1 .block-8.paragraph_block td.pad,
                                    .row-5 .column-1 .block-1.social_block td.pad {
                                        padding: 0 30px !important;
                                    }
                        
                                    .row-3 .column-1 .block-1.paragraph_block td.pad>div {
                                        text-align: center !important;
                                        font-size: 20px !important;
                                    }
                        
                                    .row-3 .column-1 .block-1.paragraph_block td.pad {
                                        padding: 0 30px 10px !important;
                                    }
                        
                                    .row-2 .column-1 .block-4.heading_block td.pad {
                                        padding: 10px 30px !important;
                                    }
                        
                                    .row-2 .column-1 .block-4.heading_block h1 {
                                        font-size: 22px !important;
                                    }
                        
                                    .row-1 .column-1 .block-1.image_block td.pad {
                                        padding: 15px 0 0 !important;
                                    }
                        
                                    .row-1 .column-1 .block-1.image_block img,
                                    .row-6 .column-1 .block-2.button_block .alignment div,
                                    .row-6 .column-2 .block-2.button_block .alignment div {
                                        display: inline-block !important;
                                    }
                        
                                    .row-1 .column-1 .block-1.image_block .alignment,
                                    .row-6 .column-1 .block-2.button_block .alignment,
                                    .row-6 .column-2 .block-2.button_block .alignment {
                                        text-align: center !important;
                                    }
                        
                                    .row-6 .column-1 .block-2.button_block a span,
                                    .row-6 .column-1 .block-2.button_block div,
                                    .row-6 .column-1 .block-2.button_block div span,
                                    .row-6 .column-2 .block-2.button_block a span,
                                    .row-6 .column-2 .block-2.button_block div,
                                    .row-6 .column-2 .block-2.button_block div span {
                                        line-height: 2 !important;
                                    }
                                }
                            </style>
                        </head>
                        
                        <body style='background-color: transparent; margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;'>
                            <table class='nl-container' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: transparent;'>
                                <tbody>
                                    <tr>
                                        <td>
                                            <table class='row row-1' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #e8e5ec;'>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; border-radius: 0; color: #000000; width: 800px;' width='800'>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class='column column-1' width='100%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 0px; padding-bottom: 0px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                                                                            <table class='image_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                                <tr>
                                                                                    <td class='pad' style='padding-left:60px;padding-top:20px;width:100%;padding-right:0px;'>
                                                                                        <div class='alignment' align='left' style='line-height:10px'><img src='".base_url()."/public/img/verificacion/logo-binf.png' style='display: block; height: auto; border: 0; width: 160px; max-width: 100%;' width='160' alt='Logo Binf' title='Logo Binf'></div>
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
                                            <table class='row row-2' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #e8e5ec;'>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 800px;' width='800'>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class='column column-1' width='100%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                                                                            <div class='spacer_block' style='height:5px;line-height:5px;font-size:1px;'>&#8202;</div>
                                                                            <div class='spacer_block mobile_hide' style='height:25px;line-height:25px;font-size:1px;'>&#8202;</div>
                                                                            <div class='spacer_block desktop_hide' style='mso-hide: all; display: none; max-height: 0; overflow: hidden; height: 25px; line-height: 25px; font-size: 1px;'>&#8202;</div>
                                                                            <table class='heading_block block-4' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                                <tr>
                                                                                    <td class='pad font_mont' style='padding-bottom:10px;padding-top:10px;text-align:center;width:100%;'>
                                                                                        <h1 class='font_mont' style='margin: 0; color: #1d1d1b; direction: ltr; font-size: 32px; font-weight: 700; letter-spacing: normal; line-height: 120%; text-align: center; margin-top: 0; margin-bottom: 0;'><span class='tinyMce-placeholder'>Verifica tu registro</span></h1>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <table class='divider_block block-5' width='100%' border='0' cellpadding='10' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                                <tr>
                                                                                    <td class='pad'>
                                                                                        <div class='alignment' align='center'>
                                                                                            <table border='0' cellpadding='0' cellspacing='0' role='presentation' width='70%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                                                <tr>
                                                                                                    <td class='divider_inner' style='font-size: 1px; line-height: 1px; border-top: 7px solid #1D1D1B;'><span>&#8202;</span></td>
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
                                            <table class='row row-3' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #e8e5ec;'>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 800px;' width='800'>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class='column column-1' width='100%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                                                                            <table class='paragraph_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                                                                                <tr>
                                                                                    <td class='pad' style='padding-bottom:15px;padding-left:60px;padding-right:60px;'>
                                                                                        <div style='color:#000000;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:24px;font-weight:700;letter-spacing:0px;line-height:180%;text-align:center;mso-line-height-alt:43.2px;'>
                                                                                            <p style='margin: 0;'>Bienvenida/o a Busco Influencers</p>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <table class='paragraph_block block-2' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                                                                                <tr>
                                                                                    <td class='pad' style='padding-left:60px;padding-right:60px;'>
                                                                                        <div style='color:#726f70;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:16px;font-weight:400;letter-spacing:0px;line-height:180%;text-align:center;mso-line-height-alt:28.8px;'>
                                                                                            <p style='margin: 0;'>Te damos la bienvenida a un nuevo sitio creado para darte a conocer en el<br>universo digital. Con <span style='color: #000000;'><strong>BuscoInfluencers.com</strong></span> tienes una gran oportunidad de estar<br>en un directorio cada vez más completo de creadores de contenido en todas las<br>categorías. Más de una vez habrás escuchado a alguien preguntar por algún<br>influencer para cierto proyecto o producto; pues bien, en <span style='color: #000000;'><strong>buscoinfluencers.com</strong></span><br>todos tendrán esa respuesta sin buscar tanto.</p>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <div class='spacer_block desktop_hide' style='mso-hide: all; display: none; max-height: 0; overflow: hidden; height: 40px; line-height: 40px; font-size: 1px;'>&#8202;</div>
                                                                            <div class='spacer_block mobile_hide' style='height:40px;line-height:40px;font-size:1px;'>&#8202;</div>
                                                                            <table class='paragraph_block block-5' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                                                                                <tr>
                                                                                    <td class='pad' style='padding-left:60px;padding-right:60px;'>
                                                                                        <div style='color:#726f70;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:16px;font-weight:400;letter-spacing:0px;line-height:180%;text-align:center;mso-line-height-alt:28.8px;'>
                                                                                            <p style='margin: 0;'>Para continuar necesitas verificar tu cuenta en nuestra plataforma. Haz clic en el siguiente botón para confirmar tu correo electrónico:</p>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <div class='spacer_block desktop_hide' style='mso-hide: all; display: none; max-height: 0; overflow: hidden; height: 40px; line-height: 40px; font-size: 1px;'>&#8202;</div>
                                                                            <div class='spacer_block mobile_hide' style='height:40px;line-height:40px;font-size:1px;'>&#8202;</div>
                                                                            <table class='paragraph_block block-8' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                                                                                <tr>
                                                                                    <td class='column column-1' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;' width='100%'>
                                                                                    <table border='0' cellpadding='10' cellspacing='0' class='button_block block-1' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;' width='100%'>
                                                                                    <tr>
                                                                                    <td class='pad'>
                                                                                        <a href='".base_url()."/validarCorreo/".$tokens."/".$id."'>
                                                                                            <div align='center' class='alignment'><!--[if mso]><v:roundrect xmlns:v='urn:schemas-microsoft-com:vml' xmlns:w='urn:schemas-microsoft-com:office:word' style='height:50px;width:188px;v-text-anchor:middle;' arcsize='8%' stroke='false' fillcolor='#000000'><w:anchorlock/><v:textbox inset='0px,0px,0px,0px'><center style='color:#ffffff; font-family:Arial, sans-serif; font-size:20px'><![endif]-->
                                                                                            <div style='text-decoration:none;display:inline-block;color:#ffffff;background-color:#000000;border-radius:4px;width:auto;border-top:0px solid transparent;font-weight:700;border-right:0px solid transparent;border-bottom:0px solid transparent;border-left:0px solid transparent;padding-top:5px;padding-bottom:5px;font-family:Arial, Helvetica, sans-serif;font-size:20px;text-align:center;mso-border-alt:none;word-break:keep-all;'><span style='padding-left:20px;padding-right:20px;font-size:20px;display:inline-block;letter-spacing:normal;'><span dir='ltr' style='word-break: break-word; line-height: 40px;'>Verificar cuenta</span></span></div><!--[if mso]></center></v:textbox></v:roundrect><![endif]-->
                                                                                            </div>
                                                                                        </a>
                                                                                    </td>
                                                                                    </tr>
                                                                                    </table>
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
                                            <table class='row row-4' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #e8e5ec;'>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 800px;' width='800'>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class='column column-1' width='100%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                                                                            <div class='spacer_block' style='height:5px;line-height:5px;font-size:1px;'>&#8202;</div>
                                                                            <div class='spacer_block desktop_hide' style='mso-hide: all; display: none; max-height: 0; overflow: hidden; height: 50px; line-height: 50px; font-size: 1px;'>&#8202;</div>
                                                                            <div class='spacer_block mobile_hide' style='height:100px;line-height:100px;font-size:1px;'>&#8202;</div>
                                                                            <div class='spacer_block' style='height:5px;line-height:5px;font-size:1px;'>&#8202;</div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table class='row row-5' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #e8e5ec;'>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 800px;' width='800'>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class='column column-1' width='100%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                                                                            <table class='social_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                                <tr>
                                                                                    <td class='pad' style='text-align:center;padding-right:0px;padding-left:0px;'>
                                                                                        <div class='alignment' align='center'>
                                                                                            <table class='social-table' width='137.98228128460687px' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; display: inline-block;'>
                                                                                                <tr>
                                                                                                    <td style='padding:0 7px 0 7px;'><a href='https://www.instagram.com/buscoinfluencers/' target='_blank'><img src='".base_url()."/public/img/verificacion/instagram.png' width='31.238095238095237' height='32' alt='Instagram' title='Instagram' style='display: block; height: auto; border: 0;'></a></td>
                                                                                                    <td style='padding:0 7px 0 7px;'><a href='https://wa.link/d17wli' target='_blank'><img src='".base_url()."/public/img/verificacion/whatsapp.png' width='32.74418604651163' height='32' alt='Whatsapp' title='Whats app' style='display: block; height: auto; border: 0;'></a></td>
                                                                                                    <td style='padding:0 7px 0 7px;'><a href='https://wa.link/d17wli' target='_blank'><img src='".base_url()."/public/img/verificacion/tiktok.png' width='32' height='32' alt='Tiktok' title='Tiktok' style='display: block; height: auto; border: 0;'></a></td>
                                                                                                </tr>
                                                                                            </table>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <table class='paragraph_block block-2' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                                                                                <tr>
                                                                                    <td class='pad' style='padding-left:60px;padding-right:60px;padding-top:15px;'>
                                                                                        <div style='color:#979ca2;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:180%;text-align:center;mso-line-height-alt:25.2px;'>
                                                                                            <p style='margin: 0;'>Este correo electrónico fue enviado por:<br>WD Studios Corporation SAS. Cra 101 # 12A bis-70, Cali, Colombia.<br>Teléfonos: (2) 405 9935 &nbsp;/ &nbsp;Móvil: 316 7627511</p>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <table class='paragraph_block block-4' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                                                                                <tr>
                                                                                    <td class='pad' style='padding-left:60px;padding-right:60px;padding-top:25px;'>
                                                                                        <div style='color:#979ca2;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:150%;text-align:center;mso-line-height-alt:21px;'>
                                                                                            <p style='margin: 0;'>Este mensaje ha sido verificado con herramientas de eliminación de virus y contenido malicioso. Si tiene algún&nbsp;inconveniente con la información recibida comuníquese con nosotros vía telefónica o respondamos este mismo correo. Recomendamos agregarnos a sus contactos para mantener una comunicación más efectiva.</p>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <table class='divider_block block-5' width='100%' border='0' cellpadding='10' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                                <tr>
                                                                                    <td class='pad'>
                                                                                        <div class='alignment' align='center'>
                                                                                            <table border='0' cellpadding='0' cellspacing='0' role='presentation' width='70%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
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
                                            <table class='row row-6' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #e8e5ec;'>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; border-radius: 0; color: #000000; width: 800px;' width='800'>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class='column column-1' width='50%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: middle; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                                                                            <table class='button_block block-2' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                                <tr>
                                                                                    <td class='pad' style='text-align:right;padding-top:5px;padding-bottom:5px;'>
                                                                                        <div class='alignment' align='right'>
                                                                                            <!--[if mso]><v:roundrect xmlns:v='urn:schemas-microsoft-com:vml' xmlns:w='urn:schemas-microsoft-com:office:word' href='".base_url()."public/aviso-de-privacidad-2/' style='height:38px;width:173px;v-text-anchor:middle;' arcsize='11%' stroke='false' fill='false'><w:anchorlock/><v:textbox inset='0px,0px,0px,0px'><center style='color:#979ca2; font-family:Arial, sans-serif; font-size:14px'><![endif]--><a href='https://www.buscoinfluencers.com/aviso-de-privacidad-2/' target='_blank' style='text-decoration:none;display:inline-block;color:#979ca2;background-color:transparent;border-radius:4px;width:auto;border-top:0px solid transparent;font-weight:400;border-right:0px solid transparent;border-bottom:0px solid transparent;border-left:0px solid transparent;padding-top:5px;padding-bottom:5px;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:14px;text-align:center;mso-border-alt:none;word-break:keep-all;'><span style='padding-left:20px;padding-right:20px;font-size:14px;display:inline-block;letter-spacing:normal;'><span dir='ltr' style='word-break: break-word; line-height: 28px;'>Política de privacidad</span></span></a>
                                                                                            <!--[if mso]></center></v:textbox></v:roundrect><![endif]-->
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                        <td class='column column-2' width='50%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: middle; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                                                                            <table class='button_block block-2' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                                <tr>
                                                                                    <td class='pad' style='text-align:left;padding-top:5px;padding-bottom:5px;'>
                                                                                        <div class='alignment' align='left'>
                                                                                            <!--[if mso]><v:roundrect xmlns:v='urn:schemas-microsoft-com:vml' xmlns:w='urn:schemas-microsoft-com:office:word' href='https://www.buscoinfluencers.com/terminos-y-condiciones-2/' style='height:38px;width:187px;v-text-anchor:middle;' arcsize='11%' stroke='false' fill='false'><w:anchorlock/><v:textbox inset='0px,0px,0px,0px'><center style='color:#979ca2; font-family:Arial, sans-serif; font-size:14px'><![endif]--><a href='https://www.buscoinfluencers.com/terminos-y-condiciones-2/' target='_blank' style='text-decoration:none;display:inline-block;color:#979ca2;background-color:transparent;border-radius:4px;width:auto;border-top:0px solid transparent;font-weight:400;border-right:0px solid transparent;border-bottom:0px solid transparent;border-left:0px solid transparent;padding-top:5px;padding-bottom:5px;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:14px;text-align:center;mso-border-alt:none;word-break:keep-all;'><span style='padding-left:20px;padding-right:20px;font-size:14px;display:inline-block;letter-spacing:normal;'><span dir='ltr' style='word-break: break-word; line-height: 28px;'>Términos y condiciones</span></span></a>
                                                                                            <!--[if mso]></center></v:textbox></v:roundrect><![endif]-->
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
                                            <table class='row row-7' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #e8e5ec;'>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; border-radius: 0; color: #000000; width: 800px;' width='800'>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class='column column-1' width='100%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: middle; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                                                                            <table class='icons_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                                <tr>
                                                                                    <td class='pad' style='vertical-align: middle; color: #000000; font-family: inherit; font-size: 14px; text-align: center; padding-bottom: 25px;'>
                                                                                        <table class='alignment' cellpadding='0' cellspacing='0' role='presentation' align='center' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                                            <tr>
                                                                                                <td style='vertical-align: middle; text-align: center; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; padding-right: 5px;'><img class='icon' src='".base_url()."/public/img/verificacion/wd-studios.png' alt='WD STUDIOS' height='32' width='62' align='center' style='display: block; height: auto; margin: 0 auto; border: 0;'></td>
                                                                                            </tr>
                                                                                        </table>
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
                        $asunto="Valida tu correo y activa tu cuenta";
                        $this->_enviarCorreo($correo,$cuerpo,$asunto);
                        
                        session()->set('idinfluencer',$id);

                        return redirect()->to('/')->with('mensaje', 'Tu cuenta se creo con exito, antes de ingresar debes validarla desde tu correo electrónico');
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
            return redirect()->to("/")->with('mensaje', 'Tokens invalido');
        
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

        return redirect()->back()->withinput()->with('mensaje',"Ocurrio un error inesperado,No se pudo enviar su mensaje.");

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

             //ACATUALIZAR REDES SOCIALES
            $misInfluRedes=$influredes->where("idinfluencer",$id);
            foreach ($misInfluRedes as $key => $m) {
                $this->_actualizarRedesSociales($id,$m['idredes']);
            }

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
	<meta name='viewport' content='width=device-width, initial-scale=1.0'>
	<!--[if mso]><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch><o:AllowPNG/></o:OfficeDocumentSettings></xml><![endif]-->
	<!--[if !mso]><!-->
	<link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
	<!--<![endif]-->
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

        .font_mont {
			font-family: 'Montserrat';
		}

		@media (max-width:820px) {
			.social_block.desktop_hide .social-table {
				display: inline-block !important;
			}

			.row-content {
				width: 100% !important;
			}

			.mobile_hide {
				display: none;
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
		}

		@media (max-width:768px) {

			.row-3 .column-1 .block-2.paragraph_block td.pad>div,
			.row-3 .column-1 .block-5.paragraph_block td.pad>div,
			.row-3 .column-1 .block-8.paragraph_block td.pad>div {
				text-align: justify !important;
				font-size: 16px !important;
			}

			.row-3 .column-1 .block-2.paragraph_block td.pad,
			.row-3 .column-1 .block-5.paragraph_block td.pad,
			.row-3 .column-1 .block-8.paragraph_block td.pad,
			.row-5 .column-1 .block-1.social_block td.pad {
				padding: 0 30px !important;
			}

			.row-3 .column-1 .block-1.paragraph_block td.pad>div {
				text-align: center !important;
				font-size: 20px !important;
			}

			.row-3 .column-1 .block-1.paragraph_block td.pad {
				padding: 0 30px 10px !important;
			}

			.row-2 .column-1 .block-4.heading_block td.pad {
				padding: 10px 30px !important;
			}

			.row-2 .column-1 .block-4.heading_block h1 {
				font-size: 22px !important;
			}

			.row-1 .column-1 .block-1.image_block td.pad {
				padding: 15px 0 0 !important;
			}

			.row-1 .column-1 .block-1.image_block img,
			.row-6 .column-1 .block-2.button_block .alignment div,
			.row-6 .column-2 .block-2.button_block .alignment div {
				display: inline-block !important;
			}

			.row-1 .column-1 .block-1.image_block .alignment,
			.row-6 .column-1 .block-2.button_block .alignment,
			.row-6 .column-2 .block-2.button_block .alignment {
				text-align: center !important;
			}

			.row-6 .column-1 .block-2.button_block a span,
			.row-6 .column-1 .block-2.button_block div,
			.row-6 .column-1 .block-2.button_block div span,
			.row-6 .column-2 .block-2.button_block a span,
			.row-6 .column-2 .block-2.button_block div,
			.row-6 .column-2 .block-2.button_block div span {
				line-height: 2 !important;
			}
		}
	</style>
</head>

<body style='background-color: transparent; margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;'>
	<table class='nl-container' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: transparent;'>
		<tbody>
			<tr>
				<td>
					<table class='row row-1' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #e8e5ec;'>
						<tbody>
							<tr>
								<td>
									<table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; border-radius: 0; color: #000000; width: 800px;' width='800'>
										<tbody>
											<tr>
												<td class='column column-1' width='100%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 0px; padding-bottom: 0px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
													<table class='image_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
														<tr>
															<td class='pad' style='padding-left:60px;padding-top:20px;width:100%;padding-right:0px;'>
																<div class='alignment' align='left' style='line-height:10px'><img src='".base_url()."/public/img/verificacion/logo-binf.png' style='display: block; height: auto; border: 0; width: 160px; max-width: 100%;' width='160' alt='Logo Binf' title='Logo Binf'></div>
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
					<table class='row row-2' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #e8e5ec;'>
						<tbody>
							<tr>
								<td>
									<table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 800px;' width='800'>
										<tbody>
											<tr>
												<td class='column column-1' width='100%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
													<div class='spacer_block' style='height:5px;line-height:5px;font-size:1px;'>&#8202;</div>
													<div class='spacer_block mobile_hide' style='height:25px;line-height:25px;font-size:1px;'>&#8202;</div>
													<div class='spacer_block desktop_hide' style='mso-hide: all; display: none; max-height: 0; overflow: hidden; height: 25px; line-height: 25px; font-size: 1px;'>&#8202;</div>
													<table class='heading_block block-4' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
														<tr>
															<td class='pad font_mont' style='padding-bottom:10px;padding-top:10px;text-align:center;width:100%;'>
																<h1 style='margin: 0; color: #1d1d1b; direction: ltr; font-size: 32px; font-weight: 700; letter-spacing: normal; line-height: 120%; text-align: center; margin-top: 0; margin-bottom: 0;'><span class='tinyMce-placeholder'>Restablece tu contraseña</span></h1>
															</td>
														</tr>
													</table>
													<table class='divider_block block-5' width='100%' border='0' cellpadding='10' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
														<tr>
															<td class='pad'>
																<div class='alignment' align='center'>
																	<table border='0' cellpadding='0' cellspacing='0' role='presentation' width='70%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
																		<tr>
																			<td class='divider_inner' style='font-size: 1px; line-height: 1px; border-top: 7px solid #1D1D1B;'><span>&#8202;</span></td>
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
					<table class='row row-3' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #e8e5ec;'>
						<tbody>
							<tr>
								<td>
									<table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 800px;' width='800'>
										<tbody>
											<tr>
												<td class='column column-1' width='100%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
													
                                                    <table class='paragraph_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
														<tr>
															<td class='pad' style='padding-bottom:15px;padding-left:60px;padding-right:60px;'>
																<div style='color:#000000;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:24px;font-weight:700;letter-spacing:0px;line-height:180%;text-align:center;mso-line-height-alt:43.2px;'>
																	<p style='margin: 0;'>Hola ".$influ["nombreinflu"]."</p>
																</div>
															</td>
														</tr>
													</table>
                                                    
													<table class='paragraph_block block-2' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
														<tr>
															<td class='pad' style='padding-left:60px;padding-right:60px;'>
																<div style='color:#726f70;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:16px;font-weight:400;letter-spacing:0px;line-height:180%;text-align:center;mso-line-height-alt:28.8px;'>
																	<p style='margin: 0;'>
																		<br>Hemos recibido una solicitud para restablecer tu contraseña. Para continuar haz clic en el siguiente botón para restablecer tu contraseña<br>
																	</p>

																</div>
															</td>
														</tr>
													</table>


													

													<div class='spacer_block desktop_hide' style='mso-hide: all; display: none; max-height: 0; overflow: hidden; height: 40px; line-height: 40px; font-size: 1px;'>&#8202;</div>
													<div class='spacer_block mobile_hide' style='height:40px;line-height:40px;font-size:1px;'>&#8202;</div>

													<table class='paragraph_block block-8' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
														<tr>
                                                            <td class='column column-1' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;' width='100%'>
                                                            <table border='0' cellpadding='10' cellspacing='0' class='button_block block-1' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;' width='100%'>
                                                            <tr>
                                                            <td class='pad'>
                                                            	<a href='".base_url()."/respassword/".$tokens."/".$influ["idinfluencer"]."'>
                                                            <div align='center' class='alignment'><!--[if mso]><v:roundrect xmlns:v='urn:schemas-microsoft-com:vml' xmlns:w='urn:schemas-microsoft-com:office:word' style='height:50px;width:188px;v-text-anchor:middle;' arcsize='8%' stroke='false' fillcolor='#000000'><w:anchorlock/><v:textbox inset='0px,0px,0px,0px'><center style='color:#ffffff; font-family:Arial, sans-serif; font-size:20px'><![endif]-->
                                                            <div style='text-decoration:none;display:inline-block;color:#ffffff;background-color:#000000;border-radius:4px;width:auto;border-top:0px solid transparent;font-weight:700;border-right:0px solid transparent;border-bottom:0px solid transparent;border-left:0px solid transparent;padding-top:5px;padding-bottom:5px;font-family:Arial, Helvetica, sans-serif;font-size:20px;text-align:center;mso-border-alt:none;word-break:keep-all;'><span style='padding-left:20px;padding-right:20px;font-size:20px;display:inline-block;letter-spacing:normal;'><span dir='ltr' style='word-break: break-word; line-height: 40px;'>Restablecer contraseña</span></span></div><!--[if mso]></center></v:textbox></v:roundrect><![endif]-->
                                                            </div>
                                                            </td>
                                                            </a>
                                                            </tr>
                                                            </table>
                                                            </td>
                                                            </tr>
													</table>

													<div class='spacer_block desktop_hide' style='mso-hide: all; display: none; max-height: 0; overflow: hidden; height: 40px; line-height: 40px; font-size: 1px;'>&#8202;</div>
													<div class='spacer_block mobile_hide' style='height:40px;line-height:40px;font-size:1px;'>&#8202;</div>

													<table class='paragraph_block block-5' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
														<tr>
															<td class='pad' style='padding-left:60px;padding-right:60px;'>
																<div style='color:#726f70;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:16px;font-weight:400;letter-spacing:0px;line-height:180%;text-align:center;mso-line-height-alt:28.8px;'>
																	<p style='margin: 0;'>Si no realizaste esta solicitud, no se requiere realizar ninguna otra acción. <br>En caso que necesites asistencia, escribenos en nuestras redes sociales o contactanos directamente.</p>
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
					<table class='row row-4' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #e8e5ec;'>
						<tbody>
							<tr>
								<td>
									<table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 800px;' width='800'>
										<tbody>
											<tr>
												<td class='column column-1' width='100%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
													<div class='spacer_block' style='height:5px;line-height:5px;font-size:1px;'>&#8202;</div>
													<div class='spacer_block desktop_hide' style='mso-hide: all; display: none; max-height: 0; overflow: hidden; height: 50px; line-height: 50px; font-size: 1px;'>&#8202;</div>
													<div class='spacer_block mobile_hide' style='height:100px;line-height:100px;font-size:1px;'>&#8202;</div>
													<div class='spacer_block' style='height:5px;line-height:5px;font-size:1px;'>&#8202;</div>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<table class='row row-5' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #e8e5ec;'>
						<tbody>
							<tr>
								<td>
									<table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 800px;' width='800'>
										<tbody>
											<tr>
												<td class='column column-1' width='100%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
													<table class='social_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
														<tr>
															<td class='pad' style='text-align:center;padding-right:0px;padding-left:0px;'>
																<div class='alignment' align='center'>
																	<table class='social-table' width='137.98228128460687px' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; display: inline-block;'>
																		<tr>
																			<td style='padding:0 7px 0 7px;'><a href='https://www.instagram.com/buscoinfluencers/' target='_blank'><img src='".base_url()."/public/img/verificacion/instagram.png' width='31.238095238095237' height='32' alt='Instagram' title='Instagram' style='display: block; height: auto; border: 0;'></a></td>
																			<td style='padding:0 7px 0 7px;'><a href='https://wa.link/d17wli' target='_blank'><img src='".base_url()."/public/img/verificacion/whatsapp.png' width='32.74418604651163' height='32' alt='Whatsapp' title='Whats app' style='display: block; height: auto; border: 0;'></a></td>
																			<td style='padding:0 7px 0 7px;'><a href='https://wa.link/d17wli' target='_blank'><img src='".base_url()."/public/img/verificacion/tiktok.png' width='32' height='32' alt='Tiktok' title='Tiktok' style='display: block; height: auto; border: 0;'></a></td>
																		</tr>
																	</table>
																</div>
															</td>
														</tr>
													</table>
													<table class='paragraph_block block-2' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
														<tr>
															<td class='pad' style='padding-left:60px;padding-right:60px;padding-top:15px;'>
																<div style='color:#979ca2;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:180%;text-align:center;mso-line-height-alt:25.2px;'>
																	<p style='margin: 0;'>Este correo electrónico fue enviado por:<br>WD Studios Corporation SAS. Cra 101 # 12A bis-70, Cali, Colombia.<br>Teléfonos: (2) 405 9935 &nbsp;/ &nbsp;Móvil: 316 7627511</p>
																</div>
															</td>
														</tr>
													</table>
													<table class='paragraph_block block-4' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
														<tr>
															<td class='pad' style='padding-left:60px;padding-right:60px;padding-top:25px;'>
																<div style='color:#979ca2;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:150%;text-align:center;mso-line-height-alt:21px;'>
																	<p style='margin: 0;'>Este mensaje ha sido verificado con herramientas de eliminación de virus y contenido malicioso. Si tiene algún&nbsp;inconveniente con la información recibida comuníquese con nosotros vía telefónica o respondamos este mismo correo. Recomendamos agregarnos a sus contactos para mantener una comunicación más efectiva.</p>
																</div>
															</td>
														</tr>
													</table>
													<table class='divider_block block-5' width='100%' border='0' cellpadding='10' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
														<tr>
															<td class='pad'>
																<div class='alignment' align='center'>
																	<table border='0' cellpadding='0' cellspacing='0' role='presentation' width='70%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
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
					<table class='row row-6' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #e8e5ec;'>
						<tbody>
							<tr>
								<td>
									<table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; border-radius: 0; color: #000000; width: 800px;' width='800'>
										<tbody>
											<tr>
												<td class='column column-1' width='50%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: middle; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
													<table class='button_block block-2' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
														<tr>
															<td class='pad' style='text-align:right;padding-top:5px;padding-bottom:5px;'>
																<div class='alignment' align='right'>
																	<!--[if mso]><v:roundrect xmlns:v='urn:schemas-microsoft-com:vml' xmlns:w='urn:schemas-microsoft-com:office:word' href='https://www.buscoinfluencers.com/aviso-de-privacidad-2/' style='height:38px;width:173px;v-text-anchor:middle;' arcsize='11%' stroke='false' fill='false'><w:anchorlock/><v:textbox inset='0px,0px,0px,0px'><center style='color:#979ca2; font-family:Arial, sans-serif; font-size:14px'><![endif]--><a href='https://www.buscoinfluencers.com/aviso-de-privacidad-2/' target='_blank' style='text-decoration:none;display:inline-block;color:#979ca2;background-color:transparent;border-radius:4px;width:auto;border-top:0px solid transparent;font-weight:400;border-right:0px solid transparent;border-bottom:0px solid transparent;border-left:0px solid transparent;padding-top:5px;padding-bottom:5px;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:14px;text-align:center;mso-border-alt:none;word-break:keep-all;'><span style='padding-left:20px;padding-right:20px;font-size:14px;display:inline-block;letter-spacing:normal;'><span dir='ltr' style='word-break: break-word; line-height: 28px;'>Política de privacidad</span></span></a>
																	<!--[if mso]></center></v:textbox></v:roundrect><![endif]-->
																</div>
															</td>
														</tr>
													</table>
												</td>
												<td class='column column-2' width='50%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: middle; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
													<table class='button_block block-2' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
														<tr>
															<td class='pad' style='text-align:left;padding-top:5px;padding-bottom:5px;'>
																<div class='alignment' align='left'>
																	<!--[if mso]><v:roundrect xmlns:v='urn:schemas-microsoft-com:vml' xmlns:w='urn:schemas-microsoft-com:office:word' href='https://www.buscoinfluencers.com/terminos-y-condiciones-2/' style='height:38px;width:187px;v-text-anchor:middle;' arcsize='11%' stroke='false' fill='false'><w:anchorlock/><v:textbox inset='0px,0px,0px,0px'><center style='color:#979ca2; font-family:Arial, sans-serif; font-size:14px'><![endif]--><a href='https://www.buscoinfluencers.com/terminos-y-condiciones-2/' target='_blank' style='text-decoration:none;display:inline-block;color:#979ca2;background-color:transparent;border-radius:4px;width:auto;border-top:0px solid transparent;font-weight:400;border-right:0px solid transparent;border-bottom:0px solid transparent;border-left:0px solid transparent;padding-top:5px;padding-bottom:5px;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:14px;text-align:center;mso-border-alt:none;word-break:keep-all;'><span style='padding-left:20px;padding-right:20px;font-size:14px;display:inline-block;letter-spacing:normal;'><span dir='ltr' style='word-break: break-word; line-height: 28px;'>Términos y condiciones</span></span></a>
																	<!--[if mso]></center></v:textbox></v:roundrect><![endif]-->
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
					<table class='row row-7' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #e8e5ec;'>
						<tbody>
							<tr>
								<td>
									<table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; border-radius: 0; color: #000000; width: 800px;' width='800'>
										<tbody>
											<tr>
												<td class='column column-1' width='100%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: middle; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
													<table class='icons_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
														<tr>
															<td class='pad' style='vertical-align: middle; color: #000000; font-family: inherit; font-size: 14px; text-align: center; padding-bottom: 25px;'>
																<table class='alignment' cellpadding='0' cellspacing='0' role='presentation' align='center' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
																	<tr>
																		<td style='vertical-align: middle; text-align: center; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; padding-right: 5px;'><img class='icon' src='".base_url()."/public/img/verificacion/wd-studios.png' alt='WD STUDIOS' height='32' width='62' align='center' style='display: block; height: auto; margin: 0 auto; border: 0;'></td>
																	</tr>
																</table>
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
            $asunto="Restablecimiento de Contraseña";
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
                        return redirect()->to("/influencer/edit/$id")->with('mensaje', 'Tus red social se creo con exito');
                    } else{
                        $redinfluencer->update($buscar[0]['id'],['cant_seguidores'=>$r]);
                        return redirect()->to("/influencer/edit/$id")->with('mensaje', 'Tus red se actualizó con exito');
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