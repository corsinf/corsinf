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

    <?php if ($_id != '') { ?>
    // Cargar plaza automáticamente
    cargar_plaza(<?= $_id ?>);
    cargar_etapas_plaza(<?= $_id ?>);

    <?php } ?>

    // Cargar tabla inicial
    cargar_seguimientos(<?= $_id ?>);

    // Eventos de filtros
    $('#ddl_etapa').on('change', function() {
        cargar_seguimientos(<?= $_id ?>);
    });

    $('#btn_limpiar_filtros').on('click', function() {
        $('#ddl_etapa').val('');
        cargar_seguimientos(<?= $_id ?>);
    });

    /**
     * Cargar información de la plaza
     */
    function cargar_plaza(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_plazasC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (!response || !response[0]) {
                    console.warn('No se encontró información de la plaza');
                    return;
                }
                var r = response[0];

                // Llenar el select con la plaza actual
                $('#ddl_plaza').empty().append($('<option>', {
                    value: r._id,
                    text: r.th_pla_titulo,
                    selected: true
                }));

                // Mostrar información adicional
                $('#nombre_plaza').text(r.th_pla_titulo || 'Plaza sin nombre');

                var tipo_color = r.th_pla_tipo === 'Interna' ? 'info' :
                    r.th_pla_tipo === 'Externa' ? 'success' : 'warning';
                $('#badge_tipo_plaza').html(
                    `<span class="badge bg-${tipo_color}">${r.th_pla_tipo}</span>`);
            },
            error: function(err) {
                console.error('Error al cargar la plaza:', err);
                Swal.fire('Error', 'No se pudo cargar la información de la plaza', 'error');
            }
        });
    }

    /**
     * Cargar etapas de la plaza
     */
    function cargar_etapas_plaza(idPlaza) {
        $('#ddl_etapa').select2({
            ajax: {
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_plaza_etapas_procesoC.php?buscar_etapas_plaza=true',
                dataType: 'json',
                data: function(params) {
                    return {
                        q: params.term, // texto buscado
                        pla_id: idPlaza // ID de la plaza
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                }
            },
            minimumInputLength: 0,
            placeholder: "Seleccione un requisito",
            language: {
                noResults: function() {
                    return "No hay requisitos disponibles para asignar";
                },
                searching: function() {
                    return "Buscando...";
                }
            }
        });
    }

    /**
     * Cargar tabla de seguimientos
     */
    function cargar_seguimientos(id_plaza) {
        // Destruir la tabla existente si ya existe
        if ($.fn.DataTable.isDataTable('#tbl_seguimientos')) {
            $('#tbl_seguimientos').DataTable().clear().destroy();
        }

        var tbl_seguimientos = $('#tbl_seguimientos').DataTable({
            responsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            ajax: {
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_seguimiento_postulanteC.php?listar_todos=true',
                type: 'POST',
                data: function(d) {
                    d.id_plaza = id_plaza;
                    d.id_etapa = $('#ddl_etapa').val() || '';
                    d.id_pos = '';
                },
                dataSrc: '',
                error: function(xhr, error, thrown) {
                    console.error('Error al cargar seguimientos:', error, thrown);
                    Swal.fire('Error', 'No se pudo cargar la información de seguimiento', 'error');
                }
            },
            columns: [{
                    data: null,
                    render: function(data, type, item) {
                        // link al formulario de registro/modificación (ajusta acc si lo tienes distinto)
                        href =
                            `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_seguimiento_postulante&_id=${item._id}&_id_plaza=${<?= $_id?>}`;
                        return `<a href="${href}"><u>${item.th_etapa_nombre}</u></a>`;
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        var tipo_badge = item.tipo_candidato === 'Postulante Externo' ? 'info' :
                            item.tipo_candidato === 'Empleado Interno' ? 'primary' :
                            'secondary';

                        return `
                            <div>
                                <strong>${item.nombre_completo || 'Sin nombre'}</strong>
                                <span class="badge bg-${tipo_badge} ms-2">${item.tipo_candidato || 'N/A'}</span>
                                <div class="small mt-1">
                                    <i class="bx bx-id-card text-primary"></i> ${item.cedula || 'N/A'}
                                </div>
                                ${item.telefono ? `<div class="small"><i class="bx bx-phone text-success"></i> ${item.telefono}</div>` : ''}
                                ${item.correo ? `<div class="small"><i class="bx bx-envelope text-info"></i> ${item.correo}</div>` : ''}
                            </div>
                        `;
                    }
                },
                {
                    data: 'th_seg_fecha_programada',
                    render: function(data) {
                        if (!data) return '<span class="text-muted">No programada</span>';

                        var fecha = new Date(data);
                        var dia = String(fecha.getDate()).padStart(2, '0');
                        var mes = String(fecha.getMonth() + 1).padStart(2, '0');
                        var anio = fecha.getFullYear();
                        var hora = String(fecha.getHours()).padStart(2, '0');
                        var minuto = String(fecha.getMinutes()).padStart(2, '0');

                        return `
                            <div>
                                <i class="bx bx-calendar text-primary"></i> ${dia}/${mes}/${anio}
                                <div class="small text-muted">${hora}:${minuto}</div>
                            </div>
                        `;
                    }
                },
                {
                    data: 'th_seg_fecha_realizada',
                    render: function(data) {
                        if (!data) return '<span class="badge bg-warning">Pendiente</span>';

                        var fecha = new Date(data);
                        var dia = String(fecha.getDate()).padStart(2, '0');
                        var mes = String(fecha.getMonth() + 1).padStart(2, '0');
                        var anio = fecha.getFullYear();

                        return `<span class="badge bg-success"><i class="bx bx-check-circle"></i> ${dia}/${mes}/${anio}</span>`;
                    }
                },
                {
                    data: 'th_seg_calificacion',
                    render: function(data) {
                        if (!data) return '-';

                        var calificacion = parseFloat(data);
                        var color = calificacion >= 80 ? 'success' :
                            calificacion >= 60 ? 'warning' : 'danger';

                        return `<span class="badge bg-${color} fs-6">${calificacion.toFixed(2)}</span>`;
                    },
                    className: 'text-center'
                },
                {
                    data: 'th_seg_resultado',
                    render: function(data) {
                        if (!data) return '-';

                        var upper = data.toUpperCase();
                        var color = 'secondary';
                        var icono = 'bx-circle';

                        if (upper.includes('APROB')) {
                            color = 'success';
                            icono = 'bx-check-circle';
                        } else if (upper.includes('RECHAZ')) {
                            color = 'danger';
                            icono = 'bx-x-circle';
                        } else if (upper.includes('PENDIENTE')) {
                            color = 'warning';
                            icono = 'bx-time';
                        }

                        return `<span class="badge bg-${color}"><i class="bx ${icono}"></i> ${data}</span>`;
                    }
                }
            ],
            order: [
                [2, 'desc']
            ], // Ordenar por fecha programada descendente
            pageLength: 25,
            drawCallback: function() {
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        });
    }

});
</script>

<div class="page-wrapper">
    <div class="page-content">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Seguimiento de Postulantes</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="#"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Seguimiento por Plaza</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-4">

                        <!-- Header con información de la plaza -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="mb-1 text-primary">
                                    <i class="bx bx-clipboard-check me-2"></i>
                                    Seguimiento de Postulantes
                                </h5>
                                <div class="d-flex align-items-center gap-2">
                                    <h6 class="mb-0" id="nombre_plaza">Cargando plaza...</h6>
                                    <div id="badge_tipo_plaza"></div>
                                </div>
                            </div>
                            <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_informacion_plaza&_id=<?= $_id ?>"
                                class="btn btn-outline-dark">
                                <i class="bx bx-arrow-back"></i> Regresar
                            </a>
                        </div>

                        <hr>

                        <!-- Filtros de búsqueda -->
                        <div class="card border-0 bg-light mb-4">
                            <div class="card-body">
                                <h6 class="mb-3">
                                    <i class="bx bx-filter-alt text-primary"></i> Filtros de Búsqueda
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="ddl_plaza" class="form-label fw-semibold">
                                            <i class="bx bx-briefcase text-primary"></i> Plaza
                                        </label>
                                        <select id="ddl_plaza" class="form-select" disabled>
                                            <option value="">Cargando...</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="ddl_etapa" class="form-label fw-semibold">
                                            <i class="bx bx-list-ul text-success"></i> Etapa del Proceso
                                        </label>
                                        <select id="ddl_etapa" class="form-select">
                                            <option value="">-- Todas las etapas --</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <button type="button" id="btn_limpiar_filtros"
                                            class="btn btn-outline-secondary w-100">
                                            <i class="bx bx-x-circle"></i> Limpiar Filtros
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla de seguimientos -->
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle" id="tbl_seguimientos"
                                style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th width="12%"><i class="bx bx-list-ul me-1"></i>Etapa</th>
                                        <th width="22%"><i class="bx bx-user me-1"></i>Postulante</th>
                                        <th width="12%"><i class="bx bx-calendar me-1"></i>Fecha Prog.</th>
                                        <th width="12%"><i class="bx bx-check me-1"></i>Fecha Realiz.</th>
                                        <th width="8%" class="text-center"><i class="bx bx-trophy me-1"></i>Calif.</th>
                                        <th width="10%"><i class="bx bx-flag me-1"></i>Resultado</th>
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