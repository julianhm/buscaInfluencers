<?php

namespace App\Controllers;

use Config\Services;
use App\Models\PagoModel;
use App\Models\MarcaModel;
use App\Models\RedesModel;
use App\Models\IdiomaModel;
use App\Models\GaleriaModel;
use App\Models\MensajesModel;
use App\Models\InfluencerModel;
use App\Models\MensajeCorreoModel;
use App\Models\InfluencerPagoModel;
use App\Models\IdiomaInfluencerModel;
use App\Models\InfluencersRedesModel;

class PerfilController extends BaseController
{
    public function index($id=null)
    {
        $influencermodel= new InfluencerModel();
        $redesinfluencer = new InfluencersRedesModel();
        $redes = new RedesModel();
        $mensajes = new MensajesModel();
        $galeria= new GaleriaModel();
        $idiomas = new IdiomaInfluencerModel();
        $idiomasInfluencer = new IdiomaModel();
        $pago= new PagoModel();
        $influpago = new InfluencerPagoModel();
        $marcas= new MarcaModel();
        
        session();

        if($influencermodel->where('alias', $id)->findAll()!=null){
            $influ= $influencermodel->where('alias', $id)->first();
            //$influ=$infl[0];
            $id=$influ['idinfluencer'];  
            //Actualizar redes
            $misredes_antes= $redesinfluencer->where(['idinfluencer'=>$id])->find();
            if ($misredes_antes >= 1) {
                
                foreach ($misredes_antes as $key => $m) {
                    //last update in social media
                    $date1=date_create($m['last_update']);
                    //today
                    $date2=date_create(date('Y-m-d H:i:s'));
                    $diff = $date1->diff($date2);
                    //echo $diff->days . ' minutos ';
                    //if past more than 15 days since the last update
                    if (($diff->days) >= 15) {
                        //=======DISABLE TO NOT WASTE RAPPIAPI REQUEST======//
                        //$this->_actualizarRedesSociales($id,$m['idredes'], $m['user']);
                    }  
       
                }  
            }
    
            $misredes= $redesinfluencer->where(['idinfluencer'=>$id])->find();
            $misMensajes= $mensajes->where(['idinfluencer'=>$id,'aprobado'=>1])->find();
            $misFotos= $galeria->where(['idinfluencer'=>$id])->find();
            $misIdiomas = $idiomas->where(['idinfluencer'=>$id])->find();
            $mispagos = $influpago->where(['idinfluencer'=>$id])->find();
            $mismarcas = $marcas->where(['idinfluencer'=>$id])->find();
    
            $suma=0;
            $cont=0;
            foreach($misMensajes as $key=>$m){
                $suma=$suma+$m['valoracion'];
                $cont++;
            }
            if($cont>0){
                $influencermodel->update($id,['reputacion'=>$suma/$cont]);
            }
    
            $arregloDeredes=[];
            foreach ($misredes as $key => $m) {
                array_push($arregloDeredes,$redes->find($m['idredes']));
            }
    
            $arregloDeidiomas=[];
            foreach ($misIdiomas as $key => $m) {
                array_push($arregloDeidiomas,$idiomasInfluencer->find($m['ididioma']));
            }
    
            $arregloDepagos=[];
            foreach ($mispagos as $key => $m) {
                array_push($arregloDepagos,$pago->find($m['idpago']));
            }
    
            $arregloDeMarcas=[];
            foreach ($mismarcas as $key => $m) {
                array_push($arregloDeMarcas,$pago->find($m['idmarca']));
            }
    
           // var_dump($misMensajes);
           
            $data=[ 'influencer'=>$influ,'redes'=>$misredes,'arregloredes'=>$arregloDeredes,
            'mensajes'=>$misMensajes,'misfotos'=>$misFotos, 'misidiomas'=>$arregloDeidiomas,'pagos'=>$arregloDepagos,
            'marcas'=>$mismarcas];

            $dataHeader=['titulo'=>'Perfil-Busca Influencer',
                     'titulo_meta'=>'Perfil BINF - '.$influ['alias'],
                     'descripcion' => 'Visita mi perfil | '.$influ['resenia'],
                     'url_foto' => base_url("/uploads")."/".$influ['foto_perfil'],
                     'mensaje'=>""];
    
            if($influ!=null){
                $this-> _loadDefaultView($dataHeader,$data,'perfil');
            } 
        }else{
            //throw PageNotFoundException::forPageNotFound();
            $dataHeader =['titulo' => 'Oops',
                'mensaje'=>"",];

            echo view("influencer/templates/header",$dataHeader);
            echo view('errors/html/404'); 
            echo view("influencer/templates/footerindex");
        }    
            
    }


    private function _loadDefaultView($dataHeader,$data,$view){
      
        echo view("influencer/templates/header",$dataHeader);
        echo view("influencer/influencers/$view",$data);
        echo view("influencer/templates/footer");  

    }

    Public function enviarMensajeAInfluencer(){

        $influencer = new InfluencerModel();
        $nombre= $this->request->getPost('nombre');
        $id= $this->request->getPost('influencerid31');
        $miInfluencer=$influencer->find($id);
        $empresa= $this->request->getPost('empresa');
        $cuerpo= $this->request->getPost('cuerpo');
        $valoracion= $this->request->getPost('valoracion');
        
        $mensaje= new MensajesModel();

        $data=['nombre'=>$nombre,'empresa'=>$empresa,'cuerpo'=>$cuerpo,'valoracion'=>$valoracion,'idinfluencer'=>$id,'aprobado'=>0];
        //echo $valoracion;
               
        if($mensaje->insert($data)!=null){
            return redirect()->to("/perfil/".$miInfluencer['alias'])->with('mensaje', 'Tu mensaje se envió con exito');
        }
        return redirect()->back()->withinput();
 
    }

    Public function enviarCorreoLocal(){
        
        $influencer = new InfluencerModel();
        $correo=new MensajeCorreoModel();

        $id= $this->request->getPost('influencerid32');
        $nombre= $this->request->getPost('nombrecontacto');
        $empresa= $this->request->getPost('empresacontacto');
        $correoremi= $this->request->getPost('emailcontacto');
        $celular= $this->request->getPost('celularcontacto');
        $cuerpo= $this->request->getPost('cuerpocontacto');
        
        $miInfluencer= $influencer->find($id);
 
        /*
        $email = Services::email();

        $email->setFrom($correo, 'WD STUDIO CORP');
        $email->setTo($miInfluencer['correo']);
        

        $email->setSubject($nombre);
        $email->setMessage($cuerpo);

        $email->send();
        ***/

        $data=['idinfluencer'=>$id,'nombre'=>$nombre,'empresa'=>$empresa,
                'correoremitente'=>$correoremi,'celularremitente'=>$celular,'cuerpo'=>$cuerpo, 'eliminado'=>1
        ];
       $validation =  \Config\Services::validation();

        if($correo->insert($data)!=null){

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
            
                        .row-2 .column-1 .block-3.heading_block h2,
                        .row-3 .column-2 .block-1.heading_block h2 {
                            font-size: 19px !important;
                        }
            
                        .row-2 .column-1 .block-2.heading_block h1,
                        .row-7 .column-1 .block-1.paragraph_block td.pad>div,
                        .row-7 .column-2 .block-1.paragraph_block td.pad>div {
                            text-align: center !important;
                        }
            
                        .row-2 .column-1 .block-2.heading_block h1 {
                            font-size: 22px !important;
                        }
            
                        .row-2 .column-1 .block-4.paragraph_block td.pad>div,
                        .row-3 .column-2 .block-2.paragraph_block td.pad>div,
                        .row-4 .column-1 .block-1.paragraph_block td.pad>div {
                            text-align: center !important;
                            font-size: 15px !important;
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
                                                                        <td class='pad' style='width:100%;'>
                                                                            <div class='alignment' align='center' style='line-height:10px'>
                                                                                <div style='max-width: 240px;'><a href='https://buscoinfluencers.com/' target='_blank' style='outline:none' tabindex='-1'><img src='".base_url()."img/verificacion/logo-bandeja.png' style='display: block; height: auto; border: 0; width: 100%;' width='240' alt='Logo Binf' title='Logo Binf'></a></div>
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
                                                                <table class='divider_block block-1' width='100%' border='0' cellpadding='10' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
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
                                                                <table class='heading_block block-2' width='100%' border='0' cellpadding='10' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                    <tr>
                                                                        <td class='pad'>
                                                                            <h1 style='margin: 0; color: #000000; direction: ltr; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; font-size: 38px; font-weight: 900; letter-spacing: normal; line-height: 120%; text-align: center; margin-top: 0; margin-bottom: 0; mso-line-height-alt: 45.6px;'><em><span class='tinyMce-placeholder'>¡Llegó un cliente!</span></em></h1>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                                <table class='heading_block block-3' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                    <tr>
                                                                        <td class='pad' style='padding-bottom:15px;padding-left:10px;padding-right:10px;padding-top:10px;text-align:center;width:100%;'>
                                                                            <h2 style='margin: 0; color: #000000; direction: ltr; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; font-size: 24px; font-weight: 800; letter-spacing: normal; line-height: 120%; text-align: center; margin-top: 0; margin-bottom: 0; mso-line-height-alt: 28.799999999999997px;'><span class='tinyMce-placeholder'>Hola ".$miInfluencer["nombreinflu"]."</span></h2>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                                <table class='paragraph_block block-4' width='100%' border='0' cellpadding='10' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                                                                    <tr>
                                                                        <td class='pad'>
                                                                            <div style='color:#726f70;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:16px;font-weight:400;letter-spacing:0px;line-height:120%;text-align:center;mso-line-height-alt:19.2px;'>
                                                                                <p style='margin: 0; margin-bottom: 6px;'>¡Te ha escrito un cliente para iniciar una negociación! Inicia</p>
                                                                                <p style='margin: 0;'>sesión en tu cuenta y búscalo en bandeja de entrada.</p>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                                <div class='spacer_block block-5' style='height:25px;line-height:25px;font-size:1px;'>&#8202;</div>
                                                                <table class='image_block block-6' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                    <tr>
                                                                        <td class='pad' style='width:100%;'>
                                                                            <div class='alignment' align='center' style='line-height:10px'>
                                                                                <div style='max-width: 172px;'><a href='https://buscoinfluencers.com/' target='_blank' style='outline:none' tabindex='-1'><img src='".base_url()."img/verificacion/btn-iniciar-sesion.png' style='display: block; height: auto; border: 0; width: 100%;' width='172' alt='Inicia sesión' title='Inicia sesión'></a></div>
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
                                                                                <div style='max-width: 172px;'><a href='https://buscoinfluencers.com/contacto' target='_blank' style='outline:none' tabindex='-1'><img src='".base_url()."img/verificacion/btn-escribenos.png' style='display: block; height: auto; border: 0; width: 100%;' width='172' alt='Escribenos' title='Escribenos'></a></div>
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
                                <table class='row row-4' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 800.00px; margin: 0 auto;' width='800.00'>
                                                    <tbody>
                                                        <tr>
                                                            <td class='column column-1' width='100%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                                                                <table class='paragraph_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                                                                    <tr>
                                                                        <td class='pad' style='padding-bottom:10px;padding-left:10px;padding-right:10px;padding-top:25px;'>
                                                                            <div style='color:#726f70;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:16px;font-weight:400;letter-spacing:0px;line-height:180%;text-align:center;mso-line-height-alt:28.8px;'>
                                                                                <p style='margin: 0;'>¡Prepárate para una nueva era de influencia digital con BINF!<br><br>El equipo de Busco Influencers trabaja para ti.</p>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                                <div class='spacer_block block-2' style='height:35px;line-height:35px;font-size:1px;'>&#8202;</div>
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
                                                <table class='row-content' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 800.00px; margin: 0 auto;' width='800.00'>
                                                    <tbody>
                                                        <tr>
                                                            <td class='column column-1' width='46%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                                                                <table class='image_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                                                                    <tr>
                                                                        <td class='pad' style='padding-right:10px;width:100%;'>
                                                                            <div class='alignment' align='right' style='line-height:10px'>
                                                                                <div style='max-width: 27px;'><a href='https://www.instagram.com/buscoinfluencers/' target='_blank' style='outline:none' tabindex='-1'><img src='".base_url()."img/verificacion/binf-ig.png' style='display: block; height: auto; border: 0; width: 100%;' width='27' alt='Binf - Instagram' title='Binf - Instagram'></a></div>
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
                                                                                <div style='max-width: 27px;'><a href='https://www.tiktok.com/@binfluencers' target='_blank' style='outline:none' tabindex='-1'><img src='".base_url()."img/verificacion/binf-tik-tok.png' style='display: block; height: auto; border: 0; width: 100%;' width='27' alt='Binf - TikTok' title='Binf - TikTok'></a></div>
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
                                                            <td class='column column-1' width='100%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; padding-left: 60px; padding-right: 60px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                                                                <table class='paragraph_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                                                                    <tr>
                                                                        <td class='pad' style='padding-bottom:10px;padding-left:10px;padding-right:10px;padding-top:15px;'>
                                                                            <div style='color:#726f70;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:180%;text-align:center;mso-line-height-alt:25.2px;'>
                                                                                <p style='margin: 0;'>Este correo electrónico fue enviado por:<br>WD Studios Corporation SAS.<br>Móvil: (318) 619-7481</p>
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
                                <table class='row row-7' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
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
                                <table class='row row-8' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
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
                                                                                <div style='max-width: 57px;'><img src='".base_url()."img/verificacion/wd-studios.png' style='display: block; height: auto; border: 0; width: 100%;' width='57'></div>
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

            $asunto="Buscoinfluencers | Te llegó un cliente 🎉";
            $this->_enviarCorreo($miInfluencer['correo'],$cuerpo,$asunto);


            return redirect()->to("/perfil/".$miInfluencer['alias'])->with('mensaje', 'Tu correo de envió con exito');

        }
        
        return redirect()->to("/perfil/".$miInfluencer['alias'])->with('mensaje', 'Ocurrio un error al enviar tu correo');

       
        
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



    private function _actualizarRedesSociales($id,$idRedes,$user){

        $redesmodel=new RedesModel();
        $influencerredesmodel=new InfluencersRedesModel();
        $redesSociales= $redesmodel->where('activa',1)->findAll();
        $cont=0;
 

        for ($i=0; $i < count($redesSociales); $i++) { 

            if ($idRedes == $redesSociales[$i]['idredes'] ) {

                $miArray=['idinfluencer'=>$id,'idredes'=>$redesSociales[$i]['idredes']];
                $obj=$influencerredesmodel->where($miArray)->findAll();
                /*
                echo '<pre>';
                print_r($obj);
                echo '</pre>';
                */
                $nombre= $user;
                //$r=777;
                    $r=$this->buscarSeguidoresAPI($redesSociales[$i]['idredes'],$nombre);

                if($r!=0){
                    $influencerredesmodel->update($obj[0]['id'],['cant_seguidores'=>$r, 'last_update'=>date('Y-m-d H:i:s')]);

                }

            }
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
