<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alquiler de Espacios</title>
    <style>
        body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f7f6;
    margin: 0;
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.container {
    max-width: 600px;
    padding: 20px 40px;
    background-color: #ffffff;
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    color: #333;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #333;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 12px 15px;
    box-sizing: border-box;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #fafafa;
    transition: border-color 0.3s, background-color 0.3s;
}

.form-group input:focus,
.form-group select:focus {
    border-color: #007bff;
    background-color: #fff;
    outline: none;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background-color: #ffffff;
    border: 1px solid #ddd;
    border-radius: 5px;
    overflow: hidden;
}

th, td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #f9f9f9;
    font-weight: bold;
}

tr:hover {
    background-color: #f1f1f1;
}

.btn-add, .btn-submit {
    display: inline-block;
    padding: 10px 20px;
    margin-top: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
    text-align: center;
}

.btn-add:hover, .btn-submit:hover {
    background-color: #0056b3;
    transform: scale(1.05);
}

.btn-remove {
    padding: 8px 15px;
    background-color: #dc3545;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
}

.btn-remove:hover {
    background-color: #c82333;
    transform: scale(1.1);
}

    </style>
    
</head>
<body>
    <div class="container" id="pnl_container">
        <h2 id="lbl_form_title"> Alquiler de Espacios</h2>
        <form id="rental_form">
            <div class="form-group">
                <label for="txt_name">Nombre del Espacio:</label>
                <input type="text" class="form-control" name="txt_name" id="txt_name" placeholder="Introduce el nombre del espacio" required>
            </div>
            <div class="form-group">
                <label for="ddl_category">Categoría:</label>
                <select class="form-control" name="ddl_category" id="ddl_category" required>
                    <option value="">Selecciona una categoría</option>
                    <option value="Sala de Reuniones">Sala de Reuniones</option>
                    <option value="Auditorio">Auditorio</option>
                    <option value="Aula">Aula</option>
                    <option value="Oficina">Oficina</option>
                </select>
            </div>
            <div class="form-group">
                <label for="txt_capacity">Aforo:</label>
                <input type="number" class="form-control" name="txt_capacity" id="txt_capacity" placeholder="Introduce el aforo" required>
            </div>
            <div class="form-group">
                <label for="txt_price">Precio:</label>
                <input type="number" class="form-control" name="txt_price" id="txt_price" placeholder="Introduce el precio" required>
            </div>
            <h3 id="lbl_furniture">Mueblería</h3>
            <table id="tbl_furniture">
                <thead>
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
                        <td><button type="button" class="btn-remove" onclick="remove_row(this)">Eliminar</button></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="btn-add" onclick="add_row()">Agregar Mueble</button>
            <button type="submit" class="btn-submit">Enviar</button>
        </form>
    </div>

    <script>
        function add_row() {
            const table = document.getElementById('tbl_furniture').getElementsByTagName('tbody')[0];
            const new_row = table.insertRow();
            new_row.innerHTML = `
                <td><input type="text" class="form-control" name="txt_furniture_name[]" placeholder="Nombre del mueble" required></td>
                <td><input type="number" class="form-control" name="txt_furniture_quantity[]" placeholder="Cantidad" required></td>
                <td><button type="button" class="btn-remove" onclick="remove_row(this)">Eliminar</button></td>
            `;
        }

        function remove_row(button) {
            const row = button.parentElement.parentElement;
            row.remove();
        }

        document.getElementById('rental_form').addEventListener('submit', function(event) {
            event.preventDefault();
            alert('Formulario enviado!');
        });
    </script>
</body>
</html>
