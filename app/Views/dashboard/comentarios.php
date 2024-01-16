
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
                            <h4 class="card-title" style="font-weight: bold;">Comentarios de recientes en perfiles</h4>
                        </div>
                            <?php if(session('mensaje')!=""){  ?>
                                <div class="alert alert-success d-flex align-items-center" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
                        <div>
                        <?php echo session('mensaje'); ?>
                        
                    </div>

                </div>
                <?php } ?>

            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Lista de comentarios</button>
                        <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Aprobados</button>
                </div>
            </nav>



            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div class="comment-widgets" style="height: 430px">
                        <div class="box-body">
                            <table id="tablaComentarios" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                    <th>Influencers</th>
                                    <th>Enviado Por</th>
                                    <th>Empresa</th>
                                    <th>Mensaje</th>
                                    <th>Autorizar</th>
                                    <th>Editar</th>
                                    <th>Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($mensajesInfluencer as $key => $m) {?>
                                    <tr>
                                        <td><?php echo $influencers[array_search($m['idinfluencer'], array_column($influencers, 'idinfluencer'))]['alias']?></td>
                                        <td><?=$m['nombre'] ?>  </td>
                                        <td><?=$m['empresa'] ?></td>
                                        <td><?=$m['cuerpo'] ?></td>
                                        <td><a href="<?=base_url('dashboard/aprobarcomentarios').'/'.$m['idmensaje']?>" class="btn btn-success"><i class="mdi mdi-comment-check" title="Aprobar mensaje" ></i></a></td></td>
                                        <td><a class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalEditarComentario" data-placement="top" title="Editar comentario"  onclick="editarComentario(<?=$m['idmensaje']?>,'<?=$m['cuerpo']?>');" ><i class="mdi mdi-comment-text" title="Editar mensaje" ></i></a>
                                        
                                        </td>
                                    <td><a data-bs-toggle="modal" data-bs-target="#exampleModalEliminarMens" data-placement="top" title="Eliminar comentario" class="btn btn-danger" onclick="recibir3(<?=$m['idmensaje']?>);" ><i class="mdi mdi-comment-remove-outline menu-icon" title="Eliminar mensaje"></i></a></td>
                                    </tr>
                                <?php } ?>
                
                
              </table>
            </div>

            <div class="container">
                <?=$pager->links();?>  
                                    </div>

    




</div>



  </div>
  <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">

        <!--open-->


        <div class="table-responsive">
        <table id="tablaComentariosaprobados" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Influencers</th>
                  <th>Enviado Por</th>
                  <th>Empresa</th>
                  <th>Mensaje</th>
                  <th>Eliminar</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($mensajesInfluenceraprobados as $key => $m) {?>
                <tr>
                  <td><?php echo $influencers[array_search($m['idinfluencer'], array_column($influencers, 'idinfluencer'))]['alias']?></td>
                  <td><?=$m['nombre'] ?>  </td>
                  <td><?=$m['empresa'] ?></td>
                  <td><?=$m['cuerpo'] ?></td>
                  <td><a data-bs-toggle="modal" data-bs-target="#exampleModalEliminarMens" data-placement="top" title="Eliminar comentario" class="btn btn-danger" onclick="recibir3(<?=$m['idmensaje']?>);" ><i class="mdi mdi-comment-remove-outline menu-icon" title="Eliminar mensaje"></i></a></td>
                </tr>
                <?php } ?>
                
                
              </table>
            </div>
            <div class="container">
                <?=$pager->links();?>  
                                    </div>

                                </div>

        <!--close-->

  </div>

</div>
                            



                        </div>
                    </div>
                </div>


 


                
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
      

<script>
        function recibir3(numero)
        {
            //alert(document.getElementById(numero).id);
            //var valor = document.getElementById("eliminar"+numero).value;
           
            document.getElementById("eliminarcomentariomodal").value=numero;        
            
        }
        
        
        function editarComentario(numero,cuerpo)
        {
            //alert("LLEGUEEEEE");
            document.getElementById("idEditarComentario").value=numero;  
            document.getElementById("cuerpomodal").value=cuerpo;       
            
        }

        

        
</script>      

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

                        COMENTARIO?.</p>

                    </div>

                    <form action="<?= route_to('eliminarComentariosdash') ?>" method="POST" class="form-horizontal"  enctype="multipart/form-data">
                        <input id="eliminarcomentariomodal" name="eliminarcomentariomodal" type="hidden" >


                        <div class="text-center">

                            <button type="submit" id="botonEliminar" class="btn btn-get-info user-decription btn-lg" >Eliminar</button>

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


<!-- Modal Editar Comentario -->

<div class="modal fade" id="modalEditarComentario" tabindex="-1" aria-labelledby="exampleModalEliminarMens" aria-hidden="true">

<div class="modal-dialog modal-dialog-centered">

    <div class="modal-content" style="border-radius: 0rem; border: 2px solid #000;">

        <div class="modal-header">

            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

        </div>

        <div class="modal-body ">
       <div class="text-center user-decription-black" style="font-weight: bold; font-size: 35px">

                        

                <p>EDITAR COMENTARIO</p>

            </div>
            

            <form action="<?= route_to('editarComentariosdash') ?>" method="POST" class="form-horizontal"  enctype="multipart/form-data">
                
            <input id="idEditarComentario" name="idEditarComentario" type="hidden" >
               
                <p><textarea name="cuerpomodal" id="cuerpomodal" class="form-control" ></textarea></p>


                <div class="text-center">

                    <button type="submit" class="btn btn-get-info user-decription btn-lg" >Editar</button>

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