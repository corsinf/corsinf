<?php

$id_ficha = '';
$id_paciente = '';
$tipo_consulta = '';

$id_consulta = '';

$regresar = '';

if (isset($_GET['id_consulta'])) {
    $id_consulta = $_GET['id_consulta'];
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // La solicitud es un POST
    if (isset($_POST['id_ficha'])) {
        $id_ficha = $_POST['id_ficha'];
    }

    if (isset($_POST['id_paciente'])) {
        $id_paciente = $_POST['id_paciente'];
    }

    if (isset($_POST['tipo_consulta'])) {
        $tipo_consulta = $_POST['tipo_consulta'];
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // La solicitud es un GET
    if (isset($_GET['id_ficha'])) {
        $id_ficha = $_GET['id_ficha'];
    }

    if (isset($_GET['id_paciente'])) {
        $id_paciente = $_GET['id_paciente'];
    }

    if (isset($_GET['tipo_consulta'])) {
        $tipo_consulta = $_GET['tipo_consulta'];
    }

    if (isset($_GET['regresar'])) {
        $regresar = $_GET['regresar'];
    }
}

?>

<script src="../js/ENFERMERIA/operaciones_generales.js"></script>
<script src="../js/ENFERMERIA/consulta_medica_detalle.js"></script>

<!-- //link de api icd -->
<link rel="stylesheet" href="https://icdcdn.who.int/embeddedct/icd11ect-1.6.1.css">
<script src="https://icdcdn.who.int/embeddedct/icd11ect-1.6.1.js"></script>
<script src="../js/ENFERMERIA/icd11_config.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //Logica para registrar o modificar la consulta
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        var id_ficha = '<?php echo $id_ficha; ?>';
        var id_consulta = '<?php echo $id_consulta; ?>';
        var id_paciente = '<?php echo $id_paciente; ?>';
        var tipo_consulta = '<?php echo $tipo_consulta; ?>';

        cargar_datos_paciente(id_paciente);

        datos_col_ficha_medica(id_paciente);

        if (id_consulta !== '') {
            datos_col_consulta(id_consulta);
            cargar_farmacologia(id_consulta);
        }


        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //Para la consulta - llenado de datos
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        consulta_hora_desde();

        $('#sa_conp_desde_hora, #sa_conp_hasta_hora').change(function() {
            calcular_diferencia_hora();
        });

        $('#sa_conp_fecha_inicio_falta_certificado, #sa_conp_fecha_fin_alta_certificado').change(function() {
            calcular_diferencia_fecha();
        });
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        $('#tipo_farmacologia_presentacion').on('select2:select', function(e) {
            var data = e.params.data.data;
            if ($('#tipo_farmacologia').val() == 'medicamentos') {
                $('#stock_farmacologia').val(data['sa_cmed_stock']);
            } else {
                $('#stock_farmacologia').val(data['sa_cins_stock']);
            }
            console.log(data);
        });

    });



    function limitarMaximo(id) {
        var max = parseInt($('#sa_det_conp_cantidad_' + id).attr('max'));
        if (parseInt($('#sa_det_conp_cantidad_' + id).val()) > max) {
            Swal.fire('Cantidad supera el stock', '', 'info').then(function() {
                $('#sa_det_conp_cantidad_' + id).val(max);
            })
        }
    }

    function limpiar() {
        $('#stock_farmacologia').val('');
        $('#cantidad_farmacologia').val('');
        $('#tipo_farmacologia').val('');
        $('#tipo_farmacologia_presentacion').empty();

    }


    //Para traer los datos necesarios para cargar el formulario
    function carga_datos_consulta(id_consulta = '') {
        //alert(id_consulta)
        $.ajax({
            data: {
                id_consulta: id_consulta
            },
            url: '../controlador/consultasC.php?datos_consulta=true',
            type: 'post',
            dataType: 'json',
            //Para el id representante tomar los datos con los de session
            success: function(response) {
                //console.log(response);

            }
        });
    }

    //Datos del paciente
    function cargar_datos_paciente(sa_pac_id) {
        $.ajax({
            data: {
                sa_pac_id: sa_pac_id

            },
            url: '../controlador/pacientesC.php?obtener_info_paciente=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                // console.log(response);
                ///  Para la tabla de inicio /////////////////////////////////////////////////////////////////////////////////////////////////////////
                $('#txt_ci').html(response[0].sa_pac_temp_cedula + " <i class='bx bxs-id-card'></i>");
                nombres = response[0].sa_pac_temp_primer_nombre + ' ' + response[0].sa_pac_temp_segundo_nombre;
                apellidos = response[0].sa_pac_temp_primer_apellido + ' ' + response[0].sa_pac_temp_segundo_apellido;

                $('#txt_nombres').html(apellidos + " " + nombres);

                $('#title_paciente').html(apellidos + " " + nombres);

                $('#nombre_modal').val(apellidos + " " + nombres);

                $('#tipo_paciente').html(response[0].sa_pac_tabla);

                $('#sa_permiso_pac_id').val(response[0].sa_pac_id);
                $('#sa_permiso_pac_tabla').val(response[0].sa_pac_tabla);


                sexo_paciente = '';
                if (response[0].sa_pac_temp_sexo === 'Masculino') {
                    sexo_paciente = "Masculino <i class='bx bx-male'></i>";
                } else if (response[0].sa_pac_temp_sexo === 'Femenino') {
                    sexo_paciente = "Famenino <i class='bx bx-female'></i>";
                }
                $('#txt_sexo').html(sexo_paciente);
                $('#txt_fecha_nacimiento').html(fecha_nacimiento_formateada(response[0].sa_pac_temp_fecha_nacimiento) + ' (' + calcular_edad_fecha_nacimiento(response[0].sa_pac_temp_fecha_nacimiento) + ' años)');


                if (response[0].sa_pac_tabla == 'estudiantes') {
                    curso = response[0].sa_pac_temp_sec_nombre + '/' + response[0].sa_pac_temp_gra_nombre + '/' + response[0].sa_pac_temp_par_nombre;
                    $('#txt_curso').html(curso);
                    $('#sa_conp_nivel').val(response[0].sa_pac_temp_gra_nombre);
                    $('#sa_conp_paralelo').val(response[0].sa_pac_temp_par_nombre);

                    $('#lbl_telefono_emergencia').html('Teléfono Representante: ' + '<label style="color: red;">*</label>');
                    $('#btn_telefono').val('estudiantes');

                } else {
                    $('#variable_paciente').html('Teléfono:');
                    $('#txt_curso').html(response[0].sa_pac_temp_telefono_1);

                    $('#lbl_telefono_emergencia').html('Teléfono de Emergencia: ' + '<label style="color: red;">*</label>');
                }

                /////////////Para el formulario
                $('#sa_conp_edad').val(calcular_edad_fecha_nacimiento(response[0].sa_pac_temp_fecha_nacimiento));

                $('#txt_id_comunidad').val(response[0].sa_pac_id_comunidad);
                $('#txt_tabla').val(response[0].sa_pac_tabla);

                //alert(calcular_edad_fecha_nacimiento(response[0].sa_pac_temp_fecha_nacimiento.date))
            }
        });
    }

    function lista_seguros(id_seleccionado) {

        var parametros = {
            'id': $('#txt_id_comunidad').val(),
            'tabla': $('#txt_tabla').val(),
        }

        console.log(parametros);
        var option = '<option selected disabled value="">-- Seleccione Seguro --</option>';
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/ficha_medicaC.php?lista_seguros=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                //console.log(response);
                $.each(response, function(i, item) {
                    if (id_seleccionado == item.id_arti_asegurados) {
                        option += '<option value ="' + item.id_arti_asegurados + '" selected>' + item.nombre + '</option>'
                        $('#sa_conp_permiso_telefono_seguro').val(item.telefono);
                        $('#sa_conp_permiso_telefono_padre').val(item.telefono_asesor)
                        console.log(item)
                    } else {
                        option += '<option value ="' + item.id_arti_asegurados + '">' + item.nombre + '</option>'
                    }
                });

                $('#sa_conp_permiso_seguro_traslado').html(option);

            }
        });
    }


    //Datos de la ficha medica
    function datos_col_ficha_medica(sa_pac_id) {
        //alert(id_ficha)
        $.ajax({
            data: {
                sa_pac_id: sa_pac_id
            },
            url: '../controlador/ficha_MedicaC.php?listar_paciente_ficha=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                // console.log(response);
                // Preguntas
                $('#txt_sa_fice_pac_grupo_sangre').html(response[0].sa_fice_pac_grupo_sangre);
                $('#txt_sa_fice_pregunta_1_obs').html(response[0].sa_fice_pregunta_1_obs);
                $('#txt_sa_fice_pregunta_2_obs').html(response[0].sa_fice_pregunta_2_obs);
                $('#txt_sa_fice_pregunta_3_obs').html(response[0].sa_fice_pregunta_3_obs);
                $('#txt_sa_fice_pregunta_4_obs').html(response[0].sa_fice_pregunta_4_obs);
                $('#txt_sa_fice_pregunta_5_obs').html(response[0].sa_fice_pregunta_5_obs);
                lista_seguros(response[0].sa_fice_pac_seguro_predeterminado);
                // $('#sa_conp_permiso_seguro_traslado').val(response[0].sa_fice_pac_seguro_predeterminado);
            }
        });
    }

    //Telefonos en caso de salida
    function llenar_telefonos_salida() {

        sa_pac_id = $('#sa_permiso_pac_id').val();
        sa_pac_tabla = $('#sa_permiso_pac_tabla').val();

        //alert(sa_pac_id);

        $.ajax({
            data: {
                sa_pac_id: sa_pac_id
            },
            url: '../controlador/ficha_MedicaC.php?listar_paciente_ficha=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                //console.log(response);

                nombre_completo_1 = '';
                telefono_1_1 = '';
                telefono_1_2 = '';

                if (sa_pac_tabla === 'estudiantes') {
                    nombre_completo_1 = response[0].sa_fice_rep_1_primer_apellido + ' ' + response[0].sa_fice_rep_1_segundo_apellido + ' ' + response[0].sa_fice_rep_1_primer_nombre + ' ' + response[0].sa_fice_rep_1_segundo_nombre;
                    telefono_1_1 = response[0].sa_fice_rep_1_telefono_1;
                    telefono_1_2 = response[0].sa_fice_rep_1_telefono_2;
                } else {
                    nombre_completo_1 = response[0].sa_fice_rep_2_primer_apellido + ' ' + response[0].sa_fice_rep_2_segundo_apellido + ' ' + response[0].sa_fice_rep_2_primer_nombre + ' ' + response[0].sa_fice_rep_2_segundo_nombre;
                    telefono_1_1 = response[0].sa_fice_rep_2_telefono_1;
                    telefono_1_2 = response[0].sa_fice_rep_2_telefono_2;
                }

                $('#txt_nombre_contacto').html(nombre_completo_1 + '<br> <label style="color: black;">Teléfono 2:&nbsp;</label>' + telefono_1_2);
                $('#sa_conp_permiso_telefono_padre').val(telefono_1_1);

            }
        });
    }

    //Datos de la consulta aun no se utiliza
    function datos_col_consulta(id_consulta) {
        $.ajax({
            data: {
                id: id_consulta
            },
            url: '../controlador/consultasC.php?listar_solo_consulta=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {

                // console.log(response);
                // Asignar los valores a los campos del formulario
                $('#sa_conp_id').val(response[0].sa_conp_id);
                $('#sa_fice_id').val(response[0].sa_fice_id);
                //$('#sa_conp_nivel').val(response[0].sa_conp_nivel);
                //$('#sa_conp_paralelo').val(response[0].sa_conp_paralelo);

                //$('#sa_conp_edad').val(response[0].sa_conp_edad);
                $('#sa_conp_peso').val(response[0].sa_conp_peso);
                $('#sa_conp_altura').val(response[0].sa_conp_altura);

                calcularIMC();

                $('#sa_conp_temperatura').val(response[0].sa_conp_temperatura);
                $('#sa_conp_presion_ar').val(response[0].sa_conp_presion_ar);
                $('#sa_conp_frec_cardiaca').val(response[0].sa_conp_frec_cardiaca);
                $('#sa_conp_frec_respiratoria').val(response[0].sa_conp_frec_respiratoria);

                $('#sa_conp_fecha_ingreso').val(fecha_nacimiento_formateada(response[0].sa_conp_fecha_ingreso.date));

                $('#sa_conp_tiempo_aten').val(response[0].sa_conp_tiempo_aten);
                $('#sa_conp_CIE_10_1').val(response[0].sa_conp_CIE_10_1);
                $('#sa_conp_diagnostico_1').val(response[0].sa_conp_diagnostico_1);
                $('#sa_conp_CIE_10_2').val(response[0].sa_conp_CIE_10_2);
                $('#sa_conp_diagnostico_2').val(response[0].sa_conp_diagnostico_2);

                $('#sa_conp_salud_certificado').val(response[0].sa_conp_salud_certificado);
                $('#sa_conp_motivo_certificado').val(response[0].sa_conp_motivo_certificado);
                $('#sa_conp_CIE_10_certificado').val(response[0].sa_conp_CIE_10_certificado);
                $('#sa_conp_diagnostico_certificado').val(response[0].sa_conp_diagnostico_certificado);
                //$('#sa_conp_fecha_entrega_certificado').val(fecha_nacimiento_formateada(response[0].sa_conp_fecha_entrega_certificado.date));
                //$('#sa_conp_fecha_inicio_falta_certificado').val(fecha_nacimiento_formateada(response[0].sa_conp_fecha_inicio_falta_certificado.date));
                //$('#sa_conp_fecha_fin_alta_certificado').val(fecha_nacimiento_formateada(response[0].sa_conp_fecha_fin_alta_certificado.date));


                validar_fecha_formulario(response[0].sa_conp_fecha_inicio_falta_certificado, 'sa_conp_fecha_inicio_falta_certificado');
                validar_fecha_formulario(response[0].sa_conp_fecha_fin_alta_certificado, 'sa_conp_fecha_fin_alta_certificado');

                $('#sa_conp_dias_permiso_certificado').val(response[0].sa_conp_dias_permiso_certificado);

                /////////////////////////////
                //$('#sa_conp_permiso_salida').val(response[0].sa_conp_permiso_salida);

                $('input[name=sa_conp_permiso_salida][value=' + response[0].sa_conp_permiso_salida + ']').prop('checked', true);
                if (response[0].sa_conp_permiso_salida === "SI") {
                    $('#permiso_salida').show();
                } else if (response[0].sa_conp_permiso_salida === "NO") {
                    $('#permiso_salida').hide();
                }

                validar_fecha_formulario(response[0].sa_conp_fecha_permiso_salud_salida, 'sa_conp_fecha_permiso_salud_salida')

                $('#sa_conp_hora_permiso_salida').val(obtener_hora_formateada(response[0].sa_conp_hora_permiso_salida.date));

                //////////////////////////////
                //$('#sa_conp_permiso_tipo').val(response[0].sa_conp_permiso_tipo);

                $('input[name=sa_conp_permiso_tipo][value=' + response[0].sa_conp_permiso_tipo + ']').prop('checked', true);
                if (response[0].sa_conp_permiso_tipo === "emergencia") {
                    $('#permiso_salida_tipo').show();
                } else if (response[0].sa_conp_permiso_tipo === "normal") {
                    $('#permiso_salida_tipo').hide();
                }


                $('#sa_conp_permiso_seguro_traslado').val(response[0].sa_conp_permiso_seguro_traslado);
                $('#sa_conp_permiso_telefono_padre').val(response[0].sa_conp_permiso_telefono_padre);
                $('#sa_conp_permiso_telefono_seguro').val(response[0].sa_conp_permiso_telefono_seguro);

                $('#sa_conp_notificacion_envio_representante').val(response[0].sa_conp_notificacion_envio_representante);
                $('#sa_id_representante').val(response[0].sa_id_representante);
                $('#sa_conp_notificacion_envio_docente').val(response[0].sa_conp_notificacion_envio_docente);
                $('#sa_id_docente').val(response[0].sa_id_docente);
                $('#sa_conp_notificacion_envio_inspector').val(response[0].sa_conp_notificacion_envio_inspector);
                $('#sa_id_inspector').val(response[0].sa_id_inspector);
                $('#sa_conp_notificacion_envio_guardia').val(response[0].sa_conp_notificacion_envio_guardia);
                $('#sa_id_guardia').val(response[0].sa_id_guardia);

                $('#sa_conp_observaciones').val(response[0].sa_conp_observaciones);
                $('#sa_conp_motivo_consulta').val(response[0].sa_conp_motivo_consulta);
                $('#sa_conp_tratamiento').val(response[0].sa_conp_tratamiento);

                $('#sa_conp_enfermedad_actual').val(response[0].sa_conp_enfermedad_actual);
                $('#sa_conp_saturacion').val(response[0].sa_conp_saturacion);

                estado = response[0].sa_conp_estado_revision;
                //alert(estado)
                if ((estado != 0)) {
                    $('#sa_conp_desde_hora').val(obtener_hora_formateada(response[0].sa_conp_desde_hora.date));
                    $('#sa_conp_hasta_hora').val(obtener_hora_formateada(response[0].sa_conp_hasta_hora.date));

                    //Para certficado
                    validar_fecha_formulario(response[0].sa_conp_fecha_entrega_certificado, 'sa_conp_fecha_entrega_certificado');
                }

                /*$('#sa_conp_tipo_consulta').val(response[0].sa_conp_tipo_consulta);
                $('#sa_conp_estado_revision').val(response[0].sa_conp_estado_revision);
                $('#sa_conp_fecha_creacion').val(response[0].sa_conp_fecha_creacion);
                $('#sa_conp_fecha_modificacion').val(response[0].sa_conp_fecha_modificacion);
                $('#sa_conp_estado').val(response[0].sa_conp_estado);*/

            }
        });
    }

    function validar_fecha_formulario(campo, nombre_input) {
        if (campo && campo.date !== null) {
            $('#' + nombre_input).val(fecha_nacimiento_formateada(campo.date));
        } else {
            $('#' + nombre_input).val(''); // Establecer el valor como vacío
        }
    }

    //falta estado para profesor
    function editar_insertar(n_representante = '', n_docente = '', n_inspector = '', n_guardia = '', revision = '') {

        hora_hasta = obtener_hora_hasta();
        $('#sa_conp_hasta_hora').val(hora_hasta);

        tardo = calcular_diferencia_hora_retorno();


        var sa_conp_id = $('#sa_conp_id').val();

        var sa_fice_id = $('#sa_fice_id').val();
        var sa_conp_nivel = $('#sa_conp_nivel').val();
        var sa_conp_paralelo = $('#sa_conp_paralelo').val();
        var sa_conp_edad = $('#sa_conp_edad').val();
        var sa_conp_peso = $('#sa_conp_peso').val();
        var sa_conp_altura = $('#sa_conp_altura').val();

        var sa_conp_temperatura = $('#sa_conp_temperatura').val();
        var sa_conp_presion_ar = $('#sa_conp_presion_ar').val();
        var sa_conp_frec_cardiaca = $('#sa_conp_frec_cardiaca').val();
        var sa_conp_frec_respiratoria = $('#sa_conp_frec_respiratoria').val();

        // Fechas y horas
        var sa_conp_fecha_ingreso = ($('#sa_conp_fecha_ingreso').val());
        var sa_conp_desde_hora = ($('#sa_conp_desde_hora').val());
        var sa_conp_hasta_hora = ($('#sa_conp_hasta_hora').val());
        var sa_conp_tiempo_aten = $('#sa_conp_tiempo_aten').val();

        if (sa_conp_tiempo_aten == 'NaN') {
            Swal.fire('', 'Ingrese una hora valida', 'info');
            return false;
        }

        // Diagnósticos y medicamentos
        var sa_conp_CIE_10_1 = $('#sa_conp_CIE_10_1').val() ?? '';
        var sa_conp_diagnostico_1 = $('#sa_conp_diagnostico_1').val() ?? '';
        var sa_conp_CIE_10_2 = $('#sa_conp_CIE_10_2').val() ?? '';
        var sa_conp_diagnostico_2 = $('#sa_conp_diagnostico_2').val() ?? '';

        // Certificados y permisos
        var sa_conp_salud_certificado = $('#sa_conp_salud_certificado').val();
        var sa_conp_motivo_certificado = $('#sa_conp_motivo_certificado').val();
        var sa_conp_CIE_10_certificado = $('#sa_conp_CIE_10_certificado').val();
        var sa_conp_diagnostico_certificado = $('#sa_conp_diagnostico_certificado').val();
        var sa_conp_fecha_entrega_certificado = ($('#sa_conp_fecha_entrega_certificado').val());
        var sa_conp_fecha_inicio_falta_certificado = ($('#sa_conp_fecha_inicio_falta_certificado').val());
        var sa_conp_fecha_fin_alta_certificado = ($('#sa_conp_fecha_fin_alta_certificado').val());
        var sa_conp_dias_permiso_certificado = $('#sa_conp_dias_permiso_certificado').val();

        // Permisos de salida
        var sa_conp_permiso_salida = $('input[name=sa_conp_permiso_salida]:checked').val() ?? '';
        var sa_conp_fecha_permiso_salud_salida = ($('#sa_conp_fecha_permiso_salud_salida').val()) ?? '';
        var sa_conp_hora_permiso_salida = ($('#sa_conp_hora_permiso_salida').val()) ?? '';

        var sa_conp_permiso_tipo = $('input[name=sa_conp_permiso_tipo]:checked').val() ?? '';
        var sa_conp_permiso_seguro_traslado = ($('#sa_conp_permiso_seguro_traslado').val()) ?? '';
        var sa_conp_permiso_telefono_padre = ($('#sa_conp_permiso_telefono_padre').val()) ?? '';
        var sa_conp_permiso_telefono_seguro = ($('#sa_conp_permiso_telefono_seguro').val()) ?? '';

        // Notificaciones y observaciones
        var sa_conp_notificacion_envio_representante = n_representante;
        var sa_id_representante = $('#sa_id_representante').val();

        var sa_conp_notificacion_envio_docente = n_docente;
        var sa_id_docente = $('#sa_id_docente').val();

        var sa_conp_notificacion_envio_inspector = n_inspector;
        var sa_id_inspector = $('#sa_id_inspector').val();

        var sa_conp_notificacion_envio_guardia = n_guardia;
        var sa_id_guardia = $('#sa_id_guardia').val();

        var sa_conp_observaciones = $('#sa_conp_observaciones').val();

        var sa_conp_motivo_consulta = $('#sa_conp_motivo_consulta').val();
        var sa_conp_tratamiento = $('#sa_conp_tratamiento').val() ?? '';

        var sa_conp_tipo_consulta = '<?= $tipo_consulta; ?>';

        var sa_conp_enfermedad_actual = $('#sa_conp_enfermedad_actual').val() ?? '';
        var sa_conp_saturacion = $('#sa_conp_saturacion').val();

        var sa_conp_estado_revision = revision;

        var sa_examen_fisico_regional = generarJSON();

        // Crear objeto de parámetros
        var parametros = {
            'sa_conp_id': sa_conp_id,
            'sa_fice_id': sa_fice_id,
            'sa_conp_nivel': sa_conp_nivel,
            'sa_conp_paralelo': sa_conp_paralelo,
            'sa_conp_edad': sa_conp_edad,
            'sa_conp_peso': sa_conp_peso,
            'sa_conp_altura': sa_conp_altura,

            'sa_conp_temperatura': sa_conp_temperatura,
            'sa_conp_presion_ar': sa_conp_presion_ar,
            'sa_conp_frec_cardiaca': sa_conp_frec_cardiaca,
            'sa_conp_frec_respiratoria': sa_conp_frec_respiratoria,

            'sa_conp_fecha_ingreso': sa_conp_fecha_ingreso,
            'sa_conp_desde_hora': sa_conp_desde_hora,
            'sa_conp_hasta_hora': sa_conp_hasta_hora,
            'sa_conp_tiempo_aten': sa_conp_tiempo_aten,
            'sa_conp_CIE_10_1': sa_conp_CIE_10_1,
            'sa_conp_diagnostico_1': sa_conp_diagnostico_1,
            'sa_conp_CIE_10_2': sa_conp_CIE_10_2,
            'sa_conp_diagnostico_2': sa_conp_diagnostico_2,

            'sa_conp_salud_certificado': sa_conp_salud_certificado,
            'sa_conp_motivo_certificado': sa_conp_motivo_certificado,
            'sa_conp_CIE_10_certificado': sa_conp_CIE_10_certificado,
            'sa_conp_diagnostico_certificado': sa_conp_diagnostico_certificado,
            'sa_conp_fecha_entrega_certificado': sa_conp_fecha_entrega_certificado,
            'sa_conp_fecha_inicio_falta_certificado': sa_conp_fecha_inicio_falta_certificado,
            'sa_conp_fecha_fin_alta_certificado': sa_conp_fecha_fin_alta_certificado,
            'sa_conp_dias_permiso_certificado': sa_conp_dias_permiso_certificado,

            'sa_conp_permiso_salida': sa_conp_permiso_salida,
            'sa_conp_fecha_permiso_salud_salida': sa_conp_fecha_permiso_salud_salida,
            'sa_conp_hora_permiso_salida': sa_conp_hora_permiso_salida,

            'sa_conp_permiso_tipo': sa_conp_permiso_tipo,
            'sa_conp_permiso_seguro_traslado': sa_conp_permiso_seguro_traslado,
            'sa_conp_permiso_telefono_padre': sa_conp_permiso_telefono_padre,
            'sa_conp_permiso_telefono_seguro': sa_conp_permiso_telefono_seguro,

            'sa_conp_notificacion_envio_representante': sa_conp_notificacion_envio_representante,
            'sa_id_representante': sa_id_representante,
            'sa_conp_notificacion_envio_docente': sa_conp_notificacion_envio_docente,
            'sa_id_docente': sa_id_docente,
            'sa_conp_notificacion_envio_inspector': sa_conp_notificacion_envio_inspector,
            'sa_id_inspector': sa_id_inspector,
            'sa_conp_notificacion_envio_guardia': sa_conp_notificacion_envio_guardia,
            'sa_id_guardia': sa_id_guardia,

            'sa_conp_observaciones': sa_conp_observaciones,

            'sa_conp_motivo_consulta': sa_conp_motivo_consulta,
            'sa_conp_tratamiento': sa_conp_tratamiento,

            'sa_conp_tipo_consulta': sa_conp_tipo_consulta,
            'sa_conp_enfermedad_actual': sa_conp_enfermedad_actual,
            'sa_conp_saturacion': sa_conp_saturacion,

            'sa_conp_estado_revision': sa_conp_estado_revision,

            'sa_examen_fisico_regional': sa_examen_fisico_regional,

        };

        //alert(sa_conp_tipo_consulta)

        //console.log(parametros);
        //insertar(parametros)


        var filas_tabla_farmacologia = [];

        $('#lista_medicamentos tr:gt(0)').each(function(index, fila) {
            var valores = {
                sa_det_conp_id: $(fila).find('input[id^="sa_det_conp_id_"]').val(),

                sa_det_conp_nombre: $(fila).find('input[id^="sa_det_conp_nombre_"]').val(),
                sa_det_conp_id_cmed_cins: $(fila).find('input[id^="sa_det_conp_id_cmed_cins_"]').val(),
                sa_det_conp_tipo: $(fila).find('input[id^="sa_det_conp_tipo_"]').val(),
                sa_det_conp_dosificacion: $(fila).find('input[id^="sa_det_conp_dosificacion_"]').val(),
                sa_det_conp_cantidad: $(fila).find('input[id^="sa_det_conp_cantidad_"]').val(),
                sa_det_conp_nombre_temp: $(fila).find('label[id^="sa_det_conp_nombre_temp_"]').text(),
                medicamentos: $(fila).find('input[id^="medicamentos_"]').val(),
                sa_det_conp_estado_entrega: $(fila).find('input[type="checkbox"][name^="sa_det_conp_estado_entrega_"]').prop('checked')

            };

            filas_tabla_farmacologia.push(valores);
        });

        //console.log(filas_tabla_farmacologia);

        if (sa_conp_tipo_consulta == 'consulta') {
            if (sa_conp_id == '') {
                if (
                    sa_conp_peso == '' ||
                    sa_conp_altura == '' ||
                    sa_conp_temperatura == '' ||
                    sa_conp_presion_ar == '' ||
                    sa_conp_frec_cardiaca == '' ||
                    sa_conp_frec_respiratoria == '' ||
                    sa_conp_saturacion == ''

                ) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Asegurese de llenar todo los campos',
                    })
                } else {
                    parametros.filas_tabla_farmacologia = filas_tabla_farmacologia;
                    insertar(parametros);
                    //console.log(parametros);

                }
            } else {
                parametros.filas_tabla_farmacologia = filas_tabla_farmacologia;
                insertar(parametros);
                //console.log(parametros);
            }

        } else if (sa_conp_tipo_consulta == 'certificado') {
            insertar(parametros)
            //console.log(parametros);
        }

    }

    function insertar(parametros) {
        tardo = calcular_diferencia_hora_retorno();

        console.log(parametros);
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/consultasC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                //console.log(response);

                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito. La atención tardó: ' + tardo + ' minutos.', 'success').then(function() {
                        <?php if ($regresar == 'agendamiento') { ?>
                            location.href = '../vista/inicio.php?mod=7&acc=agendamiento';
                        <?php } else { ?>
                            location.href = '../vista/inicio.php?mod=7&acc=pacientes';
                        <?php } ?>


                        
                    });
                } else if (response == -2) {
                    Swal.fire('', 'Código ya registrado', 'success');
                }
                //console.log(response);
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
                            <?php
                            if ($id_consulta == '') {
                                echo 'REGISTRAR ' . strtoupper($tipo_consulta) . '';
                            } else {
                                echo 'MODIFICAR ' . strtoupper($tipo_consulta) . '';
                            }
                            ?>
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
                        <div class="row">

                            <div class="col-6">
                                <div class="card-title d-flex align-items-center">
                                    <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                                    </div>
                                    <h5 class="mb-0 text-primary">
                                        <?php if ($id_consulta == '') { ?>
                                            Paciente: <b class="text-success" id="title_paciente"></b>
                                        <?php } else { ?>
                                            Paciente: <b class="text-success" id="title_paciente"></b>
                                        <?php } ?>
                                    </h5>
                                </div>
                            </div>

                            <div class="col-6 text-end">
                                <a hidden href="../vista/inicio.php?mod=7&acc=consultas_pacientes&pac_id=<?= $id_paciente ?>" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>

                                <input type="hidden" name="nombre_modal" id="nombre_modal">

                                <button class="btn btn-outline-primary" onclick="consultar_datos_h(<?php echo $id_ficha; ?>, $('#nombre_modal').val())"><i class='bx bx-list-ol'></i> Historial</button>
                            </div>


                        </div>

                        <hr>

                        <form action="">

                            <input type="hidden" id="sa_conp_id" name="sa_conp_id">
                            <input type="hidden" id="sa_fice_id" name="sa_fice_id" value="<?= $id_ficha; ?>">

                            <input type="hidden" id="sa_conp_nivel" name="sa_conp_nivel">
                            <input type="hidden" id="sa_conp_paralelo" name="sa_conp_paralelo">
                            <input type="hidden" id="sa_conp_edad" name="sa_conp_edad">

                            <input type="hidden" id="sa_conp_notificacion_envio_representante" name="sa_conp_notificacion_envio_representante">
                            <input type="hidden" id="sa_id_representante" name="sa_id_representante">

                            <input type="hidden" id="sa_conp_notificacion_envio_docente" name="sa_conp_notificacion_envio_docente">
                            <input type="hidden" id="sa_id_docente" name="sa_id_docente">

                            <input type="hidden" id="sa_conp_notificacion_envio_inspector" name="sa_conp_notificacion_envio_inspector">
                            <input type="hidden" id="sa_id_inspector" name="sa_id_inspector">

                            <input type="hidden" id="sa_conp_notificacion_envio_guardia" name="sa_conp_notificacion_envio_guardia">
                            <input type="hidden" id="sa_id_guardia" name="sa_id_guardia">

                            <div class="row" hidden>
                                <div class="col-md-3">
                                    <label for="" class="form-label fw-bold">Fecha <label style="color: red;">*</label> </label>
                                    <input type="date" class="form-control form-control-sm" id="sa_conp_fecha_ingreso" name="sa_conp_fecha_ingreso" value="<?= date('Y-m-d'); ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="" class="form-label fw-bold">Desde <label style="color: red;">*</label> </label>
                                    <input type="time" class="form-control form-control-sm" id="sa_conp_desde_hora" name="sa_conp_desde_hora">
                                </div>
                                <div class="col-md-3">
                                    <label for="" class="form-label fw-bold">Hasta <label style="color: red;">*</label> </label>
                                    <input type="time" class="form-control form-control-sm" id="sa_conp_hasta_hora" name="sa_conp_hasta_hora">
                                </div>
                                <div class="col-md-3">
                                    <label for="" class="form-label fw-bold">Tiempo (Minutos) <label style="color: red;">*</label> </label>
                                    <input type="text" class="form-control form-control-sm" id="sa_conp_tiempo_aten" name="sa_conp_tiempo_aten" readonly>
                                </div>
                            </div>



                            <div id="main_consulta" style="display: block;" class="pt-4">
                                <ul class="nav nav-tabs nav-success" role="tablist">

                                    <li class="nav-item" role="presentation" id="seccion_navtab_consulta">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#triage_tab" role="tab" aria-selected="true">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon"><i class='bx bx-receipt font-20 me-1'></i>
                                                </div>

                                                <?php if ($tipo_consulta == 'consulta') { ?>
                                                    <div class="tab-title">TRIAGE</div>
                                                <?php } else if ($tipo_consulta == 'certificado') { ?>
                                                    <div class="tab-title">CERTIFICADO</div>
                                                <?php } ?>

                                            </div>
                                        </a>
                                    </li>

                                    <?php if ($tipo_consulta == 'consulta') { ?>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" data-bs-toggle="tab" href="#consulta_tab" role="tab" aria-selected="false">
                                                <div class="d-flex align-items-center">
                                                    <div class="tab-icon"><i class='bx bx-user-pin font-18 me-1'></i>
                                                    </div>
                                                    <div class="tab-title">CONSULTA</div>
                                                </div>
                                            </a>
                                        </li>
                                    <?php   } ?>

                                    <?php if ($tipo_consulta == 'consulta') { ?>
                                        <li <?php if ($tipo_consulta != 'consulta') {
                                                echo 'hidden';
                                            } ?> class="nav-item" role="presentation" id="">
                                            <a class="nav-link" data-bs-toggle="tab" href="#recetario_tab" role="tab" aria-selected="false">
                                                <div class="d-flex align-items-center">
                                                    <div class="tab-icon"><i class='bx bx-receipt font-20 me-1'></i>
                                                    </div>
                                                    <div class="tab-title">RECETARIO</div>
                                                </div>
                                            </a>
                                        </li>
                                    <?php   } ?>
                                </ul>
                                <div class="tab-content py-3">

                                    <div class="tab-pane fade show active" id="triage_tab" role="tabpanel">

                                        <div>
                                            <!-- Consulta -->
                                            <div <?php if ($tipo_consulta != 'consulta') {
                                                        echo 'hidden';
                                                    } ?>>

                                                <div class="row pt-0">

                                                    <?php include('../vista/ENFERMERIA/Consultas/triage.php'); ?>

                                                </div>
                                            </div>

                                            <!-- Certificado -->
                                            <div <?php if ($tipo_consulta != 'certificado') {
                                                        echo 'hidden';
                                                    } ?>>
                                                <div class="row pt-0">
                                                    <div class="row pt-1">
                                                        <div class="col-md-3">
                                                            <label for="" class="form-label fw-bold">Certificado por Salud <label style="color: red;">*</label> </label>
                                                            <input type="text" class="form-control form-control-sm" id="sa_conp_salud_certificado" name="sa_conp_salud_certificado">
                                                        </div>

                                                        <div class="col-md-9">
                                                            <label for="" class="form-label fw-bold">Motivo Certificado <label style="color: red;">*</label> </label>
                                                            <input type="text" class="form-control form-control-sm" id="sa_conp_motivo_certificado" name="sa_conp_motivo_certificado">
                                                        </div>
                                                    </div>

                                                    <div class="row pt-3">
                                                        <div class="col-md-12">
                                                            <label for="" class="form-label fw-bold">CIE 10 - Diagnóstico de Certificado <label style="color: red;">*</label> </label>
                                                            <input type="text" class="ctw-input form-control form-control-sm" autocomplete="off" data-ctw-ino="3" id="sa_conp_diagnostico_certificado" placeholder="Diagnostico">

                                                            <input type="hidden" id="sa_conp_CIE_10_certificado">
                                                            <div class="ctw-window" data-ctw-ino="3"></div>

                                                        </div>
                                                    </div>

                                                    <div class="row pt-3">
                                                        <div class="col-md-3">
                                                            <label for="" class="form-label fw-bold">Fecha de Entrega del Certificado <label style="color: red;">*</label> </label>
                                                            <input type="date" class="form-control form-control-sm" id="sa_conp_fecha_entrega_certificado" name="sa_conp_fecha_entrega_certificado" value="<?= date('Y-m-d'); ?>">
                                                        </div>

                                                        <div class="col-md-3">
                                                            <label for="" class="form-label fw-bold">Fecha Inicio Falta <label style="color: red;">*</label> </label>
                                                            <input type="date" class="form-control form-control-sm" id="sa_conp_fecha_inicio_falta_certificado" name="sa_conp_fecha_inicio_falta_certificado">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="" class="form-label fw-bold">Fecha Fin Alta <label style="color: red;">*</label> </label>
                                                            <input type="date" class="form-control form-control-sm" id="sa_conp_fecha_fin_alta_certificado" name="sa_conp_fecha_fin_alta_certificado">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="" class="form-label fw-bold"># Días de Permiso <label style="color: red;">*</label> </label>
                                                            <input type="text" class="form-control form-control-sm" id="sa_conp_dias_permiso_certificado" name="sa_conp_dias_permiso_certificado">
                                                        </div>
                                                    </div>

                                                    <?php if ($tipo_consulta == 'certificado') { ?>
                                                        <div class="row pt-3">
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label fw-bold">Observaciones <label style="color: red;">*</label> </label>
                                                                <textarea name="sa_conp_observaciones" id="sa_conp_observaciones" cols="30" rows="1" class="form-control" placeholder="Observaciones"></textarea>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>

                                                <div class="modal-footer pt-4" id="seccion_boton_consulta">

                                                    <button hidden class="btn btn-danger btn-sm px-2 m-1" onclick="editar_insertar(1, 1, 1, 0, 2)" type="button"><i class='bx bx-pause-circle'></i> En Proceso</button>

                                                    <button class="btn btn-success btn-sm px-2 m-1" onclick="editar_insertar(1, 1, 1, 0, 1)" type="button"><i class="bx bx-save"></i> Finalizar</button>

                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <?php if ($tipo_consulta == 'consulta') { ?>
                                        <div class="tab-pane fade show" id="consulta_tab" role="tabpanel">

                                            <div class="accordion" id="consulta_acordeon">

                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="flush-headingOne">
                                                        <button class="accordion-button collapsed bg-success" type="button" data-bs-toggle="collapse" data-bs-target="#flush-estudiante" aria-expanded="false" aria-controls="flush-estudiante">
                                                            <h6 class="text-white"><b>Ficha Médica</b></h6>
                                                        </button>
                                                    </h2>
                                                    <div id="flush-estudiante" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#consulta_acordeon">
                                                        <div class="accordion-body">



                                                            <div class="row pt-3">
                                                                <div class="col-12">
                                                                    <div class="">
                                                                        <table class="table mb-0" style="width:100%">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th style="width: 30%;"></th>
                                                                                    <th style="width: 25%;"></th>
                                                                                    <th style="width: 25%;"></th>
                                                                                    <th style="width: 25%;"></th>
                                                                                </tr>

                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <th class="table-primary text-end">Cédula:</th>
                                                                                    <td id="txt_ci"></td>

                                                                                    <th class="table-primary text-end">Sexo:</th>
                                                                                    <td id="txt_sexo"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th class="table-primary text-end">Nombres:</th>
                                                                                    <td id="txt_nombres" colspan="3"></td>
                                                                                </tr>

                                                                                <tr>
                                                                                    <th class="table-primary text-end">Fecha de Nacimiento:</th>
                                                                                    <td id="txt_fecha_nacimiento"></td>

                                                                                    <th class="table-primary text-end" id="variable_paciente">Grupo Sanguíneo:</th>
                                                                                    <td id="txt_sa_fice_pac_grupo_sangre"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th class="table-primary text-end" id="variable_paciente">Curso:</th>
                                                                                    <td id="txt_curso" colspan="3"></td>
                                                                                </tr>

                                                                                <tr>
                                                                                    <th class="table-primary text-end" id="variable_paciente">1.- ¿Ha sido diagnosticado con alguna enfermedad?:</th>
                                                                                    <td id="txt_sa_fice_pregunta_1_obs" colspan="3"></td>
                                                                                </tr>

                                                                                <tr>
                                                                                    <th class="table-primary text-end" id="variable_paciente">2.- ¿Tiene algún antecedente familiar de importancia?:</th>
                                                                                    <td id="txt_sa_fice_pregunta_2_obs" colspan="3"></td>
                                                                                </tr>

                                                                                <tr>
                                                                                    <th class="table-primary text-end" id="variable_paciente">3.- ¿Ha sido sometido a cirugías previas?:</th>
                                                                                    <td id="txt_sa_fice_pregunta_3_obs" colspan="3"></td>
                                                                                </tr>

                                                                                <tr>
                                                                                    <th class="table-primary text-end" id="variable_paciente">4.- ¿Tiene alergias?:</th>
                                                                                    <td id="txt_sa_fice_pregunta_4_obs" colspan="3"></td>
                                                                                </tr>

                                                                                <tr>
                                                                                    <th class="table-primary text-end" id="variable_paciente">5.- ¿Qué medicamentos usa?:</th>
                                                                                    <td id="txt_sa_fice_pregunta_5_obs" colspan="3"></td>
                                                                                </tr>

                                                                            </tbody>
                                                                        </table>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <hr>

                                            <div>
                                                <!-- Consulta -->
                                                <div>

                                                    <div class="row pt-0">

                                                        <div class="row pt-3">
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label fw-bold"> <b>Agregar exámen físico regional <label class="text-danger">*</label></b></label>

                                                                <div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="rb_examen_fisico" id="rb_examen_fisico_1" value="SI">
                                                                        <label class="form-check-label" for="rb_examen_fisico_1">SI</label>
                                                                    </div>

                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="rb_examen_fisico" id="rb_examen_fisico_2" value="NO" checked>
                                                                        <label class="form-check-label" for="rb_examen_fisico_2">NO</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>



                                                        <div class="row pt-3" id="pnl_examen_fisico" style="display: none;">
                                                            <div class="col-12">
                                                                <label for="" class="form-label fw-bold"><b>EXAMEN FÍSICO REGIONAL</b> <label style="color: red;">*</label> </label>

                                                                <div class="">
                                                                    <style>
                                                                        .vertical-text {
                                                                            writing-mode: vertical-lr;
                                                                            transform: rotate(180deg);
                                                                            white-space: nowrap;
                                                                            text-align: center;
                                                                            /* Centra el texto horizontalmente */
                                                                            vertical-align: middle;
                                                                            /* Centra el texto verticalmente */
                                                                        }

                                                                        .table-bordered th,
                                                                        .table-bordered td {
                                                                            border: 2px solid black;
                                                                            /* Ajusta el grosor de las líneas a tu preferencia */
                                                                        }
                                                                    </style>

                                                                    <table class="table table-bordered mb-0" style="width:100%">
                                                                        <thead>
                                                                        </thead>
                                                                        <tbody class="small">
                                                                            <tr>
                                                                                <th rowspan="3" class="vertical-text table-primary">1. Piel</th>

                                                                                <th class="table-primary text-end">a. Cicatrices</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Cicatrices">
                                                                                </td>

                                                                                <th rowspan="5" class="vertical-text table-primary">4. Oro faringe</th>

                                                                                <th class="table-primary text-end">a. Labios</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Labios">
                                                                                </td>

                                                                                <th rowspan="2" class="vertical-text table-primary">7. Tórax</th>

                                                                                <th class="table-primary text-end">a. Mamas</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Mamas">
                                                                                </td>

                                                                                <th rowspan="3" class="vertical-text table-primary">12. Extremidades</th>

                                                                                <th class="table-primary text-end">a. Vascular</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Vascular">
                                                                                </td>
                                                                            </tr>

                                                                            <tr>
                                                                                <th class="table-primary text-end">b. Tatuajes</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Tatuajes">
                                                                                </td>

                                                                                <th class="table-primary text-end">b. Lengua</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Lengua">
                                                                                </td>

                                                                                <th class="table-primary text-end">b. Corazón</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Corazon">
                                                                                </td>

                                                                                <th class="table-primary text-end">b. Miembros superiores</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Miembros_superiores">
                                                                                </td>
                                                                            </tr>

                                                                            <tr>
                                                                                <th class="table-primary text-end">c. Piel y Faneras</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Piel_Faneras">
                                                                                </td>

                                                                                <th class="table-primary text-end">c. Faringe</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Faringe">
                                                                                </td>

                                                                                <th rowspan="2" class="vertical-text table-primary">8. Tórax</th>

                                                                                <th class="table-primary text-end">a. Pulmones</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Pulmones">
                                                                                </td>

                                                                                <th class="table-primary text-end">c. Miembros inferiores</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Miembros_inferiores">
                                                                                </td>


                                                                            </tr>

                                                                            <tr>
                                                                                <th rowspan="5" class="vertical-text table-primary">2. Ojos</th>

                                                                                <th class="table-primary text-end">a. Párpados</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Parpados">
                                                                                </td>

                                                                                <th class="table-primary text-end">d. Amígdalas</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Amigdalas">
                                                                                </td>


                                                                                <th class="table-primary text-end">b. Parrilla Costal</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Parrilla_Costal">
                                                                                </td>

                                                                                <th rowspan="4" class="vertical-text table-primary">13. Neurológico</th>


                                                                                <th class="table-primary text-end">a. Fuerza</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Fuerza">
                                                                                </td>
                                                                            </tr>

                                                                            <tr>
                                                                                <th class="table-primary text-end">b. Conjuntivas</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Conjuntivas">
                                                                                </td>

                                                                                <th class="table-primary text-end">e. Dentadura</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Dentadura">
                                                                                </td>

                                                                                <th rowspan="2" class="vertical-text table-primary">9. Abdomen</th>


                                                                                <th class="table-primary text-end">a. Vísceras</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Visceras">
                                                                                </td>

                                                                                <th class="table-primary text-end">b. Sencibilidad</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Sencibilidad">
                                                                                </td>

                                                                            </tr>

                                                                            <tr>
                                                                                <th class="table-primary text-end">c. Pupilas</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Pupilas">
                                                                                </td>

                                                                                <th rowspan="4" class="vertical-text table-primary">5. Naríz</th>

                                                                                <th class="table-primary text-end">a. Tabique</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Tabique">
                                                                                </td>

                                                                                <th class="table-primary text-end">b. Pared Abdominal</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Pared_Abdominal">
                                                                                </td>

                                                                                <th class="table-primary text-end">c. Marcha</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Marcha">
                                                                                </td>


                                                                            </tr>

                                                                            <tr>
                                                                                <th class="table-primary text-end">d. Córnea</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Cornea">
                                                                                </td>

                                                                                <th class="table-primary text-end">b. Cornetes</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Cornetes">
                                                                                </td>

                                                                                <th rowspan="3" class="vertical-text table-primary">10. Columna</th>


                                                                                <th class="table-primary text-end">a. Flexibilidad</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Flexibilidad">
                                                                                </td>

                                                                                <th class="table-primary text-end">d. Reflejos</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Reflejos">
                                                                                </td>
                                                                            </tr>

                                                                            <tr>
                                                                                <th class="table-primary text-end">e. Motilidad</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Motilidad">
                                                                                </td>

                                                                                <th class="table-primary text-end">c. Mucosas</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Mucosas">
                                                                                </td>

                                                                                <th class="table-primary text-end">b. Desviación</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Desviacion">
                                                                                </td>


                                                                            </tr>

                                                                            <tr>
                                                                                <th rowspan="3" class="vertical-text table-primary">3. Oído</th>

                                                                                <th class="table-primary text-end">a. C. auditivo externo</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_C_auditivo_externo">
                                                                                </td>


                                                                                <th class="table-primary text-end">d. Senos paranasales</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Senos_paranasales">
                                                                                </td>


                                                                                <th class="table-primary text-end">c. Dolor</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Dolor">
                                                                                </td>


                                                                            </tr>



                                                                            <tr>
                                                                                <th class="table-primary text-end">b. Pabellón</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Pabellon">
                                                                                </td>

                                                                                <th rowspan="2" class="vertical-text table-primary">6. Cuello</th>


                                                                                <th class="table-primary text-end">a. Tiroides/masas</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Tiroides">
                                                                                </td>

                                                                                <th rowspan="2" class="vertical-text table-primary">11. Pelvis</th>

                                                                                <th class="table-primary text-end">a. Pelvis</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Pelvis">
                                                                                </td>


                                                                            </tr>

                                                                            <tr>
                                                                                <th class="table-primary text-end">c. Timpanos</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Timpanos">
                                                                                </td>

                                                                                <th class="table-primary text-end">b. Movilidad</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Movilidad">
                                                                                </td>

                                                                                <th class="table-primary text-end">b. Genitales</th>
                                                                                <td>
                                                                                    <input type="checkbox" class="custom-control-input" id="chx_Genitales">
                                                                                </td>


                                                                            </tr>

                                                                            <tr class="small">
                                                                                <th colspan="9" class="small text-danger">*SI EXISTE EVIDENCIA DE PATOLOGÍA MARCAR Y DESCRIBIR EN LA SIGUIENTE SECCIÓN COLOCANDO EL NUMERAL</th>
                                                                            </tr>

                                                                        </tbody>
                                                                    </table>

                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div class="row pt-3">
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label fw-bold">Observaciones  <label style="color: red;">*</label> </label>
                                                                <textarea name="sa_examen_fisico_regional_obs" id="sa_examen_fisico_regional_obs" cols="30" rows="2" class="form-control" placeholder="OBSERVACIONES  EXAMEN FÍSICO REGIONAL"></textarea>
                                                            </div>
                                                        </div>



                                                        <div class="row pt-4">
                                                            <hr>
                                                        </div>

                                                        <div class="row pt-3">
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label fw-bold">Enfermedad Actual <label style="color: red;">*</label> </label>
                                                                <textarea name="sa_conp_enfermedad_actual" id="sa_conp_enfermedad_actual" cols="30" rows="2" class="form-control" placeholder="Enfermedad Actual"></textarea>
                                                            </div>
                                                        </div>

                                                        <div class="row pt-3">
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label fw-bold">CIE 10 - Diagnóstico 1 <label style="color: red;">*</label> </label>
                                                                <input type="text" class="ctw-input form-control form-control-sm" autocomplete="off" data-ctw-ino="1" id="sa_conp_diagnostico_1" placeholder="Diagnostico 1">
                                                                <input type="hidden" id="sa_conp_CIE_10_1">
                                                                <div class="ctw-window" data-ctw-ino="1"></div>
                                                            </div>
                                                        </div>

                                                        <div class="row pt-3">
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label fw-bold">CIE 10 - Diagnóstico 2 <label style="color: red;">*</label> </label>
                                                                <input type="text" class="ctw-input form-control form-control-sm" autocomplete="off" data-ctw-ino="2" id="sa_conp_diagnostico_2" placeholder="Diagnostico 2">
                                                                <input type="hidden" id="sa_conp_CIE_10_2">
                                                                <div class="ctw-window" data-ctw-ino="2"></div>
                                                            </div>
                                                        </div>

                                                        <div class="row pt-3">
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label fw-bold">Observaciones <label style="color: red;">*</label> </label>
                                                                <textarea name="sa_conp_observaciones" id="sa_conp_observaciones" cols="30" rows="1" class="form-control" placeholder="Observaciones"></textarea>
                                                            </div>
                                                        </div>

                                                        <div class="row pt-3">
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label fw-bold"> <b>¿Necesita permiso de salida? <label class="text-danger">*</label></b></label>

                                                                <div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="sa_conp_permiso_salida" id="sa_conp_permiso_salida_1" value="SI">
                                                                        <label class="form-check-label" for="sa_conp_permiso_salida_1">SI</label>
                                                                    </div>

                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="sa_conp_permiso_salida" id="sa_conp_permiso_salida_2" value="NO" checked>
                                                                        <label class="form-check-label" for="sa_conp_permiso_salida_2">NO</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div id="permiso_salida" style="display: none;">

                                                            <div class="row pt-2">
                                                                <div class="col-md-4">
                                                                    <label for="" class="form-label fw-bold">Fecha Permiso de Salida <label style="color: red;">*</label> </label>
                                                                    <input type="date" class="form-control form-control-sm" id="sa_conp_fecha_permiso_salud_salida" name="sa_conp_fecha_permiso_salud_salida" value="<?= date('Y-m-d'); ?>">
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <label for="" class="form-label fw-bold">Hora Permiso de Salida <label style="color: red;">*</label> </label>
                                                                    <input type="time" class="form-control form-control-sm" id="sa_conp_hora_permiso_salida" name="sa_conp_hora_permiso_salida">
                                                                </div>
                                                            </div>

                                                            <div class="row pt-3">
                                                                <div class="col-md-12">
                                                                    <label for="" class="form-label fw-bold"> <b>¿Tipo de Salida? <label class="text-danger">*</label></b></label>
                                                                    <input type="hidden" name="txt_id_comunidad" id="txt_id_comunidad">
                                                                    <input type="hidden" name="txt_tabla" id="txt_tabla">

                                                                    <div>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="sa_conp_permiso_tipo" id="sa_conp_permiso_tipo_1" value="emergencia">
                                                                            <label class="form-check-label" for="sa_conp_permiso_tipo_1">Emergencia</label>
                                                                        </div>

                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="sa_conp_permiso_tipo" id="sa_conp_permiso_tipo_2" value="normal" checked>
                                                                            <label class="form-check-label" for="sa_conp_permiso_tipo_2">Normal</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div id="permiso_salida_tipo" style="display: none;">

                                                            <div class="row pt-2">
                                                                <div class="col-md-12">
                                                                    <label for="" class="form-label fw-bold">Paciente Referido a <label style="color: red;">*</label> </label>
                                                                    <select class="form-select form-select-sm" id="sa_conp_permiso_seguro_traslado" name="sa_conp_permiso_seguro_traslado">
                                                                        <option selected disabled>-- Seleccione --</option>
                                                                        <option value="IESS">IESS</option>
                                                                        <option value="SPPAT">SPPAT</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="row pt-3">
                                                                <div class="col-md-4">
                                                                    <label for="" class="form-label fw-bold">Teléfono Seguro <label style="color: red;">*</label> </label>
                                                                    <input type="text" class="form-control form-control-sm" id="sa_conp_permiso_telefono_seguro" name="sa_conp_permiso_telefono_seguro">
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <label for="" class="form-label fw-bold" id="lbl_telefono_emergencia">Teléfono Representante <label style="color: red;">*</label> </label>
                                                                    <input type="text" class="form-control form-control-sm" aria-describedby="btn_telefono" id="sa_conp_permiso_telefono_padre" name="sa_conp_permiso_telefono_padre">

                                                                    <p id="txt_nombre_contacto" class="me-0 text-success"></p>

                                                                    <input type="hidden" name="sa_permiso_pac_id" id="sa_permiso_pac_id">
                                                                    <input type="hidden" name="sa_permiso_pac_tabla" id="sa_permiso_pac_tabla">

                                                                </div>


                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="modal-footer pt-4" id="seccion_boton_consulta">

                                                    <button hidden class="btn btn-danger btn-sm px-2 m-1" onclick="editar_insertar(1, 1, 1, 0, 2)" type="button"><i class='bx bx-pause-circle'></i> En Proceso</button>

                                                    <button class="btn btn-success btn-sm px-2 m-1" onclick="editar_insertar(1, 1, 1, 0, 1)" type="button"><i class="bx bx-save"></i> Finalizar</button>

                                                </div>

                                            </div>

                                        </div>
                                    <?php } ?>


                                    <?php if ($tipo_consulta == 'consulta') { ?>
                                        <div class="tab-pane fade show" id="recetario_tab" role="tabpanel" <?php if ($tipo_consulta != 'consulta') {
                                                                                                                echo 'hidden';
                                                                                                            } ?>>
                                            <div>

                                                <!-- Consulta -->
                                                <div>
                                                    <div class="row pt-0">
                                                        <!-- Farmacologia -->
                                                        <div>
                                                            <div class="row pt-4">
                                                                <hr>

                                                                <div class="col-md-3">
                                                                    <label for="tipo_farmacologia" class="form-label fw-bold">Farmacología <label style="color: red;">*</label> </label>
                                                                    <select class="form-select form-select-sm" id="tipo_farmacologia" name="tipo_farmacologia" onchange="consultar_medicinas_insumos(this.value);">
                                                                        <option selected disabled>-- Seleccione --</option>
                                                                        <option value="medicamentos">Medicamentos</option>
                                                                        <option value="insumos">Insumos</option>
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-5">
                                                                    <label for="tipo_farmacologia_presentacion" class="form-label fw-bold">Presentación <label style="color: red;">*</label> </label>
                                                                    <select class="form-select form-select-sm" id="tipo_farmacologia_presentacion" name="tipo_farmacologia_presentacion">
                                                                        <option selected disabled>-- Seleccione --</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <label for="Stock_farmacologia" class="form-label fw-bold">Stock </label>
                                                                    <input type="text" name="stock_farmacologia" id="stock_farmacologia" readonly class="form-control form-control-sm solo_numeros">
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <label for="cantidad_farmacologia" class="form-label fw-bold">Cant <label style="color: red;">*</label> </label>
                                                                    <input type="number" name="cantidad_farmacologia" id="cantidad_farmacologia" class="form-control form-control-sm solo_numeros" min="1">
                                                                </div>

                                                                <div class="col-md-2 mt-4 ">
                                                                    <label for="agregarFila_medicamentos" class="form-label fw-bold"></label>
                                                                    <button class="btn btn-primary" title="Agregar Medicamentos" id="agregarFila_medicamentos" type="button"><i class='bx bx-plus me-0'></i> Agregar</button>
                                                                </div>
                                                            </div>

                                                            <div class="row pt-3">
                                                                <div class="col-sm-12">
                                                                    <div class="mb-2">

                                                                        <table class="table table-bordered table-hover" id="lista_medicamentos">

                                                                            <tr>
                                                                                <th width="2%"><input id="checkAll_Medicamentos" class="form-check" type="checkbox"></th>

                                                                                <th width="40%">Farmacología</th>
                                                                                <th width="48%">Dosificación</th>
                                                                                <th width="8%">Cantidad</th>
                                                                                <th width="2%%">Entregado?</th>

                                                                            </tr>

                                                                        </table>

                                                                        <button class="btn btn-danger btn-sm mb-2" title="Seleccione el Medicamento para Eliminar" id="eliminarFila_medicamentos" type="button"><i class='bx bx-minus me-0'></i>Eliminar</button>

                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row pt-2">
                                                                <div class="col-md-12">
                                                                    <label for="" class="form-label fw-bold">Tratamiento <label style="color: red;">*</label> </label>
                                                                    <textarea name="sa_conp_tratamiento" id="sa_conp_tratamiento" cols="30" rows="2" class="form-control" placeholder="Tratamiento"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal-footer pt-4" id="seccion_boton_consulta">

                                                    <button hidden class="btn btn-danger btn-sm px-2 m-1" onclick="editar_insertar(1, 1, 1, 0, 2)" type="button"><i class='bx bx-pause-circle'></i> En Proceso</button>

                                                    <button class="btn btn-success btn-sm px-2 m-1" onclick="editar_insertar(1, 1, 1, 0, 1)" type="button"><i class="bx bx-save"></i> Finalizar</button>

                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>



                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../js/ENFERMERIA/consulta_medica.js"></script>

<script>
    $('input[name=sa_conp_permiso_salida]').change(function() {
        if ($(this).val() === 'SI') {
            $('#permiso_salida').show();

            hora_hasta = obtener_hora_hasta();
            $('#sa_conp_hora_permiso_salida').val(hora_hasta);

        } else if ($(this).val() === 'NO') {
            $('#permiso_salida').hide();
            $('#permiso_salida_tipo').hide();
        }
    });

    $('input[name=sa_conp_permiso_tipo]').change(function() {
        if ($(this).val() === 'emergencia') {
            $('#permiso_salida_tipo').show();
            llenar_telefonos_salida();
        } else if ($(this).val() === 'normal') {
            $('#permiso_salida_tipo').hide();
            $('#sa_conp_permiso_telefono_padre').val('');
        }
    });

    $("input[name='rb_examen_fisico']").change(function() {
        if ($(this).val() === "SI") {
            $("#pnl_examen_fisico").show();
        } else if ($(this).val() === "NO") {
            $("#pnl_examen_fisico").hide();
        }
    });
</script>

<div class="modal" id="myModal_historial" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Historial de consultas - <b id="title_nombre" class="text-primary"> as</b></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-striped responsive text-center" id="tbl_consultas_pac" style="width:100%">

                                <thead class="">
                                    <tr>
                                        <th>#</th>
                                        <th>Fecha</th>
                                        <th>Hora</th>
                                        <th>Tipo de Atención</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>