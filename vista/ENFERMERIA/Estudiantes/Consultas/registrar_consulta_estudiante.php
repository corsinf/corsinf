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

        if (id_estudiante != '') {
            datos_col_estudiante(id_estudiante);
            datos_col_representante(id_representante);
        }
        //alert(id_consulta)
        if (id_consulta != '') {
            datos_col_consulta_estudiante(id_consulta)
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

    });

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
                $('#sa_conp_medicina_1').val(response[0].sa_conp_medicina_1);
                $('#sa_conp_dosis_1').val(response[0].sa_conp_dosis_1);
                $('#sa_conp_medicina_2').val(response[0].sa_conp_medicina_2);
                $('#sa_conp_dosis_2').val(response[0].sa_conp_dosis_2);
                $('#sa_conp_medicina_3').val(response[0].sa_conp_medicina_3);
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

    function editar_insertar() {
        var sa_fice_id = $('#sa_fice_id').val();

        // Datos del estudiante
        var sa_fice_est_id = $('#sa_fice_est_id').val();
        var sa_fice_est_primer_apellido = $('#sa_fice_est_primer_apellido').val();
        var sa_fice_est_segundo_apellido = $('#sa_fice_est_segundo_apellido').val();
        var sa_fice_est_primer_nombre = $('#sa_fice_est_primer_nombre').val();
        var sa_fice_est_segundo_nombre = $('#sa_fice_est_segundo_nombre').val();
        var sa_fice_est_fecha_nacimiento = $('#sa_fice_est_fecha_nacimiento').val();

        // Resto de los campos del estudiante
        var sa_fice_est_grupo_sangre = $('#sa_fice_est_grupo_sangre').val();
        var sa_fice_est_direccion_domicilio = $('#sa_fice_est_direccion_domicilio').val();
        var sa_fice_est_seguro_medico = $('#sa_fice_est_seguro_medico').val();
        var sa_fice_est_nombre_seguro = $('#sa_fice_est_nombre_seguro').val();

        // Datos del representante 1
        var sa_fice_rep_1_id = $('#sa_fice_rep_1_id').val();
        var sa_fice_rep_1_primer_apellido = $('#sa_fice_rep_1_primer_apellido').val();
        var sa_fice_rep_1_segundo_apellido = $('#sa_fice_rep_1_segundo_apellido').val();
        var sa_fice_rep_1_primer_nombre = $('#sa_fice_rep_1_primer_nombre').val();
        var sa_fice_rep_1_segundo_nombre = $('#sa_fice_rep_1_segundo_nombre').val();
        var sa_fice_rep_1_parentesco = $('#sa_fice_rep_1_parentesco').val();
        var sa_fice_rep_1_telefono_1 = $('#sa_fice_rep_1_telefono_1').val();
        var sa_fice_rep_1_telefono_2 = $('#sa_fice_rep_1_telefono_2').val();

        // Datos del representante 2
        var sa_fice_rep_2_primer_apellido = $('#sa_fice_rep_2_primer_apellido').val();
        var sa_fice_rep_2_segundo_apellido = $('#sa_fice_rep_2_segundo_apellido').val();
        var sa_fice_rep_2_primer_nombre = $('#sa_fice_rep_2_primer_nombre').val();
        var sa_fice_rep_2_segundo_nombre = $('#sa_fice_rep_2_segundo_nombre').val();
        var sa_fice_rep_2_parentesco = $('#sa_fice_rep_2_parentesco').val();
        var sa_fice_rep_2_telefono_1 = $('#sa_fice_rep_2_telefono_1').val();
        var sa_fice_rep_2_telefono_2 = $('#sa_fice_rep_2_telefono_2').val();

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

        // Crear objeto de parámetros
        var parametros = {
            'sa_fice_id': sa_fice_id,
            'sa_fice_est_id': sa_fice_est_id,
            'sa_fice_est_primer_apellido': sa_fice_est_primer_apellido,
            'sa_fice_est_segundo_apellido': sa_fice_est_segundo_apellido,
            'sa_fice_est_primer_nombre': sa_fice_est_primer_nombre,
            'sa_fice_est_segundo_nombre': sa_fice_est_segundo_nombre,
            'sa_fice_est_fecha_nacimiento': sa_fice_est_fecha_nacimiento,
            'sa_fice_est_grupo_sangre': sa_fice_est_grupo_sangre,
            'sa_fice_est_direccion_domicilio': sa_fice_est_direccion_domicilio,
            'sa_fice_est_seguro_medico': sa_fice_est_seguro_medico,
            'sa_fice_est_nombre_seguro': sa_fice_est_nombre_seguro,
            'sa_fice_rep_1_id': sa_fice_rep_1_id,
            'sa_fice_rep_1_primer_apellido': sa_fice_rep_1_primer_apellido,
            'sa_fice_rep_1_segundo_apellido': sa_fice_rep_1_segundo_apellido,
            'sa_fice_rep_1_primer_nombre': sa_fice_rep_1_primer_nombre,
            'sa_fice_rep_1_segundo_nombre': sa_fice_rep_1_segundo_nombre,
            'sa_fice_rep_1_parentesco': sa_fice_rep_1_parentesco,
            'sa_fice_rep_1_telefono_1': sa_fice_rep_1_telefono_1,
            'sa_fice_rep_1_telefono_2': sa_fice_rep_1_telefono_2,
            'sa_fice_rep_2_primer_apellido': sa_fice_rep_2_primer_apellido,
            'sa_fice_rep_2_segundo_apellido': sa_fice_rep_2_segundo_apellido,
            'sa_fice_rep_2_primer_nombre': sa_fice_rep_2_primer_nombre,
            'sa_fice_rep_2_segundo_nombre': sa_fice_rep_2_segundo_nombre,
            'sa_fice_rep_2_parentesco': sa_fice_rep_2_parentesco,
            'sa_fice_rep_2_telefono_1': sa_fice_rep_2_telefono_1,
            'sa_fice_rep_2_telefono_2': sa_fice_rep_2_telefono_2,
            'sa_fice_pregunta_1': sa_fice_pregunta_1,
            'sa_fice_pregunta_1_obs': sa_fice_pregunta_1_obs,
            'sa_fice_pregunta_2': sa_fice_pregunta_2,
            'sa_fice_pregunta_2_obs': sa_fice_pregunta_2_obs,
            'sa_fice_pregunta_3': sa_fice_pregunta_3,
            'sa_fice_pregunta_3_obs': sa_fice_pregunta_3_obs,
            'sa_fice_pregunta_4': sa_fice_pregunta_4,
            'sa_fice_pregunta_4_obs': sa_fice_pregunta_4_obs,
            'sa_fice_pregunta_5_obs': sa_fice_pregunta_5_obs,
        };

        if (sa_fice_id == '') {
            if (
                sa_fice_est_grupo_sangre == null ||
                sa_fice_est_seguro_medico == null ||
                sa_fice_pregunta_1 == null ||
                sa_fice_pregunta_2 == null ||
                sa_fice_pregunta_3 == null ||
                sa_fice_pregunta_4 == null ||
                sa_fice_est_direccion_domicilio === ''
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
        } else {
            if (
                sa_fice_est_grupo_sangre == null ||
                sa_fice_est_seguro_medico == null ||
                sa_fice_pregunta_1 == null ||
                sa_fice_pregunta_2 == null ||
                sa_fice_pregunta_3 == null ||
                sa_fice_pregunta_4 == null ||
                sa_fice_est_direccion_domicilio === ''
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
            }
        }
        console.log(parametros);
        //insertar(parametros);
    }

    function insertar(parametros) {
        var id_estudiante = '<?php echo $id_estudiante; ?>';
        var id_representante = '<?php echo $id_representante; ?>';
        var id_ficha = '<?php echo $id_ficha; ?>';

        $.ajax({
            data: {
                parametros: parametros
            },
            url: '<?= $url_general ?>/controlador/fichas_EstudianteC.php?insertar=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        location.href = '<?= $url_general ?>/vista/inicio.php?mod=7&acc=ficha_estudiante&id_estudiante=' + id_estudiante + '&id_representante=' + id_representante;
                    });
                } else if (response == -2) {
                    Swal.fire('', 'codigo ya registrado', 'success');
                }
                console.log(response);
            }
        });
    }

    function delete_datos() {
        var id_ficha = '<?php echo $id_ficha; ?>';
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
                eliminar(id_ficha);
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
            url: '<?= $url_general ?>/controlador/fichas_EstudianteC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
                        location.href = '<?= $url_general ?>/vista/inicio.php?mod=7&acc=ficha_estudiante&id_estudiante=' + id_estudiante + '&id_representante=' + id_representante;
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
                        <hr>

                        <form action="" method="post">

                            <input type="hidden" id="sa_conp_id" name="sa_conp_id">



                            <ul class="nav nav-tabs nav-success" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#consulta_tab" role="tab" aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bx-home font-18 me-1'></i>
                                            </div>
                                            <div class="tab-title">CONSULTA</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#certificado_tab" role="tab" aria-selected="false">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bx-user-pin font-18 me-1'></i>
                                            </div>
                                            <div class="tab-title">CERTIFICADO</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
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

                                                    <select class="form-select" id="sa_conp_CIE_10_1" name="sa_conp_CIE_10_1">
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
                                                    <select class="form-select" id="sa_conp_CIE_10_2" name="sa_conp_CIE_10_2">
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
                                                    <select class="form-select" id="sa_conp_medicina_1" name="sa_conp_medicina_1">
                                                        <option selected disabled>-- Seleccione --</option>
                                                        <option value="Medicina_1">Medicina 1</option>
                                                        <option value="Medicina_2">Medicina 2</option>
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
                                                    <select class="form-select" id="sa_conp_medicina_2" name="sa_conp_medicina_2">
                                                        <option selected disabled>-- Seleccione --</option>
                                                        <option value="Medicina_1">Medicina 1</option>
                                                        <option value="Medicina_2">Medicina 2</option>
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
                                                    <select class="form-select" id="sa_conp_medicina_3" name="sa_conp_medicina_3">
                                                        <option selected disabled>-- Seleccione --</option>
                                                        <option value="Medicina_1">Medicina 1</option>
                                                        <option value="Medicina_2">Medicina 2</option>
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
                                                    <select class="form-select" id="sa_conp_CIE_10_certificado" name="sa_conp_CIE_10_certificado">
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
                                </div>
                                <div class="tab-pane fade" id="permiso_tab" role="tabpanel">
                                    <h6 class="text-success"><b>IV. PERMISO DE SALIDA</b></h6>
                                    <div>
                                        <div class="row pt-2">
                                            <div class="row pt-2">
                                                <div class="col-md-2">
                                                    <label for="" class="form-label">Permiso de Salida: <label style="color: red;">*</label> </label>
                                                    <select class="form-select" id="sa_conp_permiso_salida" name="sa_conp_permiso_salida">
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
                                    </div>
                                </div>
                            </div>



                            <div class="modal-footer pt-4">

                                <?php if ($id_estudiante == '') { ?>
                                    <button class="btn btn-primary btn-sm px-4 m-1" onclick="editar_insertar()" type="button"><i class="bx bx-save"></i> Guardar</button>
                                <?php } else { ?>
                                    <button class="btn btn-primary btn-sm px-4 m-1" onclick="editar_insertar()" type="button"><i class="bx bx-save"></i> Guardar</button>
                                    <button class="btn btn-danger btn-sm px-4 m-1" onclick="delete_datos()" type="button"><i class="bx bx-trash"></i> Eliminar</button>
                                <?php } ?>
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