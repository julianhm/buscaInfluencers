
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
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
                        <h4 class="page-title main-section-title">Mensajes</h4>
                    </div>
                    <!--
                    <div class="col-7 align-self-center">
                        <div class="d-flex align-items-center justify-content-end">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="#">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    -->
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
                    <!-- column -->
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body user-decription-black">
                                <h4 class="card-title" style="font-weight: bold;">Comentarios recientes</h4>
                            </div>
                            <?php if(session('mensaje')!=""){  ?>
                    <div class="alert alert-success d-flex align-items-center" role="alert">

                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
                        <div>
                        <?php echo session('mensaje'); ?>
                        
                        </div>

                        </div>
                    <?php } ?>
                            <div class="comment-widgets" style="height: 430px">

                                <?php foreach ($mensajesAdmin as $key => $m) {?>

                                    <!-- Comment Row -->
                                    <div class="d-flex flex-row comment-row mt-0 user-decription-black">
                                
                                        <div class="comment-text w-100">
                                            <div class="comment-header ">
                                                <?php if ($m['leido']==0) {?>
                                                    <a href="<?=base_url()."/dashboard/mensajeleido/".$m['idmensaje']?>" class="btn btn-success"><i class="mdi mdi-account-check" title="Mensaje no leido"  ></i></a></td>
                                                <?php } else{?>
                                                    <a href="#" class="btn btn-primary"><i class="mdi mdi-account-check" title="Mensaje leido" ></i></a></td>
                                                <?php } ?>
                                                    <a id="<?=$m['idmensaje']?>" data-bs-toggle="modal" data-bs-target="#exampleModalEliminarMens" data-placement="top" title="Eliminar mensaje" class="btn btn-danger" onclick="recibir3(<?=$m['idmensaje']?>);" ><i class="mdi mdi-account-remove menu-icon" ></i></a>
                                            </div>
                                            <br>
                                            <div class="comment-body ">
                                                <h6 class="font-medium" style="font-weight: bold;"><?=$m['nombre']?></h6><span class="mb-3 d-block"><?=$m['cuerpo']?></span>
                                            </div>
                                            <div class="comment-footer ">
                                                <span class="text-muted float-end user-decription-black"><?=$m['correo']?></span>
                                            </div>
                                          
                                            
                                        </div>
                                    </div>

                                <?php } ?>
                                <script>
                                        function recibir3(numero)
                                        {
                                            //alert(document.getElementById(numero).id);
                                            //var valor = document.getElementById("eliminar"+numero).value;
                                           
                                            document.getElementById("eliminarmensamodal").value=numero;        
                                            
                                        }
                                </script>

                               
                            </div>
                        </div>
                    </div>
                </div>
            
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            

                <!-- Modal Representante Confirmación Start -->

    <div class="modal fade" id="exampleModalEliminarMens" tabindex="-1" aria-labelledby="exampleModalEliminarMens" aria-hidden="true">

<div class="modal-dialog modal-dialog-centered">

    <div class="modal-content" style="border-radius: 0rem; border: 2px solid #000;">

        <div class="modal-header">

            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

        </div>

        <div class="modal-body ">

            <img class="img-hecho mb-3" src="<?=base_url('img/warning.png')?>" >

            <div class="text-center user-decription-black" style="font-weight: bold; font-size: 35px">

                

                <p>¡ADVERTENCIA!</p>

            </div>

            <div class="text-center user-decription-black" style="font-weight: bold; font-size: 15px">

                <p>ESTAS SEGURO DE QUERER ELIMINAR ESTE <br>

                MENSAJE?.</p>

            </div>

            <form action="<?= route_to('eliminarMensajedash') ?>" method="POST" class="form-horizontal"  enctype="multipart/form-data">
                <input id="eliminarmensamodal" name="eliminarmensamodal" type="hidden" >


                <div class="text-center">

                    <button type="submit" id="botonEliminar" class="btn btn-get-info user-decription btn-lg" >Aceptar</button>

                </div>
            </form>
            <br>
            <div class="text-center">

                <button  class="btn btn-get-info user-decription btn-lg"  data-bs-dismiss="modal" aria-label="Close">Cancelar</button>

            </div>



        </div>

        <div class="modal-footer"></div>

    </div>

</div>

</div>

<!-- Modal Representante Confirmación End -->