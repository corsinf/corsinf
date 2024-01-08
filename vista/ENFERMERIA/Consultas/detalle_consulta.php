<?php

$id_consulta = '';

if (isset($_GET['id_consulta'])) {
    $id_consulta = $_GET['id_consulta'];
}

?>

<script type="text/javascript">
    $(document).ready(function() {
        var id_consulta = '<?php echo $id_consulta; ?>';
        ver_pdf();
        //alert(id_consulta);

        // pdf_consulta(id_consulta);
    });


    function ver_pdf()
    {
        var id_consulta = '<?php echo $id_consulta; ?>';
        $('#ifr_pdf_consulta').prop('src','../controlador/consultasC.php?pdf_consulta=true&id_consulta='+id_consulta);
    }

    function pdf_consulta(id_consulta = '') {

        ////Para cargar el pdf en una vista

        /*$.ajax({
            data: {
                id_consulta: id_consulta
            },
            url: '<?php echo $url_general ?>/controlador/consultasC.php?pdf_consulta=true',
            type: 'post',
            method: 'GET',
            dataType: 'arraybuffer', // Ajusta el tipo de datos a 'arraybuffer'
            responseType: 'arraybuffer', // Puede ser necesario en algunos navegadores
            success: function(response) {
                // Crea un blob con el contenido binario del PDF
                var pdfBlob = new Blob([response], {
                    type: 'application/pdf'
                });

                // Crea una URL de datos para el blob
                var pdfDataUrl = URL.createObjectURL(pdfBlob);

                // Actualiza el contenido del iframe con el PDF
                $('#ifr_pdf_consulta').attr('src', pdfDataUrl);
            },
            error: function(error) {
                console.error('Error al cargar el PDF:', error);
            }
        });*/

        $.ajax({
            url: '<?php echo $url_general ?>/controlador/consultasC.php?pdf_consulta=true',
            data: {
                id_consulta: id_consulta
            },
            type: 'post',

            //method: 'GET',
            dataType: 'json', // Especifica que se espera un búfer de bytes
            success: function(response) {
                console.log(response);

            }
        });

        /*$.ajax({
            url: '<?php echo $url_general ?>/controlador/consultasC.php?pdf_consulta=true',
            data: {
                id_consulta: id_consulta
            },
            type: 'POST',
            dataType: 'arraybuffer', // Especifica que se espera un búfer de bytes
            responseType: 'arraybuffer', // Puede ser necesario en algunos navegadores
            success: function(response) {
                // Crea un blob con el contenido binario del PDF
                var pdfBlob = new Blob([response], {
                    type: 'application/pdf'
                });

                // Crea una URL de datos para el blob
                var pdfDataUrl = URL.createObjectURL(pdfBlob);

                // Obtén el iframe por su ID
                var iframe = document.getElementById('ifr_pdf_consulta');

                // Cambia el atributo src del iframe con la URL de datos del PDF
                iframe.src = pdfDataUrl;
            },
            error: function(xhr, status, error) {
                console.error('Error al cargar el PDF:', error);
            }
        });*/


    }
</script>

<div class="page-wrapper">
    <div class="page-content">

        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Enfermería</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Formulario de Consulta
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">

            <div class="col-12">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                            </div>
                            <h5 class="mb-0 text-primary">Formulario de Consulta</h5>
                        </div>
                        <hr>

                        <div class="content">
                            <!-- Content Header (Page header) -->
                            <br>

                            <section class="content">
                                <div class="container-fluid">

                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="panel">
                                            <div class="embed-responsive embed-responsive-4by3" style="margin-top: 30px">
                                                <iframe class="embed-responsive-item" id="ifr_pdf_consulta" width="90%" height="1000" src="">



                                                </iframe>

                                            </div>
                                        </div>
                                    </div>

                                </div><!-- /.container-fluid -->
                            </section>
                            <!-- /.content -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>