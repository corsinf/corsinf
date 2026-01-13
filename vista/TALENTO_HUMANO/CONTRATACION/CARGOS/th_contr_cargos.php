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

        tbl_cargos = $('#tbl_cargos').DataTable($.extend({}, configuracion_datatable('Nombre', 'area', 'nivel'), {
            responsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            ajax: {
                // Cambia la ruta si es diferente en tu proyecto
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargosC.php?listar=true',
                dataSrc: ''
            },

            columns: [{
                    data: null,
                    render: function(data, type, item) {
                        // link al formulario de registro/modificación (ajusta acc si lo tienes distinto)
                        href =
                            `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registro_cargo&_id=${item._id}`;
                        return `<a href="${href}"><u>${item.nombre}</u></a>`;
                    }
                },
                {
                    data: 'descripcion',
                    render: function(data, type, item) {
                        if (!data) return '';
                        // Acortar descripción en la tabla
                        return data.length > 120 ? data.substring(0, 117) + '...' : data;
                    }
                },
                {
                    data: 'nivel'
                },
                {
                    data: 'departamento'
                }
            ],
            order: [
                [0, 'asc']
            ]
        }));

    });
</script>

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

<script type="text/javascript">
    $(document).ready(function() {

        tbl_requisitos = $('#tbl_requisitos').DataTable($.extend({}, configuracion_datatable('Nombre',
            'descripcion', 'fecha'), {
            responsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            ajax: {
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_requisitosC.php?listar=true',
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    render: function(data, type, item) {
                        // link al formulario de registro/modificación (ajusta acc si lo tienes distinto)
                        const href =
                            `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_cargo_requisitos&_id=${item._id}`;
                        return `<a href="${href}"><u>${item.nombre || 'Sin nombre'}</u></a>`;
                    }
                },
                {
                    data: 'descripcion',
                    render: function(data, type, item) {
                        if (!data) return '';
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
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">
                        <div class="">
                            <ul class="nav nav-tabs nav-primary" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#tab_cargos">
                                        Cargos
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tab_requisitos_cargo">
                                        Requisitos del cargo
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tab_niveles_cargo">
                                        Niveles del cargo
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content py-3">
                                <div class="tab-pane fade show active" id="tab_cargos">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registro_cargo"
                                        class="btn btn-success btn-sm">
                                        <i class="bx bx-plus me-0 pb-1"></i> Nuevo Cargo
                                    </a>
                                    <section class="content pt-2">
                                        <div class="container-fluid">
                                            <div class="table-responsive">
                                                <table class="table table-striped responsive " id="tbl_cargos" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Nombre</th>
                                                            <th>Descripción</th>
                                                            <th>Nivel</th>
                                                            <th>Departamento</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="">

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div><!-- /.container-fluid -->
                                    </section>
                                </div>
                                <div class="tab-pane fade" id="tab_requisitos_cargo">
                                    <div class="d-flex gap-2 mb-2">
                                        <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_cargo_requisitos"
                                            class="btn btn-success btn-sm">
                                            <i class="bx bx-plus me-0 pb-1"></i> Nuevo Requisito
                                        </a>
                                        <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_requisitos_detalles"
                                            class="btn btn-success btn-sm">
                                            <i class="bx bx-plus me-0 pb-1"></i> Lista de requisitos detalles
                                        </a>
                                    </div>
                                    <section class="content pt-2">
                                        <div class="container-fluid">
                                            <div class="table-responsive">
                                                <table class="table table-striped responsive" id="tbl_requisitos"
                                                    style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Nombre</th>
                                                            <th>Descripción</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div><!-- /.container-fluid -->
                                    </section>
                                </div>
                                <div class="tab-pane fade" id="tab_niveles_cargo">
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
        </div>
    </div>
</div>