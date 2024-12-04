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

                placeholder: '-- Seleccione --',
                width: '100%',
                ajax: {
                    url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_provinciasC.php?buscar=true',
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

                placeholder: '-- Seleccione --',
                width: '100%',
                ajax: {
                    url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_ciudadC.php?buscar=true',
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

                placeholder: '-- Seleccione --',
                width: '100%',
                ajax: {
                    url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_parroquiasC.php?buscar=true',
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
        var codigo_postal = $('#txt_direccion_postal');

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
        <select class="form-select form-select-sm" id="ddl_provincias" name="ddl_provincias" maxlenght="5000">
            <option value="">Seleccione</option>
        </select>
    </div>
    <div class="col-md-3">
        <label for="ddl_ciudad" class="form-label form-label-sm">Ciudad </label>
        <select class="form-select form-select-sm" id="ddl_ciudad" name="ddl_ciudad" maxlenght="5000">
        </select>
    </div>
    <div class="col-md-3">
        <label for="ddl_parroquia" class="form-label form-label-sm">Parroquia </label>
        <select class="form-select form-select-sm" id="ddl_parroquia" name="ddl_parroquia" maxlenght="5000">
        </select>
    </div>
    <div class="col-md-3">
        <label for="txt_direccion_postal" class="form-label form-label-sm">Código Postal </label>
        <div class="input-group mb-3">
            <input type="text" class="form-control form-control-sm" name="txt_direccion_postal" id="txt_direccion_postal" placeholder="Escriba su código postal o de click en 'Obtener'">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="obtener_codigo_postal();" id="button-addon2">Obtener</button>
        </div>
    </div>

</div>
<script>
    
</script>
