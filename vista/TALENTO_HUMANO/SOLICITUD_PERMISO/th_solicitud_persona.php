<?php $modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']); ?>

<?php
$_id = (isset($_GET['_id'])) ? $_GET['_id'] : '';
?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
    // 1. Definimos variables globales y cargamos datos de sesión de inmediato
    const session = <?= json_encode($_SESSION) ?>;
    const TIPO_USUARIO = session.INICIO.TIPO;

    // Si no es ADMIN, asignamos el ID de persona antes de cargar la tabla
    let id_persona = (TIPO_USUARIO === 'DBA' || TIPO_USUARIO === 'ADMINISTRADOR') ? '' : session.INICIO.NO_CONCURENTE;
    let tbl_permisos;

    $(document).ready(function() {
        <?php if ($_id != '') { ?>
            id_persona = '<?= $_id ?>';
        <?php } ?>
        cargar_tabla();
    });

    function cargar_tabla() {
        // Destruir instancia previa si existe para evitar duplicados
        if ($.fn.DataTable.isDataTable('#tbl_permisos')) {
            $('#tbl_permisos').DataTable().destroy();
        }
        $('#tbl_permisos tbody').empty();

        // Inicialización de DataTable
        tbl_permisos = $('#tbl_permisos').DataTable($.extend({}, configuracion_datatable(
            'Motivo', 'Solicitante', 'Fecha'
        ), {
            responsive: true,
            language: {
                url: '../assets/plugins/datatable/spanish.json'
            },
            ajax: {
                url: '../controlador/TALENTO_HUMANO/th_solicitud_permisoC.php?listar=true',
                type: 'POST',
                data: function(d) {
                    d.th_per_id = id_persona; // Se envía el ID filtrado o vacío si es ADMIN
                },
                dataSrc: ''
            },
            columns: [{
                    data: 'motivo',
                    render: function(data, type, item) {
                        let href = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_solicitud_permiso&_id=${item._id}`;
                        return `<a href="${href}"><u>${data}</u></a>`;
                    }
                },
                {
                    data: 'nombre_completo',
                    className: 'text-center'
                },
                {
                    data: null,
                    className: 'text-center',
                    render: function(data, type, item) {
                        let fecha = item.fecha_modificacion || item.fecha_creacion;
                        return `<span class="text-dark">${formatearFecha(fecha)}</span>`;
                    }
                }
            ],
            order: [
                [2, 'desc']
            ]
        }));
    }

    function formatearFecha(fecha) {
        if (!fecha) return '';
        let f = new Date(fecha.replace(' ', 'T'));
        return f.toLocaleDateString('es-EC') + ' ' + f.toLocaleTimeString('es-EC', {
            hour: '2-digit',
            minute: '2-digit'
        });
    }
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

                            <div class="d-flex gap-2">
                                <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_solicitud_permiso"
                                    class="btn btn-outline-dark btn-sm">
                                    <i class="bx bx-arrow-back"></i> Regresar
                                </a>
                            </div>
                        </div>

                        <div class="table-responsive pt-3">
                            <table class="table table-striped" id="tbl_permisos" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Motivo</th>
                                        <th>Solicitante</th>
                                        <th>Fecha</th>
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