
<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="assets/images/favicon-32x32.png" type="image/png" />
	<!-- loader-->
	<link href="assets/css/pace.min.css" rel="stylesheet" />
	<script src="assets/js/pace.min.js"></script>
	<script src="assets/js/jquery.min.js"></script>
	
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
    if(email != '' )
    {
       var parametros = 
       {
         'email':email,
       } 
       $.ajax({
         data:  {parametros:parametros},
         url:   'controlador/loginC.php?reseteo=true',
         type:  'post',
         dataType: 'json',
         beforeSend: function () {   
              // var spiner = '<div class="text-center"><img src="img/de_sistema/loader_puce.gif" width="20px" height="20px"></div>'     
            $('#img_logo').attr('src','img/de_sistema/loader_puce.gif');
         },
           success:  function (response) {    
           if(response.respuesta==1)
	        {
	          Swal.fire('Reseteado','se envio un correo a '+response.mensaje+' con su nueva contraseña','success').then(function(){
	            location.href = 'login.php';
	          })
	        }else
	        {
	          $('#mensaje').text(response.mensaje)
	        } 

            $('#img_logo').attr('src','img/de_sistema/puce_logo.png');
         }
       });

    }else
    {
       Swal.fire( '','LLene todo los campos.','error');
    }
  }
  </script>
</head>

<body class="bg-forgot">
	<!-- wrapper -->
	<div class="wrapper">
		<div class="authentication-forgot d-flex align-items-center justify-content-center">
			<div class="card forgot-box">
				<div class="card-body">
					<div class="p-4 rounded  border">
						<div class="text-center">
							<img src="assets/images/icons/forgot-2.png" width="120" alt="" />
						</div>
						<h4 class="mt-5 font-weight-bold">Olvido su Password?</h4>
						<p class="text-muted">Ingrese  su email registrado para resetear la password</p>
						<div class="my-4">
							<label class="form-label">Email</label>
							<!-- <input type="text" class="form-control form-control-lg" placeholder="example@user.com" /> -->
							 <input type="email" class="form-control form-control-lg" id="email" placeholder="example@user.com">
						</div>
						 <div class="text-center" id="mensaje" style="color:red;"></div>      
						<div class="d-grid gap-2">
							<button type="button" class="btn btn-primary btn-lg" onclick="consultar_datos()" >Enviar</button> <a href="login.php" class="btn btn-light btn-lg"><i class='bx bx-arrow-back me-1'></i>Regresa a Login</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end wrapper -->
</body>

</html>