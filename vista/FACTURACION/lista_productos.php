<?php  $mod = $_GET['mod']; ?>
<script type="text/javascript">
	var modulo = '<?php echo $mod; ?>'
</script>
<script src="../js/FACTURACION/lista_productos.js"></script> 
<script type="text/javascript">
    $(document).ready(function () {
        list_articulos();
        categorias();
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
                            Lista de productos
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
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
            </div>

        </div>
        <div class="row">
			<div class="card shadow">
			    <div class="card-body">
			        <div class="row mb-4">
			            <div class="col-lg-4 col-sm-12">
			                <a class="btn btn-success btn-sm" href="inicio.php?mod=<?php echo $_GET['mod'] ?>&acc=detalle_articulos"><i class="bx bx-plus"></i> Nuevo</a>     
			                <a class="btn btn-primary btn-sm" href="alimentar_stock.php"><i class="bx bx-plus"></i> Alimentar stock</a>           
			            </div>
			        </div>
			        <div class="row">
			            <div class=" col-lg-3 col-sm-4">
			                <b class="mb-4">Tipo de Articulo</b><br>
			                <label class="mr-2"><input type="radio" name="opc" id="OpcP" checked value="P"  onclick="list_articulos()">Producto</label>
			                <label class="mr-2"><input type="radio" name="opc" id="OpcS" value="S" onclick="list_articulos()">Servicio</label>
			            </div>
			            
			            <div class=" col-lg-5 col-sm-8">
			                <b>Buscar Articulo</b>
			                <input type="text" name="txt_query" id="txt_query" class="form-control form-control-sm" onkeyup="list_articulos()" placeholder="Nombre producto / Referencia">
			            </div>
			            <div class=" col-lg-3 col-sm-4">
			                <b>Categoria</b><br>
			                <select class="form-select form-select-sm" style="width:100%" id="ddl_categoria" name="ddl_categoria" onchange="list_articulos()">
			                    <option value="">Categoria</option>
			                </select>
			            </div>            
			        </div>
			         
			    </div>
			</div>
        </div>
        <div class="row">
        	<div class="card mt-2 shadow mb-8">
			    <div class="card-body">
			        <div class="row" style="overflow-y: scroll; height:450px">
			            <div class="col-sm-12">
			                <table class="table table-hover">
			                    <thead>
			                        <th>Referencia</th>
			                        <th>Cod Auxi</th>
			                        <th>Producto</th>
			                        <th>Stock</th>
			                        <th>Medida</th>
			                        <th>Peso</th>
			                        <th>Localizacion</th>  
			                        <th>Categoria</th>                                    
			                    </thead>
			                    <tbody id="tbl_productos">
			                       
			                        
			                    </tbody>
			                </table>
			            </div>
			        </div>
			    </div>             	           
			</div> 
        	
        </div>
    </div>
</div>


