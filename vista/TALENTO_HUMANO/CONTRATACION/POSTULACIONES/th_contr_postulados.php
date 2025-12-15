<?php
$modulo_sistema = $_SESSION['INICIO']['MODULO_SISTEMA'];
$_id = '';

if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}
?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
$(document).ready(function() {

    <?php if ($_id != '') { ?>
    cargar_tabla_postulados(<?= $_id ?>);
    cargar_plaza(<?= $_id ?>);

    <?php } ?>

    $('#tbl_personas tbody').on('change', '.cbx_pla_per', function() {
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
                $('#txt_th_pla_titulo').val(r.th_pla_titulo);
                $('#txt_th_pla_id').val(r._id);

                const ddl = $('#ddl_tipo_postulante');

                // Reiniciamos estado
                ddl.prop('disabled', false);
                ddl.val(''); // limpia selección previa

                if (r.th_pla_tipo === "Interna") {
                    ddl.val('interno').prop('disabled', true);
                    cargar_personas("personas", r._id); // carga empleados internos
                } else if (r.th_pla_tipo === "Externa") {
                    ddl.val('externo').prop('disabled', true);
                    cargar_personas("postulantes", r._id); // carga postulantes externos
                } else if (r.th_pla_tipo === "Mixta") {
                    ddl.prop('disabled', false); // el usuario puede cambiar
                    ddl.val('interno'); // sin selección por defecto
                }

            },
            error: function(err) {
                console.error(err);
                alert('Error al cargar la plaza (revisar consola).');
            }
        });
    }

    function cargar_tabla_postulados(id_plaza) {
        // Asegurarnos de que id_plaza no sea undefined
        id_plaza = id_plaza || '';

        // Si ya existe el DataTable, lo destruimos para evitar duplicados
        if ($.fn.dataTable.isDataTable('#tbl_postulaciones')) {
            $('#tbl_postulaciones').DataTable().clear().destroy();
            $('#tbl_postulaciones').empty(); // opcional: limpia el tbody
        }

        tbl_postulaciones = $('#tbl_postulaciones').DataTable($.extend({}, configuracion_datatable('ID',
            'Candidato'), {
            responsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            ajax: {
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_postulacionesC.php?listar_plaza_postulados=true',
                type: 'POST',
                data: function(d) {
                    d.id = id_plaza;
                },
                dataSrc: ''
            },
            columns: [{
                    data: 'nombre_completo',
                    render: function(data, type, item) {
                        let href =
                            `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registro_postulaciones&_id=${item._id}&_id_p=${id_plaza}`;
                        let foto = '';

                        if (item.foto_url && item.foto_url !== '') {
                            foto =
                                `<img src="${item.foto_url}" class="rounded-circle me-2" width="40" height="40" style="object-fit: cover;" alt="Foto">`;
                        } else {
                            foto = `
                        <div class="bg-primary text-white rounded-circle me-2 d-inline-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px; font-size: 18px;">
                            <i class="bx bx-user"></i>
                        </div>`;
                        }

                        // 🔹 Envolvemos todo en un enlace clickeable
                        return `
                    <a href="${href}" class="text-decoration-none text-dark d-flex align-items-center">
                        ${foto}
                        <div>
                            <div class="fw-bold">${data || 'Sin nombre'}</div>
                            <small class="text-muted">
                                <i class="bx bx-id-card me-1"></i>${item.cedula || 'Sin cédula'}
                            </small>
                        </div>
                    </a>
                `;
                    },
                    className: 'text-center'
                },
                {
                    data: 'tipo_candidato',
                    render: function(data, type, item) {
                        let badge = 'secondary';
                        let icon = 'bx-user';

                        if (data === 'Empleado Contratado') {
                            badge = 'success';
                            icon = 'bx-briefcase-alt';
                        } else if (data === 'Empleado Interno') {
                            badge = 'primary';
                            icon = 'bx-home';
                        } else if (data === 'Postulante Contratado') {
                            badge = 'success';
                            icon = 'bx-check-circle';
                        } else if (data === 'Postulante Externo') {
                            badge = 'info';
                            icon = 'bx-user-plus';
                        } else if (data === 'Interno') {
                            badge = 'warning';
                            icon = 'bx-building';
                        }

                        return `<span class="badge bg-${badge}"><i class="bx ${icon} me-1"></i>${data}</span>`;
                    },
                    className: 'text-center'
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        let correo = item.correo && item.correo !== '-' ? item.correo :
                            'Sin correo';
                        let telefono = item.telefono && item.telefono !== '-' ? item
                            .telefono : 'Sin teléfono';

                        return `
                    <div>
                        <small class="d-block">
                            <i class="bx bx-envelope me-1 text-primary"></i>${correo}
                        </small>
                        <small class="d-block">
                            <i class="bx bx-phone me-1 text-success"></i>${telefono}
                        </small>
                    </div>
                `;
                    }
                },
                {
                    data: 'fecha_postulacion',
                    render: function(data) {
                        if (data) {
                            // Convertir fecha al formato dd/mm/yyyy hh:mm
                            let fecha = new Date(data);
                            let dia = String(fecha.getDate()).padStart(2, '0');
                            let mes = String(fecha.getMonth() + 1).padStart(2, '0');
                            let anio = fecha.getFullYear();
                            let hora = String(fecha.getHours()).padStart(2, '0');
                            let minuto = String(fecha.getMinutes()).padStart(2, '0');

                            return `
                        <div>
                            <div class="fw-bold">${dia}/${mes}/${anio}</div>
                            <small class="text-muted">${hora}:${minuto}</small>
                        </div>
                    `;
                        }
                        return '-';
                    }
                },
                {
                    data: 'estado_descripcion',
                    render: function(data) {
                        // Diferentes colores según el estado
                        let badge = 'secondary';
                        let icon = 'bx-circle';

                        if (data) {
                            let estado = data.toLowerCase();

                            if (estado.includes('preseleccionado') || estado.includes(
                                    'seleccionado')) {
                                badge = 'success';
                                icon = 'bx-check-circle';
                            } else if (estado.includes('entrevista')) {
                                badge = 'warning';
                                icon = 'bx-time';
                            } else if (estado.includes('rechazado')) {
                                badge = 'danger';
                                icon = 'bx-x-circle';
                            } else if (estado.includes('revisión')) {
                                badge = 'info';
                                icon = 'bx-search';
                            } else {
                                badge = 'secondary';
                                icon = 'bx-file';
                            }
                        }

                        return `<span class="badge bg-${badge}"><i class="bx ${icon} me-1"></i>${data || 'Postulado'}</span>`;
                    }
                },
                {
                    data: 'fuente',
                    render: function(data) {
                        let icon = 'bx-world';
                        let color = 'text-secondary';

                        if (data) {
                            let fuente = data.toLowerCase();
                            if (fuente.includes('linkedin')) {
                                icon = 'bxl-linkedin-square';
                                color = 'text-primary';
                            } else if (fuente.includes('indeed')) {
                                icon = 'bx-briefcase-alt';
                                color = 'text-info';
                            } else if (fuente.includes('interno') || fuente.includes(
                                    'referido')) {
                                icon = 'bx-home';
                                color = 'text-success';
                            } else if (fuente.includes('facebook')) {
                                icon = 'bxl-facebook-square';
                                color = 'text-primary';
                            } else if (fuente.includes('web') || fuente.includes(
                                    'página')) {
                                icon = 'bx-globe';
                                color = 'text-warning';
                            }
                        }

                        return data ?
                            `<i class="bx ${icon} ${color} fs-5 me-1"></i>${data}` : '-';
                    }
                },
                {
                    data: 'score',
                    render: function(data) {
                        let score = parseFloat(data) || 0;
                        let badge = 'secondary';
                        let icon = 'bx-minus';

                        if (score >= 80) {
                            badge = 'success';
                            icon = 'bx-check-circle';
                        } else if (score >= 50) {
                            badge = 'warning';
                            icon = 'bx-error-circle';
                        } else if (score > 0) {
                            badge = 'danger';
                            icon = 'bx-x-circle';
                        }

                        return `
                    <span class="badge bg-${badge} fs-6" style="min-width: 50px;">
                        <i class="bx ${icon} me-1"></i>${score.toFixed(2)}
                    </span>
                `;
                    },
                    className: 'text-center'
                }
            ],
            order: [
                [4, 'desc']
            ], // Ordenar por fecha
            drawCallback: function() {
                // Activar tooltips después de cargar la tabla
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        }));
    }





    $('#ddl_tipo_postulante').on('change', function() {
        const tipo = $(this).val();
        if (tipo === 'interno') {
            cargar_personas("personas", <?= $_id ?>);
        } else if (tipo === 'externo') {
            cargar_personas("postulantes", <?= $_id ?>);
        } else if (tipo === 'externo_recomendados') {
            cargar_personas("recomendados");
        }
    });



});


function obtener_ids_postulaciones() {
    var array_ids = tbl_postulaciones.rows().data().toArray().map(function(item) {
        return item._id;
    });
    return array_ids;
}

function actualizar_seguimiento() {
    var ids = obtener_ids_postulaciones();

    var parametros = {
        'postulantes_seleccionadas': ids,
        'ddl_tipo_postulante': $('#ddl_plaza').val(),
        'th_pla_id': <?= $_id ?>,
    };

    insertar_postulantes_pruebas(parametros);

}


function insertar_postulantes_pruebas(parametros) {
    $.ajax({
        data: {
            parametros: parametros
        },
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_seguimiento_postulanteC.php?insertar_editar=true',
        type: 'post',
        dataType: 'json',

        success: function(response) {

            // Si devuelve objetos tipo {ok: true, msg: "...", etapas_creadas: 0}
            if (response.ok === true) {
                Swal.fire('', response.msg, 'success');
                return;
            }

            // Si devuelve error personalizado (ej. -2)
            if (response == -2) {
                Swal.fire('', 'Seleccione personas', 'warning');
                return;
            }

            // Si no coincide nada, mostrar mensaje genérico
            Swal.fire('', 'Respuesta no esperada del servidor', 'warning');
        },

        error: function(xhr, status, error) {
            console.log('Status: ' + status);
            console.log('Error: ' + error);
            console.log('XHR Response: ' + xhr.responseText);

            Swal.fire('', 'Error: ' + xhr.responseText, 'error');
        }
    });
}


let personas_seleccionadas = []; //Array de personas seleccionadas
let tbl_personas = null; // Variable global para la tabla

function cargar_personas(tipoPostulante, id_plaza, buscarCoincidencias = false) {

    // Asegurarnos de que id_plaza no sea undefined
    id_plaza = id_plaza || '';
    let url = "";
    if (tipoPostulante == "personas") {
        url = '../controlador/TALENTO_HUMANO/th_personasC.php?listar_postulantes=true';
    } else {
        url = '../controlador/TALENTO_HUMANO/POSTULANTES/th_postulantesC.php?listar_postulantes=true';
    }

    // Destruir la tabla existente si ya existe
    if ($.fn.DataTable.isDataTable('#tbl_personas')) {
        $('#tbl_personas').DataTable().clear().destroy();
    }

    tbl_personas = $('#tbl_personas').DataTable({
        responsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        ajax: {
            url: url,
            type: 'POST',
            data: function(d) {
                d.id = id_plaza;
                d.coincidencias = buscarCoincidencias; // Nuevo parámetro
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
                            <input class="form-check-input cbx_pla_per" type="checkbox" value="${item._id}" name="cbx_pla_per_${item._id}" id="cbx_pla_per_${item._id}">
                            <label class="form-label" for="cbx_pla_per_${item._id}">Seleccionar</label>
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

function aplicar_filtro_coincidencias() {
    const buscarCoincidencias = document.getElementById('chk_buscar_coincidencias').checked;
    const tipoPostulante = document.getElementById('ddl_tipo_postulante').value;

    // Recargar la tabla con el nuevo filtro
    cargar_personas(tipoPostulante === 'interno' ? 'personas' : 'postulantes', <?= $_id ?>, buscarCoincidencias);
}

function abrir_modal_postulantes() {
    $('#modal_postulantes').modal('show');
    // Solo llama a cargar_personas si la tabla no ha sido inicializada
    if (!$.fn.DataTable.isDataTable('#tbl_personas')) {
        cargar_personas("personas", <?= $_id ?>);
    }
}



function marcar_cbx_modal_plaza_personas(source) {
    var cbx_pla_per_all = document.querySelectorAll('.cbx_pla_per');

    cbx_pla_per_all.forEach(function(cbx) {
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


function insertar_editar_personas_plaza() {

    var parametros = {
        'th_pla_id': '<?= $_id ?>',
        'personas_seleccionadas': personas_seleccionadas,
        'ddl_tipo_postulante': $('#ddl_tipo_postulante').val() || 'interno'
    };

    insertar_personas_plaza(parametros);
}

function insertar_personas_plaza(parametros) {
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
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_postulacionesC.php?insertar_editar=true',
        type: 'post',
        dataType: 'json',

        success: function(response) {
            if (response == 1) {
                Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                    $('#modal_personas').modal('hide');
                    tbl_postulaciones.ajax.reload();
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
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Postulaciones</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Todas las postulaciones
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
                        <div class="card-title d-flex justify-content-between align-items-center">
                            <h5 class="text-primary mb-0">
                                <i class="bx bx-clipboard-check me-1"></i> Gestión de Postulaciones
                            </h5>
                            <div class="d-flex align-items-center gap-2">
                                <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_informacion_plaza&_id=<?= $_id ?>"
                                    class="btn btn-outline-dark btn-sm">
                                    <i class="bx bx-arrow-back"></i> Regresar
                                </a>

                                <button type="button" class="btn btn-success btn-sm"
                                    onclick="abrir_modal_postulantes()">
                                    <i class="bx bx-plus me-1"></i> Nuevo
                                </button>

                                <button type="button" class="btn btn-primary btn-sm" onclick="actualizar_seguimiento()">
                                    <i class="bx bx-refresh me-1"></i> Actualizar pruebas
                                </button>
                            </div>


                        </div>
                        <hr>
                        <!-- Si viene plaza por defecto, mostrar título readonly -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Plaza asociada</label>
                            <input type="text" id="txt_th_pla_titulo" class="form-control" readonly value=""
                                placeholder="Plaza asociada..." />
                        </div>

                        <div class="table-responsive pt-3">
                            <table id="tbl_postulaciones" class="table table-striped table-hover align-middle"
                                style="width:100%">
                                <thead class="">
                                    <tr>
                                        <th width="22%">
                                            <i class="bx bx-user me-1"></i>Candidato
                                        </th>
                                        <th width="10%">
                                            <i class="bx bx-category me-1"></i>Tipo
                                        </th>
                                        <th width="15%">
                                            <i class="bx bx-envelope me-1"></i>Contacto
                                        </th>
                                        <th width="10%">
                                            <i class="bx bx-calendar me-1"></i>Fecha
                                        </th>
                                        <th width="10%">
                                            <i class="bx bx-list-ul me-1"></i>Estado
                                        </th>
                                        <th width="10%">
                                            <i class="bx bx-world me-1"></i>Fuente
                                        </th>
                                        <th width="8%" class="text-center">
                                            <i class="bx bx-line-chart me-1"></i>Score
                                        </th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal" id="modal_postulantes" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">

                <div class="row pt-3">
                    <!-- 🔹 Selector de tipo de postulante -->
                    <div class="col-md-6 mb-3">
                        <label for="ddl_tipo_postulante" class="form-label fw-bold">
                            <i class="bx bx-user-pin me-2 text-primary"></i>Tipo de Postulante
                        </label>
                        <select class="form-select select2-validation" id="ddl_tipo_postulante"
                            name="ddl_tipo_postulante" required>
                            <option value="interno">Postulantes Internos</option>
                            <option value="externo">Postulantes Nuevos</option>
                        </select>
                    </div>

                    <!-- 🔹 NUEVO: Checkbox para filtrar por coincidencias -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            <i class="bx bx-filter me-2 text-success"></i>Filtros
                        </label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="chk_buscar_coincidencias"
                                onchange="aplicar_filtro_coincidencias()">
                            <label class="form-check-label" for="chk_buscar_coincidencias">
                                Buscar solo postulantes con coincidencias en requisitos
                            </label>
                        </div>
                    </div>
                </div>

                <!-- 🔹 Tabla de personas -->
                <div class="row">
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
                                            <input class="form-check-input" type="checkbox" id="cbx_pla_per_all"
                                                onchange="marcar_cbx_modal_plaza_personas(this)">
                                            <label class="form-check-label" for="cbx_pla_per_all">
                                                Todo
                                            </label>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <!-- 🔹 Botones -->
                <div class="d-flex justify-content-center mt-3">
                    <button class="btn btn-success btn-sm px-4 m-1" onclick="insertar_editar_personas_plaza();"
                        type="button">
                        <i class="bx bx-plus me-1"></i> Agregar
                    </button>
                    <button class="btn btn-secondary btn-sm px-4 m-1" data-bs-dismiss="modal" type="button">
                        <b>X</b> Cancelar
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>