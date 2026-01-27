<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
$_id = (isset($_GET['_id'])) ? $_GET['_id'] : '';
?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        let tbl_permisos = $('#tbl_permisos').DataTable($.extend({}, {
            responsive: true,
            language: {
                url: '../assets/plugins/datatable/spanish.json'
            },
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
                            `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_aprobacion_solicitudes&_id=${row.id_solicitud_medica ? row.id_solicitud_medica: ''}&_id_sol=${row.id_solicitud}&_per_id=<?= $_id ?>`;
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
                    data: 'estado_medico',
                    className: 'text-center',
                    render: function(data, type, row) {
                        if (data === null) {
                            return `<span class="badge bg-secondary">Sin revisión</span>`; // Corresponde a total_por_revisar
                        }

                        // Usamos Number() para asegurar la comparación numérica tras el cambio a INT en la DB
                        let estado = Number(data);

                        if (estado === 0) {
                            return `<span class="badge bg-warning text-dark">Pendiente</span>`; // total_pendientes
                        }

                        if (estado === 1) {
                            return `<span class="badge bg-success">Aprobado</span>`; // total_aprobadas
                        }

                        if (estado === 2) {
                            // Corregido: faltaba cerrar la comilla del class y el tag >
                            return `<span class="badge bg-danger">Rechazada</span>`; // total_rechazada
                        }

                        return `<span class="badge bg-info">Otro</span>`;
                    }
                }, {
                    data: null,
                    className: 'text-center',
                    render: function(data, type, row) {

                        return `<button type="button" class="btn btn-xs btn-outline-danger" 
                                            onclick="cargar_solicitud('${row.id_solicitud}')" 
                                            title="Ver PDF de la Solicitud">
                                    <i class="bx bxs-file-pdf fs-7 me-0 fw-bold"></i>
                                    </button>`;
                    }
                }
            ],
            order: [
                [2, 'desc']
            ]
        }));




    });

    function cargar_solicitud(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_solicitud_permiso_medicoC.php?listar_solicitud_pdf=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                if (response) {
                    generarPDF(response);
                }
            }
        });
    }

    function generarPDF(datos_completos) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/SOLICITUDES/index.php?ver_documento_pdf=true',
            type: 'post',
            data: {
                parametros: JSON.stringify(datos_completos)
            },
            xhrFields: {
                responseType: 'blob'
            },
            success: function(blob) {
                var url = window.URL.createObjectURL(blob);
                var win = window.open(url, '_blank');
                win.focus();
            },
            error: function() {
                Swal.fire('Error', 'No se pudo generar el documento', 'error');
            }
        });
    }
</script>

<div class="page-wrapper">
    <div class="page-content">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Solicitudes</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Solicitudes y Justificaciones - Nombre persona
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-4">

                        <!-- FILA SUPERIOR: TÍTULO + BOTONES -->
                        <div class="row align-items-center mb-2">



                            <!-- BOTONES -->
                            <div class="">
                                <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_aprobacion_solicitudes"
                                    class="btn btn-outline-dark btn-sm me-2">
                                    <i class="bx bx-arrow-back"></i> Regresar
                                </a>
                            </div>

                        </div>

                        <div class="table-responsive pt-3">
                            <table class="table table-striped" id="tbl_permisos" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Motivo</th>
                                        <th>Médico</th>
                                        <th>Estado</th>
                                        <th>PDF</th>
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