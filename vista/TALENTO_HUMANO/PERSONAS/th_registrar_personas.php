<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
$redireccionar_vista = 'th_personas';

$_id = '';

if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}

$id_postulante = '';
if (isset($_GET['id_postulante'])) {
    $id_postulante = $_GET['id_postulante'];
    $redireccionar_vista = "th_informacion_personal&id=$id_postulante&id_persona=$_id";
}

?>

<script>
    //Se lo utiliza para la seccion de biometria
    var PersonaId = '<?php echo $_id; ?>'
</script>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>
<script src="../js/RECURSOS_HUMANOS/biometria.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        dispositivos();
        // cargar_tabla();
        <?php if (isset($_GET['_id'])) { ?>
            cargar_datos_persona(<?= $_id ?>);
            cargar_departamento(<?= $_id ?>);
        <?php } ?>
        cargar_selects2();

    });

    function cargar_selects2() {

        url_departamentosC = '../controlador/TALENTO_HUMANO/th_departamentosC.php?buscar=true';
        cargar_select2_url('ddl_departamentos', url_departamentosC);

        url_etniaC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_etniaC.php?buscar=true';
        cargar_select2_url('ddl_etnia', url_etniaC);

        url_religionC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_religionC.php?buscar=true';
        cargar_select2_url('ddl_religion', url_religionC);

        url_orientacion_sexualC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_orientacion_sexualC.php?buscar=true';
        cargar_select2_url('ddl_orientacion_sexual', url_orientacion_sexualC);

        url_identidad_generoC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_identidad_generoC.php?buscar=true';
        cargar_select2_url('ddl_identidad_genero', url_identidad_generoC);

    }

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
                        location.href =
                            '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=<?= $redireccionar_vista ?>';
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

<script>
    //Funciones para enviar correos
    $(function() {
        var $cbx = $('#cbx_enviar_credenciales');
        var $contInputs = $('#cont_inputs_mensaje');
        var $infoCred = $('#info_credenciales');
        var $modal = $('#modal_mensaje');


        function actualizarVista() {
            if ($cbx.length && $cbx.is(':checked')) {
                $contInputs.hide();
                $infoCred.show();
            } else {
                $contInputs.show();
                $infoCred.hide();
            }
        }


        // Al mostrar el modal, inicializamos la vista
        $modal.on('show.bs.modal', function() {
            actualizarVista();
        });
        $cbx.on('change', actualizarVista);
        window.enviarMensaje = function() {
            var enviarCred = $('#cbx_enviar_credenciales').is(':checked');
            var asunto = $.trim($('#txt_asunto').val() || '');
            var descripcion = $.trim($('#txt_descripcion').val() || '');
            if (!enviarCred) {
                if (asunto === '') {
                    alert('Ingresa el asunto.');
                    $('#txt_asunto').focus();
                    return;
                }
                if (descripcion === '') {
                    alert('Ingresa la descripción.');
                    $('#txt_descripcion').focus();
                    return;
                }
            }
            var parametrosLogCorreos = {
                enviar_credenciales: enviarCred ? 1 : 0,
                asunto: asunto,
                descripcion: descripcion,
                per_id: '<?= $_id ? $_id : '' ?>',
                personas: 'general'
            };
            enviar_Mail_Persona(parametrosLogCorreos);
            $modal.modal('hide');
        };


        function enviar_Mail_Persona(parametrosLogCorreos) {

            $.ajax({
                data: {
                    parametros: parametrosLogCorreos
                },
                url: '../controlador/TALENTO_HUMANO/th_logs_correosC.php?enviar_correo=true',
                type: 'post',
                dataType: 'json',
                beforeSend: function() {
                    Swal.fire({
                        title: 'Guardando...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {

                    if (response && response.total !== undefined) {

                        let mensaje = `
            <b>Total:</b> ${response.total}<br>
            <b>Enviados:</b> ${response.enviados}<br>
            <b>Fallidos:</b> ${response.fallidos}
        `;

                        // Si quieres mostrar el detalle de fallidos
                        if (response.fallidos > 0) {
                            mensaje += '<hr><b>Correos con error:</b><br>';

                            response.detalle.forEach(item => {
                                if (item.estado === 'ERROR') {
                                    mensaje += `• ${item.correo}<br>`;
                                }
                            });
                        }

                        Swal.fire({
                            icon: response.fallidos > 0 ? 'warning' : 'success',
                            title: 'Resultado del envío',
                            html: mensaje,
                            confirmButtonText: 'Aceptar'
                        })
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Respuesta inválida del servidor'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error', 'Error en la conexión: ' + error, 'error');
                }
            });
        }
    });
</script>

<script>
    function cargar_departamento(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/th_personasC.php?listar_persona_departamento=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response && response.length > 0) {
                    $('#id_perdep').val(response[0]._id_perdep);
                    $('#ddl_departamentos').append($('<option>', {
                        value: response[0].id_departamento,
                        text: response[0].nombre_departamento,
                        selected: true
                    }));
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al cargar departamento:', error);
            }
        });
    }

    function insertar_persona_departamento() {
        const deptId = $('#ddl_departamentos').val();
        const perdepId = $('#id_perdep').val();

        if (!deptId) {
            Swal.fire('', 'Seleccione un departamento', 'warning');
            return;
        }

        const parametros = {
            '_id': perdepId || '',
            'id_persona': '<?= $_id ?>',
            'id_departamento': deptId,
            'txt_visitor': $('#txt_visitor').val() || ''
        };

        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_personas_departamentosC.php?insertar_editar_persona=true',
            type: 'post',
            dataType: 'json',
            data: {
                parametros: parametros
            },
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operación realizada con éxito.', 'success').then(() => {
                        location.reload();
                    });
                } else if (response == -2) {
                    Swal.fire('', 'Esta persona ya está asignada a este departamento', 'warning');
                } else {
                    Swal.fire('', 'Error en la operación', 'error');
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
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Información personal</li>
                    </ol>
                </nav>
            </div>
            <div class="row m-2">
                <div class="col-sm-12">
                    <button onclick="boton_regresar_js();"
                        class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i>
                        Regresar</button>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="container-fluid">
            <div class="main-body">
                <div class="row d-flex justify-content-center">

                    <!-- Cards de la derecha -->
                    <div class="">
                        <div class="card-body">
                            <!-- Nav Cards -->
                            <ul class="nav nav-tabs nav-success" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#tab_persona" role="tab"
                                        aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bxs-briefcase font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">Información</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tab_departamento" role="tab"
                                        aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bxs-file-doc font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">Departamento</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tab_estado_laboral" role="tab"
                                        aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bxs-file-doc font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">Estado Laboral</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tab_vehiculos" role="tab"
                                        aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bxs-file-doc font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">Vehículos</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tab_nomina" role="tab"
                                        aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bxs-file-doc font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">Nómina</div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content pt-3">
                                <!-- Primera Sección, Informacion de la persona -->
                                <div class="tab-pane fade show active" id="tab_persona" role="tabpanel">
                                    <div class="card">
                                        <div class="d-flex flex-column mx-4">
                                            <div class="card-body">
                                                <div class="col-12">
                                                    <h5 class="mb-0 text-primary">
                                                        <i class="bx bxs-user me-1 font-22 text-primary"></i>
                                                        <?php
                                                        if ($_id == '') {
                                                            echo 'Registrar Persona';
                                                        } else {
                                                            echo 'Modificar Persona';
                                                        }
                                                        ?>
                                                    </h5>
                                                </div>

                                                <hr>

                                                <div class="col-12">
                                                    <button class="btn btn-primary btn-sm" onclick="modalBiometria()"><i
                                                            class="bx bx-sync"></i>Biometria</button>
                                                    <a href="javascript:void(0)" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#modal_mensaje">
                                                        <i class="bx bx-envelope"></i> Enviar Mensaje
                                                    </a>
                                                    <!-- Todo lo relacionado con Biometria -->
                                                    <?php include_once('../vista/TALENTO_HUMANO/PERSONAS/MENU/th_persona_biometrico.php'); ?>

                                                    <!-- <button class="btn btn-primary btn-sm" onclick="syncronizarPersona()"><i class="bx bx-sync"></i>Syncronizar persona en biometrico</button>                                     -->
                                                </div>

                                                <div class="pt-2">
                                                    <form id="registrar_personas" class="modal_general_provincias">
                                                        <?php include_once('../vista/GENERAL/registrar_personas.php'); ?>

                                                        <div class="d-flex justify-content-end pt-2">
                                                            <?php if ($_id == '') { ?>
                                                                <button class="btn btn-primary btn-sm px-4 m-0 d-flex align-items-center"
                                                                    onclick="insertar_editar_persona();" type="button"><i class="bx bx-save"></i>
                                                                    Guardar</button>
                                                            <?php } else { ?>
                                                                <button class="btn btn-primary btn-sm px-4 m-1 d-flex align-items-center"
                                                                    onclick="insertar_editar_persona();" type="button"><i class="bx bx-save"></i>
                                                                    Guardar</button>
                                                                <button class="btn btn-danger btn-sm px-4 m-1 d-flex align-items-center"
                                                                    onclick="delete_datos_persona()" type="button"><i class="bx bx-trash"></i>
                                                                    Eliminar</button>
                                                            <?php } ?>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Segunda Sección, Departamentos -->
                                <div class="tab-pane fade" id="tab_departamento" role="tabpanel">
                                    <div class="card">
                                        <div class="d-flex flex-column mx-4">
                                            <!-- Documento de Identidad -->
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <?php include_once('../vista/TALENTO_HUMANO/PERSONAS/MENU/th_persona_departamento.php'); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tercera Sección, Estado Labaral -->
                                <div class="tab-pane fade" id="tab_estado_laboral" role="tabpanel">
                                    <div class="card">
                                        <div class="d-flex flex-column mx-4">
                                            <!-- Idiomas -->
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-6 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Estado laboral:</h6>
                                                        </div>
                                                        <div class="col-6 d-flex justify-content-end">
                                                            <a href="#"
                                                                class="text-success icon-hover d-flex align-items-center"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal_estado_laboral">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr>
                                                <?php include_once('../vista/TALENTO_HUMANO/PERSONAS/MENU/th_persona_estado_laboral.php'); ?>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!-- Cuarta Sección, Vehiculos -->
                                <div class="tab-pane fade" id="tab_vehiculos" role="tabpanel">
                                    <div class="card">
                                        <div class="d-flex flex-column mx-4">
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-6 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Vehiculos:</h6>
                                                        </div>
                                                        <div class="col-6 d-flex justify-content-end">
                                                            <a href="#"
                                                                class="text-success icon-hover d-flex align-items-center"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal_agregar_vehiculo">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr>

                                                <?php include_once('../vista/TALENTO_HUMANO/PERSONAS/MENU/th_persona_vehiculo.php'); ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tab_nomina" role="tabpanel">
                                    <div class="card">
                                        <div class="d-flex flex-column mx-4">
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-6 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Nómina:</h6>
                                                        </div>
                                                        <div class="col-6 d-flex justify-content-end">
                                                            <a href="#"
                                                                class="text-success icon-hover d-flex align-items-center"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal_agregar_nomina">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <?php include_once('../vista/TALENTO_HUMANO/PERSONAS/MENU/th_persona_nomina.php'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                    $element.next(".select2-container").find(".select2-selection").removeClass(
                        "is-valid").addClass("is-invalid");
                } else if ($element.is(':radio')) {
                    // Si es un radio button, aplicar la clase al grupo de radios (al contenedor padre si existe)
                    $('input[name="' + $element.attr("name") + '"]').addClass("is-invalid")
                        .removeClass("is-valid");
                } else {
                    // Elimina la clase 'is-invalid' y agrega 'is-valid' al input normal
                    $element.removeClass("is-valid").addClass("is-invalid");
                }
            },

            unhighlight: function(element) {
                let $element = $(element);

                if ($element.hasClass("select2-hidden-accessible")) {
                    // Para Select2, elimina 'is-invalid' y agrega 'is-valid' en el contenedor adecuado
                    $element.next(".select2-container").find(".select2-selection").removeClass(
                        "is-invalid").addClass("is-valid");
                } else if ($element.is(':radio')) {
                    // Si es un radio button, marcar todo el grupo como válido
                    $('input[name="' + $element.attr("name") + '"]').removeClass("is-invalid")
                        .addClass("is-valid");
                } else {
                    // Para otros elementos normales
                    $element.removeClass("is-invalid").addClass("is-valid");
                }
            }
        });
    });
</script>