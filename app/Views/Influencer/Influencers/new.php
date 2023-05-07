



   
    <!-- Content Register Start -->
    <div class="container">
        <div class="row">
            <div class="text-center" >

            <?php  echo $validation->listErrors(); ?>
                <div>

                

                <form action="create" method="POST" class="register-form pt-2" id="registro" name="registro" enctype="multipart/form-data">
                <?= csrf_field() ?>     
                
                        
                        <div class="d-flex justify-content-center mb-4">
                            <div class="section-title">
                               Permítenos conocerte un poco mejor
                            </div>
                        </div>

                        <div class="form-group">
                            <img class="icon-input" src=<?php echo base_url('img/icon-user.png')?> for="nombre">

                            <input class="input-modify" type="text" name="nombre" id="nombre" maxlength = "20" placeholder="Tu nombre (si lo crees necesario)" value='<?= old('nombre') ?>'>
                            <p style="color:red"><?=session("errors.nombre")?></p>
                        </div>
                        <div class="form-group">
                            <img class="icon-input" src=<?php echo base_url('img/icon-alias.png')?> for="alias" >

                            <input class="input-modify" type="text" name="alias" id="alias" maxlength = "20" placeholder="Alias (solo si usas)" value='<?= old('alias') ?>'>
                            <p style="color:red"><?=session("errors.alias")?></p>
                        </div>
                        <div class="form-group">
                            <img class="icon-input" src=<?php echo base_url('img/icon-pass.png')?> for="password">

                            <input class="input-modify" type="password" name="password" id="password" placeholder="Escribe una clave" >
                            <p style="color:red"><?=session("errors.password")?></p>
                        </div>
                        <div class="form-group">
                            <img class="icon-input" src=<?php echo base_url('img/icon-pass.png')?> for="passwordver">

                            <input class="input-modify" type="password" name="passwordver" id="passwordver" placeholder="Escribe nuevamente tu clave" >
                        </div>
                        <div class="form-group">
                            <img class="icon-input" src=<?php echo base_url('img/icon-email.png')?> for="correo">
                            
                            <input class="input-modify" type="email" name="correo" id="correo" placeholder="Tu correo electrónico" value='<?= old('correo') ?>'>
                            <p style="color:red"><?=session("errors.correo")?></p>
                        </div>
                        <div class="form-group">
                            <img class="icon-input" src=<?php echo base_url('img/icon-location.png')?> for="pais">
                            
                            <select class="input-modify" name="pais" id="pais" onchange='cambia_ciudades();' value='<?= old('pais') ?>'>
                                    <option value="0" selected>Elija tu país de nacimiento</option>
                                <?php foreach ($paises as $key => $m) {?>
                                   <option value="<?=$m['idpais']?>"><?=$m['nombre']?></option>
                               <?php } ?>
                            </select>
                            <p style="color:red"><?=session("errors.pais")?></p>
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

                        

                        <div class="form-group">
                            <img class="icon-input" src=<?php echo base_url('img/icon-location.png')?> for="ciudades">
                            <select class="input-modify" name="ciudades" id="ciudades" value='<?= old('ciudades') ?>' disabled>
                                    <option value="0" selected>Elije tu región</option>
                                   
                            </select>
                            <p style="color:red"><?=session("errors.ciudades")?></p>
                            <!--<input class="input-modify" type="text" name="ciu" id="ciu" placeholder="Tu ciudad de residencia" value=''>-->
                        </div>


                      <!--  <div class="form-group my-3 user-decription-black" >
                                                      
                            <div class="row g-4">
                                <div>
                                    <img id="img-profile" name= "img-profile"class="my-profile-photo" src=<?php echo base_url('img/mas1.png')?> >
                                </div>   
                            </div>
                        </div>-->
                        <div class="d-flex justify-content-center mb-4">
                            <div class="section-title">
                                Háblanos de ti y crearemos una reseña*
                            </div>
                        </div>

                        <div class="form-group mb-4 ">
                            <textarea class="input-redes" name="resenia" style="resize: none; width: 100%;" rows="6" placeholder="Cuéntanos un poco de ti" ><?= old('resenia') ?></textarea>
                        </div>
                        <p style="color:red"><?=session("errors.resenia")?></p>

                        
                        <div class="d-flex justify-content-center mb-4">
                            <div class="section-title">
                                Subir foto de perfil
                            </div>
                        </div>
                       
                       
                        <div class="col-lg-12 d-flex align-items-center my-4 btn-media">
                        
                               <!-- Da click sobre la imagen para modificarla-->
                               
                        
                            
                                <input type="file" name="fotoperfil"  class="open-send btn btn-gray-normal btn-lg" style="border-radius: 0.7rem;" value='<?= old('fotoperfil') ?>'  accept="image/*" /> 
                       
                            
                            <!-- data-bs-toggle="modal" data-bs-target="#modal-upload-image" onclick="cleanUpload(); showInfoProfile();" data-id="btn-profile"-->
                        </div>
                        <div class="text-center user-decription-black" style="">
                                    Te recomendamos que el tamaño de tu foto sea 600px por 600px.
                                </div>
<!--
                        <label class="label" data-toggle="tooltip" title="Cambia tu foto de Perfil">
                            <img class="rounded" id="avatar" src="<?php echo base_url("/uploads")."/perfil.png"?>" alt="avatar">
                            <input type="file" class="sr-only" id="fotoperfil" name="fotoperfil" accept="image/*">
                        </label>
                    -->

                        <div class="alert" role="alert"></div>

                        <div style="padding-left: 10%; padding-right: 10%;">
                            <hr class="break_line">
                        </div>


                        <div class="d-flex justify-content-center user-decription-black">
                            <div class="btn-register">
                                <div class="row align-privacy">
                                    <div class="col-lg-1" >
                                    <input class="form-check-input" type="checkbox" value="" id="terminoscheck" name="terminoscheck">
                                </div>
                                <div class="col-lg-8 align-privacy-text">
                                    AUTORIZO de manera previa, expresa,<br> 
                                informada y explícita, a WD Studios Corp, para <br>el uso y tratamiento de mis datos.
                                </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            var miCheckbox = document.getElementById('terminoscheck');
                            var boton = document.getElementById('submit');
                            miCheckbox.addEventListener('change', function(){
                                if(miCheckbox.checked){
                                    document.getElementById('submit').disabled=false;
                                }else{
                                    document.getElementById('submit').disabled=true;
                                }
                                
                               
                            });
                        </script>

                       <div class="d-flex justify-content-center user-decription-black my-4">
                            <div>
                                <img src=<?php echo base_url('img/doc.png')?>>
                            </div>
                            <div style="padding: 10px;">
                                <a style="color: #000; text-decoration: underline;" href="/privacidad" target="_blank">Ver documento</a>
                            </div>
           
                        </div>

                        <div class="d-flex justify-content-center mb-4">
                            <div class="btn-register">
                                <input type="submit" name="submit" id="submit" class="btn btn-login btn-lg btn-register-width user-decription-black" style="font-size: 20px; width: fit-content; padding: 12px 45px;" value= "¡REGISTRARME!" disabled/>
                               <!-- data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#confirmation-modal"-->
                            </div>
                        </div>
                        </form>

                       <!-- 
                        
                        -->
                    
                </div>
            </div>
        </div>
    </div>
    <!-- Content Register End -->


    <!-- ============================================================== -->
                            <!-- MODALES -->
    <!-- ============================================================== -->
<!------- MODAL DE CARGA DE IMAGEN ---->
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">Recorta la imagen</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="img-container">
              <img id="image" src="https://avatars0.githubusercontent.com/u/3456749">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="crop">Recortar</button>
          </div>
        </div>
      </div>
    </div>


   <!-- Modal Upload Image Start -->
    <div class="modal fade" id="modal-upload-image" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 0rem; border: 2px solid #000;">
                <div class="modal-header">

                    <div id="info-profile" class="text-center user-decription-black" style="font-weight: bold; font-size: 15px; margin-left: auto; display: none; padding: 0 10px;">
                        Para una mejor experiencia en tu foto de perfil, selecciona una imagen cuadrada!
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">

                    <input type="hidden" id="picId" name="picId" value="">

                    <div class="file-upload">
                        <div class="text-center">
                            <button id="btn-img-up" type="button" class="btn btn-get-info user-decription btn-lg" onclick="$('.file-upload-input').trigger( 'click' )" >Cargar Imagen</button>
                        </div>
                        <div class="image-upload-wrap">
                            <input id="uploader-input" class="file-upload-input" name="foto_perfil" type='file' onchange="uploadImage($('#picId').val());" accept="image/*" />
                            <div class="drag-text">
                                <h3>Arrastra una imagen para subir</h3>
                            </div>
                        </div>
                        <div class="file-upload-content">
                            <div class="text-center user-decription-black" style="font-weight: bold; font-size: 15px">
                                <img class="img-hecho my-3" src=<?php echo base_url('img/upload-img.png')?> >
                                <div class="text-center user-decription-black" style="font-weight: bold; font-size: 35px">
                                    <p>¡LISTO!</p>
                                </div>
                                <p class="image-title"></p>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-get-info user-decription btn-lg" onclick="cleanUpload()" data-bs-dismiss="modal" aria-label="Close" style="width: 40%;">CONTINUAR</button>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer user-decription-black" style="font-weight: bold; "></div>
            </div>
        </div>
    </div>
    <!-- Modal Upload Image End -->


<!-- Modal Upload Video Start -->
    <div class="modal fade" id="modal-upload-video" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 0rem; border: 2px solid #000;">
                <div class="modal-header">
                    <button type="button" onclick="closeVid()" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">

                    <div class="file-upload">
                        <div class="text-center">
                            <button id="btn-vid-up" type="button" class="btn btn-get-info user-decription btn-lg" onclick="$('.upload-video-file').trigger( 'click' )" >Buscar Video</button>
                        </div>
                        <div class="video-upload-wrap">
                            <input id="uploader-input-vid" class="upload-video-file file-upload-input-vid" type='file' accept="video/*" />
                            <div class="drag-text">
                                <h3>Arrastre y suelte o seleccione Buscar Video</h3>
                            </div>
                        </div>
                        <div class="file-upload-content-vid">
                            <div class="text-center user-decription-black" style="font-weight: bold; font-size: 15px">
                                <img class="img-hecho my-3" src="img/upload-vid.png" >
                                <div class="text-center user-decription-black" style="font-weight: bold; font-size: 35px">
                                    <p>¡LISTO!</p>
                                </div>
                                <p class="video-title"></p>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-get-info user-decription btn-lg" data-bs-dismiss="modal" onclick="showVidmyProfile();" aria-label="Close" style="width: 40%;">CONTINUAR</button>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer user-decription-black" style="font-weight: bold; "></div>
            </div>
        </div>
    </div>
    <!-- Modal Upload Video End --> 



    <!-- Modal Confirmación Registro Start -->
    <div class="modal fade" id="confirmation-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 0rem; border: 2px solid #000;">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <img class="img-hecho mb-3" style="width: 25%;" src=<?php echo base_url('img/stars.png')?> >
                    <div class="text-center user-decription-black" style="font-weight: bold; font-size: 35px">
                        
                        <p>¡LISTO!</p>
                    </div>
                    <div class="text-center user-decription-black" style="font-weight: bold; font-size: 15px">
                        <p>Ahora por favor revisa tu bandeja de
                            entrada para confirmar tu correo.
                            A partir de entonces comenzaremos a crear
                            tu perfil y te avisaremos cuando puedas
                            ingresar y editarlo a tu gusto.</p>
                    </div>
                    
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
    <!-- Modal Confirmación Registro End -->

   

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
<!--
  <script src="https://unpkg.com/jquery@3/dist/jquery.min.js" crossorigin="anonymous"></script>
  <script src="https://unpkg.com/bootstrap@4/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="js/cropper.js"></script>
  <script>
    window.addEventListener('DOMContentLoaded', function () {
      var avatar = document.getElementById('avatar');
      var image = document.getElementById('image');
      var input = document.getElementById('fotoperfil');
      var $progress = $('.progress');
      var $progressBar = $('.progress-bar');
      var $alert = $('.alert');
      var $modal = $('#modal');
      var cropper;

      $('[data-toggle="tooltip"]').tooltip();

      input.addEventListener('change', function (e) {
        var files = e.target.files;
        var done = function (url) {
          input.value = '';
          image.src = url;
          $alert.hide();
          $modal.modal('show');
        };
        var reader;
        var file;
        var url;

        if (files && files.length > 0) {
          file = files[0];

          if (URL) {
            done(URL.createObjectURL(file));
          } else if (FileReader) {
            reader = new FileReader();
            reader.onload = function (e) {
              done(reader.result);
            };
            reader.readAsDataURL(file);
          }
        }
      });

      $modal.on('shown.bs.modal', function () {
        cropper = new Cropper(image, {
          aspectRatio: 1,
          viewMode: 3,
        });
      }).on('hidden.bs.modal', function () {
        cropper.destroy();
        cropper = null;
      });

      document.getElementById('crop').addEventListener('click', function () {
        var initialAvatarURL;
        var canvas;

        $modal.modal('hide');

        if (cropper) {
          canvas = cropper.getCroppedCanvas({
            width: 160,
            height: 160,
          });
          initialAvatarURL = avatar.src;
          avatar.src = canvas.toDataURL();
          $progress.show();
          $alert.removeClass('alert-success alert-warning');
          canvas.toBlob(function (blob) {
            var formData = new FormData();

            formData.append('avatar', blob, 'avatar.jpg');
            $.ajax('https://jsonplaceholder.typicode.com/posts', {
              method: 'POST',
              data: formData,
              processData: false,
              contentType: false,

              xhr: function () {
                var xhr = new XMLHttpRequest();

                xhr.upload.onprogress = function (e) {
                  var percent = '0';
                  var percentage = '0%';

                  if (e.lengthComputable) {
                    percent = Math.round((e.loaded / e.total) * 100);
                    percentage = percent + '%';
                    $progressBar.width(percentage).attr('aria-valuenow', percent).text(percentage);
                  }
                };

                return xhr;
              },

              success: function () {
                input.value=
                $alert.show().addClass('alert-success').text('Imagen Recortada');
              },

              error: function () {
                avatar.src = initialAvatarURL;
                $alert.show().addClass('alert-warning').text('Error al recortaar la imagen');
              },

              complete: function () {
                $progress.hide();
              },
            });
          });
        }
      });
    });
  </script>-->