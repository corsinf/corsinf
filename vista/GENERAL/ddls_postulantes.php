<script type="text/javascript">
    $(document).ready(function() {
        if (!$('#ddl_etnia').hasClass('select2-hidden-accessible')) {
            inicializar_selects_generales();
        }
    });

    function inicializar_selects_generales() {
        cargar_datos_etnia();
        cargar_datos_religion();
        cargar_datos_orientacion_sexual();
        cargar_datos_identidad_genero();
        cargar_datos_nacionalidad();
        cargar_datos_tipo_sangre();
        cargar_datos_estado_civil();
        cargar_datos_sexo();
        cargar_datos_origen_indigena();
    }

    function cargar_datos_etnia() {
        if ($('#ddl_etnia').hasClass('select2-hidden-accessible')) {
            $('#ddl_etnia').select2('destroy');
        }

        $('#ddl_etnia').select2({
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
                url: '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_etniaC.php?buscar=true',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        }).off('select2:select');
    }

    function cargar_datos_religion() {
        if ($('#ddl_religion').hasClass('select2-hidden-accessible')) {
            $('#ddl_religion').select2('destroy');
        }

        $('#ddl_religion').select2({
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
                url: '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_religionC.php?buscar=true',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        }).off('select2:select');
    }

    function cargar_datos_orientacion_sexual() {
        if ($('#ddl_orientacion_sexual').hasClass('select2-hidden-accessible')) {
            $('#ddl_orientacion_sexual').select2('destroy');
        }

        $('#ddl_orientacion_sexual').select2({
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
                url: '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_orientacion_sexualC.php?buscar=true',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        }).off('select2:select');
    }

    function cargar_datos_identidad_genero() {
        if ($('#ddl_identidad_genero').hasClass('select2-hidden-accessible')) {
            $('#ddl_identidad_genero').select2('destroy');
        }

        $('#ddl_identidad_genero').select2({
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
                url: '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_identidad_generoC.php?buscar=true',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        }).off('select2:select');
    }

    function cargar_datos_nacionalidad() {
        if ($('#ddl_nacionalidad').hasClass('select2-hidden-accessible')) {
            $('#ddl_nacionalidad').select2('destroy');
        }

        $('#ddl_nacionalidad').select2({
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
                url: '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_paisC.php?buscar_nacionalidad=true',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        }).off('select2:select');
    }

    function cargar_datos_tipo_sangre() {
        if ($('#ddl_tipo_sangre').hasClass('select2-hidden-accessible')) {
            $('#ddl_tipo_sangre').select2('destroy');
        }

        $('#ddl_tipo_sangre').select2({
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
                url: '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_tipo_sangreC.php?buscar=true',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        }).off('select2:select');
    }

    function cargar_datos_estado_civil() {
        if ($('#ddl_estado_civil').hasClass('select2-hidden-accessible')) {
            $('#ddl_estado_civil').select2('destroy');
        }

        $('#ddl_estado_civil').select2({
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
                url: '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_tipo_estado_civilC.php?buscar=true',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        }).off('select2:select');
    }

    function cargar_datos_sexo() {
        if ($('#ddl_sexo').hasClass('select2-hidden-accessible')) {
            $('#ddl_sexo').select2('destroy');
        }

        $('#ddl_sexo').select2({
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
                url: '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_tipo_sexoC.php?buscar=true',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        }).off('select2:select');
    }

    function cargar_datos_origen_indigena() {
        if ($('#ddl_origen_indigena').hasClass('select2-hidden-accessible')) {
            $('#ddl_origen_indigena').select2('destroy');
        }

        $('#ddl_origen_indigena').select2({
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
                url: '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_tipo_origen_indigenaC.php?buscar=true',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        }).off('select2:select');
    }
</script>