<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
$_id = (isset($_GET['_id'])) ? $_GET['_id'] : '';
?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
$(document).ready(function() {

    let tbl_permisos = $('#tbl_permisos').DataTable($.extend({},
        configuracion_datatable(
            'Motivo',
            'Médico',
            'Fecha',
            'Desde',
            'Hasta',
        ), {
            responsive: true,
            dom: 'frtip',
            buttons: [{
                extend: 'colvis',
                text: '<i class="bx bx-columns"></i> Columnas',
                className: 'btn btn-outline-secondary btn-sm'
            }],
            ajax: {
                url: '../controlador/TALENTO_HUMANO/th_solicitud_permiso_medicoC.php?listar_solicitudes_persona=true',
                type: 'POST',
                data: function(d) {
                    d.id = <?= $_id ?>;
                },
                dataSrc: ''
            },
            columns: [{
                    data: 'motivo',
                    render: function(data, type, row) {
                        let href =
                            `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_aprobacion_solicitudes&_id=${row.id_solicitud_medica ? row.id_solicitud_medica: ''}&_id_sol=${row.id_solicitud}&_per_id=<?=$_id?>`;
                        return `<a href="${href}"><u>${data}</u></a>`;
                    }
                },
                {
                    data: 'nombre_medico',
                    render: function(data) {
                        return data ? data : '<span class="text-muted">—</span>';
                    }
                },
                {
                    data: 'fecha_creacion',
                    render: function(data) {
                        return data ? data.substring(0, 10) : '';
                    }
                },
                {
                    data: 'fecha_desde',
                    render: function(data) {
                        return data ? data.substring(0, 10) : '';
                    }
                },
                {
                    data: 'fecha_hasta',
                    render: function(data) {
                        return data && data !== '1900-01-01 00:00:00' ?
                            data.substring(0, 10) :
                            '<span class="text-muted">—</span>';
                    }
                },
                {
                    data: 'estado_medico',
                    className: 'text-center',
                    render: function(data, type, row) {

                        if (data === null) {
                            return `<span class="badge bg-secondary">Sin revisión</span>`;
                        }

                        if (data == 0) {
                            return `<span class="badge bg-warning text-dark">Pendiente</span>`;
                        }

                        if (data == 1) {
                            return `<span class="badge bg-success">Aprobado</span>`;
                        }

                        return '';
                    }
                }
            ],
            order: [
                [2, 'desc']
            ]
        }
    ));


});
</script>

<div class="page-wrapper">
    <div class="page-content">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Permisos Médicos</div>
        </div>

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-4">

                        <!-- FILA SUPERIOR: TÍTULO + BOTONES -->
                        <div class="row align-items-center mb-2">

                            <!-- TÍTULO -->
                            <div class="col-md-6">
                                <h5 class="mb-0 text-primary">Listado de Solicitudes y Permisos</h5>
                            </div>

                            <!-- BOTONES -->
                            <div class="col-md-6 text-end">
                                <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_aprobacion_solicitudes"
                                    class="btn btn-outline-dark btn-sm me-2">
                                    <i class="bx bx-arrow-back"></i> Regresar
                                </a>
                            </div>

                        </div>

                        <!-- FILA INFERIOR: FILTROS -->
                        <div class="row mb-3">
                            <div class="col-12 text-center">

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rbx_variable" value="2" checked>
                                    <label class="form-check-label">Todas</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rbx_variable" value="1">
                                    <label class="form-check-label">Aprobadas</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rbx_variable" value="0">
                                    <label class="form-check-label">Pendientes</label>
                                </div>

                            </div>
                        </div>



                        <div class="table-responsive pt-3">
                            <table class="table table-striped" id="tbl_permisos" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Motivo</th>
                                        <th>Médico</th>
                                        <th>Fecha</th>
                                        <th>Desde</th>
                                        <th>Hasta</th>
                                        <th>Estado</th>
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