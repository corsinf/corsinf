<?php include('./header.php'); include('../controlador/usuariosC.php');?>
<script type="text/javascript">
	$( document ).ready(function() {
    lista_usuario();
    lista_usuario_ina();
    autocoplet_tipo();
  });

	function autocoplet_tipo(){
      $('#ddl_tipo_usuario').select2({
        placeholder: 'Seleccione una tipo de usuario',
        width:'90%',
        ajax: {
          url:   '../controlador/usuariosC.php?tipo=true',
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

  function lista_usuario(parametros=false)
  {
  	if(parametros==false)
  	{
  	 var parametros = 
  	 {
  		'id':'',
  		'query':'',
    	}
   }
    $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/usuariosC.php?lista_usuarios=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
            console.log(response);
           // if (response) 
           // {
            $('#tbl_datos').html(response);
            // $('#tbl_usuarios').html(response);
           // } 
          } 
          
       });
  }

  function lista_usuario_ina(parametros=false)
  {
  	if(parametros==false)
  	{
  	 var parametros = 
  	 {
  		'id':'',
  		'query':'',
    	}
   }
    $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/usuariosC.php?lista_usuarios_ina=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
           if (response) 
           {
            $('#tbl_usuarios_ina').html(response);
           } 
          } 
          
       });
  }

  function buscar_usuario()
  {
  	var parametros = 
  	{
  		'id':'',
  		'query':$('#txt_query').val(),
  	}
  	lista_usuario(parametros);
  }

  function add_usuario()
  {
  	var nom = $('#txt_nombre').val();
  	var ci = $('#txt_ci').val();
  	var tel = $('#txt_telefono').val();
  	var ema = $('#txt_emial').val();
  	var tip = $('#ddl_tipo_usuario').val();
  	var nic = $('#txt_nick').val();
  	var pas = $('#txt_pass').val();
  	var dir = $('#txt_dir').val();

    var id = $('#txt_usuario_update').val();
    if(tip=='' || nom=='' || ci=='' || tel=='' || ema=='' || nic=='' || pas=='')
    {
      Swal.fire('','Asegurese de llenar todo los campos.','info')
      return false;
    }

    var datos = $('#form_usuario_new').serialize();
    $.ajax({
         data:  datos,
         url:   '../controlador/usuariosC.php?guardar_usuario=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
           if (response==1) 
           {
            $('#nuevo_tipo_usuario').modal('hide');
            lista_usuario();
            lista_usuario_ina();

            if(id!='')
            {
              Swal.fire(
                  '',
                  'Registro Editado.',
                  'success');
            limpiar();
            }else{
            Swal.fire(
                  '',
                  'Registro agregado.',
                  'success');
          }
            limpiar();
            $('#btn_opcion').text('Guardar');
            $('#exampleModalLongTitle').text('Nuevo tipo de usuario');
           }else
           {

            $('#nuevo_tipo_usuario').modal('hide');
            Swal.fire(
                  '',
                  'No se pudo guardar intente mas tarde.',
                  'info');

            limpiar();
            $('#btn_opcion').text('Guardar');
           } 
          } 
          
       });

  }
  function limpiar()
  {
  	$('#txt_nombre').val('');
    $('#txt_ci').val('');
  	$('#txt_telefono').val('');
  	$('#txt_emial').val('');
  	$('#ddl_tipo_usuario').empty();
  	$('#txt_nick').val('');
  	$('#txt_pass').val('');
  	$('#txt_dir').val('');
    $('#txt_usuario_update').val('');

  }

  function Editar(id)
  {
     $('#nuevo_tipo_usuario').modal('show');
     $('#btn_opcion').text('Editar');
     $('#exampleModalLongTitle').text('Editar tipo de usuario');
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
           $('#txt_nombre').val(response[0].nom);
           $('#txt_ci').val(response[0].ci);
           $('#txt_telefono').val(response[0].tel);
  	       $('#txt_emial').val(response[0].email);
  	       $('#ddl_tipo_usuario').append($('<option>',{value: response[0].idt, text:response[0].tipo,selected: true }));;
  	       $('#txt_nick').val(response[0].nick);
  	       $('#txt_pass').val(response[0].pass);
  	       $('#txt_dir').val(response[0].dir);
           $('#txt_usuario_update').val(response[0].id);
           
          } 
          
       });

   }
  function Eliminar(id)
  {
     Swal.fire({
      title: 'Quiere eliminar este registro?',
      text: "Esta seguro de eliminar este registro!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) {

    $.ajax({
         data:  {id:id},
         url:   '../controlador/usuariosC.php?eliminar_tipo=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) { 
           if(response==1)
           {
            Swal.fire('','Registro eliminado.','success');
            lista_usuario();
            lista_usuario_ina();
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

   function Habilitar(id)
  {
     Swal.fire({
      title: 'Quiere habilitar este registro?',
      text: "Esta seguro de habilitar este registro!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) {
        	habilitar_usuario(id);
        }
      });
   }

   function inhabilitar_usuario(id)
   {
    $.ajax({
         data:  {id:id},
         url:   '../controlador/usuariosC.php?usuario_estado=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) { 
           	if (response==1) 
           	{
            lista_usuario();
            lista_usuario_ina();
           		Swal.fire('El usuario  se a inhabilitado!', 'El usuario no podra ingresar al sistema', 'success');

           	}else
           	{
           		Swal.fire('', 'UPs aparecio un problema', 'success');
           	}          
           
          } 
          
       });

   }
   function habilitar_usuario(id)
   {
    $.ajax({
         data:  {id:id},
         url:   '../controlador/usuariosC.php?usuario_estado_=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) { 
           	if (response==1) 
           	{
            Swal.fire('','Registro habilitado.','success');
            lista_usuario();
            lista_usuario_ina();
           	}else
           	{
           		Swal.fire('', 'UPs aparecio un problema', 'success');
           	}          
           
          } 
          
       });

   }


</script>

<div class="page-wrapper">
      <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
          <div class="breadcrumb-title pe-3">Administracion</div>
          <div class="ps-3">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Usuarios</li>
              </ol>
            </nav>
          </div>         
        </div>
        <!--end breadcrumb-->
        <hr>
        <div class="card">
          <div class="card-body">
              <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap5">
                <div class="row">
                  <div class="col-sm-12 col-md-6">
                    <div class="dt-buttons btn-group"> 
                      <a class="btn btn-outline-secondary buttons-copy buttons-html5 " href="detalle_usuario.php">Nuevo</a>     
                      <!-- <button class="btn btn-outline-secondary buttons-copy buttons-html5" tabindex="0" aria-controls="example2" type="button"><span>Copy</span></button>  -->
                      
                    </div>
                  </div>
                  <div class="col-sm-12 col-md-6">
                    <div class="row mb-3 dataTables_filter">
                      <div class="col-sm-3">
                        <h6 class="mb-0">Buscar</h6>
                      </div>
                      <div class="col-sm-9 text-secondary">
                        <input type="text" name="" id="txt_query" onkeyup="buscar_usuario()" class="form-control form-control-sm" placeholder="Buscar usuario por CI o Nombre">
                      </div>
                    </div>                   
                  </div>
                </div>
              </div>
          </div>
        </div>

        <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-4" id="tbl_datos">

        </div>
        <!--end row-->
      </div>
    </div>
<?php include('./footer.php'); ?>
