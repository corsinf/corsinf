<?php //include('../../../../cabeceras/header.php');

$id_ficha = '';
$id_estudiante = '';
$id_representante = '';

if (isset($_GET['id_estudiante'])) {
    $id_estudiante = $_GET['id_estudiante'];
}

if (isset($_GET['id_representante'])) {
    $id_representante = $_GET['id_representante'];
}

if (isset($_GET['id_ficha'])) {
    $id_ficha = $_GET['id_ficha'];
}

?>

<script type="text/javascript">
    $(document).ready(function() {
        var id_estudiante = '<?php echo $id_estudiante; ?>';
        var id_representante = '<?php echo $id_representante; ?>';
        var id_ficha = '<?php echo $id_ficha; ?>';

        //alert(id_estudiante + ' + ' + id_representante);

        if (id_estudiante != '') {
            datos_col_estudiante(id_estudiante);
            datos_col_representante(id_representante);
        }

        if (id_ficha != '') {
            datos_col_ficha_estudiante(id_ficha)
        }

        //////////////////////////////////////////////////

        $('input[name=sa_fice_pregunta_1]').change(function() {
            if ($(this).val() === 'Si') {
                $('#sa_fice_pregunta_1_obs').show();
            } else if ($(this).val() === 'No') {
                $('#sa_fice_pregunta_1_obs').hide();
                $('#sa_fice_pregunta_1_obs').val('');
            }
        });

        $('input[name=sa_fice_pregunta_2]').change(function() {
            if ($(this).val() === 'Si') {
                $('#sa_fice_pregunta_2_obs').show();
            } else if ($(this).val() === 'No') {
                $('#sa_fice_pregunta_2_obs').hide();
                $('#sa_fice_pregunta_2_obs').val('');
            }
        });

        $('input[name=sa_fice_pregunta_3]').change(function() {
            if ($(this).val() === 'Si') {
                $('#sa_fice_pregunta_3_obs').show();
            } else if ($(this).val() === 'No') {
                $('#sa_fice_pregunta_3_obs').hide();
                $('#sa_fice_pregunta_3_obs').val('');
            }
        });

        $('input[name=sa_fice_pregunta_4]').change(function() {
            if ($(this).val() === 'Si') {
                $('#sa_fice_pregunta_4_obs').show();
            } else if ($(this).val() === 'No') {
                $('#sa_fice_pregunta_4_obs').hide();
                $('#sa_fice_pregunta_4_obs').val('');
            }
        });

        $('#sa_fice_est_seguro_medico').change(function() {
            if ($(this).val() === 'Si') {
                $('#sa_fice_est_nombre_seguro').show();
            } else if ($(this).val() === 'No') {
                $('#sa_fice_est_nombre_seguro').hide();
                $('#sa_fice_est_nombre_seguro').val('');
            }
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

                $('#sa_fice_est_id').val(response[0].sa_est_id);
                $('#sa_fice_est_primer_apellido').val(response[0].sa_est_primer_apellido);
                $('#sa_fice_est_segundo_apellido').val(response[0].sa_est_segundo_apellido);
                $('#sa_fice_est_primer_nombre').val(response[0].sa_est_primer_nombre);
                $('#sa_fice_est_segundo_nombre').val(response[0].sa_est_segundo_nombre);

                ///////////////////////////////////////////////////////////////////////////////////////////
                //Fecha de nacimiento

                $('#sa_fice_est_fecha_nacimiento').val(fecha_nacimiento_formateada(response[0].sa_est_fecha_nacimiento.date));
                $('#sa_fice_est_edad').val(edad_fecha_nacimiento(response[0].sa_est_fecha_nacimiento.date));
                ///////////////////////////////////////////////////////////////////////////////////////////

                //console.log(response);

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

    function fecha_nacimiento_formateada(fecha) {
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

                $('#sa_fice_rep_1_id').val(response[0].sa_rep_id);
                $('#sa_fice_rep_1_primer_apellido').val(response[0].sa_rep_primer_apellido);
                $('#sa_fice_rep_1_segundo_apellido').val(response[0].sa_rep_segundo_apellido);
                $('#sa_fice_rep_1_primer_nombre').val(response[0].sa_rep_primer_nombre);
                $('#sa_fice_rep_1_segundo_nombre').val(response[0].sa_rep_segundo_nombre);

                ///////////////////////////////////////////////////////////////////////////////////////////

                //$('#sa_rep_parentesco').val(response[0].sa_rep_parentesco);

                if (response[0].sa_rep_parentesco === 'Padre') {
                    selectElement = $('#sa_fice_rep_1_parentesco');
                    optionElement = selectElement.find('option[value="Padre"]');
                    if (optionElement.length > 0) {
                        optionElement.prop('selected', true);
                    }
                } else if (response[0].sa_rep_parentesco === 'Madre') {
                    selectElement = $('#sa_fice_rep_1_parentesco');
                    optionElement = selectElement.find('option[value="Madre"]');
                    if (optionElement.length > 0) {
                        optionElement.prop('selected', true);
                    }
                } else if (response[0].sa_rep_parentesco === 'Hermano') {
                    selectElement = $('#sa_fice_rep_1_parentesco');
                    optionElement = selectElement.find('option[value="Hermano"]');
                    if (optionElement.length > 0) {
                        optionElement.prop('selected', true);
                    }
                } else if (response[0].sa_rep_parentesco === 'Tio') {
                    selectElement = $('#sa_fice_rep_1_parentesco');
                    optionElement = selectElement.find('option[value="Tio"]');
                    if (optionElement.length > 0) {
                        optionElement.prop('selected', true);
                    }
                } else if (response[0].sa_rep_parentesco === 'Primo') {
                    selectElement = $('#sa_fice_rep_1_parentesco');
                    optionElement = selectElement.find('option[value="Primo"]');
                    if (optionElement.length > 0) {
                        optionElement.prop('selected', true);
                    }
                } else if (response[0].sa_rep_parentesco === 'Abuelo') {
                    selectElement = $('#sa_fice_rep_1_parentesco');
                    optionElement = selectElement.find('option[value="Abuelo"]');
                    if (optionElement.length > 0) {
                        optionElement.prop('selected', true);
                    }
                } else if (response[0].sa_rep_parentesco === 'Otro') {
                    selectElement = $('#sa_fice_rep_1_parentesco');
                    optionElement = selectElement.find('option[value="Otro"]');
                    if (optionElement.length > 0) {
                        optionElement.prop('selected', true);
                    }
                }

                ///////////////////////////////////////////////////////////////////////////////////////////

                $('#sa_fice_rep_1_telefono_1').val(response[0].sa_rep_telefono_1);
                $('#sa_fice_rep_1_telefono_2').val(response[0].sa_rep_telefono_2);

                console.log(response);

            }
        });
    }

    //Ficha del estudiante datos

    function datos_col_ficha_estudiante(id_ficha) {
        $.ajax({
            data: {
                id: id_ficha
            },
            url: '<?php echo $url_general ?>/controlador/ficha_MedicaC.php?listar_solo_ficha=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                //Id de la ficha
                $('#sa_fice_id').val(response[0].sa_fice_id);

                /*// Datos del estudiante
                $('#sa_fice_est_id').val(response[0].sa_fice_est_id);
                $('#sa_fice_est_primer_apellido').val(response[0].sa_fice_est_primer_apellido);
                $('#sa_fice_est_segundo_apellido').val(response[0].sa_fice_est_segundo_apellido);
                $('#sa_fice_est_primer_nombre').val(response[0].sa_fice_est_primer_nombre);
                $('#sa_fice_est_segundo_nombre').val(response[0].sa_fice_est_segundo_nombre);
                $('#sa_fice_est_fecha_nacimiento').val(fecha_nacimiento_formateada(response[0].sa_fice_est_fecha_nacimiento.date));*/

                //Probar con todos
                $('#sa_fice_est_grupo_sangre').val(response[0].sa_fice_est_grupo_sangre);
                $('#sa_fice_est_direccion_domicilio').val(response[0].sa_fice_est_direccion_domicilio);
                $('#sa_fice_est_seguro_medico').val(response[0].sa_fice_est_seguro_medico);
                $('#sa_fice_est_nombre_seguro').val(response[0].sa_fice_est_nombre_seguro);

                /*if (response[0].sa_fice_est_nombre_seguro === 'Si') {
                    $('#sa_fice_est_nombre_seguro').show();
                } else if (response[0].sa_fice_est_nombre_seguro === 'No') {
                    $('#sa_fice_est_nombre_seguro').hide();
                    $('#sa_fice_est_nombre_seguro').val('');
                }*/

                /* // Datos del representante 1
                 $('#sa_fice_rep_1_id').val(response[0].sa_fice_rep_1_id);
                 $('#sa_fice_rep_1_primer_apellido').val(response[0].sa_fice_rep_1_primer_apellido);
                 $('#sa_fice_rep_1_segundo_apellido').val(response[0].sa_fice_rep_1_segundo_apellido);
                 $('#sa_fice_rep_1_primer_nombre').val(response[0].sa_fice_rep_1_primer_nombre);
                 $('#sa_fice_rep_1_segundo_nombre').val(response[0].sa_fice_rep_1_segundo_nombre);
                 $('#sa_fice_rep_1_parentesco').val(response[0].sa_fice_rep_1_parentesco);
                 $('#sa_fice_rep_1_telefono_1').val(response[0].sa_fice_rep_1_telefono_1);
                 $('#sa_fice_rep_1_telefono_2').val(response[0].sa_fice_rep_1_telefono_2);*/

                // Datos del representante 2
                $('#sa_fice_rep_2_id').val(response[0].sa_fice_rep_2_id);
                $('#sa_fice_rep_2_primer_apellido').val(response[0].sa_fice_rep_2_primer_apellido);
                $('#sa_fice_rep_2_segundo_apellido').val(response[0].sa_fice_rep_2_segundo_apellido);
                $('#sa_fice_rep_2_primer_nombre').val(response[0].sa_fice_rep_2_primer_nombre);
                $('#sa_fice_rep_2_segundo_nombre').val(response[0].sa_fice_rep_2_segundo_nombre);
                $('#sa_fice_rep_2_parentesco').val(response[0].sa_fice_rep_2_parentesco);
                $('#sa_fice_rep_2_telefono_1').val(response[0].sa_fice_rep_2_telefono_1);
                $('#sa_fice_rep_2_telefono_2').val(response[0].sa_fice_rep_2_telefono_2);

                // Preguntas
                $('input[name=sa_fice_pregunta_1][value=' + response[0].sa_fice_pregunta_1 + ']').prop('checked', true);
                $('#sa_fice_pregunta_1_obs').val(response[0].sa_fice_pregunta_1_obs);

                $('input[name=sa_fice_pregunta_2][value=' + response[0].sa_fice_pregunta_2 + ']').prop('checked', true);
                $('#sa_fice_pregunta_2_obs').val(response[0].sa_fice_pregunta_2_obs);

                $('input[name=sa_fice_pregunta_3][value=' + response[0].sa_fice_pregunta_3 + ']').prop('checked', true);
                $('#sa_fice_pregunta_3_obs').val(response[0].sa_fice_pregunta_3_obs);

                $('input[name=sa_fice_pregunta_4][value=' + response[0].sa_fice_pregunta_4 + ']').prop('checked', true);
                $('#sa_fice_pregunta_4_obs').val(response[0].sa_fice_pregunta_4_obs);

                $('#sa_fice_pregunta_5_obs').val(response[0].sa_fice_pregunta_5_obs);

                // Otros campos
                $('#sa_fice_estado').val(response[0].sa_fice_estado);
                $('#sa_fice_fecha_creacion').val(response[0].sa_fice_fecha_creacion);
                $('#sa_fice_fecha_modificar').val(response[0].sa_fice_fecha_modificar);

                //console.log(response);
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
            url: '<?= $url_general ?>/controlador/ficha_medicaC.php?insertar=true',
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
            url: '<?= $url_general ?>/controlador/ficha_medicaC.php?eliminar=true',
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
                                echo 'Registrar Ficha del Estudiante';
                            } else {
                                echo 'Modificar Ficha del Estudiante';
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
                                    echo 'Registrar Ficha del Estudiante';
                                } else {
                                    echo 'Modificar Ficha del Estudiante';
                                }
                                ?>
                            </h5>
                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=ficha_estudiante&id_estudiante=<?= $id_estudiante ?>&id_representante=<?= $id_representante ?>" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <form action="" method="post">

                            <input type="hidden" id="sa_fice_id" name="sa_fice_id">
                            <input type="hidden" id="sa_fice_est_id" name="sa_fice_est_id">
                            <input type="hidden" id="sa_fice_rep_1_id" name="sa_fice_rep_1_id">

                            <h5>I. DATOS GENERALES DEL ESTUDIANTE</h5>

                            <div class="row pt-3">
                                <div class="col-md-3">
                                    <label for="" class="form-label">Primer Apellido: <label style="color: red;">*</label> </label>
                                    <input type="text" class="form-control" id="sa_fice_est_primer_apellido" name="sa_fice_est_primer_apellido" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="" class="form-label">Segundo Apellido: <label style="color: red;">*</label> </label>
                                    <input type="text" class="form-control" id="sa_fice_est_segundo_apellido" name="sa_fice_est_segundo_apellido" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="" class="form-label">Primer Nombre: <label style="color: red;">*</label> </label>
                                    <input type="text" class="form-control" id="sa_fice_est_primer_nombre" name="sa_fice_est_primer_nombre" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="" class="form-label">Segundo Nombre: <label style="color: red;">*</label> </label>
                                    <input type="text" class="form-control" id="sa_fice_est_segundo_nombre" name="sa_fice_est_segundo_nombre" readonly>
                                </div>
                            </div>

                            <div class="row pt-3">
                                <div class="col-md-3">
                                    <label for="" class="form-label">Fecha de Nacimiento: <label style="color: red;">*</label> </label>
                                    <input type="date" class="form-control" id="sa_fice_est_fecha_nacimiento" name="sa_fice_est_fecha_nacimiento" onchange="edad_normal(this.value);" readonly>
                                </div>

                                <div class="col-md-3">
                                    <label for="" class="form-label">Edad: <label style="color: red;">*</label> </label>
                                    <input type="text" class="form-control" id="sa_fice_est_edad" name="sa_fice_est_edad" readonly>
                                </div>
                            </div>

                            <br>
                            <hr>

                            <div class="row pt-3">
                                <div class="col-md-3">
                                    <label for="" class="form-label"> Grupo Sanguíneo y Factor Rh: <label style="color: red;">*</label> </label>
                                    <select class="form-select" id="sa_fice_est_grupo_sangre" name="sa_fice_est_grupo_sangre">
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

                                <div class="col-md-9">
                                    <label for="" class="form-label">Dirección del Domicilio: <label style="color: red;">*</label> </label>
                                    <input type="text" class="form-control" id="sa_fice_est_direccion_domicilio" name="sa_fice_est_direccion_domicilio">
                                </div>
                            </div>

                            <div class="row pt-3">
                                <div class="col-md-3">
                                    <label for="" class="form-label">¿El estudiante posee seguro médico?: <label style="color: red;">*</label> </label>
                                    <select class="form-select" id="sa_fice_est_seguro_medico" name="sa_fice_est_seguro_medico">
                                        <option selected disabled>-- Seleccione --</option>
                                        <option value="Si">Si</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="" class="form-label">Nombre del seguro: <label style="color: red;">*</label> </label>
                                    <select class="form-select" id="sa_fice_est_nombre_seguro" name="sa_fice_est_nombre_seguro">
                                        <option selected disabled value="">-- Seleccione --</option>
                                        <option value="IESS">IESS</option>
                                        <option value="ISSFA">ISSFA</option>
                                        <option value="ISSPOL">ISSPOL</option>
                                    </select>
                                </div>
                            </div>

                            <hr>
                            <h5>Representante</h5>

                            <p style="color: red;">*En caso de urgencia llamar a (orden de importancia), Indique obligatoriamente al menos un número fijo de contacto</p>

                            <div>

                                <h6><b>Nombre del Representante o Familiar Responsable 1</b></h6>

                                <div class="row pt-2">
                                    <div class="col-md-3">
                                        <label for="" class="form-label">Primer Apellido: <label style="color: red;">*</label> </label>
                                        <input type="text" class="form-control" id="sa_fice_rep_1_primer_apellido" name="sa_fice_rep_1_primer_apellido" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="" class="form-label">Segundo Apellido: <label style="color: red;">*</label> </label>
                                        <input type="text" class="form-control" id="sa_fice_rep_1_segundo_apellido" name="sa_fice_rep_1_segundo_apellido" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="" class="form-label">Primer Nombre: <label style="color: red;">*</label> </label>
                                        <input type="text" class="form-control" id="sa_fice_rep_1_primer_nombre" name="sa_fice_rep_1_primer_nombre" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="" class="form-label">Segundo Nombre: <label style="color: red;">*</label> </label>
                                        <input type="text" class="form-control" id="sa_fice_rep_1_segundo_nombre" name="sa_fice_rep_1_segundo_nombre" readonly>
                                    </div>
                                </div>

                                <div class="row pt-3">
                                    <div class="col-md-4">
                                        <label for="" class="form-label">Parentesco: <label style="color: red;">*</label> </label>

                                        <select class="form-select" id="sa_fice_rep_1_parentesco" name="sa_fice_rep_1_parentesco" disabled>
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

                                    <div class="col-md-4">
                                        <label for="" class="form-label">Teléfono Fijo: <label style="color: red;">*</label> </label>
                                        <input type="text" class="form-control" id="sa_fice_rep_1_telefono_1" name="sa_fice_rep_1_telefono_1" readonly>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="" class="form-label">Teléfono Celular: <label style="color: red;">*</label> </label>
                                        <input type="text" class="form-control" id="sa_fice_rep_1_telefono_2" name="sa_fice_rep_1_telefono_2" readonly>
                                    </div>
                                </div>

                            </div>

                            <div>

                                <h6 class="row pt-3"><b>Nombre del Representante o Familiar Responsable 2 (Opcional)</b></h6>

                                <div class="row pt-2">
                                    <div class="col-md-3">
                                        <label for="" class="form-label">Primer Apellido: <label style="color: red;">*</label> </label>
                                        <input type="text" class="form-control" id="sa_fice_rep_2_primer_apellido" name="sa_fice_rep_2_primer_apellido">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="" class="form-label">Segundo Apellido: <label style="color: red;">*</label> </label>
                                        <input type="text" class="form-control" id="sa_fice_rep_2_segundo_apellido" name="sa_fice_rep_2_segundo_apellido">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="" class="form-label">Primer Nombre: <label style="color: red;">*</label> </label>
                                        <input type="text" class="form-control" id="sa_fice_rep_2_primer_nombre" name="sa_fice_rep_2_primer_nombre">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="" class="form-label">Segundo Nombre: <label style="color: red;">*</label> </label>
                                        <input type="text" class="form-control" id="sa_fice_rep_2_segundo_nombre" name="sa_fice_rep_2_segundo_nombre">
                                    </div>
                                </div>

                                <div class="row pt-3">
                                    <div class="col-md-4">
                                        <label for="" class="form-label">Parentesco: <label style="color: red;">*</label> </label>

                                        <select class="form-select" id="sa_fice_rep_2_parentesco" name="sa_fice_rep_2_parentesco">
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

                                    <div class="col-md-4">
                                        <label for="" class="form-label">Teléfono Fijo: <label style="color: red;">*</label> </label>
                                        <input type="text" class="form-control" id="sa_fice_rep_2_telefono_1" name="sa_fice_rep_2_telefono_1">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="" class="form-label">Teléfono Celular: <label style="color: red;">*</label> </label>
                                        <input type="text" class="form-control" id="sa_fice_rep_2_telefono_2" name="sa_fice_rep_2_telefono_2">
                                    </div>
                                </div>

                            </div>

                            <hr>

                            <h5>II. INFORMACIÓN IMPORTANTE</h5>

                            <p style="color: red;">*Si usted considera que existe alguna condición médica importante en el estudiante. Mencionar, por favor explíquelo a continuación.</p>

                            <div class="row pt-2">

                                <div class="col-md-12">
                                    <label for="" class="form-label">1.- ¿Ha sido diagnosticado con alguna enfermedad?: <label style="color: red;">* OBLIGATORIO</label> </label>
                                    <div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="sa_fice_pregunta_1" id="sa_fice_pregunta_1_1" value="Si">
                                            <label class="form-check-label" for="flexRadioDefault1">SI</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="sa_fice_pregunta_1" id="sa_fice_pregunta_1_2" value="No">
                                            <label class="form-check-label" for="flexRadioDefault2">NO</label>
                                        </div>

                                        <textarea name="sa_fice_pregunta_1_obs" id="sa_fice_pregunta_1_obs" cols="30" rows="1" class="form-control" placeholder="¿Cúal?"></textarea>

                                    </div>
                                </div>

                                <div class="col-md-12 pt-4">
                                    <label for="" class="form-label">2.- ¿Tiene algún antecedente familiar de importancia?: <label style="color: red;">* PADRES – HERMANOS – ABUELOS - TIOS </label> </label>
                                    <div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="sa_fice_pregunta_2" id="sa_fice_pregunta_2_1" value="Si">
                                            <label class="form-check-label" for="flexRadioDefault1">SI</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="sa_fice_pregunta_2" id="sa_fice_pregunta_2_2" value="No">
                                            <label class="form-check-label" for="flexRadioDefault2">NO</label>
                                        </div>

                                        <textarea name="sa_fice_pregunta_2_obs" id="sa_fice_pregunta_2_obs" cols="30" rows="1" class="form-control" placeholder="¿Cúal?"></textarea>

                                    </div>
                                </div>

                                <div class="col-md-12 pt-4">
                                    <label for="" class="form-label">3.- ¿Ha sido sometido a cirugías previas?: <label style="color: red;">* OBLIGATORIO </label> </label>
                                    <div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="sa_fice_pregunta_3" id="sa_fice_pregunta_3_1" value="Si">
                                            <label class="form-check-label" for="flexRadioDefault1">SI</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="sa_fice_pregunta_3" id="sa_fice_pregunta_3_2" value="No">
                                            <label class="form-check-label" for="flexRadioDefault2">NO</label>
                                        </div>

                                        <textarea name="sa_fice_pregunta_3_obs" id="sa_fice_pregunta_3_obs" cols="30" rows="1" class="form-control" placeholder="¿Cúal?"></textarea>

                                    </div>
                                </div>

                                <div class="col-md-12 pt-4">
                                    <label for="" class="form-label">4.- ¿Tiene alergias?: <label style="color: red;">* OBLIGATORIO </label> </label>
                                    <div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="sa_fice_pregunta_4" id="sa_fice_pregunta_4_1" value="Si">
                                            <label class="form-check-label" for="flexRadioDefault1">SI</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="sa_fice_pregunta_4" id="sa_fice_pregunta_4_2" value="No">
                                            <label class="form-check-label" for="flexRadioDefault2">NO</label>
                                        </div>

                                        <textarea name="sa_fice_pregunta_4_obs" id="sa_fice_pregunta_4_obs" cols="30" rows="1" class="form-control" placeholder="¿Cúal?"></textarea>

                                    </div>
                                </div>

                                <div class="col-md-12 pt-4">

                                    <label for="" class="form-label">5.- ¿Qué medicamentos usa?: <label style="color: red;">*</label> </label>
                                    <p style="color: red;">*Si el estudiante requiere algún tratamiento específico durante el horario escolar, el representante deberá enviar el medicamento con la indicación médica correspondiente por agenda a través del docente tutor</p>

                                    <div>
                                        <textarea name="sa_fice_pregunta_5_obs" id="sa_fice_pregunta_5_obs" cols="30" rows="10" class="form-control"></textarea>
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