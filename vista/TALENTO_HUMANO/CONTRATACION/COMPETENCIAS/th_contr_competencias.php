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

    tbl_competencias = $('#tbl_competencias').DataTable($.extend({}, configuracion_datatable(
        'codigo', 'Descripción'), {

        responsive: true,
        language: {
            url: '../assets/plugins/datatable/spanish.json'
        },

        ajax: {
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_competenciasC.php?listar=true',
            dataSrc: ''
        },

        columns: [{
                data: null,
                render: function(data, type, item) {

                    let href =
                        `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_competencia&_id=${item._id}`;

                    return `<a href="${href}"><u>${item.codigo}</u></a>`;
                }
            },
            {
                data: 'nombre'
            },
            {
                data: 'categoria'
            },
            {
                data: 'descripcion'
            },

        ],

        order: [
            [0, 'asc']
        ]
    }));
});
</script>


<div class="page-wrapper">
    <div class="page-content">

        <!-- breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Requisitos</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Todos las competencias</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- end breadcrumb -->

        <div class="row">
            <div class="col-xl-12 mx-auto">

                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">

                        <!-- Título + botón nuevo -->
                        <div class="card-title d-flex align-items-center w-100 justify-content-between">
                            <h5 class="mb-0 text-primary">Listado de competencias</h5>

                            <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_competencia"
                                class="btn btn-success btn-sm">
                                <i class="bx bx-plus"></i> Nuevo
                            </a>
                        </div>

                        <section class="content pt-2">
                            <div class="container-fluid">

                                <div class="table-responsive">
                                    <table class="table table-striped" id="tbl_competencias" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Codigo</th>
                                                <th>Nombre</th>
                                                <th>Categoria</th>
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