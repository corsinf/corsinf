<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);


?>

<script src="../js/GENERAL/operaciones_generales.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        tbl_reportes = $('#tbl_reportes').DataTable($.extend({}, configuracion_datatable('Reportes', 'reportes'), {
            reponsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            ajax: {
                url: '../controlador/TALENTO_HUMANO/th_reportesC.php?listar=true',
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    render: function(data, type, item) {
                        href = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_reporte_tabla&_id=${item._id}`;
                        botones = `<a role="button" href="${href}" class="btn btn-primary btn-xs me-1"><i class="lni lni-eye fs-7 me-0 fw-bold"></i></a>`

                        href = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_reporte_atributos&_id=${item._id}`;
                        botones += `<a role="button" href="${href}" class="btn btn-success btn-xs"><i class="bx bx-list-plus fs-7 me-0 fw-bold"></i></a>`

                        return botones;
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        href = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_reportes&_id=${item._id}`;
                        return `<a href="${href}"><u>${item.nombre}</u></a>`;
                    }
                },
                {
                    data: 'descripcion'
                },

                {
                    // data: null,
                    // render: function(data, type, item) {
                    //     return `<button type="button" class="btn btn-primary btn-xs" onclick=""><i class="lni lni-spinner-arrow fs-7 me-0 fw-bold"></i></button>`;
                    // }
                    data: 'nombre_tipo_reporte'
                },

            ],
            order: [
                [1, 'asc']
            ],
        }));
    });

    function importar() {
        var parametros = {
            'datos': $('#txt_recuperado').val(),
        };

        // $('#myModal_espera').modal('show');
        // $('#lbl_msj_espera').text("Conectando y Sincronizando");
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/th_reportesC.php?guardarImport=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response.msj == '') {
                    Swal.fire('Registros Importados', '', 'success');
                } else {
                    Swal.fire('Registros Importados', response.msj, 'info');
                }

                tbl_reportes.ajax.reload(null, false);
                $('#importar_device').modal('hide');
            },
            error: function(xhr, status, error) {
                console.log('Status: ' + status);
                console.log('Error: ' + error);
                console.log('XHR Response: ' + xhr.responseText);

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
                $('#myModal_espera').modal('hide');
            }
        });

    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Reportes</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Lista de Reportes
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

                                        <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_reportes"
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
                                    <table class="table table-striped responsive " id="tbl_reportes" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Acción</th>
                                                <th>Nombre</th>
                                                <th>Descripción</th>
                                                <th>Tipo de Reporte</th>
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