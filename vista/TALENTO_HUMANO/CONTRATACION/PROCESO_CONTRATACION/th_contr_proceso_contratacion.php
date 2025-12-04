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
function formatDateToInput(dateStr) {
    if (!dateStr) return '';
    // casos: SQL suele devolver "YYYY-MM-DD HH:MM:SS" o con zona, intentamos normalizar
    // quitamos milisegundos y zona si los hay
    dateStr = dateStr.replace('.000', '').trim();
    // si contiene espacio entre fecha y hora -> convertir a T
    if (dateStr.indexOf(' ') !== -1) {
        return dateStr.slice(0, 16).replace(' ', 'T');
    }
    // si ya contiene T
    if (dateStr.indexOf('T') !== -1) {
        return dateStr.slice(0, 16);
    }
    return dateStr;
}



function boolVal(val) {
    // normalizamos 1/0 / true/false / 'true' / 'false'
    return (val === 1 || val === '1' || val === true || val === 'true') ? true : false;
}


function guardar_plaza() {

    Swal.fire({
        title: '¿Esta seguro de crear la plaza?',
        text: '',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, completar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            localStorage.clear(); // o removeItem('plaza_id')
            Swal.fire('', 'Plaza creada con éxito.', 'success').then(function() {
                window.location.href =
                    '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_plazas';
            });
        }
    });
}


$(document).ready(function() {

    // #########################
    // Coloca esto en tu hoja principal (antes de usar las funciones cargar_requisitos/cargar_etapas_proceso)
    // #########################

    var tbl_requisitos = null;
    var tbl_etapas_proceso = null;

    // Helper para obtener idPlaza seguro
    function obtenerIdPlaza() {
        var id = Number(localStorage.getItem('plaza_id'));
        return (id && id > 0) ? id : '';
    }

    // -------------------------
    // Evento personalizado: cuando el modal notifique que hubo un cambio
    // -------------------------
    $(document).on('plaza:actualizada', function(e, detalle) {
        var idPlaza = (detalle && detalle.idPlaza) ? Number(detalle.idPlaza) : obtenerIdPlaza();
        if (!idPlaza) return;

        // REQUISITOS
        if (tbl_requisitos && tbl_requisitos.ajax && typeof tbl_requisitos.ajax.reload === 'function') {
            tbl_requisitos.ajax.reload(null, false);
        } else {
            // Si no existe la instancia, inicializarla (tu función debe existir en global)
            if (typeof cargar_requisitos === 'function') cargar_requisitos(idPlaza);
        }

        // ETAPAS
        if (tbl_etapas_proceso && tbl_etapas_proceso.ajax && typeof tbl_etapas_proceso.ajax.reload ===
            'function') {
            tbl_etapas_proceso.ajax.reload(null, false);
        } else {
            if (typeof cargar_etapas === 'function') cargar_etapas(idPlaza);
        }
    });

    // -------------------------
    // Fallback: escuchar cierre de modales (Bootstrap)
    // -------------------------
    $('#modal_etapa_proceso').on('hidden.bs.modal', function() {
        var idPlaza = obtenerIdPlaza();
        if (!idPlaza) return;

        if (tbl_etapas_proceso && tbl_etapas_proceso.ajax && typeof tbl_etapas_proceso.ajax.reload ===
            'function') {
            tbl_etapas_proceso.ajax.reload(null, false);
        } else {
            if (typeof cargar_etapas === 'function') cargar_etapas(idPlaza);
        }
    });

    $('#modal_requisito').on('hidden.bs.modal', function() {
        var idPlaza = obtenerIdPlaza();
        if (!idPlaza) return;

        if (tbl_requisitos && tbl_requisitos.ajax && typeof tbl_requisitos.ajax.reload === 'function') {
            tbl_requisitos.ajax.reload(null, false);
        } else {
            if (typeof cargar_requisitos === 'function') cargar_requisitos(idPlaza);
        }
    });




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
    $('#txt_th_pla_fecha_publicacion, #txt_th_pla_fecha_cierre').on('change', function() {
        validarFechas();
    });

    function validarSalarios() {
        const salarioMinStr = $('#txt_th_pla_salario_min').val();
        const salarioMaxStr = $('#txt_th_pla_salario_max').val();

        if (!salarioMinStr || !salarioMaxStr) return;

        const salarioMin = parseFloat(salarioMinStr);
        const salarioMax = parseFloat(salarioMaxStr);

        // Salario mínimo no puede ser negativo
        if (salarioMin < 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Valor inválido',
                text: 'El salario mínimo no puede ser negativo.',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#3085d6'
            }).then(() => {
                $('#txt_th_pla_salario_min').val('');
                $('#txt_th_pla_salario_min').focus();
            });
            return false;
        }

        // Salario máximo no puede ser negativo
        if (salarioMax < 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Valor inválido',
                text: 'El salario máximo no puede ser negativo.',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#3085d6'
            }).then(() => {
                $('#txt_th_pla_salario_max').val('');
                $('#txt_th_pla_salario_max').focus();
            });
            return false;
        }

        // Validar que el salario mínimo no sea mayor al máximo
        if (salarioMin > salarioMax) {
            Swal.fire({
                icon: 'error',
                title: 'Rango de salarios incorrecto',
                text: 'El salario mínimo no puede ser mayor que el salario máximo.',
                confirmButtonText: 'Corregir',
                confirmButtonColor: '#d33'
            }).then(() => {
                $('#txt_th_pla_salario_min').val('');
                $('#txt_th_pla_salario_min').focus();
            });
            return false;
        }

        return true;
    }

    // Ejecutar validación cada vez que cambie uno de los salarios
    $('#txt_th_pla_salario_min, #txt_th_pla_salario_max').on('change', function() {
        validarSalarios();
    });


    $('#btn_eliminar_todo').on('click', function() {

        Swal.fire({
            title: '¿Eliminar todo?',
            text: 'Esto limpiará todo el almacenamiento local y recargará la página.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                localStorage.clear(); // o removeItem('plaza_id')
                location.reload(); // recargar página
            }
        });

    });
    let idPlaza = Number(localStorage.getItem('plaza_id'));
    let idPlazaCargo = Number(localStorage.getItem('plaza_cargo_id'));
    if (idPlaza > 0) {
        cargar_plaza(idPlaza);
        cargar_requisitos(idPlaza);
        cargar_etapas(idPlaza);
    }

    if (idPlazaCargo > 0) {
        cargar_asignacion(idPlazaCargo);

    }

    function cargar_requisitos(id_plaza) {
        // Asegurarnos de que id_plaza no sea undefined
        id_plaza = id_plaza || '';

        tbl_requisitos = $('#tbl_requisitos').DataTable($.extend({}, configuracion_datatable('ID',
            'Candidato'), {
            responsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            ajax: {
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_plaza_requisitosC.php?listar_requisitos=true',
                type: 'POST',
                data: function(d) {
                    d.id = id_plaza;
                },
                dataSrc: ''
            },
            columns: [{
                    data: 'th_req_tipo',
                    render: function(data) {
                        return data ? data.replace(/_/g, ' ') : '';
                    }
                },
                {
                    data: 'th_req_descripcion'
                },
                {
                    data: 'th_req_obligatorio'
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, item) {
                        var id = item.th_req_id;
                        return `
                <button class="btn btn-danger btn-sm"
                        onclick="eliminarRequisito(${id})"
                        title="Eliminar requisito">
                    <i class="bx bx-trash"></i>
                </button>
            `;
                    }
                }
            ],
            order: [
                [1, 'desc']
            ], // Ordenar por fecha
            drawCallback: function() {
                // Activar tooltips después de cargar la tabla
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        }));
    }


    function cargar_etapas(id_plaza) {
        // Asegurarnos de que id_plaza no sea undefined
        id_plaza = id_plaza || '';

        if ($.fn.DataTable.isDataTable('#tbl_etapas_proceso')) {
            $('#tbl_etapas_proceso').DataTable().clear().destroy();
            $('#tbl_etapas_proceso tbody').empty();
        }


        tbl_etapas_proceso = $('#tbl_etapas_proceso').DataTable($.extend({}, configuracion_datatable('ID',
            'Candidato'), {
            responsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            ajax: {
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_plaza_etapas_procesoC.php?listar_etapas=true',
                type: 'POST',
                data: function(d) {
                    d.id = id_plaza;
                },
                dataSrc: ''
            },
            columns: [{
                    data: 'th_etapa_nombre'
                },
                {
                    data: 'th_etapa_tipo',
                    render: function(data) {
                        return data ? data.replace(/_/g, ' ') : '';
                    }
                },
                {
                    data: 'th_etapa_obligatoria'
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, item) {
                        var id = item.th_pla_eta_id;
                        return `
                <button class="btn btn-danger btn-sm"
                        onclick="eliminarEtapa(${id})"
                        title="Eliminar Etapa">
                    <i class="bx bx-trash"></i>
                </button>
            `;
                    }
                }
            ],
            order: [
                [1, 'desc']
            ], // Ordenar por fecha
            drawCallback: function() {
                // Activar tooltips después de cargar la tabla
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        }));
    }



    smartwizardFormularios('smartwizard_seleccion');
    cargar_selects2();

    function cargar_selects2() {
        var url_plazas = '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_plazasC.php?buscar_todas=true';
        cargar_select2_url('ddl_plaza', url_plazas);
        cargar_select2_url('ddl_plaza_etapa', url_plazas);

        url_horariosC = '../controlador/TALENTO_HUMANO/th_horariosC.php?buscar=true';
        cargar_select2_url('ddl_horario', url_horariosC);

        var url_cargos = '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargosC.php?buscar=true';
        cargar_select2_url('ddl_cargo', url_cargos);

        url_personasC = '../controlador/TALENTO_HUMANO/th_personasC.php?buscar=true';
        cargar_select2_url('ddl_th_pla_responsable', url_personasC);

    }

    function cargar_tipos_req() {
        var tipos = ['Formación', 'Experiencia', 'Certificado', 'Habilidad', 'Otro'];
        tipos.forEach(function(t) {
            $('#ddl_th_req_tipo').append($('<option>', {
                value: t,
                text: t
            }));
        });
    }
    cargar_tipos_req();

    // variable opcional para saber qué botón disparó la acción
    var lastAction = '';

    function smartwizardFormularios(formulario) {
        var $sw = $('#' + formulario);

        var btnSiguiente = $('<button></button>').text('Siguiente').addClass('btn btn-info').on('click',
            function() {
                lastAction = 'next';

                // obtener índice del paso actual (0-based) usando el nav activo
                var $navItems = $sw.find('.nav li');
                var $activeNav = $sw.find('.nav li .nav-link.active').closest('li');
                var currentIndex = $navItems.index($activeNav);
                if (currentIndex < 0) {
                    currentIndex = 0;
                }

                var totalSteps = $navItems.length;

                // Si estamos en el último paso, no avanzar más
                if (currentIndex === totalSteps - 1) {
                    return false;
                } else {
                    // SmartWizard ejecutará leaveStep antes de avanzar
                    $sw.smartWizard("next");
                    return true;
                }
            });

        var btnAtras = $('<button></button>').text('Atras').addClass('btn btn-info').on('click', function() {
            lastAction = 'prev';
            $('#' + formulario).smartWizard("prev");
            return true;
        });

        // VALIDACIÓN: prevenir salida del paso si la validación falla
        $sw.on("leaveStep", function(e, anchorObject, stepIndex, stepDirection, stepPosition) {
            console.log('Saliendo del paso index:', stepIndex, 'dirección:', stepDirection,
                'lastAction:', lastAction);

            var paneId = $('#tab_content_smart .tab-pane').eq(stepIndex).attr('id');
            console.log('ID del paso que se deja:', paneId);

            // Solo validar si vamos hacia adelante (next)
            if (stepDirection === 'forward') {

                if (paneId == "paso-1") {
                    // Validar formulario de plaza
                    if (!$("#form_plaza").valid()) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Validación',
                            text: 'Por favor complete todos los campos requeridos del formulario de Plaza'
                        });
                        return false; // Bloquea el avance
                    }

                    var params = ParametrosPlaza();
                    insertar_plaza(params);

                } else if (paneId == "paso-2") {
                    // Validar formulario de plaza cargo
                    if (!$("#form_plaza_cargo").valid()) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Validación',
                            text: 'Por favor complete todos los campos requeridos del formulario de Plaza Cargo'
                        });
                        return false; // Bloquea el avance
                    }

                    var params = ParametrosPC();
                    insertar_pc(params);

                } else if (paneId == "paso-3") {
                    // Agregar validación para requisitos si es necesario
                    console.log("Validando requisitos");
                    // Ejemplo:
                    // if (!validarRequisitos()) {
                    //     Swal.fire({
                    //         icon: 'warning',
                    //         title: 'Validación',
                    //         text: 'Por favor complete los requisitos'
                    //     });
                    //     return false;
                    // }

                } else if (paneId == "paso-4") {
                    // Agregar validación para etapas si es necesario
                    console.log("Validando etapas");
                }
            }

            // Si llegamos aquí, permitir el avance
            return true;
        });

        // escucha cuando un paso se muestra
        $sw.on("showStep", function(e, anchorObject, stepIndex, stepPosition, stepDirection) {
            if (stepIndex == 0) {
                console.log("Mostrando plaza");
            } else if (stepIndex == 1) {
                console.log("Mostrando cargo_plaza");
            } else if (stepIndex == 2) {
                console.log("Mostrando requisitos");
            } else if (stepIndex == 3) {
                console.log("Mostrando etapas");
            }
        });

        // Inicializa SmartWizard
        $sw.smartWizard({
            selected: 0,
            theme: 'dots',
            transition: {
                animation: 'slide-horizontal'
            },
            toolbarSettings: {
                toolbarPosition: '',
                toolbarExtraButtons: [btnAtras, btnSiguiente],
                showNextButton: false,
                showPreviousButton: false,
            },
        });

    }



});

function smartwizardFormularios(formulario) {
    var btnSiguiente = $('<button></button>').text('Siguiente').addClass('btn btn-info').on('click', function() {
        $('#' + formulario).smartWizard("next");
        return true;
    });
    var btnAtras = $('<button></button>').text('Atras').addClass('btn btn-info').on('click', function() {
        $('#' + formulario).smartWizard("prev");
        return true;
    });

    $('#' + formulario).on("showStep", function(e, anchorObject, stepNumber, stepDirection, stepPosition) {
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
    $('#' + formulario).smartWizard({
        selected: 0,
        theme: 'dots',
        transition: {
            animation: 'slide-horizontal',
        },
        toolbarSettings: {
            toolbarPosition: '',
            toolbarExtraButtons: [btnAtras, btnSiguiente],
            showNextButton: false,
            showPreviousButton: false,
        },
    });
}
</script>
<!--Crud para plaza -->
<script>
function ParametrosPlaza() {
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
        'ddl_th_pla_responsable': $('#ddl_th_pla_responsable').val(),
        'chk_th_pla_prioridad_interna': $('#chk_th_pla_prioridad_interna').is(':checked') ? 1 : 0,
        'chk_th_pla_requiere_documentos': $('#chk_th_pla_requiere_documentos').is(':checked') ? 1 : 0,
        'txt_th_pla_descripcion': $('#txt_th_pla_descripcion').val(),
        'txt_th_pla_observaciones': $('#txt_th_pla_observaciones').val()
    };
}

function insertar_plaza(parametros) {
    $.ajax({
        data: {
            parametros: parametros
        },
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_plazasC.php?insertar_editar=true',
        type: 'post',
        dataType: 'json',
        success: function(res) {
            if (res > 0) {
                Swal.fire('', 'Plaza creada con éxito.', 'success').then(function() {
                    localStorage.setItem('plaza_id', res);
                    cargar_plaza(res);
                });
            } else if (res == -2) {
                Swal.fire('', res.msg || 'Error al guardar plaza.', 'error');
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


function cargar_plaza(id) {
    $.ajax({
        data: {
            id: id
        },
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
            $('#txt_th_pla_fecha_publicacion').val(formatDateToInput(r
                .th_pla_fecha_publicacion));
            $('#txt_th_pla_fecha_cierre').val(formatDateToInput(r.th_pla_fecha_cierre));
            $('#txt_th_pla_jornada_id').val(r.th_pla_jornada_id);
            $('#txt_th_pla_salario_min').val(r.th_pla_salario_min);
            $('#txt_th_pla_salario_max').val(r.th_pla_salario_max);
            $('#txt_th_pla_tiempo_contrato').val(r.th_pla_tiempo_contrato);
            $('#chk_th_pla_prioridad_interna').prop('checked', boolVal(r
                .th_pla_prioridad_interna));
            $('#chk_th_pla_requiere_documentos').prop('checked', boolVal(r
                .th_pla_requiere_documentos));
            $('#ddl_th_pla_responsable').val(r.th_pla_responsable_persona_id);
            $('#txt_th_pla_observaciones').val(r.th_pla_observaciones);
            $('#txt_th_pla_fecha_creacion').val(formatDateToInput(r.th_pla_fecha_creacion));
            $('#txt_th_pla_fecha_modificacion').val(formatDateToInput(r
                .th_pla_fecha_modificacion));
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

            $('#ddl_plaza').append($('<option>', {
                value: r._id,
                text: r.th_pla_titulo,
                selected: true
            }));
            $('#ddl_plaza_etapa').append($('<option>', {
                value: r._id,
                text: r.th_pla_titulo,
                selected: true
            }));
            $('#ddl_th_pla_responsable').append($('<option>', {
                value: r.per_id,
                text: r.per_cedula ? r.per_cedula : "" + " - " + r.per_nombre_completo,
                selected: true
            }));

        },
        error: function(err) {
            console.error(err);
            alert('Error al cargar la plaza (revisar consola).');
        }
    });
}
</script>
<script>
function ParametrosPC() {
    return {
        'txt_th_pc_id': $('#txt_th_pc_id').val() || '',
        'th_pla_id': $('#ddl_plaza').val(),
        'th_car_id': $('#ddl_cargo').val(),
        'th_pc_cantidad': $('#txt_th_pc_cantidad').val() || 1,
        'th_pc_salario_ofertado': $('#txt_th_pc_salario_ofertado').val() || null,
        'th_pc_estado': 1
    };
}

function insertar_pc(parametros) {
    $.ajax({
        data: {
            parametros: parametros
        },
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_plaza_cargoC.php?insertar_editar=true',
        type: 'post',
        dataType: 'json',
        success: function(res) {
            if (res > 0) {
                Swal.fire('', 'Asignación creada con éxito.', 'success').then(function() {
                    localStorage.setItem('plaza_cargo_id', res);
                    cargar_asignacion(res);
                });
            } else if (res == -2) {
                Swal.fire('', 'Ya existe una asignación activa para esa Plaza y Cargo.',
                    'warning');
            } else {
                Swal.fire('', res.msg || 'Error al guardar asignación.', 'error');
            }
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            Swal.fire('', 'Error: ' + xhr.responseText, 'error');
        }
    });
}


function cargar_asignacion(id) {
    $.ajax({
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_plaza_cargoC.php?listar=true',
        type: 'post',
        dataType: 'json',
        data: {
            id: id
        },
        success: function(response) {
            if (!response || !response[0]) return;
            var r = response[0];

            // id interno
            $('#txt_th_pc_id').val(r.th_pc_id || r._id || '');

            // Cantidad y salario
            $('#txt_th_pc_cantidad').val(r.th_pc_cantidad || r.cantidad || '');
            $('#txt_th_pc_salario_ofertado').val(r.th_pc_salario_ofertado || r
                .salario_ofertado || '');

            $('#ddl_plaza').append($('<option>', {
                value: r.plaza_id,
                text: r.plaza_titulo,
                selected: true
            }));

            $('#ddl_cargo').append($('<option>', {
                value: r.cargo_id,
                text: r.cargo_nombre,
                selected: true
            }));

        },
        error: function(err) {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al cargar la asignación (revisar consola).'
            });
        }
    });
}
</script>
<script>
function abrir_modal_requisito() {

    var modal = new bootstrap.Modal(
        document.getElementById('modal_requisito'), {
            backdrop: 'static',
            keyboard: false
        }
    );

    let idPlaza = Number(localStorage.getItem('plaza_id'));
    if (idPlaza > 0) {
        cargar_requisitos(idPlaza);
    }

    modal.show();
}

function eliminarRequisito(id) {
    if (!id) {
        console.error("ID no encontrado");
        return;
    }

    Swal.fire({
        title: '¿Eliminar requisito?',
        text: 'Se eliminará del listado de la plaza.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_plaza_requisitosC.php?eliminar=true',
                type: 'POST',
                data: {
                    id: id
                },
                dataType: 'json',

                success: function(resp) {
                    if (resp == 1 || resp === true) {
                        Swal.fire('', 'Requisito eliminado.', 'success');
                        // recargar DataTable}

                        $('#tbl_requisitos').DataTable().ajax.reload(null, false);
                    } else {
                        Swal.fire('', 'No se pudo eliminar.', 'error');
                    }
                },

                error: function(err) {
                    console.error(err);
                    Swal.fire('', 'Error en el servidor.', 'error');
                }
            });

        }

    });
}
</script>

<script>
function abrir_modal_etapa_proceso() {

    var modal = new bootstrap.Modal(
        document.getElementById('modal_etapa_proceso'), {
            backdrop: 'static',
            keyboard: false
        }
    );
    let idPlaza = Number(localStorage.getItem('plaza_id'));
    if (idPlaza > 0) {
        cargar_etapas_proceso(idPlaza);
    }

    modal.show();
}

function eliminarEtapa(id) {
    if (!id) {
        console.error("ID no encontrado");
        return;
    }

    Swal.fire({
        title: '¿Eliminar requisito?',
        text: 'Se eliminará del listado de la plaza.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_plaza_etapas_procesoC.php?eliminar=true',
                type: 'POST',
                data: {
                    id: id
                },
                dataType: 'json',

                success: function(resp) {
                    if (resp == 1 || resp === true) {
                        Swal.fire('', 'Requisito eliminado.', 'success');

                        // recargar DataTable
                        $('#tbl_etapas_proceso').DataTable().ajax.reload(null, false);
                    } else {
                        Swal.fire('', 'No se pudo eliminar.', 'error');
                    }
                },

                error: function(err) {
                    console.error(err);
                    Swal.fire('', 'Error en el servidor.', 'error');
                }
            });

        }

    });
}
</script>

<style>
/* Mostrar solo el tab-pane activo */
#tab_content_smart .tab-pane {
    display: none;
    /* ocultar todos por defecto */
    height: auto !important;
    max-height: none !important;
    overflow: visible !important;
    box-sizing: border-box;
}

/* El pane que tiene la clase .active se muestra */
#tab_content_smart .tab-pane.active {
    display: block;
    /* mostrar solo el activo */
}

/* Asegurar que el contenedor del wizard no cree scroll interno */
#smartwizard_seleccion,
#smartwizard_seleccion>.tab-content {
    overflow: visible !important;
    height: auto !important;
}

/* Reafirmar tablas y input-groups sin scroll interno */
.table-responsive {
    max-height: none !important;
    overflow: visible !important;
}

.input-group {
    flex-wrap: nowrap;
}
</style>



<div class="page-wrapper">
    <div class="page-content">

        <!--breadcrumb-->
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
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">
                        <div class="row">
                            <div class="col-6">
                                <div class="card-title d-flex align-items-center">
                                    <div><i class="bx bxs-briefcase me-1 font-22 text-primary"></i></div>
                                    <h5 class="mb-0 text-primary">Proceso de Selección de Personal</h5>
                                </div>
                            </div>
                            <div class="col-6 text-end">
                                <button class="btn btn-outline-primary">
                                    <i class='bx bx-list-ol'></i> Historial
                                </button>

                                <button class="btn btn-outline-danger" id="btn_eliminar_todo">
                                    <i class='bx bx-trash'></i> Eliminar Todo
                                </button>
                            </div>

                        </div>

                        <br>

                        <div id="smartwizard_seleccion">
                            <ul class="nav">
                                <li class="nav-item">
                                    <a class="nav-link" href="#paso-1">
                                        <strong>Paso 1</strong><br>Crear Plaza
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#paso-2">
                                        <strong>Paso 2</strong><br>Asociar Plaza - Cargo
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#paso-3">
                                        <strong>Paso 3</strong><br>Crear Requisitos
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#paso-4">
                                        <strong>Paso 4</strong><br>Crear Etapas de Selección
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content" id="tab_content_smart">

                                <!-- ============================== -->
                                <!-- PASO 1: CREAR PLAZA -->
                                <!-- ============================== -->
                                <div id="paso-1" class="tab-pane" role="tabpanel">
                                    <div class="container-fluid">
                                        <div id="smartwizard_seleccion">
                                            <form id="form_plaza">
                                                <input type="hidden" id="txt_th_pla_id" name="txt_th_pla_id" value="" />

                                                <!-- Información Básica -->
                                                <div class="border-bottom border-primary border-3 mb-3 pb-2">
                                                    <h6 class="text-primary fw-bold mb-3">
                                                        <i class="bx bx-info-circle me-2"></i>Información Básica
                                                    </h6>
                                                </div>

                                                <div class="row g-2 mb-3">
                                                    <div class="col-md-12">
                                                        <label for="txt_th_pla_titulo" class="form-label fw-bold">
                                                            <i class="bx bx-id-card me-2 text-primary"></i>Título de la
                                                            Plaza
                                                        </label>
                                                        <input type="text" class="form-control" id="txt_th_pla_titulo"
                                                            name="txt_th_pla_titulo"
                                                            placeholder="Ingrese el título de la plaza"
                                                            autocomplete="off" required />
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="ddl_th_pla_tipo" class="form-label fw-bold">
                                                            <i class="bx bx-tag me-2 text-success"></i>Tipo
                                                        </label>
                                                        <select class="form-select select2-validation"
                                                            id="ddl_th_pla_tipo" name="ddl_th_pla_tipo" required>
                                                            <option value="" selected hidden>-- Seleccione --</option>
                                                            <option value="Interna">Interna</option>
                                                            <option value="Externa">Externa</option>
                                                            <option value="Mixta">Mixta</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="txt_th_pla_num_vacantes" class="form-label fw-bold">
                                                            <i class="bx bx-user-plus me-2 text-warning"></i>Número de
                                                            Vacantes
                                                        </label>
                                                        <input type="number" min="1" class="form-control"
                                                            id="txt_th_pla_num_vacantes" name="txt_th_pla_num_vacantes"
                                                            placeholder="Ej: 1" required />
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="ddl_horario" class="form-label fw-bold">
                                                            <i class="bx bx-time me-2 text-info"></i>Horario
                                                        </label>
                                                        <select class="form-select select2-validation" id="ddl_horario"
                                                            name="ddl_horario" required>
                                                            <option value="" selected hidden>-- Seleccione --</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Fechas y Salarios -->
                                                <div class="border-bottom border-info border-3 mb-3 pb-2">
                                                    <h6 class="text-info fw-bold mb-3">
                                                        <i class="bx bx-calendar me-2"></i>Fechas y Salarios
                                                    </h6>
                                                </div>

                                                <div class="row g-2 mb-3">
                                                    <div class="col-md-3">
                                                        <label for="txt_th_pla_fecha_publicacion"
                                                            class="form-label fw-bold">
                                                            <i class="bx bx-calendar me-2 text-info"></i>Publicación
                                                        </label>
                                                        <input type="datetime-local" class="form-control"
                                                            id="txt_th_pla_fecha_publicacion"
                                                            name="txt_th_pla_fecha_publicacion" required />
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="txt_th_pla_fecha_cierre" class="form-label fw-bold">
                                                            <i class="bx bx-calendar-check me-2 text-danger"></i>Cierre
                                                        </label>
                                                        <input type="datetime-local" class="form-control"
                                                            id="txt_th_pla_fecha_cierre" name="txt_th_pla_fecha_cierre"
                                                            required />
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label fw-bold" for="txt_th_pla_salario_min">
                                                            <i class="bx bx-money me-2 text-success"></i>Salario Mínimo
                                                        </label>
                                                        <input type="number" step="0.01" class="form-control ps-4"
                                                            id="txt_th_pla_salario_min" name="txt_th_pla_salario_min"
                                                            placeholder="0.00" required min="0" />
                                                    </div>


                                                    <div class="col-md-3">
                                                        <label class="form-label fw-bold" for="txt_th_pla_salario_max">
                                                            <i class="bx bx-money me-2 text-success"></i>Salario Máximo
                                                        </label>
                                                        <input type="number" step="0.01" class="form-control ps-4"
                                                            id="txt_th_pla_salario_max" name="txt_th_pla_salario_max"
                                                            placeholder="0.00" required min="0" />
                                                    </div>


                                                    <div class="col-md-12">
                                                        <label for="txt_th_pla_tiempo_contrato"
                                                            class="form-label fw-bold">
                                                            <i class="bx bx-time-five me-2 text-primary"></i>Duración
                                                            del
                                                            Contrato
                                                        </label>
                                                        <input type="text" class="form-control"
                                                            id="txt_th_pla_tiempo_contrato"
                                                            name="txt_th_pla_tiempo_contrato"
                                                            placeholder="Ej: Indefinido, 6 meses, 1 año" required />
                                                    </div>
                                                </div>

                                                <!-- Configuración -->
                                                <div class="border-bottom border-warning border-3 mb-3 pb-2">
                                                    <h6 class="text-warning fw-bold mb-3">
                                                        <i class="bx bx-cog me-2"></i>Configuración
                                                    </h6>
                                                </div>

                                                <div class="row g-2 mb-3">

                                                    <div class="col-md-4">
                                                        <label for="ddl_th_pla_responsable" class="form-label fw-bold">
                                                            <i class="bx bx-user me-2 text-primary"></i> Responsable
                                                        </label>

                                                        <select id="ddl_th_pla_responsable"
                                                            name="ddl_th_pla_responsable"
                                                            class="form-select select2-validation" required>
                                                            <option value="" selected hidden>-- Seleccione el
                                                                responsable --</option>
                                                        </select>
                                                    </div>


                                                    <div class="col-md-4">
                                                        <label class="form-label fw-bold d-block">
                                                            <i
                                                                class="bx bx-check-shield me-2 text-primary"></i>Prioridad
                                                            Interna
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="chk_th_pla_prioridad_interna" />
                                                            <label class="form-check-label"
                                                                for="chk_th_pla_prioridad_interna">
                                                                Activar prioridad interna
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label fw-bold d-block">
                                                            <i class="bx bx-folder-open me-2 text-primary"></i>Requiere
                                                            Documentos
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="chk_th_pla_requiere_documentos" />
                                                            <label class="form-check-label"
                                                                for="chk_th_pla_requiere_documentos">
                                                                Requiere documentación
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Descripción -->
                                                <div class="border-bottom border-secondary border-3 mb-3 pb-2">
                                                    <h6 class="text-secondary fw-bold mb-3">
                                                        <i class="bx bx-file-blank me-2"></i>Descripción
                                                    </h6>
                                                </div>

                                                <div class="row g-2 mb-3">
                                                    <div class="col-md-6">
                                                        <label for="txt_th_pla_descripcion" class="form-label fw-bold">
                                                            <i class="bx bx-file me-2 text-primary"></i>Descripción del
                                                            Puesto
                                                        </label>
                                                        <textarea class="form-control" id="txt_th_pla_descripcion"
                                                            name="txt_th_pla_descripcion" rows="3"
                                                            placeholder="Describa responsabilidades y funciones..."
                                                            required></textarea>
                                                        <div class="form-text">Visible para postulantes</div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="txt_th_pla_observaciones"
                                                            class="form-label fw-bold">
                                                            <i
                                                                class="bx bx-comment-detail me-2 text-warning"></i>Observaciones
                                                        </label>
                                                        <textarea class="form-control" id="txt_th_pla_observaciones"
                                                            name="txt_th_pla_observaciones" rows="3"
                                                            placeholder="Notas internas..." required></textarea>
                                                        <div class="form-text">Solo visible internamente</div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                </div>

                                <!-- ============================== -->
                                <!-- PASO 2: ASOCIAR PLAZA - CARGO -->
                                <!-- ============================== -->
                                <div id="paso-2" class="tab-pane" role="tabpanel">
                                    <div class="container-fluid">
                                        <div id="smartwizard_seleccion">
                                            <form id="form_plaza_cargo">
                                                <input type="hidden" id="txt_th_pc_id" name="txt_th_pc_id" value="" />

                                                <div class="row g-3">

                                                    <div class="col-md-6">
                                                        <label for="ddl_plaza" class="form-label fw-bold">
                                                            <i class="bx bx-briefcase me-2 text-primary"></i> Plaza
                                                        </label>
                                                        <select class="form-select select2-validation" id="ddl_plaza"
                                                            name="ddl_plaza" required disabled>
                                                            <option value="" selected hidden>-- Seleccione Plaza --
                                                            </option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="ddl_cargo" class="form-label fw-bold">
                                                            <i class="bx bx-id-card me-2 text-success"></i> Cargo
                                                        </label>
                                                        <select class="form-select select2-validation" id="ddl_cargo"
                                                            name="ddl_cargo" required>
                                                            <option value="" selected hidden>-- Seleccione Cargo --
                                                            </option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="txt_th_pc_cantidad" class="form-label fw-bold">
                                                            <i class="bx bx-layer me-2 text-warning"></i> Cantidad
                                                        </label>
                                                        <input type="number" min="1" class="form-control"
                                                            id="txt_th_pc_cantidad" name="txt_th_pc_cantidad"
                                                            placeholder="Ej: 1" required />
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="txt_th_pc_salario_ofertado"
                                                            class="form-label fw-bold">
                                                            <i class="bx bx-money me-2 text-success"></i> Salario
                                                            ofertado
                                                        </label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">$</span>
                                                            <input type="number" step="0.01" class="form-control"
                                                                id="txt_th_pc_salario_ofertado"
                                                                name="txt_th_pc_salario_ofertado" placeholder="0.00"
                                                                required />
                                                        </div>
                                                    </div>

                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>

                                <!-- ============================== -->
                                <!-- PASO 3: CREAR REQUISITOS -->
                                <!-- ============================== -->

                                <div id="paso-3" class="tab-pane" role="tabpanel">
                                    <div class="container-fluid">
                                        <div id="smartwizard_seleccion">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Plaza asociada</label>
                                                <div class="input-group">
                                                    <input type="text" id="txt_th_pla_titulo3" class="form-control"
                                                        readonly placeholder="Plaza asociada..." />
                                                    <button type="button" class="btn btn-success"
                                                        onclick="abrir_modal_requisito()">
                                                        <i class="bx bx-plus me-1"></i> Nuevo
                                                    </button>
                                                    <?php include_once('../vista/TALENTO_HUMANO/CONTRATACION/REQUISITOS/th_contr_modal_requisitos.php'); ?>
                                                </div>
                                            </div>
                                            <section class="content pt-2">
                                                <div class="container-fluid">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped responsive "
                                                            id="tbl_requisitos" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>Tipo</th>
                                                                    <th>Descripción</th>
                                                                    <th>Ponderación</th>
                                                                    <th>Acción</th>
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

                                <!-- ============================== -->
                                <!-- PASO 4: CREAR ETAPAS DE SELECCIÓN -->
                                <!-- ============================== -->
                                <div id="paso-4" class="tab-pane" role="tabpanel">
                                    <div class="container-fluid">
                                        <div id="smartwizard_seleccion">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Plaza asociada</label>
                                                <div class="input-group">
                                                    <input type="text" id="txt_th_pla_titulo4" class="form-control"
                                                        readonly placeholder="Plaza asociada..." />
                                                    <button type="button" class="btn btn-success"
                                                        onclick="abrir_modal_etapa_proceso()">
                                                        <i class="bx bx-plus me-1"></i> Nuevo
                                                    </button>
                                                    <?php include_once('../vista/TALENTO_HUMANO/CONTRATACION/ETAPAS_PROCESO/th_contr_modal_etapas_proceso.php'); ?>
                                                </div>
                                            </div>
                                            <section class="content pt-2">
                                                <div class="container-fluid">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped responsive "
                                                            id="tbl_etapas_proceso" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>Nombre</th>
                                                                    <th>Tipo</th>
                                                                    <th>Obligatoria</th>
                                                                    <th>Acción</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="">

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div><!-- /.container-fluid -->
                                        </div><!-- /.container-fluid -->
                                    </div><!-- /.container-fluid -->
                                    </section>

                                    <div class="modal-footer pt-4">
                                        <button class="btn btn-primary btn-sm px-3 m-1" onclick="guardar_plaza()"
                                            type="button">
                                            <i class='bx bx-save'></i> Guardar Proceso Completo
                                        </button>
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
$(function() {
    $("#form_plaza").validate({
        ignore: ":hidden:not(.select2-hidden-accessible)",

        rules: {
            txt_th_pla_titulo: {
                required: true,
                maxlength: 150
            },
            ddl_th_pla_tipo: {
                required: true
            },
            txt_th_pla_num_vacantes: {
                required: true,
                min: 1,
            },
            ddl_horario: {
                required: true
            },
            txt_th_pla_fecha_publicacion: {
                required: true
            },
            txt_th_pla_fecha_cierre: {
                required: true
            },
            txt_th_pla_salario_min: {
                number: true,
                min: 0
            },
            txt_th_pla_salario_max: {
                number: true,
                min: 0
            }
        },

        messages: {
            txt_th_pla_titulo: {
                required: "Por favor ingrese el título de la plaza",
                maxlength: "Máximo 150 caracteres"
            },
            ddl_th_pla_tipo: {
                required: "Seleccione el tipo de plaza"
            },
            txt_th_pla_num_vacantes: {
                required: "Ingrese número de vacantes",
                min: "La cantidad mínima es 1",
            },
            ddl_horario: {
                required: "Seleccione un horario"
            },
            txt_th_pla_fecha_publicacion: {},
            txt_th_pla_fecha_cierre: {},
            txt_th_pla_salario_min: {
                number: "Ingrese un valor numérico válido",
                min: "El salario mínimo no puede ser negativo"
            },
            txt_th_pla_salario_max: {
                number: "Ingrese un valor numérico válido",
                min: "El salario máximo no puede ser negativo"
            }
        },


        errorClass: "text-danger error", // clase para el label de error
        validClass: "is-valid",
        errorElement: "div",

        highlight: function(element) {
            var $el = $(element);
            if ($el.hasClass("select2-hidden-accessible")) {
                $el.next(".select2-container").find(".select2-selection").addClass("is-invalid")
                    .removeClass("is-valid");
            } else {
                $el.addClass("is-invalid").removeClass("is-valid");
            }
        },

        unhighlight: function(element) {
            var $el = $(element);
            if ($el.hasClass("select2-hidden-accessible")) {
                $el.next(".select2-container").find(".select2-selection").addClass("is-valid")
                    .removeClass("is-invalid");
            } else {
                $el.removeClass("is-invalid").addClass("is-valid");
            }
        },

        errorPlacement: function(error, element) {
            if (element.hasClass("select2-hidden-accessible")) {
                error.insertAfter(element.next('.select2-container'));
            } else {
                error.insertAfter(element);
            }
        },

        // para depuración: muestra los errores en consola también
        showErrors: function(errorMap, errorList) {
            console.log("Errores validate:", errorMap, errorList);
            this.defaultShowErrors();
        },

        submitHandler: function(form) {
            // evita enviar si quieres manejarlo con JS
            return false;
        }
    });

    // Si usas select2, inicialízalo primero y luego fuerza validación en change
    // Ejemplo:
    // $(".select2-validation").select2({ placeholder: "-- Seleccione --", allowClear: true, width: '100%' });
    $(".select2-validation").on("change.select2Validation", function() {
        $(this).valid();
    });

    // Limpieza de placeholders que el plugin haya copiado al value
    $("#form_plaza input, #form_plaza textarea").each(function() {
        var $i = $(this);
        var ph = $i.attr("placeholder") || "";
        if ($i.val() && $.trim($i.val()) === $.trim(ph)) {
            $i.val("");
        }
    });

    // Botón avanzar: solo ejemplo
    $("#btn_avanzar").on("click", function(e) {
        if (!$("#form_plaza").valid()) {
            Swal.fire({
                icon: 'warning',
                title: 'Validación',
                text: 'Por favor complete todos los campos requeridos del formulario de Plaza'
            });
            return false;
        }
        // continuar...
    });

}); // doc ready
</script>

<script>
$("#form_plaza_cargo").validate({
    ignore: ":hidden:not(.select2-hidden-accessible)",

    rules: {
        ddl_plaza: {
            required: true
        },
        ddl_cargo: {
            required: true
        },
        txt_th_pc_cantidad: {
            required: true,
            min: 1,
            digits: true
        },
        txt_th_pc_salario_ofertado: {
            required: true,
            number: true,
            min: 0
        }
    },

    messages: {
        ddl_plaza: {
            required: "Seleccione una plaza"
        },
        ddl_cargo: {
            required: "Seleccione un cargo"
        },
        txt_th_pc_cantidad: {
            required: "Ingrese la cantidad",
            min: "La cantidad mínima es 1",
            digits: "Debe ingresar solo números enteros"
        },
        txt_th_pc_salario_ofertado: {
            required: "Ingrese el salario ofertado",
            number: "Debe ingresar un valor numérico válido",
            min: "El salario no puede ser negativo"
        }
    }
});
</script>