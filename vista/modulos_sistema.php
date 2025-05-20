<?php include ('../cabeceras/header3.php'); //print_r($_SESSION['INICIO']);die(); ?>
<script type="text/javascript">
 $( document ).ready(function() {
  // restriccion();    
	consultar_datos()
});

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
                'pass': "12345",
                'email': '<?php echo $_SESSION['INICIO']['EMAIL']; ?>',
                'modulo_sistema':1,
            }
            // titulos_cargados();

            $.ajax({
                data: {
                    parametros: parametros
                },
                url: '../controlador/loginC.php?empresa_seleccionada_x_modulo=true',
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
function registrar_licencia(empresa, modulo) {
      licencia = $('#licencia_' + modulo).val();
      var parametros = {
          'empresa': empresa,
          'modulo': modulo,
          'licencia': licencia,
          'modulos_sistema':1,
      }
      $('#myModal_espera').modal('show');
      $.ajax({
          data: {
              parametros: parametros
          },
          url: '../controlador/loginC.php?registrar_licencia=true',
          type: 'post',
          dataType: 'json',
          success: function(response) {
              // $('#myModal_espera').modal('show');
              if (response == -1) {
                  Swal.fire('La licencian es erronea', '', 'error');
              } else if (response == 1) {
                  Swal.fire('La licencian registrada', 'Debe iniciar session nuevamente', 'success').then(function(){
				        		cerrar_session();
                  });
                  $('#myModal_modulos').modal('hide');
                  $('#myModal_espera').modal('hide');
              }
          },
          error: function(xhr, status, error) {
              $('#myModal_espera').modal('hide');
          }
      });
  }

function consultar_datos()
  {           
   $.ajax({
     // data:  {parametros:parametros},
     url:   '../controlador/loginC.php?modulos_sistema=true',
     type:  'post',
     dataType: 'json',
       success:  function (response) {
       console.log(response);    
       if (response.num==0) 
       {
          Swal.fire( '','Su perfil no esta asignado a ningun modulo.','error').then(function(){
          	window.location.href = "../login.php";
          });
       }else
       {
       		if(response.num==1)
       		{
       			modulo_seleccionado(response.id,response.link)
       		}else
       		{
       			$('#modulos_sis').html(response.html);
       		}
       } 
     }
   });
  }

  function modulo_seleccionado(modulo,link)
  {
	  	$.ajax({
	     data:  {modulo_sistema:modulo},
	     url:   '../controlador/loginC.php?modulos_sistema_selected=true',
	     type:  'post',
	     dataType: 'json',
	       success:  function (response) { 
	       	console.log(response);
	       	if(response==-2)
	       	{	

	       		 Swal.fire({
				      title: 'Su Licencia esta vencida',
				      text: "Cominiquese con su canal de servicios",
				      icon: 'warning',
				      showCancelButton: true,
				      confirmButtonColor: '#3085d6',
				      cancelButtonColor: '#d33',
				      confirmButtonText: 'Agregar licencia',
				      allowOutsideClick: false,
				    }).then((result) => {
				        if (result.value) {
				        	empresa_selecconada('<?php echo $_SESSION['INICIO']['ID_EMPRESA']; ?>',0,0)
				        	$('#myModal_modulos').modal('show');
				        }else
				        {
				        		cerrar_session();
				        }
				    })

	       	

	       		// Swal.fire("Su Licencia esta vencida","","error").then(function(){
	       		// 	cerrar_session();
	       		// })       	   
	      	}else if(response==1){
	      		location.href = 'inicio.php?mod='+modulo+'&acc='+link;         
	       	}
	     }
	   });
  }
</script>
<body>
	<!-- wrapper -->
	<div class="wrapper">
		<nav class="navbar navbar-expand-lg navbar-light bg-white rounded fixed-top rounded-0 shadow-sm">
			<div class="container-fluid">
				<a class="navbar-brand" href="#">
					<img src="<?php echo $_SESSION['INICIO']['LOGO']; ?>" style="width: 6%;" alt="" />
				</a>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent1" aria-controls="navbarSupportedContent1" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarSupportedContent1">
					
				</div>
				<div class="collapse navbar-collapse" id="navbarSupportedContent1">
					<ul class="navbar-nav ms-auto mb-2 mb-lg-0">
						<li class="nav-item"> <a href="#" class="nav-link active" onclick="cerrar_session()"><i class="bx bx-power-off me-1"></i>Salir</a>
						</li>
					</ul>
				</div>
			</div>

		</nav>
		<div class="error-404 d-flex align-items-center justify-content-center">
			<div class="container">
				<h1>Modulos disponibles</h1>
				<div class="row row-cols-1 row-cols-md-3 row-cols-xl-5" id="modulos_sis">					
					
				</div>
			</div>
		</div>
		<div class="bg-white p-3 fixed-bottom border-top shadow">
			<div class="d-flex align-items-center justify-content-between flex-wrap">
				<ul class="list-inline mb-0">
					<li class="list-inline-item">Follow Us :</li>
					<li class="list-inline-item"><a href="javascript:;"><i class='bx bxl-facebook me-1'></i>Facebook</a>
					</li>
					<li class="list-inline-item"><a href="javascript:;"><i class='bx bxl-twitter me-1'></i>Twitter</a>
					</li>
					<li class="list-inline-item"><a href="javascript:;"><i class='bx bxl-google me-1'></i>Google</a>
					</li>
				</ul>
				<p class="mb-0">Copyright © 2021. All right reserved.</p>
			</div>
		</div>
	</div>
	<!-- end wrapper -->
	<!-- Bootstrap JS -->
	<script src="../assets/js/bootstrap.bundle.min.js"></script>

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