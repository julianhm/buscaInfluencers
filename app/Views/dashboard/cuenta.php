
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-5 align-self-center">
                        <h4 class="page-title main-section-title">Mi cuenta</h4>
                    </div>
                   


                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">

                <div class="row">
 <!--MENSAJES FLASH-->
 <?php if(session('mensaje')!=""){  ?>
    <div class="alert alert-success d-flex align-items-center" role="alert">

        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
        <div>
        <?php echo session('mensaje'); ?>
        
        </div>

        </div>
    <?php } ?>

                <!-- Column -->
                    <div class="col-lg-4 col-xlg-3">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="page-title main-section-title" style="font-size: 20px;">Perfil</h4>
                                <center class="mt-4">
                                    <img src=<?=base_url("fotosnoticias/".$administrador['url_foto'])?> class="rounded-circle" width="150">
                                    <h4 class="card-title mt-2 user-decription-black" style="font-size: 20px"><?=$administrador['nombre']?></h4>
                                    <h6 class="card-subtitle">Administrador</h6>
                                    <form action="<?= route_to('cambiarFotoAdmindash') ?>" method="POST" class="register-form pt-2" id="loginAdmin" name="loginAdmin" enctype="multipart/form-data">
                                    <?= csrf_field() ?> 
                                        <div>
                                            <small class="user-decription-black">Subir ímagen</small>
                                            <input type="file" class="form-control mt-2" id="fotoAdmin" name="fotoAdmin">
                                        </div>
                                        <div class="card-body " style="text-align: center;">
                                            <button type="submit" class="btn btn-ver-perfil btn-sm btn-on-white" style="font-size: 18px; padding: 4px 30px !important;">Cambiar Foto</button>
                                        </div>
                                    </form>
                    
                                </center>
                            </div>
                            <div>
                                <hr>
                            </div>
                            <form action="<?= route_to('cambiarNombreAdmindash') ?>" method="POST" class="register-form pt-2" id="loginAdmin" name="loginAdmin" enctype="multipart/form-data">
                                        <?= csrf_field() ?> 
                                <div class="card-body">
                                    <small class="user-decription-black">Cambiar nombre</small>
                                    <div class="mb-3">
                                        <input type="text" class="form-control" style="margin-top: 8px;" value="<?=$administrador['nombre']?>" id="newnombre" name="newnombre">
                                    </div>

                                    <small class="user-decription-black pt-4">Correo</small>
                                    <div class="mb-3">
                                        <input type="email" class="form-control" style="margin-top: 8px;" value="<?=$administrador['correo']?>" id="newcorreo" name="newcorreo">
                                    </div>

                                    <div class="card-body " style="text-align: center;">
                                        <button type="submit" class="btn btn-ver-perfil btn-sm btn-on-white" style="font-size: 18px; padding: 4px 30px !important;">Actualizar perfil</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-8 col-xlg-9">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="page-title main-section-title" style="font-size: 20px;">Cambiar contraseña</h4>
                                <form action="<?= route_to('cambiarClaveAdmindash') ?>" method="POST" class="register-form pt-2" id="loginAdmin" name="loginAdmin" enctype="multipart/form-data">
                                        <?= csrf_field() ?>
                    
                                    <div class="pt-4">
                                        <small class="col-md-8 user-decription-black">Anterior contraseña</small>
                                        <div class="col-md-8 pt-2">
                                            <input type="password" id= "passadmin" name= "passadmin" value="" class="form-control form-control-line" placeholder="Escriba su password actual">
                                        </div>
                                    </div>

                                    <div class="pt-4">
                                        <small class="col-md-8 user-decription-black">Nueva contraseña</small>
                                        <div class="col-md-8 pt-2">
                                            <input type="password" id= "newpassadmin" name= "newpassadmin" value="" class="form-control form-control-line" placeholder="Escriba su nuevo password">
                                        </div>
                                    </div>

                                    <div class="pt-4">
                                        <small class="col-md-8 user-decription-black">Confirmación nueva contraseña</small>
                                        <div class="col-md-8 pt-2">
                                            <input type="password" id= "confnewpassadmin" name= "confnewpassadmin value="" class="form-control form-control-line" placeholder="Confirma tu nuevo password">
                                        </div>
                                    </div>
                        
                                    <div class="pt-4">
                                        <div class="col-sm-8">
                                            <div class="card-body " style="text-align: center;">
                                                <button type="submit" class="btn btn-ver-perfil btn-sm btn-on-white" style="font-size: 18px; padding: 4px 30px !important;">Cambiar contraseña</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
            <!-- Column -->



          </div>
  

            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            
   