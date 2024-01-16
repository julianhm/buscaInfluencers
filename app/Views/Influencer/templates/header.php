<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?= $titulo ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Influencer, Influenciador, Marketing de influencer, Twittero, Instagrammer, Youtuber, Tiktoker, Blogger, Bloggero, Seguirdor, Popular, Edutuber, Poscaster, creador de contenido" name="keywords">
        
    <!-- Metadata para mostrar cuando se comparte el vinculo -->
    <meta content="<?= (isset($titulo_meta) ? $titulo_meta : 'buscoinfluencers.com') ?>" property="og:title">
    <meta content="<?= (isset($url_foto) ? $url_foto : 'https://oficial.buscoinfluencers.com/public/img/logo-blue.png') ?>" property="og:image">
    <meta content="<?= (isset($descripcion) ? $descripcion : 'busconfluencers.com, pretende conectar a distintos creadores de contenido/influencers con personas o empresarios interesados en su contenido y asi entablar negociaciones') ?>" name="description">
    
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Last-Modified" content="0">
    <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
    <meta http-equiv="Pragma" content="no-cache">

    <?php
    header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
    header('Expires: Sat, 1 Jul 2000 05:00:00 GMT'); // Fecha en el pasado
    ?>

    <!-- Favicon -->
    <link href=<?php echo base_url('img/favicon2.ico');?> rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <!-- <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap" rel="stylesheet"> -->
    <link href=<?php echo base_url('css/opensans.css');?> rel="stylesheet">
    <!-- Icon Font Stylesheet -->
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">-->
    <link href=<?php echo base_url('css/all.min.css');?> rel="stylesheet">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet"> -->
    <link href=<?php echo base_url('css/bootstrap-icons.css');?> rel="stylesheet">
        <!-- Customized Bootstrap Stylesheet -->
    <link href=<?php echo base_url('css/bootstrap.min.css');?> rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href=<?php echo base_url('css/style.css?n=4');?> rel="stylesheet">
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.1.4/pagination.min.js"></script>-->
    <script src=<?php echo base_url('js/pagination.min.js');?>></script>

<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.1.4/pagination.css"/> -->
    <link href=<?php echo base_url('css/pagination.css');?> rel="stylesheet">

  <link rel='stylesheet' href=<?php echo base_url('lib/owlcarousel/assets/owl.carousel.min.css');?>>
  <link rel='stylesheet' href=<?php echo base_url('lib/owlcarousel/assets/owl.theme.green.min.css');?>>
  <link rel='stylesheet' href=<?php echo base_url('lib/owlcarousel/assets/owl.theme.default.min.css');?>>
  <link rel='stylesheet' href=<?php echo base_url('css/animate.min.css');?>>
  <link rel='stylesheet' href=<?php echo base_url('css/upload.img.css');?>>
  <link rel="stylesheet" href=<?php echo base_url("css/lightbox.min.css");?>>

    <link rel='stylesheet' href=<?php echo base_url('css/star-rating.css');?>>

    <!-- crop photo -->
    <link rel='stylesheet' href=<?php echo base_url('css/cropper.css');?>>

    
  
</head>

<body>


    <style>
        
        .navbar-menu {
          overflow: hidden;
          /*background-color: #333;*/
          /*position: fixed;*/
          /*top: 0;*/
          font-family: 'Myriad Pro Regular';
          width: 100%;
        }
        
        .navbar-menu a {
          float: right;
          display: block;
          /*color: #f2f2f2;*/
          color: #000000;
          text-align: center;
          padding: 14px 16px;
          text-decoration: none;
          font-size: 15px;
          font-weight: 600;
        }
        
        .navbar-menu a:hover, .dropdown:hover .dropbtn {
          /*background: #ffffff;
          color: black;
          */
          background: #000;
          color: #fff;
        }

        
        
        .nav-dropdown {
            float: right;
            overflow: hidden;
        }
        .nav-dropdown .dropbtn {
            font-size: 15px;
            font-weight: 600;
            border: none;
            outline: none;
            color: #000;
            padding: 12px 14px;
            background-color: inherit;
            font-family: inherit;
            margin: 0;
            
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #fff;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 5;
            right: 0;
            left: auto
            
        }

        .dropdown-content a {
            float: none;
            color: #000;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
        }

        .dropdown-content a:hover {
            background-color: #000;
        }

        .nav-dropdown:hover .dropdown-content {
            display: block;
            
        }

    </style>


    <!-- Header Start -->
    <div id="header" class="container-fluid mb-2 px-4 headerNav">
        <div class="row ">
            <div class="row g-0 pb-2 position_title">
                <div class="text-center text-title-normal" >
                    <b>LOS SEGUIDORES QUE TU MARCA NECESITA SIGUEN A UN INFLUENCER</b>
                </div>
            </div>
            <div class="mx-auto text-end mb-2 mt-2 main-section-title"><?=$titulo?></div>
            <hr class="header_black_line">
            <a style="display: contents;" href="<?=base_url()?>"><img class="logo-header-normal" src=<?php echo base_url('img/logo-blue.png')?> > </a>

                
        </div>

        
        <!-- MENU -->
        <div class="row">
            <div class="navbar-menu">

            <?php
            //var_dump($_SESSION);
                if(isset($_SESSION['idinfluencer'])){ ?>

                    <div class="nav-dropdown">
                    <button class="dropbtn">
                        <img src="<?php echo base_url("/uploads")."/".$_SESSION['foto'] ?>" alt="Avatar" style="aspect-ratio: 1 / 1;width:30px;border-radius: 50%;">
                        <i class="fa fa-caret-down"></i>
                    </button>
                        <div class="dropdown-content">
                            <a href="<?php echo base_url("/influencer/edit/")."/".$_SESSION['idinfluencer'] ?>">Mi perfil</a>
                            <a href="<?php echo base_url("logout")?>">Cerrar sesión</a>
                        </div>
                    </div> 

            <?php } ?>

                <a href="<?=base_url('busqueda')?>">Buscar</a>
                <a href="<?=base_url('#noticias')?>">Noticias</a>
                
            </div>
           
        </div>


        

    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.js"></script>

    <script type="text/javascript">

        $(document).ready(function() {

            setTimeout(function() {

                $(".alert").fadeOut(1500);

            },3000);

        });

    </script>
    
    <!--MENSAJES FLASH-->
    <?php if(session('mensaje')!=""){  ?>
        
        <style>
            .alert {
                padding: 20px;
                background-color: #fff;
                color: #000;
                opacity: 1;
                transition: opacity 0.6s;
                margin-bottom: 15px;
                box-shadow: -4px 1px 11px -2px #888888;

                position: absolute;
                z-index: 2;
                right: 10px;
                top: 10px;
            }

            .alert.info {
                background-color: #fff;
            }

            .closebtn {
                margin-left: 15px;
                color: #000;
                font-weight: bold;
                float: right;
                font-size: 22px;
                line-height: 20px;
                cursor: pointer;
                transition: 0.3s;
            }

            .closebtn:hover {
                color: black;
            }
        </style>

        <script>
            var close = document.getElementsByClassName("closebtn");
            var i;

            for (i = 0; i < close.length; i++) {
                close[i].onclick = function(){
                var div = this.parentElement;
                div.style.opacity = "0";
                setTimeout(function(){ div.style.display = "none"; }, 600);
                }
            }
        </script>

        <div class="alert info user-decription-black" style="font-size: 17px;" role="alert">
            <span class="closebtn">&times;</span>  
            <i class="fa fa-info-circle" ></i> <?php echo session('mensaje'); ?>
        </div>

    <?php } ?>

   
    <!-- Header End -->