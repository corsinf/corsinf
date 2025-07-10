<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$_id = '';

if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}

?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        <?php if (isset($_GET['_id'])) { ?>
            datos_col(<?= $_id ?>);
        <?php } ?>

        cargar_selects2();

    });

    function datos_col(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/th_justificacionesC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                $('#txt_fecha_inicio').val(fecha_input_datelocal(response[0].fecha_inicio));
                $('#txt_fecha_fin').val(fecha_input_datelocal(response[0].fecha_fin));
                $('#txt_motivo').val(response[0].motivo);

                if (response[0].id_persona == 0) {
                    $('#pnl_departamentos').show();
                    $('#cbx_programar_departamento').prop('checked', true);
                }

                if (response[0].id_departamento == 0) {
                    $('#pnl_personas').show();
                    $('#cbx_programar_persona').prop('checked', true);
                }

                //Tipo de horario - Con horario o sin horario

                //Selects
                $('#ddl_departamentos').append($('<option>', {
                    value: response[0].id_departamento,
                    text: response[0].nombre_departamento,
                    selected: true
                }));

                $('#ddl_personas').append($('<option>', {
                    value: response[0].id_persona,
                    text: response[0].nombre_persona,
                    selected: true
                }));

                $('#ddl_tipo_justificacion').append($('<option>', {
                    value: response[0].id_tipo_justificacion,
                    text: response[0].tipo_motivo,
                    selected: true
                }));
                if (response[0].es_rango == 1) {
                    $('#cbx_justificar_rango').prop('checked', true);
                    $('#txt_fecha_inicio').attr('type', 'date').val(response[0].fecha_inicio.split(' ')[0]);
                    $('#txt_fecha_fin').attr('type', 'date').val(response[0].fecha_fin.split(' ')[0]);
                    $('#pnl_horas_totales').hide();
                } else {
                    $('#cbx_justificar_rango').prop('checked', false);
                    $('#pnl_horas_totales').show();

                    let horaValidaInicio = response[0].fecha_inicio.split(' ')[1].substring(0, 5);
                    let horaValidaFinal = response[0].fecha_fin.split(' ')[1].substring(0, 5);
                    $('#txt_fecha_inicio').attr('type', 'time').val(horaValidaInicio);
                    $('#txt_fecha_fin').attr('type', 'time').val(horaValidaFinal);
                    calcular_Diferencia_Horas();
                }

            }
        });
    }

    function editar_insertar() {

        var txt_fecha_inicio = $('#txt_fecha_inicio').val();
        var txt_fecha_fin = $('#txt_fecha_fin').val();
        var ddl_personas = $('#ddl_personas').val() ?? 0;
        var ddl_departamentos = $('#ddl_departamentos').val() ?? 0;
        var ddl_tipo_justificacion = $('#ddl_tipo_justificacion').val() ?? 0;
        var txt_motivo = $('#txt_motivo').val() ?? 0;
        var cbx_justificar_rango = $('#cbx_justificar_rango').prop('checked') ? 1 : 0;
        var txt_horas_totales = $('#txt_horas_totales').val();

        var parametros = {
            '_id': '<?= $_id ?>',
            'txt_fecha_inicio': txt_fecha_inicio,
            'txt_fecha_fin': txt_fecha_fin,
            'ddl_personas': ddl_personas,
            'ddl_departamentos': ddl_departamentos,
            'ddl_tipo_justificacion': ddl_tipo_justificacion,
            'txt_motivo': txt_motivo,
            'cbx_justificar_rango': cbx_justificar_rango,
            'txt_horas_totales': txt_horas_totales,
        };

        if ($("#form_justificaciones").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            insertar(parametros);
        }

        console.log(parametros);

    }

    function insertar(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/th_justificacionesC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_justificaciones';
                    });
                } else if (response == -2) {
                    Swal.fire('', 'Error al guardar la información.', 'warning');
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
            url: '../controlador/TALENTO_HUMANO/th_justificacionesC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_justificaciones';
                    });
                }
            }
        });
    }

    function cargar_selects2() {
        url_personasC = '../controlador/TALENTO_HUMANO/th_personasC.php?buscar=true';
        cargar_select2_url('ddl_personas', url_personasC);
        url_departamentosC = '../controlador/TALENTO_HUMANO/th_departamentosC.php?buscar=true';
        cargar_select2_url('ddl_departamentos', url_departamentosC);
        url_tipo_justificacionC = '../controlador/TALENTO_HUMANO/th_cat_tipo_justificacionC.php?buscar=true';
        cargar_select2_url('ddl_tipo_justificacion', url_tipo_justificacionC);
    }
</script>



<script>
    //Funciones adicionales 
    $(document).ready(function() {

        $('#txt_fecha_inicio').attr('type', 'time');
        $('#txt_fecha_fin').attr('type', 'time');
        $('#pnl_horas_totales').show();

        $('input[name="cbx_programar"]').on('change', function() {
            $('#pnl_personas, #pnl_departamentos').hide();

            // Reiniciar valores y remover required
            $('#ddl_personas').val(null).trigger('change').removeAttr('required');
            $('#ddl_departamentos').val(null).trigger('change').removeAttr('required');

            if ($(this).attr('id') === 'cbx_programar_persona') {
                $('#pnl_personas').show();
                $('#ddl_personas').attr('required', true); // Agregar required dinámicamente
            } else if ($(this).attr('id') === 'cbx_programar_departamento') {
                $('#pnl_departamentos').show();
                $('#ddl_departamentos').attr('required', true); // Opcional si quieres validar departamentos también
            }

            limpiar_parametros_validate();

        });

        //Validacion para las fechas
        $("input[name='txt_fecha_fin']").on("blur", function() {
            if (!verificar_fecha_inicio_fecha_fin('txt_fecha_inicio', 'txt_fecha_fin')) return;
        });
        $("input[name='txt_fecha_inicio']").on("blur", function() {
            if (!verificar_fecha_inicio_fecha_fin('txt_fecha_inicio', 'txt_fecha_fin')) return;
        });


        $('#cbx_justificar_rango').on('change', function() {
            if ($(this).is(':checked')) {
                $('#txt_fecha_inicio').attr('type', 'date');
                $('#txt_fecha_fin').attr('type', 'date');
                $('#pnl_horas_totales').hide();
            } else {
                $('#txt_fecha_inicio').attr('type', 'time');
                $('#txt_fecha_fin').attr('type', 'time');
                $('#pnl_horas_totales').show();
            }
            calcular_Diferencia_Horas();
        });

        $('#txt_fecha_inicio, #txt_fecha_fin').on('change', calcular_Diferencia_Horas);


    });

    function calcular_Diferencia_Horas() {
        let tipo = $('#txt_fecha_inicio').attr('type');
        let inicio = $('#txt_fecha_inicio').val();
        let fin = $('#txt_fecha_fin').val();

        if (!inicio || !fin) return;

        if (tipo === 'time') {
            let [h1, m1] = inicio.split(':').map(Number);
            let [h2, m2] = fin.split(':').map(Number);

            let minInicio = h1 * 60 + m1;
            let minFin = h2 * 60 + m2;

            if (minFin < minInicio) {
                Swal.fire({
                    icon: 'error',
                    title: '¡Error!',
                    text: 'La hora final no puede ser menor que la hora inicial',
                    confirmButtonColor: '#d33',
                });
                $('#txt_fecha_fin').val('');
                $('#txt_horas_totales').val('');
                return;
            }

            let diferencia = minFin - minInicio;
            let horas = Math.floor(diferencia / 60).toString().padStart(2, '0');
            let minutos = (diferencia % 60).toString().padStart(2, '0');

            $('#txt_horas_totales').val(`${horas}:${minutos}`);
        } else if (tipo === 'date') {
            let fechaInicio = new Date(inicio);
            let fechaFin = new Date(fin);

            if (fechaFin < fechaInicio) {
                Swal.fire({
                    icon: 'error',
                    title: '¡Error!',
                    text: 'La hora final no puede ser menor que la hora inicial',
                    confirmButtonColor: '#d33',
                });
                $('#txt_fecha_fin').val('');
            }
        }
    }



    function limpiar_parametros_validate() {
        //Limpiar validaciones
        //$("#form_articulo").validate().resetForm();
        $('.select2-selection').removeClass('is-valid is-invalid');
        $('.select2-validation').each(function() {
            $('label.error[for="' + this.id + '"]').hide();
        });
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Justificaciones</div>
            <?php
            //print_r($_SESSION['INICIO']);die(); 

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Registro
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
                                    echo 'Registrar Justificaciones';
                                } else {
                                    echo 'Modificar Justificaciones';
                                }
                                ?>
                            </h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_justificaciones" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <form id="form_justificaciones">

                            <div class="mb-col">
                                <label class="form-label" for="lbl_programar">Programar Horario </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="cbx_programar" id="cbx_programar_persona">
                                    <label class="form-check-label" for="cbx_programar_persona">Personas</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="cbx_programar" id="cbx_programar_departamento">
                                    <label class="form-check-label" for="cbx_programar_departamento">Departamentos</label>
                                </div>
                                <label class="error" style="display: none;" for="cbx_programar"></label>
                            </div>

                            <div class="row pt-3 mb-col" id="pnl_personas" style="display: none;">
                                <div class="col-md-6">
                                    <label for="ddl_personas" class="form-label">Personas </label>
                                    <select class="form-select form-select-sm select2-validation" id="ddl_personas" name="ddl_personas">
                                        <option selected disabled>-- Seleccione --</option>
                                    </select>
                                    <label class="error" style="display: none;" for="ddl_personas"></label>
                                </div>
                            </div>

                            <div class="row pt-3 mb-col" id="pnl_departamentos" style="display: none;">
                                <div class="col-md-6">
                                    <label for="ddl_departamentos" class="form-label">Departamentos </label>
                                    <select class="form-select form-select-sm select2-validation" id="ddl_departamentos" name="ddl_departamentos">
                                        <option selected disabled>-- Seleccione --</option>
                                    </select>
                                    <label class="error" style="display: none;" for="ddl_departamentos"></label>
                                </div>
                            </div>

                            <hr class="w-50">

                            <div class="row mb-col">
                                <div class="col-sm-6">
                                    <label for="ddl_tipo_justificacion" class="form-label">Tipo de Horario </label>
                                    <select class="form-control form-control-sm select2-validation" name="ddl_tipo_justificacion" id="ddl_tipo_justificacion">
                                        <option value="">Seleccione</option>
                                    </select>
                                    <label class="error" style="display: none;" for="ddl_tipo_justificacion"></label>
                                </div>
                            </div>

                            <div class="row mb-col">
                                <div class="col-md-6">
                                    <label for="txt_motivo" class="form-label">Motivo </label>
                                    <textarea class="form-control form-control-sm no_caracteres" name="txt_motivo" id="txt_motivo" rows="3" maxlength="200"></textarea>
                                </div>
                            </div>

                            <div class="row pt-3 mb-col align-items-center">
                                <div class="col-md-9">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="cbx_justificar_rango" name="cbx_justificar_rango" value="1">
                                        <label class="form-check-label" for="cbx_justificar_rango">Justificar, por rangos</label>
                                    </div>
                                </div>
                            </div>
                            <div id="pnl_horas_totales" style="display: none;">
                                <div class="row pt-3 mb-col">
                                    <div class="col-md-3">
                                        <label for="txt_horas_totales" class="form-label">Horas Totales</label>
                                        <input type="time" class="form-control form-control-sm" id="txt_horas_totales" name="txt_horas_totales" disabled>
                                    </div>
                                </div>

                            </div>


                            <div class="row pt-3 mb-col">
                                <div class="col-md-3">
                                    <label for="txt_fecha_inicio" class="form-label">Fecha Inicial </label>
                                    <input type="datetime-local" class="form-control form-control-sm" id="txt_fecha_inicio" name="txt_fecha_inicio" maxlength="50">
                                </div>
                                <div class="col-md-3">
                                    <label for="txt_fecha_fin" class="form-label">Fecha Final </label>
                                    <input type="datetime-local" class="form-control form-control-sm" id="txt_fecha_fin" name="txt_fecha_fin" maxlength="50">
                                </div>
                            </div>



                            <div class="d-flex justify-content-end pt-2">

                                <?php if ($_id == '') { ?>
                                    <button class="btn btn-success btn-sm px-4 m-0" onclick="editar_insertar()" type="button"><i class="bx bx-save"></i> Guardar</button>
                                <?php } else { ?>
                                    <button class="btn btn-success btn-sm px-4 m-1" onclick="editar_insertar()" type="button"><i class="bx bx-save"></i> Editar</button>
                                    <button class="btn btn-danger btn-sm px-4 m-1" onclick="delete_datos()" type="button"><i class="bx bx-trash"></i> Eliminar</button>
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

        agregar_asterisco_campo_obligatorio('txt_fecha_inicio');
        agregar_asterisco_campo_obligatorio('txt_fecha_fin');
        agregar_asterisco_campo_obligatorio('ddl_personas');
        agregar_asterisco_campo_obligatorio('ddl_departamentos');
        agregar_asterisco_campo_obligatorio('ddl_tipo_justificacion');
        agregar_asterisco_campo_obligatorio('lbl_programar');

        //Para validar los select2
        $(".select2-validation").on("select2:select", function(e) {
            unhighlight_select(this);
        });

        $("#form_justificaciones").validate({
            rules: {
                txt_fecha_inicio: {
                    required: true,
                },
                txt_fecha_fin: {
                    required: true,
                },
                ddl_tipo_justificacion: {
                    required: true,
                },
                cbx_programar: {
                    required: true,
                },
                txt_motivo: {
                    required: true,
                }
            },
            messages: {
                ddl_personas: {
                    required: "El campo 'Persona' es obligatorio",
                },
                ddl_departamentos: {
                    required: "El campo 'Departamento' es obligatorio",
                },
            },

            highlight: function(element) {
                let $element = $(element);

                if ($element.hasClass("select2-hidden-accessible")) {
                    // Elimina la clase 'is-invalid' y agrega 'is-valid' al contenedor correcto de select2
                    $element.next(".select2-container").find(".select2-selection").removeClass("is-valid").addClass("is-invalid");
                } else if ($element.is(':radio')) {
                    // Si es un radio button, aplicar la clase al grupo de radios (al contenedor padre si existe)
                    $('input[name="' + $element.attr("name") + '"]').addClass("is-invalid").removeClass("is-valid");
                } else {
                    // Elimina la clase 'is-invalid' y agrega 'is-valid' al input normal
                    $element.removeClass("is-valid").addClass("is-invalid");
                }
            },

            unhighlight: function(element) {
                let $element = $(element);

                if ($element.hasClass("select2-hidden-accessible")) {
                    // Para Select2, elimina 'is-invalid' y agrega 'is-valid' en el contenedor adecuado
                    $element.next(".select2-container").find(".select2-selection").removeClass("is-invalid").addClass("is-valid");
                } else if ($element.is(':radio')) {
                    // Si es un radio button, marcar todo el grupo como válido
                    $('input[name="' + $element.attr("name") + '"]').removeClass("is-invalid").addClass("is-valid");
                } else {
                    // Para otros elementos normales
                    $element.removeClass("is-invalid").addClass("is-valid");
                }
            }
        });
    });
</script>