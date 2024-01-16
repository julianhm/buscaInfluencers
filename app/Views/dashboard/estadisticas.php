<!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<style>
   
    .txt-id, .txt-mail {
        font-weight: 700;
    }

    .padding-mail {
        margin-right: 5px;
    }

    .validado-true {
        color: #5ac146;
    }

    .validado-false {
        color: #fa5838;
    }
                
</style>
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
                <h4 class="page-title main-section-title">Estadisticas</h4>
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
                                    <p class="fs-4 mb-1"><i class="mdi mdi-account-multiple"></i> Total de influencers: </p>
                                </div>
                                <div class="ms-auto">
                                    <h1 class="fw-light text-end"><?= $cantidadInfluencers ?></h1>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="progress">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: <?=$cantidadInfluencers*100?>%; height: 6px" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column 1 -->
            <!-- Column 2 -->
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 user-decription-black">
                            <div class="d-flex no-block align-items-center">
                                <div>
                                    <i class="ri-emotion-line fs-6 text-muted"></i>
                                    <p class="fs-4 mb-1"><i class="validado-false mdi mdi-close-circle"></i> No activados: </p>
                                </div>
                                <div class="ms-auto">
                                    <h1 class="fw-light text-end"><?= $cantidadNoValidos ?></h1>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="progress">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: <?=$cantidadNoValidos/$cantidadInfluencers*100?>%; height: 6px" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 user-decription-black">
                            <div class="d-flex no-block align-items-center">
                                <div>
                                    <i class="ri-image-fill fs-6 text-muted"></i>
                                    <p class="fs-4 mb-1"><i class="validado-true mdi mdi-checkbox-marked-circle"></i> Activados: </p>
                                </div>
                                <div class="ms-auto">
                                    <h1 class="fw-light text-end"><?= $cantidadValidos ?></h1>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: <?=$cantidadValidos/$cantidadInfluencers*100?>%; height: 6px" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column 2 -->
            
            <!-- Column 3-->
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 user-decription-black">
                            <div class="d-flex no-block align-items-center">
                                <div>
                                    <i class="ri-image-fill fs-6 text-muted"></i>
                                    <p class="fs-4 mb-1"><span class='label label-danger label-rounded'>Cantidad 0</span>  </p>
                                </div>
                                <div class="ms-auto">
                                    <h1 class="fw-light text-end"><?= $cantidad_0 ?></h1>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="progress">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: <?=$cantidad_0/$cantidadInfluencers*100?>%; height: 6px" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 user-decription-black">
                            <div class="d-flex no-block align-items-center">
                                <div>
                                    <i class="ri-image-fill fs-6 text-muted"></i>
                                    <p class="fs-4 mb-1"><span class='label label-warning label-rounded'>Cantidad 50</span>  </p>
                                </div>
                                <div class="ms-auto">
                                    <h1 class="fw-light text-end"><?= $cantidad_50 ?></h1>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="progress">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: <?=$cantidad_50/$cantidadInfluencers*100?>%; height: 6px" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 user-decription-black">
                            <div class="d-flex no-block align-items-center">
                                <div>
                                    <i class="ri-image-fill fs-6 text-muted"></i>
                                    <p class="fs-4 mb-1"><span class='label label-success label-rounded'>Cantidad 100</span>  </p>
                                </div>
                                <div class="ms-auto">
                                    <h1 class="fw-light text-end"><?= $cantidad_100 ?></h1>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: <?=$cantidad_100/$cantidadInfluencers*100?>%; height: 6px" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- Column 3-->
        </div>
        <!-- Row 1 -->
        
        <!-- Row 2 -->
        <div class="row">
            <div class="col-lg-6">
                <div class="card-group">
                    <!-- Column 1-->
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 user-decription-black" >
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <i class="ri-image-fill fs-6 text-muted"></i>
                                            <p class="fs-4 mb-1"><i class="mdi mdi-comment-check"></i> Total de reseñas: </p>
                                        </div>
                                        <div class="ms-auto">
                                            <h1 class="fw-light text-end"><?= $totalComentarios ?></h1>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="progress">
                                        <div class="progress-bar bg-purple" role="progressbar" style="width: 1%; height: 6px" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 user-decription-black" >
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <i class="ri-image-fill fs-6 text-muted"></i>
                                            <p class="fs-4 mb-1"><i class="mdi mdi-comment-account"></i> Perfiles con reseñas: </p>
                                        </div>
                                        <div class="ms-auto">
                                            <h1 class="fw-light text-end"><?= $influencersComentados ?></h1>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="progress">
                                        <div class="progress-bar bg-purple" role="progressbar" style="width: 1%; height: 6px" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column 1-->
            
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card-group">
                    <!-- Column 2-->
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 user-decription-black" >
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <i class="ri-image-fill fs-6 text-muted"></i>
                                            <p class="fs-4 mb-1"><i class="mdi mdi-message-bulleted"></i> Total de clientes: </p>
                                        </div>
                                        <div class="ms-auto">
                                            <h1 class="fw-light text-end"><?= $totalMensajes ?></h1>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="progress">
                                        <div class="progress-bar bg-purple" role="progressbar" style="width: 1%; height: 6px" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 user-decription-black" >
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <i class="ri-image-fill fs-6 text-muted"></i>
                                            <p class="fs-4 mb-1"><i class="mdi mdi-message-processing"></i> Perfiles con clientes: </p>
                                        </div>
                                        <div class="ms-auto">
                                            <h1 class="fw-light text-end"><?= $totalInfluencersConMensajes ?></h1>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="progress">
                                        <div class="progress-bar bg-purple" role="progressbar" style="width: 1%; height: 6px" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column 2-->
            
                </div>
            </div>

        </div>
        <!-- Row 2 -->


        
        <!-- Row 3 -->
        <div class="row">
            <div class="col-lg-6 user-decription-black">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><i class="mdi mdi-comment-account"></i> Cantidad de reseñas recientes por perfiles (12)</h4>
                    </div>

                    <div class="comment-widgets" style="height:550px;">
                    
                        <div class="list-wrapper-1">

                            <?php foreach (array_slice($infoInfluencerComentados, 0, 12) as $key => $value) { ?>
                                
                                <div class="list-item-1">

                                    <div class="d-flex flex-row comment-row mt-0" id="container2">
                                        <div class="p-2">
                                            <img src="<?php echo base_url("/uploads")."/".$value['foto_perfil']?>" alt="user" width="50" class="rounded-circle" style="aspect-ratio: 1 / 1">
                                        </div>
                                        <div class="comment-text w-100">
                                            <div class="d-flex no-block align-items-center">
                                                <div>
                                                    <p class="fs-4 mb-1"><?= $value['nombreinflu'] ?> </p>
                                                </div>
                                                <div class="ms-auto">
                                                    <h3 class="fw-light text-end"><?= $value['numcomentarios'] ?></h3>
                                                </div>
                                            </div>
                                            <span >Última vez conectado:</span>
                                            <span class="badge bg-info float-end" style="color: #fff"><?= $value['updated_at'] ?></span>
                                            
                                        </div>
                                    </div>
            
                                </div>
                            <?php } ?>
                        </div>
                        
                        <div id="pagination-container-1"></div>
                        
                    </div>

                    <link rel='stylesheet' href=<?php echo base_url('css/normalize.min.css');?>>
                    <link rel='stylesheet' href=<?php echo base_url('css/style-pagination-dash.css');?>>
                    <script src=<?php echo base_url("js/jquery.min.v3.3.1.js")?>></script>
                    <script src=<?php echo base_url("js/jquery.simplePagination.js")?>></script>
                    <script src=<?php echo base_url("js/script-pagination.js")?>></script>

                </div>
            </div>



            <div class="col-lg-6 user-decription-black">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><i class="mdi mdi-message-processing"></i> Cantidad de clientes recientes por perfil (12)</h4>
                    </div>

                    <div class="comment-widgets" style="height:550px;">
                    
                        <div class="list-wrapper2">

                            <?php 
                                foreach (array_slice($infoInfluencersMensajeados, 0, 12) as $key => $value) { ?>
                                
                                <div class="list-item2">

                                    <div class="d-flex flex-row comment-row mt-0" id="container2">
                                        <div class="p-2">
                                            <img src="<?php echo base_url("/uploads")."/".$value['foto_perfil']?>" alt="user" width="50" class="rounded-circle" style="aspect-ratio: 1 / 1">
                                        </div>
                                        <div class="comment-text w-100">
                                            <div class="d-flex no-block align-items-center">
                                                <div>
                                                    <p class="fs-4 mb-1"><?= $value['nombreinflu'] ?> </p>
                                                </div>
                                                <div class="ms-auto">
                                                    <h3 class="fw-light text-end"><?= $value['nummensajes'] ?></h3>
                                                </div>
                                            </div>
                                            <span >Última vez conectado:</span>
                                            <span class="badge bg-secondary float-end" style="color: #fff"><?= $value['updated_at'] ?></span>
                                            
                                        </div>
                                    </div>
            
                                </div>
                            <?php  }?>
                        </div>

                    </div>

                </div>
            </div>

        </div>
        <!-- Row 3 -->
            
    </div> 
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->                               
</div>