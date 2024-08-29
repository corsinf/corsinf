<?php
include(dirname(__DIR__, 2) . '/modelo/COWORKING/crear_mienbrosM.php');

$controlador = new crear_mienbrosC();
$id_miembro = isset($_GET['id_miembro']) ? intval($_GET['id_miembro']) : '';

if (isset($_GET['lista_mienbro'])) {
    echo json_encode($controlador->listar());
}


if (isset($_GET['listar_productos'])) {
    echo json_encode($controlador->listar_productos());
}

if (isset($_GET['lista_compra'])) {
    echo json_encode($controlador->compraslista());
}

if (isset($_GET['add'])) {
    $data = isset($_POST['data']) ? $_POST['data'] : [];
    echo json_encode($controlador->add($data));
}

if (isset($_GET['add_compra'])) {
    $data = isset($_POST['data']) ? $_POST['data'] : [];
    echo json_encode($controlador->add_compra($data));
}

class crear_mienbrosC
{
    private $modelo;
    
    function __construct() 
    {
        $this->modelo = new crear_mienbrosM;
    }

    function listar_productos()
    {
        $slista = array(
            array('id_producto' => 1, 'Producto' => 'Producto1', 'Precio' => 10.00),
            array('id_producto' => 2, 'Producto' => 'Producto2', 'Precio' => 20.00),
            array('id_producto' => 3, 'Producto' => 'Producto3', 'Precio' => 30.00),
            array('id_producto' => 4, 'Producto' => 'Producto4', 'Precio' => 40.00),
            array('id_producto' => 5, 'Producto' => 'Producto5', 'Precio' => 50.00)
        );
    
        $str = '';
        foreach ($slista as $key => $value) {
            $str .= '<option value="' . ($value['id_producto']) . '" data-precio="' . ($value['Precio']) . '">' . ($value['Producto']) . '</option>';
        }
        return $str;
    }

    function compraslista()
    {
        $slista = $this->modelo->compraslista();

;

        $str = '';
        foreach ($slista as $key => $value) {
            $id_miembro = isset($value['id_miembro']) ? $value['id_miembro'] : 'id_miembro';
            $str .= '<tr>
                        <td>' . $id_miembro . '</td>
                        <td>' . ($value['id_producto']) . '</td>
                        <td>' . ($value['cantidad_compra']) . '</td>
                        <td>' . ($value['pvp_compra']) . '</td>
                        <td>' . ($value['total_compra']) . '</td>
                        
                        <td>
                            <button type="button" class="btn btn-danger btn-sm">
                                <i class="bx bx-trash"></i>
                            </button>
                        </td>
                    </tr>';
        }

        return $str;
    }

    function add($parametros)
    {
        $res = $this->modelo->insertarnombre($parametros);
        return $res;
    }

    function add_compra($parametros)
    {
        $res = $this->modelo->insertarcompra($parametros);
        return $res;
    }
    
    function listar()
    {
        $slista = $this->modelo->listardebase();
        $str = '';
        foreach ($slista as $key => $value) {
            $id_miembro = isset($value['id_miembro']) ? $value['id_miembro'] : 'desconocido';
            $str .= '<tr id="row-' . ($id_miembro) . '">
                        <td>' . ($value['nombre_miembro']) . '</td>
                        <td>' . ($value['apellido_miembro']) . '</td>
                        <td>' . ($value['telefono_miembro']) . '</td>
                        <td>' . ($value['direccion_miembro']) . '</td>
                        <td>' . ($value['id_espacio']) . '</td>
                        <td>
                            <button type="button" onclick="abrirModal(' . $id_miembro . ')" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_registrar_compra">
                                <i class="bx bx-save"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm">
                                <i class="bx bx-trash"></i>
                            </button>
                        </td>
                    </tr>';
        }
    
        return $str;
    }
}
?>