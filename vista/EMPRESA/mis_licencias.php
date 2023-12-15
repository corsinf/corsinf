<script type="text/javascript">
 $( document ).ready(function() {
 	cargar_licencias();
  
 })
  
function cargar_licencias()
{
  	 
   $.ajax({
     // data:  {parametros:parametros},
     url:   '../controlador/licenciasC.php?lista_licencias=true',
     type:  'post',
     dataType: 'json',
     /*beforeSend: function () {   
          var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
        $('#tabla_').html(spiner);
     },*/
       success:  function (response) {  

      $('#tbl_licencias').html(response);
     }
   });
}

function registrar_licencia(modulo)
{
	var lic = $('#txt_licencia_'+modulo).val();
	var parametros = {
		'licencias':lic,
		'modulo':modulo,
	}
	 $.ajax({
     data:  {parametros:parametros},
     url:   '../controlador/licenciasC.php?guardar_licencia=true',
     type:  'post',
     dataType: 'json',
     /*beforeSend: function () {   
          var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
        $('#tabla_').html(spiner);
     },*/
       success:  function (response) {  
       	if(response==-2)
       	{
       		Swal.fire('Error en licencia','Revice su licencia','error');
       	}
       	if(response==1)
       	{
       		Swal.fire('Registrado','Licencia Registrada','success').then(function(){
       			cargar_licencias();
       		});
       	}

      // $('#tbl_licencias').html(response);
     }
   });

}


function eliminar_licencia(id)
  {
    Swal.fire({
      title: 'Quiere eliminar esta Licencia?',
      text: "Esta seguro de eliminar esta licencia!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) { 
          eliminar(id);
        }
      });
  }


function eliminar(id)
{
	var parametros = {
		'licencias':id,
	}
	 $.ajax({
     data:  {parametros:parametros},
     url:   '../controlador/licenciasC.php?eliminar_licencia=true',
     type:  'post',
     dataType: 'json',
     /*beforeSend: function () {   
          var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
        $('#tabla_').html(spiner);
     },*/
       success:  function (response) {  
       	
       	if(response==1)
       	{
       		Swal.fire('Licencia eliminada','La licencia fue eliminada','success').then(function(){
       			cargar_licencias();
       		});
       	}

      // $('#tbl_licencias').html(response);
     }
   });
}

</script>
<div class="page-wrapper">
      <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
          <div class="breadcrumb-title pe-3">licencias</div>
          <div class="ps-3">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Wizard</li>
              </ol>
            </nav>
          </div>         
        </div>
        <!--end breadcrumb-->
        <div class="row">
          <div class="col-xl-12 mx-auto">
            <h6 class="mb-0 text-uppercase">Form Wizard</h6>
            <hr>
            <div class="card">
              <div class="card-body">
                <div class="row">
                	<div class="table-responsive">
                		<table class="table table-hover">
	                		<thead>
	                			<th>Modulo</th>
		                		<th>Clave</th>
		                		<th></th>                			
	                		</thead>
	                		<tbody id="tbl_licencias">
	                			
	                		</tbody>
	                		
	                	</table>                	
                		
                	</div>
                	
                	<!-- <li class="list-group-item d-flex align-items-center radius-10 mb-2 shadow-sm">
						<div class="d-flex align-items-center">
							<div class="font-20">
							</div>
							<div class="flex-grow-1 ms-2">
								<h6 class="mb-0">Modulos</h6>
								
							</div>
						</div>
						<div class="ms-auto">
						
						</div>
					</li> -->
                </div>
               
              </div>
            </div>
          </div>
        </div>
        <!--end row-->
      </div>
    </div>

