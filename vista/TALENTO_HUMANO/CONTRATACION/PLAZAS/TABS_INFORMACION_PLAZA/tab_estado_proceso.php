<?php
$modulo_sistema = isset($_SESSION['INICIO']['MODULO_SISTEMA']) ? $_SESSION['INICIO']['MODULO_SISTEMA'] : '';
$_id_plaza      = isset($_GET['_id_plaza']) ? $_GET['_id_plaza'] : '';
?>

<script>
    $(document).ready(function() {
        cargar_plaza_historial('<?= $_id_plaza ?>');
        cargar_ddl_plaza_estados();
    });

    function verificar_borrador_plaza() {
        setTimeout(function() {
            var valor = $('#txt_id_plaza_estados').val();
            var id_plaza_estados = parseInt(valor) || 0;
            if (id_plaza_estados <= 0) {
                $('#pnl_estado_proceso').hide();
                $('#pnl_mensaje_borrador').show();
            } else {
                $('#pnl_estado_proceso').show();
                $('#pnl_mensaje_borrador').hide();
            }
        }, 50);
    }

    function cargar_plaza_historial(id) {
        $.ajax({
            data: { id: id },
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/cn_plaza_historialC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (!response || response.length === 0) return;
                var ultimo = response[response.length - 1];
                var orden_actual = parseInt(ultimo.orden) || 0;
                renderizar_historial(response);
                actualizar_tabs_estado(orden_actual);
            }
        });
    }

    function cargar_ddl_plaza_estados() {
        var data_extra_aprobacion = { 'orden': 2 };
        var data_extra_cierre     = { 'orden': 5 };
        var url_plaza_estados = '../controlador/TALENTO_HUMANO/CONTRATACION/cn_cat_plaza_estadosC.php?buscar_plaza_estados=true';
        cargar_select2_url('ddl_aprobacion', url_plaza_estados, '', '', 0, data_extra_aprobacion);
        cargar_select2_url('ddl_cierre',     url_plaza_estados, '', '', 0, data_extra_cierre);
    }

    function actualizar_tabs_estado(orden_actual) {
        var tabs = [
            { btn: '#btn-tab-borrador',    orden_req: 1 },
            { btn: '#btn-tab-aprobacion',  orden_req: 2 },
            { btn: '#btn-tab-publicacion', orden_req: 3 },
            { btn: '#btn-tab-evaluacion',  orden_req: 4 },
            { btn: '#btn-tab-cierre',      orden_req: 5 },
        ];

        tabs.forEach(function(tab, i) {
            var $btn = $(tab.btn);
            $btn.removeClass('tab-completado tab-activo tab-pendiente');
            if (orden_actual > tab.orden_req) {
                $btn.addClass('tab-completado');
                $btn.find('.tab-estado-icon').html('<i class="bx bx-check-circle text-success ms-2"></i>');
            } else if (orden_actual === tab.orden_req || (i === 0 && orden_actual >= 1)) {
                $btn.addClass('tab-activo');
                $btn.find('.tab-estado-icon').html('<i class="bx bx-radio-circle-marked text-primary ms-2"></i>');
            } else {
                $btn.addClass('tab-pendiente');
                $btn.find('.tab-estado-icon').html('<i class="bx bx-time-five text-muted ms-2"></i>');
            }
        });

        var tab_activo = ['#tab-borrador', '#tab-aprobacion', '#tab-publicacion', '#tab-evaluacion', '#tab-cierre'];
        // orden 6 (DESIERTA/CONTRATADO) también apunta al tab cierre
        var idx = Math.min(orden_actual - 1, 4);
        if (idx >= 0) {
            $('#v-pills-tab .nav-link').removeClass('active');
            $('#v-pills-tabContent .tab-pane').removeClass('show active');
            $(tab_activo[idx]).addClass('show active');
            $('#v-pills-tab [data-bs-target="' + tab_activo[idx] + '"]').addClass('active');
        }
    }

    var _grupos_cache = {};

    function badge_color(codigo) {
        var c = (codigo || '').trim().toUpperCase();
        if (c === 'APROBACION')   return 'bg-success';
        if (c === 'RECHAZADA')    return 'bg-danger';
        if (c === 'PROGRAMADA')   return 'bg-warning text-dark';
        if (c === 'PUBLICADA')    return 'bg-success';
        if (c === 'BORRADOR')     return 'bg-secondary';
        if (c === 'CONTRATADO')   return 'bg-primary';
        if (c === 'DESIERTA')     return 'bg-danger';
        if (c === 'SUSPENDIDA')   return 'bg-warning text-dark';
        if (c === 'ANULADA')      return 'bg-danger';
        if (c === 'EN_EVALUACION') return 'bg-info text-dark';
        return 'bg-secondary';
    }

    function card_colors(codigo) {
        var c = (codigo || '').trim().toUpperCase();
        if (c === 'APROBACION')   return { bg: '#f4fdf7', border: '#a3cfbb', icon: 'bx-check-circle',  icon_color: '#198754' };
        if (c === 'RECHAZADA')    return { bg: '#fff8f8', border: '#f5c6cb', icon: 'bx-x-circle',      icon_color: '#dc3545' };
        if (c === 'PROGRAMADA')   return { bg: '#fffdf0', border: '#fde8a0', icon: 'bx-time-five',     icon_color: '#856404' };
        if (c === 'PUBLICADA')    return { bg: '#f4fdf7', border: '#a3cfbb', icon: 'bx-broadcast',     icon_color: '#198754' };
        if (c === 'CONTRATADO')   return { bg: '#f0f4ff', border: '#b6c8fb', icon: 'bx-user-check',    icon_color: '#0d6efd' };
        if (c === 'DESIERTA')     return { bg: '#fff8f8', border: '#f5c6cb', icon: 'bx-x-circle',      icon_color: '#dc3545' };
        if (c === 'SUSPENDIDA')   return { bg: '#fffdf0', border: '#fde8a0', icon: 'bx-pause-circle',  icon_color: '#856404' };
        if (c === 'ANULADA')      return { bg: '#fff8f8', border: '#f5c6cb', icon: 'bx-x-circle',      icon_color: '#dc3545' };
        if (c === 'EN_EVALUACION') return { bg: '#f0f8ff', border: '#b6d4fe', icon: 'bx-analyse',      icon_color: '#0d6efd' };
        return { bg: '#f8f9fa', border: '#dee2e6', icon: 'bx-info-circle', icon_color: '#6c757d' };
    }

    function estado_mensaje(codigo) {
        var c = (codigo || '').trim().toUpperCase();
        if (c === 'APROBACION')   return 'Esta plaza pasó por el estado de <strong>Aprobación</strong> correctamente.';
        if (c === 'RECHAZADA')    return 'Esta plaza fue <strong>Rechazada</strong> en el proceso de aprobación.';
        if (c === 'PROGRAMADA')   return 'Esta plaza se encuentra <strong>Programada</strong> para aprobación.';
        if (c === 'PUBLICADA')    return 'Esta plaza está <strong>Publicada</strong> y visible para los postulantes.';
        if (c === 'BORRADOR')     return 'Esta plaza fue registrada en estado <strong>Borrador</strong>.';
        if (c === 'CONTRATADO')   return 'El proceso finalizó con éxito. Plaza en estado <strong>Contratado</strong>.';
        if (c === 'DESIERTA')     return 'El proceso finalizó sin candidatos. Plaza declarada <strong>Desierta</strong>.';
        if (c === 'SUSPENDIDA')   return 'Esta plaza fue <strong>Suspendida</strong> temporalmente.';
        if (c === 'ANULADA')      return 'Esta plaza fue <strong>Anulada</strong> definitivamente.';
        if (c === 'EN_EVALUACION') return 'Esta plaza se encuentra <strong>En Evaluación</strong>.';
        return 'Esta plaza se encuentra en estado <strong>' + (codigo || '') + '</strong>.';
    }

    function card_estado(h, con_eliminar, con_cascade) {
        var b_color = badge_color(h.codigo);
        var colors  = card_colors(h.codigo);
        var id_hist = h._id || '';
        var onclick = con_cascade
            ? "eliminar_historial('" + id_hist + "', true)"
            : "eliminar_historial('" + id_hist + "')";
        var mensaje = estado_mensaje(h.codigo);

        var btn_html = con_eliminar
            ? '<button type="button" class="btn btn-danger btn-sm" onclick="' + onclick + '" title="Eliminar registro" style="border-radius:6px;padding:4px 10px;flex-shrink:0;"><i class="bx bx-trash fs-6"></i></button>'
            : '';

        return '<div class="card mb-2 border-0" style="background-color:' + colors.bg + ';border:1px solid ' + colors.border + ' !important;border-radius:10px;">' +
            '<div class="card-body py-2 px-3">' +
            '<div class="d-flex align-items-center justify-content-between gap-3">' +
            '<div class="d-flex align-items-center gap-2">' +
            '<i class="bx ' + colors.icon + ' fs-5" style="color:' + colors.icon_color + ';flex-shrink:0;"></i>' +
            '<span class="badge ' + b_color + '" style="font-size:10px;text-transform:uppercase;letter-spacing:.4px;white-space:nowrap;">' + (h.codigo || 'S/E') + '</span>' +
            '<p class="mb-0 fw-medium" style="font-size:13px;color:#444;">' + mensaje + '</p>' +
            '</div>' +
            btn_html +
            '</div>' +
            '</div>' +
            '</div>';
    }

    function renderizar_historial(response) {
        // orden 5 = SUSPENDIDA, ANULADA
        // orden 6 = DESIERTA, CONTRATADO
        var grupos = { 1: [], 2: [], 3: [], 4: [], 5: [], 6: [] };
        response.forEach(function(h) {
            var o = parseInt(h.orden) || 1;
            if (grupos[o] !== undefined) grupos[o].push(h);
        });
        _grupos_cache = grupos;

        var hay_evaluacion  = grupos[4].length > 0;
        var hay_publicacion = grupos[3].length > 0;
        // cierre = orden 5 (SUSPENDIDA/ANULADA) O orden 6 (DESIERTA/CONTRATADO)
        var hay_cierre_5    = grupos[5].length > 0;
        var hay_cierre_6    = grupos[6].length > 0;
        var hay_cierre      = hay_cierre_5 || hay_cierre_6;

        // ── BORRADOR (orden 1) ────────────────────────────────────────────
        $('#aviso_borrador').toggle(!grupos[2].length);
        var html_b = '';
        grupos[1].forEach(function(h) { html_b += card_estado(h, false, false); });
        $('#hist-borrador').html(html_b);

        // ── APROBACIÓN (orden 2) ──────────────────────────────────────────
        if (grupos[2].length) {
            $('#bloque_ddl_aprobacion').hide();
            var html_ap = '';
            grupos[2].forEach(function(h) { html_ap += card_estado(h, !hay_evaluacion, true); });
            $('#hist-aprobacion').html(html_ap);
        } else {
            $('#bloque_ddl_aprobacion').show();
            $('#hist-aprobacion').html('');
        }

        // ── PUBLICACIÓN (orden 3) ─────────────────────────────────────────
        var ultimo_ap = grupos[2].length ? grupos[2][grupos[2].length - 1] : null;
        var aprobada  = ultimo_ap && (ultimo_ap.codigo || '').trim().toUpperCase() === 'APROBACION';

        $('#bloque_btn_publicar').hide();
        $('#bloque_sin_aprobacion').hide().css('display', 'none');
        $('#bloque_fechas_publicacion').hide().html('');
        $('#hist-publicacion').html('');

        if (hay_publicacion) {
            var fecha_pub    = $('#txt_fecha_inicio').text();
            var fecha_cierre_txt = $('#txt_fecha_cierre').text();

            function fmt_fecha(f) {
                if (!f) return '—';
                var d = new Date(f + (f.length === 10 ? 'T00:00:00' : ''));
                return isNaN(d) ? f : d.toLocaleDateString('es-EC', { day: '2-digit', month: '2-digit', year: 'numeric' });
            }

            $('#bloque_fechas_publicacion')
                .html('<div class="alert alert-success d-flex align-items-start gap-2 py-2 mb-3" style="border-radius:10px;">' +
                    '<i class="bx bx-calendar-check fs-5 flex-shrink-0 mt-1 text-success"></i>' +
                    '<div><strong>Periodo de publicación activo</strong><br>' +
                    '<span class="small text-muted">' +
                    '<i class="bx bx-calendar me-1"></i>Inicio: <strong>' + fmt_fecha(fecha_pub) + '</strong>' +
                    '&nbsp;&nbsp;' +
                    '<i class="bx bx-calendar-x me-1"></i>Cierre: <strong>' + fmt_fecha(fecha_cierre_txt) + '</strong>' +
                    '</span></div></div>')
                .show();

            var html_pub = '';
            grupos[3].forEach(function(h) { html_pub += card_estado(h, !hay_evaluacion, false); });
            $('#hist-publicacion').html(html_pub);

        } else if (aprobada) {
            $('#bloque_btn_publicar').show();
        } else {
            $('#bloque_sin_aprobacion').css('display', 'flex');
        }

        // ── EVALUACIÓN (orden 4) ──────────────────────────────────────────
        var html_ev = '';
        grupos[4].forEach(function(h) { html_ev += card_estado(h, false, false); });
        $('#hist-evaluacion').html(html_ev);

        // ── CIERRE (orden 5 y 6) ──────────────────────────────────────────
        if (!hay_publicacion) {
            $('#bloque_ddl_cierre').hide();
            $('#hist-cierre').html('');
            $('#bloque_aviso_cierre_bloqueado')
                .html('<div class="alert alert-warning d-flex align-items-center gap-2 py-2">' +
                    '<i class="bx bx-lock-alt fs-5"></i>' +
                    '<span>El cierre estará disponible una vez que la plaza sea <strong>publicada</strong>.</span>' +
                    '</div>')
                .show();
        } else if (hay_cierre) {
            $('#bloque_aviso_cierre_bloqueado').hide().html('');
            $('#bloque_ddl_cierre').hide();

            var html_ci = '';

            // orden 5: SUSPENDIDA / ANULADA → con botón eliminar
            grupos[5].forEach(function(h) {
                html_ci += card_estado(h, true, false);
            });

            // orden 6: DESIERTA / CONTRATADO → sin botón eliminar
            grupos[6].forEach(function(h) {
                html_ci += card_estado(h, false, false);
            });

            $('#hist-cierre').html(html_ci);

        } else {
            $('#bloque_aviso_cierre_bloqueado').hide().html('');
            $('#bloque_ddl_cierre').show();
            $('#hist-cierre').html('');
        }
    }

    function eliminar_historial(id_historial, es_aprobacion) {
        if (!id_historial) {
            Swal.fire('', 'No se encontró el registro a eliminar.', 'warning');
            return;
        }

        var tiene_publicacion = es_aprobacion && _grupos_cache[3] && _grupos_cache[3].length > 0;
        var id_publicacion    = tiene_publicacion ? (_grupos_cache[3][_grupos_cache[3].length - 1]._id || '') : '';
        var texto = tiene_publicacion
            ? 'Al eliminar la aprobación también se eliminará el registro de publicación.'
            : 'Esta acción revertirá el estado de la plaza.';

        Swal.fire({
            title: '¿Eliminar registro?',
            text: texto,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor:  '#6c757d',
            confirmButtonText:  'Sí, eliminar',
            cancelButtonText:   'Cancelar'
        }).then(function(result) {
            if (!result.isConfirmed) return;

            function eliminar_registro(id, callback) {
                $.ajax({
                    url: '../controlador/TALENTO_HUMANO/CONTRATACION/cn_plaza_historialC.php?eliminar=true',
                    type: 'POST',
                    dataType: 'json',
                    data: { id: id },
                    success: callback,
                    error: function(xhr) { Swal.fire('', 'Error: ' + xhr.responseText, 'error'); }
                });
            }

            if (tiene_publicacion) {
                eliminar_registro(id_publicacion, function() {
                    eliminar_registro(id_historial, function() {
                        Swal.fire('', 'Aprobación y publicación eliminadas correctamente.', 'success').then(function() {
                            cargar_plaza_historial('<?= $_id_plaza ?>');
                            actualizar_boton_postulante(false);
                            mostrar_boton_verificar(false);
                        });
                    });
                });
            } else {
                eliminar_registro(id_historial, function() {
                    Swal.fire('', 'Registro eliminado correctamente.', 'success').then(function() {
                        cargar_plaza_historial('<?= $_id_plaza ?>');
                        actualizar_boton_postulante(false);
                        mostrar_boton_verificar(false);
                    });
                });
            }
        });
    }

    function cambiar_estado_plaza(id_estado, selector_usado) {
        if (!id_estado) {
            Swal.fire('', 'Selecciona un estado primero.', 'warning');
            return;
        }
        var accion_texto = $('#' + selector_usado + ' option:selected').text();
        Swal.fire({
            title: '¿Cambiar estado?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor:  '#6c757d',
            confirmButtonText:  'Sí, cambiar',
            cancelButtonText:   'Cancelar'
        }).then(function(result) {
            if (!result.isConfirmed) return;
            $.ajax({
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/cn_plazaC.php?cambiar_estado_plaza=true',
                type: 'POST',
                dataType: 'json',
                data: {
                    parametros: {
                        '_id': '<?= $_id_plaza ?>',
                        'id_plaza_estados': id_estado,
                        'accion': accion_texto
                    }
                },
                success: function() {
                    Swal.fire('', 'Estado actualizado correctamente.', 'success').then(function() {
                        cargar_plaza_historial('<?= $_id_plaza ?>');
                    });
                },
                error: function(xhr) { Swal.fire('', 'Error: ' + xhr.responseText, 'error'); }
            });
        });
    }

    function validarFechaPublicacion() {
        const $pub = $('#txt_pub_fecha_publicacion');
        if (!$pub.val()) return true;
        const hoy = new Date(); hoy.setHours(0, 0, 0, 0);
        const fechaPub = new Date($pub.val() + 'T00:00:00');
        if (fechaPub < hoy) {
            $pub.addClass('is-invalid').removeClass('is-valid');
            Swal.fire({ icon: 'warning', title: 'Fecha inválida', text: 'La fecha de publicación no puede ser anterior a hoy.', confirmButtonText: 'Entendido' })
                .then(() => { $pub.val(''); $pub.focus(); });
            return false;
        }
        const $cierre = $('#txt_pub_fecha_cierre');
        if ($cierre.val()) {
            const fechaCierre = new Date($cierre.val() + 'T00:00:00');
            if (fechaPub > fechaCierre) {
                $pub.addClass('is-invalid').removeClass('is-valid');
                Swal.fire({ icon: 'error', title: 'Rango incorrecto', text: 'La fecha de publicación no puede ser mayor que la de cierre.', confirmButtonText: 'Corregir' })
                    .then(() => { $pub.val(''); $pub.focus(); });
                return false;
            }
        }
        $pub.removeClass('is-invalid').addClass('is-valid');
        if ($cierre.val()) validarFechaCierre_pub();
        return true;
    }

    function validarFechaCierre_pub() {
        const $pub    = $('#txt_pub_fecha_publicacion');
        const $cierre = $('#txt_pub_fecha_cierre');
        if (!$cierre.val()) return true;
        const hoy = new Date(); hoy.setHours(0, 0, 0, 0);
        const fechaCierre = new Date($cierre.val() + 'T00:00:00');
        if (fechaCierre < hoy) {
            $cierre.addClass('is-invalid').removeClass('is-valid');
            Swal.fire({ icon: 'warning', title: 'Fecha inválida', text: 'La fecha de cierre no puede ser anterior a hoy.', confirmButtonText: 'Entendido' })
                .then(() => { $cierre.val(''); $cierre.focus(); });
            return false;
        }
        if ($pub.val()) {
            const fechaPub = new Date($pub.val() + 'T00:00:00');
            if (fechaCierre < fechaPub) {
                $cierre.addClass('is-invalid').removeClass('is-valid');
                Swal.fire({ icon: 'error', title: 'Rango incorrecto', text: 'La fecha de cierre no puede ser menor que la de publicación.', confirmButtonText: 'Corregir' })
                    .then(() => { $cierre.val(''); $cierre.focus(); });
                return false;
            }
        }
        $cierre.removeClass('is-invalid').addClass('is-valid');
        return true;
    }

    $(document).on('change', '#txt_pub_fecha_publicacion', function() { validarFechaPublicacion(); });
    $(document).on('change', '#txt_pub_fecha_cierre',      function() { validarFechaCierre_pub(); });

    function publicar_plaza() {
        var fecha_pub    = $('#txt_pub_fecha_publicacion').val();
        var fecha_cierre = $('#txt_pub_fecha_cierre').val();

        if (!fecha_pub || !fecha_cierre) {
            Swal.fire({ icon: 'warning', title: 'Fechas requeridas', text: 'Debes ingresar la fecha de publicación y la fecha de cierre.', confirmButtonText: 'Entendido' });
            return;
        }
        if (!validarFechaPublicacion() || !validarFechaCierre_pub()) return;

        Swal.fire({
            title: '¿Publicar plaza?',
            text: 'La plaza será visible para los postulantes.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor:  '#6c757d',
            confirmButtonText:  'Sí, publicar',
            cancelButtonText:   'Cancelar'
        }).then(function(result) {
            if (!result.isConfirmed) return;
            $.ajax({
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/cn_plazaC.php?cambiar_estado_plaza=true',
                type: 'POST',
                dataType: 'json',
                data: {
                    parametros: {
                        '_id': '<?= $_id_plaza ?>',
                        'id_plaza_estados': 5,
                        'accion': 'Publicación de la plaza',
                        'fecha_publicacion': fecha_pub,
                        'fecha_cierre':      fecha_cierre
                    }
                },
                success: function() {
                    Swal.fire('', 'Plaza publicada correctamente.', 'success').then(function() {
                        cargar_plaza_historial('<?= $_id_plaza ?>');
                        actualizar_boton_postulante(true);
                        mostrar_boton_verificar(true);
                    });
                },
                error: function(xhr) { Swal.fire('', 'Error: ' + xhr.responseText, 'error'); }
            });
        });
    }
</script>

<!-- ══════════════════════════════════════════════════════════════════════ -->
<!--  HTML                                                                  -->
<!-- ══════════════════════════════════════════════════════════════════════ -->

<section id="pnl_estado_proceso" class="py-1" style="display: none;">
    <div class="row g-0 shadow-sm border rounded-3">

        <div class="col-md-3 bg-light border-end">
            <div class="p-2 border-bottom">
                <small class="text-muted fw-bold text-uppercase" style="font-size:11px;letter-spacing:.5px;">
                    <i class="bx bx-history me-1"></i> Flujo de Estado
                </small>
            </div>
            <div class="nav flex-column nav-pills gap-1 p-2" id="v-pills-tab" role="tablist">

                <button class="nav-link active py-3 px-3 border shadow-sm d-flex align-items-center justify-content-between"
                    id="btn-tab-borrador" data-bs-toggle="pill" data-bs-target="#tab-borrador" type="button" role="tab">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-edit me-2 fs-5"></i><span>Borrador</span>
                    </div>
                    <span class="tab-estado-icon"></span>
                </button>

                <button class="nav-link py-3 px-3 border shadow-sm d-flex align-items-center justify-content-between"
                    id="btn-tab-aprobacion" data-bs-toggle="pill" data-bs-target="#tab-aprobacion" type="button" role="tab">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-check-shield me-2 fs-5"></i><span>Aprobación</span>
                    </div>
                    <span class="tab-estado-icon"></span>
                </button>

                <button class="nav-link py-3 px-3 border shadow-sm d-flex align-items-center justify-content-between"
                    id="btn-tab-publicacion" data-bs-toggle="pill" data-bs-target="#tab-publicacion" type="button" role="tab">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-broadcast me-2 fs-5"></i><span>Publicación</span>
                    </div>
                    <span class="tab-estado-icon"></span>
                </button>

                <button class="nav-link py-3 px-3 border shadow-sm d-flex align-items-center justify-content-between"
                    id="btn-tab-evaluacion" data-bs-toggle="pill" data-bs-target="#tab-evaluacion" type="button" role="tab">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-analyse me-2 fs-5"></i><span>Evaluación</span>
                    </div>
                    <span class="tab-estado-icon"></span>
                </button>

                <button class="nav-link py-3 px-3 border shadow-sm d-flex align-items-center justify-content-between"
                    id="btn-tab-cierre" data-bs-toggle="pill" data-bs-target="#tab-cierre" type="button" role="tab">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-flag me-2 fs-5"></i><span>Cierre</span>
                    </div>
                    <span class="tab-estado-icon"></span>
                </button>

            </div>
        </div>

        <div class="col-md-9 bg-white">
            <div class="tab-content p-4" id="v-pills-tabContent" style="min-height:400px;">

                <div class="tab-pane fade show active" id="tab-borrador" role="tabpanel">
                    <h6 class="mb-3 fw-bold">Plaza en edición</h6>
                    <div id="aviso_borrador" class="alert alert-warning d-flex align-items-start gap-2 mb-3">
                        <i class="bx bx-info-circle fs-5 flex-shrink-0 mt-1"></i>
                        <span>La plaza está en borrador. Para poder publicarla debes ir a la etapa de <strong>Aprobación</strong> y registrar su estado.</span>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-aprobacion" role="tabpanel">
                    <h6 class="mb-3 fw-bold">Gestionar aprobación</h6>
                    <div id="bloque_ddl_aprobacion">
                        <p class="text-muted small mb-3">Selecciona el nuevo estado para esta plaza.</p>
                        <div class="row g-2 align-items-end mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Estado</label>
                                <select class="form-select form-select-sm" id="ddl_aprobacion">
                                    <option value="">-- Seleccione --</option>
                                </select>
                            </div>
                            <div class="col-md-auto">
                                <button class="btn btn-primary btn-sm"
                                    onclick="cambiar_estado_plaza($('#ddl_aprobacion').val(), 'ddl_aprobacion')">
                                    <i class="bx bx-save me-1"></i> Aplicar
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="hist-aprobacion"></div>
                </div>

                <div class="tab-pane fade" id="tab-publicacion" role="tabpanel">
                    <h6 class="mb-3 fw-bold">Publicar plaza</h6>
                    <div id="bloque_fechas_publicacion" style="display:none;"></div>
                    <div id="bloque_btn_publicar" class="mb-3" style="display:none;">
                        <p class="text-muted small mb-2">Una vez publicada, la plaza será visible para los postulantes.</p>
                        <div class="p-3 bg-light rounded-3 border mb-3">
                            <h6 class="text-muted fs-7 mb-2 fw-bold text-uppercase" style="letter-spacing:.5px;">Periodo de Publicación</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="txt_pub_fecha_publicacion" class="form-label fs-7 mb-1 fw-bold">Fecha de Publicación</label>
                                    <input type="date" class="form-control form-control-sm" id="txt_pub_fecha_publicacion" name="txt_pub_fecha_publicacion" />
                                </div>
                                <div class="col-md-6">
                                    <label for="txt_pub_fecha_cierre" class="form-label fs-7 mb-1 fw-bold">Fecha de Cierre</label>
                                    <input type="date" class="form-control form-control-sm" id="txt_pub_fecha_cierre" name="txt_pub_fecha_cierre" />
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-success btn-sm" onclick="publicar_plaza()">
                            <i class="bx bx-broadcast me-1"></i> Publicar Plaza
                        </button>
                    </div>
                    <div id="bloque_sin_aprobacion" class="alert alert-warning align-items-start gap-2 mb-3" style="display:none;">
                        <i class="bx bx-info-circle fs-5 flex-shrink-0 mt-1"></i>
                        <span>La plaza debe tener estado <strong>APROBACION</strong> para poder publicarse. El estado actual no permite la publicación.</span>
                    </div>
                    <div id="hist-publicacion"></div>
                </div>

                <div class="tab-pane fade" id="tab-evaluacion" role="tabpanel">
                    <h6 class="mb-3 fw-bold">Proceso de evaluación</h6>
                    <p class="text-muted small">El proceso de evaluación se gestiona desde el módulo de etapas del proceso.</p>
                    <div id="hist-evaluacion"></div>
                </div>

                <div class="tab-pane fade" id="tab-cierre" role="tabpanel">
                    <h6 class="mb-3 fw-bold">Gestionar cierre</h6>
                    <div id="bloque_aviso_cierre_bloqueado" style="display:none;"></div>
                    <div id="bloque_ddl_cierre" style="display:none;">
                        <p class="text-muted small mb-3">Selecciona el estado final de la plaza.</p>
                        <div class="row g-2 align-items-end mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Estado de cierre</label>
                                <select class="form-select form-select-sm" id="ddl_cierre">
                                    <option value="">-- Seleccione --</option>
                                </select>
                            </div>
                            <div class="col-md-auto">
                                <button class="btn btn-dark btn-sm"
                                    onclick="cambiar_estado_plaza($('#ddl_cierre').val(), 'ddl_cierre')">
                                    <i class="bx bx-save me-1"></i> Aplicar
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="hist-cierre"></div>
                </div>

            </div>
        </div>
    </div>
</section>

<section id="pnl_mensaje_borrador" class="py-2" style="display: none;">
    <div class="d-flex align-items-center p-3 rounded-3" style="background-color: #fff4e5; border: 1px solid #ffe2b9;">
        <i class="bx bxs-error-circle me-3" style="color: #ff9800; font-size: 24px;"></i>
        <div class="text-dark">
            <span class="fw-bold d-block mb-1">Registro Pendiente</span>
            <p class="mb-0 small">
                Para habilitar esta sección, completa el Paso 3 y haz clic en <b>"Guardar"</b>.
                Esto activará el estado <b>Borrador</b> para continuar con el proceso.
            </p>
        </div>
    </div>
</section>