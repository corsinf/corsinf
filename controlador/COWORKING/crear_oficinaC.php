<?php 
include(dirname(__DIR__, 2).'/modelo/COWORKING/ClaseEjemploM.php');

$controlador = new claseEjemplo();
// Obtener datos de un espacio específico
if (isset($_POST['getEspacio'])) {
    $id_espacio = $_POST['id_espacio'];
    echo json_encode($controlador->getEspacio($id_espacio));
}

// Listar categorías
if (isset($_GET['categoria'])) {
    echo json_encode($controlador->listaCategorias());
}

// Listar espacios
if (isset($_GET['listaEspacios'])) {
    echo json_encode($controlador->listaEspacios());
}

// Agregar espacio
if (isset($_POST['add'])) {
    $data = $_POST["data"];
    echo json_encode($controlador->add($data));
}

// Editar espacio
if (isset($_POST['edit'])) {
    $data = $_POST["data"];
    echo json_encode($controlador->edit($data));
}

// Eliminar espacio
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    if (isset($id) && is_numeric($id)) {
        $resultado = $controlador->delete($id);
        if ($resultado) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el espacio.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID inválido.']);
    }
}

// Agregar nueva categoría
if (isset($_POST['addCategoria'])) {
    $data = $_POST['data'];
    echo json_encode($controlador->addCategoria($data));
}

// Listar mobiliario para un espacio
if (isset($_GET['listaMobiliario'])) {
    $id_espacio = $_GET['id_espacio'];
    echo json_encode($controlador->listarMobiliario($id_espacio));
}

// Agregar mobiliario
if (isset($_POST['addMobiliario'])) {
    $data = $_POST["data"];
    echo json_encode($controlador->addMobiliario($data));
}

class claseEjemplo {
    private $modelo;

    function __construct() {
        $this->modelo = new claseEjemploM();
    }
    // Obtener datos de un espacio específico
    function getEspacio($id_espacio) {
        $espacio = $this->modelo->obtenerEspacio($id_espacio);
        if ($espacio) {
            return ['success' => true, 'espacio' => $espacio];
        } else {
            return ['success' => false, 'message' => 'No se pudo obtener el espacio.'];
        }
    }
    // Agregar espacio
    function add($parametros) {
        return $this->modelo->insertarnombre($parametros);
    }

    // Editar espacio
        function edit($parametros) {
            $resultado = $this->modelo->actualizarEspacio($parametros);
            if ($resultado) {
                return ['success' => true, 'message' => 'Espacio actualizado correctamente.'];
            } else {
                return ['success' => false, 'message' => 'No se pudo actualizar el espacio.'];
            }
        }

    // Eliminar espacio
    function delete($id_espacio) {
        return $this->modelo->eliminarEspacio($id_espacio);
    }

    // Listar categorías
    function listaCategorias() {
        $lista = $this->modelo->listarCategorias(); 
        $select = '';
        foreach ($lista as $value) {
            $select .= '<option value="'.$value['id_categoria'].'">'.$value['nombre_categoria'].'</option>';
        }
        return $select;
    }

    // Añadir categoría
    function addCategoria($parametros) {
        $datos[0]["campo"] = "nombre_categoria";
        $datos[0]["dato"] = $parametros["nombre"]; 
        return $this->modelo->insertarCategoria($datos, "co_categoria");
    }

    // Listar espacios
    function listaEspacios() {
        $lista = $this->modelo->listardebase();
        $tr = '';
        foreach ($lista as $value) {
            $estado = $value['estado_espacio'] == 'A' ? 'Inactivo' : 'Activo';
            $tr .= '<tr>
                <td>' . htmlspecialchars($value['id_espacio'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($value['nombre_espacio'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($value['aforo_espacio'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($value['precio_espacio'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($estado, ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($value['nombre_categoria'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>
                <button class="btn btn-sm btn-primary" onclick="editarEspacio(' . htmlspecialchars($value['id_espacio'], ENT_QUOTES, 'UTF-8') . ')"><i class="bx bx-edit"></i></button>
                <button class="btn btn-sm btn-danger" onclick="eliminarEspacio(this)" data-id="' . htmlspecialchars($value['id_espacio'], ENT_QUOTES, 'UTF-8') . '"><i class="bx bx-trash"></i></button>
                <button class="btn btn-sm btn-secondary" onclick="openFurnitureModal(' . htmlspecialchars($value['id_espacio'], ENT_QUOTES, 'UTF-8') . ')">Gestionar Mobiliario</button>
                </td>
                </tr>';
        }   
        return $tr;
    }

    // Agregar mobiliario
    function addMobiliario($parametros) {
        return $this->modelo->insertarMobiliario($parametros);
    }

    // Listar mobiliario por espacio
    function listarMobiliario($id_espacio) {
        $lista = $this->modelo->listarMobiliario($id_espacio);
        $tr = '';  
        foreach ($lista as $value) {
            $tr .= '<tr>
                        <td>'.htmlspecialchars($value['detalle_mobiliario'], ENT_QUOTES, 'UTF-8').'</td>
                        <td>'.htmlspecialchars($value['cantidad'], ENT_QUOTES, 'UTF-8').'</td>
                    </tr>';
        }
        $tr = str_replace(array("\r", "\n"), '', $tr);
        return $tr;
    }
}
?>
