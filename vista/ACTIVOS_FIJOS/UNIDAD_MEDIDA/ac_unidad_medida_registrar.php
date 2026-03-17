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
        <?php } ?>
    });

    function datos_col(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/ACTIVOS_FIJOS/CATALOGOS/ac_cat_unidad_medidaC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                $('#txt_nombre').val(response[0].nombre);
                $('#txt_simbolo').val(response[0].simbolo);
                $('#txt_tipo').val(response[0].tipo);
                $('#txt_descripcion').val(response[0].descripcion);
            }
        });
    }

    function editar_insertar() {
        var parametros = {
            '_id': '<?= $_id ?>',
            'txt_nombre': $('#txt_nombre').val(),
            'txt_simbolo': $('#txt_simbolo').val(),
            'txt_tipo': $('#txt_tipo').val(),
            'txt_descripcion': $('#txt_descripcion').val(),
        };

        if ($("#form_unidad_medida").valid()) {
            insertar(parametros);
        }
    }

    function insertar(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/ACTIVOS_FIJOS/CATALOGOS/ac_cat_unidad_medidaC.php?insertar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operación realizada con éxito.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=ac_unidad_medida';
                    });
                } else if (response == -2) {
                    $('#txt_nombre').addClass('is-invalid');
                    $('#error_txt_nombre').text('El nombre ya está en uso.');
                }
            },
            error: function(xhr, status, error) {
                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });

        $('#txt_nombre').on('input', function() {
            $('#error_txt_nombre').text('');
            $(this).removeClass('is-invalid');
        });
    }

    function delete_datos() {
        Swal.fire({
            title: '¿Eliminar Registro?',
            text: '¿Está seguro de eliminar este registro?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                eliminar('<?= $_id ?>');
            }
        });
    }

    function eliminar(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/ACTIVOS_FIJOS/CATALOGOS/ac_cat_unidad_medidaC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado', 'Registro eliminado correctamente.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=ac_unidad_medida';
                    });
                } else {
                    Swal.fire('Error', 'No se pudo eliminar el registro.', 'error');
                }
            },
            error: function(xhr, status, error) {
                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
    }
</script>

<div class="page-wrapper">
    <div class="page-content">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Unidad de Medida</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?= $_id == '' ? 'Agregar Unidad de Medida' : 'Modificar Unidad de Medida' ?>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">

                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bxs-ruler me-1 font-22 text-primary"></i></div>
                            <h5 class="mb-0 text-primary">
                                <?= $_id == '' ? 'Registrar Unidad de Medida' : 'Modificar Unidad de Medida' ?>
                            </h5>
                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=ac_unidad_medida"
                                        class="btn btn-outline-dark btn-sm">
                                        <i class="bx bx-arrow-back"></i> Regresar
                                    </a>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <form id="form_unidad_medida">

                            <div class="row pt-3 mb-col">
                                <div class="col-md-6">
                                    <label for="txt_nombre" class="form-label">Nombre </label>
                                    <input type="text"
                                        class="form-control form-control-sm no_caracteres"
                                        id="txt_nombre"
                                        name="txt_nombre"
                                        maxlength="100">
                                    <span id="error_txt_nombre" class="text-danger small"></span>
                                </div>
                                <div class="col-md-3">
                                    <label for="txt_simbolo" class="form-label">Símbolo </label>
                                    <input type="text"
                                        class="form-control form-control-sm no_caracteres"
                                        id="txt_simbolo"
                                        name="txt_simbolo"
                                        maxlength="20">
                                    <span id="error_txt_simbolo" class="text-danger small"></span>
                                </div>
                                <div class="col-md-3">
                                    <label for="txt_tipo" class="form-label">Tipo </label>
                                    <input type="text"
                                        class="form-control form-control-sm no_caracteres"
                                        id="txt_tipo"
                                        name="txt_tipo"
                                        maxlength="50">
                                </div>
                            </div>

                            <div class="row mb-col">
                                <div class="col-md-12">
                                    <label for="" class="form-label">Descripción</label>
                                    <input type="text"
                                        class="form-control form-control-sm no_caracteres"
                                        id="txt_descripcion"
                                        name="txt_descripcion"
                                        maxlength="200">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end pt-2">
                                <?php if ($_id == '') { ?>
                                    <button class="btn btn-success btn-sm px-4 m-0"
                                        onclick="editar_insertar()" type="button">
                                        <i class="bx bx-save"></i> Guardar
                                    </button>
                                <?php } else { ?>
                                    <button class="btn btn-success btn-sm px-4 m-1"
                                        onclick="editar_insertar()" type="button">
                                        <i class="bx bx-save"></i> Editar
                                    </button>
                                    <button class="btn btn-danger btn-sm px-4 m-1"
                                        onclick="delete_datos()" type="button">
                                        <i class="bx bx-trash"></i> Eliminar
                                    </button>
                                <?php } ?>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    $(document).ready(function() {

        agregar_asterisco_campo_obligatorio('txt_nombre');
        agregar_asterisco_campo_obligatorio('txt_simbolo');
        agregar_asterisco_campo_obligatorio('txt_tipo');

        $("#form_unidad_medida").validate({
            rules: {
                txt_nombre: {
                    required: true
                },
                txt_simbolo: {
                    required: true
                },
                txt_tipo: {
                    required: true
                },
            },
            messages: {
                txt_nombre: {
                    required: 'Por favor ingrese el nombre'
                },
                txt_simbolo: {
                    required: 'Por favor ingrese el símbolo'
                },
                txt_tipo: {
                    required: 'Por favor ingrese el tipo'
                },
            },
            highlight: function(element) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid').addClass('is-valid');
            }
        });
    });
</script>