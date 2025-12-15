<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$_id = '';

if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}
$_id_plaza = '';

if (isset($_GET['_id_plaza'])) {
    $_id_plaza = $_GET['_id_plaza'];
}

?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    <?php if (isset($_GET['_id'])) { ?>
    cargar_seguimiento(<?= $_id ?>);
    <?php } ?>

    // Formato para inputs datetime-local
    function formatDateToInput(dateStr) {
        if (!dateStr) return '';
        dateStr = dateStr.replace('.000', '').trim();
        if (dateStr.indexOf(' ') !== -1) {
            return dateStr.slice(0, 16).replace(' ', 'T');
        }
        if (dateStr.indexOf('T') !== -1) {
            return dateStr.slice(0, 16);
        }
        return dateStr;
    }

    // Validar fechas
    function validarFechas() {
        const fechaProgramadaStr = $('#txt_th_seg_fecha_programada').val();
        const fechaRealizadaStr = $('#txt_th_seg_fecha_realizada').val();

        if (!fechaProgramadaStr) return true;

        const fechaProgramada = new Date(fechaProgramadaStr);
        const fechaActual = new Date();
        fechaActual.setSeconds(0, 0);

        if (fechaProgramada < fechaActual) {
            Swal.fire({
                icon: 'warning',
                title: 'Fecha inválida',
                text: 'La fecha programada no puede ser anterior a la fecha actual.',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#3085d6'
            }).then(() => {
                $('#txt_th_seg_fecha_programada').val('');
                $('#txt_th_seg_fecha_programada').focus();
            });
            return false;
        }

        if (fechaRealizadaStr && fechaProgramadaStr) {
            const fechaRealizada = new Date(fechaRealizadaStr);

            if (fechaRealizada < fechaProgramada) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Fecha inválida',
                    text: 'La fecha realizada no puede ser anterior a la fecha programada.',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#3085d6'
                }).then(() => {
                    $('#txt_th_seg_fecha_realizada').val('');
                    $('#txt_th_seg_fecha_realizada').focus();
                });
                return false;
            }
        }

        return true;
    }

    $('#txt_th_seg_fecha_programada, #txt_th_seg_fecha_realizada').on('change', function() {
        validarFechas();
    });

    // Validar calificación
    function validarCalificacion() {
        const calificacion = parseFloat($('#txt_th_seg_calificacion').val());

        if (isNaN(calificacion)) return true;

        if (calificacion < 0 || calificacion > 100) {
            Swal.fire({
                icon: 'warning',
                title: 'Calificación inválida',
                text: 'La calificación debe estar entre 0 y 100.',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#3085d6'
            }).then(() => {
                $('#txt_th_seg_calificacion').val('');
                $('#txt_th_seg_calificacion').focus();
            });
            return false;
        }

        return true;
    }

    $('#txt_th_seg_calificacion').on('change', function() {
        validarCalificacion();
    });

    // CARGAR DATOS DEL SEGUIMIENTO EN EL FORMULARIO
    function cargar_seguimiento(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_seguimiento_postulanteC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (!response || !response[0]) return;
                var r = response[0];
                $('#txt_th_seg_id').val(r._id);
                $('#txt_th_posu_id').val(r.postulante_id);
                $('#txt_th_etapa_id').val(r.etapa_id);
                $('#txt_th_seg_fecha_programada').val(formatDateToInput(r.fecha_programada));
                $('#txt_th_seg_fecha_realizada').val(formatDateToInput(r.fecha_realizada));
                $('#txt_th_seg_calificacion').val(r.calificacion);
                $('#txt_th_seg_resultado').val(r.resultado);
                $('#txt_th_seg_observaciones').val(r.observaciones);
                $('#txt_th_seg_documentos_json').val(r.documentos_json);
            },
            error: function(err) {
                console.error(err);
                Swal.fire('Error', 'Error al cargar el seguimiento (revisar consola).', 'error');
            }
        });
    }
});
</script>

<script type="text/javascript">
$(document).ready(function() {

    // jQuery Validate rules
    $("#form_seguimiento").validate({
        rules: {
            txt_th_posu_id: {
                required: true
            },
            txt_th_etapa_id: {
                required: true
            },
            txt_th_seg_fecha_programada: {
                required: true
            },
            ddl_th_seg_estado: {
                required: true
            }
        },
        messages: {
            txt_th_posu_id: {
                required: "Seleccione un postulante"
            },
            txt_th_etapa_id: {
                required: "Seleccione una etapa"
            },
            txt_th_seg_fecha_programada: {
                required: "Ingrese la fecha programada"
            },
            ddl_th_seg_estado: {
                required: "Seleccione un estado"
            }
        },
        highlight: function(element) {
            let $element = $(element);
            if ($element.hasClass("select2-hidden-accessible")) {
                $element.next(".select2-container").find(".select2-selection")
                    .removeClass("is-valid").addClass("is-invalid");
            } else {
                $element.removeClass("is-valid").addClass("is-invalid");
            }
        },
        unhighlight: function(element) {
            let $element = $(element);
            if ($element.hasClass("select2-hidden-accessible")) {
                $element.next(".select2-container").find(".select2-selection")
                    .removeClass("is-invalid").addClass("is-valid");
            } else {
                $element.removeClass("is-invalid").addClass("is-valid");
            }
        },
        submitHandler: function(form) {
            return false;
        }
    });

    // Función para obtener parámetros
    function ParametrosSP() {
        return {
            '_id': $('#txt_th_seg_id').val() || '',
            'txt_th_posu_id': $('#txt_th_posu_id').val(),
            'txt_th_etapa_id': $('#txt_th_etapa_id').val(),
            'txt_th_seg_fecha_programada': $('#txt_th_seg_fecha_programada').val(),
            'txt_th_seg_fecha_realizada': $('#txt_th_seg_fecha_realizada').val(),
            'txt_th_seg_calificacion': $('#txt_th_seg_calificacion').val(),
            'txt_th_seg_resultado': $('#txt_th_seg_resultado').val(),
            'ddl_responsable': $('#ddl_responsable').val(),
            'txt_th_seg_observaciones': $('#txt_th_seg_observaciones').val(),
            'txt_th_seg_documentos_json': $('#txt_th_seg_documentos_json').val(),
            'ddl_th_seg_estado': $('#ddl_th_seg_estado').val()
        };
    }

    // Función editar
    function editar_seguimiento(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_seguimiento_postulanteC.php?editar=true',
            type: 'post',
            dataType: 'json',
            success: function(res) {
                if (res == 1) {
                    Swal.fire('', 'Seguimiento actualizado con éxito.', 'success').then(function() {
                        window.location.href =
                            '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_plaza_etapas&_id=<?= $_id_plaza ?>';
                    });
                } else {
                    Swal.fire('', res.msg || 'Error al actualizar seguimiento.', 'error');
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
    }

    // Función eliminar
    function delete_seguimiento() {
        var id = $('#txt_th_seg_id').val() || '';
        if (!id) {
            Swal.fire('', 'ID no encontrado para eliminar', 'warning');
            return;
        }
        Swal.fire({
            title: '¿Eliminar Seguimiento?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    data: {
                        _id: id
                    },
                    url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_seguimiento_postulanteC.php?eliminar=true',
                    type: 'post',
                    dataType: 'json',
                    success: function(res) {
                        if (res == 1) {
                            Swal.fire('¡Eliminado!', 'Seguimiento eliminado correctamente.',
                                'success').then(function() {
                                window.location.href =
                                    '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_plaza_etapas&_id=<?= $_id_plaza ?>';
                            });
                        } else {
                            Swal.fire('', res.msg || 'No se pudo eliminar.', 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire('', 'Error: ' + xhr.responseText, 'error');
                    }
                });
            }
        });
    }

    // Bind botones
    $('#btn_editar_seguimiento').on('click', function() {
        if (!$("#form_seguimiento").valid()) return;
        var params = ParametrosSP();
        editar_seguimiento(params);
    });

    $('#btn_eliminar_seguimiento').on('click', function() {
        delete_seguimiento();
    });

});
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Seguimiento de Postulantes</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Modificar Seguimiento</li>
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
                            <div><i class="bx bx-user-check me-1 font-22 text-primary"></i></div>
                            <h5 class="mb-0 text-primary">Modificar Seguimiento de Postulante</h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_plaza_etapas&_id=<?= $_id_plaza ?>"
                                        class="btn btn-outline-dark btn-sm">
                                        <i class="bx bx-arrow-back"></i> Regresar
                                    </a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <form id="form_seguimiento">
                            <input type="hidden" id="txt_th_seg_id" name="txt_th_seg_id" value="<?= $_id ?>" />
                            <input type="hidden" id="txt_th_posu_id" name="txt_th_posu_id" value="" />
                            <input type="hidden" id="txt_th_etapa_id" name="txt_th_etapa_id" value="" />

                            <!-- Fechas y Evaluación -->
                            <div class="border-bottom border-info border-3 mb-3 pb-2">
                                <h6 class="text-info fw-bold mb-0">
                                    <i class="bx bx-calendar me-2"></i>Fechas y Evaluación
                                </h6>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-md-4">
                                    <label for="txt_th_seg_fecha_programada" class="form-label">
                                        <i class="bx bx-calendar-event me-1"></i> Fecha Programada
                                    </label>
                                    <input type="datetime-local" class="form-control form-control-sm"
                                        id="txt_th_seg_fecha_programada" name="txt_th_seg_fecha_programada" required />
                                </div>

                                <div class="col-md-4">
                                    <label for="txt_th_seg_fecha_realizada" class="form-label">
                                        <i class="bx bx-calendar-check me-1"></i> Fecha Realizada
                                    </label>
                                    <input type="datetime-local" class="form-control form-control-sm"
                                        id="txt_th_seg_fecha_realizada" name="txt_th_seg_fecha_realizada" />
                                </div>

                                <div class="col-md-4">
                                    <label for="txt_th_seg_calificacion" class="form-label">
                                        <i class="bx bx-star me-1"></i> Calificación (0-100)
                                    </label>
                                    <input type="number" step="0.01" min="0" max="100"
                                        class="form-control form-control-sm" id="txt_th_seg_calificacion"
                                        name="txt_th_seg_calificacion" placeholder="0 - 100" />
                                </div>
                            </div>

                            <!-- Resultado -->
                            <div class="border-bottom border-warning border-3 mb-3 pb-2">
                                <h6 class="text-warning fw-bold mb-0">
                                    <i class="bx bx-file-blank me-2"></i>Información Adicional
                                </h6>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-md-4">
                                    <label for="txt_th_seg_resultado" class="form-label">
                                        <i class="bx bx-comment-detail me-1"></i> Resultado de la Evaluación
                                    </label>
                                    <textarea class="form-control form-control-sm" id="txt_th_seg_resultado"
                                        name="txt_th_seg_resultado" rows="3"
                                        placeholder="Describa el resultado de la entrevista o evaluación..."></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label for="txt_th_seg_observaciones" class="form-label">
                                        <i class="bx bx-note me-1"></i> Observaciones
                                    </label>
                                    <textarea class="form-control form-control-sm" id="txt_th_seg_observaciones"
                                        name="txt_th_seg_observaciones" rows="3"
                                        placeholder="Notas internas adicionales..."></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label for="txt_th_seg_documentos_json" class="form-label">
                                        <i class="bx bx-data me-1"></i> Documentos JSON
                                    </label>
                                    <textarea class="form-control form-control-sm font-monospace"
                                        id="txt_th_seg_documentos_json" name="txt_th_seg_documentos_json" rows="3"
                                        placeholder='{"documento1": "url1", "documento2": "url2"}'></textarea>
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="row g-2">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="button" class="btn btn-primary btn-sm"
                                            id="btn_editar_seguimiento">
                                            <i class="bx bx-save me-1"></i> Actualizar
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm"
                                            id="btn_eliminar_seguimiento">
                                            <i class="bx bx-trash me-1"></i> Eliminar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>