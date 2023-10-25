function cargar_lineas_solicitud(id)
{	     
	parametros = 
	{
		'id':id,
	}
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/ingresar_procesoC.php?lineas=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
      	var tr ='';
        var trs = '';
        var tre = '';
        var ids = '';

        // console.log(response);
        console.log(estado);
      	response.forEach(function(item,i){
          if(item.salida == null){item.salida = '';}
          if(item.entrada == null){item.entrada = '';}
      		tr+='<tr><td>'+item.codigo+'</td><td>'+item.item+'</td><td>'+item.serie+'</td><td>'+item.modelo+'</td></tr>';
          ids+=item.id+',';
          trs+='<tr><td>'+item.codigo+'</td><td>'+item.item+'</td><td><textarea class="form-control" id="txt_l_salida_'+item.idls+'">'+item.salida+'</textarea></td><td>';
          if(estado==0){
            trs+='<button class="btn btn-primary btn-sm" onclick="detalle_salida('+item.idls+')"><i class="bx bx-save"></i></button>';
          }
          trs+='</td></tr>';

          tre+='<tr><td>'+item.codigo+'</td><td>'+item.item+'</td><td>'+item.salida+'</td><td><textarea class="form-control" id="txt_l_entrada_'+item.idls+'">'+item.entrada+'</textarea></td><td>';
          if(estado==0)
          {
            tre+='<button class="btn btn-primary btn-sm" onclick="detalle_entrada('+item.idls+')" ><i class="bx bx-save"></i></button>';
          }
          tre+='</td></tr>';
      	})

        ids = ids.substring(1,-1);
        $('#txt_lineas').val(ids);
      	$('#tbl_lineas').html(tr);
        $('#tbl_lineas_salida').html(trs);
        $('#tbl_lineas_entrada').html(tre);

        if(ids!='')
        {
          var url = '../lib/Reporte_pdf.php?reporte_cedula_lista=true&id='+ids
          $('#iframe').attr('src',url);
        }
      }
    });
}

function detalle_salida(id)
{
  var obs = $('#txt_l_salida_'+id).val();
  parametros = 
  {
    'id':id,
    'obs': obs,
  }
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/ingresar_procesoC.php?observacion_salida=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        if(response==1)
        {
          Swal.fire('Observacion de salida Registrada','','success');
        }
      }
    });

}

function detalle_entrada(id)
{
  var obs = $('#txt_l_entrada_'+id).val();
  parametros = 
  {
    'id':id,
    'obs': obs,
  }
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/ingresar_procesoC.php?observacion_entrada=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        if(response==1)
        {
          Swal.fire('Observacion de Entrada Registrada','','success');
        }
      }
    });

}

function cargar_solicitud(id)
{
  parametros = 
  {
    'id':id,
  }
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/ingresar_procesoC.php?solicitud_pdf=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        response = response[0];
        $('#lbl_responsable').text(response.PERSON_NOM);
        $('#lbl_destino').text(response.destino);
        $('#lbl_fechas').text(response.fecha_salida.date.substr(0,10));
        $('#lbl_fechae').text(response.fecha_regreso.date.substr(0,10));
        $('#lbl_motivo').text(response.observacion);
        $('#lbl_duracion').text(response.duracion);
        var broker = response.fecha_notificacion_broker;
        // var veri_salida = ;

        if(response.salida_verificada==1)
        {
           $('#rbl_contatacion').prop('checked',true);
           $('#rbl_contatacion').attr('disabled',true);
        }
        if(response.entrada_verificada==1)
        {
           $('#rbl_contatacion2').prop('checked',true);
           $('#rbl_contatacion2').attr('disabled',true);
        }
        if(broker!='' && broker!=null)
        {
          $('#rbl_notificacion').prop('checked',true);
        }
        if(response.paso!=null)
        {
          location.href = '#step-'+response.paso;
        }
        // console.log(broker);
        // if(response.fecha_notificacion_broker.hasOwnProperty('date'))
        // {
        //   $('#rbl_notificacion').prop('checked',true);
        // }
        console.log(broker);
      }
    });

}

function copy_message()
{
	/*var contenidoDiv = $("#div_mensaje")[0]; // Obtenemos el elemento DOM
        
        // Usamos html2clipboard.js para copiar el contenido al portapapeles
        html2clipboard.copy(contenidoDiv);

        alert("Contenido de la tabla copiado al portapapeles");*/

      var divContenido = document.getElementById('div_mensaje');
      var range = document.createRange();
      range.selectNode(divContenido);
      window.getSelection().removeAllRanges();
      window.getSelection().addRange(range);

      try {
        document.execCommand('copy');
        alert('Contenido de la tabla copiado al portapapeles');
      } catch (e) {
        console.error('Error al copiar el contenido', e);
      }

      window.getSelection().removeAllRanges();

}

 function solicitud_salida()
 {
   // var donante = $('#txt_donante').val();
   // var ci = $('#txt_ci_donante').val();
   // var director = $('#txt_director').val();
   // var unidad = $('#txt_nom_unidad').val();
   // var sub = $('#txt_subtitulo5').val();

   var url = '../lib/phpword/generar_word.php?solicitud_salida=true&id='+id;    
   window.open(url, '_blank');
 }

 function guardar_proceso()
 {
     var link = location.href.split('-');
     var step = link[1];
     $('#txt_anterior').val(0);


      console.log(step);


     // alert('proceso guardado'+ step);
     var rbl = $('#rbl_notificacion').prop('checked');
      if(rbl==false)
      {
        Swal.fire('No se ha notificado al broker','','info').then(function(){
          location.href = '#step-1';
          return false;
        });
      }


      switch(step)
      {
        case '3':
          var c = $('#rbl_contatacion').prop('checked');
          console.log(c)
          if(c==false)
          {
            Swal.fire('Seleccione la opcion de Activos verificados fisicamente','','info').then(function(){
              location.href = '#step-3';
              return false;
            });
          }
        break;
      }
      // Swal.fire({
      //     title: 'Are you sure?',
      //     text: "You won't be able to revert this!",
      //     icon: 'warning',
      //     showCancelButton: true,
      //     confirmButtonColor: '#3085d6',
      //     cancelButtonColor: '#d33',
      //     confirmButtonText: 'Yes, delete it!'
      //   }).then((result) => {
      //     if (result.isConfirmed) {
           step_save(step);
      //     }
      //   })

     
 }

 function veri_salida()
 {
   var veri = $('#rbl_contatacion').prop('checked');
   if(veri)
   {
     var parametros = 
      {
        'id':id,
      }
       $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/ingresar_procesoC.php?veri_salida=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
         
          console.log(response);
        }
      });
   }

 }
 function veri_entrada()
 {
   var veri = $('#rbl_contatacion2').prop('checked');
   if(veri)
   {
     var parametros = 
      {
        'id':id,
      }
       $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/ingresar_procesoC.php?veri_entrada=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
         
          console.log(response);
        }
      });
   }

 }

 function step_save(step)
 {

   var ant = $('#txt_anterior').val();
   var parametros = 
      {
        'step':step,
        'id':id,
        'ant':ant,
      }
       $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/ingresar_procesoC.php?update_step=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
         
          console.log(response);
        }
      });
 }
 function retroceder_proceso(){
   var link = location.href.split('-');
   var step = link[1];
   // alert('proceso guardado'+ step);
   $('#txt_anterior').val(1);
 }
 function notificacion_broker()
 {
    var ant = $('#txt_anterior').val();
    var rbl = $('#rbl_notificacion').prop('checked');
    var parametros = {
      'ant':ant,
      'rbl':rbl,
      'id': id,
    }
      $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/ingresar_procesoC.php?notificacion_broker=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
         
          console.log(response);
        }
      });

 }

 function finalizar_proceso()
 {
    var veri = $('#rbl_contatacion2').prop('checked');
    if(veri==false)
    {
      Swal.fire('Activos verificados fisicamente (Entrada) no seleccionado','seleccione esta opcion solo si esta seguro de haber realizado una verificacion real','info');
      return false;
    }

   Swal.fire({
          title: 'Esta seguro de finalizar el proceso?',
          text: "Se va ha finalizar el proceso",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si'
        }).then((result) => {
          if (result.isConfirmed) {
             var parametros = {
                'id': id,
              }
                $.ajax({
                  data:  {parametros:parametros},
                  url:   '../controlador/ingresar_procesoC.php?finalizar_proceso=true',
                  type:  'post',
                  dataType: 'json',
                  success:  function (response) { 
                    if(response==1)
                    {
                      var url = location.href;
                      url = url.replace('estado=0', 'estado=1');
                      location.href = url;
                    }
                  }
                });
          }
        })
 }

 function enviar_correo()
 {
    var tbl = $('#div_mensaje').html();
    var men = $('#mensaje').val();
    var to  = $('#txt_to').val();
    var subjet  = $('#txt_subjet').val();

    parametros = 
    {
      'to':to,
      'sub':subjet,
      'men':men,
      'tbl':tbl,
    }

    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/ingresar_procesoC.php?enviar_correo=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
          if(response==true)
          {
           Swal.fire('Email enviado','','success').then(function(){
            notificacion_broker();
            $('#rbl_notificacion').prop('checked',true);
           });
          }else
          {
           Swal.fire('No se pudo Enviar el correo','','error');
          }
        }
    });
 }