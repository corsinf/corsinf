<script type="text/javascript">
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
        });

        // Evento para cuando se selecciona una ciudad 
        $('#ddl_ciudad').on('change', function() {
            if ($(this).val()) {
                $('#ddl_parroquia').prop('disabled', false);
                cargar_datos_parroquias($(this).val());
            } else {
                $('#ddl_parroquia').prop('disabled', true).val('');
            }
        });
    })

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

</script>

<div class="row mb-col">
    <div class="col-3">
        <label for="ddl_provincias" class="form-label form-label-sm">Provincia </label>
        <select class="form-select form-select-sm" id="ddl_provincias" name="ddl_provincias" maxlenght="5000">
            <option value="">Seleccione</option>
        </select>
    </div>
    <div class="col-3">
        <label for="ddl_ciudad" class="form-label form-label-sm">Ciudad </label>
        <select class="form-select form-select-sm" id="ddl_ciudad" name="ddl_ciudad" maxlenght="5000">
        </select>
    </div>
    <div class="col-3">
        <label for="ddl_parroquia" class="form-label form-label-sm">Parroquia </label>
        <select class="form-select form-select-sm" id="ddl_parroquia" name="ddl_parroquia" maxlenght="5000">
        </select>
    </div>
    <div class="col-3">
        <label for="txt_codigo_postal" class="form-label form-label-sm">Codigo Postal </label>
        <input type="text" class="form-control form-control-sm" name="txt_codigo_postal" id="codigo_postal" readonly>
    </div>
</div>