<script type="text/javascript">
    $(document).ready(function() {
        var admin = '<?php echo $admin; ?>';
        if (admin == 0) {
            var punt = '<?php echo $punto; ?>';
            var nom = '<?php echo $nom_punto; ?>';
            $('#ddl_bodega').prop('disabled', true);
            $('#btn_lim').prop('disabled', true);
            $('#ddl_bodega').append($('<option>', {
                value: punt,
                text: nom,
                selected: true
            }));
        }
        console.log(admin);
        autocoplet_bodegas();
        cargar_todas_factura();
        cargar_factura_pendientes();
        cargar_factura_fnalizadas();

    });

    function autocoplet_bodegas() {
        $('#ddl_bodega').select2({
            placeholder: 'Seleccione una bodega',
            width: '90%',
            ajax: {
                url: '../controlador/venta_facturasC.php?punto_venta=true',
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


    function cargar_todas_factura() {

        var parametros = {
            'punto': $('#ddl_bodega').val(),
            'query': $('#txt_query').val(),
        }
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/venta_facturasC.php?facturas=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                if (response != "") {
                    $('#todas').html(response);
                }
            }

        });
    }

    function cargar_factura_pendientes() {
        var parametros = {
            'tipo': 'P',
            'punto': $('#ddl_bodega').val(),
            'query': $('#txt_query').val(),
        }
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/venta_facturasC.php?facturas_pendientes=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                if (response != "") {
                    $('#pendientes').html(response);
                }
            }

        });
    }

    function cargar_factura_fnalizadas() {
        var parametros = {
            'tipo': 'F',
            'punto': $('#ddl_bodega').val(),
            'query': $('#txt_query').val(),
        }
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/venta_facturasC.php?facturas_finalizadas=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                if (response != "") {
                    $('#finalizadas').html(response);
                }
            }

        });
    }

    function Ver_factura(id, doc, estado, punto) {
        var url = "Facturacion.php?numfac=" + id + "&doc=" + doc + '&est=' + estado + '&pnt=' + punto;
        $(location).attr('href', url);
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Facturas de venta</div>
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

                            <h5 class="mb-0 text-primary"></h5>
                        </div>
                        <section class="content">
                            <div class="container-fluid">
                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <a href="punto_venta.php" class="btn btn-success btn-sm">
                                            <i class="fa fa-plus"></i> Nuevo
                                        </a>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <label for="txt_query" class="form-label">Cliente</label>
                                        <input type="text" id="txt_query" class="form-control form-control-sm"
                                            onkeyup="cargar_todas_factura(); cargar_factura_fnalizadas(); cargar_factura_pendientes();">
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="ddl_bodega" class="form-label">Punto de venta</label>
                                        <div class="input-group input-group-sm">
                                            <select class="form-select form-select-sm" id="ddl_bodega" onchange="cargar_todas_factura(); cargar_factura_fnalizadas(); cargar_factura_pendientes();">
                                                <option value="">Seleccione bodega</option>
                                            </select>
                                            <button type="button" class="btn btn-primary" id="btn_lim" onclick="$('#ddl_bodega').val(null).trigger('change'); cargar_todas_factura(); cargar_factura_fnalizadas(); cargar_factura_pendientes();">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header p-2">
                                                <ul class="nav nav-pills">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" data-bs-toggle="tab" href="#todas">Todos</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#finalizadas">Finalizadas</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#pendientes">Pendientes</a>
                                                    </li>
                                                </ul>
                                            </div><!-- /.card-header -->

                                            <div class="card-body">
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="todas">
                                                        <!-- Contenido de todas las facturas -->
                                                    </div>
                                                    <div class="tab-pane fade" id="finalizadas">
                                                        <!-- Contenido de facturas finalizadas -->
                                                    </div>
                                                    <div class="tab-pane fade" id="pendientes">
                                                        <!-- Contenido de facturas pendientes -->
                                                    </div>
                                                </div>
                                            </div><!-- /.card-body -->
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.container-fluid -->
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