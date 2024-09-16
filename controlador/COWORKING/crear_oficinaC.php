<?php
include(dirname(__DIR__, 2).'/modelo/COWORKING/ClaseEjemploM.php');

$controlador = new claseEjemplo();

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
    echo json_encode($controlador->delete($id));
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

    // Agregar espacio
    function add($parametros) {
        return $this->modelo->insertarnombre($parametros);
    }
    
    // Editar espacio
    function edit($parametros) {
        return $this->modelo->actualizarEspacio($parametros);
    }

    // Eliminar espacio
    function delete($id) {
        return $this->modelo->eliminarEspacio($id);
    }

    // Agregar nueva categoría
    function addCategoria($parametros) {
        return $this->modelo->insertarCategoria($parametros);
    }

    // Listar categorías
    function listaCategorias() {
        $lista = $this->modelo->listarCategorias(); 
        $select = '';
        foreach ($lista as $value) {
            $select .= '<option value="'.$value['id'].'">'.$value['Nombre'].'</option>';
        }
        return $select;
    }

    // Listar espacios
    function listaEspacios() {
        $lista = $this->modelo->listardebase();
        $tr = '';
        foreach ($lista as $value) {
            $tr .= '<tr>
                <td>'.htmlspecialchars($value['id_espacio'], ENT_QUOTES, 'UTF-8').'</td>
                <td>'.htmlspecialchars($value['nombre_espacio'], ENT_QUOTES, 'UTF-8').'</td>
                <td>'.htmlspecialchars($value['aforo_espacio'], ENT_QUOTES, 'UTF-8').'</td>
                <td>'.htmlspecialchars($value['precio_espacio'], ENT_QUOTES, 'UTF-8').'</td>
                <td>'.htmlspecialchars($value['estado_espacio'], ENT_QUOTES, 'UTF-8').'</td>
                <td>'.htmlspecialchars($value['id_categoria'], ENT_QUOTES, 'UTF-8').'</td>
                <td>
                <button class="btn btn-sm btn-primary" onclick="editarEspacio('.$value['id_espacio'].')"><i class="bx bx-edit"></i></button>
                <button class="btn btn-sm btn-danger" onclick="eliminarEspacio('.$value['id_espacio'].')"><i class="bx bx-trash"></i></button>
                </td>
               <button class="btn btn-sm btn-secondary" onclick="openFurnitureModal('.htmlspecialchars($value['id_espacio'], ENT_QUOTES, 'UTF-8').')">Gestionar Mobiliario</button>
            </tr>';
        }   
        return $tr;
    }
    
    // Agregar mobiliario
    function addMobiliario($parametros) {
        $res = $this->modelo->insertarMobiliario($parametros);
        return $res;
    }

    // Listar mobiliario por espacio
    function listarMobiliario($id_espacio) {
        $lista = $this->modelo->listarMobiliario($id_espacio);
        $tr = '';  // Inicialización corregida
        foreach ($lista as $value) {
            $tr .= '<tr>
                        <td>'.htmlspecialchars($value['nombre_mobiliario'], ENT_QUOTES, 'UTF-8').'</td>
                        <td>'.htmlspecialchars($value['cantidad'], ENT_QUOTES, 'UTF-8').'</td>
                        <td><button class="btn btn-sm btn-danger" onclick="eliminarMobiliario('.$value['id'].')">Eliminar</button></td>
                    </tr>';
        }
        return $tr;
    }
}

?>