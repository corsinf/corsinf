//Calcula la edad en base a la fecha de nacimiento
function calcular_edad_fecha_nacimiento(fecha_nacimiento) {
    fechaNacimientoJson = fecha_nacimiento;

    // Crear un objeto Date a partir del string de fecha
    fechaNacimiento = new Date(fechaNacimientoJson);

    // Obtener la fecha actual
    fechaActual = new Date();

    // Calcular la diferencia en milisegundos entre la fecha actual y la fecha de nacimiento
    diferenciaEnMilisegundos = fechaActual - fechaNacimiento;

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
    año = fecha.getFullYear();
    mes = (fecha.getMonth() + 1).toString().padStart(2, '0'); // Añade un 0 si es necesario
    dia = fecha.getDate().toString().padStart(2, '0'); // Añade un 0 si es necesario
    fechaFormateada = `${año}-${mes}-${dia}`;

    var salida = '';
    salida = fechaFormateada;

    return salida;

}

//Valida si el email esta bien
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

//Cuando se envia por array y en formato de fecha
function obtener_hora_formateada_arr(hora) {
    var fechaActual = new Date(hora);
    var hora = fechaActual.getHours();
    var minutos = fechaActual.getMinutes();

    // Formatear la hora como una cadena
    var horaFormateada = (hora < 10 ? '0' : '') + hora + ':' +
        (minutos < 10 ? '0' : '') + minutos;
    return horaFormateada;
}

//cuando se resive solo la hora 
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

function base_url() {
    return (window.document.location.origin + "/" + window.location.pathname.split('/')[1]);
}


