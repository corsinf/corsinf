<script type="text/javascript">
 $( document ).ready(function() {
  autocompletar_empresa();
 	cargar_licencias();
  modulos_sistemas_all()
  
 })


function autocompletar_empresa(){
      $('#ddl_empresa').select2({
        placeholder: 'Seleccione Empresa',
        ajax: {
          url: '../controlador/licenciasC.php?lista_empresas=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
      });
}

  
function cargar_licencias()
{
  	 
   $.ajax({
     // data:  {parametros:parametros},
     url:   '../controlador/licenciasC.php?lista_licencias_all=true',
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

function modulos_sistemas_all()
{
     
   $.ajax({
     // data:  {parametros:parametros},
     url:   '../controlador/licenciasC.php?modulos_sistemas_all=true',
     type:  'post',
     dataType: 'json',
     success:  function (response) {  
      var op = '<option value = "">Seleccione Modulo</option>';
      response.forEach(function(item,i){
          // console.log(item);
         op+= '<option value ="'+item.id_modulos+'">'+item.nombre_modulo+'</option>';
      })
        $('#ddl_modulos_sistema').html(op);
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
     url:   '../controlador/licenciasC.php?eliminar_licencia_definitivo=true',
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

function validar_year()
{
  let desde = $('#txt_desde').val();
  let hasta = $('#txt_hasta').val();
  if(desde=='')
  {
    return false; 
  }
  var fechaEspecifica = new Date(desde);
  fechaEspecifica.setFullYear(fechaEspecifica.getFullYear() + 1);
  // console.log(fechaEspecifica);
  fechaEspecifica =  fechaEspecifica;
  $('#txt_hasta').val(fechaEspecifica);

}
function add_licencia()
{

  var emp = $('#ddl_empresa').val();
  var cla = $('#txt_clave').val();
  var mod = $('#ddl_modulos_sistema').val();
  var des= $('#txt_desde').val();
  var has = $('#txt_hasta').val();
  var maq = $('#txt_maquinas').val();
  if(emp == '' || cla == '' || mod == '' || des == '' || has == '' || maq == '')
  {
    Swal.fire('','Ingrese todo los datos','info');
    return false;
  }


  var parametros = {
    'empresa': emp,
    'clave': cla,
    'modulo': mod,
    'desde': des,
    'hasta': has,
    'maquinas':maq,
  }
   $.ajax({
     data:  {parametros:parametros},
     url:   '../controlador/licenciasC.php?add_licencias=true',
     type:  'post',
     dataType: 'json',
       success:  function (response) {          
        if(response==1)
        {
            Swal.fire('','Licencia Ingresada','success').then(function(){
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
            <!-- <h6 class="mb-0 text-uppercase">Form Wizard</h6> -->
            <hr>
            <div class="card">
              <div class="card-body">
                <div class="row">
                    <div class="col-sm-4">
                      <b>Empresa</b>
                      <select class="form-select form-select-sm" id="ddl_empresa" name="ddl_empresa">
                        <option>Seleccine empresa</option>
                      </select>
                    </div>       
                     <div class="col-sm-4">
                      <b>Clave</b>
                      <div class="input-group">
                          <input type="" class="form-control form-control-sm" name="txt_clave" id="txt_clave">
                          <span title="Generar Licencia">
                            <button class="btn btn-sm btn-primary"><i class="bx bx-refresh"></i></button>
                          </span>
                      </div>
                    </div> 
                    <div class="col-sm-4">
                      <b>Modulo</b>
                      <select class="form-select form-select-sm" id="ddl_modulos_sistema" name="ddl_modulos_sistema">
                        <option>Seleccine empresa</option>
                      </select>        
                    </div>    
                    <div class="col-sm-2">
                      <b>Desde</b>
                          <input type="date" class="form-control form-control-sm" name="txt_desde" id="txt_desde" onblur="validar_year()" value="<?php echo date('Y-m-d'); ?>">
                    </div>     
                    <div class="col-sm-2">
                      <b>Hasta</b>
                      <input type="date" class="form-control form-control-sm" name="txt_hasta" id="txt_hasta">                          
                    </div>                   
                   <div class="col-sm-2">
                    <b>N° Maquinas</b>
                    <input type="" class="form-control form-control-sm" name="txt_maquinas" id="txt_maquinas">
                  </div>    
                  <div class="col-sm-6 text-end">
                    <br>
                    <button class="btn btn-sm btn-primary" onclick="add_licencia()">Agregar</button>
                  </div>                             
                </div>
                <hr>
                <div class="row">
                	<div class="table-responsive">
                		<table class="table table-hover">
	                		<thead>
                        <th>Empresa</th>
                        <th>Licencia</th>
	                			<th>Modulo</th>
		                		<th>Desde</th>
                        <th>Hasta</th>
                        <th>Maquinas</th>
                        <th>Estado</th>
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

