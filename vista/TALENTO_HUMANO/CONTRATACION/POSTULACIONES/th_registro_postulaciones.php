<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$_id = '';
$_id_p = '';

if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}
if (isset($_GET['_id_p'])) {
    $_id_p = $_GET['_id_p'];
}

?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    <?php if (isset($_GET['_id'])) { ?>
    cargar_postulacion(<?= $_id ?>);
    <?php } ?>
    <?php if (isset($_GET['_id_p'])) { ?>
    cargar_plaza(<?= $_id_p ?>);
    <?php } ?>
    // Inicializar y cargar selects (plaza y cargo)
    cargar_selects2();

    // Añadir asteriscos a campos obligatorios
    agregar_asterisco_campo_obligatorio('ddl_plaza');
    agregar_asterisco_campo_obligatorio('ddl_tipo_postulante');
    agregar_asterisco_campo_obligatorio('txt_th_posu_fecha');



    function cargar_plaza(id) {
        $('#ddl_plaza').prop('disabled', true);
        $.ajax({
            data: {
                id: id
            },
            // <-- Cambia esta URL por la de tu controlador
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_plazasC.php?listar_plaza=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (!response || !response[0]) return;
                let r = response[0];
                $('#txt_th_id_plaza').val(r._id);
                $('#ddl_plaza').append($('<option>', {
                    value: r._id,
                    text: r.th_pla_titulo,
                    selected: true
                }));

            },
            error: function(err) {
                console.error(err);
                alert('Error al cargar la plaza (revisar consola).');
            }
        });
    }

    function cargar_selects2() {
        // Ajusta estas URLs si tus controladores difieren
        let url_plazas = '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_plazasC.php?buscar_todas=true';

        // helper que ya usas en el proyecto: cargar_select2_url(nombre_select, url)
        cargar_select2_url('ddl_plaza', url_plazas);
    }


    // Inicializar validación
    $("#form_postulacion").validate({
        ignore: [],
        rules: {
            txt_th_posu_fecha: {
                required: true
            }
        },
        messages: {
            txt_th_posu_fecha: {
                required: "Ingrese la fecha de postulación."
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

    function boolVal(val) {
        return (val === 1 || val === '1' || val === true || val === 'true') ? true : false;
    }



    // CARGAR DATOS DE UNA POSTULACIÓN EN EL FORMULARIO
    function cargar_postulacion(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_postulacionesC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (!response || !response[0]) return;
                let r = response[0];

                $('#txt_th_posu_id').val(r._id);
                $('#ddl_plaza').val(r.th_pla_id);

                // Determinar tipo de postulante
                if (r.id_persona) {
                    $('#ddl_tipo_postulante').val('interno');
                    $('#txt_postulante_per_id').val(r.id_persona);
                    $('#txt_postulante_pos_id').val(0);
                    $('#txt_postulante_nombre').val(r.nombre_postulante);
                } else if (r.id_postulante) {
                    $('#ddl_tipo_postulante').val('externo');
                    $('#txt_postulante_per_id').val(0);
                    $('#txt_postulante_pos_id').val(r.id_postulante);
                    $('#txt_postulante_nombre').val(r.nombre_postulante);
                }

                $('#txt_th_posu_fecha').val(formatDateToInput(r.fecha_postulacion));
                $('#ddl_th_posu_estado').val(r.estado_descripcion ? r.estado_descripcion
                    .toLowerCase() : '');
                $('#ddl_th_posu_fuente').val(r.fuente ? r.fuente.toLowerCase() : '');
                $('#txt_th_posu_curriculum_url').val(r.curriculum_url);
                $('#txt_th_posu_score').val(r.score);

                $('#ddl_th_posu_prioridad').val(r.prioridad);
                $('#txt_th_posu_observaciones').val(r.observaciones);
            },
            error: function(err) {
                console.error(err);
                alert('Error al cargar la postulación (revisar consola).');
            }
        });
    }
});
</script>

<script type="text/javascript">
function editar_insertar_postulacion() {


    let txt_th_posu_id = $('#txt_th_posu_id').val();
    let id_plaza = $('#txt_th_id_plaza').val();
    let postulante_per_id = $('#txt_postulante_per_id').val();
    let postulante_pos_id = $('#txt_postulante_pos_id').val();
    let ddl_tipo_postulante = $('#ddl_tipo_postulante').val();
    let txt_postulante_id = $('#txt_postulante_id').val();
    let txt_th_posu_fecha = $('#txt_th_posu_fecha').val();
    let ddl_th_posu_estado = $('#ddl_th_posu_estado').val();
    let ddl_th_posu_fuente = $('#ddl_th_posu_fuente').val();
    let txt_th_posu_curriculum_url = $('#txt_th_posu_curriculum_url').val();
    let txt_th_posu_score = $('#txt_th_posu_score').val();
    let ddl_th_posu_prioridad = $('#ddl_th_posu_prioridad').val();
    let txt_th_posu_observaciones = $('#txt_th_posu_observaciones').val();

    let parametros = {
        '_id': txt_th_posu_id,
        'th_pla_id': id_plaza,
        'tipo_postulante': ddl_tipo_postulante,
        'postulante_per_id': postulante_per_id,
        'postulante_pos_id': postulante_pos_id,
        'th_posu_fecha': txt_th_posu_fecha,
        'th_posu_estado': ddl_th_posu_estado,
        'th_posu_fuente': ddl_th_posu_fuente,
        'th_posu_curriculum_url': txt_th_posu_curriculum_url,
        'th_posu_score': txt_th_posu_score,
        'th_posu_prioridad': ddl_th_posu_prioridad,
        'th_posu_observaciones': txt_th_posu_observaciones
    };

    if (!txt_th_posu_id || txt_th_posu_id == '') {
        insertar_postulacion(parametros);
    } else {
        editar_postulacion(parametros);
    }
}

function insertar_postulacion(parametros) {
    $.ajax({
        data: {
            parametros: parametros
        },
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_postulacionesC.php?insertar_editar=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response == 1) {
                Swal.fire('', 'Postulación registrada con éxito.', 'success').then(function() {
                    location.href =
                        `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_postulados&_id=` +
                        (parametros.th_pla_id || '');
                });
            } else if (response == -2) {
                Swal.fire('', 'Este postulante ya está registrado en esta plaza.', 'warning');
            } else {
                Swal.fire('', response.msg || 'Error al guardar la postulación.', 'error');
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

function editar_postulacion(parametros) {
    $.ajax({
        data: {
            parametros: parametros
        },
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_postulacionesC.php?insertar_editar=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response == 1) {
                Swal.fire('', 'Postulación actualizada con éxito.', 'success').then(function() {
                    location.href =
                        `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_postulados&_id=` +
                        (parametros.th_pla_id || '');
                });
            } else if (response == -2) {
                Swal.fire('', 'Este postulante ya está registrado en esta plaza.', 'warning');
            } else {
                Swal.fire('', response.msg || 'Error al actualizar la postulación.', 'error');
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

function delete_postulacion() {
    let id = $('#txt_th_posu_id').val() || '<?= $_id ?>';
    if (!id) {
        Swal.fire('', 'ID no encontrado para eliminar.', 'warning');
        return;
    }

    Swal.fire({
        title: 'Eliminar Postulación?',
        text: '¿Está seguro de eliminar esta postulación?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si'
    }).then((result) => {
        if (result.isConfirmed) {
            eliminar_postulacion(id);
        }
    });
}

function eliminar_postulacion(id) {
    $.ajax({
        data: {
            id: id
        },
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_postulacionesC.php?eliminar=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response == 1) {
                Swal.fire('Eliminado!', 'Postulación eliminada.', 'success').then(function() {
                    location.href =
                        `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_postulados&_id=` +
                        (<?=$_id_p?> || '');
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

// MODAL PARA SELECCIONAR POSTULANTE
function abrir_modal_postulantes() {
    let plaza_id = $('#ddl_plaza').val();
    if (!plaza_id) {
        Swal.fire('', 'Primero seleccione una plaza.', 'warning');
        return;
    }

    $('#modal_postulantes').modal('show');
    let tipo = $('#ddl_tipo_postulante').val();

    if (!$.fn.DataTable.isDataTable('#tbl_personas')) {
        cargar_personas(tipo, plaza_id);
    }
}

$(document).ready(function() {
    $('#btn_guardar_postulacion').on('click', function() {
        editar_insertar_postulacion();
    });
    $('#btn_editar_postulacion').on('click', function() {
        editar_insertar_postulacion();
    });
    $('#btn_eliminar_postulacion').on('click', function() {
        delete_postulacion();
    });
    $('#btn_seleccionar_postulante').on('click', function() {
        abrir_modal_postulantes();
    });
});
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Postulaciones</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Registros</li>
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
                            <div><i class="bx bxs-user-check me-1 font-22 text-primary"></i></div>
                            <h5 class="mb-0 text-primary">
                                <?php
                                if ($_id == '') {
                                    echo 'Registrar Postulación';
                                } else {
                                    echo 'Modificar Postulación';
                                }
                                ?>
                            </h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_postulados&_id=<?= $_id_p ?>"
                                        class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i>
                                        Regresar</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <form id="form_postulacion">
                            <!-- Hidden ID -->
                            <input type="hidden" id="txt_th_posu_id" name="txt_th_posu_id" value="<?= $_id ?>" />
                            <input type="hidden" id="txt_postulante_per_id" name="txt_postulante_per_id" />
                            <input type="hidden" id="txt_postulante_pos_id" name="txt_postulante_pos_id" />
                            <input type="hidden" id="txt_th_id_plaza" name="txt_th_id_plaza" />

                            <!-- SECCIÓN 1: INFORMACIÓN DE LA PLAZA -->
                            <div class="card mb-3">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="bx bx-briefcase me-2"></i>Información de la Plaza</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <!-- Plaza -->
                                        <div class="col-md-6">
                                            <label for="ddl_plaza" class="form-label fw-bold">
                                                <i class="bx bx-building me-2 text-primary"></i> Plaza
                                            </label>
                                            <select class="form-select" id="ddl_plaza" name="ddl_plaza" required>
                                                <option value="">-- Seleccione una plaza --</option>
                                            </select>
                                        </div>

                                        <!-- Fecha de Postulación -->
                                        <div class="col-md-6">
                                            <label for="txt_th_posu_fecha" class="form-label fw-bold">
                                                <i class="bx bx-calendar me-2 text-info"></i> Fecha de Postulación
                                            </label>
                                            <input type="datetime-local" class="form-control" id="txt_th_posu_fecha"
                                                name="txt_th_posu_fecha" required />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="pnl_datos_postulante" style="display:none">
                                <!-- SECCIÓN 2: INFORMACIÓN DEL POSTULANTE -->
                                <div class="card mb-3">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0"><i class="bx bx-user me-2"></i>Información del Postulante</h6>
                                    </div>
                                    <div class="card-body">

                                        <div class="row g-3">
                                            <!-- Tipo de Postulante -->
                                            <div class="col-md-4">
                                                <label for="ddl_tipo_postulante" class="form-label fw-bold">
                                                    <i class="bx bx-group me-2 text-success"></i> Tipo de Postulante
                                                </label>
                                                <select class="form-select" id="ddl_tipo_postulante"
                                                    name="ddl_tipo_postulante" required>
                                                    <option value="">-- Seleccione --</option>
                                                    <option value="interno">Interno (Personal)</option>
                                                    <option value="externo">Externo (Postulante)</option>
                                                </select>
                                            </div>

                                            <!-- Nombre del Postulante (readonly) -->
                                            <div class="col-md-6">
                                                <label for="txt_postulante_nombre" class="form-label fw-bold">
                                                    <i class="bx bx-user-circle me-2 text-info"></i> Postulante
                                                    Seleccionado
                                                </label>
                                                <input type="text" class="form-control" id="txt_postulante_nombre"
                                                    name="txt_postulante_nombre" readonly
                                                    placeholder="Ninguno seleccionado" />
                                            </div>


                                            <!-- Botón para seleccionar -->
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-primary w-100"
                                                    id="btn_seleccionar_postulante">
                                                    <i class="bx bx-search-alt me-1"></i> Buscar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- SECCIÓN 3: DETALLES DE LA POSTULACIÓN -->
                            <div class="card mb-3">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0"><i class="bx bx-detail me-2"></i>Detalles de la Postulación</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <!-- Fuente -->
                                        <div class="col-md-3">
                                            <label for="ddl_th_posu_fuente" class="form-label fw-bold">
                                                <i class="bx bx-link me-2 text-primary"></i> Fuente
                                            </label>
                                            <select class="form-select" id="ddl_th_posu_fuente"
                                                name="ddl_th_posu_fuente">
                                                <option value="">-- Seleccione --</option>

                                                <optgroup label="Fuentes en línea">
                                                    <option value="indeed">Indeed</option>
                                                    <option value="linkedin">LinkedIn</option>
                                                    <option value="portal_empleos">Portal de Empleos</option>
                                                    <option value="computrabajo">Computrabajo</option>
                                                    <option value="otro_portal">Otro portal web</option>
                                                </optgroup>

                                                <optgroup label="Fuentes directas">
                                                    <option value="web">Sitio Web Corporativo</option>
                                                    <option value="email">Correo Electrónico</option>
                                                    <option value="referido">Referido</option>
                                                    <option value="interno">Interno (Empleado actual)</option>
                                                    <option value="cargado_sistema">Subido en el sistema</option>
                                                </optgroup>

                                                <optgroup label="Otros">
                                                    <option value="feria_empleo">Feria de Empleo</option>
                                                    <option value="otro">Otro</option>
                                                </optgroup>
                                            </select>
                                        </div>

                                        <!-- Estado -->
                                        <div class="col-md-3">
                                            <label for="ddl_th_posu_estado" class="form-label fw-bold">
                                                <i class="bx bx-check-circle me-2 text-success"></i> Estado
                                            </label>
                                            <select class="form-select" id="ddl_th_posu_estado"
                                                name="ddl_th_posu_estado">
                                                <option value="">-- Seleccione estado --</option>
                                                <option value="activo">Activo / Recibido</option>
                                                <option value="revision">En Revisión</option>
                                                <option value="preseleccionado">Preseleccionado</option>
                                                <option value="aprobado">Aprobado</option>
                                                <option value="rechazado">Rechazado</option>
                                                <option value="inactivo">Inactivo / Cancelado</option>
                                            </select>
                                        </div>

                                        <!-- Prioridad -->
                                        <div class="col-md-3">
                                            <label for="ddl_th_posu_prioridad" class="form-label fw-bold">
                                                <i class="bx bx-star me-2 text-warning"></i> Prioridad
                                            </label>
                                            <select class="form-select" id="ddl_th_posu_prioridad"
                                                name="ddl_th_posu_prioridad">
                                                <option value="">-- Seleccione --</option>
                                                <option value="2">Alta</option>
                                                <option value="1">Media</option>
                                                <option value="0">Baja</option>
                                            </select>
                                        </div>

                                        <!-- Score -->
                                        <div class="col-md-3">
                                            <label for="txt_th_posu_score" class="form-label fw-bold">
                                                <i class="bx bx-trophy me-2 text-warning"></i> Score
                                            </label>
                                            <input type="number" class="form-control" id="txt_th_posu_score"
                                                name="txt_th_posu_score" min="0" max="100" step="0.01"
                                                placeholder="0-100" />
                                        </div>

                                        <!-- URL Curriculum -->
                                        <div class="col-md-12">
                                            <label for="txt_th_posu_curriculum_url" class="form-label fw-bold">
                                                <i class="bx bx-file-blank me-2 text-info"></i> URL del Currículum
                                            </label>
                                            <input type="url" class="form-control" id="txt_th_posu_curriculum_url"
                                                name="txt_th_posu_curriculum_url" placeholder="https://..." />
                                        </div>

                                        <!-- Observaciones -->
                                        <div class="col-12">
                                            <label for="txt_th_posu_observaciones" class="form-label fw-bold">
                                                <i class="bx bx-note me-2 text-secondary"></i> Observaciones
                                            </label>
                                            <textarea class="form-control" id="txt_th_posu_observaciones"
                                                name="txt_th_posu_observaciones" rows="4"
                                                placeholder="Comentarios adicionales sobre la postulación..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- BOTONES DE ACCIÓN -->
                            <div class="d-flex justify-content-end gap-2">
                                <?php if ($_id == '') { ?>
                                <button type="button" class="btn btn-success" id="btn_guardar_postulacion">
                                    <i class="bx bx-save me-1"></i> Guardar Postulación
                                </button>
                                <?php } else { ?>
                                <button type="button" class="btn btn-primary" id="btn_editar_postulacion">
                                    <i class="bx bx-edit me-1"></i> Actualizar Postulación
                                </button>
                                <button type="button" class="btn btn-danger" id="btn_eliminar_postulacion">
                                    <i class="bx bx-trash me-1"></i> Eliminar Postulación
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

<!-- MODAL PARA SELECCIONAR POSTULANTE -->
<div class="modal fade" id="modal_postulantes" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bx bx-search me-2"></i>Seleccionar Postulante</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table id="tbl_personas" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Cédula</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>