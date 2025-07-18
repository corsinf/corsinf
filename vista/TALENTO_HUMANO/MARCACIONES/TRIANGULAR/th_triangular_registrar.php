<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$_id = '';

if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}

?>


<script src="../js/ACTIVOS_FIJOS/avaluos.js"></script>
<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
<style>
    #map {
        height: 500px;
        width: 100%;
    }

    .control-panel {
        background-color: #f8f9fa;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .custom-marker {
        background: transparent;
        border: none;
        font-size: 20px;
    }

    .editable-point {
        background-color: #fff3cd;
        border: 1px solid #ffeaa7;
    }

    .coord-input {
        width: 120px;
        padding: 2px 5px;
        font-size: 12px;
        border: 1px solid #ddd;
        border-radius: 3px;
    }

    .coord-input:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .selected-marker {
        filter: drop-shadow(0 0 5px #007bff);
    }
</style>

<script type="text/javascript">
    $(document).ready(function() {


        $('input[name="opcion_localizacion"]').on('change', function() {
            const selectedValue = $(this).val();
            // Muestra el formulario correspondiente
            if (selectedValue === '1') {
                $('#pnl_buscar_descripcion').show();
                $('#pnl_buscar_lat_lon').hide();
                $('#pnl_buscar_actual').hide();
            } else if (selectedValue === '2') {
                $('#pnl_buscar_lat_lon').show();
                $('#pnl_buscar_descripcion').hide();
                $('#pnl_buscar_actual').hide();
            } else if (selectedValue === '3') {
                $('#pnl_buscar_actual').show();
                $('#pnl_buscar_descripcion').hide();
                $('#pnl_buscar_lat_lon').hide();
            }
        });

        initMap();

        <?php if (isset($_GET['_id'])) { ?>
            // Si existe ID, activar modo solo lectura
            toggleEdicion(false);
            datos_col(<?= $_id ?>);
            cargar_Datos_Triangulares(<?= $_id ?>);
            $('#pnl_herramientas').hide();
            $('#pnl_informacion').hide();
            $('#pnl_configuracion').hide();
            $('#pnl_radios').hide();

        <?php } else { ?>
            $('#pnl_herramientas').show();
            $('#pnl_configuracion').show();
            $('#pnl_radios').show();
            $('#acciones').show();
        <?php } ?>
        <?php if ($_SESSION['INICIO']['NO_CONCURENTE_TABLA'] == 'th_personas') { ?>
            $('#pnl_herramientas').hide();
            $('#pnl_informacion').hide();
            $('#pnl_configuracion').show();
            $('#pnl_buscar_actual').show();
            $('#pnl_radios').hide();
            toggleEdicion(false);
            $('#acciones').hide();
            actualizar_Ubicacion();
            generar_Area_Automatica();
            ocultar_botones_Table(); 
        <?php } ?>
    });


    function datos_col(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/th_triangularC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                // console.log(response);
                $('#txt_nombre').val(response[0].nombre);
                $('#txt_descripcion_ubicacion').val(response[0].descripcion);
            }
        });
    }

    function editar_insertar() {
        var txt_nombre = $('#txt_nombre').val();
        var txt_descripcion_ubicacion = $('#txt_descripcion_ubicacion').val();

        var parametros = {
            '_id': '<?= $_id ?>',
            'txt_nombre': txt_nombre,
            'txt_descripcion_ubicacion': txt_descripcion_ubicacion,
            puntos: polygonCoordsForDB,
        };

        if (polygonCoordsForDB.length === 0) {
            return Swal.fire('Aviso', 'Debes ingresar al menos un punto.', 'warning');
        }

        if ($("#form_tipo_justificacion").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            insertar(parametros);
        }
        //console.log(parametros);

    }

    function insertar(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/th_triangularC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_triangular';
                    });
                } else if (response == -2) {
                    //Swal.fire('', 'El nombre del dispositivo ya está en uso', 'warning');
                    $(txt_nombre).addClass('is-invalid');
                    $('#error_txt_nombre').text('El nombre ya está en uso.');
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
        $('#txt_descripcion_ubicacion').on('input', function() {
            $('#error_txt_descripcion_ubicacion').text('');
        });
    }

    function delete_datos() {
        var id = '<?= $_id ?>';
        Swal.fire({
            title: 'Eliminar Registro?',
            text: "Esta seguro de eliminar este registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then((result) => {
            if (result.value) {
                eliminar(id);
            }
        })
    }

    function eliminar(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/th_triangularC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_triangular';
                    });
                }
            }
        });
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Tipo de triangulación</div>
            <?php
            //print_r($_SESSION['INICIO']);die(); 

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Agregar la triangulación
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

                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                            </div>
                            <h5 class="mb-0 text-primary">
                                <?php
                                if ($_id == '') {
                                    echo 'Registrar Triangulación';
                                } else {
                                    echo 'Modificar Triangulación';
                                }
                                ?>
                            </h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_triangular" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>
                        </div>
                        <hr>


                        <form id="form_tipo_justificacion">

                            <!-- Fila principal -->
                            <div class="row pt-3">
                                <!-- Columna 1: Nombre de la ubicación -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="txt_nombre" class="form-label">Nombre de la ubicación</label>
                                        <input type="text" class="form-control form-control-sm no_caracteres" id="txt_nombre" name="txt_nombre" maxlength="50" placeholder="Ej. Zona A">
                                        <span id="error_txt_nombre" class="text-danger"></span>
                                    </div>
                                    <div class="mb-3">
                                        <label for="txt_descripcion_ubicacion" class="form-label">Descripción de la ubicación</label>
                                        <textarea class="form-control form-control-sm" id="txt_descripcion_ubicacion" name="txt_descripcion_ubicacion" rows="3" placeholder="Ej. Área utilizada para almacenamiento..."></textarea>
                                        <span id="error_txt_descripcion_ubicacion" class="text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-md-6" id="pnl_radios" style="display: none;">
                                    <label class="col-sm-5 col-form-label fw-bold text-start">¿Cuál opción deseas para la localización?</label>
                                    <div class="col-sm-7">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="opcion_localizacion" id="rbx_descipcion" value="1">
                                            <label class="form-check-label" for="rbx_descipcion">Descripcion</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="opcion_localizacion" id="rbx_long_lati" value="2">
                                            <label class="form-check-label" for="rbx_long_lati">Longitud y Latitud</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="opcion_localizacion" id="rbx_ubi_actual" value="3">
                                            <label class="form-check-label" for="rbx_ubi_actual">Ubicación actual</label>
                                        </div>
                                    </div>
                                    <label class="col-sm-5 col-form-label fw-bold text-start">¿Cómo desea definir el área?</label>
                                    <div class="col-sm-7">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="opcion_area" id="rbx_dibujar" value="1" checked>
                                            <label class="form-check-label" for="rbx_dibujar">Dibujar manualmente</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="opcion_area" id="rbx_automatico" value="2">
                                            <label class="form-check-label" for="rbx_automatico">Generar automáticamente</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="pnl_configuracion" class="row pt-3" style="display: none;">
                                <div class="col-md-6">
                                    <!-- Buscar por descripción -->
                                    <div id="pnl_buscar_descripcion" class="mb-3" style="display: none;">
                                        <label class="form-label fw-bold">Buscar por descripción</label>
                                        <div class="row g-2 align-items-end">
                                            <div class="col-md-10">
                                                <input type="text" class="form-control form-control-sm" id="txt_descripcion" name="txt_descripcion" maxlength="100" placeholder="Ej. Zona Norte">
                                            </div>
                                            <div class="col-md-2">
                                                <button class="btn btn-success btn-sm w-100" type="button" onclick=" buscar_Ubicacion()">
                                                    <i class="bx bx-search"></i> Buscar
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Buscar por Latitud y Longitud -->
                                    <div id="pnl_buscar_lat_lon" class="mb-3" style="display: none;">
                                        <label class="form-label fw-bold">Buscar por latitud y longitud</label>
                                        <div class="row g-2 align-items-end">
                                            <div class="col-md-4">
                                                <input type="text" class="form-control form-control-sm" id="txt_latitud" name="txt_latitud" placeholder="Latitud">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control form-control-sm" id="txt_longitud" name="txt_longitud" placeholder="Longitud">
                                            </div>
                                            <div class="col-md-4">
                                                <button class="btn btn-success btn-sm w-100" type="button" onclick=" buscar_Ubicacion()">
                                                    <i class="bx bx-search"></i> Buscar
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Actualizar ubicación -->
                                    <div id="pnl_buscar_actual" class="mb-3" style="display: none;">
                                        <label class="form-label fw-bold">Actualizar ubicación actual</label>
                                        <button class="btn btn-primary btn-sm w-100" type="button" onclick=" actualizar_Ubicacion()">
                                            <i class="bx bx-location-plus"></i> Actualizar ubicación
                                        </button>
                                    </div>
                                </div>
                            </div>



                            <div class="container-fluid mt-4">
                                <div class="row">
                                    <div class="col-xl-12 mx-auto">
                                        <div class="card border-top border-0 border-4 border-primary">
                                            <div class="card-body p-5">
                                                <div class="card-title d-flex align-items-center">
                                                    <h5 class="mb-0 text-primary"><i class="bi bi-geo-alt-fill"></i> Control de Geofencing</h5>
                                                </div>
                                                <!-- Panel de Control -->
                                                <div class="control-panel">
                                                    <div id="pnl_herramientas" class="row" style="display: none;">
                                                        <div class="col-md-6">
                                                            <h6><i class="bi bi-gear-fill"></i> Herramientas</h6>
                                                            <div class="btn-group" role="group">
                                                                <button type="button" class="btn btn-outline-success" id="btnDefineArea">
                                                                    <i class="bi bi-square"></i> Definir Área
                                                                </button>
                                                                <button type="button" class="btn btn-outline-danger" id="btnClearAll">
                                                                    <i class="bi bi-trash"></i> Limpiar Todo
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h6><i class="bi bi-info-circle-fill"></i> Estado</h6>
                                                            <div id="statusPanel" class="alert alert-info">
                                                                <i class="bi bi-info-circle"></i> Haz clic en "Definir Área" para comenzar
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Mapa -->
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div id="map"></div>
                                                    </div>
                                                </div>

                                                <!-- Tabla de Puntos del Área -->
                                                <div class="row mt-4" id="areaPointsSection" style="display: none;">
                                                    <div class="col-12">
                                                        <div id="pnl_informacion" style="display: none;">
                                                            <h6><i class="bi bi-geo-alt"></i> Puntos del Área Definida</h6>
                                                            <div class="alert alert-info">
                                                                <i class="bi bi-info-circle"></i>
                                                                <strong>Instrucciones:</strong><br>
                                                                • Haz clic en el mapa para agregar puntos<br>
                                                                • Haz clic cerca de una línea existente para insertar un punto en ese segmento<br>
                                                                • Arrastra los puntos para moverlos<br>
                                                                • Edita las coordenadas directamente en la tabla
                                                            </div>
                                                        </div>
                                                        <div class="table-responsive">
                                                            <table class="table table-striped table-sm" id="tbl_area_points">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Punto</th>
                                                                        <th>Latitud</th>
                                                                        <th>Longitud</th>
                                                                        <th id="acciones" style="display:none">Acción</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
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





                            <div class="d-flex justify-content-end pt-2">

                                <?php if ($_id == '') { ?>
                                    <button class="btn btn-success btn-sm px-4 m-0" onclick="editar_insertar()" type="button"><i class="bx bx-save"></i> Guardar</button>
                                <?php } else { ?>
                                    <button class="btn btn-danger btn-sm px-4 m-1" onclick="delete_datos()" type="button"><i class="bx bx-trash"></i> Eliminar</button>
                                <?php } ?>
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
    //Validacion de formulario
    $(document).ready(function() {
        // Selecciona el label existente y añade el nuevo label

        agregar_asterisco_campo_obligatorio('txt_nombre');
        agregar_asterisco_campo_obligatorio('txt_descripcion_ubicacion');


        $("#form_tipo_justificacion").validate({
            rules: {
                txt_nombre: {
                    required: true,
                },
                txt_descripcion_ubicacion: {
                    required: true,
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
    let map;
    let currentPolygon = null;
    let polygonPoints = [];
    let areaMarkers = [];
    let isDefiningArea = false;
    let selectedMarkerIndex = -1;
    let isDragging = false;

    function actualizar_Ubicacion() {
        if (navigator.geolocation) {
            actualizacion_estado('Obteniendo ubicación...', 'info');

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    // Crear el punto en el mapa
                    const latlng = L.latLng(lat, lng);

                    // Centrar el mapa en la ubicación
                    map.setView(latlng, 15);

                    // Si estamos definiendo área, agregar el punto
                    if (isDefiningArea) {
                        // Verificar si causaría intersecciones
                        if (wouldCauseIntersection(latlng)) {
                            actualizacion_estado('No se puede agregar el punto en esta ubicación: causaría que las líneas se crucen', 'warning');
                            return;
                        }
                        addPolygonPoint(latlng);
                        actualizacion_estado('Punto agregado en tu ubicación actual', 'success');
                    } else {
                        // Crear marcador para mostrar la ubicación
                        const textoUbicacion = `Coordenadas: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                        marcar_Ubicacion(lat, lng, textoUbicacion);
                        actualizacion_estado(`Ubicación encontrada: ${textoUbicacion}`, 'success');
                    }
                },
                function(error) {
                    let mensaje = 'Error al obtener la ubicación: ';
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            mensaje += 'Permiso denegado';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            mensaje += 'Ubicación no disponible';
                            break;
                        case error.TIMEOUT:
                            mensaje += 'Tiempo de espera agotado';
                            break;
                        default:
                            mensaje += 'Error desconocido';
                            break;
                    }
                    actualizacion_estado(mensaje, 'danger');
                }
            );
        } else {
            actualizacion_estado('Geolocalización no soportada por este navegador', 'warning');
        }
    }

    // Función para generar área automáticamente alrededor de la ubicación actual
    $('#rbx_automatico').on('change', function() {
        if (this.checked) {
            generar_Area_Automatica();
        }
    });



    function generar_Area_Automatica() {
        // Verificar si hay datos existentes en los campos
        const descripcionExistente = $('#txt_descripcion').val();
        const latitudExistente = $('#txt_latitud').val();
        const longitudExistente = $('#txt_longitud').val();


        // Prioridad 1: Si hay un marcador de ubicación existente en el mapa, usarlo
        if (window.marcadorUbicacion) {
            const latlng = window.marcadorUbicacion.getLatLng();
            const lat = latlng.lat;
            const lng = latlng.lng;

            actualizacion_estado('Generando área automática basada en punto existente en el mapa...', 'info');

            generar_Area_Con_Coordenadas(lat, lng, 'punto existente en el mapa');
            return;
        }

        // Prioridad 2: Si hay coordenadas válidas, usarlas
        if (latitudExistente && longitudExistente &&
            !isNaN(parseFloat(latitudExistente)) && !isNaN(parseFloat(longitudExistente))) {

            const lat = parseFloat(latitudExistente);
            const lng = parseFloat(longitudExistente);

            // Validar rango de coordenadas para Ecuador
            if (lat < -5 || lat > 2 || lng < -82 || lng > -75) {
                actualizacion_estado('Las coordenadas están fuera del rango de Ecuador', 'warning');
                return;
            }

            actualizacion_estado('Generando área automática basada en coordenadas existentes...', 'info');
            generar_Area_Con_Coordenadas(lat, lng, 'coordenadas existentes');

        }
        // Prioridad 3: Si hay descripción, buscar por geocodificación
        else if (descripcionExistente && descripcionExistente.trim() !== '') {
            actualizacion_estado('Buscando ubicación por descripción...', 'info');
            buscar_Por_Descripcion_Para_Area(descripcionExistente.trim());
        }
        // Prioridad 4: Usar geolocalización actual
        else {
            if (navigator.geolocation) {
                actualizacion_estado('Obteniendo ubicación actual para generar área automática...', 'info');

                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        // Llenar los inputs con las coordenadas obtenidas
                        $('#txt_latitud').val(lat.toFixed(6));
                        $('#txt_longitud').val(lng.toFixed(6));

                        generar_Area_Con_Coordenadas(lat, lng, 'ubicación actual');
                    },
                    function(error) {
                        let mensaje = 'Error al obtener la ubicación: ';
                        switch (error.code) {
                            case error.PERMISSION_DENIED:
                                mensaje += 'Permiso denegado';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                mensaje += 'Ubicación no disponible';
                                break;
                            case error.TIMEOUT:
                                mensaje += 'Tiempo de espera agotado';
                                break;
                            default:
                                mensaje += 'Error desconocido';
                                break;
                        }
                        actualizacion_estado(mensaje, 'danger');
                    }
                );
            } else {
                actualizacion_estado('Geolocalización no soportada por este navegador', 'warning');
            }
        }
    }

    function buscar_Por_Descripcion_Para_Area(descripcion) {
        // Usar el servicio de geocodificación de Nominatim (OpenStreetMap)
        const geocodeUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(descripcion)}&countrycodes=EC&limit=1`;

        fetch(geocodeUrl)
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    const result = data[0];
                    const lat = parseFloat(result.lat);
                    const lng = parseFloat(result.lon);

                    // Validar rango de coordenadas para Ecuador
                    if (lat < -5 || lat > 2 || lng < -82 || lng > -75) {
                        actualizacion_estado('La ubicación encontrada está fuera del rango de Ecuador', 'warning');
                        return;
                    }

                    // Llenar los inputs con las coordenadas encontradas
                    $('#txt_latitud').val(lat.toFixed(6));
                    $('#txt_longitud').val(lng.toFixed(6));

                    actualizacion_estado(`Ubicación encontrada: ${result.display_name}`, 'success');
                    generar_Area_Con_Coordenadas(lat, lng, `descripción: "${descripcion}"`);

                } else {
                    actualizacion_estado(`No se encontró ubicación para: "${descripcion}"`, 'warning');
                    // Si no se encuentra por descripción, intentar con geolocalización
                    if (navigator.geolocation) {
                        actualizacion_estado('Intentando obtener ubicación actual como alternativa...', 'info');

                        navigator.geolocation.getCurrentPosition(
                            function(position) {
                                const lat = position.coords.latitude;
                                const lng = position.coords.longitude;

                                $('#txt_latitud').val(lat.toFixed(6));
                                $('#txt_longitud').val(lng.toFixed(6));

                                generar_Area_Con_Coordenadas(lat, lng, 'ubicación actual (alternativa)');
                            },
                            function(error) {
                                actualizacion_estado('No se pudo obtener la ubicación por descripción ni por geolocalización', 'danger');
                            }
                        );
                    } else {
                        actualizacion_estado('No se pudo encontrar la ubicación por descripción', 'danger');
                    }
                }
            })
            .catch(error => {
                console.error('Error en geocodificación:', error);
                actualizacion_estado('Error al buscar la ubicación por descripción', 'danger');

                // Si hay error, intentar con geolocalización como alternativa
                if (navigator.geolocation) {
                    actualizacion_estado('Intentando obtener ubicación actual como alternativa...', 'info');

                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;

                            $('#txt_latitud').val(lat.toFixed(6));
                            $('#txt_longitud').val(lng.toFixed(6));

                            generar_Area_Con_Coordenadas(lat, lng, 'ubicación actual (alternativa)');
                        },
                        function(error) {
                            actualizacion_estado('No se pudo obtener la ubicación por descripción ni por geolocalización', 'danger');
                        }
                    );
                }
            });
    }

    function generar_Area_Con_Coordenadas(lat, lng, origen) {
        // Limpiar datos existentes
        limpiarTodosLosDatos();

        // Crear 4 puntos alrededor de la ubicación (1km de rango)
        const rangoKm = 1; // 1 kilómetro
        const rangoGrados = rangoKm / 111.32; // Conversión aproximada de km a grados

        // Crear los 4 puntos formando un cuadrado
        const puntosArea = [
            L.latLng(lat + rangoGrados, lng - rangoGrados), // Punto superior izquierdo
            L.latLng(lat + rangoGrados, lng + rangoGrados), // Punto superior derecho
            L.latLng(lat - rangoGrados, lng + rangoGrados), // Punto inferior derecho
            L.latLng(lat - rangoGrados, lng - rangoGrados) // Punto inferior izquierdo
        ];

        // Agregar cada punto al polígono
        puntosArea.forEach((punto, index) => {
            agregar_puntos_desde_DB(punto, index + 1, null);
        });

        // Actualizar vista del mapa para mostrar todos los puntos
        if (polygonPoints.length > 0) {
            const group = new L.featureGroup(areaMarkers);
            map.fitBounds(group.getBounds().pad(0.1));

            actualizacion_estado(`Área generada automáticamente con ${puntosArea.length} puntos basada en ${origen}`, 'success');

            // Actualizar botón de definir área
            $('#btnDefineArea').html(' Redefinir Área').removeClass('btn-warning').addClass('btn-outline-success');
        }
    }

    // Evento para el radio button
    $(document).ready(function() {
        $('#rbx_automatico').on('change', function() {
            if (this.checked) {
                generar_Area_Automatica();
            }
        });
        $('#rbx_dibujar').on('change', function() {

            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción limpiará todos los datos ingresados.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, limpiar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                limpiar_mapa();
            });
        });
    });




    // Función para buscar ubicación por descripción, latitud o longitud
    function buscar_Ubicacion() {
        const descripcion = $('#txt_descripcion').val().trim();
        const latitud = $('#txt_latitud').val().trim();
        const longitud = $('#txt_longitud').val().trim();
        limpiar_mapa();
        $('#rbx_dibujar').prop('checked', true);

        // Validar que al menos un campo tenga datos
        if (!descripcion && !latitud && !longitud) {
            actualizacion_estado('Ingresa al menos un criterio de búsqueda (descripción, latitud o longitud)', 'warning');
            return;
        }

        // Si hay latitud y longitud, buscar por coordenadas
        if (latitud && longitud) {
            buscar_Por_Coordenadas(parseFloat(latitud), parseFloat(longitud));
        } else {
            // Buscar por descripción usando geocodificación
            buscar_Por_Descripcion(descripcion);
        }
    }

    // Buscar por coordenadas específicas
    function buscar_Por_Coordenadas(lat, lng) {
        // Validar que las coordenadas sean válidas
        if (isNaN(lat) || isNaN(lng)) {
            actualizacion_estado('Las coordenadas ingresadas no son válidas', 'warning');
            return;
        }

        // Validar rango de coordenadas para Ecuador
        if (lat < -5 || lat > 2 || lng < -82 || lng > -75) {
            actualizacion_estado('Las coordenadas están fuera del rango de Ecuador', 'warning');
            return;
        }

        // Crear el punto en el mapa
        const latlng = L.latLng(lat, lng);

        // Centrar el mapa en las coordenadas
        map.setView(latlng, 15);

        // Si estamos definiendo área, agregar el punto
        if (isDefiningArea) {
            // Verificar si causaría intersecciones
            if (wouldCauseIntersection(latlng)) {
                actualizacion_estado('No se puede agregar el punto en estas coordenadas: causaría que las líneas se crucen', 'warning');
                return;
            }
            addPolygonPoint(latlng);

            actualizacion_estado(`Punto agregado en coordenadas: ${lat}, ${lng}`, 'success');

        } else {
            // Crear marcador para mostrar la ubicación
            const textoUbicacion = `Coordenadas: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
            marcar_Ubicacion(lat, lng, textoUbicacion);
            actualizacion_estado(`Ubicación encontrada: ${textoUbicacion}`, 'success');
        }

        // Limpiar inputs después de la búsqueda exitosa
        $('#txt_descripcion').val('');
        $('#txt_latitud').val('');
        $('#txt_longitud').val('');
    }

    // Buscar por descripción usando geocodificación
    function buscar_Por_Descripcion(descripcion) {
        if (!descripcion) {
            actualizacion_estado('Ingresa una descripción para buscar', 'warning');
            return;
        }

        // Usar el servicio de geocodificación de Nominatim
        const geocodeUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(descripcion)}&countrycodes=ec&limit=5`;

        actualizacion_estado('Buscando ubicación...', 'info');

        fetch(geocodeUrl)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    // Tomar el primer resultado
                    const resultado = data[0];
                    const lat = parseFloat(resultado.lat);
                    const lng = parseFloat(resultado.lon);

                    // Centrar mapa en la ubicación
                    map.setView(L.latLng(lat, lng), 15);

                    // Si estamos definiendo área, agregar el punto
                    if (isDefiningArea) {
                        const latlng = L.latLng(lat, lng);
                        // Verificar si causaría intersecciones
                        if (wouldCauseIntersection(latlng)) {
                            actualizacion_estado('No se puede agregar el punto en esta ubicación: causaría que las líneas se crucen', 'warning');
                            return;
                        }
                        addPolygonPoint(latlng);
                        actualizacion_estado(`Punto agregado: ${resultado.display_name}`, 'success');
                    } else {
                        // Marcar la ubicación encontrada
                        marcar_Ubicacion(lat, lng, resultado.display_name);
                        actualizacion_estado(`Ubicación encontrada: ${resultado.display_name}`, 'success');
                    }

                    // Limpiar inputs después de la búsqueda exitosa
                    $('#txt_descripcion').val('');
                    $('#txt_latitud').val('');
                    $('#txt_longitud').val('');
                } else {
                    actualizacion_estado('No se encontró la ubicación especificada', 'warning');
                }
            })
            .catch(error => {
                console.error('Error en geocodificación:', error);
                actualizacion_estado('Error al buscar la ubicación', 'danger');
            });
    }

    function marcar_Ubicacion(lat, lng, texto = 'Ubicación encontrada') {
        const latlng = L.latLng(lat, lng);

        // Remover marcador anterior si ya existe
        if (window.marcadorUbicacion) {
            map.removeLayer(window.marcadorUbicacion);
        }

        // Crear nuevo marcador
        window.marcadorUbicacion = L.marker(latlng, {
            icon: L.icon({
                iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png',
                iconSize: [32, 32],
                iconAnchor: [16, 32],
                popupAnchor: [0, -32]
            }),
            title: texto
        }).addTo(map);

        // Mostrar popup
        window.marcadorUbicacion.bindPopup(`<strong>${texto}</strong><br>Lat: ${lat}<br>Lng: ${lng}`).openPopup();

        // Centrar mapa en la ubicación
        map.setView(latlng, 15);
    }

    // Función para limpiar marcador de ubicación
    function limpiar_Marcador_Ubicacion() {
        if (window.marcadorUbicacion) {
            map.removeLayer(window.marcadorUbicacion);
            window.marcadorUbicacion = null;
        }
    }



    // Función para cargar datos desde la base de datos
    function cargar_Datos_Triangulares(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/th_triangular_itemC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);

                if (response && response.length > 0) {
                    // Limpiar datos existentes
                    limpiarTodosLosDatos();

                    // Procesar cada punto de la respuesta
                    response.forEach((punto, index) => {
                        const lat = parseFloat(punto.longitud); // Nota: según tu ejemplo, longitud tiene el valor de latitud
                        const lng = parseFloat(punto.latitud); // y latitud tiene el valor de longitud

                        // Validar coordenadas
                        if (!isNaN(lat) && !isNaN(lng)) {
                            const latlng = L.latLng(lat, lng);

                            // Agregar punto al polígono
                            agregar_puntos_desde_DB(latlng, punto.n_punto, punto._id);
                        }
                    });

                    // Actualizar vista del mapa para mostrar todos los puntos
                    if (polygonPoints.length > 0) {
                        const group = new L.featureGroup(areaMarkers);
                        map.fitBounds(group.getBounds().pad(0.1));

                        actualizacion_estado(`Cargados ${response.length} puntos desde la base de datos`, 'success');

                        // Actualizar botón de definir área
                        if (polygonPoints.length >= 3) {
                            $('#btnDefineArea').html('<i class="bi bi-square"></i> Redefinir Área').removeClass('btn-warning').addClass('btn-outline-success');
                        }
                    }
                } else {
                    actualizacion_estado('No se encontraron datos para cargar', 'info');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al cargar datos:', error);
                actualizacion_estado('Error al cargar los datos desde la base de datos', 'danger');
            }
        });
    }

    // Función para agregar punto desde la base de datos
    function agregar_puntos_desde_DB(latlng, numeroPunto, dbId) {
        const pointIndex = polygonPoints.length;
        polygonPoints.push(latlng);

        // Crear marcador para el punto
        const marker = L.marker(latlng, {
            icon: L.divIcon({
                html: `<i class="bi bi-geo-alt-fill text-success"></i>`,
                iconSize: [20, 20],
                className: 'custom-marker'
            }),
            draggable: true
        }).addTo(map);

        // Agregar propiedades personalizadas al marcador
        marker.numeroPunto = numeroPunto;
        marker.dbId = dbId;

        // Agregar eventos al marcador (similar a addPolygonPoint)
        marker.on('click', function(e) {
            e.originalEvent.stopPropagation();
            selectMarker(pointIndex);
        });

        marker.on('dragstart', function() {
            isDragging = true;
        });

        marker.on('drag', function(e) {
            const newPos = e.target.getLatLng();
            polygonPoints[pointIndex] = newPos;
            actualizar_Polygon();
            ocultar_botones_Table();
        });

        marker.on('dragend', function(e) {
            isDragging = false;
            const newPos = e.target.getLatLng();

            // Verificar si la nueva posición causa intersecciones
            const tempPoints = [...polygonPoints];
            tempPoints[pointIndex] = newPos;

            if (checkPolygonIntersections(tempPoints)) {
                actualizacion_estado('Posición no válida: causaría que las líneas se crucen', 'warning');
                marker.setLatLng(polygonPoints[pointIndex]);
                return;
            }

            polygonPoints[pointIndex] = newPos;
            actualizar_Polygon();
            ocultar_botones_Table();
            actualizacion_estado(`Punto ${pointIndex + 1} movido a nueva posición`, 'success');
        });

        // Agregar popup con información del punto
        marker.bindPopup(`
        <strong>Punto ${numeroPunto || (pointIndex + 1)}</strong><br>
        Lat: ${latlng.lat.toFixed(6)}<br>
        Lng: ${latlng.lng.toFixed(6)}<br>
        <small>ID: ${dbId || 'N/A'}</small>
    `);

        areaMarkers.push(marker);

        // Actualizar tabla de puntos
        ocultar_botones_Table();

        // Si hay al menos 3 puntos, crear/actualizar polígono
        if (polygonPoints.length >= 3) {
            actualizar_Polygon();
        }
    }

    // Función para limpiar todos los datos
    function limpiarTodosLosDatos() {
        // Limpiar polígono
        if (currentPolygon) {
            map.removeLayer(currentPolygon);
            currentPolygon = null;
        }

        // Limpiar marcadores
        areaMarkers.forEach(marker => map.removeLayer(marker));
        areaMarkers = [];
        polygonPoints = [];
        selectedMarkerIndex = -1;
        isDefiningArea = false;

        // Limpiar tabla
        ocultar_botones_Table();
    }

    // Función para rellenar campos cuando se hace clic en un marcador
    function llenarCamposDesdeMarker(marker) {
        const latlng = marker.getLatLng();
        $('#txt_latitud').val(latlng.lat.toFixed(6));
        $('#txt_longitud').val(latlng.lng.toFixed(6));

        // Si el marcador tiene información adicional, llenar descripción
        if (marker.numeroPunto) {
            $('#txt_descripcion').val(`Punto ${marker.numeroPunto}`);
        }
    }

    // Modificar la función selectMarker para incluir el llenado de campos
    function selectMarkerWithFields(index) {
        selectMarker(index);

        // Llenar campos con información del marcador seleccionado
        if (areaMarkers[index]) {
            llenarCamposDesdeMarker(areaMarkers[index]);
        }
    }

    // Función para buscar punto por número
    function buscarPorNumero(numeroPunto) {
        const marker = areaMarkers.find(m => m.numeroPunto == numeroPunto);
        if (marker) {
            const index = areaMarkers.indexOf(marker);
            selectMarkerWithFields(index);
            map.setView(marker.getLatLng(), 15);
            marker.openPopup();
            actualizacion_estado(`Punto ${numeroPunto} encontrado y seleccionado`, 'success');
        } else {
            actualizacion_estado(`No se encontró el punto ${numeroPunto}`, 'warning');
        }
    }

    // Función para actualizar datos en la base de datos cuando se mueva un punto
    function actualizarPuntoEnDB(index, nuevaLatitud, nuevaLongitud) {
        const marker = areaMarkers[index];
        if (marker && marker.dbId) {
            // Aquí puedes agregar la llamada AJAX para actualizar en la base de datos
            $.ajax({
                data: {
                    id: marker.dbId,
                    latitud: nuevaLongitud, // Nota: intercambio según tu estructura
                    longitud: nuevaLatitud,
                    n_punto: marker.numeroPunto || (index + 1)
                },
                url: '../controlador/TALENTO_HUMANO/th_triangular_itemC.php?actualizar=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    console.log('Punto actualizado en BD:', response);
                },
                error: function(xhr, status, error) {
                    console.error('Error al actualizar punto en BD:', error);
                }
            });
        }
    }

    // Eventos adicionales para los inputs
    $(document).ready(function() {
        // Permitir buscar con Enter en cualquier input
        $('#txt_descripcion, #txt_latitud, #txt_longitud').on('keypress', function(e) {
            if (e.which === 13) { // Enter
                buscar_Ubicacion();
            }
        });

        // Validar coordenadas mientras se escribe
        $('#txt_latitud, #txt_longitud').on('input', function() {
            const valor = $(this).val();
            if (valor && isNaN(parseFloat(valor))) {
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });
    });

    // Inicializar mapa
    function initMap() {
        map = L.map('map').setView([-0.2313, -78.4675], 13); // Quito, Ecuador como ejemplo

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Eventos del mapa
        map.on('click', bloquear_puntos);
    }

    // Verificar si las líneas se cruzan
    function doLinesIntersect(p1, q1, p2, q2) {
        function orientation(p, q, r) {
            const val = (q.lng - p.lng) * (r.lat - q.lat) - (q.lat - p.lat) * (r.lng - q.lng);
            if (val === 0) return 0;
            return (val > 0) ? 1 : 2;
        }

        function onSegment(p, q, r) {
            return (q.lng <= Math.max(p.lng, r.lng) && q.lng >= Math.min(p.lng, r.lng) &&
                q.lat <= Math.max(p.lat, r.lat) && q.lat >= Math.min(p.lat, r.lat));
        }

        const o1 = orientation(p1, q1, p2);
        const o2 = orientation(p1, q1, q2);
        const o3 = orientation(p2, q2, p1);
        const o4 = orientation(p2, q2, q1);

        if (o1 !== o2 && o3 !== o4) return true;

        if (o1 === 0 && onSegment(p1, p2, q1)) return true;
        if (o2 === 0 && onSegment(p1, q2, q1)) return true;
        if (o3 === 0 && onSegment(p2, p1, q2)) return true;
        if (o4 === 0 && onSegment(p2, q1, q2)) return true;

        return false;
    }

    // Verificar si agregar un punto causará intersecciones
    function wouldCauseIntersection(newPoint) {
        if (polygonPoints.length < 2) return false;

        const lastPoint = polygonPoints[polygonPoints.length - 1];

        // Solo verificar intersecciones con líneas que no sean adyacentes
        for (let i = 0; i < polygonPoints.length - 2; i++) {
            if (doLinesIntersect(lastPoint, newPoint, polygonPoints[i], polygonPoints[i + 1])) {
                return true;
            }
        }

        return false;
    }

    // Evento click en el mapa
    function bloquear_puntos(e) {
        if (isDefiningArea && !isDragging) {
            // Verificar si se hace clic cerca de una línea existente para insertar un punto
            const insertInfo = getInsertPointInfo(e.latlng);

            if (insertInfo) {
                insertPointInSegment(insertInfo.index, e.latlng);
            } else {
                // Verificar si el nuevo punto causará intersecciones
                if (wouldCauseIntersection(e.latlng)) {
                    actualizacion_estado('No se puede agregar el punto: causaría que las líneas se crucen', 'warning');
                    return;
                }
                addPolygonPoint(e.latlng);
            }
        }
    }

    // Verificar si un punto está cerca de una línea para insertar
    function getInsertPointInfo(clickPoint) {
        if (polygonPoints.length < 3) return null;

        const threshold = 0.0001; // Distancia en grados para considerar "cerca"

        for (let i = 0; i < polygonPoints.length; i++) {
            const p1 = polygonPoints[i];
            const p2 = polygonPoints[(i + 1) % polygonPoints.length];

            const distance = distanceToLineSegment(clickPoint, p1, p2);
            if (distance < threshold) {
                return {
                    index: i,
                    distance: distance
                };
            }
        }

        return null;
    }

    // Calcular distancia de un punto a un segmento de línea
    function distanceToLineSegment(point, lineStart, lineEnd) {
        const A = point.lat - lineStart.lat;
        const B = point.lng - lineStart.lng;
        const C = lineEnd.lat - lineStart.lat;
        const D = lineEnd.lng - lineStart.lng;

        const dot = A * C + B * D;
        const lenSq = C * C + D * D;

        if (lenSq === 0) return Math.sqrt(A * A + B * B);

        let t = Math.max(0, Math.min(1, dot / lenSq));

        const projection = {
            lat: lineStart.lat + t * C,
            lng: lineStart.lng + t * D
        };

        const dx = point.lat - projection.lat;
        const dy = point.lng - projection.lng;

        return Math.sqrt(dx * dx + dy * dy);
    }

    // Insertar un punto en un segmento específico
    function insertPointInSegment(segmentIndex, newPoint) {
        // Verificar que la inserción no cause intersecciones
        const tempPoints = [...polygonPoints];
        tempPoints.splice(segmentIndex + 1, 0, newPoint);

        if (checkPolygonIntersections(tempPoints)) {
            actualizacion_estado('No se puede insertar el punto: causaría que las líneas se crucen', 'warning');
            return;
        }

        // Insertar el punto
        polygonPoints.splice(segmentIndex + 1, 0, newPoint);

        // Recrear todos los marcadores
        recreateMarkers();

        // Actualizar polígono y tabla
        actualizar_Polygon();
        ocultar_botones_Table();

        actualizacion_estado(`Punto insertado en el segmento ${segmentIndex + 1}`, 'success');
    }

    // Recrear todos los marcadores después de insertar un punto
    function recreateMarkers() {
        // Limpiar marcadores existentes
        areaMarkers.forEach(marker => map.removeLayer(marker));
        areaMarkers = [];
        selectedMarkerIndex = -1;

        // Crear nuevos marcadores
        polygonPoints.forEach((point, index) => {
            const marker = L.marker(point, {
                icon: L.divIcon({
                    html: `<i class="bi bi-geo-alt-fill text-success"></i>`,
                    iconSize: [20, 20],
                    className: 'custom-marker'
                }),
                draggable: true
            }).addTo(map);

            // Agregar eventos al marcador
            marker.on('click', function(e) {
                e.originalEvent.stopPropagation();
                selectMarker(index);
            });

            marker.on('dragstart', function() {
                isDragging = true;
            });

            marker.on('drag', function(e) {
                const newPos = e.target.getLatLng();
                polygonPoints[index] = newPos;
                actualizar_Polygon();
                ocultar_botones_Table();
            });

            marker.on('dragend', function(e) {
                isDragging = false;
                const newPos = e.target.getLatLng();

                // Verificar si la nueva posición causa intersecciones
                const tempPoints = [...polygonPoints];
                tempPoints[index] = newPos;

                if (checkPolygonIntersections(tempPoints)) {
                    actualizacion_estado('Posición no válida: causaría que las líneas se crucen', 'warning');
                    // Revertir a la posición anterior
                    marker.setLatLng(polygonPoints[index]);
                    return;
                }

                polygonPoints[index] = newPos;
                actualizar_Polygon();
                ocultar_botones_Table();
                actualizacion_estado(`Punto ${index + 1} movido a nueva posición`, 'success');
            });

            // Agregar popup con información del punto
            marker.bindPopup(`<strong>Punto ${index + 1}</strong><br>Lat: ${point.lat.toFixed(6)}<br>Lng: ${point.lng.toFixed(6)}`);
            areaMarkers.push(marker);
        });
    }

    // Agregar punto al polígono
    function addPolygonPoint(latlng) {
        const pointIndex = polygonPoints.length;
        polygonPoints.push(latlng);

        // Crear marcador para el punto
        const marker = L.marker(latlng, {
            icon: L.divIcon({
                html: `<i class="bi bi-geo-alt-fill text-success"></i>`,
                iconSize: [20, 20],
                className: 'custom-marker'
            }),
            draggable: true
        }).addTo(map);

        // Agregar eventos al marcador
        marker.on('click', function(e) {
            e.originalEvent.stopPropagation();
            selectMarker(pointIndex);
        });

        marker.on('dragstart', function() {
            isDragging = true;
        });

        marker.on('drag', function(e) {
            const newPos = e.target.getLatLng();
            polygonPoints[pointIndex] = newPos;
            actualizar_Polygon();
            ocultar_botones_Table();
        });

        marker.on('dragend', function(e) {
            isDragging = false;
            const newPos = e.target.getLatLng();

            // Verificar si la nueva posición causa intersecciones
            const tempPoints = [...polygonPoints];
            tempPoints[pointIndex] = newPos;

            if (checkPolygonIntersections(tempPoints)) {
                actualizacion_estado('Posición no válida: causaría que las líneas se crucen', 'warning');
                // Revertir a la posición anterior
                marker.setLatLng(polygonPoints[pointIndex]);
                return;
            }

            polygonPoints[pointIndex] = newPos;
            actualizar_Polygon();
            ocultar_botones_Table();
            actualizacion_estado(`Punto ${pointIndex + 1} movido a nueva posición`, 'success');
        });

        // Agregar popup con información del punto
        marker.bindPopup(`<strong>Punto ${pointIndex + 1}</strong><br>Lat: ${latlng.lat.toFixed(6)}<br>Lng: ${latlng.lng.toFixed(6)}`);
        areaMarkers.push(marker);

        // Actualizar tabla de puntos
        ocultar_botones_Table();

        // Si hay al menos 3 puntos, crear/actualizar polígono
        if (polygonPoints.length >= 3) {
            actualizar_Polygon();
            actualizacion_estado(`Área definida con ${polygonPoints.length} puntos. Haz clic para agregar más puntos o presiona "Finalizar Área".`);
            $('#btnDefineArea').html('<i class="bi bi-check-square"></i> Finalizar Área').removeClass('btn-outline-success').addClass('btn-warning');
        } else {
            actualizacion_estado(`Punto ${polygonPoints.length} agregado. Necesitas al menos 3 puntos para formar un área.`);
        }
    }

    // Verificar intersecciones en todo el polígono
    function checkPolygonIntersections(points) {
        if (points.length < 4) return false;

        for (let i = 0; i < points.length; i++) {
            const p1 = points[i];
            const p2 = points[(i + 1) % points.length];

            // Verificar intersección con líneas no adyacentes
            for (let j = i + 2; j < points.length; j++) {
                // Evitar verificar la última línea con la primera (son adyacentes)
                if (j === points.length - 1 && i === 0) continue;

                const p3 = points[j];
                const p4 = points[(j + 1) % points.length];

                if (doLinesIntersect(p1, p2, p3, p4)) {
                    return true;
                }
            }
        }
        return false;
    }

    // Seleccionar marcador
    function selectMarker(index) {
        // Deseleccionar marcador anterior
        if (selectedMarkerIndex >= 0 && areaMarkers[selectedMarkerIndex]) {
            areaMarkers[selectedMarkerIndex].getElement().classList.remove('selected-marker');
        }

        // Seleccionar nuevo marcador
        selectedMarkerIndex = index;
        if (areaMarkers[index]) {
            areaMarkers[index].getElement().classList.add('selected-marker');
            actualizacion_estado(`Punto ${index + 1} seleccionado. Puedes arrastrarlo para moverlo.`, 'info');
        }
    }

    // Actualizar polígono
    function actualizar_Polygon() {
        if (currentPolygon) {
            map.removeLayer(currentPolygon);
            currentPolygon = null;
        }

        if (polygonPoints.length >= 3) {
            currentPolygon = L.polygon(polygonPoints, {
                color: 'blue',
                fillColor: 'lightblue',
                fillOpacity: 0.3
            }).addTo(map);
        }
    }
    let polygonCoordsForDB = [];

    // Actualizar tabla de puntos del área
    function ocultar_botones_Table() {
        const tbody = $('#tbl_area_points tbody');
        tbody.empty();

        if (polygonPoints.length > 0) {
            $('#areaPointsSection').show();


            // Actualiza el array global
            polygonCoordsForDB = polygonPoints.map((p, index) => ({
                punto: `${index + 1}`,
                lat: parseFloat(p.lat.toFixed(6)),
                lng: parseFloat(p.lng.toFixed(6))
            }));
            polygonPoints.forEach((point, index) => {
                const row = `
    <tr>
        <td>Punto ${index + 1}</td>
        <td>${point.lat.toFixed(6)}</td>
        <td>${point.lng.toFixed(6)}</td>
        <td>
            <button class="btn btn-sm btn-outline-danger" onclick="removeAreaPoint(${index})">
                <i class="bx bx-trash"></i>
            </button>
        </td>
    </tr>
`;
                tbody.append(row);
            });
        } else {
            $('#areaPointsSection').hide();
        }
    }


    // Actualizar punto desde la tabla
    function updatePointFromTable(index, coord, value) {
        const numValue = parseFloat(value);
        if (isNaN(numValue)) {
            actualizacion_estado('Valor de coordenada inválido', 'warning');
            ocultar_botones_Table(); // Restaurar valor anterior
            return;
        }

        const newPoint = {
            ...polygonPoints[index]
        };
        newPoint[coord] = numValue;

        // Verificar si la nueva posición causa intersecciones
        const tempPoints = [...polygonPoints];
        tempPoints[index] = newPoint;

        if (checkPolygonIntersections(tempPoints)) {
            actualizacion_estado('Coordenada no válida: causaría que las líneas se crucen', 'warning');
            ocultar_botones_Table(); // Restaurar valor anterior
            return;
        }

        polygonPoints[index] = newPoint;

        // Actualizar marcador en el mapa
        if (areaMarkers[index]) {
            areaMarkers[index].setLatLng(newPoint);
            // Actualizar popup
            areaMarkers[index].bindPopup(`<strong>Punto ${index + 1}</strong><br>Lat: ${newPoint.lat.toFixed(6)}<br>Lng: ${newPoint.lng.toFixed(6)}`);
        }

        actualizar_Polygon();
        actualizacion_estado(`Punto ${index + 1} actualizado desde la tabla`, 'success');
    }

    // Remover punto del área
    function removeAreaPoint(index) {
        if (polygonPoints.length <= 3) {
            if (!confirm('¿Estás seguro de que quieres eliminar este punto? El polígono tendrá menos de 3 puntos.')) {
                return;
            }
        } else {
            if (!confirm('¿Estás seguro de que quieres eliminar este punto?')) {
                return;
            }
        }

        // Remover punto del array
        polygonPoints.splice(index, 1);

        // Recrear todos los marcadores
        recreateMarkers();

        // Actualizar polígono
        actualizar_Polygon();

        // Actualizar tabla
        ocultar_botones_Table();

        // Actualizar estado
        if (polygonPoints.length >= 3) {
            actualizacion_estado(`Área actualizada con ${polygonPoints.length} puntos.`);
        } else if (polygonPoints.length > 0) {
            actualizacion_estado(`${polygonPoints.length} puntos agregados. Necesitas al menos 3 puntos para formar un área.`);
        } else {
            actualizacion_estado('No hay puntos definidos. Haz clic en "Definir Área" para comenzar.');
        }
    }

    // Actualizar estado
    function actualizacion_estado(message, type = 'info') {
        const statusPanel = $('#statusPanel');
        statusPanel.removeClass('alert-info alert-success alert-warning alert-danger');
        statusPanel.addClass(`alert-${type}`);
        statusPanel.html(`<i class="bi bi-info-circle"></i> ${message}`);
    }

    // Eventos de botones
    $('#btnDefineArea').click(function() {
        if (!isDefiningArea) {
            // Iniciar definición de área
            isDefiningArea = true;
            polygonPoints = [];
            areaMarkers = [];
            selectedMarkerIndex = -1;

            // Limpiar polígono anterior
            if (currentPolygon) {
                map.removeLayer(currentPolygon);
                currentPolygon = null;
            }

            // Limpiar marcadores anteriores
            map.eachLayer(function(layer) {
                if (layer instanceof L.Marker) {
                    map.removeLayer(layer);
                }
            });

            ocultar_botones_Table();
            actualizacion_estado('Haz clic en el mapa para definir los puntos del área. Necesitas al menos 3 puntos.');
            $(this).html('<i class="bi bi-square"></i> Definiendo...').removeClass('btn-outline-success').addClass('btn-warning');
        } else {
            // Finalizar definición de área
            if (polygonPoints.length >= 3) {
                isDefiningArea = false;
                actualizacion_estado('Área definida correctamente. Puedes editar los puntos haciendo clic en ellos o desde la tabla.', 'success');
                $(this).html('<i class="bi bi-square"></i> Redefinir Área').removeClass('btn-warning').addClass('btn-outline-success');
            } else {
                actualizacion_estado('Necesitas al menos 3 puntos para definir un área.', 'warning');
            }
        }
    });

    $('#btnClearAll').click(function() {
        $('#rbx_dibujar').on('change', function() {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción limpiará todos los datos ingresados.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, limpiar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                limpiar_mapa();

            });
        });
    });

    // Inicializar cuando el documento esté listo
    $(document).ready(function() {
        initMap();
    });


    let modoEdicion = true;

    function limpiar_mapa() {
        if (currentPolygon) {
            map.removeLayer(currentPolygon);
            currentPolygon = null;
        }
        polygonPoints = [];
        areaMarkers = [];
        isDefiningArea = false;
        selectedMarkerIndex = -1;

        // Limpiar todos los marcadores
        map.eachLayer(function(layer) {
            if (layer instanceof L.Marker) {
                map.removeLayer(layer);
            }
        });

        ocultar_botones_Table();
        actualizacion_estado('Todo limpiado. Puedes comenzar de nuevo.');
        $('#btnDefineArea').html('<i class="bi bi-square"></i> Definir Área').removeClass('btn-warning').addClass('btn-outline-success');
    }

    // Función para bloquear/desbloquear la edición
    function toggleEdicion(permitirEdicion) {
        modoEdicion = permitirEdicion;

        if (!permitirEdicion) {
            // Ocultar botones de control
            $('#btnDefineArea').hide();
            $('#btnClearAll').hide();

            // Hacer marcadores no arrastrables
            areaMarkers.forEach(marker => {
                marker.dragging.disable();
            });

            // Deshabilitar inputs de coordenadas en la tabla
            $('.coord-input').prop('disabled', true);

            actualizacion_estado('Modo solo lectura: Los puntos no se pueden modificar', 'info');
        } else {
            // Mostrar botones de control
            $('#btnDefineArea').show();
            $('#btnClearAll').show();

            // Hacer marcadores arrastrables
            areaMarkers.forEach(marker => {
                marker.dragging.enable();
            });

            // Habilitar inputs de coordenadas en la tabla
            $('.coord-input').prop('disabled', false);

            actualizacion_estado('Modo edición: Los puntos se pueden modificar', 'success');
        }
    }

    // Modificar la función ocultar_botones_Table para ocultar botones de eliminar
    function ocultar_botones_Table() {
        const tbody = $('#tbl_area_points tbody');
        tbody.empty();

        if (polygonPoints.length > 0) {
            $('#areaPointsSection').show();

            polygonCoordsForDB = polygonPoints.map((p, index) => ({
                punto: `${index + 1}`,
                lat: parseFloat(p.lat.toFixed(6)),
                lng: parseFloat(p.lng.toFixed(6))
            }));

            polygonPoints.forEach((point, index) => {
                // Determinar qué botones mostrar según el modo
                let botonesAccion = '';
                if (modoEdicion) {
                    botonesAccion = `
                    <button class="btn btn-sm btn-outline-danger" onclick="removeAreaPoint(${index})">
                        <i class="bx bx-trash"></i>
                    </button>
                `;
                }
                const row = `
                <tr>
                    <td>Punto ${index + 1}</td>
                    <td>
                        <input type="number" class="coord-input" 
                               value="${point.lat.toFixed(6)}" 
                               step="0.000001" 
                               ${!modoEdicion ? 'disabled' : ''}
                               onchange="updatePointFromTable(${index}, 'lat', this.value)">
                    </td>
                    <td>
                        <input type="number" class="coord-input" 
                               value="${point.lng.toFixed(6)}" 
                               step="0.000001" 
                               ${!modoEdicion ? 'disabled' : ''}
                               onchange="updatePointFromTable(${index}, 'lng', this.value)">
                    </td>
                    <td>
                        ${botonesAccion}
                    </td>
                </tr>
            `;
                tbody.append(row);
            });
        } else {
            $('#areaPointsSection').hide();
        }
    }

    // Modificar la función bloquear_puntos para no permitir agregar puntos si no está en modo edición
    function bloquear_puntos(e) {
        if (isDefiningArea && !isDragging && modoEdicion) {
            // Verificar si se hace clic cerca de una línea existente para insertar un punto
            const insertInfo = getInsertPointInfo(e.latlng);

            if (insertInfo) {
                insertPointInSegment(insertInfo.index, e.latlng);
            } else {
                // Verificar si el nuevo punto causará intersecciones
                if (wouldCauseIntersection(e.latlng)) {
                    actualizacion_estado('No se puede agregar el punto: causaría que las líneas se crucen', 'warning');
                    return;
                }
                addPolygonPoint(e.latlng);
            }
        }
    }

    // Modificar la función agregar_puntos_desde_DB para aplicar el modo edición
    function agregar_puntos_desde_DB(latlng, numeroPunto, dbId) {
        const pointIndex = polygonPoints.length;
        polygonPoints.push(latlng);

        // Crear marcador para el punto
        const marker = L.marker(latlng, {
            icon: L.divIcon({
                html: `<i class="bi bi-geo-alt-fill text-success"></i>`,
                iconSize: [20, 20],
                className: 'custom-marker'
            }),
            draggable: modoEdicion // Solo draggable si está en modo edición
        }).addTo(map);

        // Agregar propiedades personalizadas al marcador
        marker.numeroPunto = numeroPunto;
        marker.dbId = dbId;

        // Agregar eventos al marcador solo si está en modo edición
        if (modoEdicion) {
            marker.on('click', function(e) {
                e.originalEvent.stopPropagation();
                selectMarker(pointIndex);
            });

            marker.on('dragstart', function() {
                isDragging = true;
            });

            marker.on('drag', function(e) {
                const newPos = e.target.getLatLng();
                polygonPoints[pointIndex] = newPos;
                actualizar_Polygon();
                ocultar_botones_Table();
            });

            marker.on('dragend', function(e) {
                isDragging = false;
                const newPos = e.target.getLatLng();

                // Verificar si la nueva posición causa intersecciones
                const tempPoints = [...polygonPoints];
                tempPoints[pointIndex] = newPos;

                if (checkPolygonIntersections(tempPoints)) {
                    actualizacion_estado('Posición no válida: causaría que las líneas se crucen', 'warning');
                    marker.setLatLng(polygonPoints[pointIndex]);
                    return;
                }

                polygonPoints[pointIndex] = newPos;
                actualizar_Polygon();
                ocultar_botones_Table();
                actualizacion_estado(`Punto ${pointIndex + 1} movido a nueva posición`, 'success');
            });
        }

        // Agregar popup con información del punto
        marker.bindPopup(`
        <strong>Punto ${numeroPunto || (pointIndex + 1)}</strong><br>
        Lat: ${latlng.lat.toFixed(6)}<br>
        Lng: ${latlng.lng.toFixed(6)}<br>
        <small>ID: ${dbId || 'N/A'}</small>
    `);

        areaMarkers.push(marker);

        // Actualizar tabla de puntos
        ocultar_botones_Table();

        // Si hay al menos 3 puntos, crear/actualizar polígono
        if (polygonPoints.length >= 3) {
            actualizar_Polygon();
        }
    }
</script>