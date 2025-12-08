<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
$_id = (isset($_GET['_id'])) ? $_GET['_id'] : '';
?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script>
$(document).ready(function() {

    <?php if($_id != ''){ ?>
    cargar_competencia(<?= $_id ?>);
    cargar_competencias_detalle(<?= $_id ?>);
    <?php } ?>

    $("#form_competencia").validate({
        ignore: [],
        rules: {
            txt_nombre: {
                required: true
            },
            txt_codigo: {
                required: true
            }
        },
        messages: {
            txt_nombre: {
                required: "Ingrese el nombre de la competencia"
            },
            txt_codigo: {
                required: "Ingrese un código para la competencia"
            }
        },
        highlight: r => $(r).addClass('is-invalid').removeClass('is-valid'),
        unhighlight: r => $(r).addClass('is-valid').removeClass('is-invalid'),
        submitHandler: () => false
    });
});

/* Cargar datos para editar */
function cargar_competencia(id) {
    $.ajax({
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_competenciasC.php?listar=true',
        type: 'post',
        data: {
            id
        },
        dataType: 'json',
        success: function(r) {
            r = Array.isArray(r) ? r[0] : r;

            $("#txt_id").val(r._id);
            $("#txt_codigo").val(r.codigo);
            $("#txt_nombre").val(r.nombre);
            $("#ddl_categoria").val(r.categoria);
            $("#ddl_tipo_disc").val(r.tipo_disc);
            $("#txt_descripcion").val(r.descripcion);
            $("#txt_definicion").val(r.definicion_completa);
            $("#txt_comportamientos").val(r.comportamientos_esperados);
            $("#chk_es_disc").prop('checked', r.es_disc == 1);
        }
    });
}

/* Guardar o editar */
function guardar_competencia() {
    if (!$("#form_competencia").valid()) return;

    var parametros = {
        _id: $("#txt_id").val(),
        txt_th_comp_codigo: $("#txt_codigo").val(),
        txt_th_comp_nombre: $("#txt_nombre").val(),
        ddl_th_comp_categoria: $("#ddl_categoria").val(),
        ddl_th_comp_tipo_disc: $("#ddl_tipo_disc").val(),
        txt_th_comp_descripcion: $("#txt_descripcion").val(),
        txt_th_comp_definicion_completa: $("#txt_definicion").val(),
        txt_th_comp_comportamientos_esperados: $("#txt_comportamientos").val(),
        chk_th_comp_es_disc: $("#chk_es_disc").is(':checked') ? 1 : 0,
        chk_th_comp_estado: 1
    };

    $.ajax({
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_competenciasC.php?insertar_editar=true',
        type: 'post',
        data: {
            parametros
        },
        dataType: 'json',
        success: function(r) {
            if (r == 1) {
                Swal.fire("Éxito", "Competencia guardada correctamente", "success")
                    .then(() => location.href =
                        "../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_competencias");
            } else if (r == -2) {
                Swal.fire("Atención", "Ya existe una competencia con este nombre.", "warning");
            } else {
                Swal.fire("Error", "No se pudo guardar la información", "error");
            }
        }
    });
}

/* Eliminar */
function eliminar_competencia() {
    var id = $("#txt_id").val();
    if (!id) return Swal.fire("", "No hay ID", "info");

    Swal.fire({
        title: '¿Eliminar competencia?',
        text: 'La competencia será desactivada.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar'
    }).then((r) => {
        if (r.isConfirmed) {
            $.ajax({
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_competenciasC.php?eliminar=true',
                type: 'post',
                data: {
                    id
                },
                dataType: 'json',
                success: function(resp) {
                    Swal.fire("Eliminada", "Registro desactivado", "success")
                        .then(() =>
                            location.href =
                            "../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_competencias"
                        );
                }
            });
        }
    });
}
</script>

<script>
function abrir_modal_competencia_detalle(id = '') {
    var modal = new bootstrap.Modal(
        document.getElementById('modal_detalle_comp'), {
            backdrop: 'static',
            keyboard: false
        }
    );

    if (id != "") {
        cargar_detalle_competencia(id);
    } else {
        limpiar_modal_detalle_comp();
    }

    modal.show();
}

function limpiar_modal_detalle_comp() {

    // Limpiar todos los inputs dentro del formulario
    $("#form_detalle_comp")[0].reset();

    // Limpiar campos hidden
    $("#txt_th_carcompdet_id").val('');
    $("#txt_th_carcomp_id").val('');

    // Asegurar que los number se limpian (por si no los limpia reset())
    $("#num_th_carcompdet_nivel_utilizacion").val('');
    $("#num_th_carcompdet_nivel_contribucion").val('');
    $("#num_th_carcompdet_nivel_habilidad").val('');
    $("#num_th_carcompdet_nivel_maestria").val('');
    $("#num_th_carcompdet_orden").val('');

    // Ocultar botón eliminar
    $("#btn_eliminar_detalle_modal").hide();
}


function cargar_competencias_detalle(id_cargo) {

    // Si ya existe el DataTable, lo destruimos para evitar duplicados
    if ($.fn.dataTable.isDataTable('#tbl_competencia_detalles')) {
        $('#tbl_competencia_detalles').DataTable().clear().destroy();
        $('#tbl_competencia_detalles').empty();
    }

    tbl_competencia_detalles = $('#tbl_competencia_detalles').DataTable($.extend({}, configuracion_datatable('Nombre',
        'tipo',
        'fecha'), {
        responsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        ajax: {
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_competencias_detalleC.php?listar_competencia_detalle=true',
            type: 'POST',
            data: function(d) {
                d.id = id_cargo;
            },
            dataSrc: ''
        },
        columns: [{
                data: null,
                render: function(data, type, item) {

                    return `
            <a href="javascript:void(0);" 
               onclick="abrir_modal_competencia_detalle('${item._id}')">
               <u>${item.subcompetencia}</u>
            </a>
        `;
                }
            },
            {
                data: 'descripcion',
                title: 'Descripción'
            },
            {
                data: 'nivel_utilizacion',
                title: 'Nivel Utilización',
                className: 'text-center'
            },
            {
                data: 'nivel_contribucion',
                title: 'Nivel Contribución',
                className: 'text-center'
            },
            {
                data: 'nivel_habilidad',
                title: 'Nivel Habilidad',
                className: 'text-center'
            },
            {
                data: 'nivel_maestria',
                title: 'Nivel Maestría',
                className: 'text-center'
            },
            {
                data: 'indicador_medicion',
                title: 'Indicador Medición'
            },
            {
                data: 'comportamientos_observables',
                title: 'Comportamientos Observables',
                render: function(data) {
                    // Limita el texto si es muy largo
                    if (data && data.length > 100) {
                        return '<span title="' + data + '">' + data.substring(0, 100) +
                            '...</span>';
                    }
                    return data || '';
                }
            },
            {
                data: 'orden',
                title: 'Orden',
                className: 'text-center',
                width: '60px'
            }
        ],
        order: [
            [8, 'asc'] // Ordenar por la columna "orden"
        ],
        columnDefs: [{
            targets: [2, 3, 4, 5], // Columnas de niveles
            className: 'dt-body-center'
        }]
    }));
}


function cargar_detalle_competencia(id) {

    $.ajax({
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_competencias_detalleC.php?listar=true',
        type: 'post',
        data: {
            id: id
        },
        dataType: 'json',
        success: function(r) {

            r = Array.isArray(r) ? r[0] : r;

            $("#txt_th_carcompdet_id").val(r._id);
            $("#txt_th_carcomp_id").val(r.th_carcomp_id);
            $("#txt_th_carcompdet_subcompetencia").val(r.subcompetencia);
            $("#txt_th_carcompdet_descripcion").val(r.descripcion);
            $("#txt_th_carcompdet_indicador_medicion").val(r.indicador_medicion);
            $("#txt_th_carcompdet_comportamientos_observables").val(r.comportamientos_observables);
            $("#num_th_carcompdet_nivel_utilizacion").val(r.nivel_utilizacion);
            $("#num_th_carcompdet_nivel_contribucion").val(r.nivel_contribucion);
            $("#num_th_carcompdet_nivel_habilidad").val(r.nivel_habilidad);
            $("#num_th_carcompdet_nivel_maestria").val(r.nivel_maestria);
            $("#num_th_carcompdet_orden").val(r.orden);

            if (r._id > 0) {
                $("#btn_eliminar_detalle_modal").show();
            } else {
                $("#btn_eliminar_detalle_modal").hide();
            }
            $("#modal_detalle_comp").modal("show");
        }
    });
}



$(function() {
    // Inicializar: cargar lista si existe th_carcomp_id
    var th_carcomp_id = $("#hd_th_carcomp_id").val() || $("#txt_th_carcomp_id").val();
    if (th_carcomp_id) {
        cargar_lista_detalles(th_carcomp_id);
    }

    // Validación del modal
    $("#form_detalle_comp").validate({
        ignore: [],
        rules: {
            txt_th_carcompdet_subcompetencia: {
                required: true
            }
        },
        messages: {
            txt_th_carcompdet_subcompetencia: {
                required: "Ingrese la subcompetencia"
            }
        },
        highlight: r => $(r).addClass('is-invalid').removeClass('is-valid'),
        unhighlight: r => $(r).addClass('is-valid').removeClass('is-invalid'),
        submitHandler: () => false
    });
});

function abrir_modal_detalle(th_carcomp_id, detalle_id = '') {
    $("#txt_th_carcomp_id").val(th_carcomp_id || $("#hd_th_carcomp_id").val());

    $("#form_detalle_comp")[0].reset();
    $("#txt_th_carcompdet_id").val('');
    $("#btn_eliminar_detalle_modal").hide();

    if (detalle_id) {
        // editar: cargar
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_competencias_detalleC.php?listar=true',
            type: 'post',
            data: {
                id: detalle_id
            },
            dataType: 'json',
            success: function(resp) {
                var r = Array.isArray(resp) ? resp[0] : resp;
                if (!r) return;
                $("#txt_th_carcompdet_id").val(r.th_carcompdet_id || r._id || '');
                $("#txt_th_carcompdet_subcompetencia").val(r.th_carcompdet_subcompetencia || '');
                $("#txt_th_carcompdet_descripcion").val(r.th_carcompdet_descripcion || '');
                $("#txt_th_carcompdet_indicador_medicion").val(r.th_carcompdet_indicador_medicion || '');
                $("#txt_th_carcompdet_comportamientos_observables").val(r
                    .th_carcompdet_comportamientos_observables || '');
                $("#num_th_carcompdet_nivel_utilizacion").val(r.th_carcompdet_nivel_utilizacion || '');
                $("#num_th_carcompdet_nivel_contribucion").val(r.th_carcompdet_nivel_contribucion || '');
                $("#num_th_carcompdet_nivel_habilidad").val(r.th_carcompdet_nivel_habilidad || '');
                $("#num_th_carcompdet_nivel_maestria").val(r.th_carcompdet_nivel_maestria || '');
                $("#num_th_carcompdet_orden").val(r.th_carcompdet_orden || '');
                $("#btn_eliminar_detalle_modal").show();
                // abrir modal
                var modal = new bootstrap.Modal(document.getElementById('modal_detalle_comp'));
                modal.show();
            },
            error: function(xhr) {
                console.error('Error cargar detalle:', xhr.responseText);
                Swal.fire('Error', 'No se pudo cargar el detalle', 'error');
            }
        });
    } else {
        // nuevo: abrir modal vacío
        var modal = new bootstrap.Modal(document.getElementById('modal_detalle_comp'));
        modal.show();
    }
}

/* Guardar / actualizar */
function guardar_actualizar_detalle_comp() {
    if (!$("#form_detalle_comp").valid()) return;

    var parametros = {
        '_id': $("#txt_th_carcompdet_id").val(),
        'th_carcomp_id': "<?= isset($_id) ? $_id : '' ?>",
        'th_carcompdet_subcompetencia': $("#txt_th_carcompdet_subcompetencia").val(),
        'th_carcompdet_descripcion': $("#txt_th_carcompdet_descripcion").val(),
        'th_carcompdet_indicador_medicion': $("#txt_th_carcompdet_indicador_medicion").val(),
        'th_carcompdet_comportamientos_observables': $("#txt_th_carcompdet_comportamientos_observables").val(),
        'th_carcompdet_nivel_utilizacion': $("#num_th_carcompdet_nivel_utilizacion").val(),
        'th_carcompdet_nivel_contribucion': $("#num_th_carcompdet_nivel_contribucion").val(),
        'th_carcompdet_nivel_habilidad': $("#num_th_carcompdet_nivel_habilidad").val(),
        'th_carcompdet_nivel_maestria': $("#num_th_carcompdet_nivel_maestria").val(),
        'th_carcompdet_orden': $("#num_th_carcompdet_orden").val(),
        'th_carcompdet_estado': $('#chk_th_carcompdet_estado').is(':checked') ? 1 : 0
    };

    $.ajax({
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_competencias_detalleC.php?insertar_editar=true',
        type: 'post',
        data: {
            parametros: parametros
        },
        dataType: 'json',
        success: function(r) {
            if (r == 1) {
                Swal.fire('Éxito', 'Detalle guardado correctamente', 'success');
                $("#modal_detalle_comp").modal("hide");
                $('#tbl_competencia_detalles').DataTable().ajax.reload(null, false);
            } else if (r == -2) {
                Swal.fire('Atención', 'Ya existe un detalle con ese nombre.', 'warning');
            } else {
                Swal.fire('Error', 'No se pudo guardar el detalle', 'error');
            }
        },
        error: function(xhr) {
            console.error('Error guardar detalle:', xhr.responseText);
            Swal.fire('Error', 'Ocurrió un error al guardar (revisar consola).', 'error');
        }
    });
}


function eliminar_detalle_comp() {
    Swal.fire({
        title: 'Eliminar detalle?',
        text: 'Se desactivará el detalle (soft-delete).',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar'
    }).then((res) => {
        if (res.isConfirmed) {
            id = $("#txt_th_carcompdet_id").val()
            eliminar_detalle_competencia(id);
        }
    });
}

function eliminar_detalle_competencia(id) {
    $.ajax({
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_competencias_detalleC.php?eliminar=true',
        type: 'post',
        data: {
            id: id
        },
        dataType: 'json',
        success: function(resp) {
            if (resp == 1) {
                Swal.fire('Eliminado', 'Detalle eliminado', 'success');
                $("#modal_detalle_comp").modal("hide");
                $('#tbl_competencia_detalles').DataTable().ajax.reload(null, false);
            } else {
                Swal.fire('Error', 'No se pudo eliminar', 'error');
            }
        },
        error: function(xhr) {
            console.error('Error eliminar detalle:', xhr.responseText);
            Swal.fire('Error', 'Ocurrió un error al eliminar (revisar consola).', 'error');
        }
    });
}
</script>


<div class="page-wrapper">
    <div class="page-content">

        <div class="card border-primary border-3">
            <div class="card-body p-4">

                <div class="card-title d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-list-ul me-2 font-22 text-primary"></i>
                        <h5 class="mb-0 text-primary">
                            <?= ($_id == '') ? 'Registrar Competencia' : 'Modificar Competencia' ?>
                        </h5>
                    </div>

                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_competencias"
                        class="btn btn-outline-dark btn-sm">
                        <i class="bx bx-arrow-back"></i> Regresar
                    </a>
                </div>

                <hr>


                <div class="">
                    <ul class="nav nav-tabs nav-primary" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" data-bs-toggle="tab" href="#competencia" role="tab"
                                aria-selected="true">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon"><i class='bx bx-briefcase-alt font-18 me-1'></i>
                                    </div>
                                    <div class="tab-title">Competenecia</div>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#detalleCompetencia" role="tab"
                                aria-selected="false">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon"><i class='bx bxs-file-blank font-18 me-1'></i>
                                    </div>
                                    <div class="tab-title">Detalle de la competenecias</div>
                                </div>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content py-3">
                        <div class="tab-pane fade show active" id="competencia" role="tabpanel">
                            <section class="content pt-0">
                                <div class="container-fluid">
                                    <h5 class="fw-bold text-primary mb-3">
                                        <i class="bx bx-info-circle me-2"></i>
                                        Competencia
                                    </h5>

                                    <form id="form_competencia" novalidate>
                                        <input type="hidden" id="txt_id" value="<?= $_id ?>">

                                        <div class="row">

                                            <!-- Código -->
                                            <div class="col-md-4 mb-3">
                                                <label class="fw-bold">Código</label>
                                                <input type="text" id="txt_codigo" name="txt_codigo"
                                                    class="form-control" placeholder="Ej: DISC_DOM, COMP_COM_01">
                                            </div>

                                            <!-- Nombre -->
                                            <div class="col-md-4 mb-3">
                                                <label class="fw-bold">Nombre</label>
                                                <input type="text" id="txt_nombre" name="txt_nombre"
                                                    class="form-control" placeholder="Ej: Comunicación efectiva">
                                            </div>

                                            <!-- Categoría -->
                                            <div class="col-md-4 mb-3">
                                                <label class="fw-bold">Categoría</label>
                                                <select id="ddl_categoria" class="form-select">
                                                    <option value="">Seleccione...</option>
                                                    <option value="DISC">DISC</option>
                                                    <option value="Blanda">Blanda</option>
                                                    <option value="Técnica">Técnica</option>
                                                    <option value="Liderazgo">Liderazgo</option>
                                                </select>
                                            </div>

                                            <!-- Tipo DISC -->
                                            <div class="col-md-4 mb-3">
                                                <label class="fw-bold">Tipo DISC</label>
                                                <select id="ddl_tipo_disc" class="form-select">
                                                    <option value="">N/A</option>
                                                    <option value="D">D – Dominancia</option>
                                                    <option value="I">I – Influencia</option>
                                                    <option value="S">S – Solidez</option>
                                                    <option value="C">C – Cumplimiento</option>
                                                </select>
                                            </div>

                                            <!-- Descripción corta -->
                                            <div class="col-md-8 mb-3">
                                                <label class="fw-bold">Descripción</label>
                                                <textarea id="txt_descripcion" class="form-control" rows="1"></textarea>
                                            </div>

                                            <!-- Definición completa -->
                                            <div class="col-md-12 mb-3">
                                                <label class="fw-bold">Definición completa</label>
                                                <textarea id="txt_definicion" class="form-control" rows="2"></textarea>
                                            </div>

                                            <!-- Comportamientos esperados -->
                                            <div class="col-md-12 mb-3">
                                                <label class="fw-bold">Comportamientos esperados</label>
                                                <textarea id="txt_comportamientos" class="form-control"
                                                    rows="2"></textarea>
                                            </div>

                                            <!-- Es DISC -->
                                            <div class="col-md-3 d-flex align-items-center mb-3">
                                                <div>
                                                    <label class="fw-bold">¿Es DISC?</label><br>
                                                    <input type="checkbox" id="chk_es_disc" class="form-check-input">
                                                </div>
                                            </div>

                                        </div>

                                        <!-- Botones -->
                                        <div class="d-flex justify-content-end gap-2 mt-3">
                                            <?php if($_id == ''){ ?>
                                            <button type="button" class="btn btn-success"
                                                onclick="guardar_competencia()">
                                                <i class="bx bx-save"></i> Guardar
                                            </button>
                                            <?php } else { ?>
                                            <button type="button" class="btn btn-primary"
                                                onclick="guardar_competencia()">
                                                <i class="bx bx-edit"></i> Actualizar
                                            </button>
                                            <button type="button" class="btn btn-danger"
                                                onclick="eliminar_competencia()">
                                                <i class="bx bx-trash"></i> Eliminar
                                            </button>
                                            <?php } ?>
                                        </div>

                                    </form>

                                </div>
                            </section>
                        </div>
                        <div class="tab-pane fade" id="detalleCompetencia" role="tabpanel">
                            <section class="content pt-0">
                                <div class="container-fluid">
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-md-8">
                                            <h5 class="fw-bold text-primary mb-0">
                                                <i class="bx bx-info-circle me-2"></i>
                                                Detalle de la competencia
                                            </h5>
                                            <small class="text-muted">Subcompetencias, indicadores y comportamientos
                                                observables</small>
                                        </div>

                                        <div class="col-md-4 text-end">
                                            <button type="button" class="btn btn-success btn-sm shadow-sm"
                                                onclick="abrir_modal_competencia_detalle('')">
                                                <i class="bx bx-plus-circle me-1"></i> Registrar Aspectos
                                            </button>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered"
                                                id="tbl_competencia_detalles" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Subcompetencia</th>
                                                        <th>Descripción</th>
                                                        <th>Nivel Utilización</th>
                                                        <th>Nivel Contribución</th>
                                                        <th>Nivel Habilidad</th>
                                                        <th>Nivel Maestría</th>
                                                        <th>Indicador Medición</th>
                                                        <th>Comportamientos Observables</th>
                                                        <th>Orden</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
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

<!-- Modal: Detalle de Competencia -->
<div class="modal fade" id="modal_detalle_comp" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalle de Competencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="form_detalle_comp" novalidate>
                    <input type="hidden" id="txt_th_carcompdet_id" name="txt_th_carcompdet_id" value="">
                    <input type="hidden" id="txt_th_carcomp_id" name="txt_th_carcomp_id" value="">

                    <div class="row g-2">
                        <div class="col-md-12">
                            <label class="fw-bold">Subcompetencia</label>
                            <input type="text" id="txt_th_carcompdet_subcompetencia"
                                name="txt_th_carcompdet_subcompetencia" class="form-control"
                                placeholder="Ej: Toma de decisiones bajo presión" required>
                        </div>

                        <div class="col-md-12">
                            <label class="fw-bold">Descripción</label>
                            <textarea id="txt_th_carcompdet_descripcion" name="txt_th_carcompdet_descripcion"
                                class="form-control" rows="2" placeholder="Descripción breve..."></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="fw-bold">Indicador de medición</label>
                            <input type="text" id="txt_th_carcompdet_indicador_medicion"
                                name="txt_th_carcompdet_indicador_medicion" class="form-control"
                                placeholder="Cómo medir esta subcompetencia">
                        </div>

                        <div class="col-md-6">
                            <label class="fw-bold">Comportamientos observables</label>
                            <input type="text" id="txt_th_carcompdet_comportamientos_observables"
                                name="txt_th_carcompdet_comportamientos_observables" class="form-control"
                                placeholder="Ej: Decide con rapidez; lidera en crisis">
                        </div>

                        <div class="col-md-3">
                            <label class="fw-bold">Nivel - Utilización</label>
                            <input type="number" id="num_th_carcompdet_nivel_utilizacion" min="0" max="100"
                                class="form-control" placeholder="0-100">
                        </div>
                        <div class="col-md-3">
                            <label class="fw-bold">Nivel - Contribución</label>
                            <input type="number" id="num_th_carcompdet_nivel_contribucion" min="0" max="100"
                                class="form-control" placeholder="0-100">
                        </div>
                        <div class="col-md-3">
                            <label class="fw-bold">Nivel - Habilidad</label>
                            <input type="number" id="num_th_carcompdet_nivel_habilidad" min="0" max="100"
                                class="form-control" placeholder="0-100">
                        </div>
                        <div class="col-md-3">
                            <label class="fw-bold">Nivel - Maestría</label>
                            <input type="number" id="num_th_carcompdet_nivel_maestria" min="0" max="100"
                                class="form-control" placeholder="0-100">
                        </div>

                        <div class="col-md-3">
                            <label class="fw-bold">Orden</label>
                            <input type="number" id="num_th_carcompdet_orden" min="1" class="form-control"
                                placeholder="1">
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <div class="me-auto">
                    <button type="button" class="btn btn-danger" id="btn_eliminar_detalle_modal" style="display:none"
                        onclick="eliminar_detalle_comp()"> <i class="bx bx-trash"></i>
                        Eliminar</button>
                </div>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="guardar_actualizar_detalle_comp()">
                    <i class="bx bx-save"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>