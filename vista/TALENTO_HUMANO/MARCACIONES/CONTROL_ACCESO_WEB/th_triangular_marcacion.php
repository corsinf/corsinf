<script type="text/javascript">
    $(document).ready(function() {

    });
</script>
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
<style>
    #map {
        height: 500px;
        width: 100%;
    }

    .status-inside {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }

    .status-outside {
        background-color: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
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
</style>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Marcar localización</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Localización
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

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
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6><i class="bi bi-gear-fill"></i> Herramientas</h6>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-success" id="btnDefineArea">
                                                <i class="bi bi-square"></i> Definir Área
                                            </button>
                                            <button type="button" class="btn btn-outline-primary" id="btnAddPerson">
                                                <i class="bi bi-person-plus"></i> Agregar Persona
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
                                    <h6><i class="bi bi-geo-alt"></i> Puntos del Área Definida</h6>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-sm" id="tbl_area_points">
                                            <thead>
                                                <tr>
                                                    <th>Punto</th>
                                                    <th>Latitud</th>
                                                    <th>Longitud</th>
                                                    <th>Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Lista de Personas -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h6><i class="bi bi-people-fill"></i> Personas Registradas</h6>
                                    <div class="table-responsive">
                                        <table class="table table-striped" id="tbl_persons">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nombre</th>
                                                    <th>Latitud</th>
                                                    <th>Longitud</th>
                                                    <th>Estado</th>
                                                    <th>Acción</th>
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

        <!-- Modal para agregar persona -->
        <div class="modal fade" id="modalPerson" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-person-plus"></i> Agregar Persona</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="personForm">
                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="personName" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Latitud</label>
                                <input type="number" class="form-control" id="personLat" step="any" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Longitud</label>
                                <input type="number" class="form-control" id="personLng" step="any" required>
                            </div>
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> También puedes hacer clic en el mapa para seleccionar la ubicación
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="btnSavePerson">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>

<script>
    let map;
    let currentPolygon = null;
    let polygonPoints = [];
    let areaMarkers = []; // Array para marcadores del área
    let isDefiningArea = false;
    let persons = [];
    let personMarkers = [];
    let tempMarker = null;

    // Inicializar mapa
    function initMap() {
        map = L.map('map').setView([-0.2313, -78.4675], 13); // Quito, Ecuador como ejemplo

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Eventos del mapa
        map.on('click', onMapClick);
    }

    // Evento click en el mapa
    function onMapClick(e) {
        if (isDefiningArea) {
            addPolygonPoint(e.latlng);
        } else if ($('#modalPerson').hasClass('show')) {
            // Si el modal está abierto, actualizar coordenadas
            $('#personLat').val(e.latlng.lat.toFixed(6));
            $('#personLng').val(e.latlng.lng.toFixed(6));

            // Mostrar marcador temporal
            if (tempMarker) {
                map.removeLayer(tempMarker);
            }
            tempMarker = L.marker(e.latlng, {
                icon: L.divIcon({
                    html: '<i class="bi bi-geo-alt-fill text-warning"></i>',
                    iconSize: [20, 20],
                    className: 'custom-marker'
                })
            }).addTo(map);
        }
    }

    // Agregar punto al polígono
    function addPolygonPoint(latlng) {
        polygonPoints.push(latlng);

        // Crear marcador para el punto
        const marker = L.marker(latlng, {
            icon: L.divIcon({
                html: '<i class="bi bi-geo-alt-fill text-success"></i>',
                iconSize: [20, 20],
                className: 'custom-marker'
            })
        }).addTo(map);

        // Agregar popup con información del punto
        marker.bindPopup(`<strong>Punto ${polygonPoints.length}</strong><br>Lat: ${latlng.lat.toFixed(6)}<br>Lng: ${latlng.lng.toFixed(6)}`);
        areaMarkers.push(marker);

        // Actualizar tabla de puntos
        updateAreaPointsTable();

        // Si hay al menos 3 puntos, crear/actualizar polígono
        if (polygonPoints.length >= 3) {
            if (currentPolygon) {
                map.removeLayer(currentPolygon);
            }

            currentPolygon = L.polygon(polygonPoints, {
                color: 'blue',
                fillColor: 'lightblue',
                fillOpacity: 0.3
            }).addTo(map);

            updateStatus(`Área definida con ${polygonPoints.length} puntos. Haz clic para agregar más puntos o presiona "Finalizar Área".`);
            $('#btnDefineArea').html('<i class="bi bi-check-square"></i> Finalizar Área').removeClass('btn-outline-success').addClass('btn-warning');
        } else {
            updateStatus(`Punto ${polygonPoints.length} agregado. Necesitas al menos 3 puntos para formar un área.`);
        }
    }

    // Actualizar tabla de puntos del área
    function updateAreaPointsTable() {
        const tbody = $('#tbl_area_points tbody');
        tbody.empty();

        if (polygonPoints.length > 0) {
            $('#areaPointsSection').show();

            polygonPoints.forEach((point, index) => {
                const row = `
                    <tr>
                        <td>Punto ${index + 1}</td>
                        <td>${point.lat.toFixed(6)}</td>
                        <td>${point.lng.toFixed(6)}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-danger" onclick="removeAreaPoint(${index})">
                                <i class="bi bi-trash"></i>
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

    // Remover punto del área
    function removeAreaPoint(index) {
        if (confirm('¿Estás seguro de que quieres eliminar este punto?')) {
            // Remover punto del array
            polygonPoints.splice(index, 1);

            // Remover marcador del mapa
            if (areaMarkers[index]) {
                map.removeLayer(areaMarkers[index]);
                areaMarkers.splice(index, 1);
            }

            // Actualizar polígono
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

            // Actualizar tablas
            updateAreaPointsTable();
            updatePersonsTable();

            // Actualizar estado
            if (polygonPoints.length >= 3) {
                updateStatus(`Área actualizada con ${polygonPoints.length} puntos.`);
            } else if (polygonPoints.length > 0) {
                updateStatus(`${polygonPoints.length} puntos agregados. Necesitas al menos 3 puntos para formar un área.`);
            } else {
                updateStatus('No hay puntos definidos. Haz clic en "Definir Área" para comenzar.');
            }
        }
    }

    // Verificar si un punto está dentro del polígono
    function isPointInPolygon(point, polygon) {
        if (polygon.length < 3) return false;

        const x = point.lat;
        const y = point.lng;
        let inside = false;

        for (let i = 0, j = polygon.length - 1; i < polygon.length; j = i++) {
            const xi = polygon[i].lat;
            const yi = polygon[i].lng;
            const xj = polygon[j].lat;
            const yj = polygon[j].lng;

            if (((yi > y) !== (yj > y)) && (x < (xj - xi) * (y - yi) / (yj - yi) + xi)) {
                inside = !inside;
            }
        }

        return inside;
    }

    // Actualizar estado
    function updateStatus(message, type = 'info') {
        const statusPanel = $('#statusPanel');
        statusPanel.removeClass('alert-info alert-success alert-warning alert-danger status-inside status-outside');
        statusPanel.addClass(`alert-${type}`);
        statusPanel.html(`<i class="bi bi-info-circle"></i> ${message}`);
    }

    // Actualizar tabla de personas
    function updatePersonsTable() {
        const tbody = $('#tbl_persons tbody');
        tbody.empty();

        persons.forEach((person, index) => {
            const isInside = currentPolygon && polygonPoints.length >= 3 ? isPointInPolygon(person, polygonPoints) : false;
            const statusClass = isInside ? 'status-inside' : 'status-outside';
            const statusText = isInside ? 'Dentro' : 'Fuera';
            const statusIcon = isInside ? 'bi-check-circle-fill' : 'bi-x-circle-fill';

            const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${person.name}</td>
                    <td>${person.lat.toFixed(6)}</td>
                    <td>${person.lng.toFixed(6)}</td>
                    <td><span class="badge ${statusClass}"><i class="${statusIcon}"></i> ${statusText}</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-danger" onclick="removePerson(${index})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
    }

    // Remover persona
    function removePerson(index) {
        if (confirm('¿Estás seguro de que quieres eliminar esta persona?')) {
            // Remover persona del array
            persons.splice(index, 1);

            // Remover marcador del mapa
            if (personMarkers[index]) {
                map.removeLayer(personMarkers[index]);
            }
            personMarkers.splice(index, 1);

            // Actualizar tabla
            updatePersonsTable();
        }
    }

    // Eventos de botones
    $('#btnDefineArea').click(function() {
        if (!isDefiningArea) {
            // Iniciar definición de área
            isDefiningArea = true;
            polygonPoints = [];
            areaMarkers = [];

            // Limpiar polígono anterior
            if (currentPolygon) {
                map.removeLayer(currentPolygon);
                currentPolygon = null;
            }

            // Limpiar marcadores anteriores del área
            map.eachLayer(function(layer) {
                if (layer instanceof L.Marker) {
                    // Solo remover marcadores que no sean de personas
                    let isPersonMarker = false;
                    personMarkers.forEach(personMarker => {
                        if (layer === personMarker) {
                            isPersonMarker = true;
                        }
                    });
                    if (!isPersonMarker && layer !== tempMarker) {
                        map.removeLayer(layer);
                    }
                }
            });

            updateAreaPointsTable();
            updateStatus('Haz clic en el mapa para definir los puntos del área. Necesitas al menos 3 puntos.');
            $(this).html('<i class="bi bi-square"></i> Definiendo...').removeClass('btn-outline-success').addClass('btn-warning');
        } else {
            // Finalizar definición de área
            if (polygonPoints.length >= 3) {
                isDefiningArea = false;
                updateStatus('Área definida correctamente. Ahora puedes agregar personas para verificar su ubicación.', 'success');
                $(this).html('<i class="bi bi-square"></i> Redefinir Área').removeClass('btn-warning').addClass('btn-outline-success');
                updatePersonsTable();
            } else {
                updateStatus('Necesitas al menos 3 puntos para definir un área.', 'warning');
            }
        }
    });

    $('#btnAddPerson').click(function() {
        if (tempMarker) {
            map.removeLayer(tempMarker);
            tempMarker = null;
        }
        $('#personForm')[0].reset();
        $('#modalPerson').modal('show');
    });

    // Limpiar marcador temporal cuando se cierre el modal
    $('#modalPerson').on('hidden.bs.modal', function() {
        if (tempMarker) {
            map.removeLayer(tempMarker);
            tempMarker = null;
        }
    });

    $('#btnSavePerson').click(function() {
        const name = $('#personName').val().trim();
        const lat = parseFloat($('#personLat').val());
        const lng = parseFloat($('#personLng').val());

        if (!name || isNaN(lat) || isNaN(lng)) {
            alert('Por favor completa todos los campos correctamente.');
            return;
        }

        const person = {
            name: name,
            lat: lat,
            lng: lng
        };
        persons.push(person);

        // Crear marcador en el mapa
        const marker = L.marker([lat, lng], {
            icon: L.divIcon({
                html: '<i class="bi bi-person-fill text-primary"></i>',
                iconSize: [20, 20],
                className: 'custom-marker'
            })
        }).addTo(map);

        marker.bindPopup(`<strong>${name}</strong><br>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}`);
        personMarkers.push(marker);

        updatePersonsTable();
        $('#modalPerson').modal('hide');

        // Limpiar marcador temporal
        if (tempMarker) {
            map.removeLayer(tempMarker);
            tempMarker = null;
        }

        updateStatus(`Persona "${name}" agregada correctamente.`, 'success');
    });

    $('#btnClearAll').click(function() {
        if (confirm('¿Estás seguro de que quieres limpiar todo?')) {
            // Limpiar polígono
            if (currentPolygon) {
                map.removeLayer(currentPolygon);
                currentPolygon = null;
            }
            polygonPoints = [];
            areaMarkers = [];
            isDefiningArea = false;

            // Limpiar personas
            persons = [];
            personMarkers.forEach(marker => map.removeLayer(marker));
            personMarkers = [];

            // Limpiar marcador temporal
            if (tempMarker) {
                map.removeLayer(tempMarker);
                tempMarker = null;
            }

            // Limpiar todos los marcadores
            map.eachLayer(function(layer) {
                if (layer instanceof L.Marker) {
                    map.removeLayer(layer);
                }
            });

            updatePersonsTable();
            updateAreaPointsTable();
            updateStatus('Todo limpiado. Puedes comenzar de nuevo.');
            $('#btnDefineArea').html('<i class="bi bi-square"></i> Definir Área').removeClass('btn-warning').addClass('btn-outline-success');
        }
    });

    // Inicializar cuando el documento esté listo
    $(document).ready(function() {
        initMap();
    });
</script>