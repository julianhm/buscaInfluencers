

    <!-- Content noticia Start -->
    <div class="container">
        <div class="row">
            <div class="user-decription-black" >
                <div class="col-lg-12 my-3">
                    
                    <h2 class="text-center main-section-title" style="font-size: 30px; font-weight: 100;"><?=$noticias['titulo'] ?></h2>
                    
                    <br>
                    <br>

                    <div class="row text-center" style="display: block; margin-left: auto; margin-right: auto;">
                        <img class="img-noticia" src="<?=base_url('fotosnoticias')."/".$noticias['url_foto'] ?>" >
                    </div>

                    <br>
                    
                    <div class="row " style="font-size: 20px">
                        <p style="text-align:center"><span style="color:#e74c3c"><?=$noticias['cuerpo']?></span></p>
                    </div>

                </div>
            </div>
        
            <div class="mb-3 mt-5" style="border-top: 1px solid #000;">
                <h2 class="mypro-section-title">Noticias recomendadas</h2>
            </div>

            <div>
                <?php foreach ($recomendada as $key => $m) {?>
                    <p>
                        <u>
                            <a href="<?=base_url('noticia')."/".$m['idnoticia'] ?>"><?=$m['titulo'] ?></a>
                        </u>
                    </p>
                <?php } ?>
            </div>

        </div>
    </div>

    <!-- Content noticia End -->
    








