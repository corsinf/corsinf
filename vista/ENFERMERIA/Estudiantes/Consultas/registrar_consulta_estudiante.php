<?php //include('../../../../cabeceras/header.php');

$id_ficha = '';
$id_estudiante = '';
$id_representante = '';
$id_consulta = '';

if (isset($_GET['id_estudiante'])) {
    $id_estudiante = $_GET['id_estudiante'];
}

if (isset($_GET['id_representante'])) {
    $id_representante = $_GET['id_representante'];
}

if (isset($_GET['id_ficha'])) {
    $id_ficha = $_GET['id_ficha'];
}

if (isset($_GET['id_consulta'])) {
    $id_consulta = $_GET['id_consulta'];
}

?>

<script type="text/javascript">
    $(document).ready(function() {
        var id_estudiante = '<?php echo $id_estudiante; ?>';
        var id_representante = '<?php echo $id_representante; ?>';
        var id_ficha = '<?php echo $id_ficha; ?>';
        var id_consulta = '<?php echo $id_consulta; ?>';


        //alert(id_estudiante + ' + ' + id_representante);

        //Para cargar el id de la ficha del estudiante al momento de insertar
        $('#sa_fice_id').val(id_ficha);

        if (id_estudiante != '') {
            datos_col_estudiante(id_estudiante);
            datos_col_representante(id_representante);
        }
        //alert(id_consulta)
        if (id_consulta != '') {
            datos_col_consulta_estudiante(id_consulta)
        } else {
            datos_col_medicamentos_1();
            datos_col_medicamentos_2();
            datos_col_medicamentos_3();
        }

        //////////////////////////////////
        //Para la consulta llenado de datos
        cosnulta_hora_desde();

        $('#sa_conp_desde_hora, #sa_conp_hasta_hora').change(function() {
            calcular_diferencia_hora();
        });

        $('#sa_conp_fecha_inicio_falta_certificado, #sa_conp_fecha_fin_alta_certificado').change(function() {
            calcular_diferencia_fecha();
        });

        //Preguntas para tipo de atencion que se escoja una opcion, para habilitar funcion comentar las dos lineas de abajo y descomentar lo que esta con /* */ 
        //$('#main_consulta').show();
        //$('#radio_tipo_atencion').hide();

        $('input[name=tipo_atencion_1]').change(function() {
             if ($(this).val() === 'Consulta') {
                 $('#main_consulta').show();

                 $('#seccion_navtab_consulta').show();
                 $('#seccion_navtab_certificado').hide();
                 $('#seccion_navtab_salida').hide();

                 $('#seccion_boton_consulta').show();
                 $('#seccion_boton_certificado').hide();
                 $('#seccion_boton_salida').hide();

                 $('#radio_tipo_atencion').hide();
             } else if ($(this).val() === 'Certificado') {
                 $('#main_consulta').show();

                 $('#seccion_navtab_consulta').show();
                 $('#seccion_navtab_certificado').show();
                 $('#seccion_navtab_salida').hide();

                 $('#seccion_boton_consulta').hide();
                 $('#seccion_boton_certificado').show();
                 $('#seccion_boton_salida').hide();

                 $('#radio_tipo_atencion').hide();
             } else if ($(this).val() === 'Salida') {
                 $('#main_consulta').show();

                 $('#seccion_navtab_consulta').show();
                 $('#seccion_navtab_certificado').hide();
                 $('#seccion_navtab_salida').show();

                 $('#seccion_boton_consulta').hide();
                 $('#seccion_boton_certificado').hide();
                 $('#seccion_boton_salida').show();

                 $('#radio_tipo_atencion').hide();
             }
         });

    });

    //Funciones para la consulta
    function cosnulta_hora_desde() {
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
            $('#sa_conp_tiempo_aten').val('NaN');
        }
    }

    function edad_fecha_nacimiento(fecha_nacimiento) {
        fechaNacimientoJson = fecha_nacimiento;

        // Crear un objeto Date a partir del string de fecha
        fechaNacimiento = new Date(fechaNacimientoJson);

        // Obtener la fecha actual
        fechaActual = new Date();

        // Calcular la diferencia en milisegundos entre la fecha actual y la fecha de nacimiento
        diferenciaEnMilisegundos = fechaActual - fechaNacimiento;

        // Calcular la edad en años a partir de la diferencia en milisegundos
        edadEnMilisegundos = new Date(diferenciaEnMilisegundos);
        edadEnAnios = Math.abs(edadEnMilisegundos.getUTCFullYear() - 1970);

        var salida = '';
        // Mostrar la edad en años
        salida = edadEnAnios;

        return salida;
    }

    function fecha_formateada(fecha) {
        fechaYHora = fecha;
        fecha = new Date(fechaYHora);
        año = fecha.getFullYear();
        mes = (fecha.getMonth() + 1).toString().padStart(2, '0'); // Añade un 0 si es necesario
        dia = fecha.getDate().toString().padStart(2, '0'); // Añade un 0 si es necesario
        fechaFormateada = `${año}-${mes}-${dia}`;

        var salida = '';
        salida = fechaFormateada;

        return salida;

    }

    function obtener_hora_formateada(hora) {
        var fechaActual = new Date(hora);
        var hora = fechaActual.getHours();
        var minutos = fechaActual.getMinutes();

        // Formatear la hora como una cadena
        var horaFormateada = (hora < 10 ? '0' : '') + hora + ':' +
            (minutos < 10 ? '0' : '') + minutos;

        return horaFormateada;
    }

    //Estudiante 
    function datos_col_estudiante(id_estudiante) {
        $.ajax({
            data: {
                id: id_estudiante
            },
            url: '<?= $url_general ?>/controlador/estudiantesC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                nombres = response[0].sa_est_primer_apellido + ' ' + response[0].sa_est_segundo_apellido + ' ' + response[0].sa_est_primer_nombre + ' ' + response[0].sa_est_segundo_apellido;

                $('#sa_conp_nombres').val(nombres);
                $('#sa_conp_fecha_nacimiento').val(fecha_formateada(response[0].sa_est_fecha_nacimiento.date));
                $('#sa_conp_edad').val(edad_fecha_nacimiento(response[0].sa_est_fecha_nacimiento.date));
                $('#sa_conp_nivel').val(response[0].sa_gra_nombre);
                $('#sa_conp_paralelo').val(response[0].sa_par_nombre);
            }
        });
    }

    //Representante
    function datos_col_representante(id_representante) {
        $.ajax({
            data: {
                id: id_representante
            },
            url: '<?= $url_general ?>/controlador/representantesC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                nombres = response[0].sa_rep_primer_apellido + ' ' + response[0].sa_rep_segundo_apellido + ' ' + response[0].sa_rep_primer_nombre + ' ' + response[0].sa_rep_segundo_apellido;

                $('#sa_conp_rep_nombres').val(nombres);
                $('#sa_conp_correo').val(response[0].sa_rep_correo);
                $('#sa_conp_telefono').val(response[0].sa_rep_telefono_1);

            }
        });
    }

    //Medicamentos 1
    function datos_col_medicamentos_1(id_medicamento = '', nombre_medicamento_consulta = '') {
        var medicamentos = '';
        medicamentos = '<option selected disabled>-- Seleccione --</option>'
        $.ajax({
            data: {
                id: id_medicamento
            },
            url: '<?php echo $url_general ?>/controlador/medicamentosC.php?listar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                // console.log(response);   
                $.each(response, function(i, item) {
                    //console.log(item);

                    if (nombre_medicamento_consulta == item.sa_med_nombre) {
                        // Marca la opción correspondiente con el atributo 'selected'
                        medicamentos += '<option value="' + item.sa_med_nombre + '" selected>' + item.sa_med_nombre + '</option>';
                    } else {
                        medicamentos += '<option value="' + item.sa_med_nombre + '">' + item.sa_med_nombre + '</option>';
                    }
                });

                $('#sa_conp_medicina_1').html(medicamentos);
            }
        });
    }

    //Medicamentos 2
    function datos_col_medicamentos_2(id_medicamento = '', id_medicamento_consulta = '') {
        var medicamentos = '';
        medicamentos = '<option selected disabled>-- Seleccione --</option>'
        $.ajax({
            data: {
                id: id_medicamento
            },
            url: '<?php echo $url_general ?>/controlador/medicamentosC.php?listar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                // console.log(response);   
                $.each(response, function(i, item) {
                    //console.log(item);

                    if (id_medicamento_consulta == item.sa_med_nombre) {
                        // Marca la opción correspondiente con el atributo 'selected'
                        medicamentos += '<option value="' + item.sa_med_nombre + '" selected>' + item.sa_med_nombre + '</option>';
                    } else {
                        medicamentos += '<option value="' + item.sa_med_nombre + '">' + item.sa_med_nombre + '</option>';
                    }
                });

                $('#sa_conp_medicina_2').html(medicamentos);
            }
        });
    }

    //Medicamentos 3
    function datos_col_medicamentos_3(id_medicamento = '', id_medicamento_consulta = '') {
        var medicamentos = '';
        medicamentos = '<option selected disabled>-- Seleccione --</option>'
        $.ajax({
            data: {
                id: id_medicamento
            },
            url: '<?php echo $url_general ?>/controlador/medicamentosC.php?listar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                // console.log(response);   
                $.each(response, function(i, item) {
                    //console.log(item);

                    if (id_medicamento_consulta == item.sa_med_nombre) {
                        // Marca la opción correspondiente con el atributo 'selected'
                        medicamentos += '<option value="' + item.sa_med_nombre + '" selected>' + item.sa_med_nombre + '</option>';
                    } else {
                        medicamentos += '<option value="' + item.sa_med_nombre + '">' + item.sa_med_nombre + '</option>';
                    }
                });

                $('#sa_conp_medicina_3').html(medicamentos);
            }
        });
    }

    //Consultas del Estudiante datos
    function datos_col_consulta_estudiante(id_consulta) {
        $.ajax({
            data: {
                id: id_consulta
            },
            url: '<?php echo $url_general ?>/controlador/consultasC.php?listar_solo_consulta=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                // Asignar valores del estudiante
                $('#sa_conp_id').val(response[0].sa_conp_id);
                $('#sa_fice_id').val(response[0].sa_fice_id);

                /*$('#sa_conp_nombres').val(response[0].sa_conp_nombres);
                $('#sa_conp_nivel').val(response[0].sa_conp_nivel);
                $('#sa_conp_paralelo').val(response[0].sa_conp_paralelo);
                $('#sa_conp_edad').val(response[0].sa_conp_edad);
                $('#sa_conp_correo').val(response[0].sa_conp_correo);
                $('#sa_conp_telefono').val(response[0].sa_conp_telefono);*/

                // Asignar valores de fechas y horas
                $('#sa_conp_fecha_ingreso').val(fecha_formateada(response[0].sa_conp_fecha_ingreso.date));

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
                $('#sa_conp_certificado_salud').val(response[0].sa_conp_certificado_salud);
                $('#sa_conp_motivo_certificado').val(response[0].sa_conp_motivo_certificado);
                $('#sa_conp_CIE_10_certificado').val(response[0].sa_conp_CIE_10_certificado);
                $('#sa_conp_diagnostico_certificado').val(response[0].sa_conp_diagnostico_certificado);
                $('#sa_conp_fecha_entrega_certificado').val(fecha_formateada(response[0].sa_conp_fecha_entrega_certificado.date));
                $('#sa_conp_fecha_inicio_falta_certificado').val(fecha_formateada(response[0].sa_conp_fecha_inicio_falta_certificado.date));
                $('#sa_conp_fecha_fin_alta_certificado').val(fecha_formateada(response[0].sa_conp_fecha_fin_alta_certificado.date));
                $('#sa_conp_dias_permiso_certificado').val(response[0].sa_conp_dias_permiso_certificado);

                $('#sa_conp_permiso_salida').val(response[0].sa_conp_permiso_salida);
                $('#sa_conp_fecha_permiso_salud_salida').val(fecha_formateada(response[0].sa_conp_fecha_permiso_salud_salida.date));
                $('#sa_conp_hora_permiso_salida').val(obtener_hora_formateada(response[0].sa_conp_hora_permiso_salida.date));

                // Asignar valores de notificaciones y observaciones
                $('#sa_conp_notificacion_envio_representante').val(response[0].sa_conp_notificacion_envio_representante);
                $('#sa_conp_notificacion_envio_inspector').val(response[0].sa_conp_notificacion_envio_inspector);
                $('#sa_conp_notificacion_envio_guardia').val(response[0].sa_conp_notificacion_envio_guardia);

                $('#sa_conp_observaciones').val(response[0].sa_conp_observaciones);
                $('#sa_conp_tipo_consulta').val(response[0].sa_conp_tipo_consulta);


                // Asignar valores de estado y fechas de creación/modificación
                $('#sa_conp_estado').val(response[0].sa_conp_estado);
                //$('#sa_conp_fecha_creacion').val(response[0].sa_conp_fecha_creacion);
                //$('#sa_conp_fecha_modificar').val(response[0].sa_conp_fecha_modificar);

                console.log(response);
            }
        });
    }

    //falta estado para profesor
    function editar_insertar(tipo_atencion = '', n_representante = '', n_docente = '', n_inspector = '', n_guardia = '') {
        var sa_conp_id = $('#sa_conp_id').val();
        var sa_fice_id = $('#sa_fice_id').val();

        // Datos del estudiante
        var sa_conp_nombres = $('#sa_conp_nombres').val();
        var sa_conp_nivel = $('#sa_conp_nivel').val();
        var sa_conp_paralelo = $('#sa_conp_paralelo').val();
        var sa_conp_edad = $('#sa_conp_edad').val();
        var sa_conp_correo = $('#sa_conp_correo').val();
        var sa_conp_telefono = $('#sa_conp_telefono').val();

        // Fechas y horas
        var sa_conp_fecha_ingreso = ($('#sa_conp_fecha_ingreso').val());
        var sa_conp_desde_hora = ($('#sa_conp_desde_hora').val());
        var sa_conp_hasta_hora = ($('#sa_conp_hasta_hora').val());
        var sa_conp_tiempo_aten = $('#sa_conp_tiempo_aten').val();

        // Diagnósticos y medicamentos
        var sa_conp_CIE_10_1 = $('#sa_conp_CIE_10_1').val();
        var sa_conp_diagnostico_1 = $('#sa_conp_diagnostico_1').val();
        var sa_conp_CIE_10_2 = $('#sa_conp_CIE_10_2').val();
        var sa_conp_diagnostico_2 = $('#sa_conp_diagnostico_2').val();
        var sa_conp_medicina_1 = $('#sa_conp_medicina_1').val();
        var sa_conp_dosis_1 = $('#sa_conp_dosis_1').val();
        var sa_conp_medicina_2 = $('#sa_conp_medicina_2').val();
        var sa_conp_dosis_2 = $('#sa_conp_dosis_2').val();
        var sa_conp_medicina_3 = $('#sa_conp_medicina_3').val();
        var sa_conp_dosis_3 = $('#sa_conp_dosis_3').val();

        // Certificados y permisos
        var sa_conp_certificado_salud = $('#sa_conp_certificado_salud').val();
        var sa_conp_motivo_certificado = $('#sa_conp_motivo_certificado').val();
        var sa_conp_CIE_10_certificado = $('#sa_conp_CIE_10_certificado').val();
        var sa_conp_diagnostico_certificado = $('#sa_conp_diagnostico_certificado').val();
        var sa_conp_fecha_entrega_certificado = ($('#sa_conp_fecha_entrega_certificado').val());
        var sa_conp_fecha_inicio_falta_certificado = ($('#sa_conp_fecha_inicio_falta_certificado').val());
        var sa_conp_fecha_fin_alta_certificado = ($('#sa_conp_fecha_fin_alta_certificado').val());
        var sa_conp_dias_permiso_certificado = $('#sa_conp_dias_permiso_certificado').val();

        // Permisos de salida
        var sa_conp_permiso_salida = $('#sa_conp_permiso_salida').val();
        var sa_conp_fecha_permiso_salud_salida = ($('#sa_conp_fecha_permiso_salud_salida').val());
        var sa_conp_hora_permiso_salida = ($('#sa_conp_hora_permiso_salida').val());

        // Notificaciones y observaciones
        var sa_conp_notificacion_envio_representante = n_representante;
        var sa_conp_notificacion_envio_docente = n_docente;
        var sa_id_docente = $('#sa_id_docente').val();
        var sa_conp_notificacion_envio_inspector = n_inspector;
        var sa_conp_notificacion_envio_guardia = n_guardia;

        var sa_conp_observaciones = $('#sa_conp_observaciones').val();
        var sa_conp_tipo_consulta = tipo_atencion;

        // Estado y fechas de creación/modificación
        //var sa_conp_estado = $('#sa_conp_estado').val();
        //var sa_conp_fecha_creacion = $('#sa_conp_fecha_creacion').val();
        //var sa_conp_fecha_modificar = $('#sa_conp_fecha_modificar').val()

        // Crear objeto de parámetros
        var parametros = {
            'sa_conp_id': sa_conp_id,
            'sa_fice_id': sa_fice_id,
            'sa_conp_nombres': sa_conp_nombres,
            'sa_conp_nivel': sa_conp_nivel,
            'sa_conp_paralelo': sa_conp_paralelo,
            'sa_conp_edad': sa_conp_edad,
            'sa_conp_correo': sa_conp_correo,
            'sa_conp_telefono': sa_conp_telefono,
            'sa_conp_fecha_ingreso': sa_conp_fecha_ingreso,
            'sa_conp_desde_hora': sa_conp_desde_hora,
            'sa_conp_hasta_hora': sa_conp_hasta_hora,
            'sa_conp_tiempo_aten': sa_conp_tiempo_aten,
            'sa_conp_CIE_10_1': sa_conp_CIE_10_1,
            'sa_conp_diagnostico_1': sa_conp_diagnostico_1,
            'sa_conp_CIE_10_2': sa_conp_CIE_10_2,
            'sa_conp_diagnostico_2': sa_conp_diagnostico_2,
            'sa_conp_medicina_1': sa_conp_medicina_1,
            'sa_conp_dosis_1': sa_conp_dosis_1,
            'sa_conp_medicina_2': sa_conp_medicina_2,
            'sa_conp_dosis_2': sa_conp_dosis_2,
            'sa_conp_medicina_3': sa_conp_medicina_3,
            'sa_conp_dosis_3': sa_conp_dosis_3,
            'sa_conp_certificado_salud': sa_conp_certificado_salud,
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
            'sa_conp_notificacion_envio_docente': sa_conp_notificacion_envio_docente,
            'sa_id_docente': sa_id_docente,
            'sa_conp_notificacion_envio_inspector': sa_conp_notificacion_envio_inspector,
            'sa_conp_notificacion_envio_guardia': sa_conp_notificacion_envio_guardia,
            'sa_conp_observaciones': sa_conp_observaciones,
            'sa_conp_tipo_consulta': sa_conp_tipo_consulta
        };

        //alert(sa_conp_tipo_consulta)

        if (sa_conp_id == '') {
            if (
                sa_conp_nombres == null
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
                //console.log(parametros);
            }
        } else {
            if (
                sa_conp_nombres == null
            ) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Asegurese de llenar todo los campos',
                })
                //alert('error');
            } else {
                insertar(parametros);
                //alert('entra');
                //console.log(parametros);
            }
        }
        //console.log(parametros);
        //insertar(parametros);
    }

    function insertar(parametros) {
        var id_estudiante = '<?php echo $id_estudiante; ?>';
        var id_representante = '<?php echo $id_representante; ?>';
        var id_ficha = '<?php echo $id_ficha; ?>';

        console.log(parametros);

        $.ajax({
            data: {
                parametros: parametros
            },
            url: '<?= $url_general ?>/controlador/consultasC.php?insertar=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        location.href = '<?= $url_general ?>/vista/inicio.php?mod=7&acc=consulta_estudiante&id_estudiante=' + id_estudiante + '&id_representante=' + id_representante + '&id_ficha=' + id_ficha;
                    });
                } else if (response == -2) {
                    Swal.fire('', 'codigo ya registrado', 'success');
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
        var id_estudiante = '<?php echo $id_estudiante; ?>';
        var id_representante = '<?php echo $id_representante; ?>';
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
                        location.href = '<?= $url_general ?>/vista/inicio.php?mod=7&acc=consulta_estudiante&id_estudiante=' + id_estudiante + '&id_representante=' + id_representante + '&id_ficha=' + id_ficha;
                    });
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
                            <?php
                            if ($id_ficha == '') {
                                echo 'Registrar Consultas del Estudiante';
                            } else {
                                echo 'Modificar Consultas del Estudiante';
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
                                <?php
                                if ($id_ficha == '') {
                                    echo 'Registrar Consultas del Estudiante';
                                } else {
                                    echo 'Modificar Consultas del Estudiante';
                                }
                                ?>
                            </h5>
                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=consulta_estudiante&id_estudiante=<?= $id_estudiante ?>&id_representante=<?= $id_representante ?>&id_ficha=<?= $id_ficha ?>" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 pt-4" id="radio_tipo_atencion">
                            <label for="" class="form-label">Tipo de Atención: <label style="color: red;">* </label> </label>
                            <div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo_atencion_1" id="tipo_atencion_1_1" value="Consulta">
                                    <label class="form-check-label" for="flexRadioDefault1">Consulta</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo_atencion_1" id="tipo_atencion_1_2" value="Certificado">
                                    <label class="form-check-label" for="flexRadioDefault2">Certficado</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo_atencion_1" id="tipo_atencion_1_3" value="Salida">
                                    <label class="form-check-label" for="flexRadioDefault2">Salida</label>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <form action="" method="post">

                            <input type="hidden" id="sa_conp_id" name="sa_conp_id">
                            <input type="hidden" id="sa_fice_id" name="sa_fice_id">
                            <input type="hidden" id="sa_conp_notificacion_envio_guardia" name="sa_conp_notificacion_envio_guardia">
                            <input type="hidden" id="sa_conp_notificacion_envio_inspector" name="sa_conp_notificacion_envio_inspector">
                            <input type="hidden" id="sa_conp_notificacion_envio_docente" name="sa_conp_notificacion_envio_docente">
                            <input type="hidden" id="sa_id_docente" name="sa_id_docente">
                            <input type="hidden" id="sa_conp_notificacion_envio_representante" name="sa_conp_notificacion_envio_representante">

                            <div id="main_consulta" style="display: none;">

                                <ul class="nav nav-tabs nav-success" role="tablist">

                                    <li class="nav-item" role="presentation" id="seccion_navtab_consulta">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#consulta_tab" role="tab" aria-selected="true">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon"><i class='bx bx-home font-18 me-1'></i>
                                                </div>
                                                <div class="tab-title">CONSULTA</div>
                                            </div>
                                        </a>
                                    </li>

                                    <li class="nav-item" role="presentation" id="seccion_navtab_certificado">
                                        <a class="nav-link" data-bs-toggle="tab" href="#certificado_tab" role="tab" aria-selected="false">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon"><i class='bx bx-user-pin font-18 me-1'></i>
                                                </div>
                                                <div class="tab-title">CERTIFICADO</div>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation" id="seccion_navtab_salida">
                                        <a class="nav-link" data-bs-toggle="tab" href="#permiso_tab" role="tab" aria-selected="false">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon"><i class='bx bx-user-pin font-18 me-1'></i>
                                                </div>
                                                <div class="tab-title">PERMISO DE SALIDA</div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content py-3">
                                    <div class="tab-pane fade show active" id="consulta_tab" role="tabpanel">

                                        <div class="accordion accordion-flush" id="consulta_acordeon">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="flush-headingOne">
                                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#flush-estudiante" aria-expanded="true" aria-controls="flush-estudiante">
                                                        <h6 class="text-success"><b>I. DATOS GENERALES DEL ESTUDIANTE</b></h6>
                                                    </button>
                                                </h2>
                                                <div id="flush-estudiante" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne" data-bs-parent="#consulta_acordeon">
                                                    <div class="accordion-body">
                                                        <div>
                                                            <div class="row pt-2">
                                                                <div class="col-md-12">
                                                                    <label for="" class="form-label">Nombres: <label style="color: red;">*</label> </label>
                                                                    <input type="text" class="form-control form-control-sm" id="sa_conp_nombres" name="sa_conp_nombres" readonly>
                                                                </div>
                                                            </div>

                                                            <div class="row pt-3">
                                                                <div class="col-md-5">
                                                                    <label for="" class="form-label">Nivel: <label style="color: red;">*</label> </label>
                                                                    <input type="text" class="form-control form-control-sm" id="sa_conp_nivel" name="sa_conp_nivel" readonly>
                                                                </div>

                                                                <div class="col-md-1">
                                                                    <label for="" class="form-label">Paralelo: <label style="color: red;">*</label> </label>
                                                                    <input type="text" class="form-control form-control-sm" id="sa_conp_paralelo" name="sa_conp_paralelo" readonly>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <label for="" class="form-label">Fecha de Nacimiento: <label style="color: red;">*</label> </label>
                                                                    <input type="date" class="form-control form-control-sm" id="sa_conp_fecha_nacimiento" name="sa_conp_fecha_nacimiento" onchange="edad_normal(this.value);" readonly>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <label for="" class="form-label">Edad: <label style="color: red;">*</label> </label>
                                                                    <input type="text" class="form-control form-control-sm" id="sa_conp_edad" name="sa_conp_edad" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="flush-headingTwo">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-representante" aria-expanded="false" aria-controls="flush-representante">
                                                        <h6 class="text-success"><b>I.I Representante</b></h6>
                                                    </button>
                                                </h2>
                                                <div id="flush-representante" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#consulta_acordeon">
                                                    <div class="accordion-body">
                                                        <div>
                                                            <div class="row pt-2">
                                                                <div class="col-md-12">
                                                                    <label for="" class="form-label">Nombres: <label style="color: red;">*</label> </label>
                                                                    <input type="text" class="form-control form-control-sm" id="sa_conp_rep_nombres" name="sa_conp_rep_nombres" readonly>
                                                                </div>
                                                            </div>

                                                            <div class="row pt-3">
                                                                <div class="col-md-8">
                                                                    <label for="" class="form-label">Correo: <label style="color: red;">*</label> </label>
                                                                    <input type="text" class="form-control form-control-sm" id="sa_conp_correo" name="sa_conp_rep_correo" readonly>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <label for="" class="form-label">Teléfono Celular: <label style="color: red;">*</label> </label>
                                                                    <input type="text" class="form-control form-control-sm" id="sa_conp_telefono" name="sa_conp_rep_telefono" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>

                                        <h6 class="text-success"><b>II. CONSULTA</b></h6>
                                        <div>
                                            <div class="row pt-2">
                                                <div class="row pt-1">
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

                                                <div class="row pt-3">
                                                    <div class="col-md-12">
                                                        <label for="" class="form-label">CIE 10 - Diagnóstico 1: <label style="color: red;">*</label> </label>

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
                                                    <div class="col-md-8">
                                                        <label for="" class="form-label">Medicina 1: <label style="color: red;">*</label> </label>
                                                        <select class="form-select form-select-sm" id="sa_conp_medicina_1" name="sa_conp_medicina_1">
                                                            <option selected disabled>-- Seleccione --</option>

                                                        </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="" class="form-label">Dosis: <label style="color: red;">*</label> </label>
                                                        <input type="text" class="form-control form-control-sm" id="sa_conp_dosis_1" name="sa_conp_dosis_1">
                                                    </div>
                                                </div>

                                                <div class="row pt-3">
                                                    <div class="col-md-8">
                                                        <label for="" class="form-label">Medicina 2: <label style="color: red;">*</label> </label>
                                                        <select class="form-select form-select-sm" id="sa_conp_medicina_2" name="sa_conp_medicina_2">
                                                            <option selected disabled>-- Seleccione --</option>

                                                        </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="" class="form-label">Dosis: <label style="color: red;">*</label> </label>
                                                        <input type="text" class="form-control form-control-sm" id="sa_conp_dosis_2" name="sa_conp_dosis_2">
                                                    </div>
                                                </div>

                                                <div class="row pt-3">
                                                    <div class="col-md-8">
                                                        <label for="" class="form-label">Medicina 3: <label style="color: red;">*</label> </label>
                                                        <select class="form-select form-select-sm" id="sa_conp_medicina_3" name="sa_conp_medicina_3">
                                                            <option selected disabled>-- Seleccione --</option>

                                                        </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="" class="form-label">Dosis: <label style="color: red;">*</label> </label>
                                                        <input type="text" class="form-control form-control-sm" id="sa_conp_dosis_3" name="sa_conp_dosis_3">
                                                    </div>
                                                </div>

                                                <div class="row pt-3">
                                                    <div class="col-md-12">
                                                        <label for="" class="form-label">Observaciones: <label style="color: red;">*</label> </label>
                                                        <textarea name="sa_conp_observaciones" id="sa_conp_observaciones" cols="30" rows="1" class="form-control" placeholder="Observaciones"></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer pt-4" id="seccion_boton_consulta">
                                                <?php if ($id_estudiante == '') { ?>
                                                    <button class="btn btn-primary btn-sm px-4 m-1" onclick="editar_insertar('Consulta', 1, 1, 1, 0)" type="button"><i class="bx bx-save"></i> Guardar</button>
                                                <?php } else { ?>
                                                    <button class="btn btn-primary btn-sm px-4 m-1" onclick="editar_insertar('Consulta', 1, 1, 1, 0)" type="button"><i class="bx bx-save"></i> Guardar</button>
                                                    <button class="btn btn-danger btn-sm px-4 m-1" onclick="delete_datos()" type="button"><i class="bx bx-trash"></i> Eliminar</button>
                                                <?php } ?>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="tab-pane fade" id="certificado_tab" role="tabpanel">
                                        <h6 class="text-success"><b>III. CERTIFICADO</b></h6>
                                        <div>
                                            <div class="row pt-2">
                                                <div class="row pt-2">
                                                    <div class="col-md-2">
                                                        <label for="" class="form-label">Certificado por Salud: <label style="color: red;">*</label> </label>
                                                        <input type="text" class="form-control form-control-sm" id="sa_conp_certificado_salud" name="sa_conp_certificado_salud">
                                                    </div>

                                                    <div class="col-md-10">
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

                                            <div class="modal-footer pt-4" id="seccion_boton_certificado">
                                                <?php if ($id_estudiante == '') { ?>
                                                    <button class="btn btn-primary btn-sm px-4 m-1" onclick="editar_insertar('Certfificado', 0, 1, 1, 0)" type="button"><i class="bx bx-save"></i> Guardar</button>
                                                <?php } else { ?>
                                                    <button class="btn btn-primary btn-sm px-4 m-1" onclick="editar_insertar('Certfificado', 0, 1, 1, 0)" type="button"><i class="bx bx-save"></i> Guardar</button>
                                                    <button class="btn btn-danger btn-sm px-4 m-1" onclick="delete_datos()" type="button"><i class="bx bx-trash"></i> Eliminar</button>
                                                <?php } ?>
                                            </div>

                                        </div>



                                    </div>
                                    <div class="tab-pane fade" id="permiso_tab" role="tabpanel">
                                        <h6 class="text-success"><b>IV. PERMISO DE SALIDA</b></h6>
                                        <div>
                                            <div class="row pt-2">
                                                <div class="row pt-2">
                                                    <div class="col-md-2">
                                                        <label for="" class="form-label">Permiso de Salida: <label style="color: red;">*</label> </label>
                                                        <select class="form-select form-select-sm" id="sa_conp_permiso_salida" name="sa_conp_permiso_salida">
                                                            <option selected disabled>-- Seleccione --</option>
                                                            <option value="Si">Si</option>
                                                            <option value="No">No</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="" class="form-label">Fecha Permiso de Salud: <label style="color: red;">*</label> </label>
                                                        <input type="date" class="form-control form-control-sm" id="sa_conp_fecha_permiso_salud_salida" name="sa_conp_fecha_permiso_salud_salida">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="" class="form-label">Hora Permiso de Salida: <label style="color: red;">*</label> </label>
                                                        <input type="time" class="form-control form-control-sm" id="sa_conp_hora_permiso_salida" name="sa_conp_hora_permiso_salida">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer pt-4" id="seccion_boton_salida">
                                                <?php if ($id_estudiante == '') { ?>
                                                    <button class="btn btn-primary btn-sm px-4 m-1" onclick="editar_insertar('Salida', 1, 1, 1, 1)" type="button"><i class="bx bx-save"></i> Guardar</button>
                                                <?php } else { ?>
                                                    <button class="btn btn-primary btn-sm px-4 m-1" onclick="editar_insertar('Salida', 1, 1, 1, 1)" type="button"><i class="bx bx-save"></i> Guardar</button>
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
<!--plugins-->

<!--app JS-->
<!-- <script src="assets/js/app.js"></script> -->

<?php //include('../../../../cabeceras/footer.php'); 
?>