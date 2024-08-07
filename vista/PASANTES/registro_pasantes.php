<script>
    $(document).ready(function() {
        cargarDatos();
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

        var pas_id = $('#txt_student').val();
        var pas_usu_id = $('#txt_student').val();
        var pas_nombre = $('#txt_student').val();
        var pas_hora_llegada = $('#txt_student').val();
        var pas_hora_salida = $('#txt_student').val();
        var pas_horas_total = $('#txt_student').val();
        var pas_observacion_pasante = $('#txt_student').val();
        var pas_observacion_tutor = $('#txt_student').val();
        var pas_usu_id_tutor = $('#txt_student').val();
        var pas_tutor_estado = $('#txt_student').val();
        var pas_estado = $('#txt_student').val();
        var pas_fecha_creacion = $('#txt_student').val();
        var pas_fecha_modficacion = $('#txt_student').val();

        var parametros = {

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

    // function insertar(parametros) {
    //     $.ajax({
    //         data: {
    //             parametros: parametros
    //         },
    //         url: '../controlador/estudiantesC.php?insertar=true',
    //         type: 'post',
    //         dataType: 'json',

    //         success: function(response) {
    //             if (response == 1) {
    //                 Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
    //                     //location.href = '../vista/inicio.php?mod=7&acc=estudiantes';
    //                 });
    //             } else if (response == -2) {
    //                 Swal.fire('', 'Cédula ya registrada.', 'warning');
    //             }
    //         }
    //     });
    // }
</script>
<div class="page-wrapper">
    <div class="page-content">

        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Facturación </div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
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
                            <!-- <div class="col-3 text-end">
                <a href="../vista/inicio.php?mod=7&acc=pacientes" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
              </div> -->
                        </div>

                        <hr>

                        <div class="content">
                            <!-- Content Header (Page header) -->
                            <br>

                            <section class="content">
                                <div class="container-fluid">

                                    <div class="row justify-content-center" id="btn_nuevo">

                                        <div class="col-auto">

                                            <div class="card">
                                                <div class="card-body bg-primary">
                                                    <!-- <form action="../vista/inicio.php?mod=7&acc=registrar_consulta_paciente" method="post"> -->

                                                    <!-- <input type="hidden" name="id_ficha" id="id_ficha">
                            <input type="hidden" name="id_paciente" id="id_paciente">
                            <input type="hidden" name="tipo_consulta" id="tipo_consulta" value="consulta"> -->

                                                    <button type="submit" class="btn btn-primary btn-lg m-4">Hora de entrada</button>
                                                    </form>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-auto">

                                            <div class="card">
                                                <div class="card-body bg-primary">
                                                    <!-- <form action="../vista/inicio.php?mod=7&acc=registrar_consulta_paciente" method="post"> -->

                                                    <!-- <input type="hidden" name="id_ficha" id="id_ficha">
                            <input type="hidden" name="id_paciente" id="id_paciente">
                            <input type="hidden" name="tipo_consulta" id="tipo_consulta" value="certificado"> -->

                                                    <button type="submit" class="btn btn-primary btn-lg m-4"> Hora de salida</button>
                                                    </form>
                                                </div>
                                            </div>

                                        </div>

                                        <!-- <div class="col-auto" id="pnl_segumiento_personal" style="display: none;">

                      <div class="card">
                        <div class="card-body bg-primary">
                          <button type="button" class="btn btn-primary btn-lg m-4" onclick="seguimiento()"> Seguimiento</button>
                        </div>
                      </div>

                    </div> -->
                                    </div>

                                    <br>

                                    <div>
                                        <form action="" class="form p-3">
                                            <div class="py-3">
                                                <label for="txt_pasantes">Observacion Pasantes</label>
                                                <input type="text" class="form-control" name="txt_pasantes" id="txt_pasantes">
                                            </div>
                                            <div class="py-3">
                                                <label for="txt_tutor">Observacion Tutor</label>
                                                <input type="text" class="form-control" name="txt_tutor" id="txt_tutor">
                                            </div>
                                        </form>
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

<!-- Observaci+on pasantes y observación tutor -->
