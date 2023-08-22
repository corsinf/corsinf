<?php  include('../../cabeceras/header.php');?>
<script type="text/javascript">
  $( document ).ready(function() {
  	lista_solicitudes()
  })

  function lista_solicitudes()
  {
  	$.ajax({
     // data:  {parametros:parametros},
     url:   '../../controlador/prestamos_bienesC.php?lista_solicitudes_all=true',
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

       		html+='<tr>'+
					'<td>'+
						'<div class="d-flex align-items-center">'+									
							'<div class="ms-2">'+
								'<h6 class="mb-0 font-14"><a href="ingresar_proceso.php?id='+item.id_solicitud+'&estado='+item.estado+'">'+item.PERSON_NOM+'</a></h6>'+
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
       	});       
        $('#tbl_lista').html(html);
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
	    	<div class="col-xl-12 mx-auto">
              	
<div class="card radius-10">
		<div class="card-body">
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
						<tr>
							<td>
								<div class="d-flex align-items-center">									
									<div class="ms-2">
										<h6 class="mb-0 font-14">Ronald Waters</h6>
										<p class="mb-0 font-13 text-secondary">Lead Designers</p>
									</div>
								</div>
							</td>
							<td>$89,620</td>
							<td class=" w-25">
								<div class="progress radius-10 h-5">
									<div class="progress-bar bg-primary w-75" role="progressbar"></div>
								</div>
							</td>
							<td>14 Oct 2020</td>
							<td>
								<div class="badge rounded-pill bg-primary w-100">In Progress</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="d-flex align-items-center">									
									<div class="ms-2">
										<h6 class="mb-0 font-14">David Buckley</h6>
										<p class="mb-0 font-13 text-secondary">Lead Designers</p>
									</div>
								</div>
							</td>
							<td>$38,520</td>
							<td class=" w-25">
								<div class="progress radius-10 h-5">
									<div class="progress-bar bg-danger w-50" role="progressbar"></div>
								</div>
							</td>
							<td>15 Oct 2020</td>
							<td>
								<div class="badge rounded-pill bg-danger w-100">Cancelled</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="d-flex align-items-center">
									
									<div class="ms-2">
										<h6 class="mb-0 font-14">James Caviness</h6>
										<p class="mb-0 font-13 text-secondary">Lead Designers</p>
									</div>
								</div>
							</td>
							<td>$63,820</td>
							<td class=" w-25">
								<div class="progress radius-10 h-5">
									<div class="progress-bar bg-success w-100" role="progressbar"></div>
								</div>
							</td>
							<td>16 Oct 2020</td>
							<td>
								<div class="badge rounded-pill bg-success w-100">Completed</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
			</div>
		</div>
	</div>
</div>



<?php include('../../cabeceras/footer.php'); ?>