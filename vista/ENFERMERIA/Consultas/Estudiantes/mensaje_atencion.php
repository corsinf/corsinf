<?php

$id_estudiante = '';
$id_representante = '';
$id_ficha = '';



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


        if (id_consulta != '') {
            consultar_datos(id_consulta);
        }

    });

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
        var segundos = fechaActual.getSeconds();

        // Formatear la hora como una cadena
        var horaFormateada = (hora < 10 ? '0' : '') + hora + ':' +
            (minutos < 10 ? '0' : '') + minutos;

        return horaFormateada;
    }

    function consultar_datos(id_consulta = '') {
        var ficha_estudiante = '';
        var cont = 1;
        var mensaje = '';
        $.ajax({
            data: {
                id: id_consulta
            },
            url: '<?php echo $url_general ?>/controlador/consultasC.php?listar_solo_consulta=true',
            type: 'post',
            dataType: 'json',
            //Para el id representante tomar los datos con los de session
            success: function(response) {

                $('#txt_to').val(response[0].sa_conp_correo);
                $('#txt_subjet').val('Atención - Departamento Médico');

                mensaje += 'Estimado representante\n\n';

                mensaje += 'Me comunico con usted en calidad para informarle sobre el diagnóstico médico reciente de ' + response[0].sa_conp_nombres + '\n\n';
                mensaje += 'Diagnóstico: ' + response[0].sa_conp_diagnostico_1 + '\n';
                mensaje += 'Hora de atención: ' + obtener_hora_formateada(response[0].sa_conp_desde_hora.date) + '\n';
                mensaje += 'Motivo: ' + response[0].sa_conp_tipo_consulta + '\n\n';

                $('#mensaje').val(mensaje);
            }
        });
    }

    function enviar_correo() {
        var id_estudiante = '<?php echo $id_estudiante; ?>';
        var id_representante = '<?php echo $id_representante; ?>';
        var id_ficha = '<?php echo $id_ficha; ?>';
        
        var tbl = $('#div_mensaje').html();
        var men = $('#mensaje').val();
        var to = $('#txt_to').val();
        var subjet = $('#txt_subjet').val();

        parametros = {
            'to': to,
            'sub': subjet,
            'men': men,
        }

        $.ajax({
            data: {
                parametros: parametros
            },
            url: '<?php echo $url_general ?>/controlador/consultasC.php?enviar_correo=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == true) {
                    Swal.fire('Email enviado', '', 'success').then(function() {
                        //$('#rbl_notificacion').prop('checked', true);
                        //$('#notificacion_').show();
                        location.href = '<?= $url_general ?>/vista/inicio.php?mod=7&acc=consulta_estudiante&id_estudiante=' + id_estudiante + '&id_representante=' + id_representante + '&id_ficha=' + id_ficha;
                    });
                } else {
                    Swal.fire('No se pudo Enviar el correo', '', 'error');
                }
            }
        });
    }
</script>

<div class="page-wrapper">
    <div class="page-content">

        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Enfermería </div>

            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Mensaje por consulta</li>
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
                            <h5 class="mb-0 text-primary">Mensaje por consulta</h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=consulta_estudiante&id_estudiante=<?= $id_estudiante ?>&id_representante=<?= $id_representante ?>&id_ficha=<?= $id_ficha ?>" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="content">
                            <!-- Content Header (Page header) -->
                            <br>

                            <section class="content">
                                <div class="container-fluid">
                                    <div class="row m-1">
                                        <div class="col-sm-12">
                                            <div style="display:none;" id="notificacion_">
                                                <label style="display:none;"><input type="checkbox" id="rbl_notificacion"> Se ha notificado correctamente</label>
                                            </div>
                                            <div class="card">
                                                <div class="card-header bg-dark text-white py-2 cursor-pointer">
                                                    <div class="d-flex align-items-center">
                                                        <div class="compose-mail-title"><b>Mensaje</b></div>
                                                    </div>
                                                </div>
                                                <div class="card-body">

                                                    <div class="email-form">
                                                        <div class="mb-1">
                                                            <input type="text" class="form-control form-control-sm" placeholder="Para:" id="txt_to">
                                                        </div>
                                                        <div class="mb-1">
                                                            <input type="text" class="form-control form-control-sm" placeholder="Ausnto:" id="txt_subjet">
                                                        </div>
                                                        <div class="mb-1">
                                                            <textarea class="form-control form-control-sm" placeholder="Mensaje" rows="10" cols="10" id="mensaje"></textarea>
                                                        </div>
                                                        <div class="mb-0">
                                                            <div class="d-flex align-items-center">
                                                                <div class="">
                                                                    <div class="btn-group">
                                                                        <button type="button" id="btn_enviar" class="btn btn-primary btn-sm" onclick="enviar_correo()"><i class="bx bx-send"></i>Enviar</button>

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
                        </div><!-- /.container-fluid -->
                        </section>
                        <!-- /.content -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>