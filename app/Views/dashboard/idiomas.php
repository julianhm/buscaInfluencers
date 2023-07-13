         
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
                        <h4 class="page-title main-section-title">Idiomas</h4>
                    </div>
<!--  -->
                     <!--MENSAJES FLASH-->
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
                        <h4 class="card-title"><?php ?></h4>
                        <div>
                            <p>
                                <a class="btn btn-white-normal btn-lg" href="<?= route_to('nuevoidiomadash') ?>" class="btn btn-info">Crear Idioma</a>

                                
                            </p>
                        </div>

                        <div class="table-responsive user-decription-black">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>Nombre</th>
                                        <!--<th>Borrar</th>-->
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    $cont=0;
                                    foreach ($idiomas as $key => $m) {
                                        $cont++;?>
                                        
                                   
                                    <tr>
                                        <td><?=$cont?></td>
                                        <td><?=$m['nombre']?></td>
                                        
                                       <!-- <td><a id="<?=$m['ididioma']?>"  data-bs-toggle="modal" data-bs-target="#exampleModalEliminarid" data-placement="top" title="Eliminar Idioma" class="btn btn-danger" onclick="recibir(<?=$m['ididioma']?>);"><i class="mdi mdi-window-close menu-icon" ></i></a></td>-->
                                    </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
                                <script>
                                        function recibir(numero)
                                        {
                                            //alert(document.getElementById(numero).id);
                                            //var valor = document.getElementById("eliminar"+numero).value;
                                           
                                            document.getElementById("eliminaridiomamodal").value=numero;        
                                            
                                        }
                                </script>
                        </div>
                    </div>
                </div>
  
                <div class="container">
                <?=$pager->links();?>  
                                    </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->

             <!-- Modal Representante Confirmación Start -->

    <div class="modal fade" id="exampleModalEliminarid" tabindex="-1" aria-labelledby="exampleModalEliminarid" aria-hidden="true">

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

                IDIOMA. ESTA ACCION NO SE PUEDE REVERTIR</p>

            </div>
            <form action="<?= route_to('eliminarIdiomdash') ?>" method="POST" class="form-horizontal"  enctype="multipart/form-data">
                <input id="eliminaridiomamodal" name="eliminaridiomamodal" type="hidden" >


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
   