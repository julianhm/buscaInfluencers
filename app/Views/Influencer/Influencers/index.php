

<body>

<script src="https://code.jquery.com/jquery-3.2.1.js"></script>

<script type="text/javascript">

    $(document).ready(function() {

        setTimeout(function() {

            $(".alert").fadeOut(1500);

        },3000);

    });

</script>



<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">

  <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">

    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>

  </symbol>

  <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">

    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>

  </symbol>

  <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">

    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>

  </symbol>

</svg>

<!--

<div class="alert alert-primary d-flex align-items-center" role="alert">

  <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>

  <div>

    An example alert with an icon

  </div> -->

</div>


 <!--MENSAJES FLASH-->

<?php if(session('mensaje')!=""){  ?>
 
    <style>
        .alert {
            padding: 20px;
            background-color: #fff;
            color: #000;
            opacity: 1;
            transition: opacity 0.6s;
            margin-bottom: 15px;
            box-shadow: -4px 1px 11px -2px #888888;

            position: absolute;
            z-index: 2;
            right: 10px;
            top: 10px;
        }

        .alert.info {
            background-color: #fff;
        }

        .closebtn {
            margin-left: 15px;
            color: #000;
            font-weight: bold;
            float: right;
            font-size: 22px;
            line-height: 20px;
            cursor: pointer;
            transition: 0.3s;
        }

        .closebtn:hover {
            color: black;
        }
    </style>

    <script>
        var close = document.getElementsByClassName("closebtn");
        var i;

        for (i = 0; i < close.length; i++) {
            close[i].onclick = function(){
            var div = this.parentElement;
            div.style.opacity = "0";
            setTimeout(function(){ div.style.display = "none"; }, 600);
            }
        }
    </script>

    <div class="alert info user-decription-black" style="font-size: 17px;" role="alert">
        <span class="closebtn">&times;</span>  
        <i class="fa fa-info-circle" ></i> <?php echo session('mensaje'); ?>
    </div>

<?php } ?>




   

    <!-- Header End -->



<!--

<div class="alert alert-warning d-flex align-items-center" role="alert">

  <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>

  <div>

    An example warning alert with an icon

  </div>

</div>

<div class="alert alert-danger d-flex align-items-center" role="alert">

  <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>

  <div>

    An example danger alert with an icon

  </div>

</div>

-->



    <!-- Header Start -->

    <div class="container-fluid ">

        <div class="row ">

            <div class="text-center text-main-title" >

                    <b>LOS SEGUIDORES QUE TU MARCA NECESITA SIGUEN A UN INFLUENCER</b>

            </div>

            

            <div>

                <img class="main-logo" src='<?php echo base_url("img/logo-white.png")?>' alt="Binf" >

            </div>



            <div class="col bg-main-inf" >

            
                <?php
                    if(!isset($_SESSION['idinfluencer'])){?>

                <div >
                    <div class="text-center type-user">
                        SOY
                    </div>

                    <div class="text-center user-decription">
                        Si eres influencer y quieres darte a conocer <br>
                        fácilmente, comienza aquí.
                    </div>

                    <div class="text-center pt-5" >

                        <a href="influencer/new">
                        <button type="button" class="btn btn-light btn-sm" style="width: 180px; font-weight: 100;">REGISTRARME</button>
                        </a>
                    </div>

                    <div class="text-center pt-3" >
                        <button type="button" class="btn btn-sm btn-login" style="width: 180px; font-weight: 100;" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            INICIAR SESIÓN
                        </button>
                    </div>

                </div>

                <?php } else { ?>
    
                <div>
                    
                    <div style="text-align: center;">
                        <img src="<?php echo base_url("/uploads")."/".$_SESSION['foto'] ?>" alt="Avatar" style="aspect-ratio: 1 / 1;width:100px;border-radius: 50%;border: 1px solid #fff">
                    </div>

                    <div class="text-center pt-4" >
                        <a href="<?php echo base_url("/influencer/edit/")."/".$_SESSION['alias'] ?>">
                            <button type="button" class="btn btn-light btn-sm" style="width: 180px; font-weight: 100;">Mi perfil</button>
                        </a>
                    </div>

                    <div class="text-center pt-3" >
                        <a href="<?php echo base_url("logout")?>">
                            <button type="button" class="btn btn-light btn-sm" style="width: 180px; font-weight: 100;">Cerrar sesión</button>
                        </a>
                    </div>
                </div>

                <?php } ?>
                
            </div>

            

            <div class="col bg-main-cli" >

                <div class="text-center type-user">

                    BUSCO

                </div>

                <div class="text-center user-decription">

                    Si buscas un influencer para fortalecer tu marca, <br>

                    impulsar tu negocio, promover un evento, una <br>

                    campaña, una idea, llegaste al lugar indicado.

                </div>

                <div class="text-center pt-5" >

                    <a href="<?=base_url('busqueda')?>">

                        <button type="button" class="btn btn-light btn-sm" style="width: 180px; font-weight: 100;">BUSCAR</button>

                    </a>

                    

                </div>

            </div>

        </div>

    </div>

    <!-- Header End -->





<div class="container-new">



    





    <!-- Talentos Recientes Start -->

    <div class="container-fluid  py-5">

        <div class="container-fluid">

            <div class="mx-auto text-center mb-5 main-section-title" style="max-width: 500px;">

                Talentos recientes

            </div>

           

            <div class="owl-one owl-carousel px-5">

                 <?php foreach ($datos as $key => $m) {?>

                    <div>

                        <div class="product-item position-relative d-flex flex-column text-center">

                            <a href="<?php echo base_url('perfil')."/".$m['alias']?>">
                                <img class='img-fluid mb-4' style='aspect-ratio: 1 / 1; border: 1px solid #000; margin-left: auto; margin-right: auto;' src=" <?= base_url('uploads')."/".$m['foto_perfil'] ?>" alt='' >
                            </a>

                            <h6 class="user-decription-black"><?=$m['nombreinflu']?> <br> <?=$m['alias']?> <br> <?=$m['nombrecat']?> </h6>

                            <div class="container-fluid">

                                <a href="<?php echo base_url('perfil')."/".$m['alias']?>"><button type="button" class="btn btn-ver-perfil btn-sm btn-on-white">Ver perfil</button></a>

                            </div>

                        </div>

                    </div>



                <?php } ?> 

                

                

            </div>

            <br>

            <br>

        </div>

    </div>

    <!-- Talentos Recientes End -->





    <div >

      <img class="line" src="img/line.png">

    </div>





    <!-- Mundo influencer Start -->

    <div id="noticias" class="container-fluid py-5">

        <div class="container-fluid">

            <div class="mx-auto text-center mb-5 main-section-title" style="max-width: 500px;">

                Mundo influencer

            </div>

            <div class="owl-two owl-carousel owl-theme">

                <?php  foreach ($noticias as $key => $m) {?>

                    <a class="container-img" href="<?=base_url('noticia')."/".$m['idnoticia']?>">

                    <div position-relative d-flex flex-column user-decription">

                        <div class="mundo-description"><b><?=$m['titulo']?></b></div>

                        <img class="img-fluid-mundo-influencer mb-4 img-effect" style="height: 350px; object-fit: cover;" src="<?=base_url('fotosnoticias')."/".$m['url_foto']?>">

                    </div></a>

                <?php }?>

                

            </div>

        </div>

    </div>

    <!-- Mundo influencer End -->







    <!-- Categoria Start -->

    <div class="container-fluid ">

        <div class="container-fluid">

            <div class="mx-auto text-center mb-5 main-section-title" style="max-width: 600px;">

                Encuéntralo por categoría

            </div>

            

            <div class="owl-three owl-carousel px-5">

            

                <?php foreach ($categorias as $key => $m) {?>

                                   <div>

                       <div class="product-item position-relative d-flex flex-column text-center" >

                           <a class="container-img" href="busqueda/resultado/<?=$m['idcategoria']?>" >

                           

                           <img class="img-fluid-categoria img-effect" src="<?=base_url('img').'/categorias/'.$m['imagen']?>" alt="Tecnologia">

                           <div class="py-2 categoria-description " style="background-color: #B3AFAE; border: 0px; font-size: 15px;">

                               <?php echo $m['nombrecat'] ?>

                           </div>

                           

                           </a>                                

                       </div>

                   </div>

   

                   <?php } ?>

                

                

            </div>

            

        </div>

    </div>

    <!-- Categoria End -->





</div>





    <!-- El/la + seguido Start -->

    <div class="container-fluid pt-3" style="background-color: #00ffff;">



        <div class="container-new">

        <div class="container-fluid">

            <div class="mx-auto text-center mb-5 main-section-title" style="max-width: 600px;">

                El/la más seguidos(as) en

            </div>

            

            <div class="owl-four owl-carousel px-5">

                 

                <?php foreach ($informacion as $key => $m) {?>

                           

                <div>

                    <div class="product-item position-relative d-flex flex-column text-center">

                        <div class="tag-categoria">

                            <?=$m['nombrecat']?>

                        </div>

                        <a href="<?php echo base_url('perfil')."/".$m['alias']?>">
                            <img class="img-fluid-el-mas mb-2" style="" src="<?=base_url('uploads')."/".$m['foto_perfil']?>" >
                        </a>
                        
                        <h6 class="user-decription-black"><?=$m['nombreinflu']?> <br> <?=$m['alias']?></h6>

                        <div class="container-fluid">

                            <div class="row ">

                                <div class="col-lg-3">

                                    <div style="width: 40px; margin: auto" >

                                        <img class="img-sm-main" style="object-fit: cover; margin: auto" src="<?=base_url('img')."/iconos/".$m['icono']?>" >

                                    </div>

                                        

                                </div>

                                <div class="col-lg-9" >

                                    <p class="followers-sm-main"><?=$m['cant_seguidores']?></p>

                                </div>

                            </div>

                            <div class="row text-center mb-3">

                                <h6 class="user-decription-black">Seguidores</h6>

                            </div>

                            <a href="<?php echo base_url('perfil')."/".$m['alias']?>"><button type="button" class="btn btn-ver-perfil btn-sm btn-on-white">Ver perfil</button></a>

                        </div>

                    </div>

                </div>

                <?php } ?>

                           

            </div>

            

        </div>

        </div>



        

    </div>

    <!-- El/la + seguido End -->



   





    <!-- Donar Start -->

   <div class="container py-5 ">

        <div class="row">

            <div class="col-8 text-center donar-decription-black" style="padding-left: 20%;">

                Buscoinfluencers es un servicio gratuito, construido y sostenido para la industria de generadores de <br>

                contenido. Es de uso y consulta libre. Si deseas ayudarnos a su sostenimiento y que siempre pueda <br>

                estar disponible, bienvenidas las donaciones. Cualquier cariño se te agradecerá.

            </div>

            <div class="col-4" >

                <a href="https://www.paypal.com/paypalme/LGWDS" target="_blank"> <img class="img-donar" src="img/donar.png" ></a>

            </div>

        </div>

        <div class="row">

            <div class="col" style="padding-left: 20%;">

                <button type="button" class="btn btn-contactar btn-lg" data-bs-toggle="modal" data-bs-target="#exampleModal2">Contáctanos si necesitas un representante</button>

            </div>

        </div>

    </div>

    <!-- Donar End -->





    <!-- ============================================================== -->

                        <!-- MODALES -->

    <!-- ============================================================== -->

    

    <!-- Modal Login Start -->

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-sm modal-dialog-centered">

            <div class="modal-content" style="border-radius: 0rem; border: 2px solid #000;">

                <div class="modal-header">

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>

                <div class="modal-body " style="padding-top">

                    <form class="user-decription-black" action="login" method="POST">

                        <div class="mb-3">

                            <label for="exampleInputEmail1" class="text-center user-decription-black mb-2" style="font-weight: bold; display: block;">CORREO</label>

                            <div class="input-container">
                                <input type="email" class="input-redes" style="margin-bottom: 0px;border-right: none;" id="emaillogin" name="emaillogin" aria-describedby="emailHelp" placeholder="correo electrónico">
                                <i class="fa fa-envelope icon"></i>
                            </div>
                    

                        </div>

                        <div class="mb-3">

                            <label for="exampleInputPassword1" class="text-center user-decription-black mb-2" style="font-weight: bold; display: block;">CONTRASEÑA</label>

                            <div class="input-container">
                                <input type="password" class="input-redes" style="margin-bottom: 0px;border-right: none;" id="passwordlogin" name="passwordlogin" placeholder="Contraseña">
                                <i class="fa fa-eye  icon" id="togglePassword"></i>
                            </div>


                            <style>

                                input:focus, textarea:focus {
                                border-color: #000000;
                                outline: none;
                                box-shadow: none;
                                }
                                .input-container {
                                display: -ms-flexbox; /* IE10 */
                                display: flex;
                                width: 100%;
                                margin-bottom: 15px;
                                }

                                .icon {
                                padding: 10px 10px 0px 10px;
                                cursor: pointer;
                                color: #000;
                                min-width: 40px;
                                text-align: center;
                                border-top: 1px solid;
                                border-right: 1px solid;
                                border-bottom: 1px solid;
                                }

                                .input-field {
                                width: 100%;
                                padding: 10px;
                                outline: none;
                                }
                            </style>

                            <script>
                                const togglePassword = document.querySelector('#togglePassword');
                                const password = document.querySelector('#passwordlogin');

                                togglePassword.addEventListener('click', function (e) {
                                    // toggle the type attribute
                                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                                    password.setAttribute('type', type);
                                    // toggle the eye slash icon
                                    this.classList.toggle('fa-eye-slash');
                                });
                            </script>

                        </div>


                        <button type="submit" class="btn btn-ingresar user-decription btn-lg mt-3" style="border-radius: 50px; width: 100%;" >Iniciar sesión</button>


                    </form>

                </div>

                <div class="modal-footer user-decription-black" style="font-weight: bold; ">

                    

                    <a style="color: #000;" href="olvido">¿Olvidaste tu contraseña?</a>

                </div>

            </div>

        </div>

    </div>

    <!-- Modal Login End -->



    



    <!-- Modal Representante Start -->

    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content" style="border-radius: 0rem; border: 2px solid #000;">

                <div class="modal-header">

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>

                <div class="modal-body ">

                    <div class="text-center user-decription-black" style="font-weight: bold; font-size: 35px">

                        

                        <p>¿BUSCAS <br> REPRESENTANTE?</p>

                    </div>

                    <form action="representante" class="user-decription-black" style="padding-left: 40px; padding-right: 40px;"  method="POST">

                        <div class="mb-3">

                            <input type="text" class="input-redes" style="text-align: center;" id="represenatnteInputNombre" name="represenatnteInputNombre" aria-describedby="emailHelp" placeholder="TU NOMBRE">

                    

                        </div>

                        <div class="mb-3">

                            <input type="email" class="input-redes" style="text-align: center;" id="representanteInputEmail" name="representanteInputEmail" aria-describedby="emailHelp" placeholder="TU CORREO">

                    

                        </div>



                        <div class="text-center">

                            <button type="submit" class="btn btn-get-info user-decription btn-lg">SOLICITAR INFORMACIÓN</button>

                        </div>



                    </form>

                </div>

                <div class="modal-footer"></div>

            </div>

        </div>

    </div>

    <!-- Modal Representante End -->







    <!-- Modal Representante Confirmación Start -->

    <div class="modal fade" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content" style="border-radius: 0rem; border: 2px solid #000;">

                <div class="modal-header">

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>

                <div class="modal-body ">

                    <img class="img-hecho mb-3" src="img/hecho.png" >

                    <div class="text-center user-decription-black" style="font-weight: bold; font-size: 35px">

                        

                        <p>¡HECHO!</p>

                    </div>

                    <div class="text-center user-decription-black" style="font-weight: bold; font-size: 15px">

                        <p>PRONTO NOS CONTACTAREMOS CONTIGO. <br>

                            EL EQUIPO BINF.</p>

                    </div>

                    

                </div>

                <div class="modal-footer"></div>

            </div>

        </div>

    </div>

    <!-- Modal Representante Confirmación End -->





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



    



