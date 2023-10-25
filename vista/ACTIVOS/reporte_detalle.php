<?php //include ('../cabeceras/header.php');?>
<script type="text/javascript" src="../js/reportes.js"></script>
<script type="text/javascript">  
  id = '<?php echo $_GET['id']; ?>';
 $( document ).ready(function() {
  detalle_reporte(id);
  filtros_reporte(id);
 })
</script>

<div class="page-wrapper">
      <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-0">
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
            <div class="card">
              <div class="card-body">
                <div class="row row-cols-auto g-1">
                   <div class="col">                  
                    <a class="btn btn-outline-dark btn-sm" href="inicio.php?mod=<?php echo $_SESSION['INICIO']['MODULO_SISTEMA']; ?>&acc=reportes"><i class="bx bx-arrow-back"></i> Regresar</a>
                  </div>
                  <div class="col">
                    <button class="btn btn-success btn-sm" id="Generar_excel"><i class="bx bx-file"></i> Excel</button>
                  </div>                  
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-body">
                <form id="form_filtro">
                    <div class="row" id="filtros" name="filtros">              	
                    </div>  
                </form>  
              </div>
            </div>
            <div class="row">
					<div class="col-sm-12">
						<div class="card">
							<div class="card-body">
								<!-- <h6 class="mb-0 text-uppercase">DataTable Import</h6> -->
        <!-- <hr/> -->
        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <div class="col-sm-12">
                      <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                        <ul class="pagination pagination-sm justify-content-end" id="pag">
                        </ul>
                      </div>
                    </div>
                  <input type="hidden" id="txt_pag" name="" value="0-25">
                  <input type="hidden" id="txt_pag1" name="" value="0-25">
                  <input type="hidden" id="txt_numpag" name="">

              <table id="tbl_regi" class="table table-striped table-bordered table table-sm mb-0">
                <thead id="tbl_header">
                    
                </thead>
                <tbody id="tbl_datos">
                  
                </tbody>
              </table>
            </div>
            <div class="table-responsive">
            </div>
          </div>
        </div>
							</div>
						</div>
					</div>
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