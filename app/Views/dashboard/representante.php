
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
                        <h4 class="page-title main-section-title">Solicitud de representante</h4>
                    </div>
                    <?php if(session('mensaje')!=""){  ?>
                    <div class="alert alert-success d-flex align-items-center" role="alert">

                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
                        <div>
                        <?php echo session('mensaje'); ?>
                        
                        </div>

                        </div>
                    <?php } ?>

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

                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><?php  ?></h4>
                        <div class="table-responsive user-decription-black">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Fecha</th>    
                                        <th>Nombre</th>
                                        <th>Correo</th>
                                        <!--<th>Aceptar</th>-->
                                        <th>Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($comentarios as $key => $m) {?>
                                    <tr>
                                        <td><?=$m['idcomentario']?></td>
                                        <td><?=$m['created_at']?></td>
                                        <td><?=$m['nombre']?></td>
                                        <td><?=$m['correo']?></td>
                          
                                        <!--<td><a href="<?php ?>" class="btn btn-success"><i class="mdi mdi-account-check" ></i></a></td>-->
                                       
                                        <td><a data-bs-toggle="modal" data-bs-target="#exampleModalEliminar" data-placement="top" title="Eliminar registro" class="btn btn-danger" onclick="recibir4(<?=$m['idcomentario']?>);"><i class="mdi mdi-account-remove menu-icon" ></i></a></td>
                                    </tr>                                   
                                
                                <?php  } ?>

                                    
                                </tbody>
                            </table>
                            <script>
                                        function recibir4(numero)
                                        {
                                            //alert(document.getElementById(numero).id);
                                            //var valor = document.getElementById("eliminar"+numero).value;
                                           
                                            document.getElementById("eliminarcomentariomodal").value=numero;        
                                            
                                        }
                                </script>
                        </div>
                    </div>
                </div>
  
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
    <!-- Modal Representante Confirmación Start -->

    <div class="modal fade" id="exampleModalEliminar" tabindex="-1" aria-labelledby="exampleModalEliminar" aria-hidden="true">

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

                        COMENTARIO.</p>

                    </div>

                    <form action="<?= route_to('eliminarComentariodash') ?>" method="POST" class="form-horizontal"  enctype="multipart/form-data">
                        <input id="eliminarcomentariomodal" name="eliminarcomentariomodal" type="hidden" >


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