<script type="text/javascript">
    $(document).ready(function() {
        consultar_datos();
    });

    function consultar_datos(id = '') {
        var grado = '';
        var id_tabla = 1;
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/gradoC.php?listar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                // console.log(response);   
                $.each(response, function(i, item) {
                    //console.log(item);
                    grado +=
                        '<tr>' +
                        '<td>' + 'COD - ' + item.sa_gra_id + '</td>' +
                        '<td>' + item.sa_sec_nombre + '</td>' +
                        '<td><a href="../vista/inicio.php?mod=7&acc=registrar_grado&id=' + item.sa_gra_id + '&id_seccion=' + item.sa_sec_id + '"><u>' + item.sa_gra_nombre + '</u></a></td>' +
                        '<td></td>' +
                        '</tr>';
                });

                $('#tbl_datos').html(grado);
            }
        });
    }

    function buscar(buscar) {
        var grado = '';

        $.ajax({
            data: {
                buscar: buscar
            },
            url: '../controlador/gradoC.php?buscar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                // console.log(response);   
                $.each(response, function(i, item) {
                    //console.log(item);
                    grado +=
                        '<tr>' +
                        '<td>' + 'COD - ' + item.sa_gra_id + '</td>' +
                        '<td>' + item.sa_sec_nombre + '</td>' +
                        '<td><a href="../vista/inicio.php?mod=7&acc=registrar_grado&id=' + item.sa_gra_id + '&id_seccion=' + item.sa_sec_id + '"><u>' + item.sa_gra_nombre + '</u></a></td>' +
                        '<td> </td>' +
                        '</tr>';
                });
                $('#tbl_datos').html(grado);
            }
        });
    }

    function limpiar() {
        $('#codigo').val('');
        $('#descripcion').val('');
        $('#id').val('');
        $('#titulo').text('Nueva Sección');
        $('#op').text('Guardar');
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
                            Parametrización - Grado
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">

            <div class="col-12">
                <div class="card card border-top border-0 border-4 border-primary">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                            </div>
                            <h5 class="mb-0 text-primary">Grado</h5>
                        </div>
                        <hr>

                        <div class="content">
                            <!-- Content Header (Page header) -->
                            <br>

                            <section class="content">
                                <div class="container-fluid">

                                    <div class="row">
                                        <div class="col-sm-12" id="btn_nuevo">
                                            <a href="../vista/inicio.php?mod=7&acc=registrar_grado" class="btn btn-success btn-sm"><i class="bx bx-plus"></i> Nuevo</a>
                                            <a href="#" class="btn btn-outline-secondary btn-sm" id="excel_grado" title="Informe en excel del total de Grados"><i class="bx bx-file"></i> Total Grados</a>
                                        </div>

                                    </div>

                                    <div>
                                        <div class="col-sm-8 pt-3">
                                            <input type="" name="" id="txt_buscar" onkeyup="buscar($('#txt_buscar').val())" class="form-control form-control-sm" placeholder="Buscar Sección">
                                        </div>
                                    </div>
                                    <br>

                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Codigo</th>
                                                    <th>Sección</th>
                                                    <th>Grado</th>
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
        <!--end row-->
    </div>
</div>