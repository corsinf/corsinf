<?php include('./header.php'); ?>
<script type="text/javascript">
	 $( document ).ready(function() {
     //  // restriccion();
     // Lista_clientes();
     // Lista_procesos();

    });


</script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Ficha de clientes</h1>
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
      						<input type="text" class="form-control" placeholder="Nombre de cliente" id="txt_query">
      					</div>
      				</div>
              <div class="form-group">
                Por calificacion
                <div class="input-group">
                 <select class="form-control" id="ddl_calificacion" >
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
                 <select class="form-control" id="ddl_procesos">
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
