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
            columns: [
                {
                    data: null,
                    render: function(data, type, item) {
                        // link al formulario de registro/modificación (ajusta acc si lo tienes distinto)
                        href = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registro_cargo&_id=${item._id}`;
                        return `<a href="${href}"><u>${item.nombre}</u></a>`;
                    }
                },
                {
                    data: 'descripcion',
                    render: function (data, type, item) {
                        if (!data) return '';
                        // Acortar descripción en la tabla
                        return data.length > 120 ? data.substring(0, 117) + '...' : data;
                    }
                },
                {
                    data: 'nivel'
                },
                {
                    data: 'area'
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
                        <div class="card-title d-flex align-items-center">

                            <h5 class="mb-0 text-primary"></h5>

                            <div class="row mx-0">

                                <div class="" id="btn_nuevo">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registro_cargo"
                                        type="button" class="btn btn-success btn-sm ">
                                        <i class="bx bx-plus me-0 pb-1"></i> Nuevo
                                    </a>
                                </div>
                            </div>
                        </div>


                        <section class="content pt-2">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped responsive " id="tbl_cargos" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Descripción</th>
                                                <th>Nivel</th>
                                                <th>Área</th>
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


<!-- Modal de ejemplo (puedes reutilizarlo para filtros o creación rápida) -->
<div class="modal" id="modal_cargo_quick" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">Agregar cargo (rápido)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

                <div class="row">
                    <div class="col-12">
                        <label for="quick_th_car_nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" id="quick_th_car_nombre" class="form-control form-control-sm" />
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12">
                        <label for="quick_th_car_area" class="form-label">Área</label>
                        <input type="text" id="quick_th_car_area" class="form-control form-control-sm" />
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12">
                        <label for="quick_th_car_nivel" class="form-label">Nivel</label>
                        <input type="text" id="quick_th_car_nivel" class="form-control form-control-sm" />
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-success btn-sm" onclick="guardar_cargo_rapido()"><i class="bx bx-save"></i> Agregar</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>