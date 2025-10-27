<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$_id = '';

if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}

?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        <?php if (isset($_GET['_id'])) { ?>
            cargar_movimiento(<?= $_id ?>);
        <?php } ?>
        cargar_selects2();


        function cargar_movimiento(id) {
            $.ajax({
                data: {
                    id: id
                },
                url: '../controlador/XPACE_CUBE/movimiento_bodegaC.php?listar=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    //console.log(response);
                    $('#txt_tipo_movimiento').val(response[0].tipo_movimiento);
                    $('#txt_cantidad').val(response[0].cantidad);
                    $('#txt_motivo').val(response[0].motivo);
                    $('#txt_fecha_movimiento').val(formatearFechaParaInput(response[0].fecha_movimiento));
                    $('#ddl_articulo').append($('<option>', {
                        value: response[0].id_articulo,
                        text: response[0].nombre_articulo,
                        selected: true
                    }));
                    $('#ddl_reserva').append($('<option>', {
                        value: response[0].id_reserva,
                        text: response[0].nombre_reserva,
                        selected: true
                    }));
                }
            });

        }

        function formatearFechaParaInput(fechaSQL) {
            if (!fechaSQL) return "";
            // Elimina los microsegundos si existen y reemplaza el espacio por 'T'
            return fechaSQL.replace(" ", "T").substring(0, 16);
        }

        function cargar_selects2() {
            url_articuloC = '../controlador/XPACE_CUBE/bodegasC.php?buscar=true';
            cargar_select2_url('ddl_articulo', url_articuloC);
            url_reservaC = '../controlador/XPACE_CUBE/reservasC.php?buscar=true';
            cargar_select2_url('ddl_reserva', url_reservaC);
        }
    });


    function editar_insertar() {
        // recoger valores
        var ddl_articulo = $('#ddl_articulo').val() ?? '';
        var ddl_reserva = $('#ddl_reserva').val() ?? '';
        var txt_tipo_movimiento = $('#txt_tipo_movimiento').val() ?? '';
        var txt_cantidad = $('#txt_cantidad').val() ?? 0;
        var txt_motivo = $('#txt_motivo').val() ?? '';
        var txt_fecha_movimiento_raw = $('#txt_fecha_movimiento').val() ?? '';

        // convertir fecha al formato SQL que usa el backend
        var txt_fecha_movimiento = formatDatetimeLocalToSQL(txt_fecha_movimiento_raw);

        var parametros = {
            '_id': '<?= $_id ?>',
            'ddl_articulo': ddl_articulo,
            'ddl_reserva': ddl_reserva,
            'txt_tipo_movimiento': txt_tipo_movimiento,
            'txt_cantidad': txt_cantidad,
            'txt_motivo': txt_motivo,
            'txt_fecha_movimiento': txt_fecha_movimiento
        };

        // validar con jquery.validate
        if ($("#form_movimientos_bodega").valid()) {
            insertar(parametros);
        } else {
            // marcar visualmente select2 inválidos si aplica
            $("#form_movimientos_bodega").find('.select2-validation').each(function() {
                var $this = $(this);
                if (!$this.val() || $this.val().length === 0) {
                    marcar_select2($this, false);
                } else {
                    marcar_select2($this, true);
                }
            });
        }

        console.log('Parametros movimiento:', parametros);
    }

    function insertar(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            // <-- Cambia la URL si tu controlador se llama distinto
            url: './controlador/XPACE_CUBE/movimiento_bodegaC.php?insertar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Movimiento guardado correctamente.', 'success').then(function() {
                        // recargar página o redirigir a listado
                        location.reload();
                    });
                } else if (response == -2) {
                    Swal.fire('', 'Error al guardar la información.', 'warning');
                } else {
                    Swal.fire('', 'Respuesta inesperada: ' + JSON.stringify(response), 'info');
                }
            },
            error: function(xhr, status, error) {
                console.error('Status: ' + status);
                console.error('Error: ' + error);
                console.error('XHR Response: ' + xhr.responseText);
                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Ubicaciones</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Registros
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

                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                            </div>
                            <h5 class="mb-0 text-primary">
                                <?php
                                if ($_id == '') {
                                    echo 'Registrar Movimiento Artículo';
                                } else {
                                    echo 'Modificar Movimiento Artículo';
                                }
                                ?>
                            </h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_movimiento_articulo" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <form id="form_movimientos_bodega">
                            <div class="row g-3">

                                <!-- Artículo -->
                                <div class="col-md-6">
                                    <label for="ddl_articulo" class="form-label fw-bold d-flex align-items-center">
                                        <i class="bx bx-package me-2 text-primary fs-5"></i> Artículo
                                    </label>
                                    <select class="form-select form-select-sm select2-validation" id="ddl_articulo" name="ddl_articulo" required>
                                        <option value="" selected hidden>-- Seleccione --</option>
                                        <!-- opciones dinámicas -->
                                    </select>
                                    <div class="form-text">Seleccione el artículo del movimiento</div>
                                    <div class="invalid-feedback">Seleccione un artículo.</div>
                                </div>


                                <!-- Reserva -->
                                <div class="col-md-6">
                                    <label for="ddl_reserva" class="form-label fw-bold d-flex align-items-center">
                                        <i class="bx bx-calendar-event me-2 text-danger fs-5"></i> Reserva
                                    </label>
                                    <select class="form-select form-select-sm select2-validation" id="ddl_reserva" name="ddl_reserva">
                                        <option value="" selected hidden>-- Seleccione --</option>
                                        <!-- opciones dinámicas -->
                                    </select>
                                    <div class="form-text">Seleccione la reserva (si aplica)</div>
                                </div>

                                <!-- Tipo de movimiento -->
                                <div class="col-md-6">
                                    <label for="txt_tipo_movimiento" class="form-label fw-bold d-flex align-items-center">
                                        <i class="bx bx-transfer me-2 text-info fs-5"></i> Tipo de movimiento
                                    </label>
                                    <input type="text"
                                        class="form-control form-control-sm no_caracteres"
                                        id="txt_tipo_movimiento"
                                        name="txt_tipo_movimiento"
                                        placeholder="Ejemplo: Entrada o Salida"
                                        autocomplete="off"
                                        required />
                                </div>

                                <!-- Cantidad -->
                                <div class="col-md-6">
                                    <label for="txt_cantidad" class="form-label fw-bold d-flex align-items-center">
                                        <i class="bx bx-sort-numeric-up me-2 text-primary fs-5"></i> Cantidad
                                    </label>
                                    <input type="number"
                                        class="form-control form-control-sm"
                                        id="txt_cantidad"
                                        name="txt_cantidad"
                                        placeholder="Ingrese la cantidad"
                                        min="0"
                                        autocomplete="off"
                                        required />
                                </div>

                                <!-- Motivo -->
                                <div class="col-md-6">
                                    <label for="txt_motivo" class="form-label fw-bold d-flex align-items-center">
                                        <i class="bx bx-message-dots me-2 text-success fs-5"></i> Motivo
                                    </label>
                                    <input type="text"
                                        class="form-control form-control-sm no_caracteres"
                                        id="txt_motivo"
                                        name="txt_motivo"
                                        placeholder="Ingrese el motivo del movimiento"
                                        autocomplete="off" />
                                </div>

                                <!-- Fecha de movimiento -->
                                <div class="col-md-6">
                                    <label for="txt_fecha_movimiento" class="form-label fw-bold d-flex align-items-center">
                                        <i class="bx bx-calendar me-2 text-warning fs-5"></i> Fecha de movimiento
                                    </label>
                                    <input type="datetime-local"
                                        class="form-control form-control-sm"
                                        id="txt_fecha_movimiento"
                                        name="txt_fecha_movimiento"
                                        autocomplete="off"
                                        required />
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="mt-4 text-end">
                                <?php if ($_id == '') { ?>
                                    <button type="button" class="btn btn-success btn-sm px-4 m-0" id="btn_guardar"
                                        onclick="editar_insertar()">
                                        <i class="bx bx-save"></i> Guardar
                                    </button>
                                <?php } else { ?>
                                    <button type="button" class="btn btn-success btn-sm px-4 m-1" id="btn_editar"
                                        onclick="editar_insertar()">
                                        <i class="bx bx-edit"></i> Editar
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm px-4 m-1" id="btn_eliminar">
                                        <i class="bx bx-trash"></i> Eliminar
                                    </button>
                                <?php } ?>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>

<script>
/* ============================
   Helpers visuales / utilidades
   ============================ */

// Añade un asterisco rojo al label asociado a un campo (si existe)
function agregar_asterisco_campo_obligatorio(campoId) {
    var $label = $('label[for="' + campoId + '"]');
    if ($label.length === 0) {
        $label = $('#lbl_' + campoId);
    }
    if ($label.length && $label.find('.req-asterisk').length === 0) {
        $label.append(' <span class="req-asterisk text-danger" title="Obligatorio">*</span>');
    }
}

// Marca select2 como válido/inválido
function marcar_select2($select, valid) {
    var $container = $select.next('.select2-container').find('.select2-selection');
    if (valid) {
        $container.removeClass('is-invalid').addClass('is-valid');
    } else {
        $container.removeClass('is-valid').addClass('is-invalid');
    }
}

// Convierte valor datetime-local ("YYYY-MM-DDTHH:MM") a "YYYY-MM-DD HH:MM:SS"
function formatDatetimeLocalToSQL(value) {
    if (!value) return null;
    // valor esperado: "2025-10-24T11:57"
    var dt = value.split('T');
    if (dt.length === 2) {
        return dt[0] + ' ' + dt[1] + ':00';
    }
    return value;
}


$(document).ready(function() {

    // añadir asteriscos a labels obligatorios
    agregar_asterisco_campo_obligatorio('ddl_articulo');
    agregar_asterisco_campo_obligatorio('ddl_reserva');
    agregar_asterisco_campo_obligatorio('txt_tipo_movimiento');
    agregar_asterisco_campo_obligatorio('txt_cantidad');
    agregar_asterisco_campo_obligatorio('txt_motivo');
    agregar_asterisco_campo_obligatorio('txt_fecha_movimiento');

    // select2: al seleccionar, quitar error visual
    $(".select2-validation").on("select2:select", function(e) {
        marcar_select2($(this), true);
    });

    // si cambia el select (por ej. limpiar), validar visualmente
    $(".select2-validation").on("change", function() {
        var $s = $(this);
        if (!$s.val() || $s.val().length === 0) {
            marcar_select2($s, false);
        } else {
            marcar_select2($s, true);
        }
    });

    // Inicializar validación
    $("#form_movimientos_bodega").validate({
        ignore: [], // importante para no ignorar los inputs hidden de select2
        rules: {
            ddl_articulo: { required: true },
            ddl_reserva: { required: true },
            txt_tipo_movimiento: { required: true },
            txt_motivo: { required: true },
            txt_cantidad: { required: true, number: true, min: 1 },
            txt_fecha_movimiento: { required: true }
        },
        messages: {
            ddl_articulo: { required: "Seleccione un artículo." },
            ddl_articulo: { required: "Seleccione una reserva." },
            txt_tipo_movimiento: { required: "Indique el tipo de movimiento (Entrada/Salida)." },
            txt_cantidad: { required: "Ingrese la cantidad.", number: "Debe ser un número.", min: "La cantidad debe ser al menos 1." },
            txt_fecha_movimiento: { required: "Seleccione la fecha de movimiento." },
            txt_motivo: { required: "Ingrese un motivo." },
        },
        highlight: function(element) {
            var $el = $(element);
            if ($el.hasClass('select2-hidden-accessible')) {
                $el.next('.select2-container').find('.select2-selection').removeClass('is-valid').addClass('is-invalid');
            } else {
                $el.removeClass('is-valid').addClass('is-invalid');
            }
        },
        unhighlight: function(element) {
            var $el = $(element);
            if ($el.hasClass('select2-hidden-accessible')) {
                $el.next('.select2-container').find('.select2-selection').removeClass('is-invalid').addClass('is-valid');
            } else {
                $el.removeClass('is-invalid').addClass('is-valid');
            }
        },
        errorPlacement: function(error, element) {
            if (element.hasClass('select2-hidden-accessible')) {
                error.insertAfter(element.next('.select2-container'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            // evitamos submit normal
            return false;
        }
    });

    // botón guardar/editar también puede llamarse desde el HTML (ya lo pones con onclick="editar_insertar()")
    $('#btn_guardar, #btn_editar').on('click', function(e) {
        e.preventDefault();
        editar_insertar();
    });

});
</script>
