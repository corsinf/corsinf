<?php

$id = $_SESSION['INICIO']['ID_USUARIO'] ?? '';
$id_tutor = '';
if ($id != null && $id != '') {
    $id_tutor = $id;
}

?>

<script src="../js/ENFERMERIA/operaciones_generales.js"></script>
<script src="../js/ENFERMERIA/pacientes.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        consultar_datos_seccion();
        carga_tabla();
    });

    function carga_tabla() {
        var id_tutor = '<?php echo $id_tutor; ?>';

        tbl_doc_par = $('#tbl_doc_par').DataTable({
            language: {
                url: '../assets/plugins/datatable/spanish.json'
            },
            responsive: true,
            ajax: {
                url: '../controlador/SALUD_INTEGRAL/tutores_paraleloC.php',
                data: function(d) {
                    d.listar = true;
                    d.id_tutor = id_tutor;
                },
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    render: function(data, type, item) {
                        return item.sa_sec_nombre + ' - ' + item.sa_gra_nombre + ' - ' + item.sa_par_nombre;
                    }
                }, {
                    data: 'sa_par_id',
                    visible: false,
                }

            ],
        });
    }

    //Para cargar los datos en el select
    function consultar_datos_seccion(id = '') {
        var seccion = '';

        //console.log(id_seccion);
        seccion = '<option selected disabled>-- Seleccione --</option>'
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/SALUD_INTEGRAL/seccionC.php?listar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                //console.log(response);

                $.each(response, function(i, item) {
                    //console.log(item);
                    seccion += '<option value="' + item.sa_sec_id + '">' + item.sa_sec_nombre + '</option>';
                });

                $('#sa_id_seccion').html(seccion);
            }
        });
    }

    function consultar_datos_seccion_grado(id_grado = '', id_seccion = '') {
        /*///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            Para Buscar el Grado con la Seccion

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/

        if (id_seccion == '') {
            id_seccion = $("#sa_id_seccion").val();
        }

        if (id_grado == '') {
            id_grado = $("#sa_id_grado").val();
        }

        var grado = '';
        grado = '<option selected disabled>-- Seleccione --</option>'
        $.ajax({
            data: {
                "id_seccion": id_seccion
            },
            url: '../controlador/SALUD_INTEGRAL/paraleloC.php?listar_seccion_grado=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                // console.log(response);   
                $.each(response, function(i, item) {
                    //console.log(item);

                    if (id_grado == item.sa_gra_id) {
                        // Marca la opción correspondiente con el atributo 'selected'
                        grado += '<option value="' + item.sa_gra_id + '" selected>' + item.sa_gra_nombre + '</option>';
                    } else {
                        grado += '<option value="' + item.sa_gra_id + '">' + item.sa_gra_nombre + '</option>';
                    }

                });

                $('#sa_id_grado').html(grado);
            }
        });



    }

    function consultar_datos_grado_paralelo(id_grado = '', id_paralelo = '') {
        var id_tutor = '<?php echo $id_tutor; ?>';

        if (id_paralelo == '') {
            id_paralelo = $("#sa_par_id").val();
        }

        if (id_grado == '') {
            id_grado = $("#sa_id_grado").val();
        }

        var grado = '';
        var paralelo = '<option selected disabled>-- Seleccione --</option>';

        // Realiza la llamada AJAX para obtener los datos de tutores_paralelos donde solo estan los disponibles
        $.ajax({
            data: {
                "id_grado": id_grado
            },
            url: '../controlador/SALUD_INTEGRAL/tutores_paraleloC.php?listar_paralelos=true',
            type: 'post',
            dataType: 'json',

            success: function(paralelo_response) {

                $.each(paralelo_response, function(i, item) {
                    paralelo += '<option value="' + item.sa_par_id + '">' + item.sa_par_nombre + '</option>';
                });

                console.log(paralelo_response);
                $('#sa_par_id').html(paralelo);
            }
        });
    }

    function insertar() {
        var ac_tutor_id = '<?php echo $id_tutor; ?>';
        var ac_paralelo_id = $('#sa_par_id').val();

        //alert(ac_par_id + ' ' + ac_doc_id);

        var parametros = {
            'ac_tutor_paralelo_id': '',
            'ac_tutor_id': ac_tutor_id,
            'ac_paralelo_id': ac_paralelo_id,
        }

        $.ajax({
            url: '../controlador/SALUD_INTEGRAL/tutores_paraleloC.php?insertar=true',
            data: {
                parametros: parametros
            },
            type: 'post',
            dataType: 'json',
            success: function(response) {
                //console.log(response)
                if(response == 1){
                    Swal.fire('', 'Curso Asignado.', 'success');
                }else if(response == -2){
                    Swal.fire('', 'Curso asiganado a otro tutor', 'error');
                }
               
            }
        });

        tbl_doc_par.ajax.reload(); // Recargar el DataTable

        //$('#modal_paralelo').modal('hide');
        //$('#sa_id_seccion').val('');
        //$('#sa_id_grado').val('');
        //$('#sa_par_id').val('');
        consultar_datos_grado_paralelo();

    }
</script>

<form id="form_enviar" action="../vista/inicio.php?mod=7&acc=ficha_medica_pacientes" method="post" style="display: none;">
    <input type="hidden" id="sa_pac_id" name="sa_pac_id" value="">
</form>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Accesos</div>

            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Áreas de instrucción que supervisa.
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <!-- <p>
            <?php
            // print_r($_SESSION['INICIO']);
            // die();
            ?>
        </p> -->

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">
                        <div class="card-title d-flex align-items-center">

                            <h5 class="mb-0 text-primary"></h5>

                            <div class="row mx-0">
                                <div class="col-sm-12" id="btn_nuevo">

                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal_paralelo"><i class="bx bx-plus"></i> Nuevo Curso</button>

                                </div>
                            </div>
                        </div>


                        <section class="content pt-2">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped responsive " id="tbl_doc_par" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Sección - Grado - Curso</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- /.container-fluid -->
                        </section>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>


<div class="modal" id="modal_paralelo" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12">
                        <label for="" class="form-label">Sección <label style="color: red;">*</label> </label>
                        <select class="form-select form-select-sm" id="sa_id_seccion" name="sa_id_seccion" onchange="consultar_datos_seccion_grado()">

                        </select>
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-md-12">
                        <label for="" class="form-label">Grado <label style="color: red;">*</label> </label>
                        <select class="form-select form-select-sm" id="sa_id_grado" name="sa_id_grado" onchange="consultar_datos_grado_paralelo();">
                            <option selected disabled>-- Seleccione --</option>
                        </select>
                    </div>

                </div>

                <div class="row pt-3">
                    <div class="col-md-12">
                        <label for="" class="form-label">Paralelo <label style="color: red;">*</label> </label>
                        <select class="form-select form-select-sm" id="sa_par_id" name="sa_par_id">
                            <option selected disabled>-- Seleccione --</option>
                        </select>
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-success btn-sm" onclick="insertar()"><i class="bx bx-save"></i> Agregar</button>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>