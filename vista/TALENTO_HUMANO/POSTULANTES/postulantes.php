<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);


?>

<script src="../js/GENERAL/operaciones_generales.js"></script>
<script>
    $(document).ready(function() {
        $('#tabla_postulantes').DataTable({
            language: {
                url: '../assets/plugins/datatable/spanish.json'
            },
            responsive: false,
            ajax: {
                url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_postulantesC.php?listar_todo=true',
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    render: function(data, type, item) {
                        href =
                            `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_informacion_personal&id_postulante=${item._id}`;

                        btns =
                            `<a href="${href}" class="btn btn-xs btn-primary" title="CV Postulante"><i class="bx bxs-user-pin fs-6 me-0"></i></a>`;

                        if (item.th_pos_contratado == 0) {
                            btns +=
                                ` <button onclick="agregar_postulante_persona('${item.th_pos_cedula}');" class="btn btn-xs btn-success disabled" title="Agregar a Personas"><i class="bx bx-user-plus fs-6 me-0"></i></button>`;
                        }

                        return btns;
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        href =
                            `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_postulantes&id_postulante=${item._id}`;
                        return `<a title="Editar a Postulante" href="${href}"><u> ${item.th_pos_primer_apellido} ${item.th_pos_segundo_apellido} ${item.th_pos_primer_nombre} ${item.th_pos_segundo_nombre}</u></a>`;
                    }
                },
                {
                    data: 'th_pos_cedula'
                },
                {
                    data: 'th_pos_correo'
                },
                {
                    data: 'th_pos_telefono_1'
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        fecha_nacimiento = item.th_pos_fecha_nacimiento;

                        salida = fecha_nacimiento ? calcular_edad_fecha(item
                            .th_pos_fecha_nacimiento) : '';

                        return salida;
                    }
                },
            ],
            order: [
                [0, 'asc']
            ],
        });
    });

    function agregar_postulante_persona(pos_cedula) {
        // botón que disparó la acción (si lo tienes). Si no, comentarlo.
        const $btn = $('#btnAgregarPostulante'); // ajusta selector si hace falta
        const originalHtml = $btn.length ? $btn.html() : null;

        if ($btn.length) {
            $btn.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...');
        }

        $.ajax({
            data: {
                pos_cedula: pos_cedula
            },
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_postulantesC.php?agregar_postulante_persona=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                // Si tu backend devuelve error en formato { error: "texto" } o { ok:false, msg:... }
                if (!response) {
                    mostrarMensaje('error', 'Respuesta vacía del servidor');
                    return;
                }

                if (response.error) {
                    mostrarMensaje('error', response.error);
                } else if (response.ok === false) {
                    // tu función devolvía { ok:false, msg: '...' }
                    mostrarMensaje('warning', response.msg || 'No se pudo insertar la persona');
                } else if (response.ok === true) {
                    // éxito
                    const msg = response.msg || 'Persona creada correctamente';
                    mostrarMensaje('success', msg);
                    $('#tabla_postulantes').DataTable().ajax.reload(null, false);

                } else {
                    // caso general: response tiene formato distinto
                    mostrarMensaje('info', JSON.stringify(response));
                }
            },
            error: function(xhr, status, error) {

                mostrarMensaje('error', texto);
                console.error('AJAX error:', status, error, xhr.responseText);
            },
        });
    }

    // Helper para mostrar mensajes con SweetAlert2 si está instalado, si no usa alert()
    function mostrarMensaje(tipo, texto) {
        // tipos: success, error, warning, info
        if (typeof Swal !== 'undefined') {
            let icon = 'info';
            if (tipo === 'success') icon = 'success';
            if (tipo === 'error') icon = 'error';
            if (tipo === 'warning') icon = 'warning';

            Swal.fire({
                icon: icon,
                text: texto,
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false
            });
        } else {
            // fallback muy básico
            if (tipo === 'error') {
                alert('Error: ' + texto);
            } else {
                alert(texto);
            }
        }
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Postulantes</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Postulantes</li>
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
                            <h5 class="mb-0 text-primary">Postulantes</h5>

                            <div class="row mx-1">
                                <div class="col-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_postulantes"
                                        class="btn btn-sm btn-success d-flex align-items-center"><i
                                            class="bx bx-plus me-1"></i><span>Nuevo</span></a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" class="btn btn-sm btn-primary d-flex align-items-center"
                                        onclick="abrir_modal_nuevo_postulante()">
                                        <i class="bx bx-plus me-1"></i><span>Invitar</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="table-responsive">
                            <table class="table table-striped align-middle" id="tabla_postulantes">
                                <thead>
                                    <tr>
                                        <th>Acciones</th>
                                        <th>Nombre</th>
                                        <th>Cédula</th>
                                        <th>Correo</th>
                                        <th>Teléfono</th>
                                        <th>Edad</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_nuevo_postulante" tabindex="-1"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bx bx-user-plus me-2 text-success"></i>
                    Nuevo Postulante
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 py-3">
                <p class="text-muted small mb-3">
                    <i class="bx bx-info-circle me-1"></i>
                    Se registrará el postulante y se enviará automáticamente
                    un correo con sus credenciales de acceso.
                </p>

                <!-- Cédula -->
                <div class="mb-3">
                    <label class="form-label fw-semibold small">
                        Cédula <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="txt_cedula_inv"
                        class="form-control form-control-sm"
                        placeholder="Ej: 1234567890"
                        maxlength="10"
                        oninput="this.value=this.value.replace(/\D/g,'')">
                    <div class="invalid-feedback" id="err_cedula_inv"></div>
                </div>

                <!-- Correo -->
                <div class="mb-1">
                    <label class="form-label fw-semibold small">
                        Correo electrónico <span class="text-danger">*</span>
                    </label>
                    <input type="email" id="txt_correo_inv"
                        class="form-control form-control-sm"
                        placeholder="correo@ejemplo.com">
                    <div class="invalid-feedback" id="err_correo_inv"></div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm"
                    data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success btn-sm"
                    onclick="guardar_e_invitar()">
                    <i class="bx bx-save me-1"></i> Guardar y enviar correo
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    /* ── Abrir modal ───────────────────────────────── */
    function abrir_modal_nuevo_postulante() {
        $('#txt_cedula_inv').val('').removeClass('is-invalid is-valid');
        $('#txt_correo_inv').val('').removeClass('is-invalid is-valid');
        $('#err_cedula_inv').text('');
        $('#err_correo_inv').text('');
        $('#modal_nuevo_postulante').modal('show');
    }

    /* ── Validación ────────────────────────────────── */
    function validar_campos_inv() {
        var ok = true;
        var ced = $.trim($('#txt_cedula_inv').val());
        var email = $.trim($('#txt_correo_inv').val());
        var reEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        // Cédula
        if (!ced) {
            $('#txt_cedula_inv').addClass('is-invalid').removeClass('is-valid');
            $('#err_cedula_inv').text('Ingrese el número de cédula.');
            ok = false;
        } else if (ced.length !== 10) {
            $('#txt_cedula_inv').addClass('is-invalid').removeClass('is-valid');
            $('#err_cedula_inv').text('La cédula debe tener 10 dígitos.');
            ok = false;
        } else {
            $('#txt_cedula_inv').removeClass('is-invalid').addClass('is-valid');
            $('#err_cedula_inv').text('');
        }

        // Correo
        if (!email) {
            $('#txt_correo_inv').addClass('is-invalid').removeClass('is-valid');
            $('#err_correo_inv').text('Ingrese el correo electrónico.');
            ok = false;
        } else if (!reEmail.test(email)) {
            $('#txt_correo_inv').addClass('is-invalid').removeClass('is-valid');
            $('#err_correo_inv').text('El formato del correo no es válido.');
            ok = false;
        } else {
            $('#txt_correo_inv').removeClass('is-invalid').addClass('is-valid');
            $('#err_correo_inv').text('');
        }

        return ok;
    }

    /* ── Validación en tiempo real ─────────────────── */
    $(document).on('input', '#txt_cedula_inv', function() {
        if ($.trim($(this).val()).length === 10) {
            $(this).removeClass('is-invalid').addClass('is-valid');
            $('#err_cedula_inv').text('');
        }
    });
    $(document).on('input', '#txt_correo_inv', function() {
        if (/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test($.trim($(this).val()))) {
            $(this).removeClass('is-invalid').addClass('is-valid');
            $('#err_correo_inv').text('');
        }
    });

    /* ════════════════════════════════════════════════
       PASO 1: Insertar postulante
       PASO 2: Enviar correo con credenciales
       (usa el mismo endpoint que enviar_correo_postulante)
    ════════════════════════════════════════════════ */
    function guardar_e_invitar() {
        if (!validar_campos_inv()) return;

        var cedula = $.trim($('#txt_cedula_inv').val());
        var correo = $.trim($('#txt_correo_inv').val());

        // ── NO ocultar el modal aquí ────────────────────────

        Swal.fire({
            title: 'Registrando...',
            text: 'Guardando postulante.',
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: function() {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_postulantesC.php?insertar_postulante=true',
            type: 'POST',
            dataType: 'json',
            data: {
                parametros: {
                    cedula: cedula,
                    correo: correo
                }
            },
            success: function(res) {

                if (res == -2) {
                    Swal.close();
                    $('#txt_cedula_inv').addClass('is-invalid').removeClass('is-valid');
                    $('#err_cedula_inv').text('Esta cédula ya está registrada.');
                    return;
                }

                if (res == -3) {
                    Swal.close();
                    $('#txt_correo_inv').addClass('is-invalid').removeClass('is-valid');
                    $('#err_correo_inv').text('Este correo ya está registrado.');
                    return;
                }

                if (res == -4) {
                    Swal.close();
                    $('#txt_cedula_inv').addClass('is-invalid').removeClass('is-valid');
                    $('#err_cedula_inv').text('Esta cédula ya está registrada.');
                    $('#txt_correo_inv').addClass('is-invalid').removeClass('is-valid');
                    $('#err_correo_inv').text('Este correo ya está registrado.');
                    return;
                }

                // ── Solo aquí ocultar el modal porque fue exitoso ──
                $('#modal_nuevo_postulante').modal('hide');

                Swal.update({
                    title: 'Enviando correo...',
                    text: 'Enviando credenciales al postulante.'
                });

                $.ajax({
                    url: '../controlador/TALENTO_HUMANO/th_logs_correosC.php?enviar_correo_postulante=true',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        parametros: {
                            pos_id: res,
                            enviar_credenciales: 1,
                            asunto: '',
                            descripcion: ''
                        }
                    },
                    success: function(resMail) {
                        if (resMail.error) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Postulante registrado',
                                html: 'El postulante fue guardado, pero ocurrió un error al enviar el correo:<br><small class="text-danger">' + resMail.error + '</small>',
                                confirmButtonColor: '#f0ad4e'
                            }).then(function() {
                                recargar_tabla();
                            });
                            return;
                        }

                        Swal.fire({
                            icon: 'success',
                            title: '¡Listo!',
                            html: 'Postulante registrado y credenciales enviadas a:<br><strong>' + resMail.correo + '</strong>',
                            confirmButtonColor: '#198754'
                        }).then(function() {
                            recargar_tabla();
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Postulante registrado',
                            text: 'Guardado correctamente, pero falló el envío del correo: ' + error,
                            confirmButtonColor: '#f0ad4e'
                        }).then(function() {
                            recargar_tabla();
                        });
                    }
                });
            },
            error: function(xhr, status, error) {
                Swal.fire('Error', 'Error en la conexión: ' + error, 'error');
            }
        });
    }
    /* ── Recargar tabla si existe ──────────────────── */
    function recargar_tabla() {
        if (typeof cargar_tabla_postulantes === 'function') {
            cargar_tabla_postulantes();
        }
    }
</script>

<style>
    .disabled {
        /* Hace que el enlace no responda al clic */
        pointer-events: none;
        /* Cambia el aspecto visual (grisáceo) */
        opacity: 0.6;
        /* Quita el cursor de mano */
        cursor: not-allowed;
        text-decoration: none;
    }
</style>