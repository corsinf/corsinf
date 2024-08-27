<?php
require_once(dirname(__DIR__, 3) . '/modelo/PASANTES/02_ADRIAN/ejemploM.php');

$controlador = new claseEjemplo();

if (isset($_GET['categoria'])) {
    echo json_encode($controlador->lista());
}

if (isset($_GET['ingreso'])) {
    echo json_encode($controlador->listaIngreso());
}

if (isset($_GET['add'])) {
    $data = $_POST['data'];
    echo json_encode($controlador->add($data));
}


class claseEjemplo
{

    private $modelo;

    function __construct()
    {
        $this->modelo = new claseEjemploM();
    }

    function add($parametros)
    {
        $res = $this->modelo->insertarNombre($parametros['primer_apellido']);
        return $res;
        // print_r($res);die();
    }

    function lista()
    {
        $lista = array(
            array('Apellido' => 'Acuña', 'id' => 1),
            array('Apellido' => 'Cantuña', 'id' => 2),
            array('Apellido' => 'Bedoya', 'id' => 3),
            array('Apellido' => 'Díaz', 'id' => 4),
            array('Apellido' => 'Moyano', 'id' => 5),
        );
        $select = '';
        foreach ($lista as $key => $value) {
            $select .= '<option value="' . $value['id'] . '">' . $value['Apellido'] . '</option>';
        }
        
        return $select;
    }

    function listaIngreso()
    {
        // $lista = array(
        //     array('Apellido' => 'Acuña', 'id' => 1),
        //     array('Apellido' => 'Cantuña', 'id' => 2),
        //     array('Apellido' => 'Bedoya', 'id' => 3),
        //     array('Apellido' => 'Díaz', 'id' => 4),
        //     array('Apellido' => 'Moyano', 'id' => 5),
        //     array('Apellido' => 'Estrada', 'id' => 6),
        // );

        $lista = $this->modelo->listarBase();

        $tr = '';
        foreach ($lista as $key => $value) {
            $tr .= '<tr>
            <td>' . $value['id'] . '</td>
            <td>' . $value['nombre'] . '</td>
            <td>Adrian</td>
            <td></td>
            <td><button class="btn btn-sm btn-primary"><i class="bx bx-save"></i></button></td>
            </tr>';
        }

        return $tr;
    }

    

    function eliminar() {}
}
