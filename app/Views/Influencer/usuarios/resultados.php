
<body>


    <!-- Header Start -->
    <div id="header" class="container-fluid mb-3 px-4 headerNav">
        <div class="row ">
            <div class="row g-0 pb-2 position_title">
                <div class="text-center text-title-normal" >
                    <b>LOS SEGUIDORES QUE TU MARCA NECESITA SIGUEN A UN INFLUENCER</b>
                </div>
            </div>
            <div class="mx-auto text-end mb-2 mt-2 main-section-title">
               Resultados de búsqueda
            </div>
            <hr class="header_black_line">
            <a style="display: contents;" href="index.html"><img class="logo-header-normal" src="<?=base_url(),"/img/logo-blue.png"?>" > </a>
                
        </div>
    </div>
    <!-- Header End -->


    
    <!-- Content search result Start -->
    <div class="container-fluid px-5  py-3">
    <form action="nuevoresultado" method="POST" class="register-form pt-2" id="otrabusqueda" name="otrabusqueda" enctype="multipart/form-data">
                  
            <div class="row">
                <div class="col sidebar-width">

                    <div class="row">
                        <div class="px-2 py-3 user-decription searched-black-box">
                            <p style="font-weight: bold;">TU BÚSQUEDA FUE:</p>

                            <div id="filter_1" class="select_custom_small user-decription-black filter_searched">
                                <span><?=$criteriosBusqueda['categoria'];?></span>
                                <a style="cursor:pointer" onclick="removeAcc('filter_1')" ><img class="img_remove_filter" src="<?=base_url(),"/img/remove.png"?>" alt="Remove"></a>
                            </div>

                            <div id="filter_2" class="select_custom_small user-decription-black filter_searched">
                                <span><?=$criteriosBusqueda['redes'];?></span>
                                <a style="cursor:pointer" onclick="removeAcc('filter_2')" ><img class="img_remove_filter" src="<?=base_url(),"/img/remove.png"?>" alt="Remove"></a>
                            </div>
                            
                            <div id="filter_3" class="select_custom_small user-decription-black filter_searched">
                                <span><?=$criteriosBusqueda['cantidad']." seguidores";?> </span>
                                <a style="cursor:pointer" onclick="removeAcc('filter_3')" ><img class="img_remove_filter" src="<?=base_url(),"/img/remove.png"?>" alt="Remove"></a>
                            </div>

                            <div id="filter_4" class="select_custom_small user-decription-black filter_searched">
                                <span><?=$criteriosBusqueda['idioma'];?></span>
                                <a style="cursor:pointer" onclick="removeAcc('filter_4')" ><img class="img_remove_filter" src="<?=base_url(),"/img/remove.png"?>" alt="Remove"></a>
                            </div>
                            <?php foreach ($criteriosBusqueda['pago'] as $key => $pag) { ?>
                                <div id="<?="filter_5".$pag['idpago']?>" class="select_custom_small user-decription-black filter_searched">
                                    <span><?=$pag['nombre'];?></span>
                                    <a style="cursor:pointer" onclick="removeAcc('<?="filter_5".$pag["idpago"];?>')" ><img class="img_remove_filter" src="<?=base_url(),"/img/remove.png"?>" alt="Remove"></a>
                                </div>
                        <?php } ?>
                            

                            <div id="filter_6" class="select_custom_small user-decription-black filter_searched">
                                <span><?=$criteriosBusqueda['pais']."/".$criteriosBusqueda['ciudad'];?></span>
                                <a style="cursor:pointer" onclick="removeAcc('filter_6')" ><img class="img_remove_filter" src="<?=base_url(),"/img/remove.png"?>" alt="Remove"></a>
                            </div>

                            <div >
                                <button type="button" class="btn btn-gray-clean-all btn-sm" style="margin-top: 10px;">Limpiar todo</button>
                            </div>

                        </div>
                    </div>
                    
                    <div class="row sidebar-filter">
                        <div class="px-2 py-3 sidebar-filter-text ">
                            <p style="font-weight: bold;">FILTRAR POR:</p>
                        
                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header sidebar-filter-text" style="margin-bottom: 8px;" id="headingOne">
                                        <button class="accordion-button sidebar-filter-text" style="font-weight: bold;" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Categoría
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show"  aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <?php if(count($busquedaActual['categoria'])>0){
                                                    foreach ($categorias as $key => $m) {
                                                        $encontrado=true;
                                                        foreach ($busquedaActual['categoria'] as $key => $cate) {
                                                            if($m['idcategoria']==$cate){?>
                                                                <div class="select_custom_small user-decription-black">
                                                                    <span><?=$m['nombrecat']?></span>
                                                                    <input class="form-check-input img_remove_filter" type="checkbox" name="<?="cat".$m['idcategoria']?>" id="<?="cat".$m['idcategoria']?>" checked>
                                                                </div>
                                                <?php       $encontrado=false;
                                                            }
                                                        }
                                                        if($encontrado){?>
                                                            <div class="select_custom_small user-decription-black">
                                                                <span><?=$m['nombrecat']?></span>
                                                                <input class="form-check-input img_remove_filter" type="checkbox" name="<?="cat".$m['idcategoria']?>" id="<?="cat".$m['idcategoria']?>">
                                                            </div>

                                                  <?php      
                                                        }
                                                    }
                                                  }else{
                                                    foreach ($categorias as $key => $m) {?>
                                                            <div class="select_custom_small user-decription-black">
                                                                <span><?=$m['nombrecat']?></span>
                                                                <input class="form-check-input img_remove_filter" type="checkbox" name="<?="cat".$m['idcategoria']?>" id="<?="cat".$m['idcategoria']?>">
                                                            </div>
                                                   <?php }
                                                }                                     
                                            ?>
                  
                                        </div>
                                    </div>
                                </div>


                                <div class="accordion-item">
                                    <h2 class="accordion-header sidebar-filter-text" style="margin-bottom: 8px;" id="headingTwo">
                                        <button class="accordion-button sidebar-filter-text collapsed" style="font-weight: bold;" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Red Social
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">

                                        <?php if(count($busquedaActual['redes'])>0){
                                                    foreach ($redes as $key => $m) {
                                                        $encontrado=true;
                                                        foreach ($busquedaActual['redes'] as $key => $cate) {
                                                            if($m['idredes']==$cate){?>
                                                                <div class="select_custom_small user-decription-black">
                                                                    <span><?=$m['nombre']?></span>
                                                                    <input class="form-check-input img_remove_filter" type="checkbox" name="<?="red".$m['idredes']?>" id="<?="red".$m['idredes']?>" checked>
                                                                </div>
                                                <?php       $encontrado=false;
                                                            }
                                                        }
                                                        if($encontrado){?>
                                                            <div class="select_custom_small user-decription-black">
                                                                <span><?=$m['nombre']?></span>
                                                                <input class="form-check-input img_remove_filter" type="checkbox" name="<?="red".$m['idredes']?>" id="<?="red".$m['idredes']?>">
                                                            </div>

                                                  <?php      
                                                        }
                                                    }
                                                  }else{
                                                    foreach ($redes as $key => $m) {?>
                                                            <div class="select_custom_small user-decription-black">
                                                                <span><?=$m['nombre']?></span>
                                                                <input class="form-check-input img_remove_filter" type="checkbox" name="<?="red".$m['idredes']?>" id="<?="red".$m['idredes']?>">
                                                            </div>
                                                   <?php }
                                                }                                     
                                            ?>

                                        </div>
                                    </div>
                                </div>

                                <div class="select_custom_small user-decription-black filter_searched" style="margin-bottom: 8px; font-weight: bold; color: #606060 !important;">
                                    Seguidores

                                </div>

                                <div style="margin-bottom: 20px;">
                                    
                                    <input type="range" min="0" value="6" max="12" step="1" class="slider" id="input">
                                    <div class="row" style="font-size: 10px;">
                                        <div class="col">
                                            <p>0</p>
                                        </div>
                                        <div class="col" style="text-align: center;">
                                            <p><span id="output"></span></p>
                                            <input type="hidden" id="seguidores2" name="seguidores2" value="<?=$busquedaActual['cantidad']?>" >
                                        </div>
                                        <div class="col" style="text-align: right;">
                                            <p>+1 millon</p>
                                        </div>
                                    </div>
                                    
                                </div>

                                <script>
                                    var values = [0,3000,5000,10000,15000,20000,25000, 30000, 50000, 100000, 300000, 500000, "+1M"];
                                    var item=6,cont=0;
                                    var valor= document.getElementById('seguidores2').value;
                                    values.forEach(element => {
                                        
                                        if (element==valor) {
                                            item=cont;
                                        }
                                        cont++;
                                    });
                                    document.getElementById('input').value=item;
                                    var input = document.getElementById('input'),
                                    output = document.getElementById('output');

                                    input.oninput = function(){
                                        output.innerHTML = values[this.value];
                                        if (values[this.value]!="+1M") {
                                            document.getElementById('seguidores2').value=values[this.value];
                                        } else {
                                            document.getElementById('seguidores2').value=1000000; 
                                        }
                                        
                                    };
                                    input.oninput();

                                </script>

                                <div class="accordion-item">
                                    <h2 class="accordion-header sidebar-filter-text" style="margin-bottom: 8px;" id="headingThree">
                                        <button class="accordion-button sidebar-filter-text collapsed" style="font-weight: bold;" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        Idioma
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            
                                            <div id="languages" class="row mt-4 justify-content-left" ></div>

                                            <div style="padding-left: 15%; padding-right: 15%">
                                                <select id="idiomaSelect2" name="idiomaSelect2"  class="form-select select-sm-profile" aria-label="Default select example">
                                                    <option <?php if ($busquedaActual['idioma']==""){?> selected <?php } ?> value="0">Selecciona un idioma</option>
                                                    <?php foreach ($idiomas as $key => $m) {?>
                                                        <option value="<?=$m['ididioma']?>" <?php if ($busquedaActual['idioma']==$m['ididioma']){?> selected <?php } ?>><?=$m['nombre']?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                    
                                        </div>
                                    </div>
                                </div>


                                <div class="accordion-item">
                                    <h2 class="accordion-header sidebar-filter-text" style="margin-bottom: 8px;" id="headingFour">
                                        <button class="accordion-button sidebar-filter-text collapsed" style="font-weight: bold;" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                        Negociación
                                        </button>
                                    </h2>
                                    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">

                                        <?php if(count($busquedaActual['pago'])>0){
                                                    foreach ($pagos as $key => $m) {
                                                        $encontrado=true;
                                                        foreach ($busquedaActual['pago'] as $key => $cate) {
                                                            if($m['idpago']==$cate['idpago']){?>
                                                                <div class="select_custom_small user-decription-black">
                                                                    <span><?=$m['nombre']?></span>
                                                                    <input class="form-check-input img_remove_filter" type="checkbox" name="<?="pago".$m['idpago']?>" id="<?="pago".$m['idpago']?>" checked>
                                                                </div>
                                                <?php       $encontrado=false;
                                                            }
                                                        }
                                                        if($encontrado){?>
                                                            <div class="select_custom_small user-decription-black">
                                                                <span><?=$m['nombre']?></span>
                                                                <input class="form-check-input img_remove_filter" type="checkbox" name="<?="pago".$m['idpago']?>" id="<?="pago".$m['idpago']?>">
                                                            </div>

                                                  <?php      
                                                        }
                                                    }
                                                  }else{
                                                    foreach ($pagos as $key => $m) {?>
                                                            <div class="select_custom_small user-decription-black">
                                                                <span><?=$m['nombre']?></span>
                                                                <input class="form-check-input img_remove_filter" type="checkbox" name="<?="pago".$m['idpago']?>" id="<?="pago".$m['idpago']?>">
                                                            </div>
                                                   <?php }
                                                }                                     
                                            ?>




                                                                              
                                        </div>
                                    </div>
                                </div>


                                <div class="accordion-item">
                                    <h2 class="accordion-header sidebar-filter-text" style="margin-bottom: 8px;" id="headingFive">
                                        <button class="accordion-button sidebar-filter-text collapsed" style="font-weight: bold;" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                        Ubicación
                                        </button>
                                    </h2>
                                    <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="select_custom_small user-decription-black" style="margin-bottom: 8px;">
                                                <select class="form-select select_custom" aria-label="Default select example" name="pais3" id="pais3" onchange='cambia_ciudades3();'>
                                                    <option value="0" selected>Selecciona país</option>
                                                    <?php foreach ($paises as $key => $m) {?>
                                                        <option value="<?=$m['idpais']?>"><?=$m['nombre']?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <script>
                                    function cambia_ciudades3(){
                                        //tomamos el valor del select paises elegido
                                        var idpais
                                    
                                        idpais = document.getElementById('pais3').value	
                                        
                                        // verificamos si el Pais está definido
                                
                                        if (idpais!=0) { 
                                            //si estaba definido, entonces coloco las opciones del pais correspondiente. 
                                            //selecciono el array del departamento adecuado 
                                            //alert("LLEGUE");
                                            document.getElementById('ciudades3').disabled=false;
                                            mis_ciudades=eval("pais_"+idpais) 
                                            mis_ciudades_id=eval("pais_"+idpais+"_id") 
                                            //alert(mis_ciudades); 
                                            //calculo el numero de municipios 
                                            num_ciudades = mis_ciudades.length +1
                                            //marco el número de municipios en el select 
                                            document.otrabusqueda.ciudades3.length = num_ciudades 
                                            //para cada pais del array, lo introduzco en el select 
                                                
                                            for(i=0;i<num_ciudades+1;i++){ 
                                                document.otrabusqueda.ciudades3.options[i+1].value=mis_ciudades_id[i]
                                                document.otrabusqueda.ciudades3.options[i+1].text=mis_ciudades[i] 
                                            }	
                                            }else{ 
                                            //si no había pais seleccionado, elimino los municipios del select 
                                            document.otrabusqueda.ciudades3.length = 1 
                                            //coloco un guión en la única opción que he dejado 
                                            document.otrabusqueda.ciudades3.options[0].value = "0" 
                                            document.otrabusqueda.ciudades3.options[0].text = "SELECCIONE UN DEPARTAMENTO" 
                                            } 
                                        }// FIN DE FUNCIONcambia_departamento
                                    </script>

                                            <div class="select_custom_small user-decription-black">
                                                <select class="form-select select_custom" aria-label="Default select example" name="ciudades3" id="ciudades3" disabled>
                                                    <option value="0" selected>Elije tu región</option>
                                                </select>
                                    </br>
                                    </br>
                                            </div>

                                            <?php 
                                                $ciudadesarray=[];
                                                $ciudadesarrayid=[];
                                                for ($i=0; $i < count($ciudades[1]) ; $i++) { 
                                                    array_push($ciudadesarray,$ciudades[1][$i]['nombre']);
                                                    array_push($ciudadesarrayid,$ciudades[1][$i]['idciudad']);
                                                }

                                                $ciudadesarray2=[];
                                                $ciudadesarrayid2=[];
                                                for ($i=0; $i < count($ciudades[2]) ; $i++) { 
                                                    array_push($ciudadesarray2,$ciudades[2][$i]['nombre']);
                                                    array_push($ciudadesarrayid2,$ciudades[2][$i]['idciudad']);
                                                }

                                                $ciudadesarray3=[];
                                                $ciudadesarrayid3=[];
                                                for ($i=0; $i < count($ciudades[3]) ; $i++) { 
                                                    array_push($ciudadesarray3,$ciudades[3][$i]['nombre']);
                                                    array_push($ciudadesarrayid3,$ciudades[3][$i]['idciudad']);
                                                }

                                                $ciudadesarray4=[];
                                                $ciudadesarrayid4=[];
                                                for ($i=0; $i < count($ciudades[4]) ; $i++) { 
                                                    array_push($ciudadesarray4,$ciudades[4][$i]['nombre']);
                                                    array_push($ciudadesarrayid4,$ciudades[4][$i]['idciudad']);
                                                }

                                                $ciudadesarray5=[];
                                                $ciudadesarrayid5=[];
                                                for ($i=0; $i < count($ciudades[5]) ; $i++) { 
                                                    array_push($ciudadesarray5,$ciudades[5][$i]['nombre']);
                                                    array_push($ciudadesarrayid5,$ciudades[5][$i]['idciudad']);
                                                }
                                            
                                            ?>
                                            <script type="text/javascript">
                                                //creamos variableas array para cada departamento
                                                
                                                var pais_1 = <?php echo json_encode($ciudadesarray);?>;
                                                var pais_1_id = <?php echo json_encode($ciudadesarrayid);?>;
                                                var pais_2 = <?php echo json_encode($ciudadesarray2);?>;
                                                var pais_2_id = <?php echo json_encode($ciudadesarrayid2);?>; 
                                                var pais_3 = <?php echo json_encode($ciudadesarray3);?>;
                                                var pais_3_id = <?php echo json_encode($ciudadesarrayid3);?>;
                                                var pais_4 = <?php echo json_encode($ciudadesarray4);?>;
                                                var pais_4_id = <?php echo json_encode($ciudadesarrayid4);?>;
                                                var pais_5 = <?php echo json_encode($ciudadesarray5);?>;
                                                var pais_5_id = <?php echo json_encode($ciudadesarrayid5);?>;

                                            </script>
                                    
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                
                    <div class="col">
                        
                        <?php $cont=4; $cerrar=false;
                            foreach ($influencer as $key => $m) { 
                            
                                if($cont==4){?>
                            <div class="row mb-3">
                                <?php 
                                    $cont=0; 
                                    $cerrar=true;  
                                    }
                                    $cont++;
                                ?>
                                <div class="col">
                                    <div class="product-item position-relative d-flex flex-column text-center">
                                        <img class="img-fluid mb-2" src="<?php echo base_url('uploads')."/".$m['foto_perfil'];?>" alt="">
                                        <h6 class="user-decription-black"><?=$m['nombreinflu'] ?><br> <?=$m['alias'] ?> <br><?=$m['nombrecat']?> </h6>
                                        <div class="container-fluid">
                                            <a href="<?php echo base_url()."/perfil/".$m['idinfluencer'];?>"><button type="button" class="btn btn-ver-perfil btn-sm btn-on-white">Ver perfil</button></a>
                                        </div>
                                    </div>
                                </div>
                            
                        <?php if($cerrar){?>
                            </div>
                        <?php $cerrar=false; } ?>

                        <?php  } ?>
                        
                            


                            


                            <div class="text-center">
                                <div class="pagination">
                                    <a href="#">&laquo;</a>
                                    <a href="#">1</a>
                                    <a class="active" href="#">2</a>
                                    <a href="#">3</a>
                                    <a href="#">4</a>
                                    <a href="#">5</a>
                                    <a href="#">6</a>
                                    <a href="#">&raquo;</a>
                                </div>
                            </div>


                            <div class="my-5 d-flex justify-content-center text-center">
                                <div class="btn-register">
                                    <button type="submit" class="btn btn-login btn-lg btn-register-width user-decription-black" style="font-size: 20px; width: fit-content;" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#confirmation-modal">VOLVER A BUSCAR</button>
                                </div>
                            </div>
                

                    </div>
                        </form>
            </div>
        </form>
    </div>
    <!-- Content search result End -->

    
    <!-- ============================================================== -->
                            <!-- MODALES -->
    <!-- ============================================================== -->
    
    <!-- Modal Nosotros -->
    <div class="modal fade" id="modal-nosotros" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 0rem; border: 2px solid #000; background-color: #00ffff">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    
                    <div class="text-center user-decription-black" style="padding: 0 12px; font-size: 18px">
                        <p>BUSCOINFLUENCERS es una marca registrada de WD Studios Corporation S.A.S. Somos una organización abierta, emprendedora y diversa con el gusto por innovar, dedicada al diseño y desarrollo de portales de servicios, tiendas virtuales y soluciones digitales para mercados nacionales e internacionales, creando alternativas para la comercialización de productos y servicios para todo público.</p>
                    </div>
                    
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
    <!-- Modal Nosotros -->

