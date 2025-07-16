<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);


?>

<script src="../js/GENERAL/operaciones_generales.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

        tbl_marcaciones_web = $('#tbl_marcaciones_web').DataTable($.extend({}, configuracion_datatable('Marcaciones', 'Marcaciones'), {
            reponsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            ajax: {
                url: '../controlador/TALENTO_HUMANO/th_control_acceso_temporalC.php?listar=true',
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    render: function(data, type, item) {
                        href = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_marcaciones_web_registrar&_id=${item._id}`;
                        return `<a href="${href}"><u>${item.nombre_persona}</u></a>`;
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        salida = fecha_formateada_hora(item.fecha_hora);
                        return `${salida}`;
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        estado = (item.estado_aprobacion || '').toUpperCase();

                        if (estado === 'PENDIENTE') {
                            return '<div class="badge rounded-pill text-warning bg-light-warning p-2 text-uppercase px-3">PENDIENTE</div>';
                        } else if (estado === 'APROBADO') {
                            return '<div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">APROBADO</div>';
                        } else if (estado === 'RECHAZADO') {
                            return '<div class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3">RECHAZADO</div>';
                        } else {
                            return '';
                        }
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return item.observacion_aprobacion; // Entrada / Salida
                    }
                }
            ],
            order: [
                [1, 'asc']
            ]
        }));

    });
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Registro de marcación</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Marcación
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

                            <div class="col-12 col-md-6">
                                <div class="card-title d-flex align-items-center">

                                    <?php if ($_SESSION['INICIO']['NO_CONCURENTE_TABLA'] == 'th_personas') { ?>
                                        <div class="" id="btn_nuevo">
                                            <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_marcaciones_web_registrar"
                                                type="button" class="btn btn-success btn-sm ">
                                                <i class="bx bx-plus me-0 pb-1"></i> Marcación
                                            </a>
                                        </div>
                                    <?php } ?>

                                </div>
                            </div>

                            <div class="col-12 col-md-6 text-md-end text-start">
                                <div id="contenedor_botones"></div>
                            </div>

                        </div>

                        <hr>

                        <section class="content pt-0">
                            <div class="container-fluid">

                                <div class="table-responsive">
                                    <table class="table table-striped responsive " id="tbl_marcaciones_web" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Persona</th>
                                                <th>Fecha/Hora</th>
                                                <th>Estado</th>
                                                <th>Observación</th>
                                            </tr>
                                        </thead>
                                        <tbody class="">

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