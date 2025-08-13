<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);


?>

<script src="../js/GENERAL/operaciones_generales.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        validacion_aprobacion();
        cargar_tbl_marcaciones_web();
        /**
         * 
         * Datatable
         */

        //Para seleccionar a cada persona
        $('#tbl_marcaciones_web tbody').on('change', '.cbx_aprobar_marc', function() {
            var id = $(this).val();

            if (this.checked) {
                if (!marcaciones_seleccionadas.includes(id)) {
                    marcaciones_seleccionadas.push(id);
                }
            } else {
                // Eliminar el ID si el checkbox no está seleccionado
                marcaciones_seleccionadas = marcaciones_seleccionadas.filter(item => item !== id);
            }

            if (marcaciones_seleccionadas.length > 0) {
                $('.btn_aprobacion').removeAttr('disabled');
            } else {
                $('.btn_aprobacion').prop('disabled', true);
            }

            console.log('Seleccionados:', marcaciones_seleccionadas);
        });

        // Evento para detectar cuando se cambia de página
        $('#tbl_marcaciones_web').on('page.dt', function() {
            // Aquí colocas la acción que quieres realizar al cambiar de página
            $('#cbx_aprobacion_all').prop('checked', false);
            console.log('Página cambiada');
        });


        //Validacion de que sea solo pendiente para marcar 
        $('input[name="rbx_estado_aprobacion"]').on('change', function() {
            let valor = $(this).val();
            if (valor === 'PENDIENTE') {
                tbl_marcaciones_web.column(0).visible(true, false);
                $('#pnl_boton_aprobacion').show();
            } else {
                $('#pnl_boton_aprobacion').hide();
            }
        });
    });

    function cargar_tbl_marcaciones_web() {

        let rbx_estado_aprobacion = $('input[name="rbx_estado_aprobacion"]:checked').val();

        // console.log(rbx_estado_aprobacion);

        tbl_marcaciones_web = $('#tbl_marcaciones_web').DataTable($.extend({}, configuracion_datatable('Marcaciones', 'Marcaciones'), {
            reponsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            ajax: {
                url: '../controlador/TALENTO_HUMANO/th_control_acceso_temporalC.php?listar=true',
                data: function(d) {
                    d.rbx_estado_aprobacion = rbx_estado_aprobacion;
                },
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    render: function(data, type, item) {
                        return `<div class="form-check">
                                <input class="form-check-input cbx_aprobar_marc" type="checkbox" value="${item._id}" name="cbx_aprobar_marc_${item._id}" id="cbx_aprobar_marc_${item._id}">
                                <label class="form-label" for="cbx_aprobar_marc_${item._id}">Seleccionar</label>
                            </div>`;
                    },
                    orderable: false
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        salida = fecha_formateada(item.fecha_creacion);
                        return `${salida}`;
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        if (item.tipo_origen == 'WEB_MANUAL') {
                            return `${item.nombre_persona}`;
                        } else {
                            href = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_marcaciones_web_registrar&_id=${item._id}`;
                            return `<a href="${href}"><u>${item.nombre_persona}</u></a>`;
                        }
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        salida = fecha_formateada_hora(item.fecha_hora);
                        return `${salida}`;
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        estado = (item.estado_aprobacion || '').toUpperCase();

                        if (estado === 'PENDIENTE') {
                            return `<div class="badge rounded-pill text-warning bg-light-warning p-2 text-uppercase px-3" onclick=validacion_aprobacion('${item._id}');>PENDIENTE</div>`;
                        } else if (estado === 'APROBADO') {
                            return '<div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">APROBADO</div>';
                        } else if (estado === 'RECHAZADO') {
                            return '<div class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3">RECHAZADO</div>';
                        } else {
                            return '';
                        }
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return item.observacion_aprobacion; // Entrada / Salida
                    }
                },
                {
                    data: 'nombre_triangulacion'
                },
                {
                    data: 'origen_marc'
                },
            ],
            order: [
                [1, 'desc']
            ],
            columnDefs: [{
                targets: 0,
                visible: false,
                searchable: false
            }]
        }));
    }

    function buscar_tabla() {
        if (tbl_marcaciones_web) {
            tbl_marcaciones_web.destroy(); // Destruir la instancia existente del DataTable
        }

        cargar_tbl_marcaciones_web();
    }

    function validacion_aprobacion(id) {

        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_control_aprobacionC.php?autorizado=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                console.log(response);
                if (response == 1) {
                    if (id) {
                        $('#id_marcacion').val(id);
                        $('#modal_aprobacion').modal('show');
                    } else {
                        tbl_marcaciones_web.column(0).visible(true, false);
                        $('#pnl_boton_aprobacion').show();
                    }
                } else {
                    if (id) {
                        // Swal.fire('', 'Error, no autorizado', 'warning');
                    }
                }
            },

            error: function(xhr, status, error) {
                console.log('Status: ' + status);
                console.log('Error: ' + error);
                console.log('XHR Response: ' + xhr.responseText);

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
    }

    function aprobar_marcacion(estado_marcacion) {

        var ddl_usuarios = $('#ddl_usuarios').val();
        var id_marcacion = $('#id_marcacion').val();

        var parametros = {
            'estado_marcacion': estado_marcacion,
            'id_marcacion': id_marcacion,
        };

        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/th_control_acceso_temporalC.php?aprobar_marcacion=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    // console.log(response);

                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        tbl_marcaciones_web.ajax.reload();
                        $('#modal_aprobacion').modal('hide');
                        $('.btn_aprobacion').prop('disabled', true);

                    });
                } else if (response == -2) {
                    Swal.fire('', 'Error', 'warning');
                }
            },

            error: function(xhr, status, error) {
                console.log('Status: ' + status);
                console.log('Error: ' + error);
                console.log('XHR Response: ' + xhr.responseText);

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
    }

    // Función para marcar/desmarcar todos los cbx_aprobacion_all
    let marcaciones_seleccionadas = []; //Array de marcaciones seleccionadas

    function marcar_cbx_all_aprobacion(source) {
        var cbx_aprobacion_all = document.querySelectorAll('.cbx_aprobar_marc');

        cbx_aprobacion_all.forEach(function(cbx) {
            cbx.checked = source.checked; // Marca o desmarca todos
            var id = cbx.value;

            // Actualiza el array de marcaciones seleccionadas
            if (source.checked) {
                if (!marcaciones_seleccionadas.includes(id)) {
                    marcaciones_seleccionadas.push(id);
                }
            } else {
                marcaciones_seleccionadas = marcaciones_seleccionadas.filter(item => item !== id);
            }
        });

        if (marcaciones_seleccionadas.length > 0) {
            $('.btn_aprobacion').removeAttr('disabled');
        } else {
            $('.btn_aprobacion').prop('disabled', true);
        }

        console.log('Seleccionados:', marcaciones_seleccionadas);
    }

    function insertar_marcaciones(estado_marcacion) {
        var parametros = {
            'marcaciones_seleccionadas': marcaciones_seleccionadas,
            'estado_marcacion': estado_marcacion,
        };

        Swal.fire({
            title: 'Por favor, espere',
            text: 'Procesando la solicitud...',
            allowOutsideClick: false,
            onOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/th_control_acceso_temporalC.php?aprobar_marcacion=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        tbl_marcaciones_web.ajax.reload();
                        marcaciones_seleccionadas = [];
                        $('.btn_aprobacion').prop('disabled', true);

                        $('#cbx_aprobacion_all').prop('checked', false);
                        Swal.close();
                    });

                } else if (response == -4 || response == null) {
                    Swal.fire('', 'Seleccione Marcaciones', 'warning');
                } else if (response == -2 || response == null) {
                    Swal.fire('', 'No tiene permisos para realizar esta acción.', 'warning');
                } else if (response == -12 || response == null) {
                    Swal.fire('', 'No se encuentra dentro de alguna zona permitida.', 'warning');
                }
            },

            error: function(xhr, status, error) {
                console.log('Status: ' + status);
                console.log('Error: ' + error);
                console.log('XHR Response: ' + xhr.responseText);

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });

        //console.log(parametros);
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Marcación</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Gestión de Marcaciones
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

                        <div class="row mb-col">
                            <div class="col-12">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rbx_estado_aprobacion" id="rbx_pendiente" value="PENDIENTE" checked onclick="buscar_tabla();">
                                    <label class="form-check-label" for="rbx_pendiente">PENDIENTE</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rbx_estado_aprobacion" id="rbx_aprobado" value="APROBADO" onclick="buscar_tabla();">
                                    <label class="form-check-label" for="rbx_aprobado">APROBADO</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rbx_estado_aprobacion" id="rbx_rechazado" value="RECHAZADO" onclick="buscar_tabla();">
                                    <label class="form-check-label" for="rbx_rechazado">RECHAZADO</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-12 col-md-6">
                                <div class="card-title d-flex align-items-center">

                                    <?php if ($_SESSION['INICIO']['NO_CONCURENTE_TABLA'] == 'th_personas') { ?>
                                        <div class="" id="btn_nuevo">
                                            <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_marcaciones_web_registrar"
                                                type="button" class="btn btn-success btn-sm ">
                                                <i class="bx bx-plus me-0 pb-1"></i> Registrar Marcación
                                            </a>
                                        </div>
                                    <?php } else { ?>
                                        <div id="pnl_boton_aprobacion" style="display: none;">
                                            <div class="me-1 mb-1" id="btn_nuevo">
                                                <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_marcaciones_web_registrar_manual"
                                                    type="button" class="btn btn-success btn-sm ">
                                                    <i class="bx bx-plus me-0 pb-1"></i> Registrar Marcación Manual
                                                </a>
                                            </div>

                                            <button type="button" class="btn btn-success btn-sm me-1 mb-1 btn_aprobacion" onclick="insertar_marcaciones(1);" disabled><i class="bx bx-check me-0 pb-1"></i> Aprobar Marcaciones</button>
                                            <button type="button" class="btn btn-danger btn-sm me-1 mb-1 btn_aprobacion" onclick="insertar_marcaciones(2);" disabled><i class="bx bx-x-circle me-0 pb-1"></i> Rechazar Marcaciones</button>
                                        </div>
                                    <?php } ?>

                                </div>
                            </div>

                            <div class="col-12 col-md-6 text-md-end text-start">
                                <div id="contenedor_botones"></div>
                            </div>

                        </div>

                        <hr>

                        <section class="content pt-0">
                            <div class="container-fluid">

                                <div class="table-responsive">
                                    <table class="table table-striped responsive " id="tbl_marcaciones_web" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th id="th_check_aprobacion">
                                                    <div class="form-check" style="display: block;">
                                                        <input class="form-check-input" type="checkbox" id="cbx_aprobacion_all" onchange="marcar_cbx_all_aprobacion(this)">
                                                        <label class="form-check-label" for="cbx_aprobacion_all">
                                                            Todo
                                                        </label>
                                                    </div>
                                                </th>
                                                <th>Creado</th>
                                                <th>Persona</th>
                                                <th>Marcación</th>
                                                <th>Estado</th>
                                                <th>Observación</th>
                                                <th>Triangulación</th>
                                                <th>Origen</th>
                                            </tr>
                                        </thead>
                                        <tbody class="">

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


<div class="modal" id="modal_aprobacion" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <input type="hidden" name="id_marcacion" id="id_marcacion">

                <div class="container">
                    <!-- Primer cuadro -->
                    <div class="border rounded p-3 mb-4 bg-light-success" onclick="aprobar_marcacion(1);">
                        <h5 class="text-center text-success"><i class='bx bx-check'></i> Aprobar</h5>
                    </div>

                    <!-- Segundo cuadro -->
                    <div class="border rounded p-3 bg-light-danger" onclick="aprobar_marcacion(2);">
                        <h5 class="text-center text-danger"><i class='bx bx-x-circle'></i> Rechazar</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>