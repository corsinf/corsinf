<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
$redireccionar_vista = 'ge_personas';

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
            cargar_datos_persona(<?= $_id ?>);
        <?php } ?>
    })

    function insertar_editar_persona() {
        let parametros = {
            '_id': '<?= $_id ?>',
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
                                if ($_id == '') {
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

                            <?php include_once('../vista/GENERAL/registrar_personas.php'); ?>

                            <div class="d-flex justify-content-end pt-2">
                                <?php if ($_id == '') { ?>
                                    <button class="btn btn-primary btn-sm px-4 m-0 d-flex align-items-center" onclick="insertar_editar_persona();" type="button"><i class="bx bx-save"></i> Guardar</button>
                                <?php } else { ?>
                                    <button class="btn btn-primary btn-sm px-4 m-1 d-flex align-items-center" onclick="insertar_editar_persona();" type="button"><i class="bx bx-save"></i> Guardar</button>
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

        //Para validar los select2
        $(".select2-validation").on("select2:select", function(e) {
            unhighlight_select(this);
        });

        //* Validacion de formulario
        $("#registrar_personas").validate({
            rules: {
                txt_primer_apellido: {
                    required: true,
                },
                txt_segundo_apellido: {
                    // required: true,
                },
                txt_primer_nombre: {
                    required: true,
                },
                txt_segundo_nombre: {
                    // required: true,
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
                let $element = $(element);

                if ($element.hasClass("select2-hidden-accessible")) {
                    // Elimina la clase 'is-invalid' y agrega 'is-valid' al contenedor correcto de select2
                    $element.next(".select2-container").find(".select2-selection").removeClass("is-valid").addClass("is-invalid");
                } else if ($element.is(':radio')) {
                    // Si es un radio button, aplicar la clase al grupo de radios (al contenedor padre si existe)
                    $('input[name="' + $element.attr("name") + '"]').addClass("is-invalid").removeClass("is-valid");
                } else {
                    // Elimina la clase 'is-invalid' y agrega 'is-valid' al input normal
                    $element.removeClass("is-valid").addClass("is-invalid");
                }
            },

            unhighlight: function(element) {
                let $element = $(element);

                if ($element.hasClass("select2-hidden-accessible")) {
                    // Para Select2, elimina 'is-invalid' y agrega 'is-valid' en el contenedor adecuado
                    $element.next(".select2-container").find(".select2-selection").removeClass("is-invalid").addClass("is-valid");
                } else if ($element.is(':radio')) {
                    // Si es un radio button, marcar todo el grupo como válido
                    $('input[name="' + $element.attr("name") + '"]').removeClass("is-invalid").addClass("is-valid");
                } else {
                    // Para otros elementos normales
                    $element.removeClass("is-invalid").addClass("is-valid");
                }
            }
        });
    });
</script>