<script type="text/javascript">
	var tablaAll;
	var tablaMedi;
	var tablaInsu;
<<<<<<< HEAD
	$(document).ready(function() {
		lista_proveedor();
		tablaAll = $('#tabla_todos').DataTable({
			language: {
				url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
			},
			responsive: true,
			ajax: {
				url: '../controlador/ingreso_stockC.php?lista_kardex=true',
				dataSrc: ''
			},
			columns: [{
					data: null,
					searchable: false
				}, // #
				{
					data: null,
					searchable: true
				}, // Fecha 
				{
					data: null,
					searchable: true
				}, // Producto
				{
					data: null,
					searchable: true
				}, // Tipo
				// ... el resto de columnas
			],
			columns: [{
					data: null,
					render: function(data, type, item, meta) {
						return meta.row + 1;
					}
				},
				{
					data: null,
					render: function(data, type, item) {
						return formatoDate(item.Fecha.date);
					}
				},
				{
					data: null,
					render: function(data, type, item) {
						return '<a href="../vista/inicio.php?mod=7&acc=registrar_insumos&id=' + item.id_ar + '"><u>' + item.Productos + '</u></a>';
					}
				},
				{
					data: 'Tipo'
				},
				{
					data: 'Entrada'
				},
				{
					data: 'Salida'
				},
				{
					data: 'Precio'
				},
				{
					data: 'Stock'
				},
				{
					data: 'Serie'
				},
				{
					data: 'Factura'
				},
			],
			dom: '<"top"Bfr>t<"bottom"lip>',
			buttons: [
				'excel', 'pdf' // Configura los botones que deseas
			],
			initComplete: function() {
				// Mover los botones al contenedor personalizado
				$('#pnl_exportar').append($('.dt-buttons'));
				$('#tabla_todos_filter input').unbind().bind('input', function() {
					buscarExacto($(this).val());
				});
			}
		});
=======
    $(document).ready(function() {
    	lista_proveedor();
    	tablaAll = $('#tabla_todos').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            responsive: true,
            ajax: {
                url: '../controlador/ingreso_stockC.php?lista_kardex=true',
                dataSrc: ''
            },
              columns: [
                { data: null, searchable: false }, // #
                { data: null, searchable: true },  // Fecha 
                { data: null, searchable: true }, // Producto
                { data: null, searchable: true }, // Tipo
                // ... el resto de columnas
              ],
            columns: [{
                    data: null,
                    render: function(data, type, item, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return formatoDate(item.Fecha.date);
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        if(item.Tipo=='Insumos')
                        {
                        return '<a href="../vista/inicio.php?mod=7&acc=registrar_insumos&id=' + item.id_ar + '" target="_blank"><u>' + item.Productos + '</u></a>';
                        }else
                        {
                             return '<a href="../vista/inicio.php?mod=7&acc=registrar_medicamentos&id=' + item.id_ar + '" target="_blank"><u>' + item.Productos + '</u></a>';
                        }
                    }
                },
                {
                    data: 'Tipo'
                },
                {
                    data: 'Entrada'
                },
                {
                    data: 'Salida'
                },
                {
                    data: 'Precio'
                },
                {
                    data: 'Stock'
                },
                {
                    data: 'Serie'
                },
                {
                    data: 'Factura'
                },
            ],
            dom: '<"top"Bfr>t<"bottom"lip>',
		    buttons: [
		        'excel', 'pdf' // Configura los botones que deseas
		    ],
            initComplete: function() {
                // Mover los botones al contenedor personalizado
                $('#pnl_exportar').append($('.dt-buttons'));
                $('#tabla_todos_filter input').unbind().bind('input', function() {
                    buscarExacto($(this).val());
                });
            }
        });
>>>>>>> 4f31380b28051e3bb3c24f8c85f8193ecdd58cef


		function buscarExacto(valorABuscar) {
			tablaAll.search("^" + $.fn.dataTable.util.escapeRegex(valorABuscar) + "$|\\b" + $.fn.dataTable.util.escapeRegex(valorABuscar) + "\\b", true, false).draw();

		}


		tablaInsu = $('#tabla_insumos').DataTable({
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

			]
		});


<<<<<<< HEAD
		tablaMedi = $('#tabla_medicamentos').DataTable({
			language: {
				url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
			},
			responsive: true,
			ajax: {
				url: '../controlador/medicamentosC.php?listar_todo=true',
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
						return '<a href="../vista/inicio.php?mod=7&acc=registrar_medicamentos&id=' + item.sa_cmed_id + '"><u>' + item.sa_cmed_presentacion + '</u></a>';
					}
				},
				{
					data: 'sa_cmed_concentracion'
				},
				{
					data: 'sa_cmed_serie'
				},
				{
					data: 'sa_cmed_minimos'
				},
				{
					data: 'sa_cmed_stock'
				}
			]
		});
=======
	  	 $('#ddl_lista_productos').on('select2:select', function (e) {
	  	 	  var data = e.params.data.data;
              if($('#ddl_tipo').val()!='Insumos'){
              $("#txt_existencias").val(data.sa_cmed_stock)
          }else{
              $("#txt_existencias").val(data.sa_cins_stock)
	  	 	  console.log(data);
            }
	  	 })
>>>>>>> 4f31380b28051e3bb3c24f8c85f8193ecdd58cef


		$('#ddl_lista_productos').on('select2:select', function(e) {
			var data = e.params.data.data;
			console.log(data);
		})

	});

	function modal_ingreso(tipo) {
		$('#ddl_tipo').val(tipo)
		$('#myTabs a[href="#' + tipo + '"]').tab('show');
		$('#exampleVerticallycenteredModal').modal('hide');
		$('#exampleModal').modal('show');
		lista_medicamentos(tipo);
	}

	function lista_proveedor() {
		$('#ddl_proveedor').select2({
			placeholder: 'Seleccione Proveedor',
			dropdownParent: $('#exampleModal'),
			width: '87%',
			ajax: {
				url: '../controlador/contratoC.php?lista_proveedores=true',
				dataType: 'json',
				delay: 250,
				processResults: function(data) {
					// console.log(data);
					return {
						results: data
					};
				},
				cache: true
			}
		});
	}

	function lista_medicamentos(tipo) {
		$('#ddl_lista_productos').select2({
			placeholder: 'Seleccione producto',
			dropdownParent: $('#exampleModal'),
			width: '87%',
			ajax: {
				url: '../controlador/ingreso_stockC.php?lista_articulos=true&tipo=' + tipo,
				dataType: 'json',
				delay: 250,
				processResults: function(data) {
					// console.log(data);
					return {
						results: data
					};
				},
				cache: true
			}
		});
	}

	function calculos() {
		let cant = parseFloat($('#txt_canti').val());
		let pre = parseFloat($('#txt_precio').val());
		let des = parseFloat($('#txt_descto').val());
		if ($('#rbl_si').prop('checked')) {
			let subtotal = pre * cant;
			let dscto = (subtotal * des) / 100;
			let total = (subtotal - dscto) * 1.12;

			let iva = parseFloat($('#txt_iva').val());
			let sub_val = subtotal - dscto;
			$('#txt_subtotal').val(sub_val.toFixed(2));
			$('#txt_total').val(total.toFixed(2));
			let iva_val = total - (subtotal - dscto)
			$('#txt_iva').val(iva_val.toFixed(2));

		} else {
			$('#txt_iva').val(0);
			let iva = parseFloat($('#txt_iva').val());
			let sub = (pre * cant);
			let dscto = (sub * des) / 100;

<<<<<<< HEAD
			let total = (sub - dscto);
			let sub_val = sub - dscto;
			$('#txt_subtotal').val(sub_val.toFixed(2));
			$('#txt_total').val(total.toFixed(2));
		}
	}
=======
     var ddl = $('#ddl_tipo option:selected').text();
     var datos = datos+'&ddl_tipo='+ddl;
     
     if($('#ddl_proveedor').val()=='' || $('#ddl_lista_productos').val()==''  || $('#ddl_lista_productos').val()==null || $('#txt_precio').val()=='0' || $('#txt_precio').val()=='' || $('#txt_serie').val()=='' || $('#txt_factura').val()=='' || $('#txt_fecha_ela').val()=='' || $('#txt_fecha_exp').val()=='')
     {
       Swal.fire('','Llene todo los campos.','info');   
      return false;
     }
>>>>>>> 4f31380b28051e3bb3c24f8c85f8193ecdd58cef

	function guardar_producto() {
		var datos = $("#form_nuevo_producto").serialize();

		var ddl = $('#ddl_tipo option:selected').text();
		var datos = datos + '&ddl_tipo=' + ddl;

		if ($('#ddl_proveedor').val() == '' || $('#ddl_lista_productos').val() == '' || $('#txt_precio').val() == '0' || $('#txt_precio').val() == '' || $('#txt_serie').val() == '' || $('#txt_factura').val() == '' || $('#txt_fecha_ela').val() == '' || $('#txt_fecha_exp').val() == '') {
			Swal.fire('', 'Llene todo los campos.', 'info');
			return false;
		}

		if ($('#txt_canti').val() < 1) {
			Swal.fire('', 'Cantidad no valida.', 'info');
			return false;
		}

		$.ajax({
			data: datos,
			url: '../controlador/ingreso_stockC.php?producto_nuevo=true',
			type: 'post',
			dataType: 'json',
			success: function(response) {
				// console.log(response);

				$('#Nuevo_producto').modal('hide');
				if (response == 1) {

					Swal.fire('Ingresado a stock.', '', 'success').then(function() {
						if (tablaAll) {
							tablaAll.ajax.reload();
							tablaInsu.ajax.reload();
							tablaMedi.ajax.reload();
						}
					});
					limpiar_nuevo_producto();
				}
			}
		});
		// console.log(datos);
	}

	function limpiar_nuevo_producto() {
		$('#ddl_lista_productos').empty();
	}
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
									<div class="col-sm-8" id="btn_nuevo">
										<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleVerticallycenteredModal"><i class="bx bx-plus me-0"></i> Nuevo Ingreso</button>
									</div>
									<div class="col-sm-4 text-end" id="pnl_exportar">

									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-sm-12">
										<ul class="nav nav-tabs nav-success" role="tablist" id="myTabs">
											<li class="nav-item" role="presentation">
												<a class="nav-link active" data-bs-toggle="tab" href="#all" role="tab" aria-selected="true">
													<div class="d-flex align-items-center">
														<div class="tab-icon">
															<i class='bx bxs-capsule font-18 me-1'></i>
														</div>
														<div class="tab-title">Todos</div>
													</div>
												</a>
											</li>
											<li class="nav-item" role="presentation">
												<a class="nav-link" data-bs-toggle="tab" href="#Medicamento" role="tab" aria-selected="true">
													<div class="d-flex align-items-center">
														<div class="tab-icon">
															<i class='bx bxs-capsule font-18 me-1'></i>
														</div>
														<div class="tab-title">Medicamentos</div>
													</div>
												</a>
											</li>
											<li class="nav-item" role="presentation">
												<a class="nav-link" data-bs-toggle="tab" href="#Insumos" role="tab" aria-selected="false" tabindex="-1">
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
											<div class="tab-pane fade show active" id="all" role="tabpanel">
												<div class="table-responsive">
													<table class="table table-striped responsive " id="tabla_todos" style="width:100%">
														<thead>
															<tr>
																<th>#</th>
																<th>Fecha Ingreso</th>
																<th>Productos</th>
																<th>Tipo</th>
																<th>Entrada</th>
																<th>Salida</th>
																<th>Precio</th>
																<th>Stock</th>
																<th>Serie</th>
																<th>Factura</th>
															</tr>
														</thead>
														<tbody>

														</tbody>
													</table>
												</div>
											</div>
											<div class="tab-pane fade show" id="Medicamento" role="tabpanel">
												<div class="table-responsive">
													<table class="table table-striped responsive " id="tabla_medicamentos" style="width:100%">
														<thead>
															<tr>
																<th>#</th>
																<th>Presentación</th>
																<th>Concentración</th>
																<th>Serie</th>
																<th>Mínimos</th>
																<th>Stock</th>
															</tr>
														</thead>
														<tbody>

														</tbody>
													</table>
												</div>
											</div>
											<div class="tab-pane fade" id="Insumos" role="tabpanel">
												<div class="table-responsive">
													<table class="table table-striped responsive " id="tabla_insumos" style="width:100%">
														<thead>
															<tr>
																<th>#</th>
																<th>Concentración</th>
																<th>Lote</th>
																<th>Mínimos</th>
																<th>Stock</th>
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
				<form id="form_nuevo_producto">

					<div class="row">
						<div class="col-sm-4 mb-2">
							<label for="" class="fw-bold">Proveedor <label style="color: red;">*</label> </label>
							<select class="form-select form-select-sm" id="ddl_proveedor" name="ddl_proveedor">
								<option value="">Seleccione proveedor</option>
							</select>
						</div>
						<div class="col-sm-3 mb-2"></div>

						<div class="col-sm-1 mb-2">
							<label for="" class="fw-bold">Serie <label style="color: red;">*</label> </label>
							<input type="" class="form-control form-control-sm" name="txt_serie" id="txt_serie">
						</div>

						<div class="col-sm-2 mb-2">
							<label for="" class="fw-bold">Factura <label style="color: red;">*</label> </label>
							<input type="" class="form-control form-control-sm" name="txt_factura" id="txt_factura">
						</div>

						<div class="col-sm-2 mb-2">
							<label for="" class="fw-bold">Fecha Ingreso <label style="color: red;">*</label> </label>
							<input type="date" readonly class="form-control form-control-sm" name="txt_fecha" id="txt_fecha" value="<?php echo date('Y-m-d'); ?>">
						</div>
					</div>


					<hr>

					<div class="row pt-1">
						<div class="col-sm-2 mb-2">
							<label for="" class="fw-bold">Referencia <label style="color: red;">*</label> </label>
							<input type="text" name="txt_referencia" id="txt_referencia" class="form-control form-control-sm" readonly="">
						</div>

						<div class="col-sm-4 mb-2">
							<label for="" class="fw-bold">Producto <label style="color: red;">*</label> </label>
							<select class="form-select form-select-sm" id="ddl_lista_productos" name="ddl_lista_productos">
								<option value="">Seleccione una producto</option>
							</select>
						</div>

						<div class="col-sm-3 mb-2">
							<label for="ddl_tipo" class="fw-bold">Tipo <label style="color: red;">*</label> </label>
							<select class="form-control form-control-sm" id="ddl_tipo" name="ddl_tipo" disabled>
								<option value=""> -- Selecione -- </option>
								<option value="Insumos">Insumos</option>
								<option value="Medicamento">Medicamento</option>
							</select>
						</div>

						<div class="col-sm-1 mb-2">
							<label for="" class="fw-bold">Unidad <label style="color: red;">*</label> </label>
							<input type="" name="txt_unidad" id="txt_unidad" class="form-control form-control-sm" value="">
						</div>

						<div class="col-sm-2 mb-2">
							<label for="" class="fw-bold">Lleva IVA <label style="color: red;">*</label> </label>
							<br>
							<label class="online-radio "><input type="radio" name="rbl_radio" id="rbl_no" checked="" onchange="calculos()"> No</label>
							<label class="online-radio "><input type="radio" name="rbl_radio" id="rbl_si" onchange="calculos()"> Si</label>
						</div>
					</div>

					<div class="row pt-2">
						<div class="col-sm-2 mb-2">
							<label for="" class="fw-bold">Existente <label style="color: red;">*</label> </label>
							<input type="text" name="txt_existencias" id="txt_existencias" class="form-control form-control-sm" readonly="">
						</div>

						<div class="col-sm-2 mb-2">
							<label for="" class="fw-bold">Fecha Elab <label style="color: red;">*</label> </label>
							<input type="date" name="txt_fecha_ela" id="txt_fecha_ela" class="form-control form-control-sm">
						</div>

						<div class="col-sm-2 mb-2">
							<label for="" class="fw-bold">Fecha Exp <label style="color: red;">*</label> </label>
							<input type="date" name="txt_fecha_exp" id="txt_fecha_exp" class="form-control form-control-sm">
						</div>

						<div class="col-sm-2 mb-2">
							<label for="" class="fw-bold">Reg. Sanitario <label style="color: red;">*</label> </label>
							<input type="text" name="txt_reg_sani" id="txt_reg_sani" class="form-control form-control-sm" readonly="" value=".">
						</div>

						<div class="col-sm-2 mb-2">
							<label for="" class="fw-bold">Procedencia <label style="color: red;">*</label> </label>
							<input type="text" name="txt_procedencia" id="txt_procedencia" class="form-control form-control-sm" value=".">
						</div>

						<div class="col-sm-2 mb-2">
							<label for="" class="fw-bold">Lote <label style="color: red;">*</label> </label>
							<input type="text" name="txt_lote" id="txt_lote" class="form-control form-control-sm" value=".">
						</div>
					</div>

					<hr>

					<div class="row pt-2">
						<div class="col-sm-2 mb-2">
							<label for="" class="fw-bold">Cantidad <label style="color: red;">*</label> </label>
							<input type="number" min="1" name="txt_canti" id="txt_canti" class="form-control form-control-sm solo_numeros" value="1" onblur="calculos()">
						</div>

						<div class="col-sm-2 mb-2">
							<label for="" class="fw-bold">Precio <label style="color: red;">*</label> </label>
							<input type="number" min="1" name="txt_precio" id="txt_precio" class="form-control form-control-sm solo_numeros" value="0" onblur="calculos()">
						</div>

						<div class="col-sm-2 mb-2">
							<label for="" class="fw-bold">PvP Ref. <label style="color: red;">*</label> </label>
							<input type="text" name="txt_precio_ref" id="txt_precio_ref" class="form-control form-control-sm solo_numeros" value="0" readonly="">
						</div>

						<div class="col-sm-2 mb-2">
							<label for="" class="fw-bold">% descto. <label style="color: red;">*</label> </label>
							<input type="number" min="0" name="txt_descto" id="txt_descto" class="form-control form-control-sm solo_numeros" value="0" onblur="calculos()">
						</div>

						<div class="col-sm-2 mb-2">
							<label for="" class="fw-bold">Subtotal <label style="color: red;">*</label> </label>
							<input type="text" name="txt_subtotal" id="txt_subtotal" class="form-control form-control-sm" readonly="" value="0">
						</div>

						<div class="col-sm-1 mb-2">
							<label for="" class="fw-bold">IVA <label style="color: red;">*</label> </label>
							<input type="text" name="txt_iva" id="txt_iva" class="form-control form-control-sm" readonly="" value="0">
						</div>

						<div class="col-sm-1 mb-2">
							<label for="" class="fw-bold">Total <label style="color: red;">*</label> </label>
							<input type="text" name="txt_total" id="txt_total" class="form-control form-control-sm" readonly="" value="0">
						</div>
					</div>

				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary" onclick="guardar_producto()">Guardar</button>
			</div>
		</div>
	</div>
</div>

<div class="col">
	<!-- Button trigger modal -->

	<!-- Modal -->
	<div class="modal fade" id="exampleVerticallycenteredModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">

				<div class="modal-body pt-5">
					<div class="row">

						<div class="col-sm-12" onclick="modal_ingreso('Medicamento')">
							<div class="card radius-10 shadow-card">
								<div class="card-body">
									<div class="d-flex align-items-center">
										<div>
											<p class="mb-0 text-secondary">Ingreso</p>
											<h4 class="my-1">Medicamentos</h4>
										</div>
										<div class="text-primary ms-auto font-35"><i class="bx bxs-capsule"></i>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-sm-12" onclick="modal_ingreso('Insumos')">
							<div class="card radius-10 shadow-card">
								<div class="card-body">
									<div class="d-flex align-items-center">
										<div>
											<p class="mb-0 text-secondary">Ingreso</p>
											<h4 class="my-1">Insumos</h4>
										</div>
										<div class="text-danger ms-auto font-35"><i class="bx bx-test-tube"></i>
										</div>
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$('.shadow-card').on('mouseover', function() {
			$(this).addClass('hoverEffect');
		});

		$('.shadow-card').on('mouseout', function() {
			$(this).removeClass('hoverEffect');
		});

		$('.shadow-card').on('click', function() {
			$(this).toggleClass('clickedEffect');
		});

		$(document).on('mouseout', '.shadow-card', function() {
			$(this).removeClass('clickedEffect');
		});

		var validacion_numeros = $('.solo_numeros');

		validacion_numeros.on('keypress', function(event) {
			var charCode = (event.which) ? event.which : event.keyCode;
			// Permitir números (48-57) y el punto (46)
			if (charCode !== 46 && (charCode < 48 || charCode > 57)) {
				event.preventDefault();
			}
		});
	});
</script>

<style>
	.card {
		cursor: pointer;
		transition: background-color 0.3s, box-shadow 0.3s;
	}

	.card.hoverEffect {
		box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
		background-color: rgba(43, 150, 245, 0.1);
	}

	.card.clickedEffect {
		background-color: rgba(99, 159, 212);
	}

	.card-body {
		padding: 20px;
	}
</style>