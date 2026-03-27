<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$_id = '';

if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}
?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        tbl_turnos = $('#tbl_turnos').DataTable($.extend({},{
            responsive: true,
            language: {
                url: '../assets/plugins/datatable/spanish.json'
            },
            ajax: {
                url: '../controlador/HOST_TIME/TURNOS/hub_turnosC.php?listar=true',
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    render: function(data, type, item) {
                        var href = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_turnos_registrar&_id=${item._id}`;
                        return `<a href="${href}"><u>${item.nombre}</u></a>`;
                    }
                },
                {
                    data: 'hora_entrada',
                    render: function(data, type, row) {
                        if (data === null || data === undefined || data === '') return '--:--';
                        return minutos_formato_hora(data);
                    }
                },
                {
                    data: 'hora_salida',
                    render: function(data, type, row) {
                        if (data === null || data === undefined || data === '') return '--:--';
                        return minutos_formato_hora(data);
                    }
                }
            ],
            order: [
                [0, 'asc']
            ]
        }));

    });
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Turnos</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Todos los turnos</li>
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
                            <div class="row mx-0">
                                <div id="btn_nuevo">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_turnos_registrar"
                                        type="button" class="btn btn-success btn-sm">
                                        <i class="bx bx-plus me-0 pb-1"></i> Nuevo
                                    </a>
                                </div>
                            </div>
                        </div>

                        <section class="content pt-2">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped responsive" id="tbl_turnos" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Hora Entrada</th>
                                                <th>Hora Salida</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </section>

                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>