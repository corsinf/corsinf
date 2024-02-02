<?php //include ('../cabeceras/header.php');?>
<script type="text/javascript">
$( document ).ready(function() {
    var id = '<?php echo $_SESSION['INICIO']['ID_USUARIO'];?>';
   	Editar(id);

    $("#subir_imagen").on('click', function() {

       var fileInput = $('#file_img').val();  
       var id = $('#txt_id').val();
      if(id=='')
      {
        Swal.fire('','Asegurese de llenar los datos primero','warning');
        return false;
      }
      if(fileInput=='')
      {
        Swal.fire('','Seleccione una imagen','warning');
        return false;
      }


        var formData = new FormData(document.getElementById("form_img"));
         $.ajax({
            url: '../controlador/usuariosC.php?cargar_imagen_no_concurente=true',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            dataType:'json',
         // beforeSend: function () {
         //        $("#foto_alumno").attr('src',"../img/gif/proce.gif");
         //     },
            success: function(response) {
               if(response==-1)
               {
                 Swal.fire(
                  '',
                  'Algo extraño a pasado intente mas tarde.',
                  'error')

               }else if(response ==-2)
               {
                  Swal.fire(
                  '',
                  'Asegurese que el archivo subido sea una imagen.',
                  'error')
               }else
               {
                  location.reload();
               } 
            }
        });
    });
    // --------------------------

    
});

 function Editar(id)
  {
     // $('#nuevo_tipo_usuario').modal('show');
     // $('#btn_opcion').text('Editar');
     // $('#exampleModalLongTitle').text('Editar tipo de usuario');
     var noconcurente = '<?php echo $_SESSION['INICIO']['NO_CONCURENTE'];?>';
     var parametros = 
  	{
  		'id':id,
  		'query':'',
  	}
    $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/usuariosC.php?datos_usuarios=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {            

            // console.log(response);
           $('#lbl_nombre_usuario').text(response[0].nombre+' '+response[0].apellido)
           $('#txt_nombre').val(response[0].nombre);
           $('#txt_apellido').val(response[0].apellido);
           $('#txt_ci').val(response[0].ci);
           $('#name_img').val(response[0].ci);
           $('#txt_telefono').val(response[0].telefono);
  	       $('#txt_emial').val(response[0].email);
  	       $('#txt_emial_2').val(response[0].email);
  	       // $('#ddl_tipo_usuario').append($('<option>',{value: response[0].idt, text:response[0].tipo,selected: true }));;
  	       $('#txt_nick').val(response[0].nick);
  	       $('#txt_pass').val(response[0].pass);
  	       var passlen = response[0].pass.length;
  	       $('#pass').text('*'.repeat(passlen));

  	       $('#txt_dir').val(response[0].direccion);
           $('#txt_id').val(response[0].id);           
           if(response[0].foto!='' && response[0].foto!=null )
           {
              $('#img_foto').attr('src',response[0].foto);
           }
           $('#txt_link_web').text(response[0].web);
           $('#txt_link_tw').text(response[0].tw);
           $('#txt_link_in').text(response[0].ins);           
           $('#txt_link_fb').text(response[0].fb);

            if(noconcurente!='')
            {
              $('#panel_apellido').css('display','none');
            }
           
          } 
          
       });

   }
	
   function pass()
   {
   	 var pa =document.getElementById("txt_pass");
   	 if(pa.type == 'password')
   	 {
   	 	pa.type = 'text';
   	 }else
   	 {
      $('#eye').css('addClass','')
   	 	pa.type = 'password';
   	 }
   }


   function confirmar(tipo)
   {
   	 Swal.fire({
      title: 'Quiere guardar nuevas credenciales?',
      text: "Si guarda las crecenciales tendra que iniciar session nuevamente!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) {
        	if(tipo=='E')
        	{
        		guardar_email();
        	}else
        	{
        		guardar_pass();
        	}
        }
    });
   }

   function guardar_datos_personales()
   {
    var id = '<?php echo $_SESSION['INICIO']['ID_USUARIO'];?>';
    var noconcurente = '<?php echo $_SESSION['INICIO']['NO_CONCURENTE'];?>';
    if(noconcurente!='')
    {
      var id = noconcurente;
    }
   	
    var parametros = 
   	{
   		'nombre':$('#txt_nombre').val(),
   		'apellido':$('#txt_apellido').val(),
   		'ci':$('#txt_ci').val(),
   		'telefono':$('#txt_telefono').val(),
   		'email':$('#txt_emial').val(),
   		'direccion':$('#txt_dir').val(),
   		'id':id,
   	}
   	 $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/usuariosC.php?guardar_perfil=true',
         type:  'post',
         dataType: 'json',         
           success:  function (response) {  
           	if(response==1)
           	{
           		Swal.fire('Datos guardados','','success');
           	}
           }
       })


   }
   function guardar_email()
   {
   	 var email = $('#txt_emial_2').val();
	   	if(email!='')
	   	{

	   	}else
	   	{
	   		Swal.fire('Email incorrecto','debe llenar el campo de email','info');
	   	}

	var parametros = 
   	{
   		'email':email,
   		'id':'<?php echo $_SESSION['INICIO']['ID_USUARIO'];?>',
   	}
   	 $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/usuariosC.php?guardar_email=true',
         type:  'post',
         dataType: 'json',         
           success:  function (response) {  
           	if(response==1)
           	{
           		Swal.fire('Email Cambiado','','success').then(function(){
           		cerrar_session();
           		});
           	}
           }
       })

   }
   function guardar_pass()
   {
   	var pass_n= $('#txt_pass_n').val();
   	var pass_c= $('#txt_pass_c').val();
   	if(pass_c=='' || pass_n=='')
   	{
   		Swal.fire('Llene todos los campos','Nueva password o confirmacion vacia','info');
   		return false;
   	}
   	if(pass_c!=pass_n)
   	{
   		Swal.fire('Password no son iguales','Ingrese la misma password','error');
   		return false;
   	}
   	var parametros = 
   	{
   		'pass':pass_c,
   		'id':'<?php echo $_SESSION['INICIO']['ID_USUARIO'];?>',
   	}
   	 $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/usuariosC.php?guardar_pass=true',
         type:  'post',
         dataType: 'json',         
           success:  function (response) {  
           	if(response==1)
           	{
           		Swal.fire('Password Cambiada','','success').then(function(){
           		cerrar_session();
           		});
           	}
           }
       })

   }
</script>

<div class="page-wrapper">
      <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
          <div class="breadcrumb-title pe-3" _msthash="975975" _msttexthash="292578">Perfil de usuario</div>
          <div class="ps-3">
            <nav aria-label="miga de pan" _mstaria-label="133588">
              <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page" _msthash="1867034" _msttexthash="292578">Perfil de usuario</li>
              </ol>
            </nav>
          </div>          
        </div>

        <?php //print_r($_SESSION['INICIO']); ?>
        <!--end breadcrumb-->
        <div class="container">
          <div class="main-body">
            <div class="row">
              <div class="col-lg-4">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex flex-column align-items-center text-center">
                      <img src="<?php if($_SESSION['INICIO']['FOTO']!=''){ echo $_SESSION['INICIO']['FOTO'];}else{echo "../img/sin_imagen.jpg"; }  ?>" alt="Admin" class="rounded-circle p-1 bg-primary" width="110" height="110" id="img_foto">
                      <div class="mt-3">
                        <h4 id="lbl_nombre_usuario"><?php if($_SESSION['INICIO']['NO_CONCURENTE']==''){echo $_SESSION['INICIO']['USUARIO'];} ?></h4>
                        <p class="text-secondary mb-1"><?php echo $_SESSION['INICIO']['TIPO']; ?></p>
                        <!-- <p class="text-muted font-size-sm">Área de la Bahía, San Francisco, CA</p> -->
                        <form id="form_img">
                        <input type="file" name="file_img" _msthash="4232514" _msttexthash="78117" id="file_img">
                        <input type="hidden" name="name_img" _msthash="4232514" _msttexthash="78117" id="name_img">
                        <button type="button" class="btn btn-outline-primary" id="subir_imagen" _msthash="4232696" _msttexthash="92807">Subir</button>
                        </form>

                      </div>
                    </div>
                   <!-- <hr class="my-4">
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                        <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-globe me-2 icon-inline"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg><font _mstmutation="1">Website</font></h6><br>
                        <span class="text-secondary" id="txt_link_web"></span>
                      </li>                      
                      <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                        <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-twitter me-2 icon-inline text-info"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg><font _mstmutation="1">Twitter</font></h6> <br>
                        <span class="text-secondary" id="txt_link_tw"></span>
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                        <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-instagram me-2 icon-inline text-danger"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg><font _mstmutation="1">Instagram</font></h6>
                        <span class="text-secondary" id="txt_link_in"></span>
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                        <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-facebook me-2 icon-inline text-primary"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg><font _mstmutation="1">Facebook</font></h6>
                        <span class="text-secondary" id="txt_link_fb"></span>
                      </li>
                    </ul> -->
                  </div>
                </div>
              </div>
              <div class="col-lg-8">
                <div class="card">
                  <div class="card-body">
                    <div class="row mb-3">
                      <div class="col-sm-3">
                        <h6 class="mb-0" _msthash="3834077" _msttexthash="262691">Nombre</h6>
                      </div>
                      <div class="col-sm-9 text-secondary">
                        <input type="" name="txt_nombre" id="txt_nombre" class="form-control">
                      </div>
                    </div>
                    <div class="row mb-3" id="panel_apellido">
                      <div class="col-sm-3">
                        <h6 class="mb-0" _msthash="3834077" _msttexthash="262691">Apellido</h6>
                      </div>
                      <div class="col-sm-9 text-secondary">
                        <input type="" name="txt_apellido" id="txt_apellido" class="form-control">
                      </div>
                    </div>
                    <div class="row mb-3">
                      <div class="col-sm-3">
                        <h6 class="mb-0" _msthash="3834077" _msttexthash="262691">CI / RUC</h6>
                      </div>
                      <div class="col-sm-9 text-secondary">
                        <input type="" name="txt_ci" id="txt_ci" class="form-control form-control-sm">
                      </div>
                    </div>
                    <div class="row mb-3">
                      <div class="col-sm-3">
                        <h6 class="mb-0" _msthash="3834363" _msttexthash="385671">Correo electrónico</h6>
                      </div>
                      <div class="col-sm-9 text-secondary">
                        <input type="" name="txt_emial" id="txt_emial" class="form-control" readonly>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <?php if($_SESSION['INICIO']['NO_CONCURENTE']==""){ ?>
                        <div class="col-sm-3">
                          <h6 class="mb-0" _msthash="3834935" _msttexthash="75179">Password</h6>
                        </div>
                      <div class="col-sm-9 text-secondary">
                        <div class="input-group mb-3">
                           <input type="password" class="form-control form-control-sm" name="txt_pass" id="txt_pass" required="" readonly>
                            <button type="button" class="btn btn-info btn-flat" onclick="pass()"><i class="lni lni-eye" id="eye"></i></button>                          
                        </div>
                      </div>
                      <?php } ?>
                    </div>
                    <div class="row mb-3">
                      <div class="col-sm-3">
                        <h6 class="mb-0" _msthash="3834649" _msttexthash="131768">Teléfono</h6>
                      </div>
                      <div class="col-sm-9 text-secondary">
                        <input type="" name="txt_telefono" id="txt_telefono" class="form-control form-control-sm">  
                      </div>
                    </div>                    
                    <div class="row mb-3">
                      <div class="col-sm-3">
                        <h6 class="mb-0" _msthash="3835221" _msttexthash="156598">Dirección</h6>
                      </div>
                      <div class="col-sm-9 text-secondary">
                        <textarea rows="3" class="form-control" style="resize:none;" id="txt_dir"></textarea>
                      </div>
                    </div>

                      <?php if($_SESSION['INICIO']['NO_CONCURENTE']==""){ ?>
                    <div class="row">
                      <div class="col-sm-3"></div>
                      <div class="col-sm-9 text-secondary">
                        <input type="button" class="btn btn-primary px-4" onclick="guardar_datos_personales()" value="Guardar cambios" _mstvalue="256646">
                      </div>
                    </div>
                  <?php } ?>
                  </div>
                </div>

                <?php if($_SESSION['INICIO']['NO_CONCURENTE']==""){ ?>
                <div class="card">
                  <div class="card-body">
                    <div class="row mb-3">
                      <div class="col-sm-3">
                        <h6 class="mb-0">Usuario</h6>
                      </div>
                      <div class="col-sm-7 text-secondary">
                        <input type="" name="txt_emial_2" id="txt_emial_2" class="form-control form-control-sm">
                      </div>
                      <div class="col-sm-2">
                         <input type="button" class="btn btn-primary btn-sm" onclick="confirmar('E')" value="Guardar">
                      </div>
                    </div>       
                    <div class="row mb-3">
                      <div class="col-sm-3"><br>
                        <h6 class="mb-0">Password</h6>
                      </div>
                      <div class="col-sm-3 text-secondary">
                        <b>Nueva pasword</b>
                        <input type="password" name="txt_pass_n" id="txt_pass_n" class="form-control form-control-sm">
                      </div>
                      <div class="col-sm-4 text-secondary">                        
                        <b>Confirmar password</b>
                        <input type="password" name="txt_pass_c" id="txt_pass_c" class="form-control form-control-sm">
                      </div>
                      <div class="col-sm-2"><br>
                         <input type="button" class="btn btn-primary btn-sm" onclick="confirmar('P')" value="Guardar">
                      </div>
                    </div>                   
                  </div>
                </div>
              <?php } ?>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

<?php //include ('../cabeceras/footer.php');?>
