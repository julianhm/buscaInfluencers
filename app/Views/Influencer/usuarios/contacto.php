


    <!-- Contact Start -->
    <div class="container-fluid py-5">
        <div class="container">

            <div class="row g-0 pb-5">
                <div class="col-lg-6">
                    <div class="h-100 contact-form-align black-right-line">

                        <div class="mb-3 user-decription-black contact-description-text">
                            <p>Sugerencias, inquietudes, preguntas, lo que sea:</p>
                        </div>

                        <form action="influencer/contactanos" method="POST" class="buscar-form pt-2" id="contactar" name="contactar">
                            <div class="row g-2">

                                <div class="col-12">
                                    <input class="input-redes" type="text" style="text-align: center;" name="nombrecontacto" id="nombrecontacto" placeholder="Nombre">
                                    <p class="help is-danger"><?=session('errors.nombrecontacto')?></p>
                                </div>

                                <div class="col-12">
                                    <input class="input-redes" type="text" style="text-align: center;" name="correocontacto" id="correocontacto" placeholder="Correo">
                                    <p class="help is-danger"><?=session('errors.correocontacto')?></p>
                                </div>

                                <div class="col-12">
                                    <textarea class="input-redes" style="text-align: center;" rows="6" name="cuerpocontacto" id="cuerpocontacto" placeholder="Escribe aquí tu comentario"></textarea>
                                    <p class="help is-danger"><?=session('errors.cuerpocontacto')?></p>
                                </div>

                                <div class="d-flex justify-content-center mt-5">  
                                    <button type="submit" class="btn btn-login btn-lg btn-contact-width user-decription-black">ENVIAR</button>  
                                </div>

                                <!-- data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#confirmationContactUs-modal"-->
                            
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-6">

                    <div class="h-100 contact-form-align">
                        <div class="mb-3 user-decription-black contact-description-text contact-margin-top">
                            <p>Publicidad</p>
                        </div>

                        <div class="row">
                            <div class="col-sm-3">
                                <img class="img-hecho contact-email-icon" src="img/contact-email.png" >
                            </div>
                            <div class="col-sm-9">
                                <div class="col pt-2" style="margin-bottom: 0px;">
                                    <div class="user-decription-black contact-description-text" style="padding: 6px 0;">
                                        <p>info@buscoinfluencers.com</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div style="display: flex;">
                                <div style="flex: 33.33%;">
                                    <img class="img-hecho contact-ws-phone-icon" src="img/contact-ws.png" >
                                    
                                </div>
                                <div style="flex: 33.33%;">
                                    
                                    <img class="img-hecho contact-ws-phone-icon" src="img/contact-number.png" >
                                </div>
                            </div>  
                        </div>
                        <div class="col-sm-9">
                            <div class="col pt-2" style="margin-bottom: 0px;">
                                <div class="user-decription-black contact-description-text" style="padding: 6px 0;">
                                    <p>(57) 123 4567890</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->



    <!-- ============================================================== -->
                            <!-- MODALES -->
    <!-- ============================================================== -->
    <!-- Modal Confirmación Start -->
    <div class="modal fade" id="confirmationContactUs-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 0rem; border: 2px solid #000;">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <img class="img-hecho mb-3" style="width: 25%;" src="img/stars.png" >
                    <div class="text-center user-decription-black" style="font-weight: bold; font-size: 35px">
                        
                        <p>¡LISTO!</p>
                    </div>
                    <div class="text-center user-decription-black" style="font-weight: bold; font-size: 15px">
                        <p>Gracias por tomarse el tiempo de escribirnos. Hemos recibido su mensaje. Estaremos en contacto con usted pronto.</p>
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

