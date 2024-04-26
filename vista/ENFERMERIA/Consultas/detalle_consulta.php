<?php

$id_consulta = '';

if (isset($_GET['id_consulta'])) {
    $id_consulta = $_GET['id_consulta'];
}

$tipo_consulta = '';

if (isset($_GET['tipo_consulta'])) {
    $tipo_consulta = $_GET['tipo_consulta'];
}

$id_paciente = '';

if (isset($_GET['id_paciente'])) {
    $id_paciente = $_GET['id_paciente'];
}

$btn_regresar = '';
if (isset($_GET['btn_regresar'])) {

    $btn_regresar_temp = $_GET['btn_regresar'];

    $btn_regresar = $_GET['btn_regresar'];

    if ($btn_regresar == 'admin') {
        $btn_regresar = '../vista/inicio.php?mod=7&acc=consultas_pacientes&pac_id=' . $id_paciente;
    } else if ($btn_regresar == 'represententes') {
        $btn_regresar = '../vista/inicio.php?mod=7&acc=index';
    } else if ($btn_regresar == 'docentes') {
        $btn_regresar = '../vista/inicio.php?acc=historial_salud_estudiantil';
    } else if ($btn_regresar == 'consultas_m') {
        $btn_regresar = '../vista/inicio.php?acc=consultas';
    } else if ($btn_regresar == 'represententes_consulta') {
        $id_estudiante = $_GET['id_estudiante'] ?? '';
        $btn_regresar = '../vista/inicio.php?mod=7&acc=perfil_estudiante_salud&id_estudiante='.$id_estudiante;
    } else {
        $btn_regresar = '../vista/inicio.php?mod=7&acc=index';
    }
}

?>

<script type="text/javascript">
    $(document).ready(function() {
        var id_consulta = '<?php echo $id_consulta; ?>';
        ver_pdf_consulta(id_consulta);
        datos_col_consulta(id_consulta);

        //var id_paciente = '<?php echo $id_paciente; ?>';

    });

    function ver_pdf_consulta(id_consulta) {
        $('#ifr_pdf_consulta').prop('src', '../controlador/consultasC.php?pdf_consulta=true&id_consulta=' + id_consulta);
        $('#ifr_pdf_receta').prop('src', '../controlador/consultasC.php?pdf_recetario=true&id_consulta=' + id_consulta);
        $('#ifr_pdf_notificacion').prop('src', '../controlador/consultasC.php?pdf_notificacion=true&id_consulta=' + id_consulta);
    }

    <?php if ($btn_regresar_temp == 'admin' || $btn_regresar_temp == 'consultas_m') { ?>

        function datos_col_consulta(id_consulta) {
            $.ajax({
                data: {
                    id: id_consulta
                },
                url: '../controlador/consultasC.php?listar_solo_consulta=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    //console.log(response);
                    $('#sa_conp_observaciones').val(response[0].sa_conp_observaciones);
                }
            });
        }

        function editar_insertar() {
            var sa_conp_observaciones = $('#sa_conp_observaciones').val();
            var sa_conp_id = $('#sa_conp_id').val();
            // Crear objeto de parámetros
            var parametros = {
                'sa_conp_id': sa_conp_id,
                'sa_conp_observaciones': sa_conp_observaciones,
            };

            insertar(parametros);

        }

        function insertar(parametros) {
            $.ajax({
                data: {
                    parametros: parametros
                },
                url: '../controlador/consultasC.php?observacion=true',
                type: 'post',
                dataType: 'json',

                success: function(response) {
                    //console.log(response);

                    if (response == 1) {
                        Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {


                            <?php if ($btn_regresar_temp == 'consultas_m') { ?>
                                location.href = '../vista/inicio.php?mod=7&acc=consultas';
                            <?php } else if ($btn_regresar_temp == 'admin') { ?>
                                location.href = '../vista/inicio.php?mod=7&acc=consultas_pacientes&pac_id=<?= $id_paciente; ?>';
                            <?php  } else { ?>
                                location.href = '../vista/inicio.php?mod=7&acc=index';
                            <?php } ?>
                        });
                    } else if (response == -2) {
                        Swal.fire('', 'Código ya registrado', 'success');
                    }
                    //console.log(response);
                }
            });
        }

    <?php } ?>
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
                            Formulario de Consulta
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">

            <div class="col-12">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                            </div>
                            <h5 class="mb-0 text-primary">Formulario de Consulta</h5>

                            <?php if ($btn_regresar != '') { ?>
                                <div class="row m-2">
                                    <div class="col-sm-12">
                                        <a href="<?= $btn_regresar ?>" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                    </div>
                                </div>
                            <?php } ?>

                        </div>

                        <hr>

                        <div class="content">
                            <!-- Content Header (Page header) -->
                            <?php if ($btn_regresar_temp == 'admin' || $btn_regresar_temp == 'consultas_m') { ?>
                                <input type="hidden" id="sa_conp_id" name="sa_conp_id" value="<?= $id_consulta; ?>">
                                <div class="row pt-2">
                                    <div class="col-md-12">
                                        <label for="" class="form-label">Observaciones <label style="color: red;">*</label> </label>
                                        <textarea name="sa_conp_observaciones" id="sa_conp_observaciones" cols="30" rows="2" class="form-control" placeholder="Motivo de la consulta"></textarea>
                                    </div>
                                </div>

                                <div class="modal-footer pt-2" id="seccion_boton_consulta">
                                    <button class="btn btn-success btn-sm px-2 m-1" onclick="editar_insertar()" type="button"><i class="bx bx-save"></i> Guardar</button>
                                </div>
                            <?php } ?>

                            <div class="col">
                                <h6 class="mb-0 text-uppercase">Documentos</h6>
                                <hr />


                                <ul class="nav nav-tabs nav-success" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#tab_hoja" role="tab" aria-selected="true">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon"><i class='bx bx-receipt font-18 me-1'></i></i>
                                                </div>
                                                <div class="tab-title">Notificación</div>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-bs-toggle="tab" href="#tab_formulario" role="tab" aria-selected="false">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon"><i class='bx bx-file font-18 me-1'></i>
                                                </div>
                                                <div class="tab-title">Formulario</div>
                                            </div>
                                        </a>
                                    </li>
                                    <?php if ($tipo_consulta == 'consulta') { ?>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" data-bs-toggle="tab" href="#tab_recetario" role="tab" aria-selected="false">
                                                <div class="d-flex align-items-center">
                                                    <div class="tab-icon"><i class='bx bxs-capsule font-18 me-1'></i>
                                                    </div>
                                                    <div class="tab-title">Recetario</div>
                                                </div>
                                            </a>
                                        </li>
                                    <?php } ?>

                                </ul>
                                <div class="tab-content py-3">
                                    <div class="tab-pane fade show active" id="tab_hoja" role="tabpanel">

                                        <section class="content">
                                            <div class="container-fluid">

                                                <p>Abrir solo <a href="../controlador/consultasC.php?pdf_notificacion=true&id_consulta=<?= $id_consulta; ?>" TARGET="_BLANK">PDF</a></p>

                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <div class="panel">
                                                        <div class="embed-responsive embed-responsive-4by3" style="margin-top: 30px">
                                                            <iframe class="embed-responsive-item" id="ifr_pdf_notificacion" width="90%" height="1000" src="">



                                                            </iframe>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- /.container-fluid -->
                                        </section>
                                    </div>
                                    <div class="tab-pane fade" id="tab_formulario" role="tabpanel">

                                        <section class="content">
                                            <div class="container-fluid">

                                                <p>Abrir solo <a href="../controlador/consultasC.php?pdf_consulta=true&id_consulta=<?= $id_consulta; ?>" TARGET="_BLANK">PDF</a></p>

                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <div class="panel">
                                                        <div class="embed-responsive embed-responsive-4by3" style="margin-top: 30px">
                                                            <iframe class="embed-responsive-item" id="ifr_pdf_consulta" width="90%" height="1000" src="">



                                                            </iframe>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- /.container-fluid -->
                                        </section>
                                    </div>
                                    <?php if ($tipo_consulta == 'consulta') { ?>
                                        <div class="tab-pane fade" id="tab_recetario" role="tabpanel">

                                            <section class="content">
                                                <div class="container-fluid">

                                                    <p>Abrir solo <a href="../controlador/consultasC.php?pdf_recetario=true&id_consulta=<?= $id_consulta; ?>" TARGET="_BLANK">PDF</a></p>

                                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                                        <div class="panel">
                                                            <div class="embed-responsive embed-responsive-4by3" style="margin-top: 30px">
                                                                <iframe class="embed-responsive-item" id="ifr_pdf_receta" width="90%" height="1000" src="">



                                                                </iframe>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!-- /.container-fluid -->
                                            </section>
                                        </div>
                                    <?php } ?>

                                </div>


                            </div>


                            <!-- /.content -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>