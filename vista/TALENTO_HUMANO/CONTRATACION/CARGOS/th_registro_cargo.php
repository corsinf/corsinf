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
            cargar_cargo(<?= $_id ?>);
        <?php } ?>

        // Añadir asteriscos a campos obligatorios
        agregar_asterisco_campo_obligatorio('txt_th_car_nombre');

        // Inicializar validación
        $("#form_cargo").validate({
            ignore: [],
            rules: {
                txt_th_car_nombre: { required: true }
            },
            messages: {
                txt_th_car_nombre: { required: "Ingrese el nombre del cargo." }
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

        // Formato para inputs datetime-local
        function formatDateToInput(dateStr) {
            if (!dateStr) return '';
            dateStr = dateStr.replace('.000','').trim();
            if (dateStr.indexOf(' ') !== -1) {
                return dateStr.slice(0,16).replace(' ', 'T');
            }
            if (dateStr.indexOf('T') !== -1) {
                return dateStr.slice(0,16);
            }
            return dateStr;
        }

        function boolVal(val) {
            return (val === 1 || val === '1' || val === true || val === 'true') ? true : false;
        }

        // CARGAR DATOS DE UN CARGO EN EL FORMULARIO
        function cargar_cargo(id) {
            $.ajax({
                data: { id: id },
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargosC.php?listar=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    if (!response || !response[0]) return;
                    var r = response[0];

                    $('#txt_th_car_id').val(r._id);
                    $('#txt_th_car_nombre').val(r.nombre);
                    $('#txt_th_car_descripcion').val(r.descripcion);
                    $('#txt_th_car_nivel').val(r.nivel);
                    $('#txt_th_car_area').val(r.area);
                    $('#chk_th_car_estado').prop('checked', boolVal(r.estado));
                },
                error: function(err) {
                    console.error(err);
                    alert('Error al cargar el cargo (revisar consola).');
                }
            });
        }

        
    });
</script>

<script type="text/javascript">
    // Asegúrate de ejecutar esto dentro de $(document).ready(...) si lo pegas suelto
    function editar_insertar_cargo() {
        var txt_th_car_id = $('#txt_th_car_id').val(); // hidden id
        var txt_th_car_nombre = $('#txt_th_car_nombre').val();
        var txt_th_car_nivel = $('#txt_th_car_nivel').val();
        var txt_th_car_area = $('#txt_th_car_area').val();
        var txt_th_car_descripcion = $('#txt_th_car_descripcion').val();

        var parametros = {
            '_id': txt_th_car_id,
            'txt_th_car_nombre': txt_th_car_nombre,
            'txt_th_car_nivel': txt_th_car_nivel,
            'txt_th_car_area': txt_th_car_area,
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
            data: { parametros: parametros },
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargosC.php?insertar_editar=true',
            type: 'post',
            dataType: 'json',
            success: function (response) {
                if (response == 1) {
                    Swal.fire('', 'Operación realizada con éxito.', 'success').then(function () {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_cargos';
                    });
                } else if (response == -2) {
                    // nombre duplicado
                    $('#txt_th_car_nombre').addClass('is-invalid');
                    // Si tienes un elemento para mostrar error:
                    if ($('#error_txt_th_car_nombre').length == 0) {
                        $('#txt_th_car_nombre').after('<div id="error_txt_th_car_nombre" class="invalid-feedback">El nombre del cargo ya está en uso.</div>');
                    } else {
                        $('#error_txt_th_car_nombre').text('El nombre del cargo ya está en uso.');
                    }
                } else {
                    Swal.fire('', response.msg || 'Error al guardar el cargo.', 'error');
                }
            },
            error: function (xhr, status, error) {
                console.error('Status: ' + status);
                console.error('Error: ' + error);
                console.error('XHR Response: ' + xhr.responseText);
                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });

        // limpiar error cuando el usuario teclea
        $('#txt_th_car_nombre').on('input', function () {
            $(this).removeClass('is-invalid');
            $('#error_txt_th_car_nombre').text('');
        });
    }

    function editar_cargo(parametros) {
        $.ajax({
            data: { parametros: parametros },
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargosC.php?insertar_editar=true',
            type: 'post',
            dataType: 'json',
            success: function (response) {
                if (response == 1) {
                    Swal.fire('', 'Cargo actualizado con éxito.', 'success').then(function () {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_cargos';
                    });
                } else if (response == -2) {
                    // nombre duplicado en otro registro
                    $('#txt_th_car_nombre').addClass('is-invalid');
                    if ($('#error_txt_th_car_nombre').length == 0) {
                        $('#txt_th_car_nombre').after('<div id="error_txt_th_car_nombre" class="invalid-feedback">El nombre del cargo ya está en uso por otro registro.</div>');
                    } else {
                        $('#error_txt_th_car_nombre').text('El nombre del cargo ya está en uso por otro registro.');
                    }
                } else {
                    Swal.fire('', response.msg || 'Error al actualizar el cargo.', 'error');
                }
            },
            error: function (xhr, status, error) {
                console.error('Status: ' + status);
                console.error('Error: ' + error);
                console.error('XHR Response: ' + xhr.responseText);
                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });

        $('#txt_th_car_nombre').on('input', function () {
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
            data: { id: id },
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargosC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function (response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro eliminado.', 'success').then(function () {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_cargos';
                    });
                } else {
                    Swal.fire('', response.msg || 'No se pudo eliminar.', 'error');
                }
            },
            error: function (xhr, status, error) {
                console.error('Status: ' + status);
                console.error('Error: ' + error);
                console.error('XHR Response: ' + xhr.responseText);
                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
    }

    // Bind botones (si tu HTML ya tiene los botones con esos ids)
    $(document).ready(function () {
        $('#btn_guardar_cargo').on('click', function () {
            editar_insertar_cargo();
        });
        $('#btn_editar_cargo').on('click', function () {
            editar_insertar_cargo();
        });
        $('#btn_eliminar_cargo').on('click', function () {
            delete_cargo();
        });
    });
</script>


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
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_cargos" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <form id="form_cargo">
    <!-- Hidden ID -->
    <input type="hidden" id="txt_th_car_id" name="txt_th_car_id" value="<?= $_id ?>" />

    <!-- SECCIÓN 1: INFORMACIÓN BÁSICA -->
    <div class="card mb-3">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0"><i class="bx bx-info-circle me-2"></i>Información Básica</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <!-- Nombre del cargo -->
                <div class="col-md-6">
                    <label for="txt_th_car_nombre" class="form-label fw-bold">
                        <i class="bx bx-id-card me-2 text-primary"></i> Nombre del Cargo
                    </label>
                    <input type="text" class="form-control" id="txt_th_car_nombre" name="txt_th_car_nombre" placeholder="Ingrese el nombre del cargo" autocomplete="off" required />
                </div>

                <!-- Nivel -->
                <div class="col-md-3">
                    <label for="txt_th_car_nivel" class="form-label fw-bold">
                        <i class="bx bx-layer me-2 text-info"></i> Nivel
                    </label>
                    <input type="text" class="form-control" id="txt_th_car_nivel" name="txt_th_car_nivel" placeholder="Ej: Junior, Senior" />
                </div>

                <!-- Área -->
                <div class="col-md-3">
                    <label for="txt_th_car_area" class="form-label fw-bold">
                        <i class="bx bx-buildings me-2 text-success"></i> Área
                    </label>
                    <input type="text" class="form-control" id="txt_th_car_area" name="txt_th_car_area" placeholder="Ej: Administrativa" />
                </div>

                <!-- Descripción del cargo -->
                <div class="col-12">
                    <label for="txt_th_car_descripcion" class="form-label fw-bold">
                        <i class="bx bx-file me-2 text-warning"></i> Descripción del Cargo
                    </label>
                    <textarea class="form-control" id="txt_th_car_descripcion" name="txt_th_car_descripcion" rows="5" placeholder="Describa las funciones y responsabilidades del cargo..."></textarea>
                    <div class="form-text">Detalle las funciones principales del cargo</div>
                </div>
                <!-- Estado -->
               
            </div>
        </div>
    </div>

    <!-- BOTONES DE ACCIÓN -->
    <div class="d-flex justify-content-end gap-2">
        <?php if ($_id == '') { ?>
            <button type="button" class="btn btn-success" id="btn_guardar_cargo">
                <i class="bx bx-save me-1"></i> Guardar Cargo
            </button>
        <?php } else { ?>
            <button type="button" class="btn btn-primary" id="btn_editar_cargo">
                <i class="bx bx-edit me-1"></i> Actualizar Cargo
            </button>
            <button type="button" class="btn btn-danger" id="btn_eliminar_cargo">
                <i class="bx bx-trash me-1"></i> Eliminar Cargo
            </button>
        <?php } ?>
    </div>
</form>

                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>