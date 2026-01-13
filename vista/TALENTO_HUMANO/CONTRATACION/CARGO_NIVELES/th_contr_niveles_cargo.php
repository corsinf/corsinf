<?php // include('../cabeceras/header.php');

$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$_id = '';

if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}

?>

<!-- scripts (ajusta rutas si tu proyecto tiene otras) -->
<script src="../js/ACTIVOS_FIJOS/avaluos.js"></script>
<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
$(document).ready(function() {

    tbl_niveles = $('#tbl_niveles').DataTable($.extend({}, configuracion_datatable('Nombre', 'descripcion',
        'nombre'), {
        responsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        ajax: {
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_niveles_cargoC.php?listar=true',
            dataSrc: ''
        },
        columns: [{
                data: null,
                render: function(data, type, item) {
                    // Link a formulario de registro/edición (ajusta acc si lo tienes distinto)
                    href =
                        `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_niveles_cargo&_id=${item._id}`;
                    return `<a href="${href}"><u>${item.nombre}</u></a>`;
                }
            },
            {
                data: 'descripcion',
                render: function(data, type, item) {
                    if (!data) return '';
                    return data.length > 140 ? data.substring(0, 137) + '...' : data;
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
            <div class="breadcrumb-title pe-3">Niveles</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Todos los niveles</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-4">
                        <div class="card-title d-flex align-items-center justify-content-between">

                            <h5 class="mb-0 text-primary">Catálogo de Niveles</h5>

                           

                        </div>
                         <div class="d-flex gap-2">
                                <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_niveles_cargo"
                                    class="btn btn-success btn-sm">
                                    <i class="bx bx-plus me-0 pb-1"></i> Nuevo Nivel
                                </a>
                            </div>

                        <section class="content pt-2">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="tbl_niveles" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Descripción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- DataTables cargará aquí -->
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