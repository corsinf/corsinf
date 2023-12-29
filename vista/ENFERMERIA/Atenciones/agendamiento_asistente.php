<?php


?>

<script src="<?= $url_general ?>/js/ENFERMERIA/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
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
                id:'btn_sigiente',
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


        <!--En algun punto de aqui esta psando algo para que no deje reenviar el post-->

        <div class="row">
            <div class="col-xl-12 mx-auto">

                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body">

                        <div class="card-title d-flex align-items-center">

                            <div class="col-sm-3">
                                <a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=agendamiento" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                            </div>


                            <div class="col-sm-9 text-end m-2">
                                <h6 class="mb-0 text-primary">


                                    Agendamiento

                                </h6>
                            </div>

                        </div>


                        <div class="row justify-content-center" id="btn_nuevo">
                            <div class="col-auto" id="btn_consulta">
                                <div class="card">
                                    <div class="card-body bg-dark">
                                        <!-- Agrega un identificador al botón y al div que deseas mostrar/ocultar -->
                                        <button type="button" class="btn btn-primary btn-lg m-4" onclick="tipoConsulta('registro_sw', 1)"><i class='bx bx-file-blank'></i> Consulta</button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-auto" id="btn_certificado">
                                <div class="card">
                                    <div class="card-body bg-dark">
                                        <!-- Agrega un identificador al botón y al div que deseas mostrar/ocultar -->
                                        <button type="button" class="btn btn-primary btn-lg m-4" onclick="tipoConsulta('triage_sw', 2)"><i class='bx bx-file-blank'></i> Certificado</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <script>
                            // Función para mostrar u ocultar un div específico
                            var id_consulta_g = 0;

                            function tipoConsulta(divId, id_consulta) {
                                //1 Consulta
                                //2 Certificado
                                id_consulta_g = id_consulta;

                                $("#btn_consulta").css('display', 'none');
                                $("#btn_certificado").css('display', 'none');

                                if (id_consulta == 1) {
                                    
                                    $('#pnl_main').css('display', 'initial');
                                    $('#step-1').css('width','auto');
                                    $('#pnl_contenido').css('height','auto');
                                    $("#triage_sw").show();
                                    //$("#pnl_triage").show();



                                } else if (id_consulta == 2) {
                                    $('#triage_sw').css('display', 'none');
                                    $('#pnl_main').css('display', 'initial');
                                    $('#step-1').css('width','auto');
                                    $('#pnl_contenido').css('height','auto');
                                }

                            }
                        </script>



                        <div id="pnl_main" style="display: none;">
                            <!-- SmartWizard html -->
                            <div id="smartwizard_fm" class="sw sw-theme-arrows sw-justified">
                                <ul class="nav">
                                    <li class="nav-item" id="registro_sw">
                                        <a class="nav-link" href="#step-1"> <strong>Paso 1</strong>
                                            <br>Agendamiento</a>
                                    </li>

                                    <li class="nav-item" id="triage_sw" style="display: none;">
                                        <a class="nav-link" href="#step-2"> <strong>Paso 2</strong>
                                            <br>Triage</a>
                                    </li>
                                </ul>

                                <div class="tab-content" id="pnl_contenido">

                                        <div id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-1">

                                            <form class="needs-validation" novalidate>


                                                <div class="row pt-0 mx-1">
                                                    <b>
                                                        <h3 class="pt-0 text-primary">Agendar Consulta</h3>
                                                    </b>

                                                    <div class="row pt-2">
                                                        <div class="col-sm-12">
                                                            <b>Pacientes: <label class="text-danger">*</label></b>
                                                            <select class="form-select form-select-sm" id="ddl_pacientes" name="ddl_pacientes">
                                                                <option value="">-- Seleccione Paciente --</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row pt-3">
                                                        <div class="col-sm-5">
                                                            <b>Fecha de Atención: <label class="text-danger">*</label></b>
                                                            <input type="date" name="txt_fecha_consulta" id="txt_fecha_consulta" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>">
                                                        </div>

                                                        <div class="col-sm-7">
                                                            <b>Tipo de Consulta: <label class="text-danger">*</label></b>
                                                            <select class="form-select form-select-sm" id="txt_tipo_consulta" name="txt_tipo_consulta">
                                                                <option value="">-- Seleccione Tipo de Consulta --</option>
                                                                <option value="consulta">Consulta General</option>
                                                                <option value="certificado">Validar Certificado</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                            </form>

                                        </div>

                                        <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2">

                                            <form class="needs-validation" novalidate>

                                                <input type="hidden" name="sa_fice_id" id="sa_fice_id">
                                                <input type="hidden" name="sa_fice_pac_id" id="sa_fice_pac_id">



                                                <div class="row pt-0 mx-1">
                                                    <b>
                                                        <h3 class="pt-0 text-primary">Triage</h3>
                                                    </b>

                                                    <div class="row pt-2">
                                                        <div class="col-md-3">
                                                            <b><label for="" class="form-label">Peso: <label style="color: red;">*</label></b>
                                                            <input type="number" class="form-control form-control-sm" id="sa_conp_peso" name="sa_conp_peso">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <b><label for="" class="form-label">Altura: <label style="color: red;">*</label> </label></b>
                                                            <input type="number" class="form-control form-control-sm" id="sa_conp_altura" name="sa_conp_altura">
                                                        </div>
                                                    </div>

                                                    <div class="row pt-3">
                                                        <div class="col-md-3">
                                                            <b><label for="" class="form-label">Temperatura: <label style="color: red;">*</label> </label></b>
                                                            <input type="number" class="form-control form-control-sm" id="sa_conp_temperatura" name="sa_conp_temperatura">
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
                                                </div>



                                            </form>

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