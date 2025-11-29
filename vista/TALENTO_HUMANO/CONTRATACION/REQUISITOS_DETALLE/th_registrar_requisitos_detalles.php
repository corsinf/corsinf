<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
$_id = (isset($_GET['_id'])) ? $_GET['_id'] : '';
?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>

<script>
$(document).ready(function() {

    // Si existe ID cargamos para editar
    <?php if($_id != ''){ ?>
    cargar_detalle(<?= $_id ?>);
    <?php } ?>

    // Validación del formulario
    $("#form_detalle").validate({
        ignore: [],
        rules: {
            txt_nombre: {
                required: true
            }
        },
        messages: {
            txt_nombre: {
                required: "Ingrese el nombre del detalle"
            }
        },
        highlight: r => $(r).addClass('is-invalid').removeClass('is-valid'),
        unhighlight: r => $(r).addClass('is-valid').removeClass('is-invalid'),
        submitHandler: () => false
    });
});

/* Cargar detalle para modificar */
function cargar_detalle(id) {
    $.ajax({
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_requisitos_detallesC.php?listar=true',
        type: 'post',
        data: {
            id: id
        },
        dataType: 'json',
        success: function(response) {
            // El controlador devuelve array o registro; adaptamos
            var r = (Array.isArray(response) && response.length > 0) ? response[0] : response;
            if (!r) return;
            $("#txt_id").val(r.th_reqdet_id || r._id || '');
            $("#txt_nombre").val(r.th_reqdet_nombre || r.nombre || '');
            $("#txt_descripcion").val(r.th_reqdet_descripcion || r.descripcion || '');
            var ob = r.th_reqdet_obligatorio || r.obligatorio || 0;
            $('#chk_obligatorio').prop('checked', (ob == 1 || ob === true || ob === '1'));
        },
        error: function(xhr) {
            console.error('Error cargar_detalle:', xhr.responseText);
            Swal.fire('Error', 'No se pudo cargar el detalle (revisar consola).', 'error');
        }
    });
}

/* Guardar o editar según exista ID */
function guardar_actualizar_detalle() {
    if (!$("#form_detalle").valid()) return;

    var parametros = {
        '_id': $("#txt_id").val(),
        'txt_th_reqdet_nombre': $("#txt_nombre").val(),
        'txt_th_reqdet_descripcion': $("#txt_descripcion").val(),
        'chk_th_reqdet_obligatorio': $('#chk_obligatorio').is(':checked') ? 1 : 0
    };

    $.ajax({
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_requisitos_detallesC.php?insertar_editar=true',
        type: 'post',
        data: {
            parametros: parametros
        },
        dataType: 'json',
        success: function(r) {
            if (r == 1) {
                Swal.fire("", "Operación realizada con éxito", "success")
                    .then(() => location.href =
                        "../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_requisitos_detalles");
            } else if (r == -2) {
                Swal.fire("Atención", "Ya existe un detalle con ese nombre.", "warning");
            } else {
                Swal.fire("Error", (r.msg || 'No se guardó el detalle'), "error");
            }
        },
        error: function(xhr) {
            console.error('Error guardar:', xhr.responseText);
            Swal.fire('Error', 'Ocurrió un error al guardar (revisar consola).', 'error');
        }
    });
}

/* Eliminar */
function eliminar_detalle() {
    var id = $("#txt_id").val();
    if (!id) return Swal.fire("", "No hay ID para eliminar", "info");

    Swal.fire({
        title: '¿Eliminar detalle?',
        text: 'Se desactivará este detalle (soft-delete).',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_requisitos_detallesC.php?eliminar=true',
                type: 'post',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(resp) {
                    if (resp == 1) {
                        Swal.fire('Eliminado', 'Registro eliminado.', 'success')
                            .then(() => location.href =
                                "../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_requisitos_detalles"
                            );
                    } else {
                        Swal.fire('Error', 'No se pudo eliminar.', 'error');
                    }
                },
                error: function(xhr) {
                    console.error('Error eliminar:', xhr.responseText);
                    Swal.fire('Error', 'Ocurrió un error al eliminar (revisar consola).', 'error');
                }
            });
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
                        <div><i class="bx bx-list-ul me-2 font-22 text-primary"></i></div>
                        <h5 class="mb-0 text-primary">
                            <?= ($_id == '') ? 'Registrar Detalle de Requisito' : 'Modificar Detalle de Requisito' ?>
                        </h5>
                    </div>

                    <div>
                        <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_requisitos_detalles"
                            class="btn btn-outline-dark btn-sm">
                            <i class="bx bx-arrow-back"></i> Regresar
                        </a>
                    </div>
                </div>

                <hr>

                <form id="form_detalle" novalidate>
                    <input type="hidden" id="txt_id" value="<?= $_id ?>">

                    <div class="row">

                        <!-- Nombre -->
                        <div class="col-md-4 mb-3">
                            <label class="fw-bold">Nombre</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bx bx-id-card"></i></span>
                                <input type="text" id="txt_nombre" name="txt_nombre" class="form-control"
                                    placeholder="Ej: Instrucción básica, Esfuerzo físico..." autocomplete="off"
                                    required>
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Descripción</label>
                            <textarea id="txt_descripcion" name="txt_descripcion" class="form-control" rows="1"
                                placeholder="Descripción del detalle..."></textarea>
                        </div>

                        <!-- Obligatorio -->
                        <div class="col-md-2 d-flex align-items-center mb-3">
                            <div>
                                <label class="fw-bold">¿Obligatorio?</label><br>
                                <input class="form-check-input" type="checkbox" id="chk_obligatorio">
                            </div>
                        </div>

                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <?php if($_id == ''){ ?>
                        <button type="button" class="btn btn-success" onclick="guardar_actualizar_detalle()">
                            <i class="bx bx-save"></i> Guardar
                        </button>
                        <?php } else { ?>
                        <button type="button" class="btn btn-primary" onclick="guardar_actualizar_detalle()">
                            <i class="bx bx-edit"></i> Actualizar
                        </button>
                        <button type="button" class="btn btn-danger" onclick="eliminar_detalle()">
                            <i class="bx bx-trash"></i> Eliminar
                        </button>
                        <?php } ?>
                    </div>
                </form>


            </div>
        </div>

    </div>
</div>