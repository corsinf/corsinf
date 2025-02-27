<?php

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
    }
}

?>

<script type="text/javascript">
    $(document).ready(function() {
        var sa_pac_id = '<?= $id_paciente ?>';
        cargar_datos_paciente(sa_pac_id)
    });

    function cargar_datos_paciente(sa_pac_id) {
        //alert('Estudiante')
        $.ajax({
            data: {
                sa_pac_id: sa_pac_id

            },
            url: '../controlador/SALUD_INTEGRAL/pacientesC.php?obtener_info_paciente=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                //Para el encabezado
                nombres = response[0].sa_pac_temp_primer_nombre + ' ' + response[0].sa_pac_temp_segundo_nombre;
                apellidos = response[0].sa_pac_temp_primer_apellido + ' ' + response[0].sa_pac_temp_segundo_apellido;
                tabla = response[0].sa_pac_tabla;

                $('#title_paciente').html(apellidos + " " + nombres);
            }
        });
    }

    function insertar() {

        var pac_id = '<?= $id_paciente ?>';
        var sa_sep_observacion = $('#sa_sep_observacion').val();
        // Crear objeto de parámetros
        var parametros = {
            'pac_id': pac_id,
            'sa_sep_observacion': sa_sep_observacion,
        };

        if (sa_sep_observacion != '') {
            $.ajax({
                data: {
                    parametros: parametros
                },
                url: '../controlador/SALUD_INTEGRAL/seguimiento_personalC.php?insertar=true',
                type: 'post',
                dataType: 'json',

                success: function(response) {
                    //console.log(response);

                    if (response == 1) {
                        Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                            location.href = '<?= $btn_regresar ?>';
                        });
                    } else if (response == -2) {
                        Swal.fire('', 'Error', 'error');
                    }
                }
            });
        } else {
            Swal.fire('', 'Campo vacío.', 'error');
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
                            Seguimiento
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
                            <h5 class="mb-0 text-primary">Seguimiento - <label id="title_paciente" class="text-success"></label></h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="<?= $btn_regresar ?>" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>

                        </div>

                        <hr>

                        <div class="content" id="pnl_docentes_seg">
                            <!-- Content Header (Page header) -->
                            <div class="row pt-2">
                                <div class="col-md-12">
                                    <label for="" class="form-label">Observaciones <label style="color: red;">*</label> </label>
                                    <textarea name="sa_conp_observaciones" id="sa_sep_observacion" cols="30" rows="2" class="form-control" placeholder="Observaciones"></textarea>
                                </div>
                            </div>

                            <div class="modal-footer pt-2">
                                <button class="btn btn-success btn-sm px-2 m-1" onclick="insertar()" type="button"><i class="bx bx-save"></i> Guardar</button>
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