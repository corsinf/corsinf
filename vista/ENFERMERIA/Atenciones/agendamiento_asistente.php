<?php

$tipo_consulta = '';
$txt_fecha_consulta = '';

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // La solicitud es un POST
    if (isset($_POST['tipo_consulta'])) {
        $tipo_consulta = $_POST['tipo_consulta'];
    }

    if (isset($_POST['txt_fecha_consulta'])) {
        $txt_fecha_consulta = $_POST['txt_fecha_consulta'];
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // La solicitud es un GET
    if (isset($_GET['tipo_consulta'])) {
        $tipo_consulta = $_GET['tipo_consulta'];
    }

    if (isset($_GET['txt_fecha_consulta'])) {
        $txt_fecha_consulta = $_GET['txt_fecha_consulta'];
    }
}

?>

<script src="<?= $url_general ?>/js/ENFERMERIA/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        window.addEventListener('beforeunload', function(event) {
            // Mostrar un mensaje de alerta personalizado
            var confirmationMessage = '¿Estás seguro de que quieres abandonar la página?';

            (event || window.event).returnValue = confirmationMessage; // Para navegadores más antiguos
            return confirmationMessage; // Para navegadores modernos
        });

        var tipo_consulta = '<?php echo $tipo_consulta; ?>';
        var txt_fecha_consulta = '<?php echo $txt_fecha_consulta; ?>';

        $('#txt_fecha_consulta').val(txt_fecha_consulta);




        smartwizard();
        autocoplete_paciente();
    });

    function smartwizard() {
        $("#smartwizard_fm").on("showStep", function(e, anchorObject, stepNumber, stepDirection, stepPosition) {
            $("#prev-btn").removeClass('disabled');
            $("#next-btn").removeClass('disabled');
            if (stepPosition === 'first') {
                $("#prev-btn").addClass('disabled');
            } else if (stepPosition === 'last') {
                $("#next-btn").addClass('disabled');
            } else {
                $("#prev-btn").removeClass('disabled');
                $("#next-btn").removeClass('disabled');
            }
        });
        // Smart Wizard
        $('#smartwizard_fm').smartWizard({
            selected: 0,
            theme: 'dots',
            transition: {
                animation: 'slide-horizontal', // Effect on navigation, none/fade/slide-horizontal/slide-vertical/slide-swing
            },
            toolbarSettings: {
                toolbarPosition: '', // both bottom
            },
            lang: {
                next: 'Siguiente',
                id: 'btn_sigiente',
                previous: 'Anterior'
            }
        });
    }

    function autocoplete_paciente() {
        $('#ddl_pacientes').select2({
            placeholder: '-- Seleccione Paciente --',
            //dropdownParent: $('#myModal'),
            ajax: {
                url: '<?php echo $url_general ?>/controlador/agendamientoC.php?buscar=true',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    // console.log(data);
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    }

    function agendar() {
        var paciente = $('#ddl_pacientes').val();
        //var tipoConsulta = $('#txt_tipo_consulta').val();
        var tipoConsulta = '<?php echo $tipo_consulta; ?>';
        var fechaConsulta = $('#txt_fecha_consulta').val();

        var sa_conp_peso = $('#sa_conp_peso').val();
        var sa_conp_altura = $('#sa_conp_altura').val();
        var sa_conp_temperatura = $('#sa_conp_temperatura').val();
        var sa_conp_presion_ar = $('#sa_conp_presion_ar').val();
        var sa_conp_frec_cardiaca = $('#sa_conp_frec_cardiaca').val();
        var sa_conp_frec_respiratoria = $('#sa_conp_frec_respiratoria').val();
        var sa_conp_motivo_consulta = $('#sa_conp_motivo_consulta').val();



        if (tipoConsulta === 'consulta') {
            if (
                paciente &&
                tipoConsulta &&
                fechaConsulta &&
                sa_conp_peso &&
                sa_conp_altura &&
                sa_conp_temperatura &&
                sa_conp_presion_ar &&
                sa_conp_frec_cardiaca &&
                sa_conp_frec_respiratoria &&
                sa_conp_motivo_consulta) {
                // Todos los campos están llenos, puedes continuar con el envío de datos
                var parametros = {
                    'paciente': paciente,
                    'tipo': tipoConsulta,
                    'fecha': fechaConsulta,
                    'sa_conp_peso': sa_conp_peso,
                    'sa_conp_altura': sa_conp_altura,
                    'sa_conp_temperatura': sa_conp_temperatura,
                    'sa_conp_presion_ar': sa_conp_presion_ar,
                    'sa_conp_frec_cardiaca': sa_conp_frec_cardiaca,
                    'sa_conp_frec_respiratoria': sa_conp_frec_respiratoria,
                    'sa_conp_motivo_consulta': sa_conp_motivo_consulta,
                };

                $.ajax({
                    url: '<?php echo $url_general ?>/controlador/agendamientoC.php?add_agenda=true',
                    data: {
                        parametros: parametros
                    },
                    type: 'post',
                    dataType: 'json',
                    success: function(response) {
                        console.log(response)
                        Swal.fire('', 'Cita Agendada', 'success').then(function() {
                            location.href = '<?= $url_general ?>/vista/inicio.php?mod=7&acc=agendamiento';
                        })
                    }
                });

            } else {
                Swal.fire('Oops...', 'Todos los campos son obligatorios. Por favor, completa la información.', 'error').then(function() {})
            }

        } else if (tipoConsulta === 'certificado') {
            if (paciente && tipoConsulta && fechaConsulta) {
                // Todos los campos están llenos, puedes continuar con el envío de datos
                var parametros = {
                    'paciente': paciente,
                    'tipo': tipoConsulta,
                    'fecha': fechaConsulta
                };

                $.ajax({
                    url: '<?php echo $url_general ?>/controlador/agendamientoC.php?add_agenda=true',
                    data: {
                        parametros: parametros
                    },
                    type: 'post',
                    dataType: 'json',
                    success: function(response) {
                        console.log(response)
                        Swal.fire('', 'Cita Agendada', 'success').then(function() {
                            location.href = '<?= $url_general ?>/vista/inicio.php?mod=7&acc=agendamiento';
                        })
                    }
                });

            } else {
                Swal.fire('Oops...', 'Todos los campos son obligatorios. Por favor, completa la información.', 'error').then(function() {})
            }
        }


    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Enfermería</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Agenda Médica
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-xl-12 mx-auto">

                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body">



                        <?php if ($tipo_consulta !== "") { ?>

                            <div class="card-title d-flex align-items-center">

                                <div class="col-sm-3">
                                    <a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=agendamiento" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>

                                <div class="col-sm-9 text-end m-2">
                                    <h6 class="mb-0 text-primary">
                                        <?= strtoupper($tipo_consulta) ?>
                                    </h6>
                                </div>
                            </div>

                            <br>

                            <div id="pnl_main">
                                <!-- SmartWizard html -->
                                <div id="smartwizard_fm" class="sw sw-theme-arrows sw-justified">
                                    <ul class="nav">
                                        <li class="nav-item" id="registro_sw">
                                            <a class="nav-link" href="#step-1"> <strong>Paso 1</strong>
                                                <br>Agendamiento</a>
                                        </li>

                                        <?php if ($tipo_consulta == "consulta") { ?>
                                            <li class="nav-item" id="triage_sw">
                                                <a class="nav-link" href="#step-2"> <strong>Paso 2</strong>
                                                    <br>Triage</a>
                                            </li>
                                        <?php } ?>
                                    </ul>

                                    <div class="tab-content" id="pnl_contenido">

                                        <div id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-1">

                                            <form class="needs-validation" novalidate>
                                                <div class="row pt-0 mx-1">
                                                    <b>
                                                        <h3 class="pt-0 text-primary">Agendar</h3>
                                                    </b>

                                                    <div class="row pt-2">
                                                        <div class="col-sm-8">
                                                            <b>Pacientes: <label class="text-danger">*</label></b>
                                                            <select class="form-select form-select-sm" id="ddl_pacientes" name="ddl_pacientes">
                                                                <option value="">-- Seleccione Paciente --</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row pt-3">
                                                        <div class="col-sm-8">
                                                            <b>Fecha de Atención: <label class="text-danger">*</label></b>
                                                            <input type="date" name="txt_fecha_consulta" id="txt_fecha_consulta" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>" readonly>
                                                        </div>
                                                    </div>

                                                    <?php if ($tipo_consulta == "certificado") { ?>
                                                        <div class="row pt-3 text-end">
                                                            <div class="col-sm-8">

                                                                <button class="btn btn-success px-4 m-1" onclick="agendar()" type="button"><i class="bx bx-save"></i> Agendar</button>

                                                            </div>
                                                        </div>
                                                    <?php } ?>



                                                </div>
                                            </form>
                                        </div>

                                        <?php if ($tipo_consulta == "consulta") { ?>
                                            <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2">

                                                <form class="needs-validation" novalidate>

                                                    <div class="row pt-0 mx-1">
                                                        <b>
                                                            <h3 class="pt-0 text-primary">Triage</h3>
                                                        </b>

                                                        <div class="row pt-2">
                                                            <div class="col-md-3">
                                                                <b><label for="" class="form-label">Peso: <label style="color: red;">*</label></b>
                                                                <input type="number" class="form-control form-control-sm" id="sa_conp_peso" name="sa_conp_peso" placeholder="Ejemplo: 68.45 (Kilogramos)">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <b><label for="" class="form-label">Altura: <label style="color: red;">*</label> </label></b>
                                                                <input type="number" class="form-control form-control-sm" id="sa_conp_altura" name="sa_conp_altura" placeholder="Ejemplo: 1.45 (Metros)">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <b><label for="" class="form-label">IMC: <label style="color: red;">*</label> </label></b>
                                                                <input type="number" class="form-control form-control-sm" id="txt_imc" name="txt_imc" readonly disabled>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <b><label for="" class="form-label">Nivel del Peso: <label style="color: red;">*</label> </label></b>
                                                                <input type="text" class="form-control form-control-sm" id="txt_np" name="txt_np" readonly disabled>
                                                            </div>
                                                        </div>

                                                        <div class="row pt-3">
                                                            <div class="col-md-3">
                                                                <b><label for="" class="form-label">Temperatura: <label style="color: red;">*</label> </label></b>
                                                                <input type="number" class="form-control form-control-sm" id="sa_conp_temperatura" name="sa_conp_temperatura" placeholder="Ejemplo: 37.5 (°C)">
                                                                <p></p>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <b><label for="" class="form-label">Presión Arterial: <label style="color: red;">*</label> </label></b>
                                                                <input type="number" class="form-control form-control-sm" id="sa_conp_presion_ar" name="sa_conp_presion_ar">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <b><label for="" class="form-label">Frecuencia Cardiáca: <label style="color: red;">*</label> </label></b>
                                                                <input type="number" class="form-control form-control-sm" id="sa_conp_frec_cardiaca" name="sa_conp_frec_cardiaca">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <b><label for="" class="form-label">Frecuencia Respiratoria: <label style="color: red;">*</label> </label></b>
                                                                <input type="number" class="form-control form-control-sm" id="sa_conp_frec_respiratoria" name="sa_conp_frec_respiratoria">
                                                            </div>
                                                        </div>

                                                        <div class="row pt-3">
                                                            <div class="col-md-12">
                                                                <b><label for="" class="form-label">Motivo de la consulta: <label style="color: red;">*</label> </label></b>
                                                                <textarea name="sa_conp_motivo_consulta" id="sa_conp_motivo_consulta" cols="30" rows="4" class="form-control" placeholder="Motivo de la consulta"></textarea>
                                                            </div>
                                                        </div>

                                                        <?php if ($tipo_consulta == "consulta") { ?>
                                                            <div class="row pt-3 text-end">
                                                                <div class="col-sm-12">

                                                                    <button class="btn btn-success px-4 m-1" onclick="agendar()" type="button"><i class="bx bx-save"></i> Agendar</button>

                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </form>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                        <?php } else {  ?>

                            <div class="card-title d-flex align-items-center">

                                <div class="col-sm-3">
                                    <a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=agendamiento" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>

                            </div>

                            <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show py-2">
                                <div class="d-flex align-items-center">
                                    <div class="font-35 text-white"><i class='bx bxs-message-square-x'></i>
                                    </div>
                                    <div class="ms-3">
                                        <div class="text-white">¡Atención! Por favor, evita recargar la página. Si lo haces, perderás los datos que has ingresado en el formulario y tendrás que volver a llenarlos. Si encuentras algún problema o necesitas asistencia, no dudes en contactarnos. ¡Gracias por tu comprensión!</div>
                                    </div>
                                </div>
                            </div>

                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= $url_general ?>/js/ENFERMERIA/consulta_medica.js"></script>
