<?php
$modulo_sistema = $_SESSION['INICIO']['MODULO_SISTEMA'];
$_id = '';

if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}
?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">

    $(document).ready(function () {

    <?php if ($_id != '') { ?>
        cargar_tabla_postulados(<?= $_id ?>);
    <?php } ?>

   function cargar_tabla_postulados(id_plaza) {
    // Asegurarnos de que id_plaza no sea undefined
    id_plaza = id_plaza || '';

    // Si ya existe el DataTable, lo destruimos para evitar duplicados
    if ($.fn.dataTable.isDataTable('#tbl_postulaciones')) {
        $('#tbl_postulaciones').DataTable().clear().destroy();
        $('#tbl_postulaciones').empty(); // opcional: limpia el tbody
    }

    tbl_postulaciones = $('#tbl_postulaciones').DataTable($.extend({}, configuracion_datatable('ID', 'Candidato'), {
    responsive: true,
    language: {
        url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
    },
    ajax: {
        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_postulacionesC.php?listar_plaza_postulados=true',
        type: 'POST',
        data: function (d) {
            d.id = id_plaza;
        },
        dataSrc: ''
    },
    columns: [
        {
            data: 'nombre_completo',
            render: function (data, type, item) {
                let href = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registro_postulacion&_id=${item._id}`;
                let foto = '';

                if (item.foto_url && item.foto_url !== '') {
                    foto = `<img src="${item.foto_url}" class="rounded-circle me-2" width="40" height="40" style="object-fit: cover;" alt="Foto">`;
                } else {
                    foto = `
                        <div class="bg-primary text-white rounded-circle me-2 d-inline-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px; font-size: 18px;">
                            <i class="bx bx-user"></i>
                        </div>`;
                }

                // 🔹 Envolvemos todo en un enlace clickeable
                return `
                    <a href="${href}" class="text-decoration-none text-dark d-flex align-items-center">
                        ${foto}
                        <div>
                            <div class="fw-bold">${data || 'Sin nombre'}</div>
                            <small class="text-muted">
                                <i class="bx bx-id-card me-1"></i>${item.cedula || 'Sin cédula'}
                            </small>
                        </div>
                    </a>
                `;
            },
            className: 'text-center'
        },
        {
            data: 'tipo_candidato',
            render: function (data, type, item) {
                let badge = 'secondary';
                let icon = 'bx-user';
                
                if (data === 'Empleado Contratado') {
                    badge = 'success';
                    icon = 'bx-briefcase-alt';
                } else if (data === 'Empleado Interno') {
                    badge = 'primary';
                    icon = 'bx-home';
                } else if (data === 'Postulante Contratado') {
                    badge = 'success';
                    icon = 'bx-check-circle';
                } else if (data === 'Postulante Externo') {
                    badge = 'info';
                    icon = 'bx-user-plus';
                } else if (data === 'Interno') {
                    badge = 'warning';
                    icon = 'bx-building';
                }
                
                return `<span class="badge bg-${badge}"><i class="bx ${icon} me-1"></i>${data}</span>`;
            },
            className: 'text-center'
        },
        {
            data: null,
            render: function (data, type, item) {
                let correo = item.correo && item.correo !== '-' ? item.correo : 'Sin correo';
                let telefono = item.telefono && item.telefono !== '-' ? item.telefono : 'Sin teléfono';
                
                return `
                    <div>
                        <small class="d-block">
                            <i class="bx bx-envelope me-1 text-primary"></i>${correo}
                        </small>
                        <small class="d-block">
                            <i class="bx bx-phone me-1 text-success"></i>${telefono}
                        </small>
                    </div>
                `;
            }
        },
        {
            data: 'fecha_postulacion',
            render: function (data) {
                if (data) {
                    // Convertir fecha al formato dd/mm/yyyy hh:mm
                    let fecha = new Date(data);
                    let dia = String(fecha.getDate()).padStart(2, '0');
                    let mes = String(fecha.getMonth() + 1).padStart(2, '0');
                    let anio = fecha.getFullYear();
                    let hora = String(fecha.getHours()).padStart(2, '0');
                    let minuto = String(fecha.getMinutes()).padStart(2, '0');
                    
                    return `
                        <div>
                            <div class="fw-bold">${dia}/${mes}/${anio}</div>
                            <small class="text-muted">${hora}:${minuto}</small>
                        </div>
                    `;
                }
                return '-';
            }
        },
        {
            data: 'estado_descripcion',
            render: function (data) {
                // Diferentes colores según el estado
                let badge = 'secondary';
                let icon = 'bx-circle';
                
                if (data) {
                    let estado = data.toLowerCase();
                    
                    if (estado.includes('preseleccionado') || estado.includes('seleccionado')) {
                        badge = 'success';
                        icon = 'bx-check-circle';
                    } else if (estado.includes('entrevista')) {
                        badge = 'warning';
                        icon = 'bx-time';
                    } else if (estado.includes('rechazado')) {
                        badge = 'danger';
                        icon = 'bx-x-circle';
                    } else if (estado.includes('revisión')) {
                        badge = 'info';
                        icon = 'bx-search';
                    } else {
                        badge = 'secondary';
                        icon = 'bx-file';
                    }
                }
                
                return `<span class="badge bg-${badge}"><i class="bx ${icon} me-1"></i>${data || 'Postulado'}</span>`;
            }
        },
        {
            data: 'fuente',
            render: function (data) {
                let icon = 'bx-world';
                let color = 'text-secondary';
                
                if (data) {
                    let fuente = data.toLowerCase();
                    if (fuente.includes('linkedin')) {
                        icon = 'bxl-linkedin-square';
                        color = 'text-primary';
                    } else if (fuente.includes('indeed')) {
                        icon = 'bx-briefcase-alt';
                        color = 'text-info';
                    } else if (fuente.includes('interno') || fuente.includes('referido')) {
                        icon = 'bx-home';
                        color = 'text-success';
                    } else if (fuente.includes('facebook')) {
                        icon = 'bxl-facebook-square';
                        color = 'text-primary';
                    } else if (fuente.includes('web') || fuente.includes('página')) {
                        icon = 'bx-globe';
                        color = 'text-warning';
                    }
                }
                
                return data ? `<i class="bx ${icon} ${color} fs-5 me-1"></i>${data}` : '-';
            }
        },
        {
            data: 'score',
            render: function (data) {
                let score = parseFloat(data) || 0;
                let badge = 'secondary';
                let icon = 'bx-minus';
                
                if (score >= 80) {
                    badge = 'success';
                    icon = 'bx-check-circle';
                } else if (score >= 50) {
                    badge = 'warning';
                    icon = 'bx-error-circle';
                } else if (score > 0) {
                    badge = 'danger';
                    icon = 'bx-x-circle';
                }
                
                return `
                    <span class="badge bg-${badge} fs-6" style="min-width: 50px;">
                        <i class="bx ${icon} me-1"></i>${score.toFixed(2)}
                    </span>
                `;
            },
            className: 'text-center'
        },
        {
            data: null,
            render: function (data, type, item) {
                let disabled = item.esta_contratado == 1 ? 'disabled' : '';
                let btnClass = item.esta_contratado == 1 ? 'btn-secondary' : 'btn-danger';
                let iconClass = item.esta_contratado == 1 ? 'bx-lock' : 'bx-trash';
                let title = item.esta_contratado == 1 ? 'No se puede eliminar - Ya contratado' : 'Eliminar';
                
                return `
                    <div class="d-flex justify-content-center gap-2">
                        <button class="btn btn-sm btn-info" 
                                onclick="verDetalle(${item._id})" 
                                title="Ver detalle">
                            <i class="bx bx-show"></i>
                        </button>
                        <button class="btn btn-sm ${btnClass}" 
                                onclick="eliminarPostulacion(${item._id})" 
                                ${disabled}
                                title="${title}">
                            <i class="bx ${iconClass}"></i>
                        </button>
                    </div>
                `;
            },
            className: 'text-center'
        }
    ],
    order: [[4, 'desc']], // Ordenar por fecha
    drawCallback: function() {
        // Activar tooltips después de cargar la tabla
        $('[data-bs-toggle="tooltip"]').tooltip();
    }
}));
    }
});

   
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Postulaciones</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Todas las postulaciones
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
                        <div class="card-title d-flex justify-content-between align-items-center">
                            <h5 class="text-primary mb-0">
                                <i class="bx bx-clipboard-check me-1"></i> Gestión de Postulaciones
                            </h5>
                            <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registro_postulacion"
                               class="btn btn-success btn-sm">
                                <i class="bx bx-plus me-0"></i> Nueva
                            </a>
                        </div>

                        <div class="table-responsive pt-3">
                           <table id="tbl_postulaciones" class="table table-striped table-hover align-middle" style="width:100%">
                                <thead class="">
                                    <tr>
                                        <th width="22%">
                                            <i class="bx bx-user me-1"></i>Candidato
                                        </th>
                                        <th width="10%">
                                            <i class="bx bx-category me-1"></i>Tipo
                                        </th>
                                        <th width="15%">
                                            <i class="bx bx-envelope me-1"></i>Contacto
                                        </th>
                                        <th width="10%">
                                            <i class="bx bx-calendar me-1"></i>Fecha
                                        </th>
                                        <th width="10%">
                                            <i class="bx bx-list-ul me-1"></i>Estado
                                        </th>
                                        <th width="10%">
                                            <i class="bx bx-world me-1"></i>Fuente
                                        </th>
                                        <th width="8%" class="text-center">
                                            <i class="bx bx-line-chart me-1"></i>Score
                                        </th>
                                        <th width="5%" class="text-center">
                                            <i class="bx bx-cog"></i>
                                        </th>
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
