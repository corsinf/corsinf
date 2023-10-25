<?php include ('../cabeceras/header3.php'); //print_r($_SESSION['INICIO']);die(); ?>
<script type="text/javascript">
 $( document ).ready(function() {
  // restriccion();    
	consultar_datos()
});

function consultar_datos()
  {           
   $.ajax({
     // data:  {parametros:parametros},
     url:   '../controlador/loginC.php?modulos_sistema=true',
     type:  'post',
     dataType: 'json',
       success:  function (response) {    
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
       	   
      	location.href = 'inicio.php?mod='+modulo+'&acc='+link;         
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
					<img src="../img/de_sistema/logo_puce.png" width="140" alt="" />
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
</body>

</html>