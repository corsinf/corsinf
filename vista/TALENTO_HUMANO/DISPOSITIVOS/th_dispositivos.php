<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);



?>


<script type="text/javascript">
    $(document).ready(function() {
        tbl_dispositivos = $('#tbl_dispositivos').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            responsive: true,
            ajax: {
                url: '../controlador/TALENTO_HUMANO/th_dispositivosC.php?listar=true',
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
                        href = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_dispositivos&_id=${item._id}`;
                        return `<a href="${href}"><u>${item.nombre}</u></a>`;
                    }
                },
                {
                    data: 'host'
                },

                {
                    data: null,
                    render: function(data, type, item) {
                        return `<button type="button" class="btn btn-primary btn-sm m-1" onclick=""><i class="lni lni-spinner-arrow fs-6 me-0 fw-bold"></i></button>`;
                    }
                },
            ],
            order: [
                [1, 'asc']
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
            <div class="breadcrumb-title pe-3">Dispositivos</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Lista de Dispositivos
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


                                    <div class="row mx-1">
                                        <div class="col-sm-12" id="btn_nuevo">

                                            <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_dispositivos" type="button" class="btn btn-success btn-sm"><i class="bx bx-plus"></i> Nuevo</a>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 text-end">
                                <div id="contenedor_botones"></div>
                            </div>

                        </div>

                        <hr>

                        <section class="content pt-2">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped responsive " id="tbl_dispositivos" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Host</th>
                                                <th width="10px">Acción</th>
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