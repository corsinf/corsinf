<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
$(document).ready(function() {

    tbl_permisos = $('#tbl_permisos').DataTable($.extend({}, configuracion_datatable(
        'Motivo', 'Detalle', 'Atención', 'Horas', 'Días'
    ), {
        responsive: true,
        language: {
            url: '../assets/plugins/datatable/spanish.json'
        },
        ajax: {
            url: '../controlador/TALENTO_HUMANO/th_solicitud_permisoC.php?listar=true',
            dataSrc: ''
        },
        columns: [{
                data: 'motivo',
                render: function(data, type, item) {
                    let href =
                        `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_solicitud_permiso&_id=${item._id}`;
                    return `<a href="${href}"><u>${data}</u></a>`;
                }
            },
            {
                data: 'nombre_completo',
                className: 'text-center'
            },
            {
                data: 'total_horas',
                className: 'text-center'
            },
            {
                data: 'total_dias',
                className: 'text-center'
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

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Permisos</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active">Solicitudes de Permiso</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-4">

                        <div class="card-title d-flex align-items-center justify-content-between">
                            <h5 class="mb-0 text-primary">Listado de Solicitudes</h5>

                            <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_solicitud_permiso"
                                class="btn btn-success btn-sm">
                                <i class="bx bx-plus"></i> Nuevo Permiso
                            </a>
                        </div>

                        <div class="table-responsive pt-3">
                            <table class="table table-striped" id="tbl_permisos" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Motivo</th>
                                        <th>Solicitante</th>
                                        <th>Total Horas</th>
                                        <th>Total Días</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>