<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?= $titulo ?></title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href=<?php echo base_url('img/favicon2.ico');?> rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Customized Bootstrap Stylesheet -->
    <link href=<?php echo base_url('css/bootstrap.min.css');?> rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href=<?php echo base_url('css/style.css');?> rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.1.4/pagination.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.1.4/pagination.css"/>

  <link rel='stylesheet' href=<?php echo base_url('lib/owlcarousel/assets/owl.carousel.min.css');?>>
  <link rel='stylesheet' href=<?php echo base_url('lib/owlcarousel/assets/owl.theme.green.min.css');?>>
  <link rel='stylesheet' href=<?php echo base_url('lib/owlcarousel/assets/owl.theme.default.min.css');?>>
  <link rel='stylesheet' href=<?php echo base_url('css/animate.min.css');?>>
  <link rel='stylesheet' href=<?php echo base_url('css/upload.img.css');?>>
  <link rel="stylesheet" href=<?php echo base_url("css/lightbox.min.css");?>>

    <link rel='stylesheet' href=<?php echo base_url('css/star-rating.css');?>>
    <link rel='stylesheet' href=<?php echo base_url('css/star-index.css');?>>
  
</head>

<body>


    <!-- Header Start -->
    <div id="header" class="container-fluid mb-3 px-4 headerNav">
        <div class="row ">
            <div class="row g-0 pb-2 position_title">
                <div class="text-center text-title-normal" >
                    <b>LOS SEGUIDORES QUE TU MARCA NECESITA SIGUEN A UN INFLUENCER</b>
                </div>
            </div>
            <div class="mx-auto text-end mb-2 mt-2 main-section-title">
               <?=$titulo?>
            </div>
            <hr class="header_black_line">
            <a style="display: contents;" href="<?=base_url()?>"><img class="logo-header-normal" src=<?php echo base_url('img/logo-blue.png')?> > </a>

                
        </div>

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

   
    <!-- Header End -->