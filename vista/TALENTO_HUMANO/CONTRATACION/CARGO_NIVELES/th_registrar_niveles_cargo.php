<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
$_id = (isset($_GET['_id'])) ? $_GET['_id'] : '';
?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {

    // Si existe ID cargamos nivel para editar
    <?php if ($_id != '') { ?>
    cargar_nivel(<?= $_id ?>);
    <?php } ?>

    // Validación del formulario
    $("#form_nivel").validate({
        ignore: [],
        rules: {
            th_niv_nombre: {
                required: true
            }
        },
        messages: {
            th_niv_nombre: {
                required: "Ingrese el nombre del nivel"
            }
        },
        highlight: r => $(r).addClass('is-invalid').removeClass('is-valid'),
        unhighlight: r => $(r).addClass('is-valid').removeClass('is-invalid'),
        submitHandler: () => false
    });

});

// Cargar nivel por id
function cargar_nivel(id) {
    $.ajax({
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_niveles_cargoC.php?listar=true',
        type: 'post',
        data: {
            id: id
        },
        dataType: 'json',
        success: function(response) {
            // tu controlador devuelve array o registro; adaptamos
            var r = (Array.isArray(response) && response.length > 0) ? response[0] : response;
            if (!r) return;
            $("#txt_th_niv_id").val(r.th_niv_id || r._id || '');
            $("#th_niv_nombre").val(r.th_niv_nombre || r.nombre || '');
            $("#th_niv_descripcion").val(r.th_niv_descripcion || r.descripcion || '');
        },
        error: function(xhr) {
            console.error('Error cargar_nivel:', xhr.responseText);
            Swal.fire('Error', 'No se pudo cargar el nivel. Revisa la consola.', 'error');
        }
    });
}

// Guardar o actualizar
function guardar_actualizar_nivel() {
    if (!$("#form_nivel").valid()) return;

    var parametros = {
        '_id': $("#txt_th_niv_id").val() || '',
        'txt_th_niv_nombre': $("#th_niv_nombre").val().trim(),
        'txt_th_niv_descripcion': $("#th_niv_descripcion").val().trim()
    };

    $.ajax({
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_niveles_cargoC.php?insertar_editar=true',
        type: 'post',
        data: {
            parametros: parametros
        },
        dataType: 'json',
        success: function(res) {
            if (res == 1) {
                Swal.fire('', 'Nivel guardado correctamente.', 'success').then(function() {
                    window.location.href =
                        "../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_niveles_cargo";
                });
            } else if (res == -2) {
                Swal.fire('Atención', 'Ya existe un nivel con ese nombre.', 'warning');
            } else {
                Swal.fire('Error', (res.msg || 'No se pudo guardar el nivel.'), 'error');
            }
        },
        error: function(xhr) {
            console.error('Error guardar_actualizar_nivel:', xhr.responseText);
            Swal.fire('Error', 'Ocurrió un error al guardar (ver consola).', 'error');
        }
    });
}

// Eliminar (soft-delete)
function eliminar_nivel() {
    var id = $("#txt_th_niv_id").val() || '';
    if (!id) {
        Swal.fire('', 'ID no encontrado para eliminar', 'warning');
        return;
    }

    Swal.fire({
        title: 'Eliminar Nivel?',
        text: "¿Está seguro de desactivar este nivel?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_niveles_cargoC.php?eliminar=true',
                type: 'post',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(res) {
                    if (res == 1) {
                        Swal.fire('Eliminado', 'Nivel eliminado correctamente.', 'success').then(
                            function() {
                                window.location.href =
                                    "../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_niveles_cargo";
                            });
                    } else {
                        Swal.fire('Error', res.msg || 'No se pudo eliminar.', 'error');
                    }
                },
                error: function(xhr) {
                    console.error('Error eliminar_nivel:', xhr.responseText);
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
                        <div><i class="bx bx-layer me-2 font-22 text-primary"></i></div>
                        <h5 class="mb-0 text-primary">
                            <?= ($_id == '') ? 'Registrar Nivel' : 'Modificar Nivel' ?>
                        </h5>
                    </div>

                    <div>
                        <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_niveles"
                            class="btn btn-outline-dark btn-sm">
                            <i class="bx bx-arrow-back"></i> Regresar
                        </a>
                    </div>
                </div>

                <hr>

                <form id="form_nivel" novalidate>
                    <input type="hidden" id="txt_th_niv_id" value="<?= $_id ?>">

                    <div class="row">

                        <!-- Nombre -->
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Nombre</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bx bx-font"></i></span>
                                <input type="text" id="th_niv_nombre" name="th_niv_nombre" class="form-control"
                                    placeholder="Ej: Junior, Senior, Coordinador..." autocomplete="off" required>
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div class="col-md-12 mb-3">
                            <label class="fw-bold">Descripción</label>
                            <textarea id="th_niv_descripcion" name="th_niv_descripcion" class="form-control" rows="4"
                                placeholder="Breve descripción del nivel (opcional)"></textarea>
                        </div>


                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <?php if ($_id == '') { ?>
                        <button type="button" class="btn btn-success" onclick="guardar_actualizar_nivel()">
                            <i class="bx bx-save"></i> Guardar
                        </button>
                        <?php } else { ?>
                        <button type="button" class="btn btn-primary" onclick="guardar_actualizar_nivel()">
                            <i class="bx bx-edit"></i> Actualizar
                        </button>
                        <button type="button" class="btn btn-danger" onclick="eliminar_nivel()">
                            <i class="bx bx-trash"></i> Eliminar
                        </button>
                        <?php } ?>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>