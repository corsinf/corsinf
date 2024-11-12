<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb Section -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3"><strong>Espacios</strong></div>
            <div class="breadcrumb-separator"></div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:;"><i class="bx bx-home"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <strong>Lista de espacios</strong>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Content Section -->
        <div class="row">
            <div class="col-xl-12 mx-auto">
                <hr>
                <div class="card">
                    <div class="card-body">
                        <!-- Formulario Registro Miembros -->
                        <h1 class="titulo mb-4">Espacios</h1>

                        <!-- Sección de Productos -->
                        <div class="container my-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <!-- Agrupación de búsqueda y filtros -->
                                <div class="d-flex align-items-center">
                                    <!-- Formulario de búsqueda y filtros -->
                                    <form id="filterForm" method="GET" class="d-flex">
                                        <div class="input-group me-3" style="width: 300px;">
                                            <input type="search" name="buscar" id="buscar" class="form-control" placeholder="Buscar Espacio..." onkeyup='lista_espaciostarjetas()'>
                                            <button class="btn btn-outline-secondary" type="button">
                                                <i class="bx bx-search"></i>
                                            </button>
                                        </div>

                                        <!-- Selector de rango de precio con onchange -->
                                        <select name="rango_precio" id="rango_precio" class="form-select me-3" style="width: 130px;" onchange="lista_espaciostarjetas()">
                                            <option value="">Rango de Precio</option>
                                            <option value="1">$1 - $100</option>
                                            <option value="2">$100 - $500</option>
                                        </select>

                                        <!-- Selector de estado -->
                                        <select name="estado" id="estado" class="form-select me-3" style="width: 130px;" onchange="lista_espaciostarjetas()">
                                            <option value="">Estado</option>
                                            <option value="A">Activo</option>
                                            <option value="I">Inactivo</option>
                                        </select>
                                    </form>
                                </div>
                            </div>

                            <!-- Mensaje de "sin resultados" -->
                            <div id="noResultsMessage" class="text-center text-muted my-4" style="display: none;">
                                <big><strong>No se encontraron espacios que coincidan con la búsqueda.</strong></big>
                            </div>

                            <!-- Lista de Espacios -->
                            <div class="row" id="espaciosLista">
                                <!-- Los espacios filtrados se mostrarán aquí -->
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
    lista_espaciostarjetas();
});

function lista_espaciostarjetas() {
    var parametros = {
        'nombre_espacio': $('#buscar').val(),
        'rango_precio': $('#rango_precio').val(),
        'estado': $('#estado').val(),
    };

    $.ajax({
        url: '../controlador/COWORKING/crear_miembrosdosC.php?lista_tarjetas=true',
        type: 'post',
        data: { data: parametros },
        dataType: 'json',
        success: function(response) {
            if (response.length === 0) {
                // Si no hay resultados, mostramos el mensaje
                $('#noResultsMessage').show();
                $('#espaciosLista').html(''); // Limpiar cualquier espacio previo mostrado
            } else {
                // Si hay resultados, mostramos las tarjetas
                $('#noResultsMessage').hide();
                $('#espaciosLista').html(response);
            }
            console.log(response);
        },
        error: function() {
            // Manejo de errores, en caso de que la solicitud AJAX falle
            $('#noResultsMessage').show();
            $('#espaciosLista').html('');
        }
    });
}



</script>












