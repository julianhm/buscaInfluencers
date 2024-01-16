

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
                                    <th class="border-top-0">ID</th>
                                    <th class="border-top-0">VALIDO</th>
                                    <th class="border-top-0">NOMBRE</th>
                                    <th class="border-top-0">PERFIL</th>
                                    <th class="border-top-0">CORREO</th>
                                    <th class="border-top-0">FALTA</th>
                                    <th class="border-top-0">ENVIAR</th>
                                    <th class="border-top-0">ELIMINAR</th>
                                </tr>
                            </thead>

                            <tbody id="container">

                            </tbody>

                            <tbody>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="user-decription-black" style="font-weight: bold; font-size: 20px">
                                    Todos a Mailchimp 
                                </td>
                                <td colspan="2" style="text-center">
                                    <a href="<?=base_url('dashboard/enviarTodos/perfil_0')?>">
                                        <button type="submit" class="btn btn-danger" title="Completar perfil (Todos)" ><i class='mdi mdi-email-outline'></i></button>  
                                    </a>
                                    <a href="<?=base_url('dashboard/enviarTodos/perfil_50')?>">
                                        <button type="submit" class="btn btn-warning" title="Perfil incompleto (Todos)" ><i class='mdi mdi-email-outline'></i></button>  
                                    </a>
                                    <a href="<?=base_url('dashboard/enviarTodos/perfil_100')?>">
                                        <button type="submit" class="btn btn-success" title="Perfil completo (Todos)" ><i class='mdi mdi-email-outline'></i></button>  
                                    </a>                                    
                                </td>
                            </tbody>

                        </table>

                        <script>
                            function recibir2(numero)
                            {
                                document.getElementById("eliminarinfluencermodal").value=numero; 
                            }
                            function recibirCorreo(validado, estado, mail, id, token, nombre) {
                                //Activar
                                document.getElementById("validadoactivar").value=validado;
                                document.getElementById("correoactivar").value=mail;
                                document.getElementById("idactivar").value=id;
                                document.getElementById("tokenactivar").value=token;

                                //Estado en 0
                                //console.log("sale "+estado);
                                document.getElementById("correo_0").value=mail;
                                document.getElementById("nombre_0").value=nombre;
                                
                                //Estado en 50
                                document.getElementById("correo_50").value=mail;
                                document.getElementById("nombre_50").value=nombre;

                                //Estado en 100
                                document.getElementById("correo_100").value=mail;
                                document.getElementById("nombre_100").value=nombre;
                            }     
                        </script>

                    </div>

                    <div class="text-center">
                        <div class="pagination mt-4">
                            <a href="javascript:prevPage()" id="btn_prev">&laquo;Anterior</a>
                            <a id="pa" style="align:center"></a>
                            <a href="javascript:nextPage()" id="btn_next">Siguiente&raquo;</a>
                        </div>
                    </div>

                    
                    
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

                    <script>

                        var obj = <?=json_encode($influencers); ?>;
                        //alert(obj);
                        var current_page = 1;
                        var obj_per_page = 8;
                        
                        function totNumPages()
                        {
                            //console.log(Math.ceil(obj.length / obj_per_page));
                            return Math.ceil(obj.length / obj_per_page);
                        }

                        function prevPage()
                        {
                            if (current_page > 1) {
                                current_page--;
                                change(current_page);
                            }
                        }
                        function nextPage()
                        {
                            if (current_page < totNumPages()) {
                                current_page++;
                                change(current_page);
                            }
                        }
                        function change(page)
                        {
                            var btn_next = document.getElementById("btn_next");
                            var page_span = document.getElementById("pa");
                            var btn_prev = document.getElementById("btn_prev");
                            var listing_table = document.getElementById("container");
                            
                            if (page < 1) page = 1;
                            if (page > totNumPages()) page = totNumPages();
                            
                            page_span.innerHTML = page;
                            
                            if (page == 1) {
                                btn_prev.style.visibility = "hidden";
                                page_span.classList.add("active");
                            } else {
                                btn_prev.style.visibility = "visible";
                            }
                            if (page == totNumPages()) {
                                btn_next.style.visibility = "hidden";
                            } else {
                                btn_next.style.visibility = "visible";
                            }

                            listing_table.innerHTML = "";
                                              
                            for (var i = (page-1) * obj_per_page; i < (page * obj_per_page); i++) {
                                                                
                                listing_table.innerHTML += 
                                
                                "<tr>"+
                                    "<td class='txt-id'>"+obj[i].idinfluencer+"</td>"+
                                    "<td><i title="+(obj[i].validado == 0 ? "'No confirmo correo'" : "'Confirmado'" )+" class='"+(obj[i].validado == 0 ? "validado-false mdi mdi-close-circle" : "validado-true mdi mdi-checkbox-marked-circle" )+"'></i></td>"+
                                    "<td>"+obj[i].nombreinflu+"</td>"+
                                    "<td><span class='label "+ (obj[i].estado == 0 ? "label-danger" : obj[i].estado == 50 ? "label-warning" :  "label-success") +" label-rounded'>"+obj[i].estado+"</span> </td>"+
                                    "<td><span class='txt-mail'>"+obj[i].correo+"</span></td>"+
                                    "<td>"+
                                        (typeof obj[i].noPhoto !== "undefined" ? "<span class='font-medium'>"+ obj[i].noPhoto+"</span><br>" : "")+
                                        (typeof obj[i].noRedes !== "undefined" ? "<span class='font-medium'>"+ obj[i].noRedes+"</span><br>" : "")+
                                        (typeof obj[i].noGaleria !== "undefined" ? "<span class='font-medium'>"+ obj[i].noGaleria+"</span><br>" : "")+
                                        (typeof obj[i].noVideo !== "undefined" ? "<span class='font-medium'>"+ obj[i].noVideo+"</span><br>" : "")+
                                        (typeof obj[i].noOferta !== "undefined" ? "<span class='font-medium'>"+ obj[i].noOferta+"</span><br>" : "")+
                                        (typeof obj[i].noIdiomas !== "undefined" ? "<span class='font-medium'>"+ obj[i].noIdiomas+"</span><br>" : "")+
                                        (typeof obj[i].noPagos !== "undefined" ? "<span class='font-medium'>"+ obj[i].noPagos+"</span><br>" : "")+
                                    "</td>"+
                                    "<td>"+
                                        
                                        "<a data-bs-toggle='modal' data-bs-target='#modalCorreos' data-placement='top' title='Enviar correo' class='btn btn-success' onclick=\"recibirCorreo("+obj[i].validado+", '"+obj[i].correo+"', "+obj[i].idinfluencer+", '"+obj[i].tokens+"', '"+obj[i].nombreinflu+"'); activarCorreoVerificacion();\"; ><i class='mdi mdi-email'></i></a>"+
                                    "</td>"+  
                                    "<td><a data-bs-toggle='modal' data-bs-target='#exampleModalEliminarInflu' data-placement='top' title='Eliminar Influencer' class='btn btn-danger' onclick='recibir2("+obj[i].idinfluencer+");' ><i class='mdi mdi-account-remove menu-icon'></i></a></td>"+
                                "</tr>";

                                page_span.innerHTML = page;
                                    
                            }
                            //alert(page);
                           
                        }
                                                
                        window.onload = function() {
                            change(1);
                        };

                    </script>

                </div>
            </div>
        </div>

        
    </div> 
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->                               
</div>
<!-- ============================================================== -->
<!-- End Page  -->
<!-- ============================================================== --> 




            

<!-- ============================================================== -->
<!-- Modal Eliminar Influencer Start  -->
<!-- ============================================================== -->  

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
                    INFLUENCER?</p><br>
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
<!-- Modal Eliminar Influencer End -->



<!-- ============================================================== -->
<!-- Modal Correos Start  -->
<!-- ============================================================== -->  

<div class="modal fade" id="modalCorreos" tabindex="-1" aria-labelledby="modalCorreos" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content" style="border-radius: 0rem; border: 2px solid #000;">

            <div class="modal-header">

            <h4 class="modal-title user-decription-black" style="font-weight: bold; font-size: 22px" id="myLargeModalLabel">Enviar correo </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body ">
                
                <div class="text-center user-decription-black" style="font-weight: bold; font-size: 25px">
                    <p>Individual</p>
                </div>
                                
                <script>
                    function activarCorreoVerificacion() {
                        var x = document.getElementById("validadoactivar").value;
                        var y = document.getElementById("correoVerificacion");
                        if (x == 1) {
                            y.disabled = true;
                        } else {
                            y.disabled = false;
                        }
                    }
                </script>

                <div class="row">
                    <div class="col-md-6 text-center user-decription-black">
                        <form action="<?= route_to('enviarActiInfludash') ?>" method="POST" class="form-horizontal"  enctype="multipart/form-data">
                            <input id="correoactivar" name="correoactivar" type="hidden" >
                            <input id="idactivar" name="idactivar" type="hidden" >
                            <input id="tokenactivar" name="tokenactivar" type="hidden" >
                            <input id="validadoactivar" name="validadoactivar" type="hidden" >
                            
                                <button id="correoVerificacion" type="submit"  class="btn btn-light" style="font-size: xx-large; border: solid 2px; border-radius: 10px;" ><i class='mdi mdi-email-outline'></i></button>
                            
                            <div>
                                Verificacón de cuenta
                            </div>
                            
                        </form>
                    </div>
                    <div class="col-md-6 text-center user-decription-black">
                        <form action="<?= route_to('enviarEstado_0_dash') ?>" method="POST" class="form-horizontal"  enctype="multipart/form-data">
                            <input id="correo_0" name="correo_0" type="hidden" >
                            <input id="nombre_0" name="nombre_0" type="hidden" >
                                            
                            <button type="submit"  class="btn btn-danger" style="font-size: xx-large; border-radius: 10px;" ><i class='mdi mdi-email-outline'></i></button>

                            <div>
                                0<br>
                                Completar perfil
                            </div>
                            
                        </form>
                    </div>
                    <div class="col-md-6 text-center user-decription-black" style="padding-top: 25px;">
                        <form action="<?= route_to('enviarEstado_50_dash') ?>" method="POST" class="form-horizontal"  enctype="multipart/form-data">
                            <input id="correo_50" name="correo_50" type="hidden" >
                            <input id="nombre_50" name="nombre_50" type="hidden" >
                                            
                            <button type="submit"  class="btn btn-warning" style="font-size: xx-large; border-radius: 10px;" ><i class='mdi mdi-email-outline'></i></button>

                            <div>
                                50<br>
                                Perfil incompleto
                            </div>
                            
                        </form>
                    </div>
                    <div class="col-md-6 text-center user-decription-black" style="padding-top: 25px;">
                        <form action="<?= route_to('enviarEstado_100_dash') ?>" method="POST" class="form-horizontal"  enctype="multipart/form-data">
                            <input id="correo_100" name="correo_100" type="hidden" >
                            <input id="nombre_100" name="nombre_100" type="hidden" >
                                            
                            <button type="submit"  class="btn btn-success" style="font-size: xx-large; border-radius: 10px;" ><i class='mdi mdi-email-outline'></i></button>

                            <div>
                                100<br>
                                Perfil completo
                            </div>
                            
                        </form>
                    </div>
                </div>
                
            </div>

            <div class="modal-footer">

                
            </div>

        </div>
    </div>
</div>
<!-- Modal Correos End -->