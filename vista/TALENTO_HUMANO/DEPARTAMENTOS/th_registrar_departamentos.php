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
    datos_col(<?= $_id ?>);
    cargar_personas_departamentos();
    <?php } ?>
    cargar_selects2();

    /**
     * 
     * Datatable
     */

    //Para seleccionar a cada persona
    $('#tbl_personas tbody').on('change', '.cbx_dep_per', function() {
        var id = $(this).val();

        if (this.checked) {
            if (!personas_seleccionadas.includes(id)) {
                personas_seleccionadas.push(id);
            }
        } else {
            // Eliminar el ID si el checkbox no está seleccionado
            personas_seleccionadas = personas_seleccionadas.filter(item => item !== id);
        }

        console.log('Seleccionados:', personas_seleccionadas);
    });

    // Evento para detectar cuando se cambia de página
    $('#tbl_personas').on('page.dt', function() {
        // Aquí colocas la acción que quieres realizar al cambiar de página
        $('#cbx_per_dep_all').prop('checked', false);
        console.log('Página cambiada');
    });

    document.getElementById("checkMostrarPersonas").addEventListener("change", function() {
        const cargos_personas = document.querySelectorAll(".cargo-persona");

        if (this.checked) {
            // Cargar personas
            organigrama_personas(<?= $_id ?>);
        } else {
            // Limpiar todas las tarjetas
            cargos_personas.forEach(el => {
                el.textContent = "";
                el.classList.remove("text-success", "text-danger");
                el.classList.add("text-info");
            });
        }
    });


});

/**
 * 
 * Departamentos
 * 
 */
function cargar_selects2(enModal = false) {
    url_departamentosC = '../controlador/TALENTO_HUMANO/th_departamentosC.php?buscar=true';
    cargar_select2_url('ddl_departamentos', url_departamentosC, '-- Seleccione --', '#modal_blank');
    url_departamentosTodosC = '../controlador/TALENTO_HUMANO/th_departamentosC.php?buscar=true';
    cargar_select2_url('ddl_departamento_destino', url_departamentosTodosC, '-- Seleccione --', '#modalMoverPersonas');
}

function datos_col(id) {
    $.ajax({
        data: {
            id: id
        },
        url: '../controlador/TALENTO_HUMANO/th_departamentosC.php?listar=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            console.log(response);
            $('#txt_nombre').val(response[0].nombre);
        }
    });
}

// Normalizador sencillo
function normalizarDepartamentosConPersonasSimple(data) {
    if (!Array.isArray(data)) return [];

    return data.map(dept => {
        // asegurar cargos como array
        dept.cargos = Array.isArray(dept.cargos) ? dept.cargos : [];

        dept.cargos = dept.cargos.map(cargo => {
            // asegurar persons como array
            if (Array.isArray(cargo.personas)) {
                // ok
            } else if (Array.isArray(cargo.personas_json)) {
                cargo.personas = cargo.personas_json;
            } else if (typeof cargo.personas_json === 'string' && cargo.personas_json.trim() !== '') {
                try {
                    cargo.personas = JSON.parse(cargo.personas_json);
                } catch (e) {
                    cargo.personas = [];
                }
            } else {
                cargo.personas = [];
            }

            return cargo;
        });

        return dept;
    });
}

// Extraer lista plana de cargos con la persona principal (simple)
function extraerListaCargosSimple(departamentos) {
    const lista = [];
    if (!Array.isArray(departamentos)) return lista;

    departamentos.forEach(dept => {
        (dept.cargos || []).forEach(cargo => {
            const id = cargo.th_car_id ?? cargo.th_carId ?? cargo.id ?? null;
            const personas = Array.isArray(cargo.personas) ? cargo.personas : [];
            const primera = personas.length ? personas[0] : null;

            // elegir nombre a mostrar
            let nombre = null;
            if (primera) {
                nombre = primera.nombre_completo ?? primera.nombres_completos ?? primera
                    .th_per_nombres_completos ?? null;
            }

            lista.push({
                id_cargo: id,
                cargo_nombre: cargo.th_car_nombre ?? cargo.nombre ?? null,
                persona_nombre: nombre,
                personas_count: personas.length
            });
        });
    });

    return lista;
}

// Función que actualiza el DOM de forma simple
function organigrama_personas(id) {
    $.ajax({
        url: '../controlador/TALENTO_HUMANO/th_departamentosC.php?listar_organigrama_personas=true',
        type: 'post',
        data: {
            id: id
        },
        dataType: 'json',
        success: function(response) {
            const datos = normalizarDepartamentosConPersonasSimple(response || []);
            const lista = extraerListaCargosSimple(datos);

            lista.forEach(item => {
                if (!item.id_cargo) return;

                const el = document.getElementById(`car_id_${item.id_cargo}`);
                if (!el) return;

                if (item.persona_nombre) {
                    el.textContent = `Encargado: ${item.persona_nombre}`;
                    el.classList.remove('text-danger');
                    el.classList.add('text-dark'); // o text-success
                } else {
                    el.textContent = 'Sin persona asignada';
                    el.classList.remove('text-dark', 'text-success');
                    el.classList.add('text-danger');
                }
            });
        },
        error: function() {
            console.error('Error al obtener organigrama_personas');
        }
    });
}


function extraerCargosConPersonas(datos) {
    let resultado = [];

    datos.forEach(dept => {
        if (Array.isArray(dept.cargos)) {
            dept.cargos.forEach(cargo => {

                // Si tiene personas, generar una fila por persona
                if (Array.isArray(cargo.personas) && cargo.personas.length > 0) {
                    cargo.personas.forEach(per => {
                        resultado.push({
                            id_cargo: cargo.th_car_id,
                            nombre_cargo: cargo.th_car_nombre,
                            nombre_persona: per.nombres_completos,
                            cedula: per.cedula
                        });
                    });
                } else {
                    // Si NO tiene personas, igual lo agregamos pero con persona vacía
                    resultado.push({
                        id_cargo: cargo.th_car_id,
                        nombre_cargo: cargo.th_car_nombre,
                        nombre_persona: null,
                        cedula: null
                    });
                }

            });
        }
    });

    return resultado;
}





function verificar_contrasenia(password) {
    $.ajax({
        data: {
            id: password
        },
        url: '../controlador/TALENTO_HUMANO/th_departamentosC.php?verificar=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            console.log(response);
        }
    });
}

function editar_insertar() {
    var txt_nombre = $('#txt_nombre').val();

    var parametros = {
        '_id': '<?= $_id ?>',
        'txt_nombre': txt_nombre,
    };

    if ($("#form_departamento").valid()) {
        // Si es válido, puedes proceder a enviar los datos por AJAX
        insertar(parametros);
    }
    //console.log(parametros);

}

function insertar(parametros) {
    $.ajax({
        data: {
            parametros: parametros
        },
        url: '../controlador/TALENTO_HUMANO/th_departamentosC.php?insertar=true',
        type: 'post',
        dataType: 'json',

        success: function(response) {
            if (response == 1) {
                Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                    location.href =
                        '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_departamentos';
                });
            } else if (response == -2) {
                //Swal.fire('', 'El nombre del departamento ya está en uso', 'warning');
                $(txt_nombre).addClass('is-invalid');
                $('#error_txt_nombre').text('El nombre del departamento ya está en uso.');
            }
        },

        error: function(xhr, status, error) {
            console.log('Status: ' + status);
            console.log('Error: ' + error);
            console.log('XHR Response: ' + xhr.responseText);

            Swal.fire('', 'Error: ' + xhr.responseText, 'error');
        }
    });

}

function insertar_persona_departamento() {
    var deptId = $('#ddl_departamentos').val();
    var perdepId = $('#id_perdep').val();
    var id_per = $('#id_per').val();

    if (!deptId) {
        Swal.fire('', 'Seleccione un departamento', 'warning');
        return;
    }

    var parametros = {
        '_id': perdepId || '', // th_perdep_id para edición
        'id_persona': id_per, // th_per_id
        'id_departamento': deptId, // th_dep_id
        'txt_visitor': $('#txt_visitor').val() || ''
    };

    $.ajax({
        url: '../controlador/TALENTO_HUMANO/th_personas_departamentosC.php?insertar_editar_persona=true',
        type: 'post',
        dataType: 'json',
        data: {
            parametros: parametros
        },
        success: function(response) {
            if (response == 1) {
                Swal.fire('', 'Operación realizada con éxito.', 'success').then(() => {
                    location.reload(); // O redirigir según necesites
                });
            } else if (response == -2) {
                Swal.fire('', 'Esta persona ya está asignada a este departamento', 'warning');
            } else {
                Swal.fire('', 'Error en la operación', 'error');
            }
        },
        error: function(xhr, status, error) {
            Swal.fire('', 'Error: ' + xhr.responseText, 'error');
        }
    });
}


function editar_datos_personas_departamentos(id_dep_per, id_persona) {

    var modalEl = document.getElementById('modal_blank');
    var modal = new bootstrap.Modal(modalEl);
    modal.show();

    $.ajax({
        data: {
            id: id_persona
        },
        url: '../controlador/TALENTO_HUMANO/th_personasC.php?listar_persona_departamento=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response && response.length > 0) {
                // Cargar el _id_perdep en el campo oculto para edición
                $('#id_perdep').val(id_dep_per);
                $('#id_per').val(id_persona);

                // Cargar el departamento seleccionado
                $('#ddl_departamentos').append($('<option>', {
                    value: response[0].id_departamento,
                    text: response[0].nombre_departamento,
                    selected: true
                }));

            }
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar departamento:', error);
        }
    });


}


function delete_datos() {
    var id = '<?= $_id ?>';
    Swal.fire({
        title: 'Eliminar Registro?',
        text: "Esta seguro de eliminar este registro?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) {
            eliminar(id);
        }
    })
}

function eliminar(id) {
    $.ajax({
        data: {
            id: id
        },
        url: '../controlador/TALENTO_HUMANO/th_departamentosC.php?eliminar=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response == 1) {
                Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
                    location.href =
                        '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_departamentos';
                });
            }
        }
    });
}

/**
 * 
 * Acerca de la relacion personas_departamentos en vista
 * 
 */

let tbl_departamento_personas;

function cargar_personas_departamentos() {
    // Si ya existe, destruir para recrear
    if ($.fn.DataTable.isDataTable('#tbl_departamento_personas')) {
        $('#tbl_departamento_personas').DataTable().destroy();
        $('#tbl_departamento_personas tbody').empty();
    }

    tbl_departamento_personas = $('#tbl_departamento_personas').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        responsive: true,
        ajax: {
            url: '../controlador/TALENTO_HUMANO/th_personas_departamentosC.php?listar=true',
            type: 'POST',
            data: function(d) {
                return {
                    id: '<?= $_id ?>'
                }; // ya lo tenías
            },
            dataSrc: ''
        },
        columns: [{
                data: null,
                orderable: false,
                className: 'text-center',
                render: function(data, type, item) {
                    // item._id -> th_perdep_id (relación)
                    // item.id_persona -> th_per_id (persona)
                    const perdep = item._id || '';
                    const person = item.id_persona || item.th_per_id || '';
                    return `<input type="checkbox" class="select-person"
                    data-perdep="${perdep}"
                    data-person="${person}" />`;
                }
            },
            {
                data: 'cedula'
            },
            {
                data: null,
                render: function(data, type, item) {
                    let nombres_completos =
                        `${item.primer_apellido} ${item.segundo_apellido} ${item.primer_nombre} ${item.segundo_nombre}`;
                    return `<a href="#"><u>${nombres_completos}</u></a>`;
                }
            },
            {
                data: 'correo'
            },
            {
                data: 'telefono_1'
            },
            {
                data: null,
                orderable: false,
                className: 'text-center',
                render: function(data, type, item) {
                    return `
                        <div class="d-flex justify-content-center gap-1">
                            <button type="button" class="btn btn-warning btn-xs" onclick="editar_datos_personas_departamentos('${item._id}','${item.id_persona}')">
                                <i class="bx bx-edit fs-7 fw-bold"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-xs" onclick="delete_datos_personas_departamentos('${item._id}')">
                                <i class="bx bx-trash fs-7 fw-bold"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        order: [
            [2, 'asc']
        ], // ordenar por nombre completo (col index 2 después del checkbox)
        drawCallback: function() {
            // Después de dibujar, asegura eventos de checkboxes
            setupCheckboxHandlers();
        }
    });
}

// Variable global para saber si es una o varias personas
let ids_a_mover = [];

// Maneja select-all y clicks en checkboxes
function setupCheckboxHandlers() {
    $('#select_all_personas').off('change').on('change', function() {
        const checked = $(this).is(':checked');
        $('#tbl_departamento_personas').find('tbody input.select-person').prop('checked', checked);
    });

    $('#tbl_departamento_personas').on('change', 'tbody input.select-person', function() {
        const total = $('#tbl_departamento_personas').find('tbody input.select-person').length;
        const marcados = $('#tbl_departamento_personas').find('tbody input.select-person:checked').length;
        $('#select_all_personas').prop('checked', total === marcados && total > 0);
    });
}

function obtener_ids_personas_seleccionadas() {
    const items = [];
    $('#tbl_departamento_personas').find('tbody input.select-person:checked').each(function() {
        const perdep = $(this).data('perdep') === undefined ? '' : String($(this).data('perdep'));
        const person = $(this).data('person') === undefined ? '' : String($(this).data('person'));
        items.push({
            perdep: perdep,
            person: person
        });
    });
    return items;
}

// Abre modal para mover UNA persona (desde botón de fila)
function abrir_modal_mover_una_persona(id_persona) {
    ids_a_mover = [id_persona];

    $('#titulo_modal_mover').text('Mover persona a departamento');
    $('#texto_personas_seleccionadas').text('Se moverá 1 persona');

    cargar_departamentos_en_select();

    const modalEl = document.getElementById('modalMoverPersonas');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();
}

// Abre modal para mover VARIAS personas (desde botón general)
function abrir_modal_mover_varios() {
    ids_a_mover = obtener_ids_personas_seleccionadas();

    if (ids_a_mover.length === 0) {
        Swal.fire('', 'Seleccione al menos una persona para mover.', 'warning');
        return;
    }

    $('#titulo_modal_mover').text('Mover personas a departamento');
    $('#texto_personas_seleccionadas').text(`Se moverán ${ids_a_mover.length} persona(s)`);

    cargar_departamentos_en_select();

    const modalEl = document.getElementById('modalMoverPersonas');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();
}

// Carga departamentos en el select
function cargar_departamentos_en_select() {
    const _idPHP = "<?php echo $_id; ?>";

    $('#ddl_departamento_destino').html('<option value="">-- Seleccione --</option>');

    $.ajax({
        data: {
            id: _idPHP
        },
        url: '../controlador/TALENTO_HUMANO/th_departamentosC.php?listar=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response && response.length > 0) {

                // Cargar el departamento seleccionado
                $('#ddl_departamento_destino').append($('<option>', {
                    value: response[0]._id,
                    text: response[0].nombre,
                    selected: true
                }));

            }
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar departamento:', error);
        }
    });

}

function mover_personas_departamento() {
    const selected = obtener_ids_personas_seleccionadas();
    const destino = $('#ddl_departamento_destino').val();
    if (!destino) {
        Swal.fire('', 'Seleccione el departamento destino.', 'warning');
        return;
    }
    if (!selected || selected.length === 0) {
        Swal.fire('', 'Seleccione al menos una persona.', 'warning');
        return;
    }

    $('#mover_msg').text('Procesando...');

    $.ajax({
        url: '../controlador/TALENTO_HUMANO/th_personas_departamentosC.php?mover_varios=true',
        method: 'POST',
        data: {
            ids: JSON.stringify(selected), // array de {perdep, person}
            id_departamento_destino: destino,
            txt_visitor: '' // opcional
        },
        dataType: 'json',
        success: function(resp) {
            $('#mover_msg').text('');
            const modalEl = document.getElementById('modalMoverPersonas');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();

            if (tbl_departamento_personas) tbl_departamento_personas.ajax.reload(null, false);
            $('#select_all_personas').prop('checked', false);

            if (!resp) {
                Swal.fire('', 'Respuesta inesperada del servidor.', 'error');
                return;
            }

            // Mensaje principal (viene en resp.message)
            const titulo = resp.success ? 'Operación completada' : 'Resultado';
            const icono = (resp.fallidos && resp.fallidos > 0) ? 'warning' : (resp.success ? 'success' :
                'info');

            // Construir texto extra con contadores
            const contadores =
                `Movidas: ${resp.exitosos || 0}. Ya estaban: ${resp.duplicados || 0}. Fallidos: ${resp.fallidos || 0}.`;

            // Si hay errores, formatearlos en una lista HTML corta
            let htmlDetalles = `<p>${resp.message || contadores}</p>`;
            if (resp.errores && resp.errores.length > 0) {
                htmlDetalles +=
                    '<hr><div style="text-align:left"><strong>Detalles:</strong><ul style="margin:0;padding-left:1.2em">';
                resp.errores.forEach(function(err) {
                    // si el elemento es string o un objeto con mensaje
                    if (typeof err === 'string') htmlDetalles += `<li>${escapeHtml(err)}</li>`;
                    else if (err.message) htmlDetalles += `<li>${escapeHtml(err.message)}</li>`;
                    else htmlDetalles += `<li>${escapeHtml(JSON.stringify(err))}</li>`;
                });
                htmlDetalles += '</ul></div>';
            }

            // Mostrar SweetAlert con HTML
            Swal.fire({
                title: titulo,
                html: htmlDetalles,
                icon: icono,
                confirmButtonText: 'Aceptar',
                width: '520px'
            });

            // log en consola si hay detalles para debugging
            if (resp.errores && resp.errores.length) console.warn('Errores mover_varios:', resp.errores);
        },
        error: function(xhr, status, error) {
            $('#mover_msg').text('');
            console.error(error, xhr.responseText);
            Swal.fire('', 'Error en el servidor al intentar mover las personas.', 'error');
        }
    });
}


function delete_datos_personas_departamentos(id) {
    Swal.fire({
        title: 'Eliminar Registro',
        html: `
            <p>Está seguro de eliminar este registro?</p>
            <div class="mt-3 text-start">
                <label for="txt_pass_eliminar" class="form-label fw-bold">Contraseña de seguridad:</label>
                <input type="password" id="txt_pass_eliminar" class="swal2-input" placeholder="Ingrese su contraseña" autocomplete="off">
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar',
        focusConfirm: false,
        preConfirm: () => {
            const pass = Swal.getPopup().querySelector('#txt_pass_eliminar').value.trim();
            if (!pass) {
                Swal.showValidationMessage('Debe ingresar la contraseña');
                return false;
            }

            // Retornamos la promesa AJAX
            return $.ajax({
                url: '../controlador/TALENTO_HUMANO/th_personas_departamentosC.php?verificar=true',
                type: 'POST',
                dataType: 'json',
                data: {
                    password: pass
                }
            }).then(function(response) {
                console.log('Respuesta del servidor:', response); // Para depurar

                if (!response || !response.valido) {
                    Swal.showValidationMessage('Contraseña incorrecta');
                    return false;
                }
                return pass;
            }).catch(function(error) {
                console.error('Error completo:', error);
                console.log('Response Text:', error.responseText);
                Swal.showValidationMessage('Error al validar la contraseña. Intente de nuevo.');
                return false;
            });
        }
    }).then((result) => {
        // Este then() es del Swal.fire(), no del AJAX
        if (result.isConfirmed) {
            console.log('Contraseña validada correctamente');
            // Aquí eliminas el registro
            // eliminar_personas_departamentos(id);
            Swal.fire('Eliminado!', 'El registro ha sido eliminado.', 'success');
        }
    });
}


function eliminar_personas_departamentos(id) {
    $.ajax({
        data: {
            id: id
        },
        url: '../controlador/TALENTO_HUMANO/th_personas_departamentosC.php?eliminar=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response == 1) {
                Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
                    tbl_departamento_personas.ajax.reload();
                    if ($.fn.DataTable.isDataTable('#tbl_personas')) {
                        tbl_personas.ajax.reload();
                    }
                });
            }
        }
    });
}

/**
 * 
 * Acerca de la relacion personas_departamentos en modal
 * 
 */

let personas_seleccionadas = []; //Array de personas seleccionadas
function cargar_personas() {

    tbl_personas = $('#tbl_personas').DataTable({
        responsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        ajax: {
            url: '../controlador/TALENTO_HUMANO/th_personas_departamentosC.php?listar_personas_modal=true',
            type: 'POST',
            data: function(d) {
                return {
                    id: '<?= $_id ?>',
                };
            },
            dataSrc: ''
        },
        columns: [{
                data: null,
                render: function(data, type, item) {
                    return `<a href="#"><u>${item.primer_apellido} ${item.segundo_apellido} ${item.primer_nombre} ${item.segundo_nombre}</u></a>`;
                }
            },
            {
                data: 'cedula'
            },

            {
                data: 'correo'
            },
            {
                data: 'telefono_1'
            },
            {
                data: null,
                render: function(data, type, item) {
                    return `<div class="form-check">
                                <input class="form-check-input cbx_dep_per" type="checkbox" value="${item._id}" name="cbx_dep_per_${item._id}" id="cbx_dep_per_${item._id}">
                                <label class="form-label" for="cbx_dep_per_${item._id}">Seleccionar</label>
                            </div>`;
                },
                orderable: false
            }
        ],
        order: [
            [1, 'asc']
        ],

    });

}

// Función para marcar/desmarcar todos los cbx_per_dep_all
function marcar_cbx_modal_departamentos_personas(source) {
    var cbx_per_dep_all = document.querySelectorAll('.cbx_dep_per');

    cbx_per_dep_all.forEach(function(cbx) {
        cbx.checked = source.checked; // Marca o desmarca todos
        var id = cbx.value;

        // Actualiza el array de personas seleccionadas
        if (source.checked) {
            if (!personas_seleccionadas.includes(id)) {
                personas_seleccionadas.push(id);
            }
        } else {
            personas_seleccionadas = personas_seleccionadas.filter(item => item !== id);
        }
    });

    console.log('Seleccionados:', personas_seleccionadas);
}

function insertar_editar_personas_departamentos() {

    var parametros = {
        '_id': '<?= $_id ?>',
        'personas_seleccionadas': personas_seleccionadas,
        'txt_visitor': ''
    };

    insertar_personas_departamentos(parametros);

    //console.log(parametros);
}

function insertar_personas_departamentos(parametros) {
    Swal.fire({
        title: 'Por favor, espere',
        text: 'Procesando la solicitud...',
        allowOutsideClick: false,
        onOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        data: {
            parametros: parametros
        },
        url: '../controlador/TALENTO_HUMANO/th_personas_departamentosC.php?insertar=true',
        type: 'post',
        dataType: 'json',

        success: function(response) {
            if (response == 1) {
                Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                    $('#modal_personas').modal('hide');
                    tbl_departamento_personas.ajax.reload();
                    tbl_personas.ajax.reload();
                    personas_seleccionadas = [];
                    $('#cbx_per_dep_all').prop('checked', false);
                    Swal.close();
                });

            } else if (response == -2 || response == null) {
                Swal.fire('', 'Seleccione personas', 'warning');
            }
        },

        error: function(xhr, status, error) {
            console.log('Status: ' + status);
            console.log('Error: ' + error);
            console.log('XHR Response: ' + xhr.responseText);

            Swal.fire('', 'Error: ' + xhr.responseText, 'error');
        }
    });
}

function abrir_modal_personas() {
    $('#modal_personas').modal('show');
    // Solo llama a cargar_personas si la tabla no ha sido inicializada
    if (!$.fn.DataTable.isDataTable('#tbl_personas')) {
        cargar_personas();
    }
}
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Departamentos</div>
            <?php
            //print_r($_SESSION['INICIO']);die(); 

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Agregar Departamento
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
                                    echo 'Registrar Departamento';
                                } else {
                                    echo 'Modificar Departamento';
                                }
                                ?>
                            </h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_departamentos"
                                        class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i>
                                        Regresar</a>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="pt-2">
                            <ul class="nav nav-tabs nav-primary" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#successhome" role="tab"
                                        aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bx-home font-18 me-1'></i>
                                            </div>
                                            <div class="tab-title">Datos</div>
                                        </div>
                                    </a>
                                </li>

                                <?php if (isset($_GET['_id'])) { ?>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#successprofile" role="tab"
                                        aria-selected="false">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bx-user-pin font-18 me-1'></i>
                                            </div>
                                            <div class="tab-title">Personas</div>
                                        </div>
                                    </a>
                                </li>
                                <?php } ?>

                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#successDepartament" role="tab"
                                        aria-selected="false">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bx-home font-18 me-1'></i>
                                            </div>
                                            <div class="tab-title">Jerarquia Departamento</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#successPostulacion" role="tab"
                                        aria-selected="false">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bx-home font-18 me-1'></i>
                                            </div>
                                            <div class="tab-title">Postulación de Trabajo</div>
                                        </div>
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content py-3">
                                <div class="tab-pane fade show active" id="successhome" role="tabpanel">

                                    <form id="form_departamento">

                                        <div class="row pt-3 mb-col">
                                            <div class="col-md-12">
                                                <label for="txt_nombre" class="form-label">Nombre </label>
                                                <input type="text" class="form-control form-control-sm no_caracteres"
                                                    id="txt_nombre" name="txt_nombre" maxlength="50">
                                                <span id="error_txt_nombre" class="text-danger"></span>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end pt-2">

                                            <?php if ($_id == '') { ?>
                                            <button class="btn btn-success btn-sm px-4 m-0" onclick="editar_insertar();"
                                                type="button"><i class="bx bx-save"></i> Guardar</button>
                                            <?php } else { ?>
                                            <button class="btn btn-success btn-sm px-4 m-1" onclick="editar_insertar();"
                                                type="button"><i class="bx bx-save"></i> Editar</button>
                                            <button class="btn btn-danger btn-sm px-4 m-1" onclick="delete_datos();"
                                                type="button"><i class="bx bx-trash"></i> Eliminar</button>
                                            <?php } ?>
                                        </div>

                                    </form>

                                </div>

                                <div class="tab-pane fade" id="successprofile" role="tabpanel">

                                    <div class="row pt-2">
                                        <div class="col-sm-12 text-end" id="btn_nuevo_bottom">
                                            <button type="button" class="btn btn-success btn-sm"
                                                onclick="abrir_modal_personas();">
                                                <i class="bx bx-plus"></i> Agregar Personas
                                            </button>
                                            <button type="button" class="btn btn-primary btn-sm ms-2"
                                                onclick="abrir_modal_mover_varios();">
                                                <i class="bx bx-transfer"></i> Mover seleccionados
                                            </button>
                                        </div>
                                    </div>

                                    <div class="row pt-4">
                                        <div class="table-responsive">
                                            <table class="table table-striped responsive" id="tbl_departamento_personas"
                                                style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th style="width:40px"><input id="select_all_personas"
                                                                type="checkbox" /></th>
                                                        <th>Cédula</th>
                                                        <th>Nombre</th>
                                                        <th>Correo</th>
                                                        <th>Teléfono</th>
                                                        <th>Acción</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>

                                <div class="tab-pane fade" id="successDepartament" role="tabpanel">
                                    <div class="container-fluid py-4">
                                        <div class="row">
                                            <div class="col-12">
                                                <h1 class="text-center mb-4 text-primary">
                                                    <i class="fas fa-sitemap me-3"></i>
                                                    Organigrama Empresarial
                                                </h1>

                                                <div class="text-center mb-4">
                                                    <button id="btnExpandir" class="btn btn-primary"
                                                        onclick="toggleExpandirTodo()">
                                                        <i class="fas fa-expand-arrows-alt me-2"></i>
                                                        <span id="btnText">Expandir Todo</span>
                                                    </button>
                                                </div>
                                                <div class="form-check form-switch d-inline-block ms-3 mb-3">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="checkMostrarPersonas"
                                                        style="cursor: pointer; width: 3em; height: 1.5em;">
                                                    <label class="form-check-label ms-2" for="checkMostrarPersonas"
                                                        style="cursor: pointer; font-weight: 500;">
                                                        <i class="fas fa-users me-1"></i>
                                                        Mostrar Personal Asignado
                                                    </label>
                                                </div>

                                                <div id="organigramaContent"></div>
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
        <!--end row-->
    </div>
</div>

<!-- Modal para abrir una tabla con las personas registradas -->
<div class="modal" id="modal_personas" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <!-- <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div> -->

            <!-- Modal body -->
            <div class="modal-body">

                <div class="row pt-3">

                    <div class="table-responsive">
                        <table class="table table-striped responsive" id="tbl_personas" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Cédula</th>
                                    <th>Correo</th>
                                    <th>Teléfono</th>
                                    <th>
                                        <div class="form-check" style="display: block;">
                                            <input class="form-check-input" type="checkbox" id="cbx_per_dep_all"
                                                onchange="marcar_cbx_modal_departamentos_personas(this)">
                                            <label class="form-check-label" for="cbx_per_dep_all">
                                                Todo
                                            </label>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="d-flex justify-content-center">
                    <button class="btn btn-success btn-sm px-4 m-1" onclick="insertar_editar_personas_departamentos();"
                        type="button"><i class="bx bx-plus me-0"></i> Agregar</button>
                    <button class="btn btn-secondary btn-sm px-4 m-1" data-bs-dismiss="modal" type="button"><b>X </b>
                        Cancelar</button>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalMoverPersonas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mover personas a departamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="ddl_departamento_destino" class="form-label">Departamento destino</label>
                    <select id="ddl_departamento_destino" class="form-select">
                        <option value="">-- Seleccione --</option>
                        <!-- Se cargan via AJAX -->
                    </select>
                </div>
                <div id="mover_msg" class="small text-muted"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary btn-sm"
                    onclick="mover_personas_departamento();">Mover</button>
            </div>
        </div>
    </div>
</div>



<div class="modal" id="modal_blank" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cambiar De Departamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="registrar_departamento" class="modal_general_provincias">
                    <input id="id_perdep" type="hidden" value="" />
                    <input id="id_per" type="hidden" value="" /> <!-- guarda la persona -->

                    <div class="mb-3">
                        <label for="ddl_departamentos" class="form-label">Departamentos <span
                                class="text-danger">*</span></label>
                        <select class="form-select form-select-sm select2-validation" id="ddl_departamentos"
                            name="ddl_departamentos">
                            <option selected disabled>-- Seleccione --</option>
                        </select>
                        <label class="error" style="display: none;" for="ddl_departamentos"></label>
                    </div>

                    <div class="d-flex justify-content-end pt-2">
                        <button type="button" class="btn btn-success btn-sm" onclick="insertar_persona_departamento();">
                            <i class="bx bx-save"></i> Guardar
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm ms-2"
                            data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Selecciona el label existente y añade el nuevo label
    agregar_asterisco_campo_obligatorio('txt_nombre');

    $("#form_departamento").validate({
        rules: {
            txt_nombre: {
                required: true,
            },
        },
        messages: {
            txt_nombre: {
                required: "El campo 'Nombre' es obligatorio",
            },
        },

        highlight: function(element) {
            // Agrega la clase 'is-invalid' al input que falla la validación
            $(element).addClass('is-invalid');
            $(element).removeClass('is-valid');
        },
        unhighlight: function(element) {
            // Elimina la clase 'is-invalid' si la validación pasa
            $(element).removeClass('is-invalid');
            $(element).addClass('is-valid');
        }
    });
});
</script>
<script>
// ============================================
// FUNCIÓN PARA NORMALIZAR DATOS DEL SERVIDOR
// ============================================
function normalizarDepartamentos(data) {
    return data.map(dept => {
        // Parsear cargos_json si es string
        if (typeof dept.cargos_json === 'string') {
            dept.cargos = JSON.parse(dept.cargos_json);
            delete dept.cargos_json;
        } else if (Array.isArray(dept.cargos_json)) {
            dept.cargos = dept.cargos_json;
            delete dept.cargos_json;
        }

        // Parsear aspectos_json dentro de cada cargo
        if (dept.cargos && Array.isArray(dept.cargos)) {
            dept.cargos = dept.cargos.map(cargo => {
                if (typeof cargo.aspectos_json === 'string') {
                    cargo.aspectos = JSON.parse(cargo.aspectos_json);
                    delete cargo.aspectos_json;
                } else if (Array.isArray(cargo.aspectos_json)) {
                    cargo.aspectos = cargo.aspectos_json;
                    delete cargo.aspectos_json;
                }
                return cargo;
            });
        }

        return dept;
    });
}

// ============================================
// CONSTRUCCIÓN DE JERARQUÍA
// ============================================
function construirJerarquiaCargos(departamento) {
    const cargos = departamento.cargos || [];

    // Crear un mapa de cargos por ID
    const cargoMap = new Map();
    cargos.forEach(cargo => {
        cargoMap.set(cargo.th_car_id, {
            ...cargo,
            subordinados: []
        });
    });

    // Organizar jerárquicamente según subordinación
    const raices = [];

    cargos.forEach(cargo => {
        const aspecto = cargo.aspectos && cargo.aspectos[0];
        const subordinacionId = aspecto?.th_carasp_subordinacion_id;

        if (subordinacionId && cargoMap.has(subordinacionId)) {
            // Este cargo es subordinado de otro
            const superior = cargoMap.get(subordinacionId);
            superior.subordinados.push(cargoMap.get(cargo.th_car_id));
        } else {
            // Este es un cargo raíz (jefe principal)
            raices.push(cargoMap.get(cargo.th_car_id));
        }
    });

    // Si no hay jerarquía definida, mostrar todos como raíz
    if (raices.length === 0) {
        return Array.from(cargoMap.values());
    }

    return raices;
}

// ============================================
// FUNCIONES DE RENDERIZADO
// ============================================
function obtenerColoresDepartamento(indiceDepartamento) {
    const colores = [{
            cardClass: 'border-success bg-light text-dark',
            textClass: 'text-success',
            textSecondaryClass: 'text-muted',
            iconClass: 'text-success',
            borderColor: 'border-success'
        },
        {
            cardClass: 'border-info bg-light text-dark',
            textClass: 'text-info',
            textSecondaryClass: 'text-muted',
            iconClass: 'text-info',
            borderColor: 'border-info'
        },
        {
            cardClass: 'border-warning bg-light text-dark',
            textClass: 'text-warning',
            textSecondaryClass: 'text-muted',
            iconClass: 'text-warning',
            borderColor: 'border-warning'
        },
        {
            cardClass: 'border-danger bg-light text-dark',
            textClass: 'text-danger',
            textSecondaryClass: 'text-muted',
            iconClass: 'text-danger',
            borderColor: 'border-danger'
        },
        {
            cardClass: 'border-purple bg-light text-dark',
            textClass: 'text-purple',
            textSecondaryClass: 'text-muted',
            iconClass: 'text-purple',
            borderColor: 'border-purple'
        }
    ];

    return colores[indiceDepartamento % colores.length];
}

function contarSubordinados(cargo) {
    if (!cargo.subordinados || cargo.subordinados.length === 0) return 0;
    let count = cargo.subordinados.length;
    cargo.subordinados.forEach(sub => {
        count += contarSubordinados(sub);
    });
    return count;
}

function obtenerIconoCargo(nombreCargo) {
    const nombre = nombreCargo.toLowerCase();

    if (nombre.includes('jefe') || nombre.includes('director')) {
        return 'fas fa-user-tie';
    } else if (nombre.includes('desarrollador') || nombre.includes('software')) {
        return 'fas fa-laptop-code';
    } else if (nombre.includes('técnico') || nombre.includes('mantenimiento')) {
        return 'fas fa-tools';
    } else if (nombre.includes('analista')) {
        return 'fas fa-chart-line';
    } else if (nombre.includes('diseñador')) {
        return 'fas fa-palette';
    }

    return 'fas fa-user';
}

function crearTarjetaCargo(cargo, nivel = 0, indiceDepartamento = 0, esJefePrincipal = false) {
    const tieneSubordinados = cargo.subordinados && cargo.subordinados.length > 0;
    const cardSize = esJefePrincipal ? 'col-12 col-md-6 col-lg-4' : 'col-12 col-sm-6 col-md-4 col-lg-3';

    const aspecto = cargo.aspectos && cargo.aspectos[0];
    const nivelCargo = aspecto?.th_carasp_nivel_cargo || 'N/A';

    const equipoInfo = tieneSubordinados ? `
        <small class="text-muted">
            <i class="fas fa-users me-1"></i>
            Subordinados: ${contarSubordinados(cargo)} cargos
        </small>
    ` : '';

    const clickable = tieneSubordinados ?
        `data-bs-toggle="collapse" data-bs-target="#collapse-${cargo.th_car_id}" aria-expanded="false" aria-controls="collapse-${cargo.th_car_id}" role="button"` :
        '';

    // Determinar colores según nivel
    let cardClass, textClass, textSecondaryClass, iconClass;

    if (esJefePrincipal) {
        cardClass = 'border-primary bg-primary text-white';
        textClass = 'text-white';
        textSecondaryClass = 'text-white-50';
        iconClass = 'text-white';
    } else if (nivel >= 2) {
        const colores = obtenerColoresDepartamento(indiceDepartamento);
        cardClass = colores.cardClass;
        textClass = colores.textClass;
        textSecondaryClass = colores.textSecondaryClass;
        iconClass = colores.iconClass;
    } else {
        cardClass = 'border-secondary bg-light';
        textClass = 'text-primary';
        textSecondaryClass = 'text-muted';
        iconClass = 'text-primary';
    }

    const shadowClass = esJefePrincipal ? 'shadow-lg' : 'shadow-sm';
    const iconoCargo = obtenerIconoCargo(cargo.th_car_nombre);

    return `
        <div class="${cardSize} mb-3">
            <div class="card ${cardClass} ${shadowClass} h-100 ${tieneSubordinados ? 'card-clickable' : ''}" 
                 ${clickable} 
                 data-id="${cargo.th_car_id}">
                <div class="card-body text-center p-3">
                    <div class="mb-2">
                        <i class="${iconoCargo} fa-3x ${iconClass}"></i>
                    </div>
                    <h6 class="card-title mb-1 ${textClass}">${cargo.th_car_nombre}</h6>
                    <p class="card-text small mb-2 ${textSecondaryClass}">${cargo.th_car_descripcion || 'Sin descripción'}</p>
                    <span class="badge bg-secondary mb-2">Nivel: ${nivelCargo}</span>
                    ${equipoInfo}
                    ${tieneSubordinados ? `<i class="fas fa-chevron-down mt-2 ${iconClass}"></i>` : ''}
                    <div id="car_id_${cargo.th_car_id}"  class="cargo-persona fw-bold mt-1" disabled></div>
                </div>
            </div>
        </div>
    `;
}

function generarNivelCargos(cargos, nivel = 0, departamentoIndex = 0) {
    if (!cargos || cargos.length === 0) return '';

    let html = '';

    html += `<div class="row justify-content-center mb-3">`;
    cargos.forEach((cargo, index) => {
        const esJefe = nivel === 0;
        html += crearTarjetaCargo(cargo, nivel, index, esJefe);
    });
    html += `</div>`;

    cargos.forEach((cargo, index) => {
        if (cargo.subordinados && cargo.subordinados.length > 0) {
            let borderColor = 'border-primary';
            if (nivel >= 1) {
                const colores = obtenerColoresDepartamento(index);
                borderColor = colores.borderColor;
            }

            html += `
                <div class="collapse mt-3" id="collapse-${cargo.th_car_id}">
                    <div class="container-fluid">
                        <div class="border-start border-3 ${borderColor} ps-4 ms-3">
                            ${generarNivelCargos(cargo.subordinados, nivel + 1, index)}
                        </div>
                    </div>
                </div>
            `;
        }
    });

    return html;
}

// ============================================
// FUNCIÓN PRINCIPAL PARA CARGAR ORGANIGRAMA
// ============================================
function cargarOrganigrama(departamentoId) {
    $.ajax({
        data: {
            id: departamentoId
        },
        url: '../controlador/TALENTO_HUMANO/th_departamentosC.php?listar_organigrama=true',
        type: 'post',
        dataType: 'json',
        beforeSend: function() {
            $('#organigramaContent').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-3">Cargando organigrama...</p>
                </div>
            `);
        },
        success: function(response) {
            console.log('Respuesta original:', response);

            // Normalizar los datos
            const datosNormalizados = normalizarDepartamentos(response);
            console.log('Datos normalizados:', datosNormalizados);

            if (datosNormalizados.length === 0) {
                $('#organigramaContent').html(`
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        No se encontraron cargos para este departamento
                    </div>
                `);
                return;
            }

            // Construir jerarquía
            const departamento = datosNormalizados[0];
            const jerarquia = construirJerarquiaCargos(departamento);
            console.log('Jerarquía construida:', jerarquia);

            // Generar HTML
            const htmlOrganigrama = `
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-building me-2"></i>
                            Departamento: ${departamento.departamento_nombre}
                        </h5>
                    </div>
                </div>
                ${generarNivelCargos(jerarquia, 0, 0)}
            `;

            $('#organigramaContent').html(htmlOrganigrama);

            // Añadir efectos hover
            $('.card-clickable').hover(
                function() {
                    $(this).css({
                        'transform': 'translateY(-2px)',
                        'transition': 'transform 0.2s ease'
                    });
                },
                function() {
                    $(this).css('transform', 'translateY(0)');
                }
            );
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            $('#organigramaContent').html(`
                <div class="alert alert-danger text-center">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Error al cargar el organigrama: ${error}
                </div>
            `);
        }
    });
}

// ============================================
// EXPANDIR/COLAPSAR TODO
// ============================================
let todoExpandido = false;

function toggleExpandirTodo() {
    const btn = document.querySelector('#btnExpandir');
    const icon = btn.querySelector('i');
    const text = document.getElementById('btnText');

    if (todoExpandido) {
        document.querySelectorAll('.collapse').forEach(collapse => {
            const bsCollapse = new bootstrap.Collapse(collapse, {
                toggle: false
            });
            bsCollapse.hide();
        });

        icon.className = 'fas fa-expand-arrows-alt me-2';
        text.textContent = 'Expandir Todo';
        todoExpandido = false;
    } else {
        document.querySelectorAll('.collapse').forEach(collapse => {
            const bsCollapse = new bootstrap.Collapse(collapse, {
                toggle: false
            });
            bsCollapse.show();
        });

        icon.className = 'fas fa-compress-arrows-alt me-2';
        text.textContent = 'Colapsar Todo';
        todoExpandido = true;
    }
}

// ============================================
// INICIALIZACIÓN
// ============================================
$(document).ready(function() {
    // Cargar organigrama cuando se muestre la pestaña
    $('a[href="#successDepartament"]').on('shown.bs.tab', function() {

        cargarOrganigrama(<?= $_id ?>);
    });

    // Si la pestaña ya está activa al cargar la página
    if ($('#successDepartament').hasClass('active')) {

        cargarOrganigrama(<?= $_id ?>);
    }
});
</script>