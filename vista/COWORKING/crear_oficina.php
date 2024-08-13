<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alquiler de Espacios</title>

    <!-- CSS Styles -->
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap-extended.css">
    <link rel="stylesheet" href="pace.min.css">
    <link rel="stylesheet" href="semi-dark.css">
    <link rel="stylesheet" href="icons.css">
    <link rel="stylesheet" href="app.css">
    <link rel="stylesheet" href="header-colors.css">
    <link rel="stylesheet" href="dark-theme.css">

</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
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

                            <h3 id="lbl_furniture" class="text-center mb-3">Mobiliario</h3>
                            <table id="tbl_furniture" class="table table-bordered mb-4">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Nombre del Modal</th>
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
                                <button type="button" class="btn btn-success btn-sm mb-3">Agregar Mueble</button>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg">Enviar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

  
    <!-- Bootstrap JS -->
    <script src="bootstrap.bundle.min.js"></script>
</body>
</html>
