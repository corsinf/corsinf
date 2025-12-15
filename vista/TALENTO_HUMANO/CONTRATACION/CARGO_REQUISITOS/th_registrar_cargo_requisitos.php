<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
$_id = '';

if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}
?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script>
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
                        "../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_cargo_requisitos"
                    );
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
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_union_cargo_requisito_detalleC.php?eliminar=true',
                type: 'POST',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(resp) {
                    if (resp == 1 || resp === true) {
                        Swal.fire('', 'Requisito detalle eliminado.', 'success');
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

function abrir_modal_requisitos_detalle() {
    var modal = new bootstrap.Modal(
        document.getElementById('modal_requisito_detalle'), {
            backdrop: 'static',
            keyboard: false
        }
    );

    cargar_cargo_requisitos(<?= $_id ?>);
    modal.show();
}

function cargar_cargo_requisitos(id_cargo_requisito) {
    // Si select2 ya está inicializado, destruirlo
    if ($('#ddl_requisito_detalle').hasClass("select2-hidden-accessible")) {
        $('#ddl_requisito_detalle').select2('destroy');
    }

    $('#ddl_requisito_detalle').select2({
        dropdownParent: $('#modal_requisito_detalle'),
        ajax: {
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_union_cargo_requisito_detalleC.php?buscar=true',
            dataType: 'json',
            data: function(params) {
                return {
                    q: params.term,
                    th_car_req_id: id_cargo_requisito
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

function insertar_requisito_detalle() {
    $.ajax({
        data: {
            parametros: Parametros_Car_Req()
        },
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_union_cargo_requisito_detalleC.php?insertar_editar=true',
        type: 'post',
        dataType: 'json',
        success: function(res) {
            if (res > 0) {
                Swal.fire('', 'Requisito agregado con éxito.', 'success').then(function() {
                    $('#modal_requisito_detalle').modal('hide');
                    $('#tbl_req_detalles').DataTable().ajax.reload(null, false);
                    $('#ddl_requisito_detalle').empty().append(
                        '<option value="" selected hidden>-- Seleccione el requisito detalle --</option>'
                    );
                });
            } else if (res == -2) {
                Swal.fire('', res.msg || 'Error al guardar requisito.', 'error');
            } else {
                Swal.fire('', res.msg || 'Error al guardar requisito.', 'error');
            }
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            Swal.fire('', 'Error: ' + xhr.responseText, 'error');
        }
    });
}

function Parametros_Car_Req() {
    return {
        'th_car_req_id': "<?= isset($_id)? $_id : '' ?>",
        'ddl_requisito_detalle': $('#ddl_requisito_detalle').val()
    };
}

function cargar_requisitos_cargo(id_cargo_requisito) {
    // Si ya existe el DataTable, lo destruimos
    if ($.fn.dataTable.isDataTable('#tbl_req_detalles')) {
        $('#tbl_req_detalles').DataTable().clear().destroy();
        $('#tbl_req_detalles').empty();
    }

    $('#tbl_req_detalles').DataTable($.extend({}, configuracion_datatable('Nombre', 'tipo', 'fecha'), {
        responsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        ajax: {
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_union_cargo_requisito_detalleC.php?listar=true',
            type: 'POST',
            data: function(d) {
                d.id = id_cargo_requisito;
            },
            dataSrc: ''
        },
        columns: [{
            data: 'nombre'
        }, {
            data: 'descripcion',
            render: function(data, type, item) {
                if (!data) return '';
                return data.length > 120 ? data.substring(0, 117) + '...' : data;
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
                var id = item.th_req_reqdet_id;
                return `
                    <button class="btn btn-danger btn-sm"
                            onclick="eliminar_cargo_requisito(${id})"
                            title="Eliminar Requisito">
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

// ============================================
// DOCUMENT READY
// ============================================
$(document).ready(function() {

    // Si existe ID cargamos para editar
    <?php if($_id != ''){ ?>
    cargar_requisito(<?= $_id ?>);
    cargar_requisitos_cargo(<?= $_id ?>);
    <?php } ?>

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

    // Event listener para limpiar errores
    $('#txt_th_pla_titulo').on('input', function() {
        $(this).removeClass('is-invalid');
        $('#error_txt_th_pla_titulo').text('');
    });

});
</script>

<div class="page-wrapper">
    <div class="page-content">

        <div class="card border-primary border-3">
            <div class="card-body p-4">

                <div class="card-title d-flex align-items-center">
                    <div><i class="bx bxs-briefcase me-1 font-22 text-primary"></i></div>
                    <h5 class="mb-0 text-primary">
                        <?php
                                if ($_id == '') {
                                    echo 'Registrar Requisito del Cargo';
                                } else {
                                    echo 'Modificar Requisito del Cargo';
                                }
                                ?>
                    </h5>

                    <div class="row m-2">
                        <div class="col-sm-12">
                            <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_cargo_requisitos"
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
                                        <div class="tab-icon"><i class='bx bxs-file-blank font-18 me-1'></i></div>
                                        <div class="tab-title">Requisitos</div>
                                    </div>
                                </a>
                            </li>

                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#primaryprofile" role="tab"
                                    aria-selected="false">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon"><i class='bx bx-briefcase-alt font-18 me-1'></i></div>
                                        <div class="tab-title">Requisitos del cargo</div>
                                    </div>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content py-3">
                            <div class="tab-pane fade show active" id="primaryhome" role="tabpanel">
                                <section class="content pt-0">
                                    <div class="container-fluid">
                                        <form id="form_requisito">
                                            <input type="hidden" id="txt_id" value="<?= $_id ?>">

                                            <div class="mb-3">
                                                <label class="fw-bold">Nombre del Requisito</label>
                                                <input type="text" id="txt_nombre" name="txt_nombre"
                                                    class="form-control"
                                                    placeholder="Ejemplo: Licencia de conducir, Certificación, etc...">
                                            </div>

                                            <div class="mb-3">
                                                <label class="fw-bold">Descripción</label>
                                                <textarea id="txt_descripcion" name="txt_descripcion"
                                                    class="form-control" rows="4"
                                                    placeholder="Detalle del requerimiento..."></textarea>
                                            </div>

                                            <div class="d-flex justify-content-end gap-2">
                                                <?php if($_id==""){ ?>
                                                <button type="button" class="btn btn-success"
                                                    onclick="guardar_actualizar()">
                                                    <i class="bx bx-save"></i> Guardar
                                                </button>
                                                <?php }else{ ?>
                                                <button type="button" class="btn btn-primary"
                                                    onclick="guardar_actualizar()">
                                                    <i class="bx bx-edit"></i> Actualizar
                                                </button>
                                                <button type="button" class="btn btn-danger"
                                                    onclick="eliminar_requisito()">
                                                    <i class="bx bx-trash"></i> Eliminar
                                                </button>
                                                <?php } ?>

                                            </div>
                                        </form>
                                    </div>
                                </section>
                            </div><!-- /.container-fluid -->

                        </div>
                        <div class="tab-pane fade" id="primaryprofile" role="tabpanel">
                            <section class="content pt-0">
                                <div class="container-fluid">
                                    <?php if ($_id != '') { ?>
                                    <button type="button" class="btn btn-success"
                                        onclick="abrir_modal_requisitos_detalle()">
                                        <i class="bx bx-plus me-1"></i> Agregar Requisito Detalle
                                    </button>
                                    <?php } ?>
                                    </hr>

                                    <div class="table-responsive">
                                        <table class="table table-striped responsive" id="tbl_req_detalles"
                                            style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Nombre</th>
                                                    <th>Descripción</th>
                                                    <th class="text-center">Oblig.</th>
                                                    <th>Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>

                                </div><!-- /.container-fluid -->
                            </section>
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>

</div>

<div class="modal fade" id="modal_requisito_detalle" tabindex="-1" aria-labelledby="modalRequisitoLabel"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="modalRequisitoLabel">
                    <i class="bx bx-list-check me-2"></i> Registrar Requisito Detalle
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body p-4">
                <form id="form_requisito">
                    <div class="col-md-12">
                        <label for="ddl_requisito_detalle" class="form-label fw-bold">
                            <i class="bx bx-briefcase me-2 text-primary"></i> Requisito Detalle
                        </label>
                        <select class="form-select select2-validation" id="ddl_requisito_detalle"
                            name="ddl_requisito_detalle" required>
                            <option value="" selected hidden>-- Seleccione el requisito detalle --</option>
                        </select>
                    </div>
                    <!-- Botones de acción -->
                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            <i class="bx bx-x me-1"></i> Cancelar
                        </button>

                        <div id="pnl_crear">
                            <button type="button" class="btn btn-success" onclick="insertar_requisito_detalle()">
                                <i class="bx bx-save me-1"></i> Crear Requisito Detalle
                            </button>
                        </div>

                        <div id="pnl_actualizar" style="display:none">
                            <button type="button" class="btn btn-danger" id="btn_eliminar_req">
                                <i class="bx bx-trash me-1"></i> Eliminar
                            </button>
                            <button type="button" class="btn btn-primary" id="btn_editar_req">
                                <i class="bx bx-check me-1"></i> Actualizar Requisito Detalle
                            </button>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>