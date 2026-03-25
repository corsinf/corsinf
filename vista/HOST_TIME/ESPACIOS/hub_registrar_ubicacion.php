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
            url: '../controlador/HOST_TIME/UBICACIONES/hub_ubicacionesC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                $('#txt_nombre').val(response[0].nombre);
                $('#txt_direccion').val(response[0].direccion);
                $('#txt_telefono').val(response[0].telefono);
                $('#ddl_provincias').append($('<option>', {
                    value: response[0].th_prov_id,
                    text: response[0].provincia,
                    selected: true
                }));
                $('#ddl_ciudad').append($('<option>', {
                    value: response[0].th_ciu_id,
                    text: response[0].ciudad,
                    selected: true
                }));
                $('#ddl_parroquia').append($('<option>', {
                    value: response[0].th_parr_id,
                    text: response[0].parroquia,
                    selected: true
                }));
            }
        });
    }

    function editar_insertar() {
        var parametros = {
            '_id': '<?= $_id ?>',
            'txt_nombre': $('#txt_nombre').val(),
            'txt_direccion': $('#txt_direccion').val(),
            'txt_telefono': $('#txt_telefono').val(),
            'ddl_provincias': $('#ddl_provincias').val(),
            'ddl_ciudad': $('#ddl_ciudad').val(),
            'ddl_parroquia': $('#ddl_parroquia').val(),
        };

        if ($("#form_ubicaciones").valid()) {
            insertar(parametros);
        }
    }

    function insertar(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/HOST_TIME/UBICACIONES/hub_ubicacionesC.php?insertar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_ubicaciones';
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
        });
    }

    function eliminar(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/HOST_TIME/UBICACIONES/hub_ubicacionesC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_ubicaciones';
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
            <div class="breadcrumb-title pe-3">Ubicaciones</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?php echo ($_id == '') ? 'Agregar Ubicación' : 'Modificar Ubicación'; ?>
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
                                <?php echo ($_id == '') ? 'Registrar Ubicación' : 'Modificar Ubicación'; ?>
                            </h5>
                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_ubicaciones" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <form id="form_ubicaciones">

                            <div class="row pt-3 mb-col">
                                <div class="col-md-6">
                                    <label for="txt_nombre" class="form-label">Nombre </label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_nombre" name="txt_nombre" maxlength="150" autocomplete="off">
                                    <span id="error_txt_nombre" class="text-danger"></span>
                                </div>

                                <div class="col-md-6">
                                    <label for="txt_direccion" class="form-label">Dirección </label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_direccion" name="txt_direccion" maxlength="200" autocomplete="off">
                                    <span id="error_txt_direccion" class="text-danger"></span>
                                </div>
                            </div>

                            <div class="row pt-3 mb-col">
                                <div class="col-md-6">
                                    <label for="txt_telefono" class="form-label">Teléfono </label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_telefono" name="txt_telefono" maxlength="20" autocomplete="off">
                                    <span id="error_txt_telefono" class="text-danger"></span>
                                </div>

                                <?php include_once('../vista/HOST_TIME/ESPACIOS/provincias_ciudades_parroquias.php'); ?>
                            </div>

                            <div class="d-flex justify-content-end pt-2">
                                <?php if ($_id == '') { ?>
                                    <button class="btn btn-success btn-sm px-4 m-0" onclick="editar_insertar()" type="button"><i class="bx bx-save"></i> Guardar</button>
                                <?php } else { ?>
                                    <button class="btn btn-success btn-sm px-4 m-1" onclick="editar_insertar()" type="button"><i class="bx bx-save"></i> Editar</button>
                                    <button class="btn btn-danger btn-sm px-4 m-1" onclick="delete_datos()" type="button"><i class="bx bx-trash"></i> Eliminar</button>
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
        agregar_asterisco_campo_obligatorio('txt_direccion');
        agregar_asterisco_campo_obligatorio('txt_telefono');
        agregar_asterisco_campo_obligatorio('ddl_provincias');
        agregar_asterisco_campo_obligatorio('ddl_ciudad');
        agregar_asterisco_campo_obligatorio('ddl_parroquia');

        $("#form_ubicaciones").validate({
            rules: {
                txt_nombre: {
                    required: true
                },
                txt_direccion: {
                    required: true
                },
                txt_telefono: {
                    required: true
                },
                ddl_provincias: {
                    required: true
                },
                ddl_ciudad: {
                    required: true
                },
                ddl_parroquia: {
                    required: true
                },
            },
            errorPlacement: function(error, element) {
                if (element.closest('.input-group').length) {
                    error.insertAfter(element.closest('.input-group'));
                } else {
                    error.insertAfter(element);
                }
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