<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .page-wrapper {
            padding: 20px;
        }
        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .card-body {
            padding: 20px;
        }
        .card-title {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .card-title h5 {
            color: #007bff;
            margin: 0;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="password"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            width: 100%;
        }
        button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .breadcrumb {
            background: none;
            padding: 0;
        }
        .breadcrumb-item a {
            color: #007bff;
        }
        .breadcrumb-item.active {
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="page-content">
            <!-- Formulario Persona Natural -->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Persona Natural</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Formulario Persona Natural</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="card border-top border-0 border-4 border-primary">
                <div class="card-body p-5">
                    <div class="card-title d-flex align-items-center">
                        <h5 class="mb-0 text-primary">Formulario Persona Natural</h5>
                    </div>
                    <form id="form_persona_natural">
                        <label for="txt_nombre_completo">Nombres Completos:</label>
                        <input type="text" id="txt_nombre_completo" name="txt_nombre_completo">
                        <label for="txt_ruc">Número de RUC:</label>
                        <input type="text" id="txt_ruc" name="txt_ruc">
                        <label for="txt_direccion_domicilio">Dirección Domicilio:</label>
                        <input type="text" id="txt_direccion_domicilio" name="txt_direccion_domicilio">
                        <label for="txt_provincia">Provincia:</label>
                        <input type="text" id="txt_provincia" name="txt_provincia">
                        <label for="txt_ciudad">Ciudad:</label>
                        <input type="text" id="txt_ciudad" name="txt_ciudad">
                        <label for="txt_correo">Dirección Correo Electrónico Válido:</label>
                        <input type="email" id="txt_correo" name="txt_correo">
                        <label for="txt_celular">No. Celular (poner código de país):</label>
                        <input type="tel" id="txt_celular" name="txt_celular">
                        <label for="txt_fijo">No. Fijo (poner código de país):</label>
                        <input type="tel" id="txt_fijo" name="txt_fijo">
                        <button type="submit">Enviar</button>
                    </form>
                </div>
            </div>