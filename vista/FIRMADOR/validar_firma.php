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
        $('input[id="txt_cargar_imagen"]').imageuploadify();

        iniciar_validaciones();


        $('#tbl_firmas').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            responsive: true,
            order: []
        });

        // Modificar el texto de los elementos
        $('.imageuploadify-message').html('Arrastre y suelte sus archivos aquí para cargarlos');
        $('.btn-default').text('O seleccione el archivo para cargar');

        // Agregar manejo de eventos para drag and drop
        const drop_area = $('.imageuploadify');

        drop_area.on('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
        });

        drop_area.on('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
        });

        // Interceptar el evento drop antes de que lo maneje imageuploadify
        drop_area.on('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const transferir_datos = e.originalEvent.dataTransfer;
            const archivos = transferir_datos.files;

            if (archivos.length > 0) {
                // Tomar solo el primer archivo
                const archivo = archivos[0];

                if (validar_archivos([archivo])) {
                    // Crear un nuevo FileList con solo el archivo validado
                    const transferir_datos = new DataTransfer();
                    transferir_datos.items.add(archivo);

                    // Actualizar el input file con el nuevo archivo
                    document.getElementById('txt_cargar_imagen').files = transferir_datos.files;

                    $('.imageuploadify-container').delete();

                    // Disparar el evento change manualmente
                    $('#txt_cargar_imagen').trigger('change');

                } else {
                    // Prevenir que imageuploadify procese archivos inválidos
                    return false;
                }
            }
        });
    });

    function iniciar_validaciones() {
        const input_archivo = $("#txt_cargar_imagen");
        input_archivo.on("change", function() {
            validar_archivos(this.files);
        });
    }

    function validar_archivos(archivos) {
        for (let i = 0; i < archivos.length; i++) {
            const archivo = archivos[i];
            const nombre_archivo = archivo.name;
            const extension = obtener_extension_archivo(nombre_archivo);

            if (!extension_valida(extension)) {
                mostrar_alerta("Oops...", "El archivo debe estar en formato .p12 o .pfx");
                return false;
            }
        }
        return true;
    }

    function obtener_extension_archivo(nombre_archivo) {
        return nombre_archivo.split('.').pop().toLowerCase();
    }

    function extension_valida(extension) {
        return extension === 'p12' || extension === 'pfx';
    }

    function mostrar_alerta(titulo, mensaje) {
        Swal.fire({
            icon: "error",
            title: titulo,
            text: mensaje,
        });
    }

    function validar_firma() {

        if (!validar_form()) {
            event.preventDefault();
        }

        var formData = new FormData(document.getElementById("form_firma"));
        $.ajax({
            url: '../controlador/FIRMADOR/validar_firmaC.php?validar_firma=true',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            // beforeSend: function () {
            //        $("#foto_alumno").attr('src',"../img/gif/proce.gif");
            //     },
            success: function(response) {
                if (response.resp == 1) {
                    Swal.fire(response.msj, "", "success")
                } else {
                    Swal.fire(response.msj, "", "error")
                }
            }
        });

    }

    function validar_form() {
        var clave = $("#txt_ingresarClave").val();
        var confirmar_clave = $("#txt_comprobarClave").val();

        if (clave !== confirmar_clave) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Las contraseñas no coinciden.",
            });
            return false
        } else if (confirmar_clave !== clave) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Las contraseñas no coinciden.",
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
                        <li class="breadcrumb-item active" aria-current="page">Validar Firma</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col">
            <hr />
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-primary" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" data-bs-toggle="tab" href="#successhome" role="tab" aria-selected="true">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon"><i class='bx bx-pen font-18 me-1'></i>
                                    </div>
                                    <div class="tab-title">Certificados Externos</div>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#successprofile" role="tab" aria-selected="false">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon"><i class='bx bxs-edit font-18 me-1'></i>
                                    </div>
                                    <div class="tab-title">Certificados Almacenados</div>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content py-3">
                        <div class="tab-pane fade show active" id="successhome" role="tabpanel">
                            <form id="form_firma" enctype="multipart/form-data" method="post">
                                <div class="row">
                                    <div class="col-12 col-lg-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <input id="txt_cargar_imagen" name="txt_cargar_imagen" type="file" accept=".p12,.pfx" multiple>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row mt-3">
                                                    <div class="col-12 col-lg-12">
                                                        <div class="card shadow-none">
                                                            <div class="card-body">
                                                                <h5 class="text-center mb-4 text-bold"><strong>Ingrese su Contraseña</strong></h5>
                                                                <div class="mb-4">
                                                                    <input type="password" class="form-control form-control" name="txt_ingresarClave" id="txt_ingresarClave" value="" placeholder="Ingrese la contraseña de su firma electrónica.">
                                                                </div>
                                                                <div class="mb-4">
                                                                    <input type="password" class="form-control form-control" name="txt_comprobarClave" id="txt_comprobarClave" value="" placeholder="Confirme su contraseña ingresada.">
                                                                </div>
                                                                <div class=" d-grid gap-2 mb-4">
                                                                    <button type="button" class="btn btn-dark" id="btn_validar" onclick="validar_firma();">Validar</button>
                                                                </div>
                                                                <p><strong>Nota: </strong>Recuerde que su contraseña y firma electrónica no se almacenan en nuestros sistemas. Estos datos solo se utilizan una única vez para llevar a cabo el proceso de validación.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </form>
                            <div class="py-3">
                                <h6 class="mb-4"><strong>Indicaciones para validar un certificado:</strong></h6>
                                <ol>
                                    <li>Cargue su certificado en el apartado correspondiente</li>
                                    <li>Ingrese la contraseña del certificado</li>
                                    <li>De click en <strong>Validar</strong></li>
                                </ol>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="successprofile" role="tabpanel">
                            <section class="content pt-2">
                                <div class="container-fluid">
                                    <div class="table-responsive">
                                        <table class="table table-striped responsive " id="tbl_firmas" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Nombre</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Nombre1</td>
                                                </tr>
                                                <tr>
                                                    <td>Nombre2</td>
                                                </tr>
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