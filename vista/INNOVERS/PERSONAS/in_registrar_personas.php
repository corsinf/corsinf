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
            url: '../controlador/INNOVERS/in_personasC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                $('#txt_primer_apellido').val(response[0]['primer_apellido']);
                $('#txt_segundo_apellido').val(response[0]['segundo_apellido']);
                $('#txt_primer_nombre').val(response[0]['primer_nombre']);
                $('#txt_segundo_nombre').val(response[0]['segundo_nombre']);
                $('#txt_cedula').val(response[0]['cedula']);
                $('#ddl_sexo').val(response[0]['sexo']);
                $('#txt_fecha_nacimiento').val(response[0]['fecha_nacimiento']);
                $('#txt_correo').val(response[0]['correo']);
                $('#txt_telefono_1').val(response[0]['telefono_1']);
                $('#txt_telefono_2').val(response[0]['telefono_2']);
                $('#ddl_estado_civil').val(response[0]['estado_civil']);
                $('#txt_postal').val(response[0]['postal']);
                $('#txt_direccion').val(response[0]['direccion']);
                $('#txt_observaciones').val(response[0]['observaciones']);

                // //$('#txt_foto_url').val(response[0]['foto_url']);

                calcular_edad('txt_edad', response[0]['fecha_nacimiento']);
            }
        });
    }

    function editar_insertar() {

        var txt_primer_apellido = $('#txt_primer_apellido').val();
        var txt_segundo_apellido = $('#txt_segundo_apellido').val();
        var txt_primer_nombre = $('#txt_primer_nombre').val();
        var txt_segundo_nombre = $('#txt_segundo_nombre').val();
        var txt_cedula = $('#txt_cedula').val();
        var ddl_sexo = $('#ddl_sexo').val();
        var txt_fecha_nacimiento = $('#txt_fecha_nacimiento').val();
        var txt_edad = $('#txt_edad').val();
        var txt_correo = $('#txt_correo').val();
        var txt_telefono_1 = $('#txt_telefono_1').val();
        var txt_telefono_2 = $('#txt_telefono_2').val();
        var ddl_estado_civil = $('#ddl_estado_civil').val();
        var txt_postal = $('#txt_postal').val();
        var txt_direccion = $('#txt_direccion').val();
        var txt_observaciones = $('#txt_observaciones').val();

        var parametros = {
            '_id': '<?= $_id ?>',
            'txt_primer_apellido': txt_primer_apellido,
            'txt_segundo_apellido': txt_segundo_apellido,
            'txt_primer_nombre': txt_primer_nombre,
            'txt_segundo_nombre': txt_segundo_nombre,
            'txt_cedula': txt_cedula,
            'ddl_sexo': ddl_sexo,
            'txt_fecha_nacimiento': txt_fecha_nacimiento,
            'txt_edad': txt_edad,
            'txt_correo': txt_correo,
            'txt_telefono_1': txt_telefono_1,
            'txt_telefono_2': txt_telefono_2,
            'ddl_estado_civil': ddl_estado_civil,
            'txt_postal': txt_postal,
            'txt_direccion': txt_direccion,
            'txt_observaciones': txt_observaciones,
        };

        if ($("#form_persona").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            insertar(parametros);
        }
        //console.log(parametros);

    }

    function insertar(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/INNOVERS/in_personasC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=in_personas';
                    });
                } else if (response == -2) {
                    //Swal.fire('', 'El nombre del dispositivo ya está en uso', 'warning');
                    $(txt_cedula).addClass('is-invalid');
                    $('#error_txt_cedula').text('La cédula ya está en uso.');
                }
            },

            error: function(xhr, status, error) {
                console.log('Status: ' + status);
                console.log('Error: ' + error);
                console.log('XHR Response: ' + xhr.responseText);

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });

        $('#txt_cedula').on('input', function() {
            $('#error_txt_cedula').text('');
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
            url: '../controlador/INNOVERS/in_personasC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=in_personas';
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
            <div class="breadcrumb-title pe-3">Personas</div>
            <?php
            //print_r($_SESSION['INICIO']);die(); 

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Agregar Persona
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

                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                            </div>
                            <h5 class="mb-0 text-primary">
                                <?php
                                if ($_id == '') {
                                    echo 'Registrar Persona';
                                } else {
                                    echo 'Modificar Persona';
                                }
                                ?>
                            </h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=in_personas" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <form id="form_persona">

                            <div>
                                <div class="row pt-3 mb-col">
                                    <div class="col-md-3">
                                        <label for="txt_primer_apellido" class="form-label">Primer Apellido </label>
                                        <input type="text" class="form-control form-control-sm no_caracteres" id="txt_primer_apellido" name="txt_primer_apellido" maxlength="30">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="txt_segundo_apellido" class="form-label">Segundo Apellido </label>
                                        <input type="text" class="form-control form-control-sm no_caracteres" id="txt_segundo_apellido" name="txt_segundo_apellido" maxlength="30">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="txt_primer_nombre" class="form-label">Primer Nombre </label>
                                        <input type="text" class="form-control form-control-sm no_caracteres" id="txt_primer_nombre" name="txt_primer_nombre" maxlength="30">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="txt_segundo_nombre" class="form-label">Segundo Nombre </label>
                                        <input type="text" class="form-control form-control-sm no_caracteres" id="txt_segundo_nombre" name="txt_segundo_nombre" maxlength="30">
                                    </div>
                                </div>

                                <div class="row mb-col">
                                    <div class="col-md-3">
                                        <label for="txt_cedula" class="form-label">Cédula de Identidad </label>
                                        <input type="text" class="form-control form-control-sm solo_numeros_int" id="txt_cedula" name="txt_cedula" maxlength="15">
                                        <span id="error_txt_cedula" class="text-danger"></span>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="ddl_sexo" class="form-label">Sexo </label>
                                        <select class="form-select form-select-sm" id="ddl_sexo" name="ddl_sexo">
                                            <option selected disabled>-- Seleccione --</option>
                                            <option value="Femenino">Femenino</option>
                                            <option value="Masculino">Masculino</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="txt_fecha_nacimiento" class="form-label">Fecha de Nacimiento </label>
                                        <input type="date" class="form-control form-control-sm" id="txt_fecha_nacimiento" name="txt_fecha_nacimiento" onblur="calcular_edad('txt_edad', this.value);">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="txt_edad" class="form-label">Edad </label>
                                        <input type="text" class="form-control form-control-sm no_caracteres" id="txt_edad" name="txt_edad" readonly>
                                    </div>
                                </div>

                                <div class="row mb-col">
                                    <div class="col-md-6">
                                        <label for="txt_correo" class="form-label">Correo </label>
                                        <input type="email" class="form-control form-control-sm" id="txt_correo" name="txt_correo" maxlength="100">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="txt_telefono_1" class="form-label">Teléfono 1 </label>
                                        <input type="text" class="form-control form-control-sm solo_numeros_int" id="txt_telefono_1" name="txt_telefono_1" maxlength="15">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="txt_telefono_2" class="form-label">Teléfono 2 </label>
                                        <input type="text" class="form-control form-control-sm solo_numeros_int" id="txt_telefono_2" name="txt_telefono_2" maxlength="15">
                                    </div>

                                </div>

                                <div class="row mb-col">
                                    <div class="col-md-3">
                                        <label for="ddl_estado_civil" class="form-label">Estado Civíl </label>
                                        <select class="form-select form-select-sm" id="ddl_estado_civil" name="ddl_estado_civil">
                                            <option selected disabled value="">-- Selecciona un Estado Civil --</option>
                                            <option value="Soltero">Soltero/a</option>
                                            <option value="Casado">Casado/a</option>
                                            <option value="Divorciado">Divorciado/a</option>
                                            <option value="Viudo">Viudo/a</option>
                                            <option value="Union">Unión de hecho</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="txt_postal" class="form-label">Cod. Postal </label>
                                        <input type="text" class="form-control form-control-sm no_caracteres" id="txt_postal" name="txt_postal" maxlength="20">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="txt_direccion" class="form-label">Dirección </label>
                                        <input type="text" class="form-control form-control-sm no_caracteres" id="txt_direccion" name="txt_direccion" maxlength="400">
                                    </div>
                                </div>

                                <div class="row mb-col">
                                    <div class="col-md-12">
                                        <label for="txt_observaciones" class="form-label">Observaciones </label>
                                        <textarea class="form-control form-control-sm no_caracteres" name="txt_observaciones" id="txt_observaciones" rows="3" maxlength="200"></textarea>
                                    </div>
                                </div>

                            </div>

                            <div class="d-flex justify-content-end pt-2">

                                <?php if ($_id == '') { ?>
                                    <button class="btn btn-success btn-sm px-4 m-0" onclick="editar_insertar();" type="button"><i class="bx bx-save"></i> Guardar</button>
                                <?php } else { ?>
                                    <button class="btn btn-success btn-sm px-4 m-1" onclick="editar_insertar();" type="button"><i class="bx bx-save"></i> Editar</button>
                                    <button class="btn btn-danger btn-sm px-4 m-1" onclick="delete_datos();" type="button"><i class="bx bx-trash"></i> Eliminar</button>
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
    //Validacion de formulario
    $(document).ready(function() {
        // Selecciona el label existente y añade el nuevo label

        agregar_asterisco_campo_obligatorio('txt_primer_apellido');
        agregar_asterisco_campo_obligatorio('txt_segundo_apellido');
        agregar_asterisco_campo_obligatorio('txt_primer_nombre');
        agregar_asterisco_campo_obligatorio('txt_segundo_nombre');
        agregar_asterisco_campo_obligatorio('txt_cedula');
        agregar_asterisco_campo_obligatorio('txt_correo');

        $("#form_persona").validate({
            rules: {
                txt_primer_apellido: {
                    required: true,
                },
                txt_segundo_apellido: {
                    required: true,
                },
                txt_primer_nombre: {
                    required: true,
                },
                txt_segundo_nombre: {
                    required: true,
                },
                txt_cedula: {
                    required: true,
                    minlength: 10,
                },
                ddl_sexo: {
                    //required: true,
                },
                txt_fecha_nacimiento: {
                    //required: true,
                },
                txt_correo: {
                    required: true,
                },
                txt_telefono_1: {
                    required: true,
                    minlength: 10,
                    digits: true
                },
                txt_telefono_2: {
                    //required: true,
                },
                ddl_estado_civil: {
                    //required: true,
                },
                txt_postal: {
                    //required: true,
                },
                txt_direccion: {
                    //required: true,
                },
                txt_cargo: {
                    //required: true,
                },
                txt_observaciones: {
                    //required: true,
                },
            },

            highlight: function(element) {
                // Agrega la clase 'is-invalid' al input que falla la validación
                $(element).addClass('is-invalid');
                $(element).removeClass('is-valid');
            },
            unhighlight: function(element) {
                // Elimina la clase 'is-invalid' si la validación pasa
                $(element).removeClass('is-invalid');
                $(element).addClass('is-valid');

            }
        });
    });
</script>