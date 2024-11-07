<?php
require_once(dirname(__DIR__, 2) . '/modelo/COWORKING/ClaseEjemploM.php'); // Incluye la clase

$buscar = isset($_GET['buscar']) ? $_GET['buscar'] : '';
$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$rango_precio = isset($_GET['rango_precio']) ? $_GET['rango_precio'] : '';
$estado = isset($_GET['estado']) ? $_GET['estado'] : '';

$ejemplo = new claseEjemploM(); // Crea una instancia de la clase
$resultado = $ejemplo->listardebase($buscar, '', $categoria, $rango_precio, $estado);

$id_espacio = $resultado ? $resultado : []; // Asigna $resultado a $id_espacio o un array vacío
?>

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
                            <a href="/"><i class="bx bx-home"></i></a>
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
                                            <input type="search" name="buscar" id="buscar" value="<?php echo htmlspecialchars($buscar); ?>" class="form-control" placeholder="Buscar Espacio...">
                                            <button class="btn btn-outline-secondary" type="button">
                                                <i class="bx bx-search"></i>
                                            </button>
                                        </div>

                                        <!-- Selector de rango de precio -->
                                        <select name="rango_precio" id="rango_precio" class="form-select me-3" style="width: 130px;">
                                            <option value="" <?php echo $rango_precio == '' ? 'selected' : ''; ?>>Rango de Precio</option>
                                            <option value="1" <?php echo $rango_precio == '1' ? 'selected' : ''; ?>>0 - 5</option>
                                            <option value="2" <?php echo $rango_precio == '2' ? 'selected' : ''; ?>>100 - 500</option>
                                        </select>

                                        <!-- Selector de estado -->
                                        <select name="estado" id="estado" class="form-select me-3" style="width: 130px;">
                                            <option value="" <?php echo $estado == '' ? 'selected' : ''; ?>>Estado</option>
                                            <option value="A" <?php echo $estado == 'A' ? 'selected' : ''; ?>>Activo</option>
                                            <option value="I" <?php echo $estado == 'I' ? 'selected' : ''; ?>>Inactivo</option>
                                        </select>
                                    </form>
                                </div>
                            </div>

                            <!-- Mensaje de "sin resultados" -->
                            <div id="noResultsMessage" class="text-center text-muted my-4" style="display: none;">
                                No se encontraron espacios que coincidan con la búsqueda.
                            </div>

                            <!-- Lista de Espacios -->
                            <div class="row" id="espaciosLista">
                                <?php foreach ($resultado as $espacio): ?>
                                    <div class="col-md-3 mb-4 espacio" 
                                        data-nombre="<?php echo htmlspecialchars($espacio['nombre_espacio']); ?>"
                                        data-estado="<?php echo strtoupper($espacio['estado_espacio']); ?>"
                                        data-categoria="<?php echo htmlspecialchars($espacio['nombre_categoria']); ?>"
                                        data-precio="<?php echo htmlspecialchars($espacio['precio_espacio']); ?>">

                                        <div class="product-card ms-2">
                                            <img src="https://media.istockphoto.com/id/157334256/es/foto/auditorio.jpg?s=1024x1024&w=is&k=20&c=FcTLVow6mKMcNk76ybgTuo04L39IOB3qnsZHDN1h4xI=" alt="Product Image" class="product-image img-fluid" style="width: 150px; height: auto;">
                                            <h5 class="product-title mt-4"><big><?php echo htmlspecialchars($espacio['nombre_espacio']); ?></big></h5>

                                            <p><strong>Espacio:</strong> <?php echo htmlspecialchars($espacio['id_espacio']); ?></p>
                                            <p><strong>Categoría:</strong> <?php echo htmlspecialchars($espacio['nombre_categoria']); ?></p>
                                            <p><strong>Aforo de personas:</strong> <?php echo htmlspecialchars($espacio['aforo_espacio']); ?></p>

                                            <div class="product-price">
                                                <span><strong>Precio:</strong> $<?php echo htmlspecialchars($espacio['precio_espacio']); ?></span>
                                            </div>
                                            
                                            <strong><p class="text-muted">Estado:</strong> 
                                                <?php echo (trim(strtoupper($espacio['estado_espacio'])) === 'A') ? 'Activo' : 'Inactivo'; ?>
                                            </p>

                                            <!-- Botón de detalles sin condición de estado -->
                                            <button class="btn btn-primary" onclick="window.location.href='http://localhost/corsinf/vista/inicio.php?mod=1010&acc=crear_mienbros';">Detalles</button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterForm = document.getElementById('filterForm');
    const buscarInput = document.getElementById('buscar');
    const rangoPrecioSelect = document.getElementById('rango_precio');
    const estadoSelect = document.getElementById('estado');
    const espaciosLista = document.getElementById('espaciosLista');
    const espacios = Array.from(espaciosLista.getElementsByClassName('espacio'));
    const noResultsMessage = document.getElementById('noResultsMessage');

    // Función para filtrar espacios según los criterios
    function filterEspacios() {
        const searchTerm = buscarInput.value.toLowerCase();
        const selectedPriceRange = rangoPrecioSelect.value;
        const selectedState = estadoSelect.value.toUpperCase();
        let hasVisibleEspacio = false; // Bandera para verificar si hay coincidencias visibles

        espacios.forEach(function(espacio) {
            const nombre = espacio.getAttribute('data-nombre').toLowerCase();
            const estado = espacio.getAttribute('data-estado').toUpperCase();
            const categoria = espacio.getAttribute('data-categoria').toLowerCase();
            const precio = parseFloat(espacio.getAttribute('data-precio'));

            // Filtrar por nombre, estado y categoría
            const matchesSearch = nombre.includes(searchTerm) || estado.includes(searchTerm) || categoria.includes(searchTerm);

            // Filtrar por estado
            const matchesState = selectedState ? estado === selectedState : true;

            // Filtrar por precio
            let matchesPriceRange = true;
            if (selectedPriceRange === '1' && (precio < 0 || precio > 5)) {
                matchesPriceRange = false;
            } else if (selectedPriceRange === '2' && (precio < 100 || precio > 500)) {
                matchesPriceRange = false;
            }

            // Mostrar u ocultar espacio según los filtros
            if (matchesSearch && matchesPriceRange && matchesState) {
                espacio.style.display = '';
                hasVisibleEspacio = true;
            } else {
                espacio.style.display = 'none';
            }
        });

        // Mostrar o ocultar mensaje de "sin resultados"
        noResultsMessage.style.display = hasVisibleEspacio ? 'none' : 'block';
    }

    // Agregar eventos de cambio y entrada para activar la función de filtro
    filterForm.addEventListener('submit', function (event) {
        event.preventDefault();
        filterEspacios();
    });
    buscarInput.addEventListener('input', filterEspacios);
    rangoPrecioSelect.addEventListener('change', filterEspacios);
    estadoSelect.addEventListener('change', filterEspacios);

    // Ejecutar filtro al cargar la página
    filterEspacios();
});
</script>











