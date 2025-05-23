<style>
    /* Ajustes para dropdown en pantallas pequeñas */
    .select2-container {
        width: 100% !important;
    }

    .select2-dropdown {
        /* position: fixed !important; */
        z-index: 1056 !important;
        /* width: auto !important;  */
        /* Para mantenerlo sobre el modal */
    }

    .modal-body {
        max-height: 80vh;
        /* Permite desplazarse si el contenido es largo */
        overflow-y: auto;
    }
</style>

<script type="text/javascript">
    //corregir para cargar los id
    $(document).ready(function() {

        cargar_datos_provincias();

        // Evento para cuando se selecciona una provincia
        $('#ddl_ciudad').prop('disabled', true);
        $('#ddl_parroquia').prop('disabled', true);

        $('#ddl_provincias').on('change', function() {
            if ($(this).val()) {
                $('#ddl_ciudad').prop('disabled', false);
                cargar_datos_ciudades($(this).val());
            } else {
                $('#ddl_ciudad').prop('disabled', true).val('');
                $('#ddl_parroquia').prop('disabled', true).val('');
            }

            $('#ddl_ciudad').val(null).trigger('change');
            $('#ddl_parroquia').val(null).trigger('change');
        });

        // Evento para cuando se selecciona una ciudad 
        $('#ddl_ciudad').on('change', function() {
            if ($(this).val()) {
                $('#ddl_parroquia').prop('disabled', false);
                cargar_datos_parroquias($(this).val());
            } else {
                $('#ddl_parroquia').prop('disabled', true).val('');
            }
            $('#ddl_parroquia').val(null).trigger('change');
        });
    });

    function cargar_datos_provincias() {
        $('#ddl_provincias').select2({
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
                dropdownParent: $('.modal_general_provincias'),
                placeholder: '-- Seleccione --',
                width: '100%',
                ajax: {
                    url: '../controlador/GENERAL/th_provinciasC.php?buscar=true',
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
            .off('select2:select');
    }

    function cargar_datos_ciudades(th_prov_id) {
        $('#ddl_ciudad').select2({
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
                dropdownParent: $('.modal_general_provincias'),
                placeholder: '-- Seleccione --',
                width: '100%',
                ajax: {
                    url: '../controlador/GENERAL/th_ciudadC.php?buscar=true',
                    dataType: 'json',
                    delay: 250,
                    data: {
                        th_prov_id: th_prov_id
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            })
            .off('select2:select');
    }

    function cargar_datos_parroquias(th_ciu_id) {
        $('#ddl_parroquia').select2({
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
                dropdownParent: $('.modal_general_provincias'),
                placeholder: '-- Seleccione --',
                width: '100%',
                ajax: {
                    url: '../controlador/GENERAL/th_parroquiasC.php?buscar=true',
                    dataType: 'json',
                    delay: 250,
                    data: {
                        th_ciu_id: th_ciu_id
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            })
            .off('select2:select');
    }

    function obtener_codigo_postal() {
        var ubicacion = $('#ubicacion');
        var codigo_postal = $('#txt_codigo_postal');

        function success(position) {
            var lat = position.coords.latitude;
            var lon = position.coords.longitude;

            // Llamada a la API de Nominatim para obtener el código postal
            var url = `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`;

            $.getJSON(url, function(data) {
                if (data && data.address && data.address.postcode) {
                    codigo_postal.val(data.address.postcode);
                } else {
                    codigo_postal.val('No se pudo obtener');
                }
            }).fail(function() {
                codigo_postal.val('Error al obtener el código postal');
            });
        }

        // Obtener la ubicación del usuario
        navigator.geolocation.getCurrentPosition(success);
    }
</script>

<div class="row mb-col ">
    <div class="col-md-3">
        <label for="ddl_provincias" class="form-label form-label-sm">Provincia </label>
        <select class="form-select form-select-sm select2-validation" id="ddl_provincias" name="ddl_provincias" maxlenght="5000">
            <option value="">Seleccione</option>
        </select>
        <label class="error" style="display: none;" for="ddl_provincias"></label>
    </div>
    <div class="col-md-3">
        <label for="ddl_ciudad" class="form-label form-label-sm">Ciudad </label>
        <select class="form-select form-select-sm select2-validation" id="ddl_ciudad" name="ddl_ciudad" maxlenght="5000">
        </select>
        <label class="error" style="display: none;" for="ddl_ciudad"></label>
    </div>
    <div class="col-md-3">
        <label for="ddl_parroquia" class="form-label form-label-sm">Parroquia </label>
        <select class="form-select form-select-sm select2-validation" id="ddl_parroquia" name="ddl_parroquia" maxlenght="5000">
        </select>
        <label class="error" style="display: none;" for="ddl_parroquia"></label>
    </div>
    <div class="col-md-3">
        <label for="txt_codigo_postal" class="form-label form-label-sm">Código Postal </label>
        <div class="input-group">
            <input type="text" class="form-control form-control-sm" name="txt_codigo_postal" id="txt_codigo_postal" placeholder="Escriba su código postal o de click en 'Obtener'">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="obtener_codigo_postal();" id="button-addon2">Obtener</button>
        </div>
        <small><a href="https://www.codigopostal.gob.ec/" target="_blank">Consultar Código Postal Terceros</a></small>
        <label class="error" style="display: none;" for="txt_codigo_postal"></label>
    </div>

</div>

<script>

</script>