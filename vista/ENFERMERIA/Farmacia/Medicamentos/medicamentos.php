<script type="text/javascript">
    var hoy = new Date();
    var fecha = hoy.toISOString().split('T')[0];



    $(document).ready(function() {
        $('#tabla_medicamentos').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            responsive: true,
            ajax: {
                url: '../controlador/medicamentosC.php?listar_todo=true',
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
                        return '<a href="../vista/inicio.php?mod=7&acc=registrar_medicamentos&id=' + item.sa_cmed_id + '"><u>' + item.sa_cmed_presentacion + '</u></a>';
                    }
                },
                {
                    data: 'sa_cmed_nombre_comercial'
                },
                {
                    data: 'sa_cmed_stock'
                },

            ],
            dom: '<"top"Bfr>t<"bottom"lip>',
            buttons: [{
                    extend: 'excel',
                    text: '<i class="bx bxs-file-pdf me-0"></i> Exportar a Excel',
                    title: 'MEDICAMENTOS',
                    filename: 'medicamentos_' + fecha
                },
                {
                    extend: 'pdf',
                    text: '<i class="bx bxs-spreadsheet me-0"></i> Exportar a PDF',
                    title: 'MEDICAMENTOS',
                    filename: 'medicamentos_' + fecha
                }
            ],
            initComplete: function() {
                // Mover los botones al contenedor personalizado
                $('#contenedor_botones').append($('.dt-buttons'));
            }
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

                        <div class="row">

                            <div class="col-6">
                                <div class="card-title d-flex align-items-center">
                                    <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                                    </div>
                                    <h5 class="mb-0 text-primary">Medicamentos</h5>

                                    <div class="row mx-1">
                                        <div class="col-sm-12" id="btn_nuevo">
                                            <a href="../vista/inicio.php?mod=7&acc=registrar_medicamentos" class="btn btn-success btn-sm"><i class="bx bx-plus"></i> Nuevo</a>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 text-end">
                                <div id="contenedor_botones"></div>
                            </div>

                        </div>

                        <hr>

                        <div class="content">
                            <!-- Content Header (Page header) -->
                            <br>

                            <section class="content">
                                <div class="container-fluid">






                                    <br>

                                    <div class="table-responsive">
                                        <table class="table table-striped responsive " id="tabla_medicamentos" style="width:100%">
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