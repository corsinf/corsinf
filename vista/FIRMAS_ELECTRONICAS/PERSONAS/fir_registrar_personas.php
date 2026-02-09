<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
$redireccionar_vista = 'fir_personas';

$id_persona = '';

if (isset($_GET['_id'])) {
    $id_persona = $_GET['_id'];
}

?>
<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>


<script>
    $(document).ready(function() {
        <?php if (isset($_GET['_id'])) { ?>
            cargar_datos_persona('<?= $id_persona ?>');
            listar_solicitud_persona('<?= $id_persona ?>');
        <?php } ?>
    })

    function insertar_editar() {
        let parametros = {
            '_id': '<?= $id_persona ?>',
        };

        let parametros_vista_persona = parametros_persona();
        parametros = {
            ...parametros,
            ...parametros_vista_persona
        };

        if ($("#registrar_personas").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            insertar(parametros);
        }
    }

    function insertar(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/GENERAL/th_personasC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=<?= $redireccionar_vista ?>';
                    });
                } else if (response == -2) {
                    //Swal.fire('', 'Operación fallida', 'warning');
                    $(txt_cedula).addClass('is-invalid');
                    $('#error_txt_cedula').text('La cédula ya está en uso.');
                }
            }
        });

        $('#txt_cedula').on('input', function() {
            $('#error_txt_cedula').text('');
        });
    }

    function listar_solicitud_persona(_id) {
        $.ajax({
            data: {
                _id: _id
            },
            url: '../controlador/FIRMAS_ELECTRONICAS/fi_personasC.php?listar_solicitud_persona=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                let archivos = {
                    '#img_foto_personal': response['archivo_foto'],
                    '#ifr_copia_cedula': response['archivo_cedula'],
                    '#ifr_cert_ruc': response['archivo_ruc'],
                    '#ifr_cert_juridico': response['archivo_juridico']
                };

                $.each(archivos, function(selector, archivo) {
                    let panelId = $(selector).closest('.col-md-3').attr('id'); // Obtiene el ID del panel contenedor

                    if (archivo && archivo.trim() !== "") {
                        $(selector).attr('src', archivo);
                        $("#" + panelId).show();
                    } 
                });
            },

            error: function(xhr, status, error) {
                console.log('Status: ' + status);
                console.log('Error: ' + error);
                console.log('XHR Response: ' + xhr.responseText);

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Personas</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Registrar Personas</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="container-fluid">
            <div class="main-body">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">
                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bxs-id-card me-1 font-24 text-primary"></i>
                            </div>
                            <h5 class="mb-0 text-primary">
                                <?php
                                if ($id_persona == '') {
                                    echo 'Registrar Persona';
                                } else {
                                    echo 'Modificar Persona';
                                }
                                ?>
                            </h5>
                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=<?= $redireccionar_vista ?>" class="btn btn-outline-dark btn-sm d-flex align-items-center"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <form id="registrar_personas" class="modal_general_provincias">

                            <?php require_once('../vista/GENERAL/registrar_personas.php'); ?>

                            <div class="row">
                                <div class="col-12">
                                    <div class="row mb-col">
                                        <div class="col-md-3" id="pnl_foto_personal_paso_3" style="display: none;">
                                            <p class="form-label form-labeli-sm">1. Foto personal con la cédula colocada debajo del mentón.</p>
                                            <img src="" class="img-fluid w-100 border rounded shadow-sm" id="img_foto_personal" alt="Ejemplo Foto Personal" />
                                        </div>

                                        <div class="col-md-3" id="pnl_copia_cedula_paso_3" style="display: none;">
                                            <p class="form-label form-label-sm">2. Copia legible de la cédula. </p>
                                            <iframe src="" class="border rounded shadow-sm" id="ifr_copia_cedula" frameborder="0" width="100%" height="200px"></iframe>
                                        </div>

                                        <div class="col-md-3" id="pnl_cert_ruc_paso_3" style="display: none;">
                                            <p class="form-label form-label-sm">3. Certificado actualizado del RUC. </p>
                                            <iframe src="" class="border rounded shadow-sm" id="ifr_cert_ruc" frameborder="0" width="100%" height="200px"></iframe>
                                        </div>

                                        <div class="col-md-3" id="pnl_cert_juridico_paso_3" style="display: none;">
                                            <p class="form-label form-label-sm">4. Certificado Jurídico. </p>
                                            <iframe src="" class="border rounded shadow-sm" id="ifr_cert_juridico" frameborder="0" width="100%" height="200px"></iframe>
                                        </div>

                                    </div>

                                </div>
                            </div>

                            <div class="d-flex justify-content-end pt-2">
                                <?php if ($id_persona == '') { ?>
                                    <button class="btn btn-primary btn-sm px-4 m-0 d-flex align-items-center" onclick="insertar_editar();" type="button"><i class="bx bx-save"></i> Guardar</button>
                                <?php } else { ?>
                                    <button class="btn btn-primary btn-sm px-4 m-1 d-flex align-items-center" onclick="insertar_editar();" type="button"><i class="bx bx-save"></i> Guardar</button>
                                    <button class="btn btn-danger btn-sm px-4 m-1 d-flex align-items-center" onclick="delete_datos_persona()" type="button"><i class="bx bx-trash"></i> Eliminar</button>
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

        //* Validacion de formulario
        $("#registrar_personas").validate({
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
                },
                ddl_sexo: {
                    required: true,
                },
                txt_fecha_nacimiento: {
                    required: true,
                },
                txt_edad: {
                    //required: true,
                },
                txt_telefono_1: {
                    required: true,
                },
                txt_telefono_2: {
                    //required: true,
                },
                txt_correo: {
                    required: true,
                },
                ddl_nacionalidad: {
                    //required: true,
                },
                ddl_estado_civil: {
                    //required: true,
                },
                ddl_provincias: {
                    required: true,
                },
                ddl_ciudad: {
                    required: true,
                },
                ddl_parroquia: {
                    //required: true,
                },
                txt_codigo_postal: {
                    required: true,
                },
                txt_direccion: {
                    //required: true,
                },
            },
            messages: {
                txt_primer_apellido: {
                    required: "Por favor ingrese el primer apellido",
                },
                txt_segundo_apellido: {
                    required: "Por favor ingrese el segundo apellido",
                },
                txt_primer_nombre: {
                    required: "Por favor ingrese el primer nombre",
                },
                txt_segundo_nombre: {
                    required: "Por favor ingrese el segundo nombre",
                },
                txt_cedula: {
                    required: "Por favor ingresa un número de cédula",
                },
                ddl_sexo: {
                    required: "Por favor seleccione el sexo",
                },
                txt_fecha_nacimiento: {
                    required: "Por favor ingrese la fecha de nacimiento",
                },
                txt_edad: {
                    required: "Por favor ingrese la edad (fecha de nacimiento)",
                },
                txt_telefono_1: {
                    required: "Por favor ingrese un número de teléfono",
                },
                txt_telefono_2: {
                    required: "Por favor ingrese un número de teléfono",
                },
                txt_correo: {
                    required: "Por favor ingrese un correo electrónico",
                },
                ddl_nacionalidad: {
                    required: "Por favor seleccione una nacionalidad",
                },
                ddl_estado_civil: {
                    required: "Por favor seleccione un estado civil",
                },
                ddl_provincias: {
                    required: "Por favor seleccione una provincia",
                },
                ddl_ciudad: {
                    required: "Por favor seleccione una ciudad",
                },
                ddl_parroquia: {
                    required: "Por favor seleccione una parroquia",
                },
                txt_codigo_postal: {
                    required: "Por favor ingrese una dirección postal",
                },
                txt_direccion: {
                    required: "Por favor ingrese una dirección",
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