<style>
    .dashed-2 {
  border: none;
  height: 2px;
  background: #000;
  opacity: 1;
  background: repeating-linear-gradient(90deg,#000,#000 10px,transparent 6px,transparent 15px);
}

.form-control:focus {

    box-shadow: 0 0 0 0.25rem rgb(117 118 118 / 25%) !important;
}
</style>


<div class="container" >
    
    <div style="padding: 0 25px 50px">

        <div class="row">
            <!-- Profile Photo -->
            <div class="col-lg-3 text-center " style="background-color: #FF9933; ">
                <img id="img-profile" class="my-profile-photo mb-3" style="border-radius: 15%;" src="<?php echo base_url("/uploads")."/".$influencer['foto_perfil']?>" >
            </div>
            <!-- Info User -->
            <div class="col-lg-9 " style="font-family: 'Bemio Italic'; font-weight: 400; color: #000; background-color: #a9fff6">
                <div class="row align-items-end" style="height: 180px; ">
                    <div class="row">
                        <p style="margin-bottom: 8px; font-size: 30px; line-height: 30px;"><?php echo $influencer['nombreinflu']?></p>
                        <p style="margin-bottom: 8px; font-size: 26px; line-height: 30px;"><?php echo $influencer['alias']?></p>
                        <p style="margin-bottom: 8px; font-size: 26px; line-height: 30px;">Colombia / Cali</p>
                    </div>       
                </div>

                <hr class="dashed-2">
            
            </div>
        </div>

        <div class="row" style="padding-top: 25px;">
            <!-- Stars ratio / comments -->
            <div class="col-lg-3" style="background-color: #FF9933; text-align: end;">
                <?php 
                    $puntaje=0;
                    $porcentaje=0;
                    $promedio=0;
                    $cont=0;
                    
                    foreach ($mensajes as $key => $m) {
                        $puntaje=$puntaje+$m['valoracion'];
                        $cont++;
                    }
                    if($cont!=0){
                        $promedio=$puntaje/$cont;
                        $porcentaje=($promedio)/5*100;   
                    }
                ?>
                
                <div class="d-flex justify-content-center align-items-center">
                    <div class="card p-3 text-center" style="background-color: transparent;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="star-rating" style="width: 160px; height: 32px; background-size: 32px;" data-rating="<?php echo $retVal = ($porcentaje==0) ? 5 : $promedio ; ?>" title="<?php echo $promedio."/5" ?>">
                                <div class="star-value rating-comment" style="background-size: 32px; width: <?=$porcentaje?>%;">
                            
                                </div>
                            </div>
                        </div>
                        <h3 class="user-decription-black" style="color: #606060"><?=count($mensajes)?> valoraciones</h3>
                    </div>
                </div>

            </div>

            <!-- User's Social Media info -->
            <div class="col-lg-9 " style="background-color: #a9fff6">
                <div class="row align-items-center" >
                    <div class="flex-container">
                        <?php 
                            for ($i=0; $i < count($redes) ; $i++) { 
                                if($arregloredes[$i]['activa']!=0) {
                        ?>
                            <div>
                                <div>
                                    <a class="profile-sm-logo" style="cursor:pointer" href="<?php if($arregloredes[$i]['url']!=null){echo $arregloredes[$i]['url'].$redes[$i]['user'];}else{echo "#";}?>" target="_blank" ><img src=<?php echo base_url('img/iconos')."/".$arregloredes[$i]['icono'] ?>></a>
                                </div>

                                <div>
                                    <h3 class="profile-followers" style="margin-top: 10px;"><?php echo $redes[$i]['cant_seguidores'] ?></h3>
                                </div>
                                
                                <div>
                                    <p class="profile-decription-black">Seguidores</p>
                                </div>
                            </div>
                        <?php
                                } 
                            }
                        ?>
                    </div>       
                </div>
            </div>
        </div>

        
        <div class="row" style="padding-top: 25px;">
            <!-- Video -->
            <div class="col-lg-3 " style="background-color: #FF9933;">

                <?php if($influencer['video']!=""){ ?>
                    
                    <div id="video" class="d-flex justify-content-center align-items-center mt-4" >
                        <!-- video size width="800" height="400" -->
                        <video controlsList="nodownload" oncontextmenu="return false;" height="auto" style="max-height: 480px; max-width:100%; background-color: #000;border-radius: 12px;" controls>
                            <source src="<?php echo base_url('video').'/'.$influencer['video']?>" type="video/mp4">
                            <source src="movie.ogg" type="video/ogg">
                            Su video no es soportado
                        </video>
                    </div>

                <?php } ?>

                <script>
                    $("video").on("contextmenu",function(e){
                        return false;
                    });
                </script>

            </div>
            
            <!-- Gallery -->
            <div class="col-lg-9" style="background-color: #a9fff6">

                <div class="text-left my-4 profile-decription-black">
                    <p><?=$influencer['resenia'];?></p>
                </div>
                
                <div style="align-self: flex-end;">
                <div class="d-flex justify-content-left flex-wrap">
                               
                    <?php foreach ($misfotos as $key => $m) {?>
                        <a class="gallery-profile-show-margin" href="<?php echo base_url('uploads')."/".$m['url']?>" data-lightbox="photos">
                            <div class="gallery-profile-show">
                                <img class="perfil-gal-img" src="<?php echo base_url('uploads')."/".$m['url']?>">
                            </div>
                        </a>
                    <?php } ?>

                </div>
                </div>
            </div>

        </div>
        
        <div class="row" style="padding-top: 25px; ">
            
            <!-- Promo -->
            <div class="col-lg-4 d-flex justify-content-start" style="background-color: #9d81d1; color: #fff">
                <div class="card" style="height:240px; width: 250px; border-radius: 15px; background-color: #5554ed; background-image: linear-gradient(to bottom right, #5554ed, #be4bd6);" >
                    <div class="card-body">
                        <h5 class="card-title" style="font-family: 'Bemio Italic'; font-weight: 400; color: #fff; letter-spacing: 1px;">Promo</h5>
                        <p class="card-text text-center"><?php echo $influencer['oferta']; ?></p>
                    </div>
                </div>
            </div>
            <!-- Idiomas -->
            <div class="col-lg-4 d-flex justify-content-center" style="background-color: #9d81d1; color: #fff">
                <div class="card" style="height:240px; width: 250px; border-radius: 15px; background-color: #e05fbf; background-image: linear-gradient(to bottom right, #e05fbf, #f6a984);" >
                    <div class="card-body">
                        <h5 class="card-title" style="font-family: 'Bemio Italic'; font-weight: 400; color: #fff; letter-spacing: 1px;">Idioma</h5>
                        <p class="card-text text-center">
                            <?php foreach ($misidiomas as $key => $m) {
                                echo $m['nombre']."<br>"; 
                            } ?></p>
                    </div>
                </div>
            </div>
            <!-- Pagos -->
            <div class="col-lg-4 d-flex justify-content-end" style="background-color: #9d81d1; color: #fff">
                <div class="card" style="height:240px; width: 250px; border-radius: 15px; background-color: #e05ebf; background-image: linear-gradient(to bottom right, #e05ebf, #f6a983);" >
                    <div class="card-body">
                        <h5 class="card-title" style="font-family: 'Bemio Italic'; font-weight: 400; color: #fff; letter-spacing: 1px;">Pagos</h5>
                        <p class="card-text text-center">
                            <?php foreach ($pagos as $key => $m) {
                                echo $m['nombre']."<br>";
                            } ?>
                        </p>
                    </div>
                </div>
            </div>
 
        </div>

        <!-- Experiencias con marcas -->
        <div class="row d-flex justify-content-center" style="padding-top: 12px;">
            <div class="col-lg-12">
                <div class="text-center mt-4" style="padding-left: 20%; padding-right: 20%; margin-bottom: 0px; position: relative;">
                    <h2 class="section-title profile-section-title" style="font-family: 'Bemio Italic'; font-weight: 400; color: #fff; letter-spacing: 1px; width: fit-content; margin: auto; padding-left: 15px; padding-right: 15px;">Experiencias con marcas</h2>
                                
                </div>
        
                <div class="justify-content-center text-center" style=" border: 1px dashed #000; border-radius: 15px; padding-top: 30px;">
                    <div class="containTag">
                        <div id="tagcloud" class="user-decription-black" style="text-align: center;">
                            
                            <h2 class="user-decription-black" style="font-size: 20px;"><?php 
                                $array_num = count($marcas);
                                for ($i = 0; $i < $array_num; ++$i){
                                    
                                    if ( ($i+1) !== $array_num) {
                                        // print with star separator
                                        echo $marcas[$i]['nombre']."<i class='bi bi-star-fill' style='padding-left: 15px; font-size: 15px; padding-right: 15px;'></i>";

                                    } else {
                                        // print without star separator
                                        echo $marcas[$i]['nombre'];
                                    }
                                        
                                } ?>             
                            </h2>
                            
                        </div>
                    </div>
                </div>

            </div>
        </div>


             
 

        <!-- Comentarios de clientes --> 
        <?php if (count($mensajes)>0) { ?>

            <div class="text-center" style="padding-left: 20%; padding-right: 20%; padding-top: 25px; margin-bottom: 0px; position: relative;">
                <h2 class="section-title profile-section-title" style="font-family: 'Bemio Italic'; font-weight: 400; color: #fff; letter-spacing: 1px; width: fit-content; margin: auto; padding-left: 15px; padding-right: 15px;">Reseñas</h2>           
            </div>

            <!-- Solo si hay un solo comentario -->   
            <?php if (count($mensajes)==1) {  ?>

                <div class="row justify-content-center" style="padding-top: 12px; ">
                    <div class="card card-comments" style="margin: auto; color: #000";>               
                        <div class="card-body" >
                            <h5 class="card-title user-decription-black" style="font-size: 20px"><?=$mensajes[0]['nombre']?><br><?=$mensajes[0]['empresa']?></h5>
            
                            <div class="star-rating mb-3" style="width: 100px; height: 20px; background-size: 20px;" data-rating="<?=$mensajes[0]['valoracion'] ?>" title="<?=$mensajes[0]['valoracion']."/5" ?>">
                                <?php $por=$mensajes[0]['valoracion']/5*100; ?>  
                                <div class="star-value rating-comment" style="background-size: 20px; width: <?php echo $por."%" ?>;"></div>
                            </div> 
                            <p class="card-text user-decription-black"><?=$mensajes[0]['cuerpo']?> </p>
                                                    
                        </div>
                    </div>
                </div>    
            

            <!-- Solo si hay 2 comentarios --> 
            <?php } if (count($mensajes)==2) { ?>

                <div class="row justify-content-center" style="padding-top: 12px; ">
                    <div class="col-4">
                        <div class="card card-comments" style="margin: auto; color: #000";>               
                            <div class="card-body" > 
                                <h5 class="card-title user-decription-black" style=" font-size: 20px"><?=$mensajes[0]['nombre']?><br><?=$mensajes[0]['empresa']?></h5>
                                
                                <div class="star-rating mb-3" style="width: 100px; height: 20px; background-size: 20px;" data-rating="<?=$mensajes[0]['valoracion'] ?>" title="<?=$mensajes[0]['valoracion']."/5" ?>">
                                    <?php $por=$mensajes[0]['valoracion']/5*100; ?>  
                                    <div class="star-value rating-comment" style="background-size: 20px; width: <?php echo $por."%" ?>;"></div>
                                </div> 
                                <p class="card-text user-decription-black"><?=$mensajes[0]['cuerpo']?> </p> 
                                    
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card card-comments" style="margin: auto; color: #000";>  
                            <div class="card-body"> 
                                <h5 class="card-title user-decription-black" style="font-size: 20px"><?=$mensajes[1]['nombre']?><br><?=$mensajes[1]['empresa']?></h5>
                                
                                <div class="star-rating mb-3" style="width: 100px; height: 20px; background-size: 20px;" data-rating="<?=$mensajes[1]['valoracion'] ?>" title="<?=$mensajes[1]['valoracion']."/5" ?>">
                                    <?php $por=$mensajes[1]['valoracion']/5*100; ?>  
                                    <div class="star-value rating-comment" style="background-size: 20px; width: <?php echo $por."%" ?>;"></div>
                                </div> 
                                <p class="card-text user-decription-black"><?=$mensajes[1]['cuerpo']?> </p>
                                    
                            </div>
                        </div>
                    </div>
                </div> 
                
                
            <!-- Mas de 3 comentarios --> 
            <?php }if (count($mensajes)>=3) {?>

                <div class="row" style="padding-top: 12px; ">
                    <div class="owl-five owl-carousel owl-theme" style="" >
                        <?php foreach ($mensajes as $key => $m) {?>
                            <div class="d-flex justify-content-center" style="background-color: #9d81d1; color: #fff">
                                
                                <div class="card card-comments" style="margin-bottom: 5px; color: #000">               
                                    <div class="card-body ">
                                        <h5 class="card-title user-decription-black" style="font-size: 20px"><?=$m['nombre']?><br><?=$m['empresa']?></h5>
                                
                                        <div class="star-rating mb-3" style="width: 100px; height: 20px; background-size: 20px;" data-rating="<?=$m['valoracion'] ?>" title="<?=$m['valoracion']."/5" ?>">
                                            <?php $por=$m['valoracion']/5*100; ?>  
                                            <div class="star-value rating-comment" style="background-size: 20px; width: <?php echo $por."%" ?>;"></div>
                                        </div> 
                                        <p class="card-text user-decription-black"><?=$m['cuerpo']?> </p>

                                    </div>
                                </div>
                            </div>
                        <?php }?>
                    </div>
                </div>
            <?php } ?>

        <?php } ?> 


        <?php if(!isset($_SESSION['idinfluencer'])){?>

        <div class="row justify-content-center my-5">
            <button type="button" class="btn btn-contactar-influencer btn-lg shrink-on-hover" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#contactar-modal">CONTACTAR ESTE INFLUENCER</button>
        </div>


        
        <div class="col-lg-12" style="background-color: #e8e8e8; border-radius: 15px; ">

            <div class="row ">

                <div class="col-6 align-self-center" style="font-family: 'Bemio Italic'; font-weight: 400; color: #000;">
                    <div class="row text-center ">
                        <p style="margin-bottom: 8px; font-size: 28px; line-height: 30px;">¿Te gustó este influencer? </p>
                        <p style="margin-bottom: 8px; font-size: 35px; line-height: 40px;">¡Contáctalo y lleva tu <br>
                            estrategia digital a<br>
                            otro nivel!</p>
                        <div class="row justify-content-center my-2">
                            <button type="button" class="btn btn-contactar-influencer btn-lg shrink-on-hover" style="font-family: 'Bemio Italic'; font-weight: 400; padding: 4px 20px; box-shadow: 2px 3px 7px 0px #000;" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#contactar-modal">Contactar</button>
                        </div>
                    </div>
                </div>


                <div class="col-6 justify-content-center" style="font-family: 'Bemio Italic'; font-weight: 400; color: #000;">
                    
                    <div class="row text-center mt-3">
                        <p style="margin-bottom: 8px; font-size: 24px; line-height: 30px;">Califica a este influencer </p>
                        
                        <div class="text-center user-decription-black"  >
                            <span>Los campos en * son requeridos</span>
                        </div>

                        <form action="create" method="POST" class="register-form pt-2" id="mensaje" name="mensaje" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                            
                            <div class="form-group" style="margin: 4px 0px; ">
                                <input type="text" class="form-control" style="border-radius: 10px;" id="nombre" placeholder="Nombre *" name="nombre" required>
                            </div>
                        
                            <input type="hidden" id="influencerid31" name="influencerid31" value="<?=$influencer['idinfluencer']?>">

                            <div class="form-group" style="margin: 4px 0px;">
                                <input type="text" class="form-control" style="border-radius: 10px;" id="empresa" placeholder="Empresa *" name="empresa">
                            </div>

                            <div class="form-group" style="margin: 4px 0px;">
                                <textarea id="cuerpo" name="cuerpo" class="form-control" style="border-radius: 10px;" placeholder="Comentario *" rows="4" style="resize: none;" required></textarea>
                            </div>

                            <div class="form-group row" style="margin: 4px 0px;">
                                <label for="inputBusiness" class="col-sm-5 col-form-label" style="padding-left: 0px">Valoración *</label>
                                <div class="col-sm-6" onClick="cambio()">
                                    <div id="rater" name="rater" ></div>
                                </div>
                            </div>
                                
                            <input type="hidden" id="valoracion" name="valoracion" value=" ">
                            <script>
                                function cambio() {
                                    document.querySelector("#valoracion").value = document.getElementById("rater").dataset.rating;
                                }
                            </script>

                            <button type="submit" class="mt-2 mb-4 btn btn-contactar-influencer btn-lg shrink-on-hover" style="font-family: 'Bemio Italic'; font-weight: 400; padding: 4px 20px; box-shadow: 2px 3px 7px 0px #000;">Enviar</button>
                            
                        </form>    
                    </div>

                </div>
            </div>
        </div>

        <?php } ?>
    <!-- Content Profile End -->

        



    </div>
</div>

   





    
    <!-- ============================================================== -->
                         <!-- MODALES -->
    <!-- ============================================================== -->

    <!-- Modal Contact Influencer Start -->
    <div class="modal fade" id="contactar-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
       
            <div class="modal-content" style="border-radius: 0rem; border: 2px solid #000;">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body " style="padding-right: 65px; padding-left: 65px; padding-top: 0px;">
                    <form action="correo" method="POST" class="register-form pt-2" id="mensajecorreo" name="mensajecorreo" enctype="multipart/form-data">
                        <?= csrf_field() ?>   
                        
                        <input type="hidden" id="influencerid32" name="influencerid32" value="<?=$influencer['idinfluencer']?>">
                    

                        <div class="d-flex justify-content-center mb-4">
                            <div class="section-title title-modal-contactar">
                                Contactarlo
                            </div>
                        </div>


                        <div class="col ">
                            <input class="input-redes" type="text" name="nombrecontacto" id="nombrecontacto" placeholder="Tu nombre">
                        </div>

                        <div class="col pt-2" style="    margin-bottom: 0px;">
                            <input class="input-redes" type="text" name="empresacontacto" id="empresacontacto" placeholder="Empresa">
                        </div>

                        <div class="user-decription-black " style="font-size: 25px;" >
                            Datos de contacto
                        </div>

                        <div class="row">
                            <div class="col-sm-3">
                                <img class="img-hecho" style="width: 50%; padding-top: 15px;" src=<?php echo base_url("img/contact-email.png")?> >
                            </div>
                            <div class="col-sm-9">
                                <div class="col pt-2" style="margin-bottom: 0px;">
                                    <input class="input-redes" type="text" name="emailcontacto" id="emailcontacto" placeholder="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">

                                <div style="display: flex;">
                                    <div style="flex: 33.33%;">
                                        <img class="img-hecho" style="width: 100%; padding-top: 15px;" src=<?php echo base_url("img/contact-ws.png")?> >    
                                    </div>
                                    <div style="flex: 33.33%;">
                                        
                                        <img class="img-hecho" style="width: 100%; padding-top: 15px;" src=<?php echo base_url("img/contact-number.png")?> >
                                    </div>
                                </div>    
                            </div>
                            <div class="col-sm-9">
                                <div class="col pt-2" style="margin-bottom: 0px;">
                                    <input class="input-redes" type="text" name="celularcontacto" id="celularcontacto" placeholder="">
                                </div>
                            </div>
                        </div>

                        <div class="pt-2">
                            <textarea class="input-redes" rows="4" style="resize: none;" placeholder="Escribe aquí" name="cuerpocontacto" id="cuerpocontacto"></textarea>
                        </div>

                        <div class="form-check user-decription-black mt-2" style="color: #606060">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                            <span>AUTORIZO de manera previa, expresa,
                            informada y explícita, a WD Studios Corp, para
                            el uso y tratamiento de mis datos. <a style="font-weight: bold; color: #606060;" href="/privacidad" target="_blank">Ver aquí.</a>
                            </span>
                        </div> 

                        <div class="text-center mt-2">
                            <button type="submit" class="btn btn-ver-perfil btn-sm btn-on-white" style="padding: 4px 30px !important;">Enviar</button>
                        </div>

                    </form>
                </div>

                <div class="modal-footer"></div>
            </div>
            </div>
       
    </div>
    <!-- Modal Contact Influencer End -->


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

