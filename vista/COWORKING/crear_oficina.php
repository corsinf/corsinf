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
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="txt_estadoEspacio">Estado:</label>
                        <select class="form-select" id="txt_estadoEspacio" name="txt_estadoEspacio" required>
                            <option value="" disabled selected>Selecciona el estado</option>
                            <option value="A">Disponible</option>
                            <option value="B">No disponible</option>
                            <!-- Otros estados si es necesario -->
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
                    </div>
                </form>

                <h6 class="mb-0 text-uppercase">Espacios</h6>
                <hr />
                <div class="card">
                    <div class="card-body">
                    <table id="tbl_espacios" class="table table-bordered mb-4">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Aforo</th>
                            <th>Precio</th>
                            <th>Estado</th>
                            <th>Categoría</th>
                            <th>Acciones</th> 
                        </tr>
                    </thead>
                    <tbody id="tbl_espacios_body">
    
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

                <form id="furniture_form">
                    <div class="form-group mb-3">
                        <label for="txt_furniture_name">Nombre del Mueble:</label>
                        <input type="text" class="form-control" name="txt_furniture_name" id="txt_furniture_name" placeholder="Introduce el nombre del mueble" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="txt_furniture_quantity">Cantidad:</label>
                        <input type="number" class="form-control" name="txt_furniture_quantity" id="txt_furniture_quantity" placeholder="Introduce la cantidad" required>
                    </div>
                    <input type="hidden" id="hidden_espacio_id" name="hidden_espacio_id">
                    <div class="text-end mb-4">
                        <button type="button" onclick="enviarMobiliario()" class="btn btn-primary btn-sm">Guardar Mobiliario</button>
                    </div>
                </form>

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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<!--end page wrapper-->


<script>
    $(document).ready(function () {
        lista_categorias();
        listarEspacios();
    });

    function lista_categorias() {
        $.ajax({
            url: '../controlador/COWORKING/crear_oficinaC.php',
            method: 'GET',
            data: { categoria: true },
            dataType: 'json',
            success: function (response) {
                $('#ddl_categoriaEspacio').html(response);
            }
        });
    }

    function listarEspacios() {
        $.ajax({
            url: '../controlador/COWORKING/crear_oficinaC.php',
            method: 'GET',
            data: { listaEspacios: true },
            dataType: 'json',
            success: function (response) {
                $('#tbl_espacios_body').html(response);
            }
        });
    }

    function enviarDatos() {
    // Obtiene los valores de los campos
    var nombre = $('#txt_name').val();
    var aforo = $('#txt_capacity').val();
    var precio = $('#txt_price').val();
    var estado = $('#txt_estadoEspacio').val();
    var categoria = $('#ddl_categoriaEspacio').val();

    // Verifica si algún campo está vacío
    if (!nombre || !aforo || !precio || !estado || !categoria) {
        // Si hay un campo vacío, muestra una alerta y detiene la función
        Swal.fire({
            title: 'Error',
            text: 'Todos los campos son obligatorios.',
            icon: 'error',
            confirmButtonText: 'Ok'
        });
        return;  // Detiene el envío de los datos
    }

    // Si todos los campos están completos, procede con el envío de los datos
    var datos = {
        nombre: nombre,
        aforo: aforo,
        precio: precio,
        estado: estado,
        categoria: categoria
    };

    $.ajax({
        url: '../controlador/COWORKING/crear_oficinaC.php',
        method: 'POST',
        data: { add: true, data: datos },
        success: function (response) {
            // Mostrar alerta de éxito si el espacio se agregó correctamente
            Swal.fire({
                title: 'Espacio agregado correctamente',
                icon: 'success',
                confirmButtonText: 'Ok'
            });

            // Luego recargar la lista de espacios
            listarEspacios();
        },
        error: function (xhr, status, error) {
            // En caso de error, mostrar otra alerta
            Swal.fire({
                title: 'Error al agregar espacio',
                text: 'Por favor, intenta nuevamente.',
                icon: 'error',
                confirmButtonText: 'Ok'
            });
        }
    });
}
    
    function listarMobiliario(id_espacio) {
        $.ajax({
            url: '../controlador/COWORKING/crear_oficinaC.php',
            method: 'GET',
            data: { listaMobiliario: true, id_espacio: id_espacio },
            success: function (response) {
                $('#tbl_furniture_body').html(response);
            }
        });
    }

    function enviarMobiliario() {
        var datos = {
            nombre: $('#txt_furniture_name').val(),
            cantidad: $('#txt_furniture_quantity').val(),
            id_espacio: $('#hidden_espacio_id').val()
        };

        $.ajax({
            url: '../controlador/COWORKING/crear_oficinaC.php',
            method: 'POST',
            data: { addMobiliario: true, data: datos },
            success: function (response) {
                alert('Mobiliario agregado correctamente');
                listarMobiliario(datos.id_espacio);
            },
            error: function (xhr, status, error) {
                console.error('Error al enviar los datos:', error);
                alert('Hubo un error al guardar los datos. Por favor, intenta de nuevo.');
            }
        });
    }

    function openFurnitureModal(id_espacio) {
        $('#hidden_espacio_id').val(id_espacio);
        listarMobiliario(id_espacio);
        $('#furnitureModal').modal('show');
    }
</script>
