<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$_id = '';
if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}
?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<style>
    #timeline_24h {
        user-select: none;
        -webkit-user-select: none;
    }

    #rango_actual {
        transition: none !important;
    }

    .handle-drag {
        touch-action: none;
    }
</style>

<script type="text/javascript">
    $(document).ready(function() {
        cargar_select2_url('ddl_espacio', '../controlador/HOST_TIME/ESPACIOS/espaciosC.php?buscar=true');

        dibujar_marcas();

        <?php if (isset($_GET['_id'])): ?>
            datos_col(<?= $_id ?>);
        <?php else: ?>
            $('#txt_hora_inicio').val('08:00');
            $('#txt_hora_fin').val('17:00');
            sync_bar_desde_inputs();
        <?php endif; ?>

        $('#txt_hora_inicio, #txt_hora_fin').on('change input', function() {
            sync_bar_desde_inputs();
            $('#form_horario').valid();
        });

        init_drag();
    });

    /* ─── Timeline helpers ─── */

    function t_a_min(t) {
        if (!t) return 0;
        let p = t.split(':');
        return parseInt(p[0]) * 60 + parseInt(p[1]);
    }

    function min_a_t(m) {
        m = Math.max(0, Math.min(1439, Math.round(m / 5) * 5));
        return String(Math.floor(m / 60)).padStart(2, '0') + ':' + String(m % 60).padStart(2, '0');
    }

    function dibujar_marcas() {
        let $tl = $('#timeline_24h');
        for (let h = 0; h < 24; h++) {
            let pct = (h / 24) * 100;
            $tl.append(
                `<div style="position:absolute;left:${pct}%;top:0;bottom:0;border-left:1px solid #dee2e6;z-index:0;"></div>` +
                `<span style="position:absolute;left:calc(${pct}% + 2px);top:2px;font-size:9px;color:#adb5bd;pointer-events:none;">${String(h).padStart(2, '0')}h</span>`
            );
        }
    }

    function sync_bar_desde_inputs() {
        let ini = t_a_min($('#txt_hora_inicio').val());
        let fin = t_a_min($('#txt_hora_fin').val());
        let pi = (ini / 1440) * 100;
        let pw = Math.max(0, ((fin - ini) / 1440) * 100);

        $('#rango_actual').css({
            left: pi + '%',
            width: pw + '%'
        });
        $('#lbl_rango').text(min_a_t(ini) + ' – ' + min_a_t(fin));
    }

    /* ─── Drag ─── */

    let _dragging = false,
        _dragType = null,
        _dragX0, _dragPi0, _dragPf0;

    function get_cx(e) {
        return e.originalEvent && e.originalEvent.touches ?
            e.originalEvent.touches[0].clientX :
            e.clientX;
    }

    function init_drag() {
        let $tl = $('#timeline_24h');

        $tl.on('mousedown touchstart', '.handle-drag', function(e) {
            e.preventDefault();
            e.stopPropagation();
            _dragging = true;
            _dragType = $(this).data('type');
            _dragX0 = get_cx(e);
            _dragPi0 = (t_a_min($('#txt_hora_inicio').val()) / 1440) * 100;
            _dragPf0 = (t_a_min($('#txt_hora_fin').val()) / 1440) * 100;
        });

        $tl.on('mousedown touchstart', '#rango_actual', function(e) {
            if ($(e.target).hasClass('handle-drag')) return;
            e.preventDefault();
            _dragging = true;
            _dragType = 'move';
            _dragX0 = get_cx(e);
            _dragPi0 = (t_a_min($('#txt_hora_inicio').val()) / 1440) * 100;
            _dragPf0 = (t_a_min($('#txt_hora_fin').val()) / 1440) * 100;
        });

        $(document).on('mousemove touchmove', function(e) {
            if (!_dragging) return;
            let W = $('#timeline_24h').width();
            let delta = ((get_cx(e) - _dragX0) / W) * 100;
            let snap = (5 / 1440) * 100;

            if (_dragType === 'ini') {
                let np = Math.max(0, Math.min(_dragPf0 - snap, _dragPi0 + delta));
                $('#txt_hora_inicio').val(min_a_t((np / 100) * 1440));
            } else if (_dragType === 'fin') {
                let np = Math.max(_dragPi0 + snap, Math.min(100, _dragPf0 + delta));
                $('#txt_hora_fin').val(min_a_t((np / 100) * 1440));
            } else {
                let dur = _dragPf0 - _dragPi0;
                let ni = Math.max(0, Math.min(100 - dur, _dragPi0 + delta));
                $('#txt_hora_inicio').val(min_a_t((ni / 100) * 1440));
                $('#txt_hora_fin').val(min_a_t(((ni + dur) / 100) * 1440));
            }

            sync_bar_desde_inputs();
        });

        $(document).on('mouseup touchend', function() {
            if (_dragging) {
                _dragging = false;
                _dragType = null;
                $('#form_horario').valid();
            }
        });
    }

    /* ─── AJAX ─── */

    function datos_col(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/HOST_TIME/HORARIOS/hub_horariosC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                let r = response[0];
                $('#ddl_espacio').append($('<option>', {
                    value: r.id_espacio,
                    text: r.nombre_espacio,
                    selected: true
                }));
                $('#ddl_dia_semana').val(r.dia_semana);
                $('#txt_hora_inicio').val(r.hora_inicio.substring(0, 5));
                $('#txt_hora_fin').val(r.hora_fin.substring(0, 5));
                $('#cbx_activo').prop('checked', r.activo == 1);
                sync_bar_desde_inputs();
            }
        });
    }

    function editar_insertar() {
        if ($('#form_horario').valid()) {
            insertar({
                '_id': '<?= $_id ?>',
                'ddl_espacio': $('#ddl_espacio').val(),
                'ddl_dia_semana': $('#ddl_dia_semana').val(),
                'txt_hora_inicio': $('#txt_hora_inicio').val(),
                'txt_hora_fin': $('#txt_hora_fin').val(),
                'cbx_activo': $('#cbx_activo').prop('checked') ? 1 : 0,
            });
        }
    }

    function insertar(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/HOST_TIME/HORARIOS/hub_horariosC.php?insertar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response && response.duplicado) {
                    Swal.fire('', response.mensaje, 'warning');
                    return;
                }
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_horarios';
                    });
                }
            },
            error: function(xhr) {
                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
    }

    function delete_datos() {
        Swal.fire({
            title: 'Eliminar Registro?',
            text: 'Esta seguro de eliminar este registro?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    data: {
                        id: '<?= $_id ?>'
                    },
                    url: '../controlador/HOST_TIME/HORARIOS/hub_horariosC.php?eliminar=true',
                    type: 'post',
                    dataType: 'json',
                    success: function(response) {
                        if (response == 1) {
                            Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
                                location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_horarios';
                            });
                        }
                    }
                });
            }
        });
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Horarios</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?= $_id == '' ? 'Registrar Horario' : 'Ver Horario' ?>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">
                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bxs-time me-1 font-22 text-primary"></i></div>
                            <h5 class="mb-0 text-primary">
                                <?= $_id == '' ? 'Registrar Horario' : 'Ver Horario' ?>
                            </h5>
                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_horarios" class="btn btn-outline-dark btn-sm">
                                        <i class="bx bx-arrow-back"></i> Regresar
                                    </a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <form id="form_horario">

                            <div class="row pt-3 mb-col">
                                <div class="col-md-6">
                                    <label for="ddl_espacio" class="form-label">Espacio</label>
                                    <select class="form-select form-select-sm select2-validation" id="ddl_espacio" name="ddl_espacio" required>
                                        <option value="" selected hidden>-- Seleccione --</option>
                                    </select>
                                    <label class="error" style="display:none;" for="ddl_espacio"></label>
                                </div>

                                <div class="col-md-6">
                                    <label for="ddl_dia_semana" class="form-label">Día de la semana</label>
                                    <select class="form-select form-select-sm" id="ddl_dia_semana" name="ddl_dia_semana" required>
                                        <option value="" selected hidden>-- Seleccione --</option>
                                        <option value="0">Domingo</option>
                                        <option value="1">Lunes</option>
                                        <option value="2">Martes</option>
                                        <option value="3">Miércoles</option>
                                        <option value="4">Jueves</option>
                                        <option value="5">Viernes</option>
                                        <option value="6">Sábado</option>
                                    </select>
                                    <label class="error" style="display:none;" for="ddl_dia_semana"></label>
                                </div>
                            </div>

                            <!-- Timeline interactivo (solo horario actual) -->
                            <div class="row mb-col">
                                <div class="col-12">
                                    <div class="card border">
                                        <div class="card-header d-flex align-items-center justify-content-between py-2">
                                            <strong class="small text-muted">
                                                <i class="bx bx-move-horizontal me-1"></i>
                                                Arrastra los extremos para ajustar el horario
                                            </strong>
                                            <span id="lbl_rango" class="badge bg-primary">--:-- – --:--</span>
                                        </div>
                                        <div class="card-body py-3">
                                            <div id="timeline_24h"
                                                style="position:relative;height:58px;background:#f8f9fa;border-radius:4px;border:1px solid #dee2e6;overflow:visible;">

                                                <!-- Rango arrastrable -->
                                                <div id="rango_actual"
                                                    style="position:absolute;top:20px;bottom:4px;
                                                           background:rgba(13,110,253,0.18);
                                                           border:2px solid #0d6efd;border-radius:4px;
                                                           cursor:grab;min-width:6px;z-index:10;">

                                                    <!-- Handle inicio -->
                                                    <div class="handle-drag" data-type="ini"
                                                        style="position:absolute;left:-7px;top:-2px;bottom:-2px;width:14px;
                                                               background:#0d6efd;border-radius:4px 0 0 4px;
                                                               cursor:ew-resize;z-index:11;
                                                               display:flex;align-items:center;justify-content:center;">
                                                        <span style="color:#fff;font-size:9px;pointer-events:none;">◂</span>
                                                    </div>

                                                    <!-- Handle fin -->
                                                    <div class="handle-drag" data-type="fin"
                                                        style="position:absolute;right:-7px;top:-2px;bottom:-2px;width:14px;
                                                               background:#0d6efd;border-radius:0 4px 4px 0;
                                                               cursor:ew-resize;z-index:11;
                                                               display:flex;align-items:center;justify-content:center;">
                                                        <span style="color:#fff;font-size:9px;pointer-events:none;">▸</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-col">
                                <div class="col-md-6">
                                    <label for="txt_hora_inicio" class="form-label">Hora inicio</label>
                                    <input type="time" class="form-control form-control-sm" id="txt_hora_inicio" name="txt_hora_inicio">
                                    <label class="error" for="txt_hora_inicio" style="display:none;"></label>
                                </div>

                                <div class="col-md-6">
                                    <label for="txt_hora_fin" class="form-label">Hora fin</label>
                                    <input type="time" class="form-control form-control-sm" id="txt_hora_fin" name="txt_hora_fin">
                                    <label class="error" for="txt_hora_fin" style="display:none;"></label>
                                </div>
                            </div>

                            <div class="row mb-col">
                                <div class="col-md-6">
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" id="cbx_activo" name="cbx_activo" checked>
                                        <label class="form-check-label" for="cbx_activo">Disponible</label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end pt-2">
                                <?php if ($_id == ''): ?>
                                    <button class="btn btn-success btn-sm px-4 m-0" onclick="editar_insertar()" type="button"><i class="bx bx-save"></i> Guardar</button>
                                <?php else: ?>
                                    <button class="btn btn-success btn-sm px-4 m-1" onclick="editar_insertar()" type="button"><i class="bx bx-save"></i> Editar</button>
                                    <button class="btn btn-danger btn-sm px-4 m-1" onclick="delete_datos()" type="button"><i class="bx bx-trash"></i> Eliminar</button>
                                <?php endif; ?>
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
        agregar_asterisco_campo_obligatorio('ddl_espacio');
        agregar_asterisco_campo_obligatorio('ddl_dia_semana');
        agregar_asterisco_campo_obligatorio('txt_hora_inicio');
        agregar_asterisco_campo_obligatorio('txt_hora_fin');

        $.validator.addMethod('hora_ini_menor', function(value) {
            let fin = $('#txt_hora_fin').val();
            return !fin || !value || value < fin;
        }, 'La hora inicio debe ser menor a la hora fin.');

        $.validator.addMethod('hora_fin_mayor', function(value) {
            let ini = $('#txt_hora_inicio').val();
            return !ini || !value || value > ini;
        }, 'La hora fin debe ser mayor a la hora inicio.');

        $('#form_horario').validate({
            rules: {
                ddl_espacio: {
                    required: true
                },
                ddl_dia_semana: {
                    required: true
                },
                txt_hora_inicio: {
                    required: true,
                    hora_ini_menor: true
                },
                txt_hora_fin: {
                    required: true,
                    hora_fin_mayor: true
                },
            },
            highlight: function(element) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid').addClass('is-valid');
            }
        });
    });
</script>