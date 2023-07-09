         
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
                        <h4 class="page-title main-section-title">Noticias</h4>
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
                                <a class="btn btn-white-normal btn-lg" href="<?= route_to('nuevanoticiadash') ?>" class="btn btn-info">Crear noticia</a>

                                
                            </p>
                        </div>

                        <div class="table-responsive user-decription-black">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>Fecha</th>
                                        <th>Titulo</th>
                                        <th>Foto</th>
                                        <th>Favorito</th>
                                        <th>Editar</th>
                                        <th>Borrar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $cont=0;
                                    foreach ($noticias as $key => $m) {
                                        $cont++;?>
                                        
                                   
                                    <tr>
                                        <td><?=$cont?></td>
                                        <td><?=$m['created_at']?></td>
                                        <td><?=$m['titulo']?></td>
                                        <td><?=$m['url_foto']?></td>
                                        <td><?php if($m['favorito']==1){
                                            echo "SI";}?></td>
                                        <td><a href="<?=base_url('/dashboard/editarNoticia/'.$m['idnoticia']) ?>" class="btn btn-success"><i class="mdi mdi-pencil menu-icon" ></i></a></td>
                                        
                                        <td><a id="<?=$m['idnoticia']?>"  data-bs-toggle="modal" data-bs-target="#exampleModalEliminarnot" data-placement="top" title="Eliminar noticia" class="btn btn-danger" onclick="recibir(<?=$m['idnoticia']?>);"  ><i class="mdi mdi-window-close menu-icon" ></i></a></td>
                                    </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
                                <script>
                                        function recibir(numero)
                                        {
                                            //alert(document.getElementById(numero).id);
                                            //var valor = document.getElementById("eliminar"+numero).value;
                                           
                                            document.getElementById("eliminarnoticiamodal").value=numero;        
                                            
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

    <div class="modal fade" id="exampleModalEliminarnot" tabindex="-1" aria-labelledby="exampleModalEliminarnot" aria-hidden="true">

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

                <p>ESTAS SEGURO DE QUERER ELIMINAR ESTA <br>

                NOTICIA. ESTA ACCION NO SE PUEDE REVERTIR</p>

            </div>
            <form action="<?= route_to('eliminarNotidash') ?>" method="POST" class="form-horizontal"  enctype="multipart/form-data">
                <input id="eliminarnoticiamodal" name="eliminarnoticiamodal" type="hidden" >


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
   