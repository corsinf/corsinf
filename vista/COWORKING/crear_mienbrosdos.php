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
                                <!-- Botón para agregar un nuevo producto -->
                                <button class="btn btn-primary btn-new-product">
                                    <i class="bi bi-plus-circle"></i> New espacio
                                </button>

                                <!-- Agrupación de búsqueda y filtros -->
                                <div class="d-flex align-items-center">
                                    <!-- Input con ícono de búsqueda -->
                                    <div class="input-group me-3" style="width: 300px;">
                                        <input type="text" class="form-control" placeholder="Search Product...">
                                        <button class="btn btn-outline-secondary" type="button">
                                            <i class="bx bx-search"></i>
                                        </button>
                                    </div>

                                    <!-- Selector de ordenamiento -->
                                    <select class="form-select me-3" style="width: 150px;">
                                        <option selected>Sort By</option>
                                        <option value="1">Price</option>
                                        <option value="2">Popularity</option>
                                    </select>

                                    <!-- Selector de tipo de colección -->
                                    <select class="form-select me-3" style="width: 180px;">
                                        <option selected>Collection Type</option>
                                        <option value="1">Furniture</option>
                                        <option value="2">Decor</option>
                                    </select>

                                    <!-- Selector de rango de precio -->
                                    <select class="form-select" style="width: 130px;">
                                        <option selected>Price Range</option>
                                        <option value="1">0 - 100</option>
                                        <option value="2">100 - 500</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Aquí puedes continuar con el resto del contenido, como la lista de productos -->
                            <div class="row">
                                <!-- Product 1 -->
                                <div class="col-md-3 mb-4">
                                    <div class="product-card ms-2"> <!-- Agrega margen a la izquierda -->
                                        <img src="https://media.istockphoto.com/id/157334256/es/foto/auditorio.jpg?s=1024x1024&w=is&k=20&c=FcTLVow6mKMcNk76ybgTuo04L39IOB3qnsZHDN1h4xI=" alt="Product Image" class="product-image" style="width: 150px; height: auto;">
                                        <h5 class="product-title mt-4">Nest Shaped Chair</h5>
                                        <p>134 Sales</p>
                                        <div class="product-price">
                                            <span class="product-old-price">$350</span>
                                            <span>$240</span>
                                        </div>
                                        <div class="product-rating">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star"></i>
                                        </div>
                                        <p class="text-muted">4.2 (182)</p>
                                        <button class="btn btn-primary" onclick="window.location.href='http://localhost/corsinf/vista/inicio.php?mod=1010&acc=crear_mienbros';">Detalles</button>
                                    </div>
                                </div>

                                <!-- Puedes repetir las tarjetas de producto según sea necesario -->
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
