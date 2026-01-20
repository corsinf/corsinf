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
             dom: 'frtip',
            buttons: [{
                extend: 'colvis',
                text: '<i class="bx bx-columns"></i> Columnas',
                className: 'btn btn-outline-secondary btn-sm'
            }],
            language: {
                url: '../assets/plugins/datatable/spanish.json'
            },
            ajax: {
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_plazasC.php?listar=true',
                dataSrc: '',

            },
            columns: [{
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

<script type="text/javascript">
    $(document).ready(function() {

        tbl_requisitos = $('#tbl_requisitos').DataTable($.extend({}, configuracion_datatable(
            'Tipo', 'Descripción', 'Obligatorio', 'Ponderación', 'Estado'), {

            responsive: true,
             dom: 'frtip',
            buttons: [{
                extend: 'colvis',
                text: '<i class="bx bx-columns"></i> Columnas',
                className: 'btn btn-outline-secondary btn-sm'
            }],
            language: {
                url: '../assets/plugins/datatable/spanish.json'
            },

            ajax: {
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_requisitosC.php?listar=true',
                dataSrc: ''
            },

            columns: [{
                    data: null,
                    render: function(data, type, item) {

                        // quitar guiones bajos en el texto
                        let tipoLimpio = item.tipo ? item.tipo.replace(/_/g, ' ') : '';

                        let href =
                            `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registro_requisito&_id=${item._id}`;

                        return `<a href="${href}"><u>${tipoLimpio}</u></a>`;
                    }
                },
                {
                    data: 'descripcion'
                },
                {
                    data: 'obligatorio',
                    render: d => d == 1 ? "<span class='badge bg-success'>Sí</span>" : "<span class='badge bg-secondary'>No</span>"
                },
                {
                    data: 'ponderacion'
                },

            ],

            order: [
                [0, 'asc']
            ]
        }));
    });
</script>

<script type="text/javascript">
let tbl_etapas;

$(document).ready(function() {

    // Inicializar datatable de etapas
    tbl_etapas = $('#tbl_etapas').DataTable($.extend({}, configuracion_datatable('Nombre', 'tipo',
        'orden'), {
        responsive: true,
        dom: 'frtip',
            buttons: [{
                extend: 'colvis',
                text: '<i class="bx bx-columns"></i> Columnas',
                className: 'btn btn-outline-secondary btn-sm'
            }],
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

                        <div class="">
                            <ul class="nav nav-tabs nav-primary" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#tab_plazas">
                                        Plazas
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tab_requisitos">
                                        Requisitos de la plaza
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tab_etapas_seleccion">
                                        Etapas de selección
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content py-3">

                                <!-- TAB PLAZAS -->
                                <div class="tab-pane fade show active" id="tab_plazas">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_proceso_contratacion"
                                        class="btn btn-success btn-sm mb-3">
                                        <i class="bx bx-plus"></i> Generar Plaza
                                    </a>
                                    <div class="table-responsive">
                                        <table class="table table-striped" id="tbl_plazas" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Título</th>
                                                    <th>Descripción</th>
                                                    <th>Tipo</th>
                                                    <th>N° Vacantes</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- TAB REQUISITOS -->
                                <div class="tab-pane fade" id="tab_requisitos">
                                    <div class="card-title d-flex align-items-center w-100 justify-content-between">
                                        <h5 class="mb-0 text-primary">Listado de requisitos</h5>

                                        <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registro_requisito"
                                            class="btn btn-success btn-sm">
                                            <i class="bx bx-plus"></i> Nuevo
                                        </a>
                                    </div>

                                    <section class="content pt-2">
                                        <div class="container-fluid">

                                            <div class="table-responsive">
                                                <table class="table table-striped" id="tbl_requisitos" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Tipo</th>
                                                            <th>Descripción</th>
                                                            <th>Obligatorio</th>
                                                            <th>Ponderación</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </section>

                                </div>


                                <div class="tab-pane fade" id="tab_etapas_seleccion">
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
        </div>
        <!--end row-->
    </div>
</div>