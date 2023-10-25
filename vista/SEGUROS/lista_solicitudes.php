<?php  //include('../../cabeceras/header.php');?>
<script type="text/javascript">
  $( document ).ready(function() {
  	lista_solicitudes()
  })

  function lista_solicitudes()
  {
  	htmlC = '';
  	htmlP = '';
  	htmlI = '';
  	htmlT = '';

  	$.ajax({
     // data:  {parametros:parametros},
     url:   '../controlador/prestamos_bienesC.php?lista_solicitudes_all=true',
     type:  'post',
     dataType: 'json',
       success:  function (response) {   
       console.log(response); 
       	html ="";
       	paso = 4;
       	response.forEach(function(item,i){
       		porcentaje = 0;
       		fecha = '';
       		if(item.fecha!=null)
       		{
       			fecha = item.fecha.date.substr(0,10);
       		}
       		fecha_up = '';
       		if(item.fecha_update!=null)
       		{
       			fecha_up = item.fecha_update.date.substr(0,10);
       		}
       		if(item.paso!=null)
       		{
       			porcentaje = (item.paso * 100)/paso;
       		}
       		switch(item.paso)
					{
						case 4:
							estado ='<div class="badge rounded-pill bg-success w-100">Completo</div>';
							color = 'success';
						break;
						case 3:
						case 2:							
						case 1:
							estado ='<div class="badge rounded-pill bg-primary w-100">En proceso</div>';
							color = 'primary';
						break;
						default:
							estado ='<div class="badge rounded-pill bg-warning w-100">Ingresado</div>';
							color = 'warning';
						break;
					}			

       		html ='<tr>'+
					'<td>'+
						'<div class="d-flex align-items-center">'+									
							'<div class="ms-2">'+
								'<h6 class="mb-0 font-14"><a href="inicio.php?acc=ingresar_proceso&id='+item.id_solicitud+'&estado='+item.estado+'">'+item.PERSON_NOM+'</a></h6>'+
								'<!-- <p class="mb-0 font-13 text-secondary">Lead Designers</p> -->'+
							'</div>'+
						'</div>'+
					'</td>'+
					'<td>'+fecha+'</td>'+
					'<td class=" w-25">'+
						'<div class="progress radius-10 h-5">'+
							'<div class="progress-bar bg-'+color+' w-'+porcentaje+'" role="progressbar"></div>'+
						'</div>'+
					'</td>'+
					'<td>'+fecha_up+'</td>'+
					'<td>'+
					estado+	
					'</td>'+
				'</tr>';
				htmlT+=html;


				switch(item.paso)
					{
						case 4:
							htmlC+=html;
						break;
						case 3:
						case 2:							
						case 1:
							htmlP+=html;
						break;
						default:
							htmlI+=html;
						break;
					}			





       	});       
        $('#tbl_lista').html(htmlT);
        $('#tbl_lista_ing').html(htmlI);
        $('#tbl_lista_pro').html(htmlP);
        $('#tbl_lista_com').html(htmlC);
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
	    	<div class="card">
							<div class="card-body">
								<ul class="nav nav-tabs nav-danger" role="tablist">
									<li class="nav-item" role="presentation">
										<a class="nav-link active" data-bs-toggle="tab" href="#dangerhome" role="tab" aria-selected="true">
											<div class="d-flex align-items-center">
												<div class="tab-icon"><i class="bx bx-list-ul font-18 me-1"></i>
												</div>
												<div class="tab-title">Todos</div>
											</div>
										</a>
									</li>
									<li class="nav-item" role="presentation">
										<a class="nav-link" data-bs-toggle="tab" href="#dangerprofile" role="tab" aria-selected="false" tabindex="-1">
											<div class="d-flex align-items-center">
												<div class="tab-icon"><i class="bx bx-list-plus font-18 me-1"></i>
												</div>
												<div class="tab-title">Ingresados</div>
											</div>
										</a>
									</li>
									<li class="nav-item" role="presentation">
										<a class="nav-link" data-bs-toggle="tab" href="#dangercontact" role="tab" aria-selected="false" tabindex="-1">
											<div class="d-flex align-items-center">
												<div class="tab-icon"><i class="bx bx-cog font-18 me-1"></i>
												</div>
												<div class="tab-title">En proceso</div>
											</div>
										</a>
									</li>
									<li class="nav-item" role="presentation">
										<a class="nav-link" data-bs-toggle="tab" href="#dangercompletado" role="tab" aria-selected="false" tabindex="-1">
											<div class="d-flex align-items-center">
												<div class="tab-icon"><i class="bx bx-list-check font-18 me-1"></i>
												</div>
												<div class="tab-title">Completado</div>
											</div>
										</a>
									</li>
								</ul>
								<div class="tab-content py-3">
									<div class="tab-pane fade active show" id="dangerhome" role="tabpanel">
										<div class="table-responsive lead-table">
											<table class="table mb-0 align-middle">
												<thead class="table-light">
													<tr>
														<th>Solicitante</th>
														<th>Fecha solicitud</th>
														<th>Progreso</th>
														<th>Ultima actualizacion</th>
														<th>Estado</th>
													</tr>
												</thead>
												<tbody id="tbl_lista">
												</tbody>
											</table>
										</div>
									</div>
									<div class="tab-pane fade" id="dangerprofile" role="tabpanel">
										<div class="table-responsive lead-table">
											<table class="table mb-0 align-middle">
												<thead class="table-light">
													<tr>
														<th>Solicitante</th>
														<th>Fecha solicitud</th>
														<th>Progreso</th>
														<th>Ultima actualizacion</th>
														<th>Estado</th>
													</tr>
												</thead>
												<tbody id="tbl_lista_ing">
												</tbody>
											</table>
										</div>
									</div>
									<div class="tab-pane fade" id="dangercontact" role="tabpanel">
										<div class="table-responsive lead-table">
											<table class="table mb-0 align-middle">
												<thead class="table-light">
													<tr>
														<th>Solicitante</th>
														<th>Fecha solicitud</th>
														<th>Progreso</th>
														<th>Ultima actualizacion</th>
														<th>Estado</th>
													</tr>
												</thead>
												<tbody id="tbl_lista_pro">
												</tbody>
											</table>
										</div>
									</div>
									<div class="tab-pane fade" id="dangercompletado" role="tabpanel">
										<div class="table-responsive lead-table">
											<table class="table mb-0 align-middle">
												<thead class="table-light">
													<tr>
														<th>Solicitante</th>
														<th>Fecha solicitud</th>
														<th>Progreso</th>
														<th>Ultima actualizacion</th>
														<th>Estado</th>
													</tr>
												</thead>
												<tbody id="tbl_lista_com">
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
	    </div>	    	    
	</div>
</div>



<?php //include('../cabeceras/footer.php'); ?>