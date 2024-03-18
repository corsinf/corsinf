
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

  <link rel="stylesheet" href="./css/multi_step.css">
  
	<script type="text/javascript">	
// function consultar_datos()
//   {
//     // var email=$('#email').val();
//     // var pass =$('#pass').val();
//     // if(email != '' && pass !='')
//     // {
//     //    var parametros = 
//     //    {
//     //      'email':email,
//     //      'pass':pass,
//     //    } 
//        $.ajax({
//          // data:  {parametros:parametros},
//          url:   'controlador/nueva_empresaC.php?iniciar=true',
//          type:  'post',
//          dataType: 'json',
//          /*beforeSend: function () {   
//               var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
//             $('#tabla_').html(spiner);
//          },*/
//            success:  function (response) {    
//            if (response==-2) 
//            {
//               Swal.fire( '','Email no registrado.','error');

//            }else if(response == -1)
//            {
//               Swal.fire( '','No tiene accesos.','info');

//            }else if(response == 1)
//            {
//              window.location.href = "vista/modulos_sistema.php";
//            }        
//          }
//        });

//     // }else
//     // {
//     //    Swal.fire( '','LLene todo los campos.','error');
//     // }
//   }
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

    var formData = new FormData($('#msform')[0]);
    
    // Puedes agregar más datos al formData, por ejemplo:
    // formData.append('otroDato', 'Valor adicional');

    console.log(formData);

    $.ajax({
        data:  formData,
        url:   'controlador/nueva_empresaC.php?Guardar_empresa=true',
        type: 'POST',
        contentType: false,
        processData: false,
        success: function(response) {
           if(response==-2)
           {
           	 Swal.fire("La empresa ya esta registrada","","Error");
           }else if(response==1)
           {
           	 Swal.fire("Empresa guardada Revise correo para ingresar","","info").then(function(){
           	 	location.href = "./login.php";
           	 });           	
           }
        },
        error: function(error) {
            console.error('Error al enviar datos:', error);
            // Puedes manejar los errores aquí
        }
    });
}


 // function Guardar_empresa()
 // {
 // 	// datos = $('#msform').serialize();
 	 
 // 	 var datos  = new FormData($('#msform')[0]);

 // 	 $.ajax({
 //         data:  datos,
 //         url:   'controlador/nueva_empresaC.php?Guardar_empresa=true',
 //         type:  'post',
 //         dataType: 'json',
 //         contentType: false,
 //         processData: false,
 //         /*beforeSend: function () {   
 //              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
 //            $('#tabla_').html(spiner);
 //         },*/
 //           success:  function (response) {    
 //           if (response==-2) 
 //           {
 //              Swal.fire( '','Email no registrado.','error');

 //           }else if(response == -1)
 //           {
 //              Swal.fire( '','No tiene accesos.','info');

 //           }else if(response == 1)
 //           {
 //             window.location.href = "vista/modulos_sistema.php";
 //           }        
 //         }
 //       });
 // }
</script>
</head>

<body class="bg-login">

	<div class="d-flex align-items-center justify-content-center my-5">
			<div class="container">
				<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-2">
					<div class="col mx-auto">
						<div class="card mt-5">
							<div class="card-body">
								<div class="border p-4 rounded">
									<div class="text-center">
										<h3 class="">Nueva empresa</h3>										
									</div>
									<div class="form-body">
										<div class="">
                <form id="msform">
                    <!-- progressbar -->
                    <ul id="progressbar" style="margin-bottom: 0px" class="text-center">
                        <li class="active"  id="account"><strong>Datos Empresa</strong></li>
                        <li id="personal"><strong>Base de datos</strong></li>
                        <li id="payment"><strong>Envios Email (SMTP)</strong></li>
                    </ul>
                    <br> <!-- fieldsets -->
                    <fieldset>
                        <div class="form-card"> 
                        	<div class="row">
                        		<div class="col-sm-6">
                        			<img class="img-profile rounded-circle" src="./img/de_sistema/sin-logo.jpg" alt="User Avatar" id="img_foto" name="img_foto" style="width: 96%;">
									<input type="file"  name="txt_logo" id="txt_logo" placeholder="Jhon">
								</div> 
								<div class="col-sm-6">
									<label for="inputFirstName" class="form-label">Razon Social</label>
									<input type="text" class="form-control" name="txt_empresa" id="txt_empresa" placeholder="Jhon">
									<label for="inputFirstName" class="form-label">Nombre Comercial</label>
									<input type="text" class="form-control" name="txt_empresa" id="txt_empresa" placeholder="Jhon">
									<label for="inputLastName" class="form-label">Ruc / CI </label>
									<input type="text" class="form-control"  name="txt_ci" id="txt_ci" placeholder="Deo">
								</div>

                        	</div>
                            <div class="row">
								<div class="col-7">
									<label for="inputEmailAddress" class="form-label">Email</label>
									<input type="text" class="form-control" name="txt_email" id="txt_email" placeholder="example@user.com">
								</div>
								<div class="col-5">
									<label for="inputEmailAddress" class="form-label">Telefono</label>
									<input type="text" class="form-control" name="txt_telefono" id="txt_telefono" placeholder="0999999999">
								</div>
								<div class="col-12">
									<label for="inputEmailAddress" class="form-label">Direccion</label>
									<textarea  name="txt_direccion" id="txt_direccion" class="form-control-sm form-control"></textarea>
								</div>
                            </div>
                        </div><br><br> 
                        <button  type="button" class="btn btn-primary next">Siguiente</button>
                    </fieldset>
                    <fieldset>
                        <div class="form-card">
                        	<div class="row">
                            <div class="col-sm-6"><label class="fieldlabels">Tipo de base: *</label> 
                            	<input type="text" class="form-control" id="txt_tipo_base" name="txt_tipo_base" placeholder="First Name" /> 
                          	</div>
                            <div class="col-sm-6"><label class="fieldlabels">IP / Host: *</label> 
                            	<input type="text" class="form-control" id="txt_ip" name="txt_ip" placeholder="Last Name" />
                          	</div>
                            <div class="col-sm-6"><label class="fieldlabels">Puerto: *</label> 
                            	<input type="text" class="form-control" id="txt_puerto" name="txt_puerto" placeholder="Alternate Contact No." /> 
                          	</div>
                            <div class="col-sm-6"><label class="fieldlabels">Usuario: *</label> 
                            	<input type="text" class="form-control" id="txt_usuario_db" name="txt_usuario_db" placeholder="Contact No." /> 
                          	</div>
                            <div class="col-sm-6"><label class="fieldlabels">Password: *</label> 
                            	<input type="text" class="form-control" id="txt_pass_db" name="txt_pass_db"  placeholder="Alternate Contact No." />
                          	</div>
                          	<div class="col-sm-6"><label class="fieldlabels">Base de datos: *</label> 
                            	<input type="text" class="form-control" id="txt_base" name="txt_base"  placeholder="Alternate Contact No." />
                          	</div>
                          </div>
                        </div><br><br>                         
                        <button  type="button" class="btn btn-primary next2">Siguiente</button>
                    </fieldset>
                    <fieldset>
                        <div class="form-card">
                        	<div class="row">
                        		<div class="col-sm-12 text-end">
                        			<label class="fieldlabels">SMTP por Default:</label> 
                        			<br>
		                           	<label><input type="radio" id="rbl_smtp_si" name="rbl_smtp_default" value="si" onclick="smtp_config()"  /> SI</label>
		                           	<label><input type="radio" id="rbl_smtp_no" name="rbl_smtp_default" value="no" checked  onclick="smtp_config()" /> NO</label>
		                        </div>                        		
                        	</div>
                             <div class="row">
		                            <div class="col-sm-6"><label class="fieldlabels">SMTP Host:*</label> 
		                            	<input type="text" class="form-control" id="txt_host" name="txt_host" placeholder="First Name" /> 
		                          	</div>
		                            <div class="col-sm-6"><label class="fieldlabels">SMTP Puerto: *</label> 
		                            	<input type="text" class="form-control" id="txt_puerto_smtp" name="txt_puerto_smtp" placeholder="Last Name" />
		                          	</div>
		                            <div class="col-sm-6"><label class="fieldlabels">SMTP Secure: *</label> 
		                            	<br>
		                            	<label> <input type="radio" id="txt_ssl" name="rbl_secure" value="ssl" checked  />ssl</label> 
		                            	<label> <input type="radio" id="txt_tls" name="rbl_secure" value="tls"  />tls</label> 
		                          	</div>
		                            <div class="col-sm-6"><label class="fieldlabels">SMTP Usuario: *</label> 
		                            	<input type="text" class="form-control" id="txt_usuario_smtp" name="txt_usuario_smtp" placeholder="Contact No." /> 
		                          	</div>
		                            <div class="col-sm-6"><label class="fieldlabels">SMTP Password: *</label> 
		                            	<input type="text" class="form-control" id="txt_pass_smtp" name="txt_pass_smtp"  placeholder="Alternate Contact No." />
		                          	</div>
		                          </div>
                        </div> <br><br>                        
                        <button  type="button" class="btn btn-primary" onclick="Guardar_empresa()">Finalizar</button>
                    </fieldset>
                   
                </form>
            </div>
									</div>								
									
									<!--  -->
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
	<script  src="./js/multi_step.js"></script>
</body>

</html>