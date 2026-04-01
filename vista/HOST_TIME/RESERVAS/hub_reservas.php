<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
?>
<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        $('#tbl_reservas').DataTable({
            responsive: true,
            language: {
                url: '../assets/plugins/datatable/spanish.json'
            },
            ajax: {
                url: '../controlador/HOST_TIME/RESERVAS/hub_reservasC.php?listar_detalle=true',
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    orderable: false,
                    className: 'text-center',
                    render: function(data, type, item) {
                        // Ruta solicitada
                        let href = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_reservas_info&_id=${item._id}`;

                        return `
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="${href}" class="btn btn-primary btn-xs" title="Ver">
                                        <i class="bx bx-show fs-7 me-0 fw-bold"></i>
                                    </a>
                                </div>
                            `;
                    }
                },
                {
                    data: 'codigo',
                },
                {
                    data: null,
                    render: function(data) {
                        return data.codigo_espacio + ' - ' + data.nombre_espacio;
                    }
                },
                {
                    data: 'estado_reserva',
                },
                {
                    data: 'inicio',
                    render: function(data) {
                        return fecha_formateada(data);
                    }
                },
                {
                    data: 'fin',
                    render: function(data) {
                        return fecha_formateada(data);
                    }
                },
                {
                    data: 'nombre_persona'
                }
            ],
            order: [
                [0, 'desc']
            ]
        });

    });

    function eliminar_reserva(id) {
        if (confirm('¿Eliminar esta reserva?')) {
            $.post('../controlador/HOST_TIME/RESERVAS/hub_reservasC.php?eliminar=true', {
                id: id
            }, function(response) {
                $('#tbl_reservas').DataTable().ajax.reload();
            });
        }
    }
</script>

<div class="page-wrapper">
    <div class="page-content">

        <!-- BREADCRUMB -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Reservas</div>

            <div class="ps-3">
                <nav>
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <i class="bx bx-home-alt"></i>
                        </li>
                        <li class="breadcrumb-item active">
                            Listado de reservas
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- CARD -->
        <div class="card border-top border-0 border-4 border-primary">
            <div class="card-body p-4">



                <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_reservas_registrar"
                    class="btn btn-success btn-sm">
                    <i class="bx bx-plus"></i> Nueva Reserva
                </a>

                </br>
                </br>
                <!-- TABLA -->
                <div class="table-responsive">
                    <table id="tbl_reservas" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>Acciones</th>
                                <th>Código</th>
                                <th>Espacio</th>
                                <th>Estado</th>
                                <th>Inicio</th>
                                <th>Fin</th>
                                <th>Cliente</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
</div>