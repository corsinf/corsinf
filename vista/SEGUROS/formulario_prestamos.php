<?php /*include ('../cabeceras/header4.php');  print_r($_SESSION['INICIO']);die(); */?>
<script type="text/javascript">
	noconcurente = '<?php echo $_SESSION['INICIO']['NO_CONCURENTE']; ?>';
	noconcurente_nom = '<?php echo $_SESSION['INICIO']['NO_CONCURENTE_NOM']; ?>';
$( document ).ready(function() {
	eliminar_solicitud();
	autocompletar_custodio();
	autocompletar_bien();
	if(noconcurente!='')
	{
		$('#ddl_custodio').prop('disabled',true);
		$('#ddl_custodio').append($('<option>',{value: noconcurente, text:noconcurente_nom,selected: true }));
	}
})

function eliminar_solicitud()
{
	 
	 var parametros = 
  	{
  		'id':$('#txt_id').val(),
  	}
    $.ajax({
         // data:  {parametros:parametros},
         url:   '../controlador/prestamos_bienesC.php?eliminar_solicitud=true',
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
          url: '../controlador/ACTIVOS_FIJOS/custodioC.php?lista_acta=true',
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
      url: '../controlador/prestamos_bienesC.php?lista_bienes=true',
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
	var fecha_r = $('#txt_fecha_regreso').val();
	contadorFinesDeSemana = 0;
	
	let fecha1 = new Date(fecha);
	let fecha2 = new Date(fecha_s);
	let fecha3 = new Date(fecha_r);

	let diferencia = fecha2.getTime()-fecha1.getTime();
	let diasDeDiferencia = (diferencia/86400)/1000;

	//dias de diferencia entre regreso y salida
	if(fecha_r!='' && fecha_s!='')
	{
		let diferencia2 = fecha3.getTime()-fecha2.getTime();
		let diasDeDiferencia2 = (diferencia2/86400)/1000;	
		$('#txt_duracion').val(diasDeDiferencia2);
	}

	while (fecha1 <= fecha2) {
	    var diaSemana = fecha1.getDay();
	    if (diaSemana === 5 || diaSemana === 6) { // 5: Domingo, 6: Sábado
	      contadorFinesDeSemana++;
	    }
	    fecha1.setDate(fecha1.getDate() + 1);
	    // console.log(diaSemana);
	}

	var Totaldias = diasDeDiferencia - contadorFinesDeSemana;   
	// console.log(diasDeDiferencia);
	// console.log(contadorFinesDeSemana);
	// console.log(Totaldias); 
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
  		'duracion':$('#txt_duracion').val(),
  		'destino':$('#txt_destino').val(),
  	}
    $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/prestamos_bienesC.php?add_linea=true',
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
         url:   '../controlador/prestamos_bienesC.php?cargar_lineas=true',
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
         url:   '../controlador/prestamos_bienesC.php?generar_solicitud=true',
         type:  'post',
         dataType: 'json',
         success:  function (response) { 
         	if(response==1)
         	{
         		Swal.fire("Solicitud Generado","","success").then(function(){
         			 location.reload();
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
         url:   '../controlador/prestamos_bienesC.php?eliminar_linea=true',
         type:  'post',
         dataType: 'json',
         success:  function (response) { 
         	cargar_lineas_solicitud();
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
	    <div class="card">
	    	<div class="card-body">
	    		
							<form class="row g-3">
								<div class="col-sm-6">
									<input type="hidden" name="txt_id" id="txt_id">
									<b>Solicitante</b>
									<select class="form-select" id="ddl_custodio"  name="ddl_custodio" onchange="solicitante()" >
										<option value="">Seleccione Solicitante</option>
									</select>
								</div>
								<div class="col-sm-2">
									<b>Fecha de solicitud</b>
									<input type="date" class="form-control form-control-sm" id="txt_fecha" name="txt_fecha"value="<?php echo date('Y-m-d') ?>" readonly>
								</div>
								<div class="col-sm-2">
									<b>Fecha de Salida</b>
									<input type="date" class="form-control form-control-sm" id="txt_fecha_salida" name="txt_fecha_salida" onblur="validar_fecha_salida()">
								</div>
								<div class="col-sm-2">
									<b>Fecha de Regreso</b>
									<input type="date" class="form-control form-control-sm" id="txt_fecha_regreso" name="txt_fecha_regreso" onblur="validar_fecha_salida()">
								</div>
								<div class="col-sm-2">
									<b>Duracion (Dias)</b>
									<input type="input" class="form-control form-control-sm" id="txt_duracion" name="txt_duracion" readonly >
								</div>
								<div class="col-sm-10">
									<b>Destino</b>
									<input type="input" class="form-control form-control-sm" id="txt_destino" name="txt_destino">
								</div>
								<div class="col-12">
									<b>Motivo de la movilizacion</b>
									<textarea class="form-control form-control-sm" style="resize:none;" rows="3" placeholder="Observacion" id="txt_observacion" ></textarea>
								</div>
								<div class="col-sm-11">
									<b>Bien</b>
									<select class="form-select form-select-sm" id="ddl_producto" name="ddl_producto">
										<option value="">Seleccione bien</option>
									</select>
								</div>								
								<div class="col-sm-1 text-end">
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
							
								<div class="col-sm-12">
									<div class="row">
										<div class="col-sm-12 text-end">
													<button type="button" onclick="generar_solicitud()" class="btn btn-primary"><i class='bx bx-user'></i>Generar solicitud</button>
								
										</div>
									</div>
								</div>
							</form>


	    	</div>
	    </div>

	   
	</div>
</div>


<!-- <div class="container">
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
</div> -->
<?php //include ('../cabeceras/footer4.php');?>