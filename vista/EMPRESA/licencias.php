<script type="text/javascript">
 $( document ).ready(function() {
  autocompletar_empresa();
 	cargar_licencias();
  modulos_sistemas_all()
  calcular_fecha();  
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

  if ($.fn.DataTable.isDataTable('#tbl_licencias_all')) {
      $('#tbl_licencias_all').DataTable().destroy();
  }

  var parametros = 
  {
    'empresa':$('#ddl_empresa').val(),
  }  	 
   $.ajax({
     data:  {parametros:parametros},
     url:   '../controlador/licenciasC.php?lista_licencias_all=true',
     type:  'post',
     dataType: 'json',
     success:  function (response) {  
      $('#tbl_licencias').html(response);

      $('#tbl_licencias_all').DataTable({
              dom: "<'row'<'col text-end'B>>" + // Botones alineados a la derecha
       "<'row'<'col-sm-12'tr>>" +
       "<'row'<'col-sm-6'i><'col-sm-6'p>>",
              buttons: [
                {
                  extend: 'excelHtml5',
                  text: '<i class="bi bi-file-earmark-excel"></i> Excel',
                  className: 'btn btn-success btn-sm'
                },
                {
                  extend: 'pdfHtml5',
                  text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
                  className: 'btn btn-danger btn-sm'
                }
              ],
              scrollX: true,
              searching: false,
              responsive: false,
          // paging: false,   
              info: false,   
              autoWidth: false,  
          order: [[1, 'asc']], // Ordenar por la segunda columna
              /*autoWidth: false,
              responsive: true,*/
              language: {
              url: '../assets/plugins/datatable/spanish.json'
            },
        });
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
        if(response==-3)
        {
          Swal.fire('Error en licencia','No existe base de datos de referencia','error');
        } 
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
  var pda = $('#txt_pda').val();
  var acti = $('#txt_num_activos').val();

  if(emp == '' || cla == '' || mod == '' || des == '' || has == '' || maq == '' || pda == '' || acti == '' )
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
    'pda':pda,
    'acti':acti,
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

function calcular_fecha()
{
  var valor = $('input[name="rbl_periodo_2"]:checked').val();
  var fecha = $('#txt_desde').val();

 const fechaOriginal = new Date(fecha);
fechaOriginal.setMonth(fechaOriginal.getMonth()+parseInt(valor));

// Formatear y asignar el nuevo valor
const nuevaFecha = fechaOriginal.toISOString().split('T')[0];

$('#txt_hasta').val(nuevaFecha);
  generar_key();
}

function generar_key()
{

  var emp = $('#ddl_empresa').val();
  var mod = $('#ddl_modulos_sistema').val();
  var des= $('#txt_desde').val();
  var has = $('#txt_hasta').val();
  var maq = $('#txt_maquinas').val();
  var pda = $('#txt_pda').val();
  var acti = $('#txt_num_activos').val();

  if(emp == '' || mod == '' || des == '' || has == '' || maq == '' || pda == '' || acti == '' )
  {
    // Swal.fire('','Ingrese todo los datos','info');
    return false;
  }

  var parametros = {
    'empresa': emp,
    'modulo': mod,
    'desde': des,
    'hasta': has,
    'maquinas':maq,
    'pda':pda,
    'acti':acti,
  }
   $.ajax({
     data:  {parametros:parametros},
     url:   '../controlador/licenciasC.php?generar_key=true',
     type:  'post',
     dataType: 'json',
       success:  function (response) {   

       $('#txt_clave').val(response);       
       
      // $('#tbl_licencias').html(response);
     }
   });

}

function borrar_seleccion()
{
   // $('#ddl_empresa').val('');
  $('#ddl_empresa').val(null).trigger('change');
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
                      <div class="d-flex align-items-center">
                        <select class="form-select form-select-sm" id="ddl_empresa" name="ddl_empresa" onchange=" cargar_licencias();">
                          <option value="">Seleccine empresa</option>
                        </select>
                        <button class="btn btn-sm btn-danger" onclick="borrar_seleccion()"><i class="bx bx-x me-0"></i></button>                        
                      </div>
                     
                    </div>  
                    <div class="col-sm-3">
                      <b>Modulo</b>
                      <select class="form-select form-select-sm" id="ddl_modulos_sistema" name="ddl_modulos_sistema">
                        <option>Seleccine empresa</option>
                      </select>        
                    </div>
                    <div class="col-sm-5">
                      <div class="row">
                        <div class="col-sm-12">
                          <label><input type="radio" onclick="calcular_fecha()" name="rbl_periodo_2" value="12" checked="">Anual</label>
                          <label><input type="radio" onclick="calcular_fecha()" name="rbl_periodo_2" value="6">Semestral</label>
                          <label><input type="radio" onclick="calcular_fecha()" name="rbl_periodo_2" value="3">trimestral</label>
                          <label><input type="radio" onclick="calcular_fecha()" name="rbl_periodo_2" value="1">Mesual</label>                          
                        </div>
                      </div>
                      <div class="row">
                         <div class="col-sm-6">
                            <div class="input-group input-group-sm mb-3">
                                <b class="input-group-text">Desde</b>
                                <input type="date" class="form-control form-control-sm" readonly="" name="txt_desde" id="txt_desde" onblur="validar_year()" value="<?php echo date('Y-m-d'); ?>">
                              
                            </div>
                          </div>     
                          <div class="col-sm-6">
                            <div class="input-group input-group-sm mb-3">
                              <b class="input-group-text">Hasta</b>
                              <input type="date" class="form-control form-control-sm" readonly="" name="txt_hasta" id="txt_hasta">
                            </div>
                          </div>  
                      </div>
                    </div>    
                                    
                   <div class="col-sm-2">
                    <b>N° Maquinas / users</b>
                    <input type="" class="form-control form-control-sm" name="txt_maquinas" id="txt_maquinas" placeholder="1" value="1" onblur="generar_key()">
                  </div>
                  <div class="col-sm-2">
                    <b>N° PDA</b>
                    <input type="" class="form-control form-control-sm" name="txt_pda" id="txt_pda" placeholder="1" value="1" onblur="generar_key()">
                  </div>
                  <div class="col-sm-2">
                    <b>N° Activos</b>
                    <input type="" class="form-control form-control-sm" name="txt_num_activos" id="txt_num_activos" placeholder="1000" value="10" onblur="generar_key()">
                  </div>
                  <div class="col-sm-4">
                      <b>Clave</b>
                      <div class="input-group">
                          <input type="" class="form-control form-control-sm" name="txt_clave" id="txt_clave" readonly>
                          <span title="Generar Licencia">
                            <button class="btn btn-sm btn-primary" onclick="generar_key()"><i class="bx bx-refresh"></i></button>
                          </span>
                      </div>
                  </div>   
                  </div>
                  <div class="row">  
                  <div class="col-sm-12 text-end">
                    <br>
                    <button class="btn btn-sm btn-primary" onclick="add_licencia()">Agregar</button>
                  </div>                             
                </div>
                <hr>
                <div class="row">
                	<div class="table-responsive">
                		<table class="table table-hover" id="tbl_licencias_all">
	                		<thead>
                        <th></th>       
                        <th>Empresa</th>
                        <th>Licencia</th>
	                			<th>Modulo</th>
		                		<th>Desde</th>
                        <th>Hasta</th>
                        <th>Maquinas</th>
                        <th>Estado</th>         			
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

