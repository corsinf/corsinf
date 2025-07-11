<script type="text/javascript">
    $(document).ready(function() {

    });
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

                            <h5 class="mb-0 text-primary"></h5>

                            <div class="row mx-0">
                                <div class="col-sm-12" id="btn_nuevo">

                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal_blank"><i class="bx bx-plus"></i> Nuevo</button>

                                </div>
                            </div>
                        </div>

                        <!-- Contenedor principal -->
                        <div class="container mt-4">
                            <div class="row">
                                <!-- Columna 1: Formulario -->
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header bg-primary text-white">Ubicación</div>
                                        <div class="card-body">
                                            <button id="btn_ubicacion" class="btn btn-success mb-3">Obtener ubicación</button>
                                            <div class="mb-3">
                                                <label>Latitud:</label>
                                                <input type="text" id="txt_lat" class="form-control" disabled>
                                            </div>
                                            <div class="mb-3">
                                                <label>Longitud:</label>
                                                <input type="text" id="txt_lon" class="form-control" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Columna 2: Mapa -->
                                <div class="col-md-6">
                                    <div class="card h-100">
                                        <div class="card-header bg-info text-white">Mapa</div>
                                        <div class="card-body p-0">
                                            <div id="map_embed" style="height: 400px; width: 100%;"></div>
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
                                    <!-- Video en vivo -->
                                    <div class="mb-3 text-center">
                                        <div class="mx-auto" style="max-width: 320px;">
                                            <video
                                                id="video_stream"
                                                class="w-100 border rounded"
                                                autoplay
                                                playsinline>
                                            </video>
                                        </div>
                                    </div>


                                    <!-- Botones de control -->
                                    <div class="mb-3 text-center">
                                        <button id="btn_start" class="btn btn-success me-2">
                                            <i class="bi bi-camera-reels"></i> Iniciar cámara
                                        </button>
                                        <button id="btn_capture" class="btn btn-primary" disabled>
                                            <i class="bi bi-camera"></i> Capturar foto
                                        </button>
                                    </div>

                                    <!-- Previsualización -->
                                    <div id="preview_container" class="mb-3 text-center d-none">
                                        <label class="form-label fw-bold">Previsualización:</label>
                                        <div class="ratio ratio-4x3">
                                            <canvas id="canvas_preview" class="w-100 h-100 border rounded"></canvas>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>

<script>
    $(function() {
        $('#btn_ubicacion').click(function() {
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

                $('#txt_lat').val(lat);
                $('#txt_lon').val(lon);

                // Mostrar punto exacto (marcador) en el mapa
                let mapUrl = `https://www.google.com/maps/embed/v1/place?key=AIzaSyBpiGf-qNlzyMrRhEbxO8mZG5QvHYHvd2c&q=${lat},${lon}&center=${lat},${lon}&zoom=18&maptype=roadmap`;

                $('#map_embed').html(`
          <iframe width="100%" height="100%" frameborder="0" style="border:0"
            src="${mapUrl}" allowfullscreen></iframe>
        `);

                Swal.fire({
                    icon: 'success',
                    title: '¡Ubicación obtenida!',
                    html: `Latitud: <strong>${lat}</strong><br>Longitud: <strong>${lon}</strong>`
                });
            }, function(err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error al obtener ubicación',
                    text: err.message
                });
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
            // Ajusta el tamaño interno real del canvas antes de dibujar
            let videoEl = $('#video_stream')[0];
            let canvasEl = $('#canvas_preview')[0];
            canvasEl.width = videoEl.videoWidth;
            canvasEl.height = videoEl.videoHeight;

            // Dibuja la imagen
            canvasEl.getContext('2d').drawImage(videoEl, 0, 0, canvasEl.width, canvasEl.height);

            // Muestra el contenedor
            $('#preview_container').removeClass('d-none');

        });
    });
</script>