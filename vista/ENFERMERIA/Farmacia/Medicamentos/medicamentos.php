<script type="text/javascript">
    
    $(document).ready(function() {
        consultar_datos();
    });

    function consultar_datos(id = '') {
        var medicamentos = '';
  
        $.ajax({
            data: {
                id: id
            },
            url: '<?php echo $url_general ?>/controlador/medicamentosC.php?listar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                // console.log(response);   
                $.each(response, function(i, item) {
                    console.log(item);
                    medicamentos +=
                        '<tr>' +
                        '<td>' + 'COD - ' + item.sa_med_id + '</td>' +
                        '<td><a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=registrar_medicamentos&id=' + item.sa_med_id + '"><u>' + item.sa_med_nombre + '</u></a></td>' +
                        '<td></td>' +
                        '</tr>';
                });

                $('#tbl_datos').html(medicamentos);
            }
        });
    }

    function buscar(buscar) {
        var medicamentos = '';

        $.ajax({
            data: {
                buscar: buscar
            },
            url: '<?= $url_general ?>/controlador/medicamentosC.php?buscar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                // console.log(response);   
                $.each(response, function(i, item) {
                    console.log(item);
                    medicamentos +=
                        '<tr>' +
                        '<td>' + 'COD - ' + item.sa_med_id + '</td>' +
                        '<td><a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=registrar_medicamentos&id=' + item.sa_med_id + '"><u>' + item.sa_med_nombre + '</u></a></td>' +
                        '<td> </td>' +
                        '</tr>';
                });
                $('#tbl_datos').html(medicamentos);
            }
        });
    }

    function limpiar() {
        $('#codigo').val('');
        $('#descripcion').val('');
        $('#id').val('');
        $('#titulo').text('Nueva Medicamentos');
        $('#op').text('Guardar');
    }
    
</script>

<div class="page-wrapper">
    <div class="page-content">

        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Enfermería  </div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Medicamentos</li>
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
                            <h5 class="mb-0 text-primary">Medicamentos</h5>
                        </div>
                        <hr>

                        <div class="content">
                            <!-- Content Header (Page header) -->
                            <br>

                            <section class="content">
                                <div class="container-fluid">
                             
                                    <div class="row">
                                        <div class="col-sm-12" id="btn_nuevo">
                                            <a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=registrar_medicamentos" class="btn btn-success btn-sm"><i class="bx bx-plus"></i> Nuevo</a>
                                            <a href="#" class="btn btn-outline-secondary btn-sm" id="excel_medicamentos" title="Informe en excel del total de Secciones"><i class="bx bx-file"></i> Total Secciones</a>
                                        </div>

                                    </div>

                                    <div>
                                        <div class="col-sm-8 pt-3">
                                            <input type="" name="" id="txt_buscar" onkeyup="buscar($('#txt_buscar').val())" class="form-control form-control-sm" placeholder="Buscar Medicamentos">
                                        </div>
                                    </div>
                                    <br>

                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Codigo</th>
                                                    <th>Medicamentos</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbl_datos">

                                            </tbody>
                                        </table>
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



