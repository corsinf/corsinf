<?php 
include(dirname(__DIR__, 2).'/modelo/COWORKING/ClaseEjemploM.php');

$controlador = new claseEjemplo();

if (isset($_GET['categoria'])) {
    echo json_encode($controlador->listaCategorias());
}

if (isset($_GET['listaEspacios'])) {
    echo json_encode($controlador->listaEspacios());
}

if (isset($_POST['add'])) {
    $data = $_POST["data"];
    echo json_encode($controlador->add($data));
}

class claseEjemplo {
    private $modelo;

    function __construct() {
        $this->modelo = new claseEjemploM();
    }

    function add($parametros) {
        $res = $this->modelo->insertarnombre($parametros);
        return $res;
    }

    function listaCategorias() {
        $lista = [
            ['Nombre' =>'Categoria 1', 'id'=>1],
            ['Nombre' =>'Categoría 2', 'id'=>2],
            ['Nombre' =>'Categoría 3', 'id'=>3]
        ];

        $select = '';
        foreach ($lista as $value) {
            $select .= '<option value="'.$value['id'].'">'.$value['Nombre'].'</option>';
        }

        return $select;
    }

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
                <td><button class="btn btn-sm btn-primary"><i class="bx bx-save"></i></button></td>
                <td><button class="btn btn-sm btn-primary" onclick="openFurnitureModal('.htmlspecialchars($value['id_espacio'], ENT_QUOTES, 'UTF-8').')">Gestionar Mobiliario</button></td>
            </tr>';
        }
    
        return $tr;
    }
    
    function addMobiliario($parametros) {
        $res = $this->modelo->insertarMobiliario($parametros);
        return $res;
    }

    function listarMobiliario($id_espacio) {
        $lista = $this->modelo->listarMobiliario($id_espacio);
        $tr = '';
        foreach ($lista as $value) {
            $tr .= '<tr>
                        <td>'.$value['nombre_mobiliario'].'</td>
                        <td>'.$value['cantidad'].'</td>
                        <td><button class="btn btn-sm btn-danger" onclick="eliminarMobiliario('.$value['id'].')">Eliminar</button></td>
                    </tr>';
        }
        return $tr;
    }
}

$controlador = new claseEjemplo();

if (isset($_GET['listaMobiliario'])) {
    $id_espacio = $_GET['id_espacio'];
    echo json_encode($controlador->listarMobiliario($id_espacio));
}

if (isset($_GET['addMobiliario'])) {
    $data = $_POST["data"];
    echo json_encode($controlador->addMobiliario($data));
}

?>
