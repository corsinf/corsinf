<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
$redireccionar_vista = 'th_personas';

$id_persona = '';

if (isset($_GET['id_persona'])) {
    $id_persona = $_GET['id_persona'];
}

$id_postulante = '';

if (isset($_GET['id_postulante'])) {
    $id_postulante = $_GET['id_postulante'];
}

if (isset($_GET['_origen']) && $_GET['_origen'] == 'nomina') {
    $redireccionar_vista = 'th_personas_nomina';
}

if ($_SESSION['INICIO']['TIPO'] == "PERSONAS") {
    $redireccionar_vista = "th_registrar_personas&id_persona=$id_persona&id_postulante=$id_postulante&_origen=nomina&_persona_nomina=true";
}

$roles_restringidos = ['PERSONAS', 'POSTULANTES'];
$tipo_usuario = strtoupper($_SESSION['INICIO']['TIPO']);
$es_restringido = in_array($tipo_usuario, $roles_restringidos);

// Esta variable sirve para CUALQUIER input, select o button
$html_disabled = $es_restringido ? "disabled" : "";


?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script>
    $(window).on('load', function() {
        $("#loader-overlay").fadeOut("slow");
    });

    // Postulante agregar en caso de que no este
    $(document).ready(function() {
        <?php if (isset($_GET['id_postulante'])) { ?>
            recargar_persona_postulante('<?= $id_postulante ?>', '<?= $id_persona ?>');
        <?php } ?>
    })

    function recargar_persona_postulante(id, id_persona) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_postulantesC.php?listar=true',
            type: 'post',
            data: {
                id: id,
                id_persona: id_persona
            },
            dataType: 'json',
            success: function(response) {

                if (response.recargar == 1 && response.id_postulante) {
                    let nueva_Url = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_personas&id_persona=<?= $id_persona ?>&id_postulante=${response.id_postulante}&_origen=nomina&_persona_nomina=true`;

                    // Cambia la URL sin recargar
                    window.history.replaceState(null, '', nueva_Url);

                    // Recarga real solo una vez
                    location.reload();
                    return;
                }

                // //Input para todos los pos_id que se vayan a colocar en los modales
                $('input[name="txt_postulante_id"]').val(response[0].th_pos_id);
                $('input[name="txt_postulante_cedula"]').val(response[0].th_pos_cedula);
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


<script type="text/javascript">
    $(document).ready(function() {
        <?php if (isset($_GET['id_persona'])) { ?>
            cargar_datos_persona('<?= $id_persona ?>');
            cargar_selects2();
        <?php } ?>
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
                per_id: '<?= $id_persona ? $id_persona : '' ?>',
                personas: 'nomina'
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

<?php include_once('../vista/GENERAL/sppiner_cargar_pagina.php'); ?>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Persona</div>
            <h6></h6>
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
                    <a href="inicio.php?mod=<?= $modulo_sistema ?>&acc=<?= $redireccionar_vista ?>"
                        class="btn btn-outline-primary btn-sm"><i class="bx bx-arrow-back"></i>
                        Regresar</a>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="container-fluid">
            <div class="main-body">

                <div class="card shadow-sm border-0">
                    <div class="row g-0">
                        <div class="col-md-3 bg-light border-end">
                            <div class="p-3">

                                <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 15px;">
                                    <div class="bg-primary" style="height: 80px; background: linear-gradient(45deg, #4e73df 0%, #224abe 100%);"></div>

                                    <div class="card-body pt-0 pb-4">
                                        <div class="d-flex flex-column align-items-center" style="margin-top: -40px;">

                                            <div class="position-relative">
                                                <div class="shadow-sm rounded-circle bg-white p-1">
                                                    <img src="../img/sin_imagen.jpg"
                                                        id="img_persona_inf"
                                                        alt="Perfil"
                                                        class="rounded-circle object-fit-cover"
                                                        style="width: 115px; height: 115px; display: block;">
                                                </div>

                                                <button type="button"
                                                    class="btn btn-dark btn-sm rounded-circle position-absolute bottom-0 end-0 d-flex align-items-center justify-content-center shadow"
                                                    style="width: 34px; height: 34px; border: 2px solid #fff;"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modal_agregar_cambiar_foto_persona"
                                                    onclick="abrir_modal_cambiar_foto_persona('<?= $id_persona ?>');"
                                                    title="Cambiar foto">
                                                    <i class='bx bxs-camera fs-6'></i>
                                                </button>
                                            </div>

                                            <div class="mt-2 text-center">
                                                <div class="fw-bold" id="lbl_nombre_completo_perfil">---</div>
                                                <div class="text-secondary small" id="lbl_cedula_perfil">---</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="mb-4 opacity-25">

                                <div class="nav flex-column nav-pills gap-2" id="v-pills-tab" role="tablist" aria-orientation="vertical">


                                    <button class="nav-link active py-2 px-3 border shadow-sm" data-bs-toggle="pill" data-bs-target="#tab_persona" type="button" role="tab">
                                        <div class="d-flex align-items-center">
                                            <i class="bx bxs-user-circle me-3 fs-5"></i>
                                            <span>Información Personal</span>
                                        </div>
                                    </button>

                                    <?php if (isset($_GET['_persona_nomina']) && $_GET['_persona_nomina'] == 'true'): ?>

                                        <button class="nav-link py-2 px-3 border shadow-sm" data-bs-toggle="pill" data-bs-target="#tab_departamento" type="button" role="tab">
                                            <div class="d-flex align-items-center">
                                                <i class="bx bxs-building-house me-3 fs-5"></i>
                                                <span>Departamento</span>
                                            </div>
                                        </button>

                                        <button class="nav-link py-2 px-3 border shadow-sm" data-bs-toggle="pill" data-bs-target="#tab_estado_laboral" type="button" role="tab">
                                            <div class="d-flex align-items-center">
                                                <i class="bx bxs-briefcase me-3 fs-5"></i>
                                                <span>Estado Laboral</span>
                                            </div>
                                        </button>

                                        <button class="nav-link py-2 px-3 border shadow-sm" data-bs-toggle="pill" data-bs-target="#tab_vehiculos" type="button" role="tab">
                                            <div class="d-flex align-items-center">
                                                <i class="bx bxs-car me-3 fs-5"></i>
                                                <span>Vehículos</span>
                                            </div>
                                        </button>

                                        <button class="nav-link py-2 px-3 border shadow-sm" data-bs-toggle="pill" data-bs-target="#tab_comision" type="button" role="tab">
                                            <div class="d-flex align-items-center">
                                                <i class="bx bxs-dollar-circle me-3 fs-5"></i>
                                                <span>Comisión</span>
                                            </div>
                                        </button>

                                        <button class="nav-link py-2 px-3 border shadow-sm" data-bs-toggle="pill" data-bs-target="#tab_parientes" type="button" role="tab">
                                            <div class="d-flex align-items-center">
                                                <i class="bx bxs-group me-3 fs-5"></i>
                                                <span>Referencias</span>
                                            </div>
                                        </button>

                                        <button class="nav-link py-2 px-3 border shadow-sm" data-bs-toggle="pill" data-bs-target="#tab_informacion_adicional" type="button" role="tab">
                                            <div class="d-flex align-items-center">
                                                <i class="bx bxs-info-circle me-3 fs-5"></i>
                                                <span>Adicional</span>
                                            </div>
                                        </button>

                                        <!-- Vista para postulante -->
                                        <div class="mt-4 mb-2 ps-2">
                                            <small class="text-muted fw-bold" style="font-size: 0.7rem;">DETALLES</small>
                                        </div>
                                        <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_all_tab.php'); ?>
                                        <!-- end vista postulante -->

                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-9 bg-white min-vh-100">
                            <div class="tab-content p-4" id="v-pills-tabContent">

                                <div class="tab-pane fade show active" id="tab_persona" role="tabpanel">
                                    <!-- <div class="card border-0"> -->
                                    <div class="card-body">
                                        <div class="col-12">
                                            <!-- <h5 class="mb-0 text-primary">
                                                <i class="bx bxs-user me-1 font-22 text-primary"></i>
                                                <?php
                                                if ($id_persona == '') {
                                                    echo 'Registrar Persona';
                                                } else {
                                                    echo 'Información personal';
                                                }
                                                ?>
                                            </h5> -->


                                            <?php if ($_SESSION['INICIO']['TIPO'] != "PERSONAS") { ?>
                                                <div class="col-12 pt-2">
                                                    <button class="btn btn-primary btn-sm" onclick="modalBiometria()"><i
                                                            class="bx bx-sync"></i>Biometria</button>
                                                    <a href="javascript:void(0)" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#modal_mensaje">
                                                        <i class="bx bx-envelope"></i> Enviar Mensaje
                                                    </a>
                                                    <!-- Todo lo relacionado con Biometria -->
                                                    <?php //include_once('../vista/TALENTO_HUMANO/PERSONAS/MENU/th_persona_biometrico.php'); ?>

                                                    <!-- <button class="btn btn-primary btn-sm" onclick="syncronizarPersona()"><i class="bx bx-sync"></i>Syncronizar persona en biometrico</button>                                     -->
                                                </div>
                                            <?php } ?>

                                        </div>

                                        <hr>

                                        <div class="pt-2">
                                            <form id="registrar_personas" class="modal_general_provincias">
                                                <?php include_once('../vista/GENERAL/registrar_personas.php'); ?>

                                                <div class="d-flex justify-content-end pt-2">
                                                    <?php if ($id_persona == '') { ?>
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
                                            <?php include_once('../vista/TALENTO_HUMANO/PERSONAS/th_per_cambiar_foto.php'); ?>
                                        </div>
                                    </div>
                                    <!-- </div> -->
                                </div>

                                <?php if (isset($_GET['_persona_nomina']) && $_GET['_persona_nomina'] == 'true') { ?>
                                    <!-- Segunda Sección, Departamentos -->
                                    <div class="tab-pane fade" id="tab_departamento" role="tabpanel">
                                        <!-- <div class="card"> -->
                                        <div class="d-flex flex-column mx-4">
                                            <!-- Documento de Identidad -->
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <?php include_once('../vista/TALENTO_HUMANO/PERSONAS/MENU/th_persona_departamento.php'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- </div> -->

                                    </div>

                                    <!-- Tercera Sección, Estado Labaral -->
                                    <div class="tab-pane fade" id="tab_estado_laboral" role="tabpanel">
                                        <!-- <div class="card"> -->
                                        <div class="d-flex flex-column mx-4">
                                            <!-- Idiomas -->
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-6 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Estado laboral:</h6>
                                                        </div>
                                                        <div id="pnl_crear_estado_laboral" class="col-6 d-flex justify-content-end">

                                                            <?php if (!$es_restringido): ?>
                                                                <a href="#"
                                                                    class="text-success icon-hover d-flex align-items-center"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#modal_estado_laboral">
                                                                    <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                    <span>Agregar</span>
                                                                </a>
                                                            <?php endif; ?>

                                                        </div>
                                                    </div>
                                                </div>

                                                <hr>
                                                <?php include_once('../vista/TALENTO_HUMANO/PERSONAS/MENU/th_persona_estado_laboral.php'); ?>
                                            </div>

                                        </div>
                                        <!-- </div> -->
                                    </div>
                                    <!-- Cuarta Sección, Vehiculos -->
                                    <div class="tab-pane fade" id="tab_vehiculos" role="tabpanel">
                                        <!-- <div class="card"> -->
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
                                        <!-- </div> -->
                                    </div>

                                    <div class="tab-pane fade" id="tab_comision" role="tabpanel">
                                        <!-- <div class="card"> -->
                                        <div class="d-flex flex-column mx-4">
                                            <div class="card-body">

                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-6 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Comisiones:</h6>
                                                        </div>

                                                        <div class="col-6 d-flex justify-content-end">
                                                            <?php if (!$es_restringido): ?>
                                                                <a href="#"
                                                                    class="text-success icon-hover d-flex align-items-center"
                                                                    onclick="abrir_modal_comision('');">
                                                                    <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                    <span>Agregar</span>
                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr>

                                                <?php include_once('../vista/TALENTO_HUMANO/PERSONAS/MENU/th_persona_comision.php'); ?>

                                            </div>
                                        </div>
                                        <!-- </div> -->
                                    </div>

                                    <div class="tab-pane fade" id="tab_parientes" role="tabpanel">
                                        <!-- <div class="card"> -->
                                        <div class="d-flex flex-column mx-4">
                                            <div class="card-body">

                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-6 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Referencias Personales:</h6>
                                                        </div>

                                                        <div class="col-6 d-flex justify-content-end">
                                                            <a href="#"
                                                                class="text-success icon-hover d-flex align-items-center"
                                                                onclick="abrir_modal_nuevo_pariente('');">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr>

                                                <?php include_once('../vista/TALENTO_HUMANO/PERSONAS/MENU/th_persona_parentesco.php'); ?>

                                            </div>
                                        </div>
                                        <!-- </div> -->
                                    </div>

                                    <div class="tab-pane fade" id="tab_informacion_adicional" role="tabpanel">
                                        <!-- <div class="card"> -->
                                        <div class="d-flex flex-column mx-4">
                                            <div class="card-body">

                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-6 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Información Adicional:</h6>
                                                        </div>
                                                        <!-- <div class="col-6 d-flex justify-content-end">
                                                            <a href="#"
                                                                class="text-success icon-hover d-flex align-items-center"
                                                                onclick="abrir_modal_informacion_adicional('');">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div> -->
                                                    </div>
                                                </div>

                                                <h3>Próximamente</h3>

                                                <hr>

                                                <?php //include_once('../vista/TALENTO_HUMANO/PERSONAS/MENU/th_persona_informacion_adicional.php'); 
                                                ?>

                                            </div>
                                        </div>
                                        <!-- </div> -->
                                    </div>

                                    <?php if (isset($_GET['id_postulante']) && $_GET['id_postulante'] != null) { ?>
                                        <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_all_tab_pane.php'); ?>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php //include_once('../vista/TALENTO_HUMANO/PERSONAS/prueba.php'); 
            ?>

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


<style>
    /* CSS para que se vea profesional */
    .nav-pills .nav-link {
        color: #4b5563;
        background-color: #ffffff;
        border: 1px solid #e5e7eb !important;
        transition: all 0.2s ease;
        text-align: left;
    }

    .nav-pills .nav-link:hover {
        background-color: #f3f4f6;
        color: #2563eb;
        border-color: #2563eb !important;
    }

    .nav-pills .nav-link.active {
        background-color: #2563eb !important;
        color: #ffffff !important;
        border-color: #2563eb !important;
        font-weight: 600;
    }

    .bg-light {
        background-color: #f9fafb !important;
    }
</style>