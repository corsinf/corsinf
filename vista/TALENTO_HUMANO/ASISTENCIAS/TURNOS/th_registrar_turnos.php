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
                $('#cbx_descanso').prop('checked', (response[0].descanso == 1));
                $('#txt_tiempo_descanso').val(minutos_formato_hora(response[0].hora_descanso));

                if (response[0].descanso == 1) {
                    $('#pnl_tiempo_descanso').show();
                }

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
        var cbx_descanso = $('#cbx_descanso').prop('checked') ? 1 : 0;
        var txt_tiempo_descanso = $('#txt_tiempo_descanso').val();

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
            'cbx_descanso': cbx_descanso,
            'txt_tiempo_descanso': txt_tiempo_descanso,

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
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
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

                        <form id="form_turnos">

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
                                                <label class="" id="txt_valor_trabajar_hora">hora(s)</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="">
                                                <input type="number" class="form-control form-control-sm" name="txt_valor_trabajar_min" id="txt_valor_trabajar_min" value="30" readonly>
                                                <label class="" id="txt_valor_trabajar_min">min</label>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row mb-col">
                                        <div class="col-md-6">
                                            <div class="form-check ">
                                                <input type="checkbox" class="form-check-input" name="cbx_descanso" id="cbx_descanso">
                                                <label class="form-check-label" for="cbx_descanso">Descanso</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-col" id="pnl_tiempo_descanso" style="display: none;">
                                        <div class="col-md-6">
                                            <label for="txt_nombre" class="form-label">Tiempo de descanso </label>
                                            <input type="time" class="form-control form-control-sm" name="txt_tiempo_descanso" id="txt_tiempo_descanso" value="00:00">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <hr>

                            <div class="row mb-col pt-3">
                                <input id="slider_hora_dia" type="text" />
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


                                    <div class="row mb-col">
                                        <label for="txt_checkin_registro_inicio" class="col-sm-4 col-form-label text-end fw-bold">Rango de tiempo válido para el registro de entrada </label>

                                        <div class="col-sm-3">
                                            <input type="time" class="form-control form-control-sm" name="txt_checkin_registro_inicio" id="txt_checkin_registro_inicio" value="06:30">
                                        </div>

                                        <div class="col-sm-3">
                                            <input type="time" class="form-control form-control-sm" name="txt_checkin_registro_fin" id="txt_checkin_registro_fin" value="07:30">
                                        </div>

                                        <!-- <div class="col-md-2">
                                            <div class="form-check ">
                                                <input type="checkbox" class="form-check-input" name="cbx_checkin_late" id="cbx_checkin_late">
                                                <label class="form-check-label" for="cbx_checkin_late">Finaliza el día siguiente</label>
                                            </div>
                                        </div> -->
                                    </div>

                                    <div class="row mb-col">
                                        <label for="txt_checkout_salida_inicio" class="col-sm-4 col-form-label text-end fw-bold">Rango de tiempo válido para el registro de salida </label>

                                        <div class="col-sm-3">
                                            <input type="time" class="form-control form-control-sm" name="txt_checkout_salida_inicio" id="txt_checkout_salida_inicio" value="15:00">
                                        </div>

                                        <div class="col-sm-3">
                                            <input type="time" class="form-control form-control-sm" name="txt_checkout_salida_fin" id="txt_checkout_salida_fin" value="16:00">
                                        </div>

                                        <!-- <div class="col-md-2">
                                            <div class="form-check ">
                                                <input type="checkbox" class="form-check-input" name="cbx_checkout_late" id="cbx_checkout_late">
                                                <label class="form-check-label" for="cbx_checkout_late">Finaliza el día siguiente</label>
                                            </div>
                                        </div> -->
                                    </div>

                                    <div class="row mb-col">
                                        <label for="txt_limite_tardanza_in" class="col-sm-4 col-form-label text-end fw-bold">Tolerancia de llegada tarde (min) </label>
                                        <div class="col-sm-6">
                                            <input type="number" class="form-control form-control-sm" name="txt_limite_tardanza_in" id="txt_limite_tardanza_in" value="5">
                                        </div>
                                    </div>

                                    <div class="row mb-col">
                                        <label for="txt_limite_tardanza_out" class="col-sm-4 col-form-label text-end fw-bold">Tolerancia de salida anticipada (min) </label>
                                        <div class="col-sm-6">
                                            <input type="number" class="form-control form-control-sm" name="txt_limite_tardanza_out" id="txt_limite_tardanza_out" value="5">
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <div class="d-flex justify-content-end pt-2">

                                <?php if ($_id == '') { ?>
                                    <button class="btn btn-success btn-sm px-4 m-0" onclick="editar_insertar();" type="button"><i class="bx bx-save"></i> Guardar</button>
                                <?php } else { ?>
                                    <button class="btn btn-success btn-sm px-4 m-1" onclick="editar_insertar();" type="button"><i class="bx bx-save"></i> Editar</button>
                                    <button class="btn btn-danger btn-sm px-4 m-1" onclick="delete_datos();" type="button"><i class="bx bx-trash"></i> Eliminar</button>
                                <?php } ?>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>


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
        slider_hora_dia = $("#slider_hora_dia").ionRangeSlider({
            min: 0,
            max: 2879, // 23 horas y 59 minutos
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

        $('#txt_hora_entrada, #txt_hora_salida').on('change', actualizar_slider);

    });

    /* $(document).ready(function() {
        let hora_entrada = '<?= $hora_entrada ?>' ?? 420;
        let hora_salida = '<?= $hora_salida ?>' ?? 930;

        slider_hora_dia = $("#slider_hora_dia").ionRangeSlider({
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
                    $("#slider_hora_dia").data('ionRangeSlider').update({
                        from: 1439
                    });
                }

                if (data.to < 1440) {
                    $("#slider_hora_dia").data('ionRangeSlider').update({
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
    }); */

    function actualizar_slider() {
        let hora_entrada_min = hora_a_minutos($('#txt_hora_entrada').val());
        let hora_salida_min = hora_a_minutos($('#txt_hora_salida').val());

        let slider_instancia = slider_hora_dia.data("ionRangeSlider");

        slider_instancia.update({
            from: hora_entrada_min,
            to: hora_salida_min
        });
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
        $('#cbx_descanso').on('change', function() {
            if ($(this).is(':checked')) {
                $('#pnl_tiempo_descanso').show();
            } else {
                $('#pnl_tiempo_descanso').hide();
                $('#txt_tiempo_descanso').val(`00:00`);
                calcular_horas_trabajadas();
            }
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

    function calcular_horas_trabajadas() {
        var entrada = $('#txt_hora_entrada').val();
        var salida = $('#txt_hora_salida').val();
        var descanso = $('#txt_tiempo_descanso').val();

        if (entrada && salida) {
            hora_entrada = new Date(`1970-01-01T${entrada}:00`);
            hora_salida = new Date(`1970-01-01T${salida}:00`);

            diferencia = hora_salida.getTime() - hora_entrada.getTime();

            if (diferencia < 0) {
                diferencia += 24 * 60 * 60 * 1000;
            }

            horas = Math.floor(diferencia / (1000 * 60 * 60));
            minutos = Math.floor((diferencia % (1000 * 60 * 60)) / (1000 * 60));

            if (descanso && descanso !== "00:00") {
                [descanso_horas, descanso_minutos] = descanso.split(':').map(Number);

                minutos -= descanso_minutos;
                if (minutos < 0) {
                    minutos += 60;
                    horas -= 1;
                }

                horas -= descanso_horas;
            }

            if (horas < 0) horas = 0;
            if (minutos < 0) minutos = 0;

            $('#txt_valor_trabajar_hora').val(`${horas}`);
            $('#txt_valor_trabajar_min').val(`${minutos}`);

            //Calculo para checkin y checkout
            let hora_entrada_min = hora_a_minutos($('#txt_hora_entrada').val());
            let hora_salida_min = hora_a_minutos($('#txt_hora_salida').val());

            $('#txt_checkin_registro_inicio').val(minutos_formato_hora((hora_entrada_min) - 30 * 1));
            $('#txt_checkin_registro_fin').val(minutos_formato_hora((hora_entrada_min) + 30 * 1));
            $('#txt_checkout_salida_inicio').val(minutos_formato_hora((hora_salida_min) - 30 * 1));
            $('#txt_checkout_salida_fin').val(minutos_formato_hora((hora_salida_min) + 30 * 1));

        } else {
            $('#txt_valor_trabajar_hora').val("");
        }
    }
</script>