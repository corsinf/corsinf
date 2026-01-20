<script src="../js/PASANTES/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        //cargarDatos();


        $('#tbl_pasante').DataTable({
            language: {
                url: '../assets/plugins/datatable/spanish.json'
            },
            responsive: true,
            ajax: {
                url: '../controlador/PASANTES/asistencias_pasantesC.php?listar=true',
                dataSrc: ''
            },
            dom: '<"top"Bfr>t<"bottom"lip>',
            buttons: [{
                    extend: 'excel',
                    text: '<i class="bx bxs-file-pdf me-0"></i> Exportar a Excel',
                    title: 'Título del archivo Excel',
                    filename: 'nombre_archivo_excel'
                },
                {
                    extend: 'pdf',
                    text: '<i class="bx bxs-spreadsheet me-0"></i> Exportar a PDF',
                    title: 'Título del archivo PDF',
                    filename: 'nombre_archivo_PDF'
                }
            ],
             columns: [{
                    data: null,
                    render: function(data, type, item) {
                        return fecha_formateada(item.pas_fecha_creacion);
                    }
                },
                {
                    data: 'pas_nombre',
                    render: function(data, type, item) {
                        if (item.pas_hora_salida != null && item.pas_hora_salida != '') {
                            return `<button type="button" class="btn btn-primary badge" onclick="abrir_modal(${item.pas_id});">${data}</button>`;
                        } else {
                            return `<a href="../vista/inicio.php?mod=1010&acc=registro_pasantes_fin&id_asistencia=${item.pas_id}">${data}</a>`;
                        }

                    }
                },
                {
                    data: 'pas_hora_llegada',
                    render: function(data, type, item) {
                        return obtener_hora_formateada_arr(item.pas_hora_llegada);
                    }
                },
                {
                    data: 'pas_hora_salida',
                    render: function(data, type, item) {
                        if (item.pas_hora_salida != null && item.pas_hora_salida != '') {
                            return obtener_hora_formateada_arr(item.pas_hora_salida);
                        } else {
                            return item.pas_hora_salida
                        }
                    }
                },
                {
                    data: 'pas_horas_total',
                    render: function(data, type, item) {
                        return parseFloat(data).toFixed(2);
                    }

                }
            ],
            order: [
                [0, 'DESC']
            ],
            initComplete: function() {
                // Mover los botones al contenedor personalizado
                $('#contenedor_botones').append($('.dt-buttons'));
            }
        });
    });

    function abrir_modal(id) {

        $.ajax({
            url: '../controlador/PASANTES/asistencias_pasantesC.php?listar=true',
            type: 'post',
            data: {
                id: id,
                modal: 1,
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);

                $('#txt_obs_pasantes').val(response[0]['pas_observacion_pasante']);
                $('#txt_obs_tutor').val(response[0]['pas_observacion_tutor']);
                $('#txt_id_registro').val(response[0]['pas_id']);

            },
            //error: function(jqXHR, textStatus, errorThrown) {
            // Manejo de errores
            //console.error('Error al cargar los configs:', textStatus, errorThrown);
            //$('#pnl_config_general').append('<p>Error al cargar las configuraciones. Por favor, inténtalo de nuevo más tarde.</p>');
            //}
        });

        $('#modal_pasantes').modal('show');
    }

    function obs_tutor() {
        var txt_id_registro = $('#txt_id_registro').val();
        var txt_obs_tutor = $('#txt_obs_tutor').val();
        var txt_obs_tutor = $('#txt_obs_tutor').val();


        var parametros = {
            'txt_id_registro': txt_id_registro,
            'txt_obs_tutor': txt_obs_tutor,
        };

        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/PASANTES/asistencias_pasantesC.php?editar_tutor=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=7&acc=asistencias_pasantes';
                    });
                } else {
                    Swal.fire('', 'Error', 'error');
                }
            }
        });
    }
</script>

<div class="page-wrapper">
    <div class="page-content">

        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Pasantias</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            CORSINF - Pasantes
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
                                    <h5 class="mb-0 text-primary">Pasantes</h5>
                                    <div class="row mx-1">
                                        <div class="col-sm-12" id="btn_nuevo">
                                            <a href="../vista/inicio.php?mod=7&acc=registro_pasantes" class="btn btn-success btn-sm"><i class="bx bx-plus"></i> Registro</a>
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
                                <div class="table-responsive">
                                    <table class="table table-striped responsive" id="tbl_pasante" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Fecha Creación</th>
                                                <th>Nombre Pasante</th>
                                                <th>Hora de llegada</th>
                                                <th>Hora de salida</th>
                                                <th>Total de Horas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Datos se llenan mediante AJAX -->
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

<div class="modal" id="modal_pasantes" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div>
                    <input type="hidden" name="txt_id_registro" id="txt_id_registro">
                    <div class="row pt-3">
                        <div class="col-12">
                            <label for="txt_obs_pasantes">Observacion Pasantes</label>
                            <textarea class="form-control form-control-sm" name="txt_obs_pasantes" id="txt_obs_pasantes" readonly></textarea>

                        </div>
                    </div>

                    <?php
                    if ($_SESSION['INICIO']['ID_USUARIO'] == 1) {
                    ?>
                        <div class="row pt-3">
                            <div class="col-12">
                                <label for="txt_obs_tutor">Observacion Tutor</label>
                                <textarea class="form-control form-control-sm" name="txt_obs_tutor" id="txt_obs_tutor"></textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end pt-3">
                            <button type="button" class="btn btn-success btn-sm px-4" onclick="obs_tutor();"><i class="bx bx-save"></i> Guardar</button>
                        </div>
                    <?php  } else { ?>
                        <div class="row pt-3">
                            <div class="col-12">
                                <label for="txt_obs_tutor">Observacion Tutor</label>
                                <textarea class="form-control form-control-sm" name="txt_obs_tutor" id="txt_obs_tutor" readonly></textarea>
                            </div>
                        </div>
                    <?php  }  ?>
                </div>
            </div>
        </div>
    </div>
</div>