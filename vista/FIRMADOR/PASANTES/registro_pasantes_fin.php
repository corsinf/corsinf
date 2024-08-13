<?php
$registro_id = '';

if (!empty($_GET['id_asistencia'])) {
    $registro_id = $_GET['id_asistencia'];
}

?>

<script>
    $(document).ready(function() {
        cargarDatos();
    });

    function cargarDatos() {
        $.ajax({
            url: '../controlador/PASANTES/asistencias_pasantesC.php?listar=true',
            type: 'post',
            data: {
                id: <?= $registro_id ?>
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);

                $('#txt_obs_pasantes').val(response[0]['pas_observacion_pasante']);
                $('#txt_obs_tutor').val(response[0]['pas_observacion_tutor']);
                //$('#txt_registro_id').val(response[0]['pas_id']);

            },
            //error: function(jqXHR, textStatus, errorThrown) {
            // Manejo de errores
            //console.error('Error al cargar los configs:', textStatus, errorThrown);
            //$('#pnl_config_general').append('<p>Error al cargar las configuraciones. Por favor, inténtalo de nuevo más tarde.</p>');
            //}
        });
    }


    function insertar_fin() { //fin
        var txt_obs_pasantes = $('#txt_obs_pasantes').val();
        var txt_obs_tutor = $('#txt_obs_tutor').val();
        var registro_id = $('#txt_registro_id').val();

        var parametros = {
            'txt_obs_pasantes': txt_obs_pasantes,
            'txt_obs_tutor': txt_obs_tutor,
            'registro_id': registro_id
        };

        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/PASANTES/asistencias_pasantesC.php?editar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=7&acc=asistencias_pasantes';
                    });
                } else if (response == -2) {
                    Swal.fire('', 'Cédula ya registrada.', 'warning');
                }
            }
        });
    }
</script>

<input type="hidden" name="txt_registro_id" id="txt_registro_id" value="<?= $registro_id ?>">

<div class="page-wrapper">
    <div class="page-content">

        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Facturación</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Registro Hora de Salida - Pasantes
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
                                    <div><i class="bx bxs-user me-1 font-22 text-primary"></i></div>
                                    <h5 class="mb-0 text-primary">Registro Hora de Salida</h5>
                                    <div class="row mx-1">
                                        <div class="col-sm-12" id="btn_nuevo">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 text-end">
                                <div id="contenedor_botones"></div>
                            </div>
                        </div>
                        <hr>
                        <section class="content pt-4">
                            <div class="container-fluid">
                                <section class="content">
                                    <div class="container-fluid">

                                        <div class="row justify-content-center" id="btn_nuevo">

                                            <div class="col-auto">
                                                <button type="button" class="btn btn-primary btn-lg m-4 p-5" onclick="insertar_fin();">Hora de salida</button>
                                            </div>

                                        </div>

                                        <br>

                                        <div>
                                            <div class="row pt-3">
                                                <div class="col-12">
                                                    <label for="txt_obs_pasantes">Observacion Pasantes</label>
                                                    <textarea class="form-control form-control-sm" name="txt_obs_pasantes" id="txt_obs_pasantes"></textarea>

                                                </div>
                                            </div>

                                        </div>

                                    </div><!-- /.container-fluid -->
                                </section>
                            </div><!-- /.container-fluid -->
                        </section>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>