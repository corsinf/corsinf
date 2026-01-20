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

        tbl_plaza_cargo = $('#tbl_plaza_cargo').DataTable($.extend({}, configuracion_datatable('Plaza', 'Cargo', 'cantidad'), {
            responsive: true,
            language: {
                url: '../assets/plugins/datatable/spanish.json'
            },
            ajax: {
                // Ruta para listar las asignaciones plaza-cargo
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_plaza_cargoC.php?listar=true',
                dataSrc: ''
            },
            columns: [
                {
                    // Plaza (enlazada al detalle/edición)
                    data: null,
                    render: function(data, type, item) {
                        // item._id es el th_pc_id (alias _id)
                        href = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_plaza_cargo&_id=${item._id}`;
                        var titulo = item.plaza_titulo || '(Sin título)';
                        return `<a href="${href}"><u>${titulo}</u></a>`;
                    }
                },
                {
                    // Cargo
                    data: 'cargo_nombre',
                    render: function(data, type, item) {
                        return data || '';
                    }
                },
                {
                    // Cantidad
                    data: 'cantidad',
                    className: 'text-center',
                    render: function (data, type, item) {
                        return data ? data : '0';
                    }
                },
                {
                    // Salario ofertado
                    data: 'salario_ofertado',
                    className: 'text-end',
                    render: function (data, type, item) {
                        if (!data) return '-';
                        // Formateo simple a 2 decimales
                        return parseFloat(data).toFixed(2);
                    }
                }
            ],
            order: [
                [3, 'desc'] // orden por fecha_creacion descendente
            ]
        }));

    });
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Asignaciones</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Vincular Cargo con Plaza
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

                            <h5 class="mb-0 text-primary">Vincular Cargo con Plaza</h5>

                            <div class="row mx-0">

                                <div class="" id="btn_nuevo">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_plaza_cargo"
                                        type="button" class="btn btn-success btn-sm ">
                                        <i class="bx bx-plus me-0 pb-1"></i> Nuevo
                                    </a>
                                </div>
                            </div>
                        </div>


                        <section class="content pt-2">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped responsive " id="tbl_plaza_cargo" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Plaza</th>
                                                <th>Cargo</th>
                                                <th class="text-center">Cantidad</th>
                                                <th class="text-end">Salario ofertado</th>
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
