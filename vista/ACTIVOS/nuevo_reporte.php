<?php include ('../../cabeceras/header.php');?>
<script type="text/javascript" src="../../js/reportes.js"></script>
<script type="text/javascript">
 	id = '<?php echo $_GET['id']; ?>';
 $( document ).ready(function() {
 	datos_reporte(id)
 });
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
         
        </div>
        <!--end breadcrumb-->
        <div class="row">
          <div class="col-xl-12 mx-auto">
            <!-- <h6 class="mb-0 text-uppercase">Activos</h6> -->
            <hr>
            <form id="campos_informe">
            	<div class="row">
            		<div class="col-sm-4">
	            		<input type="" name="txt_titulo" id="txt_titulo" class="form-control form-control-sm" placeholder="titulo reporte">
	            		<br>
	            	</div>
            		<div class="col-sm-8">
	            		<input type="" name="txt_detalle" id="txt_detalle" class="form-control form-control-sm" placeholder="Descripcion de informe">
	            		<br>
	            	</div>
            	</div>
	            <div class="row" id="campos">
			            			            	
	            </div>
            </form>
            <div class="card">
	              <div class="card-body">
			              <div class="row">
			              	<div class="col-sm-12 text-end">
			              		<button class="btn btn-primary btn-sm" onclick="guardar_campos()"><i class="bx bx-save"></i>Guardar</button>              		
			              	</div>
			              </div>
	              </div>
            </div>
          </div>
        </div>
        <!--end row-->
      </div>
    </div>



<?php include ('../../cabeceras/footer.php');?>