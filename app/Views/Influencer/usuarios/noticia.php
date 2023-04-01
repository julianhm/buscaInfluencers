

    <!-- Content noticia Start -->
    <div class="container">
        <div class="row">
            <div class="text-center user-decription-black" >
                <div class="col-lg-12 my-3">
                    <div class="mb-3 mt-5" >
                        <a href="<?=base_url()?>"><h2 class="mypro-section-title" ><i class="fa fa-arrow-left" style="font-size: 13px;"></i>  Volver</h2></a>
                    </div>

                    <h2 class="main-section-title" style="font-size: 30px; font-weight: 100;"><?=$noticias['titulo'] ?></h2>
                    <br>
                    <br>
                    <div class="row text-center" style="display: block; margin-left: auto; margin-right: auto;">
                        <img style="width: 70%" src="<?=base_url('fotosnoticias')."/".$noticias['url_foto'] ?>" >
                    </div>
                    <br>
                    <br>

                    <div class="row " style="font-size: 20px">
                        <p style="text-align:center"><span style="color:#e74c3c"><?=$noticias['cuerpo']?></span></p>


                       
                        <p>&nbsp;&nbsp;</p>

                    </div>

                    <div style="text-align: left; font-size: 15px; background-color: #e5e5e5;">
                        <p><b>Fuentes:&nbsp;</b> </p>
                        <?php foreach ($fuente as $key => $m) {?>
                            <p><a href="<?=$m['fuente'] ?>" rel="noreferrer noopener" target="_blank"><?=$m['fuente'] ?></a>&nbsp;</p>
                        <?php } ?>
                        
                       </div>
                </div>
            </div>
        
            <div class="mb-3 mt-5" style="border-top: 1px solid #000;">
                <h2 class="mypro-section-title">Noticias recomendadas</h2>
            </div>

            <div>
            <?php foreach ($recomendada as $key => $m) {?>

                <p><u><a href="<?=base_url('noticia')."/".$m['idnoticia'] ?>"><?=$m['titulo'] ?></a></u></p>
            <?php } ?>
            </div>

        </div>
    </div>

    <!-- Content noticia End -->
    








