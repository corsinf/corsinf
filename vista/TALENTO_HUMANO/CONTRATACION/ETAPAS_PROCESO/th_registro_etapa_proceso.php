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
    cargar_etapa(<?= $_id ?>);
    <?php } ?>



    // Añadir asterisco a campos obligatorios
    agregar_asterisco_campo_obligatorio('txt_th_etapa_nombre');

    // Inicializar validación
    $("#form_etapa").validate({
        ignore: [],
        rules: {
            txt_th_etapa_nombre: {
                required: true
            },
            txt_th_etapa_orden: {
                number: true
            }
        },
        messages: {
            txt_th_etapa_nombre: {
                required: "Ingrese el nombre de la etapa."
            },
            txt_th_etapa_orden: {
                number: "Ingrese un número válido."
            }
        },
        highlight: function(element) {
            $(element).removeClass('is-valid').addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid').addClass('is-valid');
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            return false;
        }
    });


});

function boolVal(val) {
    return (val === 1 || val === '1' || val === true || val === 'true') ? true : false;
}

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

// CARGAR DATOS DE UNA ETAPA EN EL FORMULARIO
function cargar_etapa(id) {
    $.ajax({
        data: {
            id: id
        },
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_etapas_procesoC.php?listar=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (!response || !response[0]) return;
            var r = response[0];

            $('#txt_th_etapa_id').val(r._id);

            $('#txt_th_etapa_nombre').val(r.nombre || '');
            $('#ddl_etapa_tipo').val(r.tipo || '');
            $('#txt_th_etapa_orden').val(r.orden !== null ? r.orden : '');
            $('#quick_th_etapa_obligatoria, #chk_th_etapa_obligatoria').prop('checked', boolVal(r
                .obligatoria));
            $('#txt_th_etapa_descripcion').val(r.descripcion || '');
        },
        error: function(err) {
            console.error(err);
            Swal.fire('', 'Error al cargar la etapa (revisa consola).', 'error');
        }
    });
}

// Guardar / actualizar (decide según si existe id)
function editar_insertar_etapa() {
    var txt_th_etapa_id = $('#txt_th_etapa_id').val(); // hidden id
    var parametros = {
        '_id': txt_th_etapa_id,
        'txt_th_etapa_nombre': $('#txt_th_etapa_nombre').val().trim(),
        'ddl_etapa_tipo': $('#ddl_etapa_tipo').val().trim(),
        'txt_th_etapa_orden': $('#txt_th_etapa_orden').val().trim(),
        'chk_th_etapa_obligatoria': $('#chk_th_etapa_obligatoria').is(':checked') ? 1 : 0,
        'txt_th_etapa_descripcion': $('#txt_th_etapa_descripcion').val().trim()
    };

    if ($("#form_etapa").valid()) {
        if (!txt_th_etapa_id || txt_th_etapa_id == '') {
            insertar_etapa(parametros);
        } else {
            editar_etapa(parametros);
        }
    }
}

function insertar_etapa(parametros) {
    $.ajax({
        data: {
            parametros: parametros
        },
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_etapas_procesoC.php?insertar_editar=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response == 1) {
                Swal.fire('', 'Etapa creada con éxito.', 'success').then(function() {
                    location.href =
                        '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_etapas_proceso';
                });
            } else if (response == -2) {
                // duplicado por nombre+plaza
                $('#txt_th_etapa_nombre').addClass('is-invalid');
                if ($('#error_txt_th_etapa_nombre').length == 0) {
                    $('#txt_th_etapa_nombre').after(
                        '<div id="error_txt_th_etapa_nombre" class="invalid-feedback">Ya existe una etapa con ese nombre en la plaza seleccionada.</div>'
                    );
                } else {
                    $('#error_txt_th_etapa_nombre').text(
                        'Ya existe una etapa con ese nombre en la plaza seleccionada.');
                }
            } else {
                Swal.fire('', response.msg || 'Error al guardar la etapa.', 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('Status: ' + status);
            console.error('Error: ' + error);
            console.error('XHR Response: ' + xhr.responseText);
            Swal.fire('', 'Error: ' + xhr.responseText, 'error');
        }
    });

    // limpiar error cuando el usuario teclea
    $('#txt_th_etapa_nombre').on('input', function() {
        $(this).removeClass('is-invalid');
        $('#error_txt_th_etapa_nombre').text('');
    });
}

function editar_etapa(parametros) {
    $.ajax({
        data: {
            parametros: parametros
        },
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_etapas_procesoC.php?insertar_editar=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response == 1) {
                Swal.fire('', 'Etapa actualizada con éxito.', 'success').then(function() {
                    location.href =
                        '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_etapas_proceso';
                });
            } else if (response == -2) {
                $('#txt_th_etapa_nombre').addClass('is-invalid');
                if ($('#error_txt_th_etapa_nombre').length == 0) {
                    $('#txt_th_etapa_nombre').after(
                        '<div id="error_txt_th_etapa_nombre" class="invalid-feedback">El nombre de la etapa ya está en uso en la plaza seleccionada.</div>'
                    );
                } else {
                    $('#error_txt_th_etapa_nombre').text(
                        'El nombre de la etapa ya está en uso en la plaza seleccionada.');
                }
            } else {
                Swal.fire('', response.msg || 'Error al actualizar la etapa.', 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('Status: ' + status);
            console.error('Error: ' + error);
            console.error('XHR Response: ' + xhr.responseText);
            Swal.fire('', 'Error: ' + xhr.responseText, 'error');
        }
    });

    $('#txt_th_etapa_nombre').on('input', function() {
        $(this).removeClass('is-invalid');
        $('#error_txt_th_etapa_nombre').text('');
    });
}

function delete_etapa() {
    var id = $('#txt_th_etapa_id').val() || '<?= $_id ?>';
    if (!id) {
        Swal.fire('', 'ID no encontrado para eliminar.', 'warning');
        return;
    }

    Swal.fire({
        title: 'Eliminar Registro?',
        text: '¿Está seguro de eliminar esta etapa?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si'
    }).then((result) => {
        if (result.isConfirmed) {
            eliminar_etapa(id);
        }
    });
}

function eliminar_etapa(id) {
    $.ajax({
        data: {
            id: id
        },
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_etapas_procesoC.php?eliminar=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response == 1) {
                Swal.fire('Eliminado!', 'Etapa eliminada.', 'success').then(function() {
                    location.href =
                        '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_etapas_proceso';
                });
            } else {
                Swal.fire('', response.msg || 'No se pudo eliminar.', 'error');
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

// Bind botones
$(document).ready(function() {
    $('#btn_guardar_etapa').on('click', function() {
        editar_insertar_etapa();
    });
    $('#btn_editar_etapa').on('click', function() {
        editar_insertar_etapa();
    });
    $('#btn_eliminar_etapa').on('click', function() {
        delete_etapa();
    });
});
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Etapas</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Registro / Modificar</li>
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
                            <div><i class="bx bx-list-check me-1 font-22 text-primary"></i></div>
                            <h5 class="mb-0 text-primary">
                                <?php
                                if ($_id == '') {
                                    echo 'Registrar Etapa';
                                } else {
                                    echo 'Modificar Etapa';
                                }
                                ?>
                            </h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_etapas_proceso"
                                        class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i>
                                        Regresar</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <form id="form_etapa">
                            <!-- Hidden ID -->
                            <input type="hidden" id="txt_th_etapa_id" name="txt_th_etapa_id" value="<?= $_id ?>" />

                            <div class="card mb-3">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="bx bx-info-circle me-2"></i>Información de la Etapa</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">

                                        <div class="col-md-6">
                                            <label for="txt_th_etapa_nombre" class="form-label fw-bold">
                                                <i class="bx bx-font-family me-2 text-primary"></i> Nombre de la Etapa
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="txt_th_etapa_nombre"
                                                name="txt_th_etapa_nombre" placeholder="Ingrese el nombre de la etapa"
                                                autocomplete="off" required />
                                        </div>

                                        <div class="col-md-6">
                                            <label for="ddl_etapa_tipo" class="form-label fw-bold">
                                                <i class="bx bx-tag me-2 text-info"></i> Tipo
                                            </label>
                                            <select id="ddl_etapa_tipo" name="ddl_etapa_tipo" class="form-select">
                                                <option value="">-- Seleccione --</option>

                                                <!-- Etapas iniciales -->
                                                <option value="revision_cv">Revisión de CV</option>
                                                <option value="preseleccion">Preselección</option>
                                                <option value="filtro_telefonico">Filtro Telefónico</option>

                                                <!-- Evaluaciones -->
                                                <option value="evaluacion">Evaluación</option>
                                                <option value="prueba_tecnica">Prueba Técnica</option>
                                                <option value="prueba_practica">Prueba Práctica</option>
                                                <option value="prueba_psicometrica">Prueba Psicométrica</option>
                                                <option value="prueba_conocimientos">Prueba de Conocimientos</option>
                                                <option value="test_personalidad">Test de Personalidad</option>
                                                <option value="test_habilidades_blandas">Test de Habilidades Blandas
                                                </option>

                                                <!-- Entrevistas -->
                                                <option value="entrevista">Entrevista</option>
                                                <option value="entrevista_tecnica">Entrevista Técnica</option>
                                                <option value="entrevista_competencias">Entrevista de Competencias
                                                </option>
                                                <option value="entrevista_final">Entrevista Final</option>

                                                <!-- Validaciones -->
                                                <option value="validacion_documental">Validación Documental</option>
                                                <option value="validacion_requisitos">Validación de Requisitos</option>
                                                <option value="validacion_experiencia">Validación de Experiencia
                                                </option>
                                                <option value="validacion_referencias">Validación de Referencias
                                                </option>

                                                <!-- Etapa final -->
                                                <option value="seleccion_final">Selección Final</option>
                                                <option value="oferta_laboral">Oferta Laboral</option>
                                                <option value="contratacion">Contratación</option>

                                                <!-- Otros -->
                                                <option value="otra">Otra</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="txt_th_etapa_orden" class="form-label fw-bold">
                                                <i class="bx bx-sort-alt-2 me-2 text-success"></i> Orden
                                            </label>
                                            <input type="number" class="form-control" id="txt_th_etapa_orden"
                                                name="txt_th_etapa_orden" placeholder="1" />
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label d-block fw-bold">Obligatoria</label>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox"
                                                    id="chk_th_etapa_obligatoria" name="chk_th_etapa_obligatoria">
                                                <label class="form-check-label"
                                                    for="chk_th_etapa_obligatoria">Sí</label>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label for="txt_th_etapa_descripcion" class="form-label fw-bold">
                                                <i class="bx bx-file me-2 text-warning"></i> Descripción
                                            </label>
                                            <textarea class="form-control" id="txt_th_etapa_descripcion"
                                                name="txt_th_etapa_descripcion" rows="4"
                                                placeholder="Descripción de la etapa..."></textarea>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- BOTONES DE ACCIÓN -->
                            <div class="d-flex justify-content-end gap-2">
                                <?php if ($_id == '') { ?>
                                <button type="button" class="btn btn-success" id="btn_guardar_etapa">
                                    <i class="bx bx-save me-1"></i> Guardar Etapa
                                </button>
                                <?php } else { ?>
                                <button type="button" class="btn btn-primary" id="btn_editar_etapa">
                                    <i class="bx bx-edit me-1"></i> Actualizar Etapa
                                </button>
                                <button type="button" class="btn btn-danger" id="btn_eliminar_etapa">
                                    <i class="bx bx-trash me-1"></i> Eliminar Etapa
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