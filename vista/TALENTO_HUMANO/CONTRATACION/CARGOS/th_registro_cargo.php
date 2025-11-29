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
    <?php } ?>

    cargar_selects2();

    function cargar_selects2() {
        url_departamentosC = '../controlador/TALENTO_HUMANO/th_departamentosC.php?buscar=true';
        cargar_select2_url('ddl_departamentos', url_departamentosC);
        url_nivelesC = '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_niveles_cargoC.php?buscar=true';
        cargar_select2_url('ddl_niveles', url_nivelesC);
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
                // normalizar a array
                if (!response) {
                    mostrarAspectosVacios();
                    return;
                }
                var rows = Array.isArray(response) ? response : [response];
                if (rows.length === 0) {
                    mostrarAspectosVacios();
                    return;
                }

                cargar_aspecto_en_modal(response[0]);

                // Si hay varios registros, concatenamos con saltos de línea
                var subordinaciones = [];
                var niveles = [];
                var supervisiones = [];
                var comunicacioness = [];

                rows.forEach(function(r) {
                    subordinaciones.push(r.subordinacion || r.th_carasp_subordinacion ||
                        'Sin especificar');
                    niveles.push(nivelLabel(r.nivel_cargo || r.th_carasp_nivel_cargo || r
                        .nivel));
                    supervisiones.push(r.supervision || r.th_carasp_supervision ||
                        'Sin especificar');
                    comunicacioness.push(r.comunicaciones_colaterales || r
                        .th_carasp_comunicaciones_colaterales || 'Sin especificar');
                });

                // Renderizar (usar <br> para separar si hay múltiples)
                $('#info_subordinacion').html(subordinaciones.join('<br>'));
                $('#info_nivel_cargo').text(niveles.join(' / ')); // compacto
                $('#info_supervision').html(supervisiones.join('<br>'));
                $('#info_comunicaciones').html(comunicacioness.join('<br>'));

                // Badges
                $('#badge_subordinacion').html('<i class="bi bi-arrow-up"></i> Reporta a: ' + (
                    subordinaciones.join(', ')));
                $('#badge_nivel').html('<i class="bi bi-person-badge"></i> Nivel: ' + ((rows
                    .length === 1) ? niveles[0] : niveles.join(' / ')));
                $('#badge_supervision').html('<i class="bi bi-arrow-down"></i> Supervisa: ' + (
                    supervisiones.join(', ')));
                $('#badge_comunicaciones').html('<i class="bi bi-arrows"></i> Comunica con: ' + (
                    comunicacioness.join(', ')));
            },
            error: function(err) {
                console.error(err);
                mostrarAspectosVacios();
            }
        });
    }

    function cargar_aspecto_en_modal(r) {
        // acepta distintos alias por si tu respuesta usa nombres distintos
        var id = r._id || r.th_carasp_id || r.id || '';
        var car_id = r.car_id || r.th_car_id || r.th_car || '';
        var nivel = r.nivel_cargo || r.th_carasp_nivel_cargo || r.nivel || '';
        var subordinacion = r.subordinacion || r.th_carasp_subordinacion || r.subordinacion_campo || '';
        var supervision = r.supervision || r.th_carasp_supervision || r.supervision_campo || '';
        var comunicaciones = r.comunicaciones_colaterales || r.th_carasp_comunicaciones_colaterales || r
            .comunicaciones || '';

        $('#th_carasp_id').val(id);
        $('#th_car_id').val(car_id);
        $('#th_carasp_nivel_cargo').val(nivel);
        $('#txt_subordinacion').val(subordinacion);
        $('#txt_supervision').val(supervision);
        $('#txt_comunicaciones').val(comunicaciones);
    }

    function mostrarAspectosVacios() {
        $('#info_subordinacion').html('<em class="text-muted">No registrado</em>');
        $('#info_nivel_cargo').text('No registrado');
        $('#info_supervision').html('<em class="text-muted">No registrado</em>');
        $('#info_comunicaciones').html('<em class="text-muted">No registrado</em>');

        $('#badge_subordinacion').html('<i class="bi bi-arrow-up"></i> Reporta a: -');
        $('#badge_nivel').html('<i class="bi bi-person-badge"></i> Nivel: -');
        $('#badge_supervision').html('<i class="bi bi-arrow-down"></i> Supervisa: -');
        $('#badge_comunicaciones').html('<i class="bi bi-arrows"></i> Comunica con: -');
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
        'th_car_id': <?= $_id ?>,
        'ddl_cargo_requisito': $('#ddl_cargo_requisito').val()
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


// -- Helpers: obtener valores del modal --
function obtenerParametrosAspecto() {
    return {
        '_id': $('#th_carasp_id').val() || '', // id del aspecto (vacío => insertar)
        'th_car_id': <?= $_id ?>, // id del cargo asociado (debe venir)
        'th_carasp_nivel_cargo': $('#th_carasp_nivel_cargo').val() || '',
        'th_carasp_subordinacion': $('#txt_subordinacion').val().trim() || '',
        'th_carasp_supervision': $('#txt_supervision').val().trim() || '',
        'th_carasp_comunicaciones_colaterales': $('#txt_comunicaciones').val().trim() || '',
        'chk_th_carasp_estado': 1 // si manejas estado, ajustar aquí
    };
}

// -- Validación mínima del modal (ajusta según necesites) --
function validarAspectoParametros(p) {
    if (!p.th_car_id || p.th_car_id === '') {
        Swal.fire('', 'ID del cargo no encontrado. Abra el modal desde un cargo válido.', 'warning');
        return false;
    }
    // Si quieres exigir subordinación/supervisión/comunicaciones, descomenta:
    // if (!p.th_carasp_nivel_cargo) { Swal.fire('', 'Seleccione el nivel del cargo.', 'warning'); return false; }
    return true;
}

// -- Guardar (insertar o actualizar) --
function guardar_o_actualizar_aspecto() {
    var parametros = obtenerParametrosAspecto();

    if (!validarAspectoParametros(parametros)) return;

    $.ajax({
        data: {
            parametros: parametros
        },
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_aspectos_intrinsecosC.php?insertar_editar=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            // Se asume: 1 => éxito, -2 => duplicado, otro => error o id
            if (response == 1 || response === true) {
                Swal.fire('', (parametros._id ? 'Aspecto actualizado con éxito.' :
                        'Aspecto creado con éxito.'), 'success')
                    .then(function() {
                        // cerrar modal y recargar datos
                        var modalEl = document.getElementById('modal_aspectos_intrinsecos');
                        var modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();

                        // si tienes una tabla DataTable llamada tbl_aspectos la recarga, si no refresca la página
                        if (typeof tbl_aspectos !== 'undefined' && tbl_aspectos.ajax) {
                            tbl_aspectos.ajax.reload(null, false);
                        } else {
                            location.reload();
                        }
                    });
            } else if (response == -2) {
                Swal.fire('', 'Ya existe un aspecto intrínseco duplicado para este cargo/nivel.',
                    'warning');
            } else {
                // si el controlador devuelve objeto con msg
                var msg = (typeof response === 'object' && response.msg) ? response.msg :
                    'Error al guardar los aspectos.';
                Swal.fire('', msg, 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error guardar_aspecto: ', status, error, xhr.responseText);
            Swal.fire('', 'Error al conectar con el servidor: ' + xhr.responseText, 'error');
        }
    });
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

                // Llenar formulario para edición
                $('#th_comp_id').val(data.th_comp_id || data.id);
                $('#th_comp_requisitos_totales').val(data.th_comp_requisitos_totales || 0);
                $('#th_comp_requisitos_completados').val(data.th_comp_requisitos_completados || 0);
                $('#th_comp_ultima_revision').val(data.th_comp_ultima_revision || '');
                $('#th_comp_estado').val(data.th_comp_estado || '');
                $('#th_comp_observaciones').val(data.th_comp_observaciones || '');

                // Calcular valores
                calcularComplianceModal();

                // Cambiar a modo edición
                $('#pnl_crear_compliance').hide();
                $('#pnl_actualizar_compliance').show();
                $('#modalComplianceLabel').html(
                    '<i class="bx bx-edit me-2"></i> Editar Compliance del Cargo');
            }
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
        'th_car_id': <?= $_id ?>,
        'th_comp_porcentaje_completado': $('#th_comp_porcentaje_completado').val() || 0,
        'th_comp_requisitos_totales': $('#th_comp_requisitos_totales').val() || 0,
        'th_comp_requisitos_completados': $('#th_comp_requisitos_completados').val() || 0,
        'th_comp_requisitos_faltantes': $('#th_comp_requisitos_faltantes').val() || 0,
        'th_comp_ultima_revision': $('#th_comp_ultima_revision').val() || null,
        'th_comp_estado': $('#th_comp_estado').val() || 1,
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
                        listar_compliance_cargo(<?= $_id ?>);
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

            // Extraer datos
            const porcentaje = parseFloat(data.th_comp_porcentaje_completado || 0).toFixed(2);
            const totales = parseInt(data.th_comp_requisitos_totales || 0);
            const completados = parseInt(data.th_comp_requisitos_completados || 0);
            const faltantes = parseInt(data.th_comp_requisitos_faltantes || 0);
            const ultimaRevision = data.th_comp_ultima_revision || '';
            const estado = data.th_comp_estado || '';
            const observaciones = data.th_comp_observaciones || '';

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

    // Guardar nuevo
    $(document).on('click', '#pnl_crear_compliance button', function(e) {
        e.preventDefault();
        guardar_o_actualizar_compliance();
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
    const cargoId = <?= $_id ?>;
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
                                                    <div class="col-md-4 text-end">
                                                        <button type="button" class="btn btn-success btn-sm shadow-sm"
                                                            onclick="abrir_modal_aspectos_intrinsecos()">
                                                            <i class="bx bx-plus-circle me-1"></i> Registrar Aspectos
                                                        </button>
                                                    </div>
                                                </div>

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

                                            </div>
                                        </section>
                                    </div>




                                    <div class="tab-pane fade" id="secundaryprofile" role="tabpanel">
                                        <section class="content pt-0">
                                            <div class="container-fluid">
                                                <?php if ($_id != '') { ?>

                                                <h5 class="fw-bold text-primary mb-3">
                                                    <i class="bx bx-info-circle me-2"></i>Aspectos Extrínsecos del cargo
                                                </h5>
                                                <button type="button" class="btn btn-success"
                                                    onclick="abrir_modal_cargo_requisitos()">
                                                    <i class="bx bx-plus me-1"></i> Agregar Requisito Detalle
                                                </button>
                                                <?php }?>


                                                </hr>

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

                                            </div><!-- /.container-fluid -->
                                        </section>
                                    </div>
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
                                                <div class="col-md-4 text-end">
                                                    <button type="button" class="btn btn-success btn-sm shadow-sm"
                                                        onclick="abrir_modal_compliance()">
                                                        <i class="bx bx-plus-circle me-1"></i> Actualizar Compliance
                                                    </button>
                                                </div>
                                            </div>

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
                                                            <div class="card border-primary border-2 h-100 shadow-sm">
                                                                <div class="card-body text-center p-3">
                                                                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                                                        style="width: 50px; height: 50px;">
                                                                        <i class="bi bi-list-check text-primary"
                                                                            style="font-size: 1.5rem;"></i>
                                                                    </div>
                                                                    <h4 class="fw-bold text-primary mb-1"
                                                                        id="comp_totales">
                                                                        0</h4>
                                                                    <small class="text-muted">Requisitos Totales</small>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Requisitos Completados -->
                                                        <div class="col-md-4">
                                                            <div class="card border-success border-2 h-100 shadow-sm">
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
                                                            <div class="card border-danger border-2 h-100 shadow-sm">
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
                                                                    <strong class="text-dark">Última Revisión:</strong>
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
                                                                <span class="badge bg-secondary" id="comp_estado_badge">
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
                                                                    <strong class="text-dark">Observaciones:</strong>
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

                                        </div>
                                    </section>
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
                                <option value="1">Nivel 1 - Alta Dirección
                                </option>
                                <option value="2">Nivel 2 - Gerencia
                                </option>
                                <option value="3">Nivel 3 -
                                    Jefatura/Coordinación
                                </option>
                                <option value="4">Nivel 4 - Supervisión
                                </option>
                                <option value="5">Nivel 5 -
                                    Operativo/Técnico
                                </option>
                                <option value="6">Nivel 6 -
                                    Auxiliar/Asistente
                                </option>
                            </select>
                        </div>

                        <!-- Subordinación -->
                        <div class="col-md-12">
                            <label for="txt_subordinacion" class="form-label fw-bold">
                                <i class="bx bx-sitemap me-2 text-info"></i> Subordinación
                            </label>
                            <textarea class="form-control" id="txt_subordinacion" name="txt_subordinacion" rows="2"
                                placeholder="Indique a quién reporta este cargo" required></textarea>
                        </div>

                        <!-- Supervisión -->
                        <div class="col-md-12">
                            <label for="txt_supervision" class="form-label fw-bold">
                                <i class="bx bx-user-check me-2 text-success"></i> Supervisión
                            </label>
                            <textarea class="form-control" id="txt_supervision" name="txt_supervision" rows="2"
                                placeholder="Describa qué cargos o personal supervisa" required></textarea>
                        </div>

                        <!-- Comunicaciones Colaterales -->
                        <div class="col-md-12">
                            <label for="txt_comunicaciones" class="form-label fw-bold">
                                <i class="bx bx-conversation me-2 text-warning"></i> Comunicaciones Colaterales
                            </label>
                            <textarea class="form-control" id="txt_comunicaciones" name="txt_comunicaciones" rows="3"
                                placeholder="Indique con qué áreas o cargos del mismo nivel se comunica"
                                required></textarea>
                        </div>


                    </div>
                    <div class="d-flex justify-content-end gap-2 pt-3 mt-3 border-top">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            <i class="bx bx-x me-1"></i> Cancelar
                        </button>

                        <div id="pnl_crear_aspecto">
                            <button type="button" class="btn btn-success" onclick="insertar_aspectos_intrinsecos()">
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
                        <div class="col-md-6">
                            <label for="th_comp_estado" class="form-label fw-bold">
                                <i class="bx bx-flag me-2 text-secondary"></i> Estado
                            </label>
                            <select class="form-select" id="th_comp_estado" name="th_comp_estado" required>
                                <option value="">-- Seleccione --</option>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                                <option value="2">En Revisión</option>
                                <option value="3">Observado</option>
                            </select>
                        </div>

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