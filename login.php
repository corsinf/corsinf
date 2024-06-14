<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="assets/images/favicon-32x32.png" type="image/png" />
	<!--plugins-->
	<link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
	<!-- loader-->
	<link href="assets/css/pace.min.css" rel="stylesheet" />
	<script src="assets/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/bootstrap-extended.css" rel="stylesheet">
	<link href="assets/css/app.css" rel="stylesheet">
	<link href="assets/css/icons.css" rel="stylesheet">
	<title>Activos Fijos| PUCE</title>
	<script src="js/sweetalert2.all.min.js"></script>
  
	<script type="text/javascript">	
	function consultar_datos()
  {
    var email=$('#email').val();
    var pass =$('#pass').val();
    if(email != '' && pass !='')
    {
       var parametros = 
       {
         'email':email,
         'pass':pass,
       } 
       $.ajax({
         data:  {parametros:parametros},
         url:   'controlador/loginC.php?iniciar=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) {  
           	console.log(response);

           	if(response.lista!='')
           	{
           		$('#lista_empresas').html(response.lista);
           		$('#txt_no_concurente').val(response.no_concurente);
  						$('#myModal_empresas').modal('show');
  					}else
  					{
  						Swal.fire( '','Usuario No registrado.','error');
  					}           
         }
       });

    }else
    {
       Swal.fire( '','LLene todo los campos.','error');
    }
  }

  function iniciar_sesion(id)
  {
  	$('#myModal_espera').modal('show');
  	 var email=$('#email').val();
    var pass =$('#pass').val();
    var no_concurente = $('#txt_no_concurente').val();
       var parametros = 
       {
         	'id':id,
					'email':email,
         	'pass':pass,
         	'no_concurente':no_concurente,
       } 
       $.ajax({
         data:  {parametros:parametros},
         url:   'controlador/loginC.php?iniciar_empresa=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  

  					$('#myModal_espera').modal('hide');

           console.log(response);  
           if (response==-2) 
           {
              Swal.fire( '','Email no registrado.','error');

           }else if(response == -1)
           {
              Swal.fire( '','Empresa Inexistente','info');

           }else if(response == -3)
           {
              Swal.fire( '','Usuario sin acceso','error');

           }else if(response == -4)
           {
              Swal.fire( '','Usuario de active directory no autentificado','error');
           }
           else if(response == 1)
           {
             window.location.href = "vista/modulos_sistema.php";
           }        
         }
       });

    
  }



  function empresa_selecconada(empresa)
  {
  		var parametros = 
       {
         'empresa':empresa,
       } 
        titulos_cargados();
       $.ajax({
         data:  {parametros:parametros},
         url:   'controlador/loginC.php?empresa_seleccionada=true',
         type:  'post',
         dataType: 'json',        
           success:  function (response) {  
       			// $('#myModal_espera').modal('hide');
           	if(response.respuesta==2)
           	{
           		$('#lista_modulos_empresas').html(response.modulos);
           		$('#txt_id').val(empresa);
           		$('#myModal_modulos').modal('show');
       			// $('#myModal_espera').modal('hide');
           	}else if(response.respuesta==1)
           	{

      				$('#myModal_espera').modal('show');
           		iniciar_sesion(empresa);
           	}      
         },
         error:function(xhr, status, error)
         {
         	 $('#myModal_espera').modal('hide');
         }
       });

  }

  function registrar_licencia(empresa,modulo)
  {

  	licencia = $('#licencia_'+modulo).val();
  		var parametros = 
       {
         'empresa':empresa,
         'modulo':modulo,
         'licencia':licencia,
       } 
       $('#myModal_espera').modal('show');
       $.ajax({
         data:  {parametros:parametros},
         url:   'controlador/loginC.php?registrar_licencia=true',
         type:  'post',
         dataType: 'json',        
           success:  function (response) {  
           	// $('#myModal_espera').modal('show');
           	if(response==-1)
           	{
           		Swal.fire('La licencian es erronea','','error');
           	}else if(response==1)
           	{
           		Swal.fire('La licencian registrada','','success');
           	}
         },
         error:function(xhr, status, error)
         {
         	 $('#myModal_espera').modal('hide');
         }
       });

  }
  function enter_press(event) {

    var codigo = event.which || event.keyCode;
    if(codigo === 13){qw
    	
      consultar_datos()
      // console.log("Tecla ENTER");
   	 }     
	}

	function primer_inicio()
	{
  		var parametros = 
       {
         'empresa':$('#txt_id').val(),
       } 
       $.ajax({
         data:  {parametros:parametros},
         url:   'controlador/loginC.php?primer_inicio=true',
         type:  'post',
         dataType: 'json',        
           success:  function (response) {  
           	if(response==-1)
           	{
           		Swal.fire('La licencian es erronea','','error');
           	}else if(response==1)
           	{
           		Swal.fire('Se a enviado a su correo registrado las credenciales de ingreso','','success').then(function(){
           			$('#myModal_empresas').modal('hide');
           			$('#myModal_modulos').modal('hide');
           		});
           	}
         }
       });
	}

	function validar_directory()
	{
		 user = $('#email').val();
		 if(user=='' || user==null)
		 {
		 		return false;
		 }
		 console.log(user);
		 var parametros = 
       {
         'user':user,
       } 
       $.ajax({
         data:  {parametros:parametros},
         url:   'controlador/loginC.php?validar_directory=true',
         type:  'post',
         dataType: 'json',        
           success:  function (response) { 
           	if(response.length==1)
           	{
		           if(response[0].ActiveDirectory=='1' && response[0].PrimerIngresoActiveDir=='1')
		           {
		           		$('#myModal_Active').modal('show');
		           		$('#txt_EmpresaIdActive').val(response[0]['id']);
		           		$('#txt_tablaActive').val(response[0]['tabla']);
		           	 //alert('ingres por primera vez debe validar en active directory y copiar la contraseña, muetra por primera vez una caja de tecto que va al ActiveDirectory');
		           }
		           //else
		           // {
		           // 	 alert('valida en active y actualiza pas en caso de que sea otra credencial, proceso de actualizacion interno');
		           // } 
		        }else if(response.length>1)
		        {
		        	 alert('debe salir la lista de empresas');
		        }
         }
       });

	}

	function primerInicioActive()
	{
		 var parametros = 
       {
				'user' :$('#email').val(),
				'pass' : $('#txt_passActive').val(),
				'empresa' :  $('#txt_EmpresaIdActive').val(),
				'tabla':$('#txt_tablaActive').val(),
       } 
       $.ajax({
         data:  {parametros:parametros},
         url:   'controlador/loginC.php?primerInicioActive=true',
         type:  'post',
         dataType: 'json',        
           success:  function (response) { 
           	console.log(response);
           	if(response.resp==1)
           	{
           		 Swal.fire('ActiveDirectory',response.msj,'success').then(function(){
           		 	 $('#pass').val($('#txt_passActive').val());
		           	 $('#btn_inicio').click();
           		 		$('#myModal_Active').modal('hide');
           		 })
		          
		        }
         }
       });

	}

	</script>
</head>

<body class="bg-login">
	<!--wrapper-->
	<div class="wrapper">
		<div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
			<div class="container-fluid">
				<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
					<div class="col mx-auto">
					<!-- 	<div class="mb-4 text-center">
							<img src="img/de_sistema/apudata.jpeg" width="300" alt="" />
						</div> -->
						<div class="card">
							<div class="card-body">
								<div class="border p-4 rounded">
									<div class="text-center">
										<div class="mb-4 text-center">
							<img src="img/de_sistema/apudata.jpeg" width="300" alt="" />
						</div>
										<!-- <p>Don't have an account yet? <a href="authentication-signup.html">Sign up here</a></p> -->
									</div>
									<!-- <div class="d-grid">
										<a class="btn my-4 shadow-sm btn-white" href="javascript:;"> <span class="d-flex justify-content-center align-items-center">
                          <img class="me-2" src="assets/images/icons/search.svg" width="16" alt="Image Description">
                          <span>Sign in with Google</span>
											</span>
										</a> <a href="javascript:;" class="btn btn-facebook"><i class="bx bxl-facebook"></i>Sign in with Facebook</a>
									</div>
									<div class="login-separater text-center mb-4"> <span>OR SIGN IN WITH EMAIL</span>
										<hr/>
									</div> -->
									<div class="form-body">
										<form class="row g-3">
											<div class="col-12">

												<input type="hidden" class="form-control" id="txt_no_concurente" name="txt_no_concurente" value="0">
												<label for="inputEmailAddress" class="form-label">Email</label>
												<!-- <input type="email" class="form-control" id="inputEmailAddress" placeholder="Email Address"> -->
												<input type="email" autocomplete="username" class="form-control" id="email" placeholder="Email" onblur="validar_directory()">
											</div>
											<div class="col-12">
												<label for="inputChoosePassword" class="form-label">Password</label>
												<div class="input-group" id="show_hide_password">
													<!-- <input type="password" class="form-control border-end-0" id="inputChoosePassword" value="12345678" placeholder="Enter Password"> -->
													 <input type="password" autocomplete="current-password" class="form-control" id="pass" name="pass" placeholder="Password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
												</div>
											</div>
											<!-- <div class="col-md-6">
												<div class="form-check form-switch">
													<input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>
													<label class="form-check-label" for="flexSwitchCheckChecked">Remember Me</label>
												</div>
											</div> -->
											<div class="col-md-12 text-end">
												<a href="reset.php">Olvido su contraseña ?</a>
												<br>
												<a href="Nueva_empresa.php">Nueva empresa</a>
											</div>
											<!-- <div class="col-md-12 text-end">	<a href="vista/SEGUROS/formulario_prestamos.php">Solicitar Salida de bienes</a>
											</div> -->
											<div class="col-12">
												<div class="d-grid">
													<button type="button" class="btn btn-primary" onclick="consultar_datos()" id="btn_inicio"><i class="bx bxs-lock-open"></i> Iniciar sesión</button>
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
		</div>
	</div>
	<!--end wrapper-->
	<!-- Bootstrap JS -->
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<!--Password show & hide js -->
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

		const textos = [
		    "Cargando Modulos...",
		    "Cargando Usuarios...",
		    "Cargando Permiso...",
		    "Probando configuracion",
		    "Dando ultimos toques"
		];

		// Índice inicial
		let indice = 0;

		// Función que muestra un texto diferente en un alert
		function mostrarTexto() {
			  $('#lbl_proceso').text(textos[indice]);
		    indice = (indice + 1) % textos.length; // Incrementar el índice y reiniciarlo si alcanza el final de la lista
		}

		// Ejecutar la función cada 2 segundos (2000 milisegundos)


		function titulos_cargados()
		{
				setInterval(mostrarTexto, 10000);
		}

		function limpiarInicio()
		{
			$('#myModal_Active').modal('hide');
			$('#txt_passActive').val('');
			$('#email').val('');
		}
	</script>
	
	<!--app JS-->
	

<div class="modal fade" id="myModal_empresas" tabindex="-1" role="dialog" data-bs-backdrop="static" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="titulo">Empresas</h3>
      </div>
      <div class="modal-body">
        <ul class="list-group list-group-flush radius-10" id="lista_empresas">
									
									<li class="list-group-item d-flex align-items-center radius-10 mb-2 shadow-sm">
										<div class="d-flex align-items-center">
											<div class="font-20"><i class="flag-icon flag-icon-us"></i>
											</div>
											<div class="flex-grow-1 ms-2">
												<h6 class="mb-0">United States</h6>
											</div>
										</div>
										<div class="ms-auto">435</div>
									</li>
									<li class="list-group-item d-flex align-items-center radius-10 mb-2 shadow-sm">
										<div class="d-flex align-items-center">
											<div class="font-20"><i class="flag-icon flag-icon-vn"></i>
											</div>
											<div class="flex-grow-1 ms-2">
												<h6 class="mb-0">Vietnam</h6>
											</div>
										</div>
										<div class="ms-auto">287</div>
									</li>
									<li class="list-group-item d-flex align-items-center radius-10 mb-2 shadow-sm">
										<div class="d-flex align-items-center">
											<div class="font-20"><i class="flag-icon flag-icon-au"></i>
											</div>
											<div class="flex-grow-1 ms-2">
												<h6 class="mb-0">Australia</h6>
											</div>
										</div>
										<div class="ms-auto">432</div>
									</li>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="myModal_modulos" tabindex="-1" role="dialog" data-bs-backdrop="static" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="titulo">Modulos</h3>
      </div>
      <div class="modal-body">
      	<input type="hidden" name="txt_id" id="txt_id">
        <ul class="list-group list-group-flush radius-10" id="lista_modulos_empresas">
					
					<li class="list-group-item d-flex align-items-center radius-10 mb-2 shadow-sm">
						<div class="d-flex align-items-center">
							<div class="font-20"><i class="flag-icon flag-icon-us"></i>
							</div>
							<div class="flex-grow-1 ms-2">
								<h6 class="mb-0">United States</h6>
							</div>
						</div>
						<div class="ms-auto">435</div>
					</li>
									
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="primer_inicio()">Iniciar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

</body>

</html>

<script>

// console.log(advertencia);
</script>


<div class="modal fade" id="myModal_espera" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
	  <div class="modal-dialog modal-sm modal-dialog-centered">
	    <div class="modal-content">
	      <div class="modal-header">
	      </div>
	      <div class="modal-body">
	      			
	         <div class="text-center">
	         		<h5 id="lbl_proceso">Configurando entorno...</h5>
		         	<div class="card-body">
									<div class="spinner-grow text-primary" role="status"> <span class="visually-hidden">Loading...</span>
									</div>
									<div class="spinner-grow text-secondary" role="status"> <span class="visually-hidden">Loading...</span>
									</div>
									<div class="spinner-grow text-success" role="status"> <span class="visually-hidden">Loading...</span>
									</div>
									<div class="spinner-grow text-danger" role="status"> <span class="visually-hidden">Loading...</span>
									</div>
									<div class="spinner-grow text-warning" role="status"> <span class="visually-hidden">Loading...</span>
									</div>																
							</div>

	         </div>         
	      </div>
	      <div class="modal-footer">        
	      </div>
	    </div>
	  </div>
	</div>

<div class="modal fade" id="myModal_Active" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
	  <div class="modal-dialog modal-sm modal-dialog-centered">
	    <div class="modal-content">
	      <div class="modal-header">
	      	<h5>Usuario de Active directory </h5>
	      </div>
	      <div class="modal-body">
	      			
	         <div class="text-center">
		         	<div class="card-body">
		         			<b>Ingrese contraseña de ActiveDirectory</b>
									<input type="" class="form-control form-control-sm" name="txt_passActive" id="txt_passActive">			

									<input type="hidden" class="form-control form-control-sm" name="txt_EmpresaIdActive" id="txt_EmpresaIdActive">
									<input type="hidden" class="form-control form-control-sm" name="txt_tablaActive" id="txt_tablaActive">			
									<!-- <input type="" class="form-control form-control-sm" name="txt_passActive" id="txt_passActive">										 -->
							</div>

	         </div>         
	      </div>
	      <div class="modal-footer">      
        	<button type="button" class="btn btn-primary" onclick="primerInicioActive()">Iniciar</button>
        	<button type="button" class="btn btn-secondary" onclick="limpiarInicio()">Cerrar</button>  
	      </div>
	    </div>
	  </div>
	</div>
