<?php
include(dirname(__DIR__, 2) . '/modelo/COWORKING/crear_mienbrosM.php');

$controlador = new crear_mienbrosC();
$id_miembro = isset($_GET['id_miembro']) ? intval($_GET['id_miembro']) : '';
$id_compra = isset($_GET['id_compra']) ? intval($_GET['id_compra']) : '';
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

if (isset($_GET['eliminar_miembro'])) {
    $id_miembro = isset($_POST['id_miembro']) ? intval($_POST['id_miembro']) : 0;
    echo json_encode($controlador->eliminar_miembro($id_miembro));
}

if (isset($_GET['eliminar_compra'])) {
    $id_compra = isset($_POST['id_compra']) ? intval($_POST['id_compra']) : 0;
    echo json_encode($controlador->eliminar_compra($id_compra));
}

if (isset($_POST['action']) && $_POST['action'] === 'verificar_compras') {
    $id_miembro = isset($_POST['id_miembro']) ? intval($_POST['id_miembro']) : 0;
    $response = $controlador->verificar_compras($id_miembro);
    echo $response;}





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



        $str = '';
        foreach ($slista as $key => $value) {
            $id_miembro = isset($value['id_miembro']) ? $value['id_miembro'] : 'id_miembro';
            $id_compra = isset($value['id_compra']) ? $value['id_compra'] : 'id_compra';
            
            $str .= '<tr>
                        <td>' . $id_compra .'</td>
                        <td>' . $id_miembro . '</td>
                        <td>' . ($value['id_producto']) . '</td>
                        <td>' . ($value['cantidad_compra']) . '</td>
                        <td>' . ($value['pvp_compra']) . '</td>
                        <td>' . ($value['total_compra']) . '</td>
                        <td>
                            <button type="button" onclick="eliminarCompra(' . intval ($id_compra) . ')" class="btn btn-danger btn-sm">
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
                        <td>
                                <button type="button" onclick="abrirModal(' . $id_miembro . ')" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_registrar_compra">
                                    <i class="bx bx-cart"></i></i>
                                </button>
                        </td>
                        <td>' . ($value['nombre_miembro']) . '</td>
                        <td>' . ($value['apellido_miembro']) . '</td>
                        <td>' . ($value['telefono_miembro']) . '</td>
                        <td>' . ($value['direccion_miembro']) . '</td>
                        <td>' . ($value['id_espacio']) . '</td>
                        <td>
                            <button type="button" onclick="eliminarMiembro(' . $id_miembro . ')" class="btn btn-danger btn-sm">
                                <i class="bx bx-trash"></i>
                            </button>
                        </td>
                    </tr>';
        }
    
        return $str;
    }

    function eliminar_compra($id_compra)
    {
        $res = $this->modelo->eliminar_compra($id_compra);
        return $res;
    }
    

    function eliminar_miembro($id_miembro)
    {
        try {
            $verificar = $this->modelo->verificar_compras($id_miembro);
            $datos = json_decode($verificar, true);
            
            if ($datos['tiene_compras']) {
                return json_encode(['error' => 'El miembro no puede ser eliminado porque tiene compras']);
            }
            
            $res = $this->modelo->eliminar_miembro($id_miembro);
            
            if ($res) {
                return json_encode(['success' => 'Miembro eliminado correctamente']);
            } else {
                error_log('Error en la eliminación: Miembro no encontrado o no se pudo eliminar');
                return json_encode(['error' => 'Error al eliminar el miembro']);
            }
        } catch (Exception $e) {
            error_log('Error en eliminar_miembro: ' . $e->getMessage());
            return json_encode(['error' => 'Error al eliminar el miembro']);
        }
    }
    
    
    
    function verificar_compras($id_miembro)
    {
        try {
            $tiene_compras = $this->modelo->tiene_compras($id_miembro);
            return json_encode(['tiene_compras' => $tiene_compras]);
        } catch (Exception $e) {
            error_log('Error en verificar_compras: ' . $e->getMessage());
            return json_encode(['error' => 'Error al verificar las compras del miembro']);
        }
    }
    

}
?>