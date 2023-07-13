<?php include('./header.php');  
include('../controlador/clientesC.php');?>
<script type="text/javascript">
   $( document ).ready(function() {
     clientes();
     clientes_inactivos()

    });


   function search()
   {
   	var query = $('#txt_query').val();
   	if($('#txt_tipo').val() == 'C')
   	{
   		clientes(query);

   	}else
   	{
   		clientes_inactivos(query);
   	}

   }

  function clientes(query='')
  {
       $.ajax({
         data:  {query:query},
         url:   '../controlador/clientesC.php?clientes=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
           if (response!="") 
           {             
             $('#clientes').html(response);            
           } 
          } 
          
       });
    }

    function clientes_inactivos(query='')
      {
       $.ajax({
         data:  {query:query},
         url:   '../controlador/clientesC.php?clientes_inactivo=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
           if (response!="") 
           {             
             $('#proveedores').html(response);            
           } 
          } 
          
       });
    }

  function new_usuario()
  {

    if($('#txt_nombre_new').val()=='' || $('#txt_ci_new').val()=='' || $('#txt_telefono').val()=='' || $('#txt_emial').val()=='' || $('#txt_dir').val()=='')
    {
        Swal.fire('','Llene todo los campos.','info');
    	return false;
    }

     var datos = $('#form_usuario_new').serialize();
    $.ajax({
         data:  datos,
         url:   '../controlador/clientesC.php?new_usuario=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) { 
            if (response==1) 
            {
               Swal.fire('','Nuevo cliente registrado.','success');
               $('#nuevo_cliente').modal('hide');
               limpiar();
               clientes();
               clientes_inactivos();
            }else
            {
              Swal.fire('', 'UPs aparecio un problema', 'success');
              $('#nuevo_cliente').modal('hide');
            }          
           
          } 
          
       });
  }
  function limpiar()
  {
     $('#txt_nombre_new').val('');
     $('#txt_ci_new').val('');
     $('#txt_dir').val('');
     $('#txt_emial').val('');
     $('#txt_telefono').val('');
     $('#txt_credito').val('');
     $('#txt_id').val('');
  }

  function Editar(id)
  {
     $('#txt_id').val(id);
    $.ajax({
         data:  {id,id},
         url:   '../controlador/clientesC.php?ficha_usuario=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) { 
             $('#txt_nombre_new').val(response.nombre);
             $('#txt_ci_new').val(response.ci);
             $('#txt_dir').val(response.direccion);
             $('#txt_emial').val(response.email);
             $('#txt_telefono').val(response.telefono);
             $('#txt_credito').val(response.credito);
            $('#nuevo_cliente').modal('show');
              // console.log(response);
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
         data:  {id,id},
         url:   '../controlador/clientesC.php?delete_usuario=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) { 
            if(response==1)
            {
               Swal.fire('', 'Cliente eliminado', 'success');
               clientes();
            }else if(response == -2)
           {
            Swal.fire({
              title:'Este cliente tiene Facturas asociadas y no se podrta eliminar',
              text: "Desea inhabilitado a este cliente?",
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
               Swal.fire('', 'Eno se pudo eliminar', 'info');
            }
          } 
          
       });
      }
      });

   }

    function inhabilitar_usuario(id)
   {
    $.ajax({
         data:  {id:id},
         url:   '../controlador/clientesC.php?cliente_estado=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) { 
            if (response==1) 
            {
              clientes();
              clientes_inactivos();
              Swal.fire('El cliente  se a inhabilitado!', 'El cliente no podra ser seleccionado en futuras compras o ventas', 'success');

            }else
            {
              Swal.fire('', 'UPs aparecio un problema', 'success');
            }          
           
          } 
          
       });

   }

  function Activar(id)
   {
    $.ajax({
         data:  {id:id},
         url:   '../controlador/clientesC.php?cliente_activar=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) { 
            if (response==1) 
            {
              clientes();
              clientes_inactivos();
              Swal.fire('El cliente  se a habilitado!','', 'success');

            }else
            {
              Swal.fire('', 'UPs aparecio un problema', 'success');
            }          
           
          } 
          
       });

   }



</script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">CLIENTES</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <section class="content">
      <div class="container-fluid">
      	<div class="row">
      		<button class="btn btn-success btn-sm"  data-toggle="modal" data-target="#nuevo_cliente"><i class="fa fa-plus"></i> Nuevo</button>
      		
      	</div>
      	<div class="row">
      		<div class="col-sm-6"><br>
      			<b>Clientes</b>
      			<input type="text" name="txt_query" id="txt_query" class="form-control form-control-sm" placeholder="Buscar nombre cliente" onkeyup="search()">
      		</div>
      	</div>
      	<div class="row">
      		<input type="hidden" name="txt_tipo" id="txt_tipo" value="C">
      		<div class="col-md-12"><br>
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item" onclick="$('#txt_tipo').val('C')"><a class="nav-link active" href="#clientes" data-toggle="tab">CLIENTES</a></li>
                  <li class="nav-item" onclick="$('#txt_tipo').val('P')"><a class="nav-link" href="#proveedores" data-toggle="tab"> CLIENTES INACTIVOS</a></li>
                  <!-- <li class="nav-item"><a class="nav-link" href="#pendientes" data-toggle="tab">Pendientes</a></li> -->
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane active" id="clientes">

                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="proveedores">
                  </div>

                 <!--  <div class="tab-pane" id="pendientes">
                    
                  </div> -->
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
          </div>
      		
      	</div>

                   

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>




<div class="modal fade" id="nuevo_cliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Nuevo cliente</h5>
      </div>
      <div class="modal-body">
        <form id="form_usuario_new">
        <div class="row">
          <input type="hidden" name="txt_id" id="txt_id">
          <div class="col-sm-12">
            <b>NOMBRE</b>
            <input type="text" name="txt_nombre_new" id="txt_nombre_new" class="form-control-sm form-control">          
          </div>
           <div class="col-sm-6">
            <b>CI / RUC  </b>          
            <input type="text"  class="form-control form-control-sm" name="txt_ci_new" id="txt_ci_new" required="" onblur="validar_cedula('txt_ci_new','CP')" onkeyup=" solo_numeros('txt_ci_new');num_caracteres('txt_ci_new',10)">
          </div>
          <div class="col-sm-6">
            <b>TELEFONO</b>
            <input type="text"  class="form-control form-control-sm" name="txt_telefono" id="txt_telefono" required="" onkeyup=" solo_numeros('txt_telefono');num_caracteres('txt_telefono',10)">
          </div>
          <div class="col-sm-12">
            <b>EMAIL   </b>         
            <input type="text"  class="form-control form-control-sm" name="txt_emial" id="txt_emial" required="">
            <b>DIRECCION</b>
            <textarea style="resize:none;" class="form-control" id="txt_dir" name="txt_dir" required=""></textarea>
          </div>
          <div class="col-sm-4">
            <b>MONTO DE CREDITO</b>    
            <input type="text"  class="form-control form-control-sm" name="txt_credito" id="txt_credito" required="" value="0">            
          </div>
        </div>
        </form>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="new_usuario();" id="btn_opcion"> Guardar</button>
          <!-- <button type="button" class="btn btn-primary" onclick="new_usuario();" id="btn_opcion">Guardar y continuar</button> -->
          <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Cerrar</button>
    </div>
  </div>
</div>
</div>


<?php include('./footer.php'); ?>
