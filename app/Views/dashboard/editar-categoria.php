
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
                        <h4 class="page-title main-section-title">Crear noticia</h4>
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
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card card-body">
                            <h5 class="card-title user-decription-black text-center" style="font-weight: bold;">CREA UNA NUEVA CATEGORIA</h5>
                            <form action="<?= route_to('editarnuevacategoriadash') ?>" method="POST" class="form-horizontal"  enctype="multipart/form-data">
                            <input type="hidden" class="form-control" id="idcategoria" name="idcategoria" value='<?=$categoria['idcategoria']?>'>
                            

                                <div class="mb-3">
                                    <label class="user-decription-black">Nombre (*)</label>
                                    <input type="text" class="form-control" id="nombrenewcategoria" name="nombrenewcategoria" value='<?=$categoria['nombrecat']?>'>
                                </div>

                               <!-- <div class="mb-3">
                                    <label class="user-decription-black">Descripción</label>
                                    <textarea class="form-control" rows="5" id="descripcionnewnoticia" name="descripcionnewnoticia"></textarea>
                                </div>-->

                                <div class="mb-3">
                                    <label for="formFile" class="form-label user-decription-black">Subir imagen de la categoria</label>
                                    <input class="form-control" type="file" id="fotonewcategoria" name="fotonewcategoria">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label user-decription-black"><input type="checkbox" id="esvisible2" name ="esvisible2" value="seleccionado" <?php if($categoria['mostradas']==1){echo "checked";}?> > Visible</label>
                                </div>
                                
                               




                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="card card-body">
                                            
                                            <div class="card-body border-top" style="text-align: center;">
                                            <button type="submit" class="btn btn-ver-perfil btn-sm btn-on-white" style="font-size: 18px; padding: 4px 30px !important;">Crear Categoria</button>
                                                </div>
                                        </div>
                                    </div>
                                </div> 

                      
                            </form>
                        </div>
                    </div>
                </div>

                
  

            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
