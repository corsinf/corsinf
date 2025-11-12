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
            cargar_plaza(<?= $_id ?>);
        <?php } ?>
         cargar_selects2();


        function cargar_selects2() {
            url_horariosC = '../controlador/TALENTO_HUMANO/th_horariosC.php?buscar=true';
            cargar_select2_url('ddl_horario', url_horariosC);
        }

        // formato para inputs datetime-local (espera: "YYYY-MM-DDTHH:MM")
        function formatDateToInput(dateStr) {
            if (!dateStr) return '';
            // casos: SQL suele devolver "YYYY-MM-DD HH:MM:SS" o con zona, intentamos normalizar
            // quitamos milisegundos y zona si los hay
            dateStr = dateStr.replace('.000','').trim();
            // si contiene espacio entre fecha y hora -> convertir a T
            if (dateStr.indexOf(' ') !== -1) {
                return dateStr.slice(0,16).replace(' ', 'T');
            }
            // si ya contiene T
            if (dateStr.indexOf('T') !== -1) {
                return dateStr.slice(0,16);
            }
            return dateStr;
        }

        function boolVal(val) {
            // normalizamos 1/0 / true/false / 'true' / 'false'
            return (val === 1 || val === '1' || val === true || val === 'true') ? true : false;
        }

         function validarFechas() {
        const fechaPublicacionStr = $('#txt_th_pla_fecha_publicacion').val();
        const fechaCierreStr = $('#txt_th_pla_fecha_cierre').val();

        if (!fechaPublicacionStr || !fechaCierreStr) return;

        const fechaPublicacion = new Date(fechaPublicacionStr);
        const fechaCierre = new Date(fechaCierreStr);
        const fechaActual = new Date();

        // Normalizar (remover segundos y ms)
        fechaActual.setSeconds(0, 0);

        // Validar que las fechas sean mayores o iguales a la actual
        if (fechaPublicacion < fechaActual) {
            Swal.fire({
                icon: 'warning',
                title: 'Fecha inválida',
                text: 'La fecha de publicación no puede ser anterior a la fecha actual.',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#3085d6'
            }).then(() => {
                $('#txt_th_pla_fecha_publicacion').val('');
                $('#txt_th_pla_fecha_publicacion').focus();
            });
            return false;
        }

        if (fechaCierre < fechaActual) {
            Swal.fire({
                icon: 'warning',
                title: 'Fecha inválida',
                text: 'La fecha de cierre no puede ser anterior a la fecha actual.',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#3085d6'
            }).then(() => {
                $('#txt_th_pla_fecha_cierre').val('');
                $('#txt_th_pla_fecha_cierre').focus();
            });
            return false;
        }

        // Validar que la fecha de cierre sea mayor o igual que la publicación
        if (fechaCierre < fechaPublicacion) {
            Swal.fire({
                icon: 'error',
                title: 'Rango de fechas incorrecto',
                text: 'La fecha de cierre no puede ser menor que la fecha de publicación.',
                confirmButtonText: 'Corregir',
                confirmButtonColor: '#d33'
            }).then(() => {
                $('#txt_th_pla_fecha_cierre').val('');
                $('#txt_th_pla_fecha_cierre').focus();
            });
            return false;
        }

        return true;
    }

    // Ejecutar validación cada vez que cambie una de las fechas
    $('#txt_th_pla_fecha_publicacion, #txt_th_pla_fecha_cierre').on('change', function () {
        validarFechas();
    });
        // CARGAR DATOS DE UNA PLAZA EN EL FORMULARIO
        function cargar_plaza(id) {
            $.ajax({
                data: { id: id },
                // <-- Cambia esta URL por la de tu controlador
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_plazasC.php?listar=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    if (!response || !response[0]) return;
                    var r = response[0];

                    $('#txt_th_pla_id').val(r._id);
                    $('#txt_th_pla_titulo').val(r.th_pla_titulo);
                    $('#txt_th_pla_descripcion').val(r.th_pla_descripcion);
                    $('#txt_th_pla_num_vacantes').val(r.th_pla_num_vacantes);
                    $('#txt_th_pla_fecha_publicacion').val(formatDateToInput(r.th_pla_fecha_publicacion));
                    $('#txt_th_pla_fecha_cierre').val(formatDateToInput(r.th_pla_fecha_cierre));
                    $('#txt_th_pla_jornada_id').val(r.th_pla_jornada_id);
                    $('#txt_th_pla_salario_min').val(r.th_pla_salario_min);
                    $('#txt_th_pla_salario_max').val(r.th_pla_salario_max);
                    $('#txt_th_pla_tiempo_contrato').val(r.th_pla_tiempo_contrato);
                    $('#chk_th_pla_prioridad_interna').prop('checked', boolVal(r.th_pla_prioridad_interna));
                    $('#chk_th_pla_requiere_documentos').prop('checked', boolVal(r.th_pla_requiere_documentos));
                    $('#txt_th_pla_responsable_persona_id').val(r.th_pla_responsable_persona_id);
                    $('#txt_th_pla_observaciones').val(r.th_pla_observaciones);
                    $('#txt_th_pla_fecha_creacion').val(formatDateToInput(r.th_pla_fecha_creacion));
                    $('#txt_th_pla_fecha_modificacion').val(formatDateToInput(r.th_pla_fecha_modificacion));
                    // si no viene valor válido, dejar la opción por defecto
                    if (r.th_pla_tipo === null || r.th_pla_tipo === undefined || r.th_pla_tipo === '') {
                        $('#ddl_th_pla_tipo').val('');
                    } else {
                        // Normalizamos valor para evitar problemas de mayúsculas/minúsculas
                        var tipoNorm = String(r.th_pla_tipo).trim();
                        // si coincide con una de las opciones, la seleccionamos; si no, dejamos vacio
                        if (tipoNorm === 'Interna' || tipoNorm === 'Externa' || tipoNorm === 'Mixta') {
                            $('#ddl_th_pla_tipo').val(tipoNorm);
                        } else {
                            $('#ddl_th_pla_tipo').val('');
                        }
                    }
                    $('#ddl_horario').append($('<option>', {
                        value: r.hor_id,
                        text: r.hor_nombre,
                        selected: true
                    }));

                },
                error: function(err) {
                    console.error(err);
                    alert('Error al cargar la plaza (revisar consola).');
                }
            });
        }

       
    });
</script>


<script type="text/javascript">
$(document).ready(function() {

    // --- jQuery Validate rules (adaptadas a form_plaza) ---
    $("#form_plaza").validate({
        rules: {
            txt_th_pla_titulo: { required: true },
            ddl_th_pla_tipo: { required: true },
            txt_th_pla_num_vacantes: { required: true, min: 1 },
            ddl_horario: { required: true }
        },
        messages: {
            txt_th_pla_titulo: { required: "Por favor ingrese el título de la plaza" },
            ddl_th_pla_tipo: { required: "Seleccione el tipo de plaza" },
            txt_th_pla_num_vacantes: { required: "Ingrese número de vacantes", min: "La cantidad mínima es 1" },
            ddl_horario: { required: "Seleccione un horario" }
        },
        highlight: function(element) {
            let $element = $(element);
            if ($element.hasClass("select2-hidden-accessible")) {
                $element.next(".select2-container").find(".select2-selection").removeClass("is-valid").addClass("is-invalid");
            } else {
                $element.removeClass("is-valid").addClass("is-invalid");
            }
        },
        unhighlight: function(element) {
            let $element = $(element);
            if ($element.hasClass("select2-hidden-accessible")) {
                $element.next(".select2-container").find(".select2-selection").removeClass("is-invalid").addClass("is-valid");
            } else {
                $element.removeClass("is-invalid").addClass("is-valid");
            }
        },
        submitHandler: function(form) {
            // No se usa submit nativo: controlamos con botones
            return false;
        }
    });

    // --- funciones para insertar/editar/eliminar ---
    function Parametros() {
        return {
            '_id': $('#txt_th_pla_id').val() || '',
            'txt_th_pla_titulo': $('#txt_th_pla_titulo').val(),
            'ddl_th_pla_tipo': $('#ddl_th_pla_tipo').val(),
            'txt_th_pla_num_vacantes': $('#txt_th_pla_num_vacantes').val(),
            'ddl_horario': $('#ddl_horario').val(),
            'txt_th_pla_fecha_publicacion': $('#txt_th_pla_fecha_publicacion').val(),
            'txt_th_pla_fecha_cierre': $('#txt_th_pla_fecha_cierre').val(),
            'txt_th_pla_tiempo_contrato': $('#txt_th_pla_tiempo_contrato').val(),
            'txt_th_pla_salario_min': $('#txt_th_pla_salario_min').val(),
            'txt_th_pla_salario_max': $('#txt_th_pla_salario_max').val(),
            'txt_th_pla_responsable_persona_id': $('#txt_th_pla_responsable_persona_id').val(),
            'chk_th_pla_prioridad_interna': $('#chk_th_pla_prioridad_interna').is(':checked') ? 1 : 0,
            'chk_th_pla_requiere_documentos': $('#chk_th_pla_requiere_documentos').is(':checked') ? 1 : 0,
            'txt_th_pla_descripcion': $('#txt_th_pla_descripcion').val(),
            'txt_th_pla_observaciones': $('#txt_th_pla_observaciones').val()
        };
    }

    function insertar_plaza(parametros) {
        $.ajax({
            data: { parametros: parametros },
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_plazasC.php?insertar_editar=true',
            type: 'post',
            dataType: 'json',
            success: function(res) {
                if (res == 1) {
                    Swal.fire('', 'Plaza creada con éxito.', 'success').then(function() {
                        window.location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_plazas';
                    });
                } else if (res == -2) {
                    $('#txt_th_pla_titulo').addClass('is-invalid');
                    if ($('#error_txt_th_pla_titulo').length == 0) {
                        $('#txt_th_pla_titulo').after('<div id="error_txt_th_pla_titulo" class="invalid-feedback">El título de la plaza ya está en uso.</div>');
                    } else { $('#error_txt_th_pla_titulo').text('El título de la plaza ya está en uso.'); }
                } else {
                    Swal.fire('', res.msg || 'Error al guardar plaza.', 'error');
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });

        $('#txt_th_pla_titulo').on('input', function() {
            $(this).removeClass('is-invalid');
            $('#error_txt_th_pla_titulo').text('');
        });
    }

    function editar_plaza(parametros) {
        $.ajax({
            data: { parametros: parametros },
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_plazasC.php?insertar_editar=true',
            type: 'post',
            dataType: 'json',
            success: function(res) {
                if (res == 1) {
                    Swal.fire('', 'Plaza actualizada con éxito.', 'success').then(function() {
                        window.location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_plazas';
                    });
                } else if (res == -2) {
                    $('#txt_th_pla_titulo').addClass('is-invalid');
                    if ($('#error_txt_th_pla_titulo').length == 0) {
                        $('#txt_th_pla_titulo').after('<div id="error_txt_th_pla_titulo" class="invalid-feedback">El título de la plaza ya está en uso por otro registro.</div>');
                    } else { $('#error_txt_th_pla_titulo').text('El título de la plaza ya está en uso por otro registro.'); }
                } else {
                    Swal.fire('', res.msg || 'Error al actualizar plaza.', 'error');
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
    }

    function delete_plaza() {
        var id = $('#txt_th_pla_id').val() || '';
        if (!id) { Swal.fire('', 'ID no encontrado para eliminar', 'warning'); return; }
        Swal.fire({
            title: 'Eliminar Registro?',
            text: "¿Está seguro de eliminar esta plaza?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    data: { _id: id },
                    url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_plazasC.php?eliminar=true',
                    type: 'post',
                    dataType: 'json',
                    success: function(res) {
                        if (res == 1) {
                            Swal.fire('Eliminado!', 'Plaza eliminada.', 'success').then(function() {
                                window.location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_plazas';
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
    $('#btn_guardar_plaza').on('click', function() {
        if (!$("#form_plaza").valid()) return;
        var params = Parametros();
        insertar_plaza(params);
    });

    $('#btn_editar_plaza').on('click', function() {
        if (!$("#form_plaza").valid()) return;
        var params = Parametros();
        editar_plaza(params);
    });

    $('#btn_eliminar_plaza').on('click', function() {
        delete_plaza();
    });

});
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Plazas</div>
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
                            <div><i class="bx bxs-briefcase me-1 font-22 text-primary"></i></div>
                            <h5 class="mb-0 text-primary">
                                <?php
                                if ($_id == '') {
                                    echo 'Registrar Plaza';
                                } else {
                                    echo 'Modificar Plaza';
                                }
                                ?>
                            </h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_plazas" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                       <form id="form_plaza">
    <!-- Hidden ID -->
    <input type="hidden" id="txt_th_pla_id" name="txt_th_pla_id" value="<?= $_id ?>" />

    <!-- SECCIÓN 1: INFORMACIÓN BÁSICA (Ancho completo) -->
    <div class="card mb-3">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0"><i class="bx bx-info-circle me-2"></i>Información Básica</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <!-- Título -->
                <div class="col-md-12">
                    <label for="txt_th_pla_titulo" class="form-label fw-bold">
                        <i class="bx bx-id-card me-2 text-primary"></i> Título de la Plaza
                    </label>
                    <input type="text" class="form-control" id="txt_th_pla_titulo" name="txt_th_pla_titulo" placeholder="Ingrese el título de la plaza" autocomplete="off" required />
                </div>

                <!-- Tipo -->
                <div class="col-md-4">
                    <label for="ddl_th_pla_tipo" class="form-label fw-bold">
                        <i class="bx bx-tag me-2 text-success"></i> Tipo
                    </label>
                    <select class="form-select select2-validation" id="ddl_th_pla_tipo" name="ddl_th_pla_tipo" required>
                        <option value="" selected hidden>-- Seleccione --</option>
                        <option value="Interna">Interna</option>
                        <option value="Externa">Externa</option>
                        <option value="Mixta">Mixta</option>
                    </select>
                    <div class="form-text">Seleccione si la plaza es interna, externa o mixta</div>
                </div>

                <!-- Vacantes -->
                <div class="col-md-4">
                    <label for="txt_th_pla_num_vacantes" class="form-label fw-bold">
                        <i class="bx bx-user-plus me-2 text-warning"></i> Número de Vacantes
                    </label>
                    <input type="number" min="1" class="form-control" id="txt_th_pla_num_vacantes" name="txt_th_pla_num_vacantes" placeholder="Ej: 1" required />
                </div>

                <!-- Horarios -->
                <div class="col-md-4">
                    <label for="ddl_horario" class="form-label fw-bold">
                        <i class="bx bx-time me-2 text-info"></i> Horario
                    </label>
                    <select class="form-select select2-validation" id="ddl_horario" name="ddl_horario" required>
                        <option value="" selected hidden>-- Seleccione --</option>
                        <!-- opciones dinámicas -->
                    </select>
                    <div class="form-text">Seleccione el horario de trabajo</div>
                </div>
            </div>
        </div>
    </div>

    <!-- FILA 1: FECHAS Y PLAZOS + INFORMACIÓN SALARIAL -->
    <div class="row g-3 mb-3">
        <!-- SECCIÓN 2: FECHAS Y PLAZOS (Columna izquierda) -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bx bx-calendar me-2"></i>Fechas y Plazos</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Fecha publicación -->
                        <div class="col-md-6">
                            <label for="txt_th_pla_fecha_publicacion" class="form-label fw-bold">
                                <i class="bx bx-calendar me-2 text-info"></i> Publicación
                            </label>
                            <input type="datetime-local" class="form-control" id="txt_th_pla_fecha_publicacion" name="txt_th_pla_fecha_publicacion" />
                        </div>

                        <!-- Fecha cierre -->
                        <div class="col-md-6">
                            <label for="txt_th_pla_fecha_cierre" class="form-label fw-bold">
                                <i class="bx bx-calendar-check me-2 text-danger"></i> Cierre
                            </label>
                            <input type="datetime-local" class="form-control" id="txt_th_pla_fecha_cierre" name="txt_th_pla_fecha_cierre" />
                        </div>

                        <!-- Tiempo contrato -->
                        <div class="col-12">
                            <label for="txt_th_pla_tiempo_contrato" class="form-label fw-bold">
                                <i class="bx bx-time-five me-2 text-primary"></i> Duración del Contrato
                            </label>
                            <input type="text" class="form-control" id="txt_th_pla_tiempo_contrato" name="txt_th_pla_tiempo_contrato" placeholder="Ej: Indefinido, 6 meses, 1 año" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 3: INFORMACIÓN SALARIAL (Columna derecha) -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="bx bx-money me-2"></i>Información Salarial</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Salario min -->
                        <div class="col-12">
                            <label for="txt_th_pla_salario_min" class="form-label fw-bold">
                                <i class="bx bx-money me-2 text-success"></i> Salario Mínimo
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control" id="txt_th_pla_salario_min" name="txt_th_pla_salario_min" placeholder="0.00" />
                            </div>
                        </div>

                        <!-- Salario max -->
                        <div class="col-12">
                            <label for="txt_th_pla_salario_max" class="form-label fw-bold">
                                <i class="bx bx-money me-2 text-success"></i> Salario Máximo
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control" id="txt_th_pla_salario_max" name="txt_th_pla_salario_max" placeholder="0.00" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FILA 2: CONFIGURACIÓN Y REQUISITOS + DESCRIPCIÓN Y OBSERVACIONES -->
    <div class="row g-3 mb-3">
        <!-- SECCIÓN 4: CONFIGURACIÓN Y REQUISITOS (Columna izquierda) -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="bx bx-cog me-2"></i>Configuración</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Responsable persona id -->
                        <div class="col-12">
                            <label for="txt_th_pla_responsable_persona_id" class="form-label fw-bold">
                                <i class="bx bx-user-check me-2 text-info"></i> Responsable (ID)
                            </label>
                            <input type="number" min="0" class="form-control" id="txt_th_pla_responsable_persona_id" name="txt_th_pla_responsable_persona_id" placeholder="Ingrese el ID del responsable" />
                        </div>

                        <!-- Switches -->
                        <div class="col-12">
                            <label class="form-label fw-bold d-block">
                                <i class="bx bx-check-shield me-2 text-primary"></i> Prioridad Interna
                            </label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="chk_th_pla_prioridad_interna" />
                                <label class="form-check-label" for="chk_th_pla_prioridad_interna">
                                    Activar prioridad interna
                                </label>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold d-block">
                                <i class="bx bx-folder-open me-2 text-primary"></i> Requiere Documentos
                            </label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="chk_th_pla_requiere_documentos" />
                                <label class="form-check-label" for="chk_th_pla_requiere_documentos">
                                    Requiere documentación
                                </label>
                            </div>
                        </div>

                       
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 5: DESCRIPCIÓN Y OBSERVACIONES (Columna derecha) -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="bx bx-file-blank me-2"></i>Descripción</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Descripción larga -->
                        <div class="col-12">
                            <label for="txt_th_pla_descripcion" class="form-label fw-bold">
                                <i class="bx bx-file me-2 text-primary"></i> Descripción del Puesto
                            </label>
                            <textarea class="form-control" id="txt_th_pla_descripcion" name="txt_th_pla_descripcion"  placeholder="Describa responsabilidades y funciones..."></textarea>
                            <div class="form-text">Visible para postulantes</div>
                        </div>

                        <!-- Observaciones -->
                        <div class="col-12">
                            <label for="txt_th_pla_observaciones" class="form-label fw-bold">
                                <i class="bx bx-comment-detail me-2 text-warning"></i> Observaciones
                            </label>
                            <textarea class="form-control" id="txt_th_pla_observaciones" name="txt_th_pla_observaciones"  placeholder="Notas internas..."></textarea>
                            <div class="form-text">Solo visible internamente</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BOTONES DE ACCIÓN -->
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end gap-2">
                <?php if ($_id == '') { ?>
                    <button type="button" class="btn btn-success" id="btn_guardar_plaza">
                        <i class="bx bx-save me-1"></i> Guardar Plaza
                    </button>
                <?php } else { ?>
                    <button type="button" class="btn btn-primary" id="btn_editar_plaza">
                        <i class="bx bx-edit me-1"></i> Actualizar Plaza
                    </button>
                    <button type="button" class="btn btn-danger" id="btn_eliminar_plaza">
                        <i class="bx bx-trash me-1"></i> Eliminar Plaza
                    </button>
                <?php } ?>
            </div>
        </div>
    </div>
</form>

                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
