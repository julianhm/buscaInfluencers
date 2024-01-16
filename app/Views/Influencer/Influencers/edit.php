<?php if(session()->get('idinfluencer')!=$influencer['idinfluencer']){
            return redirect()->to(base_url())->with('mensaje', 'Error de Validación');
        }else if ((time() - session()->get('time')) > 3600){
            session_destroy();
        }?>




     <!-- Content Mi Perfil Start -->
  
     <div class="container-fluid my-profile-margin-x">

<div class="sidebar">

    <div class="px-2 user-decription">
        
            
        <div class="user-decription-black filter_searched" style="border-bottom: 1px solid #000;">
                <a href="<?php echo base_url('influencer')."/edit/".$influencer['alias']?>"><b>Editar perfil</b></a>
        </div>
        <div class="user-decription-black filter_searched" style="border-bottom: 1px solid #000;">
            <a href="<?php echo base_url("/perfil")."/".$influencer['alias']?>" target="_blank"><b>Ver perfil</b></a>
        </div>

        <div class="user-decription-black filter_searched" style="border-bottom: 1px solid #000;">
            <a href="<?php echo base_url("influencer")."/mensajes/".$influencer['alias']?>"><b>Mensajes</b></a>
        </div>
        
    </div>
    
</div>



        <div class="content">
         
            <div class="mb-3 mt-4" style="border-top: 1px solid #000;">
                <h2 class="mypro-section-title">Hola de nuevo <?=$influencer['nombreinflu'];?>!</h2>
            </div>


<!-- ===================================================================== -->
<!-- ------------------CARGAR UNA NUEVA FOTO DE PERFIL V2 AJAX--------------->
<!-- ===================================================================== -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

<style>

.image_area {
  position: relative;
}

.img-upload {
    display: block;
    max-width: 100%;

    opacity: 1;
    width: 100%;
    transition: .5s ease;
    backface-visibility: hidden;
}


.img-responsive {
    display: block;
    max-width: 75%;
    height: auto;
    aspect-ratio: 1 / 1;
}

.img-circle {
    border-radius: 50%;
}

.preview {
      overflow: hidden;
      width: 160px; 
      height: 160px;
      margin: 10px;
      border: 1px solid red;
}

.modal-lg{
      max-width: 800px !important;
}

.overlay {
    transition: .5s ease;
  opacity: 0;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
  text-align: center;
}

.image_area:hover .img-upload {
  opacity: 0.3;
  cursor: pointer;
}

.image_area:hover .overlay {
  opacity: 1;
  cursor: pointer;
}

.text {
  color: #000;
  font-size: 20px;
  position: absolute;
  top: 50%;
  left: 50%;
  -webkit-transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
  text-align: center;
  width: max-content;
}

</style>


<div class="" align="center">
    
	<br />
    <div class="row">
        <div class="col-md-4">
            <div class="image_area">
                <form method="post">
                    <label for="upload_image">
                        <img src="<?php echo base_url("/uploads")."/".$influencer['foto_perfil']?>" id="uploaded_image" class="my-profile-photo img-upload img-responsive img-circle " />
                        <div class="overlay">
                            <div class="text user-decription-black">Subir foto de perfil</div>
                        </div>
                        <input type="file" name="image" class="image" id="upload_image" style="display:none" required>
                    </label>
                </form>
            </div>
        </div>

        <div class="col-md-4">
            &nbsp;
        </div>   

        <!-- Modal MODIFICAR FOTO PERFIL -->
        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content" style="border-radius: 0rem; border: 2px solid #000;">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="padding-top: 0px;">
                        <div class="user-decription-black text-center" style="font-size: 25px;" >
                            <p>Recorta la imagen antes de subirla</p>
                        </div>
                        <div class="img-container">
                            <div class="row">
                                <div class="col-md-8">
                                    <img class="img-upload" src="" id="sample_image" />
                                </div>
                                <div class="col-md-4">
                                    <div class="preview"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-white-normal btn-lg" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
                        <button type="submit" class="btn btn-white-normal btn-lg" id="crop">Subir foto</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- FINAL MODAL ACTUALIZAR FOTO PERFIL -->

	</div>
</div>

<script>

$(document).ready(function(){
    	
	var $modal = $('#modal');
	var image = document.getElementById('sample_image');
	var cropper;

	$('#upload_image').change(function(event){
    	var files = event.target.files;
    	var done = function (url) {
      		image.src = url;
      		$modal.modal('show');
    	};

    	if (files && files.length > 0)
    	{
            reader = new FileReader();
            reader.onload = function (event) {
                done(reader.result);
            };
            reader.readAsDataURL(files[0]);
    	}
	});

	$modal.on('shown.bs.modal', function() {
    	cropper = new Cropper(image, {
    		aspectRatio: 1,
    		viewMode: 3,
    		preview: '.preview'
    	});
	}).on('hidden.bs.modal', function() {
   		cropper.destroy();
   		cropper = null;
	});

	$("#crop").click(function(){
        
    	canvas = cropper.getCroppedCanvas({
      		width: 400,
      		height: 400,
    	});

    	canvas.toBlob(function(blob) {
        	;
        	var reader = new FileReader();
         	reader.readAsDataURL(blob); 
         	reader.onloadend = function() {
            	var base64data = reader.result;  
            	$.ajax({
                    type : "POST",
            		url: "<?php echo base_url('influencer/cambiarFoto'); ?>",
                	                	
                	data: {image: base64data, id: <?=$influencer['idinfluencer']?>},
                	success: function(data){
                    	//console.log(data);
                    	$modal.modal('hide');
                    	$('#uploaded_image').attr('src', data);
                    
                	}
              	});
         	}
    	});
    });
	
});
</script>

           
            
<!-- ============================================================== -->
<!-- ------------------CARGAR UNA NUEVA FOTO DE PERFIL --------------->
<!-- 
           
                <div class="row g-0 mb-3">
                    <div class="col-lg-2">
                        <div>
                            <img id="img-profile" class="my-profile-photo" src="" >
                        </div>
                    </div>

                      
                    <div class="col-lg-8 d-flex align-items-center my-profile-justify-margin">
                       <button type="button" data-id="btn-profile" class="open-send btn btn-white-normal btn-lg"  data-bs-toggle="modal" data-bs-target="#modal-upload-image">Subir foto de perfil</button> 
                    </div>  
                </div>
 -->
           
<!-- ============================================================== -->
<!-- ------------------Actualizar PERFIL ----------------------------->
<!-- ============================================================== -->
        <form action="/influencer/actualizarPerfil" method="POST" class="register-form pt-2" enctype="multipart/form-data">
            
            <input type="hidden" id="influencerid3" name="influencerid3" value="<?=$influencer['idinfluencer']?>">
            
            <div class="row g-2 mb-3">
                <div class="col-lg-12">
                    <div>
                        <img class="icon-input" src="<?php echo base_url("img/icon-user.png")?>" ><span class="user-decription-black" style="margin-left: 12px; font-size: 18px;">Nombre</span>
                        <input class="input-modify my-profile-input-line" type="text" name="nombredit" id="nombredit" placeholder="<?=$influencer['nombreinflu']?>" value="<?=$influencer['nombreinflu']?>">
                    </div>
                </div> 
                <div class="col-lg-12">
                    <div>
                        <img class="icon-input" src="<?php echo base_url("img/icon-alias.png")?>" ><span class="user-decription-black" style="margin-left: 12px; font-size: 18px;">Alias</span>
                        <input class="input-modify my-profile-input-line" type="text" name="aliasedit" id="aliasedit" placeholder="<?=$influencer['alias']?>" value="<?=$influencer['alias']?>" disabled readonly=»readonly»>
                    </div>
                </div> 
                <div class="col-lg-12">
                    <div>
                        <img class="icon-input" src="<?php echo base_url("img/icon-user.png")?>" ><span class="user-decription-black" style="margin-left: 12px; font-size: 18px;">Usuario</span>
                        <input class="input-modify my-profile-input-line" type="text" name="usuarioedit" id="usuarioedit" placeholder="<?=$influencer['usuario']?>" value="<?=$influencer['usuario']?>">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div>
                        <img class="icon-input" src="<?php echo base_url("img/icon-email.png")?>" ><span class="user-decription-black" style="margin-left: 12px; font-size: 18px;">Correo electrónico</span>
                        <input class="input-modify my-profile-input-line" type="text" name="email" id="email" placeholder="<?=$influencer['correo']?>" value="<?=$influencer['correo']?>" disabled>
                    </div>
                </div> 
                <div class="col-lg-12">
                    <div>
                        <img class="icon-input" style="width: 18px;" src="<?php echo base_url("img/icon-location.png")?>" ><span class="user-decription-black" style="margin-left: 12px; font-size: 18px;">País</span>
                        <input class="input-modify my-profile-input-line" type="text" name="pais" id="pais" placeholder="<?=$pais['nombre']?>" value="<?=$pais['nombre']?>" disabled>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div>
                        <img class="icon-input" style="width: 18px;" src="<?php echo base_url("img/icon-location.png")?>" ><span class="user-decription-black" style="margin-left: 12px; font-size: 18px;">Ciudad</span>
                        <input class="input-modify my-profile-input-line" type="text" name="ciudad" id="ciudad" placeholder="<?=$ciudad['nombre']?>" value="<?=$ciudad['nombre']?>"disabled>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-login btn-lg btn-register-width user-decription-black" style="font-size: 20px; width: fit-content; padding: 12px 45px;">ACTUALIZAR PERFIL</button>
            </form>
            <div class="mb-3 mt-4" style="border-top: 1px solid #000;">
                <h2 class="mypro-section-title">Mis cuentas</h2>
            </div>

<!-- ============================================================== -->
<!-- ------------------ELIMINAR REDES SOCIALES     --------------->
<!-- ============================================================== -->                    
            <div id="accounts" class="row mt-4">
            <?php for ($i=0; $i < count($redes); $i++) { 
               ?>
            <form action="/influencer/eliminarRedes" method="POST" class="col-lg-6 pt-2" id="elminarRedes" name="elminarRedes" enctype="multipart/form-data">
                
            <input type="hidden" id="redeseliminar" name="redeseliminar" value="<?php echo $redesInfluencer[$i]['id']?>">
            <input type="hidden" id="influencerid2" name="influencerid2" value="<?=$influencer['idinfluencer']?>">
                                   
                
                <div id="sm_1">
                    <div class="row mb-3">
                        <div class="col-md-auto">
                            <div>
                                <input style="cursor:pointer" type="image" src="<?php echo base_url("img/remove-acc.png")?>" >
                            </div>
                        </div>
                        <div class="col-md-auto my-profile-logo-sm-align">
                            <img src="<?php echo base_url("img/iconos")."/".$redes[$i]['icono']?>" >
                        </div>
                        <div class="col input-sm-align">
                            <input class="input-redes" type="text" name="textuser" id="textuser" value="<?=$redesUsuarios[$i]?>" disabled>
                        </div> 
                    </div>
                </div>
                
            </form>
            <?php } ?>

                
            </div>

<!-- ============================================================== -->
<!-- ------------------ CARGA UN MODAL PARA AGREGAR REDES SOCIALES---->
<!-- ============================================================== --> 
            <div class="col-lg-12 d-flex align-items-center" style="justify-content: left;">
                <button type="button" class="btn btn-white-normal btn-lg"  data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#add-acc-modal">+ &nbsp; Adicionar</button>
            </div> 


<!-- ============================================================== -->
<!-- ------------------ELIMINAR CATEGORIAS     --------------->
<!-- ============================================================== --> 
            
            <div class="mb-3 mt-5" style="border-top: 1px solid #000;">
                <h2 class="mypro-section-title">Tus temas y contenidos</h2>
            </div>

                  
                <div id="topics" class="row mt-4">

                    <?php for ($i=0; $i < count($categorias); $i++) {?>

                        <form action="/influencer/elminarCategoria" method="POST" class="col-lg-3 pt-2" id="elminarCategoria" name="elminarCategoria" enctype="multipart/form-data">
                        
                            <input type="hidden" id="categoriaeliminar" name="categoriaeliminar" value="<?php echo $categoriainfluencer[$i]['id']?>">
                            <input type="hidden" id="influencerid3" name="influencerid3" value="<?=$influencer['idinfluencer']?>">
             
                            <div id="exp_1" class="row">
                                <div class="row mb-3">
                                    <div class="col-md-auto my-profile-width-remove">
                                        <div>
                                            <input style="cursor:pointer" type="image" src="<?php echo base_url("img/remove-acc.png")?>" >

                                        </div>
                                    </div>
                                    <div class="col-md-auto user-decription-black my-profile-align-txt-content">
                                        <?=$categorias[$i]['nombrecat']?>
                                    </div>
                                </div>
                            </div>
                        </form>
                    <?php } ?>
                    
                </div>
            

            <br>
            <br>
<!-- ============================================================== -->
<!-- ------------------ CARGA UN MODAL PARA AGREGAR CATEGORIAS-------->
<!-- ============================================================== --> 
            
            <div class="col-lg-12 d-flex align-items-center" style="justify-content: left;">
                <button type="button" class="btn btn-white-normal btn-lg"  data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#add-cat-modal">+ &nbsp; Adicionar</button>
            </div>

            <div class="mb-3 mt-5" style="border-top: 1px solid #000;">
                <h2 class="mypro-section-title">Tus idiomas</h2>
            </div>
<!-- ============================================================== -->
<!-- ------------------ ELIMINAR IDIOMAS ----------------------------->
<!-- ============================================================== --> 
            
                <div id="languages" class="row mt-4">
                         
                    <?php for ($i=0; $i <count($idiomas) ; $i++) {?>
                        <form action="/influencer/eliminarLenguaje" method="POST" class="col-lg-3 pt-2" id="eliminarLenguaje" name="eliminarLenguaje" enctype="multipart/form-data">
                                                
                            <input type="hidden" id="idiomaeliminar" name="idiomaeliminar" value="<?php echo $idiomainfluencer[$i]['id']?>">
                            <input type="hidden" id="influencerid5" name="influencerid5" value="<?=$influencer['idinfluencer']?>">
                                          
                            <div id="exp_1" class="row">
                                <div class="row mb-3">
                                    <div class="col-md-auto my-profile-width-remove">
                                        <div>
                                            <input style="cursor:pointer" type="image" src="<?php echo base_url("img/remove-acc.png")?>" >
                                        </div>
                                    </div>
                                    <div class="col-md-auto user-decription-black my-profile-align-txt-content">
                                        <?=$idiomas[$i]['nombre'] ?> 
                                    </div>
                                </div>
                            </div>
                        </form>
                    <?php } ?>
                </div>

<!-- ============================================================== -->
<!-- ------------------ CARGA UN MODAL PARA AGREGAR IDIOMAS----------->
<!-- ============================================================== --> 

                <div class="col-lg-12 d-flex align-items-center mt-4" style="justify-content: left;">
                        
                <button type="button" class="btn btn-white-normal btn-lg"  data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#add-lan-modal">+ &nbsp; Adicionar</button>
                    </div> 
                

            

            


            <div class="mb-3 mt-5" style="border-top: 1px solid #000;">
                <h2 class="mypro-section-title">Mi video</h2>
            </div>

<!-- ============================================================== -->
<!-- ------------------ ELIMINAR VIDEO ------------------------------->
<!-- ============================================================== --> 
            <div class="row mb-2 user-decription-black">
                        
                    <div id="video-prev" class="col-lg-8">
                    <?php if ($influencer['video']!=null) { ?>
                        <div>
                            <video height="700" class=" video-preview" style="background-color: #000; max-width:600px;" controls="controls"><source src="<?php echo base_url("/video")."/".$influencer['video']?>" type="video/mp4"></video>
                        </div>
                    
                        
                        <form action="/influencer/eliminarVideo" method="POST" class="register-form pt-2" id="eliminarVideo" name="eliminarVideo" enctype="multipart/form-data">
                            <input type="hidden" id="influencerid7" name="influencerid7" value="<?=$influencer['idinfluencer']?>">
                 
                            <input style="cursor:pointer" type="image" src="<?php echo base_url("img/remove-acc.png")?>" >
    
                        </form>
                    <?php } ?> 
                    </div>
                            
                </div>
<!-- ============================================================== -->
<!-- ------------------ MODEL PARA CARGAR VIDEO ---------------------->
<!-- ============================================================== -->

                <div class="col-lg-12 d-flex align-items-center mt-4" style="justify-content: left;">
                    <button type="button" class="btn btn-white-normal btn-lg"  data-bs-dismiss="modal" data-bs-toggle="modal" onclick="cleanVid();" data-bs-target="#modal-upload-video">+ &nbsp; Cambiar video</button>
                </div>
            
                <div class="mb-3 mt-5" style="border-top: 1px solid #000;">
                <h2 class="mypro-section-title">Mi galería</h2>
            </div>

<!-- ============================================================== -->
<!-- ------------------ ELIMINAR FOTO DE GALERIA ---------------------->
<!-- ============================================================== -->

            
        <div id="gallery" class="d-flex justify-content-left flex-wrap" style="padding-top: 10px; padding-bottom: 35px;">
            
            <?php foreach ($galeria as $key => $m) {?>
            <form action="/influencer/eliminarFotoGaleria" method="POST" class="pt-2" id="eliminarFotoGaleria" name="eliminarFotoGaleria" enctype="multipart/form-data">
                        
                <input type="hidden" id="fotoGaeliminar" name="fotoGaeliminar" value="<?php echo $m['idfoto']?>">
                <input type="hidden" id="influencerid10" name="influencerid10" value="<?=$influencer['idinfluencer']?>">
                       
                           <div id="gal-img0" class="gallery-profile-show-margin">
                               <div class="gallery-profile-show">
                                   <img class="upload-gal-img" src="<?php echo base_url("uploads")."/".$m['url']?>">
                               </div>
                               <div class="pt-2">
                                    <input style="cursor:pointer" type="image" src="<?php echo base_url("img/remove-acc.png")?>" >
                               </div>
                           </div>
                                                      
            </form>
            <?php } ?>

            </div>
<!-- ============================================================== -->
<!-- ------------------ MODAL PARA AGREGAR FOTO A LA GALERIA --------->
<!-- ============================================================== -->
            <form action="/influencer/agregarFotoGaleria" method="POST" class="register-form pt-2" id="agregarFotoGaleria" name="agregarFotoGaleria" enctype="multipart/form-data">
            
                <div class="col-lg-12 d-flex align-items-center" style="justify-content: left;">
                    <button id="btn-img1" data-id="btn-gallery" type="button" class="open-send btn btn-white-normal btn-lg" onclick="cleanUpload(); hideInfoProfile();" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modal-upload-image-galeria">Subir fotos</button>
                </div> 
            </form>



            <div class="mb-3 mt-5" style="border-top: 1px solid #000;">
                <h2 class="mypro-section-title">Mi reseña</h2>
            </div>

<!-- ============================================================== -->
<!-- ------------------ CARGAR O EDITAR LA RESEÑA -------------------->
<!-- ============================================================== -->

            <form action="/influencer/editarResenia" method="POST" class="register-form pt-2" id="editarResenia" name="editarResenia" enctype="multipart/form-data">
            
                <input type="hidden" id="influencerid12" name="influencerid12" value="<?=$influencer['idinfluencer']?>">
                       
                <div class="mb-4 ">
                    <textarea class="input-redes" id="reseniaInfluencer" name="reseniaInfluencer" style="resize: none; width: 100%;" rows="6" placeholder=""><?php echo $influencer['resenia']; ?></textarea>
                </div>

            
                <div class="col-lg-12 d-flex align-items-center" style="justify-content: left;">
                    <button id="btn-img1"  type="submit" class="open-send btn btn-white-normal btn-lg" >Actualizar</button>
                </div> 
            </form>


            <div class="mb-3 mt-5" style="border-top: 1px solid #000;">
                <h2 class="mypro-section-title">Marcas / Empresas / Experiencia</h2>
            </div>

<!-- ============================================================== -->
<!-- ------------------ Eliminar MARCAS ------------------------------>
<!-- ============================================================== -->
            <div id="experience" class="row mt-4">
                         
            
                <?php for ($i=0; $i <count($influencermarca) ; $i++) {?>
                <form action="/influencer/eliminarMarcas" method="POST" class="col-lg-3 pt-2" id="eliminarMarcas" name="eliminarMarcas" enctype="multipart/form-data">
                
                       
                       <input type="hidden" id="marcaeliminada" name="marcaeliminada" value="<?php echo $influencermarca[$i]['idmarca']?>">
                       <input type="hidden" id="influencerid13" name="influencerid13" value="<?=$influencer['idinfluencer']?>">
               
                 
                    <div id="exp_1" class="row">
                        <div class="row mb-3">
                            <div class="col-md-auto my-profile-width-remove">
                                <div>
                                    <input style="cursor:pointer" type="image" src="<?php echo base_url("img/remove-acc.png")?>" >
                                </div>
                            </div>
                            <div class="col-md-auto user-decription-black my-profile-align-txt-content">
                            <?=$influencermarca[$i]['nombre'] ?> 
                            </div>
                        </div>
                    </div>
                </form>
            <?php } ?>
        </div>
<!-- ============================================================== -->
<!-- ------------------ MODAL PARA AGREGAR MARCAS -------------------->
<!-- ============================================================== -->

                    <div class="col-lg-12 d-flex align-items-center mt-4" style="justify-content: left;">
                        <button type="button" class="btn btn-white-normal btn-lg"  data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#add-empresa-modal">+ &nbsp; Adicionar</button>
                    </div>
                
            

            



            <div class="mb-3 mt-5" style="border-top: 1px solid #000;">
                <h2 class="mypro-section-title">Mis métodos de pago</h2>
            </div>

<!-- ============================================================== -->
<!-- ------------------ Eliminar PAGOS ------------------------------>
<!-- ============================================================== -->
        <div id="pagos" class="row mt-4">
        <?php for ($i=0; $i <count($pagos) ; $i++) {?>
            <form action="/influencer/eliminarPagos" method="POST" class="col-lg-3 pt-2" id="eliminarPagos" name="eliminarPagos" enctype="multipart/form-data">
                <input type="hidden" id="pagoeliminada" name="pagoeliminada" value="<?php echo $influencerPagos[$i]['id']?>">
                <input type="hidden" id="influencerid15" name="influencerid15" value="<?=$influencer['idinfluencer']?>">
                  
                  
                <div id="exp_1" class="row">
                    <div class="row mb-3">
                        <div class="col-md-auto my-profile-width-remove">
                            <div>
                                <input style="cursor:pointer" type="image" src="<?php echo base_url("img/remove-acc.png")?>" >
                            </div>
                        </div>
                        <div class="col-md-auto user-decription-black my-profile-align-txt-content">
                        <?=$pagos[$i]['nombre'] ?>
                        </div>
                    </div>
                </div>
            </form>
        <?php } ?>
        </div>
<!-- ============================================================== -->
<!-- ------------------ MODAL PARA AGREGAR PAGOS --------------------->
<!-- ============================================================== -->

        <div class="col-lg-12 d-flex align-items-center mt-4" style="justify-content: left;">
            <button type="button" class="btn btn-white-normal btn-lg"  data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#add-pago-modal">+ &nbsp; Adicionar</button>
        </div>
                
<!-- ============================================================== -->
<!-- ------------------ AGREGAR OFERTAS ------------------------------>
<!-- ============================================================== -->
            

            <div class="mb-3 mt-5" style="border-top: 1px solid #000;">
                <h2 class="mypro-section-title">Mis promos y ofertas</h2>
            </div>
        <form action="/influencer/editarOferta" method="POST" class="register-form pt-2" id="eliminarPagos" name="eliminarPagos" enctype="multipart/form-data">
            <input type="hidden" id="influencerid19" name="influencerid19" value="<?=$influencer['idinfluencer']?>">
                 
            <div class="col-lg-12">
                <div>
                    
                    <input class="input-modify my-profile-input-line" type="text" name="promocion" id="promocion" placeholder="Escriba su promoción" value="<?php if(!($influencer['oferta']==null||$influencer['oferta']=="")){ echo $influencer['oferta'];  }?>">
                    <button type="submit" class="btn btn-white-normal btn-lg mt-4" >Actualizar promoción</button>
                </div>
            </div> 


           
        </form>

        <div class="d-flex justify-content-end text-right my-3">
            <a data-bs-toggle="modal" data-bs-target="#exampleModalEliminarInflu" data-placement="top" title="Eliminar Influencer" onclick="recibir2(<?=$influencer['idinfluencer']?>);" >
                <button class="btn btn-white-normal btn-lg mt-4" >Eliminar cuenta</button>
            </a>

            <script>
                function recibir2(numero)
                {
                    document.getElementById("eliminarinfluencermodal").value=numero;     
                }
            </script>

        </div>

        </div>
        <!-- content -->

    </div>
    <!-- Content Mi Perfil End -->


   

<!-- ============================================================== -->
                    <!-- MODALES -->
<!-- ============================================================== -->

<!-- Modal eliminar cuenta -->
<div class="modal fade" id="exampleModalEliminarInflu" tabindex="-1" aria-labelledby="exampleModalEliminarInflu" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 0rem; border: 2px solid #000;">

            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body ">
                <img class="img-hecho mb-3" src="<?=base_url('img/warning.png')?>" >
                
                <p class="user-decription-black text-center " style="font-size:17px">¿Estás seguro que quieres eliminar tu cuenta?</p>

                <div class="user-decription-black text-center"  >
                    <i class="fa fa-info-circle" style="font-size:12px"></i> Esta acción es irreversible.</p>
                </div>
                
                <form action="/influencer/eliminarMiCuenta" method="POST" class="form-horizontal"  enctype="multipart/form-data">
                    <input id="eliminarinfluencermodal" name="eliminarinfluencermodal" type="hidden" >

                    <div class="text-center mt-4" style=" font-size: 18px; padding: 6px;">   
                        <button type="submit" class="btn btn-white-normal btn-lg" >Aceptar</button>
                    </div>
                </form>
            </div>
        
            <div class="modal-footer"></div>

        </div>
    </div>
</div>



    <!-- Modal agregar RED SOCIAL -->
    <div class="modal fade" id="add-acc-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 0rem; border: 2px solid #000;">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body " style="padding-right: 65px; padding-left: 65px; padding-top: 0px;">

                    <div class="text-center" style="font-family: 'Bemio Italic'; color: #000000; font-size: 25px;" >
                        <p>Selecciona la red social<br>
                        y escribe tu usuario</p>
                    </div>
                    <div class="user-decription-black"  >
                        <p style="padding: 5px 4px;"><i class="fa fa-info-circle" style="font-size:14px"></i> Coloca solo el nombre de usuario.<br>
                        <i class="fa fa-info-circle" style="font-size:14px"></i> Telegram funciona con tu canal para que muestres tus suscriptores, ten en cuenta que no es tu cuenta personal.</p>
                    </div>
                    <form action="/influencer/agregarRedSocial" method="POST" class="register-form pt-2" id="agregarRedSocial" name="agregarRedSocial" enctype="multipart/form-data">
                        <input type="hidden" id="influencerid1" name="influencerid1" value="<?=$influencer['idinfluencer']?>">
                        <div class="col mt-4"> 
                            <select id="redessocialesagregar" name="redessocialesagregar" class="form-select select-sm-profile" aria-label="Default select example">
                                <option selected disabled>Selecciona la red social</option>
                                <?php if(count($redesNoUsadas)>0){
                                foreach ($redesNoUsadas as $key => $m) {?>
                                    <option value="<?=$m['idredes']?>"><?=$m['nombre']?></option>
                                <?php }}?>
                                
                            
                            </select>
                                
                        </div>

                        <div class="col pt-3">
                            <input class="input-redes" type="text" name="textousuariored" id="textousuariored" placeholder="Tu usuario">
                        </div>
                    
                        <div class="text-center mt-4" style=" font-size: 18px; padding: 6px;">
                            <input type="submit" class="btn btn-white-normal btn-lg"   value="Adicionar"/>
                            
                        </div>

                        

                    </form>

                </div>

                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
    <!--FINAL Modal AGREGAR RED SOCIAL -->


     <!-- Modal AGREGAR CATEGORIA -->
     <div class="modal fade" id="add-cat-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 0rem; border: 2px solid #000;">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body " style="padding-right: 65px; padding-left: 65px; padding-top: 0px;">

                    <div class="text-center" style="font-family: 'Bemio Italic'; color: #000000;font-size: 25px;" >
                        <p>Agrega un tema</p>
                    </div>

                    <form action="/influencer/adicionarCategoria" method="POST" class="register-form pt-2" id="adicionarCategoria" name="adicionarCategoria" enctype="multipart/form-data">
                        <input type="hidden" id="influencerid4" name="influencerid4" value="<?=$influencer['idinfluencer']?>">
                    
                        

                            <select id="categorianew" name="categorianew"class="form-select select-sm-profile" onchange="addTopic(this);" aria-label="Default select example">
                                <option selected disabled>Selecciona un tema</option>
                                <?php foreach ($categoriasNoUsadas as $key => $m) {?>
                                    <option value="<?=$m['idcategoria'] ?>"><?=$m['nombrecat'] ?></option>
                                    <?php }?>
                                
                                
                            </select>
                        
                            <div class="text-center mt-4" style=" font-size: 18px; padding: 6px;">
                            <input type="submit" class="btn btn-white-normal btn-lg"   value="Adicionar"/>
                            
                        </div>

                    </form>

                </div>

                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
    <!--FINAL Modal AGREGAR CATEGORIA -->



    <!-- Modal AGREGAR LENGUAJE -->
    <div class="modal fade" id="add-lan-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 0rem; border: 2px solid #000;">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body " style="padding-right: 65px; padding-left: 65px; padding-top: 0px;">

                    <div class="text-center" style="font-family: 'Bemio Italic'; color: #000000;font-size: 25px;" >
                        <p>Agrega un idioma</p>
                    </div>

                    <form action="/influencer/adicionarIdioma" method="POST" class="register-form pt-2" id="adicionarIdioma" name="adicionarIdioma" enctype="multipart/form-data">
                        <input type="hidden" id="influencerid6" name="influencerid6" value="<?=$influencer['idinfluencer']?>">
                    
                        

                            <select id="idiomanew" name="idiomanew"class="form-select select-sm-profile" >
                                <option selected disabled>Selecciona un tema</option>
                                <?php foreach ($idimanousado as $key => $m) {?>
                                    <option value="<?=$m['ididioma'] ?>"><?=$m['nombre'] ?></option>
                                    <?php }?>
                                
                                
                            </select>
                        
                            <div class="text-center mt-4" style=" font-size: 18px; padding: 6px;">
                            <input type="submit" class="btn btn-white-normal btn-lg"   value="Adicionar"/>
                            
                        </div> 

                    </form>
                    
                </div>

                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
    <!-- final Modal AGREGAR LENGUAJE -->

    

    <!-- Modal ACTUALIZAR UN VIDEO -->
    <div class="modal fade" id="modal-upload-video" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 0rem; border: 2px solid #000;">
                <div class="modal-header">
                    <button type="button" onclick="closeVid()" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">

                <div class="text-center" style="font-family: 'Bemio Italic'; color: #000000;font-size: 25px;" >
                        <p>Agrega un video</p>
                    </div>

                <form action="/influencer/cambiarVideo" method="POST" class="register-form pt-2" enctype="multipart/form-data">
            

                    <input type="hidden" id="influencer9" name="influencer9" value="<?=$influencer['idinfluencer']?>">

                    <div class="file-upload">
                        
                        <div class="image-upload-wrap">
                            <input id="newvideo" name="newvideo" class="" type='file' accept="video/mp4, video/*"/>
                            
                        </div>

                        
                        
                        <div class="text-center mt-4" style=" font-size: 18px; padding: 6px;">
                            <input type="submit" class="btn btn-white-normal btn-lg"   value="Cambiar video"/>
                            
                        </div> 
                        
                        
                    </div>
                </form>

                </div>
                <div class="modal-footer user-decription-black" style="font-weight: bold; "></div>
            </div>
        </div>
    </div>
    <!-- FINAL Modal ACTUALIZAR UN VIDEO --> 



    <!-- Modal MODIFICAR FOTO PERFIL -->
    <div class="modal fade" id="modal-upload-image" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 0rem; border: 2px solid #000;">
                <div class="modal-header">

                    

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">

                <div class="text-center" style="font-family: 'Bemio Italic'; color: #000000;font-size: 25px;" >
                        <p>Agrega una foto de perfil</p>
                        
                    </div>
                    </br>
                <form action="/influencer/cambiarFoto" method="POST" enctype="multipart/form-data">
            

                    <input type="hidden" id="picIdd" name="picIdd" value="<?=$influencer['idinfluencer']?>">

                    <div class="file-upload">
                        
                        
                            <input id="newfotoo" name="newfotoo" type='file'/>
                            
                        
                        </br></br>
                            <div class="text-center user-decription-black" style="font-weight: bold; font-size: 13px; margin-left: auto; ">
                            Te recomendamos que el tamaño de tu foto sea 600px por 600px.
                        </div>
                        
                        <div class="text-center" style="margin-top: 12px;">
                        <button type="submit" class="btn btn-white-normal btn-lg" >Cambiar foto de perfil</button>
                            <button type="submit" class="btn btn-get-info user-decription btn-lg" >Cambiar foto de perfil</button>
                        </div>
                        
                    </div>
                </form>
                </div>
                <div class="modal-footer user-decription-black" style="font-weight: bold; "></div>
            </div>
        </div>
    </div>
    <!-- FINAL MODAL ACTUALIZAR FOTO PERFIL -->


     <!-- Modal AGREGAR FOTO A LA GALERIA -->
     <div class="modal fade" id="modal-upload-image-galeria" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 0rem; border: 2px solid #000;">
                <div class="modal-header">

                    

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                <div class="text-center" style="font-family: 'Bemio Italic'; color: #000000;font-size: 25px;" >
                        <p>Agrega algunas fotos a tu galería</p>
                    </div>
                <form action="/influencer/agregarFotoGaleria" method="POST" class="register-form pt-2" id="agregarFotoGaleria" name="agregarFotoGaleria" enctype="multipart/form-data">
            

                    <input type="hidden" id="influencer11" name="influencer11" value="<?=$influencer['idinfluencer']?>">

                    <div class="file-upload">
                        
                        <div class="image-upload-wrap">
                            <input id="newfotoGaleria[]" name="newfotoGaleria[]" type='file' multiple="" accept="image/png,image/jpeg"  />
                            
                        </div>
                        
                        <div class="text-center mt-4" style=" font-size: 18px; padding: 6px;">
                            <input type="submit" class="btn btn-white-normal btn-lg"   value="Cambiar fotos de tu galería"/>
                            
                        </div> 

                        
                    </div>
                </form>

                

                </div>
                <div class="modal-footer user-decription-black" style="font-weight: bold; "></div>
            </div>
        </div>
    </div>
    <!-- FINAL AGREGAR UNA FOTO A LA GALERIA -->



    <!-- Modal CREAR UNA EMPRESA -->
    <div class="modal fade" id="add-empresa-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 0rem; border: 2px solid #000;">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body " style="padding-right: 65px; padding-left: 65px; padding-top: 0px;">

                    <div class="text-center" style="font-family: 'Bemio Italic'; color: #000000;font-size: 25px;" >
                        <p>Escribe una empresa o marca</p>
                    </div>

                    <form action="/influencer/adicionarEmpresa" method="POST" class="register-form pt-2" id="adicionarEmpresa" name="adicionarEmpresa" enctype="multipart/form-data">
                        <input type="hidden" id="influencerid16" name="influencerid16" value="<?=$influencer['idinfluencer']?>">
                            
                        <div class="col mt-4">
                            <input id="empresanewtxt" name="empresanewtxt"  class="input-redes" type="text"  placeholder="Marca / Empresa">
                        </div>
                        <div class="col mt-4">
                            <select id="tipoempres" name="tipoempres"  class="input-redes" type="text"  placeholder="Marca / Empresa">
                            <option value="publica">Pública</option>
                            <option value="privada">Privada</option>
                                </select>
                        </div>
                            
                        <div class="text-center mt-4" style=" font-size: 18px; padding: 6px;">
                            <input type="submit" class="btn btn-white-normal btn-lg"   value="Adicionar"/>
                            
                        </div> 
                    </form>
                </div>

                <div class="modal-footer"></div>

            </div>
        </div>
    </div>
    <!-- FINAL MODAL CREAR EMPRESA -->


        <!-- Modal CREAR UN PAGO -->
        <div class="modal fade" id="add-pago-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 0rem; border: 2px solid #000;">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body " style="padding-right: 65px; padding-left: 65px; padding-top: 0px;">

                    <div class="text-center" style="font-family: 'Bemio Italic'; color: #000000;font-size: 25px;" >
                        <p>Agrega una nueva forma de pago</p>
                    </div>

                    <form action="/influencer/adicionarPago" method="POST" class="register-form pt-2" id="adicionarPago" name="adicionarPago" enctype="multipart/form-data">
                        <input type="hidden" id="influencerid17" name="influencerid17" value="<?=$influencer['idinfluencer']?>">
                            <select id="pagonew" name="pagonew"class="form-select select-sm-profile" >
                                <option selected disabled>Selecciona un pago</option>
                                <?php foreach ($pagosNoUsados as $key => $m) {?>
                                    <option value="<?=$m['idpago'] ?>"><?=$m['nombre'] ?></option>
                                    <?php }?>
                                
                                
                            </select>

                    
                            <div class="text-center mt-4" style=" font-size: 18px; padding: 6px;">
                            <input type="submit" class="btn btn-white-normal btn-lg"   value="Adicionar"/>
                            
                        </div> 

                    </form>
                </div>

                <div class="modal-footer"></div>

            </div>
        </div>
    </div>
    <!-- FINAL MODAL CREAR UN PAGO -->




    <!-- Modal Confirmación Start -->
    <div class="modal fade" id="confirmation-changes-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 0rem; border: 2px solid #000;">
                <div class="modal-header">
                  <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
                </div>
                <div class="modal-body ">
                    <img class="img-hecho mb-3" src="<?php echo base_url("img/hecho.png")?>" >
                    <div class="text-center user-decription-black" style="font-weight: bold; font-size: 35px">
                        
                        <p>¡HECHO!</p>
                    </div>
                    <div class="text-center user-decription-black" style="font-weight: bold; font-size: 15px">
                        <p>Dirigirse a la página principal.</p>
                    </div>
                    
                    <div class="text-center user-decription-black" style="font-weight: bold; font-size: 35px">
                     
                    <a href="<?php echo base_url("logout")?>" type="button">CERRAR SESIÓN</a>
                    
                    </div>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
    <!-- Modal Confirmación End -->

    

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
