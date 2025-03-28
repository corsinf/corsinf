<?php
//Datos que llegan de la funcion gestion_paciente_comunidad // por el momento esta el sa_pac_tabla demas

$sa_pac_id = '';

if (isset($_POST['sa_pac_id'])) {
    $sa_pac_id = $_POST['sa_pac_id'];
}

$sa_pac_tabla = '';

if (isset($_POST['sa_pac_tabla'])) {
    $sa_pac_tabla = $_POST['sa_pac_tabla'];
}

$btn_regresar = '';

if (isset($_POST['btn_regresar'])) {
    $btn_regresar = $_POST['btn_regresar'];
}

if ($btn_regresar == '') {
    $btn_regresar = '../vista/inicio.php?mod=7&acc=inicio_representante';
}



?>

<script src="../js/ENFERMERIA/ficha_medica.js"></script>
<script src="../js/ENFERMERIA/operaciones_generales.js"></script>

<script type="text/javascript">
    <?php if ($sa_pac_id == '' && $sa_pac_tabla == '') { ?>
        recargar_pag();
    <?php } ?>

    $(document).ready(function() {

        var id = '<?php echo $_SESSION['INICIO']['ID_USUARIO']; ?>';
        //console.log(id);

        <?php if ($sa_pac_id != '' && $sa_pac_tabla != '') { ?>

            var sa_pac_id = '<?php echo $sa_pac_id; ?>';
            var sa_pac_tabla = '<?php echo $sa_pac_tabla; ?>';
            var btn_regresar = '<?php echo $btn_regresar; ?>';

            //alert(btn_regresar)

            //console.log(sa_pac_id);
            //console.log(sa_pac_tabla);

            //cargar_datos_paciente(sa_pac_id);
            datos_col_ficha_medica(sa_pac_id);

            //Para que cargue la funcionalidad de los input de las preguntas
            preguntas_ficha_medica();

            //inicializa smartwizart
            setTimeout(function() {
                smartwizard_ficha_medica();
            }, 10);

        <?php } ?>
    });

    <?php if ($sa_pac_id != '' && $sa_pac_tabla != '') { ?>

        //Para el detalle principal
        function cargar_datos_paciente(sa_pac_id, id_seguro_predeterminado) {
            //alert('Estudiante')
            $.ajax({
                data: {
                    sa_pac_id: sa_pac_id

                },
                url: '../controlador/SALUD_INTEGRAL/pacientesC.php?obtener_info_paciente=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    //console.log(response);
                    ///  Para la tabla de inicio /////////////////////////////////////////////////////////////////////////////////////////////////////////
                    $('#txt_ci').html(response[0].sa_pac_temp_cedula + " <i class='bx bxs-id-card'></i>");
                    nombres = response[0].sa_pac_temp_primer_nombre + ' ' + response[0].sa_pac_temp_segundo_nombre;
                    $('#txt_nombre').html(nombres);
                    apellidos = response[0].sa_pac_temp_primer_apellido + ' ' + response[0].sa_pac_temp_segundo_apellido;
                    $('#txt_apellido').html(apellidos);

                    $('#title_paciente').html(nombres + " " + apellidos);

                    $('#tipo_paciente').html(response[0].sa_pac_tabla);


                    sexo_paciente = '';
                    if (response[0].sa_pac_temp_sexo === 'Masculino') {
                        sexo_paciente = "Masculino <i class='bx bx-male'></i>";
                    } else if (response[0].sa_pac_temp_sexo === 'Femenino') {
                        sexo_paciente = "Famenino <i class='bx bx-female'></i>";
                    }
                    $('#txt_sexo').html(sexo_paciente);
                    $('#txt_fecha_nacimiento').html((response[0].sa_pac_temp_fecha_nacimiento));
                    $('#txt_edad').html(calcular_edad_fecha_nacimiento(response[0].sa_pac_temp_fecha_nacimiento) + ' años');
                    $('#txt_email').html(response[0].sa_pac_temp_correo + " <i class='bx bx-envelope'></i>");


                    <?php if ($sa_pac_tabla == 'estudiantes') { ?>
                        curso = response[0].sa_pac_temp_sec_nombre + '/' + response[0].sa_pac_temp_gra_nombre + '/' + response[0].sa_pac_temp_par_nombre;
                        $('#txt_curso').html(curso);
                    <?php  } else { ?>
                        $('#variable_paciente').html('Teléfono:');
                        $('#txt_curso').html(response[0].sa_pac_temp_telefono_1);

                    <?php } ?>

                    //ide de la tabla a la que pertenece estudiantes /doentes/
                    $('#txt_id_comnunidad').val(response[0].sa_pac_id_comunidad);

                    lista_seguros(response[0].sa_pac_id_comunidad, response[0].sa_pac_tabla, id_seguro_predeterminado);

                }
            });
        }

        //Aumento de carga de medicamentos a los que es alergico el paciente
        function datos_col_ficha_medica(sa_pac_id) {
            // alert(sa_pac_id)
            $.ajax({
                data: {
                    sa_pac_id: sa_pac_id
                },
                url: '../controlador/SALUD_INTEGRAL/ficha_MedicaC.php?listar_paciente_ficha=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    //console.log(response[0].sa_fice_id);
                    // Id de la ficha
                    $('#sa_fice_id').val(response[0].sa_fice_id);

                    // Datos del paciente
                    $('#sa_fice_pac_id').val(response[0].sa_fice_pac_id);
                    $('#sa_fice_pac_fecha_nacimiento').val(response[0].sa_fice_pac_fecha_nacimiento);
                    $('#sa_fice_pac_grupo_sangre').val(response[0].sa_fice_pac_grupo_sangre);
                    $('#sa_fice_pac_direccion_domicilio').val(response[0].sa_fice_pac_direccion_domicilio);
                    $('#sa_fice_pac_seguro_medico').val(response[0].sa_fice_pac_seguro_medico);

                    // Representante 1
                    $('#sa_fice_rep_1_primer_apellido').val(response[0].sa_fice_rep_1_primer_apellido);
                    $('#sa_fice_rep_1_segundo_apellido').val(response[0].sa_fice_rep_1_segundo_apellido);
                    $('#sa_fice_rep_1_primer_nombre').val(response[0].sa_fice_rep_1_primer_nombre);
                    $('#sa_fice_rep_1_segundo_nombre').val(response[0].sa_fice_rep_1_segundo_nombre);
                    $('#sa_fice_rep_1_parentesco').val(response[0].sa_fice_rep_1_parentesco);
                    $('#sa_fice_rep_1_telefono_1').val(response[0].sa_fice_rep_1_telefono_1);
                    $('#sa_fice_rep_1_telefono_2').val(response[0].sa_fice_rep_1_telefono_2);
                    $('#sa_fice_rep_1_cedula').val(response[0].sa_fice_rep_1_cedula);

                    // Representante 2
                    $('#sa_fice_rep_2_primer_apellido').val(response[0].sa_fice_rep_2_primer_apellido);
                    $('#sa_fice_rep_2_segundo_apellido').val(response[0].sa_fice_rep_2_segundo_apellido);
                    $('#sa_fice_rep_2_primer_nombre').val(response[0].sa_fice_rep_2_primer_nombre);
                    $('#sa_fice_rep_2_segundo_nombre').val(response[0].sa_fice_rep_2_segundo_nombre);
                    $('#sa_fice_rep_2_parentesco').val(response[0].sa_fice_rep_2_parentesco);
                    $('#sa_fice_rep_2_telefono_1').val(response[0].sa_fice_rep_2_telefono_1);
                    $('#sa_fice_rep_2_telefono_2').val(response[0].sa_fice_rep_2_telefono_2);
                    $('#sa_fice_rep_2_cedula').val(response[0].sa_fice_rep_2_cedula);

                    // Preguntas
                    $('input[name=sa_fice_pregunta_1][value=' + response[0].sa_fice_pregunta_1 + ']').prop('checked', true);
                    if (response[0].sa_fice_pregunta_1 === "Si") {
                        $("#sa_fice_pregunta_1_obs").show();
                        $('#sa_fice_pregunta_1_obs').val(response[0].sa_fice_pregunta_1_obs);
                    } else if (response[0].sa_fice_pregunta_1 === "No") {
                        $("#sa_fice_pregunta_1_obs").hide();
                    } else {
                        $("#sa_fice_pregunta_1_obs").hide();
                    }

                    $('input[name=sa_fice_pregunta_2][value=' + response[0].sa_fice_pregunta_2 + ']').prop('checked', true);
                    if (response[0].sa_fice_pregunta_2 === "Si") {
                        $("#sa_fice_pregunta_2_obs").show();
                        $('#sa_fice_pregunta_2_obs').val(response[0].sa_fice_pregunta_2_obs);
                    } else if (response[0].sa_fice_pregunta_2 === "No") {
                        $("#sa_fice_pregunta_2_obs").hide();
                    } else {
                        $("#sa_fice_pregunta_2_obs").hide();
                    }

                    $('input[name=sa_fice_pregunta_3][value=' + response[0].sa_fice_pregunta_3 + ']').prop('checked', true);
                    if (response[0].sa_fice_pregunta_3 === "Si") {
                        $("#sa_fice_pregunta_3_obs").show();
                        $('#sa_fice_pregunta_3_obs').val(response[0].sa_fice_pregunta_3_obs);
                    } else if (response[0].sa_fice_pregunta_3 === "No") {
                        $("#sa_fice_pregunta_3_obs").hide();
                    } else {
                        $("#sa_fice_pregunta_3_obs").hide();
                    }

                    $('input[name=sa_fice_pregunta_4][value=' + response[0].sa_fice_pregunta_4 + ']').prop('checked', true);
                    if (response[0].sa_fice_pregunta_4 === "Si") {
                        $("#sa_fice_pregunta_4_obs").show();
                        $('#sa_fice_pregunta_4_obs').val(response[0].sa_fice_pregunta_4_obs);
                    } else if (response[0].sa_fice_pregunta_4 === "No") {
                        $("#sa_fice_pregunta_4_obs").hide();
                    } else {
                        $("#sa_fice_pregunta_4_obs").hide();
                    }

                    $('#sa_fice_pregunta_5_obs').val(response[0].sa_fice_pregunta_5_obs);

                    // Estado para determinar si le ha llenado
                    $('#sa_fice_estado_realizado').val(response[0].sa_fice_estado_realizado);


                    //cargar datos del paciente
                    cargar_datos_paciente(sa_pac_id, response[0].sa_fice_pac_seguro_predeterminado);

                    //Cargar datos de farmacos que tiene alergia
                    cargar_farmacologia_fm(response[0].sa_fice_id);

                    $('input[name=sa_fice_medicamentos_alergia][value=' + response[0].sa_fice_medicamentos_alergia + ']').prop('checked', true);
                    if (response[0].sa_fice_medicamentos_alergia === "Si") {
                        $("#pnl_farmacologia").show();
                    } else if (response[0].sa_fice_medicamentos_alergia === "No") {
                        $("#pnl_farmacologia").hide();
                    } else {
                        $("#pnl_farmacologia").hide();
                    }

                    // Para autorizar al paciente recibir medicamentos 
                    if (response[0].sa_fice_autoriza_medicamentos == 1) {
                        $('#chk_terminos_medicamentos').prop('checked', true);
                        consultar_medicinas_insumos('medicamentos');
                    } else {
                        $('#chk_terminos_medicamentos').prop('checked', false);
                    }

                }
            });
        }

        function editar_insertar() {
            var sa_fice_id = $('#sa_fice_id').val();

            // Datos del estudiante
            var sa_fice_pac_id = $('#sa_fice_pac_id').val();

            // Resto de los campos del estudiante
            var sa_fice_pac_grupo_sangre = $('#sa_fice_pac_grupo_sangre').val();
            var sa_fice_pac_direccion_domicilio = $('#sa_fice_pac_direccion_domicilio').val();
            var sa_fice_pac_seguro_medico = $('#sa_fice_pac_seguro_medico').val();
            var sa_fice_pac_nombre_seguro = $('#sa_fice_pac_nombre_seguro').val();



            // Datos del representante 1
            var sa_fice_rep_1_primer_apellido = $('#sa_fice_rep_1_primer_apellido').val();
            var sa_fice_rep_1_segundo_apellido = $('#sa_fice_rep_1_segundo_apellido').val();
            var sa_fice_rep_1_primer_nombre = $('#sa_fice_rep_1_primer_nombre').val();
            var sa_fice_rep_1_segundo_nombre = $('#sa_fice_rep_1_segundo_nombre').val();
            var sa_fice_rep_1_parentesco = $('#sa_fice_rep_1_parentesco').val();
            var sa_fice_rep_1_telefono_1 = $('#sa_fice_rep_1_telefono_1').val();
            var sa_fice_rep_1_telefono_2 = $('#sa_fice_rep_1_telefono_2').val();
            var sa_fice_rep_1_cedula = $('#sa_fice_rep_1_cedula').val();

            // Datos del representante 2
            var sa_fice_rep_2_primer_apellido = $('#sa_fice_rep_2_primer_apellido').val();
            var sa_fice_rep_2_segundo_apellido = $('#sa_fice_rep_2_segundo_apellido').val();
            var sa_fice_rep_2_primer_nombre = $('#sa_fice_rep_2_primer_nombre').val();
            var sa_fice_rep_2_segundo_nombre = $('#sa_fice_rep_2_segundo_nombre').val();
            var sa_fice_rep_2_parentesco = $('#sa_fice_rep_2_parentesco').val();
            var sa_fice_rep_2_telefono_1 = $('#sa_fice_rep_2_telefono_1').val();
            var sa_fice_rep_2_telefono_2 = $('#sa_fice_rep_2_telefono_2').val();
            var sa_fice_rep_2_cedula = $('#sa_fice_rep_2_cedula').val();

            // Preguntas
            var sa_fice_pregunta_1 = $('input[name=sa_fice_pregunta_1]:checked').val();
            var sa_fice_pregunta_1_obs = $('#sa_fice_pregunta_1_obs').val();

            var sa_fice_pregunta_2 = $('input[name=sa_fice_pregunta_2]:checked').val();
            var sa_fice_pregunta_2_obs = $('#sa_fice_pregunta_2_obs').val();

            var sa_fice_pregunta_3 = $('input[name=sa_fice_pregunta_3]:checked').val();
            var sa_fice_pregunta_3_obs = $('#sa_fice_pregunta_3_obs').val();

            var sa_fice_pregunta_4 = $('input[name=sa_fice_pregunta_4]:checked').val();
            var sa_fice_pregunta_4_obs = $('#sa_fice_pregunta_4_obs').val();

            var sa_fice_pregunta_5_obs = $('#sa_fice_pregunta_5_obs').val();

            var sa_fice_estado_realizado = 1;

            var sa_fice_medicamentos_alergia = $('input[name=sa_fice_medicamentos_alergia]:checked').val();

            var sa_fice_autoriza_medicamentos = $('#chk_terminos_medicamentos').is(':checked') ? 1 : 0;

            // Crear objeto de parámetros

            var parametros = {
                'sa_fice_id': sa_fice_id,
                'sa_fice_pac_id': sa_fice_pac_id,
                'sa_fice_pac_grupo_sangre': sa_fice_pac_grupo_sangre,
                'sa_fice_pac_direccion_domicilio': sa_fice_pac_direccion_domicilio,
                'sa_fice_pac_seguro_medico': sa_fice_pac_seguro_medico,
                'sa_fice_pac_seguro_predeterminado': sa_fice_pac_nombre_seguro,

                'sa_fice_rep_1_primer_apellido': sa_fice_rep_1_primer_apellido,
                'sa_fice_rep_1_segundo_apellido': sa_fice_rep_1_segundo_apellido,
                'sa_fice_rep_1_primer_nombre': sa_fice_rep_1_primer_nombre,
                'sa_fice_rep_1_segundo_nombre': sa_fice_rep_1_segundo_nombre,
                'sa_fice_rep_1_parentesco': sa_fice_rep_1_parentesco,
                'sa_fice_rep_1_telefono_1': sa_fice_rep_1_telefono_1,
                'sa_fice_rep_1_telefono_2': sa_fice_rep_1_telefono_2,
                'sa_fice_rep_1_cedula': sa_fice_rep_1_cedula,

                'sa_fice_rep_2_primer_apellido': sa_fice_rep_2_primer_apellido,
                'sa_fice_rep_2_segundo_apellido': sa_fice_rep_2_segundo_apellido,
                'sa_fice_rep_2_primer_nombre': sa_fice_rep_2_primer_nombre,
                'sa_fice_rep_2_segundo_nombre': sa_fice_rep_2_segundo_nombre,
                'sa_fice_rep_2_parentesco': sa_fice_rep_2_parentesco,
                'sa_fice_rep_2_telefono_1': sa_fice_rep_2_telefono_1,
                'sa_fice_rep_2_telefono_2': sa_fice_rep_2_telefono_2,
                'sa_fice_rep_2_cedula': sa_fice_rep_2_cedula,

                'sa_fice_pregunta_1': sa_fice_pregunta_1,
                'sa_fice_pregunta_1_obs': sa_fice_pregunta_1_obs,
                'sa_fice_pregunta_2': sa_fice_pregunta_2,
                'sa_fice_pregunta_2_obs': sa_fice_pregunta_2_obs,
                'sa_fice_pregunta_3': sa_fice_pregunta_3,
                'sa_fice_pregunta_3_obs': sa_fice_pregunta_3_obs,
                'sa_fice_pregunta_4': sa_fice_pregunta_4,
                'sa_fice_pregunta_4_obs': sa_fice_pregunta_4_obs,
                'sa_fice_pregunta_5_obs': sa_fice_pregunta_5_obs,
                'sa_fice_medicamentos_alergia': sa_fice_medicamentos_alergia,
                'sa_fice_autoriza_medicamentos': sa_fice_autoriza_medicamentos,
            };

            if (sa_fice_id != '') {
                if (
                    sa_fice_pac_grupo_sangre == null ||
                    sa_fice_pac_seguro_medico == null ||
                    sa_fice_pregunta_1 == null ||
                    sa_fice_pregunta_2 == null ||
                    sa_fice_pregunta_3 == null ||
                    sa_fice_pregunta_4 == null ||
                    sa_fice_pac_direccion_domicilio === ''
                ) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Asegurese de llenar todo los campos',
                    })
                    //alert('error');
                } else {
                    insertar(parametros)
                    //alert('entra');
                }

                //console.log(parametros);
                //insertar(parametros);
            }
        }

        function insertar(parametros) {
            url_post = '<?php echo $btn_regresar ?>';

            $.ajax({
                data: {
                    parametros: parametros
                },
                url: '../controlador/SALUD_INTEGRAL/ficha_medicaC.php?insertar=true',
                type: 'post',
                dataType: 'json',
                /*beforeSend: function () {   
                     var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
                   $('#tabla_').html(spiner);
                },*/
                success: function(response) {
                    if (response == 1) {
                        Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                            location.href = url_post;
                        });
                    } else if (response == -2) {
                        Swal.fire('', 'Algo salió mal, repite el proceso.', 'success');
                    }
                    //console.log(response);
                }
            });
        }

        function lista_seguros(id, tabla, id_seleccionado) {

            var parametros = {
                'id': id,
                'tabla': tabla,
            }

            var option = '<option selected disabled value="">-- Seleccione Seguro --</option>';

            $.ajax({
                data: {
                    parametros: parametros
                },
                url: '../controlador/SALUD_INTEGRAL/ficha_medicaC.php?lista_seguros=true',
                type: 'post',
                dataType: 'json',

                success: function(response) {
                    //console.log(response);
                    $.each(response, function(i, item) {
                        if (id_seleccionado == item.id_arti_asegurados) {
                            option += '<option value ="' + item.id_arti_asegurados + '" selected>' + item.nombre + '</option>'
                        } else {
                            option += '<option value ="' + item.id_arti_asegurados + '">' + item.nombre + '</option>'
                        }
                    });

                    $('#sa_fice_pac_nombre_seguro').html(option);

                }
            });
        }

    <?php } ?>

    function SaveNewSeguro() {
        var prov = $('#txtSeguroProveedorNew').val();
        var seguro = $('#txtSeguroNombreNew').val();
        var estudiantes = $('#txt_id_comnunidad').val();
        var tabla = '<?php echo  $sa_pac_tabla ?>';
        var parametros = {
            'Proveedor': prov,
            'seguro': seguro,
            'todos': false,
            'estudiantes': estudiantes,
            'ids': '',
            'tabla': tabla,
        }
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/SALUD_INTEGRAL/estudiantesC.php?SaveSeguros=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                $('#myModal_seguros').modal('hide');
                if (response == 1) {
                    Swal.fire('', 'Agregado', 'success');
                    //console.log(estudiantes);
                    lista_seguros(estudiantes, tabla);
                } else if (response == -2) {
                    Swal.fire("", "Estudiante ya esta registrado con este seguro", "info")
                }
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
                            Ficha Médica
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
                                <a href="<?= $btn_regresar ?>" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                            </div>


                            <div class="col-sm-9 text-end">
                                <h6 class="mb-0 text-primary">


                                    Paciente: <b class="text-success" id="title_paciente"></b>
                                    <!--  <p id="tipo_paciente"></p> -->
                                </h6>
                            </div>

                        </div>


                        <hr>

                        <?php if ($sa_pac_id != '' && $sa_pac_tabla != '') { ?>

                            <!-- SmartWizard html -->
                            <div id="smartwizard_fm">
                                <ul class="nav">
                                    <li class="nav-item">
                                        <a class="nav-link" href="#step-1"> <strong>Paso 1</strong>
                                            <br>Datos Generales</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#step-2"> <strong>Paso 2</strong>

                                            <?php if ($sa_pac_tabla == 'estudiantes') { ?>
                                                <br>Datos del Representante</a>
                                    <?php } else { ?>
                                        <br>Contacto de Emergencia</a>
                                    <?php } ?>

                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#step-3"> <strong>Paso 3</strong>
                                            <br>Información Importante</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#step-4"> <strong>Paso 4</strong>
                                            <br>Consentimiento</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="tab_content_smart">

                                    <div id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-1" data-step="0">


                                        <form class="needs-validation" id="form-step-1">

                                            <input type="hidden" name="sa_fice_id" id="sa_fice_id">
                                            <input type="hidden" name="sa_fice_pac_id" id="sa_fice_pac_id">

                                            <h3 class="pt-3">Paso 1</h3>
                                            <p>Por favor, proporcione la siguiente información para completar el registro del paciente. Todos los campos son obligatorios, asegúrese de proporcionar datos precisos y completos.</p>

                                            <div class="row pt-3">
                                                <div class="col-6">
                                                    <div class="table-responsive">
                                                        <table class="table mb-0  table-bordered" style="width:100%">
                                                            <tbody>
                                                                <tr>
                                                                    <th style="width:40%" class="bg-light-primary text-end">Cédula:</th>
                                                                    <td id="txt_ci"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th style="width:40%" class="bg-light-primary text-end">Nombres:</th>
                                                                    <td id="txt_nombre"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th style="width:40%" class="bg-light-primary text-end">Apellidos:</th>
                                                                    <td id="txt_apellido"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th style="width:40%" class="bg-light-primary text-end">Sexo:</th>
                                                                    <td id="txt_sexo"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th style="width:40%" class="bg-light-primary text-end">Fecha de Nacimiento:</th>
                                                                    <td id="txt_fecha_nacimiento"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th style="width:40%" class="bg-light-primary text-end">Edad Actual:</th>
                                                                    <td id="txt_edad"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th style="width:40%" class="bg-light-primary text-end">Correo Electrónico:</th>
                                                                    <td id="txt_email"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th style="width:40%" class="bg-light-primary text-end" id="variable_paciente">Curso:</th>
                                                                    <td id="txt_curso"></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="row">
                                                        <div class="col-md-11">
                                                            <label for="" class="form-label"> Grupo Sanguíneo y Factor Rh <label style="color: red;">*</label> </label>
                                                            <select class="form-select form-select-sm" id="sa_fice_pac_grupo_sangre" name="sa_fice_pac_grupo_sangre" required>
                                                                <option selected disabled>-- Seleccione --</option>
                                                                <option value="A+">A+</option>
                                                                <option value="A-">A-</option>
                                                                <option value="B+">B+</option>
                                                                <option value="B-">B-</option>
                                                                <option value="AB+">AB+</option>
                                                                <option value="AB-">AB-</option>
                                                                <option value="O+">O+</option>
                                                                <option value="O-">O-</option>
                                                            </select>
                                                        </div>


                                                    </div>

                                                    <div class="row pt-3">
                                                        <div class="col-md-11">
                                                            <label for="" class="form-label">Dirección del Domicilio <label style="color: red;">*</label> </label>
                                                            <input type="text" class="form-control form-control-sm" id="sa_fice_pac_direccion_domicilio" name="sa_fice_pac_direccion_domicilio" required maxlength="100">
                                                        </div>
                                                    </div>


                                                    <div class="row pt-3">
                                                        <div class="col-md-11">
                                                            <label for="" class="form-label">¿Posee Seguro Médico Adicional? <label style="color: red;">*</label> </label>
                                                            <select class="form-select form-select-sm" id="sa_fice_pac_seguro_medico" name="sa_fice_pac_seguro_medico" required>
                                                                <option selected disabled value="">-- Seleccione --</option>
                                                                <option value="Si">Si</option>
                                                                <option value="No">No</option>
                                                            </select>
                                                        </div>

                                                        <input type="hidden" name="sa_fice_pac_seguro_medico_temp" id="sa_fice_pac_seguro_medico_temp">

                                                    </div>

                                                    <div class="row pt-3">
                                                        <div class="col-md-11" id="sa_fice_pac_nombre_seguro_div">

                                                            <!-- <label for="" class="form-label">Seguro Predeterminado <label style="color: red;">*</label> </label> -->

                                                            <label for="" class="form-label">Seleccione Seguro medico predeterminado <label style="color: red;">*</label> </label>

                                                            <div class="input-group">
                                                                <select class="form-select form-select-sm" id="sa_fice_pac_nombre_seguro" name="sa_fice_pac_nombre_seguro" required>
                                                                    <option selected disabled value="">-- Seleccione --</option>
                                                                </select>
                                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#myModal_seguros"><i class="bx bx-plus me-0"></i></button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>




                                        </form>

                                    </div>

                                    <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2" data-step="1">

                                        <form class="needs-validation" id="form-step-2">

                                            <h3 class="pt-3">Paso 2</h3>

                                            <?php if ($sa_pac_tabla == 'estudiantes') { ?>
                                                <p>En esta sección, ingrese los detalles del representante del paciente. Asegúrese de proporcionar información precisa y completa sobre el representante legal o responsable.</p>

                                            <?php  } else { ?>
                                                <p>En esta sección, ingrese los detalles del contacto de emergencia del paciente. Asegúrese de proporcionar información precisa y completa sobre el contacto de emergencia.</p>
                                            <?php } ?>

                                            <div <?php if ($sa_pac_tabla != 'estudiantes') {
                                                        echo 'hidden';
                                                    } ?>>
                                                <h6><b>Nombre del Representante o Familiar Responsable 1</b></h6>

                                                <div class="row pt-2">
                                                    <div class="col-md-3">
                                                        <label for="" class="form-label">Primer Apellido <label style="color: red;">*</label> </label>
                                                        <input type="text" class="form-control form-control-sm" id="sa_fice_rep_1_primer_apellido" name="sa_fice_rep_1_primer_apellido" maxlength="50">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="" class="form-label">Segundo Apellido <label style="color: red;">*</label> </label>
                                                        <input type="text" class="form-control form-control-sm" id="sa_fice_rep_1_segundo_apellido" name="sa_fice_rep_1_segundo_apellido" maxlength="50">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="" class="form-label">Primer Nombre <label style="color: red;">*</label> </label>
                                                        <input type="text" class="form-control form-control-sm" id="sa_fice_rep_1_primer_nombre" name="sa_fice_rep_1_primer_nombre" maxlength="50">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="" class="form-label">Segundo Nombre <label style="color: red;">*</label> </label>
                                                        <input type="text" class="form-control form-control-sm" id="sa_fice_rep_1_segundo_nombre" name="sa_fice_rep_1_segundo_nombre" maxlength="50">
                                                    </div>
                                                </div>

                                                <div class="row pt-3">
                                                    <div class="col-md-3">
                                                        <label for="" class="form-label">Cédula <label style="color: red;"></label> </label>
                                                        <input type="text" class="form-control form-control-sm" id="sa_fice_rep_1_cedula" name="sa_fice_rep_1_cedula" maxlength="15">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="" class="form-label">Parentesco <label style="color: red;">*</label> </label>

                                                        <select class="form-select form-select-sm" id="sa_fice_rep_1_parentesco" name="sa_fice_rep_1_parentesco">
                                                            <option selected disabled value="">-- Seleccione --</option>
                                                            <option value="Padre">Padre</option>
                                                            <option value="Madre">Madre</option>
                                                            <option value="Hermano">Hermano/a</option>
                                                            <option value="Tio">Tío/a</option>
                                                            <option value="Primo">Primo/a</option>
                                                            <option value="Abuelo">Abuelo/a</option>
                                                            <option value="Otro">Otro/a</option>

                                                        </select>

                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="" class="form-label">Teléfono Celular <label style="color: red;">*</label> </label>
                                                        <input type="text" class="form-control form-control-sm" id="sa_fice_rep_1_telefono_1" name="sa_fice_rep_1_telefono_1" maxlength="15">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="" class="form-label">Teléfono Fijo </label>
                                                        <input type="text" class="form-control form-control-sm" id="sa_fice_rep_1_telefono_2" name="sa_fice_rep_1_telefono_2" maxlength="15">
                                                    </div>
                                                </div>
                                            </div>

                                            <div>

                                                <?php if ($sa_pac_tabla == 'estudiantes') { ?>
                                                    <h6 class="row pt-4"><b>Nombre del Representante o Familiar Responsable 2 (Opcional)</b></h6>
                                                <?php  } else { ?>
                                                    <h6 class="row pt-4"><b>Contacto de Emergencia (Opcional)</b></h6>
                                                <?php } ?>

                                                <div class="row pt-2">
                                                    <div class="col-md-3">
                                                        <label for="" class="form-label">Primer Apellido <label style="color: red;"></label> </label>
                                                        <input type="text" class="form-control form-control-sm" id="sa_fice_rep_2_primer_apellido" name="sa_fice_rep_2_primer_apellido" maxlength="50">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="" class="form-label">Segundo Apellido <label style="color: red;"></label> </label>
                                                        <input type="text" class="form-control form-control-sm" id="sa_fice_rep_2_segundo_apellido" name="sa_fice_rep_2_segundo_apellido" maxlength="50">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="" class="form-label">Primer Nombre <label style="color: red;"></label> </label>
                                                        <input type="text" class="form-control form-control-sm" id="sa_fice_rep_2_primer_nombre" name="sa_fice_rep_2_primer_nombre" maxlength="50">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="" class="form-label">Segundo Nombre <label style="color: red;"></label> </label>
                                                        <input type="text" class="form-control form-control-sm" id="sa_fice_rep_2_segundo_nombre" name="sa_fice_rep_2_segundo_nombre" maxlength="50">
                                                    </div>
                                                </div>

                                                <div class="row pt-3">
                                                    <div class="col-md-3">
                                                        <label for="" class="form-label">Cédula <label style="color: red;"></label> </label>
                                                        <input type="text" class="form-control form-control-sm" id="sa_fice_rep_2_cedula" name="sa_fice_rep_2_cedula" maxlength="15">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="" class="form-label">Parentesco <label style="color: red;"></label> </label>

                                                        <select class="form-select form-select-sm" id="sa_fice_rep_2_parentesco" name="sa_fice_rep_2_parentesco">
                                                            <option selected disabled value="">-- Seleccione --</option>
                                                            <option value="Padre">Padre</option>
                                                            <option value="Madre">Madre</option>
                                                            <option value="Hermano">Hermano/a</option>
                                                            <option value="Tio">Tío/a</option>
                                                            <option value="Primo">Primo/a</option>
                                                            <option value="Abuelo">Abuelo/a</option>
                                                            <option value="Otro">Otro/a</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="" class="form-label">Teléfono Celular <label style="color: red;"></label> </label>
                                                        <input type="text" class="form-control form-control-sm" id="sa_fice_rep_2_telefono_1" name="sa_fice_rep_2_telefono_1" maxlength="15">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="" class="form-label">Teléfono Fijo <label style="color: red;"></label> </label>
                                                        <input type="text" class="form-control form-control-sm" id="sa_fice_rep_2_telefono_2" name="sa_fice_rep_2_telefono_2" maxlength="15">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                    </div>

                                    <div id="step-3" class="tab-pane" role="tabpanel" aria-labelledby="step-3" data-step="2">

                                        <form class="needs-validation" id="form-step-3">

                                            <h3 class="pt-3">Paso 3</h3>

                                            <p>Este paso contiene información vital que necesitamos conocer para garantizar una atención adecuada. Por favor, lea cada pregunta cuidadosamente y complete la información solicitada.</p>

                                            <p style="color: red;">*Si usted considera que existe alguna condición médica importante en el estudiante. Mencionar, por favor explíquelo a continuación.</p>

                                            <div class="row pt-2">

                                                <div class="col-md-12">
                                                    <label for="" class="form-label">1.- ¿Ha sido diagnosticado con alguna enfermedad? <label style="color: red;">* OBLIGATORIO</label> </label>
                                                    <div class="row">
                                                        <div class="col-auto">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="sa_fice_pregunta_1" id="sa_fice_pregunta_1_1" value="Si" required>
                                                                <label class="form-check-label" for="sa_fice_pregunta_1_1">SI</label>
                                                            </div>

                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="sa_fice_pregunta_1" id="sa_fice_pregunta_1_2" value="No" required>
                                                                <label class="form-check-label" for="sa_fice_pregunta_1_2">NO</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-11">
                                                            <textarea name="sa_fice_pregunta_1_obs" id="sa_fice_pregunta_1_obs" cols="30" rows="2" class="form-control form-control-sm" placeholder="¿Cúal?" maxlength="300"></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 pt-4">
                                                    <label for="" class="form-label">2.- ¿Tiene algún antecedente familiar de importancia? <label style="color: red;">* PADRES – HERMANOS – ABUELOS - TIOS </label> </label>
                                                    <div class="row">
                                                        <div class="col-auto">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="sa_fice_pregunta_2" id="sa_fice_pregunta_2_1" value="Si" required>
                                                                <label class="form-check-label" for="sa_fice_pregunta_2_1">SI</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="sa_fice_pregunta_2" id="sa_fice_pregunta_2_2" value="No" required>
                                                                <label class="form-check-label" for="sa_fice_pregunta_2_2">NO</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-11">
                                                            <textarea name="sa_fice_pregunta_2_obs" id="sa_fice_pregunta_2_obs" cols="30" rows="2" class="form-control form-control-sm " placeholder="¿Cúal?" maxlength="300"></textarea>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="col-md-12 pt-4">
                                                    <label for="" class="form-label">3.- ¿Ha sido sometido a cirugías previas? <label style="color: red;">* OBLIGATORIO </label> </label>
                                                    <div class="row">
                                                        <div class="col-auto">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="sa_fice_pregunta_3" id="sa_fice_pregunta_3_1" value="Si" required>
                                                                <label class="form-check-label" for="sa_fice_pregunta_3_1">SI</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="sa_fice_pregunta_3" id="sa_fice_pregunta_3_2" value="No" required>
                                                                <label class="form-check-label" for="sa_fice_pregunta_3_2">NO</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-11">
                                                            <textarea name="sa_fice_pregunta_3_obs" id="sa_fice_pregunta_3_obs" cols="30" rows="2" class="form-control form-control-sm" placeholder="¿Cuál?" maxlength="300"></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 pt-4">
                                                    <label for="" class="form-label">4.- ¿Tiene alergias? <label style="color: red;">* OBLIGATORIO </label> </label>
                                                    <div class="row">
                                                        <div class="col-auto">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="sa_fice_pregunta_4" id="sa_fice_pregunta_4_1" value="Si" required>
                                                                <label class="form-check-label" for="sa_fice_pregunta_4_1">SI</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="sa_fice_pregunta_4" id="sa_fice_pregunta_4_2" value="No" required>
                                                                <label class="form-check-label" for="sa_fice_pregunta_4_2">NO</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-11">
                                                            <textarea name="sa_fice_pregunta_4_obs" id="sa_fice_pregunta_4_obs" cols="30" rows="2" class="form-control form-control-sm" placeholder="¿Cúal?" maxlength="300"></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 pt-4">

                                                    <label for="" class="form-label">5.- ¿Qué medicamentos usa? <label style="color: red;">*</label> </label>
                                                    <p style="color: red;">*Si el estudiante requiere algún tratamiento específico durante el horario escolar, el representante deberá enviar el medicamento con la indicación médica correspondiente por agenda a través del docente tutor</p>

                                                    <div>
                                                        <textarea name="sa_fice_pregunta_5_obs" id="sa_fice_pregunta_5_obs" cols="30" rows="2" class="form-control form-control-sm" required maxlength="300"></textarea>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 pt-4">

                                                    <label for="" class="form-label">6.- ¿Tiene prohibido tomar algún medicamento? <label style="color: red;">*</label> </label>

                                                    <div class="row">
                                                        <div class="col-auto">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="sa_fice_medicamentos_alergia" id="sa_fice_medicamentos_alergia_1" value="Si" required>
                                                                <label class="form-check-label" for="sa_fice_medicamentos_alergia_1">SI</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="sa_fice_medicamentos_alergia" id="sa_fice_medicamentos_alergia_2" value="No" required>
                                                                <label class="form-check-label" for="sa_fice_medicamentos_alergia_2">NO</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="pnl_farmacologia" style="display: none;">
                                                        <div class="row pt-3">

                                                            <div class="col-md-3" hidden>
                                                                <label for="tipo_farmacologia" class="form-label fw-bold">Farmacología <label style="color: red;">*</label> </label>
                                                                <select class="form-select form-select-sm" id="tipo_farmacologia" name="tipo_farmacologia" disabled>
                                                                    <!-- <option selected disabled>-- Seleccione --</option> -->
                                                                    <option selected value="medicamentos">Medicamentos</option>
                                                                    <!-- <option value="insumos">Insumos</option> -->
                                                                </select>
                                                            </div>

                                                            <div class="col-md-5">
                                                                <label for="tipo_farmacologia_presentacion" class="form-label fw-bold">Medicamentos <label style="color: red;">*</label> </label>
                                                                <select class="form-select form-select-sm" id="tipo_farmacologia_presentacion" name="tipo_farmacologia_presentacion">
                                                                    <option selected>-- Seleccione --</option>
                                                                </select>
                                                            </div>

                                                            <input type="hidden" name="sa_det_fice_id_cmed_cins" id="sa_det_fice_id_cmed_cins">
                                                            <input type="hidden" name="sa_det_fice_nombre" id="sa_det_fice_nombre">
                                                            <input type="hidden" name="sa_det_fice_tipo" id="sa_det_fice_tipo">


                                                            <div class="col-md-2 mt-4 ">
                                                                <label for="agregarFila_medicamentos" class="form-label fw-bold"></label>
                                                                <button class="btn btn-primary" title="Agregar Medicamentos" id="agregarFila_medicamentos" type="button"><i class='bx bx-plus me-0'></i> Agregar</button>
                                                            </div>
                                                        </div>



                                                        <div class="row pt-3">
                                                            <div class="col-sm-6">
                                                                <div class="mb-2">

                                                                    <table class="table table-bordered table-hover" id="lista_medicamentos">

                                                                        <tr>
                                                                            <th width="2%"><input id="checkAll_Medicamentos" class="form-check" type="checkbox"></th>

                                                                            <th width="98%">Medicamentos</th>

                                                                        </tr>

                                                                    </table>

                                                                    <button class="btn btn-danger btn-sm mb-2" title="Seleccione el Medicamento para Eliminar" id="eliminarFila_medicamentos" type="button"><i class='bx bx-minus me-0'></i>Eliminar</button>

                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                        </form>

                                    </div>

                                    <div id="step-4" class="tab-pane" role="tabpanel" aria-labelledby="step-4" data-step="3">
                                        <h3 class="pt-3">Paso 4</h3>

                                        <div class="card bg-transparent shadow-none">
                                            <div class="card-body">

                                                <div class="row">
                                                    <div class="alert border-0 border-start border-5 border-dark alert-dismissible fade show">
                                                        <br>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <p><b>Consentimiento para el Uso de Medicamentos</b></p>
                                                            </div>

                                                            <div class="col-12 mx-4 text-start">

                                                                <p>Para garantizar un tratamiento médico adecuado, solicitamos su consentimiento para que los doctores y profesionales de la institución administren y supervisen el uso de medicamentos durante su atención.</p>


                                                                <p>Al hacer clic en "Aceptar términos y condiciones," usted autoriza la administración de los medicamentos necesarios para su tratamiento.</p>

                                                            </div>

                                                            <div class="col-12 mx-4">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" id="chk_terminos_medicamentos">
                                                                    <label class="form-check-label" for="chk_terminos_medicamentos">Aceptar términos y condiciones</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row pt-4">
                                                    <div class="alert border-0 border-start border-5 border-dark alert-dismissible fade show pt-4">
                                                        <br>
                                                        <div>
                                                            <div class="col-12">
                                                                <p><b>Consentimiento para el Uso de Datos de Ficha Médica</b></p>
                                                            </div>

                                                            <div class="col-12 mx-4 text-start">

                                                                <p>Para garantizar una atención médica eficaz, solicitamos su consentimiento para que los doctores y profesionales de la salud accedan y utilicen la información de su ficha médica.</p>


                                                                <p>Al dar click en aceptar términos y condiciones, usted autoriza el acceso a su ficha médica para brindarle una atención médica adecuada.</p>

                                                            </div>

                                                            <div class="col-12 mx-4">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" id="chk_terminos">
                                                                    <label class="form-check-label" for="chk_terminos">Aceptar términos y condiciones</label>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="modal-footer pt-4">
                                                            <button class="btn btn-success px-4 m-1" onclick="editar_insertar()" type="button" id="btn_editar_fm" style="display: none;"><i class="bx bx-save"></i> Guardar Datos</button>
                                                        </div>


                                                        <script>
                                                            // Agrega un evento de escucha al cambio en la casilla de verificación usando jQuery
                                                            $('#chk_terminos').change(function() {
                                                                // Verifica si la casilla de verificación está marcada
                                                                if ($(this).prop('checked')) {
                                                                    // Muestra el botón usando jQuery
                                                                    $('#btn_editar_fm').show();
                                                                } else {
                                                                    // Oculta el botón si la casilla de verificación no está marcada
                                                                    $('#btn_editar_fm').hide();
                                                                }
                                                            });
                                                        </script>

                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }  ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="myModal_seguros" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Agrega Seguro</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="txt_id_comnunidad" id="txt_id_comnunidad">
                    <div class="col-sm-12">
                        <label for="" class="form-label fw-bold">Nombre del Proveedor <label style="color: red;">*</label> </label>
                        <input type="text" name="" id="txtSeguroProveedorNew" class="form-control form-control-sm">
                    </div>

                    <div class="col-sm-12 pt-3">
                        <label for="" class="form-label fw-bold">Nombre del Seguro <label style="color: red;">*</label> </label>
                        <input type="text" name="" id="txtSeguroNombreNew" class="form-control form-control-sm">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="SaveNewSeguro()">Agregar</button>
            </div>
        </div>
    </div>
</div>