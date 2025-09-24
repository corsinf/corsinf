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
                url: '../controlador/FIRMAS_ELECTRONICAS/fi_personas_solicitudesC.php?listar=true',
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    render: function(data, type, item) {
                        botones = '';
                        botones += '<div class="d-flex justify-content-center">';
                        botones += `<a class="btn btn-primary btn-xs me-1" onclick="abrirl_modal_consentieminto('${item._id}');"><i class="bx bx-file fs-5 me-0"></i></a>`;
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
                    data: 'nombres_completos'
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
                {
                    data: 'nombre_solicitud'
                },
            ],
            order: [
                [1, 'desc']
            ],
        }));
    });

    function abrirl_modal_consentieminto(_id) {
        $('#modal_ver_pdf_consentieminto').modal('show');
        url = `../controlador/FIRMAS_ELECTRONICAS/fi_personas_solicitudesC.php?pdf_persona_consentimiento=true&id=${_id}`;
        $('#ifr_consentimiento').attr('src', url);
    }
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
                                                <th>Solicitud</th>
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

<div class="modal" id="modal_ver_pdf_consentieminto" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary fw-bold" id="lbl_titulo_referencia_laboral">Acta de Consentimiento</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick=""></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body d-flex justify-content-center">
                <iframe src='' id="ifr_consentimiento" frameborder="0" width="900px" height="700px"></iframe>
            </div>
        </div>
    </div>
</div>