<?php include ('../../cabeceras/header4.php');?>
<script type="text/javascript">
$( document ).ready(function() {
	eliminar_solicitud();
	autocompletar_custodio();
	autocompletar_bien();
})

function eliminar_solicitud()
{
	 
	 var parametros = 
  	{
  		'id':$('#txt_id').val(),
  	}
    $.ajax({
         // data:  {parametros:parametros},
         url:   '../../controlador/prestamos_bienesC.php?eliminar_solicitud=true',
         type:  'post',
         dataType: 'json',
         success:  function (response) { 
        }          
    });
}
function autocompletar_custodio(){
      $('#ddl_custodio').select2({
        placeholder: 'Seleccione una solicitante',
        width:'100%',
        ajax: {
          url: '../../controlador/custodioC.php?lista_acta=true',
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

function autocompletar_bien(){
  $('#ddl_producto').select2({
    placeholder: 'Seleccione una solicitante',
    width:'100%',
    ajax: {
      url: '../../controlador/prestamos_bienesC.php?lista_bienes=true',
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

function validar_fecha_salida()
{
	var fecha = $('#txt_fecha').val();
	var fecha_s = $('#txt_fecha_salida').val();
	contadorFinesDeSemana = 0;
	
	let fecha1 = new Date(fecha);
	let fecha2 = new Date(fecha_s);

	let diferencia = fecha2.getTime()-fecha1.getTime();
	let diasDeDiferencia = (diferencia/86400)/1000;

	while (fecha1 <= fecha2) {
	    var diaSemana = fecha1.getDay();
	    if (diaSemana === 5 || diaSemana === 6) { // 5: Domingo, 6: Sábado
	      contadorFinesDeSemana++;
	    }
	    fecha1.setDate(fecha1.getDate() + 1);
	    // console.log(diaSemana);
	}

	var Totaldias = diasDeDiferencia - contadorFinesDeSemana;   
	console.log(diasDeDiferencia);
	console.log(contadorFinesDeSemana);
	console.log(Totaldias); 
	if(Totaldias<3)
	{
		Swal.fire('Fecha invalida','Fecha de salida debe ser mayor a 3 dias laborables','info').then(function(){
			$('#txt_fecha_salida').val('');
			$('#txt_fecha_salida').select();
		});
	}
}

function solicitante()
{
	$('#ddl_custodio').prop('disabled',true);
	// url = location.href;
	// location.href = url+'?solicitud='+$('#ddl_custodio').val();
}

function agregar_solicitud()
{
	 var solicitante = $('#ddl_custodio').val();

	 var fecha = $('#txt_fecha').val();
	 var fecha2 = $('#txt_fecha_salida').val();
	 var fecha3 = $('#txt_fecha_regreso').val();

	 var articulo = $('#ddl_producto').val();
	 if(articulo=='')
	 {
	 	 Swal.fire('Seleccion un bien','','info');
	 	 return false;
	 }
	 if(solicitante=='')
	 {
	 	 Swal.fire('Seleccione un solicitante','','info');
	 	 return false;
	 }
	 if(fecha2=='' || fecha3=='')
	 {
	 	 Swal.fire('Ingrese fechas validas','','info');
	 	 return false;
	 }

	  var parametros = 
  	{
  		'id':$('#txt_id').val(),
  		'solicitante':$('#ddl_custodio').val(),
  		'bien':$('#ddl_producto').val(),
  		'fecha':$('#txt_fecha').val(),
  		'fecha2':$('#txt_fecha_salida').val() ,
  		'fecha3':$('#txt_fecha_regreso').val(),
  		'observacion':$('#txt_observacion').val(),
  	}
    $.ajax({
         data:  {parametros:parametros},
         url:   '../../controlador/prestamos_bienesC.php?add_linea=true',
         type:  'post',
         dataType: 'json',
         success:  function (response) { 
         	$('#txt_id').val(response);
         	cargar_lineas_solicitud();
        } 
          
       });

}

function cargar_lineas_solicitud()
{	
	  var parametros = 
  	{
  		'id':$('#txt_id').val(),
  	}
    $.ajax({
         data:  {parametros:parametros},
         url:   '../../controlador/prestamos_bienesC.php?cargar_lineas=true',
         type:  'post',
         dataType: 'json',
         success:  function (response) { 
         	$('#tbl_body').html(response)
        }          
    });

}

function generar_solicitud()
{
	var parametros = 
  	{
  		'id':$('#txt_id').val(),
  	}
    $.ajax({
         data:  {parametros:parametros},
         url:   '../../controlador/prestamos_bienesC.php?generar_solicitud=true',
         type:  'post',
         dataType: 'json',
         success:  function (response) { 
         	if(response==1)
         	{
         		Swal.fire("Solicitud Generado","","success").then(function(){
         			 location.href = "../../login.php";
         		});
         	}
        }          
    });
}

function elimnar_linea(id)
{
	var parametros = 
  	{
  		'id':id,
  	}
    $.ajax({
         data:  {parametros:parametros},
         url:   '../../controlador/prestamos_bienesC.php?eliminar_linea=true',
         type:  'post',
         dataType: 'json',
         success:  function (response) { 
         	cargar_lineas_solicitud();
        }          
    });
}
</script>
<div class="container">
	<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-2">
		<div class="col mx-auto">
			<div class="card mt-5">
				<div class="card-body">
					<div class="border p-4 rounded">
						<div class="text-center">
							<h3 class="">Formulario de salida de Bienes</h3>							
						</div>						
						<div class="login-separater text-center mb-4"> 	<hr/></div>
						<div class="form-body">
							<form class="row g-3">
								<div class="col-sm-12">
									<input type="hidden" name="txt_id" id="txt_id">
									<label for="inputFirstName" class="form-label">Solicitante</label>
									<select class="form-select" id="ddl_custodio"  name="ddl_custodio" onchange="solicitante()" >
										<option value="">Seleccione Solicitante</option>
									</select>
								</div>
								<div class="col-sm-4">
									<label for="inputFirstName" class="form-label">Fecha de solicitud</label>
									<input type="date" class="form-control form-control-sm" id="txt_fecha" name="txt_fecha"value="<?php echo date('Y-m-d') ?>" readonly>
								</div>
								<div class="col-sm-4">
									<label for="inputLastName" class="form-label">Fecha de Salida</label>
									<input type="date" class="form-control form-control-sm" id="txt_fecha_salida" name="txt_fecha_salida" onblur="validar_fecha_salida()">
								</div>
								<div class="col-sm-4">
									<label for="inputLastName" class="form-label">Fecha de Regreso</label>
									<input type="date" class="form-control form-control-sm" id="txt_fecha_regreso" name="txt_fecha_regreso" onblur="validar_fecha_salida()">
								</div>
								<div class="col-12">
									<textarea class="form-control form-control-sm" style="resize:none;" rows="3" placeholder="Observacion" id="txt_observacion" ></textarea>
								</div>
								<div class="col-10">
									<label for="inputEmailAddress" class="form-label">Bien</label>
									<select class="form-select form-select-sm" id="ddl_producto" name="ddl_producto">
										<option value="">Seleccione bien</option>
									</select>
								</div>								
								<div class="col-2">
									<br>
									<button type="button" class="btn btn-primary btn-sm" onclick="agregar_solicitud()">Agregar</button>
								</div>
								<div class="col-12">
									<div class="table-responsive">
										<table class="table table-hover table-sm">
											<thead>
												<th></th>												
												<th>Asset</th>
												<th>Bien</th>
												<th>Serie</th>
												<th>Modelo</th>
											</thead>
											<tbody id="tbl_body">
												<tr>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
								<!-- <div class="col-12">
									<label for="inputChoosePassword" class="form-label">Password</label>
									<div class="input-group" id="show_hide_password">
										<input type="password" class="form-control border-end-0" id="inputChoosePassword" value="12345678" placeholder="Enter Password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
									</div>
								</div>
								<div class="col-12">
									<label for="inputSelectCountry" class="form-label">Country</label>
									<select class="form-select" id="inputSelectCountry" aria-label="Default select example">
										<option selected>India</option>
										<option value="1">United Kingdom</option>
										<option value="2">America</option>
										<option value="3">Dubai</option>
									</select>
								</div>
								<div class="col-12">
									<div class="form-check form-switch">
										<input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked">
										<label class="form-check-label" for="flexSwitchCheckChecked">I read and agree to Terms & Conditions</label>
									</div>
								</div> -->
								<div class="col-12">
									<div class="d-grid">
										<button type="button" onclick="generar_solicitud()" class="btn btn-primary"><i class='bx bx-user'></i>Generar solicitud</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--end row-->
</div>
<?php include ('../../cabeceras/footer4.php');?>