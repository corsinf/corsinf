<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        let estado = 2;

        let tbl_permisos = $('#tbl_permisos').DataTable({
            responsive: true,
            language: {
                url: '../assets/plugins/datatable/spanish.json'
            },
            ajax: {
                url: '../controlador/TALENTO_HUMANO/th_solicitud_permiso_medicoC.php?listar=true',
                type: 'POST',
                data: function(d) {
                    d.estado = estado;
                },
                dataSrc: ''
            },
            columns: [{
                    data: 'nombre_completo',
                    render: function(data, type, item) {
                        let href =
                            `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_solicitudes_personas&_id=${item.id}&nombre=${item.nombre_completo}`;
                        return `<a href="${href}"><u>${data}</u></a>`;
                    }
                },
                {
                    data: 'cedula'
                },
                {
                    data: 'telefono'
                },
                {
                    data: 'total_solicitudes'
                },
                {
                    data: 'total_por_revisar',
                    render: function(data) {
                        return `${data || 0}`;
                    }
                },
                {
                    data: 'total_aprobadas',
                    render: function(data) {
                        return `${data || 0}`;
                    }
                },
                {
                    data: 'total_rechazada',
                    render: function(data) {
                        return `${data || 0}`;
                    }
                },
                {
                    data: 'total_pendientes',
                    render: function(data) {
                        return `${data || 0}`;
                    }
                }
            ],
            order: [
                [3, 'desc']
            ]
        });

        // Filtro por estado
        $('input[name="rbx_variable"]').on('change', function() {
            estado = $(this).val();
            tbl_permisos.ajax.reload();
        });

    });
</script>

<div class="page-wrapper">
    <div class="page-content">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Solicitudes</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Aprobación de Solicitudes y Justificaciones
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-4">

                        <table class="table table-striped" id="tbl_permisos" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Cédula</th>
                                    <th>Teléfono</th>
                                    <th>Total solicitudes</th>
                                    <th>Por revisar</th>
                                    <th>Aprobadas</th>
                                    <th>Rechazadas</th>
                                    <th>Pendiente</th>
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