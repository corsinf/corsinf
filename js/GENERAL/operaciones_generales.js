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

    // Reemplazar cualquier cosa que no sea números, letras, puntos, espacios, guiones bajos, guiones medios o barras
    var nuevoValor = value.replace(/[^a-zA-Z0-9.\s_/-]/g, '');

    // Actualizar el valor del campo
    $(this).val(nuevoValor);
});


// Actualizar el valor del campo a minusculas
function textoMinusculas(input) {
    let texto = input.value;
    input.value = texto.toLowerCase();
}

function minutos_formato_hora(num) {
    let hours = Math.floor(num / 60);
    let minutes = num % 60;
    return (hours < 10 ? '0' : '') + hours + ':' + (minutes < 10 ? '0' : '') + minutes;
}


//Funcion para agregar asterisco en un label para resaltar que es campo obligatorio
function agregar_asterisco_campo_obligatorio(label) {
    $('label[for="' + label + '"]').append('<label style="color: red;">*</label>');
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

//Datatable
function configuracion_datatable(title, filename) {
    return {
        dom:
            // Botones en la parte superior con margen inferior
            '<"d-flex justify-content-start top mb-4"B>' +
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
            $('#contenedor_botones').append($('.dt-buttons'));
        }
    };
}

//Para reajustar un datatable cuando se lo usa en modales
function reajustarDataTable() {
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
}


