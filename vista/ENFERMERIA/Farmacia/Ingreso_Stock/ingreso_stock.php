<script type="text/javascript">
    $(document).ready(function() {
        $('#tabla_insumos').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            responsive: true,
            ajax: {
                url: '../controlador/insumosC.php?listar_todo=true',
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    render: function(data, type, item, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return '<a href="../vista/inicio.php?mod=7&acc=registrar_insumos&id=' + item.sa_cins_id + '"><u>' + item.sa_cins_presentacion + '</u></a>';
                    }
                },
                {
                    data: 'sa_cins_lote'
                },
                {
                    data: 'sa_cins_minimos'
                },
                {
                    data: 'sa_cins_stock'
                },
                {
                    data: 'sa_cins_movimiento'
                },
            ]
        });
    });
</script>

<div class="page-wrapper">
    <div class="page-content">

        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Enfermería </div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Insumos</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-3">
                        <div class="content">
                                <div class="container-fluid">

                                    <div class="row">
                                        <div class="col-sm-12" id="btn_nuevo">
                                        	<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bx bx-plus me-0"></i> Nuevo Ingreso</button>
                                        </div>

                                    </div>
                                    <br>
                                    <div class="row">
                                    	<div class="col-sm-12">
											<ul class="nav nav-tabs nav-success" role="tablist">
												<li class="nav-item" role="presentation">
													<a class="nav-link active" data-bs-toggle="tab" href="#successhome" role="tab" aria-selected="true">
														<div class="d-flex align-items-center">
															<div class="tab-icon">
																<i class='bx bxs-capsule font-18 me-1' ></i>
															</div>
															<div class="tab-title">Medicamentos</div>
														</div>
													</a>
												</li>
												<li class="nav-item" role="presentation">
													<a class="nav-link" data-bs-toggle="tab" href="#successprofile" role="tab" aria-selected="false" tabindex="-1">
														<div class="d-flex align-items-center">
															<div class="tab-icon">
																<i class="bx bx-test-tube font-18 me-1"></i>
															</div>
															<div class="tab-title">Insumos</div>
														</div>
													</a>
												</li>												
											</ul>
											<div class="tab-content py-3">
												<div class="tab-pane fade show active" id="successhome" role="tabpanel">
													<div class="table-responsive">
				                                        <table class="table table-striped responsive " id="tabla_insumos" style="width:100%">
				                                            <thead>
				                                                <tr>
				                                                    <th>#</th>
				                                                    <th>Concentración</th>
				                                                    <th>Lote</th>
				                                                    <th>Mínimos</th>
				                                                    <th>Stock</th>
				                                                    <th>Movimiento</th>
				                                                </tr>
				                                            </thead>
				                                            <tbody>

				                                            </tbody>
				                                        </table>
				                                    </div>
												</div>
												<div class="tab-pane fade" id="successprofile" role="tabpanel">
													<div class="table-responsive">
				                                        <table class="table table-striped responsive " id="tabla_insumos" style="width:100%">
				                                            <thead>
				                                                <tr>
				                                                    <th>#</th>
				                                                    <th>Concentración</th>
				                                                    <th>Lote</th>
				                                                    <th>Mínimos</th>
				                                                    <th>Stock</th>
				                                                    <th>Movimiento</th>
				                                                </tr>
				                                            </thead>
				                                            <tbody>

				                                            </tbody>
				                                        </table>
				                                    </div>

												</div>
												
											</div>
                                    	</div>
                                    </div>

                                    <br>                                    
                                </div><!-- /.container-fluid -->
                            <!-- /.content -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Nuevo Ingreso Stock</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-4">
						<b>Proveedor</b>
						<select class="form-select form-select-sm mb-3">
							<option>Seleccione proveedor</option>
						</select>
					</div>
					<div class="col-sm-3">
										
					</div>
					<div class="col-sm-1">
						<b>Serie</b>
						<input type="" class="form-control form-control-sm" name="" id="">						
					</div>
					<div class="col-sm-2">
						<b>Factura</b>
						<input type="" class="form-control form-control-sm" name="" id="">						
					</div>
					<div class="col-sm-2">
						<b>Fecha Ingreso</b>
						<input type="date" readonly class="form-control form-control-sm" name="" id="" value="<?php echo date('Y-m-d'); ?>">						
					</div>
				</div>
				<div class="row">
		           <div class="col-md-2">
		              <b>Referencia:</b>
		              <input type="text" name="txt_referencia" id="txt_referencia" class="form-control form-control-sm" readonly="">
		           </div>
		           <div class="col-sm-5">
		              <b>Producto:</b>
		              <select class="form-select form-select-sm" id="ddl_producto" name="ddl_producto" onchange="cargar_detalles()">
		                <option value="">Seleccione una producto</option>
		              </select>
		           </div>
		          
		           <div class=" col-sm-3">
		              <b>Tipo:</b>
		                <select class="form-control form-control-sm" id="ddl_familia" name="ddl_familia" disabled>
		                  <option value=""> -- Selecione -- </option>
		                  <option value="Insumos">Insumos</option>
		                  <option value="Medicamento">Medicamento</option>
		                </select>     
		           </div>
		           <div class="col-sm-1">
		            <b>Unidad</b>
		            <input type="" name="txt_unidad" id="txt_unidad" class="form-control form-control-sm">             
		           </div>
		           <div class="col-sm-1" style="padding: 0px;">
		              <b>Lleva iva</b><br>
		              <label class="online-radio"><input type="radio" name="rbl_radio" id="rbl_no" checked="" onchange="calculos()"> No</label>
		              <label class="online-radio"><input type="radio" name="rbl_radio" id="rbl_si" onchange="calculos()"> Si</label>            
		            </div>   
		        </div>
		        <div class="row">
		            <div class="col-sm-2">
		               <b>Existente</b>
		                  <input type="text" name="txt_existencias" id="txt_existencias" class="form-control form-control-sm" readonly="">
		            </div>
		            <div class="col-sm-2">
		               <b>Fecha Elab</b>
		                  <input type="date" name="txt_fecha_ela" id="txt_fecha_ela" class="form-control form-control-sm" >
		            </div>
		            <div class="col-sm-2">
		               <b>Fecha Exp</b>
		                  <input type="date" name="txt_fecha_exp" id="txt_fecha_exp" class="form-control form-control-sm" >
		            </div>
		            <div class="col-sm-2">
		               <b>Reg. Sanitario</b>
		                  <input type="text" name="txt_reg_sani" id="txt_reg_sani" class="form-control form-control-sm" readonly="" value=".">
		            </div>
		            <div class="col-sm-2">
		               <b>Procedencia</b>
		                  <input type="text" name="txt_procedencia" id="txt_procedencia" class="form-control form-control-sm">
		            </div>
		            <div class="col-sm-2">
		               <b>Lote</b>
		                  <input type="text" name="txt_lote" id="txt_lote" class="form-control form-control-sm">
		            </div>              
		        </div>
		        <div class="row">                
		          <div class="col-sm-1">
		               <b>Cantidad</b>
		                  <input type="text" name="txt_canti" id="txt_canti" class="form-control form-control-sm"  value="1" onblur="calculos()">
		            </div>
		            <div class="col-sm-1">
		               <b>Precio</b>
		                  <input type="text" name="txt_precio" id="txt_precio" class="form-control form-control-sm"  value="0" onblur="calculos()">
		            </div>
		            <div class="col-sm-1">
		               <b>Pvp Ref</b>
		                  <input type="text" name="txt_precio_ref" id="txt_precio_ref" class="form-control form-control-sm"  value="0" readonly="">
		            </div>
		            <div class="col-sm-1">
		               <b>% descto</b>
		                  <input type="text" name="txt_descto" id="txt_descto" class="form-control form-control-sm"  value="0" onblur="calculos()">
		            </div>             
		            <div class="col-sm-1">
		               <b>Subtotal</b>
		                  <input type="text" name="txt_subtotal" id="txt_subtotal" class="form-control form-control-sm" readonly="" value="0">
		            </div>
		            <div class="col-sm-1">
		               <b>Iva</b>
		                  <input type="text" name="txt_iva" id="txt_iva" class="form-control form-control-sm" readonly="" value="0">
		            </div>  
		            <div class="col-sm-1">
		               <b>Total</b>
		                  <input type="text" name="txt_total" id="txt_total" class="form-control form-control-sm" readonly="" value="0">
		            </div>          
		        </div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>