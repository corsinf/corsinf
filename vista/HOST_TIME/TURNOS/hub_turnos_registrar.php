<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$_id = '';
if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}
?>

<!-- Color picker -->
<link rel="stylesheet" href="../lib/Pickr/nano.min.css" />
<script src="../lib/Pickr/pickr.js"></script>

<link rel="stylesheet" href="../lib/ion-rangeSlider/ion.rangeSlider.min.css" />
<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<style>
    /* ── Slider: colores personalizados ── */
    .irs .irs-from,
    .irs .irs-to,
    .irs .irs-single {
        background: #45C4B0;
        color: #012030;
    }

    .irs .irs-slider,
    .irs .irs-min,
    .irs .irs-max {
        background: #DAFDBA;
        color: #012030;
    }

    .irs .irs-bar {
        background: #13678A;
    }

    .irs .irs-handle,
    .irs .irs-handle.type_last {
        background: #13678A;
        border: 0.1px solid #13678A !important;
    }

    /* ── Etiqueta de duración ── */
    #lbl_horas_trabajadas {
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 6px;
    }

    #lbl_horas_trabajadas span {
        font-weight: 600;
        color: #13678A;
    }

    /* ── Inputs time: ocultar ícono de validación Bootstrap ── */
    input[type="time"].is-valid {
        background-image: none !important;
        padding-right: 8px !important;
    }

    /* ── Color picker button ── */
    .pcr-button {
        width: 100% !important;
        height: 31px !important;
        padding: 0 !important;
        box-sizing: border-box;
    }
</style>

<script type="text/javascript">
    /* ══════════════════════════════════════
       Utilidades
    ══════════════════════════════════════ */
    function hora_a_minutos(hora) {
        if (!hora) return null;
        var partes = hora.split(':');
        return parseInt(partes[0]) * 60 + parseInt(partes[1]);
    }

    function minutos_a_hora(min) {
        if (min === null || min === undefined) return '';
        min = ((min % 1440) + 1440) % 1440;
        var h = Math.floor(min / 60);
        var m = min % 60;
        return (h < 10 ? '0' : '') + h + ':' + (m < 10 ? '0' : '') + m;
    }

    /* ══════════════════════════════════════
       Calcular y mostrar duración del turno
    ══════════════════════════════════════ */
    function calcular_horas_trabajadas() {
        var entradaMin = hora_a_minutos($('#txt_hora_entrada').val());
        var salidaMin  = hora_a_minutos($('#txt_hora_salida').val());

        if (entradaMin === null || salidaMin === null) {
            $('#lbl_horas_trabajadas').html('');
            return;
        }

        var diff = salidaMin - entradaMin;
        if (diff < 0) diff += 1440;

        var h = Math.floor(diff / 60);
        var m = diff % 60;

        $('#lbl_horas_trabajadas').html(
            'Duración del turno: <span>' + h + 'h ' + (m > 0 ? m + 'min' : '') + '</span>'
        );
    }

    /* ══════════════════════════════════════
       Slider → inputs
    ══════════════════════════════════════ */
    function slider_a_inputs(data) {
        $('#txt_hora_entrada').val(minutos_a_hora(data.from));
        $('#txt_hora_salida').val(minutos_a_hora(data.to));
        calcular_horas_trabajadas();
    }

    /* ══════════════════════════════════════
       Inputs → slider
    ══════════════════════════════════════ */
    function inputs_a_slider() {
        var entradaMin = hora_a_minutos($('#txt_hora_entrada').val());
        var salidaMin  = hora_a_minutos($('#txt_hora_salida').val());
        if (entradaMin !== null && salidaMin !== null && typeof slider_inst !== 'undefined') {
            slider_inst.update({
                from: entradaMin,
                to: salidaMin
            });
            // Reaplicar color porque slider.update() reconstruye elementos del DOM
            var color = $('#txt_color').val();
            if (color) aplicar_color_slider(color);
        }
        calcular_horas_trabajadas();
    }

    /* ══════════════════════════════════════
       Aplicar color al slider (barra + handles + tooltips)
    ══════════════════════════════════════ */
    function aplicar_color_slider(color) {
        // Barra de rango seleccionado
        $('.irs-bar, .irs-bar-edge').css('background', color);
        // Handles (los dos círculos arrastrables)
        $('.irs-handle, .irs-handle.type_last').css({
            'background': color,
            'border-color': color
        });
        // Tooltips from/to/single
        $('.irs-from, .irs-to, .irs-single').css('background', color);
        // Etiqueta de duración
        $('#lbl_horas_trabajadas span').css('color', color);
    }

    /* ══════════════════════════════════════
       Color picker
    ══════════════════════════════════════ */
    function color_input(colorInicial) {
        // Si ya existe una instancia previa, destruirla antes de crear una nueva
        if (typeof pickr !== 'undefined' && pickr !== null) {
            try { pickr.destroyAndRemove(); } catch(e) {}
            $('#color-picker').html('');
        }

        pickr = Pickr.create({
            el: '#color-picker',
            theme: 'nano',
            default: colorInicial,
            swatches: [
                'rgba(244, 67, 54, 1)',
                'rgba(233, 30, 99, 1)',
                'rgba(156, 39, 176, 1)',
                'rgba(103, 58, 183, 1)',
                'rgba(63, 81, 181, 1)',
                'rgba(33, 150, 243, 1)',
                'rgba(3, 169, 244, 1)',
                'rgba(0, 188, 212, 1)',
                'rgba(0, 150, 136, 1)',
                'rgba(76, 175, 80, 1)',
                'rgba(139, 195, 74, 1)',
                'rgba(205, 220, 57, 1)',
                'rgba(255, 235, 59, 1)',
                'rgba(255, 193, 7, 1)',
            ],
            components: {
                preview: true,
                opacity: true,
                hue: true,
                interaction: {
                    hex: true,
                    rgba: true,
                    input: true,
                    clear: true,
                    save: true
                },
            },
            i18n: {
                'btn:save':   'Guardar',
                'btn:cancel': 'Cancelar',
                'btn:clear':  'Limpiar',
            }
        });

        // Aplicar color inicial al slider
        aplicar_color_slider(colorInicial);

        pickr.on('change', function(color) {
            var hex = color.toHEXA().toString();
            $('#txt_color').val(hex);
            aplicar_color_slider(hex);
        });

        pickr.on('save', function(color) {
            if (color) {
                var hex = color.toHEXA().toString();
                $('#txt_color').val(hex);
                aplicar_color_slider(hex);
            }
            pickr.hide();
        });

        // Al cerrar el panel (click fuera o save), aplicar el color actual
        pickr.on('hide', function() {
            var color = pickr.getColor();
            if (color) {
                var hex = color.toHEXA().toString();
                $('#txt_color').val(hex);
                aplicar_color_slider(hex);
            }
        });
    }

    /* ══════════════════════════════════════
       Cargar datos al editar
    ══════════════════════════════════════ */
    function datos_turno(id) {
        $.ajax({
            data: { id: id },
            url: '../controlador/HOST_TIME/TURNOS/hub_turnosC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response && response.length > 0) {
                    $('#txt_nombre').val(response[0].nombre);
                    $('#txt_hora_entrada').val(minutos_a_hora(response[0].hora_entrada));
                    $('#txt_hora_salida').val(minutos_a_hora(response[0].hora_salida));

                    // Cargar color
                    var color = response[0].color || '#2196F3';
                    $('#txt_color').val(color);
                    color_input(color);

                    calcular_horas_trabajadas();
                    inputs_a_slider();
                }
            }
        });
    }

    /* ══════════════════════════════════════
       Guardar / Editar
    ══════════════════════════════════════ */
    function editar_insertar() {
        if (!validar_horas()) return;
        if (!$("#form_turnos").valid()) return;

        insertar({
            '_id':             '<?= $_id ?>',
            'txt_nombre':      $('#txt_nombre').val(),
            'txt_hora_entrada': hora_a_minutos($('#txt_hora_entrada').val()),
            'txt_hora_salida': hora_a_minutos($('#txt_hora_salida').val()),
            'txt_color':       $('#txt_color').val(),
        });
    }

    function insertar(parametros) {
        $.ajax({
            data: { parametros: parametros },
            url: '../controlador/HOST_TIME/TURNOS/hub_turnosC.php?insertar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operación realizada con éxito.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_turnos';
                    });
                } else if (response == -2) {
                    $('#txt_nombre').addClass('is-invalid');
                    $('#error_txt_nombre').text('El nombre del turno ya está en uso.');
                }
            },
            error: function(xhr) {
                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });

        $('#txt_nombre').on('input', function() {
            $('#error_txt_nombre').text('');
            $(this).removeClass('is-invalid');
        });
    }

    /* ══════════════════════════════════════
       Eliminar
    ══════════════════════════════════════ */
    function delete_datos() {
        Swal.fire({
            title: '¿Eliminar Registro?',
            text: '¿Está seguro de eliminar este turno?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) eliminar('<?= $_id ?>');
        });
    }

    function eliminar(id) {
        $.ajax({
            data: { id: id },
            url: '../controlador/HOST_TIME/TURNOS/hub_turnosC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro eliminado.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_turnos';
                    });
                }
            }
        });
    }

    /* ══════════════════════════════════════
       Validación: salida > entrada
    ══════════════════════════════════════ */
    function validar_horas() {
        var entrada = $('#txt_hora_entrada').val();
        var salida  = $('#txt_hora_salida').val();
        if (!entrada || !salida) return true;

        if (hora_a_minutos(salida) <= hora_a_minutos(entrada)) {
            Swal.fire('', 'La hora de salida debe ser mayor que la hora de entrada.', 'warning');
            $('#txt_hora_salida').addClass('is-invalid');
            return false;
        }
        $('#txt_hora_salida').removeClass('is-invalid').addClass('is-valid');
        return true;
    }

    /* ══════════════════════════════════════
       DOM Ready
    ══════════════════════════════════════ */
    $(document).ready(function() {

        <?php if (isset($_GET['_id'])) { ?>
            datos_turno(<?= $_id ?>);
        <?php } else { ?>
            // Color por defecto al crear un turno nuevo
            var colorDefault = '#2196F3';
            $('#txt_color').val(colorDefault);
            color_input(colorDefault);
        <?php } ?>

        agregar_asterisco_campo_obligatorio('txt_nombre');
        agregar_asterisco_campo_obligatorio('txt_hora_entrada');
        agregar_asterisco_campo_obligatorio('txt_hora_salida');

        $("#form_turnos").validate({
            rules: {
                txt_nombre: {
                    required: true,
                    maxlength: 50
                },
                txt_hora_entrada: {
                    required: true
                },
                txt_hora_salida: {
                    required: true
                }
            },
            messages: {
                txt_nombre: {
                    required: "El nombre del turno es obligatorio.",
                    maxlength: "Máximo 50 caracteres."
                },
                txt_hora_entrada: {
                    required: "La hora de entrada es obligatoria."
                },
                txt_hora_salida: {
                    required: "La hora de salida es obligatoria."
                }
            },
            highlight: function(el) {
                $(el).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function(el) {
                $(el).removeClass('is-invalid').addClass('is-valid');
            }
        });

        // Sincronizar inputs → slider al escribir manualmente
        $('#txt_hora_entrada, #txt_hora_salida').on('change', function() {
            inputs_a_slider();
            validar_horas();
        });

    });
</script>

<!-- ════════════════════════════ HTML ════════════════════════════ -->
<div class="page-wrapper">
    <div class="page-content">

        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Turnos</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?= ($_id == '') ? 'Agregar Turno' : 'Editar Turno' ?>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <form id="form_turnos">
            <div class="row">
                <div class="col-xl-12 mx-auto">
                    <div class="card border-top border-0 border-4 border-primary">
                        <div class="card-body p-5">

                            <!-- Título -->
                            <div class="card-title d-flex align-items-center">
                                <i class="bx bxs-time me-1 font-22 text-primary"></i>
                                <h5 class="mb-0 text-primary">
                                    <?= ($_id == '') ? 'Registrar Turno' : 'Modificar Turno' ?>
                                </h5>
                                <div class="ms-3">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_turnos"
                                        class="btn btn-outline-dark btn-sm">
                                        <i class="bx bx-arrow-back"></i> Regresar
                                    </a>
                                </div>
                            </div>

                            <hr>

                            <!-- Nombre + Color -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="txt_nombre" class="form-label fw-bold">Nombre del turno</label>
                                    <input type="text"
                                        class="form-control form-control-sm no_caracteres"
                                        name="txt_nombre"
                                        id="txt_nombre"
                                        maxlength="50"
                                        placeholder="Ej: Turno Mañana">
                                    <span id="error_txt_nombre" class="text-danger small"></span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Color del turno</label>
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <div id="color-picker"></div>
                                        </div>
                                        <div class="col-md-6" hidden>
                                            <input type="text"
                                                class="form-control form-control-sm"
                                                name="txt_color"
                                                id="txt_color"
                                                maxlength="50"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ══ SLIDER VISUAL ══ -->
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Rango del turno</label>
                                    <small class="text-muted d-block mb-3">
                                        Arrastra los controles para ajustar la hora de entrada y salida.
                                    </small>
                                    <input id="slider_turno" type="text" />
                                    <div id="lbl_horas_trabajadas" class="text-center mt-2"></div>
                                </div>
                            </div>

                            <hr>

                            <!-- Inputs hora -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="txt_hora_entrada" class="form-label fw-bold">Hora de entrada</label>
                                    <input type="time"
                                        class="form-control form-control-sm"
                                        name="txt_hora_entrada"
                                        id="txt_hora_entrada"
                                        value="07:00">
                                </div>
                                <div class="col-md-6">
                                    <label for="txt_hora_salida" class="form-label fw-bold">Hora de salida</label>
                                    <input type="time"
                                        class="form-control form-control-sm"
                                        name="txt_hora_salida"
                                        id="txt_hora_salida"
                                        value="15:30">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-center pt-2 pb-4">
                <?php if ($_id == '') { ?>
                    <button class="btn btn-success btn-sm px-4 m-0" onclick="editar_insertar();" type="button">
                        <i class="bx bx-save"></i> Guardar
                    </button>
                <?php } else { ?>
                    <button class="btn btn-success btn-sm px-4 m-1" onclick="editar_insertar();" type="button">
                        <i class="bx bx-save"></i> Editar
                    </button>
                    <button class="btn btn-danger btn-sm px-4 m-1" onclick="delete_datos();" type="button">
                        <i class="bx bx-trash"></i> Eliminar
                    </button>
                <?php } ?>
            </div>

        </form>
    </div>
</div>

<!-- ══ ionRangeSlider init ══ -->
<script src="../lib/ion-rangeSlider/ion.rangeSlider.min.js"></script>
<script>
    var slider_inst;

    $(document).ready(function() {
        slider_inst = $("#slider_turno").ionRangeSlider({
            min: 0,
            max: 1439,
            from: 420,
            to: 930,
            step: 5,
            grid: true,
            grid_num: 24,
            type: "double",
            prettify: function(num) {
                var h = Math.floor(num / 60);
                var m = num % 60;
                return (h < 10 ? '0' : '') + h + ':' + (m < 10 ? '0' : '') + m;
            },
            onStart: function(data) {
                // Se dispara una vez cuando el slider termina de renderizarse
                // En este punto el DOM de .irs-bar ya existe
                var color = $('#txt_color').val();
                if (color) aplicar_color_slider(color);
            },
            onChange: function(data) {
                $('#txt_hora_entrada').val(minutos_a_hora(data.from));
                $('#txt_hora_salida').val(minutos_a_hora(data.to));
                calcular_horas_trabajadas();
                // Reaplicar color en cada cambio porque ionRangeSlider
                // puede reescribir estilos internos al mover el handle
                var color = $('#txt_color').val();
                if (color) aplicar_color_slider(color);
            },
            onFinish: function(data) {
                slider_a_inputs(data);
                validar_horas();
            }
        }).data("ionRangeSlider");

        calcular_horas_trabajadas();
    });
</script>