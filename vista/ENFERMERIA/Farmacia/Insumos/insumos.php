<script type="text/javascript">
    $(document).ready(function() {
        $('#tabla_insumos').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            responsive: true,
            ajax: {
                url: '../controlador/insumosC.php?listar_todo=true',
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    render: function(data, type, item, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return '<a href="../vista/inicio.php?mod=7&acc=registrar_insumos&id=' + item.sa_cins_id + '"><u>' + item.sa_cins_presentacion + '</u></a>';
                    }
                },
                {
                    data: 'sa_cins_nombre_comercial'
                },

                {
                    data: 'sa_cins_stock'
                },
            ]
        });
    });
</script>

<div class="page-wrapper">
    <div class="page-content">

        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Enfermería </div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Insumos</li>
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
                            <h5 class="mb-0 text-primary">Insumos</h5>
                        </div>
                        <hr>

                        <div class="content">
                            <!-- Content Header (Page header) -->
                            <br>

                            <section class="content">
                                <div class="container-fluid">

                                    <div class="row">
                                        <div class="col-sm-12" id="btn_nuevo">
                                            <a href="../vista/inicio.php?mod=7&acc=registrar_insumos" class="btn btn-success btn-sm"><i class="bx bx-plus"></i> Nuevo</a>
                                        </div>

                                    </div>

                                    <br>

                                    <div class="table-responsive">
                                        <table class="table table-striped responsive " id="tabla_insumos" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>NOMBRE DEL MEDICAMENTO</th>
                                                    <th>NOMBRE COMERCIAL</th>
                                                    <th>Stock</th>
                                                </tr>
                                            </thead>
                                            <tbody>

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