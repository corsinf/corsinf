<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$_id = '';

if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}

?>

<script type="text/javascript">
    $(document).ready(function() {
        ver_pdf('<?= $_id ?>');
        ver_pdf_1('<?= $_id ?>');
    });

    //Para pdf subido
    function ver_pdf(_id = '') {
        $('#ifr_pdf_student_consent').prop('src', '../REPOSITORIO/SALUD_EDUCATIVO/1042/123456/GPA/gpa_1.pdf?' + Math.random());
    }

    function ver_pdf_1(_id = '') {
        $('#ifr_pdf_student_consent_1').prop('src', '../controlador/EDUCATIVO/herramienta_gpa_conversionC.php?gpa_pdf_conversion=true&id=' + _id);
    }

    function insertar_editar_gpa() {
        var form_data = new FormData(document.getElementById("form_gpa")); // Captura todos los campos y archivos

        var txt_id_gpa = $('#txt_gpa_id').val();

        // if ($('#txt_copia_archivo').val() === '' && txt_id_gpa != '') {
        //     var txt_copia_archivo = $('#txt_ruta_guardada_carta_recomendacion').val()
        //     $('#txt_copia_archivo').rules("remove", "required");
        // } else {
        //     var txt_copia_archivo = $('#txt_copia_archivo').val();
        //     $('#txt_copia_archivo').rules("add", {
        //         required: true
        //     });
        // }

        // console.log([...form_data]);
        // console.log([...form_data.keys()]);
        // console.log([...form_data.values()]);
        // return;

        if ($("#form_gpa")) {
            $.ajax({
                url: '../controlador/EDUCATIVO/herramienta_gpa_conversionC.php?insertar=true',
                type: 'post',
                data: form_data,
                contentType: false,
                processData: false,

                dataType: 'json',
                success: function(response) {
                    //console.log(response);
                    if (response == -1) {
                        Swal.fire({
                            title: '',
                            text: 'Algo extraño ha ocurrido, intente más tarde.',
                            icon: 'error',
                            allowOutsideClick: false,
                            showConfirmButton: true,
                            confirmButtonText: 'Cerrar'
                        });
                    } else if (response == -2) {
                        Swal.fire({
                            title: '',
                            text: 'Asegúrese de que el archivo subido sea un PDF.',
                            icon: 'error',
                            allowOutsideClick: false,
                            showConfirmButton: true,
                            confirmButtonText: 'Cerrar'
                        });
                    } else if (response == 1) {
                        Swal.fire('', 'Operación realizada con éxito.', 'success');
                        // <?php if (isset($_GET['id'])) { ?>
                        //     cargar_datos_gpa(<?= $id ?>);
                        // <?php } ?>
                        // limpiar_parametros_gpa();
                        // $('#modal_agregar_referencia_laboral').modal('hide');

                       
                    }
                }
            });
        }

        ver_pdf();
        ver_pdf_1();
    }
</script>

<div class="page-wrapper">
    <div class="page-content">

        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">INNOVERS</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Formulario
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
                            <h5 class="mb-0 text-primary">Formulario</h5>


                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=in_personas" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>




                        </div>

                        <hr>

                        <div class="content">
                            <!-- Content Header (Page header) -->

                            <div class="col">
                                <h6 class="mb-0 text-uppercase">Documentos</h6>
                                <hr />


                                <section class="content">
                                    <div class="container-fluid">

                                        <div class="row">
                                            <form id="form_gpa" enctype="multipart/form-data" method="post" style="width: inherit;">

                                                <div class="modal-body">
                                                    <input type="hidden" name="txt_referencias_laborales_id" id="txt_referencias_laborales_id">
                                                    <input type="hidden" name="txt_estudiante_cedula" id="txt_estudiante_cedula" value="123456">
                                                    <input type="hidden" name="txt_postulante_id" id="txt_postulante_id">


                                                    <div class="row mb-col">
                                                        <div class="col-md-12">
                                                            <label for="txt_copia_archivo" class="form-label form-label-sm">PDF GPA </label>
                                                            <input type="file" class="form-control form-control-sm" name="txt_copia_archivo" id="txt_copia_archivo" accept=".pdf">
                                                            <!-- <div class="pt-2"></div> -->
                                                            <input type="text" class="form-control form-control-sm" name="txt_ruta_guardada_carta_recomendacion" id="txt_ruta_guardada_carta_recomendacion" hidden>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal-footer d-flex justify-content-end">
                                                    <button type="button" class="btn btn-success btn-sm px-4 m-1" id="btn_guardar_referencia_laboral" onclick="insertar_editar_gpa();"><i class="bx bx-save"></i>Agregar</button>
                                                </div>
                                            </form>
                                        </div>

                                        <hr>

                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-6">
                                                <!-- <p>Abrir solo <a href="../controlador/EDUCATIVO/herramienta_gpa_conversionC.php?gpa_pdf_conversion=true&id=<?= $_id; ?>" TARGET="_BLANK">PDF</a></p> -->
                                                <h3>Documento subido</h3>
                                                <div class="panel">
                                                    <div class="embed-responsive embed-responsive-4by3" style="margin-top: 30px">
                                                        <iframe class="embed-responsive-item" id="ifr_pdf_student_consent" width="90%" height="1000" src="">



                                                        </iframe>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-6 col-sm-6 col-md-6">
                                                <!-- <p>Abrir solo <a href="../controlador/EDUCATIVO/herramienta_gpa_conversionC.php?gpa_pdf_conversion=true&id=<?= $_id; ?>" TARGET="_BLANK">PDF</a></p> -->
                                                <h3>Documento Traducido</h3>
                                                <div class="panel">
                                                    <div class="embed-responsive embed-responsive-4by3" style="margin-top: 30px">
                                                        <iframe class="embed-responsive-item" id="ifr_pdf_student_consent_1" width="90%" height="1000" src="">



                                                        </iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div><!-- /.container-fluid -->
                                </section>

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