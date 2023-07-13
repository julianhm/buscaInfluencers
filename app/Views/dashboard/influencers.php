

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
                        <h4 class="page-title main-section-title">Influencers</h4>
                    </div>

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
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Ultimos influencers -->
                <!-- ============================================================== -->
                <div class="row">
                    <!-- column -->
                    <div class="col-12">
                        <div class="card user-decription-black">
                            
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="border-top-0">NOMBRE</th>
                                            <th class="border-top-0">ALIAS</th>
                                            <th class="border-top-0">CORREO</th>
                                            <th class="border-top-0">OFERTA</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php 
                                   
                                    foreach ($influ as $key => $m) {
                                    ?>

                                        <tr>

                                            <td class="txt-oflo"><?=$m['nombreinflu']?></td>
                                            <td><span class="label label-success label-rounded"><?=$m['alias']?></span> </td>
                                            <td class="txt-oflo"><?=$m['correo']?></td>
                                            <td><span class="font-medium"><?=$m['oferta']?></span></td>
                                            <td><a data-bs-toggle="modal" data-bs-target="#exampleModalEliminarInflu" data-placement="top" title="Eliminar Influencer" class="btn btn-danger" onclick="recibir2(<?=$m['idinfluencer']?>);" ><i class="mdi mdi-account-remove menu-icon" ></i></a></td>
                                        </tr>

                                    <?php  }  ?>

                                                                              
                                    </tbody>
                                </table>
                                <script>
                                        function recibir2(numero)
                                        {
                                           
                                            //var valor = document.getElementById("eliminar"+numero).value;
                                           
                                            document.getElementById("eliminarinfluencermodal").value=numero;  
                                            //alert(numero);      
                                            
                                        }
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
                <?=$pager->links();?> 
  
            </div>                                
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            
 <!-- Modal Representante Confirmación Start -->

 <div class="modal fade" id="exampleModalEliminarInflu" tabindex="-1" aria-labelledby="exampleModalEliminarInflu" aria-hidden="true">

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

                INFLUENCER.</p><br>
                <p>ESTA ACCION ES IRREVERSIBLE </p>

            </div>

            <form action="<?= route_to('eliminarInfludash') ?>" method="POST" class="form-horizontal"  enctype="multipart/form-data">
                <input id="eliminarinfluencermodal" name="eliminarinfluencermodal" type="hidden" >


                <div class="text-center">

                    <button type="submit"  class="btn btn-get-info user-decription btn-lg" >Aceptar</button>

                </div>
            </form>



        </div>

        <div class="modal-footer"></div>

    </div>

</div>

</div>

<!-- Modal Representante Confirmación End -->