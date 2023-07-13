<?php include('./header.php'); ?>
<script type="text/javascript">
	$( document ).ready(function() {
		 var id= '<?php if(isset($_GET["proyect"])){ echo $_GET['proyect'];}?>';
  	autocmpletar();
  	buscar()
  	buscar_proyecto(id);
  });
	
 function autocmpletar(){
      $('#ddl_articulos').select2({
        placeholder: 'Seleccione una custodio',
        ajax: {
          url: '../controlador/articulosC.php?articulos_ddl=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
      });
  }
  function insertar()
  {
  	var parametros = 
  	{
  		'id':'<?php if(isset($_GET["proyect"])){ echo $_GET['proyect'];}?>',
  		'pro': $('#ddl_articulos').val(),
  	}
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/proyectosC.php?insertar_conte=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) {  
        	if(response==1)
        	{
        		buscar();

        	}else{
               Swal.fire(
      'oops!',
      'No se pudo agregar.',
      'error'
    )
            }
       }
    });

  }

    function insertar_proyecto()
  {
  	var parametros = 
  	{
  		'id':'<?php if(isset($_GET["proyect"])){ echo $_GET['proyect'];}?>',
  		'pro': $('#txt_nom').val(),
  		'fec': $('#txt_fecha').val(),
  		'enc': $('#txt_encargado').val(),
  	}
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/proyectosC.php?insertar=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) {  
        	if(response==1)
        	{
        		
           Swal.fire(
      'Editado!',
      'Registro Editado.',
      'success'
    )
        		buscar_proyecto('');

        	}else{
               Swal.fire(
      'oops!',
      'No se pudo agregar.',
      'error'
    )
            }
       }
    });

  }
    function buscar_proyecto(buscar)
  {
     var proyectos='';

    $.ajax({
      data:  {buscar:buscar},
      url:   '../controlador/proyectosC.php?buscar=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) {    
        // console.log(response);   
         console.log(response);   
         $('#txt_nom').val(response[0].nom);
         $('#txt_fecha').val( response[0].fecha.date.slice(0,10));
         $('#txt_encargado').val(response[0].enca);
      }
     
    });
  }

  function buscar()
  {
     var id= '<?php if(isset($_GET["proyect"])){ echo $_GET['proyect'];}?>';
     var proyectos='';

    $.ajax({
      data:  {id:id},
      url:   '../controlador/proyectosC.php?buscar_contenido=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) {    
        // console.log(response);   
        $.each(response, function(i, item){
          console.log(item);
        proyectos+='<tr><td>'+item.id+'</td><td>'+item.nom+'</td><td><button class="btn btn-danger" tittle="Eliminar" onclick="delete_datos(\''+item.id+'\')"><i class="fa fa-trash"></i></button></td></tr>';
        });      
        $('#tbl_datos').html(proyectos);        
      }
    });
  }
    function delete_datos(id)
  {
    Swal.fire({
  title: 'Eliminar Registro?',
  text: "Esta seguro de eliminar este registro?",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Si'
}).then((result) => {
  if (result.value) {
    eliminar(id);    
  }
})

  }
    function eliminar(id)
  {
     $.ajax({
      data:  {id:id},
      url:   '../controlador/proyectosC.php?eliminar_conte=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) {  
        if(response == 1)
        {
         Swal.fire(
      'Eliminado!',
      'Registro Eliminado.',
      'success'
    )
          buscar();
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
            <h1 class="m-0 text-dark">Contenido de proyecto</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <section class="content">
      <div class="container-fluid">
      	   <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Datos del proyecto</h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-3">
                  	<b>Nombre del proyecto</b>
                    <input type="text" id="txt_nom" class="form-control" placeholder="Nombre del proyecto">
                  </div>
                  <div class="col-3">
                  	<b>Fecha</b>
                    <input type="date" id="txt_fecha" class="form-control" placeholder="">
                  </div>
                  <div class="col-3">
                  	<b>Encargado</b>
                    <input type="text" id="txt_encargado" class="form-control" placeholder="Encargado">
                  </div>
                  <div class="col-3"><br>
                  	<button class="btn btn-primary" tittle="Editar" onclick="insertar_proyecto()"><i class="fa fa-save"></i></button>
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
             <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Contenido del proyecto</h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-9">
                  	<b>Articulo</b>
                    <select class="form-control" id="ddl_articulos" name="ddl_articulos"></select>
                  </div>                 
                  <div class="col-3"><br>
                  	<button class="btn btn-primary" tittle="Editar" onclick="insertar()"><i class="fa fa-save"></i></button>
                  </div>
                </div>
                <div class="row">
                	 <div class="table-responsive">
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Articulo</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody id="tbl_datos">
               
                          </tbody>
                        </table>
                      </div>            
                	
                </div>
              </div>
              <!-- /.card-body -->
            </div>    

                   

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

<?php include('./footer.php'); ?>
