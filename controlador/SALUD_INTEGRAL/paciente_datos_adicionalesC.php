<?php

require_once(dirname(__DIR__, 2) . '/modelo/SALUD_INTEGRAL/paciente_datos_adicionalesM.php');

$controlador = new paciente_datos_adicionalesC();

if (isset($_GET['listar_ultimo'])) {
    echo json_encode($controlador->listar_ultimo($_POST['id']));
}

if (isset($_GET['listar_paciente'])) {
    echo json_encode($controlador->listar($_POST['id']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

class paciente_datos_adicionalesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new paciente_datos_adicionalesM();
    }

    function listar_ultimo($id)
    {
        $datos = $this->modelo->listar_paciente($id, true);
        return $datos;
    }

    function listar($id)
    {
        $datos = $this->modelo->listar_paciente($id);
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'sa_pac_id', 'dato' => $parametros['sa_pac_id']),
            array('campo' => 'sa_pacda_peso', 'dato' => $parametros['sa_pacda_peso']),
            array('campo' => 'sa_pacda_altura', 'dato' => $parametros['sa_pacda_altura']),
        );

        $datos = $this->modelo->insertar($datos);

        return $datos;
    }

    function eliminar($id)
    {
        $datos[0]['campo'] = 'sa_pacda_id';
        $datos[0]['dato'] = $id;
        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }
}
