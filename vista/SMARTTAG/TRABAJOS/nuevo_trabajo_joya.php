<script type="text/javascript">
    $(document).ready(function() {
        //  // restriccion();
        // Lista_clientes();
        autocoplet_material();
        autocoplet_cate();
        autocoplet_bodegas();
        autocoplet_estado_joya();
        var id = "<?php echo $id; ?>";
        var detalle = "<?php echo $detalle; ?>";
        console.log(id);
        if (id != '') {
            $('#txt_id_tra').val(id);
            $('#cod_nuevo').css('display', 'none');
            $('#cod_ant').css('display', 'initial');
            cargar_detalle(id);
        }
        if (detalle != '') {
            $('#txt_codigo').val(detalle);
        }
        // autocoplet_cliente();




        // ------------------------------------------------------
        $("#txt_proveedor").autocomplete({
            source: function(request, response) {
                var tipo = $('input[name="rbl_tipo"]:checked').val();
                $.ajax({
                    url: "../controlador/punto_ventaC.php?search_cliente_input&tipo=" + tipo,
                    type: 'post',
                    dataType: "json",
                    data: {
                        search: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            select: function(event, ui) {
                $('#txt_proveedor').val(ui.item.label); // display the selected text
                $('#txt_pro_id').val(ui.item.value); // save selected id to input
                return false;
            },
            focus: function(event, ui) {
                $('#txt_proveedor').val(ui.item.label); // display the selected text
                $('#txt_pro_id').val(ui.item.value); // save selected id to input

                return false;
            },
        });
        //----------------------------------------------------------------------

        // ------------------------------------------------------
        $("#txt_codigo").autocomplete({
            source: function(request, response) {

                $.ajax({
                    url: "../controlador/punto_ventaC.php?search_all_ord",
                    type: 'post',
                    dataType: "json",
                    data: {
                        search: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            select: function(event, ui) {
                $('#txt_nom_art').val(ui.item.nombre); // display the selected text
                $('#txt_codigo').val(ui.item.value); // save selected id to input
                $('#txt_bodega').val(ui.item.bodega); // save selected id to input
                $('#txt_pvp').val(ui.item.precio); // save selected id to input
                $('#txt_peso').val(ui.item.peso); // save selected id to input
                $('#ddl_bodega').append($('<option>', {
                    value: ui.item.bodega,
                    text: ui.item.bodega_nom,
                    selected: true
                }));
                $('#ddl_tipo_joya').append($('<option>', {
                    value: ui.item.id_tipo,
                    text: ui.item.tipo,
                    selected: true
                }));
                $('#ddl_material').append($('<option>', {
                    value: ui.item.id_ma,
                    text: ui.item.material,
                    selected: true
                }));
                // $("#img").attr("src",ui.item.foto+'?'+Math.random());
                // if(ui.item.foto!='')
                // {
                //   $('#btn_eli_img').css('display','block');
                // }
                return false;
            },
            focus: function(event, ui) {
                console.log(ui.item);
                $('#txt_nom_art').val(ui.item.nombre); // display the selected text
                $('#txt_codigo').val(ui.item.value); // save selected id to input
                $('#txt_bodega').val(ui.item.bodega); // save selected id to input
                $('#txt_bodega').empty();
                $('#txt_bodega').append($('<option>', {
                    value: ui.item.bodega,
                    text: ui.item.bodega_nom,
                    selected: true
                }));

                return false;
            },
        });
        //----------------------------------------------------------------------

        $("#btn_sub_img").on('click', function() {
            Swal.fire({
                title: 'La orden de trabajo se guardara con los datos ingresados',
                text: "Al subir una la imagen la orden de trabajo se guardara automaticamente!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si'
            }).then((result) => {
                if (result.value) {
                    guardar_imagen();
                }
            });
        });
        $("#btn_ingresar").on('click', function() {
            Swal.fire({
                title: 'La orden de trabajo se guardara con los datos ingresados',
                text: "Al subir una la imagen la orden de trabajo se guardara automaticamente!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si'
            }).then((result) => {
                if (result.value) {
                    guardar_imagen();
                }
            });
        });

        //---------------------------------------------------
        // ------------------------------------------------------
        $("#txt_maestro").autocomplete({
            source: function(request, response) {

                $.ajax({
                    url: "../controlador/orden_trabajoC.php?search_maestro",
                    type: 'post',
                    dataType: "json",
                    data: {
                        search: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            select: function(event, ui) {
                $('#txt_maestro').val(ui.item.label); // display the selected text
                $('#txt_id_ma').val(ui.item.value); // save selected id to input
                // $('#txt_bodega').val(ui.item.bodega); // save selected id to input
                // $('#txt_precio').val(ui.item.precio); // save selected id to input
                // $('#txt_bodega').append($('<option>',{value:ui.item.bodega, text: ui.item.bodega_nom,selected: true }));
                return false;
            },
            focus: function(event, ui) {
                $('#txt_maestro').val(ui.item.label); // display the selected text
                $('#txt_id_ma').val(ui.item.value); // save selected id to input
                // $('#txt_bodega').val(ui.item.bodega); // save selected id to input
                // $('#txt_bodega').empty();
                // $('#txt_bodega').append($('<option>',{value:ui.item.bodega, text: ui.item.bodega_nom,selected: true }));

                return false;
            },
        });
        //----------------------------------------------------------------------

    });

    function cargar_detalle(id) {
        $.ajax({
            url: "../controlador/punto_ventaC.php?search_all_id",
            type: 'post',
            dataType: "json",
            data: {
                search: id
            },
            success: function(response) {
                console.log(response);
                if (response != -1) {

                    $('#txt_proveedor').val(response[0].cli); // display the selected text
                    $('#txt_pro_id').val(response[0].idC); // save selected id to input

                    $('#txt_descripcion').val(response[0].detalle); // display the selected text
                    $('#txt_nom_art').val(response[0].nombre); // display the selected text
                    $('#txt_cod_an').val(response[0].value); // save selected id to input
                    $('#txt_bodega').val(response[0].bodega); // save selected id to input
                    $('#txt_pvp').val(response[0].precio); // save selected id to input
                    $('#txt_peso').val(response[0].peso); // save selected id to input
                    $('#ddl_bodega').append($('<option>', {
                        value: response[0].bodega,
                        text: response[0].bodega_nom,
                        selected: true
                    }));
                    $('#ddl_tipo_joya').append($('<option>', {
                        value: response[0].id_tipo,
                        text: response[0].tipo,
                        selected: true
                    }));
                    $('#ddl_material').append($('<option>', {
                        value: response[0].id_ma,
                        text: response[0].material,
                        selected: true
                    }));
                    $('#ddl_estado_joya').append($('<option>', {
                        value: response[0].idE,
                        text: response[0].est,
                        selected: true
                    }));
                    if (response[0].foto != '' && response[0].foto != null) {
                        $("#img").attr("src", response[0].foto + '?' + Math.random());
                        $('#btn_eli_img').css('display', 'block');
                    }
                }
            }

        });

    }





    function guardar_imagen() {
        // console.log($('input[name="rbl_codi"]:checked').val());
        if ($('#txt_id_tra').val() == '') {
            if ($('input[name="rbl_codi"]:checked').val() == 1) {
                var ref = $('#txt_codigo').val();
                if (ref == '') {
                    Swal.fire('Ingres codigo f', 'Ingrese un codigo de referencia', 'info');
                    return false;
                }
            } else {
                var ref = $('#txt_cod_joya').val();
                if (ref == '') {
                    Swal.fire('Ingrese codigo', 'Ingrese un codigo de referencia', 'info');
                    return false;
                }

            }
        }
        if ($('#txt_pro_id').val() == '' || $('#ddl_material').val() == '' || $('#txt_pvp').val() == '' || $('#txt_peso').val() == '' || $('#ddl_tipo_joya').val() == '' || $('#txt_descripcion').val() == '' || $('#txt_nom_art').val() == '' || $('#ddl_bodega').val() == '' || $('#txt_id_ma').val() == '') {

            Swal.fire('Llene los campos', 'Asergurese de que todo los campos esten llenos', 'info');
            return false;

        }
        if ($('#file_img1').val() == '') {
            Swal.fire('Ingrese Imagen', 'Ingrese un imagen', 'info');
            return false;
        }

        var formData = new FormData(document.getElementById('form_trabajo'));
        $.ajax({
            url: '../controlador/trabajosC.php?Articulos_imagen=true',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            // beforeSend: function () {
            //        $("#foto_alumno").attr('src',"../../img/gif/proce.gif");
            //     },
            success: function(response) {
                console.log(response);
                if (response == -1) {
                    Swal.fire(
                        '',
                        'Algo extraño a pasado intente mas tarde.',
                        'error')

                } else if (response == -2) {
                    Swal.fire(
                        '',
                        'Asegurese que el archivo subido sea una imagen.',
                        'error')
                } else {
                    $('#txt_id_tra').val(response[0].id_producto);
                    $('#txt_id_det_tra').val(response[0].id_detalle_trabajo);
                    $("#img").attr("src", response[0].foto_producto + '?' + Math.random());

                    Swal.fire(
                        '',
                        'Datos e imagenes registradas.',
                        'success')
                }
            }
        });

    }

    function cambiar_usu() {
        $('#txt_proveedor').val('');
    }


    function autocoplet_bodegas() {
        $('#ddl_bodega').select2({
            placeholder: 'Seleccione una bodega',
            width: '100%',
            ajax: {
                url: '../controlador/articulosC.php?bodegas=true',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    // console.log(data);
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    }

    function autocoplet_estado_joya() {
        $('#ddl_estado_joya').select2({
            placeholder: 'Seleccione estado joya',
            // width:'100%',
            ajax: {
                url: '../controlador/trabajosC.php?estado_joya=true',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    // console.log(data);
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    }



    function autocoplet_cate() {
        $('#ddl_tipo_joya').select2({
            placeholder: 'Seleccione tipo de joya',
            // width:'90%',
            ajax: {
                url: '../controlador/articulosC.php?categoria=true',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    // console.log(data);
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

    }


    function autocoplet_material() {
        $('#ddl_material').select2({
            placeholder: 'Seleccione material',
            // width:'90%',
            ajax: {
                url: '../controlador/trabajosC.php?material=true',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    // console.log(data);
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

    }


    function ingresar_trabajo() {
        var datos = $('#form_trabajo').serialize();
        $.ajax({
            data: datos,
            url: '../controlador/trabajosC.php?trabajos_ingreso=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                console.log(response);
                if (response != -1) {
                    lista_trabajos();
                }
            }

        });

    }

    //----------------------------------------
    //   function autocoplet_cliente(){
    //   let tipo = 'C'; 
    //   console.log(tipo);
    //     $('#txt_proveedor').select2({
    //       placeholder: 'Seleccione cliente',
    //       width:'90%',
    //       ajax: {
    //         url:   "../controlador/punto_ventaC.php?search_cliente&tipo="+tipo,
    //         dataType: 'json',
    //         delay: 250,
    //         processResults: function (data) {
    //           // console.log(data);
    //           return {
    //             results: data
    //           };
    //         },
    //         cache: true
    //       }
    //     });

    // }
    //---------------------------------------------------------------

    function new_usuario() {
        var datos = $('#form_usuario_new').serialize();
        $.ajax({
            data: datos,
            url: '../controlador/punto_ventaC.php?new_usuario=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Nuevo cliente registrado.', 'success');
                } else {
                    Swal.fire('', 'UPs aparecio un problema', 'success');
                }

            }

        });
    }


    function cambiar_cod() {
        var cod = $('input[name="rbl_codi"]:checked').val();
        console.log(cod);
        if (cod == 1) {
            $('#txt_cod_joya').prop('readonly', true);
            $('#txt_codigo').prop('readonly', false);
            $('#txt_cod_joya').val('');
            $('#btn_eli_img').css('display', 'none');
            $("#img").attr("src", '../img/de_sistema/sin_imagen.jpg?' + Math.random());

        } else {
            $('#txt_codigo').prop('readonly', true);
            $('#txt_cod_joya').prop('readonly', false);
            $('#txt_codigo').val('');
            $('#btn_eli_img').css('display', 'none');
            $("#img").attr("src", '../img/de_sistema/sin_imagen.jpg?' + Math.random());
        }

    }


    function limpiar_img() {
        $("#img").attr("src", '../img/de_sistema/sin_imagen.jpg?' + Math.random());
        $('#btn_eli_img').css('display', 'none');
    }

    function reporte() {
        var orden = '<?php if (isset($_GET['orden'])) {
                            echo $_GET['orden'];
                        } ?>';
        var detalle = '<?php if (isset($_GET['detalle'])) {
                            echo $_GET['detalle'];
                        } ?>';
        if (orden == '') {
            orden = $('#txt_id_tra').val();
        }
        if (detalle == '') {
            detalle = $('#txt_id_det_tra').val();
        }
        var url = '../controlador/trabajosC.php?reporte=true&orden=' + orden + '&detalle=' + detalle;
        window.open(url, '_blank');
        // var datos =  $("#form_nuevo_articulo").serialize();
        // $.ajax({
        //     data:  {parametros:parametros},
        //     url:   url,
        //     type:  'post',
        //     dataType: 'json',
        //     success:  function (response) {  

        //      } 
        //   });
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Nuevo trabajo joya</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Blank
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">
                        <div class="card-title d-flex align-items-center">

                            <h5 class="mb-0 text-primary">Nuevo trabajo joya</h5>

                        </div>

                        <section class="content">
                            <div class="container-fluid">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <a class="btn btn-secondary btn-sm" href="trabajos.php"><i class="fa fa-arrow-left"></i> Nuevo</a>
                                        <a class="btn btn-success btn-sm" href="nuevo_trabajo_joya.php"><i class="fa fa-plus"></i> Nuevo</a>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <button class="btn btn-primary btn-sm" id="btn_ingresar">INGRESAR <i class="fas fa-save"></i></button>
                                        <button class="btn btn-warning btn-sm" onclick="reporte()">IMPRIMIR <i class="fas fa-print"></i></button>
                                    </div>
                                </div>
                                <form id="form_trabajo" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="txt_id_tra" id="txt_id_tra">
                                    <input type="hidden" name="txt_id_det_tra" id="txt_id_det_tra">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="rbl_tipo" id="rbn_cliente" value="C" checked onclick="cambiar_usu()">
                                                <label class="form-check-label" for="rbn_cliente">Cliente</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="rbl_tipo" id="rbn_proveedor" value="P" onclick="cambiar_usu()">
                                                <label class="form-check-label" for="rbn_proveedor">Proveedor</label>
                                            </div>
                                            <div class="input-group input-group-sm mt-2">
                                                <input type="text" name="txt_proveedor" id="txt_proveedor" class="form-control">
                                                <input type="hidden" name="txt_pro_id" id="txt_pro_id">
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cliente_nuevo"><i class="fa fa-plus"></i></button>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="rbl_codi" value="1" checked onclick="cambiar_cod()">
                                                <label class="form-check-label">Código EFFIGIA</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="rbl_codi" value="0" onclick="cambiar_cod()">
                                                <label class="form-check-label">Otro</label>
                                            </div>
                                            <input type="text" name="txt_codigo" id="txt_codigo" class="form-control form-control-sm mt-2" placeholder="Código EFFIGIA">
                                            <input type="text" name="txt_cod_joya" id="txt_cod_joya" class="form-control form-control-sm mt-2" placeholder="Código Otro">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Código</label>
                                            <input type="text" name="txt_codigo" id="txt_codigo" class="form-control form-control-sm" placeholder="Código EFFIGIA">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Nombre de Artículo</label>
                                            <input type="text" name="txt_nom_art" id="txt_nom_art" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                    <div class="row g-3 mt-2">
                                        <div class="col-md-3">
                                            <label class="form-label">Tipo de Joya</label>
                                            <select class="form-select form-select-sm" name="ddl_tipo_joya" id="ddl_tipo_joya">
                                                <option>Seleccione tipo</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Material</label>
                                            <select class="form-select form-select-sm" name="ddl_material" id="ddl_material">
                                                <option value="">Seleccione material</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">PVP</label>
                                            <input type="text" name="txt_pvp" id="txt_pvp" class="form-control form-control-sm">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Peso (g)</label>
                                            <input type="text" name="txt_peso" id="txt_peso" class="form-control form-control-sm">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Fecha de Ingreso</label>
                                            <input type="date" name="txt_fecha_ing" id="txt_fecha_ing" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-4 text-center">
                                            <img src="../img/de_sistema/sin_imagen.jpg" class="img-fluid border" id="img" style="max-width: 300px;">
                                            <button type="button" class="btn btn-danger btn-sm mt-2" id="btn_eli_img" onclick="limpiar_img()" style="display: none;">Eliminar</button>
                                            <input type="file" name="file_img1[]" id="file_img1" class="form-control form-control-sm mt-2">
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label">Descripción de Trabajo</label>
                                            <textarea class="form-control form-control-sm" rows="5" name="txt_descripcion" id="txt_descripcion" placeholder="Detalle de trabajo"></textarea>
                                            <div class="mt-3">
                                                <label class="form-label">Subir más imágenes</label>
                                                <input type="file" name="file_img1[]" class="form-control form-control-sm">
                                                <input type="file" name="file_img1[]" class="form-control form-control-sm mt-1">
                                            </div>
                                            <button type="button" class="btn btn-primary btn-sm mt-2" id="btn_sub_img">Subir imágenes</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </section>

                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>


<div class="modal" id="modal_blank" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

                <div class="row">
                    <div class="col-12">
                        <label for="">Tipo de <label class="text-danger">*</label></label>
                        <select name="" id="" class="form-select form-select-sm" onchange="">
                            <option value="">Seleccione el </option>
                        </select>
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12">
                        <label for="">Blank <label class="text-danger">*</label></label>
                        <select name="" id="" class="form-select form-select-sm">
                            <option value="">Seleccione el </option>
                        </select>
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-success btn-sm" onclick=""><i class="bx bx-save"></i> Agregar</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>