 $( document ).ready(function() {

 		// total de activos
	 	 $('#reporte_pdf_total').click(function(){
	      var url='../lib/Reporte_pdf.php?reporte_pdf_total';
	      window.open(url, '_blank');
	    });

	 	  $('#imprimir_excel_tot').click(function(){
       var url = '../lib/Reporte_excel_spout.php?reporte_total';                 
           window.open(url, '_blank');
       });

	 	//bajar
	 	   $('#reporte_pdf_bajas').click(function(){
	 	   	  var rangos = '&desde='+$('#txt_desde').val()+'&hasta='+$('#txt_hasta').val();
		      var url='../lib/Reporte_pdf.php?reporte_pdf_bajas'+rangos;
		      window.open(url, '_blank');
       });
       $('#imprimir_excel_bajas').click(function(){
       	 var rangos = '&desde='+$('#txt_desde').val()+'&hasta='+$('#txt_hasta').val();
       		var url = '../lib/Reporte_excel_spout.php?reporte_sap_bajas'+rangos;                 
          window.open(url, '_blank');
       });
       
	 	// patrimoniales	 	   
        $('#reporte_pdf_patrimoniales').click(function(){
        	var rangos = '&desde='+$('#txt_desde').val()+'&hasta='+$('#txt_hasta').val();
		      var url='../lib/Reporte_pdf.php?reporte_pdf_patrimoniales'+rangos;  
		      window.open(url, '_blank');
       	});       
       $('#imprimir_excel_patrimoniales').click(function(){
	 	   	  var rangos = '&desde='+$('#txt_desde').val()+'&hasta='+$('#txt_hasta').val();
       		var url = '../lib/Reporte_excel_spout.php?reporte_sap_patrimoniales'+rangos;                 
          window.open(url, '_blank');
       });
	 	// terceros
        $('#reporte_pdf_terceros').click(function(){
		      var url='../lib/Reporte_pdf.php?reporte_pdf_terceros';
		      window.open(url, '_blank');
       	});
       		$('#imprimir_excel_terceros').click(function(){
	 	   	  var rangos = '&desde='+$('#txt_desde').val()+'&hasta='+$('#txt_hasta').val();
       		var url = '../lib/Reporte_excel_spout.php?reporte_sap_terceros'+rangos;                 
          window.open(url, '_blank');
       });

    // informes dinamicos
     $('#Generar_excel').click(function(){
 	   	  var filtros = $('#form_filtro').serialize();
     		var url = '../lib/excel_spout.php?reporte_dinamico=true&id='+id+'&'+filtros;                 
        window.open(url, '_blank');
     });


 })

 function limpiar_rangos()
 {
		$('#txt_desde').val('');
 	 	$('#txt_hasta').val('');
 }
 function  cargar_lista_reporte()
 {
 		var opcion = '<option value="">seleccione tipo de reporte</option>';
    $.ajax({
      // data:  {id:id},
      url:  '../controlador/reportesC.php?tipo_reporte=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) {
          // console.log(response);
          $.each(response,function(i,item){
            opcion+="<option value='"+item.ID+"'>"+item.NOMBRE+"</option>";
          })
           $('#ddl_tipo_reporte').html(opcion); 
           $('#nuevo_reporte').modal('show');
      }
    });
 }

 function crear_reporte()
 {
	nombre = $('#txt_reporte').val();
	tipo = $('#ddl_tipo_reporte').val();
	detalle = $('#txt_detalle').val();
	if(nombre=='' || tipo=='')
	{
		Swal.fire('Llene todos los campos','','info');
		return false;		
	}
	parametros= 
	{
		'nombre':nombre,
		'tipo':tipo,
		'detalle':detalle,
	}
	$.ajax({
	  data:  {parametros:parametros},
	  url:  '../controlador/reportesC.php?crear_reporte=true',
	  type:  'post',
	  dataType: 'json',
	  /*beforeSend: function () {   
	       var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
	     $('#tabla_').html(spiner);
	  },*/
	    success:  function (response) {
	    	if(response.respuesta==1)
	    	{
	    		location.href = 'inicio.php?acc=nuevo_reporte&id='+response.id;
	    	}else if(response.respuesta ==-2)
	    	{
	    		Swal.fire('','Ya existe un reporte con este nombre y tipo de reporte','info');
	    	}else
	    	{
	    		Swal.fire('','No se puedo ingresar','error');
	    	}	     
	  }
	});
 }
	function datos_reporte(id)
	{
			parametros= 
			{
				'id':id,
			}
			$.ajax({
			  data:  {parametros:parametros},
			  url:  '../controlador/reportesC.php?datos_reporte=true',
			  type:  'post',
			  dataType: 'json',
			  /*beforeSend: function () {   
			       var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
			     $('#tabla_').html(spiner);
			  },*/
			    success:  function (response) {
			    	if(response!="")
			    	{
			    		 $('#campos').html(response.div);
			    		 $('#txt_titulo').val(response.titulo);
			    		 $('#txt_detalle').val(response.detalle);
			    		 campos = response.campos.split(',');
			    		 campos.forEach(function(item,i){
			    		 	$('#'+item).prop('checked',true);
			    		 	// console.log(item);
			    		 })
			    		 // console.log(campos);
			    	}
			    	// console.log(response);
			  }
			});
	} 
	function guardar_campos()
	{

		campos = $('#campos_informe').serialize();
		campos+= '&id='+id;
		$.ajax({
			  data:  campos,
			  url:  '../controlador/reportesC.php?guardar_campos=true',
			  type:  'post',
			  dataType: 'json',
			  /*beforeSend: function () {   
			       var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
			     $('#tabla_').html(spiner);
			  },*/
			    success:  function (response) {
			    	if(response==1)
			    	{
			    		Swal.fire('','Reporte Generado','success').then(function()
			    		{
			    			location.href = 'inicio.php?acc=reporte_detalle&id='+id;
			    		})			    		
			    	}
			  }
			});

	}

	function detalle_reporte(id)
	{
		// $('#myModal_espera').modal('show');
		var filtros = $('#form_filtro').serialize();
		var pag = $('#txt_pag').val();
		var pag2 = $('#txt_pag1').val();
		filtros+='&id='+id+'&pag='+pag+'&pag2='+pag2;
		$.ajax({
			  data:  filtros,
			  url:  '../controlador/reportesC.php?detalle_reporte=true',
			  type:  'post',
			  dataType: 'json',
			  /*beforeSend: function () {   
			       var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
			     $('#tabla_').html(spiner);
			  },*/
			    success:  function (response) {

			    	$('#txt_detalle').val(response.detalle)
			    	$('#txt_titulo').val(response.nombre);
			    	$('#pag').html(response.paginacion);

			    	console.log(response);
			    	//agregamos cabecera primero
			    	var h = '<tr>';
			    	var num_cab = 0;
			    	$.each(response.head, function(index, value) {
			    		// console.log(value);
                h+='<th>'+value.data+'</th>';
                num_cab+=1;
            });
            h+='</tr>';
			    	$('#tbl_header').html(h);   	

			    	$('#tbl_datos').html(response.body);
			  },
			  error: function(xhr, status, error) {
			    // Manejo de errores
			    console.log(error);
			    console.log(xhr);
			    console.log(status);
			  }
			});

	}

	function filtros_reporte(id)
	{
		// $('#myModal_espera').modal('show');
		var filtros = $('#form_filtro').serialize();
		filtros+='&id='+id;
		$.ajax({
			  data:  filtros,
			  url:  '../controlador/reportesC.php?filtro_reporte=true',
			  type:  'post',
			  dataType: 'json',
			  /*beforeSend: function () {   
			       var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
			     $('#tabla_').html(spiner);
			  },*/
			    success:  function (response) {
			    		// console.log(value);
			    	$('#filtros').html(response);    
			  },
			  error: function(xhr, status, error) {
			    // Manejo de errores
			    console.log(error);
			  }
			});
	}

	function lista_reportes()
	{
		$.ajax({
		  // data:  filtros,
		  url:  '../controlador/reportesC.php?lista_reportes=true',
		  type:  'post',
		  dataType: 'json',
		  /*beforeSend: function () {   
		       var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
		     $('#tabla_').html(spiner);
		  },*/
		    success:  function (response) {
		    console.log(response); 
		    $('#lista_reportes').html(response);
		  },
		  error: function(xhr, status, error) {
		    // Manejo de errores
		    console.log(error);
		  }
		});
	}
function paginacion(num)
{
  $('#txt_pag').val(num);
  detalle_reporte(id);  
}

function eliminar_reporte(id)
{
	 Swal.fire({
      title: 'Quiere eliminar este registro?',
      text: "Esta seguro de eliminar este registro!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) {
        	eliminar_repo(id)
        }
    })

}

function eliminar_repo(id)
{
		$.ajax({
		  data:  {id,id},
		  url:  '../controlador/reportesC.php?eliminar_reporte=true',
		  type:  'post',
		  dataType: 'json',
		  /*beforeSend: function () {   
		       var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
		     $('#tabla_').html(spiner);
		  },*/
		    success:  function (response) {
		    	if(response==1)
		    	{
		    		lista_reportes();
		    	}
		  },
		  error: function(xhr, status, error) {
		    // Manejo de errores
		    console.log(error);
		  }
		});
}





