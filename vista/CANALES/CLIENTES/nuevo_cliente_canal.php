   <link rel="stylesheet" href="../css/multi_step.css">
  
	<script type="text/javascript">	
		var licencias = [];

	 $( document ).ready(function() {
	 	modulos_sistema();	  
	 })

	  function enter_press(event) {

	    var codigo = event.which || event.keyCode;
	    if(codigo === 13){
	      consultar_datos()
	      // console.log("Tecla ENTER");
	    }     
	}
 
	 function datos_empresa()
	 {
	 	var emp = $('#txt_empresa').val();
		var ci = $('#txt_ci').val();
		var ema = $('#txt_email').val();
		var tel = $('#txt_telefono').val();
		if(emp=='' || ci=='' || ema=='' || tel=='')
		{
			Swal.fire('Llene todo los campos','','info')
			return false;
		}
	 	$(".next").click();
	 }


	 function Guardar_empresa() {

	 	if($('input[name="rbl_smtp_default"]:checked').val()=="no"){
		 	var host = $('#txt_host').val();
			var smtp_puerto = $('#txt_puerto_smtp').val();
			var smtp_secure = $('#txt_secure').val();
			var smtp_usu = $('#txt_usuario_smtp').val();
			var smtp_pass = $('#txt_pass_smtp').val();
			if(host =='' || smtp_puerto =='' || smtp_secure  =='' || smtp_usu =='' || smtp_pass =='')
			{
				Swal.fire('Llene todo los datos','','info')
				return false;
			}
		}

	    var formData = new FormData($('#msform')[0]);
	    formData.append("licencias",JSON.stringify(licencias));
	    // console.log(licencias);
	    // console.log(formData);
	    $.ajax({
	        data:  formData,
	        url:   '../controlador/nueva_empresaC.php?Guardar_empresa=true',
	        type: 'POST',
	        contentType: false,
	        processData: false,
	        success: function(response) {
	           if(response==-2)
	           {
	           	 Swal.fire("La empresa ya esta registrada","","error");
	           }else if(response==1)
	           {
	           	 Swal.fire("Empresa guardada Revise correo para ingresar","","info").then(function(){
	           	 	location.reload();
	           	 });           	
	           }
	        },
	        error: function(error) {
	            console.error('Error al enviar datos:', error);
	            // Puedes manejar los errores aquí
	        }
	    });
	}

	function modulos_sistema() 
	{

	    $.ajax({
	        // data:  {parametros,parametros},
	        url:   '../controlador/nueva_empresaC.php?modulos_sistema=true',
	        type: 'POST',	        
            dataType:'json',
	        success: function(response) {
	        	modulos = '';
	        	tbl = '';
	        	response.forEach(function(item,i){
	        		tbl+= `<tr>
	        							<td>
	        								<br>
	        									<input type="checkbox" name="cbx_modulo_select" id="cbx_modulo_`+item.id_modulos+`" class="cbx_modulo" value="`+item.id_modulos+`"> `+item.nombre_modulo+`
	        							</td>
	        							<td>
	        								<b>N° usuarios</b>
	        								<input type="number" name="txt_maquinas" id="txt_maquinas_`+item.id_modulos+`" class="form-control form-control-sm" value="1">
	        							</td>
	        							<td>
	        								<b>N° Activos</b>
	        								<input type="number" name="txt_num_activos" id="txt_num_activos_`+item.id_modulos+`" class="form-control form-control-sm" value="1">
	        							</td>
	        							<td>
	        								<b>N° de PDA</b>
	        								<input type="number" name="txt_pda" id="txt_pda_`+item.id_modulos+`" class="form-control form-control-sm" value="0">
												</td>
	        							<td>
	        								<label><input type="radio" name="rbl_periodo_`+item.id_modulos+`" value="12" checked>Anual</label>
													<br>
													<label><input type="radio" name="rbl_periodo_`+item.id_modulos+`" value="6">Semestral</label>
													<br>
													<label><input type="radio" name="rbl_periodo_`+item.id_modulos+`" value="3">trimestral</label>
													<br>
													<label><input type="radio" name="rbl_periodo_`+item.id_modulos+`" value="1">Mesual</label>
												</td>
	        						</tr>`;
	        	})
	           console.log(response)
	           $('#tbl_modulos').html(tbl);
	        },
	        error: function(error) {
	            console.error('Error al enviar datos:', error);
	            // Puedes manejar los errores aquí
	        }
	    });
	}

	function configuara_base()
	{
		if($('input[name="rbx_base"]:checked').val()=="1")
		{
			$('#pnl_cofig_base').removeClass('d-none');
		}else
		{			
			$('#pnl_cofig_base').addClass('d-none');
			$('#txt_tipo_base').val('')
			$('#txt_ip').val('')
			$('#txt_puerto').val('')
			$('#txt_usuario_db').val('')
			$('#txt_pass_db').val('')
			$('#txt_base').val('')
		}
	}

	function pass(input)
   	{
	   	 var pa =document.getElementById(input);
	   	 if(pa.type == 'password')
	   	 {
	   	 	pa.type = 'text';
	   	 }else
	   	 {
	   	 	pa.type = 'password';
	   	 }
   	}

</script>

  
<div class="page-wrapper">
      <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-0">
          <div class="breadcrumb-title pe-3">Forms</div>
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
            <hr>
            <div class="card">
              <div class="card-body">	          		
	          		<div class="row">
	          			<div class="col-sm-12">
			                <form id="msform" class="mt-0">
			                    <!-- progressbar -->
			                    <ul id="progressbar" style="margin-bottom: 0px" class="text-center">
			                        <li class="active"  id="account"><strong>Datos Empresa</strong></li>
			                        <li id="licencias"><strong>Licencias</strong></li>
			                        <li id="personal"><strong>Base de datos</strong></li>
			                        <li id="payment"><strong>Envios Email (SMTP)</strong></li>
			                    </ul>
			                    <!-- fieldsets -->
			                    <hr>
			                    <fieldset class="text-end">
			                        <div class="form-card"> 
			                        	<div class="row">
			                        		<div class="col-sm-4">
			                        			<img class="img-profile rounded-circle" src="../img/de_sistema/sin-logo.jpg" alt="User Avatar" id="img_foto" name="img_foto" style="width: 96%;">
												<input type="file"  name="txt_logo" id="txt_logo" placeholder="Jhon">
											</div> 
											<div class="col-sm-8">
												<div class="row">
													<div class="col-sm-6">
														<label for="inputFirstName" class="form-label">Razon Social</label>
														<input type="text" class="form-control" name="txt_razon" id="txt_razon" placeholder="Jhon">
													</div>
													<div class="col-sm-6">
														<label for="inputFirstName" class="form-label">Nombre Comercial</label>
														<input type="text" class="form-control" name="txt_empresa_nom" id="txt_empresa_nom" placeholder="Jhon">
													</div>
													<div class="col-sm-12">
														<label for="inputLastName" class="form-label">Ruc / CI </label>
														<input type="text" class="form-control"  name="txt_ci" id="txt_ci" placeholder="9999999999999" onkeyup="num_caracteres('txt_ci', 13)">											
													</div>				
													<div class="col-6">
														<label for="inputEmailAddress" class="form-label">Email</label>
														<input type="text" class="form-control" name="txt_email" id="txt_email" placeholder="example@user.com">
													</div>
													<div class="col-6">
														<label for="inputEmailAddress" class="form-label">Telefono</label>
														<input type="text" class="form-control" name="txt_telefono" id="txt_telefono" placeholder="0999999999" onkeyup="num_caracteres('txt_telefono', 10)">
													</div>
													<div class="col-12">
														<label for="inputEmailAddress" class="form-label">Direccion</label>
														<textarea  name="txt_direccion" id="txt_direccion" class="form-control-sm form-control"></textarea>
													</div>								
												</div>
											</div>
			                        	</div>
			                        </div>
			                        <br>
			                        <button  type="button" id="btn_siguiente" class="btn btn-primary next">Siguiente</button>
			                    </fieldset>
			                     <fieldset class="text-end">
			                        <div class="form-card"> 
			                        			<!-- <b>Modulos</b> -->
		                        			<div class="row">
		                        				<div class="col-sm-12" style="overflow-y:scroll;height: 250px;">
		                        						<table class="table mb-0" >
		                        							<thead>
		                        								<th style="width:20%"></th>
		                        								<th style="width:20%"></th>
		                        								<th style="width:20%"></th>
		                        								<th style="width:20%"></th>
		                        								<th style="width:20%"></th>
		                        							</thead>
		                        							<tbody id="tbl_modulos">
		                        								
		                        							</tbody>
		                        							
		                        						</table>			                        					
		                        				</div>			                        				
		                        			</div>
			                        </div>
			                        <br>
					            				<button  type="button" id="btn_atras" class="btn btn-secondary previous">Atras</button>
			                        <button  type="button" id="btn_siguiente" class="btn btn-primary next2">Siguiente</button>
			                    </fieldset>
			                    <fieldset class="text-end">
			                        <div class="form-card">
			                        	<div class="row">
			                        		<div class="col-sm-6"><label class="fieldlabels">Base de datos: *</label> 
				                            	<input type="text" class="form-control" id="txt_base" name="txt_base"  placeholder="DB_PRUEBAS" />
				                          	</div>	
				                          	<div class="col-sm-6">
			                        			<label><input type="radio" name="rbx_base" checked value="0" onclick="configuara_base()"> Por default</label>
			                        			<label><input type="radio" name="rbx_base" value="1" onclick="configuara_base()"> configurar base</label>
			                        		</div>		                        		
			                        	</div>
			                        	<div class="row d-none" id="pnl_cofig_base">
				                            <div class="col-sm-6"><label class="fieldlabels">Tipo de base: *</label> 
				                            	<input type="text" class="form-control" id="txt_tipo_base" name="txt_tipo_base" placeholder="MYSQL / SQLSERVER" /> 
				                          	</div>
				                            <div class="col-sm-6"><label class="fieldlabels">IP / Host: *</label> 
				                            	<input type="text" class="form-control" id="txt_ip" name="txt_ip" placeholder="0.0.0.0" />
				                          	</div>
				                            <div class="col-sm-6"><label class="fieldlabels">Puerto: *</label> 
				                            	<input type="text" class="form-control" id="txt_puerto" name="txt_puerto" placeholder="8080" /> 
				                          	</div>
				                            <div class="col-sm-6"><label class="fieldlabels">Usuario: *</label> 
				                            	<input type="text" class="form-control" id="txt_usuario_db" name="txt_usuario_db" placeholder="user" /> 
				                          	</div>
				                            <div class="col-sm-6"><label class="fieldlabels">Password: *</label> 
				                            	<div class="input-group">
					                            	<input type="Password" class="form-control" id="txt_pass_db" name="txt_pass_db"  placeholder="******" />
					                            	 <button type="button" class="btn btn-info btn-flat btn-sm" onclick="pass('txt_pass_db')"><i class="lni lni-eye" id="eye"></i></button>      
					                            </div>               
				                          	</div>
				                          </div>
			                        </div>
			                        <br>
					            	<button  type="button" id="btn_atras" class="btn btn-secondary previous">Atras</button>
					            	<button  type="button" id="btn_siguiente" class="btn btn-primary next3">Siguiente</button>
			                    </fieldset>
			                    <fieldset class="text-end">
			                        <div class="form-card">
			                        	<div class="row">
			                        		<div class="col-sm-12">
			                        			<label class="fieldlabels">SMTP por Default:</label> 
			                        			<br>
					                           	<label><input type="radio" id="rbl_smtp_si" name="rbl_smtp_default" value="si" onclick="smtp_config()"  checked /> SI</label>
					                           	<label><input type="radio" id="rbl_smtp_no" name="rbl_smtp_default" value="no" onclick="smtp_config()" /> NO</label>
					                        </div>                        		
			                        	</div>
			                             <div class="row d-none" id="pnl_smtp">
					                            <div class="col-sm-4"><label class="fieldlabels">SMTP Host:*</label> 
					                            	<input type="text" class="form-control" id="txt_host" name="txt_host" placeholder="0.0.0.0" /> 
					                          	</div>
					                            <div class="col-sm-4"><label class="fieldlabels">SMTP Puerto: *</label> 
					                            	<input type="text" class="form-control" id="txt_puerto_smtp" name="txt_puerto_smtp" placeholder="587 / 465" />
					                          	</div>
					                            <div class="col-sm-4"><label class="fieldlabels">SMTP Secure: *</label> 
					                            	<br>
					                            	<label> <input type="radio" id="txt_ssl" name="rbl_secure" value="ssl" checked  />ssl</label> 
					                            	<label> <input type="radio" id="txt_tls" name="rbl_secure" value="tls"  />tls</label> 
					                          	</div>
					                            <div class="col-sm-4"><label class="fieldlabels">SMTP Usuario: *</label> 
					                            	<input type="text" class="form-control" id="txt_usuario_smtp" name="txt_usuario_smtp" placeholder="usuario." /> 
					                          	</div>
					                            <div class="col-sm-4"><label class="fieldlabels">SMTP Password: *</label> 
					                            	<div class="input-group">					                            		
					                            		<input type="Password" class="form-control" id="txt_pass_smtp" name="txt_pass_smtp"  placeholder="*****" />
					                            		 <button type="button" class="btn btn-info btn-flat btn-sm" onclick="pass('txt_pass_smtp')"><i class="lni lni-eye" id="eye"></i></button>    

					                            	</div>
					                          	</div>
					                          </div>
			                        </div> <br>                      

					            	<button  type="button" id="btn_atras" class="btn btn-secondary previous">Atras</button>
			                        <button  type="button" class="btn btn-primary" onclick="Guardar_empresa()">Finalizar</button>
			                    </fieldset>
			                   
			                </form>
			            </div>
			             			
	          		</div>               
              </div>
            </div>
          </div>
        </div>
        <!--end row-->
      </div>
    </div>




	
	<!--end wrapper-->
	<!-- Bootstrap JS -->
	<!-- <script src="assets/js/bootstrap.bundle.min.js"></script>

	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<script src="js/codigos_globales.js"></script>

	<script>
		$(document).ready(function () {
			$("#show_hide_password a").on('click', function (event) {
				event.preventDefault();
				if ($('#show_hide_password input').attr("type") == "text") {
					$('#show_hide_password input').attr('type', 'password');
					$('#show_hide_password i').addClass("bx-hide");
					$('#show_hide_password i').removeClass("bx-show");
				} else if ($('#show_hide_password input').attr("type") == "password") {
					$('#show_hide_password input').attr('type', 'text');
					$('#show_hide_password i').removeClass("bx-hide");
					$('#show_hide_password i').addClass("bx-show");
				}
			});
		});

		$(document).keydown(function (tecla) {
			// console.log(tecla.keyCode);
		    if (tecla.keyCode == 13) {
		    	$('#btn_inicio').click();
		    }
			});
	</script>
	
	<script src="assets/js/app.js"></script> -->
	<script  src="../js/multi_step.js"></script>
<!-- </body>

</html> -->