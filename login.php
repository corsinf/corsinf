<!DOCTYPE html>
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
	<!-- Login V2 con estilos diferentes -->
	<link rel="stylesheet" href="assets/login_v2/login_v2.css">

	<title>APUDATA</title>
	<script src="js/sweetalert2.all.min.js"></script>

	<script type="text/javascript">
		function consultar_datos() {
			var email = $('#email').val();
			var pass = $('#pass').val();
			if (email != '' && pass != '') {
				var parametros = {
					'email': email,
					'pass': pass,
				}
				$.ajax({
					data: {
						parametros: parametros
					},
					url: 'controlador/loginC.php?iniciar=true',
					type: 'post',
					dataType: 'json',
					success: function(response) {
						console.log(response);

						if (response.lista != '') {
							if (response.lista == -3) {
								Swal.fire('', 'Usuario asignado a una empresa no activa', 'error');
							} else {
								$('#lista_empresas').html(response.lista);
								$('#txt_no_concurente').val(response.no_concurente);
								$('#myModal_empresas').modal('show');
							}
						} else {
							Swal.fire('', 'Usuario No registrado.', 'error');
						}
					}
				});

			} else {
				Swal.fire('', 'LLene todo los campos.', 'error');
			}
		}

		function iniciar_sesion() {
			// $('#myModal_espera').modal('show');
			var parametros = {
				'tipo': $('#txt_tipo_rol').val(),
				'empresa': $('#txt_empresa').val(),
				'ActiveDir': $('#txt_activeDir').val(),
				'normal': $('#txt_normal').val(),
				'primera_vez': $('#txt_primera_vez').val(),
				'pass': $('#pass').val(),
				'email': $('#email').val(),
			}

			$.ajax({
				data: {
					parametros: parametros
				},
				url: 'controlador/loginC.php?iniciar_empresa=true',
				type: 'post',
				dataType: 'json',
				/*beforeSend: function () {   
				     var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
				   $('#tabla_').html(spiner);
				},*/
				success: function(response) {

					$('#myModal_espera').modal('hide');

					console.log(response);
					if (response == -2) {
						Swal.fire('', 'Email no registrado.', 'error');

					} else if (response == -1) {
						Swal.fire('', 'Contraseña incorrecta', 'info');

					} else if (response == -3) {
						Swal.fire('', 'Usuario sin acceso', 'error');

					} else if (response == -4) {
						Swal.fire('', 'Usuario de active directory no autentificado', 'error');
					} else if (response == 1) {
						window.location.href = "vista/modulos_sistema.php";
					}
				}
			});
		}

		function empresa_selecconada(empresa, activeDir, primera_vez) {

			// $('#myModal_espera').modal('show');
			pass = $('#pass').val();
			email = $('#email').val();
			$('#txt_empresa').val(empresa);
			$('#txt_activeDir').val(activeDir);
			$('#txt_primera_vez').val(primera_vez);

			var parametros = {
				'empresa': empresa,
				'activeDir': activeDir,
				'primera_vez': primera_vez,
				'pass': pass,
				'email': email,
			}
			// titulos_cargados();

			$.ajax({
				data: {
					parametros: parametros
				},
				url: 'controlador/loginC.php?empresa_seleccionada=true',
				type: 'post',
				dataType: 'json',
				success: function(response) {
					

					if (response.respuesta == 2) {
						$('#lista_modulos_empresas').html(response.modulos);
						$('#txt_id').val(empresa);
						$('#myModal_modulos').modal('show');
						// $('#myModal_espera').modal('hide');
					} else if (response.respuesta == 1) {

						// $('#myModal_espera').modal('show');
						if (response.num_roles > 1 && response.roles != '') {
							$('#lista_roles').html(response.roles);
							$('#myModal_empresas').modal('hide');
							$('#myModal_rol').modal('show');
						} else {
							$('#txt_tipo_rol').val(response.roles);
							$('#txt_normal').val(response.normal);
							iniciar_sesion();
						}
					}
					
					$('#myModal_espera').modal('hide');
				},
				error: function(xhr, status, error) {
					$('#myModal_espera').modal('hide');
				}
			});

		}

		function seleccionar_perfil(rol, usu_normal) {
			$('#txt_tipo_rol').val(rol);
			$('#txt_normal').val(usu_normal);
			iniciar_sesion();
		}

		function rol_selecconada(empresa, activeDir, normal, tipo, primera_vez) {
			pass = $('#pass').val();
			email = $('#email').val();
			var parametros = {
				'empresa': empresa,
				'activeDir': activeDir,
				'normal': normal,
				'primera_vez': primera_vez,
				'tipo': tipo,
				'pass': pass,
				'email': email,
			}
			// titulos_cargados();
			$.ajax({
				data: {
					parametros: parametros
				},
				url: 'controlador/loginC.php?empresa_seleccionada=true',
				type: 'post',
				dataType: 'json',
				success: function(response) {
					// $('#myModal_espera').modal('hide');
					if (response.respuesta == 2) {
						$('#lista_modulos_empresas').html(response.modulos);
						$('#txt_id').val(empresa);
						$('#myModal_modulos').modal('show');
						// $('#myModal_espera').modal('hide');
					} else if (response.respuesta == 1) {

						// $('#myModal_espera').modal('show');
						iniciar_sesion(parametros);
					}
				},
				error: function(xhr, status, error) {
					$('#myModal_espera').modal('hide');
				}
			});
		}

		function registrar_licencia(empresa, modulo) {
			licencia = $('#licencia_' + modulo).val();
			var parametros = {
				'empresa': empresa,
				'modulo': modulo,
				'licencia': licencia,
			}
			$('#myModal_espera').modal('show');
			$.ajax({
				data: {
					parametros: parametros
				},
				url: 'controlador/loginC.php?registrar_licencia=true',
				type: 'post',
				dataType: 'json',
				success: function(response) {
					// $('#myModal_espera').modal('show');
					if (response == -1) {
						Swal.fire('La licencian es erronea', '', 'error');
					} else if (response == 1) {
						Swal.fire('La licencian registrada', '', 'success');
					}
				},
				error: function(xhr, status, error) {
					$('#myModal_espera').modal('hide');
				}
			});
		}

		function enter_press(event) {
			var codigo = event.which || event.keyCode;
			if (codigo === 13) {
				consultar_datos()
				// console.log("Tecla ENTER");
			}
		}

		function primer_inicio() {
			var parametros = {
				'empresa': $('#txt_id').val(),
			}
			$.ajax({
				data: {
					parametros: parametros
				},
				url: 'controlador/loginC.php?primer_inicio=true',
				type: 'post',
				dataType: 'json',
				success: function(response) {
					if (response == -1) {
						Swal.fire('La licencian es erronea', '', 'error');
					} else if (response == 1) {
						Swal.fire('Se a enviado a su correo registrado las credenciales de ingreso', '', 'success').then(function() {
							$('#myModal_empresas').modal('hide');
							$('#myModal_modulos').modal('hide');
						});
					}
				}
			});
		}

		function validar_directory() {
			user = $('#email').val();
			if (user == '' || user == null) {
				return false;
			}
			console.log(user);
			var parametros = {
				'user': user,
			}
			$.ajax({
				data: {
					parametros: parametros
				},
				url: 'controlador/loginC.php?validar_directory=true',
				type: 'post',
				dataType: 'json',
				success: function(response) {
					if (response.length == 1) {
						if (response[0].ActiveDirectory == '1' && response[0].PrimerIngresoActiveDir == '1') {
							$('#myModal_Active').modal('show');
							$('#txt_EmpresaIdActive').val(response[0]['id']);
							$('#txt_tablaActive').val(response[0]['tabla']);
							//alert('ingres por primera vez debe validar en active directory y copiar la contraseña, muetra por primera vez una caja de tecto que va al ActiveDirectory');
						}
						//else
						// {
						// 	 alert('valida en active y actualiza pas en caso de que sea otra credencial, proceso de actualizacion interno');
						// } 
					} else if (response.length > 1) {
						alert('debe salir la lista de empresas');
					}
				}
			});

		}

		function primerInicioActive() {
			var parametros = {
				'user': $('#email').val(),
				'pass': $('#txt_passActive').val(),
				'empresa': $('#txt_EmpresaIdActive').val(),
				'tabla': $('#txt_tablaActive').val(),
			}
			$.ajax({
				data: {
					parametros: parametros
				},
				url: 'controlador/loginC.php?primerInicioActive=true',
				type: 'post',
				dataType: 'json',
				success: function(response) {
					console.log(response);
					if (response.resp == 1) {
						Swal.fire('ActiveDirectory', response.msj, 'success').then(function() {
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

<style>
	section {
		display: flex;
		justify-content: center;
		align-items: center;
		width: 100%;
		min-height: 100vh;
		background: url(img/inicio/login1.webp);
		background-position: center;
		background-size: cover;
	}
</style>

<!-- Inicio del body -->

<body class="">
	<section>
		<div class="contenedor">
			<div class="formulario">
				<form action="#">
					<img src="img/de_sistema/apudata_blanco.svg" width="300" alt="" />

					<!-- <div class="input-contenedor">
						<i class='bx bx-envelope'></i>
						<input type="text" name="txt_email" id="txt_email" value="" placeholder="" required>
						<label for="txt_email">Email</label>
					</div> -->

					<div class="input-contenedor">
						<input type="hidden" id="txt_no_concurente" name="txt_no_concurente" value="0">
						<!-- <input type="email" class="form-control" id="inputEmailAddress" placeholder="Email Address"> -->
						<i class='bx bx-envelope'></i>
						<input type="text" class="m-1" id="email" value="" placeholder="" autocomplete='username' onblur="/*validar_directory()*/" required>
						<label for="email">Correo</label>
					</div>

					<div class="input-contenedor">
						<div id="show_hide_password">
							<!-- <i class='bx bxs-lock-alt'></i> -->
							<a href="javascript:;" class=""><i class='bx bx-hide'></i></a>
							<input type="password" class="m-1" id="pass" name="pass" placeholder="" autocomplete="current-password" required> <!-- <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a> -->
							<label for="pass">Contraseña</label>
						</div>
					</div>

					<div class="olvidar">
						<label for="#">
							<a href="#">Bienvenido</a>
						</label>
					</div>
				</form>

				<div>
					<button type="button" class="" onclick="consultar_datos()" id="btn_inicio"><i class="bx bxs-lock-open"></i> Iniciar sesión</button>
				</div>



			</div>
		</div>
	</section>


	<div class="col-md-12 text-end" hidden>
		<a href="reset.php">Olvido su contraseña ?</a>
		<br>
		<a href="Nueva_empresa.php">Nueva empresa</a>
	</div>
	<!-- <div class="col-md-12 text-end">	<a href="vista/SEGUROS/formulario_prestamos.php">Solicitar Salida de bienes</a></div> -->

</body>
<!-- Fin del body -->

<footer>
	<!-- Bootstrap JS -->
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<!--Password show & hide js -->
	<script>
		$(document).ready(function() {
			$("#show_hide_password a").on('click', function(event) {
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

		$(document).keydown(function(tecla) {
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


		function titulos_cargados() {
			setInterval(mostrarTexto, 10000);
		}

		function limpiarInicio() {
			$('#myModal_Active').modal('hide');
			$('#txt_passActive').val('');
			$('#email').val('');
		}
	</script>
</footer>

<!--app JS-->

</html>

<div class="modal fade" id="myModal_empresas" tabindex="-1" role="dialog" data-bs-backdrop="static" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="titulo">Empresas</h3>
			</div>
			<div class="modal-body" style=" overflow-y: scroll;  height: 300px;">
				<input type="hidden" name="txt_empresa" id="txt_empresa">
				<input type="hidden" name="txt_activeDir" id="txt_activeDir">
				<input type="hidden" name="txt_normal" id="txt_normal">
				<input type="hidden" name="txt_primera_vez" id="txt_primera_vez">

				<div class="product-list mb-3 ps ps--active-y" id="lista_empresas" style="height: auto;">

				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="myModal_rol" tabindex="-1" role="dialog" data-bs-backdrop="static" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="titulo">Perfil de usuario</h3>
			</div>
			<div class="modal-body" style=" overflow-y: scroll;  height: 300px;">
				<input type="hidden" name="txt_tipo_rol" id="txt_tipo_rol">
				<div class="product-list mb-3 ps ps--active-y" id="lista_roles" style="height: auto;">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" onclick="
       			$('#myModal_empresas').modal('show');
       			$('#myModal_rol').modal('hide');">Cerrar</button>
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

<script>
	var advertencia =
		`░██████╗████████╗░█████╗░██████╗░██╗      ¡Atención! Este mensaje está destinado a los usuarios. 
██╔════╝╚══██╔══╝██╔══██╗██╔══██╗██║      Si alguien te ha instruido a copiar y pegar código aquí para activar
╚█████╗░░░░██║░░░██║░░██║██████╔╝██║      una función de APUDATA con el objetivo de acceder ilegalmente a una cuenta,  
░╚═══██╗░░░██║░░░██║░░██║██╔═══╝░╚═╝      ten en cuenta que se trata de una actividad fraudulenta.
██████╔╝░░░██║░░░╚█████╔╝██║░░░░░██╗      Si sigues estas instrucciones, podrías poner en riesgo la seguridad de tu cuenta. 
╚═════╝░░░░╚═╝░░░░╚════╝░╚═╝░░░░░╚═╝      Por favor, ten precaución y no compartas información personal o confidencial. 
Si tienes alguna pregunta o inquietud, por favor contáctanos en el equipo de soporte de APUDATA.
Para obtener más información, visita nuestro sitio web: https://www.corsinf.com.    
`;
	console.log(advertencia);
</script>