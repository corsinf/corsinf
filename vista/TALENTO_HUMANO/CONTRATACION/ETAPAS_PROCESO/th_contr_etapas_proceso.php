<?php //include('../cabeceras/header.php');

$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$_id = '';

if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}

?>

<script src="../js/ACTIVOS_FIJOS/avaluos.js"></script>
<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
let tbl_etapas;

$(document).ready(function() {

    // Inicializar datatable de etapas
    tbl_etapas = $('#tbl_etapas').DataTable($.extend({}, configuracion_datatable('Nombre', 'tipo',
        'orden'), {
        responsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        ajax: {
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_etapas_procesoC.php?listar=true',
            dataSrc: ''
        },
        columns: [{
                data: null,
                render: function(data, type, item) {
                    // link al formulario de registro/modificación (ajusta acc si lo tienes distinto)
                    href =
                        `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registro_etapa&_id=${item._id}`;
                    return `<a href="${href}"><u>${item.nombre}</u></a>`;
                }
            },
            {
                data: 'tipo',
                render: function(data) {
                    return data ? data : '';
                }
            },
            {
                data: 'orden',
                render: function(data) {
                    return data !== null && data !== undefined ? data : '';
                }
            },
            {
                data: 'obligatoria',
                render: function(data) {
                    return (data == 1 || data === true || data === '1') ?
                        '<span class="badge bg-success">Sí</span>' :
                        '<span class="badge bg-secondary">No</span>';
                }
            },
            {
                data: 'descripcion',
                render: function(data, type, item) {
                    if (!data) return '';
                    // Acortar descripción en la tabla
                    return data.length > 120 ? data.substring(0, 117) + '...' : data;
                }
            }
        ],
        order: [
            [0, 'asc']
        ]
    }));

    // Cargar opciones de plazas en el modal (si existe endpoint)
    cargarPlazasParaSelect();

});

function cargarPlazasParaSelect() {
    // Intenta cargar plazas desde tu controlador de plazas (ajusta la ruta si es otra)
    $.ajax({
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_plazasC.php?listar=true',
        method: 'POST',
        dataType: 'json'
    }).done(function(resp) {
        let $sel = $('#sel_th_pla_id');
        $sel.empty();
        $sel.append(`<option value="">-- Seleccione plaza (opcional) --</option>`);
        resp.forEach(function(r) {
            // Asumimos que r._id y r.th_pla_titulo o r.nombre están presentes; ajusta si es distinto
            let texto = r.th_pla_titulo ?? r.nombre ?? (`Plaza ${r._id}`);
            $sel.append(`<option value="${r._id}">${texto}</option>`);
        });
    }).fail(function() {
        // No hacemos nada crítico si falla; el select quedará vacío
        console.warn('No se pudieron cargar las plazas para el select (opcional).');
    });
}

// Guardado rápido desde modal
function guardar_etapa_rapida() {
    // Validaciones básicas
    let nombre = $('#quick_nombre').val().trim();
    if (nombre === '') {
        Swal.fire({
            icon: 'warning',
            title: 'Nombre requerido',
            text: 'Ingrese el nombre de la etapa.',
            confirmButtonText: 'Entendido'
        }).then(() => {
            $('#quick_nombre').focus();
        });
        return;
    }

    // Recopilar parámetros en el mismo formato que espera el controlador
    let parametros = {
        'txt_nombre': nombre,
        'txt_tipo': $('#quick_tipo').val().trim(),
        'txt_orden': $('#quick_orden').val().trim(),
        'chk_obligatoria': $('#quick_obligatoria').is(':checked') ? 1 : 0,
        'txt_descripcion': $('#quick_descripcion').val().trim(),
        'sel_th_pla_id': $('#sel_th_pla_id').val(), // opcional
        'chk_estado': $('#quick_estado').is(':checked') ? 1 : 0
    };

    $.ajax({
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_etapas_procesoC.php?insertar_editar=true',
        method: 'POST',
        data: {
            parametros: parametros
        },
        dataType: 'json',
        beforeSend: function() {
            // opcional: mostrar loading
        }
    }).done(function(resp) {
        // En tus controladores usualmente devuelves 1 = éxito, -2 = duplicado, etc.
        if (resp == 1 || resp === 1) {
            Swal.fire({
                icon: 'success',
                title: 'Guardado',
                text: 'Etapa agregada con éxito.',
                confirmButtonText: 'OK'
            }).then(() => {
                $('#modal_etapa_quick').modal('hide');
                // Limpiar formulario rápido
                $('#quick_nombre').val('');
                $('#quick_tipo').val('');
                $('#quick_orden').val('');
                $('#quick_obligatoria').prop('checked', false);
                $('#quick_descripcion').val('');
                $('#quick_estado').prop('checked', true);
                $('#sel_th_pla_id').val('');
                // Recargar tabla
                tbl_etapas.ajax.reload(null, false);
            });
        } else if (resp == -2 || resp === -2) {
            Swal.fire({
                icon: 'warning',
                title: 'Duplicado',
                text: 'Ya existe una etapa con ese nombre en la misma plaza.',
                confirmButtonText: 'Corregir'
            }).then(() => {
                $('#quick_nombre').focus();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al guardar la etapa. Código: ' + resp,
                confirmButtonText: 'OK'
            });
        }
    }).fail(function(xhr, status, err) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo conectar con el servidor. ' + err,
            confirmButtonText: 'OK'
        });
    });
}
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Etapas del proceso</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Todas las etapas
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

                            <h5 class="mb-0 text-primary"></h5>

                            <div class="row mx-0">

                                <div class="" id="btn_nuevo">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registro_etapa"
                                        type="button" class="btn btn-success btn-sm ">
                                        <i class="bx bx-plus me-0 pb-1"></i> Nuevo
                                    </a>
                                </div>
                            </div>
                        </div>


                        <section class="content pt-2">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped responsive " id="tbl_etapas" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Tipo</th>
                                                <th>Orden</th>
                                                <th>Obligatoria</th>
                                                <th>Descripción</th>
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
        </div>
        <!--end row-->
    </div>
</div>


<!-- Modal de creación rápida de etapa -->
<div class="modal" id="modal_etapa_quick" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">Agregar etapa (rápido)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

                <div class="row g-2">
                    <div class="col-md-6">
                        <label for="sel_th_pla_id" class="form-label">Plaza (opcional)</label>
                        <select id="sel_th_pla_id" class="form-select form-select-sm">
                            <option value="">-- Cargando plazas... --</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="quick_nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" id="quick_nombre" class="form-control form-control-sm" />
                    </div>

                    <div class="col-md-4 pt-2">
                        <label for="quick_tipo" class="form-label">Tipo</label>
                        <input type="text" id="quick_tipo" class="form-control form-control-sm" />
                    </div>

                    <div class="col-md-4 pt-2">
                        <label for="quick_orden" class="form-label">Orden</label>
                        <input type="number" id="quick_orden" class="form-control form-control-sm" />
                    </div>

                    <div class="col-md-4 pt-2">
                        <label class="form-label d-block">Obligatoria</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="quick_obligatoria">
                            <label class="form-check-label" for="quick_obligatoria">Sí</label>
                        </div>
                    </div>

                    <div class="col-12 pt-2">
                        <label for="quick_descripcion" class="form-label">Descripción</label>
                        <textarea id="quick_descripcion" class="form-control form-control-sm" rows="3"></textarea>
                    </div>

                    <div class="col-12 pt-3 text-end">
                        <div class="form-check form-switch d-inline-block me-3">
                            <input class="form-check-input" type="checkbox" id="quick_estado" checked>
                            <label class="form-check-label" for="quick_estado">Activo</label>
                        </div>

                        <button type="button" class="btn btn-success btn-sm" onclick="guardar_etapa_rapida()"><i
                                class="bx bx-save"></i> Agregar</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>