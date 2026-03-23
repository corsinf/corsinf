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
            cargar_espacio(<?= $_id ?>);
        <?php } ?>
        cargar_selects2();
    });

    function cargar_espacio(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/HOST_TIME/ESPACIOS/espaciosC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                $('#txt_nombre').val(response[0].nombre);
                $('#txt_codigo').val(response[0].codigo);
                $('#txt_capacidad').val(response[0].capacidad);
                $('#txt_tarifa_hora').val(response[0].tarifa_hora);
                $('#txt_tarifa_dia').val(response[0].tarifa_dia);
                $('#ddl_tipo_espacio').append($('<option>', {
                    value: response[0].id_tipo_espacio,
                    text: response[0].nombre_tipo_espacio,
                    selected: true
                }));
                $('#ddl_ubicacion').append($('<option>', {
                    value: response[0].id_ubicacion,
                    text: response[0].nombre_ubicacion,
                    selected: true
                }));
                $('#ddl_numero_piso').append($('<option>', {
                    value: response[0].id_numero_piso,
                    text: response[0].descripcion_numero_piso,
                    selected: true
                }));
            }
        });
    }

    function cargar_selects2() {
        cargar_select2_url('ddl_tipo_espacio', '../controlador/HOST_TIME/ESPACIOS/tipo_espacioC.php?buscar=true');
        cargar_select2_url('ddl_ubicacion', '../controlador/HOST_TIME/ESPACIOS/ubicacionesC.php?buscar=true');
        cargar_select2_url('ddl_numero_piso', '../controlador/HOST_TIME/CATALOGOS/hub_cat_numero_pisoC.php?buscar=true');
    }

    function editar_insertar() {
        var parametros = {
            '_id': '<?= $_id ?>',
            'txt_nombre': $('#txt_nombre').val(),
            'txt_codigo': $('#txt_codigo').val(),
            'txt_capacidad': $('#txt_capacidad').val(),
            'txt_tarifa_hora': $('#txt_tarifa_hora').val(),
            'txt_tarifa_dia': $('#txt_tarifa_dia').val(),
            'ddl_tipo_espacio': $('#ddl_tipo_espacio').val(),
            'ddl_ubicacion': $('#ddl_ubicacion').val(),
            'ddl_numero_piso': $('#ddl_numero_piso').val(),
        };

        if ($("#form_espacios").valid()) {
            insertar(parametros);
        }
    }

    function insertar(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/HOST_TIME/ESPACIOS/espaciosC.php?insertar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_espacios';
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
            url: '../controlador/HOST_TIME/ESPACIOS/espaciosC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_espacios';
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
            <div class="breadcrumb-title pe-3">Espacios</div>
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
                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i></div>
                            <h5 class="mb-0 text-primary">
                                <?php
                                if ($_id == '') {
                                    echo 'Registrar Espacio';
                                } else {
                                    echo 'Modificar Espacio';
                                }
                                ?>
                            </h5>
                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_espacios" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <form id="form_espacios">

                            <div class="row pt-3 mb-col">
                                <div class="col-md-6">
                                    <label for="txt_nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_nombre" name="txt_nombre" autocomplete="off">
                                    <span id="error_txt_nombre" class="text-danger"></span>
                                </div>

                                <div class="col-md-6">
                                    <label for="txt_codigo" class="form-label">Código</label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_codigo" name="txt_codigo" autocomplete="off">
                                </div>
                            </div>

                            <div class="row mb-col">
                                <div class="col-md-6">
                                    <label for="ddl_tipo_espacio" class="form-label">Tipo de espacio</label>
                                    <select class="form-select form-select-sm select2-validation" id="ddl_tipo_espacio" name="ddl_tipo_espacio" required>
                                        <option value="" selected hidden>-- Seleccione --</option>
                                    </select>
                                    <label class="error" style="display: none;" for="ddl_tipo_espacio"></label>
                                </div>

                                <div class="col-md-6">
                                    <label for="ddl_ubicacion" class="form-label">Ubicación</label>
                                    <select class="form-select form-select-sm select2-validation" id="ddl_ubicacion" name="ddl_ubicacion" required>
                                        <option value="" selected hidden>-- Seleccione --</option>
                                    </select>
                                    <label class="error" style="display: none;" for="ddl_ubicacion"></label>
                                </div>
                            </div>

                            <div class="row mb-col">
                                <div class="col-md-6">
                                    <label for="ddl_numero_piso" class="form-label">Número de piso</label>
                                    <select class="form-select form-select-sm select2-validation" id="ddl_numero_piso" name="ddl_numero_piso" required>
                                        <option value="" selected hidden>-- Seleccione --</option>
                                    </select>
                                    <label class="error" style="display: none;" for="ddl_numero_piso"></label>
                                </div>

                                <div class="col-md-6">
                                    <label for="txt_capacidad" class="form-label">Capacidad</label>
                                    <input type="number" class="form-control form-control-sm" id="txt_capacidad" name="txt_capacidad" min="0" step="1" inputmode="numeric">
                                    <div class="form-text">Número entero (personas/puestos).</div>
                                </div>
                            </div>

                            <div class="row mb-col">
                                <div class="col-md-6">
                                    <label for="txt_tarifa_hora" class="form-label">Tarifa (Hora)</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control form-control-sm" id="txt_tarifa_hora" name="txt_tarifa_hora" placeholder="0.00" min="0" step="0.01" inputmode="decimal">
                                    </div>
                                    <div class="form-text">Precio por hora (máx 2 decimales).</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="txt_tarifa_dia" class="form-label">Tarifa (Día)</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control form-control-sm" id="txt_tarifa_dia" name="txt_tarifa_dia" placeholder="0.00" min="0" step="0.01" inputmode="decimal">
                                    </div>
                                    <div class="form-text">Precio por día (máx 2 decimales).</div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end pt-2">
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
        agregar_asterisco_campo_obligatorio('txt_codigo');
        agregar_asterisco_campo_obligatorio('ddl_tipo_espacio');
        agregar_asterisco_campo_obligatorio('ddl_ubicacion');
        agregar_asterisco_campo_obligatorio('ddl_numero_piso');

        $("#form_espacios").validate({
            rules: {
                txt_nombre: {
                    required: true
                },
                txt_codigo: {
                    required: true
                },
                ddl_tipo_espacio: {
                    required: true
                },
                ddl_ubicacion: {
                    required: true
                },
                ddl_numero_piso: {
                    required: true
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