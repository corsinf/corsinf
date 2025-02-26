<script type="text/javascript">
    $(document).ready(function() {
        lista_trabajos();
        autocoplet_estado();
    });

    function lista_trabajos() {
        // var parametros = {}
        $.ajax({
            // data:  {parametros:parametros},
            url: '../controlador/estado_trabajoC.php?trabajos=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                console.log(response);
                if (response) {
                    $('#tbl_trabajos').html(response.joyas);
                    $('#tbl_trabajos_ord').html(response.ordenes);
                    $('#num_joy').html(response.lineas);
                    $('#num_ord').html(response.lineas2);

                }
            }

        });
    }

    function autocoplet_estado(id = false) {
        $('#ddl_estado').select2({
            placeholder: 'Seleccione una bodega',
            width: '100%',
            ajax: {
                url: '../controlador/estado_trabajoC.php?estado_trabajo=true',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    // console.log(data);
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    }

    function guardar_observacion() {
        var parametros = {
            'estado': $('#ddl_estado').val(),
            'obser': $('#txt_obs').val(),
            'id': $('#txt_id').val(),
        }
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/estado_trabajoC.php?add_ob=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'observacion añadida.', 'success');
                    lista_trabajos();
                    observaciones($('#txt_id').val());
                    // $('#tbl_trabajos').html(response);
                }
            }

        });
    }

    function reporte_trabajo(id) {
        // var datos = $('#form_usuario_new').serialize();

        $('#informe_trabajo').modal('show');
        var url = '../controlador/estado_trabajoC.php?reporte=true&id=' + id;
        // window.open(url, '_blank');
        $('#informe_pdf').html("<iframe src=" + url + '#zoom=90' + " width='100%' height='500px' frameborder='0' allowfullscreen></iframe>");

    }

    function observaciones(id) {
        $('#trabajo_observaciones').modal('show');
        $('#txt_id').val(id);
        var parametros = {
            'id': $('#txt_id').val(),
        }
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/estado_trabajoC.php?lista_trabajos=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                $('#tbl_obse').html(response);

            }

        });
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Lista de trabajos</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Blank
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

                            <h5 class="mb-0 text-primary"></h5>
                        </div>


                        <section class="content">
                            <div class="container-fluid">
                                <div class="card">
                                    <div class="card-header p-2">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item" onclick="$('#txt_tipo').val('C')">
                                                <a class="nav-link active" href="#opcion1" data-bs-toggle="tab">
                                                    Trabajo en joyas <span class="badge bg-danger" id="num_joy"></span>
                                                </a>
                                            </li>
                                            <li class="nav-item" onclick="$('#txt_tipo').val('P')">
                                                <a class="nav-link" href="#opcion2" data-bs-toggle="tab">
                                                    Ordenes de trabajo <span class="badge bg-danger" id="num_ord"></span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div><!-- /.card-header -->

                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class="tab-pane fade show active" id="opcion1">
                                                <div class="table-responsive">
                                                    <table class="table table-hover">
                                                        <thead class="table-dark">
                                                            <tr>
                                                                <th></th>
                                                                <th>Fecha de ingreso</th>
                                                                <th>Nombre de cliente</th>
                                                                <th>Joya</th>
                                                                <th>Trabajo a realizar</th>
                                                                <th>Etapa</th>
                                                                <th>Estado</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="tbl_trabajos"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <!-- /.tab-pane -->

                                            <div class="tab-pane fade" id="opcion2">
                                                <div class="table-responsive">
                                                    <table class="table table-hover">
                                                        <thead class="table-dark">
                                                            <tr>
                                                                <th></th>
                                                                <th>Fecha de ingreso</th>
                                                                <th>Encargado</th>
                                                                <th>Código</th>
                                                                <th>Observación</th>
                                                                <th>Etapa</th>
                                                                <th>Tipo</th>
                                                                <th>Estado</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="tbl_trabajos_ord"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.tab-content -->
                                    </div><!-- /.card-body -->
                                </div><!-- /.card -->
                            </div><!-- /.container-fluid -->
                        </section>

                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>


<div class="modal" id="modal_blank" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

                <div class="row">
                    <div class="col-12">
                        <label for="">Tipo de <label class="text-danger">*</label></label>
                        <select name="" id="" class="form-select form-select-sm" onchange="">
                            <option value="">Seleccione el </option>
                        </select>
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12">
                        <label for="">Blank <label class="text-danger">*</label></label>
                        <select name="" id="" class="form-select form-select-sm">
                            <option value="">Seleccione el </option>
                        </select>
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-success btn-sm" onclick=""><i class="bx bx-save"></i> Agregar</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>