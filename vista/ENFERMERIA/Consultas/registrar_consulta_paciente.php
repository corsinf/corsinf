<?php

$id_ficha = '';
$id_paciente = '';
$tipo_consulta = '';

$id_consulta = '';

if (isset($_POST['id_ficha'])) {
    $id_ficha = $_POST['id_ficha'];
}

if (isset($_POST['id_paciente'])) {
    $id_paciente = $_POST['id_paciente'];
}

if (isset($_POST['tipo_consulta'])) {
    $tipo_consulta = $_POST['tipo_consulta'];
}

if (isset($_GET['id_consulta'])) {
    $id_consulta = $_GET['id_consulta'];
}

?>

<script src="<?= $url_general ?>/js/ENFERMERIA/operaciones_generales.js"></script>

<!-- //link de api icd -->
<link rel="stylesheet" href="https://icdcdn.who.int/embeddedct/icd11ect-1.6.1.css">
<script src="https://icdcdn.who.int/embeddedct/icd11ect-1.6.1.js"></script>

<script type="text/javascript">
    const mySettings = {
  // The API located at the URL below should be used only     
  // for software development and testing. 
  // ICD content at this location might not
  // be up to date or complete. 
  // For production, use the API located at
  // id.who.int with proper OAUTH authentication
  apiServerUrl: "https://icd11restapi-developer-test.azurewebsites.net",
  language: "es", // set the language to Spanish
  enableKeyboard:false,
};

const myCallbacks = {

  selectedEntityFunction: (selectedEntity) => { 
    // paste the code into the <input>
    console.log(selectedEntity);
    // document.getElementById('paste-selectedEntity').value = selectedEntity.code;        
    // clear the searchbox and delete the search results
    // ECT.Handler.clear("1")    
  }
};



// configure the ECT Handler
ECT.Handler.configure(mySettings,myCallbacks);

ECT.Handler.overwriteConfiguration("1", {
  chaptersFilter: "01;02",
  wordsAvailable: false,
  enableKeyboard:false,
});

</script>

<script type="text/javascript">
    $(document).ready(function() {

        var id_ficha = '<?php echo $id_ficha; ?>';
        var id_consulta = '<?php echo $id_consulta; ?>';
        var id_paciente = '<?php echo $id_paciente; ?>';
        var tipo_consulta = '<?php echo $tipo_consulta; ?>';

        //alert(id_ficha)

        cargar_datos_paciente(id_paciente);
        datos_col_ficha_medica(id_paciente);


        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //Para la consulta - llenado de datos
        consulta_hora_desde();

        $('#sa_conp_desde_hora, #sa_conp_hasta_hora').change(function() {
            calcular_diferencia_hora();
        });

        $('#sa_conp_fecha_inicio_falta_certificado, #sa_conp_fecha_fin_alta_certificado').change(function() {
            calcular_diferencia_fecha();
        });
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    });

    var count_medicamento = 0;

    function insertarMedicamentos() {
        //alert(count_medicamento);

        var etiquetas = "<option value='1'>" + 'medicamento 1' + "</option>";
        etiquetas += "<option value='2'>" + 'medicamento 2' + "</option>";

        // Obtener el último select agregado y agregar opciones
        $('#lista_medicamentos').find('select:last').append(etiquetas);
    }

    $(document).ready(function() {

        $(document).on('click', '#agregarFila_medicamentos', function() {
            count_medicamento++;

            var htmlFila = '<tr>';
            htmlFila += '<td><input class="itemFila_Medicamento" type="checkbox"></td>';
            htmlFila += '<td><select class="form-select form-select-sm" name="medicamentos[]" id="medicamentos_' + count_medicamento + '"  autocomplete="off" required>';
            htmlFila += '<option disabled selected value="">-- Seleccione --</option>';
            htmlFila += '</select></td>';
            htmlFila += '</tr>';

            $('#lista_medicamentos').append(htmlFila);

            // Llamar a la función para insertar opciones
            insertarMedicamentos();
        });


        $(document).on('click', '#checkAll_Medicamentos', function() {
            $(".itemFila_Medicamento").prop("checked", this.checked);
        });

        $(document).on('click', '.itemFila_Medicamento', function() {
            if ($('.itemFila_Medicamento:checked').length == $('.itemFila_Medicamento').length) {
                $('#checkAll_Medicamentos').prop('checked', true);
            } else {
                $('#checkAll_Medicamentos').prop('checked', false);
            }
        });

        $(document).on('click', '#eliminarFila_medicamentos', function() {
            $(".itemFila_Medicamento:checked").each(function() {

                for (let i = 1; i <= count_medicamento; i++) {
                    let medicamento1 = document.getElementById("medicamentos_" + i);
                    medicamento1.id = "medicamentos_0";
                }
                $(this).closest('tr').remove();
                count_medicamento--;
                for (let i = 1; i <= count_medicamento; i++) {
                    let medicamento2 = document.getElementById("medicamentos_0");
                    medicamento2.id = "medicamentos_" + i;
                }
            });
            $('#checkAll_Medicamentos').prop('checked', false);
        });
    });

    //Datos del paciente
    function cargar_datos_paciente(sa_pac_id) {
        $.ajax({
            data: {
                sa_pac_id: sa_pac_id

            },
            url: '<?= $url_general ?>/controlador/pacientesC.php?obtener_info_paciente=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                //console.log(response);
                ///  Para la tabla de inicio /////////////////////////////////////////////////////////////////////////////////////////////////////////
                $('#txt_ci').html(response[0].sa_pac_temp_cedula + " <i class='bx bxs-id-card'></i>");
                nombres = response[0].sa_pac_temp_primer_nombre + ' ' + response[0].sa_pac_temp_segundo_nombre;
                apellidos = response[0].sa_pac_temp_primer_apellido + ' ' + response[0].sa_pac_temp_segundo_apellido;

                $('#txt_nombres').html(apellidos + " " + nombres);

                $('#title_paciente').html(apellidos + " " + nombres);

                $('#tipo_paciente').html(response[0].sa_pac_tabla);


                sexo_paciente = '';
                if (response[0].sa_pac_temp_sexo === 'Masculino') {
                    sexo_paciente = "Masculino <i class='bx bx-male'></i>";
                } else if (response[0].sa_pac_temp_sexo === 'Femenino') {
                    sexo_paciente = "Famenino <i class='bx bx-female'></i>";
                }
                $('#txt_sexo').html(sexo_paciente);
                $('#txt_fecha_nacimiento').html(fecha_nacimiento_formateada(response[0].sa_pac_temp_fecha_nacimiento.date) + ' (' + calcular_edad_fecha_nacimiento(response[0].sa_pac_temp_fecha_nacimiento.date) + ' años)');


                if (response[0].sa_pac_tabla == 'estudiantes') {
                    curso = response[0].sa_pac_temp_sec_nombre + '/' + response[0].sa_pac_temp_gra_nombre + '/' + response[0].sa_pac_temp_par_nombre;
                    $('#txt_curso').html(curso);

                    $('#sa_conp_nivel').val(response[0].sa_pac_temp_gra_nombre);
                    $('#sa_conp_paralelo').val(response[0].sa_pac_temp_par_nombre);

                } else {
                    $('#variable_paciente').html('Teléfono:');
                    $('#txt_curso').html(response[0].sa_pac_temp_telefono_1);

                }

                /////////////Para el formulario
                $('#sa_conp_edad').val(calcular_edad_fecha_nacimiento(response[0].sa_pac_temp_fecha_nacimiento.date));

                //alert(calcular_edad_fecha_nacimiento(response[0].sa_pac_temp_fecha_nacimiento.date))
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
            url: '<?php echo $url_general ?>/controlador/ficha_MedicaC.php?listar_paciente_ficha=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                //console.log(response);
                // Preguntas
                $('#txt_sa_fice_pac_grupo_sangre').html(response[0].sa_fice_pac_grupo_sangre);
                $('#txt_sa_fice_pregunta_1_obs').html(response[0].sa_fice_pregunta_1_obs);
                $('#txt_sa_fice_pregunta_2_obs').html(response[0].sa_fice_pregunta_2_obs);
                $('#txt_sa_fice_pregunta_3_obs').html(response[0].sa_fice_pregunta_3_obs);
                $('#txt_sa_fice_pregunta_4_obs').html(response[0].sa_fice_pregunta_4_obs);
                $('#txt_sa_fice_pregunta_5_obs').html(response[0].sa_fice_pregunta_5_obs);

            }
        });
    }



    //Datos de la consulta aun no se utiliza
    function datos_col_consulta_paciente(id_consulta) {
        $.ajax({
            data: {
                id: id_consulta
            },
            url: '<?php echo $url_general ?>/controlador/consultasC.php?listar_solo_consulta=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                // Asignar valores del estudiante
                // $('#sa_conp_id').val(response[0].sa_conp_id);
                $('#sa_fice_id').val(response[0].sa_fice_id);

                /*$('#sa_conp_nombres').val(response[0].sa_conp_nombres);
                $('#sa_conp_nivel').val(response[0].sa_conp_nivel);
                $('#sa_conp_paralelo').val(response[0].sa_conp_paralelo);
                $('#sa_conp_edad').val(response[0].sa_conp_edad);
                $('#sa_conp_correo').val(response[0].sa_conp_correo);
                $('#sa_conp_telefono').val(response[0].sa_conp_telefono);*/

                // Asignar valores de fechas y horas
                $('#sa_conp_fecha_ingreso').val(obtener_hora_formateada(response[0].sa_conp_fecha_ingreso.date));

                // alert(obtener_hora_formateada(response[0].sa_conp_desde_hora.date));

                $('#sa_conp_desde_hora').val(obtener_hora_formateada(response[0].sa_conp_desde_hora.date));
                $('#sa_conp_hasta_hora').val(obtener_hora_formateada(response[0].sa_conp_hasta_hora.date));
                $('#sa_conp_tiempo_aten').val(response[0].sa_conp_tiempo_aten);

                // Asignar valores de diagnósticos y medicamentos
                $('#sa_conp_CIE_10_1').val(response[0].sa_conp_CIE_10_1);
                $('#sa_conp_diagnostico_1').val(response[0].sa_conp_diagnostico_1);
                $('#sa_conp_CIE_10_2').val(response[0].sa_conp_CIE_10_2);
                $('#sa_conp_diagnostico_2').val(response[0].sa_conp_diagnostico_2);

                //$('#sa_conp_medicina_1').val(response[0].sa_conp_medicina_1);
                datos_col_medicamentos_1('', response[0].sa_conp_medicina_1);
                $('#sa_conp_dosis_1').val(response[0].sa_conp_dosis_1);

                //$('#sa_conp_medicina_2').val(response[0].sa_conp_medicina_2);
                datos_col_medicamentos_2('', response[0].sa_conp_medicina_2);
                $('#sa_conp_dosis_2').val(response[0].sa_conp_dosis_2);

                //$('#sa_conp_medicina_3').val(response[0].sa_conp_medicina_3);
                datos_col_medicamentos_3('', response[0].sa_conp_medicina_3);
                $('#sa_conp_dosis_3').val(response[0].sa_conp_dosis_3);

                // Asignar valores de certificados y permisos
                $('#sa_conp_salud_certificado').val(response[0].sa_conp_salud_certificado);
                $('#sa_conp_motivo_certificado').val(response[0].sa_conp_motivo_certificado);
                $('#sa_conp_CIE_10_certificado').val(response[0].sa_conp_CIE_10_certificado);
                $('#sa_conp_diagnostico_certificado').val(response[0].sa_conp_diagnostico_certificado);
                $('#sa_conp_fecha_entrega_certificado').val(obtener_hora_formateada(response[0].sa_conp_fecha_entrega_certificado.date));
                $('#sa_conp_fecha_inicio_falta_certificado').val(obtener_hora_formateada(response[0].sa_conp_fecha_inicio_falta_certificado.date));
                $('#sa_conp_fecha_fin_alta_certificado').val(obtener_hora_formateada(response[0].sa_conp_fecha_fin_alta_certificado.date));
                $('#sa_conp_dias_permiso_certificado').val(response[0].sa_conp_dias_permiso_certificado);

                $('#sa_conp_permiso_salida').val(response[0].sa_conp_permiso_salida);
                $('#sa_conp_fecha_permiso_salud_salida').val(obtener_hora_formateada(response[0].sa_conp_fecha_permiso_salud_salida.date));
                $('#sa_conp_hora_permiso_salida').val(obtener_hora_formateada(response[0].sa_conp_hora_permiso_salida.date));

                // Asignar valores de notificaciones y observaciones
                $('#sa_conp_notificacion_envio_representante').val(response[0].sa_conp_notificacion_envio_representante);
                $('#sa_conp_notificacion_envio_inspector').val(response[0].sa_conp_notificacion_envio_inspector);
                $('#sa_conp_notificacion_envio_guardia').val(response[0].sa_conp_notificacion_envio_guardia);

                $('#sa_conp_observaciones').val(response[0].sa_conp_observaciones);
                $('#sa_conp_tipo_consulta').val(response[0].sa_conp_tipo_consulta);

                if (response[0].sa_conp_tipo_consulta === 'Consulta') {
                    opcion_Consulta();
                } else if (response[0].sa_conp_tipo_consulta === 'Certificado') {
                    opcion_Certificado();
                } else if (response[0].sa_conp_tipo_consulta === 'Salida') {
                    opcion_Salida();
                }

                // Asignar valores de estado y fechas de creación/modificación
                $('#sa_conp_estado').val(response[0].sa_conp_estado);
                //$('#sa_conp_fecha_creacion').val(response[0].sa_conp_fecha_creacion);
                //$('#sa_conp_fecha_modificar').val(response[0].sa_conp_fecha_modificar);

                console.log(response);
            }
        });
    }

    //falta estado para profesor
    function editar_insertar(n_representante = '', n_docente = '', n_inspector = '', n_guardia = '') {

        var sa_conp_id = $('#sa_conp_id').val();

        var sa_fice_id = $('#sa_fice_id').val();
        var sa_conp_nivel = $('#sa_conp_nivel').val();
        var sa_conp_paralelo = $('#sa_conp_paralelo').val();
        var sa_conp_edad = $('#sa_conp_edad').val();
        var sa_conp_peso = $('#sa_conp_peso').val();
        var sa_conp_altura = $('#sa_conp_altura').val();

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
        var sa_conp_CIE_10_1 = $('#sa_conp_CIE_10_1').val();
        var sa_conp_diagnostico_1 = $('#sa_conp_diagnostico_1').val();
        var sa_conp_CIE_10_2 = $('#sa_conp_CIE_10_2').val();
        var sa_conp_diagnostico_2 = $('#sa_conp_diagnostico_2').val();

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
        var sa_conp_permiso_salida = $('input[name=sa_conp_permiso_salida]:checked').val();
        var sa_conp_fecha_permiso_salud_salida = ($('#sa_conp_fecha_permiso_salud_salida').val());
        var sa_conp_hora_permiso_salida = ($('#sa_conp_hora_permiso_salida').val());

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
        var sa_conp_tipo_consulta = '<?= $tipo_consulta; ?>';

        // Crear objeto de parámetros
        var parametros = {
            'sa_conp_id': sa_conp_id,
            'sa_fice_id': sa_fice_id,
            'sa_conp_nivel': sa_conp_nivel,
            'sa_conp_paralelo': sa_conp_paralelo,
            'sa_conp_edad': sa_conp_edad,
            'sa_conp_peso': sa_conp_peso,
            'sa_conp_altura': sa_conp_altura,
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
            'sa_conp_notificacion_envio_representante': sa_conp_notificacion_envio_representante,
            'sa_id_representante': sa_id_representante,
            'sa_conp_notificacion_envio_docente': sa_conp_notificacion_envio_docente,
            'sa_id_docente': sa_id_docente,
            'sa_conp_notificacion_envio_inspector': sa_conp_notificacion_envio_inspector,
            'sa_id_inspector': sa_id_inspector,
            'sa_conp_notificacion_envio_guardia': sa_conp_notificacion_envio_guardia,
            'sa_id_guardia': sa_id_guardia,
            'sa_conp_observaciones': sa_conp_observaciones,
            'sa_conp_tipo_consulta': sa_conp_tipo_consulta,
        };

        //alert(sa_conp_tipo_consulta)


        if (sa_conp_tipo_consulta == 'consulta') {
            if (sa_conp_id == '') {
                if (
                    sa_conp_peso == '' ||
                    sa_conp_altura == ''

                ) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Asegurese de llenar todo los campos',
                    })
                } else {
                    insertar(parametros);
                    //console.log(parametros);
                    //alert('entra2');
                }
            }
        } else if (sa_conp_tipo_consulta == 'certificado') {
            insertar(parametros)
        }


    }

    function insertar(parametros) {

        //console.log(parametros);
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '<?= $url_general ?>/controlador/consultasC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                console.log(response);

                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        //location.href = '<?= $url_general ?>/vista/inicio.php?mod=7&acc=atencion_estudiante';
                        location.href = '<?= $url_general ?>/vista/inicio.php?mod=7&acc=pacientes';
                    });
                } else if (response == -2) {
                    Swal.fire('', 'Código ya registrado', 'success');
                }

                //console.log(response);
            }
        });
    }

    function delete_datos() {
        var id_consulta = '<?php echo $id_consulta; ?>';
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
                eliminar(id_consulta);
            }
        })
    }

    function eliminar(id) {
        var id_ficha = '<?php echo $id_ficha; ?>';

        $.ajax({
            data: {
                id: id
            },
            url: '<?= $url_general ?>/controlador/consultasC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'      
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
                        location.href = '<?= $url_general ?>/vista/inicio.php?mod=7&acc=consulta_estudiante&id_ficha=';
                    });
                }
            }
        });
    }

    //Funciones para la consulta
    function consulta_hora_desde() {
        // Obtener la hora actual
        var ahora = new Date();

        // Formatear la hora y minutos con ceros a la izquierda si es necesario
        var horas = ahora.getHours().toString().padStart(2, '0');
        var minutos = ahora.getMinutes().toString().padStart(2, '0');

        $('#sa_conp_desde_hora').val(horas + ':' + minutos);
        // $('#sa_conp_hora_permiso_salida').val(horas + ':' + minutos);

    }

    function calcular_diferencia_fecha() {
        var fechaDesde = new Date($('#sa_conp_fecha_inicio_falta_certificado').val());
        var fechaHasta = new Date($('#sa_conp_fecha_fin_alta_certificado').val());

        // Calcular la diferencia en milisegundos
        var diferenciaEnMs = fechaHasta - fechaDesde;

        // Validar que la fecha de inicio sea menor o igual a la fecha de fin
        if (diferenciaEnMs >= 0) {
            // Calcular la diferencia en días
            var diferenciaEnDias = Math.floor(diferenciaEnMs / (1000 * 60 * 60 * 24));

            // Mostrar la diferencia en el campo de texto
            $('#sa_conp_dias_permiso_certificado').val(diferenciaEnDias + 1);
        } else {
            $('#sa_conp_dias_permiso_certificado').val('NaN');
        }
    }

    function calcular_diferencia_hora() {
        var horaDesde = $('#sa_conp_desde_hora').val();
        var horaHasta = $('#sa_conp_hasta_hora').val();

        var fechaBase = new Date('2000-01-01');
        var fechaDesde = new Date(fechaBase.toDateString() + ' ' + horaDesde);
        var fechaHasta = new Date(fechaBase.toDateString() + ' ' + horaHasta);

        var diferenciaEnMs = fechaHasta - fechaDesde;

        if (diferenciaEnMs >= 0) {
            var diferenciaEnMinutos = Math.floor(diferenciaEnMs / (1000 * 60));
            $('#sa_conp_tiempo_aten').val(diferenciaEnMinutos);
        } else {
            Swal.fire('', 'La hora Hasta de la consulta no puede ser menor', 'info');
            $('#sa_conp_hasta_hora').val('');
            $('#sa_conp_tiempo_aten').val('NaN');
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
                            <?php
                            if ($id_consulta == '') {
                                echo 'Registrar Consulta del Paciente';
                            } else {
                                echo 'Modificar Consulta del Paciente';
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
                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                            </div>
                            <h5 class="mb-0 text-primary">
                                <?php if ($id_consulta == '') { ?>
                                    Registrar Consulta del Paciente: <b class="text-success" id="title_paciente"></b>
                                <?php } else { ?>
                                    Modificar Consulta del Paciente: <b class="text-success" id="title_paciente"></b>
                                <?php } ?>
                            </h5>

                            <div class="row m-2">

                                <div class="col-sm-12">
                                    <a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=consultas_pacientes&pac_id=<?= $id_paciente ?>" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>

                            </div>
                        </div>

                        <hr>

                        <form action="">

                            <input type="hidden" id="sa_conp_id" name="sa_conp_id">
                            <input type="hidden" id="sa_fice_id" name="sa_fice_id" value="<?= $id_ficha; ?>">



                            <input type="hidden" id="sa_conp_notificacion_envio_representante" name="sa_conp_notificacion_envio_representante">
                            <input type="hidden" id="sa_id_representante" name="sa_id_representante">

                            <input type="hidden" id="sa_conp_notificacion_envio_docente" name="sa_conp_notificacion_envio_docente">
                            <input type="hidden" id="sa_id_docente" name="sa_id_docente">

                            <input type="hidden" id="sa_conp_notificacion_envio_inspector" name="sa_conp_notificacion_envio_inspector">
                            <input type="hidden" id="sa_id_inspector" name="sa_id_inspector">

                            <input type="hidden" id="sa_conp_notificacion_envio_guardia" name="sa_conp_notificacion_envio_guardia">
                            <input type="hidden" id="sa_id_guardia" name="sa_id_guardia">


                            <div id="main_consulta" style="display: block;">
                                <ul class="nav nav-tabs nav-success" role="tablist">

                                    <li class="nav-item" role="presentation" id="seccion_navtab_consulta">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#consulta_tab" role="tab" aria-selected="true">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon"><i class='bx bx-receipt font-20 me-1'></i>
                                                </div>

                                                <?php if ($tipo_consulta == 'consulta') { ?>
                                                    <div class="tab-title">CONSULTA</div>
                                                <?php } else if ($tipo_consulta == 'certificado') { ?>
                                                    <div class="tab-title">CERTIFICADO</div>
                                                <?php } ?>

                                            </div>
                                        </a>
                                    </li>


                                </ul>
                                <div class="tab-content py-3">
                                    <div class="tab-pane fade show active" id="consulta_tab" role="tabpanel">

                                        <div class="accordion" id="consulta_acordeon">

                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="flush-headingOne">
                                                    <button class="accordion-button collapsed bg-success" type="button" data-bs-toggle="collapse" data-bs-target="#flush-estudiante" aria-expanded="false" aria-controls="flush-estudiante">
                                                        <h6 class="text-white"><b>Ficha Médica</b></h6>
                                                    </button>
                                                </h2>
                                                <div id="flush-estudiante" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#consulta_acordeon">
                                                    <div class="accordion-body">

                                                        <input type="hidden" id="sa_conp_nivel" name="sa_conp_nivel">
                                                        <input type="hidden" id="sa_conp_paralelo" name="sa_conp_paralelo">
                                                        <input type="hidden" id="sa_conp_edad" name="sa_conp_edad">

                                                        <div class="row pt-3">
                                                            <div class="col-12">
                                                                <div class="table-responsive">
                                                                    <table class="table mb-0" style="width:100%">
                                                                        <tbody>
                                                                            <tr>
                                                                                <th style="width:0%" class="table-primary text-end">Cédula:</th>
                                                                                <td id="txt_ci"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th style="width:0%" class="table-primary text-end">Nombres:</th>
                                                                                <td id="txt_nombres"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th style="width:0%" class="table-primary text-end">Sexo:</th>
                                                                                <td id="txt_sexo"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th style="width:0%" class="table-primary text-end">Fecha de Nacimiento:</th>
                                                                                <td id="txt_fecha_nacimiento"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th style="width:0%" class="table-primary text-end" id="variable_paciente">Curso:</th>
                                                                                <td id="txt_curso"></td>
                                                                            </tr>



                                                                            <tr>
                                                                                <th style="width:0%" class="table-primary text-end" id="variable_paciente">Grupo Sanguíneo y Factor Rh:</th>
                                                                                <td id="txt_sa_fice_pac_grupo_sangre"></td>
                                                                            </tr>

                                                                            <tr>
                                                                                <th style="width:0%" class="table-primary text-end" id="variable_paciente">1.- ¿Ha sido diagnosticado con alguna enfermedad?:</th>
                                                                                <td id="txt_sa_fice_pregunta_1_obs"></td>
                                                                            </tr>

                                                                            <tr>
                                                                                <th style="width:0%" class="table-primary text-end" id="variable_paciente">2.- ¿Tiene algún antecedente familiar de importancia?:</th>
                                                                                <td id="txt_sa_fice_pregunta_2_obs"></td>
                                                                            </tr>

                                                                            <tr>
                                                                                <th style="width:0%" class="table-primary text-end" id="variable_paciente">3.- ¿Ha sido sometido a cirugías previas?:</th>
                                                                                <td id="txt_sa_fice_pregunta_3_obs"></td>
                                                                            </tr>

                                                                            <tr>
                                                                                <th style="width:0%" class="table-primary text-end" id="variable_paciente">4.- ¿Tiene alergias?:</th>
                                                                                <td id="txt_sa_fice_pregunta_4_obs"></td>
                                                                            </tr>

                                                                            <tr>
                                                                                <th style="width:0%" class="table-primary text-end" id="variable_paciente">5.- ¿Qué medicamentos usa?:</th>
                                                                                <td id="txt_sa_fice_pregunta_5_obs"></td>
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
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label for="" class="form-label">Fecha: <label style="color: red;">*</label> </label>
                                                    <input type="date" class="form-control form-control-sm" id="sa_conp_fecha_ingreso" name="sa_conp_fecha_ingreso" value="<?= date('Y-m-d'); ?>">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="" class="form-label">Desde: <label style="color: red;">*</label> </label>
                                                    <input type="time" class="form-control form-control-sm" id="sa_conp_desde_hora" name="sa_conp_desde_hora">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="" class="form-label">Hasta: <label style="color: red;">*</label> </label>
                                                    <input type="time" class="form-control form-control-sm" id="sa_conp_hasta_hora" name="sa_conp_hasta_hora">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="" class="form-label">Tiempo (Minutos): <label style="color: red;">*</label> </label>
                                                    <input type="text" class="form-control form-control-sm" id="sa_conp_tiempo_aten" name="sa_conp_tiempo_aten" readonly>
                                                </div>
                                            </div>
                                            <br>
                                            <hr>

                                            <!-- Consulta -->
                                            <div <?php if ($tipo_consulta != 'consulta') {
                                                        echo 'hidden';
                                                    } ?>>

                                                <div class="row pt-0">
                                                    <div class="row pt-1">
                                                        <div class="col-md-3">
                                                            <label for="" class="form-label">Peso: <label style="color: red;">*</label> </label>
                                                            <input type="number" class="form-control form-control-sm" id="sa_conp_peso" name="sa_conp_peso">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="" class="form-label">Altura: <label style="color: red;">*</label> </label>
                                                            <input type="number" class="form-control form-control-sm" id="sa_conp_altura" name="sa_conp_altura">
                                                        </div>
                                                    </div>

                                                    <div class="row pt-3">
                                                        <div class="col-md-12">
                                                            <label for="" class="form-label">CIE 10 - Diagnóstico 1: <label style="color: red;">*</label> </label>

                                                            <input type="text" class="ctw-input" autocomplete="off" data-ctw-ino="1">
                                                            <div class="ctw-window" data-ctw-ino="1"></div>
                                                            <!-- example of an extra input element for testing the Embedded Coding Tool select entity function -->
                                                            <!-- The selected code: <input type="text" id="paste-selectedEntity" value="">  -->




<!-- <div class="ctw-eb-window" data-ctw-ino="1"></div> -->

                                                            <select class="form-select form-select-sm" id="sa_conp_CIE_10_1" name="sa_conp_CIE_10_1">
                                                                <option selected disabled>-- Seleccione --</option>
                                                                <option value="A001">A001</option>
                                                                <option value="B001">B001</option>
                                                            </select>

                                                            <input type="hidden" id="sa_conp_diagnostico_1" name="sa_conp_diagnostico_1">
                                                        </div>
                                                    </div>

                                                    <div class="row pt-3">
                                                        <div class="col-md-12">
                                                            <label for="" class="form-label">CIE 10 - Diagnóstico 2: <label style="color: red;">*</label> </label>
                                                            <select class="form-select form-select-sm" id="sa_conp_CIE_10_2" name="sa_conp_CIE_10_2">
                                                                <option selected disabled>-- Seleccione --</option>
                                                                <option value="A001">A001</option>
                                                                <option value="B001">B001</option>
                                                            </select>
                                                            <input type="hidden" id="sa_conp_diagnostico_2" name="sa_conp_diagnostico_2">
                                                        </div>
                                                    </div>

                                                    <div class="row pt-3">
                                                        <div class="col-md-12">
                                                            <label for="" class="form-label">Observaciones: <label style="color: red;">*</label> </label>
                                                            <textarea name="sa_conp_observaciones" id="sa_conp_observaciones" cols="30" rows="1" class="form-control" placeholder="Observaciones"></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="row pt-3">
                                                        <div class="col-sm-4">
                                                            <div class="mb-2">
                                                                <label>
                                                                    Medicamentos:

                                                                    <button class="btn btn-success btn-sm mb-2" title="Agregar Medicamentos" id="agregarFila_medicamentos" type="button"><i class='bx bx-plus me-0'></i></button>

                                                                    <button class="btn btn-danger btn-sm mb-2" title="Seleccione el Medicamento para Eliminar" id="eliminarFila_medicamentos" type="button"><i class='bx bx-minus me-0'></i></button>
                                                                </label>

                                                                <table class="table table-bordered table-hover" id="lista_medicamentos">

                                                                    <tr>
                                                                        <th width="2%"><input id="checkAll_Medicamentos" class="form-check" type="checkbox">
                                                                        </th>

                                                                        <th><label>Medicamentos: <span style="color: crimson;">*</span></label></th>

                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row pt-3">
                                                        <div class="col-md-12">
                                                            <label for="" class="form-label"> <b>¿Necesita permiso de salida?: <label class="text-danger">*</label></b></label>

                                                            <div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="sa_conp_permiso_salida" id="sa_conp_permiso_salida_1" value="SI">
                                                                    <label class="form-check-label" for="flexRadioDefault1">SI</label>
                                                                </div>

                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="sa_conp_permiso_salida" id="sa_conp_permiso_salida_2" value="NO" checked>
                                                                    <label class="form-check-label" for="flexRadioDefault2">NO</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="permiso_salida" style="display: none;">

                                                        <div class="row pt-2">
                                                            <div class="col-md-4">
                                                                <label for="" class="form-label">Fecha Permiso de Salida: <label style="color: red;">*</label> </label>
                                                                <input type="date" class="form-control form-control-sm" id="sa_conp_fecha_permiso_salud_salida" name="sa_conp_fecha_permiso_salud_salida">
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label for="" class="form-label">Hora Permiso de Salida: <label style="color: red;">*</label> </label>
                                                                <input type="time" class="form-control form-control-sm" id="sa_conp_hora_permiso_salida" name="sa_conp_hora_permiso_salida">
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>


                                            <!-- Certificado -->
                                            <div <?php if ($tipo_consulta != 'certificado') {
                                                        echo 'hidden';
                                                    } ?>>
                                                <div class="row pt-0">
                                                    <div class="row pt-1">
                                                        <div class="col-md-3">
                                                            <label for="" class="form-label">Certificado por Salud: <label style="color: red;">*</label> </label>
                                                            <input type="text" class="form-control form-control-sm" id="sa_conp_salud_certificado" name="sa_conp_salud_certificado">
                                                        </div>

                                                        <div class="col-md-9">
                                                            <label for="" class="form-label">Motivo Certificado: <label style="color: red;">*</label> </label>
                                                            <input type="text" class="form-control form-control-sm" id="sa_conp_motivo_certificado" name="sa_conp_motivo_certificado">
                                                        </div>
                                                    </div>

                                                    <div class="row pt-3">
                                                        <div class="col-md-12">
                                                            <label for="" class="form-label">CIE 10 - Diagnóstico de Certificado: <label style="color: red;">*</label> </label>
                                                            <select class="form-select form-select-sm" id="sa_conp_CIE_10_certificado" name="sa_conp_CIE_10_certificado">
                                                                <option selected disabled>-- Seleccione --</option>
                                                                <option value="A001">A001</option>
                                                                <option value="B001">B001</option>
                                                            </select>
                                                            <input type="hidden" id="sa_conp_diagnostico_certificado" name="sa_conp_diagnostico_certificado">

                                                        </div>
                                                    </div>

                                                    <div class="row pt-3">
                                                        <div class="col-md-3">
                                                            <label for="" class="form-label">Fecha de Entrega del Certificado: <label style="color: red;">*</label> </label>
                                                            <input type="date" class="form-control form-control-sm" id="sa_conp_fecha_entrega_certificado" name="sa_conp_fecha_entrega_certificado">
                                                        </div>

                                                        <div class="col-md-3">
                                                            <label for="" class="form-label">Fecha Inicio Falta: <label style="color: red;">*</label> </label>
                                                            <input type="date" class="form-control form-control-sm" id="sa_conp_fecha_inicio_falta_certificado" name="sa_conp_fecha_inicio_falta_certificado">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="" class="form-label">Fecha Fin Alta: <label style="color: red;">*</label> </label>
                                                            <input type="date" class="form-control form-control-sm" id="sa_conp_fecha_fin_alta_certificado" name="sa_conp_fecha_fin_alta_certificado">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="" class="form-label"># Días de Permiso: <label style="color: red;">*</label> </label>
                                                            <input type="text" class="form-control form-control-sm" id="sa_conp_dias_permiso_certificado" name="sa_conp_dias_permiso_certificado">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>




                                            <div class="modal-footer pt-4" id="seccion_boton_consulta">
                                                <?php if ($id_consulta == '') { ?>
                                                    <button class="btn btn-primary btn-sm px-4 m-1" onclick="editar_insertar(1, 1, 1, 0)" type="button"><i class="bx bx-save"></i> Guardar</button>
                                                <?php } else { ?>
                                                    <button class="btn btn-primary btn-sm px-4 m-1" onclick="editar_insertar(1, 1, 1, 0)" type="button"><i class="bx bx-save"></i> Guardar</button>
                                                    <button class="btn btn-danger btn-sm px-4 m-1" onclick="delete_datos()" type="button"><i class="bx bx-trash"></i> Eliminar</button>
                                                <?php } ?>
                                            </div>

                                        </div>

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

<script>
    $('input[name=sa_conp_permiso_salida]').change(function() {
        if ($(this).val() === 'SI') {
            $('#permiso_salida').show();
        } else if ($(this).val() === 'NO') {
            $('#permiso_salida').hide();
        }
    });
</script>