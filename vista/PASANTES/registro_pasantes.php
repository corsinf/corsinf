<?php
$registro_id = 1; //$_GET['registro_id'];

?>
<script>
    $(document).ready(function() {
        cargarDatos();
        inhabilitar_boton()
    });

    function cargarDatos() {
        $.ajax({
            url: '../controlador/PASANTES/asistencias_pasantesC.php?listar=true',
            type: 'post',
            // data: {
            //     id: 1
            // },
            dataType: 'json',
            success: function(response) {
                console.log(response);
            },
            // error: function(jqXHR, textStatus, errorThrown) {
            //     // Manejo de errores
            //     console.error('Error al cargar los configs:', textStatus, errorThrown);
            //     $('#pnl_config_general').append('<p>Error al cargar las configuraciones. Por favor, inténtalo de nuevo más tarde.</p>');
            // }
        });
    }

    function editar_insertar() {
        var pas_observacion_pasante = $('#txt_pasantes').val();
        var pas_observacion_tutor = $('#txt_tutor').val();
        var registro_id = $('#txt_registro_id').val();

        var parametros = {
            'pas_observacion_pasante': pas_observacion_pasante,
            'pas_observacion_tutor': pas_observacion_tutor,
            'registro_id': registro_id
        };

        //alert(validar_email(sa_est_correo));
        // console.log(parametros);

        // if (sa_est_id == '') {
        //     if (
        //         sa_est_primer_apellido === '' ||
        //         sa_est_segundo_apellido === '' ||
        //         sa_est_primer_nombre === '' ||
        //         sa_est_segundo_nombre === '' ||
        //         sa_est_cedula === '' ||
        //         sa_est_sexo == null ||
        //         sa_est_fecha_nacimiento === '' ||
        //         sa_id_seccion == null ||
        //         sa_id_grado == null ||
        //         sa_id_paralelo == null ||
        //         validar_email(sa_est_correo) == false ||
        //         sa_id_representante == null ||
        //         sa_est_rep_parentesco == null

        //     ) {
        //         Swal.fire({
        //             icon: 'error',
        //             title: 'Oops...',
        //             text: 'Asegurese de llenar todos los campos',
        //         })

        //     } else {
        //         //console.log(parametros);
        //         insertar(parametros)
        //     }
        // } else {
        //     if (
        //         sa_est_primer_apellido === '' ||
        //         sa_est_segundo_apellido === '' ||
        //         sa_est_primer_nombre === '' ||
        //         sa_est_segundo_nombre === '' ||
        //         sa_est_cedula === '' ||
        //         sa_est_sexo == null ||
        //         sa_est_fecha_nacimiento === '' ||
        //         sa_id_seccion == null ||
        //         sa_id_grado == null ||
        //         sa_id_paralelo == null ||
        //         validar_email(sa_est_correo) == false ||
        //         sa_id_representante == null ||
        //         sa_est_rep_parentesco == null
        //     ) {
        //         Swal.fire({
        //             icon: 'error',
        //             title: 'Oops...',
        //             text: 'Asegurese de llenar todos los campos',
        //         })
        //     } else {
        //         //console.log(parametros);
        //         insertar(parametros);
        //     }
        // }
    }

    function insertar_llegada(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/PASANTES/asistencias_pasantesC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        //location.href = '../vista/inicio.php?mod=7&acc=estudiantes';
                    });
                } else if (response == -2) {
                    Swal.fire('', 'Ya has registrado tu hora de llegada.', 'warning');
                }
            }
        });
    }
    
    function inhabilitar_boton(){
        $('#btn_llegada').on('click', function(){
            $(this).prop('disabled', true)
        })
    }
</script>
<div class="page-wrapper">
    <div class="page-content">

        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Facturación </div>
            <?php
            // print_r($_SESSION['INICIO']);die();
            ?>
        </div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Registro de Pasantes</li>
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
                        <div class="col-9">
                            <div class="card-title d-flex align-items-center">
                                <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                                </div>

                                <h5 class="mb-0 text-primary">Registre su asistencia:<b id="title_paciente" class="text-success"></b></h5>
                                <?php //print_r($_SESSION)//['INICIO']['USUARIO'])  //TIPO 
                                ?>

                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="content">
                        <!-- Content Header (Page header) -->
                        <br>

                        <section class="content">
                            <div class="container-fluid">

                                <div class="row justify-content-center" id="btn_nuevo">

                                    <!-- <div class="col-auto">

                                        <div class="card">
                                            <div class="card-body bg-primary">
                                                <button type="button" class="btn btn-primary btn-lg m-4" onclick="insertar_llegada();">Hora de entrada</button>
                                            </div>
                                        </div>

                                    </div> -->

                                    <!-- <div class="col-auto">

                                        <div class="card">
                                            <div class="card-body bg-primary">
                                                <button type="button" class="btn btn-primary btn-lg m-4" onclick="insertar_fin();"> Hora de salida</button>
                                            </div>
                                        </div>

                                    </div> -->
                                    <div class="col-auto">
                                    <button type="button" class="btn btn-primary btn-lg m-4 p-5" id="btn_llegada" onclick="insertar_llegada();">Hora de entrada</button>
                                    </div>
                                </div>

                                <div>
                                    <div class="row pt-3">
                                        <div class="col-12">
                                            <label for="txt_obs_pasantes">Observacion Pasantes</label>
                                            <input type="text" class="form-control" name="txt_obs_pasantes" id="txt_obs_pasantes">
                                        </div>
                                    </div>

                                    <div class="row pt-3">
                                        <div class="col-12">
                                            <label for="txt_obs_tutor">Observacion Tutor</label>
                                            <input type="text" class="form-control" name="txt_obs_tutor" id="txt_obs_tutor">
                                        </div>
                                    </div>
                                </div>

                            </div><!-- /.container-fluid -->
                        </section>
                        <!-- /.content -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
