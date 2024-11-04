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
            url: '../controlador/TALENTO_HUMANO/th_programar_horariosC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                $('#txt_fecha_inicio').val(fecha_formateada(response[0].fecha_inicio));
                $('#txt_fecha_fin').val(fecha_formateada(response[0].fecha_fin));

                if (response[0].id_persona == 0) {
                    $('#pnl_departamentos').show();
                    $('#cbx_programar_departamento').prop('checked', true);
                }

                if (response[0].id_departamento == 0) {
                    $('#pnl_personas').show();
                    $('#cbx_programar_persona').prop('checked', true);
                }

                //Tipo de horario - Con horario o sin horario
                if (response[0].tipo_ciclo == 1) {
                    $('#cbx_horario_con').prop('checked', true);
                    $('#pnl_horarios').show();

                } else if (response[0].tipo_ciclo == 2) {
                    $('#cbx_horario_sin').prop('checked', true);
                }

                //Detalle del tipo de horario en este caso con horario
                if (response[0].si_ciclo == 1) {
                    $('#cbx_horario_detalle_1').prop('checked', true);
                } else if (response[0].si_ciclo == 2) {
                    $('#cbx_horario_detalle_2').prop('checked', true);
                }


                //Selects
                url_departamentosC = '../controlador/TALENTO_HUMANO/th_programar_horariosC.php?listar_departamentos_horarios=true';
                cargar_select2_con_id('ddl_departamentos', url_departamentosC, response[0].id_departamento, 'nombre_departamento');

                url_personasC = '../controlador/TALENTO_HUMANO/th_programar_horariosC.php?listar_personas_horarios=true';
                cargar_select2_con_id('ddl_personas', url_personasC, response[0].id_persona, 'nombre_persona');

                url_horariosC = '../controlador/TALENTO_HUMANO/th_horariosC.php?listar=true';
                cargar_select2_con_id('ddl_horarios', url_horariosC, response[0].id_horario, 'nombre');


            }
        });
    }

    function editar_insertar() {

        var txt_fecha_inicio = $('#txt_fecha_inicio').val();
        var txt_fecha_fin = $('#txt_fecha_fin').val();
        var ddl_personas = $('#ddl_personas').val() ?? 0;
        var ddl_departamentos = $('#ddl_departamentos').val() ?? 0;
        var ddl_horarios = $('#ddl_horarios').val() ?? 0;
        var cbx_horario = $('input[name="cbx_horario"]:checked').val() ?? 0;
        var cbx_horario_detalle = $('input[name="cbx_horario_detalle"]:checked').val() ?? 0;

        var parametros = {
            '_id': '<?= $_id ?>',
            'txt_fecha_inicio': txt_fecha_inicio,
            'txt_fecha_fin': txt_fecha_fin,
            'ddl_personas': ddl_personas,
            'ddl_departamentos': ddl_departamentos,
            'ddl_horarios': ddl_horarios,
            'cbx_horario': cbx_horario,
            'cbx_horario_detalle': cbx_horario_detalle,
        };

        if ($("#form_programar_horarios").valid()) {
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
            url: '../controlador/TALENTO_HUMANO/th_programar_horariosC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_programar_horarios';
                    });
                } else if (response == -2) {
                    //Swal.fire('', 'El nombre del dispositivo ya está en uso', 'warning');
                    $(txt_nombre).addClass('is-invalid');
                    $('#error_txt_nombre').text('El nombre del dispositivo ya está en uso.');
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
            url: '../controlador/TALENTO_HUMANO/th_programar_horariosC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_programar_horarios';
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
        url_horariosC = '../controlador/TALENTO_HUMANO/th_horariosC.php?buscar=true';
        cargar_select2_url('ddl_horarios', url_horariosC);
    }
</script>



<script>
    //Funciones adicionales 
    $(document).ready(function() {
        $('input[name="cbx_programar"]').on('change', function() {
            $('#pnl_personas, #pnl_departamentos').hide();

            $('#ddl_personas').val(null).trigger('change');
            $('#ddl_departamentos').val(null).trigger('change');

            if ($(this).attr('id') === 'cbx_programar_persona') {
                $('#pnl_personas').show();
            } else if ($(this).attr('id') === 'cbx_programar_departamento') {
                $('#pnl_departamentos').show();
            }
        });

        $('input[name="cbx_horario"]').on('change', function() {
            $('#pnl_horarios').hide();

            $('#ddl_horarios').val(null).trigger('change');
            $('input[name="cbx_horario_detalle"]').prop('checked', false);

            if ($(this).attr('id') === 'cbx_horario_con') {
                $('#pnl_horarios').show();
            } else if ($(this).attr('id') === 'cbx_horario_sin') {
                //$('#pnl_horarios').show();
            }
        });

    });
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Programar Horarios</div>
            <?php
            //print_r($_SESSION['INICIO']);die(); 

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Agregar
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
                                    echo 'Registrar ';
                                } else {
                                    echo 'Modificar ';
                                }
                                ?>
                            </h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_programar_horarios" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <form id="form_programar_horarios">

                            <div class="row pt-3 mb-col">
                                <div class="col-md-3">
                                    <label for="txt_fecha_inicio" class="form-label">Fecha Inicial </label>
                                    <input type="date" class="form-control form-control-sm" id="txt_fecha_inicio" name="txt_fecha_inicio" maxlength="50">
                                </div>

                                <div class="col-md-3">
                                    <label for="txt_fecha_fin" class="form-label">Fecha Final </label>
                                    <input type="date" class="form-control form-control-sm" id="txt_fecha_fin" name="txt_fecha_fin" maxlength="50">
                                </div>
                            </div>

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
                                    <select class="form-select form-select-sm" id="ddl_personas" name="ddl_personas">
                                        <option selected disabled>-- Seleccione --</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row pt-3 mb-col" id="pnl_departamentos" style="display: none;">
                                <div class="col-md-6">
                                    <label for="ddl_departamentos" class="form-label">Departamentos </label>
                                    <select class="form-select form-select-sm" id="ddl_departamentos" name="ddl_departamentos">
                                        <option selected disabled>-- Seleccione --</option>
                                    </select>
                                </div>
                            </div>


                            <div class="mb-col pt-3">
                                <label class="form-label" for="lbl_asignar_horario">Asignar Horario </label>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="cbx_horario" id="cbx_horario_con" value="1">
                                    <label class="form-check-label" for="cbx_horario_con">Con Horario </label>
                                </div>

                                <div id="pnl_horarios" style="display: none;">
                                    <div class="row mb-col">
                                        <div class="col-md-6">
                                            <label for="ddl_horarios" class="form-label">Horarios </label>
                                            <select class="form-select form-select-sm" id="ddl_horarios" name="ddl_horarios">
                                                <option selected disabled>-- Seleccione --</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-check ms-4">
                                        <input class="form-check-input" type="radio" name="cbx_horario_detalle" id="cbx_horario_detalle_1" value="1">
                                        <label class="form-check-label" for="cbx_horario_detalle_1">Tomar en cuenta los intervalos </label>
                                    </div>
                                    <div class="form-check ms-4">
                                        <input class="form-check-input" type="radio" name="cbx_horario_detalle" id="cbx_horario_detalle_2" value="2">
                                        <label class="form-check-label" for="cbx_horario_detalle_2">Sin tomar en cuenta los intervalos </label>
                                    </div>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="cbx_horario" id="cbx_horario_sin" value="2">
                                    <label class="form-check-label" for="cbx_horario_sin">Sin Horario</label>
                                </div>
                                <label class="error" style="display: none;" for="cbx_horario"></label>

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
        agregar_asterisco_campo_obligatorio('ddl_horarios');
        agregar_asterisco_campo_obligatorio('cbx_horario');
        agregar_asterisco_campo_obligatorio('cbx_horario_detalle');
        agregar_asterisco_campo_obligatorio('lbl_asignar_horario');
        agregar_asterisco_campo_obligatorio('lbl_programar');

        $("#form_programar_horarios").validate({
            rules: {
                txt_fecha_inicio: {
                    required: true,
                },
                txt_fecha_fin: {
                    required: true,
                },
                ddl_horarios: {
                    //required: true,
                },
                cbx_horario: {
                    required: true,
                },
                cbx_horario_detalle: {
                    //required: true,
                },
                cbx_programar: {
                    required: true,
                }
            },
            messages: {
                ddl_personas: {
                    required: "El campo 'Personas' es obligatorio",
                },
            },

            highlight: function(element) {
                // Agrega la clase 'is-invalid' al input que falla la validación
                $(element).addClass('is-invalid');
                $(element).removeClass('is-valid');

                $('input[name="cbx_horario"], input[name="cbx_programar"]').each(function() {
                    $(this).addClass('is-invalid');
                    $(this).removeClass('is-valid');
                });

            },
            unhighlight: function(element) {
                // Elimina la clase 'is-invalid' si la validación pasa
                $(element).removeClass('is-invalid');
                $(element).addClass('is-valid');

                $('input[name="cbx_horario"], input[name="cbx_programar"]').each(function() {
                    $(this).removeClass('is-invalid');
                    $(this).addClass('is-valid');
                });

            }
        });
    });
</script>