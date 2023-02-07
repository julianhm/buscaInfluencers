
    <?php if(session()->get('idinfluencer')!=$influencer['idinfluencer']){
        return redirect()->to(base_url())->with('mensaje', 'Error de Validación');
    }
        //var_dump($influencer['idinfluencer']."OTRO"); ?>


   
    <!-- Content Register Start -->
    <div class="container">
        <div class="row">
            <div class="text-center" >
                
                <?php  echo $validation->listErrors(); ?>

                <!-- <div class="content"> -->

                    <div class="user-decription-black my-3" style="font-size: 25px;" >
                        EDITAR PERFIL
                    </div> 


                    <form action="../guardarRedesSociales" method="POST" class="register-form pt-2" enctype="multipart/form-data">
                        <?= csrf_field() ?>    
                        
                        <input type="hidden" id="influencerid3" name="influencerid3" value="<?=$influencer['idinfluencer']?>">
                        
                        <div class="d-flex justify-content-center mb-4">
                            <div class="section-title" >
                                ¿Dónde te siguen?
                            </div>
                        </div>

                        <div style="padding-left: 20%; padding-right: 20%;">
                            <p class="user-decription-black">Marca la(s) red(es) sociales donde tienes cuenta y escribe tu nombre de usuario</p>
                        </div>
                        
                        <div>

                            
                            
                            <?php $cont=4;
                                foreach($misredes as $key => $m){ ?>
                                <?php if($cont==4){
                                    $cont=1;?>
                                    <div class="row mb-2">
                                        <?php }?> 
                                        <div class="col">
                                            <div class="row">
                                                <div class="col-md-auto check-align">
                                                    <div class="form-check">
                                                        
                                                        <input class="form-check-input chb" type="checkbox" value="<?=$m['idredes']?>" id="<?=$m['nombre']?>" onclick="smInputState('<?=$m['nombre']?>', '<?=$m['idredes']?>')">
                                                    </div>
                                                </div>
                                                <div class="col-md-auto logo-sm-align">
                                                    <img src='<?=base_url('img/iconos')."/".$m['icono']?>' >
                                                </div>
                                                <div class="col input-sm-align">
                                                    <input class="input-redes disable-sm-input inp" type="text" name="<?=$m['idredes']?>" id="<?=$m['idredes']?>" placeholder="<?=$m['nombre']?>" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if($cont==3){?>
                                    </div>
                                <?php }
                                $cont++;
                            } ?>

                            
                        </div>

                
                        <div class="d-flex justify-content-center mb-4 mt-4">
                            <div class="btn-register">
                                <input type="submit" name="submit" id="submit"class="btn btn-login btn-lg btn-register-width user-decription-black" style="font-size: 20px;" value= "AGREGAR REDES SOCIALES" />
                            <!-- data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#confirmation-modal"-->
                            </div>
                        </div>
                    </form>
               <!-- </div> -->
            </div>
        </div>
    </div>

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

