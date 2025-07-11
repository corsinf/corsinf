<link rel="stylesheet" href="../lib/ion-rangeSlider/ion.rangeSlider.min.css" />

<!-- Color picker -->
<link rel="stylesheet" href="../lib/Pickr/nano.min.css" />
<script src="../lib/Pickr/pickr.js"></script>


<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$_id = '';

if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}

$hora_entrada = isset($_GET['hora_entrada']) ? $_GET['hora_entrada'] : 420;
$hora_salida = isset($_GET['hora_salida']) ? $_GET['hora_salida'] : 930;

?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        <?php if (isset($_GET['_id'])) { ?>
            datos_col(<?= $_id ?>);
        <?php } else { ?>
            color = '#2196F3';
            color_input(color);
            $('#txt_color').val(color);
        <?php } ?>

    });

    function datos_col(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/th_turnosC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                $('#txt_nombre').val(response[0].nombre);
                $('#cbx_turno_nocturno').prop('checked', (response[0].turno_nocturno == 1));
                $('#txt_valor_trabajar_hora').val(response[0].valor_hora_trabajar);
                $('#txt_valor_trabajar_min').val(response[0].valor_min_trabajar);
                $('#txt_hora_entrada').val(minutos_formato_hora(response[0].hora_entrada));
                $('#txt_checkin_registro_inicio').val(minutos_formato_hora(response[0].checkin_registro_inicio));
                $('#txt_checkin_registro_fin').val(minutos_formato_hora(response[0].checkin_registro_fin));
                $('#txt_limite_tardanza_in').val(response[0].limite_tardanza_in);
                $('#txt_hora_salida').val(minutos_formato_hora(response[0].hora_salida));
                $('#txt_checkout_salida_inicio').val(minutos_formato_hora(response[0].checkout_salida_inicio));
                $('#txt_checkout_salida_fin').val(minutos_formato_hora(response[0].checkout_salida_fin));
                $('#txt_limite_tardanza_out').val(response[0].limite_tardanza_out);
                $('#txt_color').val(response[0].color);
                color_input(response[0].color);
                $('#txt_tiempo_descanso').val(minutos_formato_hora(response[0].hora_descanso));
                // Marcar checkbox de descanso
                $('#rbx_descanso').prop('checked', response[0].descanso == 1);
                $('#rbx_aplicar_descanso').prop('checked', response[0].usar_descanso == 1);
                $('#cbx_hora_suple_extra').prop('checked', response[0].calcular_horas_extra == 1);

                // Mostrar/ocultar panel según el estado
                if (response[0].descanso == 1) {
                    $('#pnl_tiempo_descanso').show();
                    $('#txt_tiempo_descanso').val(response[0].hora_descanso || '');
                } else {
                    $('#pnl_tiempo_descanso').hide();
                }

                if (response[0].usar_descanso == 1) {
                    $('#pnl_aplicar_tiempo_descanso').show();
                    // Cargar valores si existen, convirtiendo de minutos a HH:mm
                    $('#txt_tiempo_descanso_rango').val(response[0].hora_descanso || '');
                    $('#txt_hora_descanso_inicio').val(minutos_formato_hora(response[0].descanso_inicio || ''));
                    $('#txt_hora_descanso_final').val(minutos_formato_hora(response[0].descanso_fin || ''));
                    $('#txt_limite_tardanza_descanso_in').val(response[0].tol_ini_descanso || '');
                    $('#txt_limite_tardanza_descanso_out').val(response[0].tol_fin_descanso || '');

                } else {
                    $('#pnl_aplicar_tiempo_descanso').hide();
                }

                if (response[0].calcular_horas_extra == 1) {

                    $('#pnl_tiempo_suple_extra').show();
                    $('#txt_hora_extra_inicio').val(minutos_formato_hora(response[0].extra_ini || ''));
                    $('#txt_hora_extra_final').val(minutos_formato_hora(response[0].extra_fin || ''));
                    $('#txt_hora_suple_inicio').val(minutos_formato_hora(response[0].supl_ini || ''));
                    $('#txt_hora_suple_final').val(minutos_formato_hora(response[0].supl_fin || ''));
                } else {
                    $('#pnl_tiempo_suple_extra').hide();
                }
                actualizarOverlaySlider();
            }
        });
    }

    function editar_insertar() {
        var txt_nombre = $('#txt_nombre').val();
        var cbx_turno_nocturno = $('#cbx_turno_nocturno').prop('checked') ? 1 : 0;
        var txt_valor_trabajar_hora = $('#txt_valor_trabajar_hora').val();
        var txt_valor_trabajar_min = $('#txt_valor_trabajar_min').val();
        var txt_hora_entrada = $('#txt_hora_entrada').val();
        var txt_checkin_registro_inicio = $('#txt_checkin_registro_inicio').val();
        var txt_checkin_registro_fin = $('#txt_checkin_registro_fin').val();
        var txt_limite_tardanza_in = $('#txt_limite_tardanza_in').val();
        var txt_hora_salida = $('#txt_hora_salida').val();
        var txt_checkout_salida_inicio = $('#txt_checkout_salida_inicio').val();
        var txt_checkout_salida_fin = $('#txt_checkout_salida_fin').val();
        var txt_limite_tardanza_out = $('#txt_limite_tardanza_out').val();
        var txt_color = $('#txt_color').val();
        // para los descansos
        var rbx_descanso = $('#rbx_descanso').prop('checked') ? 1 : 0;
        var txt_tiempo_descanso = $('#txt_tiempo_descanso').val();
        var txt_tiempo_descanso_rango = $('#txt_tiempo_descanso_rango').val();
        var txt_hora_descanso_inicio = $('#txt_hora_descanso_inicio').val();
        var txt_hora_descanso_final = $('#txt_hora_descanso_final').val();
        var rbx_aplicar_descanso = $('#rbx_aplicar_descanso').prop('checked') ? 1 : 0;
        var rbx_ninguno = $('#rbx_ninguno').prop('checked') ? 1 : 0;
        var txt_limite_tardanza_descanso_in = $('#txt_limite_tardanza_descanso_in').val();
        var txt_limite_tardanza_descanso_out = $('#txt_limite_tardanza_descanso_out').val();
        // para las horas extra
        var cbx_hora_suple_extra = $('#cbx_hora_suple_extra').prop('checked') ? 1 : 0;
        var txt_hora_extra_inicio = $('#txt_hora_extra_inicio').val();
        var txt_hora_extra_final = $('#txt_hora_extra_final').val();
        var txt_hora_suple_inicio = $('#txt_hora_suple_inicio').val();
        var txt_hora_suple_final = $('#txt_hora_suple_final').val();


        var parametros = {
            '_id': '<?= $_id ?>',
            'txt_nombre': txt_nombre,
            'cbx_turno_nocturno': cbx_turno_nocturno,
            'txt_valor_trabajar_hora': txt_valor_trabajar_hora,
            'txt_valor_trabajar_min': txt_valor_trabajar_min,
            'txt_hora_entrada': txt_hora_entrada,
            'txt_checkin_registro_inicio': txt_checkin_registro_inicio,
            'txt_checkin_registro_fin': txt_checkin_registro_fin,
            'txt_limite_tardanza_in': txt_limite_tardanza_in,
            'txt_hora_salida': txt_hora_salida,
            'txt_checkout_salida_inicio': txt_checkout_salida_inicio,
            'txt_checkout_salida_fin': txt_checkout_salida_fin,
            'txt_limite_tardanza_out': txt_limite_tardanza_out,
            'txt_color': txt_color,
            'rbx_descanso': rbx_descanso,
            'txt_tiempo_descanso': txt_tiempo_descanso,
            'txt_tiempo_descanso_rango': txt_tiempo_descanso_rango,
            'txt_hora_descanso_inicio': txt_hora_descanso_inicio,
            'txt_hora_descanso_final': txt_hora_descanso_final,
            'txt_limite_tardanza_descanso_in': txt_limite_tardanza_descanso_in,
            'txt_limite_tardanza_descanso_out': txt_limite_tardanza_descanso_out,
            'rbx_aplicar_descanso': rbx_aplicar_descanso,
            'cbx_hora_suple_extra': cbx_hora_suple_extra,
            'txt_hora_extra_inicio': txt_hora_extra_inicio,
            'txt_hora_extra_final': txt_hora_extra_final,
            'txt_hora_suple_inicio': txt_hora_suple_inicio,
            'txt_hora_suple_final': txt_hora_suple_final,
            'rbx_ninguno': rbx_ninguno,

        };

        if ($("#form_turnos").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            insertar(parametros);
        }
        //console.log(parametros);

    }

    function insertar(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/th_turnosC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_turnos';
                    });
                } else if (response == -2) {
                    //Swal.fire('', 'El nombre del turno ya está en uso', 'warning');
                    $(txt_nombre).addClass('is-invalid');
                    $('#error_txt_nombre').text('El nombre del turno ya está en uso.');
                }
            },

            error: function(xhr, status, error) {
                console.log('Status: ' + status);
                console.log('Error: ' + error);
                console.log('XHR Response: ' + xhr.responseText);

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });

        $('#txt_nombre').on('input', function() {
            $('#error_txt_nombre').text('');
        });
    }

    function delete_datos() {
        var id = '<?= $_id ?>';
        Swal.fire({
            title: 'Eliminar Registro?',
            text: "Esta seguro de eliminar este registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then((result) => {
            if (result.value) {
                eliminar(id);
            }
        })
    }

    function eliminar(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/th_turnosC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_turnos';
                    });
                }
            }
        });
    }
    $(document).ready(function() {
        $('#txt_hora_descanso_inicio').on('change', function() {
            verificar_descanso();
            calcular_horas_trabajadas();
        });


        $('#txt_tiempo_descanso_rango, #txt_hora_descanso_inicio').on('blur', function() {
            verificar_descanso();
            calcular_horas_trabajadas();
        });

        $('#txt_hora_descanso_inicio, #txt_hora_descanso_final, #txt_hora_suple_inicio, #txt_hora_suple_final, #txt_hora_extra_inicio, #txt_hora_extra_final').on('change', function() {
            actualizarOverlaySlider();
        });

        $('#cbx_hora_suple_extra').change(function() {
            if ($(this).is(':checked')) {

                let horaSalida = $('#txt_hora_salida').val();
                $('#txt_hora_suple_inicio').val(horaSalida);
                $('#txt_hora_suple_final').val("19:00");
                $('#txt_hora_extra_inicio').val("19:00");
                $('#txt_hora_extra_final').val("23:59");

            } else {
                // Limpiar los campos si se desactiva
                $('#txt_hora_suple_inicio').val('');
                $('#txt_hora_suple_final').val('');
                $('#btn_crear_editar_turno').prop('disabled', false);
            }

        });

        $('#txt_hora_suple_inicio').on('blur', validar_Hora_Suple_Fuera_De_Horario);


        //Para la hora de descanso
        $('#rbx_descanso').on('change', function() {
            if ($(this).is(':checked')) {
                $('#pnl_tiempo_descanso').show();
                $('#pnl_aplicar_tiempo_descanso').hide();
                $('#txt_tiempo_descanso').val(30);
                calcular_horas_trabajadas();
            } else {
                $('#pnl_tiempo_descanso').hide();
            }
            calcular_horas_trabajadas();
        });

        $('#rbx_aplicar_descanso').on('change', function() {
            if ($(this).is(':checked')) {
                $('#pnl_aplicar_tiempo_descanso').show();
                $('#pnl_tiempo_descanso').hide();
                $('#txt_tiempo_descanso_rango').val(30);
                $('#txt_limite_tardanza_descanso_in').val(5);
                $('#txt_limite_tardanza_descanso_out').val(5);
                $('#txt_hora_descanso_inicio').val('12:00');
                $('#txt_hora_descanso_final').val('12:30');
                calcular_horas_trabajadas();
                actualizarOverlaySlider();
            } else {
                $('#pnl_aplicar_tiempo_descanso').hide();
            }
            calcular_horas_trabajadas();
        });

        var descanso = 0;

        // Si está seleccionado "descanso simple"
        if ($('#rbx_descanso').is(':checked')) {
            descanso = hora_a_minutos($('#txt_tiempo_descanso').val());
        }
        // Si está seleccionado "descanso con rango"
        else if ($('#rbx_aplicar_descanso').is(':checked')) {
            descanso = hora_a_minutos($('#txt_tiempo_descanso_rango').val());
        }

        $('#rbx_ninguno').on('change', function() {
            if ($(this).is(':checked')) {
                $('#pnl_aplicar_tiempo_descanso').hide();
                $('#pnl_tiempo_descanso').hide();
            } else {
                $('#pnl_aplicar_tiempo_descanso').hide();
            }
        });


        $('#cbx_hora_suple_extra').on('change', function() {
            if ($(this).is(':checked')) {
                $('#pnl_tiempo_suple_extra').show();
                actualizarOverlaySlider();
            } else {
                $('#pnl_tiempo_suple_extra').hide();
            }
        });

    });

    function toHHMM(minutes) {
        let h = Math.floor(minutes / 60);
        let m = minutes % 60;
        return `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}`;
    }

    function validar_Hora_Suple_Fuera_De_Horario() {
        let horaEntrada = $('#txt_hora_entrada').val();
        let horaSalida = $('#txt_hora_salida').val();
        let horaSupleInicio = $('#txt_hora_suple_inicio').val();

        if (!horaEntrada || !horaSalida || !horaSupleInicio) {
            return; // Si falta alguno, no validar aún
        }
        const entradaMin = hora_a_minutos(horaEntrada);
        const salidaMin = hora_a_minutos(horaSalida);
        const supleMin = hora_a_minutos(horaSupleInicio);

        if (supleMin >= entradaMin && supleMin <= salidaMin) {
            mostrarAlerta(
                'error',
                'Hora suple inválida',
                `❌ La hora suplementaria de inicio (${horaSupleInicio}) no puede estar dentro del horario laboral (${horaEntrada} - ${horaSalida}).`
            );
            $('#txt_hora_suple_inicio').val('');
            $('#btn_crear_editar_turno').prop('disabled', true);
        } else {
            $('#btn_crear_editar_turno').prop('disabled', false);
        }
    }

    function verificar_descanso() {
        let horaEntrada = $('#txt_checkin_registro_fin').val();
        let horaSalida = $('#txt_hora_salida').val();
        let descansoInicio = $('#txt_hora_descanso_inicio').val();
        let descansoPermitido = parseInt($('#txt_tiempo_descanso_rango').val(), 10);
        let tolerancia_ingreso = parseInt($('#txt_limite_tardanza_in').val(), 10) || 0;


        if (!horaEntrada || !horaSalida || !descansoInicio || isNaN(descansoPermitido)) {
            $('#btn_crear_editar_turno').prop('disabled', true);
            return;
        }

        // Calcular hora de descanso final automáticamente
        let descansoFinal = sumarMinutos(descansoInicio, descansoPermitido);
        $('#txt_hora_descanso_final').val(descansoFinal);


        let entradaMin = hora_a_minutos(horaEntrada) + tolerancia_ingreso;
        let salidaMin = hora_a_minutos(horaSalida);
        let inicioMin = hora_a_minutos(descansoInicio);
        let finMin = hora_a_minutos(descansoFinal);
        let duracionMin = finMin - inicioMin;

        let errores = [];

        if (inicioMin < entradaMin || inicioMin > salidaMin) {
            errores.push(`El inicio del descanso (${descansoInicio}) debe estar entre ${minutos_formato_hora(entradaMin)} y ${horaSalida}.`);
        }
        if (finMin < entradaMin || finMin > salidaMin) {
            errores.push(`El fin del descanso (${descansoFinal}) debe estar entre ${minutos_formato_hora(entradaMin)} y ${horaSalida}.`);
        }
        if (finMin <= inicioMin) {
            errores.push(`La hora de fin de descanso (${descansoFinal}) debe ser posterior a la de inicio (${descansoInicio}).`);
        }
        if (duracionMin > descansoPermitido) {
            errores.push(`La duración del descanso (${duracionMin} min) excede el máximo permitido (${descansoPermitido} min).`);
        }

        if (errores.length) {
            mostrarAlerta('error', 'Error en configuración de descanso', errores.map(e => `❌ ${e}`).join('<br>'));
            $('#btn_crear_editar_turno').prop('disabled', true);
            $('#txt_hora_descanso_inicio').val('');
            $('#txt_hora_descanso_final').val('');
        } else {
            $('#btn_crear_editar_turno').prop('disabled', false);
        }
    }

    function minutos_formato_hora(minutos) {
        let h = Math.floor(minutos / 60);
        let m = minutos % 60;
        return (h < 10 ? '0' : '') + h + ':' + (m < 10 ? '0' : '') + m;
    }


    function sumarMinutos(hora, minutos) {
        let [h, m] = hora.split(':').map(Number);
        let total = h * 60 + m + minutos;
        let nuevaH = Math.floor(total / 60) % 24;
        let nuevaM = total % 60;
        return nuevaH.toString().padStart(2, '0') + ':' + nuevaM.toString().padStart(2, '0');
    }

    function mostrarAlerta(tipo, titulo, htmlMensaje) {
        Swal.fire({
            icon: tipo, // 'success' o 'error'
            title: titulo,
            html: htmlMensaje,
            confirmButtonText: 'Entendido'
        });
    }
</script>

<!-- Slider -->
<style>
    /* Cambiar color del rango seleccionado */
    .irs .irs-from,
    .irs .irs-to,
    .irs .irs-single {
        background: #45C4B0;
        /* Color pastel de fondo del rango */
        color: #012030;
        /* Color del texto negro para contraste */
    }

    /* Cambiar color del rango entre los valores seleccionados */
    .irs .irs-slider,
    .irs .irs-min,
    .irs .irs-max {
        background: #DAFDBA;
        color: #012030;
    }

    /* Cambiar color de la barra del rango */
    .irs .irs-bar {
        background: #13678A;
    }

    /* Cambiar color del controlador (handle) */
    .irs .irs-handle {
        background: #13678A;
        /* Color para el controlador */
        border: 0.1px #13678A !important;
        ;
        /* Color de borde para el controlador */
    }

    /* Cambiar color del controlador en la última posición (type_last) */
    .irs .irs-handle.type_last {
        background: #13678A;
        /* Color para el controlador final */
        border: 0.1px #13678A !important;
        ;
        /* Color de borde para el controlador final */
    }

    /**
    CSS Adicional

    */

    input[type="time"].is-valid {
        background-image: none !important;
        /* Oculta el ícono */
        padding-right: 8px !important;
        /* Elimina el espacio reservado para el ícono */
    }

    .input-group .form-control {
        border-top-right-radius: 0;
        /* Elimina el borde redondeado derecho */
        border-bottom-right-radius: 0;
        /* Elimina el borde redondeado derecho */
    }

    .input-group .input-group-text {
        border-top-left-radius: 0;
        /* Elimina el borde redondeado izquierdo */
        border-bottom-left-radius: 0;
        /* Elimina el borde redondeado izquierdo */
    }
</style>

<!-- Color -->
<style>
    .pcr-button {
        width: 100% !important;
        height: 100% !important;
        padding: 0 !important;
        /* border-radius: 10px !important; */
        /* border: 1px solid #000 !important; */
        height: 31px !important;
        box-sizing: border-box;
    }
</style>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Turnos</div>
            <?php
            //print_r($_SESSION['INICIO']);die(); 

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Agregar Turno
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <form id="form_turnos">
            <!--end breadcrumb-->
            <div class="row pb-2">
                <div class="col-xl-12 mx-auto">
                    <div class="card border-top border-0 border-4 border-primary mb-0">
                        <div class="card-body p-5">
                            <div class="card-title d-flex align-items-center">

                                <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                                </div>
                                <h5 class="mb-0 text-primary">
                                    <?php
                                    if ($_id == '') {
                                        echo 'Registrar Turno';
                                    } else {
                                        echo 'Modificar Turno';
                                    }
                                    ?>
                                </h5>

                                <div class="row m-2">
                                    <div class="col-sm-12">
                                        <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_turnos" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                    </div>
                                </div>
                            </div>

                            <hr>


                            <div class="row pt-3 mb-col">
                                <div class="col-md-12">
                                    <label for="txt_nombre" class="form-label">Nombre del turno </label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" name="txt_nombre" id="txt_nombre" maxlength="50">
                                    <span id="error_txt_nombre" class="text-danger"></span>
                                </div>
                            </div>

                            <div class="row mb-col">

                                <div class="col-md-9">
                                    <label for="txt_color" class="form-label">Color picker</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div id="color-picker"></div>
                                        </div>
                                        <div class="col-md-6" hidden>
                                            <input type="text" class="form-control form-control-sm no_caracteres" name="txt_color" id="txt_color" maxlength="50" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-col">
                                <div class="col-md-6">
                                    <label for="value" class="form-label">Mínimo de Horas Trabajadas</label>
                                    <div class="row mb-col">
                                        <div class="col-md-3">
                                            <div class="">
                                                <input type="number" class="form-control form-control-sm" name="txt_valor_trabajar_hora" id="txt_valor_trabajar_hora" value="8" readonly>
                                                <label class="" for="txt_valor_trabajar_hora">hora(s)</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="">
                                                <input type="number" class="form-control form-control-sm" name="txt_valor_trabajar_min" id="txt_valor_trabajar_min" value="30" readonly>
                                                <label class="" for="txt_valor_trabajar_min">min</label>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <hr>

                            <div class="row mb-col pt-3" id="pnl_slider_hora_dia">
                                <input id="slider_hora_dia" type="text" />
                            </div>

                            <div class="row mb-col pt-3" id="pnl_slider_hora_dia_48">
                                <input id="slider_hora_dia_48" type="text" />
                            </div>
                            <hr>
                            <div class="row pt-4">
                                <div class="col-md-11">
                                    <div class="row mb-col">
                                        <label for="txt_hora_entrada" class="col-sm-4 col-form-label text-end fw-bold">Horario de trabajo asignado </label>

                                        <div class="col-sm-3">
                                            <input type="time" class="form-control form-control-sm" name="txt_hora_entrada" id="txt_hora_entrada" value="07:00">
                                        </div>

                                        <div class="col-sm-3">
                                            <input type="time" class="form-control form-control-sm" name="txt_hora_salida" id="txt_hora_salida" value="15:30">
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-check ">
                                                <input type="checkbox" class="form-check-input" name="cbx_turno_nocturno" id="cbx_turno_nocturno">
                                                <label class="form-check-label" for="cbx_turno_nocturno">Finaliza el día siguiente</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row pb-2">
                <div class="container mt-4">
                    <div class="row">
                        <!-- Card izquierda: Registro de Entrada -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="mb-0">Registro de Entrada</h5>
                                </div>
                                <div class="card-body">
                                    <!-- Rango válido entrada -->
                                    <div class="mb-3 row align-items-center">
                                        <label for="txt_checkin_registro_inicio" class="col-sm-5 col-form-label fw-bold text-end">
                                            Rango válido entrada:
                                        </label>
                                        <div class="col-sm-3">
                                            <input type="time" class="form-control form-control-sm" id="txt_checkin_registro_inicio" name="txt_checkin_registro_inicio" value="06:30">
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="time" class="form-control form-control-sm" id="txt_checkin_registro_fin" name="txt_checkin_registro_fin" value="07:30">
                                        </div>
                                    </div>

                                    <!-- Tolerancia llegada tarde -->
                                    <div class="mb-3 row align-items-center">
                                        <label for="txt_limite_tardanza_in" class="col-sm-5 col-form-label fw-bold text-end">
                                            Tolerancia llegada tarde (min):
                                        </label>
                                        <div class="col-sm-6">
                                            <input type="number" class="form-control form-control-sm" id="txt_limite_tardanza_in" name="txt_limite_tardanza_in" value="5">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card derecha: Registro de Salida -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="mb-0">Registro de Salida</h5>
                                </div>
                                <div class="card-body">
                                    <!-- Rango válido salida -->
                                    <div class="mb-3 row align-items-center">
                                        <label for="txt_checkout_salida_inicio" class="col-sm-5 col-form-label fw-bold text-end">
                                            Rango válido salida:
                                        </label>
                                        <div class="col-sm-3">
                                            <input type="time" class="form-control form-control-sm" id="txt_checkout_salida_inicio" name="txt_checkout_salida_inicio" value="15:00">
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="time" class="form-control form-control-sm" id="txt_checkout_salida_fin" name="txt_checkout_salida_fin" value="16:00">
                                        </div>
                                    </div>

                                    <!-- Tolerancia salida anticipada -->
                                    <div class="mb-3 row align-items-center">
                                        <label for="txt_limite_tardanza_out" class="col-sm-5 col-form-label fw-bold text-end">
                                            Tolerancia salida anticipada (min):
                                        </label>
                                        <div class="col-sm-6">
                                            <input type="number" class="form-control form-control-sm" id="txt_limite_tardanza_out" name="txt_limite_tardanza_out" value="5">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="container mt-4">
                    <div class="row">
                        <!-- Columna Descanso -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="mb-0">Descanso</h5>
                                </div>
                                <div class="card-body">
                                    <!-- Opciones de descanso -->
                                    <div class="mb-3 row">
                                        <label class="col-sm-5 col-form-label fw-bold text-end">¿Usar descanso?</label>
                                        <div class="col-sm-7">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="descanso_opcion" id="rbx_ninguno" value="3" checked>
                                                <label class="form-check-label" for="rbx_ninguno">No aplicar descanso</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="descanso_opcion" id="rbx_descanso" value="1">
                                                <label class="form-check-label" for="rbx_descanso">Sí, aplicar descanso</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="descanso_opcion" id="rbx_aplicar_descanso" value="2">
                                                <label class="form-check-label" for="rbx_aplicar_descanso">Sí, aplicar descanso con rangos</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Panel descanso simple -->
                                    <div id="pnl_tiempo_descanso" style="display: none;">
                                        <div class="mb-3 row">
                                            <label for="txt_tiempo_descanso" class="col-sm-5 col-form-label fw-bold text-end">Hora de descanso (min)</label>
                                            <div class="col-sm-7">
                                                <input type="number" class="form-control form-control-sm" id="txt_tiempo_descanso" name="txt_tiempo_descanso">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Panel descanso con rangos -->
                                    <div id="pnl_aplicar_tiempo_descanso" style="display: none;">
                                        <div class="mb-3 row">
                                            <label for="txt_tiempo_descanso_rango" class="col-sm-5 col-form-label fw-bold text-end">Hora de descanso (min)</label>
                                            <div class="col-sm-7">
                                                <input type="number" class="form-control form-control-sm" id="txt_tiempo_descanso_rango" name="txt_tiempo_descanso_rango">
                                            </div>
                                        </div>

                                        <div class="mb-3 row">
                                            <label class="col-sm-5 col-form-label fw-bold text-end">Horario de descanso</label>
                                            <div class="col-sm-3">
                                                <input type="time" class="form-control form-control-sm" id="txt_hora_descanso_inicio" name="txt_hora_descanso_inicio" value="10:00">
                                                <small class="text-muted">Inicio</small>
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="time" class="form-control form-control-sm" id="txt_hora_descanso_final" name="txt_hora_descanso_final" disabled>
                                                <small class="text-muted">Final</small>
                                            </div>
                                        </div>

                                        <div class="mb-3 row">
                                            <label for="txt_limite_tardanza_descanso_in" class="col-sm-5 col-form-label fw-bold text-end">Tolerancia llegada temprana (min)</label>
                                            <div class="col-sm-7">
                                                <input type="number" class="form-control form-control-sm" id="txt_limite_tardanza_descanso_in" name="txt_limite_tardanza_descanso_in" value="5">
                                            </div>
                                        </div>

                                        <div class="mb-3 row">
                                            <label for="txt_limite_tardanza_descanso_out" class="col-sm-5 col-form-label fw-bold text-end">Tolerancia llegada tarde (min)</label>
                                            <div class="col-sm-7">
                                                <input type="number" class="form-control form-control-sm" id="txt_limite_tardanza_descanso_out" name="txt_limite_tardanza_descanso_out" value="5">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Columna Tiempo Extra -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="mb-0">Tiempo Extra</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3 row">
                                        <label class="col-sm-5 col-form-label fw-bold text-end">¿Usar tiempo extra?</label>
                                        <div class="col-sm-7">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="cbx_hora_suple_extra" name="cbx_hora_suple_extra" value="1">
                                                <label class="form-check-label" for="cbx_hora_suple_extra">Sí, aplicar tiempo suplementario y extraordinario</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Panel tiempo extra -->
                                    <div id="pnl_tiempo_suple_extra" style="display: none;">
                                        <div class="mb-3 row">
                                            <label class="col-sm-5 col-form-label fw-bold text-end">Horas suplementarias</label>
                                            <div class="col-sm-3">
                                                <input type="time" class="form-control form-control-sm" id="txt_hora_suple_inicio" name="txt_hora_suple_inicio">
                                                <small class="text-muted">Inicio</small>
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="time" class="form-control form-control-sm" id="txt_hora_suple_final" name="txt_hora_suple_final" disabled>
                                                <small class="text-muted">Final</small>
                                            </div>
                                        </div>

                                        <div class="mb-3 row">
                                            <label class="col-sm-5 col-form-label fw-bold text-end">Horas extraordinarias</label>
                                            <div class="col-sm-3">
                                                <input type="time" class="form-control form-control-sm" id="txt_hora_extra_inicio" name="txt_hora_extra_inicio" disabled>
                                                <small class="text-muted">Inicio</small>
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="time" class="form-control form-control-sm" id="txt_hora_extra_final" name="txt_hora_extra_final" disabled>
                                                <small class="text-muted">Final</small>
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

    <div class="d-flex justify-content-center pt-2 pb-4">

        <?php if ($_id == '') { ?>
            <button id="btn_crear_editar_turno" class="btn btn-success btn-sm px-4 m-0" onclick="editar_insertar();" type="button"><i class="bx bx-save"></i> Guardar</button>
        <?php } else { ?>
            <button id="btn_crear_editar_turno" class="btn btn-success btn-sm px-4 m-1" onclick="editar_insertar();" type="button"><i class="bx bx-save"></i> Editar</button>
            <button class="btn btn-danger btn-sm px-4 m-1" onclick="delete_datos();" type="button"><i class="bx bx-trash"></i> Eliminar</button>
        <?php } ?>
    </div>
    </form>



    <script>
        //Validacion de formulario
        $(document).ready(function() {
            // Selecciona el label existente y añade el nuevo label
            agregar_asterisco_campo_obligatorio('txt_nombre');
            agregar_asterisco_campo_obligatorio('txt_hora_entrada');
            agregar_asterisco_campo_obligatorio('txt_checkin_registro_inicio');
            agregar_asterisco_campo_obligatorio('txt_checkout_salida_inicio');
            agregar_asterisco_campo_obligatorio('txt_limite_tardanza_in');
            agregar_asterisco_campo_obligatorio('txt_limite_tardanza_out');

            $("#form_turnos").validate({
                rules: {
                    txt_nombre: {
                        required: true,
                    },

                    txt_hora_entrada: {
                        //required: true,
                    },

                    txt_hora_salida: {
                        //required: true,
                    },
                },
                messages: {
                    txt_nombre: {
                        required: "El campo 'Nombre' es obligatorio",
                    },
                },

                highlight: function(element) {
                    // Agrega la clase 'is-invalid' al input que falla la validación
                    $(element).addClass('is-invalid');
                    $(element).removeClass('is-valid');
                },
                unhighlight: function(element) {
                    // Elimina la clase 'is-invalid' si la validación pasa
                    $(element).removeClass('is-invalid');
                    $(element).addClass('is-valid');
                }
            });
        });
    </script>

    <script src="../lib/ion-rangeSlider/ion.rangeSlider.min.js"></script>

    <!-- Para el slider -->
    <script>
        $(document).ready(function() {
            hora_entrada = '<?= $hora_entrada ?>' ?? 420;
            hora_salida = '<?= $hora_salida ?>' ?? 930;

            //Slider para las 24 horas
            slider_hora_dia = $("#slider_hora_dia").ionRangeSlider({
                min: 0,
                max: 1439, // 23 horas y 59 minutos
                from: hora_entrada, // Hora de inicio
                to: hora_salida, // Hora de finalización
                step: 1,
                grid: true,
                grid_num: 24, // Mostrar las horas, saltando de 59 en 59 minutos
                type: "double", // Tipo de rango doble
                prettify: function(num) {
                    // Cálculo de horas y minutos
                    let hours = Math.floor(num / 60); // Obtener horas
                    let minutes = num % 60; // Obtener minutos
                    // Formatear horas y minutos
                    return (hours < 10 ? '0' : '') + hours + ':' + (minutes < 10 ? '0' : '') + minutes;
                },

                onFinish: function(data) {
                    $('#txt_hora_entrada').val(minutos_formato_hora(data.from));
                    $('#txt_checkin_registro_inicio').val(minutos_formato_hora((data.from) - 30 * 1));
                    $('#txt_checkin_registro_fin').val(minutos_formato_hora((data.from) + (30 * 1)));

                    $('#txt_hora_salida').val(minutos_formato_hora(data.to));
                    $('#txt_checkout_salida_inicio').val(minutos_formato_hora((data.to) - 30 * 1));
                    $('#txt_checkout_salida_fin').val(minutos_formato_hora((data.to) + (30 * 1)));
                    calcular_horas_trabajadas();

                    //console.log(minutos_formato_hora(data.to));
                }
            });

            //Slider para las 48 horas
            slider_hora_dia_48 = $("#slider_hora_dia_48").ionRangeSlider({
                min: 0,
                max: 2879, // Hasta 47:59 (48 horas en minutos)
                from: hora_entrada,
                to: hora_salida,
                step: 1,
                grid: true,
                grid_num: 48,
                type: "double",
                prettify: function(num) {
                    // Convertir el tiempo en un formato de reloj de 24 horas
                    num = num % 1440; // Restringir el valor al rango de 0-1440 minutos (24 horas)
                    let hours = Math.floor(num / 60);
                    let minutes = num % 60;
                    return (hours < 10 ? '0' : '') + hours + ':' + (minutes < 10 ? '0' : '') + minutes;
                },

                onFinish: function(data) {
                    if (data.from > 1439) {
                        $("#slider_hora_dia_48").data('ionRangeSlider').update({
                            from: 1439
                        });
                    }

                    if (data.to < 1440) {
                        $("#slider_hora_dia_48").data('ionRangeSlider').update({
                            to: 1440
                        });
                    }

                    // Actualizar los valores de los campos de entrada con el formato correcto
                    $('#txt_hora_entrada').val(minutos_formato_hora(data.from % 1440));
                    $('#txt_checkin_registro_inicio').val(minutos_formato_hora((data.from % 1440) - 30));
                    $('#txt_checkin_registro_fin').val(minutos_formato_hora((data.from % 1440) + 30));

                    $('#txt_hora_salida').val(minutos_formato_hora(data.to % 1440));
                    $('#txt_checkout_salida_inicio').val(minutos_formato_hora((data.to % 1440) - 30));
                    $('#txt_checkout_salida_fin').val(minutos_formato_hora((data.to % 1440) + 30));
                    calcular_horas_trabajadas();
                }
            });

            $('#txt_hora_entrada, #txt_hora_salida').on('change', actualizar_slider);
            $('#txt_hora_entrada, #txt_hora_salida').on('change', actualizar_slider_48);

        });

        function actualizarOverlaySlider() {
            const descansoIni = $('#txt_hora_descanso_inicio').val();
            const descansoFin = $('#txt_hora_descanso_final').val();
            const supleIni = $('#txt_hora_suple_inicio').val();
            const supleFin = $('#txt_hora_suple_final').val();
            const extraIni = $('#txt_hora_extra_inicio').val();
            const extraFin = $('#txt_hora_extra_final').val();

            // Convertir a minutos
            const hora_a_minutos = (h) => {
                if (!h) return null;
                let [hh, mm] = h.split(':').map(Number);
                return hh * 60 + mm;
            }

            const overlayRanges = [{
                    id: 'descanso',
                    from: hora_a_minutos(descansoIni),
                    to: hora_a_minutos(descansoFin),
                    color: '#FFA726'
                }, // naranja
                {
                    id: 'suple',
                    from: hora_a_minutos(supleIni),
                    to: hora_a_minutos(supleFin),
                    color: '#42A5F5'
                }, // azul
                {
                    id: 'extra',
                    from: hora_a_minutos(extraIni),
                    to: hora_a_minutos(extraFin),
                    color: '#AB47BC'
                }, // morado
            ];

            // Limpiar previos
            $('.irs-overlay-range').remove();

            // Obtener el slider real
            const sliderBar = $('.irs-bar');

            overlayRanges.forEach(r => {
                if (r.from != null && r.to != null && r.to > r.from) {
                    let percentFrom = (r.from / 1440) * 100;
                    let percentTo = (r.to / 1440) * 100;
                    let width = percentTo - percentFrom;

                    let overlay = $('<div>', {
                        class: 'irs-overlay-range',
                        css: {
                            left: `${percentFrom}%`,
                            width: `${width}%`,
                            background: r.color,
                            position: 'absolute',
                            height: '100%',
                            top: 0,
                            zIndex: 1,
                            opacity: 0.4,
                            borderRadius: '2px'
                        }
                    });
                    sliderBar.parent().append(overlay);
                }
            });
        }

        //Para acualizar el slider cuando se cambia en los inputs
        function actualizar_slider() {
            let hora_entrada_min = hora_a_minutos($('#txt_hora_entrada').val());
            let hora_salida_min = hora_a_minutos($('#txt_hora_salida').val());

            let slider_instancia = slider_hora_dia.data("ionRangeSlider");

            slider_instancia.update({
                from: hora_entrada_min,
                to: hora_salida_min
            });
        }

        //Para acualizar el slider cuando se cambia en los inputs
        function actualizar_slider_48() {
            let hora_entrada_min = hora_a_minutos($('#txt_hora_entrada').val());
            let hora_salida_min = hora_a_minutos($('#txt_hora_salida').val());

            let slider_instancia_48 = slider_hora_dia_48.data("ionRangeSlider");

            slider_instancia_48.update({
                from: hora_entrada_min,
                to: hora_salida_min + 1440
            });
        }

        //Para cuando se cambia de slider
        function establecer_hora_defecto() {
            let habilitar_48_horas = $('#cbx_turno_nocturno').is(':checked');

            if (habilitar_48_horas) {
                let slider_instancia_48 = slider_hora_dia_48.data("ionRangeSlider");

                slider_instancia_48.update({
                    from: 1200,
                    to: 1680
                });

                $('#txt_hora_entrada').val('20:00');
                $('#txt_hora_salida').val('04:00');
            } else {
                let slider_instancia = slider_hora_dia.data("ionRangeSlider");

                slider_instancia.update({
                    from: 420,
                    to: 930
                });

                $('#txt_hora_entrada').val('07:00');
                $('#txt_hora_salida').val('15:30');
            }
            calcular_horas_trabajadas();
        }
    </script>

    <!-- Para tomar el color -->
    <script>
        function color_input(txt_color) {
            pickr = Pickr.create({
                el: '#color-picker',
                theme: 'nano',
                default: txt_color, // Color por defecto
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
                        //hsla: true,
                        //hsva: true,
                        //cmyk: true,
                        input: true,
                        clear: true,
                        save: true
                    },
                },

                i18n: {
                    'btn:save': 'Guardar',
                    'btn:cancel': 'Cancelar',
                    'btn:clear': 'Limpiar',
                }
            });

            pickr.on('change', (color) => {
                hex_color = color.toHEXA().toString();
                $txt_color = $('#txt_color').val(hex_color);
            });
        }
    </script>


    <!-- Validaciones de hora -->
    <script>
        $(document).ready(function() {


            //Para activar segundo slider con 48 horas
            $('#pnl_slider_hora_dia_48').hide();
            $('#cbx_turno_nocturno').on('change', function() {
                var es_turno_nocturno = $(this).is(':checked');

                $('#pnl_slider_hora_dia').toggle(!es_turno_nocturno);
                $('#pnl_slider_hora_dia_48').toggle(es_turno_nocturno);

                // Establecer las horas por defecto
                establecer_hora_defecto();
            });

            $('#txt_hora_entrada, #txt_hora_salida, #txt_tiempo_descanso').on('change', function() {
                calcular_horas_trabajadas();
            });

            // Validar que `txt_checkin_registro_inicio` no sea mayor a `txt_hora_entrada`
            $('#txt_checkin_registro_inicio').on('blur', function() {
                let checkin_inicio_min = hora_a_minutos($(this).val());
                let hora_entrada_min = hora_a_minutos($('#txt_hora_entrada').val());

                if (checkin_inicio_min > hora_entrada_min) {
                    $(this).val(minutos_formato_hora((hora_entrada_min) - 30 * 1));
                    Swal.fire('', 'El tiempo de check-in inicio no puede ser mayor que la hora de entrada.', 'warning');
                }
            });

            // Validar que `txt_checkin_registro_fin` no sea mayor a `txt_hora_entrada`
            $('#txt_checkin_registro_fin').on('blur', function() {
                let checkin_fin_min = hora_a_minutos($(this).val());
                let hora_entrada_min = hora_a_minutos($('#txt_hora_entrada').val());
                let hora_salida_min = hora_a_minutos($('#txt_hora_salida').val());

                if (checkin_fin_min >= hora_salida_min) {
                    $(this).val(minutos_formato_hora((hora_entrada_min) + 30 * 1));
                    Swal.fire('', 'El tiempo de check-in fin no puede ser mayor que la hora de salida', 'warning');
                }

                if (checkin_fin_min < hora_entrada_min) {
                    $(this).val(minutos_formato_hora((hora_entrada_min) + 30 * 1));
                    Swal.fire('', 'El tiempo de check-in fin no puede ser menor a la hora de entrada.', 'warning');
                }
            });

            // Validar que `txt_checkout_registro_inicio` no sea mayor a `txt_hora_entrada`
            $('#txt_checkout_salida_inicio').on('blur', function() {
                let checkout_inicio_min = hora_a_minutos($(this).val());
                let hora_salida_min = hora_a_minutos($('#txt_hora_salida').val());
                let checkin_fin_min = hora_a_minutos($('#txt_checkin_registro_fin').val());

                if (checkout_inicio_min <= checkin_fin_min) {
                    $(this).val(minutos_formato_hora((hora_salida_min) - 30 * 1));
                    Swal.fire('', 'El tiempo de check-out inicio no puede ser menos que el check-in fin.', 'warning');
                    return;
                }

                if (checkout_inicio_min > hora_salida_min) {
                    $(this).val(minutos_formato_hora((hora_salida_min) - 30 * 1));
                    Swal.fire('', 'El tiempo de check-out inicio no puede ser mayor que la hora de salida.', 'warning');
                }

            });

            // Validar que `txt_checkout_registro_fin` no sea mayor a `txt_hora_entrada`
            $('#txt_checkout_salida_fin').on('blur', function() {
                let checkout_fin_min = hora_a_minutos($(this).val());
                let hora_salida_min = hora_a_minutos($('#txt_hora_salida').val());

                if (checkout_fin_min < hora_salida_min) {
                    $(this).val(minutos_formato_hora((hora_salida_min) + 30 * 1));
                    Swal.fire('', 'El tiempo de check-out fin no puede ser menor que la hora de salida', 'warning');
                }
            });


        });

        //Validacion para 24 y 48 horas
        function calcular_horas_trabajadas() {
            var hora_entrada_min = hora_a_minutos($('#txt_hora_entrada').val());
            var hora_salida_min = hora_a_minutos($('#txt_hora_salida').val());
            var descanso = 0;

            // Detectar tipo de descanso seleccionado
            if ($('#rbx_descanso').is(':checked')) {
                descanso = parseInt($('#txt_tiempo_descanso').val()) || 0;
            } else if ($('#rbx_aplicar_descanso').is(':checked')) {
                descanso = parseInt($('#txt_tiempo_descanso_rango').val()) || 0;
            }

            var habilitar_48_horas = $('#cbx_turno_nocturno').is(':checked');

            if (hora_entrada_min !== null && hora_salida_min !== null) {
                var diferencia = hora_salida_min - hora_entrada_min;

                if (diferencia < 0) {
                    diferencia += 24 * 60;
                }

                // Restar descanso si aplica
                if (descanso && descanso > 0) {
                    diferencia -= descanso;
                }

                if (habilitar_48_horas && hora_salida_min >= hora_entrada_min) {
                    diferencia += 24 * 60;
                }

                var horas = Math.floor(diferencia / 60);
                var minutos = diferencia % 60;

                if (minutos < 0) {
                    minutos += 60;
                    horas -= 1;
                }

                if (horas < 0) horas = 0;
                if (minutos < 0) minutos = 0;

                // Actualizar los campos con el resultado
                $('#txt_valor_trabajar_hora').val(horas);
                $('#txt_valor_trabajar_min').val(minutos);

                // Calculo para check-in y check-out
                $('#txt_checkin_registro_inicio').val(minutos_formato_hora(hora_entrada_min - 30));
                $('#txt_checkin_registro_fin').val(minutos_formato_hora(hora_entrada_min + 30));
                $('#txt_checkout_salida_inicio').val(minutos_formato_hora(hora_salida_min - 30));
                $('#txt_checkout_salida_fin').val(minutos_formato_hora(hora_salida_min + 30));
            } else {
                $('#txt_valor_trabajar_hora').val("");
                $('#txt_valor_trabajar_min').val("");
            }
        }
    </script>