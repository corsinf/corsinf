<?php

/**
 * @deprecated Archivo dado de baja el 13/02/2026.
 * @note Este archivo se mantiene como respaldo, pero ya no se utilizará en producción.
 * @warning No modificar este archivo. Para cambios, referirse a la nueva implementación.
 */


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

        tbl_req_detalles = $('#tbl_req_detalles').DataTable($.extend({}, configuracion_datatable('Nombre', 'tipo',
            'fecha'), {
            responsive: true,
            language: {
                url: '../assets/plugins/datatable/spanish.json'
            },
            ajax: {
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_cat_requisitos_detallesC.php?listar=true',
                dataSrc: ''
            },
            columns: [{
                data: null,
                render: function(data, type, item) {
                    // link al formulario de registro/modificación (ajusta acc si lo tienes distinto)
                    const id = item.th_reqdet_id || item._id || '';
                    const href =
                        `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_requisitos_detalles&_id=${id}`;
                    return `<a href="${href}"><u>${item.nombre || 'Sin nombre'}</u></a>`;
                }
            }, {
                data: 'descripcion',
                render: function(data, type, item) {
                    if (!data) return '';
                    // Si supera 120 caracteres se recorta y agrega "..."
                    return data.length > 120 ? data.substring(0, 117) + '...' : data;
                }
            }, {
                data: 'obligatorio',
                render: function(data, type, item) {
                    var is = (data == 1 || data === true || data === '1');
                    return `<span class="badge bg-${is ? 'danger' : 'secondary'}">${is ? 'Obligatorio' : 'Opcional'}</span>`;
                },
                className: 'text-center'
            }],
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
            <div class="breadcrumb-title pe-3">Detalles de Requisitos</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Catálogo de detalles</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">
                        <div class="card-title d-flex align-items-center justify-content-between mb-3">
                            <h5 class="mb-0 text-primary">Catálogo: Detalles de Requisitos</h5>

                            <div class="d-flex gap-2">
                                <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_requisitos_detalles"
                                    class="btn btn-success btn-sm">
                                    <i class="bx bx-plus me-0 pb-1"></i> Nuevo Detalle
                                </a>

                                <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_cat_cargo_requisitos"
                                    class="btn btn-secondary btn-sm">
                                    <i class="bx bx-arrow-back me-0 pb-1"></i> Volver a Requisitos
                                </a>
                            </div>
                        </div>

                        <section class="content pt-2">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped responsive" id="tbl_req_detalles"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Descripción</th>
                                                <th class="text-center">Oblig.</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
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