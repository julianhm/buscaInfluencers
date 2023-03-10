<?php if(session()->get('idinfluencer')!=$influencer['idinfluencer']){
            return redirect()->to(base_url())->with('mensaje', 'Error de Validación');
        }else if ((time() - session()->get('time')) > 3600){
            session_destroy();
        }?>


   
    <!-- Content Register Start -->
    <div class="container">
        <div class="row">
            <div class="text-center" >

            <?php  echo $validation->listErrors(); ?>
            <div class="content">

            <div class="user-decription-black my-3" style="font-size: 25px;" >
                EDITAR PERFIL
            </div> 
                
                <div class="d-flex justify-content-center mb-4">
                    <div class="section-title">
                        Idioma en que creas tu contenido
                    </div>
                </div>

            <form action="../continuarregistro" method="POST" class="register-form pt-2" enctype="multipart/form-data">
            <?= csrf_field() ?>    
           

            <input type="hidden" id="influencerid30" name="influencerid30" value="<?=$influencer['idinfluencer']?>">
                


                <div class="col my-4" style="padding-left: 25%; padding-right: 25%;">
                    <select id="langSelect" name="langSelect" onchange="addLanguage()" class="form-select select-sm-profile" aria-label="Default select example">
                        <option selected disabled="">Selecciona un idioma</option>
                        <?php foreach ($idiomas as $key => $m) {?>
                            <option value="<?=$m['ididioma'] ?>"><?=$m['nombre'] ?></option>
                       <?php } ?>
                        
                        
                    </select>
                        
                </div>

            

                <div class="d-flex justify-content-center mb-4">
                    <div class="section-title">
                        ¿De qué temas hablas?
                    </div>
                </div>

                                  
                <div class="row mb-4"> 

                    <?php $i=0;$j=1;
                    foreach($categorias as $key=>$m){?>
                    <?php if($i==0){ ?>
                        <div class="select-size align-topics">
                        <?php } ?>
                        
                            <input type="checkbox" name="<?=$m['nombrecat']?>" id="<?="topic".$j?>" />
                            <label class="label-topic" for="<?="topic".$j?>"><?=$m['nombrecat']?></label>
                    <?php $i++;$j++;
                    if($i==3){$i=0;?>
                        </div>
                    <?php }
                        } ?>
                    
                        
                    <!--
                    <div class="row justify-content-center mt-3">
                        <div class="search-container user-decription-black">
                            <span>Otro: </span>
                            <input type="text" class="input-redes-other" style="border-radius: 12px; background-color: #ece7e7;" placeholder="escriba otros temas" name="agregar" id="agregar">
                            
                        </div>
                    </div>
                -->
                </div>
                    <br>
                    <br>
                    <br>

                
                
                <div class="d-flex justify-content-center mb-4">
                    <div class="section-title">
                        Envíanos un video tuyo hablando de ti*
                    </div>
                </div>


                <div class="row mb-2 user-decription-black">
                
               <!-- <div id="video-prev" style="display: none;" class="col-lg-12 video-width">
                    <div>
                        <video width="100%" height="400" class="video-preview"  style="background-color: #000" controls="controls"><source src=<?php echo base_url('img/bg-binf.mp4')?> type="video/mp4"></video>
                    </div>
                    <a style="cursor:pointer" onclick="hideVideoPrev()" ><img src=<?php echo base_url('img/remove-acc.png')?> alt="Remove"></a> 
                </div>-->
                <h5>Adjuntar video</h5> 
                <div class="col-lg-12 d-flex align-items-center my-2 btn-media">
                    
                    <input type="file" name="videoperfil" id="videoperfil" class="open-send btn btn-gray-normal btn-lg" style="border-radius: 0.7rem;" value='<?= old('videoperfil') ?>' accept="video/mp4, video/*" />
                    <!-- data-bs-toggle="modal" data-bs-target="#modal-upload-image" onclick="cleanUpload(); showInfoProfile();" data-id="btn-profile"-->
                   
               <!-- <button type="button" class="btn btn-gray-normal btn-lg" style="border-radius: 0.7rem;" data-bs-dismiss="modal" data-bs-toggle="modal" onclick="cleanVid();" data-bs-target="#modal-upload-video">Adjuntar Video</button> -->
                </div> 



                <div class="form-group mb-3 user-decription-black">
                    <p>* Te recomendamos no más de 1 minuto de duración.</p>
                </div>


                <div class="d-flex justify-content-center">
                    <div class="section-title">
                        Si gustas, puedes adjuntar una galería de imágenes.
                    </div>
                </div>
                    
                                        
         
                <div id="gallery" class="d-flex justify-content-center flex-wrap" style="padding-top: 10px; padding-bottom: 35px;">

                </div>
 

                <div class="col-lg-12 d-flex align-items-center mt-4 btn-media">
                    <input type="file" name="galeriaperfil[]" id="galeriaperfil[]" class="open-send btn btn-gray-normal btn-lg" style="border-radius: 0.7rem;" value='<?= old('videoperfil') ?>' multiple="" />

                   <!-- <button id="btn-img1" data-id="btn-gallery" type="button" class="btn btn-gray-normal btn-lg open-send" style="border-radius: 0.7rem;" onclick="cleanUpload(); hideInfoProfile();" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modal-upload-image">Subir foto</button>-->
                </div> 
                

                <div class="form-group mb-3 user-decription-black">
                    <p>* Peso máximo por imagen (500k). <br> * Cantidad maxima de fotos 5</p>
                </div>


                <div class="d-flex justify-content-center mb-4">
                    <div class="section-title">
                        ¿En qué empresa o marca has trabajado? 
                    </div>
                </div>

                <div id="experience" class="row justify-content-center mt-4">
                    Escribe una nueva Empresa
                </div>

                <div class="col-lg-12 d-flex align-items-center mt-3 mb-4 btn-media">
                <input id="input-exp" name="marcatxt" id="marcatxt" class="input-redes" type="text"  onkeyup="success()" placeholder="Marca / Empresa">
                </div> 

                <div class="col-lg-12 d-flex align-items-center mt-3 mb-4 btn-media">
                    <select id="tipoMarca" name="tipoMarca"  class="form-select select-sm-profile" aria-label="Default select example">
                        <option selected value="privada">PRIVADA</option>  
                        <option value="publica">PUBLICA</option>      
                    </select>
                </div>
                <br/>
                <br/>



                <div class="d-flex justify-content-center mb-4">
                    <div class="section-title">
                        Estás dispuesto(a) a trabajar por
                    </div>
                </div>



                <div class="row justify-content-center user-decription-black" style="padding-left: 112px; padding-right: 112px;">
                    <p>Marca los que consideres</p>

                    <div class="col-md-auto">
                        CANJE X PRODUCTO <br>
                        <input class="form-check-input" type="checkbox" value="" name="canje" id="flexCheckDefault">
                    </div>

                    <div class="col-md-auto">
                        DINERO <br>
                        <input class="form-check-input" type="checkbox" value="" name="dinero" id="flexCheckDefault">
                    </div>
                    <div class="col-md-auto">
                        SERVICIOS <br>
                        <input class="form-check-input" type="checkbox" value="" name="servicios" id="flexCheckDefault">
                    </div>
                    <div class="col-md-auto">
                        PATROCINIO <br>
                        <input class="form-check-input" type="checkbox" value="" name="patrocinio" id="flexCheckDefault">
                    </div>
                   <!-- <div class="col-md-auto">
                        TODOS LOS ANTERIORES <br>
                        <input class="form-check-input" type="checkbox" value="" name="canje" id="flexCheckDefault">
                    </div>-->
                
                </div>

                <div style="padding-left: 10%; padding-right: 10%;">
                    <hr class="break_line">
                </div>


                                  

                <div class="d-flex justify-content-center mb-4">
                    <div class="btn-register">
                        <input type="submit" name="submit" id="submit"class="btn btn-login btn-lg btn-register-width user-decription-black" style="font-size: 20px;" value= "¡REGISTRARME!"/>
                       <!-- data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#confirmation-modal"-->
                    </div>
                </div>
            </form>
</div>

</div>
            </div>
        </div>
    </div>
    <!-- Content Register End -->


    <!-- ============================================================== -->
                            <!-- MODALES -->
    <!-- ============================================================== -->
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
