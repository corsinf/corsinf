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
        data: JSON.stringify({                
            tipo: '1',
            tipoBusqueda:$('#ddl_metodo_busqueda').val(),
            IpAddress:$('#txt_ip_address').val(),
            datos: '^XA^FO50,50^A0N,50,50^FDHello, World!^FS^XZ'
        }),
        success: function(response) {
            let data = JSON.parse(response);
            data.forEach(function(item,i){
                console.log(item)
                op+='<option value="'+item.id+'">'+item.nombre+'</option>'
            })

            $('#ddl_impresora').html(op);
        },
        error: function(xhr, status, error) {
            console.error('Error en la solicitud:', status, error);
        }
    });

	// var parametros = {
    //     'tipoBusqueda':$('#ddl_metodo_busqueda').val(),
    // }

    //      $.ajax({
    //       data:  {parametros:parametros},
    //       url:  '../controlador/DISENIADOR_ZPL/di_diseniadorC.php?buscar_impresora=true',
    //       type:  'post',
    //       dataType: 'json',
    //       /*beforeSend: function () {   
    //            var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
    //          $('#tabla_').html(spiner);
    //       },*/
    //         success:  function (response) {
    //         	console.log(response)
    //         	const lista = Object.values(response);
    //         	op = '';
    //         	lista.forEach(function(item,i){
    //         		console.log(item)
    //         		op+='<option value="'+item+'">'+item+'</option>'
    //         	})
    //         	$('#ddl_lista_empresas').html(op);
    //            // console.log(response)
    //       },
    //         error: function (error) {
    //              setTimeout(() => {                    
    //                $('#modal_print').modal('hide');
    //             }, 2000);
                
    //         },
    //     });
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