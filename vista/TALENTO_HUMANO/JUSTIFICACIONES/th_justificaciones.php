<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

?>

<script src="../js/GENERAL/operaciones_generales.js"></script>
<script type="text/javascript">
$(document).ready(function() {

    tbl_departmento_justifacion = $('#tbl_departmento_justifacion').DataTable($.extend({},
        configuracion_datatable('Justificaciones', 'turnos', 'contenedor_botones_departamento'), {
            reponsive: true,
            language: {
                url: '../assets/plugins/datatable/spanish.json'
            },
            ajax: {
                url: '../controlador/TALENTO_HUMANO/th_justificacionesC.php?listar_departamentos_justificaciones=true',
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    render: function(data, type, item) {
                        href =
                            `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_justificaciones&_id=${item._id}`;
                        return `<a href="${href}"><u>${item.nombre_departamento}</u></a>`;
                    }
                },
                {
                    data: 'tipo_motivo'
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        salida = fecha_formateada_hora(item.fecha_inicio);
                        return salida;
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        salida = fecha_formateada_hora(item.fecha_fin);
                        return salida;
                    }
                },
            ],
            order: [
                [1, 'asc']
            ]
        }));

    tbl_persona_justifacion = $('#tbl_persona_justifacion').DataTable($.extend({}, configuracion_datatable(
        'Justificaciones', 'turnos', 'contenedor_botones_persona'), {
        reponsive: true,
        language: {
            url: '../assets/plugins/datatable/spanish.json'
        },
        ajax: {
            url: '../controlador/TALENTO_HUMANO/th_justificacionesC.php?listar_personas_justificaciones=true',
            dataSrc: ''
        },
        columns: [{
                data: null,
                render: function(data, type, item) {
                    href =
                        `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_justificaciones&_id=${item._id}`;
                    return `<a href="${href}"><u>${item.nombre_persona}</u></a>`;
                }
            },
            {
                data: 'tipo_motivo'
            },
            {
                data: null,
                render: function(data, type, item) {
                    salida = fecha_formateada_hora(item.fecha_inicio);
                    return salida;
                }
            },
            {
                data: null,
                render: function(data, type, item) {
                    salida = fecha_formateada_hora(item.fecha_fin);
                    return salida;
                }
            },
        ],
        order: [
            [1, 'asc']
        ]
    }));

});

function nuevo_registro() {
    // Detectar tab activo
    let tabActivo = $('.nav-link.active .tab-title').text().trim().toLowerCase();

    let tipo = '';
    if (tabActivo === 'departamentos') {
        tipo = 'departamento';
    } else if (tabActivo === 'personas') {
        tipo = 'persona';
    }

    // Redirigir con el parámetro correcto
    window.location.href =
        `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_justificaciones&tipo=${tipo}`;
}
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Justificaciones</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Lista de Justificaciones
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

                        <div class="row">

                            <div class="col-12 col-md-6">
                                <div class="card-title d-flex align-items-center">

                                    <div class="" id="btn_nuevo">
                                        <button type="button" class="btn btn-success btn-sm" onclick="nuevo_registro()">
                                            <i class="bx bx-plus me-0 pb-1"></i> Nuevo
                                        </button>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <hr>

                        <div class="">
                            <div class="">
                                <ul class="nav nav-tabs nav-primary" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#primaryhome" role="tab"
                                            aria-selected="true">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon"><i class='bx bxs-school font-18 me-1'></i>
                                                </div>
                                                <div class="tab-title">Departamentos</div>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-bs-toggle="tab" href="#primaryprofile" role="tab"
                                            aria-selected="false">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon"><i class='bx bx-group font-18 me-1'></i>
                                                </div>
                                                <div class="tab-title">Personas</div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content py-3">
                                    <div class="tab-pane fade show active" id="primaryhome" role="tabpanel">
                                        <section class="content pt-0">
                                            <div class="container-fluid">



                                                <div class="table-responsive">
                                                    <table class="table table-striped responsive "
                                                        id="tbl_departmento_justifacion" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>Departamento</th>
                                                                <th>Motivo</th>
                                                                <th>Fecha Inicial</th>
                                                                <th>Fecha Final</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="">

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div><!-- /.container-fluid -->
                                        </section>

                                    </div>
                                    <div class="tab-pane fade" id="primaryprofile" role="tabpanel">
                                        <section class="content pt-0">
                                            <div class="container-fluid">


                                                <div class="table-responsive">
                                                    <table class="table table-striped responsive "
                                                        id="tbl_persona_justifacion" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>Persona</th>
                                                                <th>Motivo</th>
                                                                <th>Fecha Inicial</th>
                                                                <th>Fecha Final</th>
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
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>