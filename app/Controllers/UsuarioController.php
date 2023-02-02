<?php

namespace App\Controllers;

use App\Models\PagoModel;
use App\Models\PaisModel;
use App\Models\RedesModel;
use App\Models\CiudadModel;
use App\Models\IdiomaModel;
use App\Models\CategoriasModel;
use App\Models\InfluencerModel;
use App\Models\InfluencerCategoriaModel;

class UsuarioController extends BaseController
{
    public function index()
    {
        $categorias = new CategoriasModel();
        $redes = new RedesModel();
        $idiomas = new IdiomaModel();
        $pagos = new PagoModel();
        $paises = new PaisModel();
        
        $misPaises= $paises->findAll();
        $ciudades=$this->_cargarCiudades($misPaises);
        
        


        $data=['categorias'=> $categorias->orderBy('nombrecat', 'ASC')->where('mostradas',1)->findAll(),
        'redes'=> $redes->orderBy('nombre', 'ASC')->where('activa',1)->findAll(),
        'idiomas'=> $idiomas->orderBy('nombre', 'ASC')->findAll(),
        'pagos'=> $pagos->findAll(),
        'paises'=> $misPaises,
        'ciudades'=> $ciudades,
        ];

        $dataHeader=['titulo'=>'Busca Influencer','mensaje'=>""];

        $this-> _loadDefaultView($dataHeader,$data,'buscar');
    }


     //METODO QUE ME PERMITE CARGAR PAGINAS
     private function _loadDefaultView($dataHeader,$data,$view){

     
        echo view("influencer/templates/header",$dataHeader);
        echo view("influencer/usuarios/$view",$data);
        echo view("influencer/templates/footer");
       

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


     //BUSCAR INFLUENCERS
     public function buscarInfluencers(){

        $idcategoria= $this->request->getPost('categoriaselect');
        $idredsocial= $this->request->getPost('redsocialselect');
        $cant_seguidores= $this->request->getPost('seguidores');
        $ididioma= $this->request->getPost('idiomaSelect');
        $arrayDeIdPagos=$this->_arregloDePago();
        $idpais= $this->request->getPost('pais2');
        $idregion= $this->request->getPost('ciudades2');

        $categorias = new CategoriasModel();
        $redes = new RedesModel();
        $idioma = new IdiomaModel();
        $pagos = new PagoModel();
        $pais = new PaisModel();
        $ciudad = new CiudadModel();
       
        

        $criteriosBusqueda=[
            'categoria'=>"-",
            'redes'=>"-",
            'cantidad'=>"-",
            'idioma'=>"-",
            'pago'=>[],
            'pais'=>"-",
            'ciudad'=>"-",
        ];
       
        $busquedaActual=[
            'categoria'=>[],
            'redes'=>[],
            'cantidad'=>"",
            'idioma'=>"",
            'pago'=>[],
            'pais'=>"",
            'ciudad'=>"",
        ];


        $influencerBuscado=[];

        $db      = \Config\Database::connect();

        $builder = $db->table('influencers_redes');
        $builder->select('*');
        $builder->where('categorias.mostradas', 1);
        $builder->join('influencers', 'influencers_redes.idinfluencer = influencers.idinfluencer');
        $builder->join('redes_sociales', 'redes_sociales.idredes = influencers_redes.idredes');
        $builder->join('influencers_categorias', 'influencers_categorias.idinfluencer = influencers.idinfluencer');
        $builder->join('categorias', 'categorias.idcategoria = influencers_categorias.idcategoria');
        $builder->join('idiomas_influencers', 'idiomas_influencers.idinfluencer = influencers.idinfluencer');
        $builder->join('idiomas', 'idiomas.ididioma = idiomas_influencers.ididioma');
        $builder->join('influencers_pagos', 'influencers_pagos.idinfluencer = influencers.idinfluencer');
        $builder->join('tipos_pagos', 'tipos_pagos.idpago = influencers_pagos.idpago');
        $query = $builder->get();
        $influencerBuscado =$query->getResultArray();
           
        
        $categoriaBuscada=[];
        if($idcategoria!=null && $idcategoria>0){
            $influencerBuscado=$this->_filtrarPorCategorias($influencerBuscado,$idcategoria);
            $cat=$categorias->find($idcategoria);
            $criteriosBusqueda['categoria']=$cat['nombrecat'];
            array_push($categoriaBuscada,$cat['idcategoria']);
        }
        $busquedaActual['categoria']=$categoriaBuscada;

        
        
        $redBuscada=[];
        if($idredsocial!=null && $idredsocial>0){
            $influencerBuscado=$this->_filtrarPorRedSocial($influencerBuscado,$idredsocial);
            $red=$redes->find($idredsocial);
            $criteriosBusqueda['redes']=$red['nombre'];
            array_push($redBuscada,$red['idredes']);
        }
        $busquedaActual['redes']=$redBuscada;

        

        if($cant_seguidores>0){
            
            $influencerBuscado=$this->_filtrarPorCantidadSeguidores($influencerBuscado,$cant_seguidores);
            
            $criteriosBusqueda['cantidad']=$cant_seguidores;
            $busquedaActual['cantidad']=$cant_seguidores;
        }

        

        if($ididioma!=null && $ididioma>0){
           
            $influencerBuscado=$this->_filtrarPorIdioma($influencerBuscado,$ididioma);
            $idi=$idioma->find($ididioma);
            $criteriosBusqueda['idioma']=$idi['nombre'];
            $busquedaActual['idioma']=$ididioma; 
        }
        
        $misPagos= $pagos->findAll();
        $textoPagos="";
        $arrayDePagos=[];


        foreach ($misPagos as $key => $p) {
            if (isset($_REQUEST["pago".$p['idpago']])) {
                //var_dump("ENTRE y LLEGO ".$p['nombre']) ;
                array_push($arrayDePagos,$p);
               // $textoPagos= $textoPagos."/".$p['nombre'];  
             }
        }
        if(count($arrayDePagos)>0){
            $influencerBuscado=$this->_filtrarPorPago($influencerBuscado,$arrayDePagos);
            $busquedaActual['pago']=$arrayDePagos; 
            $criteriosBusqueda['pago']=$arrayDePagos;
        }


        
        //var_dump($influencerBuscado);

        if($idpais!=null && $idpais>0){
            $miPais=$pais->find($idpais);
            if($idregion!=null && $idregion>0){
                    $miCiudad=$ciudad->find($idregion);
                    $influencerBuscado=$this->_filtrarPorCiudadYPais($influencerBuscado,$idpais,$idregion);
                    $criteriosBusqueda['pais']=$miPais['nombre'];
                    $criteriosBusqueda['ciudad']=$miCiudad['nombre'];
                    $busquedaActual['pais']=$idpais;
                    $busquedaActual['ciudad']=$idregion;
                
            }else{
                $influencerBuscado=$this->_filtrarPorCiudadYPais($influencerBuscado,$idpais,null);
                $busquedaActual['pais']=$idpais;
                $criteriosBusqueda['pais']=$miPais['nombre'];
            }
        }
        
       // $categorias = new CategoriasModel();
        //$redes = new RedesModel();
        $idiomas = new IdiomaModel();
        $pagos = new PagoModel();
        $misPaises= $pais->findAll();
        $ciudades=$this->_cargarCiudades($misPaises);
       

        $data=['influencer'=>$influencerBuscado,
                'criteriosBusqueda'=>$criteriosBusqueda,
                'busquedaActual'=>$busquedaActual,
                'categorias'=> $categorias->orderBy('nombrecat', 'ASC')->where('mostradas',1)->findAll(),
                'redes'=> $redes->orderBy('nombre', 'ASC')->where('activa',1)->findAll(),
                'idiomas'=> $idiomas->orderBy('nombre', 'ASC')->findAll(),
                'pagos'=> $pagos->findAll(),
                'paises'=> $misPaises,
                'ciudades'=> $ciudades,
        ];

        $dataHeader=['titulo'=>'Buscar Influencer','mensaje'=>""];
        
        $this-> _loadDefaultView($dataHeader,$data,'resultados');
        
    }

     //BUSCAR INFLUENCERS OTRA BUSQUEDA
     public function nuevaBusquedaInfluencers(){

        
        $cant_seguidores= $this->request->getPost('seguidores2');
        $ididioma= $this->request->getPost('idiomaSelect2');
        $arrayDeIdPagos=$this->_arregloDePago();
        $idpais= $this->request->getPost('pais3');
        $idregion= $this->request->getPost('ciudades3');

        $categorias = new CategoriasModel();
        $redes = new RedesModel();
        $idioma = new IdiomaModel();
        $pagos = new PagoModel();
        $pais = new PaisModel();
        $ciudad = new CiudadModel();
       
        

        $criteriosBusqueda=[
            'categoria'=>"-",
            'redes'=>"-",
            'cantidad'=>"-",
            'idioma'=>"-",
            'pago'=>[],
            'pais'=>"-",
            'ciudad'=>"-",
        ];


        $busquedaActual=[
            'categoria'=>[],
            'redes'=>[],
            'cantidad'=>"",
            'idioma'=>"",
            'pago'=>[],
            'pais'=>"",
            'ciudad'=>"",
        ];
       


        $influencerBuscado=[];

        $db      = \Config\Database::connect();

        $builder = $db->table('influencers_redes');
        $builder->select('*');
        $builder->where('categorias.mostradas', 1);
        $builder->join('influencers', 'influencers_redes.idinfluencer = influencers.idinfluencer');
        $builder->join('redes_sociales', 'redes_sociales.idredes = influencers_redes.idredes');
        $builder->join('influencers_categorias', 'influencers_categorias.idinfluencer = influencers.idinfluencer');
        $builder->join('categorias', 'categorias.idcategoria = influencers_categorias.idcategoria');
        $builder->join('idiomas_influencers', 'idiomas_influencers.idinfluencer = influencers.idinfluencer');
        $builder->join('idiomas', 'idiomas.ididioma = idiomas_influencers.ididioma');
        $builder->join('influencers_pagos', 'influencers_pagos.idinfluencer = influencers.idinfluencer');
        $builder->join('tipos_pagos', 'tipos_pagos.idpago = influencers_pagos.idpago');
        $query = $builder->get();
        $influencerBuscado =$query->getResultArray();
        
        //BUSQUEDA POR CATEGORIAS USANDO LA Y
       // var_dump($influencerBuscado) ;
      /*  $misCategorias = $categorias->orderBy('nombrecat', 'ASC')->where('mostradas',1)->findAll();
        $categoriaBuscada=[];
        foreach ($misCategorias as $key => $m) { 
            if (isset($_POST["cat".$m['idcategoria']])) {   
                    //var_dump("ENTRE y LLEGO ".$m['nombrecat']) ;
                    $influencerBuscado=$this->_filtrarPorCategorias($influencerBuscado,$m['idcategoria']);
                    array_push($categoriaBuscada,$m['idcategoria']);
            }
        }
        $busquedaActual['categoria']=$categoriaBuscada;
        **/

        //BUSQUEDA POR CATEGORIAS USANDO LA O
        $misCategorias = $categorias->orderBy('nombrecat', 'ASC')->where('mostradas',1)->findAll();
        $categoriaBuscada=[];
        $influencerEncontrado=[];
        
        foreach ($misCategorias as $key => $m) { 
            
            if (isset($_POST["cat".$m['idcategoria']])) { 
                
                array_push($categoriaBuscada,$m['idcategoria']);
                foreach ($influencerBuscado as $key => $infl) {       
                    if($m['idcategoria']==$infl['idcategoria']) {
                        //var_dump($m['nombrecat']);
                       
                        if(count($influencerEncontrado)>0){
                            $cont=0;
                            foreach ($influencerEncontrado as $key => $in) {
                               if($in['idinfluencer']==$infl['idinfluencer']){
                                $cont++;
                               }
                            }
                            if($cont==0){
                                array_push($influencerEncontrado,$infl);
                            }
                        }else{
                            array_push($influencerEncontrado,$infl);
                        }
                        //unset($misCategorias[$i]);
                    }  
                }
            }
        }
        $busquedaActual['categoria']=$categoriaBuscada;
        $influencerBuscado=$influencerEncontrado;
       //var_dump($influencerBuscado);

        /*
        $misredes=$redes->orderBy('nombre', 'ASC')->where('activa',1)->findAll(); 
        $redBuscada=[];
        foreach ($misredes as $key => $m) {
            if (isset($_POST["red".$m['idredes']])) {  
                //var_dump("ENTRE y LLEGO ".$m['nombre']) ;
                $influencerBuscado=$this->_filtrarPorRedSocial($influencerBuscado,$m['idredes']);
                array_push($redBuscada,$m['idredes']);               
            }
        }
        $busquedaActual['redes']=$redBuscada;
        */

        //BUSQUEDA POR REDES USANDO LA O
        $misredes=$redes->orderBy('nombre', 'ASC')->where('activa',1)->findAll(); 
        $redBuscada=[];
        $influencerEncontrado=[];
        
        foreach ($misredes as $key => $m) { 
            
            if (isset($_POST["red".$m['idredes']])) { 
                
                array_push($redBuscada,$m['idredes']);
                foreach ($influencerBuscado as $key => $infl) {       
                    if($m['idredes']==$infl['idredes']) {
                        //var_dump($m['nombrecat']);
                       
                        if(count($influencerEncontrado)>0){
                            $cont=0;
                            foreach ($influencerEncontrado as $key => $in) {
                               if($in['idinfluencer']==$infl['idinfluencer']){
                                $cont++;
                               }
                            }
                            if($cont==0){
                                array_push($influencerEncontrado,$infl);
                            }
                        }else{
                            array_push($influencerEncontrado,$infl);
                        }
                        //unset($misCategorias[$i]);
                    }  
                }
            }
        }
        $busquedaActual['redes']=$redBuscada;
        $influencerBuscado=$influencerEncontrado;

        

        if($cant_seguidores>=0){
            //var_dump("ENTRE y LLEGO ".$cant_seguidores) ;
            $influencerBuscado=$this->_filtrarPorCantidadSeguidores($influencerBuscado,$cant_seguidores);
            $busquedaActual['cantidad']=$cant_seguidores;
        }
        

        if($ididioma!=null && $ididioma>0){
            //var_dump("ENTRE y LLEGO ".$ididioma) ;
            $influencerBuscado=$this->_filtrarPorIdioma($influencerBuscado,$ididioma); 
            $busquedaActual['idioma']=$ididioma;  
        }
        

        $misPagos= $pagos->findAll();
        $textoPagos="";
        $arrayDePagos=[];

        foreach ($misPagos as $key => $p) {
            if (isset($_REQUEST["pago".$p['idpago']])) {
                //var_dump("ENTRE y LLEGO ".$p['nombre']) ;
                array_push($arrayDePagos,$p);
               // $textoPagos= $textoPagos."/".$p['nombre'];  
             }
        }
        if(count($arrayDePagos)>0){
            $influencerBuscado=$this->_filtrarPorPago($influencerBuscado,$arrayDePagos);
            $busquedaActual['pago']=$arrayDePagos; 
        }
        
        if($idpais!=null && $idpais>0){
            $miPais=$pais->find($idpais);
            if($idregion!=null && $idregion>0){
                    $miCiudad=$ciudad->find($idregion);
                    $influencerBuscado=$this->_filtrarPorCiudadYPais($influencerBuscado,$idpais,$idregion);
                    $busquedaActual['pais']=$idpais;
                    $busquedaActual['ciudad']=$idregion;
                
            }else{
                $influencerBuscado=$this->_filtrarPorCiudadYPais($influencerBuscado,$idpais,null);
                
                $busquedaActual['pais']=$idpais;
            }
        }

        $idiomas = new IdiomaModel();
        $pagos = new PagoModel();
        $misPaises= $pais->findAll();
        $ciudades=$this->_cargarCiudades($misPaises);
        
        $data=['influencer'=>$influencerBuscado,
                'criteriosBusqueda'=>$criteriosBusqueda,
                'busquedaActual'=>$busquedaActual,
                'categorias'=> $categorias->orderBy('nombrecat', 'ASC')->where('mostradas',1)->findAll(),
                'redes'=> $redes->orderBy('nombre', 'ASC')->where('activa',1)->findAll(),
                'idiomas'=> $idiomas->orderBy('nombre', 'ASC')->findAll(),
                'pagos'=> $pagos->findAll(),
                'paises'=> $misPaises,
                'ciudades'=> $ciudades,
        ];
       
        $dataHeader=['titulo'=>'Buscar Influencer','mensaje'=>""];
        //var_dump($influencerBuscado);

        $this-> _loadDefaultView($dataHeader,$data,'resultados');
        
    }



     //BUSCAR INFLUENCERS OTRA BUSQUEDA
     public function busquedaPorCategoria($idcategoria=null){

       

        $categorias = new CategoriasModel();
        $redes = new RedesModel();
        $idioma = new IdiomaModel();
        $pagos = new PagoModel();
        $pais = new PaisModel();
        $ciudad = new CiudadModel();
       
        

        $criteriosBusqueda=[
            'categoria'=>"-",
            'redes'=>"-",
            'cantidad'=>"-",
            'idioma'=>"-",
            'pago'=>[],
            'pais'=>"-",
            'ciudad'=>"-",
        ];


        $busquedaActual=[
            'categoria'=>[],
            'redes'=>[],
            'cantidad'=>"",
            'idioma'=>"",
            'pago'=>[],
            'pais'=>"",
            'ciudad'=>"",
        ];
       


        $influencerBuscado=[];

        $db      = \Config\Database::connect();

        $builder = $db->table('influencers_redes');
        $builder->select('*');
        $builder->where('categorias.mostradas', 1);
        $builder->join('influencers', 'influencers_redes.idinfluencer = influencers.idinfluencer');
        $builder->join('redes_sociales', 'redes_sociales.idredes = influencers_redes.idredes');
        $builder->join('influencers_categorias', 'influencers_categorias.idinfluencer = influencers.idinfluencer');
        $builder->join('categorias', 'categorias.idcategoria = influencers_categorias.idcategoria');
        $builder->join('idiomas_influencers', 'idiomas_influencers.idinfluencer = influencers.idinfluencer');
        $builder->join('idiomas', 'idiomas.ididioma = idiomas_influencers.ididioma');
        $builder->join('influencers_pagos', 'influencers_pagos.idinfluencer = influencers.idinfluencer');
        $builder->join('tipos_pagos', 'tipos_pagos.idpago = influencers_pagos.idpago');
        $query = $builder->get();
        $influencerBuscado =$query->getResultArray();
        
       
        //var_dump($influencerBuscado) ;
        $misCategorias = $categorias->orderBy('nombrecat', 'ASC')->where('mostradas',1)->findAll();
        $categoriaBuscada=[];
         
                    //var_dump("ENTRE y LLEGO ".$m['nombrecat']) ;
                    $influencerBuscado=$this->_filtrarPorCategorias($influencerBuscado,$idcategoria);
                    array_push($categoriaBuscada,$idcategoria);
        
        $busquedaActual['categoria']=$categoriaBuscada;
        

        $idiomas = new IdiomaModel();
        $pagos = new PagoModel();
        $misPaises= $pais->findAll();
        $ciudades=$this->_cargarCiudades($misPaises);
        
        $data=['influencer'=>$influencerBuscado,
                'criteriosBusqueda'=>$criteriosBusqueda,
                'busquedaActual'=>$busquedaActual,
                'categorias'=> $categorias->orderBy('nombrecat', 'ASC')->where('mostradas',1)->findAll(),
                'redes'=> $redes->orderBy('nombre', 'ASC')->where('activa',1)->findAll(),
                'idiomas'=> $idiomas->orderBy('nombre', 'ASC')->findAll(),
                'pagos'=> $pagos->findAll(),
                'paises'=> $misPaises,
                'ciudades'=> $ciudades,
        ];
       
        $dataHeader=['titulo'=>'Buscar Influencer','mensaje'=>""];
        //var_dump($influencerBuscado);

        $this-> _loadDefaultView($dataHeader,$data,'resultados');
        
    }



    private function _arregloDePago(){
       
         $arraypago=[];
         $pagos = new PagoModel();

         $misPagos= $pagos->findAll();

        foreach ($misPagos as $key => $m) {
            if (isset($_REQUEST[$m['idpago']])) {
                array_push($arraypago,$m['idpago']); 
            }
        }

        
        return $arraypago;
         
    }

    private function _filtrarPorCategorias($misInfluencer,$idCategoria){

             
        $arrayInfluencerBuscado=[];
        $arrayDeIdInfluencers=[];

        foreach ($misInfluencer as $key => $in) {
            
            if($in['idcategoria']==$idCategoria){
                    if(count($arrayDeIdInfluencers)==0){
                        array_push($arrayDeIdInfluencers,$in['idinfluencer']);
                        array_push($arrayInfluencerBuscado,$in);
                    } else {
                        $cont=0;
                        foreach ($arrayDeIdInfluencers as $key => $idinf) {
                            if($idinf==$in['idinfluencer']){
                                $cont++;
                            }
                        }
                        if($cont==0){
                            array_push($arrayDeIdInfluencers,$in['idinfluencer']);
                            array_push($arrayInfluencerBuscado,$in); 
                        }
                    }
               
            }
        }
        return $arrayInfluencerBuscado;

    }

    private function _filtrarPorRedSocial($misInfluencer,$idRedes){

        
              
        $arrayInfluencerBuscado=[];
        $arrayDeIdInfluencers=[];

        foreach ($misInfluencer as $key => $in) {
            
            if($in['idredes']==$idRedes){
                    if(count($arrayDeIdInfluencers)==0){
                        array_push($arrayDeIdInfluencers,$in['idinfluencer']);
                        array_push($arrayInfluencerBuscado,$in);
                    } else {
                        $cont=0;
                        foreach ($arrayDeIdInfluencers as $key => $idinf) {
                            if($idinf==$in['idinfluencer']){
                                $cont++;
                            }
                        }
                        if($cont==0){
                            array_push($arrayDeIdInfluencers,$in['idinfluencer']);
                            array_push($arrayInfluencerBuscado,$in); 
                        }
                    }
               
            }
        }
        return $arrayInfluencerBuscado;

    }

    private function _filtrarPorCantidadSeguidores($misInfluencer,$cantidad){

        
        
        $arrayInfluencerBuscado=[];
        $arrayDeIdInfluencers=[];

        foreach ($misInfluencer as $key => $in) {
            
            if($in['cant_seguidores']>$cantidad){
                    if(count($arrayDeIdInfluencers)==0){
                        array_push($arrayDeIdInfluencers,$in['idinfluencer']);
                        array_push($arrayInfluencerBuscado,$in);
                    } else {
                        $cont=0;
                        foreach ($arrayDeIdInfluencers as $key => $idinf) {
                            if($idinf==$in['idinfluencer']){
                                $cont++;
                            }
                        }
                        if($cont==0){
                            array_push($arrayDeIdInfluencers,$in['idinfluencer']);
                            array_push($arrayInfluencerBuscado,$in); 
                        }
                    }
               
            }
        }
        return $arrayInfluencerBuscado;

    }

    private function _filtrarPorIdioma($misInfluencer,$idIdioma){

        
              
        $arrayInfluencerBuscado=[];
        $arrayDeIdInfluencers=[];

        foreach ($misInfluencer as $key => $in) {
            
            if($in['ididioma']==$idIdioma){
                    if(count($arrayDeIdInfluencers)==0){
                        array_push($arrayDeIdInfluencers,$in['idinfluencer']);
                        array_push($arrayInfluencerBuscado,$in);
                    } else {
                        $cont=0;
                        foreach ($arrayDeIdInfluencers as $key => $idinf) {
                            if($idinf==$in['idinfluencer']){
                                $cont++;
                            }
                        }
                        if($cont==0){
                            array_push($arrayDeIdInfluencers,$in['idinfluencer']);
                            array_push($arrayInfluencerBuscado,$in); 
                        }
                    }
               
            }
        }
        return $arrayInfluencerBuscado;

    }

    private function _filtrarPorPago($misInfluencer,$arrayPagos){

        
              
        $arrayInfluencerBuscado=[];
        $arrayDeIdInfluencers=[];

        foreach ($misInfluencer as $key => $in) {
            foreach ($arrayPagos as $key => $pa) {
                        
                if($in['idpago']==$pa['idpago']){
                        if(count($arrayDeIdInfluencers)==0){
                            array_push($arrayDeIdInfluencers,$in['idinfluencer']);
                            array_push($arrayInfluencerBuscado,$in);
                        } else {
                            $cont=0;
                            foreach ($arrayDeIdInfluencers as $key => $idinf) {
                                if($idinf==$in['idinfluencer']){
                                    $cont++;
                                }
                            }
                            if($cont==0){
                                array_push($arrayDeIdInfluencers,$in['idinfluencer']);
                                array_push($arrayInfluencerBuscado,$in); 
                            }
                        }
                
                }
            }
        }
        return $arrayInfluencerBuscado;

    }

    private function _filtrarPorCiudadYPais($misInfluencer,$idpais,$idciudad){

        $ciudad = new CiudadModel();
              
        $arrayInfluencerBuscado=[];
        $arrayDeIdInfluencers=[];

        if($idciudad==null){

            foreach ($misInfluencer as $key => $in) {

                $miCiudad= $ciudad->find($in['idciudad']);
                if($miCiudad['idpais']==$idpais){
                    if(count($arrayDeIdInfluencers)==0){
                        array_push($arrayDeIdInfluencers,$in['idinfluencer']);
                        array_push($arrayInfluencerBuscado,$in);
                    } else {
                        $cont=0;
                        foreach ($arrayDeIdInfluencers as $key => $idinf) {
                            if($idinf==$in['idinfluencer']){
                                $cont++;
                            }
                        }
                        if($cont==0){
                            array_push($arrayDeIdInfluencers,$in['idinfluencer']);
                            array_push($arrayInfluencerBuscado,$in); 
                         }
                    }
                
                }
            
             }

        }else{
            foreach ($misInfluencer as $key => $in) {
                if($in['idciudad']==$idciudad){
                        if(count($arrayDeIdInfluencers)==0){
                            array_push($arrayDeIdInfluencers,$in['idinfluencer']);
                            array_push($arrayInfluencerBuscado,$in);
                        } else {
                            $cont=0;
                            foreach ($arrayDeIdInfluencers as $key => $idinf) {
                                if($idinf==$in['idinfluencer']){
                                    $cont++;
                                }
                            }
                            if($cont==0){
                                array_push($arrayDeIdInfluencers,$in['idinfluencer']);
                                array_push($arrayInfluencerBuscado,$in); 
                            }
                        }
                
                }
            
             }


        }
        
        return $arrayInfluencerBuscado;

    }

    



}