<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$NO_CONCURENTE_TABLA = $_SESSION['INICIO']['NO_CONCURENTE_TABLA'];
$NO_CONCURENTE_CAMPO_ID = $_SESSION['INICIO']['NO_CONCURENTE'];

$link_edicion = "#";
if ($NO_CONCURENTE_TABLA == "_talentoh.th_personas") {
    $link_edicion = "../vista/inicio.php?mod=$modulo_sistema&acc=th_registrar_personas&id_persona=$NO_CONCURENTE_CAMPO_ID&id_postulante=postulante&_origen=nomina&_persona_nomina=true";
} else if ($NO_CONCURENTE_TABLA == "_talentoh.th_postulantes") {
    $link_edicion = "../vista/inicio.php?mod=" . $modulo_sistema . "&acc=th_informacion_personal&id_postulante=" . $NO_CONCURENTE_CAMPO_ID;
}

?>

<script src="../js/GENERAL/operaciones_generales.js"></script>

<script>
    $(document).ready(function() {
        cargarDatos('<?= $_SESSION['INICIO']['ID_USUARIO']; ?>');
    });

    function cargarDatos(id) {

        var parametros = {
            'id': id,
            'query': '',
        }

        // console.log(parametros);
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/usuariosC.php?datos_usuarios=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                // alert('Función cargarDatos deshabilitada temporalmente.');  

                $('#lbl_ci').text(response[0].ci);
                $('#lbl_nombre').text(response[0].nombre);
                $('#lbl_nombre2').text(response[0].nombre2);
                $('#lbl_apellido').text(response[0].apellido);
                $('#lbl_apellido2').text(response[0].apellido2);
                $('#lbl_email').text(response[0].email);
                $('#lbl_fecha').text(response[0].fechaN);
                $('#lbl_edad_valor').text(calcular_edad_fecha(response[0].fechaN) + ' años');
                if (response[0].sexo == "Masculino") {
                    $('#lbl_sexo').html('<span class="badge bg-light-primary text-primary px-3">Masculino</span>');
                } else {
                    $('#lbl_sexo').html('<span class="badge bg-light-danger text-danger px-3">Femenino</span>');
                }

                if (response[0].foto != '') {
                    $('#img_perfil').attr("src", response[0].foto + '?' + Math.random())
                }

                $('#txt_usuario').val(response[0].usu);

            }
        });
    }

    function guardar_credencial() {

        cbx_mantener = $('#cbx_mantener').is(':checked') ? 1 : 0;
        cbx_terminos = $('#cbx_terminos').is(':checked') ? 1 : 0;
        pass = $('#txt_pass').val();
        pass_2 = $('#txt_pass_repeat').val();
        terminos_back = '<?= $_SESSION['INICIO']['NO_CONCURENTE_POLITICAS']; ?>';

        console.log(terminos_back);

        if (cbx_terminos == 0 && terminos_back == 0) {
            Swal.fire({
                title: 'Requisito Necesario',
                text: 'Para continuar, es necesario leer y aceptar los Términos y Condiciones de uso.',
                icon: 'warning',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#ffc107', // Color acorde a tu alerta anterior
            });
            return false;
        }

        if (pass !== pass_2 || pass === "" && cbx_mantener == 0) {
            Swal.fire("Las contraseñas deben coincidir y no estar vacías.", "", "warning");
            return;
        }

        parametros = {
            'id': $('#txt_id').val(),
            'tabla': $('#txt_tabla').val(),
            'usuario': $('#txt_usuario').val(),
            'pass': pass,
            'politicas': cbx_terminos,
            'cambio_clave': cbx_mantener,
        }

        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/usuariosC.php?guardar_credencial=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire("Datos guardados", "", "success");
                    cargarDatos($('#txt_id').val())
                    window.location.reload();
                } else if (response == '-2') {
                    Swal.fire("Usuario no concurrente no asignado", "Consulte con su administrador", "error");
                } else {
                    Swal.fire("No se pudo guardar los datos", "Consulte con su administrador", "error");
                }

            }
        });
    }
</script>



<div class="page-wrapper">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-4">
            <div class="breadcrumb-title pe-3 border-0 fw-bold text-dark">Información Personal</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0 bg-transparent">
                        <li class="breadcrumb-item"><a href="javascript:;" class="text-primary"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Perfil de Usuario</li>
                    </ol>
                </nav>
            </div>
        </div>

        <?php if ($_SESSION['INICIO']['NO_CONCURENTE_POLITICAS'] == 0) { ?>
            <div class="alert alert-custom shadow-sm border-0 fade show" role="alert" style="background: rgba(255, 255, 255, 0.95); border-left: 5px solid #ffc107 !important;">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="bx bxs-info-circle fs-3" style="color: #ffc107;"></i>
                    </div>

                    <div class="ms-3">
                        <h6 class="alert-heading mb-1 fw-bold" style="color: #333;">Acceso Restringido</h6>
                        <p class="mb-2 small" style="color: #555; line-height: 1.4;">
                            Para garantizar la seguridad de tu información y cumplir con la normativa vigente, <strong>es obligatorio</strong> revisar y aceptar los Términos y Condiciones antes de continuar.
                        </p>

                        <div class="d-inline-block p-2 rounded" style="background-color: rgba(255, 193, 7, 0.1); border: 1px dashed #ffc107;">
                            <p class="mb-0 small fw-bold">
                                <i class="bx bx-pointer bx-xs me-1"></i>
                                Haz clic aquí para
                                <a href="../vista/inicio.php?acc=politicas_datos" target="_blank" class="text-decoration-underline link-highlight" style="color: #d39e00; transition: 0.3s;">
                                    abrir las Políticas de Privacidad y Seguridad
                                </a>
                            </p>
                        </div>
                    </div>

                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.7rem;"></button>
                </div>
            </div>

            <style>
                /* Efecto extra para que el enlace llame la atención al pasar el mouse */
                .link-highlight:hover {
                    color: #000 !important;
                    background-color: #ffc107;
                    text-decoration: none !important;
                    padding: 2px 4px;
                    border-radius: 4px;
                }
            </style>

        <?php } else { ?>
            <div class="alert alert-custom shadow-sm border-0 fade show" role="alert" style="background: #f8f9fa; border-left: 5px solid #0dcaf0 !important;">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="bx bx-check-shield fs-3" style="color: #0dcaf0;"></i>
                    </div>

                    <div class="ms-3">
                        <h6 class="alert-heading mb-1 fw-bold" style="color: #333;">Registro Completado</h6>
                        <p class="mb-0 small" style="color: #666; line-height: 1.4;">
                            Has aceptado nuestras normativas correctamente. Recuerda que, para tu tranquilidad,
                            <strong>siempre puedes consultar</strong> los
                            <a href="../vista/inicio.php?acc=politicas_datos" target="_blank" class="fw-bold text-decoration-none" style="color: #0dcaf0; border-bottom: 1px dashed #0dcaf0;">
                                Términos y Políticas de Privacidad
                            </a>
                            desde este enlace o en la configuración de tu perfil.
                        </p>
                    </div>

                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.7rem;"></button>
                </div>
            </div>
        <?php }  ?>


        <div class="row">

            <div class="col-lg-8" id="pnl_datos_informativos" style="display: none;">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-0">
                        <div class="d-flex align-items-center">
                            <div class="bg-light-primary text-primary rounded-2 p-1 pt-0 pb-0 me-2">
                                <i class="bx bx-user-circle fs-3"></i>
                            </div>

                            <h5 class="mb-0 fw-bold text-dark">Datos del Perfil</h5>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="info-section">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="info-item p-3 rounded-3 mb-2">
                                        <label id="lbl_titulo_ci" class="text-muted small fw-bold text-uppercase d-block mb-1">
                                            <i class="bx bxs-id-card me-1 text-primary"></i> Cédula
                                        </label>
                                        <input type="hidden" id="txt_id" value="0">
                                        <input type="hidden" id="txt_tabla">
                                        <span class="fw-bold text-dark fs-6 view-mode" id="lbl_ci">0000000000</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item p-3 rounded-3 mb-2">
                                        <label id="lbl_titulo_email" class="text-muted small fw-bold text-uppercase d-block mb-1">
                                            <i class="bx bx-envelope me-1 text-primary"></i> Email
                                        </label>
                                        <span class="fw-bold text-dark fs-6 view-mode" id="lbl_email">---</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item p-3 rounded-3 mb-2">
                                        <label id="lbl_titulo_nombre" class="text-muted small fw-bold text-uppercase d-block mb-1">Primer Nombre</label>
                                        <span class="fw-bold text-dark fs-6 view-mode" id="lbl_nombre">---</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item p-3 rounded-3 mb-2">
                                        <label id="lbl_titulo_nombre2" class="text-muted small fw-bold text-uppercase d-block mb-1">Segundo Nombre</label>
                                        <span class="fw-bold text-dark fs-6 view-mode" id="lbl_nombre2">---</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item p-3 rounded-3 mb-2">
                                        <label id="lbl_titulo_apellido" class="text-muted small fw-bold text-uppercase d-block mb-1">Apellido Paterno</label>
                                        <span class="fw-bold text-dark fs-6 view-mode" id="lbl_apellido">---</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item p-3 rounded-3 mb-2">
                                        <label id="lbl_titulo_apellido2" class="text-muted small fw-bold text-uppercase d-block mb-1">Apellido Materno</label>
                                        <span class="fw-bold text-dark fs-6 view-mode" id="lbl_apellido2">---</span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="info-item p-3 rounded-3 mb-2">
                                        <label id="lbl_titulo_sexo" class="text-muted small fw-bold text-uppercase d-block mb-1">Sexo</label>
                                        <span class="fw-bold text-dark fs-6 view-mode" id="lbl_sexo">---</span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="info-item p-3 rounded-3 mb-2">
                                        <label id="lbl_titulo_fecha" class="text-muted small fw-bold text-uppercase d-block mb-1 text-primary">Fecha Nacimiento</label>
                                        <span class="fw-bold text-dark fs-6 view-mode" id="lbl_fecha">---</span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="info-item p-3 rounded-3 mb-2 bg-light-primary border-0">
                                        <label id="lbl_titulo_edad" class="text-primary small fw-bold text-uppercase d-block mb-1">Edad Actual</label>
                                        <span class="badge bg-primary rounded-pill" id="lbl_edad_valor">0 años</span>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end mt-4 pt-3 border-top">
                                <button onclick="confirmar_terminos_datos('<?= $link_edicion ?>')" class="btn btn-outline-primary px-4">
                                    <i class="bx bx-edit-alt me-1"></i>Editar Perfil
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">

                <div class="card border-0 shadow-sm rounded-4 mb-4 text-center p-4" id="pnl_foto_perfil" style="display: none;">
                    <div class="profile-photo-section">
                        <div class="position-relative d-inline-block">
                            <img id="img_perfil" src="../img/sin_imagen.jpg" alt="Perfil"
                                class="rounded-circle shadow border border-5 border-white"
                                style="width: 180px; height: 180px; object-fit: cover;">
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-2 overflow-hidden bg-security-card">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-white bg-opacity-25 rounded-2 p-1 pt-0 pb-0 me-2">
                                <i class="bx bx-lock-alt fs-4"></i>
                            </div>
                            <h6 class="mb-0 fw-bold text-white">Gestión de Seguridad </h6>

                            <small class="text-white opacity-50" style="font-size: 0.7rem;"> Actualiza tus credenciales y preferencias</small>
                        </div>

                        <div class="mb-3">
                            <label id="lbl_titulo_usuario" class="form-label mb-1 small opacity-75 text-white">Nombre de Usuario</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white bg-opacity-10 border-0 text-white">
                                    <i class="bx bx-user"></i>
                                </span>
                                <input type="text" id="txt_usuario" readonly class="form-control bg-white bg-opacity-25 border-0 text-white">
                            </div>
                        </div>

                        <div id="wrapper_passwords">
                            <div class="mb-3">
                                <label class="form-label mb-1 small opacity-75 text-white">Nueva Contraseña</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white bg-opacity-10 border-0 text-white">
                                        <i class="bx bx-key"></i>
                                    </span>
                                    <input type="password" id="txt_pass" class="form-control bg-white bg-opacity-25 border-0 text-white" placeholder="••••••••">
                                    <button type="button" class="btn btn-white bg-white bg-opacity-10 border-0 text-white btn-toggle-pass" data-target="txt_pass" tabindex="-1">
                                        <i class="bx bx-show"></i>
                                    </button>
                                </div>
                                <div class="progress mt-2" style="height: 4px; background: rgba(255,255,255,0.1);">
                                    <div id="strength_bar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                                </div>
                                <small id="lbl_strength_text" class="text-white opacity-50" style="font-size: 0.7rem;">Seguridad: Muy débil</small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label mb-1 small opacity-75 text-white">Repetir Contraseña</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white bg-opacity-10 border-0 text-white">
                                        <i class="bx bx-check-shield"></i>
                                    </span>
                                    <input type="password" id="txt_pass_repeat" class="form-control bg-white bg-opacity-25 border-0 text-white" placeholder="••••••••">
                                    <button type="button" class="btn btn-white bg-white bg-opacity-10 border-0 text-white btn-toggle-pass" data-target="txt_pass_repeat" tabindex="-1">
                                        <i class="bx bx-show"></i>
                                    </button>
                                </div>
                                <small id="match_text" class="text-white opacity-50 d-none" style="font-size: 0.7rem;"></small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check mb-2">
                                <input class="form-check-input bg-white bg-opacity-25 border-0" type="checkbox" id="cbx_mantener">
                                <label class="form-check-label small text-white opacity-75" for="cbx_mantener">
                                    Mantener contraseña actual
                                </label>
                            </div>

                            <?php if ($_SESSION['INICIO']['NO_CONCURENTE_POLITICAS'] == 0) { ?>
                                <div class="form-check">
                                    <input class="form-check-input bg-white bg-opacity-25 border-0" type="checkbox" id="cbx_terminos">
                                    <label class="form-check-label small text-white opacity-75" for="cbx_terminos">
                                        Acepto los <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=politicas_datos" target="_blank" class="text-white text-decoration-underline fw-bold">Términos y Condiciones</a>
                                    </label>
                                </div>
                            <?php } ?>


                        </div>

                        <button type="button" class="btn btn-white w-100 fw-bold" onclick="guardar_credencial()">
                            Guardar Cambios
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        // Control de visibilidad de los inputs de contraseña
        $('#cbx_mantener').on('change', function() {
            const contenedor = $('#wrapper_passwords');

            if ($(this).is(':checked')) {
                // Si está marcado, ocultamos con animación
                contenedor.slideUp(300, function() {
                    // Opcional: Limpiar los campos cuando se ocultan
                    $('#txt_pass, #txt_pass_repeat').val('');
                    $('#strength_bar').css('width', '0%');
                    $('#match_text').addClass('d-none');
                });
            } else {
                // Si se desmarca, mostramos los campos
                contenedor.slideDown(300);
            }
        });

        // Reutilizamos la lógica de ver contraseña
        $('.btn-toggle-pass').on('click', function() {
            const targetId = $(this).data('target');
            const input = $('#' + targetId);
            const icon = $(this).find('i');
            const isPass = input.attr('type') === 'password';

            input.attr('type', isPass ? 'text' : 'password');
            icon.toggleClass('bx-show bx-hide');
        });

        // Validar fuerza de la contraseña
        $('#txt_pass').on('input', function() {
            const pass = $(this).val();
            let strength = 0;
            let color = "#ff4d4d";
            let text = "Muy débil";

            if (pass.length >= 8) strength += 25; // Longitud mínima recomendada
            if (pass.match(/[A-Z]/)) strength += 25; // Mayúsculas
            if (pass.match(/[0-9]/)) strength += 25; // Números
            if (pass.match(/[^A-Za-z0-9]/)) strength += 25; // Caracteres especiales

            // Ajuste de UI según fuerza
            if (strength >= 50) {
                color = "#ffd11a";
                text = "Media";
            }
            if (strength >= 75) {
                color = "#4db8ff";
                text = "Fuerte";
            }
            if (strength === 100) {
                color = "#2eb82e";
                text = "Muy Segura";
            }

            if (pass.length === 0) strength = 0;

            $('#strength_bar').css({
                'width': strength + '%',
                'background-color': color
            });
            $('#lbl_strength_text').text("Seguridad: " + text).css('color', color);

            // Validar coincidencia también al cambiar el principal
            validar_coincidencia();
        });

        // 3. Validar coincidencia (Input Repetir)
        $('#txt_pass_repeat').on('input', function() {
            validar_coincidencia();
        });

        function validar_coincidencia() {
            const p1 = $('#txt_pass').val();
            const p2 = $('#txt_pass_repeat').val();
            const feedback = $('#match_text');

            if (p2.length > 0) {
                feedback.removeClass('d-none');
                if (p1 === p2) {
                    feedback.text("✓ Las contraseñas coinciden").css('color', '#2eb82e').addClass('opacity-100');
                } else {
                    feedback.text("✗ No coinciden").css('color', '#ff4d4d').addClass('opacity-100');
                }
            } else {
                feedback.addClass('d-none');
            }
        }
    });
</script>

<?php if ($_SESSION['INICIO']['NO_CONCURENTE_POLITICAS'] == 1) { ?>
    <script>
        $(document).ready(function() {
            $('#pnl_datos_informativos').show();
            $('#pnl_foto_perfil').show();
        });
    </script>
<?php } ?>

<style>
    .bg-light-primary {
        background-color: rgba(13, 110, 253, 0.08);
    }

    .bg-security-card {
        background: linear-gradient(135deg, #35A6DF 0%, #004a77 100%);
        box-shadow: 0 10px 20px rgba(53, 166, 223, 0.2);
        /* Sombra sutil del mismo color */
    }

    .info-item {
        background-color: #fcfcfd;
        border: 1px solid #f0f2f5;
        transition: all 0.3s ease;
    }

    .info-item:hover {
        background-color: #ffffff !important;
        border-color: #0d6efd;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transform: translateY(-2px);
    }

    #img_perfil {
        transition: transform 0.4s ease;
    }

    .profile-photo-section:hover #img_perfil {
        transform: scale(1.02);
    }

    .page-content {
        animation: slideUp 0.6s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>