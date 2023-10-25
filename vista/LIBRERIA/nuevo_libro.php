
<div class="page-wrapper">
			<div class="page-content">

				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Libreria</div>
					<?php
					// print_r($_SESSION['INICIO']);die();

					?>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">Nuevo Libro</li>
							</ol>
						</nav>
					</div>
					<!-- <div class="ms-auto">
						<div class="btn-group">
							<button type="button" class="btn btn-primary">Settings</button>
							<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">	<span class="visually-hidden">Toggle Dropdown</span>
							</button>
							<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">	<a class="dropdown-item" href="javascript:;">Action</a>
								<a class="dropdown-item" href="javascript:;">Another action</a>
								<a class="dropdown-item" href="javascript:;">Something else here</a>
								<div class="dropdown-divider"></div>	<a class="dropdown-item" href="javascript:;">Separated link</a>
							</div>
						</div>
					</div> -->
				</div>
				<!--end breadcrumb-->

              <div class="card">
				  <div class="card-body p-4">
					  <h5 class="card-title">Agregar nuevo libro</h5>
					  <hr/>
                       <div class="form-body mt-4">
					    <div class="row">
						   <div class="col-lg-8">

                           <div class="border border-3 p-4 rounded">
						   	<div class="row">
						   		<div class="col-12">
						   			<div class="mb-3">
										<label for="inputProductTitle" class="form-label">Titulo de libro</label>
										<input type="email" class="form-control" id="inputProductTitle" placeholder="Ingrese titulo de libro">
									  </div>
						   		</div>
						   		<div class="col-md-6">
						   			 <div class="mb-3">
										<label for="inputProductDescription" class="form-label">Descripcion / resumen de libro</label>
										<textarea class="form-control" id="inputProductDescription" rows="10"></textarea>
									  </div>						   			
						   		</div>
						   		<div class="col-6">
						   			<div class="mb-3">
										<label for="inputProductDescription" class="form-label">Portada</label>
										<input id="image-uploadify" type="file" accept=".xlsx,.xls,image/*,.doc,audio/*,.docx,video/*,.ppt,.pptx,.txt,.pdf" multiple>
									  </div>						   			
						   		</div>
						   	</div>
							
							 
							  
                            </div>
						   </div>
						   <div class="col-lg-4">
							<div class="border border-3 p-4 rounded">
                              <div class="row g-3">
								<!-- <div class="col-md-6">
									<label for="inputPrice" class="form-label">Precio</label>
									<input type="email" class="form-control" id="inputPrice" placeholder="00.00">
								  </div> -->
								  <div class="col-md-12">
									<label for="inputCompareatprice" class="form-label">Autor principal</label>
									<input type="text" class="form-control" id="inputCompareatprice">
								  </div>
								  <div class="col-md-6">
									<label for="inputCostPerPrice" class="form-label">Año publicacion</label>
									<input type="email" class="form-control" id="inputCostPerPrice" placeholder="">
								  </div>
								  <div class="col-md-6">
									<label for="inputStarPoints" class="form-label">Calificacion</label>
									<input type="text" class="form-control" id="inputStarPoints" placeholder="1-5">
								  </div>
								  <div class="col-12">
									<label for="inputProductType" class="form-label">Pais</label>
									<select class="form-select" id="inputProductType">
										<option>seleccione pais</option>
										<option value="1">Ecuador</option>
										<option value="2">Mexico</option>
										<option value="3">España</option>
										<option value="3">Rusia</option>
										<option value="3">Japon</option>
										<option value="3">china</option>
										<option value="3">Francia</option>
									  </select>
								  </div>
								  <div class="col-12">
									<label for="inputVendor" class="form-label">Editorial</label>
									<select class="form-select" id="inputVendor">
										<option> seleccione editoria</option>
											<option value="1">Editorial Mirahadas</option>
											<option value="2">Errara Natura</option>
											<option value="3">Pre-textos</option>
											<option value="4">Sexto Piso</option>
											<option value="5">Nórdica</option>
											<option value="6">Acantilado</option>
											<option value="7">De Conatus</option>
											<option value="8">Cabaret Voltaire</option>
											<option value="9">La Huerta Grande</option>
											<option value="10">Impedimenta</option>
									  </select>
								  </div>
								  <div class="col-12">
									<label for="inputCollection" class="form-label">Tipo de libro</label>
									<select class="form-select" id="inputCollection">
										<option>seleccione tipo de libro</option>
										<option value="1">Enciclopedia</option>
										<option value="2">Diccionario</option>
										<option value="3">Literatura</option>
									  </select>
								  </div>
								  <div class="col-12">
									<label for="inputProductType" class="form-label">Area</label>
									<select class="form-select" id="inputProductType">
										<option> Seleccione area</option>
										<option value="1">Ingenieria</option>
										<option value="2">Matematica</option>
										<option value="3">Fisica</option>
										<option value="4">Literatura</option>
										<option value="5">Quimica</option>
										<option value="6">Ciencias</option>
										<option value="7">Sociologia</option>
									  </select>
								  </div>
								<!--   <div class="col-12">
									<label for="inputProductTags" class="form-label">Product Tags</label>
									<input type="text" class="form-control" id="inputProductTags" placeholder="Enter Product Tags">
								  </div> -->
								  <div class="col-12">
									  <div class="d-grid">
                                         <button type="button" class="btn btn-primary">Guardar libro</button>
									  </div>
								  </div>
							  </div> 
						  </div>
						  </div>
					   </div><!--end row-->
					</div>
				  </div>
			  </div>

			</div>
		</div>
	<!--plugins-->
	
	<!--app JS-->
	<!-- <script src="assets/js/app.js"></script> -->