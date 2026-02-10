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
    $(document).ready(function() {

        tbl_cargos = $('#tbl_cargos').DataTable($.extend({}, configuracion_datatable('Nombre'), {
            responsive: true,
            language: {
                url: '../assets/plugins/datatable/spanish.json'
            },
            ajax: {
                // Cambia la ruta si es diferente en tu proyecto
                url: '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_cargoC.php?listar=true',
                dataSrc: ''
            },

            columns: [{
                    data: null,
                    render: function(data, type, item) {
                        href =
                            `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registro_cargo&_id=${item._id}`;
                        return `<a href="${href}"><u>${item.nombre}</u></a>`;
                    }
                },
                {
                    data: 'descripcion'
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
            <div class="breadcrumb-title pe-3">Cargos</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Todos los cargos
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="card-title d-flex align-items-center">
                                    <div id="btn_nuevo">
                                        <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registro_cargo"
                                            class="btn btn-success btn-sm">
                                            <i class="bx bx-plus me-0 pb-1"></i> Nuevo Cargo
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 text-md-end text-start">
                                <div id="contenedor_botones"></div>
                            </div>
                        </div>

                        <hr>

                        <section class="content pt-2">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped responsive" id="tbl_cargos" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Descripción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
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