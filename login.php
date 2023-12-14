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

           console.log(response);  
           if (response==-2) 
           {
              Swal.fire( '','Email no registrado.','error');

           }else if(response == -1)
           {
              Swal.fire( '','Empresa Inexistente','info');

           }else if(response == 1)
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
       $.ajax({
         data:  {parametros:parametros},
         url:   'controlador/loginC.php?empresa_seleccionada=true',
         type:  'post',
         dataType: 'json',        
           success:  function (response) {  
           	if(response.respuesta==2)
           	{
           		$('#lista_modulos_empresas').html(response.modulos);
           		$('#txt_id').val(empresa);
           		$('#myModal_modulos').modal('show');
           	}else if(response.respuesta==1)
           	{
           		iniciar_sesion(empresa);
           	}      
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
       $.ajax({
         data:  {parametros:parametros},
         url:   'controlador/loginC.php?registrar_licencia=true',
         type:  'post',
         dataType: 'json',        
           success:  function (response) {  
           	if(response==-1)
           	{
           		Swal.fire('La licencian es erronea','','error');
           	}else if(response==1)
           	{
           		Swal.fire('La licencian registrada','','success');
           	}
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



	</script>
</head>

<body class="bg-login">
	<!--wrapper-->
	<div class="wrapper">
		<div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
			<div class="container-fluid">
				<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
					<div class="col mx-auto">
						<div class="mb-4 text-center">
							<img src="img/de_sistema/logo_puce.png" width="300" alt="" />
						</div>
						<div class="card">
							<div class="card-body">
								<div class="border p-4 rounded">
									<div class="text-center">
										<h3 class="">Activos fijos PUCE</h3>
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
												<input type="email" class="form-control" id="email" placeholder="Email">
											</div>
											<div class="col-12">
												<label for="inputChoosePassword" class="form-label">Password</label>
												<div class="input-group" id="show_hide_password">
													<!-- <input type="password" class="form-control border-end-0" id="inputChoosePassword" value="12345678" placeholder="Enter Password"> -->
													 <input type="password" class="form-control" id="pass" name="pass" placeholder="Password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
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
	</script>
	
	<!--app JS-->
	<script src="assets/js/app.js"></script>

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