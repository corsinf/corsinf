<?php

$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']) ?? '';

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

<?php if (
    $_SESSION['INICIO']['TIPO'] == 'DBA' ||
    $_SESSION['INICIO']['TIPO'] == 'ADMINISTRADOR'
) {
} else { ?>
    <script>
        // Cargar notificaciones automáticamente al cargar la página
        $(document).ready(function() {
            notificacionesAsistencia();

            // Opcional: Recargar cada 5 minutos
            setInterval(notificacionesAsistencia, 300000);
        });

        function redireccionar(url_redireccion) {
            url_click = "inicio.php?mod=<?= $modulo_sistema ?>&acc=" + url_redireccion;
            window.location.href = url_click;
        }

        function notificacionesAsistencia() {

            $.ajax({
                url: '../controlador/TALENTO_HUMANO/th_indexC.php?notificaciones_asistencia=true',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    console.log('Status: ' + status);
                    console.log('Error: ' + error);
                    console.log('XHR Response: ' + xhr.responseText);
                    Swal.fire('', 'Error: ' + xhr.responseText, 'error');
                }
            });

        }

        function mostrarNotificacionAsistencia(mensajes) {
            const $container = $('#notificationContainer');

            // Limpiar notificaciones anteriores
            $container.empty();

            if (mensajes.length === 1) {
                // Sin horario asignado
                mostrarAlertaSinHorario(mensajes[0]);
            } else if (mensajes.length >= 2) {
                // Notificaciones de asistencia
                mostrarAlertasAsistencia(mensajes);
            }

            // Actualizar contador
            $('#alertCount').text(mensajes.length);
        }

        function mostrarAlertaSinHorario(mensaje) {
            const alertHtml = `
                <div class="alert alert-danger alert-compact border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-start">
                        <div class="alert-icon bg-danger bg-opacity-25">
                            <i class="fas fa-exclamation-triangle text-danger"></i>
                        </div>
                        <div class="alert-content">
                            <div class="alert-title text-danger">Sin Horario Asignado</div>
                            <div class="alert-message">${mensaje}</div>
                            <div class="alert-time">Ahora</div>
                            <div class="alert-actions">
                                <button class="btn btn-outline-danger btn-compact" onclick="contactarRRHH()">
                                    <i class="fas fa-phone me-1"></i>RRHH
                                </button>
                                <button class="btn btn-outline-secondary btn-compact" onclick="cerrarNotificacion(this)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            $('#notificationContainer').html(alertHtml);
        }

        function mostrarAlertasAsistencia(mensajes) {
            const $container = $('#notificationContainer');
            let alertsHtml = '<div class="row g-3">';

            $.each(mensajes, function(index, mensaje) {
                const esEntrada = mensaje.toLowerCase().includes('entrada') || mensaje.toLowerCase().includes('desde las');
                const esSalida = mensaje.toLowerCase().includes('salida') || mensaje.toLowerCase().includes('falta tiempo');

                let borderClass = 'border-warning';
                let bgClass = 'bg-warning';
                let textClass = 'text-warning';
                let iconClass = 'fa-clock';
                let titulo = 'Notificación de Horario';
                let timeAgo = '';
                let badgeClass = 'bg-warning';

                if (esEntrada) {
                    borderClass = 'border-danger';
                    bgClass = 'bg-danger';
                    textClass = 'text-danger';
                    iconClass = 'fa-sign-in-alt';
                    titulo = 'Entrada Tardía';
                    timeAgo = 'Hace 5h 38m';
                    badgeClass = 'bg-danger';
                } else if (esSalida) {
                    borderClass = 'border-info';
                    bgClass = 'bg-info';
                    textClass = 'text-info';
                    iconClass = 'fa-sign-out-alt';
                    titulo = 'Próxima Salida';
                    timeAgo = 'En 1h 52m';
                    badgeClass = 'bg-info';
                }

                alertsHtml += `
            <div class="col-md-6">
                <div class="card h-400 ${borderClass} border-start border-4 shadow-sm">
                <div class="card-body p-3">
                    <!-- Header con icono y título -->
                    <div class="d-flex align-items-center mb-2">
                        <div class="me-3">
                            <div class="rounded-circle ${bgClass} bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas ${iconClass} ${textClass} fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-1 ${textClass} fw-bold">${titulo}</h6>
                            <span class="badge ${badgeClass} bg-opacity-75 text-white small">${timeAgo}</span>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary border-0" onclick="cerrarNotificacion(this)" title="Cerrar">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <!-- Mensaje principal -->
                    <div class="mb-3">
                        <p class="card-text text-dark mb-0 fw-medium">${mensaje}</p>
                    </div>
                    
                    <!-- Botones de acción -->
                    <div class="d-flex gap-2 flex-wrap">
                        ${esEntrada ? `
                            <button class="btn btn-danger btn-sm flex-fill" onclick="marcarAsistencia()">
                                <i class="fas fa-fingerprint me-1"></i>
                                <span class="d-none d-sm-inline">Marcar</span>
                            </button>
                            <button class="btn btn-outline-secondary btn-sm flex-fill" onclick="justificarRetraso()">
                                <i class="fas fa-edit me-1"></i>
                                <span class="d-none d-sm-inline">Justificar</span>
                            </button>
                        ` : esSalida ? `
                            <button class="btn btn-info btn-sm flex-fill" onclick="recordarSalida()">
                                <i class="fas fa-bell me-1"></i>
                                <span class="d-none d-sm-inline">Recordar</span>
                            </button>
                        ` : ''}
                    </div>
                </div>
                </div>
            </div>
        `;
            });

            alertsHtml += '</div>';
            $container.html(alertsHtml);
        }

        function mostrarNotificacionExito() {
            const alertHtml = `
                <div class="alert alert-success alert-compact border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-start">
                        <div class="alert-icon bg-success bg-opacity-25">
                            <i class="fas fa-check text-success"></i>
                        </div>
                        <div class="alert-content">
                            <div class="alert-title text-success">¡Todo en Orden!</div>
                            <div class="alert-message">Tu asistencia está al día.</div>
                            <div class="alert-time">Actualizado</div>
                        </div>
                    </div>
                </div>
            `;

            $('#notificationContainer').html(alertHtml);
            $('#alertCount').text('0');
        }

        // Funciones de acciones (mantienen la misma funcionalidad)
        function marcarAsistencia() {
            Swal.fire({
                title: 'Marcar Asistencia',
                text: '¿Deseas marcar tu asistencia ahora?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-fingerprint me-1"></i>Marcar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('¡Marcado!', 'Tu asistencia ha sido registrada.', 'success');
                }
            });
        }

        function justificarRetraso() {
            Swal.fire({
                title: 'Justificar Retraso',
                input: 'textarea',
                inputLabel: 'Motivo del retraso',
                inputPlaceholder: 'Describe el motivo de tu retraso...',
                showCancelButton: true,
                confirmButtonText: 'Enviar Justificación',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    Swal.fire('¡Enviado!', 'Tu justificación ha sido registrada.', 'success');
                }
            });
        }

        function contactarRRHH() {
            Swal.fire({
                title: 'Contactar RRHH',
                html: `
                    <div class="text-start">
                        <p><strong>Recursos Humanos</strong></p>
                        <p><i class="fas fa-phone text-primary me-2"></i>+593 99 123 4567</p>
                        <p><i class="fas fa-envelope text-primary me-2"></i>rrhh@empresa.com</p>
                        <p><i class="fas fa-clock text-primary me-2"></i>Horario: 8:00 AM - 5:00 PM</p>
                    </div>
                `,
                icon: 'info',
                confirmButtonText: 'Entendido'
            });
        }

        function recordarSalida() {
            Swal.fire({
                title: 'Recordatorio Configurado',
                text: 'Te notificaremos 10 minutos antes de tu hora de salida.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        }

        function cerrarNotificacion(elemento) {
            const $alert = $(elemento).closest('.alert');
            $alert.css({
                'transition': 'opacity 0.3s ease',
                'opacity': '0'
            });

            setTimeout(function() {
                $alert.remove();
                // Actualizar contador
                const remainingAlerts = $('#notificationContainer .alert').length;
                $('#alertCount').text(Math.max(0, remainingAlerts));
            }, 300);
        }

        // Función principal que conecta con tu AJAX
        function notificacionesAsistencia() {
            $.ajax({
                url: '../controlador/TALENTO_HUMANO/th_indexC.php?notificaciones_asistencia=true',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    mostrarNotificacionAsistencia(response);
                },
                error: function(xhr, status, error) {
                    console.log('Status: ' + status);
                    console.log('Error: ' + error);
                    console.log('XHR Response: ' + xhr.responseText);

                    const alertHtml = `
                        <div class="alert alert-danger alert-compact border-0 shadow-sm" role="alert">
                            <div class="d-flex align-items-start">
                                <div class="alert-icon bg-danger bg-opacity-25">
                                    <i class="fas fa-exclamation-circle text-danger"></i>
                                </div>
                                <div class="alert-content">
                                    <div class="alert-title text-danger">Error de Conexión</div>
                                    <div class="alert-message">No se pudo obtener la información de asistencia.</div>
                                    <div class="alert-time">Ahora</div>
                                    <div class="alert-actions">
                                        <button class="btn btn-outline-danger btn-compact" onclick="cerrarNotificacion(this)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    $('#notificationContainer').html(alertHtml);
                }
            });
        }
    </script>

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
                    $('#lbl_nombre').text(response[0].nombre + " " + response[0].apellido);

                    if (response[0].foto != '') {
                        $('#img_perfil').attr("src", response[0].foto + '?' + Math.random())
                    }
                }
            });
        }
    </script>
<?php } ?>


<?php if (
    $_SESSION['INICIO']['TIPO'] == 'DBA' ||
    $_SESSION['INICIO']['TIPO'] == 'ADMINISTRADOR'
) {
} else { ?>

    <div class="row">
        <div class="col-12">
            <h6 class="mb-0 text-uppercase">Información Personal</h6>
            <hr>

            <div class="row row-cols-1 row-cols-lg-2">
                <div class="col">
                    <div class="card radius-15 card-user-profile shadow-sm border-0">
                        <div class="card-body text-center p-4">
                            <div class="position-relative d-inline-block mb-3">
                                <img src="../img/sin_imagen.jpg" id="img_perfil" width="115" height="115" class="rounded-circle p-1 border " alt="img_perfil">
                            </div>

                            <h5 class="mb-1 fw-bold" id="lbl_nombre">---</h5>
                            <p class="text-muted mb-3" id="lbl_ci">---</p>

                            <div class="d-grid">
                                <button onclick="confirmar_terminos_datos('<?= $link_edicion ?>')" class="btn btn-primary radius-15 px-4">
                                    <i class="bx bx-edit-alt me-1"></i>Editar Perfil
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="col-12 mb-4">
        <div class="alerts-sidebar">
            <h6 class="mb-0 text-uppercase">Notificaciones de Asistencia</h6>
            <hr>
            <div class="px-3" id="notificationContainer"> -->
                <!-- Las alertas aparecerán aquí automáticamente -->
            <!-- </div>
        </div>
    </div> -->




<?php } ?>