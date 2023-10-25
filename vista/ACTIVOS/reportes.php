<?php //include ('../cabeceras/header.php');?>
<script type="text/javascript" src="../js/reportes.js"></script>
<script type="text/javascript">
 $( document ).ready(function() {
 		lista_reportes();
 })
</script>

<div class="page-wrapper">
      <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
          <div class="breadcrumb-title pe-3">Reportes</div>
          <div class="ps-3">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Reportes</li>
              </ol>
            </nav>
          </div>
          <!-- <div class="ms-auto">
            <div class="btn-group">
              <button type="button" class="btn btn-primary">Settings</button>
              <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"> <span class="visually-hidden">Toggle Dropdown</span>
              </button>
              <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">  <a class="dropdown-item" href="javascript:;">Action</a>
                <a class="dropdown-item" href="javascript:;">Another action</a>
                <a class="dropdown-item" href="javascript:;">Something else here</a>
                <div class="dropdown-divider"></div>  <a class="dropdown-item" href="javascript:;">Separated link</a>
              </div>
            </div>
          </div> -->
        </div>
        <!--end breadcrumb-->
        <div class="row">
          <div class="col-xl-12 mx-auto">
            <h6 class="mb-0 text-uppercase">Activos</h6>
            <hr>
            <div class="card">
              <div class="card-body">
              <div class="row">
              	<div class="col-sm-3">
              		<button class="btn btn-primary btn-sm" onclick="cargar_lista_reporte()"><i class="bx bx-plus"></i> Nuevo</button>  
              	</div>
              </div>             
               
              </div>
            </div>
            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-3 row-cols-xl-3" id="lista_reportes">

						</div>
          </div>
        </div>
        <!--end row-->
      </div>
    </div>


<div class="modal fade" id="nuevo_reporte" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">NUEVO REPORTE</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
        	<div class="col-sm-12">
        		<b>Nombre de reporte</b>
        		<input type="text" name="txt_reporte" id="txt_reporte" class="form-control form-control-sm" required>
        	</div>
        	<div class="col-sm-12">
        		<b>Tipo de reporte</b>
        		<select class="form-select form-select-sm" id="ddl_tipo_reporte" name="ddl_tipo_reporte" required>
        			<option value="">Seleccione tipo de reporte</option>
        		</select>
        	</div>
        	<div class="col-sm-12">
        		<b>Detalle</b>
        		<textarea class="form-control-sm form-control" style="resize:none;" rows="3" id="txt_detalle" name="txt_detalle" placeholder="Descripcion del reporte"></textarea>
        	</div>
                  
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-secondary" onclick="crear_reporte()">Guardar</button>
      </div>
    </div>
  </div>
</div>



<?php //include ('../cabeceras/footer.php');?>