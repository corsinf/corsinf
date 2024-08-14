<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Formulario</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Crear una oficina</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="card">
            <div class="card-body">
                <h2 id="lbl_form_title" class="text-center mb-4">Alquiler de Espacios</h2>
                <form id="rental_form">
                    <div class="form-group mb-3">
                        <label for="txt_name">Nombre del Espacio:</label>
                        <input type="text" class="form-control" name="txt_name" id="txt_name" placeholder="Introduce el nombre del espacio" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="categoriaEspacio">Categoría:</label>
                        <select class="form-select" id="categoriaEspacio" name="categoriaEspacio" required>
                            <option value="" disabled selected>Selecciona una categoría</option>
                            <option value="Oficina">Oficina</option>
                            <option value="Auditorio">Auditorio</option>
                            <option value="Local">Local</option>
                        </select>
                    </div>

                    <!-- Grupo de Aforo y Precio en la misma fila -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="txt_capacity">Aforo:</label>
                                <input type="number" class="form-control" name="txt_capacity" id="txt_capacity" placeholder="Introduce el aforo" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="txt_price">Precio:</label>
                                <input type="number" class="form-control" name="txt_price" id="txt_price" placeholder="Introduce el precio" required>
                            </div>
                        </div>
                    </div>

                    <h6 class="mb-0 text-uppercase">Espacios</h6>
                    <hr/>
                    <div class="card">
                        <div class="card-body">
                            <table class="table mb-0 table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nombre de espacios</th>
                                        <th scope="col">Categoría</th>
                                        <th scope="col">Aforo</th>
                                        <th scope="col">Precio</th>
                                        <th scope="col">Gestionar Inventario</th>
                                        <th scope="col">Enviar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">1</th>
                                        <td>Ministerio</td>
                                        <td>Oficina</td>
                                        <td>140</td>
                                        <td>200.00</td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#furnitureModal">
                                                <i class="bx bx-cog"></i> Gestionar Mueblería
                                            </button>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm">
                                                <i class="bx bx-send"></i> Enviar
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">2</th>
                                        <td>Instituto</td>
                                        <td>Auditorio</td>
                                        <td>140</td>
                                        <td>200.00</td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#furnitureModal">
                                                <i class="bx bx-cog"></i> Gestionar Mueblería
                                            </button>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm">
                                                <i class="bx bx-send"></i> Enviar
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
               
<!-- Modal -->
<div class="modal fade" id="furnitureModal" tabindex="-1" aria-labelledby="furnitureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="furnitureModalLabel">Gestionar Mobiliario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h3 id="lbl_furniture" class="text-center mb-3">Mobiliario</h3>
                <table id="tbl_furniture" class="table table-bordered mb-4">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nombre del Mueble</th>
                            <th>Cantidad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" class="form-control" name="txt_furniture_name[]" placeholder="Nombre del mueble" required></td>
                            <td><input type="number" class="form-control" name="txt_furniture_quantity[]" placeholder="Cantidad" required></td>
                            <td><button type="button" class="btn btn-danger btn-sm" >Eliminar</button></td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-center">
                    <button type="button" class="btn btn-success btn-sm mb-3" >Agregar Mueble</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>


