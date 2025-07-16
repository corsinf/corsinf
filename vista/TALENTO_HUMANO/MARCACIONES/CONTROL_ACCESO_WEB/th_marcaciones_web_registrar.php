<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
$_id = '';

if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}
?>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script src="../js/GENERAL/operaciones_generales.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        <?php if (isset($_GET['_id'])) { ?>
            cargar_datos(<?= $_id ?>);
        <?php } ?>
        get_Ubicacion();

        // Botón de actualización manual
        $('#btn_ubicacion').click(function() {
            get_Ubicacion();
            Swal.fire({
                icon: 'info',
                title: 'Ubicación actualizada',
                text: 'Se han actualizado las coordenadas.'
            });
        });
    });


    let mapa = null;
    let marcador = null;

    function cargar_datos(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/th_control_acceso_temporalC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                let lat = parseFloat(response[0].latitud);
                let lon = parseFloat(response[0].longitud);

                $('#txt_latitud').val(lat);
                $('#txt_longitud').val(lon);
                $('#txt_descripcion').val(response[0].observacion_aprobacion);
                $('#_id').val(response[0]._id);

                // Inicializar o actualizar el mapa Leaflet
                if (!mapa) {
                    mapa = L.map('map_embed').setView([lat, lon], 18);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(mapa);

                    marcador = L.marker([lat, lon]).addTo(mapa).bindPopup("Ubicación registrada").openPopup();
                } else {
                    mapa.setView([lat, lon], 18);
                    marcador.setLatLng([lat, lon]).openPopup();
                }

                // Mostrar imagen si existe
                let fileName = response[0].url_foto_completa;
                if (fileName && fileName !== '') {
                    $('#preview_img').attr('src', fileName).removeClass('d-none');
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
                    Swal.fire('', 'Error', 'warning');
                } else if (response == -1) {
                    Swal.fire('', 'Debe tomar una foto.', 'error');
                }else if (response == -10) {
                    Swal.fire('', 'Lo sentimos, esta función está disponible únicamente para personas registradas como tal.', 'error');
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
            <div class="breadcrumb-title pe-3">Marcaciones</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Registrar Marcación
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <form id="form_maraciones">

            <div class="row">
                <div class="col-xl-12 mx-auto">
                    <div class="card border-top border-0 border-4 border-primary">
                        <div class="card-body pt-3 pe-5 ps-5 pb-0">
                            <div class="card-title d-flex align-items-center">


                                <div><i class="bx bxs-select-multiple me-1 font-22 text-primary"></i>
                                </div>

                                <h5 class="mb-0 text-primary">
                                    <?php
                                    if ($_id == '') {
                                        echo 'Registrar Marcación';
                                    } else {
                                        echo 'Visualizar Turno';
                                    }
                                    ?>
                                </h5>

                                <div class="row m-2">
                                    <div class="col-sm-12">
                                        <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_marcaciones_web" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end row-->

            <div class="row">

                <div class="col-sm-6 mx-auto">
                    <div class="card border-top border-0 border-4 border-primary">
                        <div class="card-header bg-primary text-white">Ubicación actual</div>

                        <div class="card-body">
                            <div class="card-title d-flex align-items-center">
                            </div>

                            <!-- Mapa centrado -->
                            <div id="map_embed" style="height: 400px; width: 100%;"></div>


                            <?php if (!isset($_GET['_id'])) { ?>
                                <!-- Botón centrado debajo del mapa -->
                                <div class="d-flex justify-content-center pt-3">
                                    <button type="button" id="btn_ubicacion" class="btn btn-success btn-sm px-4 m-1">
                                        <i class='bx bx-current-location '></i> Actualizar coordenadas
                                    </button>
                                </div>
                            <?php } ?>

                            <!-- Inputs ocultos -->
                            <input type="hidden" class="form-control form-control-sm" id="txt_latitud" name="txt_latitud">
                            <input type="hidden" class="form-control form-control-sm" id="txt_longitud" name="txt_longitud">
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 mx-auto">
                    <div class="card border-top border-0 border-4 border-primary">
                        <div class="card-header bg-primary text-white">Cámara</div>

                        <div class="card-body ">

                            <style>
                                .face-guide-oval {
                                    width: 140px;
                                    height: 200px;
                                    border: 6px solid rgba(34, 100, 145, 0.9);
                                    border-radius: 50% / 60%;
                                    /* Ovalado */
                                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
                                    pointer-events: none;
                                    z-index: 10;
                                }
                            </style>

                            <div class="row">

                                <?php if (!isset($_GET['_id'])) { ?>
                                    <!-- Columna izquierda: Cámara en vivo -->
                                    <div class="col-12 col-md-12 col-lg-12 col-xl-6">
                                        <div class="camera-wrapper position-relative mx-auto" style="width: 320px; height: 240px;">
                                            <!-- Video -->
                                            <video
                                                id="video_stream"
                                                class="w-100 h-100 border rounded"
                                                autoplay
                                                playsinline>
                                            </video>

                                            <!-- Óvalo guía -->
                                            <div id="pnl_ovalo" class="face-guide-oval position-absolute top-50 start-50 translate-middle" style="display: none;"></div>
                                        </div>

                                        <!-- Botones -->
                                        <div class="d-flex flex-wrap justify-content-center align-items-center gap-2 pt-2 pb-4">
                                            <button id="btn_start" class="btn btn-success btn-sm d-flex align-items-center" type="button">
                                                <i class="bx bx-play me-2"></i> Iniciar cámara
                                            </button>
                                            <button id="btn_capture" class="btn btn-danger btn-sm d-flex align-items-center" type="button" disabled>
                                                <i class="bx bx-stop me-2"></i> Capturar foto
                                            </button>
                                            <button id="btn_flip" class="btn btn-warning btn-sm d-flex align-items-center" type="button">
                                                <i class="bx bx-refresh me-2"></i> Cambiar cámara
                                            </button>
                                        </div>

                                    </div>
                                <?php } ?>

                                <?php
                                $col_class = isset($_GET['_id']) ? 'col-md-6 offset-md-3 text-center' : 'col-12 col-md-12 col-lg-12 col-xl-6';
                                ?>

                                <!-- Columna derecha: Previsualización (más pequeña) -->
                                <div class="<?= $col_class ?>">
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
            </div>

            <div class="row">
                <div class="col-xl-12 mx-auto">
                    <div class="card border-top border-0 border-4 border-primary">
                        <div class="card-body pt-5 pe-5 ps-5">

                            <?php
                            $is_disabled = isset($_GET['_id']) ? 'disabled' : '';
                            ?>

                            <div class="row mb-col">
                                <div class="col-md-12">
                                    <label for="txt_descripcion" class="form-label">Descripción </label>
                                    <textarea class="form-control form-control-sm no_caracteres" name="txt_descripcion" id="txt_descripcion" rows="3" maxlength="200" <?= $is_disabled ?>></textarea>
                                </div>
                            </div>

                            <?php if (!isset($_GET['_id'])) { ?>
                                <div class="d-flex justify-content-end pt-2 pb-2">
                                    <input type="hidden" class="form-control form-control-sm" id="_id" name="_id">

                                    <button id="btn_crear_editar_turno" class="btn btn-success btn-sm px-4 m-0" onclick="editar_insertar();" type="button"><i class="bx bx-save"></i> Guardar</button>
                                </div>
                            <?php } ?>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    //Validacion de formulario
    $(document).ready(function() {
        // Selecciona el label existente y añade el nuevo label
        agregar_asterisco_campo_obligatorio('txt_descripcion');


        $("#form_maraciones").validate({
            rules: {
                txt_descripcion: {
                    required: true,
                },

                txt_latitud: {
                    required: true,
                },

                txt_longitud: {
                    required: true,
                },
                preview_img: {
                    required: true,
                },
                captured_image: {
                    required: true,
                },
            },
            messages: {
                txt_descripcion: {
                    required: "El campo 'Descripción' es obligatorio",
                },
            },

            highlight: function(element) {
                // Agrega la clase 'is-invalid' al input que falla la validación
                $(element).addClass('is-invalid');
                $(element).removeClass('is-valid');
            },
            unhighlight: function(element) {
                // Elimina la clase 'is-invalid' si la validación pasa
                $(element).removeClass('is-invalid');
                $(element).addClass('is-valid');
            }
        });
    });
</script>

<script>
    function get_Ubicacion() {
        if (!navigator.geolocation) {
            return Swal.fire({
                icon: 'warning',
                title: 'Geolocalización no soportada',
                text: 'Tu navegador no soporta geolocalización.'
            });
        }

        navigator.geolocation.getCurrentPosition(function(pos) {
            
            let lat = pos.coords.latitude;
            let lon = pos.coords.longitude;

            console.log(lat + '/' + lon);

            $('#txt_latitud').val(lat);
            $('#txt_longitud').val(lon);

            if (!mapa) {
                mapa = L.map('map_embed').setView([lat, lon], 18);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap'
                }).addTo(mapa);
                marcador = L.marker([lat, lon]).addTo(mapa).bindPopup("Tu ubicación").openPopup();
            } else {
                mapa.setView([lat, lon], 18);
                marcador.setLatLng([lat, lon]).openPopup();
            }

        }, function(err) {
            let msg = '';
            switch (err.code) {
                case 1:
                    msg = 'Permiso denegado por el usuario.';
                    break;
                case 2:
                    msg = 'Ubicación no disponible.';
                    break;
                case 3:
                    msg = 'La solicitud expiró (timeout).';
                    break;
                default:
                    msg = 'Error desconocido.';
                    break;
            }
            Swal.fire({
                icon: 'error',
                title: 'Error al obtener ubicación',
                text: msg
            });
            console.error('Geolocation error:', err);
        }, {
            enableHighAccuracy: true,
            timeout: 20000, 
            maximumAge: 0
        });
    }
</script>

<script>
    $(function() {
        let $video = $('#video_stream');
        let $canvas = $('#canvas_preview');
        let ctx = $canvas[0].getContext('2d');
        let currentStream = null;
        let usingFrontCamera = false; // false = trasera (environment), true = frontal (user)

        async function startCamera() {
            try {
                // Detener cualquier stream anterior
                if (currentStream) {
                    currentStream.getTracks().forEach(track => track.stop());
                }

                let constraints = {
                    video: {
                        facingMode: usingFrontCamera ? 'user' : 'environment'
                    },
                    audio: false
                };

                currentStream = await navigator.mediaDevices.getUserMedia(constraints);
                $video.prop('srcObject', currentStream);
                $('#btn_capture').prop('disabled', false);
                $('#pnl_ovalo').show();

            } catch (err) {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo acceder a la cámara: ' + err.message
                });
            }
        }

        // Iniciar cámara
        $('#btn_start').on('click', startCamera);

        // Girar cámara
        $('#btn_flip').on('click', async function() {
            usingFrontCamera = !usingFrontCamera;
            await startCamera();
        });

        // Capturar foto
        $('#btn_capture').on('click', function() {
            let videoEl = $video[0];
            let canvasEl = $canvas[0];

            canvasEl.width = videoEl.videoWidth;
            canvasEl.height = videoEl.videoHeight;

            ctx.drawImage(videoEl, 0, 0, canvasEl.width, canvasEl.height);

            $('#preview_img').addClass('d-none');
            $canvas.removeClass('d-none');
            $('#preview_container').removeClass('d-none');

            let imageBase64 = canvasEl.toDataURL('image/jpeg');
            $('#captured_image').val(imageBase64);

            // Detener la cámara después de capturar
            if (currentStream) {
                currentStream.getTracks().forEach(track => track.stop());
                currentStream = null;
            }

            $('#btn_capture').prop('disabled', true);
            $('#pnl_ovalo').hide();

        });
    });
</script>