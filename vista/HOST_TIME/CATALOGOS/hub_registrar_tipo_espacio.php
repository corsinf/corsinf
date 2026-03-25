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
            url: '../controlador/HOST_TIME/CATALOGOS/hub_catn_tipo_espacioC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                $('#txt_nombre').val(response[0].nombre);
                $('#txt_descripcion').val(response[0].descripcion);
            }
        });
    }

    function editar_insertar() {
        var txt_nombre = $('#txt_nombre').val();
        var txt_descripcion = $('#txt_descripcion').val();

        var parametros = {
            '_id': '<?= $_id ?>',
            'txt_nombre': txt_nombre,
            'txt_descripcion': txt_descripcion,
        };

        if ($("#form_tipo_espacio").valid()) {
            insertar(parametros);
        }
    }

    function insertar(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/HOST_TIME/CATALOGOS/hub_catn_tipo_espacioC.php?insertar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_tipos_espacios';
                    });
                } else if (response == -2) {
                    $('#txt_nombre').addClass('is-invalid');
                    $('#error_txt_nombre').text('El nombre ya está en uso.');
                }
            },
            error: function(xhr, status, error) {
                console.log('Status: ' + status);
                console.log('Error: ' + error);
                console.log('XHR Response: ' + xhr.responseText);
                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });

        $('#txt_nombre').on('input', function() {
            $('#error_txt_nombre').text('');
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
            url: '../controlador/HOST_TIME/CATALOGOS/hub_catn_tipo_espacioC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_tipos_espacios';
                    });
                }
            }
        });
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Tipo de Espacios</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Registros
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
                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i></div>
                            <h5 class="mb-0 text-primary">
                                <?php
                                if ($_id == '') {
                                    echo 'Registrar Tipo de Espacio';
                                } else {
                                    echo 'Modificar Tipo de Espacio';
                                }
                                ?>
                            </h5>
                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_tipos_espacios" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <form id="form_tipo_espacio">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="txt_nombre" class="form-label fw-bold">Nombre </label>
                                    <input type="text"
                                        class="form-control form-control-sm no_caracteres"
                                        id="txt_nombre"
                                        name="txt_nombre"
                                        placeholder="Ingrese el nombre">
                                    <span id="error_txt_nombre" class="text-danger"></span>
                                </div>

                                <div class="col-12">
                                    <label for="txt_descripcion" class="form-label fw-bold">Descripción </label>
                                    <textarea class="form-control form-control-sm no_caracteres"
                                        id="txt_descripcion"
                                        name="txt_descripcion"
                                        rows="3"
                                        placeholder="Ingrese la descripción"></textarea>
                                </div>
                            </div>

                            <div class="mt-4 text-end">
                                <?php if ($_id == '') { ?>
                                    <button type="button" class="btn btn-success btn-sm px-4 m-0" onclick="editar_insertar()"><i class="bx bx-save"></i> Guardar</button>
                                <?php } else { ?>
                                    <button type="button" class="btn btn-success btn-sm px-4 m-1" onclick="editar_insertar()"><i class="bx bx-save"></i> Editar</button>
                                    <button type="button" class="btn btn-danger btn-sm px-4 m-1" onclick="delete_datos()"><i class="bx bx-trash"></i> Eliminar</button>
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

<script>
    $(document).ready(function() {
        agregar_asterisco_campo_obligatorio('txt_nombre');
        agregar_asterisco_campo_obligatorio('txt_descripcion');

        $("#form_tipo_espacio").validate({
            rules: {
                txt_nombre: {
                    required: true,
                },
                txt_descripcion: {
                    required: true,
                },
            },
            highlight: function(element) {
                $(element).addClass('is-invalid');
                $(element).removeClass('is-valid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
                $(element).addClass('is-valid');
            }
        });
    });
</script>