<?php $id=''; $mod = $_GET['mod']; ?>
<script type="text/javascript">
	var modulo = '<?php echo $mod; ?>'
</script>
<script src="../js/FACTURACION/lista_productos.js"></script> 
<script type="text/javascript">
    $(document).ready(function () {
        var id = '<?php if(isset($_GET['id'])){ echo $_GET['id']; $id= $_GET['id']; };?>';
        lista_articulos_adicionales();
        categorias();
        if(id!='')
        {
        	detalle_articulo(id);
        	tamanio_lista(id);
        	adicionales_lista(id);
        	materia_prima(id);
        }
     });
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Productos</div>
            
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                           Detalle de articulo
                        </li>
                    </ol>
                </nav>
            </div>
           <!--  <div class="ms-auto">
            	<div class="btn-group">
		          <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
		            <button class="btn btn-sm btn-light bg-white dropdown-toggle" type="button" id="dropdownMenuDate2" data-bs-toggle="dropdown" aria-bs-haspopup="true" aria-bs-expanded="true">
		             <i class="mdi mdi-calendar"></i> Carga datos
		            </button>
		            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuDate2">
		              <a class="dropdown-item" href="#">Descargar formato en excel</a>
		              <a class="dropdown-item" href="#" onclick="carga_masiva(1)">Cargar excel</a>
		            </div>
		          </div>
            	</div>            	
            </div> -->

        </div>
        <div class="row">
        	<div class="col-sm-2">
        		<a href="inicio.php?mod=<?php echo $_GET['mod'] ?>&lista_articulos" class="btn btn-sm btn-default" style="border:1px solid;"><i class="bx bx-arrow-left"></i> Regresar</a>
        	</div>
        </div>
        <div class="row">
        	<div class="card">
				<div class="card-body">
					<ul class="nav nav-tabs nav-warning" role="tablist">
						<li class="nav-item" role="presentation">
							<a class="nav-link active" data-bs-toggle="tab" href="#warninghome" role="tab" aria-selected="false" tabindex="-1">
								<div class="d-flex align-items-center">
									<div class="tab-icon"><i class="bx bx-home font-18 me-1"></i>
									</div>
									<div class="tab-title">Detalle de producto</div>
								</div>
							</a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link" data-bs-toggle="tab" href="#warningprofile" role="tab" aria-selected="false" tabindex="-1">
								<div class="d-flex align-items-center">
									<div class="tab-icon"><i class="bx bx-user-pin font-18 me-1"></i>
									</div>
									<div class="tab-title">KIT / Recetas</div>
								</div>
							</a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link" data-bs-toggle="tab" href="#warningcontact" role="tab" aria-selected="true">
								<div class="d-flex align-items-center">
									<div class="tab-icon"><i class="bx bx-microphone font-18 me-1"></i>
									</div>
									<div class="tab-title">Adicionales</div>
								</div>
							</a>
						</li>
					</ul>
					<div class="tab-content py-3">
						<div class="tab-pane fade active show" id="warninghome" role="tabpanel">
		                	<div class="row">
			                	<div class="col-lg-12 col-sm-6  text-end">
			                		<button class="btn btn-primary btn-sm" onclick="add_edit()"><i class="bx bx-save"></i> Guardar</button>
			                		<?php if($id!=''){echo '<button class="btn btn-danger btn-sm" onclick="eliminar()"><i class="bx bx-trash"></i> Eliminar</button>' ;} ?>
			                	</div>  
		                	</div>
		                    <div class="row">
			                   	<div class="col-lg-4 col-sm-6">
			                   		 <form enctype="multipart/form-data" id="form_img" method="post" class="col-sm-12">
			                   		 	<input type="hidden" name="txt_id" id="txt_id">
				                   		<img src="../img/sistema/sin_imagen.jpg" style="border:1px solid; width: inherit;" id="img_articulo">
				                   		<br><br>
				                   		<input type="file" name="file_img" id="file_img" class="form-control form-control-sm">
				                   		<input type="hidden" name="txt_nom_img" id="txt_nom_img">
				                   		<button class="btn btn-primary btn-block" id="subir_imagen" type="button">Cargar imagen</button>
			                   		</form>                   		
			                   	</div>
		                   		<div class="col-lg-8 col-sm-6">              
			                     	<div class="row">
				                     	<div class=" col-lg-8 col-sm-12">
				                     		<b><code>*</code>Descripcion</b><br>
				                     		<input type="text" class="form-control form-control-sm" name="" id="txt_description">
				                     	</div>			                     	
				                     	<div class=" col-lg-4 col-sm-12">
				                     		<b>Tipo</b><br>
				                      		<label  class="form-control-sm"><input type="radio" name="opcT" id="opcp" value="P" checked> Producto</label>
				                      		<label  class="form-control-sm"><input type="radio" name="opcT" id="opcs" value="S"> Servicio</label>                 		
				                     	</div>
				                    </div>
			                     	<div class="row">
				                     	<div class="col-lg-8 col-sm-12">
				                     		<b>Descripcion 2</b><br>
				                     		<input type="text" class="form-control form-control-sm" name="" id="txt_description2">	
				                     	</div>
				                     	<div class="col-lg-4 col-sm-12">
				                           <b>Codigo Barras </b><br>
				                           <input type="text" class="form-control form-control-sm" name="" id="txt_barras">
				                        </div> 
				                    </div>
				                    <div class="row">
				                        <div class="col-lg-4 col-sm-12">
				                           <b><code>*</code>Referencia </b><br>
				                           <input type="text" class="form-control form-control-sm" name="" id="txt_asset">
				                        </div>
				                        <div class="col-lg-4 col-sm-12">
				                            <b>Tag RFID </b><br>
				                            <input type="text" class="form-control form-control-sm" name="" id="txt_rfid">
				                        </div>
				                        <div class="col-lg-4 col-sm-12">
				                            <b>Codigo Auxiliar </b><br>
				                            <input type="text" class="form-control form-control-sm" name="" id="txt_tag_anti">
				                        </div>                       
				                    </div>
			                     	<div class="row">
			                      		<div class=" col-lg-3 col-sm-6">
			                           		<b>Cantidad </b><br>
			                           		<input type="text" class="form-control form-control-sm" name="" id="txt_cant">
			                         	</div>
			                         	<div class=" col-lg-3 col-sm-6">
			                     			<b>Peso</b><br>
			                     			<input type="text" class="form-control form-control-sm" name="" id="txt_peso" value="0">
			                     		</div>  
			                         	<div class=" col-lg-3 col-sm-6">
			                         		<b>Unidad medida  </b><br>
			                         		<input type="text" class="form-control form-control-sm" name="" id="txt_unidad">
			                         	</div>
			                          	<div class=" col-lg-3 col-sm-6">
			                           		<b><code>*</code>Valor actual </b><br>
			                           		<input type="text" class="form-control form-control-sm" name="" id="txt_valor">
			                         	</div>	               
			                     	</div>
			                     	<div class="row">
			                     		<div class="col-lg-2 col-sm-6">
			                           		<b>Minimo </b><br>
			                           		<input type="text" class="form-control form-control-sm" name="" id="txt_min" value="0">
			                         	</div>  
				                        <div class="col-lg-2 col-sm-6">
				                           <b>Maximo </b><br>
				                           <input type="text" class="form-control form-control-sm" name="" id="txt_max" value="0">
				                        </div>  
			                          	<div class="col-lg-5 col-sm-12">
					                        <div class="form-group">
					                            <b><code>*</code>Categoria</b><br>
						                   		<div class="d-flex input-group">
						                            <select class="form-select flex-grow-1" id="ddl_categoria">
						                              <option>Seleccione Categoria</option>
						                            </select>
						                            <div class="input-group-append">
							                            <button class="btn btn-secondary btn-sm" type="button" title="Nuevo Articulos" onclick="$('#nueva_categoria').modal('show');"><i class="bx bx-plus"></i></button>
							                        </div>
						                        </div>
      										</div>
			                          	</div>
			                          	<div class=" col-lg-3 col-sm-12">
			                           		<b>Lleva iva </b><br>
			                           		<label class="form-control-sm"><input type="radio" name="opc" id="opcsi" value="si"> Si</label>
			                           		<label class="form-control-sm"><input type="radio" name="opc" id="opcno" value="no" checked> No</label>
			                        	</div> 
			                     	</div>								                     
				                    <div class="row">
				                     	<div class="col-lg-6 col-sm-12">
				                           	<b><code>*</code>Localizacion</b><br>
				                            <select class="form-select form-control-sm" id="ddl_localizacion" style="width: 100%;">
				                              <option>Seleccione Custodio</option>
				                            </select>
				                        </div>    
				                        <div class="col-lg-3 col-sm-12">
				                        	<b>Modelo </b><br>
				                         	<input type="text" class="form-control form-control-sm" name="" id="txt_modelo">
				                        </div>
				                        <div class="col-lg-3 col-sm-12">
				                         	<b>Serie </b><br>
				                         	<input type="text" class="form-control form-control-sm" name="" id="txt_serie">
				                        </div>         
				                    </div>
			                     	<div class="row">
			                     	 	<div class="col-lg-4 col-sm-6">
			                           		<b>Fecha de Ingreso </b><br>
			                           		<input type="date" class="form-control form-control-sm" name="" id="txt_fecha" readonly>
			                         	</div>  
				                        <div class="col-lg-4 col-sm-6">
				                     		<b>Controlar inventario</b><br>
				                      		<label  class="form-control-sm"><input type="radio" name="opcInv" id="opcInv1" value="1"> Si</label>
				                      		<label  class="form-control-sm"><input type="radio" name="opcInv" id="opcInv0" value="0" checked> No</label>
				                     	</div>
				                        <div class="col-lg-4 col-sm-12">
				                            <b><code>*</code>Marca</b><br>
				                            <select class="form-select form-control-sm" id="ddl_marca" style="width: 100%;">
				                               <option>Selecciones</option>
				                            </select>
				                        </div>
			                          	<div class="col-lg-4 col-sm-12">
			                           		<b><code>*</code>Genero</b> <br>
				                           	<select class="form-select form-control-sm" id="ddl_genero" style="width: 100%;">
				                             	<option>Selecciones</option>
				                           	</select>
				                        </div>  
				                        <div class="col-lg-4 col-sm-12">
				                            <b><code>*</code>Estado</b> <br>
				                            <select class="form-control-sm form-select" id="ddl_estado" style="width: 100%;">
				                              <option>Selecciones</option>
				                            </select>
				                        </div>
				                        <div class="col-lg-4 col-sm-12">
				                            <b><code>*</code>Color </b><br>
				                            <select class="form-select form-control-sm" id="ddl_color" style="width: 100%;">
				                              <option>seleccione</option>
				                            </select>
				                        </div>  
			                     	</div>
				                    <div class="row">
				                      	<div class="col-sm-12">
				                         	<b>Caracteristica </b><br>
				                         	<input type="text" class="form-control form-control-sm" name="" id="txt_carac">
				                        </div>                                                     
				                    </div>	
					            </div>                 	
		                   </div>
		                </div>
						<div class="tab-pane fade" id="warningprofile" role="tabpanel">
							<div class="row">
						 		<div class="col-sm-12">				 			
							  		<h1 class="h3 mb-4" id="">Agregar Materia prima</h1>
						 		</div>
						 	</div>	  		
							<div class="row">
								<div class="col-lg-9 col-sm-12">
							  		<div class="row">
							  			<div class="col-sm-6">
									  		<label class="mb-0"><b>Materia prima</b></label>
									  		<select class="form-select" id="ddl_materia" name="ddl_materia" onchange="" style="width: 100%;">
									  			<option value="">Seleccione materia prima</option>
									  		</select>					  				
									  	</div>
							  			<div class="col-sm-2">
								            <b>cantidad</b>
									  		<input type="" name="txt_cant_materia" id="txt_cant_materia" class="form-control form-control-sm" value="0">
									  	</div>
									  	<div class="col-sm-2">								                  
								            <b>Peso(kg)</b>
									  		<input type="" name="txt_peso_materia" id="txt_peso_materia" class="form-control form-control-sm" value="0">
									  	</div>	
									  	<div class="col-sm-2"><br>
									  		<button class="btn btn-primary btn-sm" onclick="materia_prima_add()"><i class="fa fa-arrow-down"></i> Agregar</button>
									  	</div>					  					
							  		</div>
							  	</div>
							  	<div class="col-lg-9 col-sm-12">
							  		<div class="row">
							  			<table class="table table-hover">
							  				<thead>
							  					<th>Materia prima</th>
							  					<th>Canti</th>
							  					<th>Peso(Kg)</th>
							  					<th></th>
							  				</thead>
							  				<tbody id="tbl_materia">
							  					<tr>
							  						<td></td>
							  						<td></td>
							  						<td></td>
							  						<td></td>
							  					</tr>
							  				</tbody>					  						
							  			</table>
							  		</div>
							  	</div>
							</div>
						</div>
						<div class="tab-pane fade" id="warningcontact" role="tabpanel">
							<div class="row">
					  			<div class=" col-lg-8 col-sm-12 mb-2">
					  				<div class="row">
					  					<div class="col-sm-8 text-left">
					  						<b>Producto Adicional</b>
					  						<select class="form-select" id="ddl_producto_add" name="ddl_producto_add">
					  							<option>Seleccione producto</option>
					  						</select>
					  					</div>
					  					<div class="col-sm-4">
					  						<br>
					  						<button class="btn btn-sm btn-primary" onclick="adicionales_add()"><i class="fa fa-plus"></i> Agregar</button>
					  					</div>		  					
					  				</div>
					  			</div>
					  			<div class="col-lg-8 col-sm-12">
					  				<table class="table table-hover">
					  					<thead>
					  						<th>Producto</th>
					  						<th></th>
					  					</thead>
					  					<tbody id="tbl_adicional">
					  						<tr>
					  							<td colspan="3">No se encontraron adicionales</td>
					  						</tr>
					  					</tbody>
					  				</table>
					  			</div>
					  		</div>
						</div>
					</div>
				</div>
			</div>
        	
        </div>
    </div>
</div>



<div class="modal fade" id="nueva_categoria"  data-bs-backdrop="static" data-bs-keyboard="false" style="overflow-y: none;">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nueva categoria</h5>
                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
            </div>
            <div class="modal-body">
            	<b>Nombre de categoria</b>
            	<input type="" name="txt_new_cate" id="txt_new_cate" class="form-control-sm form-control">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="add_categoria()">Guardar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>


