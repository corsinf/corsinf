<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfobject/2.2.8/pdfobject.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.worker.min.js"></script>
    <style>
        .input-contenedor {
            position: relative;
            margin: 30px 0;
            width: 700px;
        }

        .input-contenedor input {
            width: 100%;
            height: 50px;
            background-color: #000;
            border: none;
            outline: none;
            font-size: 1rem;
            padding: - 35px 0;
            color: #fff;
        }

        .input-contenedor i {
            position: absolute;
            color: #fff;
            font-size: 1.6rem;
            top: 5px;
            left: 0px;
        }

        #ver_pdf {
            width: 100%;
            height: 600px;
            border: 0px solid #000;
        }

        .pdf-image {
            display: block;
            margin: 10px 0;
            width: 100%;
            height: auto;
        }

        .pdf-container {
            position: relative;
            margin: 10px 27px;
            width: 25%;
            height: auto;
            display: inline-block;
            justify-content: space-between;
        }


        .ver_pdf_inicio {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .pdf-options {
            position: absolute;
            top: 50%;
            right: 7px;
            display: flex;
            gap: 10px;
        }

        .pdf-options button {
            background-color: rgba(0, 0, 0, 0.7);
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            color: #fff;
        }


        .pdf-options button:hover {
            background-color: rgba(0, 0, 0, 1.2);
            color: #fff;
        }
    </style>
</head>

<body>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#tbl_grupos').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                },
                responsive: true,
                order: []
            });
        });
    </script>
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Nueva Vista</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Sala de Firmado</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <h6><strong>1) Documentos a firmar en la sala: <label id="contador_pdf">(0/10)</label></strong></h6>
                                <div class="row">
                                    <div class="">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row mt-3">
                                                    <div class="col-12 col-lg-12 d-grid gap-2 mb-4">
                                                        <button id="" type="button" class="btn btn-dark p-2" data-bs-toggle="modal" data-bs-target="#modal_documentos" multiple><i class='bx bxs-file-plus'></i>Seleccione sus documentos</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="" id="ver_pdf_inicio"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                                        <div class="card-body" id="validar_form">
                                            <section class="bg-secondary bg-opacity-25 mb-2">
                                                <div class="p-3">
                                                    <label for="txt_nombreSala" class="form-label form-label-sm">
                                                        <h6><strong>2) Nombre de sala:</strong></h6>
                                                    </label>
                                                    <input type="text" class="form-control" name="txt_nombreSala" id="txt_nombreSala" value="" placeholder="" required>
                                                </div>
                                            </section>
                                            <section class="bg-secondary bg-opacity-25 mb-2">
                                                <div class="p-3">
                                                    <label for="txt_descripcionSala" class="form-label form-label-sm">
                                                        <h6><strong>3) Descripción de la sala:</strong></h6>
                                                    </label>
                                                    <input type="text" class="form-control" name="txt_descripcionSala" id="txt_descripcionSala" value="" placeholder="" required>
                                                </div>
                                            </section>
                                            <section class="bg-secondary bg-opacity-25 mb-4">
                                                <div class="d-grid gap-2 p-3">
                                                    <label for="btn_firmantes" class="form-label form-label-sm">
                                                        <h6><strong>4) Firmantes:</strong></h6>
                                                    </label>
                                                    <button type="button" class="btn btn-dark" name="btn_firmantes" id="btn_firmantes" data-bs-toggle="modal" data-bs-target="#modal_firmantes"><i class='bx bxs-user-plus'></i>Añadir Firmantes</button required>
                                                </div>
                                            </section>
                                            <section class="bg-secondary bg-opacity-25">
                                                <div class="d-grid gap-2">
                                                    <button type="button" class="btn" id="btn_generarSala" disabled><i class='bx bxs-save'></i>Generar Sala</button>
                                                </div>
                                            </section>
                                            <section class="py-3">
                                                <div class="d-grid gap-2">
                                                    <button type="button" class="btn btn-white border border-dark" id="btn_cancelar">Cancelar</button>
                                                </div>
                                            </section>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal modal-xl" id="modal_documentos" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h5><small class="text-body-secondary">Selección de documentos</small></h5>
                        <button type="button" class="btn-close ms-1" data-bs-dismiss="modal"></button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 col-lg-8">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row mt-3">
                                            <div class="col-12 col-lg-12">
                                                <div id="ver_pdf"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4">
                                <div class="card">
                                    <div class="card-body">
                                        <form action="">
                                            <input id="subir_pdf" class="form-control" type="file" accept="application/pdf">
                                            <div class="d-grid gap-2 p-4">
                                                <button type="button" class="btn btn-dark" name="btn_seleccionar_documento" id="btn_seleccionar_documento" disabled>Seleccionar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para abrir el pdf desde la vista -->

        <div class="modal modal-xl" id="modal_documentos_contenedor" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <button type="button" class="btn-close ms-1" data-bs-dismiss="modal"></button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div id='ver_pdf_contenedor'>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="panel">
                                    <div class="embed-responsive embed-responsive-4by3" style="margin-top: 30px">
                                        <iframe class="embed-responsive-item" id="ifr_pdf_firmador" width="90%" height="1000" src="">



                                        </iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br><br><br><br><br><br><br><br><br>

                </div>
            </div>
        </div>


        <div class="modal modal-lg" id="modal_firmantes" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <button type="button" class="btn-close ms-1" data-bs-dismiss="modal"></button>
                        <h5><small class="text-body-secondary">Agregar firmante</small></h5>
                        <button type="button" class="btn btn-primary" id="btn_guardar_firmantes" disabled>Guardar</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <ul class="nav nav-tabs nav-success position-relative" id="validar_firmantes" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#successhome" role="tab" aria-selected="true">
                                                <div class="d-flex align-items-center">
                                                    <div class="tab-title">Añadir por correo electrónico</div>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" data-bs-toggle="tab" href="#successprofile" role="tab" aria-selected="false">
                                                <div class="d-flex align-items-center">
                                                    <div class="tab-title">Añadir un contacto</div>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" data-bs-toggle="tab" href="#successcontact" role="tab" aria-selected="false">
                                                <div class="d-flex align-items-center">
                                                    <div class="tab-title">Añadir un grupo</div>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content py-3">
                                        <div class="tab-pane fade show active" id="successhome" role="tabpanel">
                                            <div class="">
                                                <input type="email" class="form-control comprobar_firmantes" name="txt_emailFirmante" id="txt_emailFirmante" value="" placeholder="Ingrese el correo electrónico del firmante">
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="successprofile" role="tabpanel">
                                            <select class="form-select comprobar_firmantes" aria-label="Default select example">
                                                <option selected>Elija sus contactos para su sala de firmado</option>
                                                <option value="1">José</option>
                                                <option value="2"></option>
                                                <option value="3"></option>
                                            </select>
                                        </div>
                                        <div class="tab-pane fade" id="successcontact" role="tabpanel">
                                            <table class="table table-striped responsive" id="tbl_grupos" style="width:100%">
                                                <thead>
                                                    <th>Nombre del grupo</th>
                                                    <th>Ver integrantes</th>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Grupo1</td>
                                                        <td>adrian, samuel</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Grupo2</td>
                                                        <td>adrian, samuel</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        file_url_buscar = '';

        document.getElementById('subir_pdf').addEventListener('change', function(event) {
            file_url_buscar = mostrar_pdf()
            //console.log(mostrar_pdf())
        });

        function mostrar_pdf() {
            let seleccionar_pdf = false;
            var url_documento = '';
            const documento = event.target.files[0];
            if (documento && documento.type === 'application/pdf') {
                url_documento = URL.createObjectURL(documento);
                //console.log('1 ' + url_documento);
                PDFObject.embed(url_documento, "#ver_pdf");
                seleccionar_pdf = true;
            } else {
                //Poner alert 
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "No has elegido un documento o tu documento no es un PDF",
                });
                return;
            }

            $('#btn_seleccionar_documento').prop('disabled', !seleccionar_pdf)

            //console.log('2 ' +url_documento);

            return url_documento;
        }


        function crear_img_pdfcard(id_boton) {
            $('#modal_documentos_contenedor').modal('show');

            url_pdf = $('#txt_pdf_' + id_boton).val();
            //alert(url_pdf)

            $('#ifr_pdf_firmador').prop('src', url_pdf);

            console.log(lista);

        }

        const input_documento = document.getElementById('subir_pdf');
        const ver_pdf = document.getElementById('ver_pdf_inicio');
        const btn_seleccionar_documento = document.getElementById('btn_seleccionar_documento');

        // Desactiva el botón inicialmente
        btn_seleccionar_documento.disabled = true;

        let documentos_seleccionados = [];

        // Escucha el cambio en el input de archivo
        input_documento.addEventListener('change', (event) => {
            documentos_seleccionados = Array.from(event.target.files);

            // Activa el botón solo si hay archivos seleccionados
            if (documentos_seleccionados.length > 0) {
                btn_seleccionar_documento.disabled = false;
            }
        });

        let contador_indice = 0;
        const maximo_pdf = 10;

        function actualizar_contador() {
            $('#contador_pdf').text(`(${contador_indice}/${maximo_pdf})`);
        }

        // Escucha el clic en el botón
        contador_pdf = 0;
        lista = [];
        btn_seleccionar_documento.addEventListener('click', () => {
            documentos_seleccionados.forEach((documento) => {
                if (documento && documento.type === 'application/pdf') {
                    if (contador_indice >= maximo_pdf) {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Has excedido el límite de documentos permitidos.",
                        });
                        return;
                    }

                    const fileReader = new FileReader();
                    fileReader.onload = (e) => {
                        const pdfData = new Uint8Array(e.target.result);
                        $('#modal_documentos').modal('hide');
                        pdfjsLib.getDocument({
                            data: pdfData
                        }).promise.then((pdf) => {
                            pdf.getPage(1).then((page) => {
                                const scale = 1.5;
                                const viewport = page.getViewport({
                                    scale: scale
                                });
                                const canvas = document.createElement('canvas');
                                const context = canvas.getContext('2d');
                                canvas.height = viewport.height;
                                canvas.width = viewport.width;

                                const contenedor_pdf = document.createElement('div');
                                contenedor_pdf.classList.add('pdf-container');

                                const render_context = {
                                    canvasContext: context,
                                    viewport: viewport
                                };

                                page.render(render_context).promise.then(() => {

                                    lista_obj = {
                                        txt_pdf: ('txt_pdf_' + contador_pdf),
                                        url: file_url_buscar
                                    };

                                    lista.push(lista_obj);

                                    const img = document.createElement('img');
                                    img.src = canvas.toDataURL();
                                    img.classList.add('pdf-image');

                                    const opciones_div = document.createElement('div');
                                    opciones_div.classList.add('pdf-options');

                                    const btn_visualizar = document.createElement('button');
                                    btn_visualizar.textContent = 'Visualizar';
                                    btn_visualizar.type = 'button'; // Especifica el tipo de botón
                                    btn_visualizar.id = contador_pdf;

                                    // Asignar una función al evento 'onclick' del botón
                                    btn_visualizar.onclick = function() {
                                        // Obtener el id del botón clickeado
                                        const id_boton = this.id;

                                        // Llamar a la función y pasar el id del botón como parámetro
                                        crear_img_pdfcard(id_boton);
                                    };

                                    const btn_eliminar = document.createElement('button');
                                    btn_eliminar.textContent = 'Eliminar';

                                    const input_oculto = document.createElement('input');
                                    input_oculto.type = 'hidden';
                                    input_oculto.name = 'txt_pdf_' + contador_pdf;
                                    input_oculto.id = 'txt_pdf_' + contador_pdf;
                                    input_oculto.value = file_url_buscar;

                                    opciones_div.appendChild(btn_visualizar);
                                    opciones_div.appendChild(btn_eliminar);
                                    contenedor_pdf.appendChild(img);
                                    contenedor_pdf.appendChild(opciones_div);
                                    contenedor_pdf.appendChild(input_oculto);
                                    ver_pdf.appendChild(contenedor_pdf);

                                    contador_indice++;
                                    actualizar_contador();

                                    btn_eliminar.addEventListener('click', () => {
                                        contenedor_pdf.remove();
                                        // Decrementar el contador
                                        contador_indice--;
                                        actualizar_contador();
                                    });

                                    btn_eliminar.addEventListener('click', () => {
                                        contenedor_pdf.remove();
                                    });



                                    canvas.remove();
                                    //Poner un remove de la lista del documento que se elimina
                                });
                            });
                        });

                    };
                    fileReader.readAsArrayBuffer(documento);
                }
            });
            contador_pdf++;

            actualizar_contador();

        });
    </script>
    <script>
        $(document).ready(function() {
            function validar_form() {
                let form_valido = true;
                $('#validar_form [required]').each(function() {
                    if (!this.checkValidity()) {
                        form_valido = false;
                        return false;
                    }
                });
                $('#btn_generarSala').prop('disabled', !form_valido);
            }
            $('#validar_form [required]').on('input change', function() {
                validar_form();
            });

            validar_form()

            function comprobar_firmantes() {
                let firmantes = true;
                $('.comprobar_firmantes').each(function() {
                    if (!this.value.trim() == "")
                        firmantes = false;
                    return false;

                });
                $('#btn_guardar_firmantes').prop('disabled', firmantes);
            }
            $('.comprobar_firmantes').on('input change', function() {
                comprobar_firmantes();
            });

            comprobar_firmantes()

        });
    </script>
</body>