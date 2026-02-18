<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        tbl_plazas = $('#tbl_plazas').DataTable($.extend({}, configuracion_datatable('Nombre', 'cuidad', 'telefono'), {
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
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/cn_plazaC.php?listar=true',
                dataSrc: '',
            },
            columns: [{
                    data: null,
                    render: function(data, type, item) {
                        let href = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=cn_registrar_plaza&_id=${item._id}`;
                        return `<a href="${href}"><u>${item.cn_pla_titulo}</u></a>`;
                    }
                },
                {
                    data: 'cn_pla_descripcion',
                    render: function(data, type, row) {
                        if (!data) return '';
                        return data.length > 50 ? data.substring(0, 50) + '...' : data;
                    }
                },
                {
                    data: 'cn_pla_num_vacantes'
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
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Plazas</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Todos las plazas</li>
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
                                    <div class="" id="btn_nuevo">
                                        <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=cn_registrar_plaza" class="btn btn-success btn-sm">
                                            <i class="bx bx-plus"></i> Nuevo
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped" id="tbl_plazas" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Título</th>
                                        <th>Descripción</th>
                                        <th>N° Vacantes</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>