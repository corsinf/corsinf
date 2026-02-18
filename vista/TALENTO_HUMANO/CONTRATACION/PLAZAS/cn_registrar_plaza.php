<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
$_id = '';
if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}
?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<style>
    #tab_content_smart .tab-pane {
        display: none;
        height: auto !important;
        max-height: none !important;
        overflow: visible !important;
    }

    #tab_content_smart .tab-pane.active {
        display: block;
    }

    #smartwizard_seleccion,
    #smartwizard_seleccion>.tab-content {
        overflow: visible !important;
        height: auto !important;
    }
</style>

<script>
    $(document).ready(function() {
        <?php if (isset($_GET['_id'])) { ?>

            cargar_plaza(<?= $_id ?>);

        <?php } ?>
        cargar_selects2_plaza();


        function cargar_selects2_plaza() {
            url_tipoSeleccionC = '../controlador/TALENTO_HUMANO/CATALOGOS/cn_cat_tipo_seleccionC.php?buscar=true';
            cargar_select2_url('ddl_id_tipo_seleccion', url_tipoSeleccionC);
            url_cargosC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_cargoC.php?buscar=true';
            cargar_select2_url('ddl_cargo', url_cargosC);
            url_departamentosC = '../controlador/TALENTO_HUMANO/th_departamentosC.php?buscar=true';
            cargar_select2_url('ddl_th_dep_id', url_departamentosC);
            url_nominaC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_nominaC.php?buscar=true';
            cargar_select2_url('ddl_id_nomina', url_nominaC);
            url_personaNominaC = '../controlador/TALENTO_HUMANO/th_personasC.php?busca_persona_nomina=true';
            cargar_select2_url('ddl_cn_pla_responsable', url_personaNominaC);
        }
    });

    function formatDateToInput(dateStr) {
        if (!dateStr) return '';
        // Solo retorna la parte de fecha YYYY-MM-DD
        dateStr = dateStr.replace('.000', '').trim();
        if (dateStr.indexOf('T') !== -1) return dateStr.slice(0, 10);
        if (dateStr.indexOf(' ') !== -1) return dateStr.slice(0, 10);
        return dateStr.slice(0, 10);
    }

    function boolVal(val) {
        return (val === 1 || val === '1' || val === true || val === 'true');
    }

    function ParametrosPlaza() {
        return {
            '_id': $('#txt_cn_pla_id').val() || '',
            'txt_cn_pla_titulo': $('#txt_cn_pla_titulo').val(),
            'txt_cn_pla_descripcion': $('#txt_cn_pla_descripcion').val(),
            'ddl_cargo': $('#ddl_cargo').val(),
            'ddl_th_dep_id': $('#ddl_th_dep_id').val(),
            'ddl_id_tipo_seleccion': $('#ddl_id_tipo_seleccion').val(),
            'txt_cn_pla_num_vacantes': $('#txt_cn_pla_num_vacantes').val(),
            'ddl_id_nomina': $('#ddl_id_nomina').val(),
            'txt_cn_pla_fecha_publicacion': $('#txt_cn_pla_fecha_publicacion').val(),
            'txt_cn_pla_fecha_cierre': $('#txt_cn_pla_fecha_cierre').val(),
            'txt_cn_pla_salario_min': $('#txt_cn_pla_salario_min').val(),
            'txt_cn_pla_salario_max': $('#txt_cn_pla_salario_max').val(),
            'ddl_cn_pla_responsable': $('#ddl_cn_pla_responsable').val(),
            'cbx_cn_pla_req_disponibilidad': $('#cbx_cn_pla_req_disponibilidad').is(':checked') ? 1 : 0,
            'cbx_cn_pla_prioridad_interna': $('#cbx_cn_pla_prioridad_interna').is(':checked') ? 1 : 0,
            'cbx_cn_pla_req_documentos': $('#cbx_cn_pla_req_documentos').is(':checked') ? 1 : 0,
            'txt_cn_pla_observaciones': $('#txt_cn_pla_observaciones').val()
        };
    }

    function insertar_plaza(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/cn_plazaC.php?insertar_editar=true',
            type: 'post',
            dataType: 'json',
            success: function(res) {
                if (res > 0) {
                    Swal.fire('', 'Plaza creada con éxito.', 'success').then(function() {
                        localStorage.setItem('plaza_id', res);
                        cargar_plaza(res);
                    });
                } else if (res == -2) {
                    Swal.fire('', 'Ya existe una plaza con ese título.', 'warning');
                } else {
                    Swal.fire('', res.msg || 'Error al guardar plaza.', 'error');
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
    }

    function cargar_plaza(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/cn_plazaC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (!response || !response[0]) return;
                var r = response[0];

                $('#txt_cn_pla_id').val(r._id);
                $('#txt_cn_pla_titulo').val(r.cn_pla_titulo);
                $('#txt_cn_pla_descripcion').val(r.cn_pla_descripcion);
                $('#txt_cn_pla_num_vacantes').val(r.cn_pla_num_vacantes);
                $('#txt_cn_pla_fecha_publicacion').val(formatDateToInput(r.cn_pla_fecha_publicacion));
                $('#txt_cn_pla_fecha_cierre').val(formatDateToInput(r.cn_pla_fecha_cierre));
                $('#txt_cn_pla_salario_min').val(r.cn_pla_salario_min);
                $('#txt_cn_pla_salario_max').val(r.cn_pla_salario_max);
                $('#txt_cn_pla_observaciones').val(r.cn_pla_observaciones);
                $('#cbx_cn_pla_req_disponibilidad').prop('checked', boolVal(r.cn_pla_req_disponibilidad));
                $('#cbx_cn_pla_prioridad_interna').prop('checked', boolVal(r.cn_pla_req_prioridad_interna));
                $('#cbx_cn_pla_req_documentos').prop('checked', boolVal(r.cn_pla_req_documentos));

                $('#ddl_cargo').append($('<option>', {
                    value: r.id_cargo,
                    text: r.descripcion_cargo,
                    selected: true
                }));
                $('#ddl_th_dep_id').append($('<option>', {
                    value: r.th_dep_id,
                    text: r.descripcion_departamento,
                    selected: true
                }));
                $('#ddl_id_nomina').append($('<option>', {
                    value: r.id_nomina,
                    text: r.descripcion_nomina,
                    selected: true
                }));
                $('#ddl_id_tipo_seleccion').append($('<option>', {
                    value: r.id_tipo_seleccion,
                    text: r.descripcion_tipo_seleccion,
                    selected: true
                }));
                $('#ddl_cn_pla_responsable').append($('<option>', {
                    value: r.th_per_id_responsable,
                    text: r.per_cedula + ' - ' + r.per_nombre_completo,
                    selected: true
                }));
            },
            error: function(err) {
                console.error(err);
                Swal.fire('', 'Error al cargar la plaza.', 'error');
            }
        });
    }

    // ─── FECHAS ────────────────────────────────────────────────────────────────
    // Valida en tiempo real: borra y marca solo el campo con el problema
    function validarFechaPublicacion() {
        const $pub = $('#txt_cn_pla_fecha_publicacion');
        const pubStr = $pub.val();
        if (!pubStr) return true;

        const hoy = new Date();
        hoy.setHours(0, 0, 0, 0);
        const fechaPub = new Date(pubStr + 'T00:00:00');

        if (fechaPub < hoy) {
            $pub.addClass('is-invalid').removeClass('is-valid').val('');
            Swal.fire({
                icon: 'warning',
                title: 'Fecha inválida',
                text: 'La fecha de publicación no puede ser anterior a hoy.',
                confirmButtonText: 'Entendido'
            }).then(() => $pub.focus());
            return false;
        }

        $pub.removeClass('is-invalid').addClass('is-valid');
        // Revalidar cierre por si ya estaba ingresado
        validarFechaCierre();
        return true;
    }

    function validarFechaCierre() {
        const $pub = $('#txt_cn_pla_fecha_publicacion');
        const $cierre = $('#txt_cn_pla_fecha_cierre');
        const pubStr = $pub.val();
        const cierreStr = $cierre.val();
        if (!cierreStr) return true;

        const hoy = new Date();
        hoy.setHours(0, 0, 0, 0);
        const fechaCierre = new Date(cierreStr + 'T00:00:00');

        if (fechaCierre < hoy) {
            $cierre.addClass('is-invalid').removeClass('is-valid').val('');
            Swal.fire({
                icon: 'warning',
                title: 'Fecha inválida',
                text: 'La fecha de cierre no puede ser anterior a hoy.',
                confirmButtonText: 'Entendido'
            }).then(() => $cierre.focus());
            return false;
        }

        if (pubStr) {
            const fechaPub = new Date(pubStr + 'T00:00:00');
            if (fechaCierre < fechaPub) {
                $cierre.addClass('is-invalid').removeClass('is-valid').val('');
                Swal.fire({
                    icon: 'error',
                    title: 'Rango incorrecto',
                    text: 'La fecha de cierre no puede ser menor que la de publicación.',
                    confirmButtonText: 'Corregir'
                }).then(() => $cierre.focus());
                return false;
            }
        }

        $cierre.removeClass('is-invalid').addClass('is-valid');
        return true;
    }

    function validarFechas() {
        return validarFechaPublicacion() && validarFechaCierre();
    }

    // ─── SALARIOS ──────────────────────────────────────────────────────────────
    function validarSalarioMin() {
        const $min = $('#txt_cn_pla_salario_min');
        const $max = $('#txt_cn_pla_salario_max');
        if ($min.val() === '') return true;

        const min = parseFloat($min.val());

        if (min < 0) {
            $min.addClass('is-invalid').removeClass('is-valid').val('');
            Swal.fire({
                icon: 'warning',
                title: 'Valor inválido',
                text: 'El salario mínimo no puede ser negativo.',
                confirmButtonText: 'Entendido'
            }).then(() => $min.focus());
            return false;
        }

        // Si el máximo ya tiene valor, verificar rango
        if ($max.val() !== '') {
            const max = parseFloat($max.val());
            if (min > max) {
                $min.addClass('is-invalid').removeClass('is-valid').val('');
                Swal.fire({
                    icon: 'error',
                    title: 'Rango incorrecto',
                    text: 'El salario mínimo no puede ser mayor que el máximo.',
                    confirmButtonText: 'Corregir'
                }).then(() => $min.focus());
                return false;
            }
        }

        $min.removeClass('is-invalid').addClass('is-valid');
        return true;
    }

    function validarSalarioMax() {
        const $min = $('#txt_cn_pla_salario_min');
        const $max = $('#txt_cn_pla_salario_max');
        if ($max.val() === '') return true;

        const max = parseFloat($max.val());

        if (max < 0) {
            $max.addClass('is-invalid').removeClass('is-valid').val('');
            Swal.fire({
                icon: 'warning',
                title: 'Valor inválido',
                text: 'El salario máximo no puede ser negativo.',
                confirmButtonText: 'Entendido'
            }).then(() => $max.focus());
            return false;
        }

        // Si el mínimo ya tiene valor, verificar rango
        if ($min.val() !== '') {
            const min = parseFloat($min.val());
            if (max < min) {
                $max.addClass('is-invalid').removeClass('is-valid').val('');
                Swal.fire({
                    icon: 'error',
                    title: 'Rango incorrecto',
                    text: 'El salario máximo no puede ser menor que el mínimo.',
                    confirmButtonText: 'Corregir'
                }).then(() => $max.focus());
                return false;
            }
        }

        $max.removeClass('is-invalid').addClass('is-valid');
        return true;
    }

    function validarSalarios() {
        return validarSalarioMin() && validarSalarioMax();
    }

    function guardar_plaza() {
        if (!$('#form_plaza').valid()) {
            Swal.fire({
                icon: 'warning',
                title: 'Validación',
                text: 'Complete todos los campos requeridos.'
            });
            return;
        }
        if (!validarFechas() || !validarSalarios()) return;

        Swal.fire({
            title: '¿Guardar plaza?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                insertar_plaza(ParametrosPlaza());
            }
        });
    }
</script>


<div class="page-wrapper">
    <div class="page-content">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Recursos Humanos</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Proceso de Selección</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">

                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="card-title d-flex align-items-center">
                                    <i class="bx bxs-briefcase me-1 font-22 text-primary"></i>
                                    <h5 class="mb-0 text-primary">Proceso de Selección de Personal</h5>
                                </div>
                            </div>
                            <div class="col-6 text-end">
                                <button class="btn btn-outline-primary btn-sm">
                                    <i class="bx bx-list-ol"></i> Historial
                                </button>
                                <button class="btn btn-outline-danger btn-sm" id="btn_eliminar_todo">
                                    <i class="bx bx-trash"></i> Eliminar Todo
                                </button>
                            </div>
                        </div>

                        <div id="smartwizard_seleccion">
                            <ul class="nav">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#paso-1">
                                        <strong>Paso 1</strong><br>Crear Plaza
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content" id="tab_content_smart">

                                <div id="paso-1" class="tab-pane active" role="tabpanel">
                                    <div class="container-fluid">
                                        <form id="form_plaza">
                                            <input type="hidden" id="txt_cn_pla_id" name="txt_cn_pla_id" value="" />

                                            <div class="row pt-3 mb-2">
                                                <div class="col-md-4">
                                                    <label for="txt_cn_pla_titulo" class="form-label">Título de la Plaza </label>
                                                    <input type="text"
                                                        class="form-control form-control-sm"
                                                        id="txt_cn_pla_titulo"
                                                        name="txt_cn_pla_titulo"
                                                        maxlength="150"
                                                        autocomplete="off" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="ddl_cargo" class="form-label">Cargo </label>
                                                    <select class="form-select form-select-sm select2-validation"
                                                        id="ddl_cargo"
                                                        name="ddl_cargo">
                                                        <option value="" selected hidden>-- Seleccione --</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="ddl_th_dep_id" class="form-label">Departamento </label>
                                                    <select class="form-select form-select-sm select2-validation"
                                                        id="ddl_th_dep_id"
                                                        name="ddl_th_dep_id">
                                                        <option value="" selected hidden>-- Seleccione --</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <div class="col-md-12">
                                                    <label for="txt_cn_pla_descripcion" class="form-label">Descripción del Puesto </label>
                                                    <textarea class="form-control form-control-sm"
                                                        id="txt_cn_pla_descripcion"
                                                        name="txt_cn_pla_descripcion"
                                                        rows="3"
                                                        placeholder="Describa responsabilidades y funciones..."></textarea>
                                                    <small class="text-muted">Visible para postulantes</small>
                                                </div>
                                            </div>



                                            <div class="row mb-2">
                                                <div class="col-md-4">
                                                    <label for="ddl_id_tipo_seleccion" class="form-label">Tipo de Selección </label>
                                                    <select class="form-select form-select-sm select2-validation"
                                                        id="ddl_id_tipo_seleccion"
                                                        name="ddl_id_tipo_seleccion">
                                                        <option value="" selected hidden>-- Seleccione --</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="ddl_id_nomina" class="form-label">Figura Legal </label>
                                                    <select class="form-select form-select-sm select2-validation"
                                                        id="ddl_id_nomina"
                                                        name="ddl_id_nomina">
                                                        <option value="" selected hidden>-- Seleccione --</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="txt_cn_pla_num_vacantes" class="form-label">Número de Vacantes </label>
                                                    <input type="number"
                                                        min="1"
                                                        class="form-control form-control-sm"
                                                        id="txt_cn_pla_num_vacantes"
                                                        name="txt_cn_pla_num_vacantes"
                                                        placeholder="Ej: 1" />
                                                </div>
                                            </div>

                                            <div class="p-3 bg-light rounded-3 border border-dashed mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <h6 class="text-muted fs-7 mb-0 fw-bold text-uppercase ls-1">Periodo de Publicación</h6>
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label for="txt_cn_pla_fecha_publicacion" class="form-label fs-7 mb-1 fw-bold">Fecha de Publicación </label>
                                                        <input type="date"
                                                            class="form-control form-control-sm"
                                                            id="txt_cn_pla_fecha_publicacion"
                                                            name="txt_cn_pla_fecha_publicacion" />
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="txt_cn_pla_fecha_cierre" class="form-label fs-7 mb-1 fw-bold">Fecha de Cierre </label>
                                                        <input type="date"
                                                            class="form-control form-control-sm"
                                                            id="txt_cn_pla_fecha_cierre"
                                                            name="txt_cn_pla_fecha_cierre" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <div class="col-md-6">
                                                    <label for="txt_cn_pla_salario_min" class="form-label">Salario Mínimo </label>
                                                    <input type="number"
                                                        step="0.01" min="0"
                                                        class="form-control form-control-sm"
                                                        id="txt_cn_pla_salario_min"
                                                        name="txt_cn_pla_salario_min"
                                                        placeholder="0.00" />
                                                    <span id="error_salario_min" class="text-danger" style="font-size:0.8rem;"></span>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="txt_cn_pla_salario_max" class="form-label">Salario Máximo </label>
                                                    <input type="number"
                                                        step="0.01" min="0"
                                                        class="form-control form-control-sm"
                                                        id="txt_cn_pla_salario_max"
                                                        name="txt_cn_pla_salario_max"
                                                        placeholder="0.00" />
                                                    <span id="error_salario_max" class="text-danger" style="font-size:0.8rem;"></span>
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <div class="col-md-6">
                                                    <label for="ddl_cn_pla_responsable" class="form-label">Persona Responsable </label>
                                                    <select class="form-select form-select-sm select2-validation"
                                                        id="ddl_cn_pla_responsable"
                                                        name="ddl_cn_pla_responsable">
                                                        <option value="" selected hidden>-- Seleccione --</option>
                                                    </select>
                                                    <small class="text-muted">Solo personas activas en nómina</small>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <label class="form-label fw-semibold fs-7 mb-2 text-muted text-uppercase ls-1">Requerimientos Adicionales</label>

                                                    <div class="d-flex flex-wrap gap-4 p-2 border rounded bg-white">

                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="cbx_cn_pla_req_disponibilidad"
                                                                name="cbx_cn_pla_req_disponibilidad" />
                                                            <label class="form-check-label fs-7" for="cbx_cn_pla_req_disponibilidad">
                                                                Disponibilidad Tiempo Completo
                                                            </label>
                                                        </div>

                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="cbx_cn_pla_prioridad_interna"
                                                                name="cbx_cn_pla_prioridad_interna" />
                                                            <label class="form-check-label fs-7" for="cbx_cn_pla_prioridad_interna">
                                                                Prioridad Interna
                                                            </label>
                                                        </div>

                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="cbx_cn_pla_req_documentos"
                                                                name="cbx_cn_pla_req_documentos" />
                                                            <label class="form-check-label fs-7" for="cbx_cn_pla_req_documentos">
                                                                Requiere Documentos
                                                            </label>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <div class="col-md-12">
                                                    <label for="txt_cn_pla_observaciones" class="form-label">Observaciones</label>
                                                    <textarea class="form-control form-control-sm"
                                                        id="txt_cn_pla_observaciones"
                                                        name="txt_cn_pla_observaciones"
                                                        rows="2"
                                                        placeholder="Notas internas..."></textarea>
                                                    <small class="text-muted">Solo visible internamente</small>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-12 text-end">
                                                    <button class="btn btn-primary btn-sm px-3"
                                                        onclick="guardar_plaza()"
                                                        type="button">
                                                        <i class="bx bx-save"></i> Guardar
                                                    </button>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    $(document).ready(function() {

        $('#paso-1').addClass('active');

        let idPlaza = Number(localStorage.getItem('plaza_id'));
        if (idPlaza > 0) cargar_plaza(idPlaza);

        // ── Fechas: validación en tiempo real por campo ──────────────────────
        $('#txt_cn_pla_fecha_publicacion').on('change', function() {
            validarFechaPublicacion();
        });

        $('#txt_cn_pla_fecha_cierre').on('change', function() {
            validarFechaCierre();
        });

        // ── Salarios: validación en tiempo real por campo ────────────────────
        $('#txt_cn_pla_salario_min').on('change', function() {
            validarSalarioMin();
        });

        $('#txt_cn_pla_salario_max').on('change', function() {
            validarSalarioMax();
        });

        $('#txt_cn_pla_salario_min, #txt_cn_pla_salario_max').on('input', function() {
            $(this).removeClass('is-invalid');
        });

        agregar_asterisco_campo_obligatorio('txt_cn_pla_titulo');
        agregar_asterisco_campo_obligatorio('txt_cn_pla_descripcion');
        agregar_asterisco_campo_obligatorio('ddl_cargo');
        agregar_asterisco_campo_obligatorio('ddl_th_dep_id');
        agregar_asterisco_campo_obligatorio('ddl_id_tipo_seleccion');
        agregar_asterisco_campo_obligatorio('txt_cn_pla_num_vacantes');
        agregar_asterisco_campo_obligatorio('ddl_id_nomina');
        agregar_asterisco_campo_obligatorio('txt_cn_pla_fecha_publicacion');
        agregar_asterisco_campo_obligatorio('txt_cn_pla_fecha_cierre');
        agregar_asterisco_campo_obligatorio('txt_cn_pla_salario_min');
        agregar_asterisco_campo_obligatorio('txt_cn_pla_salario_max');
        agregar_asterisco_campo_obligatorio('ddl_cn_pla_responsable');

        $('#btn_eliminar_todo').on('click', function() {
            Swal.fire({
                title: '¿Eliminar todo?',
                text: 'Se limpiará el almacenamiento local y se recargará la página.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    localStorage.clear();
                    location.reload();
                }
            });
        });

        $('#form_plaza').validate({
            ignore: ':hidden:not(.select2-hidden-accessible)',
            rules: {
                txt_cn_pla_titulo: {
                    required: true,
                    maxlength: 150
                },
                txt_cn_pla_descripcion: {
                    required: true
                },
                ddl_cargo: {
                    required: true
                },
                ddl_th_dep_id: {
                    required: true
                },
                ddl_id_tipo_seleccion: {
                    required: true
                },
                txt_cn_pla_num_vacantes: {
                    required: true,
                    min: 1,
                    digits: true
                },
                ddl_id_nomina: {
                    required: true
                },
                txt_cn_pla_fecha_publicacion: {
                    required: true
                },
                txt_cn_pla_fecha_cierre: {
                    required: true
                },
                txt_cn_pla_salario_min: {
                    required: true,
                    number: true,
                    min: 0
                },
                txt_cn_pla_salario_max: {
                    required: true,
                    number: true,
                    min: 0
                },
                ddl_cn_pla_responsable: {
                    required: true
                }
            },
            messages: {
                txt_cn_pla_titulo: {
                    required: 'Ingrese el título de la plaza',
                    maxlength: 'Máximo 150 caracteres'
                },
                txt_cn_pla_descripcion: {
                    required: 'Ingrese una descripción'
                },
                ddl_cargo: {
                    required: 'Seleccione un cargo'
                },
                ddl_th_dep_id: {
                    required: 'Seleccione un departamento'
                },
                ddl_id_tipo_seleccion: {
                    required: 'Seleccione el tipo'
                },
                txt_cn_pla_num_vacantes: {
                    required: 'Ingrese el número de vacantes',
                    min: 'Mínimo 1',
                    digits: 'Solo números enteros'
                },
                ddl_id_nomina: {
                    required: 'Seleccione la figura legal / nómina'
                },
                txt_cn_pla_fecha_publicacion: {
                    required: 'Seleccione la fecha de publicación'
                },
                txt_cn_pla_fecha_cierre: {
                    required: 'Seleccione la fecha de cierre'
                },
                txt_cn_pla_salario_min: {
                    required: 'Ingrese el salario mínimo',
                    number: 'Valor numérico válido',
                    min: 'No puede ser negativo'
                },
                txt_cn_pla_salario_max: {
                    required: 'Ingrese el salario máximo',
                    number: 'Valor numérico válido',
                    min: 'No puede ser negativo'
                },
                ddl_cn_pla_responsable: {
                    required: 'Seleccione el responsable'
                }
            },
            errorClass: 'text-danger',
            errorElement: 'div',
            highlight: function(element) {
                var $el = $(element);
                $el.addClass('is-invalid').removeClass('is-valid');
                if ($el.hasClass('select2-hidden-accessible')) {
                    $el.next('.select2-container').find('.select2-selection').addClass('is-invalid').removeClass('is-valid');
                }
            },
            unhighlight: function(element) {
                var $el = $(element);
                $el.removeClass('is-invalid').addClass('is-valid');
                if ($el.hasClass('select2-hidden-accessible')) {
                    $el.next('.select2-container').find('.select2-selection').removeClass('is-invalid').addClass('is-valid');
                }
            },
            errorPlacement: function(error, element) {
                if (element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.next('.select2-container'));
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function() {
                return false;
            }
        });

        $('.select2-validation').on('change.select2Validation', function() {
            $(this).valid();
        });

    });
</script>