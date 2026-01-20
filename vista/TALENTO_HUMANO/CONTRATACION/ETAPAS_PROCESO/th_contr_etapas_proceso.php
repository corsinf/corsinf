<?php //include('../cabeceras/header.php');

$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$_id = '';

if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}

?>

<script src="../js/ACTIVOS_FIJOS/avaluos.js"></script>
<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
let tbl_etapas;

$(document).ready(function() {

    // Inicializar datatable de etapas
    tbl_etapas = $('#tbl_etapas').DataTable($.extend({}, configuracion_datatable('Nombre', 'tipo',
        'orden'), {
        responsive: true,
        language: {
            url: '../assets/plugins/datatable/spanish.json'
        },
        ajax: {
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_etapas_procesoC.php?listar=true',
            dataSrc: ''
        },
        columns: [{
                data: null,
                render: function(data, type, item) {
                    // link al formulario de registro/modificación (ajusta acc si lo tienes distinto)
                    href =
                        `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registro_etapa_proceso&_id=${item._id}`;
                    return `<a href="${href}"><u>${item.nombre}</u></a>`;
                }
            },
            {
                data: 'tipo',
                render: function(data) {
                    return data ? data.replace(/_/g, ' ') : '';
                }
            },
            {
                data: 'orden',
                render: function(data) {
                    return data !== null && data !== undefined ? data : '';
                }
            },
            {
                data: 'obligatoria',
                render: function(data) {
                    return (data == 1 || data === true || data === '1') ?
                        '<span class="badge bg-success">Sí</span>' :
                        '<span class="badge bg-secondary">No</span>';
                }
            },
            {
                data: 'descripcion',
                render: function(data, type, item) {
                    if (!data) return '';
                    // Acortar descripción en la tabla
                    return data.length > 120 ? data.substring(0, 117) + '...' : data;
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
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Etapas del proceso</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Todas las etapas
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0 text-primary">Etapas del proceso</h5>

                            <div id="btn_nuevo">
                                <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registro_etapa_proceso"
                                    class="btn btn-success btn-sm">
                                    <i class="bx bx-plus me-1"></i> Nuevo
                                </a>
                            </div>
                        </div>
                        <section class="content pt-2">
                            <div class="container-fluid px-0">
                                <div class="table-responsive">
                                    <table class="table table-striped responsive" id="tbl_etapas" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Tipo</th>
                                                <th>Orden</th>
                                                <th>Obligatoria</th>
                                                <th>Descripción</th>
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
    </div>
</div>