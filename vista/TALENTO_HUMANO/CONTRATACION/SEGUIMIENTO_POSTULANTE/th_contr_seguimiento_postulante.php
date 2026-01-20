<?php 
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$_id = '';
if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}
?>

<!-- LIBRERÍAS -->
<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    function cargar_seguimientos() {
        // Destruir la tabla existente si ya existe
        if ($.fn.DataTable.isDataTable('#tbl_seguimientos')) {
            $('#tbl_seguimientos').DataTable().clear().destroy();
        }

        tbl_seguimientos = $('#tbl_seguimientos').DataTable($.extend({}, configuracion_datatable(
            'Etapa', 'Postulante', 'Fecha Programada'
        ), {
            responsive: true,
            language: {
                url: '../assets/plugins/datatable/spanish.json'
            },
            ajax: {
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_seguimiento_postulanteC.php?listar_todos=true',
                type: 'POST',
                data: function(d) {
                    d.id_plaza = $('#ddl_plaza').val() || '';
                    d.id_etapa = $('#ddl_etapa').val() || '';
                    d.id_pos = $('#ddl_postulante').val() || '';
                },
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    render: function(data, type, item) {
                        return `<span class="badge bg-info">${item.th_etapa_nombre}</span><br>
                            <small class="text-muted">Orden: ${item.th_etapa_orden}</small>`;
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return `<strong>${item.nombre_completo}</strong><br>
                            <small><i class="bx bx-id-card"></i> ${item.cedula || 'N/A'}</small><br>
                            <small><i class="bx bx-phone"></i> ${item.telefono || 'N/A'}</small><br>
                            <small><i class="bx bx-envelope"></i> ${item.correo || 'N/A'}</small>`;
                    }
                },
                {
                    data: 'th_seg_fecha_programada',
                    render: function(data) {
                        return data ? `<i class="bx bx-calendar"></i> ${data}` :
                            'No programada';
                    }
                },
                {
                    data: 'th_seg_fecha_realizada',
                    render: function(data) {
                        return data ?
                            `<i class="bx bx-check-circle text-success"></i> ${data}` :
                            '<span class="text-muted">Pendiente</span>';
                    }
                },
                {
                    data: 'th_seg_calificacion',
                    render: function(data) {
                        if (!data) return '-';
                        const color = data >= 80 ? 'success' : data >= 60 ? 'warning' :
                            'danger';
                        return `<span class="badge bg-${color}">${data}</span>`;
                    }
                },
                {
                    data: 'th_seg_resultado',
                    render: function(data) {
                        if (!data) return '-';
                        const estilos = {
                            'APROBADO': 'success',
                            'APROBADA': 'success',
                            'RECHAZADO': 'danger',
                            'RECHAZADA': 'danger',
                            'PENDIENTE': 'warning'
                        };
                        const upper = data.toUpperCase();
                        const estilo = estilos[upper] || 'secondary';
                        return `<span class="badge bg-${estilo}">${data}</span>`;
                    }
                },
                {
                    data: 'th_seg_observaciones',
                    render: function(data) {
                        if (!data) return '';
                        return data.length > 80 ? data.substring(0, 77) + '...' : data;
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        const href =
                            `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registro_seguimiento&_id=${item._id}`;
                        return `<a href="${href}" class="btn btn-sm btn-primary">
                                <i class="bx bx-show"></i> Ver
                            </a>`;
                    }
                },
            ],
            order: [
                [2, 'desc']
            ]
        }));
    }

    function aplicar_filtros_seguimientos() {
        // Recargar completamente la tabla con los nuevos filtros
        cargar_seguimientos();
    }

    $(document).ready(function() {
        // Cargar selects primero
        cargar_selects2();

        // Cargar tabla inicial
        cargar_seguimientos();

        // Detectar cambios en los filtros y recargar tabla
        $('#ddl_plaza').on('change', function() {
            console.log('Filtro plaza cambiado:', $(this).val());
            aplicar_filtros_seguimientos();
        });

        $('#ddl_etapa').on('change', function() {
            console.log('Filtro etapa cambiado:', $(this).val());
            aplicar_filtros_seguimientos();
        });

        $('#ddl_postulante').on('change', function() {
            console.log('Filtro postulante cambiado:', $(this).val());
            aplicar_filtros_seguimientos();
        });

        // Botón para limpiar filtros
        $('#btn_limpiar_filtros').on('click', function() {
            $('#ddl_plaza').val('').trigger('change');
            $('#ddl_etapa').val('').trigger('change');
            $('#ddl_postulante').val('').trigger('change');
            // Recargar tabla con filtros vacíos
            aplicar_filtros_seguimientos();
        });
    });

    function cargar_selects2() {
        var url_plazas = '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_plazasC.php?buscar_todas=true';
        var url_etapas =
            '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_etapas_procesoC.php?buscar=true';

        cargar_select2_url('ddl_plaza', url_plazas);
        cargar_select2_url('ddl_etapa', url_etapas);
    }

});
</script>

<div class="page-wrapper">
    <div class="page-content">

        <!-- BREADCRUMB -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Seguimientos</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="#"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Todos los seguimientos</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">
                        <div class="card-title d-flex align-items-center">
                            <h5 class="mb-0 text-primary"></h5>

                            <div class="row mx-0">
                                <div id="btn_nuevo">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registro_seguimiento"
                                        class="btn btn-success btn-sm">
                                        <i class="bx bx-plus me-0 pb-1"></i> Nuevo
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6 class="mb-3"><i class="bx bx-filter-alt"></i> Filtros de Búsqueda</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="ddl_plaza" class="form-label">
                                        <i class="bx bx-briefcase text-primary"></i> Plaza
                                    </label>
                                    <select id="ddl_plaza" class="form-select">
                                        <option value="">-- Todas las plazas --</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="ddl_etapa" class="form-label">
                                        <i class="bx bx-list-ul text-success"></i> Etapa
                                    </label>
                                    <select id="ddl_etapa" class="form-select">
                                        <option value="">-- Todas las etapas --</option>
                                    </select>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="button" id="btn_limpiar_filtros" class="btn btn-secondary w-100">
                                        <i class="bx bx-x-circle"></i> Limpiar Filtros
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla de datos -->
                        <section class="content pt-2">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped responsive" id="tbl_seguimientos"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Etapa</th>
                                                <th>Postulante</th>
                                                <th>Fecha Prog.</th>
                                                <th>Fecha Realiz.</th>
                                                <th>Calif.</th>
                                                <th>Resultado</th>
                                                <th>Observaciones</th>
                                                <th>Acciones</th>
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