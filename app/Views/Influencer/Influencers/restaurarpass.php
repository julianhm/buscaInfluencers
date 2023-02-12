



   
    <!-- Content Register Start -->
    <div class="container">
        <div class="row">
            <div class="text-center" >

           
                

                <form action="../../restaurarpassword" method="POST" class="register-form pt-2"  enctype="multipart/form-data">
                <?= csrf_field() ?>     
                
                        
                        <div class="d-flex justify-content-center mb-4">
                            <div class="section-title">
                               Escribe tu Nueva Clave
                            </div>
                        </div>
                        <input class="input-modify" type="hidden" name="idinflu" id="idinflu" value="<?=$influencer['idinfluencer']?>">
                       
                      
                        <div class="form-group">
                            <img class="icon-input" src=<?php echo base_url('img/icon-pass.png')?> for="password">

                            <input class="input-modify" type="password" name="passwordrestaurado" id="passwordrestaurado" placeholder="Escribe una clave" >
                        </div>
                        <div class="form-group">
                            <img class="icon-input" src=<?php echo base_url('img/icon-pass.png')?> for="passwordver">

                            <input class="input-modify" type="password" name="passwordrestauradover" id="passwordrestauradover" placeholder="Escribe nuevamente tu clave" >
                        </div>
                        

                        <div style="padding-left: 10%; padding-right: 10%;">
                            <hr class="break_line">
                        </div>


                        

                        <div class="d-flex justify-content-center mb-4">
                            <div class="btn-register">
                                <input type="submit" name="submit" id="submit"class="btn btn-login btn-lg btn-register-width user-decription-black" style="font-size: 20px;" value= "RESTAURAR!"/>
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

