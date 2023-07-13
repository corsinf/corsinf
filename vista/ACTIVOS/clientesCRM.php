<?php include('./header.php'); ?>
<script type="text/javascript">
	 $( document ).ready(function() {
      // restriccion();
     Lista_clientes();
     Lista_procesos();

    });

	function Lista_clientes()
	{
		var parametros = 
		{
			'porusu':false,
      'query':$('#txt_query').val(),
      'cali':$('#ddl_calificacion').val(),
      'proce':$('#ddl_procesos').val(),
		}
    var star0 = '<i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>';

    var star1='<i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="fa fa-star"></i>';

    var star2='<i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>';

    var star3 = '<i class="far fa-star"></i><i class="far fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>';

    var star4 = '<i class="far fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>'

    var star5 = '<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>';
		var tr = '';    
       $.ajax({
          data:  {parametros:parametros},
         url:   '../controlador/clientesC.php?clientesCRMC=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
           if (response!="") 
           {
             $.each(response, function(i,item){
              var col_tr = 'default';
              var fe = '';
              if(item.contactado != null)
              {
                col_tr = 'success';
                fe= item.contactado;
              }

             	tr+='<tr class="table-'+col_tr+'" onclick="ficha_cliente('+item.id+')"><td style="display:none">'+item.id+'</td><td>'+item.nombre+'</td><td>categoria</td><td>'+item.dir+'</td><td>Provincia</td><td>quito</td><td><span class="right badge badge-'+item.color+'">'+item.proceso+'</span></td><td>';
                if(item.nivel ==0)
                {
                  tr+=star0;
                }else if(item.nivel == 1)
                {
                  tr+=star1;
                }else if(item.nivel == 2)
                {
                  tr+=star2;
                }else if(item.nivel == 3)
                {
                  tr+=star3;
                }else if(item.nivel == 4)
                {
                  tr+=star4;
                }else if(item.nivel == 5)
                {
                  tr+=star5;
                }

              tr+='</td><td>'+fe+'</td><td><button class="btn btn-sm" title="Nuevo contacto" data-toggle="modal" data-target="#myModal"><i class="fa fa-user-plus"></i></button></td></tr>';

               
             });
             $('#clientes').html(tr);            
           }else
           {
             $('#clientes').html('<tr><td colspan="8">No se a encontrado datos</td></tr>');  
           } 
          } 
          
       });
    }

    function Lista_procesos()
  {
    var option = '<option value ="">Seleccione proceso</option>';    
       $.ajax({
         // data:  {parametros:parametros},
         url:   '../controlador/procesosC.php?procesos=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
           if (response!="") 
           {
             $.each(response, function(i,item){
              option+='<option class="right badge badge-'+item.color+'" value="'+item.cod+'">'+item.detalle+'</option>';
             });
             $('#ddl_procesos').html(option);            
           } 
          } 
          
       });
    }
    function ficha_cliente(item)
    {
      window.location.href = "ficha_cliente.php?cliente="+item;
      // alert(item);

    }
</script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Lista de clientes</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <section class="content">
      <div class="container-fluid">
      	<div class="card card-default">
      		<div class="card-body">
      			<div class="row">
      				<div class="form-group">
      					<button class="btn btn-success"><i class="nav-icon fas fa-plus"></i> Nuevo</button>
      				</div>
      				<div class="form-group">
      					<button class="btn btn-default"><i class="fa fa-file-pdf"></i> Imprimir</button>
      				</div>      				     				
      			</div>
      			<div class="row">
      				<div class="form-group">
                Busqueda por cliente
      					<div class="input-group">
      						<div class="input-group-prepend">
      							<span class="input-group-text"><i class="fa fa-search"></i></span>
      						</div>
      						<input type="text" class="form-control" placeholder="Nombre de cliente" id="txt_query" onkeyup ="Lista_clientes()" onblur="Lista_clientes()">
      					</div>
      				</div>
              <div class="form-group">
                Por calificacion
                <div class="input-group">
                 <select class="form-control" id="ddl_calificacion" onchange="Lista_clientes()">
                   <option value="">Seleccione calificacion</option>
                   <option class="far" value="-1">0 estrellas &#xf005;&#xf005;&#xf005;&#xf005;&#xf005;</option>
                   <option class="fa" value="5">5 estrellas &#xf005;&#xf005;&#xf005;&#xf005;&#xf005;</option>
                   <option class="fa" value="4">4 estrellas &#xf005;&#xf005;&#xf005;&#xf005;</option>
                   <option class="fa" value="3">3 estrellas &#xf005;&#xf005;&#xf005;</option>
                   <option class="fa" value="2">2 estrellas &#xf005;&#xf005;</option>
                   <option class="fa" value="1">1 estrellas &#xf005;</option>
                 </select>
                  <!-- <input type="text" class="form-control" placeholder="Nombre de cliente"> -->
                </div>
              </div>
              <div class="form-group">
                Por proceso
                <div class="input-group">
                 <select class="form-control" id="ddl_procesos" onchange="Lista_clientes()">
                   <option value="">Seleccione Proceso</option>
                 </select>
                  <!-- <input type="text" class="form-control" placeholder="Nombre de cliente"> -->
                </div>
              </div>  

      			</div>
      			<div class="row">
      				<div class="table-responsive">
      					<table class="table table-hover">
      					    <thead>
      						    <th>Nombre</th>
      						    <th>Categoria</th>
      						    <th>direccion</th>
      						    <th>Provincia</th>
      						    <th>ciudad</th>
      						    <th>proceso</th>
                      <th>calificacion</th>
      						    <th></th>
      					    </thead>
      					    <tbody id="clientes">
      					    	<tr>
      					    		<td colspan="8">No se a encontrado datos</td>
      					    		<td>1</td>
      					    		<td>1</td>
      					    		<td>1</td>
      					    		<td>1</td>
      					    		<td>1</td>
                        <td>1</td>
      					    		<td><button class="btn btn-sm" title="Nuevo contacto" data-toggle="modal" data-target="#myModal"><i class="fa fa-user-plus"></i></button></td>
      					    	</tr>
      					    	
      					    </tbody>
      				    </table>
      				</div>      				
      			</div>      			
      		</div>
      	</div>

                   

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>


<?php include('./footer.php'); ?>
