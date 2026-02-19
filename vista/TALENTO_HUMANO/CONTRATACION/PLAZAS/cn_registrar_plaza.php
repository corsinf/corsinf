<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
$_id = '';
$_id_cargo = '';
if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}
if (isset($_GET['_id_cargo'])) {
    $_id_cargo = $_GET['_id_cargo'];
}

$es_plaza = true;

?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script>
    $(document).ready(function() {
        <?php if (isset($_GET['_id'])) { ?>
            cargar_plaza(<?= $_id ?>);
        <?php } ?>

        smartwizard_cargar_plaza();
        cargar_selects2_plaza();

        $('#ddl_cargo').on('change', function() {
            let id_cargo = $(this).val();
            $('#txt_id_cargo').val(id_cargo);

            cargar_descripcion_cargo(id_cargo);

            //Todo lo que tiene que ver con el cargo
            cargar_instrucciones_basicas(id_cargo, false)
        })
    });

    function cargar_selects2_plaza() {
        cargar_select2_url('ddl_id_tipo_seleccion', '../controlador/TALENTO_HUMANO/CATALOGOS/cn_cat_tipo_seleccionC.php?buscar=true');
        cargar_select2_url('ddl_cargo', '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_cargoC.php?buscar=true');
        cargar_select2_url('ddl_th_dep_id', '../controlador/TALENTO_HUMANO/th_departamentosC.php?buscar=true');
        cargar_select2_url('ddl_id_nomina', '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_nominaC.php?buscar=true');
        cargar_select2_url('ddl_cn_pla_responsable', '../controlador/TALENTO_HUMANO/th_personasC.php?busca_persona_nomina=true');
    }

    function cargar_descripcion_cargo(id_cargo) {
        $.ajax({
            data: {
                id: id_cargo
            },
            url: '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_cargoC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response && response.length > 0) {
                    $('#txt_cn_pla_descripcion').val(response[0].descripcion);
                }
            }
        });
    }

    // ─── Utilidades ────────────────────────────────────────────────────────────
    function formatDateToInput(dateStr) {
        if (!dateStr) return '';
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

    // ─── Wizard: navegación entre pasos ────────────────────────────────────────
    function smartwizard_cargar_plaza() {
        var btnSiguiente = $('<button></button>').text('Siguiente').addClass('btn btn-info').on('click', function() {
            if (valida_formulario('smartwizard_plaza')) {
                $('#smartwizard_plaza').smartWizard("next");
            } else {
                Swal.fire('', 'Llene todo los campos', 'info')
            }
        });
        var btnAtras = $('<button></button>').text('Atras').addClass('btn btn-info').on('click', function() {
            $('#smartwizard_plaza').smartWizard("prev");
            return true;
        });


        $("#smartwizard_plaza").on("showStep", function(e, anchorObject, stepNumber, stepDirection, stepPosition) {
            $("#prev-btn").removeClass('disabled');
            $("#next-btn").removeClass('disabled');
            if (stepPosition === 'first') {
                $("#prev-btn").addClass('disabled');
            } else if (stepPosition === 'last') {
                $("#next-btn").addClass('disabled');
            } else {
                $("#prev-btn").removeClass('disabled');
                $("#next-btn").removeClass('disabled');
            }
        });
        // Smart Wizard
        $('#smartwizard_plaza').smartWizard({
            selected: 0,
            theme: 'arrows',
            transition: {
                animation: 'slide-horizontal', // Effect on navigation, none/fade/slide-horizontal/slide-vertical/slide-swing
            },
            toolbarSettings: {
                toolbarPosition: '',
                toolbarExtraButtons: [btnAtras, btnSiguiente],
                showNextButton: false, // Oculta el botón predeterminado "Next"
                showPreviousButton: false,
            },
        });
    }

    function valida_formulario(formulario) {
        var pasoActual = $('#' + formulario).smartWizard('getStepIndex');
        var pasoValido = true;
        // Verificar campos requeridos en el paso actual

        $('#' + formulario + ' [data-step="' + pasoActual + '"] [required]').each(function() {
            //console.log(this)
            if (!this.checkValidity()) {
                pasoValido = false;
                num_form = pasoActual + 1;
                $('#form-step-' + num_form).addClass('was-validated');
                console.log()
                return false; // Salir del bucle si se encuentra un campo no válido
            }
        });

        return pasoValido;
    }

    // ─── AJAX ──────────────────────────────────────────────────────────────────
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
                    Swal.fire('', 'Plaza guardada con éxito.', 'success').then(function() {
                        $('#txt_cn_pla_id').val(res);
                    });
                } else if (res == -2) {
                    Swal.fire('', 'Ya existe una plaza con ese título.', 'warning');
                } else {
                    Swal.fire('', res.msg || 'Error al guardar plaza.', 'error');
                }
            },
            error: function(xhr) {
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
            error: function() {
                Swal.fire('', 'Error al cargar la plaza.', 'error');
            }
        });
    }

    // ─── Fechas ────────────────────────────────────────────────────────────────
    function validarFechaPublicacion() {
        const $pub = $('#txt_cn_pla_fecha_publicacion');
        if (!$pub.val()) return true;
        const hoy = new Date();
        hoy.setHours(0, 0, 0, 0);
        const fechaPub = new Date($pub.val() + 'T00:00:00');
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
        validarFechaCierre();
        return true;
    }

    function validarFechaCierre() {
        const $pub = $('#txt_cn_pla_fecha_publicacion');
        const $cierre = $('#txt_cn_pla_fecha_cierre');
        if (!$cierre.val()) return true;
        const hoy = new Date();
        hoy.setHours(0, 0, 0, 0);
        const fechaCierre = new Date($cierre.val() + 'T00:00:00');
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
        if ($pub.val()) {
            const fechaPub = new Date($pub.val() + 'T00:00:00');
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

    // ─── Salarios ──────────────────────────────────────────────────────────────
    function validarSalarioMin() {
        const $min = $('#txt_cn_pla_salario_min'),
            $max = $('#txt_cn_pla_salario_max');
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
        if ($max.val() !== '' && min > parseFloat($max.val())) {
            $min.addClass('is-invalid').removeClass('is-valid').val('');
            Swal.fire({
                icon: 'error',
                title: 'Rango incorrecto',
                text: 'El salario mínimo no puede ser mayor que el máximo.',
                confirmButtonText: 'Corregir'
            }).then(() => $min.focus());
            return false;
        }
        $min.removeClass('is-invalid').addClass('is-valid');
        return true;
    }

    function validarSalarioMax() {
        const $min = $('#txt_cn_pla_salario_min'),
            $max = $('#txt_cn_pla_salario_max');
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
        if ($min.val() !== '' && max < parseFloat($min.val())) {
            $max.addClass('is-invalid').removeClass('is-valid').val('');
            Swal.fire({
                icon: 'error',
                title: 'Rango incorrecto',
                text: 'El salario máximo no puede ser menor que el mínimo.',
                confirmButtonText: 'Corregir'
            }).then(() => $max.focus());
            return false;
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
            })
            .then((r) => {
                if (r.isConfirmed) insertar_plaza(ParametrosPlaza());
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
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
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
                        </div>

                        <div id="smartwizard_plaza">
                            <ul class="nav">
                                <li class="nav-item">
                                    <a class="nav-link" href="#step-1"> <strong>Step 1</strong>
                                        <br>This is step description</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#step-2"> <strong>Step 2</strong>
                                        <br>This is step description</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#step-3"> <strong>Step 3</strong>
                                        <br>This is step description</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#step-4"> <strong>Step 4</strong>
                                        <br>This is step description</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-1">

                                    <?php include_once('../vista/TALENTO_HUMANO/CONTRATACION/PLAZAS/WIZART_REGISTRAR_PLAZA/plaza_paso1.php'); ?>

                                </div>
                                <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2">

                                    <?php include_once('../vista/TALENTO_HUMANO/CARGOS/seccion_aspectos_extrinsecos.php'); ?>

                                </div>
                                <div id="step-3" class="tab-pane" role="tabpanel" aria-labelledby="step-3">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</div>
                                <div id="step-4" class="tab-pane" role="tabpanel" aria-labelledby="step-4">
                                    <h3>Step 4 Content</h3>
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
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

        // Fechas
        $('#txt_cn_pla_fecha_publicacion').on('change', function() {
            validarFechaPublicacion();
        });
        $('#txt_cn_pla_fecha_cierre').on('change', function() {
            validarFechaCierre();
        });

        // Salarios
        $('#txt_cn_pla_salario_min').on('change', function() {
            validarSalarioMin();
        });
        $('#txt_cn_pla_salario_max').on('change', function() {
            validarSalarioMax();
        });
        $('#txt_cn_pla_salario_min, #txt_cn_pla_salario_max').on('input', function() {
            $(this).removeClass('is-invalid');
        });

        // Asteriscos campos obligatorios
        ['txt_cn_pla_titulo', 'txt_cn_pla_descripcion', 'ddl_cargo', 'ddl_th_dep_id',
            'ddl_id_tipo_seleccion', 'txt_cn_pla_num_vacantes', 'ddl_id_nomina',
            'txt_cn_pla_fecha_publicacion', 'txt_cn_pla_fecha_cierre',
            'txt_cn_pla_salario_min', 'txt_cn_pla_salario_max', 'ddl_cn_pla_responsable'
        ].forEach(function(id) {
            agregar_asterisco_campo_obligatorio(id);
        });

        // Validación jQuery Validate
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
                if ($el.hasClass('select2-hidden-accessible'))
                    $el.next('.select2-container').find('.select2-selection').addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function(element) {
                var $el = $(element);
                $el.removeClass('is-invalid').addClass('is-valid');
                if ($el.hasClass('select2-hidden-accessible'))
                    $el.next('.select2-container').find('.select2-selection').removeClass('is-invalid').addClass('is-valid');
            },
            errorPlacement: function(error, element) {
                if (element.hasClass('select2-hidden-accessible'))
                    error.insertAfter(element.next('.select2-container'));
                else
                    error.insertAfter(element);
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

<!-- Para los navs del menu -->
<link rel="stylesheet" href="../assets/css/css-navs-menus.css">

<style>
    /* Hace que la fila brille un poco al pasar el mouse */
    .table-hover tbody tr:hover {
        background-color: #fbfbfb !important;
    }

    /* Quita el borde superior de la primera fila para que encaje en el rounded */
    .table tbody tr:first-child td {
        border-top: 0;
    }
</style>