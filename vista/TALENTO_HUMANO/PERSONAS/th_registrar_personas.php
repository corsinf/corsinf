<?php
require_once(dirname(__DIR__, 3) . '/helpers/helper_roles_no_concurrentes.php');

$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$id_persona = '';

if (isset($_GET['id_persona'])) {
    $id_persona = $_GET['id_persona'];
}

$id_postulante = '';

if (isset($_GET['id_postulante'])) {
    $id_postulante = $_GET['id_postulante'];
}

$link_edicion = obtener_link_edicion();
$html_disabled = html_disabled();
$redireccionar_vista = obtener_redireccion();
$es_restringido = es_restringido();


validar_acceso_persona($_GET['id_persona'] ?? '');

?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script>
    $(window).on('load', function() {
        $("#loader-overlay").fadeOut("slow");
    });

    $(document).on('input', '#txt_cedula', function() {
        var val = $(this).val().trim();
        var $err = $('#error_txt_cedula');

        $(this).removeClass('is-invalid is-valid');
        $err.text('');

        if (val.length === 10) {
            var id_actual = $('input[name="txt_persona_id"]').val() || 0;

            $.ajax({
                url: '../controlador/GENERAL/th_personasC.php?validar_cedula_duplicada=true',
                type: 'POST',
                dataType: 'json',
                data: {
                    cedula: val,
                    id_persona: '<?= $id_persona ?>'
                },
                success: function(res) {
                    if (res.duplicada) {
                        $('#txt_cedula').addClass('is-invalid').removeClass('is-valid');
                        $err.text('Esta cédula ya está registrada en el sistema.');
                    } else {
                        $('#txt_cedula').addClass('is-valid').removeClass('is-invalid');
                        $err.text('');
                    }
                }
            });
        }
    });

    // Postulante agregar en caso de que no este
    $(document).ready(function() {
        <?php if (isset($_GET['id_postulante'])) { ?>
            if ('<?= $id_postulante ?>' != 'postulante') {
                validar_persona_acceso('<?= $id_persona ?>', '<?= $id_postulante ?>');
            }
            recargar_persona_postulante('<?= $id_postulante ?>', '<?= $id_persona ?>');
        <?php } ?>
    });

    $(document).on('shown.bs.tab', '[data-bs-toggle="pill"]', function() {
        $('html, body').animate({
            scrollTop: 0
        }, 300);
    });

    function validar_persona_acceso(id_persona, id_postulante) {
        $.ajax({
            url: '../controlador/GENERAL/th_personasC.php?acceso_persona=true',
            type: 'post',
            data: {
                id_persona: id_persona,
                id_postulante: id_postulante
            },
            dataType: 'json',
            success: function(response) {
                // console.log(response);
                if (response != 1) {
                    location.href = 'inicio.php?acc=pagina_error';
                }
            },

            error: function(xhr, status, error) {
                console.log('Status: ' + status);
                console.log('Error: ' + error);
                console.log('XHR Response: ' + xhr.responseText);

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
    }

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
        <?php } ?>
        cargar_selects2();
    });

    function cargar_selects2() {

        url_departamentosC = '../controlador/TALENTO_HUMANO/th_departamentosC.php?buscar=true';
        cargar_select2_url('ddl_departamentos', url_departamentosC);

        cargar_select2_persona();

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
                } else if (response == -3) {
                    $(txt_correo).addClass('is-invalid');
                    $('#error_txt_correo').text('El correo electrónico ya está en uso.');
                } else if (response == -4) {
                    $(txt_cedula).addClass('is-invalid');
                    $(txt_correo).addClass('is-invalid');
                    $('#error_txt_cedula').text('La cédula ya está en uso.');
                    $('#error_txt_correo').text('El correo electrónico está en uso.');
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

        window.validarAperturaMensaje = function() {
            let esPersona = false;

            // Validamos la vista desde PHP
            <?php if ($redireccionar_vista == 'th_personas') { ?>
                esPersona = true;
            <?php } ?>

            if (esPersona) {
                Swal.fire({
                    title: '¿Confirmar acceso de visitante?',
                    text: 'Al confirmar a este usuario se le dará acceso al sistema como visitante para que llene sus datos. Es obligatorio la cédula y el correo.',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Continuar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#modal_mensaje').modal('show');
                        actualizarVista();

                    }
                });
            } else {
                $('#modal_mensaje').modal('show');
                actualizarVista();

            }
        }


        function actualizarVista() {
            if ($cbx.length && $cbx.is(':checked')) {
                $contInputs.hide();
                $infoCred.show();
            } else {
                $contInputs.show();
                $infoCred.hide();
            }
        }



        $cbx.on('change', actualizarVista);
        window.enviarMensaje = function() {
            var enviarCred = $('#cbx_enviar_credenciales').is(':checked');
            var asunto = $.trim($('#txt_asunto').val() || '');
            var descripcion = $.trim($('#txt_descripcion').val() || '');
            if (!enviarCred) {
                if (asunto === '') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atención',
                        text: 'Por favor, ingresa el asunto.',
                        confirmButtonColor: '#3085d6',
                    }).then(() => {
                        $('#txt_asunto').focus();
                    });
                    return;
                }

                if (descripcion === '') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atención',
                        text: 'Por favor, ingresa la descripción.',
                        confirmButtonColor: '#3085d6',
                    }).then(() => {
                        $('#txt_descripcion').focus();
                    });
                    return;
                }
            }
            let busqueda = '';

            <?php if ($redireccionar_vista == 'th_personas') { ?>
                busqueda = 'visitantes';
            <?php } else if ($redireccionar_vista == 'th_personas_nomina') { ?>
                busqueda = 'nomina';
            <?php } ?>
            var parametrosLogCorreos = {
                enviar_credenciales: enviarCred ? 1 : 0,
                asunto: asunto,
                descripcion: descripcion,
                per_id: '<?= $id_persona ? $id_persona : '' ?>',
                personas: busqueda,
            };
            enviar_Mail_Persona(parametrosLogCorreos);
            $modal.modal('hide');
        };

        function abrir_modal_nuevo_persona() {
            $('#txt_cedula_per').val('').removeClass('is-invalid is-valid');
            $('#txt_correo_per').val('').removeClass('is-invalid is-valid');
            $('#err_cedula_per').text('');
            $('#err_correo_per').text('');
            $('#modal_nuevo_persona').modal('show');
        }
 

        function enviar_Mail_Persona(parametrosLogCorreos) {

            var correo = $('#txt_correo').val();
            console.log(correo);

            var cedula = $('#txt_cedula').val();

            var correo = $.trim($('#txt_correo').val());
            var cedula = $.trim($('#txt_cedula').val());
            var error = false;

            // Resetear estados visuales antes de validar
            $('#txt_correo, #txt_cedula').removeClass('is-invalid');
            $('#error_txt_correo, #error_txt_cedula').text('');

            if (correo === '') {
                error = true;
            }

            if (cedula === '') {
                error = true;
            }

            if (error) {
                $modal.modal('hide');
                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: 'Por favor, actualice los datos faltantes.',
                    confirmButtonColor: '#3085d6',
                }).then(() => {
                    abrir_modal_nuevo_persona();
                });
                return;
            }

            $.ajax({
                data: {
                    parametros: parametrosLogCorreos
                },
                url: '../controlador/TALENTO_HUMANO/th_logs_correosC.php?enviar_correo=true',
                type: 'post',
                dataType: 'json',
                beforeSend: function() {
                    Swal.fire({
                        title: 'Enviando...',
                        showConfirmButton: false, 
                        allowOutsideClick: false, 
                        didOpen: () => {
                            Swal.showLoading(); 
                        }
                    });
                },
                success: function(response) {

                    let detalleHtml = '';

                    if (response.detalle && response.detalle.length > 0) {
                        detalleHtml = `
                                        <hr style="margin:12px 0">
                                        <details style="text-align:left">
                                            <summary style="cursor:pointer;font-weight:600">
                                                Detalle de correos fallidos/enviados
                                            </summary>
                                            <ul style="margin-top:8px;padding-left:18px">
                                                ${response.detalle.map(d => `
                                                    <li style="margin-bottom:6px">
                                                        <span style="font-weight:600">${d.correo}</span><br>
                                                        <span style="color:#666;font-size:13px">${d.mensaje}</span>
                                                    </li>
                                                `).join('')}
                                            </ul>
                                        </details>
                                    `;
                    }

                    let mensaje = `
                                    <div style="text-align:left;font-size:14px">
                                        <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                                            <span>Total procesados</span>
                                            <b>${response.total}</b>
                                        </div>
                                        <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                                            <span>Enviados</span>
                                            <b style="color:#2e7d32">${response.enviados}</b>
                                        </div>
                                        <div style="display:flex;justify-content:space-between">
                                            <span>Fallidos</span>
                                            <b style="color:#c62828">${response.fallidos}</b>
                                        </div>
                                        ${detalleHtml}
                                    </div>
                                `;

                    Swal.fire({
                        icon: response.fallidos > 0 ? 'warning' : 'success',
                        title: 'Resultado del envío',
                        html: mensaje,
                        confirmButtonText: 'Aceptar',
                        width: 480,
                        allowOutsideClick: false,
                    });
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
            <h6><?php //print_r($_SESSION['INICIO']); echo $es_restringido 
                ?></h6>
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

                                        <button class="nav-link py-2 px-3 border shadow-sm" data-bs-toggle="pill" data-bs-target="#tab_bancos" type="button" role="tab">
                                            <div class="d-flex align-items-center">
                                                <i class="bx bxs-building-house me-3 fs-5"></i>
                                                <span>Bancos</span>
                                            </div>
                                        </button>

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


                                            <?php if (!$es_restringido) { ?>
                                                <div class="col-12 pt-2">

                                                    <?php if (isset($_GET['_persona_nomina']) && $_GET['_persona_nomina'] == 'true') { ?>
                                                    <button class="btn btn-primary btn-sm" onclick="modalBiometria()"><i
                                                            class="bx bx-sync"></i>Biometria</button>
                                                    <!-- <button class="btn btn-primary btn-sm" onclick="ISAPIConnect()"><i
                                                            class="bx bx-sync"></i>ISAPI</button> -->
                                                        <?php } ?>



                                                    <a href="javascript:void(0)" class="btn btn-success btn-sm" onclick="validarAperturaMensaje();">
                                                        <i class="bx bx-envelope"></i> Enviar Mensaje
                                                    </a>

                                                    <!-- Todo lo relacionado con Biometria -->
                                                    <?php include_once('../vista/TALENTO_HUMANO/PERSONAS/MENU/th_persona_biometrico.php'); ?>

                                                    <!-- <button class="btn btn-primary btn-sm" onclick="syncronizarPersona()"><i class="bx bx-sync"></i>Syncronizar persona en biometrico</button>                                     -->
                                                </div>
                                            <?php } ?>

                                        </div>

                                        <?php if ($es_restringido) { ?>
                                            <hr>
                                        <?php } ?>

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

                                                        <?php if (isset($_GET['_persona_nomina']) && $_GET['_persona_nomina'] != 'true') { ?>
                                                            <button class="btn btn-danger btn-sm px-4 m-1 d-flex align-items-center"
                                                                onclick="delete_datos_persona()" type="button"><i class="bx bx-trash"></i>
                                                                Eliminar</button>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </div>
                                            </form>
                                            <?php include_once('../vista/TALENTO_HUMANO/PERSONAS/th_per_cambiar_foto.php'); ?>
                                        </div>
                                    </div>
                                    <!-- </div> -->
                                </div>

                                <?php if (isset($_GET['_persona_nomina']) && $_GET['_persona_nomina'] == 'true') { ?>
                                    <!-- Octava Sección, Bancos -->
                                    <div class="tab-pane fade" id="tab_bancos" role="tabpanel">
                                        <!-- <div class="card"> -->
                                        <div class="d-flex flex-column mx-4">
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-6 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Bancos:</h6>
                                                        </div>
                                                        <div class="col-6 d-flex justify-content-end">
                                                            <a href="#"
                                                                class="text-success icon-hover d-flex align-items-center"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal_agregar_bancos">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr>

                                                <?php include_once('../vista/TALENTO_HUMANO/PERSONAS/MENU/th_persona_bancos.php'); ?>

                                            </div>
                                        </div>
                                        <!-- </div> -->
                                    </div>

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

                                        <!-- Septima Sección, Contratos de Trabajo -->
                                        <div class="d-flex flex-column mx-4">
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-7 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Contratos de Trabajo:
                                                            </h6>
                                                        </div>
                                                        <div
                                                            class="col-5 d-flex justify-content-end align-items-center">
                                                            <a href="#"
                                                                class="text-success icon-hover d-flex align-items-center"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal_agregar_contratos">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span class="">Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-0 mb-3">

                                                <?php include_once('../vista/TALENTO_HUMANO/PERSONAS/MENU/th_persona_contratos_trabajo.php'); ?>

                                            </div>
                                        </div>

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

                                        <div class="d-flex flex-column mx-4">
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-6 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Lincencia de Transporte:</h6>
                                                        </div>
                                                        <div class="col-6 d-flex justify-content-end">
                                                            <a href="#"
                                                                class="text-success icon-hover d-flex align-items-center"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal_agregar_licencias_transportes">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr>

                                                <?php include_once('../vista/TALENTO_HUMANO/PERSONAS/MENU/th_persona_licencias_transporte.php'); ?>

                                            </div>
                                        </div>

                                        <!-- </div> -->
                                    </div>

                                    <!-- Quinta Sección, Comisiones -->
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

                                    <!-- Sexta Sección, Referencias Personales -->
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

                                    <!-- Octava Sección, Información Adicional: -->
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

<div class="modal fade" id="modal_mensaje" tabindex="-1" aria-labelledby="modal_mensaje_label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_mensaje_label">Enviar Mensaje</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="form_mensaje" onsubmit="return false;">
                <div class="modal-body">


                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="1" id="cbx_enviar_credenciales"
                            checked>
                        <label class="form-check-label" for="cbx_enviar_credenciales">Enviar credenciales</label>
                    </div>


                    <!-- Contenedor de inputs que se muestran cuando NO está marcado 'Enviar credenciales' -->
                    <div id="cont_inputs_mensaje" style="display: none;">
                        <div class="mb-3">
                            <label for="txt_asunto" class="form-label">Asunto</label>
                            <input type="text" class="form-control" id="txt_asunto" name="txt_asunto"
                                placeholder="Asunto del mensaje">
                        </div>
                        <div class="mb-3">
                            <label for="txt_descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="txt_descripcion" name="txt_descripcion" rows="5"
                                placeholder="Escribe aquí la descripción..."></textarea>
                        </div>
                    </div>


                    <!-- Mensaje informativo opcional -->
                    <div id="info_credenciales" class="small text-muted" style="display: block;">
                        Se enviarán las credenciales almacenadas para esta persona.
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" id="btn_enviar_mensaje" class="btn btn-primary"
                        onclick="enviarMensaje()">Enviar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_nuevo_persona" tabindex="-1"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bx bx-user-plus me-2 text-primary"></i>
                    Editar Persona
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 py-3">
                <p class="text-muted small mb-3">
                    <i class="bx bx-info-circle me-1"></i>
                    Se registrará la persona y se enviará automáticamente
                    un correo con sus credenciales de acceso.
                </p>

                <!-- Cédula -->
                <div class="mb-3">
                    <label class="form-label fw-semibold small">
                        Cédula <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="txt_cedula_per"
                        class="form-control form-control-sm"
                        placeholder="Ej: 1234567890"
                        maxlength="10"
                        oninput="this.value=this.value.replace(/\D/g,'')">
                    <div class="invalid-feedback" id="err_cedula_per"></div>
                </div>

                <!-- Correo -->
                <div class="mb-1">
                    <label class="form-label fw-semibold small">
                        Correo electrónico <span class="text-danger">*</span>
                    </label>
                    <input type="email" id="txt_correo_per"
                        class="form-control form-control-sm"
                        placeholder="correo@ejemplo.com">
                    <div class="invalid-feedback" id="err_correo_per"></div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm"
                    data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary btn-sm"
                    onclick="guardar_e_invitar_persona()">
                    <i class="bx bx-save me-1"></i> Guardar y enviar correo
                </button>
            </div>

        </div>
    </div>
</div>
<script>
    /* ── Abrir modal ───────────────────────────────── */
    function abrir_modal_nuevo_persona() {
        $('#txt_cedula_per').val('').removeClass('is-invalid is-valid');
        $('#txt_correo_per').val('').removeClass('is-invalid is-valid');
        $('#err_cedula_per').text('');
        $('#err_correo_per').text('');
        $('#modal_nuevo_persona').modal('show');
    }

    /* ── Validación ────────────────────────────────── */
    function validar_campos_per() {
        var ok = true;
        var ced = $.trim($('#txt_cedula_per').val());
        var email = $.trim($('#txt_correo_per').val());
        var reEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        // Cédula
        if (!ced) {
            $('#txt_cedula_per').addClass('is-invalid').removeClass('is-valid');
            $('#err_cedula_per').text('Ingrese el número de cédula.');
            ok = false;
        } else if (ced.length !== 10) {
            $('#txt_cedula_per').addClass('is-invalid').removeClass('is-valid');
            $('#err_cedula_per').text('La cédula debe tener 10 dígitos.');
            ok = false;
        } else {
            $('#txt_cedula_per').removeClass('is-invalid').addClass('is-valid');
            $('#err_cedula_per').text('');
        }

        // Correo
        if (!email) {
            $('#txt_correo_per').addClass('is-invalid').removeClass('is-valid');
            $('#err_correo_per').text('Ingrese el correo electrónico.');
            ok = false;
        } else if (!reEmail.test(email)) {
            $('#txt_correo_per').addClass('is-invalid').removeClass('is-valid');
            $('#err_correo_per').text('El formato del correo no es válido.');
            ok = false;
        } else {
            $('#txt_correo_per').removeClass('is-invalid').addClass('is-valid');
            $('#err_correo_per').text('');
        }

        return ok;
    }

    /* ── Validación cédula en tiempo real ──────────── */
    $(document).on('input', '#txt_cedula_per', function() {
        var val = $(this).val().trim();
        var $err = $('#err_cedula_per');

        $(this).removeClass('is-invalid is-valid');
        $err.text('');

        if (val.length === 10) {
            $.ajax({
                url: '../controlador/TALENTO_HUMANO/th_personasC.php?validar_cedula_duplicada_persona=true',
                type: 'POST',
                dataType: 'json',
                data: {
                    cedula: val
                },
                success: function(res) {
                    if (res.duplicada) {
                        $('#txt_cedula_per').addClass('is-invalid').removeClass('is-valid');
                        $err.text('Esta cédula ya está registrada en el sistema.');
                    } else {
                        $('#txt_cedula_per').addClass('is-valid').removeClass('is-invalid');
                        $err.text('');
                    }
                }
            });
        }
    });

    /* ── Validación correo en tiempo real ──────────── */
    $(document).on('input', '#txt_correo_per', function() {
        if (/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test($.trim($(this).val()))) {
            $(this).removeClass('is-invalid').addClass('is-valid');
            $('#err_correo_per').text('');
        }
    });

   
    function guardar_e_invitar_persona() {
        if (!validar_campos_per()) return;

        var cedula = $.trim($('#txt_cedula_per').val());
        var correo = $.trim($('#txt_correo_per').val());

        Swal.fire({
            title: 'Registrando...',
            text: 'Guardando persona.',
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: function() {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_personasC.php?insertar_persona=true',
            type: 'POST',
            dataType: 'json',
            data: {
                parametros: {
                    cedula: cedula,
                    correo: correo,
                    per_id: '<?= $id_persona ?>',
                }
            },
            success: function(res) {

                if (res == -2) {
                    Swal.close();
                    $('#txt_cedula_per').addClass('is-invalid').removeClass('is-valid');
                    $('#err_cedula_per').text('Esta cédula ya está registrada.');
                    return;
                }

                if (res == -3) {
                    Swal.close();
                    $('#txt_correo_per').addClass('is-invalid').removeClass('is-valid');
                    $('#err_correo_per').text('Este correo ya está registrado.');
                    return;
                }

                if (res == -4) {
                    Swal.close();
                    $('#txt_cedula_per').addClass('is-invalid').removeClass('is-valid');
                    $('#err_cedula_per').text('Esta cédula ya está registrada.');
                    $('#txt_correo_per').addClass('is-invalid').removeClass('is-valid');
                    $('#err_correo_per').text('Este correo ya está registrado.');
                    return;
                }

                // Solo ocultar si fue exitoso
                $('#modal_nuevo_persona').modal('hide');

                Swal.update({
                    title: 'Enviando correo...',
                    text: 'Enviando credenciales a la persona.'
                });

                $.ajax({
                    url: '../controlador/TALENTO_HUMANO/th_logs_correosC.php?enviar_correo=true',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        parametros: {
                            per_id: res,
                            enviar_credenciales: 1,
                            asunto: '',
                            descripcion: '',
                            personas: 'visitantes',
                        }
                    },
                    success: function(resMail) {
                        if (resMail.error) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Persona registrada',
                                html: 'La persona fue guardada, pero ocurrió un error al enviar el correo:<br><small class="text-danger">' + resMail.error + '</small>',
                                confirmButtonColor: '#f0ad4e'
                            });
                            return;
                        }

                        Swal.fire({
                            icon: 'success',
                            title: '¡Listo!',
                            html: 'Persona registrada y credenciales enviadas a:<br><strong>' + correo + '</strong>',
                            confirmButtonColor: '#0d6efd'
                        });
                        cargar_datos_persona('<?= $id_persona ?>');
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Persona registrada',
                            text: 'Guardado correctamente, pero falló el envío del correo: ' + error,
                            confirmButtonColor: '#f0ad4e'
                        });
                    }
                });
            },
            error: function(xhr, status, error) {
                Swal.fire('Error', 'Error en la conexión: ' + error, 'error');
            }
        });
    }
</script>

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
            errorPlacement: function(error, element) {
                if (element.hasClass("select2-hidden-accessible")) {
                    // Si es Select2, busca el contenedor y coloca el error después
                    error.insertAfter(element.next(".select2-container"));
                } else if (element.parent(".input-group").length) {
                    // Si el input tiene un botón (como el de Código Postal), pon el error al final
                    error.insertAfter(element.parent());
                } else {
                    // Comportamiento normal para el resto
                    error.insertAfter(element);
                }
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

<!-- Para los navs del menu -->
<link rel="stylesheet" href="../assets/css/css-navs-menus.css">