<?php //include('../cabeceras/header.php');

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

    tbl_plazas = $('#tbl_plazas').DataTable($.extend({}, configuracion_datatable('Nombre', 'cuidad',
        'telefono'), {
        reponsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        ajax: {
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_plazasC.php?listar=true',
            dataSrc: '',

        },
        columns: [{
                data: null,
                render: function(data, type, item) {
                    // enlace al módulo de requisitos
                    const href =
                        `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_postulados&_id=${item._id}`;

                    // botón único
                    const btn = `
                            <a href="${href}" class="btn btn-xs btn-primary" title="Ver Postulaciones">
                               <i class="bx bx-briefcase fs-6 me-0" aria-hidden="true" title="Postulaciones"></i>
                            </a>
                        `;

                    return btn;
                }
            },
            {
                data: null,
                render: function(data, type, item) {
                    href =
                        `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_informacion_plaza&_id=${item._id}`;
                    return `<a href="${href}"><u>${item.th_pla_titulo}</u></a>`;
                }
            },
            {
                data: 'th_pla_descripcion',
                render: function(data, type, row) {
                    if (!data) return '';
                    return data.length > 50 ? data.substring(0, 50) + '...' : data;
                }
            },
            {
                data: 'th_pla_tipo'
            },
            {
                data: 'th_pla_num_vacantes'
            },
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
            <div class="breadcrumb-title pe-3">Espacios</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Todos los espacios
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
                        <div class="card-title d-flex align-items-center">

                            <h5 class="mb-0 text-primary"></h5>

                            <div class="row mx-0">

                                <div class="" id="btn_nuevo">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_proceso_contratacion"
                                        type="button" class="btn btn-success btn-sm ">
                                        <i class="bx bx-plus me-0 pb-1"></i> Proceso Contratación
                                    </a>
                                </div>
                            </div>
                        </div>


                        <section class="content pt-2">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped responsive " id="tbl_plazas" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Postulaciones</th>
                                                <th>Titulo</th>
                                                <th>Descripción</th>
                                                <th>tipo</th>
                                                <th>N° Vacantes</th>
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