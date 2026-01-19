//Calcula la edad en base a la fecha de nacimiento
//Formato Date
function calcular_edad_fecha(fecha) {
    fechaNacimientoJson = fecha;

    // Crear un objeto Date a partir del string de fecha
    fecha = new Date(fechaNacimientoJson);

    // Obtener la fecha actual
    fechaActual = new Date();

    // Calcular la diferencia en milisegundos entre la fecha actual y la fecha de nacimiento
    diferenciaEnMilisegundos = fechaActual - fecha;

    // Calcular la edad en años a partir de la diferencia en milisegundos
    edadEnMilisegundos = new Date(diferenciaEnMilisegundos);
    edadEnAnios = Math.abs(edadEnMilisegundos.getUTCFullYear() - 1970);

    var salida = '';

    // Mostrar la edad en años
    salida = edadEnAnios;

    return salida;
}

//Fecha con formato de los input
function fecha_nacimiento_formateada(fecha) {
    fechaYHora = fecha;
    fecha = new Date(fechaYHora);
    anio = fecha.getFullYear();
    mes = (fecha.getMonth() + 1).toString().padStart(2, '0'); // Añade un 0 si es necesario
    dia = fecha.getDate().toString().padStart(2, '0'); // Añade un 0 si es necesario
    fechaFormateada = `${anio}-${mes}-${dia}`;

    var salida = '';
    salida = fechaFormateada;

    return salida;
}

//Fecha con formato de los input
function fecha_formateada(fecha) {
    if (fecha == '' || fecha == null) {
        return "dd/mm/aaaa";
    }

    fechaYHora = fecha;
    fecha = new Date(fechaYHora);
    anio = fecha.getFullYear();
    mes = (fecha.getMonth() + 1).toString().padStart(2, '0'); // Añade un 0 si es necesario
    dia = fecha.getDate().toString().padStart(2, '0'); // Añade un 0 si es necesario
    fechaFormateada = `${anio}-${mes}-${dia}`;

    var salida = '';
    salida = fechaFormateada;

    return salida;
}

//Para sacar los valores de un datetime2 la fecha y la hora
//Salida 2024/10/14 11:31:06
function fecha_formateada_hora(fecha) {
    fechaYHora = fecha;
    fecha = new Date(fechaYHora);

    // Obtener el año, mes y día
    anio = fecha.getFullYear();
    mes = (fecha.getMonth() + 1).toString().padStart(2, '0'); // Añade un 0 si es necesario
    dia = fecha.getDate().toString().padStart(2, '0'); // Añade un 0 si es necesario

    // Obtener las horas, minutos y segundos
    horas = fecha.getHours().toString().padStart(2, '0');
    minutos = fecha.getMinutes().toString().padStart(2, '0');
    segundos = fecha.getSeconds().toString().padStart(2, '0');

    // Formato de fecha y hora
    fechaFormateada = `${anio}/${mes}/${dia} ${horas}:${minutos}:${segundos}`;

    return fechaFormateada;
}

function fecha_formato_datetime2(fecha) {
    // if (!fecha) {
    //     alert("Por favor, selecciona una fecha y hora.");
    //     return null;
    // }

    let date = new Date(fecha);

    // Extraer valores asegurando formato correcto
    let year = date.getFullYear();
    let month = String(date.getMonth() + 1).padStart(2, '0');
    let day = String(date.getDate()).padStart(2, '0');
    let hours = String(date.getHours()).padStart(2, '0');
    let minutes = String(date.getMinutes()).padStart(2, '0');
    let seconds = String(date.getSeconds()).padStart(2, '0');
    let milliseconds = "0000000";

    let fecha_formateada = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}.${milliseconds}`;

    // console.log(fecha_formateada); // "2025-03-14 08:00:00.0000000"

    return fecha_formateada;
}

function fecha_input_datelocal(fecha) {
    // Formatear la fecha para datetime-local (YYYY-MM-DDTHH:MM)
    return fecha.split(" ")[0] + "T" + fecha.split(" ")[1].substring(0, 5);

}

//Valida si tiene el formato de email
function validar_email(sa_rep_correo) {

    var email = sa_rep_correo;

    // Define expresion regular
    var validad_email = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;

    if (validad_email.test(email)) {
        //alert('Email valido');
        return true;
    } else {
        //alert('Email no valido');
        return false;
    }
}

/**
 * Selecciona el sexo de una persona y actualiza el campo correspondiente en el formulario.
 * 
 * @param {string} sexo - El sexo de la persona ("M" para masculino, "F" para femenino).
 * @param {HTMLElement} campo - El campo del formulario donde se debe mostrar el sexo.
 * 
 * @returns {void} No devuelve ningún valor.
 */

function select_genero(sexo = '', campo = '') {

    if (sexo === 'Masculino') {
        selectElement = $(campo);
        optionElement = selectElement.find('option[value="Masculino"]');
        if (optionElement.length > 0) {
            optionElement.prop('selected', true); // Selecciona la opción 'Femenino'
        }
    } else if (sexo === 'Femenino') {
        selectElement = $(campo);
        optionElement = selectElement.find('option[value="Femenino"]');
        if (optionElement.length > 0) {
            optionElement.prop('selected', true); // Selecciona la opción 'Masculino'
        }
    }
}

//Obtiene la hora formateada de un DATETIME 
function obtener_hora_formateada_arr(hora) {
    var fechaActual = new Date(hora);
    var hora = fechaActual.getHours();
    var minutos = fechaActual.getMinutes();

    // Formatear la hora como una cadena
    var horaFormateada = (hora < 10 ? '0' : '') + hora + ':' +
        (minutos < 10 ? '0' : '') + minutos;
    return horaFormateada;
}

//Obtiene la hora formateada de un TIME 
function obtener_hora_formateada(hora) {
    // Dividir la cadena de hora en horas, minutos y segundos
    var partesHora = hora.split(":");
    var horas = parseInt(partesHora[0], 10);
    var minutos = parseInt(partesHora[1], 10);
    // No necesitamos los segundos, ya que no se están utilizando en la función original

    // Formatear la hora como una cadena
    var horaFormateada = (horas < 10 ? '0' : '') + horas + ':' +
        (minutos < 10 ? '0' : '') + minutos;
    return horaFormateada;
}

//Se obtiene la ULR base
function base_url() {
    return (window.document.location.origin + "/" + window.location.pathname.split('/')[1]);
}

//Permite agregar solo números a un input text
$(document).on('input', '.solo_numeros_int', function (event) {
    // Convertir el valor a entero
    var value = $(this).val();

    // Eliminar cualquier cosa que no sea un número
    value = value.replace(/[^0-9]/g, '');

    // Actualizar el valor del campo
    $(this).val(value);
});

//Permite agregar solo números a un input text y puntos
$(document).on('input', '.solo_numeros_punto', function (event) {
    // Obtener el valor actual del campo
    var value = $(this).val();

    // Reemplazar cualquier cosa que no sea un número o un punto
    var nuevoValor = value.replace(/[^0-9.]/g, '');

    // Permitir solo un punto decimal
    var partes = nuevoValor.split('.');
    if (partes.length > 2) {
        nuevoValor = partes[0] + '.' + partes.slice(1).join('');
    }

    // Actualizar el valor del campo
    $(this).val(nuevoValor);
});

// Permite agregar solo números a un input text y múltiples puntos
$(document).on('input', '.solo_numeros_puntos', function (event) {
    // Obtener el valor actual del campo
    var value = $(this).val();

    // Reemplazar cualquier cosa que no sea un número o un punto
    var nuevoValor = value.replace(/[^0-9.]/g, '');

    // Actualizar el valor del campo
    $(this).val(nuevoValor);
});

// Permite agregar solo caracteres seguros (números, letras, puntos, espacios, guiones bajos, guiones medios y barras)
$(document).on('input', '.no_caracteres', function (event) {
    // Obtener el valor actual del campo
    var value = $(this).val();

    // Reemplazar cualquier cosa que no sea números, letras, puntos, espacios, guiones bajos, guiones medios o barras o tildes o ñ
    //var nuevoValor = value.replace(/[^a-zA-Z0-9.\s_/-]/g, '');
    var nuevoValor = value.replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚüÜñÑ.\s_/-]/g, '');

    // Actualizar el valor del campo
    $(this).val(nuevoValor);
});


// Actualizar el valor del campo a minusculas
function texto_minusculas(input) {
    let texto = input.value;
    input.value = texto.toLowerCase();
}

function texto_mayusculas(input) {
    let texto = input.value;
    input.value = texto.toUpperCase();
}

//Para convertir de minutos a hora (HH:mm)
function minutos_formato_hora(num) {
    if (num < 0) return "00:00";
    if (num > 1439) return "23:59";

    let horas = Math.floor(num / 60);
    let minutos = num % 60;

    return (horas < 10 ? '0' : '') + horas + ':' + (minutos < 10 ? '0' : '') + minutos;
}

//Para convertir horas (HH:mm) a minutos
function hora_a_minutos(hora) {
    let [horas, minutos] = hora.split(':').map(Number);
    return horas * 60 + minutos;
}

//Funcion para agregar asterisco en un label para resaltar que es campo obligatorio
function agregar_asterisco_campo_obligatorio(label) {
    $('label[for="' + label + '"]').append('<b style="color: red;">*</b>');
}

//calcula la edad en base a un input date y lo coloca en un input
function calcular_edad(input, fecha_nacimiento) {
    $('#' + input).val(calcular_edad_fecha(fecha_nacimiento));
}

//No deja que se coloque una fecha mayor a la actual
function verificar_fecha_actual(input_name, fecha_actual, input_adicional) {
    let hoy = new Date().toISOString().split('T')[0];

    if (fecha_actual > hoy) {
        $('#' + input_name).val('');
        $('#' + input_adicional).val('');
    }
}

function verificar_fecha_inicio_fecha_fin(input_name_fi, input_name_ff) {
    let fechaInicio = new Date($(`input[name='${input_name_fi}']`).val());
    let fechaFin = new Date($(`input[name='${input_name_ff}']`).val());

    if (fechaFin < fechaInicio) {
        Swal.fire('', 'La fecha de fin no puede ser menor que la fecha de inicio.', 'error');

        $(`input[name='${input_name_ff}']`).val(''); // Borra la fecha incorrecta
        $(`input[name='${input_name_ff}']`).removeClass("is-valid").addClass("is-invalid");

        return false;
    } else {
        $(`input[name='${input_name_ff}']`).removeClass("is-invalid").addClass("is-valid");
        return true;
    }
}

//Para regresar a la pagina anterior
function boton_regresar_js() {
    window.history.back();
}

/**
 * 
 * Datatable
 * 
 */

//Para generar los botones de una tabla
function configuracion_datatable(title, filename, buttons = 'contenedor_botones') {
    return {
        dom:
            // Botones en la parte superior con margen inferior
            '<"d-flex justify-content-end top mb-4"B>' +
            // Selector de registros y barra de búsqueda en una fila
            '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
            // Tabla en su propia fila
            'rt' +
            // Información y paginación en la parte inferior
            '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        // Opciones adicionales que podrían ser comunes en todas las tablas
        buttons: [{
            extend: 'excel',
            text: '<i class="bx bx-grid me-0 pb-1"></i> Excel',
            title: title,
            filename: filename,
            className: 'btn btn-outline-success btn-sm me-1'
        },
        {
            extend: 'pdf',
            text: '<i class="bx bxs-file-pdf me-0 pb-1"></i> PDF',
            title: title,
            filename: filename,
            className: 'btn btn-outline-danger btn-sm me-1'
        },
        {
            extend: 'csv',
            text: '<i class="bx bx-align-justify me-0 pb-1"></i> CSV',
            title: title,
            filename: filename,
            className: 'btn btn-outline-primary btn-sm me-1'
        },
        {
            extend: 'colvis',
            text: 'Columnas',
            className: 'btn btn-dark btn-sm text-white'
        },
        ],
        initComplete: function () {
            // Añadir clase btn-sm a los botones de paginación
            $('.dataTables_paginate').find('a').addClass('btn btn-sm');
            // Mover los botones al contenedor personalizado
            // $('#' + buttons).append($('.dt-buttons'));

            let tableApi = this.api();
            let contenedor = $('#' + buttons);
            let botones = tableApi.buttons().container();

            contenedor.append(botones);
        }
    };
}

//Para reajustar un datatable cuando se lo usa en modales
function reajustarDataTable() {
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
}

/**
 * 
 * Select2
 * 
 */

//Funcion para cargar datos en un select2, es una funcion simple cuando se quiere cargar los datos

function cargar_select2_url(ddl, url_controlador, placeholder = '-- Seleccione --', dropdownParent = null, minimumInputLength = 0) {
    if (placeholder == '') {
        placeholder = '-- Seleccione --'
    }

    let configuracion = {
        language: {
            inputTooShort: function () {
                return `Por favor ingresa ${minimumInputLength} o más caracteres`;
            },
            noResults: function () {
                return "No se encontraron resultados";
            },
            searching: function () {
                return "Buscando...";
            },
            errorLoading: function () {
                return "No se encontraron resultados";
            }
        },
        minimumInputLength: minimumInputLength,
        placeholder: placeholder,
        width: '100%',
        ajax: {
            url: url_controlador,
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                //console.log(data.length);
                return { results: data };
            },
            cache: true
        }
    };

    if (dropdownParent) {
        configuracion.dropdownParent = $(dropdownParent);
    }

    $('#' + ddl).select2(configuracion)
        .on('select2:open', function () {
            let input = $('.select2-search__field');

            input.on('input', function () {
                if ($(this).val().trim() === '') {
                    $('#' + ddl).select2('close');
                    $('#' + ddl).select2('open');
                }
            });
        });
}

function cargar_select2_con_id(ddl, url_controlador, id_seleccionado, texto) {
    if (id_seleccionado == null || id_seleccionado == '') {
        return;
    }

    $.ajax({
        data: {
            id: id_seleccionado
        },
        url: url_controlador,
        type: 'post',
        dataType: 'json',

        success: function (response) {
            $('#' + ddl).html(`<option value='${id_seleccionado}'>${response[0][texto]}</option>`);
        },

        error: function (xhr, status, error) {
            console.log('Status: ' + status);
            console.log('Error: ' + error);
            console.log('XHR Response: ' + xhr.responseText);
            Swal.fire('', 'Error: ' + xhr.responseText, 'error');
        }
    });
}

//Funcion que complementa a la libreria de jquery Validation en especifico para los select2
function unhighlight_select(element) {
    let $element = $(element);

    // Verificar si es un select2
    if ($element.hasClass("select2-hidden-accessible")) {
        let select_value = $element.val();
        let $select2_container = $element.next(".select2-container");

        // Obtener el ID del select
        let selectId = $element.attr('id');

        // Buscar todos los labels con la clase 'error' asociados a este select
        let $error_labels = $('label.error[for="' + selectId + '"]');

        // Verificar si hay un valor seleccionado en el select2
        if (select_value) {
            // Si hay valor, aplicar 'is-valid' y ocultar el segundo label de error
            $select2_container.find(".select2-selection").removeClass("is-invalid").addClass("is-valid");
            $error_labels.hide(); // Ocultar el segundo label de error
        } else {
            // Si no hay valor, aplicar 'is-invalid' y mostrar el segundo label de error
            $select2_container.find(".select2-selection").removeClass("is-valid").addClass("is-invalid");
            $error_labels.show(); // Mostrar el segundo label de error
        }
    }
}


//Sweet alert, para generar las alertas de manera mejorada

function handle_ajax_response(response) {
    try {
        // console.log('Error al parsear JSON:', response);
        // return
        let json_response = (response);


        let title = 'Mensaje';
        let text = 'No se pudo interpretar la respuesta.';
        let icon = 'info';

        if (json_response.success) {
            title = 'Éxito';
            text = json_response.success;
            icon = 'success';
        } else if (json_response.warning) {
            title = 'Advertencia';
            text = json_response.warning;
            icon = 'warning';
        } else if (json_response.error) {
            title = 'Error';
            text = json_response.error;
            icon = 'error';
        }

        Swal.fire({
            title: title,
            text: text,
            icon: icon
        }).then(function () {
            if (json_response.redirect) {
                window.location.href = json_response.redirect; // Redirigir si existe un link en la respuesta
            }
        });

    } catch (e) {
        Swal.fire({
            title: 'Error',
            text: 'Respuesta inválida del servidor.',
            icon: 'error'
        });
        console.error('Error al parsear JSON:', e);
    }
}


