<?php //include('../../cabeceras/header.php'); ?> 
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/html2clipboard/2.1.2/html2clipboard.min.js"></script> -->
<script type="text/javascript" src="../js/ingresar_proceso.js"></script>
<script type="text/javascript">
	var id = '<?php echo $_GET['id']; ?>'
	var estado = '<?php echo $_GET['estado']; ?>'
	console.log(id);
  $( document ).ready(function() {
  	if(estado==1)
  	{
  		$('#btn_enviar').attr('disabled',true);
  		$('#btn_solicitud').attr('disabled',true);
  	}
  	if(id!='')
  	{
  		cargar_solicitud(id);
  		cargar_lineas_solicitud(id)  
  	}else
  	{
  		 Swal.fire('No se encontro este proceso','','info').then(function(){
  		 	location.href = "index.php";
  		 });
  	}
  })
  </script>
<div class="page-wrapper">
  	<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-0">
					<div class="breadcrumb-title pe-3">Forms</div>					
				</div>
				<!--end breadcrumb-->
				<div class="row">
					<div class="col-xl-12 mx-auto">						
						<hr>
						<div class="card">
							<div class="card-body">

								<!-- SmartWizard html -->
								<div id="smartwizard" class="sw sw-justified sw-theme-arrows">
									<ul class="nav">
										<li class="nav-item">
											<a class="nav-link inactive active" href="#step-1">	<strong>Notificar Broker</strong></a>
										</li>
										<li class="nav-item">
											<a class="nav-link inactive" href="#step-2">	<strong>Datos de solicitud</strong></a>
										</li>
										<li class="nav-item">
											<a class="nav-link inactive" href="#step-3">	<strong>Salida de bienes</strong></a>
										</li>
										<li class="nav-item">
											<a class="nav-link inactive" href="#step-4">	<strong>Ingreso de bienes</strong> </a>
										</li>
									</ul>
									<div class="toolbar toolbar-top p-0" role="toolbar" style="text-align: right;">
										<input type="hidden" name="txt_anterior" id="txt_anterior" value="0">
										<button class="btn btn-sm sw-btn-prev disabled" type="button" onclick="retroceder_proceso()">Anterior</button>
										<button class="btn btn-sm sw-btn-next" type="button" onclick="guardar_proceso()">Siguiente</button>
									</div>
									<div class="tab-content" style="height: 145.594px;">
										<div id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-1" style="position: static; left: auto; width: 1030px; display: block;">
											<h3>Notificacion de Broker</h3>
											<div class="row m-1">
												<div class="col-sm-12">
													<div class="row">
														<div class="col-sm-12">
															<label style="display:none;" ><input type="checkbox" id="rbl_notificacion" onclick="notificacion_broker()"> Se ha notificado al broker</label>	
															<div class="card">
																<div class="card-header bg-dark text-white py-2 cursor-pointer">
																	<div class="d-flex align-items-center">
																		<div class="compose-mail-title">Nuevo mensaje</div>
																		<div class="compose-mail-close ms-auto">x</div>
																	</div>
																</div>
																<div class="card-body">
																	<div class="email-form">
																		<div class="mb-1">
																			<input type="text" class="form-control form-control-sm" placeholder="To" id="txt_to">
																		</div>
																		<div class="mb-1">
																			<input type="text" class="form-control form-control-sm" value="Notificacion para Broker" placeholder="Subject" id="txt_subjet">
																		</div>
																		<div class="mb-1">
																			<textarea class="form-control" placeholder="Message" rows="5" cols="10" id="mensaje">Estimado Francisco:
																				Buenas dias, Tu ayuda por favor notificando al seguro de la salida de los siguientes bienes hasta el 23 junio 2023
																			</textarea>
																			<div class="col-sm-12 text-center" id="div_mensaje">
																					<style>
																						tableBorder {   border: 1px solid black; border-collapse: collapse;   }
																					  th, td { border: 1px solid black;   padding: 8px;  }
																					</style>
																				<table class="table">
																					<thead>
																						<th>Codigo Puce</th>
																						<th>Item</th>
																						<th>Serie</th>
																						<th>Modelo</th>
																					</thead>
																					<tbody id="tbl_lineas">
																						
																					</tbody>															
																				</table>
																			</div>	
																			<br>		
																		</div>
																		<div class="mb-0">
																			<div class="d-flex align-items-center">
																				<div class="">
																					<div class="btn-group">
																						<button type="button" id="btn_enviar" class="btn btn-primary btn-sm" onclick="enviar_correo()"><i class="bx bx-send"></i>Enviar</button>
																						<!-- <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">	<span class="visually-hidden">Toggle Dropdown</span>
																						</button> -->
																						<!-- <div class="dropdown-menu">	<a class="dropdown-item" href="javascript:;">Action</a>
																							<a class="dropdown-item" href="javascript:;">Another action</a>
																							<a class="dropdown-item" href="javascript:;">Something else here</a>
																							<div class="dropdown-divider"></div>	<a class="dropdown-item" href="javascript:;">Separated link</a>
																						</div> -->
																					</div>
																				</div>
																				<!-- <div class="ms-2">
																					<button type="button" class="btn border-0 btn-sm btn-white"><i class="lni lni-text-format"></i>
																					</button>
																					<button type="button" class="btn border-0 btn-sm btn-white"><i class="bx bx-link-alt"></i>
																					</button>
																					<button type="button" class="btn border-0 btn-sm btn-white"><i class="lni lni-emoji-tounge"></i>
																					</button>
																					<button type="button" class="btn border-0 btn-sm btn-white"><i class="lni lni-google-drive"></i>
																					</button>
																				</div> -->
																				<!-- <div class="ms-auto">
																					<button type="button" class="btn border-0 btn-sm btn-white"><i class="lni lni-trash"></i>
																					</button>
																				</div> -->
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														
														</div>
																													
													</div>										
												</div>												
											</div>
											<!-- <div class="row">
												<div class="col-sm-6">
													<div class="row">
														<div class="col-sm-12" style="height:400px; border:1px solid; overflow-y: scroll;">
															<style>
																tableBorder {   border: 1px solid black; border-collapse: collapse;   }
															  th, td { border: 1px solid black;   padding: 8px;  }
															</style>
															<br>
															<div class="row">
																<div class="col-sm-12 text-end">
																		<button class="btn btn-outline-dark btn-sm" id="btn_copy" onclick="copy_message()"><i class="fadeIn animated bx bx-copy"></i> copiar</button>																			
																</div>
																<div class="col-sm-12" id="div_mensaje">
																	<p>Estimado Francisco:</p>
																	<p>Buenas dias, Tu ayuda por favor notificando al seguro de la salida de los siguientes bienes hasta el 23 junio 2023</p>
																	<table class="table tableBorder">
																		<header>
																			<td>Codigo Puce</td>
																			<td>Item</td>
																			<td>Serie</td>
																			<td>Modelo</td>
																		</header>
																		<tbody id="tbl_lineas_">
																			
																		</tbody>															
																	</table>																	
																</div>																															
															</div>															
														</div>		
													</div>
												
												</div>
											</div>					 -->					
										</div>
										<div id="step-2" class="tab-pane p-3" role="tabpanel" aria-labelledby="step-2" style="display: none;">
											
											<div class="row">
												<div class="col-sm-6">
													<div class="row">
														<div class="col-sm-7">
																<h4>Datos de Solicitud</h4>	
														</div>
														<div class="col-sm-5 text-end">
															<button class="btn btn-primary btn-sm" id="btn_solicitud" onclick="solicitud_salida()">Descargar Solicitud</button>			
														</div>
													</div>
													<div class="row">
														<div class="col-sm-12">
															<b>Responsable</b><br>
															<label id="lbl_responsable"></label><br>
														</div>														
														<div class="col-sm-12">
															<b>Destino</b><br>
															<label id="lbl_destino"></label><br>
														</div>														
														<div class="col-sm-4">
															<b>Fecha de Salida</b><br>
															<label id="lbl_fechas"></label><br>
														</div>	
														<div class="col-sm-4">
															<b>Fecha de entrada</b><br>
															<label id="lbl_fechae"></label><br>
														</div>														
														<div class="col-sm-4">
															<b>Duracion(dias)</b><br>
															<label id="lbl_duracion"></label><br>
														</div>
														<div class="col-sm-12">
															<b>Motivo de movilizacion</b><br>
															<label id="lbl_motivo"></label><br>
														</div>
													</div>												
												</div>
												<div class="col-sm-6">
														<b>Cedula de bienes</b>
														<input type="hidden" name="" id="txt_lineas">
														<iframe src="" id="iframe" style="width:100%; height:50vw;" frameborder="0" allowfullscreen></iframe>			
												</div>												
											</div>										
										</div>
										<div id="step-3" class="tab-pane" role="tabpanel" aria-labelledby="step-3" style="display: none;">
											<div class="row">
												<div class="col-sm-12">
													<label><input type="checkbox" name="" checked disabled> Notificado al Broker</label><br>
													<label><input type="checkbox" name="rbl_contatacion" id="rbl_contatacion" onclick="veri_salida()"> Activos verificados fisicamente(Salida)</label>													
												</div>
												<div class="col-sm-12">
													<table class="table table-hover">
														<header>
															<td>Codigo Puce</td>
															<td>Item</td>
															<td>Observacion salida</td>
															<td></td>
														</header>
														<tbody id="tbl_lineas_salida">
															
														</tbody>															
													</table>	
												</div>
												
											</div>
										</div>
										<div id="step-4" class="tab-pane" role="tabpanel" aria-labelledby="step-4" style="display: none;">
											<?php if($_GET['estado']==0){ ?>
											<div class="toolbar toolbar-top p-1" role="toolbar" style="text-align: right;">
												<button class="btn btn-sm" type="button" onclick="finalizar_proceso()">Finalizar proceso</button>
											</div>
										<?php } ?>

										<div class="row">
											<div class="col-sm-12">
													<label><input type="checkbox" name="" checked disabled> Notificado al Broker</label><br>
													<label><input type="checkbox" name="" checked disabled> Activos verificados fisicamente(Salida)</label>	<br>
													<label><input type="checkbox" name="rbl_contatacion2" id="rbl_contatacion2" onclick="veri_entrada()"> Activos verificados fisicamente(Entrada)</label>													
												</div>
												<div class="col-sm-12">
													<table class="table table-hover">
														<header>
															<td>Codigo Puce</td>
															<td>Item</td>
															<td>Observacion salida</td>
															<td>Observacion Entrada</td>
															<td></td>
														</header>
														<tbody id="tbl_lineas_entrada">
															
														</tbody>															
													</table>								
													
												</div>
											
										</div>
											
															
										</div>
									</div>
									<div class="toolbar toolbar-bottom" role="toolbar" style="text-align: right;">
										<!-- <button class="btn sw-btn-prev btn-sm disabled" type="button">Previous</button> -->
										<!-- <button class="btn sw-btn-next btn-sm" type="button">Next</button> -->

										<!-- <button class="btn btn-info">Finish</button>
										<button class="btn btn-danger">Cancel</button> -->
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--end row-->
			</div>
</div>


<!-- The Modal -->
<div class="modal" id="ModalFormulario">
 <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Modal Heading</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        Modal body..
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
  <!-- Content Wrapper. Contains page content -->
  

<?php //include('../../cabeceras/footer.php'); ?>
