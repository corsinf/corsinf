
function activar() {
  $('#').addClass('Active');
}

function restriccion() {
  var pag = location.href;

  $.ajax({
    data: { pagina: pag },
    url: '../controlador/loginC.php?restriccion=true',
    type: 'post',
    dataType: 'json',
    /*beforeSend: function () {   
         var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
       $('#tabla_').html(spiner);
    },*/
    success: function (response) {

      // console.log(response);

      if (response.ver != 0) {
        $('#dba').val(response.dba);
        $('#ver').val(response.ver);
        $('#editar').val(response.editar);
        $('#eliminar').val(response.eliminar);

        if (response.ver == 1 && response.editar == 0) {
          $('#btn_editar').hide();
          $('#subir_imagen').hide();
        }
        if (response.ver == 1 && response.eliminar == 0) {
          $('#btn_eliminar').hide();
        }
        // console.log(mod);
        //console.log(response.mod);
        if (response.sistema != mod && response.pag != 'index.php') {
          Swal.fire('Se a cambiado de modulo', '', 'info').then(function () {
            location.href = 'modulos_sistema.php';
          });
        }
      } else {
        if (response.pag != 'pagina_error.php') {
          location.href = 'inicio.php?acc=pagina_error';
        }
      }

    }

  });
}

function formato_fecha(date) {
  var formattedDate = new Date(date);
  var d = formattedDate.getDate();
  var m = formattedDate.getMonth();
  m += 1; // javascript months are 0-11
  if (m < 10) {
    m = '0' + m;
  }
  if (d < 10) {
    d = '0' + d;
  }
  var y = formattedDate.getFullYear();
  var Fecha = y + "-" + m + "-" + d;
  console.log(Fecha);
  return Fecha;
}

function num_caracteres(campo, num) {
  var val = $('#' + campo).val();
  var cant = val.length;
  console.log(cant + '-' + num);

  if (cant >= num) {
    $('#' + campo).val(val.substr(0, num));
    return false;
  }

}
function solo_numeros(input) {
  var valor = input.value;

  // Reemplazar cualquier caracter que no sea un número con una cadena vacía
  var soloNumeros = valor.replace(/\D/g, '');

  // Actualizar el valor del input con solo números
  input.value = soloNumeros;
}
function validar_cedula(campo) {
  var cad = document.getElementById(campo).value.trim();
  var total = 0;
  var longitud = cad.length;
  var longcheck = longitud - 1;

  if (longitud == 10 || longitud == 13) {
    if (cad !== "" && longitud === 10) {
      for (i = 0; i < longcheck; i++) {
        if (i % 2 === 0) {
          var aux = cad.charAt(i) * 2;
          if (aux > 9) aux -= 9;
          total += aux;
        } else {
          total += parseInt(cad.charAt(i)); // parseInt o concatenará en lugar de sumar
        }
      }

      total = total % 10 ? 10 - total % 10 : 0;

      if (cad.charAt(longitud - 1) == total) {
        return true
      } else {
        Swal.fire('Error en numero de cedula', 'Tiene que tener 10 ó 13 caracteres', 'info').then(function () {
          $('#' + campo).val('');
          return false;
        })
      }
    }
  }
}


function notificaciones_1(parametros) {
  salida = '';
  contador_noti = 0;
  $.ajax({
    url: '../controlador/notificacionesC.php?listar=true',
    data: {
      parametros: parametros,
    },
    type: 'post',
    dataType: 'json',
    success: function (response) {
      //console.log(response)
      $("#pnl_notificaciones").empty();

      $.each(response, function (i, item) {

        tiempo_trans = calcularTiempoTranscurrido(item.GLO_fecha_creacion)

        salida +=
          `<a class="dropdown-item" href="${item.GLO_link_redirigir}">
                      <div class="d-flex align-items-center">
                          <div class="notify bg-light-danger text-danger"><i class='${item.GLO_icono}' ></i> 
                          </div>
                          <div class="flex-grow-1">
                              <h6 class="msg-name">${item.GLO_titulo.toUpperCase()} ${contador_noti + 1}<span class="msg-time float-end">${tiempo_trans}
                          </span></h6>
                              <p class="msg-info">${item.GLO_cuerpo}</p>
                          </div>
                      </div>
                  </a>`;

        contador_noti++;
      });

      if (contador_noti > 0) {
        $('#pnl_noti').show();
        $('#lbl_noti').html(contador_noti);

      }

      $("#pnl_notificaciones").append(salida);

    }
  });

}

function calcularTiempoTranscurrido(fecha_consulta) {
  // Convierte la fecha de consulta a un objeto Date
  fecha_consulta_format = new Date(fecha_consulta);
  // Obtiene la hora actual ajustada a la zona horaria de Quito o Guayaquil
  fechaActual = new Date().toLocaleString('en-US', { timeZone: 'America/Guayaquil' });

  // Convierte la hora actual a un objeto Date
  fechaActual_format = new Date(fechaActual);

  // Calcula la diferencia en milisegundos
  diferencia_milisegundos = fechaActual_format - fecha_consulta_format;

  // Convierte la diferencia a segundos, minutos y horas
  diferencia_segundos = Math.floor(diferencia_milisegundos / 1000);
  diferencia_minutos = Math.floor(diferencia_segundos / 60);
  diferencia_horas = Math.floor(diferencia_minutos / 60);

  // Formateo el mensaje según el tiempo transcurrido
  if (diferencia_segundos < 60) {
    return `${diferencia_segundos} segundo${diferencia_segundos !== 1 ? 's' : ''}`;
  } else if (diferencia_minutos < 60) {
    return `${diferencia_minutos} minuto${diferencia_minutos !== 1 ? 's' : ''}`;
  } else {
    return `${diferencia_horas} hora${diferencia_horas !== 1 ? 's' : ''}`;
  }
}


function pass(input) {
  var pa = document.getElementById(input);
  if (pa.type == 'password') {
    pa.type = 'text';
  } else {
    pa.type = 'password';
  }
}

