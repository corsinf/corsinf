<?php //include('../../cabeceras/header.php'); print_r($_SESSION['INICIO']);die(); ?> 
 <script src="../js/empresa.js"></script> 
 <script type="text/javascript">
  $( document ).ready(function() {
  	
  })


</script>
<div class="page-wrapper">
	<div class="page-content">
	    <!--breadcrumb-->
	    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	      <div class="breadcrumb-title pe-3">Empresa</div>
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
		<div class="container">
			<div class="main-body">
				<div class="card">
					<div class="card-body">

						<div class="row">
				            <div class="col-lg-12">
				            	<ul class="nav nav-pills">
								  <li class="nav-item">
								    <a class="nav-link active" data-bs-toggle="pill" href="#home">Datos de empresa</a>
								  </li>
								  <li class="nav-item" style="display: none;">
								    <a class="nav-link" data-bs-toggle="pill" href="#menu1">Certificados</a>
								  </li>
								  <li class="nav-item"  style="display: none;">
								    <a class="nav-link" data-bs-toggle="pill" href="#menu2">Facturacion</a>
								  </li>
								  <li class="nav-item"  style="display: none;">
								    <a class="nav-link" data-bs-toggle="pill" href="#menu4">Restaurante</a>
								  </li>
								   <li class="nav-item" style="display: none;">
								    <a class="nav-link" data-bs-toggle="pill" href="#menu3">SMTP de correo</a>
								  </li>
								</ul>

								<!-- Tab panes -->
								<div class="tab-content">
								  <div class="tab-pane container active" id="home">	
								  <br>								  	
									  	<div class="row">
								       	   	<div class="col-sm-8">
								       	   		<div class="row">
								       	   			<div class="col-sm-12">
								       	   				<b>Nombre comecial</b>
								       	   				<input type="text" name="txt_nom_comercial" id="txt_nom_comercial" class="form-control form-control-sm">
								       	   		
								       	   			</div>
								       	   			<div class="col-sm-12">
								       	   				<b>Razon social</b>       	   		
								       	   				<input type="text" name="txt_razon" id="txt_razon" class="form-control form-control-sm">   				
								       	   			</div>
								       	   			<div class="col-sm-6">
								       	   				<b>CI / RUC </b>
								       	   				<input type="text" name="txt_ci_ruc" id="txt_ci_ruc" class="form-control form-control-sm">
								       	   		       	   				
								       	   			</div>
								       	   			<div class="col-sm-6">
								       	   				<b>Telefono</b>
								       	   				<input type="text" name="txt_telefono" id="txt_telefono" class="form-control form-control-sm">
								       	   		
								       	   			</div>
								       	   			<div class="col-sm-6">
								       	   				<b>Email</b>
								       	   				<input type="text" name="txt_Email" id="txt_Email" class="form-control form-control-sm">
								       	   		       	   				
								       	   			</div>
								       	   			
								       	   			<div class="col-sm-12">
								       	   				<b>Direccion</b>
								       	   				<textarea class="form-control-sm form-control" style="resize:none" cols="3" name="txt_direccion" id="txt_direccion"></textarea>
								       	   			</div>								       	   			
								       	   		</div>
								       	   		
								       	   		
								       	   	</div>
								       	   	<div class="col-sm-4">        	   		
								       	   		<form enctype="multipart/form-data" id="form_img" method="post" class="col-sm-12">
								                 <div class="">
										                <img src="../img/de_sistema/sin-logo.jpg" alt="User Avatar" id="img_foto" name="img_foto" style="width: 300px;height: 250px;border: 1px solid;">
										             </div><br>
								               		<input type="file" name="file_img" id="file_img" class="form-control form-control-sm">
								               		<input type="hidden" name="txt_nom_img" id="txt_nom_img">
								               		<!-- <button class="btn btn-primary btn-block" id="subir_imagen" type="button">Cargar imagen</button> -->
								           		</form>   
								       	   	</div>
								       </div>
								       <div class="row">
									       	<div class="col-sm-6">
						       	   				<h6 class="mb-0 text-uppercase">Base de datos</h6>
						       	   				<hr>
						       	   				<div class="row">
										       	   	<div class="col-sm-12">
										       	   		<b>Host</b>
										       	   		<input type="text" name="txt_db_host" id="txt_db_host" class="form-control form-control-sm">     	   
										       	   		<b>Base de datos</b>
										       	   		<input type="text" name="txt_db" id="txt_db" class="form-control form-control-sm">
										       	   		<b>Usuario</b>
										       	   		<input type="text" name="txt_db_usuario" id="txt_db_usuario" class="form-control form-control-sm">
										       	   		<b>Password</b>
										       	   		<input type="text" name="txt_db_pass" id="txt_db_pass" class="form-control form-control-sm">
										       	   		<b>Puerto</b>
										       	   		<input type="text" name="txt_db_puerto" id="txt_db_puerto" class="form-control form-control-sm">
										       	   	</div>       	   
										       </div>	  	
						       	   			</div>
						       	   			<div class="col-sm-6">
						       	   				<h6 class="mb-0 text-uppercase">Configuracion SMTP </h6>
						       	   				<hr>
						       	   				<div class="row">
										       	   	<div class="col-sm-12">
										       	   		<b>SMTP Host</b>
										       	   		<input type="text" name="txt_host" id="txt_host" class="form-control form-control-sm">
										       	   		<b>SMTP Usuario</b>
										       	   		<input type="text" name="txt_usuario" id="txt_usuario" class="form-control form-control-sm">
										       	   		<b>SMTP Pass</b>
										       	   		<input type="text" name="txt_pass" id="txt_pass" class="form-control form-control-sm">
										       	   		<b>SMTP Puerto</b>
										       	   		<input type="text" name="txt_puerto" id="txt_puerto" class="form-control form-control-sm">
										       	   		<b>SMTP Secure</b>
										       	   		<input type="text" name="txt_secure" id="txt_secure" class="form-control form-control-sm">     	   		
										       	   	</div>       	   
										       </div>								       	   				
						       	   			</div>
								       </div>
								 	 </div> 
								   
								  <div class="tab-pane container fade" id="menu1">

								  	ddddd
								  </div>
								  <div class="tab-pane container fade" id="menu2">
								  	<div class="row">
								  		<div class="col-sm-6">
								       	   				<b>Facturacion Electronica</b>       	   		
												  		<div class="custom-control custom-checkbox small">
											                <input type="radio" class=""  name="rbl_fac" id="rbl_fac_no" value="0" checked>
											                <label class="" for="rbl_fac_no">No</label>

											                <input type="radio" class="" name="rbl_fac"  id="rbl_fac_si" value="1">
											                <label class="" for="rbl_fac_si">Si</label>
											            </div>              	   				
								       	   			</div>
									  		<div class="col-sm-3">
									  			<b>Ambiente</b>       	   		
										  		<div class="custom-control custom-checkbox small">
									                <input type="radio" class=""  name="rbl_ambi" id="rbl_ambiente_1" value="1" checked>
									                <label class="" for="rbl_ambiente_1">Pruebas</label>

									                <input type="radio" class="" name="rbl_ambi"  id="rbl_ambiente_2" value="2">
									                <label class="" for="rbl_ambiente_2">Produccion</label>
									            </div>           
									  		</div>
									  		<div class="col-sm-3">
									  			<b>Lleva contabilidad</b>       	   		
										  		<div class="custom-control custom-checkbox small">
									                <input type="radio" class=""  name="rbl_conta" id="rbl_conta_no" value="0" checked>
									                <label class="" for="rbl_conta_no">No</label>

									                <input type="radio" class="" name="rbl_conta"  id="rbl_conta_si" value="1">
									                <label class="" for="rbl_conta_si">Si</label>
									            </div>           
									  		</div>
									  		<div class="col-sm-2">
									  			<b>Valor de iva</b>      
									  			<input type="text" name="txt_iva" id="txt_iva" class="form-control form-control-sm"> 
									  		</div>
									  	</div>

								  	<br>
								  	<hr>
								  	<div class="row">
								  	<div class=" card card-body">
								  		<form enctype="multipart/form-data" id="form_certi" method="post" class="col-sm-12">
								  		<div class="row">
								  			<div class="col-sm-6">
									  			<input type="file" name="file_certificado" id="file_certificado">
									  		</div>
									  		<div class="col-sm-4">
									  			<input type="" name="txt_clave_cer" id="txt_clave_cer" class="form-control form-control-sm" placeholder="Clave de certificado">
									  		</div>
									  		<div class="col-sm-2">
									  			<button class="btn btn-primary" type="button" id="btn_certificados">Subir archivo</button>
									  		</div>
								  		</div>
									  	  </form>
								  	</div>	
								  	</div>
								  	<div class="row">
								  		<table class="table table-sm">
								  			<thead>
								  				<th>Nombre de Certificado</th>
								  				<th>Clave de certificado</th>
								  				<th></th>
								  			</thead>
								  			<tbody id="tbl_certificados">
								  				<tr>
								  					<td colspan="3">No se a encontrado Certifiacdos</td>
								  				</tr>
								  			</tbody>
								  		</table>
								  	</div>

								  	
								  </div>
								  <div class="tab-pane container fade" id="menu3">
								  	
								  </div>	  
								  <div class="tab-pane container fade" id="menu4">
								  	<div class="row">
							       	   	<div class="col-sm-6">
							       	   		<b>Numero de mesas</b>
							       	   		<input type="text" name="txt_mesas" id="txt_mesas" class="form-control form-control-sm" value="30">
							       	   		<!-- <b>SMTP Usuario</b>
							       	   		<input type="text" name="txt_usuario" id="txt_usuario" class="form-control form-control-sm">
							       	   		<b>SMTP Pass</b>
							       	   		<input type="text" name="txt_pass" id="txt_pass" class="form-control form-control-sm">
							       	   		<b>SMTP Puerto</b>
							       	   		<input type="text" name="txt_puerto" id="txt_puerto" class="form-control form-control-sm">
							       	   		<b>SMTP Secure</b>
							       	   		<input type="text" name="txt_secure" id="txt_secure" class="form-control form-control-sm">     	   	 -->	
							       	   	</div> 
							       	   	<div class="col-sm-3">
								  			<b>Procesar automatico</b>       	   		
									  		<div class="custom-control custom-checkbox small">
								                <input type="radio" class=""  name="rbl_proce" id="rbl_proce_no" value="0" checked>
								                <label class="" for="rbl_proce_no">No</label>

								                <input type="radio" class="" name="rbl_proce"  id="rbl_proce_si" value="1">
								                <label class="" for="rbl_proce_si">Si</label>
								            </div>           
								  		</div>      	   
							       </div>
							       <div class="row">
							       	<div class="col-sm-3">
							       		Encargado de envios
							       	</div>
							       	<div class="col-sm-3">
							       		<select class="form-select" id="ddl_tipo_usuario" name="ddl_tipo_usuario">
							       			<option>Seleccione tipo</option>
							       		</select>
							       	</div>
							       </div>
								  </div>
								</div>           

				        	</div>          
				        </div>
				        <div class="row">
							<div class="col-sm-9">
								<!-- <h1 class="h3 mb-4 text-gray-800">Empresa</h1> -->
							</div>
							<div class="col-sm-3">
								<button type="button" class="btn btn-primary" onclick="guardar_datos()">Guardar datos de empresa</button>
							</div>
						</div>
                  	</div>
              	</div>
          	</div>
        </div>
	</div>
</div>



