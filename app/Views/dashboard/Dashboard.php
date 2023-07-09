

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
                        <h4 class="page-title main-section-title"><?= $usuario2 ?></h4>
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

                <!-- ============================================================== -->
                <!-- Estadisticas -->
                <!-- ============================================================== -->
                <!-- Row 1 -->
                <div class="card-group">
                    <!-- Column 1 -->
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 user-decription-black">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <i class="ri-emotion-line fs-6 text-muted"></i>
                                            <p class="fs-4 mb-1">Total influencers: </p>
                                        </div>
                                        <div class="ms-auto">
                                            <h1 class="fw-light text-end"><?= $cantidadinfluencers ?></h1>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="progress">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: <?=$cantidadinfluencers/500*100?>%; height: 6px" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column 1 -->
                    <!-- Column 2-->
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 user-decription-black">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <i class="ri-image-fill fs-6 text-muted"></i>
                                            <p class="fs-4 mb-1">Nuevos influencers hoy: </p>
                                        </div>
                                        <div class="ms-auto">
                                            <h1 class="fw-light text-end"><?=$cantidadinfluencersUltimo?></h1>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: <?=$cantidadinfluencersUltimo/$cantidadinfluencers*100?>%; height: 6px" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column 2-->
                 </div>
                  <!-- Row 1 -->
        
                <!-- Row 2 -->
                <div class="card-group">
                <!-- Column 1-->
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 user-decription-black" >
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <i class="ri-money-euro-circle-line fs-6 text-muted"></i>
                                            <p class="fs-4 mb-1">Total clientes o empresas: </p>
                                        </div>
                                        <div class="ms-auto">
                                            <h1 class="fw-light text-end"><?=$cantidadMensajes?></h1>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="progress">
                                        <div class="progress-bar bg-purple" role="progressbar" style="width: <?=$cantidadMensajes/100*100?>%; height: 6px" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column 1-->
                    <!-- Column 2-->
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 user-decription-black">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <i class="ri-bar-chart-fill fs-6 text-muted"></i>
                                            <p class="fs-4 mb-1">Total De Noticias: </p>
                                        </div>
                                        <div class="ms-auto">
                                            <h1 class="fw-light text-end"><?=$cantidadNoticias?></h1>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="progress">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: <?=$cantidadNoticias/100*100?>%; height: 6px" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column 2-->
                </div>
                 <!-- Row 2 -->



                <!-- ============================================================== -->
                <!-- Comentarios -->
                <!-- ============================================================== -->
                <div class="row">
                    <!-- column -->
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body user-decription-black">
                                <h4 class="card-title" style="font-weight: bold;">Correos enviados a influencers recientemente</h4>
                            </div>
                            <div class="comment-widgets" style="height: 430px">
                            <?php
                            if(count($comentarios)>=3){
                                for ($i=0;$i<3; $i++) {?>
                                    
                                    <!-- Comment Row -->
                                    <div class="d-flex flex-row comment-row mt-0 user-decription-black">
                                        <div class="comment-text w-100">
                                            <h6 class="font-medium" style="font-weight: bold;"><?=$comentarios[$i]['nombre'];?></h6><span class="mb-3 d-block"><?=$comentarios[$i]['empresa'];?></span>
                                            <div class="comment-footer ">
                                                <span class="text-muted float-end user-decription-black"><?=$comentarios[$i]['created_at'];?></span>
                                            </div>
                                        </div>
                                    </div>

                            <?php }}else{
                                for ($i=0;$i<count($comentarios); $i++) {?>
                                    
                                    <!-- Comment Row -->
                                    <div class="d-flex flex-row comment-row mt-0 user-decription-black">
                                        <div class="comment-text w-100">
                                            <h6 class="font-medium" style="font-weight: bold;"><?=$comentarios[$i]['nombre'];?></h6><span class="mb-3 d-block"><?=$comentarios[$i]['empresa'];?></span>
                                            <div class="comment-footer ">
                                                <span class="text-muted float-end user-decription-black"><?=$comentarios[$i]['created_at'];?></span>
                                            </div>
                                        </div>
                                    </div>

                            <?php } } ?>
                     
                            </div>
                        </div>
                    </div>
                </div>


                <!-- ============================================================== -->
                <!-- Ultimos influencers -->
                <!-- ============================================================== -->
                <div class="row">
                    <!-- column -->
                    <div class="col-12">
                        <div class="card user-decription-black">
                            <div class="card-body">
                                <h4 class="card-title" style="font-weight: bold;">Ultimos influencers</h4>
                            </div>
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
                                    if(count($influencers)>8){
                                    for($i=0; $i<8;$i++){ ?>

                                        <tr>

                                            <td class="txt-oflo"><?=$influencers[$i]['nombreinflu']?></td>
                                            <td><span class="label label-success label-rounded"><?=$influencers[$i]['alias']?></span> </td>
                                            <td class="txt-oflo"><?=$influencers[$i]['correo']?></td>
                                            <td><span class="font-medium"><?=$influencers[$i]['oferta']?></span></td>
                                        </tr>

                                    <?php }} else { 
                                        for($i=0; $i<count($influencers);$i++){ ?>

                                        <tr>

                                            <td class="txt-oflo"><?=$influencers[$i]['nombreinflu']?></td>
                                            <td><span class="label label-success label-rounded"><?=$influencers[$i]['alias']?></span> </td>
                                            <td class="txt-oflo"><?=$influencers[$i]['correo']?></td>
                                            <td><span class="font-medium"><?=$influencers[$i]['oferta']?></span></td>
                                        </tr>

                                        <?php }} ?>                                       
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
  

            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            
