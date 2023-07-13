<?php include('../../cabeceras/header.php'); $id=''; ?>
<script type="text/javascript">
   $( document ).ready(function() {
     autocoplet_tipo();
     autocoplet_arti();
    var id = '<?php if(isset($_GET["usuario"])){ $id = $_GET["usuario"]; echo $_GET["usuario"];} ?>';
   	console.log(id);
   	if(id!='')
   	{
   		Editar(id)
   	}


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
            url: '../../controlador/usuariosC.php?cargar_imagen=true',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            dataType:'json',
         // beforeSend: function () {
         //        $("#foto_alumno").attr('src',"../../img/gif/proce.gif");
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
                $('#file_img').val('');  
                var id = '<?php echo $id; ?>';
                Editar(id);        
               } 
            }
        });
    });
    // --------------------------

    });

   	function autocoplet_tipo(){
      $('#ddl_tipo_usuario').select2({
        placeholder: 'Seleccione una tipo de usuario',
        ajax: {
          url:   '../../controlador/usuariosC.php?tipo=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });


      document.onkeydown = checkKey;   
  }

  function Editar(id)
  {
     // $('#nuevo_tipo_usuario').modal('show');
     // $('#btn_opcion').text('Editar');
     // $('#exampleModalLongTitle').text('Editar tipo de usuario');
     var parametros = 
  	{
  		'id':id,
  		'query':'',
  	}
    $.ajax({
         data:  {parametros:parametros},
         url:   '../../controlador/usuariosC.php?datos_usuarios=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) { 
           console.log(response);
           // if(response[0].maestro ==1)
           // {
           //   $('#rbl_si').prop('checked',true);
           //   $('#rbl_no').prop('checked',false);
           // }else
           // {

           //   $('#rbl_no').prop('checked',true);
           //   $('#rbl_si').prop('checked',false);
           // }
           $('#txt_nombre').val(response[0].nombres);
           $('#txt_ci').val(response[0].ci);
           $('#txt_telefono').val(response[0].tel);
  	       $('#txt_emial').val(response[0].email);
  	       $('#ddl_tipo_usuario').append($('<option>',{value: response[0].idt, text:response[0].tipo,selected: true }));;
  	       $('#txt_apellido').val(response[0].ape);
  	       $('#txt_pass').val(response[0].pass);
  	       $('#txt_dir').val(response[0].dir);
           $('#txt_usuario_update').val(response[0].id);
           $('#img_foto').attr('src','../'+response[0].foto+'?'+Math.random());
           // link

           $('#txt_link_web').val(response[0].web);
           $('#txt_link_tw').val(response[0].tw);
           $('#txt_link_in').val(response[0].ins);           
           $('#txt_link_fb').val(response[0].fb);
           
          } 
          
       });

   }

  function Eliminar()
  {
    var id = $('#txt_usuario_update').val();
     Swal.fire({
      title: 'Quiere eliminar este registro?',
      text: "Esta seguro de eliminar este registro!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) {

    $.ajax({
         data:  {id:id},
         url:   '../../controlador/usuariosC.php?eliminar_tipo=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) { 
           if(response==1)
           {
            Swal.fire('','Registro eliminado.','success').then(function(){

              window.location.href = 'usuarios.php';
            });
           } else if(response == -2)
           {
            Swal.fire({
              title: 'El Usuario esta ligado a uno o varios registros y no se podra eliminar.?',
              text: "Desea inhabilitado a este usuario?",
              showDenyButton: true,
              showCancelButton: true,
              confirmButtonText:'Si!',
            }).then((result) => {
                if (result.isConfirmed) {
                  inhabilitar_usuario(id);
                  }
                })
             // Swal.fire('','El Usuario esta ligado a uno o varios registros y no se podra eliminar.','error')
           }else
           {
            Swal.fire('','No se pudo elimnar.','info')
           }
          } 
          
       });}
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
   	 	pa.type = 'password';
   	 }
   }

  function add_usuario()
  {

  	var nom = $('#txt_nombre').val();
  	var ci = $('#txt_ci').val();
  	var tel = $('#txt_telefono').val();
  	var ema = $('#txt_emial').val();
  	var tip = $('#ddl_tipo_usuario').val();
  	var nic = $('#txt_apellido').val();
  	var pas = $('#txt_pass').val();
  	var dir = $('#txt_dir').val();

     // link
    var web = $('#txt_link_web').val();
    var tw = $('#txt_link_tw').val();
    var ins = $('#txt_link_in').val();
    var fb = $('#txt_link_fb').val();






    var id = $('#txt_usuario_update').val();
    if(tip=='' || nom=='' || ci=='' || tel=='' || ema==''  || pas=='' || nic=='')
    {
      Swal.fire('','Asegurese de llenar todo los campos.','info')
      return false;
    }

    var datos = $('#form_usuario_new').serialize();
    datos = datos+'&web='+web+'&tw='+tw+'&ins='+ins+'&fb='+fb
    $.ajax({
         data:  datos,
         url:   '../../controlador/usuariosC.php?guardar_usuario=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
           // if (response==1) 
           // {
            // $('#nuevo_tipo_usuario').modal('hide');
            // lista_usuario();
            // lista_usuario_ina();

            if(id!='')
            {
              Swal.fire(
                  '',
                  'Registro Editado.',
                  'success');
            // limpiar();
            }else{
            Swal.fire(
                  '',
                  'Registro agregado.',
                  'success').then(function(){
                    location.href='detalle_usuario.php?usuario='+response;
                  });
          }
            // limpiar();
            // $('#btn_opcion').text('Guardar');
            // $('#exampleModalLongTitle').text('Nuevo tipo de usuario');
           // }else
           // {

           //  // $('#nuevo_tipo_usuario').modal('hide');
           //  Swal.fire(
           //        '',
           //        'No se pudo guardar intente mas tarde.',
           //        'info');

           //  // limpiar();
           //  // $('#btn_opcion').text('Guardar');
           // } 
          } 
          
       });

  }
function checkKey(e) {

    e = e || window.event;

    if (e.keyCode == '38') {
        // up arrow
    }
    else if (e.keyCode == '40') {
        // down arrow
    }
    else if (e.keyCode == '37') {
      $('#btn_izquierda').click();
    }
    else if (e.keyCode == '39') {
      $('#btn_derecha').click();
    }

}
 function autocoplet_arti(){
      $('#ddl_usuario').select2({
        placeholder: 'Buscar cliente',
        width:'90%',
        ajax: {
          url:  '../../controlador/usuariosC.php?lista_usuarios_ddl2=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
   
  }

  function cargar_busqueda(id)
  {
    window.location.href = 'detalle_usuario.php?usuario='+id;
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
        <hr>        
        <!--end breadcrumb-->
        <div class="container">
          <div class="main-body">
            <div class="row">
              <div class="col-lg-4">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex flex-column align-items-center text-center">

                      <form enctype="multipart/form-data" id="form_img" method="post" style="width: inherit;">
                        <input type="hidden" name="txt_id" id="txt_id" value="<?php echo $id;?>" class="form-control"> 
                          <div class="widget-user-image text-center">
                            <img  class="rounded-circle p-1 bg-primary" src="../img/sin_imagen.jpg" alt="User Avatar" width="110" height="110" id="img_foto">
                         </div><br>
                          <input type="file" name="file_img" id="file_img" class="form-control form-control-sm">
                          <input type="hidden" name="txt_nom_img" id="txt_nom_img">
                          <button class="btn btn-outline-primary btn" id="subir_imagen" type="button">Cargar imagen</button>
                      </form>     
                    </div>
                    <hr class="my-2">
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                        <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-globe me-2 icon-inline"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg><font _mstmutation="1">Website</font></h6>
                        <input type="text" class="form-control form-control-sm" name="txt_link_web" id="txt_link_web">
                      </li>                      
                      <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                        <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-twitter me-2 icon-inline text-info"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg><font _mstmutation="1">Twitter</font></h6>
                       <input type="text" class="form-control form-control-sm" name="txt_link_tw" id="txt_link_tw">
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                        <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-instagram me-2 icon-inline text-danger"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg><font _mstmutation="1">Instagram</font></h6>
                       <input type="text" class="form-control form-control-sm" name="txt_link_in" id="txt_link_in">
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                        <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-facebook me-2 icon-inline text-primary"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg><font _mstmutation="1">Facebook</font></h6>
                        <input type="text" class="form-control form-control-sm" name="txt_link_fb" id="txt_link_fb">
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-lg-8">
                <div class="card">
                  <div class="card-body">
                    <div class="row mb-3">
                     <div class="toolbar toolbar-top" role="toolbar" style="text-align: right;">
                        <button type="button" class="btn btn-primary btn-sm" onclick="add_usuario();" id="btn_opcion">Guardar</button>
                        <button type="button" class="btn btn-danger btn-sm" onclick="Eliminar()">Eliminar</button>
                      </div>
                    </div>
                    <form id="form_usuario_new">
                    <div class="row mb-3">
                      <div class="col-sm-3">
                        <h6 class="mb-0">Nombre</h6>
                      </div>
                      <div class="col-sm-9 text-secondary">
                        <input type="hidden" name="txt_usuario_update" id="txt_usuario_update">
                        <input type="text"  class="form-control form-control-sm" name="txt_nombre" id="txt_nombre" required="">
                      </div>
                    </div>
                    <div class="row mb-3">
                      <div class="col-sm-3">
                        <h6 class="mb-0">Apellido</h6>
                      </div>
                      <div class="col-sm-9 text-secondary">
                        <input type="text"  class="form-control form-control-sm" name="txt_apellido" id="txt_apellido" required="">
                      </div>
                    </div>
                    <div class="row mb-3">
                      <div class="col-sm-3">
                        <h6 class="mb-0">CI / RUC</h6>
                      </div>
                      <div class="col-sm-9 text-secondary">
                         <input type="text"  class="form-control form-control-sm" name="txt_ci" id="txt_ci" required="" onblur="validar_cedula('txt_ci','U')" onkeyup=" solo_numeros('txt_ci');num_caracteres('txt_ci',10)">
                      </div>
                    </div>
                    <div class="row mb-3">
                      <div class="col-sm-3">
                        <h6 class="mb-0">Correo electrónico</h6>
                      </div>
                      <div class="col-sm-9 text-secondary">
                      <input type="text"  class="form-control form-control-sm" name="txt_emial" id="txt_emial" required="">
                      </div>
                    </div>
                    <div class="row mb-3">
                      <div class="col-sm-3">
                        <h6 class="mb-0">Password</h6>
                      </div>
                      <div class="col-sm-4 text-secondary">
                        <div class="input-group mb-3">
                           <input type="password" class="form-control form-control-sm" name="txt_pass" id="txt_pass" required="">
                            <button type="button" class="btn btn-info btn-flat btn-sm" onclick="pass()"><i class="lni lni-eye" id="eye"></i></button>                          
                        </div>
                      </div>
                      <div class="col-sm-2">
                        <h6 class="mb-0">Perfil</h6>
                      </div>
                      <div class="col-sm-3 text-secondary">
                        <div class="input-group mb-4">
                          <select class="form-control form-control-sm" name="ddl_tipo_usuario" id="ddl_tipo_usuario" required="">
                            <option value="">Seleccione tipo de usuario</option>
                          </select>                                    
                        </div>
                      </div>

                    </div>
                    <div class="row mb-3">
                      <div class="col-sm-3">
                        <h6 class="mb-0">Teléfono</h6>
                      </div>
                      <div class="col-sm-9 text-secondary">
                        <input type="text"  class="form-control form-control-sm" name="txt_telefono" id="txt_telefono" required="" onkeyup=" solo_numeros('txt_telefono');num_caracteres('txt_telefono',10)">
                      </div>
                    </div>                    
                    <div class="row mb-3">
                      <div class="col-sm-3">
                        <h6 class="mb-0" _msthash="3835221" _msttexthash="156598">Dirección</h6>
                      </div>
                      <div class="col-sm-9 text-secondary">
                        <textarea style="resize:none;" class="form-control" id="txt_dir" name="txt_dir" required=""></textarea>
                      </div>
                    </div> 
                   </form>                   
                  </div>
                </div>                
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

<?php  include('../../cabeceras/footer.php'); ?>
