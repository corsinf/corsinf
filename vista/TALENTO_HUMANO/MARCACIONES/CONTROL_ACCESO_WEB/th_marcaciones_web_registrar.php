<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
$_id = '';

if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}
?>
<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script src="../js/GENERAL/operaciones_generales.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        <?php if (isset($_GET['_id'])) { ?>
            cargar_datos(<?= $_id ?>);
        <?php } ?>
    });

    function cargar_datos(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/th_control_acceso_temporalC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                let lat = response[0].latitud;
                let lon = response[0].longitud;

                $('#txt_latitud').val(lat);
                $('#txt_longitud').val(lon);
                $('#txt_descripcion').val(response[0].observacion_aprobacion);
                $('#_id').val(response[0]._id);
                let mapUrl = `https://www.google.com/maps/embed/v1/place?key=AIzaSyBpiGf-qNlzyMrRhEbxO8mZG5QvHYHvd2c&q=${lat},${lon}&center=${lat},${lon}&zoom=18&maptype=roadmap`;

                $('#map_embed').html(`
                    <iframe width="100%" height="100%" frameborder="0" style="border:0"
                        src="${mapUrl}" allowfullscreen></iframe>
                `);

                let fileName = response[0].url_foto;
                if (fileName && fileName !== '') {
                    let url = `../../REPOSITORIO/TALENTO_HUMANO/4157/${fileName}`;
                    $('#preview_img').attr('src', url).removeClass('d-none');
                    $('#canvas_preview').addClass('d-none');
                    $('#preview_container').removeClass('d-none');
                }
            }
        });
    }


    function editar_insertar() {
        var _id = $('#_id').val();
        var txt_latitud = $('#txt_latitud').val();
        var txt_longitud = $('#txt_longitud').val();
        var captured_image = $('#captured_image').val();
        var preview_img = $('#preview_img').val();
        var txt_descripcion = $('#txt_descripcion').val();



        var parametros = {
            '_id': _id,
            'txt_latitud': txt_latitud,
            'txt_longitud': txt_longitud,
            'preview_img': preview_img,
            'captured_image': captured_image,
            'txt_descripcion': txt_descripcion,
        };

        if ($("#form_maraciones").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            insertar(parametros);
            console.log("cumple");
        }
        console.log("No cumple");
        //console.log(parametros);

    }

    function insertar(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/th_control_acceso_temporalC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_marcaciones_web';
                    });
                } else if (response == -2) {
                    //Swal.fire('', 'El nombre del turno ya está en uso', 'warning');
                    $(txt_nombre).addClass('is-invalid');
                    $('#error_txt_nombre').text('El nombre del turno ya está en uso.');
                }
            },

            error: function(xhr, status, error) {
                console.log('Status: ' + status);
                console.log('Error: ' + error);
                console.log('XHR Response: ' + xhr.responseText);

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });

        $('#txt_nombre').on('input', function() {
            $('#error_txt_nombre').text('');
        });
    }
</script>




<style>
    /* Ajusta la altura del mapa */
    #map {
        width: 100%;
        height: 400px;
    }
</style>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Blank</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Blank
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

                            <h5 class="mb-0 text-primary">
                                Registrar marcación
                            </h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_marcaciones_web" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>

                        </div>
                        <form id="form_maraciones">
                            <input type="hidden" class="form-control form-control-sm" id="_id" name="_id">


                            <div class="container mt-4">
                                <div class="row justify-content-center">
                                    <div class="col-md-10">
                                        <div class="card">

                                            <div class="card-header bg-primary text-white">Ubicación actual</div>
                                            <div class="card-body p-0">
                                                <!-- Mapa centrado -->
                                                <div id="map_embed" style="height: 400px; width: 100%;"></div>

                                                <!-- Botón centrado debajo del mapa -->
                                                <div class="d-flex justify-content-center py-3">
                                                    <button type="button" id="btn_ubicacion" class="btn btn-success">
                                                        <i class="bi bi-geo-alt-fill"></i> Actualizar coordenadas
                                                    </button>
                                                </div>

                                                <!-- Inputs ocultos -->
                                                <input type="hidden" class="form-control form-control-sm" id="txt_latitud" name="txt_latitud">
                                                <input type="hidden" class="form-control form-control-sm" id="txt_longitud" name="txt_longitud">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="container mt-5">
                                <div class="card shadow">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0"><i class="bi bi-camera-video-fill"></i> Cámara en vivo</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">

                                            <!-- Columna izquierda: Cámara en vivo -->
                                            <div class="col-md-6">
                                                <div class="text-center mb-3">
                                                    <div class="mx-auto" style="max-width: 320px;">
                                                        <video
                                                            id="video_stream"
                                                            class="w-100 border rounded"
                                                            autoplay
                                                            playsinline>
                                                        </video>
                                                    </div>
                                                </div>

                                                <!-- Botones -->
                                                <div class="text-center">
                                                    <button id="btn_start" class="btn btn-success me-2" type="button">
                                                        <i class="bi bi-camera-reels"></i> Iniciar cámara
                                                    </button>
                                                    <button id="btn_capture" class="btn btn-primary" type="button" disabled>
                                                        <i class="bi bi-camera"></i> Capturar foto
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Columna derecha: Previsualización (más pequeña) -->
                                            <div class="col-md-6">
                                                <div id="preview_container" class="text-center d-none">
                                                    <label class="form-label fw-bold">Previsualización:</label>
                                                    <div style="width: 100%; max-width: 320px; margin: 0 auto;">
                                                        <img id="preview_img" src="" class="w-100 border rounded d-none" style="height: 200px; object-fit: contain;" />
                                                        <canvas id="canvas_preview" class="w-100 border rounded d-none" style="height: 200px;"></canvas>
                                                    </div>
                                                    <input type="hidden" name="captured_image" id="captured_image">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-col">
                                <div class="col-md-12">
                                    <label for="txt_descripcion" class="form-label">Descripción </label>
                                    <textarea class="form-control form-control-sm no_caracteres" name="txt_descripcion" id="txt_descripcion" rows="3" maxlength="200"></textarea>
                                </div>
                            </div>

                            <div class="d-flex justify-content-center pt-2 pb-4">
                                <button id="btn_crear_editar_turno" class="btn btn-success btn-sm px-4 m-0" onclick="editar_insertar();" type="button"><i class="bx bx-save"></i> Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>

<script>
    $(function() {
        // Función para obtener y mostrar ubicación
        function get_Ubicacion() {
            if (!navigator.geolocation) {
                return Swal.fire({
                    icon: 'warning',
                    title: 'No soportado',
                    text: 'Tu navegador no soporta geolocalización.'
                });
            }

            navigator.geolocation.getCurrentPosition(function(pos) {
                let lat = pos.coords.latitude;
                let lon = pos.coords.longitude;

                $('#txt_latitud').val(lat);
                $('#txt_longitud').val(lon);

                // Mostrar punto exacto (marcador) en el mapa
                let mapUrl = `https://www.google.com/maps/embed/v1/place?key=AIzaSyBpiGf-qNlzyMrRhEbxO8mZG5QvHYHvd2c&q=${lat},${lon}&center=${lat},${lon}&zoom=18&maptype=roadmap`;

                $('#map_embed').html(`
                    <iframe width="100%" height="100%" frameborder="0" style="border:0"
                    src="${mapUrl}" allowfullscreen></iframe>
                `);

            }, function(err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error al obtener ubicación',
                    text: err.message
                });
            });
        }

        // Llamar automáticamente al cargar la página
        get_Ubicacion();

        // Si deseas seguir permitiendo botón de actualización manual
        $('#btn_ubicacion').click(function() {
            get_Ubicacion();
            Swal.fire({
                icon: 'info',
                title: 'Ubicación actualizada',
                text: 'Se han actualizado las coordenadas.'
            });
        });
    });
</script>



<script>
    $(function() {
        let $video = $('#video_stream');
        let $canvas = $('#canvas_preview');
        let ctx = $canvas[0].getContext('2d');
        let stream;

        // Iniciar cámara
        $('#btn_start').on('click', async function() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'environment'
                    },
                    audio: false
                });
                // Asignar el stream al video
                $video.prop('srcObject', stream);
                $('#btn_capture').prop('disabled', false);
            } catch (err) {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo acceder a la cámara: ' + err.message
                });
            }
        });

        // Capturar foto
        $('#btn_capture').on('click', function() {
            let videoEl = $('#video_stream')[0];
            let canvasEl = $('#canvas_preview')[0];

            // Ajusta el tamaño del canvas al tamaño del video
            canvasEl.width = videoEl.videoWidth;
            canvasEl.height = videoEl.videoHeight;

            // Dibuja la imagen en el canvas
            canvasEl.getContext('2d').drawImage(videoEl, 0, 0, canvasEl.width, canvasEl.height);

            // Oculta la imagen anterior y muestra el canvas con la nueva foto
            $('#preview_img').addClass('d-none'); // Oculta la imagen antigua (si existía)
            $('#canvas_preview').removeClass('d-none'); // Muestra el canvas con la nueva
            $('#preview_container').removeClass('d-none'); // Asegura que el contenedor esté visible

            // Obtiene el base64 de la imagen
            let imageBase64 = canvasEl.toDataURL('image/jpeg');

            // Asigna el base64 al input oculto
            $('#captured_image').val(imageBase64);
        });


    });
</script>