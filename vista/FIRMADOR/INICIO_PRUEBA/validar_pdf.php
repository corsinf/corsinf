<style>
    .imageuploadify {
        border: 2px dashed #d2d2d2;
        position: relative;
        min-height: 350px;
        min-width: 250px;
        max-width: 1000px;
        margin: auto;
        display: flex;
        padding: 0;
        flex-direction: column;
        text-align: center;
        background-color: #fff;
        color: #000000;
    }

    .imageuploadify .imageuploadify-images-list span.imageuploadify-message {
        font-size: 24px;
        border-top: 1px solid #000000;
        border-bottom: 1px solid #000000;
        padding: 10px;
        display: inline-block;
    }


    .imageuploadify .imageuploadify-images-list button.btn-default {
        display: block !important;
        color: #000000 !important;
        border-color: #000000 !important;
        border-radius: 1em !important;
        margin: 25px auto !important;
        width: 100% !important;
        max-width: 500px !important;
    }

    .imageuploadify .imageuploadify-images-list button.btn-default:hover {
        background-color: #000000 !important;
        /* Fondo negro al pasar el ratón */
        color: #ffffff !important;
        /* Texto blanco al pasar el ratón */
        border-color: #000000 !important;
        /* Borde negro al pasar el ratón */
    }
</style>


<script type="text/javascript">
    $(document).ready(function() {
        $("#btn_validar").click(function(event) {
            if (!validar_form()) {
                event.preventDefault();
            }

            $('#myModal_espera').modal('show');
            var formData = new FormData(document.getElementById("form_doc"));
            $.ajax({
                url: '../controlador/FIRMADOR/validar_firmaC.php?validar_documento=true',
                type: 'post',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                // beforeSend: function () {
                //        $("#foto_alumno").attr('src',"../img/gif/proce.gif");
                //     },
                success: function(response) {
                    $('#myModal_espera').modal('hide');
                    if (response.resp == 1) {
                        $('#tbl_body').html(response.tr);
                        Swal.fire("Firmas Validas en documento", "", "success")
                    } else {
                        Swal.fire(response.msj, "", "error")
                    }
                }
            });


        });
        $('#tbl_firmas').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            responsive: true,
            order: []
        });
    });

    function validar_form() {
        var clave = $("#txt_ingresarClave").val();
        var confirmar_clave = $("#txt_comprobarClave").val();
        var ingresar_archivo = $("#txt_cargar_imagen")[0].files[0];
        var nombre_archivo = ingresar_archivo ? ingresar_archivo.name : '';

        if (clave !== confirmar_clave) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Las contraseñas no coinciden.",
            });
            return false
        }
        if (clave !== confirmar_clave) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Las contraseñas no coinciden.",
            });
            return false
        }

        var extension_archivo = nombre_archivo.split('.').pop().toLowerCase();
        if (extension_archivo !== 'pdf') {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "El archivo debe estar en formato .pdf ",
            });
            return false
        }

        return true;
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Nueva Vista</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Validar PDF</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <div class="col-10">
                <hr />
                <div class="card">
                    <div class="card-body">
                        <div class="card">
                            <form id="form_doc" enctype="multipart/form-data" method="post">
                                <div class="card-body">
                                    <input id="txt_cargar_imagen" name="txt_cargar_imagen" type="file" accept=".pdf" multiple>
                                    <div class="mb-3 mt-4 d-grid gap-2 col-3 mx-auto">
                                        <button  type="button" class="btn btn-dark p-2" id="btn_validar">Validar PDF</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="ms-4 py-1">
                        <h6 class="mb-2"><strong>Indicaciones para validar un PDF:</strong></h6>
                        <ol>
                            <li>Cargue su PDF en el apartado correspondiente</li>
                            <li>De click en <strong>Validar PDF</strong></li>
                        </ol>
                    </div>
                </div>
                <div class="card d-flex justify-content-center">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Identificación</th>
                                        <th>Nombres</th>
                                        <th>Empresa</th>
                                        <th>Fecha y hora</th>
                                    </tr>
                                </thead>
                                <tbody id="tbl_body">
                                   
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <script>
            $(document).ready(function() {
                $('#txt_cargar_imagen').imageuploadify({
                    //'swf': 'path/to/uploadify.swf',
                    //'uploader': 'path/to/uploadify.php', // Archivo PHP que manejará la subida
                    'fileTypeExts': '*.pdf;',
                    'multi': true,
                    // 'onUploadSuccess': function(file, data, response) {
                    //     console.log('El archivo ' + file.name + ' se ha subido correctamente.');
                    // }
                });

                $('.imageuploadify-message').html('Arrastre y suelte sus archivos aquí para cargarlos');
                $('.btn-default').text('O seleccione el archivo para cargar');

            });
        </script>