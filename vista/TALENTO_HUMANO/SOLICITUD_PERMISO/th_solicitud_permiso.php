<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
    const session = <?= json_encode($_SESSION) ?>;
    const TIPO_USUARIO = session.INICIO.TIPO;
    let id_persona = (TIPO_USUARIO === 'DBA' || TIPO_USUARIO === 'ADMINISTRADOR') ? '' : session.INICIO.NO_CONCURENTE;

    if (id_persona !== "") {
        window.location.href = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_solicitud_persona&_id=${id_persona}`;
    } else {

    }

    $(document).ready(function() {

        let estado = 2;

        let tbl_permisos = $('#tbl_permisos').DataTable($.extend({},
            configuracion_datatable(
                'Motivo',
                'Tipo',
                'Médico',
                'Fecha',
                'Desde',
                'Hasta',
                'Estado'
            ), {
                responsive: true,
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
                    url: '../controlador/TALENTO_HUMANO/th_solicitud_permiso_medicoC.php?listar=true',
                    type: 'POST',
                    data: function(d) {
                        d.estado = estado;
                    },
                    dataSrc: ''
                },
                columns: [{
                        data: 'nombre_completo',
                        render: function(data, type, item) {
                            let href =
                                `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_solicitud_persona&_id=${item.id}`;
                            return `<a href="${href}"><u>${data}</u></a>`;
                        }
                    },
                    {
                        data: 'cedula'
                    },
                    {
                        data: 'telefono'
                    },
                    {
                        data: 'total_solicitudes'
                    },

                ],
                order: [
                    [3, 'desc']
                ]
            }
        ));

        // Filtro por estado
        $('input[name="rbx_variable"]').on('change', function() {
            estado = $(this).val();
            tbl_permisos.ajax.reload();
        });

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

                        <div class="card-title d-flex align-items-center justify-content-between">
                            <h5 class="mb-0 text-primary">Listado de Personas</h5>

                            <div class="d-flex gap-2">
                                <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_solicitud_permiso"
                                    class="btn btn-success btn-sm">
                                    <i class="bx bx-plus"></i> Nuevo Permiso
                                </a>
                            </div>
                        </div>


                        <div class="table-responsive pt-3">
                            <table class="table table-striped" id="tbl_permisos" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Cédula</th>
                                        <th>Telefono</th>
                                        <th>Total solicitudes</th>
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