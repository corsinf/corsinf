<style>
    #pdfContainer canvas {
        border: 1px solid black;
        /*            margin: 10px 0;*/
    }
</style>
<script type="text/javascript">
    insertedImages = [];
    $(document).ready(function() {


        cargar_datos_persona_firma();

        // JavaScript fixes - Replace your existing AJAX function with this:
        $(document).ready(function() {
            $("#btn_firmantes").click(function(event) {
                event.preventDefault(); // Evita que el formulario se envíe accidentalmente

                // Verifica que se tenga el archivo de firma (subido o almacenado)
                const hasFile = $('#uploadFirma').val() !== '' ||
                    ($('#uploadFirma').prop('disabled') && $('#uploadFirma').attr("data-filepath") !== undefined);

                if ($('#txt_passFirma').val().trim() === '') {
                    Swal.fire("Clave de firma vacía", "Ingrese la clave de la firma electrónica.", "info");
                    return false;
                }

                if ($('#uploadPDF').val() === '') {
                    Swal.fire("Seleccione un documento PDF", "", "info");
                    return false;
                }

                if (insertedImages.length === 0) {
                    Swal.fire("No se ha encontrado una firma en el documento", "", "info");
                    return false;
                }

                $('#myModal_espera').modal('show');
                var formData = new FormData(document.getElementById("form_documento"));
                formData.append('insertedImages', JSON.stringify(insertedImages));

                // Si el input file está deshabilitado y tiene un data-filepath, usarlo
                if ($('#uploadFirma').prop('disabled') && $('#uploadFirma').attr("data-filepath")) {
                    const filePath = $('#uploadFirma').attr("data-filepath");
                    formData.append('firma_path', filePath);
                    formData.append('usar_firma_almacenada', 'true');
                    console.log("Usando firma almacenada:", filePath);
                }

                $.ajax({
                    url: '../controlador/FIRMADOR/validar_firmaC.php?firmar_documento=true',
                    type: 'post',
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    timeout: 30000, // 30 segundos máximo de espera
                    success: function(response) {
                        $('#myModal_espera').modal('hide');
                        console.log(response);

                        if (response.resp === 1) {
                            Swal.fire({
                                title: 'Documento Firmado',
                                text: 'Descargar Documento',
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonText: 'Descargar'
                            }).then(function() {
                                const url = response.ruta;
                                const link = document.createElement('a');
                                link.href = url;
                                link.download = 'archivo_Firmado.pdf';
                                document.body.appendChild(link);
                                link.click();
                                document.body.removeChild(link);
                            });
                        } else if (response.resp === -2) {
                            Swal.fire("Certificado o clave inválido", "Verifique sus credenciales.", "error");
                            return false;
                        } else if (response.resp === -1 && response.errores) {
                            Swal.fire("Error en la firma", response.errores.join("<br>"), "error");
                            return false;
                        } else {
                            Swal.fire("Error en el proceso", "No se pudo firmar el documento", "error");
                            return false;
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#myModal_espera').modal('hide');
                        console.log("Error:", error);
                        console.log("Status:", status);
                        console.log("Response Text:", xhr.responseText);

                        if (status === 'timeout') {
                            Swal.fire("Tiempo de espera agotado", "La operación tardó demasiado tiempo. Verifique sus credenciales e intente nuevamente.", "warning");
                        } else {
                            Swal.fire("Error en el proceso", "No se pudo firmar el documento. Detalles en la consola.", "error");
                        }
                    }
                });
            });
        });

    })

    function cargar_datos_persona_firma() {
        $('#ddl_persona_firma').select2({
                language: {
                    inputTooShort: function() {
                        return "Por favor ingresa 0 o más caracteres";
                    },
                    noResults: function() {
                        return "No se encontraron resultados";
                    },
                    searching: function() {
                        return "Buscando...";
                    },
                    errorLoading: function() {
                        return "No se encontraron resultados";
                    }
                },
                minimumInputLength: 0,
                placeholder: '-- Seleccione --',
                width: '100%',
                ajax: {
                    url: '../controlador/FIRMADOR/th_personas_firmasC.php?buscar=true',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            })
            .off('select2:select').on('select2:select', function(e) {
                var data = e.params.data.data;

                // Setear la contraseña de la firma
                $("#txt_passFirma").val(data.th_perfir_contrasenia);

                // Aquí suponemos que data.th_perfir_documento_url contiene la URL completa del archivo .p12
                let filePath = data.th_perfir_documento_url;

                // Guardamos la ruta en un atributo "data-filepath" o en un campo oculto
                $("#txt_url_firma").val(filePath);

                // Mostrar un mensaje que indique que el archivo ya está cargado
                if ($("#file-loaded-message").length === 0) {
                    $("#uploadFirma").after('<div id="file-loaded-message" class="text-success mt-1"><small>Archivo cargado: ' + filePath.split('/').pop() + '</small></div>');
                } else {
                    $("#file-loaded-message").html('<small>Archivo cargado: ' + filePath.split('/').pop() + '</small>');
                }

                // Deshabilitar los campos para evitar modificaciones
                $("#uploadFirma").prop("disabled", true);
                $("#txt_passFirma").prop("readonly", true);

                console.log(data);
            });;
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
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Sala de Firmado</li>
                    </ol>
                </nav>
            </div>
        </div>

        <form id="form_documento" enctype="multipart/form-data" method="post">
            <div class="row">
                <div class="col-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                    <label for="ddl_persona_firma" class="form-label form-label-sm">
                                        <h6><strong>1) Seleccione la firma:</strong></h6>
                                        <select class="form-select form-select-sm" id="ddl_persona_firma" name="ddl_persona_firma" maxlenght="5000">
                                            <option value="">Seleccione</option>
                                        </select>
                                        <input id="txt_url_firma" type="hidden" name="txt_url_firma" value="">
                                    </label>
                                </div>

                                <div class="col-8">
                                    <label for="txt_nombreSala" class="form-label form-label-sm">
                                        <h6><strong>2) Seleccione Firma (.p12):</strong></h6>
                                    </label>
                                    <input id="uploadFirma" name="uploadFirma" accept=".p12,.pfx" class="form-control" type="file">
                                </div>
                                <div class="col-4">
                                    <label for="txt_passFirma" class="form-label form-label-sm">
                                        <h6><strong>3) Clave de la Firma:</strong></h6>
                                    </label>
                                    <input type="text" class="form-control" name="txt_passFirma" id="txt_passFirma" value="" placeholder="" required>
                                </div>
                                <div class="col-lg-12">
                                    <h6><strong>4) Documentos a firmar: </strong></h6>
                                    <input id="uploadPDF" name="uploadPDF" class="form-control" type="file" accept="application/pdf">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <br>
                                                    <h3><b>Vista previa del pdf</b></h3>
                                                    <p><span style="color:red">*</span> Para colocar la firma solo haga click en el documento previamente cargado y para borra click en la firma a borrar</p>
                                                </div>
                                                <div class="col-lg-12" id="vista_previa" style="display:none">
                                                    <div id="paginationControls" class="text-end">
                                                        <button type="button" class="btn btn-primary btn-sm" id="prevPage2"> <b>
                                                                << /b> </button>
                                                        <span id="pageNumber2"></span> de <span id="totalPages2"></span>
                                                        <button type="button" class="btn btn-primary btn-sm" id="nextPage2"> <b>></b> </button>
                                                    </div>
                                                    <div id="pdfContainer" style="text-align: center;"></div>
                                                    <div id="paginationControls" class="text-end">
                                                        <button type="button" class="btn btn-primary btn-sm" id="prevPage"><b>
                                                                << /b></button>
                                                        <span id="pageNumber"></span> de <span id="totalPages"></span>
                                                        <button type="button" class="btn btn-primary btn-sm" id="nextPage"><b>></b></button>
                                                    </div>

                                                    <!-- Imagen del cursor personalizada -->
                                                    <img id="cursorImage" src="../img/de_sistema/firma_ejemplo_apudata.png" style=" display: none; width:150pt;height: 50pt;" alt="Cursor Image">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <section class="bg-secondary bg-opacity-25 mb-4">
                                        <div class="d-grid gap-2 p-3">
                                            <button type="button" class="btn btn-dark" name="btn_firmantes" id="btn_firmantes">
                                                <i class='bx bxs-user-plus'></i>Firmar documento
                                            </button>
                                            <!-- <button type="button" onclick="NumFirmas()">Página Siguiente</button> -->
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
    <script>
        let pdfDoc = null;
        currentPage = 1;
        totalPages = 0;
        let canvas = document.createElement('canvas');
        // canvas.style.width = '100%';
        ctx = canvas.getContext('2d');
        cursorImage = document.getElementById('cursorImage');

        document.getElementById('pdfContainer').appendChild(canvas);

        // Función para cargar el PDF
        document.getElementById('uploadPDF').addEventListener('change', function(event) {
            $('#vista_previa').css('display', 'none');
            insertedImages = [];
            currentPage = 1;

            let file = event.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    let typedarray = new Uint8Array(e.target.result);
                    loadPDF(typedarray);
                };
                reader.readAsArrayBuffer(file);
                $('#vista_previa').css('display', 'initial');
            }
        });

        function loadPDF(pdfData) {
            pdfjsLib.getDocument(pdfData).promise.then(function(pdf) {
                pdfDoc = pdf;
                totalPages = pdf.numPages;
                document.getElementById('totalPages').textContent = totalPages;
                document.getElementById('totalPages2').textContent = totalPages;
                renderPage(currentPage);
            });
        }

        // Función para renderizar una página del PDF
        function renderPage(pageNumber) {
            pdfDoc.getPage(pageNumber).then(function(page) {
                let viewport = page.getViewport({
                    scale: 1
                });
                canvas.width = viewport.width;
                canvas.height = viewport.height;

                let renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };

                // Renderiza la página del PDF
                page.render(renderContext).promise.then(function() {
                    reinsertImages(pageNumber); // Reinserta las imágenes si las hay
                });

                document.getElementById('pageNumber').textContent = pageNumber;
                document.getElementById('pageNumber2').textContent = pageNumber;
            });
        }

        // Paginación
        document.getElementById('prevPage').addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                renderPage(currentPage);
            }
        });

        document.getElementById('nextPage').addEventListener('click', function() {
            if (currentPage < totalPages) {
                currentPage++;
                renderPage(currentPage);
            }
        });

        // Paginación
        document.getElementById('prevPage2').addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                renderPage(currentPage);
            }
        });

        document.getElementById('nextPage2').addEventListener('click', function() {
            if (currentPage < totalPages) {
                currentPage++;
                renderPage(currentPage);
            }
        });

        // Evento de clic para insertar o eliminar imágenes
        canvas.addEventListener('click', function(event) {
            if ($('#uploadPDF').val() != '') {
                let rect = canvas.getBoundingClientRect();
                let x = event.clientX - rect.left;
                let y = event.clientY - rect.top;

                let clickedImageIndex = detectClickedImage(x, y);
                if (clickedImageIndex !== -1) {
                    removeImage(clickedImageIndex); // Eliminar la imagen seleccionada
                } else {
                    insertImage(x, y, currentPage); // Insertar una nueva imagen
                }
            } else {
                Swal.fire("Seleccione un archivo pdf", "", "info")
            }
        });



        // Función para insertar una imagen
        function insertImage(x, y, pageNumber) {
            let img = new Image();
            img.src = cursorImage.src;
            img.onload = function() {
                // let width = '150pt';  // Ajusta el tamaño deseado
                // let height = '50pt'; // Ajusta el tamaño deseado
                let width = (150 * 96) / 72; // Convierte el ancho de puntos a píxeles
                let height = (50 * 96) / 72;

                // let xLeft = x - width / 2;
                // let yTop = y - height / 2;


                let xLeft = x;
                let yTop = y;


                // Dibuja la imagen en las coordenadas calculadas
                ctx.drawImage(img, xLeft, yTop, width, height);

                ctx.strokeStyle = 'red'; // Puedes cambiar el color del borde
                ctx.lineWidth = 1; // Puedes ajustar el grosor del borde
                // ctx.strokeRect(x - width / 2, y - height / 2, width, height);  // Dibuja el borde
                ctx.strokeRect(x, y, width, height); // Dibuja el borde


                // Guardar la imagen y sus coordenadas con el nuevo tamaño
                insertedImages.push({
                    page: pageNumber,
                    x: xLeft,
                    y: yTop,
                    width: width,
                    height: height,
                    canvasX: canvas.width,
                    canvasY: canvas.height
                });


                // console.log(`Imagen insertada en la página ${pageNumber}, coordenadas: (${x}, ${y}), tamaño: (${width}, ${height})`);
                // console.log(insertedImages);
            };
        }


        // Función para detectar si se ha hecho clic en una imagen
        function detectClickedImage(x, y) {
            for (let i = 0; i < insertedImages.length; i++) {
                let img = insertedImages[i];
                if (
                    img.page === currentPage &&
                    x >= img.x && x <= img.x + img.width &&
                    y >= img.y && y <= img.y + img.height
                ) {
                    return i; // Retorna el índice de la imagen seleccionada
                }
            }
            return -1; // No se detecta ninguna imagen clicada
        }

        // Función para eliminar una imagen
        function removeImage(index) {
            let img = insertedImages[index];
            insertedImages.splice(index, 1); // Eliminar la imagen de la lista

            // Volver a renderizar la página completa para eliminar cualquier trazo visual y redibujar las imágenes restantes
            renderPage(currentPage);
        }

        // Reinserta las imágenes en la página cuando se vuelve a renderizar
        function reinsertImages(pageNumber) {
            insertedImages.forEach(img => {
                if (img.page === pageNumber) {
                    let image = new Image();
                    image.src = cursorImage.src;
                    image.onload = function() {
                        // Usa las dimensiones almacenadas
                        ctx.drawImage(image, img.x, img.y, img.width, img.height);

                        ctx.strokeStyle = 'red'; // Puedes cambiar el color del borde
                        ctx.lineWidth = 1; // Puedes ajustar el grosor del borde
                        // ctx.strokeRect(x - width / 2, y - height / 2, width, height);  // Dibuja el borde
                        ctx.strokeRect(img.x, img.y, img.width, img.height); // Dibuja el borde

                    };
                }

                //console.log(`Imagen insertada en la página ${pageNumber}, coordenadas: (${img.x}, ${img.y}), tamaño: (${img.width}, ${img.height})`);
            });
        }
    </script>