<script type="text/javascript">
    $(document).ready(function() {
        cargar_todas_pedidos();
        cargar_pedidos_pendientes();
        cargar_pedidos_fnalizadas();

    });

    function cargar_todas_pedidos() {
        $.ajax({
            // data:  {parametros:parametros},
            url: '../controlador/venta_pedidosC.php?pedidos=true',
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

    function cargar_pedidos_pendientes() {
        var parametros = {
            'tipo': 'PR',
        }
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/venta_pedidosC.php?pedidos_pendientes=true',
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

    function cargar_pedidos_fnalizadas() {
        var parametros = {
            'tipo': 'F',
        }
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/venta_pedidosC.php?pedidos_finalizadas=true',
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
        var url = "presupuestos.php?numfac=" + id + "&doc=" + doc + '&est=' + estado + '&pnt=' + punto;
        $(location).attr('href', url);
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Cotización de Venta</div>
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

                                <div class="row">
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