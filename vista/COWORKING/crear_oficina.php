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
                <div class="d-flex align-items-center">
                    <select class="form-select me-2" id="ddl_categoriaEspacio" name="ddl_categoriaEspacio" required>
                        <option value="" disabled selected>Selecciona una categoría</option>
                        <!-- Aquí irán las opciones cargadas dinámicamente -->
                    </select>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal">
                    <i class='bx bx-plus-circle'></i>
                    </button>
                </div>
            </div>
        </form>
        
        
<!-- Modal para registrar nueva categoría -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel">Registrar Nueva Categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="categoryForm">
                    <div class="form-group mb-3">
                        <label for="category_name">Nombre de la Categoría:</label>
                        <input type="text" class="form-control" id="category_name" name="category_name" placeholder="Introduce el nombre de la categoría" required>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-primary" onclick="enviarCategoria()">Guardar Categoría</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Estado check -->

<form id="estadoForm" method="POST" action="controlador.php">
    <label for="estadoActivo">Estado:</label>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="estadoActivo" name="estado" value="activo" checked onclick="toggleEstado('activo')">
        <label class="form-check-label" for="estadoActivo">Activo</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="estadoInactivo" name="estado" value="inactivo" onclick="toggleEstado('inactivo')">
        <label class="form-check-label" for="estadoInactivo">Inactivo</label>
    </div>
</form>

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
                <!-- Contenedor flex para pestañas y botones -->
                <div class="d-flex justify-content-between mb-4">
                        <ul class="nav nav-pills mb-3" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active d-flex align-items-center" id="miembros-tab" data-bs-toggle="tab" data-bs-target="#miembros" type="button" role="tab" aria-controls="miembros" aria-selected="true">
                                <i class="lni lni-codepen"></i> <strong>Espacios</strong>
                                </button>
                            </li>
                            
                            <li class="nav-item" role="presentation">
                                <button class="nav-link d-flex align-items-center" id="servicios-tab" data-bs-toggle="tab" data-bs-target="#servicios" type="button" role="tab" aria-controls="servicios" aria-selected="false">
                                    <i class='bx bx-store-alt'></i> <strong>Mobiliario Extra</strong>
                                </button>
                            </li>
                        </ul>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class='bx bxs-report'></i><strong>Informe listado de espacios</strong>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="generarExcelEspacios()">Informe en Excel</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="generarPDFEspacios()">Informe en PDF</a></li>
                                    </ul>
                                </div>
                            
                </div>


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
 <!-- Modal para editar -->
 <div class="modal fade" id="editEspacioModal" tabindex="-1" aria-labelledby="editEspacioModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEspacioModalLabel">Editar Espacio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editEspacioForm">
                    <input type="hidden" id="edit_id_espacio" name="edit_id_espacio">
                    <div class="mb-3">
                        <label for="edit_nombre_espacio" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="edit_nombre_espacio" name="edit_nombre_espacio" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_aforo_espacio" class="form-label">Aforo</label>
                        <input type="number" class="form-control" id="edit_aforo_espacio" name="edit_aforo_espacio" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_precio_espacio" class="form-label">Precio</label>
                        <input type="number" class="form-control" id="edit_precio_espacio" name="edit_precio_espacio" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_estado_espacio" class="form-label">Estado</label>
                        <select class="form-select" id="edit_estado_espacio" name="edit_estado_espacio" required>
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                <label for="ddl_categoriaEspacio">Categoría:</label>
                <div class="d-flex align-items-center">
                    <select class="form-select me-2" id="ddl_categoriaEspacio" name="ddl_categoriaEspacio" required>
                        <option value="" disabled selected>Selecciona una categoría</option>
                        <!-- Aquí irán las opciones cargadas dinámicamente -->
                    </select>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal">
                    <i class='bx bx-plus-circle'></i>
                    </button>
                </div>
            </div>
        </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="guardarEdicion()">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>
             <!-- Modal -->
<div class="modal fade" id="furnitureModal" tabindex="-1" aria-labelledby="furnitureModalLabel" data-id_espacios="1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="furnitureModalLabel" onclick="abrirModalMobiliario(id_espacio)">Gestionar Mobiliario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h3 id="lbl_furniture" class="text-center mb-3">Mobiliario</h3>

                <form id="furniture_form">
                    <div class="form-group mb-3">
                        <label for="txt_furniture_detail">Detalle del Mueble:</label>
                        <input type="text" class="form-control" name="txt_furniture_detail" id="txt_furniture_detail" placeholder="Introduce el detalle del mueble" required>
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
                <div class="btn-group me-2">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class='bx bxs-report'></i><strong>Informe de Mobiliario</strong>
                                    </button>
                                    <div class="d-flex align-items-center">
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="generarExcelMobiliario()">Informe en Excel</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="generarPDFMobiliario()">Informe en PDF</a></li>
                                    </ul>
                                    </div>
                    </div>
                

                <table id="tbl_furniture" class="table table-bordered mb-4">
                    <thead class="thead-dark">
                        <tr>
                            <th>Detalle del mueble</th>
                            <th>Cantidad</th>
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
    function toggleEstado(estado) {
        const checkboxActivo = document.getElementById('estadoActivo');
        const checkboxInactivo = document.getElementById('estadoInactivo');

        if (estado === 'activo') {
            checkboxActivo.checked = true;
            checkboxInactivo.checked = false;
        } else {
            checkboxActivo.checked = false;
            checkboxInactivo.checked = true;
        }
        
    function abrirModalMobiliario(id_espacio) {
        
        document.getElementById("hidden_espacio_id").value = id_espacio;

        // Muestra el modal
        var myModal = new bootstrap.Modal(document.getElementById('furnitureModal'));
        myModal.show();
}

}
    function generarPDFMobiliario() {

        var id_espacio = document.getElementById("hidden_espacio_id").value;
        console.log("ID del espacio para PDF: ", id_espacio);
        var url = '../controlador/COWORKING/crear_oficinaC.php?generarPDFMobiliario=true&id_espacio=' + id_espacio;
        window.open(url, "_blank");
    }

    function generarPDFEspacios() {
                var url ='../controlador/COWORKING/crear_oficinaC.php?generarPDFEspacios=true'
                window.open(url,"_blank");
            }

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
    
    function editarEspacio(id_espacio) {
    $.ajax({
        url: '../controlador/COWORKING/crear_oficinaC.php',
        method: 'POST',
        data: { getEspacio: true, id_espacio: id_espacio },
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                $('#edit_id_espacio').val(data.espacio.id_espacio);
                $('#edit_nombre_espacio').val(data.espacio.nombre_espacio);
                $('#edit_aforo_espacio').val(data.espacio.aforo_espacio);
                $('#edit_precio_espacio').val(data.espacio.precio_espacio);
                $('#edit_estado_espacio').val(data.espacio.estado_espacio);
                $('#edit_categoria_espacio').val(data.espacio.id_categoria);
                
                // Mostrar el modal
                $('#editEspacioModal').modal('show');
            } else {
                Swal.fire(
                    'Error',
                    'No se pudieron cargar los datos del espacio.',
                    'error'
                );
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar los datos del espacio:', error);
            Swal.fire(
                'Error',
                'Hubo un problema al cargar los datos del espacio.',
                'error'
            );
        }
    });
}

function guardarEdicion() {
    var formData = {
        id_espacio: $('#edit_id_espacio').val(),
        nombre: $('#edit_nombre_espacio').val(),
        aforo: $('#edit_aforo_espacio').val(),
        precio: $('#edit_precio_espacio').val(),
        estado: $('#edit_estado_espacio').val(),
        categoria: $('#edit_categoria_espacio').val()
    };

    $.ajax({
        url: '../controlador/COWORKING/crear_oficinaC.php',
        method: 'POST',
        data: { edit: true, data: formData },
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                Swal.fire('Guardado', 'Los cambios han sido guardados.', 'success');
                $('#editEspacioModal').modal('hide');
                listarEspacios(); // Recargar la lista de espacios
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al guardar los cambios:', error);
            Swal.fire('Error', 'Hubo un problema al guardar los cambios.', 'error');
        }
    });
}
function eliminarEspacio(button) {
    // Obtener el ID del espacio del atributo data-id del botón
    var idEspacio = $(button).data('id');

    // Usar SweetAlert2 para la confirmación
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Una vez eliminado, no podrás recuperar este espacio.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '../controlador/COWORKING/crear_oficinaC.php',
                method: 'POST',
                data: { delete: true, id: idEspacio },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
        
                        Swal.fire(
                            'Eliminado',
                            'El espacio ha sido eliminado.',
                            'success'
                        ).then(() => {
                            $(button).closest('tr').remove();
                        });
                    } else {
                        // Mostrar mensaje de error
                        Swal.fire(
                            'Error',
                            'No se pudo eliminar el espacio.',
                            'error'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al eliminar el espacio:', error);
                    Swal.fire(
                        'Error',
                        'Hubo un problema al eliminar el espacio.',
                        'error'
                    );
                }
            });
        }
    });
}

function enviarDatos() {
    var nombre = $('#txt_name').val();
    var aforo = $('#txt_capacity').val();
    var precio = $('#txt_price').val();
    var categoria = $('#ddl_categoriaEspacio').val();

    // Obtener los valores de los checkboxes
    var estados = [];
    if ($('#estadoActivo').is(':checked')) {
        estados.push('activo');
    }
    if ($('#estadoInactivo').is(':checked')) {
        estados.push('inactivo');
    }

    
    if (!nombre || !aforo || !precio || !categoria || estados.length === 0) {
        Swal.fire({
            title: 'Error',
            text: 'Todos los campos son obligatorios y debe seleccionar al menos un estado.',
            icon: 'error',
            confirmButtonText: 'Ok'
        });
        return;  
    }

    var datos = {
        nombre: nombre,
        aforo: aforo,
        precio: precio,
        estados: estados,  
        categoria: categoria
    };

    $.ajax({
        url: '../controlador/COWORKING/crear_oficinaC.php',
        method: 'POST',
        data: { add: true, data: datos },
        success: function (response) {
            Swal.fire({
                title: 'Espacio agregado correctamente',
                icon: 'success',
                confirmButtonText: 'Ok'
            });
            listarEspacios();
        },
        error: function (xhr, status, error) {
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
            dataType:"json",
            data: { listaMobiliario: true, id_espacio: id_espacio },
            success: function (response) {
                $('#tbl_furniture_body').html(response);    
            }
        });
    }

    function enviarMobiliario() {
    var datos = {
        detalle: $('#txt_furniture_detail').val(),  
        cantidad: $('#txt_furniture_quantity').val(),
        id_espacio: $('#hidden_espacio_id').val()
    };

    if (!datos.detalle || !datos.cantidad || !datos.id_espacio) {
        Swal.fire({
            title: 'Error',
            text: 'Todos los campos son obligatorios.',
            icon: 'error',
            confirmButtonText: 'Ok'
        });
        return;  
    }

    $.ajax({
        url: '../controlador/COWORKING/crear_oficinaC.php',
        method: 'POST',
        data: { addMobiliario: true, data: datos },
        success: function (response) {
            Swal.fire({
                title: 'Mobiliario agregado correctamente',
                icon: 'success',
                confirmButtonText: 'Ok'
            });
            listarMobiliario(datos.id_espacio); 
        },
        error: function (xhr, status, error) {
            Swal.fire({
                title: 'Error al agregar mobiliario',
                text: 'Hubo un problema. Por favor, intenta nuevamente.',
                icon: 'error',
                confirmButtonText: 'Ok'
            });
        }
    });
}


        function openFurnitureModal(id_espacio) {
        $('#hidden_espacio_id').val(id_espacio);    
        listarMobiliario(id_espacio);
        $('#furnitureModal').modal('show');
    }

    function enviarCategoria() {
    var nombreCategoria = $('#category_name').val();
    
    if (nombreCategoria.trim() === "") {
        Swal.fire('Error', 'El nombre de la categoría no puede estar vacío.', 'error');
        return;
    }

    var datos = {
        nombre: nombreCategoria
    };

    $.ajax({
        url: '../controlador/COWORKING/crear_oficinaC.php', 
        method: 'POST',
        data: { addCategoria: true, data: datos },
        success: function(response) {
            if (response==1){
            Swal.fire('Éxito', 'Categoría agregada correctamente.', 'success');
            $('#categoryModal').modal('hide');
            lista_categorias(); 
            }
            else{console.log("Error al inicia")}
        },
            
        error: function(xhr, status, error) {
            console.error('Error al enviar los datos:', error);
            Swal.fire('Error', 'Hubo un error al agregar la categoría. Por favor, intenta de nuevo.', 'error');
        }
    });
}

</script>
