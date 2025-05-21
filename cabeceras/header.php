<?php @session_start();
// print_r($_SESSION['INICIO']);die();
$tiempo_inactividad = 2 * 60;
if (!isset($_SESSION['INICIO']) || !isset($_SESSION['INICIO']['NO_CONCURENTE'])) {
	header('Location: ../login.php');
}
// if (isset($_SESSION['INICIO']['ULTIMO_ACCESO']) && (time() - $_SESSION['INICIO']['ULTIMO_ACCESO'] > $tiempo_inactividad)) {
//     // Cerrar la sesión
//     session_unset();
//     session_destroy();
//     header('Location: ../login.php');
//     exit();
// }

$titulo_pestania = 'apudata';

if (($_SESSION['INICIO']['TITULO_PESTANIA']) == '.' || $_SESSION['INICIO']['TITULO_PESTANIA'] == '' || $_SESSION['INICIO']['TITULO_PESTANIA'] == null) {
	$titulo_pestania;
} else {
	$titulo_pestania = $_SESSION['INICIO']['TITULO_PESTANIA'];
}

$logo = '../assets/images/favicon-32x32.png';

if (($_SESSION['INICIO']['LOGO']) == '.' || $_SESSION['INICIO']['LOGO'] == '' || $_SESSION['INICIO']['LOGO'] == null) {
	$logo;
} else {
	$logo = $_SESSION['INICIO']['LOGO'];
}

?>

<!doctype html>
<html lang="en">

<head>
	<title><?= $titulo_pestania; ?></title>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="<?= $logo; ?>" type="image/png" />
	<!--plugins-->
	<link href="../assets/plugins/OwlCarousel/css/owl.carousel.min.css" rel="stylesheet">
	<link href="../assets/plugins/Drag-And-Drop/dist/imageuploadify.min.css" rel="stylesheet" />
	<link href="../assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link href="../assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="../assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />

	<link href="../assets/plugins/select2/css/select2.min.css" rel="stylesheet" />
	<link href="../assets/plugins/select2/css/select2-bootstrap4.css" rel="stylesheet" />


	<link href="../assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />

	<link href="../assets/plugins/smart-wizard/css/smart_wizard_all.min.css" rel="stylesheet" type="text/css" />
	<!-- loader-->
	<link href="../assets/css/pace.min.css" rel="stylesheet" />
	<script src="../assets/js/pace.min.js"></script>
	<script src="../assets/js/jquery.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="../assets/css/bootstrap-extended.css" rel="stylesheet">
	<link href="../assets/css/app.css" rel="stylesheet">
	<link href="../assets/css/icons.css" rel="stylesheet">

	<!-- CSS adicionales Generales -->
	<link href="../assets/css/css_adicionales.css" rel="stylesheet">

	<!-- Theme Style CSS -->


	<link rel="stylesheet" href="../css/jquery-ui.css">
	<link rel="stylesheet" href="../assets/plugins/summernote/summernote-lite.css">
	<!-- <link rel="stylesheet" href="../assets/plugins/summernote/css/styles_summernote.css"> -->
	<link rel="stylesheet" href="../assets/plugins/summernote/summernote-bs5.min.css">
	<!-- <link rel="stylesheet" href="../assets/plugins/summernote/css/font-awesome.min.css"> -->

	<script src="../js/informes_globales.js"></script>
	<script src="../js/jquery-3.6.0.js"></script>
	<script src="../js/jquery-ui.js"></script>
	<script src="../js/codigos_globales.js"></script>
	<script src="../js/sweetalert2.all.min.js"></script>
	<script src="../js/notificaciones_seguros.js"></script>

	<!-- <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script> -->

	<style type="text/css">
		input[readonly] {
			/* Estilos para inputs en modo de solo lectura */
			background-color: #e8e8e8;
			/* Color de fondo */
			border: 1px solid #ccc;
			/* Borde */
			color: #555;
			/* Color del texto */
			cursor: not-allowed;
			/* Cambia el cursor */
			/* Otros estilos según sea necesario */
		}
	</style>

	<style>
		.input-group>.select2-container--bootstrap {
			width: auto;
			flex: 1 1 auto;
		}

		.input-group-sm>.btn {
			padding: 1px;
		}
	</style>




	<script type="text/javascript">
		// accesos();

		var mod = '<?php echo $_SESSION['INICIO']['MODULO_SISTEMA']; ?>';

		var TIPO = '<?php echo $_SESSION['INICIO']['TIPO']; ?>';
		var tabla = '<?php echo $_SESSION['INICIO']['NO_CONCURENTE_TABLA']; ?>';
		var id_tabla = '<?php echo $_SESSION['INICIO']['NO_CONCURENTE']; ?>';

		var parametros_noti = {
			'rol': TIPO,
			'tabla': tabla,
			'id_tabla': id_tabla,
		}

		//menu_lateral();
		$(document).ready(function() {
			restriccion();
			//notificaciones();
			notificaciones_1(parametros_noti);

			//Descomentar el settime 
			setInterval(function() {
				//notificaciones_1(parametros_noti);
			}, 6000);

			solicitudes();
		});

		function formatoDate(date) {
			if (date.length > 10) {
				Fecha = date.substr(0, 10);
			}
			// var formattedDate = new Date(date);
			// var d = formattedDate.getDate();
			// var m = formattedDate.getMonth();
			// m += 1; // javascript months are 0-11
			// if (m < 10) {
			// 	m = '0' + m;
			// }
			// if (d < 10) {
			// 	d = '0' + d;
			// }
			// var y = formattedDate.getFullYear();
			// var Fecha = y + "-" + m + "-" + d;
			//console.log(Fecha);
			return Fecha;
		}

		function cerrar_session() {

			$.ajax({
				// data:  {parametros:parametros},
				url: '../controlador/loginC.php?cerrar=true',
				type: 'post',
				dataType: 'json',
				/*beforeSend: function () {   
				     var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
				   $('#tabla_').html(spiner);
				},*/
				success: function(response) {
					// if (response==1) 
					// {
					// console.log(response);
					// location.href = "../login.php";
					location.reload();
					// } 
				}

			});
		}

		function regresar_modulo() {

			$.ajax({
				// data:  {parametros:parametros},
				url: '../controlador/loginC.php?regresar_modulo=true',
				type: 'post',
				dataType: 'json',
				/*beforeSend: function () {   
				     var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
				   $('#tabla_').html(spiner);
				},*/
				success: function(response) {
					location.href = 'inicio.php?mod=' + response + '&acc=index';
				}

			});
		}

		function mi_licencias(id)
		  {
		    var parametros = {
		      'id':id
		    }
		    $.ajax({
		          data:  {parametros,parametros},
		          url:   '../controlador/nueva_empresaC.php?detalle_licencias=true',
		          type: 'POST',         
		            dataType:'json',
		          success: function(response) {
		            console.log(response)
		            var tbl = '<thead><th>Licencia</th><th>Fecha Inicio</th><th>Fecha Fin</th><th>Modulo</th><th>Num Usuarios</th><th>Estado</th></thead>';
		            response.forEach(function(item,i){
		                tbl+=`<tr><td>`+item.Codigo_licencia+`</td>
		                          <td>`+item.Fecha_ini+`</td>
		                          <td>`+item.Fecha_exp+`</td>
		                          <td>`+item.nombre_modulo+`</td>
		                          <td>`+item.Numero_maquinas+`</td>
		                          <td>`
		                          if(item.registrado=='0')
		                          {
		                              tbl+=`<div class="d-flex align-items-center text-danger"> <i class="bx bx-radio-circle-marked bx-burst bx-rotate-90 align-middle font-18 me-1"></i>
		                                    <span>Pendiente de activacion</span>`;
		                          }else
		                          {
		                               tbl+=`<div class="d-flex align-items-center text-success"> <i class="bx bx-radio-circle-marked bx-burst bx-rotate-90 align-middle font-18 me-1"></i>
		                                    <span>Activo</span>`;
		                          }

		                        tbl+=`</td>
		                      </div>
		                </tr>`
		            })

		            $('#tbl_detalles').html(tbl);  
		            $('#myModal_detalles').modal('show');        
		          },
		          error: function(error) {
		              console.error('Error al enviar datos:', error);
		              // Puedes manejar los errores aquí
		          }
		      });
		  }

		function menu_lateral() {
			$.ajax({
				url: '../controlador/loginC.php?menu_lateral=true',
				type: 'post',
				dataType: 'json',

				success: function(response) {

					// console.log(response);
					var ini = '<li><a href="inicio.php?acc=index"><div class="parent-icon"><i class="bx bx-home"></i></div>						<div class="menu-title">Inicio</div></a></li>';

					$('#menu').html(ini + response);

				}

			});
		}

		function num_caracteres(campo, num) {
			var val = $('#' + campo).val();
			var cant = val.length;
			console.log(cant + '-' + num);

			if (cant > num) {
				$('#' + campo).val(val.substr(0, num));
				return false;
			}

		}

		function cambiar_configuraciones() {
			// Swal.fire({
			//   title: 'Esta apunto de ingresar a las configuraciones del sistema?',
			//   text: "Esta seguro de Ingrear !",
			//   icon: 'warning',
			//   showCancelButton: true,
			//   confirmButtonColor: '#3085d6',
			//   cancelButtonColor: '#d33',
			//   confirmButtonText: 'Si'
			// }).then((result) => {
			//     if (result.value) {

			$.ajax({
				url: '../controlador/loginC.php?change_settings=true',
				type: 'post',
				dataType: 'json',
				success: function(response) {
					if (response == 1) {
						location.href = 'inicio.php?mod=1&acc=index';
					}
				}
			});

			//     }

			// })
		}

		function consultar_modulos() {
			$.ajax({
				// data:  {parametros:parametros},
				url: '../controlador/loginC.php?modulos_sistema_acceso_rapido=true',
				type: 'post',
				dataType: 'json',
				success: function(response) {
					console.log(response);
					if(response.length>0)
					{
						html = '';
						response.forEach(function(item,i){
							html+=item.draw;
						})

						$('#pnl_acceso_rapido_modulo').html(html);

					}else
					{
						Swal.fire('', 'Su perfil no esta asignado a ningun modulo.', 'error').then(function() {
							window.location.href = "../login.php";
						});
					}
					
				}
			});
		}

		function modulo_seleccionado(modulo, link) {
			$.ajax({
				data: {
					modulo_sistema: modulo
				},
				url: '../controlador/loginC.php?modulos_sistema_selected=true',
				type: 'post',
				dataType: 'json',
				success: function(response) {

					location.href = 'inicio.php?mod=' + modulo + '&acc=' + link;
				}
			});
		}

		function cargar_empresas() {
			$('#myModal_empresas').modal('show');
			consultar_empresas();
		}

		function consultar_empresas() {
			$.ajax({
				// data:  {parametros:parametros},
				url: '../controlador/loginC.php?mis_empresas=true',
				type: 'post',
				dataType: 'json',
				success: function(response) {
					console.log(response);

					if (response != -1) {
						if (response.lista != '') {
							$('#lista_empresas').html(response.lista);
							$('#myModal_empresas').modal('show');
						} else {
							Swal.fire('', 'Usuario No registrado.', 'error');
						}
					} else {
						$('#lista_empresas').html('<li class="list-group-item d-flex align-items-center radius-10 mb-2 shadow-sm">' +
							'<div class="d-flex align-items-center">' +
							'<div class="font-20"><i class="bx bx-info-circle"></i>' +
							'</div>' +
							'<div class="flex-grow-1 ms-2">' +
							'<h6 class="mb-0">No tiene otras empresas asociada a este usuario</h6>' +
							'</div>' +
							'</div>' +
							'<div class="ms-auto"></div>' +
							'</li>');
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
				url: '../controlador/loginC.php?empresa_seleccionada_head=true',
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

		// function iniciar_sesion(parametros) {
		// 	// var parametros = {
		// 	// 	'id': id,
		// 	// }
		// 	$.ajax({
		// 		data: {
		// 			parametros: parametros
		// 		},
		// 		url: '../controlador/loginC.php?cambiar_empresa=true',
		// 		type: 'post',
		// 		dataType: 'json',
		// 		/*beforeSend: function () {   
		// 		     var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
		// 		   $('#tabla_').html(spiner);
		// 		},*/
		// 		success: function(response) {

		// 			console.log(response);
		// 			if (response == -2) {
		// 				Swal.fire('', 'Email no registrado.', 'error');

		// 			} else if (response == -1) {
		// 				Swal.fire('', 'Empresa Inexistente', 'info');

		// 			} else if (response == -3) {
		// 				Swal.fire('', 'Usuario sin acceso', 'error');

		// 			} else if (response == 1) {
		// 				window.location.href = "modulos_sistema.php";
		// 			}
		// 		}
		// 	});
		// }

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
				url: '../controlador/loginC.php?cambiar_empresa=true',
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
						window.location.href = "modulos_sistema.php";
					}
				}
			});
		}

		function seleccionar_perfil(rol, usu_normal) {
			$('#txt_tipo_rol').val(rol);
			$('#txt_normal').val(usu_normal);
			iniciar_sesion();
		}
	</script>
</head>

<body>
	<!--wrapper-->
	<div class="wrapper">

		<input type="hidden" name="" id="dba">
		<input type="hidden" name="" id="ver">
		<input type="hidden" name="" id="editar">
		<input type="hidden" name="" id="eliminar">
		<!--sidebar wrapper -->
		<div class="sidebar-wrapper" data-simplebar="true">
			<div class="sidebar-header">
				<?php if (file_exists($_SESSION['INICIO']['LOGO'])) { ?>
					<div>
						<!-- <img src="<?php echo $_SESSION['INICIO']['LOGO']; ?>" class="logo-icon" alt="logo icon"> -->
						<?= $_SESSION['INICIO']['MODULO_SISTEMA_IMG_ICO'] ?>
					</div>
				<?php } ?>
				<div>
					<h4 class="logo-text"><?php echo $_SESSION['INICIO']['MODULO_SISTEMA_NOMBRE']; ?></h4>
				</div>
				<div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
				</div>
			</div>
			<!--navigation-->
			<ul class="metismenu" id="menu">
				<?= $_SESSION['INICIO']['MENU_LATERAL'] ?? '' ?>
			</ul>
			<!-- <ul class="metismenu" id="menu1">
				
				<li>

					<a href="javascript:;" class="has-arrow">
						<div class="parent-icon"><i class="bx bx-category"></i>
						</div>
						<div class="menu-title">Application</div>
					</a>
					<ul>
						<li> <a href="app-emailbox.html"><i class="bx bx-right-arrow-alt"></i>Email</a>
						</li>
						<li> <a href="app-chat-box.html"><i class="bx bx-right-arrow-alt"></i>Chat Box</a>
						</li>
						<li> <a href="app-file-manager.html"><i class="bx bx-right-arrow-alt"></i>File Manager</a>
						</li>
						<li> <a href="app-contact-list.html"><i class="bx bx-right-arrow-alt"></i>Contatcs</a>
						</li>
						<li> <a href="app-to-do.html"><i class="bx bx-right-arrow-alt"></i>Todo List</a>
						</li>
						<li> <a href="app-invoice.html"><i class="bx bx-right-arrow-alt"></i>Invoice</a>
						</li>
						<li> <a href="app-fullcalender.html"><i class="bx bx-right-arrow-alt"></i>Calendar</a>
						</li>
					</ul>
				</li>
				<li class="menu-label">UI Elements</li>
				<li>
					<a href="widgets.html">
						<div class="parent-icon"><i class='bx bx-cookie'></i>
						</div>
						<div class="menu-title">Widgets</div>
					</a>
				</li>
				<li>
					<a href="javascript:;" class="has-arrow">
						<div class="parent-icon"><i class='bx bx-cart'></i>
						</div>
						<div class="menu-title">eCommerce</div>
					</a>
					<ul>
						<li> <a href="ecommerce-products.html"><i class="bx bx-right-arrow-alt"></i>Products</a>
						</li>
						<li> <a href="ecommerce-products-details.html"><i class="bx bx-right-arrow-alt"></i>Product Details</a>
						</li>
						<li> <a href="ecommerce-add-new-products.html"><i class="bx bx-right-arrow-alt"></i>Add New Products</a>
						</li>
						<li> <a href="ecommerce-orders.html"><i class="bx bx-right-arrow-alt"></i>Orders</a>
						</li>
					</ul>
				</li>
				<li>
					<a class="has-arrow" href="javascript:;">
						<div class="parent-icon"><i class='bx bx-bookmark-heart'></i>
						</div>
						<div class="menu-title">Components</div>
					</a>
					<ul>
						<li> <a href="component-alerts.html"><i class="bx bx-right-arrow-alt"></i>Alerts</a>
						</li>
						<li> <a href="component-accordions.html"><i class="bx bx-right-arrow-alt"></i>Accordions</a>
						</li>
						<li> <a href="component-badges.html"><i class="bx bx-right-arrow-alt"></i>Badges</a>
						</li>
						<li> <a href="component-buttons.html"><i class="bx bx-right-arrow-alt"></i>Buttons</a>
						</li>
						<li> <a href="component-cards.html"><i class="bx bx-right-arrow-alt"></i>Cards</a>
						</li>
						<li> <a href="component-carousels.html"><i class="bx bx-right-arrow-alt"></i>Carousels</a>
						</li>
						<li> <a href="component-list-groups.html"><i class="bx bx-right-arrow-alt"></i>List Groups</a>
						</li>
						<li> <a href="component-media-object.html"><i class="bx bx-right-arrow-alt"></i>Media Objects</a>
						</li>
						<li> <a href="component-modals.html"><i class="bx bx-right-arrow-alt"></i>Modals</a>
						</li>
						<li> <a href="component-navs-tabs.html"><i class="bx bx-right-arrow-alt"></i>Navs & Tabs</a>
						</li>
						<li> <a href="component-navbar.html"><i class="bx bx-right-arrow-alt"></i>Navbar</a>
						</li>
						<li> <a href="component-paginations.html"><i class="bx bx-right-arrow-alt"></i>Pagination</a>
						</li>
						<li> <a href="component-popovers-tooltips.html"><i class="bx bx-right-arrow-alt"></i>Popovers & Tooltips</a>
						</li>
						<li> <a href="component-progress-bars.html"><i class="bx bx-right-arrow-alt"></i>Progress</a>
						</li>
						<li> <a href="component-spinners.html"><i class="bx bx-right-arrow-alt"></i>Spinners</a>
						</li>
						<li> <a href="component-notifications.html"><i class="bx bx-right-arrow-alt"></i>Notifications</a>
						</li>
						<li> <a href="component-avtars-chips.html"><i class="bx bx-right-arrow-alt"></i>Avatrs & Chips</a>
						</li>
					</ul>
				</li>
				<li>
					<a class="has-arrow" href="javascript:;">
						<div class="parent-icon"><i class="bx bx-repeat"></i>
						</div>
						<div class="menu-title">Content</div>
					</a>
					<ul>
						<li> <a href="content-grid-system.html"><i class="bx bx-right-arrow-alt"></i>Grid System</a>
						</li>
						<li> <a href="content-typography.html"><i class="bx bx-right-arrow-alt"></i>Typography</a>
						</li>
						<li> <a href="content-text-utilities.html"><i class="bx bx-right-arrow-alt"></i>Text Utilities</a>
						</li>
					</ul>
				</li>
				<li>
					<a class="has-arrow" href="javascript:;">
						<div class="parent-icon"> <i class="bx bx-donate-blood"></i>
						</div>
						<div class="menu-title">Icons</div>
					</a>
					<ul>
						<li> <a href="icons-line-icons.html"><i class="bx bx-right-arrow-alt"></i>Line Icons</a>
						</li>
						<li> <a href="icons-boxicons.html"><i class="bx bx-right-arrow-alt"></i>Boxicons</a>
						</li>
						<li> <a href="icons-feather-icons.html"><i class="bx bx-right-arrow-alt"></i>Feather Icons</a>
						</li>
					</ul>
				</li>
				<li class="menu-label">Forms & Tables</li>
				<li>
					<a class="has-arrow" href="javascript:;">
						<div class="parent-icon"><i class='bx bx-message-square-edit'></i>
						</div>
						<div class="menu-title">Forms</div>
					</a>
					<ul>
						<li> <a href="form-elements.html"><i class="bx bx-right-arrow-alt"></i>Form Elements</a>
						</li>
						<li> <a href="form-input-group.html"><i class="bx bx-right-arrow-alt"></i>Input Groups</a>
						</li>
						<li> <a href="form-layouts.html"><i class="bx bx-right-arrow-alt"></i>Forms Layouts</a>
						</li>
						<li> <a href="form-validations.html"><i class="bx bx-right-arrow-alt"></i>Form Validation</a>
						</li>
						<li> <a href="form-wizard.html"><i class="bx bx-right-arrow-alt"></i>Form Wizard</a>
						</li>
						<li> <a href="form-text-editor.html"><i class="bx bx-right-arrow-alt"></i>Text Editor</a>
						</li>
						<li> <a href="form-file-upload.html"><i class="bx bx-right-arrow-alt"></i>File Upload</a>
						</li>
						<li> <a href="form-date-time-pickes.html"><i class="bx bx-right-arrow-alt"></i>Date Pickers</a>
						</li>
						<li> <a href="form-select2.html"><i class="bx bx-right-arrow-alt"></i>Select2</a>
						</li>
					</ul>
				</li>
				<li>
					<a class="has-arrow" href="javascript:;">
						<div class="parent-icon"><i class="bx bx-grid-alt"></i>
						</div>
						<div class="menu-title">Tables</div>
					</a>
					<ul>
						<li> <a href="table-basic-table.html"><i class="bx bx-right-arrow-alt"></i>Basic Table</a>
						</li>
						<li> <a href="table-datatable.html"><i class="bx bx-right-arrow-alt"></i>Data Table</a>
						</li>
					</ul>
				</li>
				<li class="menu-label">Pages</li>
				<li>
					<a class="has-arrow" href="javascript:;">
						<div class="parent-icon"><i class="bx bx-lock"></i>
						</div>
						<div class="menu-title">Authentication</div>
					</a>
					<ul>
						<li> <a href="authentication-signin.html"><i class="bx bx-right-arrow-alt"></i>Sign In</a>
						</li>
						<li> <a href="authentication-signup.html"><i class="bx bx-right-arrow-alt"></i>Sign Up</a>
						</li>
						<li> <a href="authentication-signin-with-header-footer.html"><i class="bx bx-right-arrow-alt"></i>Sign In with Header & Footer</a>
						</li>
						<li> <a href="authentication-signup-with-header-footer.html"><i class="bx bx-right-arrow-alt"></i>Sign Up with Header & Footer</a>
						</li>
						<li> <a href="authentication-forgot-password.html"><i class="bx bx-right-arrow-alt"></i>Forgot Password</a>
						</li>
						<li> <a href="authentication-reset-password.html"><i class="bx bx-right-arrow-alt"></i>Reset Password</a>
						</li>
						<li> <a href="authentication-lock-screen.html"><i class="bx bx-right-arrow-alt"></i>Lock Screen</a>
						</li>
					</ul>
				</li>
				<li>
					<a href="user-profile.html">
						<div class="parent-icon"><i class="bx bx-user-circle"></i>
						</div>
						<div class="menu-title">User Profile</div>
					</a>
				</li>
				<li>
					<a href="timeline.html">
						<div class="parent-icon"> <i class="bx bx-video-recording"></i>
						</div>
						<div class="menu-title">Timeline</div>
					</a>
				</li>
				<li>
					<a class="has-arrow" href="javascript:;">
						<div class="parent-icon"><i class="bx bx-error"></i>
						</div>
						<div class="menu-title">Errors</div>
					</a>
					<ul>
						<li> <a href="errors-404-error.html"><i class="bx bx-right-arrow-alt"></i>404 Error</a>
						</li>
						<li> <a href="errors-500-error.html"><i class="bx bx-right-arrow-alt"></i>500 Error</a>
						</li>
						<li> <a href="errors-coming-soon.html"><i class="bx bx-right-arrow-alt"></i>Coming Soon</a>
						</li>
						<li> <a href="error-blank-page.html"><i class="bx bx-right-arrow-alt"></i>Blank Page</a>
						</li>
					</ul>
				</li>
				<li>
					<a href="faq.html">
						<div class="parent-icon"><i class="bx bx-help-circle"></i>
						</div>
						<div class="menu-title">FAQ</div>
					</a>
				</li>
				<li>
					<a href="pricing-table.html">
						<div class="parent-icon"><i class="bx bx-diamond"></i>
						</div>
						<div class="menu-title">Pricing</div>
					</a>
				</li>
				<li class="menu-label">Charts & Maps</li>
				<li>
					<a class="has-arrow" href="javascript:;">
						<div class="parent-icon"><i class="bx bx-line-chart"></i>
						</div>
						<div class="menu-title">Charts</div>
					</a>
					<ul>
						<li> <a href="charts-apex-chart.html"><i class="bx bx-right-arrow-alt"></i>Apex</a>
						</li>
						<li> <a href="charts-chartjs.html"><i class="bx bx-right-arrow-alt"></i>Chartjs</a>
						</li>
						<li> <a href="charts-highcharts.html"><i class="bx bx-right-arrow-alt"></i>Highcharts</a>
						</li>
					</ul>
				</li>
				<li>
					<a class="has-arrow" href="javascript:;">
						<div class="parent-icon"><i class="bx bx-map-alt"></i>
						</div>
						<div class="menu-title">Maps</div>
					</a>
					<ul>
						<li> <a href="map-google-maps.html"><i class="bx bx-right-arrow-alt"></i>Google Maps</a>
						</li>
						<li> <a href="map-vector-maps.html"><i class="bx bx-right-arrow-alt"></i>Vector Maps</a>
						</li>
					</ul>
				</li>
				<li class="menu-label">Others</li>
				<li>
					<a class="has-arrow" href="javascript:;">
						<div class="parent-icon"><i class="bx bx-menu"></i>
						</div>
						<div class="menu-title">Menu Levels</div>
					</a>
					<ul>
						<li> <a class="has-arrow" href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Level One</a>
							<ul>
								<li> <a class="has-arrow" href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Level Two</a>
									<ul>
										<li> <a href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Level Three</a>
										</li>
									</ul>
								</li>
							</ul>
						</li>
					</ul>
				</li>
				<li>
					<a href="javascript:;">
						<div class="parent-icon"><i class="bx bx-folder"></i>
						</div>
						<div class="menu-title">Documentation</div>
					</a>
				</li>
				<li>
					<a href="javascript:;">
						<div class="parent-icon"><i class="bx bx-support"></i>
						</div>
						<div class="menu-title">Support</div>
					</a>
				</li>
			</ul> -->
			<!--end navigation-->
		</div>
		<!--end sidebar wrapper -->
		<!--start header -->
		<header>
			<div class="topbar d-flex align-items-center">
				<nav class="navbar navbar-expand">
					<div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
					</div>
					<div class="search-bar flex-grow-1">
						<!-- <div class="position-relative search-bar-box">
							<input type="text" class="form-control search-control" placeholder="Type to search..."> <span class="position-absolute top-50 search-show translate-middle-y"><i class='bx bx-search'></i></span>
							<span class="position-absolute top-50 search-close translate-middle-y"><i class='bx bx-x'></i></span>
						</div> -->
					</div>
					<div class="top-menu ms-auto">
						<ul class="navbar-nav align-items-center">
							<!-- <li class="nav-item mobile-search-icon">
								<a class="nav-link" href="#"> <i class='bx bx-search'></i>
								</a>
							</li> -->
							<li class="nav-item dropdown dropdown-large">
								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" onclick="consultar_modulos();" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <i class='bx bx-category'></i>
								</a>
								<div class="dropdown-menu dropdown-menu-end">
									<div class="row row-cols-3 g-3 p-3" id="pnl_acceso_rapido_modulo">
										<!-- <div class="col text-center">
											<div class="app-box mx-auto bg-gradient-burning text-white"><i class='bx bx-atom'></i>
											</div>
											<div class="app-title">Projects</div>
										</a>
										</div>
										<div class="col text-center">
											<div class="app-box mx-auto bg-gradient-lush text-white"><i class='bx bx-shield'></i>
											</div>
											<div class="app-title">Tasks</div>
										</div>
										<div class="col text-center">
											<div class="app-box mx-auto bg-gradient-kyoto text-dark"><i class='bx bx-notification'></i>
											</div>
											<div class="app-title">Feeds</div>
										</div>
										<div class="col text-center">
											<div class="app-box mx-auto bg-gradient-blues text-dark"><i class='bx bx-file'></i>
											</div>
											<div class="app-title">Files</div>
										</div> -->
									</div>
								</div>
							</li>
							<li class="nav-item dropdown dropdown-large">
								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="alert-count" style="display:none" id="pnl_noti"><b id="lbl_noti">0</b></span>
									<i class='bx bx-bell'></i>
								</a>
								<div class="dropdown-menu dropdown-menu-end">
									<a href="javascript:;">
										<div class="msg-header">
											<p class="msg-header-title">Notificaciones</p>
											<!-- <p class="msg-header-clear ms-auto">Marks all as read</p> -->
										</div>
									</a>
									<div class="header-notifications-list" id="pnl_notificaciones">
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-primary text-primary"><i class="bx bx-group"></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">CONSULTA<span class="msg-time float-end">14 segundos
														</span></h6>
													<p class="msg-info">Prueba Header</p>
												</div>
											</div>
										</a>
									</div>
									<!-- <a href="javascript:;">
										<div class="text-center msg-footer">View All Notifications</div>
									</a> -->
								</div>
							</li>
							<li class="nav-item dropdown dropdown-large">
								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span class="alert-count" style="display:none;" id="pnl_soli"><b id="lbl_soli">0</b></span>
									<i class='bx bx-comment'></i>
								</a>
								<div class="dropdown-menu dropdown-menu-end">
									<a href="javascript:;">
										<div class="msg-header">
											<p class="msg-header-title">Messages</p>
											<p class="msg-header-clear ms-auto">Marks all as read</p>
										</div>
									</a>
									<div class="header-message-list" id="pnl_solicitudes">

									</div>
								</div>
							</li>
							<?php if ($_SESSION['INICIO']['NUM_MODULOS'] > 1) { ?>
								<li>
									<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="modulos_sistema.php" role="button" aria-expanded="false" title="Salir del modulo">
										<i class='bx bx-log-out'></i>
									</a>
								</li>
							<?php }
							if ($_SESSION['INICIO']['NO_CONCURENTE'] == '' && $_SESSION['INICIO']['NUM_EMPRESAS'] > 1) { ?>
								<li>
									<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" onclick="cargar_empresas()" role="button" aria-expanded="false" title="Cambiar empresa">
										<i class='bx bx-building-house'></i>
									</a>
								</li>
							<?php } ?>
						</ul>
					</div>
					<div class="user-box dropdown">
						<a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							<?php if ($_SESSION['INICIO']['NO_CONCURENTE_NOM'] == '') { ?>
								<img src="<?php if ($_SESSION['INICIO']['FOTO'] != '') {
												echo $_SESSION['INICIO']['FOTO'];
											} else {
												echo "../img/sin_imagen.jpg";
											} ?>" class="user-img" alt="user avatar">
							<?php } else { ?>
								<img src="<?php if ($_SESSION['INICIO']['FOTO'] != '') {
												echo $_SESSION['INICIO']['FOTO'];
											} else {
												echo "../img/sin_imagen.jpg";
											} ?>" class="user-img" alt="user avatar">
							<?php } ?>
							<div class="user-info ps-3">
								<p class="user-name mb-0"><?php if ($_SESSION['INICIO']['NO_CONCURENTE_NOM'] == '') {
																echo $_SESSION['INICIO']['USUARIO'];
															} else {
																echo $_SESSION['INICIO']['NO_CONCURENTE_NOM'];
															} ?></p>
								<p class="designattion mb-0"><?php echo $_SESSION['INICIO']['TIPO']; ?></p>
							</div>
						</a>
						<ul class="dropdown-menu dropdown-menu-end">
							<li><a class="dropdown-item" href="inicio.php?acc=perfil"><i class="bx bx-user"></i><span>Perfil</span></a>
							</li>
							<?php if ($_SESSION['INICIO']['TIPO'] == 'DBA' || $_SESSION['INICIO']['TIPO'] == 'ADMINISTRADOR' || $_SESSION['INICIO']['TIPO'] == 'ADMIN') { ?>
								<li><a class="dropdown-item" href="javascript:;" onclick="cambiar_configuraciones()"><i class="bx bx-cog"></i><span>Configuraciones</span></a>
								</li>

							<?php } ?>
							<!-- <li><a class="dropdown-item" href="javascript:;"><i class='bx bx-home-circle'></i><span>Dashboard</span></a>
							</li> -->

							<li><a class="dropdown-item" href="inicio.php?mod=<?php echo $_SESSION['INICIO']['MODULO_SISTEMA']; ?>&acc=descargas"><i class='bx bx-download'></i><span>Descargas</span></a>
							</li>
							<li onclick="$('#myModal_acerca_de').modal('show')"><a class="dropdown-item" href="#"><i class='bx bx-info-circle'></i><span>Acerca de</span></a>
							</li>
							<li onclick="mi_licencias('<?php echo $_SESSION['INICIO']['ID_EMPRESA'];?>')"><a class="dropdown-item" href="#"><i class='bx bxs-key'></i><span>Mi Licencia</span></a>
							</li>
							<li>
								<div class="dropdown-divider mb-0"></div>
							</li>
							<?php if ($_SESSION['INICIO']['MODULO_SISTEMA'] == 1) { ?>
								<li><a class="dropdown-item" href="javascript:;" onclick="regresar_modulo();"><i class='bx bx-cog'></i><span>Salir de configuraciones</span></a>
								<?php } ?>
								<li>

									<a class="dropdown-item" href="javascript:;" onclick="cerrar_session();">
										<i class='bx bx-log-out-circle'></i><span>Salir de sistema</span>
									</a>

								</li>
						</ul>
					</div>
				</nav>
			</div>
		</header>

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

		<script>
			$(document).ready(function() {
				let parametros = new URLSearchParams(window.location.search);
				let acc_actual = parametros.get('acc');
				let textoActivo = '';

				if (acc_actual) {
					// Buscar el <a> que tiene el href correspondiente al 'acc' actual
					$('#menu a').each(function() {
						let href = $(this).attr('href');
						if (href && href.includes('acc=' + acc_actual)) {
							textoActivo = $(this).text().trim(); // Obtener el texto del ítem
						}
					});
				}

				// Si se encontró el texto del ítem, lo agregamos al título de la página
				if (textoActivo) {
					document.title = '<?= $titulo_pestania; ?>| ' + textoActivo;
				}
			});
		</script>