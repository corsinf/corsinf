<?php
$modulo_sistema = $_SESSION['INICIO']['MODULO_SISTEMA'];
$_id = $_GET['_id'] ?? '';
?>

<script src="../js/GENERAL/operaciones_generales.js"></script>

<div class="page-wrapper">
    <div class="page-content">

        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="mb-0 fw-bold text-primary">
                <i class="bx bxs-calendar-check me-1"></i> Detalle de Reserva
            </h5>
            <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_reservas"
                class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-arrow-back"></i> Regresar
            </a>
        </div>

        <!-- HEADER -->
        <div class="card border-0 shadow-sm border-start border-primary border-4 mb-4">
            <div class="card-body">
                <div class="row align-items-center g-3">

                    <div class="col-lg-8">
                        <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                            <span id="badge_estado_reserva" class="badge fs-6 bg-secondary"></span>
                            <span id="badge_estado_espacio" class="badge fs-6 bg-warning text-dark"></span>
                        </div>
                        <h4 class="fw-bold mb-1" id="det_nombre_espacio">—</h4>
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            <span class="badge bg-light text-dark border">
                                <i class="bx bx-user me-1"></i><span id="det_persona">—</span>
                            </span>
                            <span class="badge bg-light text-dark border">
                                <i class="bx bx-id-card me-1"></i><span id="det_cedula">—</span>
                            </span>
                            <span class="badge bg-light text-dark border">
                                <i class="bx bx-hash me-1"></i><span id="det_codigo">—</span>
                            </span>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="row g-2 text-center">
                            <div class="col-6">
                                <div class="border rounded p-2">
                                    <div class="text-muted" style="font-size:.65rem; text-transform:uppercase; font-weight:700;">Inicio</div>
                                    <div class="fw-semibold small" id="det_inicio">—</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border border-danger rounded p-2">
                                    <div class="text-danger" style="font-size:.65rem; text-transform:uppercase; font-weight:700;">Fin</div>
                                    <div class="fw-bold small text-danger" id="det_fin">—</div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row g-4">

            <!-- Columna izquierda -->
            <div class="col-lg-8">

                <!-- Info del espacio -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-bold text-primary border-bottom">
                        <i class="bx bx-building me-1"></i> Información del espacio
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6 col-md-3">
                                <div class="text-muted small mb-1">Ubicación</div>
                                <div class="fw-semibold" id="det_ubicacion">—</div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-muted small mb-1">Piso</div>
                                <div class="fw-semibold" id="det_piso">—</div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-muted small mb-1">Tipo</div>
                                <div class="fw-semibold" id="det_tipo_espacio">—</div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-muted small mb-1">Capacidad</div>
                                <div class="fw-semibold" id="det_capacidad">—</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Observaciones -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-bold text-primary border-bottom">
                        <i class="bx bx-note me-1"></i> Observaciones
                    </div>
                    <div class="card-body">
                        <p class="mb-0 text-secondary" id="det_observaciones">—</p>
                    </div>
                </div>

                <!-- Panel de acciones (dinámico por estado del espacio) -->
                <div id="wrap_acciones_estado" class="card border-0 shadow-sm border-start border-warning border-4 mb-4" style="display:none;">
                    <div class="card-header bg-white fw-bold border-bottom">
                        <i class="bx bx-cog me-1 text-warning"></i> Acciones
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3" id="txt_msg_accion"></p>
                        <div class="d-flex gap-2 flex-wrap" id="contenedor_botones_accion"></div>
                    </div>
                </div>

            </div>

            <!-- Columna derecha -->
            <div class="col-lg-4">

                <!-- Resumen -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-bold text-primary border-bottom">
                        <i class="bx bx-info-circle me-1"></i> Resumen
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted small">Código</span>
                            <span class="fw-semibold small" id="res_codigo">—</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted small">Persona</span>
                            <span class="fw-semibold small" id="res_persona">—</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted small">Cédula</span>
                            <span class="fw-semibold small" id="res_cedula">—</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted small">Estado reserva</span>
                            <span class="fw-semibold small" id="res_estado">—</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted small">Inicio</span>
                            <span class="fw-semibold small" id="res_inicio">—</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted small">Fin</span>
                            <span class="fw-semibold small text-danger" id="res_fin">—</span>
                        </li>
                    </ul>
                </div>

                <!-- Estado del espacio -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-bold text-primary border-bottom">
                        <i class="bx bx-transfer me-1"></i> Estado del espacio
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-around" id="timeline_estado_espacio"></div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>


<script>
    var _id_espacio_actual = null;
    var _estado_espacio_actual = null;

    var ESTADOS_ESPACIO = {
        1: {
            nombre: 'ACTIVO',
            bg: 'bg-success'
        },
        2: {
            nombre: 'INACTIVO',
            bg: 'bg-secondary'
        },
        3: {
            nombre: 'MANTENIMIENTO',
            bg: 'bg-warning text-dark'
        },
        4: {
            nombre: 'BLOQUEADO',
            bg: 'bg-danger'
        }
    };

    $(document).ready(function() {
        datos_reserva(<?= (int)$_id ?>);
    });

    /* ── Reserva ── */
    function datos_reserva(id) {
        $.ajax({
            url: '../controlador/HOST_TIME/RESERVAS/hub_reservasC.php?listar_detalle=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                let r = response[0];
                let inicio = r.inicio ? new Date(r.inicio).toLocaleString('es-EC') : '—';
                let fin = r.fin ? new Date(r.fin).toLocaleString('es-EC') : '—';

                $('#det_nombre_espacio').text(r.nombre_espacio || '—');
                $('#det_persona').text(r.nombre_persona || '—');
                $('#det_cedula').text(r.cedula || '—');
                $('#det_codigo').text(r.codigo || '—');
                $('#det_inicio').text(inicio);
                $('#det_fin').text(fin);
                $('#badge_estado_reserva').text(r.estado_reserva || '—');
                $('#det_observaciones').text(r.observaciones || 'Sin observaciones.');

                $('#res_codigo').text(r.codigo || '—');
                $('#res_persona').text(r.nombre_persona || '—');
                $('#res_cedula').text(r.cedula || '—');
                $('#res_estado').text(r.estado_reserva || '—');
                $('#res_inicio').text(inicio);
                $('#res_fin').text(fin);

                cargar_estado_espacio(r.id_espacio);
            }
        });
    }

    /* ── Espacio ── */
    function cargar_estado_espacio(id_espacio) {
        $.ajax({
            url: '../controlador/HOST_TIME/ESPACIOS/espaciosC.php?listar=true',
            type: 'post',
            data: {
                id: id_espacio
            },
            dataType: 'json',
            success: function(response) {
                let e = response[0];
                _id_espacio_actual = e._id;
                _estado_espacio_actual = parseInt(e.id_estado_espacio);

                $('#det_ubicacion').text(e.nombre_ubicacion || '—');
                $('#det_piso').text(e.descripcion_numero_piso || '—');
                $('#det_tipo_espacio').text(e.nombre_tipo_espacio || '—');
                $('#det_capacidad').text(
                    (e.capacidad_minima && e.capacidad_maxima) ?
                    e.capacidad_minima + ' – ' + e.capacidad_maxima + ' personas' :
                    '—'
                );

                let cfg = ESTADOS_ESPACIO[_estado_espacio_actual] || {
                    nombre: '—',
                    bg: 'bg-secondary'
                };
                $('#badge_estado_espacio').removeClass().addClass('badge fs-6 ' + cfg.bg).text(cfg.nombre);

                renderizar_timeline(_estado_espacio_actual);
                renderizar_botones(_estado_espacio_actual);
            }
        });
    }

    /* ── Timeline ── */
    function renderizar_timeline(estado_actual) {
        let pasos = [{
                id: 4,
                label: 'Bloqueado',
                icono: 'bx-lock',
                color: 'danger'
            },
            {
                id: 1,
                label: 'Activo',
                icono: 'bx-check',
                color: 'success'
            }
        ];

        let html = '';
        pasos.forEach(function(paso, idx) {
            let esActual = paso.id === estado_actual;
            let esPasado = estado_actual === 1 && paso.id === 4;
            let bg = esActual ? 'bg-' + paso.color + ' text-white' :
                esPasado ? 'bg-' + paso.color + ' bg-opacity-50 text-white' :
                'bg-light text-muted border';

            html += '<div class="text-center">' +
                '<div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-1 ' + bg + '" style="width:38px;height:38px;">' +
                '<i class="bx ' + paso.icono + '"></i></div>' +
                '<div class="text-muted" style="font-size:.62rem;text-transform:uppercase;font-weight:700;">' + paso.label + '</div>' +
                '</div>';

            if (idx < pasos.length - 1) {
                html += '<div class="flex-grow-1 border-top border-2 mb-4 mx-1"></div>';
            }
        });

        $('#timeline_estado_espacio').html(html);
    }

    /* ── Botones dinámicos ── */
    function renderizar_botones(estado) {
        let $wrap = $('#wrap_acciones_estado');
        let $botones = $('#contenedor_botones_accion');
        $botones.empty();

        if (estado === 1) {
            // BLOQUEADO → Aprobar + Cancelar
            $wrap.show();
            $('#txt_msg_accion').html('<i class="bx bx-lock me-1 text-danger"></i> El espacio está <strong>bloqueado</strong>. Puede aprobarlo para habilitarlo o cancelar la reserva.');
            $botones.append(
                $('<button class="btn btn-success btn-sm">')
                .html('<i class="bx bx-check-circle me-1"></i> Aprobar espacio')
                .on('click', function() {
                    cambiar_estado_espacio(1, 'aprobar');
                })
            );
            $botones.append(
                $('<button class="btn btn-danger btn-sm">')
                .html('<i class="bx bx-x-circle me-1"></i> Cancelar')
                .on('click', function() {
                    cambiar_estado_espacio(2, 'cancelar');
                })
            );

        } else if (estado === 2) {
            // ACTIVO → Finalizado + No presentado
            $wrap.show();
            $('#txt_msg_accion').html('<i class="bx bx-check-circle me-1 text-success"></i> El espacio está <strong>activo</strong>. Indique el resultado de la reserva.');
            $botones.append(
                $('<button class="btn btn-primary btn-sm">')
                .html('<i class="bx bx-flag me-1"></i> Finalizado')
                .on('click', function() {
                    registrar_resultado('finalizado');
                })
            );
            $botones.append(
                $('<button class="btn btn-warning btn-sm">')
                .html('<i class="bx bx-user-x me-1"></i> No presentado')
                .on('click', function() {
                    registrar_resultado('no_presentado');
                })
            );

        } else {
            $wrap.hide();
        }
    }

    /* ── Cambiar estado del espacio ── */
    function cambiar_estado_espacio(nuevo_estado, accion) {

        var parametros = {
            'id_espacio': _id_espacio_actual,
            'id_estado_nuevo': 2,
            'id_reserva': '<?= $_id ?>',
        };
        let textos = {
            aprobar: 'El espacio pasará a estado ACTIVO.',
            cancelar: 'El espacio pasará a estado INACTIVO.'
        };
        Swal.fire({
            title: '¿Confirmar acción?',
            text: textos[accion] || '',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, confirmar',
            cancelButtonText: 'No'
        }).then(function(result) {
            if (!result.isConfirmed) return;

            $.ajax({
                url: '../controlador/HOST_TIME/ESPACIOS/espaciosC.php?cambiar_estado=true',
                type: 'post',
                data: {
                    parametros: parametros
                },
                dataType: 'json',
                success: function(r) {
                    if (r == 1) {
                        Swal.fire({
                                icon: 'success',
                                title: 'Estado actualizado',
                                timer: 1400,
                                showConfirmButton: false
                            })
                            .then(function() {
                                _estado_espacio_actual = nuevo_estado;
                                let cfg = ESTADOS_ESPACIO[nuevo_estado] || {
                                    nombre: '—',
                                    bg: 'bg-secondary'
                                };
                                $('#badge_estado_espacio').removeClass().addClass('badge fs-6 ' + cfg.bg).text(cfg.nombre);
                                renderizar_timeline(nuevo_estado);
                                renderizar_botones(nuevo_estado);
                            });
                    } else {
                        Swal.fire('', 'Error al cambiar el estado.', 'error');
                    }
                }
            });
        });
    }

    /* ── Resultado de reserva ── */
    function registrar_resultado(tipo) {
        let titulos = {
            finalizado: '¿Marcar la reserva como Finalizada?',
            no_presentado: '¿Marcar como No Presentado?'
        };
        Swal.fire({
            title: titulos[tipo] || '¿Confirmar?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí',
            cancelButtonText: 'No'
        }).then(function(result) {
            if (!result.isConfirmed) return;

            $.ajax({
                url: '../controlador/HOST_TIME/RESERVAS/hub_reservasC.php?cambiar_resultado=true',
                type: 'post',
                data: {
                    id: '<?= (int)$_id ?>',
                    resultado: tipo
                },
                dataType: 'json',
                success: function(r) {
                    if (r == 1) {
                        Swal.fire({
                                icon: 'success',
                                title: 'Reserva actualizada',
                                timer: 1400,
                                showConfirmButton: false
                            })
                            .then(function() {
                                location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_reservas';
                            });
                    } else {
                        Swal.fire('', 'Error al actualizar la reserva.', 'error');
                    }
                }
            });
        });
    }
</script>