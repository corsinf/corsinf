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
function eliminar_cargo_requisito(id) {
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
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_union_cargo_requisitoC.php?eliminar=true',
                type: 'POST',
                data: {
                    id: id
                },
                dataType: 'json',

                success: function(resp) {
                    if (resp == 1 || resp === true) {
                        Swal.fire('', 'Requisito del Cargo eliminado.', 'success');

                        // recargar DataTable
                        $('#tbl_req_detalles').DataTable().ajax.reload(null, false);
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


$(document).ready(function() {
    <?php if (isset($_GET['_id'])) { ?>
    cargar_cargo(<?= $_id ?>);
    listar_aspecto_cargo(<?= $_id ?>);
    cargar_competencias(<?= $_id ?>);

    <?php } ?>

    cargar_selects2();


    function cargar_selects2() {
        url_departamentosC = '../controlador/TALENTO_HUMANO/th_departamentosC.php?buscar=true';
        cargar_select2_url('ddl_departamentos', url_departamentosC);
        url_nivelesC = '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_niveles_cargoC.php?buscar=true';
        cargar_select2_url('ddl_niveles', url_nivelesC);
        var url_cargos = '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargosC.php?buscar=true';
        cargar_select2_url('ddl_subordinacion', url_cargos, '', '#modal_aspectos_intrinsecos');
        cargar_select2_url('ddl_supervision', url_cargos, '', '#modal_aspectos_intrinsecos');
        cargar_select2_url('ddl_comunicaciones', url_cargos, '', '#modal_aspectos_intrinsecos');

        url_competenciasC = '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_competenciasC.php?buscar=true';
        cargar_select2_url('ddl_competencias', url_competenciasC, '', '#modal_cargo_competencia');
    }


    agregar_asterisco_campo_obligatorio('txt_th_car_nombre');

    $("#form_cargo").validate({
        ignore: [],
        rules: {
            txt_th_car_nombre: {
                required: true
            }
        },
        messages: {
            txt_th_car_nombre: {
                required: "Ingrese el nombre del cargo."
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


    function boolVal(val) {
        return (val === 1 || val === '1' || val === true || val === 'true') ? true : false;
    }

    // CARGAR DATOS DE UN CARGO EN EL FORMULARIO
    function cargar_cargo(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargosC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (!response || !response[0]) return;
                var r = response[0];

                $('#txt_th_car_id').val(r._id);
                $('#txt_th_car_nombre').val(r.nombre);
                $('#txt_th_car_descripcion').val(r.descripcion);
                $('#ddl_niveles').val(r.nivel);
                $('#chk_th_car_estado').prop('checked', boolVal(r.estado));
                $('#ddl_departamentos').append($('<option>', {
                    value: r.th_dep_id,
                    text: r.departamento,
                    selected: true
                }));
                $('#ddl_niveles').append($('<option>', {
                    value: r.th_niv_id,
                    text: r.nivel,
                    selected: true
                }));
            },
            error: function(err) {
                console.error(err);
                alert('Error al cargar el cargo (revisar consola).');
            }
        });
    }

    function nivelLabel(n) {
        // Ajusta etiquetas si quieres otro texto
        const m = {
            '1': 'Nivel 1 - Alta Dirección',
            '2': 'Nivel 2 - Gerencia',
            '3': 'Nivel 3 - Jefatura/Coordinación',
            '4': 'Nivel 4 - Supervisión',
            '5': 'Nivel 5 - Operativo/Técnico',
            '6': 'Nivel 6 - Auxiliar/Asistente'
        };
        return m[String(n)] || (n ? 'Nivel ' + n : 'No registrado');
    }

    function listar_aspecto_cargo(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_aspectos_intrinsecosC.php?listar_aspecto_cargo=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (!response || response.length === 0) {
                    mostrarAspectosVacios();
                    return;
                }

                var rows = Array.isArray(response) ? response : [response];
                cargar_aspecto_en_modal(rows[0]);
                var subordinaciones = [];
                var niveles = [];
                var supervisiones = [];
                var comunicacioness = [];
                rows.forEach(function(r) {
                    var subText = '';
                    if (r.th_carasp_subordinacion_id && r.subordinacion_cargo_nombre) {
                        subText = r.subordinacion_cargo_nombre;

                    } else if (r.th_carasp_subordinacion) {
                        subText = r.th_carasp_subordinacion;
                    } else {
                        subText = 'Sin especificar';
                    }
                    subordinaciones.push(subText);
                    niveles.push(nivelLabel(r.th_carasp_nivel_cargo));
                    var supText = '';
                    if (r.th_carasp_supervision_id && r.supervision_cargo_nombre) {
                        supText = r.supervision_cargo_nombre;

                    } else if (r.th_carasp_supervision) {
                        supText = r.th_carasp_supervision;
                    } else {
                        supText = 'Sin especificar';
                    }
                    supervisiones.push(supText);
                    var comText = '';
                    if (r.th_carasp_comunicaciones_id && r.comunicaciones_cargo_nombre) {
                        comText = r.comunicaciones_cargo_nombre;

                    } else if (r.th_carasp_comunicaciones_colaterales) {
                        comText = r.th_carasp_comunicaciones_colaterales;
                    } else {
                        comText = 'Sin especificar';
                    }
                    comunicacioness.push(comText);
                });
                $('#info_subordinacion').html(subordinaciones.join('<br>'));
                $('#info_nivel_cargo').text(niveles.join(' / '));
                $('#info_supervision').html(supervisiones.join('<br>'));
                $('#info_comunicaciones').html(comunicacioness.join('<br>'));
                $('#badge_subordinacion').html('<i class="bi bi-arrow-up"></i> Reporta a: ' +
                    subordinaciones.join(', '));
                $('#badge_nivel').html('<i class="bi bi-person-badge"></i> Nivel: ' +
                    ((rows.length === 1) ? niveles[0] : niveles.join(' / ')));
                $('#badge_supervision').html('<i class="bi bi-arrow-down"></i> Supervisa: ' +
                    supervisiones.join(', '));
                $('#badge_comunicaciones').html('<i class="bi bi-arrows"></i> Comunica con: ' +
                    comunicacioness.join(', '));
            },
            error: function(err) {
                console.error('Error al listar aspectos:', err);
                mostrarAspectosVacios();
            }
        });
    }


    function cargar_aspecto_en_modal(r) {


        $('#th_carasp_id').val(r.th_carasp_id || '');
        $('#th_car_id').val(r.th_car_id || '');
        $('#th_carasp_nivel_cargo').val(r.th_carasp_nivel_cargo || '');


        if (r.th_carasp_subordinacion_id && r.th_carasp_subordinacion_id !== null) {
            $('#chk_subordinacion_empresa').prop('checked', true).trigger('change');
            $('#ddl_subordinacion').append($('<option>', {
                value: r.th_carasp_subordinacion_id,
                text: r.subordinacion_cargo_nombre,
                selected: true
            }));
        } else {
            $('#chk_subordinacion_empresa').prop('checked', false).trigger('change');
            $('#txt_subordinacion').val(r.th_carasp_subordinacion || '');
        }
        if (r.th_carasp_supervision_id && r.th_carasp_supervision_id !== null) {
            $('#chk_supervision_empresa').prop('checked', true).trigger('change');
            $('#ddl_supervision').append($('<option>', {
                value: r.th_carasp_supervision_id,
                text: r.supervision_cargo_nombre,
                selected: true
            }));
        } else {
            $('#chk_supervision_empresa').prop('checked', false).trigger('change');
            $('#txt_supervision').val(r.th_carasp_supervision || '');
        }
        if (r.th_carasp_comunicaciones_id && r.th_carasp_comunicaciones_id !== null) {
            $('#chk_comunicaciones_empresa').prop('checked', true).trigger('change');
            $('#ddl_comunicaciones').append($('<option>', {
                value: r.th_carasp_comunicaciones_id,
                text: r.comunicaciones_cargo_nombre,
                selected: true
            }));
        } else {
            $('#chk_comunicaciones_empresa').prop('checked', false).trigger('change');
            $('#txt_comunicaciones').val(r.th_carasp_comunicaciones_colaterales || '');
        }
        $('#pnl_crear_aspecto').hide();
        $('#pnl_actualizar_aspecto').show();
    }

    function mostrarAspectosVacios() {


        $('#th_car_id').val("<?= isset($_id) ? $_id : '' ?>");
        $('#info_subordinacion').html('<em class="text-muted">No registrado</em>');
        $('#info_nivel_cargo').text('No registrado');
        $('#info_supervision').html('<em class="text-muted">No registrado</em>');
        $('#info_comunicaciones').html('<em class="text-muted">No registrado</em>');

        $('#badge_subordinacion').html('<i class="bi bi-arrow-up"></i> Reporta a: -');
        $('#badge_nivel').html('<i class="bi bi-person-badge"></i> Nivel: -');
        $('#badge_supervision').html('<i class="bi bi-arrow-down"></i> Supervisa: -');
        $('#badge_comunicaciones').html('<i class="bi bi-arrows"></i> Comunica con: -');
    }

    function abrir_modal_nuevo_aspecto(id_cargo) {
        // Limpiar formulario
        $('#form_aspectos_intrinsecos')[0].reset();
        $('#th_carasp_id').val('');
        $('#th_car_id').val(id_cargo);

        // Desmarcar todos los checkboxes
        $('#chk_subordinacion_empresa').prop('checked', false).trigger('change');
        $('#chk_supervision_empresa').prop('checked', false).trigger('change');
        $('#chk_comunicaciones_empresa').prop('checked', false).trigger('change');

        // Mostrar botones de creación
        $('#pnl_crear_aspecto').show();
        $('#pnl_actualizar_aspecto').hide();
        // Abrir modal
        var modal = new bootstrap.Modal(document.getElementById('modal_aspectos_intrinsecos'));
        modal.show();
    }

    function abrir_modal_editar_aspecto(id_cargo) {
        listar_aspecto_cargo(id_cargo);
        var modal = new bootstrap.Modal(document.getElementById('modal_aspectos_intrinsecos'));
        modal.show();
    }



});
</script>

<script type="text/javascript">
// Asegúrate de ejecutar esto dentro de $(document).ready(...) si lo pegas suelto
function editar_insertar_cargo() {
    var txt_th_car_id = $('#txt_th_car_id').val(); // hidden id
    var txt_th_car_nombre = $('#txt_th_car_nombre').val();
    var ddl_niveles = $('#ddl_niveles').val();
    var ddl_departamentos = $('#ddl_departamentos').val();
    var txt_th_car_descripcion = $('#txt_th_car_descripcion').val();

    var parametros = {
        '_id': txt_th_car_id,
        'txt_th_car_nombre': txt_th_car_nombre,
        'ddl_niveles': ddl_niveles,
        'ddl_departamentos': ddl_departamentos,
        'txt_th_car_descripcion': txt_th_car_descripcion
    };

    if ($("#form_cargo").valid()) {
        // Si es válido, proceder según si es nuevo o edición
        if (!txt_th_car_id || txt_th_car_id == '') {
            insertar_cargo(parametros);
        } else {
            editar_cargo(parametros);
        }
    }
}

function insertar_cargo(parametros) {
    $.ajax({
        data: {
            parametros: parametros
        },
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargosC.php?insertar_editar=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response == 1) {
                Swal.fire('', 'Operación realizada con éxito.', 'success').then(function() {
                    location.href =
                        '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_cargos';
                });
            } else if (response == -2) {
                // nombre duplicado
                $('#txt_th_car_nombre').addClass('is-invalid');
                // Si tienes un elemento para mostrar error:
                if ($('#error_txt_th_car_nombre').length == 0) {
                    $('#txt_th_car_nombre').after(
                        '<div id="error_txt_th_car_nombre" class="invalid-feedback">El nombre del cargo ya está en uso.</div>'
                    );
                } else {
                    $('#error_txt_th_car_nombre').text('El nombre del cargo ya está en uso.');
                }
            } else {
                Swal.fire('', response.msg || 'Error al guardar el cargo.', 'error');
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
    $('#txt_th_car_nombre').on('input', function() {
        $(this).removeClass('is-invalid');
        $('#error_txt_th_car_nombre').text('');
    });
}

function editar_cargo(parametros) {
    $.ajax({
        data: {
            parametros: parametros
        },
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargosC.php?insertar_editar=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response == 1) {
                Swal.fire('', 'Cargo actualizado con éxito.', 'success').then(function() {
                    location.href =
                        '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_cargos';
                });
            } else if (response == -2) {
                // nombre duplicado en otro registro
                $('#txt_th_car_nombre').addClass('is-invalid');
                if ($('#error_txt_th_car_nombre').length == 0) {
                    $('#txt_th_car_nombre').after(
                        '<div id="error_txt_th_car_nombre" class="invalid-feedback">El nombre del cargo ya está en uso por otro registro.</div>'
                    );
                } else {
                    $('#error_txt_th_car_nombre').text(
                        'El nombre del cargo ya está en uso por otro registro.');
                }
            } else {
                Swal.fire('', response.msg || 'Error al actualizar el cargo.', 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('Status: ' + status);
            console.error('Error: ' + error);
            console.error('XHR Response: ' + xhr.responseText);
            Swal.fire('', 'Error: ' + xhr.responseText, 'error');
        }
    });

    $('#txt_th_car_nombre').on('input', function() {
        $(this).removeClass('is-invalid');
        $('#error_txt_th_car_nombre').text('');
    });
}

function delete_cargo() {
    var id = $('#txt_th_car_id').val() || '<?= $_id ?>';
    if (!id) {
        Swal.fire('', 'ID no encontrado para eliminar.', 'warning');
        return;
    }

    Swal.fire({
        title: 'Eliminar Registro?',
        text: '¿Está seguro de eliminar este registro?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si'
    }).then((result) => {
        if (result.isConfirmed) {
            eliminar_cargo(id);
        }
    });
}

function eliminar_cargo(id) {
    $.ajax({
        data: {
            id: id
        },
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargosC.php?eliminar=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response == 1) {
                Swal.fire('Eliminado!', 'Registro eliminado.', 'success').then(function() {
                    location.href =
                        '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_cargos';
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

// Bind botones (si tu HTML ya tiene los botones con esos ids)
$(document).ready(function() {
    $('#btn_guardar_cargo').on('click', function() {
        editar_insertar_cargo();
    });
    $('#btn_editar_cargo').on('click', function() {
        editar_insertar_cargo();
    });
    $('#btn_eliminar_cargo').on('click', function() {
        delete_cargo();
    });
});
</script>

<script>
$(document).ready(function() {

    // Si existe ID cargamos para editar
    <?php if($_id != ''){ ?>
    cargar_requisito(<?= $_id ?>);
    cargar_requisitos_cargo(<?= $_id ?>);
    <?php } ?>

    function cargar_requisitos_cargo(id_cargo) {

        // Si ya existe el DataTable, lo destruimos para evitar duplicados
        if ($.fn.dataTable.isDataTable('#tbl_req_detalles')) {
            $('#tbl_req_detalles').DataTable().clear().destroy();
            $('#tbl_req_detalles').empty(); // opcional: limpia el tbody
        }

        tbl_req_detalles = $('#tbl_req_detalles').DataTable($.extend({}, configuracion_datatable('Nombre',
            'tipo',
            'fecha'), {
            responsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            ajax: {
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_union_cargo_requisitoC.php?listar=true',
                type: 'POST',
                data: function(d) {
                    d.id = id_cargo;
                },
                dataSrc: ''

            },
            columns: [{
                data: 'nombre',

            }, {
                data: 'descripcion',
                render: function(data, type, item) {
                    if (!data) return '';
                    return data.length > 25 ? data.substring(0, 25) + '...' : data;
                }
            }, {
                data: 'obligatorio',
                render: function(data, type, item) {
                    var is = (data == 1 || data === true || data === '1');
                    return `<span class="badge bg-${is ? 'danger' : 'secondary'}">${is ? 'Obligatorio' : 'Opcional'}</span>`;
                },
                className: 'text-center'
            }, {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function(data, type, item) {
                    var id = item.th_carreq_id;
                    return `
                <button class="btn btn-danger btn-sm"
                        onclick="eliminar_cargo_requisito(${id})"
                        title="Eliminar Etapa">
                    <i class="bx bx-trash"></i>
                </button>
            `;
                }
            }],
            order: [
                [0, 'asc']
            ]
        }));

    }

    // Validación del formulario
    $("#form_requisito").validate({
        ignore: [],
        rules: {
            txt_nombre: {
                required: true
            }
        },
        messages: {
            txt_nombre: {
                required: "Ingrese el nombre del requisito"
            }
        },
        highlight: r => $(r).addClass('is-invalid').removeClass('is-valid'),
        unhighlight: r => $(r).addClass('is-valid').removeClass('is-invalid'),
        submitHandler: () => false
    });
});

/* Cargar requisito para modificar */
function cargar_requisito(id) {
    $.ajax({
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_requisitosC.php?listar=true',
        type: 'post',
        data: {
            id: id
        },
        dataType: 'json',
        success: function(response) {
            if (!response || !response[0]) return;
            var r = response[0];
            $("#txt_id").val(r._id);
            $("#txt_nombre").val(r.nombre);
            $("#txt_descripcion").val(r.descripcion);
        }
    });
}

/* Guardar o editar según exista ID */
function guardar_actualizar() {
    if (!$("#form_requisito").valid()) return;

    let parametros = {
        _id: $("#txt_id").val(),
        th_car_req_nombre: $("#txt_nombre").val(),
        th_car_req_descripcion: $("#txt_descripcion").val()
    };

    $.ajax({
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_requisitosC.php?insertar_editar=true',
        type: 'post',
        data: {
            parametros: parametros
        },
        dataType: 'json',
        success: function(r) {
            if (r == 1) {
                Swal.fire("", "Registrado correctamente", "success")
                    .then(() => location.href =
                        "../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_cargo_requisitos");
            } else if (r == -2) {
                Swal.fire("Error", "Nombre ya existe", "warning");
            } else {
                Swal.fire("Error", r.msg, "error");
            }
        }
    });
}


function eliminar_requisito() {

    let id = $("#txt_id").val();
    if (id == "") return Swal.fire("", "No hay ID para eliminar", "info");
    $.ajax({
        data: {
            id: id
        },
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_requisitosC.php?eliminar=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response == 1) {
                Swal.fire('Eliminado!', 'Registro eliminado.', 'success').then(function() {
                    location.href =
                        '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_cargo_requisitos';
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


function abrir_modal_cargo_requisitos() {

    var modal = new bootstrap.Modal(
        document.getElementById('modal_cargo_requisito'), {
            backdrop: 'static',
            keyboard: false
        }
    );
    cargar_cargo_requisitos(<?= $_id ?>);

    modal.show();
}

function cargar_cargo_requisitos(id_cargo) {
    // Si select2 ya está inicializado, destruirlo
    if ($('#ddl_cargo_requisito').hasClass("select2-hidden-accessible")) {
        $('#ddl_cargo_requisito').select2('destroy');
    }

    $('#ddl_cargo_requisito').select2({
        dropdownParent: $('#modal_cargo_requisito'),
        ajax: {
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_union_cargo_requisitoC.php?buscar=true',
            dataType: 'json',
            data: function(params) {
                return {
                    q: params.term, // texto buscado
                    th_car_id: id_cargo // ID de la plaza
                };
            },
            processResults: function(data) {
                return {
                    results: data
                };
            }
        },
        minimumInputLength: 0,
        placeholder: "Seleccione un requisito",
        language: {
            noResults: function() {
                return "No hay requisitos disponibles para asignar";
            },
            searching: function() {
                return "Buscando...";
            }
        }
    });
}



function insertar_cargo_requisito() {
    $.ajax({
        data: {
            parametros: Parametros_Car_Req()
        },
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_union_cargo_requisitoC.php?insertar_editar=true',
        type: 'post',
        dataType: 'json',
        success: function(res) {
            if (res > 0) {
                Swal.fire('', 'Plaza creada con éxito.', 'success').then(function() {
                    $('#modal_cargo_requisito').modal('hide');
                    $('#tbl_req_detalles').DataTable().ajax.reload(null, false);
                    $('#ddl_cargo_requisito').empty().append(
                        '<option value="" selected hidden>-- Seleccione el requisito detalle --</option>'
                    );
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

function Parametros_Car_Req() {

    return {
        th_car_id: "<?= isset($_id) ? $_id : '' ?>",
        ddl_cargo_requisito: $('#ddl_cargo_requisito').val()
    };

}
</script>


<script>
function abrir_modal_aspectos_intrinsecos() {

    var modal = new bootstrap.Modal(
        document.getElementById('modal_aspectos_intrinsecos'), {
            backdrop: 'static',
            keyboard: false
        }
    );



    modal.show();


}
$(document).ready(function() {
    function obtenerParametrosAspecto() {
        // Helper para obtener valor según checkbox
        const obtenerValor = (checkboxId, ddlId, inputId) => {
            const checked = $(`#${checkboxId}`).is(':checked');

            if (checked) {
                // Si checkbox marcado, retornar null (el valor viene del ID)
                return null;
            } else {
                // Si checkbox no marcado, tomar valor del input/textarea
                const valor = $(`#${inputId}`).val();
                return valor ? valor.trim() : null;
            }
        };

        // Helper para obtener ID cuando checkbox está marcado
        const obtenerIdCargo = (checkboxId, ddlId) => {
            const checked = $(`#${checkboxId}`).is(':checked');
            if (checked) {
                const valor = $(`#${ddlId}`).val();
                return valor ? parseInt(valor) : null;
            }
            return null;
        };

        return {
            // IDs básicos
            '_id': $('#th_carasp_id').val() || '',
            'th_car_id': $('#th_car_id').val() || '', // CORREGIDO: Ya no usa PHP inline

            // Nivel del cargo
            'th_carasp_nivel_cargo': $('#th_carasp_nivel_cargo').val() || '',

            // SUBORDINACIÓN
            'th_carasp_subordinacion': obtenerValor(
                'chk_subordinacion_empresa',
                'ddl_subordinacion',
                'txt_subordinacion'
            ),
            'th_carasp_subordinacion_id': obtenerIdCargo(
                'chk_subordinacion_empresa',
                'ddl_subordinacion'
            ),

            // SUPERVISIÓN
            'th_carasp_supervision': obtenerValor(
                'chk_supervision_empresa',
                'ddl_supervision',
                'txt_supervision'
            ),
            'th_carasp_supervision_id': obtenerIdCargo(
                'chk_supervision_empresa',
                'ddl_supervision'
            ),

            // COMUNICACIONES COLATERALES
            'th_carasp_comunicaciones_colaterales': obtenerValor(
                'chk_comunicaciones_empresa',
                'ddl_comunicaciones',
                'txt_comunicaciones'
            ),
            'th_carasp_comunicaciones_id': obtenerIdCargo(
                'chk_comunicaciones_empresa',
                'ddl_comunicaciones'
            ),

            // Estado
            'chk_th_carasp_estado': 1
        };
    }

    /**
     * Valida los parámetros antes de guardar
     */
    function validarAspectoParametros(parametros) {
        console.log('Validando parámetros:', parametros); // DEBUG

        // Validar que tenga cargo asociado
        if (!parametros.th_car_id) {
            Swal.fire('', 'Falta el ID del cargo asociado.', 'warning');
            return false;
        }

        // Validar nivel del cargo
        if (!parametros.th_carasp_nivel_cargo) {
            Swal.fire('', 'Debe seleccionar el nivel del cargo.', 'warning');
            $('#th_carasp_nivel_cargo').focus();
            return false;
        }

        // Validar subordinación (debe tener texto O id)
        if (!parametros.th_carasp_subordinacion && !parametros.th_carasp_subordinacion_id) {
            Swal.fire('', 'Debe completar la información de subordinación.', 'warning');
            if ($('#chk_subordinacion_empresa').is(':checked')) {
                $('#ddl_subordinacion').focus();
            } else {
                $('#txt_subordinacion').focus();
            }
            return false;
        }

        // Validar supervisión (debe tener texto O id)
        if (!parametros.th_carasp_supervision && !parametros.th_carasp_supervision_id) {
            Swal.fire('', 'Debe completar la información de supervisión.', 'warning');
            if ($('#chk_supervision_empresa').is(':checked')) {
                $('#ddl_supervision').focus();
            } else {
                $('#txt_supervision').focus();
            }
            return false;
        }

        // Validar comunicaciones (debe tener texto O id)
        if (!parametros.th_carasp_comunicaciones_colaterales && !parametros.th_carasp_comunicaciones_id) {
            Swal.fire('', 'Debe completar la información de comunicaciones colaterales.', 'warning');
            if ($('#chk_comunicaciones_empresa').is(':checked')) {
                $('#ddl_comunicaciones').focus();
            } else {
                $('#txt_comunicaciones').focus();
            }
            return false;
        }

        return true;
    }


    function guardar_o_actualizar_aspecto() {
        var parametros = obtenerParametrosAspecto();

        console.log('Parámetros a enviar:', parametros); // DEBUG

        if (!validarAspectoParametros(parametros)) return;

        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_aspectos_intrinsecosC.php?insertar_editar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log('Respuesta del servidor:', response); // DEBUG

                if (response == 1 || response === true) {

                    Swal.fire('', 'Plaza creada con éxito.', 'success').then(function() {
                        location.reload();
                    });
                } else if (response == -2) {
                    Swal.fire('',
                        'Ya existe un aspecto intrínseco duplicado para este cargo/nivel.',
                        'warning');
                } else {
                    var msg = (typeof response === 'object' && response.msg) ? response.msg :
                        'Error al guardar los aspectos.';
                    Swal.fire('', msg, 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error guardar_aspecto:', {
                    status: status,
                    error: error,
                    response: xhr.responseText
                });
                Swal.fire('',
                    'Error al conectar con el servidor. Revisa la consola para más detalles.',
                    'error');
            }
        });
    }

    /**
     * Carga los datos para edición en el modal
     */
    function cargar_datos_aspecto(datos) {
        // Cargar datos básicos
        $('#th_carasp_id').val(datos.th_carasp_id || '');
        $('#th_car_id').val(datos.th_car_id || '');
        $('#th_carasp_nivel_cargo').val(datos.th_carasp_nivel_cargo || '');

        // SUBORDINACIÓN
        if (datos.th_carasp_subordinacion_id) {
            // Tiene ID de cargo, marcar checkbox y seleccionar en DDL
            $('#chk_subordinacion_empresa').prop('checked', true).trigger('change');
            setTimeout(() => {
                $('#ddl_subordinacion').val(datos.th_carasp_subordinacion_id).trigger('change');
            }, 100);
        } else {
            // No tiene ID, desmarcar checkbox y poner texto
            $('#chk_subordinacion_empresa').prop('checked', false).trigger('change');
            $('#txt_subordinacion').val(datos.th_carasp_subordinacion || '');
        }

        // SUPERVISIÓN
        if (datos.th_carasp_supervision_id) {
            $('#chk_supervision_empresa').prop('checked', true).trigger('change');
            setTimeout(() => {
                $('#ddl_supervision').val(datos.th_carasp_supervision_id).trigger('change');
            }, 100);
        } else {
            $('#chk_supervision_empresa').prop('checked', false).trigger('change');
            $('#txt_supervision').val(datos.th_carasp_supervision || '');
        }

        // COMUNICACIONES COLATERALES
        if (datos.th_carasp_comunicaciones_id) {
            $('#chk_comunicaciones_empresa').prop('checked', true).trigger('change');
            setTimeout(() => {
                $('#ddl_comunicaciones').val(datos.th_carasp_comunicaciones_id).trigger('change');
            }, 100);
        } else {
            $('#chk_comunicaciones_empresa').prop('checked', false).trigger('change');
            $('#txt_comunicaciones').val(datos.th_carasp_comunicaciones_colaterales || '');
        }

        // Mostrar botones de edición
        $('#pnl_crear_aspecto').hide();
        $('#pnl_actualizar_aspecto').show();
    }

    $(function() {
        // Guardar nuevo
        $(document).on('click', '#pnl_crear_aspecto button', function(e) {
            e.preventDefault();
            guardar_o_actualizar_aspecto();
        });

        // Actualizar (botón del panel de editar)
        $(document).on('click', '#btn_editar_aspecto', function(e) {
            e.preventDefault();
            guardar_o_actualizar_aspecto();
        });

    });
});
</script>

<script>
// ============================================
// FUNCIONES PARA COMPLIANCE
// ============================================

function abrir_modal_compliance() {
    var modal = new bootstrap.Modal(
        document.getElementById('modal_compliance'), {
            backdrop: 'static',
            keyboard: false
        }
    );

    // Limpiar formulario
    $('#form_compliance')[0].reset();
    $('#th_comp_id').val('');
    $('#pnl_crear_compliance').show();
    $('#pnl_actualizar_compliance').hide();
    $('#modalComplianceLabel').html('<i class="bx bx-check-shield me-2"></i> Registrar Compliance del Cargo');

    // Verificar si ya existe compliance para este cargo
    verificar_compliance_existente(<?= $_id ?>);

    modal.show();
}

function verificar_compliance_existente(cargoId) {
    $.ajax({
        data: {
            id: cargoId
        },
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_complianceC.php?listar_compliance_cargo=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response && (Array.isArray(response) ? response.length > 0 : true)) {
                const data = Array.isArray(response) ? response[0] : response;

                // Llenar formulario para edición (sin prefijo th_comp_)
                $('#th_comp_id').val(data._id || data.id || '');
                $('#th_comp_requisitos_totales').val(data.requisitos_totales || 0);
                $('#th_comp_requisitos_completados').val(data.requisitos_completados || 0);

                // Formatear fecha para input type="date" (YYYY-MM-DD)
                if (data.ultima_revision) {
                    const fecha = new Date(data.ultima_revision);
                    const fechaFormatted = fecha.toISOString().split('T')[0];
                    $('#th_comp_ultima_revision').val(fechaFormatted);
                }

                $('#th_comp_observaciones').val(data.observaciones || '');

                // Calcular valores automáticos
                calcularComplianceModal();

                // Cambiar a modo edición
                $('#pnl_crear_compliance').hide();
                $('#pnl_actualizar_compliance').show();
                $('#modalComplianceLabel').html(
                    '<i class="bx bx-edit me-2"></i> Editar Compliance del Cargo'
                );
            } else {
                // Modo crear nuevo
                $('#pnl_crear_compliance').show();
                $('#pnl_actualizar_compliance').hide();
                $('#modalComplianceLabel').html(
                    '<i class="bx bx-check-shield me-2"></i> Registrar Compliance del Cargo'
                );
            }
        },
        error: function(err) {
            console.error('Error al verificar compliance:', err);
            // Modo crear nuevo por defecto
            $('#pnl_crear_compliance').show();
            $('#pnl_actualizar_compliance').hide();
            $('#modalComplianceLabel').html(
                '<i class="bx bx-check-shield me-2"></i> Registrar Compliance del Cargo'
            );
        }
    });
}


// Calcular automáticamente los valores en el modal
function calcularComplianceModal() {
    const totales = parseInt($('#th_comp_requisitos_totales').val()) || 0;
    const completados = parseInt($('#th_comp_requisitos_completados').val()) || 0;

    // Validar que completados no sea mayor que totales
    if (completados > totales) {
        $('#th_comp_requisitos_completados').val(totales);
        return;
    }

    const faltantes = totales - completados;
    const porcentaje = totales > 0 ? ((completados / totales) * 100).toFixed(2) : 0;

    // Actualizar campos calculados
    $('#th_comp_requisitos_faltantes').val(faltantes);
    $('#th_comp_porcentaje_completado').val(porcentaje);

    // Actualizar vista previa
    $('#preview_completados').text(completados);
    $('#preview_faltantes').text(faltantes);
    $('#preview_porcentaje').text(porcentaje + '%');
}

// Obtener parámetros del formulario
function obtenerParametrosCompliance() {
    return {
        '_id': $('#th_comp_id').val() || '',
        'th_car_id': "<?= isset($_id) ? $_id : '' ?>",
        'th_comp_porcentaje_completado': $('#th_comp_porcentaje_completado').val() || 0,
        'th_comp_requisitos_totales': $('#th_comp_requisitos_totales').val() || 0,
        'th_comp_requisitos_completados': $('#th_comp_requisitos_completados').val() || 0,
        'th_comp_requisitos_faltantes': $('#th_comp_requisitos_faltantes').val() || 0,
        'th_comp_ultima_revision': $('#th_comp_ultima_revision').val() || null,
        'th_comp_observaciones': $('#th_comp_observaciones').val().trim() || ''
    };
}

// Validar parámetros
function validarComplianceParametros(p) {
    if (!p.th_car_id || p.th_car_id === '') {
        Swal.fire('', 'ID del cargo no encontrado. Abra el modal desde un cargo válido.', 'warning');
        return false;
    }
    if (parseInt(p.th_comp_requisitos_totales) < 0) {
        Swal.fire('', 'Los requisitos totales no pueden ser negativos.', 'warning');
        return false;
    }
    if (parseInt(p.th_comp_requisitos_completados) > parseInt(p.th_comp_requisitos_totales)) {
        Swal.fire('', 'Los requisitos completados no pueden ser mayores a los totales.', 'warning');
        return false;
    }
    return true;
}

// Guardar o actualizar compliance
function guardar_o_actualizar_compliance() {
    var parametros = obtenerParametrosCompliance();

    if (!validarComplianceParametros(parametros)) return;

    $.ajax({
        data: {
            parametros: parametros
        },
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_complianceC.php?insertar_editar=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response == 1 || response === true) {
                Swal.fire('', (parametros._id ? 'Compliance actualizado con éxito.' :
                        'Compliance creado con éxito.'), 'success')
                    .then(function() {
                        var modalEl = document.getElementById('modal_compliance');
                        var modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();

                        // Recargar datos de compliance en la vista
                        listar_compliance_cargo("<?= isset($_id) ? $_id : '' ?>");
                    });
            } else if (response == -2) {
                Swal.fire('', 'Ya existe un registro de compliance para este cargo.', 'warning');
            } else {
                var msg = (typeof response === 'object' && response.msg) ? response.msg :
                    'Error al guardar el compliance.';
                Swal.fire('', msg, 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error guardar_compliance: ', status, error, xhr.responseText);
            Swal.fire('', 'Error al conectar con el servidor: ' + xhr.responseText, 'error');
        }
    });
}

function insertar_compliance() {
    guardar_o_actualizar_compliance();
}

// Listar compliance del cargo en la vista
function listar_compliance_cargo(cargoId) {
    $.ajax({
        data: {
            id: cargoId
        },
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_complianceC.php?listar_compliance_cargo=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (!response || (Array.isArray(response) && response.length === 0)) {
                mostrarComplianceVacio();
                return;
            }

            const data = Array.isArray(response) ? response[0] : response;

            // Extraer datos SIN el prefijo th_comp_
            const porcentaje = parseFloat(data.porcentaje_completado || 0).toFixed(2);
            const totales = parseInt(data.requisitos_totales || 0);
            const completados = parseInt(data.requisitos_completados || 0);
            const faltantes = parseInt(data.requisitos_faltantes || 0);
            const ultimaRevision = data.ultima_revision || '';
            const estado = data.estado || '';
            const observaciones = data.observaciones || '';

            // Actualizar vista principal
            $('#comp_porcentaje').text(porcentaje + '%');
            $('#comp_progress_bar').css('width', porcentaje + '%').attr('aria-valuenow', porcentaje);
            $('#comp_progress_text').text(porcentaje + '%');

            // Cambiar color de la barra según porcentaje
            const $progressBar = $('#comp_progress_bar');
            $progressBar.removeClass('bg-danger bg-warning bg-success');
            if (porcentaje < 50) {
                $progressBar.addClass('bg-danger');
            } else if (porcentaje < 80) {
                $progressBar.addClass('bg-warning');
            } else {
                $progressBar.addClass('bg-success');
            }

            $('#comp_totales').text(totales);
            $('#comp_completados').text(completados);
            $('#comp_faltantes').text(faltantes);

            // Última revisión
            if (ultimaRevision) {
                const fecha = new Date(ultimaRevision);
                $('#comp_ultima_revision').text(fecha.toLocaleDateString('es-ES'));
            } else {
                $('#comp_ultima_revision').html('<em class="text-muted">No registrada</em>');
            }

            // Estado
            const estadoConfig = {
                '1': {
                    label: 'Activo',
                    class: 'bg-success'
                },
                '0': {
                    label: 'Inactivo',
                    class: 'bg-secondary'
                },
                '2': {
                    label: 'En Revisión',
                    class: 'bg-warning'
                },
                '3': {
                    label: 'Observado',
                    class: 'bg-danger'
                }
            };
            const estadoInfo = estadoConfig[estado] || {
                label: 'Sin estado',
                class: 'bg-secondary'
            };
            $('#comp_estado_badge').removeClass('bg-success bg-secondary bg-warning bg-danger')
                .addClass(estadoInfo.class)
                .html(`<i class="bi bi-circle-fill me-1"></i>${estadoInfo.label}`);

            // Observaciones
            if (observaciones && observaciones.trim() !== '') {
                $('#comp_observaciones').text(observaciones);
            } else {
                $('#comp_observaciones').html('<em class="text-muted">Sin observaciones registradas</em>');
            }

            // Badge resumen
            $('#badge_resumen').html(
                `<i class="bi bi-graph-up me-1"></i>Completados: ${completados} de ${totales} (${porcentaje}%)`
            );

            console.log('Compliance cargado exitosamente:', data);
        },
        error: function(err) {
            console.error('Error al cargar compliance:', err);
            mostrarComplianceVacio();
        }
    });
}

function mostrarComplianceVacio() {
    $('#comp_porcentaje').text('0%');
    $('#comp_progress_bar').css('width', '0%').attr('aria-valuenow', 0).removeClass('bg-warning bg-success').addClass(
        'bg-secondary');
    $('#comp_progress_text').text('0%');
    $('#comp_totales').text('0');
    $('#comp_completados').text('0');
    $('#comp_faltantes').text('0');
    $('#comp_ultima_revision').html('<em class="text-muted">No registrada</em>');
    $('#comp_estado_badge').removeClass('bg-success bg-warning bg-danger').addClass('bg-secondary')
        .html('<i class="bi bi-circle-fill me-1"></i>Sin estado');
    $('#comp_observaciones').html('<em class="text-muted">Sin observaciones registradas</em>');
    $('#badge_resumen').html('<i class="bi bi-graph-up me-1"></i>Completados: 0 de 0 (0%)');
}

// Event listeners
$(function() {
    // Calcular automáticamente cuando cambien los valores
    $('#th_comp_requisitos_totales, #th_comp_requisitos_completados').on('input change', function() {
        calcularComplianceModal();
    });

    // Actualizar
    $(document).on('click', '#btn_editar_compliance', function(e) {
        e.preventDefault();
        guardar_o_actualizar_compliance();
    });

    // Eliminar
    $(document).on('click', '#btn_eliminar_compliance', function(e) {
        e.preventDefault();
        eliminar_compliance();
    });

    // Cargar compliance al iniciar
    const cargoId = "<?= isset($_id) ? $_id : '' ?>";
    if (cargoId) {
        listar_compliance_cargo(cargoId);
    }
});

function eliminar_compliance() {
    const compId = $('#th_comp_id').val();

    if (!compId) {
        Swal.fire('', 'No hay registro de compliance para eliminar.', 'warning');
        return;
    }

    Swal.fire({
        title: '¿Está seguro?',
        text: "Esta acción eliminará el registro de compliance del cargo",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                data: {
                    id: compId
                },
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_complianceC.php?eliminar=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    if (response == 1 || response === true) {
                        Swal.fire('Eliminado', 'El registro de compliance ha sido eliminado.',
                                'success')
                            .then(function() {
                                var modalEl = document.getElementById('modal_compliance');
                                var modal = bootstrap.Modal.getInstance(modalEl);
                                if (modal) modal.hide();

                                mostrarComplianceVacio();
                            });
                    } else {
                        Swal.fire('', 'Error al eliminar el registro de compliance.', 'error');
                    }
                },
                error: function(xhr) {
                    Swal.fire('', 'Error al conectar con el servidor: ' + xhr.responseText,
                        'error');
                }
            });
        }
    });
}
</script>

<script>
// ============================================
// FUNCIONES PARA FUNCIONES DEL CARGO
// ============================================

function abrir_modal_funciones() {
    var modal = new bootstrap.Modal(
        document.getElementById('modal_funciones_cargo'), {
            backdrop: 'static',
            keyboard: false
        }
    );

    // Limpiar formulario
    $('#form_funciones_cargo')[0].reset();
    $('#th_carfun_id').val('');
    $('#th_car_id_funcion').val(<?= $_id ?>);
    $('#pnl_crear_funcion').show();
    $('#pnl_actualizar_funcion').hide();
    $('#modalFuncionesLabel').html('<i class="bx bx-list-check me-2"></i> Registrar Función del Cargo');

    // Limpiar vista previa
    $('#preview_frecuencia').text('-');
    $('#preview_porcentaje').text('0%');
    $('#preview_tipo').removeClass('bg-warning bg-info').addClass('bg-secondary').text('-');
    $('#preview_orden').text('#1');

    modal.show();
}

// ============================================
// ACTUALIZAR VISTA PREVIA EN TIEMPO REAL
// ============================================
function actualizarVistaPrevia() {
    const frecuencia = $('#th_carfun_frecuencia').val() || '-';
    const porcentaje = $('#th_carfun_porcentaje_tiempo').val() || '0';
    const esPrincipal = $('#th_carfun_es_principal').val();
    const orden = $('#th_carfun_orden').val() || '1';

    $('#preview_frecuencia').text(frecuencia);
    $('#preview_porcentaje').text(porcentaje + '%');
    $('#preview_orden').text('#' + orden);

    const $tipoBadge = $('#preview_tipo');
    $tipoBadge.removeClass('bg-warning bg-info bg-secondary');

    if (esPrincipal === '1') {
        $tipoBadge.addClass('bg-warning').text('Principal');
    } else if (esPrincipal === '0') {
        $tipoBadge.addClass('bg-info').text('Secundaria');
    } else {
        $tipoBadge.addClass('bg-secondary').text('-');
    }
}

// ============================================
// OBTENER PARÁMETROS DEL FORMULARIO
// ============================================
function obtenerParametrosFuncion() {
    return {
        '_id': $('#th_carfun_id').val() || '',
        'th_car_id': "<?= isset($_id) ? $_id : '' ?>",
        'nombre': $('#th_carfun_nombre').val().trim(),
        'descripcion': $('#th_carfun_descripcion').val().trim(),
        'frecuencia': $('#th_carfun_frecuencia').val(),
        'porcentaje_tiempo': $('#th_carfun_porcentaje_tiempo').val() || 0,
        'es_principal': $('#th_carfun_es_principal').val() === '1' ? 1 : 0,
        'orden': $('#th_carfun_orden').val() || 1,
        'estado': 1
    };
}

// ============================================
// VALIDAR PARÁMETROS
// ============================================
function validarFuncionParametros(p) {
    if (!p.th_car_id || p.th_car_id === '') {
        Swal.fire('', 'ID del cargo no encontrado.', 'warning');
        return false;
    }
    if (!p.nombre || p.nombre === '') {
        Swal.fire('', 'El nombre de la función es obligatorio.', 'warning');
        return false;
    }
    if (!p.frecuencia || p.frecuencia === '') {
        Swal.fire('', 'La frecuencia es obligatoria.', 'warning');
        return false;
    }
    const porcentaje = parseFloat(p.porcentaje_tiempo);
    if (porcentaje < 0 || porcentaje > 100) {
        Swal.fire('', 'El porcentaje de tiempo debe estar entre 0 y 100.', 'warning');
        return false;
    }
    if (p.es_principal === '' || p.es_principal === null) {
        Swal.fire('', 'Debe indicar si es función principal o no.', 'warning');
        return false;
    }
    return true;
}

// ============================================
// GUARDAR O ACTUALIZAR FUNCIÓN
// ============================================
function guardar_o_actualizar_funcion() {
    var parametros = obtenerParametrosFuncion();

    if (!validarFuncionParametros(parametros)) return;

    $.ajax({
        data: {
            parametros: parametros
        },
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_funcionesC.php?insertar_editar=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response == 1 || response === true) {
                Swal.fire('', (parametros._id ? 'Función actualizada con éxito.' :
                        'Función creada con éxito.'), 'success')
                    .then(function() {
                        var modalEl = document.getElementById('modal_funciones_cargo');
                        var modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();

                        // Recargar tabla de funciones
                        listar_funciones_cargo(<?= $_id ?>);
                    });
            } else if (response == -2) {
                Swal.fire('', 'Ya existe una función con ese nombre para este cargo.', 'warning');
            } else {
                var msg = (typeof response === 'object' && response.msg) ? response.msg :
                    'Error al guardar la función.';
                Swal.fire('', msg, 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error guardar_funcion:', status, error, xhr.responseText);
            Swal.fire('', 'Error al conectar con el servidor: ' + xhr.responseText, 'error');
        }
    });
}


// ============================================
// LISTAR FUNCIONES EN LA TABLA
// ============================================
function listar_funciones_cargo(cargoId) {
    $.ajax({
        data: {
            id: cargoId
        },
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_funcionesC.php?listar=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            const $tbody = $('#tbody_funciones');
            $tbody.empty();

            if (!response || response.length === 0) {
                $tbody.html(`
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="bx bx-info-circle" style="font-size: 2rem;"></i>
                            <p class="mb-0 mt-2">No hay funciones registradas para este cargo</p>
                        </td>
                    </tr>
                `);
                actualizarEstadisticasFunciones(0, 0, 0, 0);
                return;
            }

            let totalPrincipales = 0;
            let totalSecundarias = 0;
            let porcentajeAcumulado = 0;

            response.forEach((func, index) => {
                const esPrincipal = parseInt(func.th_carfun_es_principal) === 1;
                const porcentaje = parseFloat(func.th_carfun_porcentaje_tiempo || 0);

                if (esPrincipal) totalPrincipales++;
                else totalSecundarias++;

                porcentajeAcumulado += porcentaje;

                const tipoBadge = esPrincipal ?
                    '<span class="badge bg-warning"><i class="bx bx-star"></i> Principal</span>' :
                    '<span class="badge bg-info"><i class="bx bx-bookmark"></i> Secundaria</span>';

                const row = `
                    <tr>
                        <td class="text-center fw-bold">${index + 1}</td>
                        <td>
                            <div class="fw-bold text-primary">${func.th_carfun_nombre}</div>
                            ${func.th_carfun_descripcion ? `<small class="text-muted">${func.th_carfun_descripcion}</small>` : ''}
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                <i class="bx bx-time"></i> ${func.th_carfun_frecuencia || 'N/A'}
                            </span>
                        </td>
                        <td class="text-center">
                            <strong class="text-success">${porcentaje.toFixed(2)}%</strong>
                        </td>
                        <td class="text-center">${tipoBadge}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-primary" onclick="editar_funcion('${func._id}')" title="Editar">
                                <i class="bx bx-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="confirmar_eliminar_funcion('${func._id}')" title="Eliminar">
                                <i class="bx bx-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                $tbody.append(row);
            });

            actualizarEstadisticasFunciones(response.length, totalPrincipales, totalSecundarias,
                porcentajeAcumulado);
        },
        error: function(err) {
            console.error('Error al cargar funciones:', err);
            $('#tbody_funciones').html(`
                <tr>
                    <td colspan="6" class="text-center py-4 text-danger">
                        <i class="bx bx-error-circle" style="font-size: 2rem;"></i>
                        <p class="mb-0 mt-2">Error al cargar las funciones</p>
                    </td>
                </tr>
            `);
        }
    });
}

// ============================================
// ACTUALIZAR ESTADÍSTICAS EN EL FOOTER
// ============================================
function actualizarEstadisticasFunciones(total, principales, secundarias, porcentajeTotal) {
    $('#stat_total').text(total);
    $('#stat_principales').text(principales);
    $('#stat_secundarias').text(secundarias);
    $('#stat_porcentaje_total').text(porcentajeTotal.toFixed(2) + '%');
}

// ============================================
// EDITAR FUNCIÓN
// ============================================
function editar_funcion() {
    $.ajax({
        data: {
            id: "<?= isset($_id) ? $_id : '' ?>"
        },
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_funcionesC.php?listar=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            // Llenar formulario
            $('#th_carfun_id').val(response[0]._id);
            $('#th_car_id_funcion').val(response[0].th_car_id);
            $('#th_carfun_nombre').val(response[0].th_carfun_nombre);
            $('#th_carfun_descripcion').val(response[0].th_carfun_descripcion || '');
            $('#th_carfun_frecuencia').val(response[0].th_carfun_frecuencia);
            $('#th_carfun_porcentaje_tiempo').val(response[0].th_carfun_porcentaje_tiempo || 0);
            $('#th_carfun_es_principal').val(response[0].th_carfun_es_principal);
            $('#th_carfun_orden').val(response[0].th_carfun_orden || 1);

            // Actualizar vista previa
            actualizarVistaPrevia();

            // Cambiar a modo edición
            $('#pnl_crear_funcion').hide();
            $('#pnl_actualizar_funcion').show();
            $('#modalFuncionesLabel').html('<i class="bx bx-edit me-2"></i> Editar Función del Cargo');

            // Abrir modal
            var modal = new bootstrap.Modal(document.getElementById('modal_funciones_cargo'));
            modal.show();
        },
        error: function(err) {
            console.error('Error al cargar función:', err);
            Swal.fire('', 'Error al cargar los datos de la función', 'error');
        }
    });
}

// ============================================
// CONFIRMAR ELIMINAR FUNCIÓN
// ============================================
function confirmar_eliminar_funcion(funcionId) {
    Swal.fire({
        title: '¿Está seguro?',
        text: "Esta acción eliminará la función del cargo",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            eliminar_funcion(funcionId);
        }
    });
}

// ============================================
// ELIMINAR FUNCIÓN
// ============================================
function eliminar_funcion(funcionId) {
    $.ajax({
        data: {
            id: funcionId
        },
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_funcionesC.php?eliminar=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response == 1 || response === true) {
                Swal.fire('Eliminado', 'La función ha sido eliminada.', 'success')
                    .then(function() {
                        listar_funciones_cargo(<?= $_id ?>);
                    });
            } else {
                Swal.fire('', 'Error al eliminar la función.', 'error');
            }
        },
        error: function(xhr) {
            Swal.fire('', 'Error al conectar con el servidor: ' + xhr.responseText, 'error');
        }
    });
}

// ============================================
// EVENT LISTENERS
// ============================================
$(function() {
    // Actualizar vista previa en tiempo real
    $('#th_carfun_frecuencia, #th_carfun_porcentaje_tiempo, #th_carfun_es_principal, #th_carfun_orden')
        .on('input change', actualizarVistaPrevia);

    // Actualizar
    $(document).on('click', '#btn_editar_funcion', function(e) {
        e.preventDefault();
        guardar_o_actualizar_funcion();
    });

    // Eliminar desde modal
    $(document).on('click', '#btn_eliminar_funcion', function(e) {
        e.preventDefault();
        const funcionId = $('#th_carfun_id').val();
        if (funcionId) {
            confirmar_eliminar_funcion(funcionId);
        }
    });

    // Cargar funciones al iniciar
    const cargoId = "<?= isset($_id) ? $_id : '' ?>";
    if (cargoId) {
        listar_funciones_cargo(cargoId);
    }
});
</script>

<script>
function abrir_modal_competencias() {
    var modal = new bootstrap.Modal(
        document.getElementById('modal_cargo_competencia'), {
            backdrop: 'static',
            keyboard: false
        }
    );

    modal.show();
}

function obtenerParametrosCargoCompetencia() {
    return {
        '_id': $('#txt_th_carcomp_id').val() || '',
        'th_car_id': $('#txt_th_car_id').val() || $('#txt_th_car_id').attr('value') || '',
        'th_comp_id': $('#ddl_competencias').val() || '',
        'th_carcomp_nivel_requerido': $('#txt_th_carcomp_nivel_requerido').val() || '',
        'nivel_utilizacion': $('#txt_th_carcomp_nivel_utilizacion').val() || '',
        'nivel_contribucion': $('#txt_th_carcomp_nivel_contribucion').val() || '',
        'nivel_habilidad': $('#txt_th_carcomp_nivel_habilidad').val() || '',
        'nivel_maestria': $('#txt_th_carcomp_nivel_maestria').val() || '',
        'disc_d': $('#txt_th_carcomp_disc_valor_d').val() || '',
        'disc_i': $('#txt_th_carcomp_disc_valor_i').val() || '',
        'disc_s': $('#txt_th_carcomp_disc_valor_s').val() || '',
        'disc_c': $('#txt_th_carcomp_disc_valor_c').val() || '',
        'grafica_json': null, // si generas gráfico poner aquí JSON
        'es_critica': $('#ddl_th_carcomp_es_critica').val() === '1' ? 1 : 0,
        'es_evaluable': $('#ddl_th_carcomp_es_evaluable').val() === '1' ? 1 : 0,
        'metodo': $('#txt_th_carcomp_metodo_evaluacion').val().trim() || null,
        'ponderacion': $('#txt_th_carcomp_ponderacion').val() || null,
        'observaciones': $('#txt_th_carcomp_observaciones').val() || null,
        'estado': 1
    };
}

// ---------- INSERTAR ----------
function insertar_cargo_competencia() {
    var parametros = obtenerParametrosCargoCompetencia();

    $.ajax({
        data: {
            parametros: parametros
        },
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_competenciasC.php?insertar_editar=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response == 1 || response === true) {
                Swal.fire('', 'Competencia creada con éxito.', 'success').then(function() {
                    var modalEl = document.getElementById('modal_cargo_competencia');
                    var modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();

                    // recargar la lista de competencias del cargo (debes tener una función listar)
                    if (typeof listar_competencias_cargo === 'function') {
                        listar_competencias_cargo(parametros.th_car_id);
                    } else {
                        // fallback: recarga la página si no existe la función
                        location.reload();
                    }
                });
            } else if (response == -2) {
                Swal.fire('', 'Ya existe esa competencia para este cargo.', 'warning');
            } else {
                var msg = (typeof response === 'object' && response.msg) ? response.msg :
                    'Error al guardar competencia.';
                Swal.fire('', msg, 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error insertar_cargo_competencia:', status, error, xhr.responseText);
            Swal.fire('', 'Error al conectar con el servidor: ' + xhr.responseText, 'error');
        }
    });
}

function eliminar_cargo_competencia(id) {
    var id = id;
    if (!id) {
        Swal.fire('', 'Registro inválido.', 'warning');
        return;
    }

    Swal.fire({
        title: '¿Eliminar competencia?',
        text: "La competencia será desactivada (soft delete).",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                data: {
                    id: id
                },
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_competenciasC.php?eliminar=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    if (response == 1 || response === true) {
                        Swal.fire('', 'Competencia eliminada.', 'success').then(function() {
                            var modalEl = document.getElementById(
                                'modal_cargo_competencia');
                            var modal = bootstrap.Modal.getInstance(modalEl);
                            if (modal) modal.hide();

                            var th_car_id = $('#txt_th_car_id').val();
                            if (typeof listar_competencias_cargo === 'function') {
                                listar_competencias_cargo(th_car_id);
                            } else {
                                location.reload();
                            }
                        });
                    } else {
                        Swal.fire('', 'Error al eliminar.', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error eliminar_cargo_competencia:', status, error, xhr
                        .responseText);
                    Swal.fire('', 'Error al conectar con el servidor: ' + xhr.responseText,
                        'error');
                }
            });
        }
    });
}

function abrir_modal_cargo_competencia_editar(competencia) {
    limpiar_form_cargo_competencia();

    $('#txt_th_carcomp_id').val(competencia.th_carcomp_id || competencia.id || '');
    $('#txt_th_car_id').val(competencia.th_car_id || '');

    if (competencia.th_comp_id && competencia.th_comp_nombre) {
        var option = new Option(competencia.th_comp_nombre, competencia.th_comp_id, true, true);
        $('#ddl_competencias').append(option).trigger('change');
    } else if (competencia.th_comp_id) {
        $('#ddl_competencias').val(competencia.th_comp_id).trigger('change');
    }

    $('#txt_th_carcomp_nivel_requerido').val(competencia.th_carcomp_nivel_requerido || '');
    $('#txt_th_carcomp_nivel_utilizacion').val(competencia.th_carcomp_nivel_utilizacion || '');
    $('#txt_th_carcomp_nivel_contribucion').val(competencia.th_carcomp_nivel_contribucion || '');
    $('#txt_th_carcomp_nivel_habilidad').val(competencia.th_carcomp_nivel_habilidad || '');
    $('#txt_th_carcomp_nivel_maestria').val(competencia.th_carcomp_nivel_maestria || '');

    $('#txt_th_carcomp_disc_valor_d').val(competencia.th_carcomp_disc_valor_d || '');
    $('#txt_th_carcomp_disc_valor_i').val(competencia.th_carcomp_disc_valor_i || '');
    $('#txt_th_carcomp_disc_valor_s').val(competencia.th_carcomp_disc_valor_s || '');
    $('#txt_th_carcomp_disc_valor_c').val(competencia.th_carcomp_disc_valor_c || '');

    $('#ddl_th_carcomp_es_critica').val(competencia.th_carcomp_es_critica ? '1' : '0');
    $('#ddl_th_carcomp_es_evaluable').val(competencia.th_carcomp_es_evaluable ? '1' : '0');
    $('#txt_th_carcomp_metodo_evaluacion').val(competencia.th_carcomp_metodo_evaluacion || '');
    $('#txt_th_carcomp_ponderacion').val(competencia.th_carcomp_ponderacion || '');
    $('#txt_th_carcomp_observaciones').val(competencia.th_carcomp_observaciones || '');

    $('#pnl_crear').hide();
    $('#pnl_actualizar').show();

    var modal = new bootstrap.Modal(document.getElementById('modal_cargo_competencia'));
    modal.show();
}

var tbl_competencias;

function cargar_competencias(id_cargo) {

    id_cargo = id_cargo || '';

    tbl_competencias = $('#tbl_competencias').DataTable({
        destroy: true,
        responsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        ajax: {
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_competenciasC.php?listar=true',
            type: 'POST',
            data: function(d) {
                d.id = id_cargo;
            },
            dataSrc: ''
        },
        columns: [{
                data: 'th_car_id'
            }, // nombre th_comp.nombre
            {
                data: 'th_carcomp_nivel_requerido'
            },
            {
                data: 'th_carcomp_ponderacion'
            },
            {
                data: 'th_carcomp_es_critica',
                render: function(v) {
                    return v == 1 ?
                        '<span class="badge bg-danger">Sí</span>' :
                        '<span class="badge bg-secondary">No</span>';
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function(data, type, item) {
                    let id = item._id;
                    return `
                        <button class="btn btn-primary btn-sm" 
                                onclick="abrir_modal_competencias(${id})"
                                data-bs-toggle="tooltip" 
                                title="Editar">
                            <i class="bx bx-edit"></i>
                        </button>

                        <button class="btn btn-danger btn-sm" 
                                onclick="eliminar_cargo_competencia(${id})"
                                data-bs-toggle="tooltip" 
                                title="Eliminar">
                            <i class="bx bx-trash"></i>
                        </button>
                    `;
                }
            }
        ],
        order: [
            [0, 'asc']
        ],
        drawCallback: function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
    });
}
</script>

<style>
/* Animación hover para las tarjetas */
.hover-lift {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

/* Animación para los badges */
.badge {
    transition: all 0.3s ease;
}

.badge:hover {
    transform: scale(1.05);
}

/* Gradiente suave para el fondo */
.bg-gradient {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}
</style>


<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Cargos</div>
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
                                    echo 'Registrar Cargo';
                                } else {
                                    echo 'Modificar Cargo';
                                }
                                ?>
                            </h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_cargos"
                                        class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i>
                                        Regresar</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="">
                            <div class="">
                                <ul class="nav nav-tabs nav-primary" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#primaryhome" role="tab"
                                            aria-selected="true">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon"><i class='bx bx-briefcase-alt font-18 me-1'></i>
                                                </div>
                                                <div class="tab-title">Cargo</div>
                                            </div>
                                        </a>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-bs-toggle="tab" href="#primaryprofile" role="tab"
                                            aria-selected="false">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon"><i class='bx bxs-file-blank font-18 me-1'></i>
                                                </div>
                                                <div class="tab-title">Aspectos Intrínsecos</div>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-bs-toggle="tab" href="#secundaryprofile" role="tab"
                                            aria-selected="false">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon"><i class='bx bxs-file-blank font-18 me-1'></i>
                                                </div>
                                                <div class="tab-title">Aspectos Extrínsecos</div>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-bs-toggle="tab" href="#complianceprofile" role="tab"
                                            aria-selected="false">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon"><i class='bx bxs-file-blank font-18 me-1'></i>
                                                </div>
                                                <div class="tab-title">Compliance</div>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-bs-toggle="tab" href="#competencias" role="tab"
                                            aria-selected="false">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon"><i class='bx bxs-file-blank font-18 me-1'></i>
                                                </div>
                                                <div class="tab-title">Competencias</div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content py-3">
                                    <div class="tab-pane fade show active" id="primaryhome" role="tabpanel">
                                        <section class="content pt-0">
                                            <div class="container-fluid">
                                                <form id="form_cargo">
                                                    <!-- Hidden ID -->
                                                    <input type="hidden" id="txt_th_car_id" name="txt_th_car_id"
                                                        value="<?= $_id ?>" />

                                                    <!-- TÍTULO (Opcional, si deseas mostrarlo como sección) -->
                                                    <h5 class="fw-bold text-primary mb-3">
                                                        <i class="bx bx-info-circle me-2"></i>Información Básica del
                                                        Cargo
                                                    </h5>

                                                    <div class="row g-3">

                                                        <!-- Nombre del cargo -->
                                                        <div class="col-md-6">
                                                            <label for="txt_th_car_nombre" class="form-label fw-bold">
                                                                <i class="bx bx-id-card me-2 text-primary"></i>Nombre
                                                                del Cargo
                                                            </label>
                                                            <input type="text" class="form-control"
                                                                id="txt_th_car_nombre" name="txt_th_car_nombre"
                                                                placeholder="Ingrese el nombre del cargo"
                                                                autocomplete="off" required>
                                                        </div>

                                                        <!-- Nivel -->
                                                        <div class="col-md-3">
                                                            <label for="ddl_niveles" class="form-label fw-bold">
                                                                <i class="bx bx-layer me-2 text-info"></i>Nivel
                                                            </label>
                                                            <select id="ddl_niveles" name="ddl_niveles"
                                                                class="form-control">
                                                                <option value="">-- Todos los niveles --</option>
                                                            </select>
                                                        </div>

                                                        <!-- Área -->
                                                        <div class="col-md-3">
                                                            <label for="ddl_departamentos" class="form-label fw-bold">
                                                                <i
                                                                    class="bx bx-briefcase me-2 text-primary"></i>Departamentos
                                                            </label>
                                                            <select id="ddl_departamentos" class="form-control">
                                                                <option value="">-- Todos los departamentos --</option>
                                                            </select>
                                                        </div>

                                                        <!-- Descripción -->
                                                        <div class="col-12">
                                                            <label for="txt_th_car_descripcion"
                                                                class="form-label fw-bold">
                                                                <i class="bx bx-file me-2 text-warning"></i>Descripción
                                                                del Cargo
                                                            </label>
                                                            <textarea class="form-control" id="txt_th_car_descripcion"
                                                                name="txt_th_car_descripcion" rows="5"
                                                                placeholder="Describa las funciones y responsabilidades del cargo..."></textarea>
                                                            <div class="form-text">Detalle las funciones principales del
                                                                cargo</div>
                                                        </div>

                                                    </div>

                                                    <hr class="my-4">

                                                    <!-- BOTONES -->
                                                    <div class="d-flex justify-content-end gap-2">
                                                        <?php if ($_id == '') { ?>
                                                        <button type="button" class="btn btn-success"
                                                            id="btn_guardar_cargo">
                                                            <i class="bx bx-save me-1"></i> Guardar Cargo
                                                        </button>
                                                        <?php } else { ?>
                                                        <button type="button" class="btn btn-primary"
                                                            id="btn_editar_cargo">
                                                            <i class="bx bx-edit me-1"></i> Actualizar Cargo
                                                        </button>
                                                        <button type="button" class="btn btn-danger"
                                                            id="btn_eliminar_cargo">
                                                            <i class="bx bx-trash me-1"></i> Eliminar Cargo
                                                        </button>
                                                        <?php } ?>
                                                    </div>
                                                </form>

                                            </div>
                                        </section>
                                    </div><!-- /.container-fluid -->

                                    <div class="tab-pane fade" id="primaryprofile" role="tabpanel">
                                        <section class="content pt-0">
                                            <div class="container-fluid">

                                                <!-- Encabezado -->
                                                <div class="row mb-4 align-items-center">
                                                    <div class="col-md-8">
                                                        <h5 class="fw-bold text-primary mb-1">
                                                            <i class="bx bx-sitemap me-2"></i>Aspectos Intrínsecos del
                                                            Cargo
                                                        </h5>
                                                        <small class="text-muted">
                                                            <i class="bi bi-info-circle-fill me-1"></i>
                                                            Estructura organizacional, jerarquía y relaciones del cargo
                                                        </small>
                                                    </div>
                                                    <?php if (isset($_GET['_id'])) { ?>
                                                    <div class="col-md-4 text-end">
                                                        <button type="button" class="btn btn-success btn-sm shadow-sm"
                                                            onclick="abrir_modal_aspectos_intrinsecos()">
                                                            <i class="bx bx-plus-circle me-1"></i> Registrar Aspectos
                                                        </button>
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                                <?php if (isset($_GET['_id'])) { ?>
                                                <!-- Tarjeta Principal con Organigrama -->
                                                <div class="card border-0 shadow-sm">
                                                    <div class="card-body p-4">

                                                        <!-- Organigrama Vertical Moderno -->
                                                        <div class="position-relative">

                                                            <!-- Nivel 1: REPORTA A -->
                                                            <div class="text-center mb-4 position-relative">
                                                                <div
                                                                    class="badge bg-success bg-opacity-25 text-success px-4 py-3 rounded-pill fs-6 fw-semibold shadow-sm">
                                                                    <i class="bi bi-arrow-up-circle-fill me-2"></i>
                                                                    <span id="info_subordinacion" class="text-dark">Sin
                                                                        superior
                                                                        directo</span>
                                                                </div>
                                                                <!-- Línea conectora -->
                                                                <div class="position-absolute start-50 translate-middle-x"
                                                                    style="top: 100%; width: 3px; height: 30px; background: linear-gradient(to bottom, #198754, #0d6efd);">
                                                                </div>
                                                            </div>

                                                            <!-- Nivel 2: CARGO ACTUAL (Principal) -->
                                                            <div class="text-center my-4 position-relative">
                                                                <div class="d-inline-block p-4 bg-gradient rounded-4 shadow border border-primary border-3"
                                                                    style="background: linear-gradient(135deg, #0d6efd15 0%, #0d6efd30 100%);">
                                                                    <div class="mb-3">
                                                                        <i class="bi bi-person-badge-fill text-primary"
                                                                            style="font-size: 3rem;"></i>
                                                                    </div>
                                                                    <h5 class="fw-bold text-primary mb-2">NIVEL DEL
                                                                        CARGO</h5>
                                                                    <span
                                                                        class="badge bg-primary px-4 py-2 fs-6 shadow-sm"
                                                                        id="info_nivel_cargo">
                                                                        No definido
                                                                    </span>
                                                                </div>
                                                                <!-- Líneas conectoras hacia abajo -->
                                                                <div class="d-flex justify-content-center gap-5 position-absolute start-50 translate-middle-x"
                                                                    style="top: 100%; width: 300px;">
                                                                    <div
                                                                        style="width: 3px; height: 30px; background: linear-gradient(to bottom, #0d6efd, #ffc107);">
                                                                    </div>
                                                                    <div
                                                                        style="width: 3px; height: 30px; background: linear-gradient(to bottom, #0d6efd, #0dcaf0);">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Nivel 3: SUPERVISA y COMUNICACIONES -->
                                                            <div class="row g-3 mt-4">

                                                                <!-- SUPERVISA -->
                                                                <div class="col-md-6">
                                                                    <div
                                                                        class="card border-warning border-2 h-100 shadow-sm hover-lift">
                                                                        <div class="card-body text-center p-4">
                                                                            <div class="mb-3">
                                                                                <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                                                                                    style="width: 70px; height: 70px;">
                                                                                    <i class="bi bi-people-fill text-warning"
                                                                                        style="font-size: 2rem;"></i>
                                                                                </div>
                                                                            </div>
                                                                            <h6 class="fw-bold text-warning mb-3">
                                                                                <i
                                                                                    class="bi bi-arrow-down-circle me-1"></i>SUPERVISA
                                                                            </h6>
                                                                            <p class="text-dark mb-0 small lh-base"
                                                                                id="info_supervision">
                                                                                <em class="text-muted">Sin personal a
                                                                                    cargo</em>
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- COMUNICACIONES COLATERALES -->
                                                                <div class="col-md-6">
                                                                    <div
                                                                        class="card border-info border-2 h-100 shadow-sm hover-lift">
                                                                        <div class="card-body text-center p-4">
                                                                            <div class="mb-3">
                                                                                <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                                                                                    style="width: 70px; height: 70px;">
                                                                                    <i class="bi bi-arrows-angle-expand text-info"
                                                                                        style="font-size: 2rem;"></i>
                                                                                </div>
                                                                            </div>
                                                                            <h6 class="fw-bold text-info mb-3">
                                                                                <i
                                                                                    class="bi bi-diagram-3 me-1"></i>COMUNICACIONES
                                                                                COLATERALES
                                                                            </h6>
                                                                            <p class="text-dark mb-0 small lh-base"
                                                                                id="info_comunicaciones">
                                                                                <em class="text-muted">Sin interacciones
                                                                                    definidas</em>
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                        </div>

                                                        <!-- Resumen Compacto en Badges -->
                                                        <div class="mt-4 pt-4 border-top">
                                                            <h6 class="fw-bold text-muted mb-3 text-center">
                                                                <i class="bi bi-diagram-2-fill me-2"></i>Resumen de
                                                                Jerarquía
                                                            </h6>
                                                            <div
                                                                class="d-flex flex-wrap justify-content-center align-items-center gap-2">
                                                                <span
                                                                    class="badge rounded-pill bg-success-subtle text-success px-3 py-2 shadow-sm"
                                                                    id="badge_subordinacion">
                                                                    <i class="bi bi-arrow-up-short"></i> Reporta: -
                                                                </span>
                                                                <i class="bi bi-chevron-right text-muted"></i>
                                                                <span
                                                                    class="badge rounded-pill bg-primary text-white px-3 py-2 shadow-sm"
                                                                    id="badge_nivel">
                                                                    <i class="bi bi-hash"></i> Nivel: -
                                                                </span>
                                                                <i class="bi bi-chevron-right text-muted"></i>
                                                                <span
                                                                    class="badge rounded-pill bg-warning-subtle text-dark px-3 py-2 shadow-sm"
                                                                    id="badge_supervision">
                                                                    <i class="bi bi-arrow-down-short"></i> Supervisa: -
                                                                </span>
                                                            </div>
                                                            <div class="text-center mt-2">
                                                                <span
                                                                    class="badge rounded-pill bg-info-subtle text-info px-3 py-2 shadow-sm"
                                                                    id="badge_comunicaciones">
                                                                    <i class="bi bi-arrows-move"></i> Comunica: -
                                                                </span>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <?php  } ?>

                                            </div>
                                        </section>
                                    </div>




                                    <div class="tab-pane fade" id="secundaryprofile" role="tabpanel">
                                        <section class="content pt-0">
                                            <div class="container-fluid">

                                                <h5 class="fw-bold text-primary mb-3">
                                                    <i class="bx bx-info-circle me-2"></i>Aspectos Extrínsecos del cargo
                                                </h5>
                                                <?php if ($_id != '') { ?>
                                                <button type="button" class="btn btn-success"
                                                    onclick="abrir_modal_cargo_requisitos()">
                                                    <i class="bx bx-plus me-1"></i> Agregar Requisito Detalle
                                                </button>
                                                <?php }?>
                                                </hr>
                                                <?php if ($_id != '') { ?>
                                                <div class="table-responsive">
                                                    <table class="table table-striped responsive" id="tbl_req_detalles"
                                                        style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>Nombre</th>
                                                                <th>Descripción</th>
                                                                <th class="text-center">Oblig.</th>
                                                                <th class="text-center">Acción</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                                <?php }?>
                                            </div><!-- /.container-fluid -->
                                        </section>
                                    </div>




                                    <div class="tab-pane fade" id="complianceprofile" role="tabpanel">
                                        <section class="content pt-0">
                                            <div class="container-fluid">

                                                <!-- Encabezado -->
                                                <div class="row mb-3 align-items-center">
                                                    <div class="col-md-8">
                                                        <h5 class="fw-bold text-primary mb-1">
                                                            <i class="bx bx-check-shield me-2"></i>Compliance del Cargo
                                                        </h5>
                                                        <small class="text-muted">
                                                            <i class="bi bi-clipboard-check me-1"></i>
                                                            Estado de cumplimiento de requisitos y documentación
                                                        </small>
                                                    </div>
                                                    <?php if (isset($_GET['_id'])) { ?>
                                                    <div class="col-md-4 d-flex justify-content-end gap-2">
                                                        <button type="button" class="btn btn-success btn-sm shadow-sm"
                                                            onclick="abrir_modal_funciones()">
                                                            <i class="bx bx-plus-circle me-1"></i> Funciones
                                                        </button>

                                                        <button type="button" class="btn btn-success btn-sm shadow-sm"
                                                            onclick="abrir_modal_compliance()">
                                                            <i class="bx bx-plus-circle me-1"></i> Actualizar Compliance
                                                        </button>
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                                <?php if (isset($_GET['_id'])) { ?>
                                                <!-- Tarjeta Principal de Compliance -->
                                                <div class="card border-0 shadow-sm">
                                                    <div class="card-body p-4">

                                                        <!-- Indicador de Progreso Principal -->
                                                        <div class="row mb-4">
                                                            <div class="col-12">
                                                                <div class="text-center p-4 bg-light rounded-3 border">
                                                                    <h3 class="fw-bold text-primary mb-2"
                                                                        id="comp_porcentaje">
                                                                        0%</h3>
                                                                    <p class="text-muted mb-3 small">Porcentaje de
                                                                        Completitud
                                                                    </p>

                                                                    <div class="progress" style="height: 25px;">
                                                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                                                            id="comp_progress_bar" role="progressbar"
                                                                            style="width: 0%;" aria-valuenow="0"
                                                                            aria-valuemin="0" aria-valuemax="100">
                                                                            <span class="fw-bold"
                                                                                id="comp_progress_text">0%</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Estadísticas Rápidas -->
                                                        <div class="row g-3 mb-4">

                                                            <!-- Requisitos Totales -->
                                                            <div class="col-md-4">
                                                                <div
                                                                    class="card border-primary border-2 h-100 shadow-sm">
                                                                    <div class="card-body text-center p-3">
                                                                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                                                            style="width: 50px; height: 50px;">
                                                                            <i class="bi bi-list-check text-primary"
                                                                                style="font-size: 1.5rem;"></i>
                                                                        </div>
                                                                        <h4 class="fw-bold text-primary mb-1"
                                                                            id="comp_totales">
                                                                            0</h4>
                                                                        <small class="text-muted">Requisitos
                                                                            Totales</small>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Requisitos Completados -->
                                                            <div class="col-md-4">
                                                                <div
                                                                    class="card border-success border-2 h-100 shadow-sm">
                                                                    <div class="card-body text-center p-3">
                                                                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                                                            style="width: 50px; height: 50px;">
                                                                            <i class="bi bi-check-circle-fill text-success"
                                                                                style="font-size: 1.5rem;"></i>
                                                                        </div>
                                                                        <h4 class="fw-bold text-success mb-1"
                                                                            id="comp_completados">
                                                                            0</h4>
                                                                        <small class="text-muted">Completados</small>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Requisitos Faltantes -->
                                                            <div class="col-md-4">
                                                                <div
                                                                    class="card border-danger border-2 h-100 shadow-sm">
                                                                    <div class="card-body text-center p-3">
                                                                        <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                                                            style="width: 50px; height: 50px;">
                                                                            <i class="bi bi-exclamation-circle-fill text-danger"
                                                                                style="font-size: 1.5rem;"></i>
                                                                        </div>
                                                                        <h4 class="fw-bold text-danger mb-1"
                                                                            id="comp_faltantes">0
                                                                        </h4>
                                                                        <small class="text-muted">Faltantes</small>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <!-- Información Adicional -->
                                                        <div class="row g-3">

                                                            <!-- Última Revisión y Estado -->
                                                            <div class="col-md-6">
                                                                <div
                                                                    class="p-3 bg-light rounded border-start border-info border-4">
                                                                    <div class="d-flex align-items-center mb-2">
                                                                        <i class="bi bi-calendar-check text-info me-2"
                                                                            style="font-size: 1.2rem;"></i>
                                                                        <strong class="text-dark">Última
                                                                            Revisión:</strong>
                                                                    </div>
                                                                    <p class="mb-0 text-muted small"
                                                                        id="comp_ultima_revision">
                                                                        <em>No registrada</em>
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div
                                                                    class="p-3 bg-light rounded border-start border-warning border-4">
                                                                    <div class="d-flex align-items-center mb-2">
                                                                        <i class="bi bi-flag text-warning me-2"
                                                                            style="font-size: 1.2rem;"></i>
                                                                        <strong class="text-dark">Estado:</strong>
                                                                    </div>
                                                                    <span class="badge bg-secondary"
                                                                        id="comp_estado_badge">
                                                                        <i class="bi bi-circle-fill me-1"></i>Sin estado
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <!-- Observaciones -->
                                                            <div class="col-12">
                                                                <div
                                                                    class="p-3 bg-light rounded border-start border-primary border-4">
                                                                    <div class="d-flex align-items-center mb-2">
                                                                        <i class="bi bi-chat-left-text text-primary me-2"
                                                                            style="font-size: 1.2rem;"></i>
                                                                        <strong
                                                                            class="text-dark">Observaciones:</strong>
                                                                    </div>
                                                                    <p class="mb-0 text-muted small"
                                                                        id="comp_observaciones">
                                                                        <em>Sin observaciones registradas</em>
                                                                    </p>
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <!-- Badge de Resumen -->
                                                        <div class="mt-3 pt-3 border-top text-center">
                                                            <span
                                                                class="badge rounded-pill bg-info-subtle text-info px-3 py-2 me-2"
                                                                id="badge_resumen">
                                                                <i class="bi bi-graph-up me-1"></i>
                                                                Completados: 0 de 0 (0%)
                                                            </span>
                                                        </div>

                                                    </div>
                                                </div>
                                                <?php } ?>



                                            </div>
                                            <?php if (isset($_GET['_id'])) { ?>
                                            <div class="row mb-3 align-items-center">
                                                <div class="col-md-8">
                                                    <h5 class="fw-bold text-primary mb-1">
                                                        <i class="bx bx-list-check me-2"></i>Funciones del Cargo
                                                    </h5>
                                                    <small class="text-muted">
                                                        <i class="bi bi-briefcase me-1"></i>
                                                        Listado de funciones y responsabilidades del cargo
                                                    </small>
                                                </div>
                                            </div>

                                            <!-- Tarjeta con Tabla -->
                                            <div class="card border-0 shadow-sm">
                                                <div class="card-body p-0">

                                                    <!-- Tabla de Funciones -->
                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-striped mb-0"
                                                            id="tabla_funciones">
                                                            <thead class="bg-light">
                                                                <tr>
                                                                    <th class="text-center" style="width: 50px;">#</th>
                                                                    <th><i class="bx bx-notepad me-1"></i> Función</th>
                                                                    <th><i class="bx bx-time me-1"></i> Frecuencia</th>
                                                                    <th class="text-center"><i
                                                                            class="bx bx-pie-chart-alt-2 me-1"></i> %
                                                                        Tiempo</th>
                                                                    <th class="text-center"><i
                                                                            class="bx bx-star me-1"></i>
                                                                        Tipo</th>
                                                                    <th class="text-center"><i
                                                                            class="bx bx-cog me-1"></i>
                                                                        Acciones</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="tbody_funciones">
                                                                <!-- Se cargará dinámicamente -->
                                                                <tr>
                                                                    <td colspan="6" class="text-center py-4 text-muted">
                                                                        <i class="bx bx-loader bx-spin"
                                                                            style="font-size: 2rem;"></i>
                                                                        <p class="mb-0 mt-2">Cargando funciones...</p>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <!-- Footer con estadísticas -->
                                                    <div class="card-footer bg-light">
                                                        <div class="row text-center">
                                                            <div class="col-md-3">
                                                                <small class="text-muted d-block">Total
                                                                    Funciones</small>
                                                                <strong class="text-primary" id="stat_total">0</strong>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <small class="text-muted d-block">Principales</small>
                                                                <strong class="text-warning"
                                                                    id="stat_principales">0</strong>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <small class="text-muted d-block">Secundarias</small>
                                                                <strong class="text-info"
                                                                    id="stat_secundarias">0</strong>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <small class="text-muted d-block">% Asignado</small>
                                                                <strong class="text-success"
                                                                    id="stat_porcentaje_total">0%</strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                        </section>
                                    </div>
                                    <div class="tab-pane fade" id="competencias" role="tabpanel">

                                        <section class="content pt-0">
                                            <div class="container-fluid">

                                                <div class="row mb-3 align-items-center">

                                                    <!-- Título -->
                                                    <div class="col-md-8">
                                                        <h5 class="fw-bold text-primary mb-1">
                                                            <i class="bx bx-check-shield me-2"></i> Competencias
                                                        </h5>

                                                        <small class="text-muted">
                                                            <i class="bi bi-clipboard-check me-1"></i>
                                                            Estado de cumplimiento de habilidades y competencias
                                                        </small>
                                                    </div>
                                                    <?php if (isset($_GET['_id'])) { ?>
                                                    <!-- Botonera -->
                                                    <div class="col-md-4 d-flex justify-content-end gap-2"
                                                        id="pnl_competencias_botonera">
                                                        <button type="button" class="btn btn-success btn-sm shadow-sm"
                                                            id="btn_abrir_modal_competencias"
                                                            onclick="abrir_modal_competencias()">
                                                            <i class="bx bx-plus-circle me-1"></i> Competencias
                                                        </button>
                                                    </div>
                                                    <?php } ?>

                                                </div>
                                                <?php if (isset($_GET['_id'])) { ?>
                                                <!-- Aquí puedes colocar la tabla -->
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="table-responsive">
                                                            <table id="tbl_competencias"
                                                                class="table table-bordered table-striped w-100">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Competencia</th>
                                                                        <th>Nivel Requerido</th>
                                                                        <th>Ponderación</th>
                                                                        <th>Crítica</th>
                                                                        <th>Acciones</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody></tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php } ?>

                                            </div>
                                        </section>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>


<div class="modal fade" id="modal_cargo_competencia" tabindex="-1" aria-labelledby="lbl_modal_cargo_competencia"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-modal="true">

    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="lbl_modal_cargo_competencia">
                    <i class="bx bx-brain me-2"></i> Registrar Competencia del Cargo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Body -->
            <div class="modal-body p-4">
                <form id="form_cargo_competencia">

                    <!-- IDs ocultos -->
                    <input type="hidden" id="txt_th_carcomp_id" name="th_carcomp_id">
                    <input type="hidden" id="txt_th_car_id" name="th_car_id">

                    <!-- Competencia -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">
                            <i class="bx bx-target-lock text-primary me-1"></i> Competencia
                        </label>
                        <select class="form-select select2-validation" id="ddl_competencias" name="ddl_competencias"
                            required>
                            <option value="" hidden selected>-- Seleccione la competencia --</option>
                        </select>
                    </div>

                    <!-- Niveles -->
                    <div class="row g-3 border rounded p-3 mb-3 bg-light">
                        <h6 class="fw-bold text-secondary">Niveles de competencia</h6>

                        <div class="col-md-3">
                            <label class="form-label">Nivel Requerido</label>
                            <input type="number" min="0" max="100" class="form-control"
                                id="txt_th_carcomp_nivel_requerido" name="th_carcomp_nivel_requerido">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Nivel Utilización</label>
                            <input type="number" min="0" max="100" class="form-control"
                                id="txt_th_carcomp_nivel_utilizacion" name="th_carcomp_nivel_utilizacion">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Nivel Contribución</label>
                            <input type="number" min="0" max="100" class="form-control"
                                id="txt_th_carcomp_nivel_contribucion" name="th_carcomp_nivel_contribucion">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Nivel Habilidad</label>
                            <input type="number" min="0" max="100" class="form-control"
                                id="txt_th_carcomp_nivel_habilidad" name="th_carcomp_nivel_habilidad">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Nivel Maestría</label>
                            <input type="number" min="0" max="100" class="form-control"
                                id="txt_th_carcomp_nivel_maestria" name="th_carcomp_nivel_maestria">
                        </div>
                    </div>

                    <!-- DISC -->
                    <div class="row g-3 border rounded p-3 mb-3">
                        <h6 class="fw-bold text-secondary">Valores DISC</h6>

                        <div class="col-md-3">
                            <label class="form-label">Valor D</label>
                            <input type="number" class="form-control" id="txt_th_carcomp_disc_valor_d"
                                name="th_carcomp_disc_valor_d">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Valor I</label>
                            <input type="number" class="form-control" id="txt_th_carcomp_disc_valor_i"
                                name="th_carcomp_disc_valor_i">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Valor S</label>
                            <input type="number" class="form-control" id="txt_th_carcomp_disc_valor_s"
                                name="th_carcomp_disc_valor_s">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Valor C</label>
                            <input type="number" class="form-control" id="txt_th_carcomp_disc_valor_c"
                                name="th_carcomp_disc_valor_c">
                        </div>
                    </div>

                    <!-- Evaluación -->
                    <div class="row g-3 border rounded p-3 mb-3">
                        <h6 class="fw-bold text-secondary">Evaluación</h6>

                        <div class="col-md-3">
                            <label class="form-label">Es Crítica</label>
                            <select id="ddl_th_carcomp_es_critica" name="th_carcomp_es_critica" class="form-select">
                                <option value="0">No</option>
                                <option value="1">Sí</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Es Evaluable</label>
                            <select id="ddl_th_carcomp_es_evaluable" name="th_carcomp_es_evaluable" class="form-select">
                                <option value="0">No</option>
                                <option value="1">Sí</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Método Evaluación</label>
                            <input type="text" class="form-control" id="txt_th_carcomp_metodo_evaluacion"
                                name="th_carcomp_metodo_evaluacion" placeholder="Ej: entrevista, prueba técnica...">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Ponderación (%)</label>
                            <input type="number" min="0" max="100" class="form-control" id="txt_th_carcomp_ponderacion"
                                name="th_carcomp_ponderacion">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Observaciones</label>
                            <textarea class="form-control" id="txt_th_carcomp_observaciones"
                                name="th_carcomp_observaciones" rows="2"></textarea>
                        </div>
                    </div>

                </form>
            </div>

            <!-- Footer -->
            <div class="modal-footer justify-content-end gap-2">

                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="bx bx-x me-1"></i> Cerrar
                </button>

                <div id="pnl_crear">
                    <button type="button" class="btn btn-success" onclick="insertar_cargo_competencia()">
                        <i class="bx bx-save me-1"></i> Crear
                    </button>
                </div>

                <div id="pnl_actualizar" style="display:none;">
                    <button type="button" class="btn btn-danger" onclick="eliminar_cargo_competencia()">
                        <i class="bx bx-trash me-1"></i> Eliminar
                    </button>

                    <button type="button" class="btn btn-primary" onclick="actualizar_cargo_competencia()">
                        <i class="bx bx-check me-1"></i> Actualizar
                    </button>
                </div>

            </div>

        </div>
    </div>
</div>




<div class="modal fade" id="modal_cargo_requisito" tabindex="-1" aria-labelledby="modalRequisitoLabel"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="modalRequisitoLabel">
                    <i class="bx bx-list-check me-2"></i> Registrar Requisito
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body p-4">
                <form id="form_requisito">
                    <div class="col-md-12">
                        <label for="ddl_cargo_requisito" class="form-label fw-bold">
                            <i class="bx bx-briefcase me-2 text-primary"></i> Requisito
                        </label>
                        <select class="form-select select2-validation" id="ddl_cargo_requisito"
                            name="ddl_cargo_requisito" required>
                            <option value="" selected hidden>-- Seleccione el requisito --</option>
                        </select>
                    </div>
                    <!-- Botones de acción -->
                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            <i class="bx bx-x me-1"></i> Cancelar
                        </button>

                        <div id="pnl_crear">
                            <button type="button" class="btn btn-success" onclick="insertar_cargo_requisito()">
                                <i class="bx bx-save me-1"></i> Crear Requisito
                            </button>
                        </div>

                        <div id="pnl_actualizar" style="display:none">
                            <button type="button" class="btn btn-danger" id="btn_eliminar_req">
                                <i class="bx bx-trash me-1"></i> Eliminar
                            </button>
                            <button type="button" class="btn btn-primary" id="btn_editar_req">
                                <i class="bx bx-check me-1"></i> Actualizar Requisito
                            </button>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>



<div class="modal fade" id="modal_aspectos_intrinsecos" tabindex="-1" aria-labelledby="modalAspectosLabel"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="modalAspectosLabel">
                    <i class="bx bx-list-check me-2"></i> Registrar Aspectos Intrínsecos
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body p-4">
                <form id="form_aspectos_intrinsecos">
                    <input type="hidden" id="th_carasp_id" name="th_carasp_id">
                    <input type="hidden" id="th_car_id" name="th_car_id">

                    <div class="row g-3">
                        <!-- Nivel del Cargo -->
                        <div class="col-md-12">
                            <label for="txt_nivel_cargo" class="form-label fw-bold">
                                <i class="bx bx-layer me-2 text-primary"></i> Nivel del Cargo
                            </label>
                            <select class="form-select form-select-md shadow-sm" id="th_carasp_nivel_cargo">
                                <option value="">Seleccione...</option>
                                <option value="1">Nivel 1 - Alta Dirección</option>
                                <option value="2">Nivel 2 - Gerencia</option>
                                <option value="3">Nivel 3 - Jefatura/Coordinación</option>
                                <option value="4">Nivel 4 - Supervisión</option>
                                <option value="5">Nivel 5 - Operativo/Técnico</option>
                                <option value="6">Nivel 6 - Auxiliar/Asistente</option>
                            </select>
                        </div>

                        <!-- Subordinación -->
                        <div class="col-md-12">
                            <label for="txt_subordinacion" class="form-label fw-bold">
                                <i class="bx bx-sitemap me-2 text-info"></i> Subordinación
                            </label>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="chk_subordinacion_empresa">
                                <label class="form-check-label" for="chk_subordinacion_empresa">
                                    Pertenece a la empresa
                                </label>
                            </div>

                            <div id="div_subordinacion_select" style="display:none;">
                                <select id="ddl_subordinacion" name="ddl_subordinacion"
                                    class="form-select select2-validation">
                                    <option value="" selected hidden>-- Seleccione el responsable --</option>
                                </select>
                            </div>

                            <div id="div_subordinacion_input">
                                <textarea class="form-control" id="txt_subordinacion" name="txt_subordinacion" rows="2"
                                    placeholder="Indique a quién reporta este cargo"></textarea>
                            </div>
                        </div>

                        <!-- Supervisión -->
                        <div class="col-md-12">
                            <label for="txt_supervision" class="form-label fw-bold">
                                <i class="bx bx-user-check me-2 text-success"></i> Supervisión
                            </label>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="chk_supervision_empresa">
                                <label class="form-check-label" for="chk_supervision_empresa">
                                    Pertenece a la empresa
                                </label>
                            </div>

                            <div id="div_supervision_select" style="display:none;">
                                <select id="ddl_supervision" name="ddl_supervision"
                                    class="form-select select2-validation">
                                    <option value="" selected hidden>-- Seleccione el personal supervisado --</option>
                                </select>
                            </div>

                            <div id="div_supervision_input">
                                <textarea class="form-control" id="txt_supervision" name="txt_supervision" rows="2"
                                    placeholder="Describa qué cargos o personal supervisa"></textarea>
                            </div>
                        </div>

                        <!-- Comunicaciones Colaterales -->
                        <div class="col-md-12">
                            <label for="txt_comunicaciones" class="form-label fw-bold">
                                <i class="bx bx-conversation me-2 text-warning"></i> Comunicaciones Colaterales
                            </label>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="chk_comunicaciones_empresa">
                                <label class="form-check-label" for="chk_comunicaciones_empresa">
                                    Pertenece a la empresa
                                </label>
                            </div>

                            <div id="div_comunicaciones_select" style="display:none;">
                                <select id="ddl_comunicaciones" name="ddl_comunicaciones"
                                    class="form-select select2-validation">
                                    <option value="" selected hidden>-- Seleccione las áreas/cargos --</option>
                                </select>
                            </div>

                            <div id="div_comunicaciones_input">
                                <textarea class="form-control" id="txt_comunicaciones" name="txt_comunicaciones"
                                    rows="3"
                                    placeholder="Indique con qué áreas o cargos del mismo nivel se comunica"></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="d-flex justify-content-end gap-2 pt-3 mt-3 border-top">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            <i class="bx bx-x me-1"></i> Cancelar
                        </button>

                        <div id="pnl_crear_aspecto">
                            <button type="button" class="btn btn-success">
                                <i class="bx bx-save me-1"></i> Guardar Aspectos
                            </button>
                        </div>

                        <div id="pnl_actualizar_aspecto" style="display:none">
                            <button type="button" class="btn btn-danger" id="btn_eliminar_aspecto">
                                <i class="bx bx-trash me-1"></i> Eliminar
                            </button>
                            <button type="button" class="btn btn-primary" id="btn_editar_aspecto">
                                <i class="bx bx-check me-1"></i> Actualizar Aspectos
                            </button>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>


<div class="modal fade" id="modal_compliance" tabindex="-1" aria-labelledby="modalComplianceLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="modalComplianceLabel">
                    <i class="bx bx-check-shield me-2"></i> Registrar Compliance del Cargo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body p-4">
                <form id="form_compliance">
                    <input type="hidden" id="th_comp_id" name="th_comp_id">
                    <input type="hidden" id="th_car_id_comp" name="th_car_id_comp">

                    <div class="row g-3">

                        <!-- Porcentaje Completado -->
                        <div class="col-md-6">
                            <label for="th_comp_porcentaje_completado" class="form-label fw-bold">
                                <i class="bx bx-pie-chart-alt-2 me-2 text-primary"></i> Porcentaje Completado
                                (%)
                            </label>
                            <input type="number" class="form-control" id="th_comp_porcentaje_completado"
                                name="th_comp_porcentaje_completado" min="0" max="100" step="0.01" placeholder="0.00"
                                readonly>
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i> Calculado automáticamente
                            </small>
                        </div>

                        <!-- Requisitos Totales -->
                        <div class="col-md-6">
                            <label for="th_comp_requisitos_totales" class="form-label fw-bold">
                                <i class="bx bx-list-ul me-2 text-info"></i> Requisitos Totales
                            </label>
                            <input type="number" class="form-control" id="th_comp_requisitos_totales"
                                name="th_comp_requisitos_totales" min="0" placeholder="Ej: 10" required>
                        </div>

                        <!-- Requisitos Completados -->
                        <div class="col-md-6">
                            <label for="th_comp_requisitos_completados" class="form-label fw-bold">
                                <i class="bx bx-check-circle me-2 text-success"></i> Requisitos Completados
                            </label>
                            <input type="number" class="form-control" id="th_comp_requisitos_completados"
                                name="th_comp_requisitos_completados" min="0" placeholder="Ej: 7" required>
                        </div>

                        <!-- Requisitos Faltantes -->
                        <div class="col-md-6">
                            <label for="th_comp_requisitos_faltantes" class="form-label fw-bold">
                                <i class="bx bx-error-circle me-2 text-danger"></i> Requisitos Faltantes
                            </label>
                            <input type="number" class="form-control" id="th_comp_requisitos_faltantes"
                                name="th_comp_requisitos_faltantes" min="0" placeholder="Ej: 3" readonly>
                            <small class="text-muted">
                                <i class="bi bi-calculator"></i> Calculado automáticamente
                            </small>
                        </div>

                        <!-- Última Revisión -->
                        <div class="col-md-6">
                            <label for="th_comp_ultima_revision" class="form-label fw-bold">
                                <i class="bx bx-calendar me-2 text-warning"></i> Última Revisión
                            </label>
                            <input type="date" class="form-control" id="th_comp_ultima_revision"
                                name="th_comp_ultima_revision">
                        </div>

                        <!-- Estado -->


                        <!-- Observaciones -->
                        <div class="col-md-12">
                            <label for="th_comp_observaciones" class="form-label fw-bold">
                                <i class="bx bx-message-detail me-2 text-info"></i> Observaciones
                            </label>
                            <textarea class="form-control" id="th_comp_observaciones" name="th_comp_observaciones"
                                rows="3" placeholder="Ingrese observaciones o notas adicionales..."></textarea>
                        </div>

                        <!-- Vista Previa del Cálculo -->
                        <div class="col-12">
                            <div class="alert alert-info mb-0">
                                <h6 class="fw-bold mb-2">
                                    <i class="bi bi-calculator me-2"></i>Vista Previa del Cálculo:
                                </h6>
                                <div class="row text-center g-2">
                                    <div class="col-4">
                                        <small class="text-muted d-block">Completados</small>
                                        <strong class="text-success" id="preview_completados">0</strong>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted d-block">Faltantes</small>
                                        <strong class="text-danger" id="preview_faltantes">0</strong>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted d-block">Porcentaje</small>
                                        <strong class="text-primary" id="preview_porcentaje">0%</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Botones de acción -->
                    <div class="d-flex justify-content-end gap-2 pt-3 mt-3 border-top">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            <i class="bx bx-x me-1"></i> Cancelar
                        </button>

                        <div id="pnl_crear_compliance">
                            <button type="button" class="btn btn-success" onclick="insertar_compliance()">
                                <i class="bx bx-save me-1"></i> Guardar Compliance
                            </button>
                        </div>

                        <div id="pnl_actualizar_compliance" style="display:none">
                            <button type="button" class="btn btn-danger" id="btn_eliminar_compliance">
                                <i class="bx bx-trash me-1"></i> Eliminar
                            </button>
                            <button type="button" class="btn btn-primary" id="btn_editar_compliance">
                                <i class="bx bx-check me-1"></i> Actualizar Compliance
                            </button>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>


<div class="modal fade" id="modal_funciones_cargo" tabindex="-1" aria-labelledby="modalFuncionesLabel"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalFuncionesLabel">
                    <i class="bx bx-list-check me-2"></i> Registrar Función del Cargo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body p-4">
                <form id="form_funciones_cargo">
                    <input type="hidden" id="th_carfun_id" name="th_carfun_id">
                    <input type="hidden" id="th_car_id_funcion" name="th_car_id_funcion">

                    <div class="row g-3">

                        <!-- Nombre de la Función -->
                        <div class="col-md-12">
                            <label for="th_carfun_nombre" class="form-label fw-bold">
                                <i class="bx bx-notepad me-2 text-primary"></i> Nombre de la Función
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="th_carfun_nombre" name="th_carfun_nombre"
                                placeholder="Ej: Supervisión de equipos de trabajo" required>
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i> Título breve y descriptivo de la función
                            </small>
                        </div>

                        <!-- Descripción -->
                        <div class="col-md-12">
                            <label for="th_carfun_descripcion" class="form-label fw-bold">
                                <i class="bx bx-message-detail me-2 text-info"></i> Descripción
                            </label>
                            <textarea class="form-control" id="th_carfun_descripcion" name="th_carfun_descripcion"
                                rows="4"
                                placeholder="Detalle las actividades y responsabilidades específicas de esta función..."></textarea>
                            <small class="text-muted">
                                <i class="bi bi-pencil"></i> Describa detalladamente las actividades de esta función
                            </small>
                        </div>

                        <!-- Frecuencia -->
                        <div class="col-md-6">
                            <label for="th_carfun_frecuencia" class="form-label fw-bold">
                                <i class="bx bx-time me-2 text-warning"></i> Frecuencia
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="th_carfun_frecuencia" name="th_carfun_frecuencia" required>
                                <option value="">-- Seleccione frecuencia --</option>
                                <option value="Diaria">Diaria</option>
                                <option value="Semanal">Semanal</option>
                                <option value="Quincenal">Quincenal</option>
                                <option value="Mensual">Mensual</option>
                                <option value="Trimestral">Trimestral</option>
                                <option value="Semestral">Semestral</option>
                                <option value="Anual">Anual</option>
                                <option value="Eventual">Eventual</option>
                                <option value="Permanente">Permanente</option>
                            </select>
                        </div>

                        <!-- Porcentaje de Tiempo -->
                        <div class="col-md-6">
                            <label for="th_carfun_porcentaje_tiempo" class="form-label fw-bold">
                                <i class="bx bx-pie-chart-alt-2 me-2 text-success"></i> Porcentaje de Tiempo (%)
                                <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control" id="th_carfun_porcentaje_tiempo"
                                name="th_carfun_porcentaje_tiempo" min="0" max="100" step="0.01" placeholder="Ej: 25.5"
                                required>
                            <small class="text-muted">
                                <i class="bi bi-calculator"></i> % del tiempo dedicado a esta función (0-100)
                            </small>
                        </div>

                        <!-- Es Función Principal -->
                        <div class="col-md-6">
                            <label for="th_carfun_es_principal" class="form-label fw-bold">
                                <i class="bx bx-star me-2 text-warning"></i> ¿Es Función Principal?
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="th_carfun_es_principal" name="th_carfun_es_principal"
                                required>
                                <option value="">-- Seleccione --</option>
                                <option value="1">Sí, es función principal</option>
                                <option value="0">No, es función secundaria</option>
                            </select>
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i> Indica si es una función primaria del cargo
                            </small>
                        </div>

                        <!-- Orden de Prioridad -->
                        <div class="col-md-6">
                            <label for="th_carfun_orden" class="form-label fw-bold">
                                <i class="bx bx-sort-alt-2 me-2 text-secondary"></i> Orden de Prioridad
                            </label>
                            <input type="number" class="form-control" id="th_carfun_orden" name="th_carfun_orden"
                                min="1" placeholder="Ej: 1, 2, 3..." value="1">
                            <small class="text-muted">
                                <i class="bi bi-sort-numeric-down"></i> Orden en que aparecerá la función (menor número
                                = mayor prioridad)
                            </small>
                        </div>

                        <!-- Resumen Visual -->
                        <div class="col-12">
                            <div class="alert alert-light border">
                                <h6 class="fw-bold mb-3">
                                    <i class="bi bi-eye me-2"></i>Vista Previa:
                                </h6>
                                <div class="row g-2">
                                    <div class="col-md-3">
                                        <small class="text-muted d-block">Frecuencia</small>
                                        <strong id="preview_frecuencia" class="text-primary">-</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted d-block">% Tiempo</small>
                                        <strong id="preview_porcentaje" class="text-success">0%</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted d-block">Tipo</small>
                                        <span id="preview_tipo" class="badge bg-secondary">-</span>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted d-block">Prioridad</small>
                                        <strong id="preview_orden" class="text-dark">#1</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Botones de acción -->
                    <div class="d-flex justify-content-end gap-2 pt-3 mt-3 border-top">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            <i class="bx bx-x me-1"></i> Cancelar
                        </button>

                        <div id="pnl_crear_funcion">
                            <button type="button" class="btn btn-success" onclick="guardar_o_actualizar_funcion()">
                                <i class="bx bx-save me-1"></i> Guardar Función
                            </button>
                        </div>

                        <div id="pnl_actualizar_funcion" style="display:none">
                            <button type="button" class="btn btn-danger" id="btn_eliminar_funcion">
                                <i class="bx bx-trash me-1"></i> Eliminar
                            </button>
                            <button type="button" class="btn btn-primary" id="btn_editar_funcion">
                                <i class="bx bx-check me-1"></i> Actualizar Función
                            </button>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

<script>
// JavaScript para manejar la funcionalidad de los checkboxes
$(document).ready(function() {
    // Subordinación
    $('#chk_subordinacion_empresa').on('change', function() {
        if ($(this).is(':checked')) {
            $('#div_subordinacion_select').show();
            $('#div_subordinacion_input').hide();
            $('#txt_subordinacion').removeAttr('required');
            $('#ddl_subordinacion').attr('required', 'required');
        } else {
            $('#div_subordinacion_select').hide();
            $('#div_subordinacion_input').show();
            $('#ddl_subordinacion').removeAttr('required');
            $('#txt_subordinacion').attr('required', 'required');
        }
    });

    // Supervisión
    $('#chk_supervision_empresa').on('change', function() {
        if ($(this).is(':checked')) {
            $('#div_supervision_select').show();
            $('#div_supervision_input').hide();
            $('#txt_supervision').removeAttr('required');
            $('#ddl_supervision').attr('required', 'required');
        } else {
            $('#div_supervision_select').hide();
            $('#div_supervision_input').show();
            $('#ddl_supervision').removeAttr('required');
            $('#txt_supervision').attr('required', 'required');
        }
    });

    // Comunicaciones Colaterales
    $('#chk_comunicaciones_empresa').on('change', function() {
        if ($(this).is(':checked')) {
            $('#div_comunicaciones_select').show();
            $('#div_comunicaciones_input').hide();
            $('#txt_comunicaciones').removeAttr('required');
            $('#ddl_comunicaciones').attr('required', 'required');
        } else {
            $('#div_comunicaciones_select').hide();
            $('#div_comunicaciones_input').show();
            $('#ddl_comunicaciones').removeAttr('required');
            $('#txt_comunicaciones').attr('required', 'required');
        }
    });
});
</script>

<script>
$(function() {
    // Actualizar vista previa de frecuencia
    $('#th_carfun_frecuencia').on('change', function() {
        const valor = $(this).val();
        $('#preview_frecuencia').text(valor || '-');
    });

    // Actualizar vista previa de porcentaje
    $('#th_carfun_porcentaje_tiempo').on('input change', function() {
        const valor = parseFloat($(this).val()) || 0;
        $('#preview_porcentaje').text(valor.toFixed(2) + '%');
    });

    // Actualizar vista previa de tipo (principal/secundaria)
    $('#th_carfun_es_principal').on('change', function() {
        const valor = $(this).val();
        const $badge = $('#preview_tipo');

        if (valor === '1') {
            $badge.removeClass('bg-secondary bg-info')
                .addClass('bg-warning')
                .html('<i class="bx bx-star me-1"></i>Principal');
        } else if (valor === '0') {
            $badge.removeClass('bg-secondary bg-warning')
                .addClass('bg-info')
                .html('<i class="bx bx-checkbox-checked me-1"></i>Secundaria');
        } else {
            $badge.removeClass('bg-warning bg-info')
                .addClass('bg-secondary')
                .text('-');
        }
    });

    // Actualizar vista previa de orden
    $('#th_carfun_orden').on('input change', function() {
        const valor = parseInt($(this).val()) || 1;
        $('#preview_orden').text('#' + valor);
    });
});

// ============================================
// FUNCIÓN PARA ABRIR MODAL DE NUEVA FUNCIÓN
// ============================================
function abrir_modal_funcion(cargoId) {
    // Limpiar formulario
    $('#form_funciones_cargo')[0].reset();
    $('#th_carfun_id').val('');
    $('#th_car_id_funcion').val(cargoId);

    // Resetear vista previa
    $('#preview_frecuencia').text('-');
    $('#preview_porcentaje').text('0%');
    $('#preview_tipo').removeClass('bg-warning bg-info').addClass('bg-secondary').text('-');
    $('#preview_orden').text('#1');

    // Modo crear
    $('#pnl_crear_funcion').show();
    $('#pnl_actualizar_funcion').hide();
    $('#modalFuncionesLabel').html('<i class="bx bx-list-check me-2"></i> Registrar Función del Cargo');

    // Abrir modal
    $('#modal_funciones_cargo').modal('show');
}
</script>