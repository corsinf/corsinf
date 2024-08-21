<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Formulario</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Crear una oficina</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                <form id="rental_form">
                    <div class="form-group mb-3">
                        <label for="txt_name">Nombre del Espacio:</label>
                        <input type="text" class="form-control" name="txt_name" id="txt_name" placeholder="Introduce el nombre del espacio" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="ddl_categoriaEspacio">Categoría:</label>
                        <select class="form-select" id="ddl_categoriaEspacio" name="ddl_categoriaEspacio" required>
                            <option value="" disabled selected>Selecciona una categoría</option>
                            <!-- Opciones cargadas dinámicamente -->
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

                    <div class="text-end mb-4">
                        <button type="button" onclick="enviarDatos()" class="btn btn-primary btn-sm">Guardar</button>
                        <button type="reset" class="btn btn-secondary btn-sm">Limpiar</button>
                    </div>
                </form>

                <h6 class="mb-0 text-uppercase">Espacios</h6>
                <hr />
                <div class="card">
                    <div class="card-body">
                        <table class="table mb-0 table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Género</th>
                                    <th scope="col">Campo 2</th>
                                    <th scope="col">Campo 3</th>
                                    <th scope="col">Campo 4</th>
                                    <th scope="col">Campo 5</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Campo 7</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbl_ingresos_body">
                                <!-- Filas cargadas dinámicamente -->
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
                                    <tbody id="tbl_furniture_body">
                                        <!-- Filas de mobiliario cargadas dinámicamente -->
                                    </tbody>
                                </table>
                                <div class="text-center">
                                    <button type="button" class="btn btn-success btn-sm mb-3">Agregar Mueble</button>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Guardar Cambios</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!--end page wrapper-->

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        lista_categorias()
        listarIngresos();
    });

    function lista_categorias()
  {
    
    $.ajax({
         // data:  {parametros:parametros},
         url:   '../controlador/COWORKING/crear_oficinaC.php?categoria=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) {  
                console.log(response);
                $('#ddl_categoriaEspacio').html(response);
          } 
    });
  }


    function listarIngresos() {
        $.ajax({
            url: '../controlador/COWORKING/crear_oficinaC.php?listaIngresos=true',
            type: 'post',
            dataType: 'json',
            success: function (response) {
                $('#tbl_ingresos_body').html(response);
            },
            error: function (xhr, status, error) {
                console.error('Error al cargar ingresos:', error);
            }
        });
    }

    function enviarDatos() {
        if ($('#rental_form')[0].checkValidity() === false) {
            $('#rental_form')[0].reportValidity();
            return;
        }

        var datos = {
            nombre: $('#txt_name').val(),
            categoria: $('#ddl_categoriaEspacio').val(),
            aforo: $('#txt_capacity').val(),
            costo: $('#txt_price').val()
        };

        $.ajax({
            url: '../controlador/COWORKING/crear_oficinaC.php?add=true',
            type: 'POST',
            dataType: 'json',
            data: { data: datos },
            success: function (response) {
                alert('Datos guardados correctamente.');
                $('#rental_form')[0].reset();
                listarIngresos();
            },
            error: function (xhr, status, error) {
                console.error('Error al enviar los datos:', error);
                alert('Hubo un error al guardar los datos. Por favor, intenta de nuevo.');
            }
        });
    }
</script>
