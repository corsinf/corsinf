<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);


?>

<script src="../js/GENERAL/operaciones_generales.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        tbl_personas = $('#tbl_personas').DataTable($.extend({}, configuracion_datatable('Personas', 'personas'), {
            reponsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            ajax: {
                url: '../controlador/INNOVERS/in_personasC.php?listar=true',
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    render: function(data, type, item) {
                        botones = '';
                        botones += '<div class="d-flex justify-content-center">';
                        botones += `<a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=in_registrar_student_consent&_id=${item._id}" class="btn btn-primary btn-xs me-1" onclick=""><i class="bx bx-file fs-5 me-0"></i></a>`;
                        botones += `<a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=in_pdf_stundent_consent&_id=${item._id}" class="btn btn-danger btn-xs me-1" onclick=""><i class="bx bxs-file-pdf fs-5 me-0"></i></a>`;
                        botones += '</div>';

                        return botones;

                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {

                        return fecha_formateada_hora(item.fecha_creacion);
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        href = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=in_registrar_personas&_id=${item._id}`;
                        return `<a href="${href}"><u>${item.primer_apellido} ${item.segundo_apellido} ${item.primer_nombre} ${item.segundo_nombre}</u></a>`;
                    }
                },
                {
                    data: 'cedula'
                },

                {
                    // data: null,
                    // render: function(data, type, item) {
                    //     return `<button type="button" class="btn btn-primary btn-xs" onclick=""><i class="lni lni-spinner-arrow fs-7 me-0 fw-bold"></i></button>`;
                    // }
                    data: 'correo'
                },
                {
                    data: 'telefono_1'
                },
            ],
            order: [
                [1, 'asc']
            ],
        }));
    });
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Personas</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Lista de Personas
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

                                        <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=in_registrar_personas"
                                            type="button" class="btn btn-success btn-sm">
                                            <i class="bx bx-plus me-0 pb-1"></i> Nuevo
                                        </a>

                                    </div>

                                </div>
                            </div>

                            <div class="col-12 col-md-6 text-md-end text-start">
                                <div id="contenedor_botones"></div>
                            </div>
                        </div>

                        <hr>

                        <section class="content pt-2">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped responsive " id="tbl_personas" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th width="5%">Acción</th>
                                                <th>Fecha</th>
                                                <th>Nombres</th>
                                                <th>Cédula</th>
                                                <th>Correo</th>
                                                <th>Teléfono</th>
                                            </tr>
                                        </thead>
                                        <tbody>

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