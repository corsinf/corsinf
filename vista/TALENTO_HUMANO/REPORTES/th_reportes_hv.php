<script type="text/javascript">
    $(document).ready(function() {
        generar_reporte_control_acceso();
    });

    function generar_reporte_excel() {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_reportes_hvC.php?contra=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response.url) {
                    // Crear un enlace temporal para descargar el archivo
                    var link = document.createElement('a');
                    link.href = response.url;
                    link.download = 'reporte.xlsx'; // Nombre del archivo para la descarga
                    document.body.appendChild(link);
                    link.click(); // Simular el clic para descargar el archivo
                    document.body.removeChild(link); // Eliminar el enlace después
                } else {
                    console.log('Error al generar el reporte');
                }
            },
            error: function() {
                console.log('Error en la solicitud AJAX');
            }
        });
    }

    function generar_reporte_control_acceso() {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_reportesC.php?con=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);
            },
            error: function() {
                console.log('Error en la solicitud AJAX');
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
                            Reportes
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
                        <div class="card-title d-flex align-items-center">

                            <h5 class="mb-0 text-primary"></h5>

                            <div class="row mx-0">
                                <div class="col-sm-12" id="btn_nuevo">

                                    <a href="../controlador/TALENTO_HUMANO/th_reportes_hvC.php?descargarExcel=true" class="btn btn-success btn-sm"><i class="bx bx-plus"></i> Reporte Excel</a>
                                    <a href="../controlador/TALENTO_HUMANO/th_reportesC.php?descargarExcel=true" class="btn btn-success btn-sm"><i class="bx bx-plus"></i> Reporte Excel 2</a>

                                </div>
                            </div>
                        </div>


                        <section class="content pt-2">

                        </section>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>