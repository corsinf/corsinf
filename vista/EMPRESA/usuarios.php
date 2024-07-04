<?php 
$activeRep = 0;

//include('../cabeceras/header.php');
?>
<script type="text/javascript">
   activeDirectory_ac();
	$( document ).ready(function() {
    lista_usuario();
    //lista_usuario_ina();
    autocoplet_tipo();
  });

	function autocoplet_tipo(){
      $('#ddl_tipo_usuario').select2({
        placeholder: 'Seleccione una tipo de usuario',
        dropdownParent: $('#myModal_tipo_usuario'),
        width:'100%',
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
              var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
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
              var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
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
              var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
           if (response==1) 
           {
            $('#nuevo_tipo_usuario').modal('hide');
            lista_usuario();
            //lista_usuario_ina();

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
              var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
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
              var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) { 
           if(response==1)
           {
            Swal.fire('','Registro eliminado.','success');
            lista_usuario();
            //lista_usuario_ina();
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
              var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) { 
           	if (response==1) 
           	{
            lista_usuario();
            //lista_usuario_ina();
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
              var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) { 
           	if (response==1) 
           	{
            Swal.fire('','Registro habilitado.','success');
            lista_usuario();
            //lista_usuario_ina();
           	}else
           	{
           		Swal.fire('', 'UPs aparecio un problema', 'success');
           	}          
           
          } 
          
       });
   }

   function abrir_modal()
   {
    activeDirectory();
      $('#myModal_active').modal('show');
   }

   function activeDirectory()
   {
      $.ajax({
      // data:  {id:id},
      url:   '../controlador/ACTIVEDIR/activedirectoryC.php?usuarios_directory=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response);
          $('#accordionExample').html(response);           
        }             
      });
   }

   function activeDirectory_ac()
   {
      $.ajax({
      // data:  {id:id},
      url:   '../controlador/ACTIVEDIR/activedirectoryC.php?repositoy_active=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response);
        if(response==1)
          {
            $('#btn_active').css('display','initial');
          }        
        }             
      });
   }

   function calcular_usu(Fromgrupo)
   {
      grupo = Fromgrupo.replace('form_',"");
      var checkboxes = document.querySelectorAll('#'+Fromgrupo+' input[type="checkbox"]');
      var checkboxes_checkeados = [];
      var contador = 0;
      checkboxes.forEach(function(checkbox) {
          if (checkbox.checked) {
            contador+=1; 
              checkboxes_checkeados.push(checkbox.value);
          }
      });
      if(contador==0)
      {
        $('#lbl_cant_usu_'+grupo).text('Todos');
      }else
      {
        $('#lbl_cant_usu_'+grupo).text(contador);        
      }
      console.log(checkboxes_checkeados);
      console.log(contador);

   }

   function modal_tipo_usu(Fromgrupo)
   {
      $('#myModal_tipo_usuario').modal('show');
      $('#id_form').val(Fromgrupo);
      buscar_claves_correo(Fromgrupo)
   }

   function buscar_claves_correo(Fromgrupo)
   {
     Fromgrupo = $('#id_form').val();
      grupo = Fromgrupo.replace('form_',"");
      var checkboxes = document.querySelectorAll('#'+Fromgrupo+' input[type="checkbox"]');
      var checkboxes_checkeados = [];
      var contador = 0;
      checkboxes.forEach(function(checkbox) {
          if (checkbox.checked) {
              checkboxes_checkeados.push(checkbox.value);
              contador+1;
          }
      });

      $.ajax({
      data:  {parametros:checkboxes_checkeados},
      url:   '../controlador/ACTIVEDIR/activedirectoryC.php?buscar_claves_correo=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
         $('#tbl_body_claves').html(response)
       }       
      });
   }

   function Asignar_usuarios()
   {
      if($('#ddl_tipo_usuario').val()=='')
      {
        Swal.fire("","Seleccione un tipo de usuario","info");
        return false;
      }
      Fromgrupo = $('#id_form').val();
      grupo = Fromgrupo.replace('form_',"");
      var checkboxes = document.querySelectorAll('#'+Fromgrupo+' input[type="checkbox"]');
      var checkboxes_checkeados = [];
      var contador = 0;
      checkboxes.forEach(function(checkbox) {
          if (checkbox.checked) {
              checkboxes_checkeados.push(checkbox.value);
              contador+1;
          }
      });


      var checkboxes = document.querySelectorAll('#form_claves input[type="password"]');
      checkboxes.forEach(function(checkbox) {
        // console.log(checkbox)
          if (checkbox.value=='') {
            Swal.fire("","Ingrese todas las claves",'error')
            return false;
          }
      });

    datos = $('#form_claves').serialize();

     var parametros = {
        'usuarios':datos,
        'tipo':$('#ddl_tipo_usuario').val(),
        'grupo':grupo,
     }
      $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/ACTIVEDIR/activedirectoryC.php?Asignar_usuarios=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 

         if(response.resp==1)
         {
            Swal.fire("","Usuario Asignado","success").then(function(){
             $('#myModal_tipo_usuario').modal('hide');   
              activeDirectory();    
          })
         }else if(response.resp==2)
         {
           Swal.fire("",response.msg,"info").then(function(){
             $('#myModal_tipo_usuario').modal('hide');   
            })
         }
       }       
      });

      // console.log(checkboxes_checkeados);
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
            <div class="row card-body">
              <div class="col-sm-12 col-md-6">
                  <div class="dt-buttons btn-group"> 
                    <a class="btn btn-outline-primary buttons-copy btn-sm" href="inicio.php?acc=detalle_usuario">Nuevo</a>     
                    <button class="btn btn-outline-primary buttons-copy btn-sm" style="display:none;"  id="btn_active" onclick="abrir_modal()" type="button">Nuevo desde Active Directory</button> 
                    <?php //print_r($_SESSION['INICIO']); ?>
                  </div>
                </div>
               <div class="col-sm-12 col-md-6">
                  <div class="row">
                    <div class="col-sm-3 text-end">
                      <h6 class="mb-0">Buscar</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <input type="text" name="" id="txt_query" onkeyup="buscar_usuario()" class="form-control form-control-sm" placeholder="Buscar usuario por CI o Nombre" autocomplete="off">
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

<div class="modal fade" id="myModal_active" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered  modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5>Active Diretory</h5>
        </div>
        <div class="modal-body">
            <div class="accordion" id="accordionExample">
             
            </div>
          </div> 
        <div class="modal-footer">        
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="myModal_tipo_usuario" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5>Tipo de usuario</h5>
        </div>
        <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                 <input type="hidden" id="id_form" name="id_fomr">
                  <select class="form-select" id="ddl_tipo_usuario" name="ddl_tipo_usuario[]" multiple="multiple">
                    <option value="">Seleccione el tipo de usuario</option>
                  </select>             
              </div>
              <div class="col-sm-12">
                <form id="form_claves">
                  <table class="table table-hover">
                     <thead>
                       <th>Correo</th>
                       <th>info</th>
                       <th>Clave</th>
                     </thead>
                     <tbody id="tbl_body_claves">
                       
                     </tbody>
                  </table>              
                </form>  
              </div>
               
            </div>
            
        </div> 
        <div class="modal-footer">        
          <button type="button" class="btn btn-primary btn-sm"  onclick="Asignar_usuarios()">Asignar</button>    
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button> 
        </div>
      </div>
    </div>
</div>

<script type="text/javascript">
   document.addEventListener('DOMContentLoaded', function () {
            var myModal = document.getElementById('myModal_tipo_usuario');

           myModal.addEventListener('shown.bs.modal', function () {
                var popoverTriggerList = [].slice.call(myModal.querySelectorAll('[data-bs-toggle="popover"]'));
                var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                    return new bootstrap.Popover(popoverTriggerEl);
                });
            });
        });

</script>


<?php //include('../cabeceras/footer.php'); ?>
