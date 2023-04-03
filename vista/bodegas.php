<?php include('./header.php');include('../controlador/bodegasC.php'); ?>
<script type="text/javascript">
   $( document ).ready(function() {
   	bodegas();
   	bodegas_inactivos();
     //  // restriccion();
     // Lista_clientes();
     // Lista_procesos();

    });
   function bodegas()
   {
    $.ajax({
         // data:  {id,id},
         url:   '../controlador/bodegasC.php?lista=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) { 
           	// console.log(response);
           	$('#tbl_body').html(response);
          } 
          
       });
   }

    function bodegas_inactivos(query='')
      {
       $.ajax({
         data:  {query:query},
         url:   '../controlador/bodegasC.php?inactivo=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
           if (response!="") 
           {             
             $('#bodegas_ina').html(response);            
           } 
          } 
          
       });
    }

 function editar(id)
   {
   	var nom  = $('#txt_nombre_'+id).val();
   	var parametros = 
   	{
   		'id':id,
   		'nom':nom,
   	}
   	$.ajax({
         data:  {parametros,parametros},
         url:   '../controlador/bodegasC.php?editar=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) { 
           	if(response == 1)
           	{
                Swal.fire('','Registro editado.','success');
           		bodegas();
           		bodegas_inactivos();
           	}else
           	{           		
               Swal.fire('','UPs Algo salio mal.','error');
           	}
          } 
          
       });

   }

function eliminar(id)
   {
   	$.ajax({
         data:  {eliminar,eliminar},
         url:   '../controlador/bodegasC.php?eliminar=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) { 
           	if(response == 1)
           	{
           		bodegas();
           		bodegas_inactivos();
           	}
          } 
          
       });

   }

 function eliminar(id)
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
         url:   '../controlador/bodegasC.php?eliminar=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) { 
            if(response==1)
            {
               Swal.fire('', 'Bodega eliminado', 'success');
               bodegas();
               bodegas_inactivos();
            }else if(response == -2)
           {
            Swal.fire({
              title:'Esta bodega esta asignada aun producto y no se podra eliminar',
              text: "Desea inhabilitado a esta bodega?",
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
               Swal.fire('', 'No se pudo eliminar', 'info');
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
          url:   '../controlador/bodegasC.php?estado=true',
        type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) { 
            if (response==1) 
            {
              bodegas();
              bodegas_inactivos();
              Swal.fire('La Bodega  inhabilitado!', 'La bodega no podra ser seleccionado en el futuro', 'success');

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
         url:   '../controlador/bodegasC.php?activar=true',        
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) { 
            if (response==1) 
            {
            	bodegas();
            	bodegas_inactivos();
              Swal.fire('Bodega  habilitada!','', 'success');

            }else
            {
              Swal.fire('', 'UPs aparecio un problema', 'success');
            }          
           
          } 
          
       });

   }

   function add_categoria()
   {
   	var nombre = $('#txt_nombre').val();
   	if(nombre =='')
   	{
   		Swal.fire('', 'Llene el campo de nombre', 'info');
   		return false;
   	}
   	$.ajax({
         data:  {nombre:nombre}, 
         url:   '../controlador/bodegasC.php?add=true',        
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) { 
            if (response==1) 
            {
            	bodegas();
            	$('#txt_nombre').val('');
            	bodegas_inactivos();
              Swal.fire('La Categoria  se a Registrado!','', 'success');

            }else if(response ==-2)
            {
              Swal.fire('', 'El nombre de la categoria ya esta registrada', 'error');
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
            <h1 class="m-0 text-dark">BODEGAS</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <section class="content">
      <div class="container-fluid">
      	<div class="row">
      		<div class="col-sm-12">
      			
      		</div>
      	</div>
      	<div class="row">
      		<div class="col-md-12"><br>
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#bodegas" data-toggle="tab">BODEGAS</a></li>
                  <li class="nav-item"><a class="nav-link" href="#bodegas_ina" data-toggle="tab">BODEGAS INACTIVAS</a></li>
                  <!-- <li class="nav-item"><a class="nav-link" href="#pendientes" data-toggle="tab">Pendientes</a></li> -->
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane active" id="bodegas">
                  	<table class="table">
      			      <thead>
      			 	     <th>Nombre de bodegas</th>
      			 	     <th></th>
      			      </thead>      			 
      			 	     <tr>
      			 		     <td>
      			 			     <input type="text" name="txt_nombre" id="txt_nombre" class="form-control-sm form-control">
      			 		     </td>
      			 		     <td>
      			 			     <button class="btn btn-primary btn-sm" type="button" onclick="add_categoria()"><i class="fa fa-save"></i> Nuevo</button>
      			 		     </td>
      			 	     </tr>
      			      <tbody id="tbl_body">
      			      </tbody>
      			     </table>

                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="bodegas_ina">
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

<?php include('./footer.php'); ?>
