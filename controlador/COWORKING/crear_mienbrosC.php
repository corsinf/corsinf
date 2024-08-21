<?php

$controlador = new crear_mienbrosC();

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
    $data = $_POST['data'];
    //$dataform       
    //print_r($data)
    echo json_encode($controlador->add($data));
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
            array('Producto' => 'Producto1'),
            array('Producto' => 'Producto2'),
            array('Producto' => 'Producto3'),
            array('Producto' => 'Producto4'),
            array('Producto' => 'Producto5')
        );

        $str = '';
        foreach ($slista as $key => $value) {
            $str .= '<option value="' . $value['Producto'] . '">' . $value['Producto'] . '</option>';
        }
        return $str;  
    }

    
    function compraslista()
    {
        $slista = array(
            array('Mienbro' => 'hola', 'Producto' => 'cola', 'Cantidad' => 1, 'Precio' => 10, 'Total' => 10),
            array('Mienbro' => 'hola2', 'Producto' => 'cola', 'Cantidad' => 2, 'Precio' => 10, 'Total' => 20),
            array('Mienbro' => 'hola3', 'Producto' => 'cola', 'Cantidad' => 3, 'Precio' => 10, 'Total' => 30),
            array('Mienbro' => 'hola4', 'Producto' => 'cola', 'Cantidad' => 4, 'Precio' => 10, 'Total' => 40),
            array('Mienbro' => 'hola5', 'Producto' => 'cola', 'Cantidad' => 5, 'Precio' => 10, 'Total' => 50)
        );

        $str = '';
        foreach ($slista as $key => $value) {
            $str .= '<tr>
                        <td>' . $value['Mienbro'] . '</td>
                        <td>' . $value['Producto'] . '</td>
                        <td>' . $value['Cantidad'] . '</td>
                        <td>' . $value['Precio'] . '</td>
                        <td>' . $value['Total'] . '</td>
                        <td>
                            <button type="button" class="btn btn-primary btn-sm">
                                <i class="bx bx-save"></i>
                            </button>
                        </td>
                    </tr>';
        }
        return $str;
    }

    function add($parametros)
    {
     $this->modelo->insertarnombre($parametros['numero']);
     print_r($parametros);die();
    }
    function listar()
    {
        $slista = array(
            array('Nombre' => 'hola', 'correo' => 'ale@puce', 'Cedula' => '1708542312'),
            array('Nombre' => 'hola2', 'correo' => 'ale@puce', 'Cedula' => '1708542312'),
            array('Nombre' => 'hola3', 'correo' => 'ale@puce', 'Cedula' => '1708542312'),
            array('Nombre' => 'hola4', 'correo' => 'ale@puce', 'Cedula' => '1708542312'),
            array('Nombre' => 'hola5', 'correo' => 'ale@puce', 'Cedula' => '1708542312'),
        );

        $str = '';
        foreach ($slista as $key => $value) {
            $str .= '<tr>
                        <td>' . $value['Nombre'] . '</td>
                        <td>' . $value['correo'] . '</td>
                        <td>' . $value['Cedula'] . '</td>
                        <td></td>
                        <td></td>
                        <td>
                            <button type="button" onclick="select_productos()" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_registrar_compra">
                                <i class="bx bx-save"></i>
                            </button>
                        </td>
                    </tr>';
        }

        return $str;
    }
}
?>
