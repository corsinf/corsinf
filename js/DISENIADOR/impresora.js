let Impresoras;
function lista_impresora()
{
     $.ajax({
      // data:  {parametros:parametros},
      url:  '../controlador/DISENIADOR_ZPL/di_diseniadorC.php?lista_impresora=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) {
        	console.log(response)
            Impresoras = response;
        	// const lista = Object.values(response);
        	op = '';
        	response.forEach(function(item,i){
        		console.log(item)
        		op+='<option value="'+item.id+'">'+item.nombre+'</option>'
        	})
        	$('#ddl_impresora').html(op);
           // console.log(response)
      },
        error: function (error) {
             setTimeout(() => {                    
               $('#modal_print').modal('hide');
            }, 2000);
            
        },
    });
}

function buscar_impresora()
{
	 $.ajax({
        url: 'http://localhost:3000',
        type: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({                
            tipo: '1',
            tipoBusqueda:$('#ddl_metodo_busqueda').val(),
            IpAddress:$('#txt_ip_address').val()
        }),
        success: function(response) {
            console.log(response)
            let dataObj = JSON.parse(response);
            let dataArray = Object.values(dataObj);
            var op = "";
            dataArray.forEach(function(item,i){
                console.log(item)
                op+='<option value="'+item+'">'+item+'</option>'
            })

            $('#ddl_lista_empresas').html(op);
        },
        error: function(xhr, status, error) {

            Swal.fire("Plugin de impresora No encontrado","Descargue el plugin de la impresora","error");
            console.error('Error en la solicitud:', status, error);
        }
    });
}

function imprimirAgente(data)
{
    impSelect = $('#ddl_impresora').val();
    let impresora = Impresoras.find(item => item.id === impSelect);
    console.log(impresora)
    $.ajax({
        url: 'http://localhost:3000',
        type: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({                
            tipo: '2',
            impresora:impresora.nombre,
            zpl: data.zpl,
            ipaddress:impresora.ip_impresora || "",
            portnumb:impresora.puerto_impresora || "",
            ruta:impresora.tipo_impresora || "",
            tipobusqueda:impresora.tipo_impresora,
        }),
        success: function(response) {
            console.log(response)
            let dataObj = JSON.parse(response);
            let dataArray = Object.values(dataObj);
            var op = "";

            console.log(dataArray)
            if(dataArray[1]=='-1')
            {
                Swal.fire(dataArray[2],dataArray[0],"error");
            }
            // dataArray.forEach(function(item,i){
            //     console.log(item)
            //     op+='<option value="'+item+'">'+item+'</option>'
            // })

            // $('#ddl_lista_empresas').html(op);
        },
        error: function(xhr, status, error) {
            Swal.fire("Plugin de impresora No encontrado","Descargue el plugin de la impresora","error");
            console.error('Error en la solicitud:', status, error);
        }
    });
}

function guardar_impresora()
{
	if($('#ddl_lista_empresas').val()=='')
	{
		Swal.fire("Seleccione una impresora","","error")
		return false;
	}
	var parametros = {
        'tipoBusqueda':$('#ddl_metodo_busqueda').val(),
        'impresora':$('#ddl_lista_empresas').val(),
        'impresora':$('#ddl_lista_empresas').val(),
        'ipAddress': $('#txt_ip_address').val(),
        'puerto':$('#txt_puerto').val(),
    }
     $.ajax({
      data:  {parametros:parametros},
      url:  '../controlador/DISENIADOR_ZPL/di_diseniadorC.php?guardar_impresora=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) {
        	console.log(response)
        	const lista = Object.values(response);
        	op = '';
        	lista.forEach(function(item,i){
        		console.log(item)
        		op+='<option value="'+item+'">'+item+'</option>'
        	})
        	$('#ddl_lista_empresas').html(op);
           // console.log(response)
      },
        error: function (error) {
             setTimeout(() => {                    
               $('#modal_print').modal('hide');
            }, 2000);
            
        },
    });
}

// function descargarLib() {
//   const link = document.createElement('a');
//   link.href = '../lib/IMPRESORA/printerlib.rar';
//   link.download = 'Printerlib.rar';
//   document.body.appendChild(link);
//   link.click();

//   setTimeout(() => document.body.removeChild(iframe), 10000);
// }


// function descargarLib() {
//   const iframe = document.createElement('iframe');
//   iframe.style.display = 'none';
//   iframe.src = '../lib/IMPRESORA/CorsinfPrinter/printerlib.rar';
//   document.body.appendChild(iframe);
//   setTimeout(() => document.body.removeChild(iframe), 10000);
// }

function descargarLib() {
  window.location.href = '../controlador/DISENIADOR_ZPL/di_diseniadorC.php?DescargarLibPrinter=true';
}