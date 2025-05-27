<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);


?>

<script src="../js/GENERAL/operaciones_generales.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        cargar_tipo_articulo();

        tbl_articulos = $('#tbl_articulos').DataTable($.extend({}, configuracion_datatable('Artículos', 'articulos'), {
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },

            searchDelay: 500,

            // "searching": true,
            // "ordering": true,
            // "info": true,
            // "autoWidth": false,
            // "responsive": true,

            processing: true,
            serverSide: true,
            stateSave: false,
            ajax: {
                url: '../controlador/ACTIVOS_FIJOS/articulosC.php?lista_cr=true',
                type: 'POST', // Asegura que se envíen los datos correctamente
                data: function(d) {
                    //d.search_value = $('#transacciones_select').DataTable().search();
                    d.search_value = $('input[name="rbl_tip_articulo"]:checked').val();
                }
            },
            search: {
                smart: true
            },

            order: [
                [0, 'asc']
            ],

            rowCallback: function(row, data, index) {
                let tipo_articulo = data[14];
                let tipo_color = data[15];

                if (tipo_articulo && tipo_color) {
                    $(row).css("background-color", tipo_color);
                }
            },

            columnDefs: [{
                targets: [13, 14],
                visible: false
            }]
        }));

    });

    function redireccionar(id) {
        var loc = 'null';
        var cus = 'null';
        if ($('#ddl_localizacion').val() != null) {
            loc = $('#ddl_localizacion').select2('data')[0].text;
        }
        if ($('#ddl_custodio').val() != null) {
            cus = $('#ddl_custodio').select2('data')[0].text;
        }
        window.location.href = "inicio.php?acc=detalle_articulo&_id=" + id + '&fil1=' + $('#ddl_localizacion').val() + '--' + loc + '&fil2=' + $('#ddl_custodio').val() + '--' + cus;
    }

    function cargar_tipo_articulo() {
        $.ajax({
            data: {
                _id: ''
            },
            url: '../controlador/ACTIVOS_FIJOS/CATALOGOS/ac_cat_tipo_articuloC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                let radioButtons = '';
                radioButtons =
                    `<div class="col-sm-auto m-0">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="rbl_tip_articulo_" name="rbl_tip_articulo" value="" checked>
                                <label class="form-check-label" for="rbl_tip_articulo_">Todo</label>
                            </div>
                        </div>`;

                $.each(response, function(i, item) {
                    radioButtons +=
                        `<div class="col-sm-auto m-0">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="rbl_tip_articulo_${item._id}" name="rbl_tip_articulo" value="${item._id}">
                                <label class="form-check-label" for="rbl_tip_articulo_${item._id}">${item.descripcion}</label>
                            </div>
                        </div>`;
                });

                mensaje_error = `<label class="error mb-2" style="display: none;" for="rbl_tip_articulo"></label>`

                $('#pnl_tipo_articulo').html(radioButtons + mensaje_error);

                $('input[name="rbl_tip_articulo"]').on('change', function() {
                    // alert($(this).val()); 
                    $('#tbl_articulos').DataTable().ajax.reload();
                });

            },
            error: function(xhr, status, error) {
                console.log('Status: ' + status);
                console.log('Error: ' + error);
                console.log('XHR Response: ' + xhr.responseText);

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Artículos</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Lista de Artículos
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

                        <div class="row mb-col" id="pnl_tipo_articulo">
                            <!-- <div class="col-sm-3">
                                <div class="form-check">
                                  <input class="form-check-input" type="radio" id="txt_bajas" name="rbl_op">
                                  <label class="form-check-label" for="txt_bajas">Bajas</label>
                                </div>
                              </div> -->
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="card-title d-flex align-items-center">

                                    <!-- <div class="" id="btn_nuevo">

                                        <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_articulos"
                                            type="button" class="btn btn-success btn-sm">
                                            <i class="bx bx-plus me-0 pb-1"></i> Nuevo
                                        </a>

                                    </div> -->

                                </div>
                            </div>

                            <div class="col-12 col-md-6 text-md-end text-start">
                                <div id="contenedor_botones"></div>
                            </div>
                        </div>

                        <hr>

                        <section class="content pt-2">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-responsive " id="tbl_articulos" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>SKU</th>
                                                <th>Descripción</th>
                                                <th>Modelo</th>
                                                <th>Serie</th>
                                                <th>RFID</th>
                                                <th>Localización</th>
                                                <th>Custodio</th>
                                                <th>Marca</th>
                                                <th>Estado</th>
                                                <th>Género</th>
                                                <th>Color</th>
                                                <th>Fecha Referencia</th>
                                                <th>Observación</th>
                                                <th>ID</th>
                                                <th>Tipo Articulo</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
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

<div class="modal fade" id="importar_device" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Importar desde Dispositivo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="txt_recuperado" id="txt_recuperado">
                    <div class="col-sm-12 mb-2">
                        <select class="form-select" id="ddl_dispositivos" name="ddl_dispositivos">
                            <option value="">Seleccione Dispositivo</option>
                        </select>
                    </div>
                    <div class="col-sm-12 text-end">
                        <button class="btn btn-primary btn-sm" onclick="conectar_buscar()"><i class="bx bx-sync"></i>Conectar y buscar</button>
                    </div>
                    <div class="col-sm-12">
                        <table class="table table-striped" id="">
                            <thead>
                                <tr>
                                    <th>Numero de tarjeta</th>
                                    <th>Nombre</th>
                                </tr>
                            </thead>
                            <tbody id="tbl_import">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button class="btn btn-primary btn-sm" onclick="importar()"><i class="bx bx-sync"></i>Importar</button>
            </div>
        </div>
    </div>
</div>