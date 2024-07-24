    <head>
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
                display: block;
                color: #000000;
                border-color: #000000;
                border-radius: 1em;
                margin: 25px auto;
                width: 100%;
                max-width: 500px;
            }
        </style>
    </head>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#btn_validar").click(function(event) {
                if (!validar_form()) {
                    event.preventDefault();
                }
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
            var ingresar_archivo = $("#image-uploadify")[0].files[0];
            var nombre_archivo = ingresar_archivo ? ingresar_archivo.name : '';

            if (clave !== confirmar_clave) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Las contraseñas no coinciden.",
                });
                return false
            }

            var extension_archivo = nombre_archivo.split('.').pop().toLowerCase();
            if (extension_archivo !== 'p12' && extension_archivo !== 'pfx') {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "El archivo debe estar en formato .p12 o .pfx",
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
                        <ul class="nav nav-tabs nav-success" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" data-bs-toggle="tab" href="#successhome" role="tab" aria-selected="true">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon"><i class='bx bx-home font-18 me-1'></i>
                                        </div>
                                        <div class="tab-title">Certificados Externos</div>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#successprofile" role="tab" aria-selected="false">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon"><i class='bx bx-user-pin font-18 me-1'></i>
                                        </div>
                                        <div class="tab-title">Certificados Almacenados</div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content py-3">
                            <div class="tab-pane fade show active" id="successhome" role="tabpanel">
                                <div class="row">
                                    <div class="col-12 col-lg-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <form>
                                                    <input id="image-uploadify" type="file" accept=".p12,.pfx" multiple>
                                                </form>
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
                                                                    <button type="button" class="btn btn-dark" id="btn_validar">Validar</button>
                                                                </div>
                                                                <p><strong>Nota: </strong>Recuerde que su contraseña y su firma electrónica no se almacenan, estas solo se utilizan una vez para realizar el proceso de validación</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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