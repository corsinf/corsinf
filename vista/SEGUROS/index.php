<?php include('../../cabeceras/header.php'); ?> 
 <script type="text/javascript">
  $( document ).ready(function() {
  	solicitudes();  
  })

function solicitudes()
{           
   $.ajax({
     // data:  {parametros:parametros},
     url:   '../../controlador/prestamos_bienesC.php?lista_solicitudes=true',
     type:  'post',
     dataType: 'json',
       success:  function (response) {   
       console.log(response); 
       	html ="";
       	response.forEach(function(item,i){
       		html+='<div class="alert alert-danger border-0 bg-danger alert-dismissible fade show py-2">'+
					'<div class="d-flex align-items-center">'+					
						'<div class="text-white"><a href="ingresar_proceso.php?id='+item.id_solicitud+'" class="btn btn-primary"><i class="font-35 bx bxs-message-square-x"></i>Procesar</a>'+
						'</div>'+
						'<div class="ms-3">'+
							'<h6 class="mb-0 text-white">solicitud de Salida de bien - Fecha Solicitud:</b><i class="text-white">'+item.fecha.date.substr(0,10)+'</i> </h6>'+
							'<div class="text-white">Encargado:'+item.PERSON_NOM+'</div>'+							
						'</div>'+
					'</div>'+
					'<div class="btn-close">'+
					'<button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button></div>'+
				'</div>';
       	});       
        $('#alertas_solicitudes').html(html);
     }
   });
}

</script>
<div class="page-wrapper">
	<div class="page-content">
	    <!--breadcrumb-->
	    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	      <div class="breadcrumb-title pe-3">Inicio</div>
	      <div class="ps-3">
	        <nav aria-label="breadcrumb">
	          <ol class="breadcrumb mb-0 p-0">
	            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
	            </li>
	            <li class="breadcrumb-item active" aria-current="page"></li>
	          </ol>
	        </nav>
	      </div>     
	    </div>
	    <!--end breadcrumb-->
	    <hr>
	    <div class="row">
	    	<div class="col-sm-12" id="alertas_solicitudes">
	    		
	    	</div>	    	
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="alert border-1 border-start border-5 border-warning alert-dismissible fade show py-2">
					<div class="d-flex align-items-center">
						<div class="font-35 text-warning"><i class="bx bx-info-circle"></i>
						</div>
						<div class="ms-3">
							<h6 class="mb-0 text-warning">Activo a retornar</h6>
							<div>Encargado: luis example - Activo: Computador</div>
						</div>
					</div>
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
			</div>
		</div>
	    <!-- <div class="row">
	    	<div class="col-xl-12 mx-auto">
              	<div class="card">
                	<div class="card-body">
               			<div class="row">
					    	s
						</div>
					</div>
				</div>
			</div>
		</div> -->
	</div>
</div>

<?php include('../../cabeceras/footer.php'); ?>