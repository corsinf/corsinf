<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_control_aprobacionM.php');

$controlador = new th_control_aprobacionC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['autorizado'])) {
    echo json_encode($controlador->autorizado());
}

class th_control_aprobacionC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_control_aprobacionM();
    }

    function listar($id = '')
    {
        $datos = $this->modelo->listar_usuarios();
        return $datos;
    }

    function autorizado()
    {
        $id_usuario = $_SESSION['INICIO']['ID_USUARIO'] ?? '';

        $usuario_aprobacion = $this->modelo->where('usu_id', $id_usuario)->listar();

        if (count($usuario_aprobacion) == 1 || $id_usuario != 2) {
            return 1;
        }

        return -2;
    }

    function insertar_editar($parametros)
    {
        // print_r($parametros); exit(); die();
        $datos = array(
            array('campo' => 'usu_id', 'dato' => $parametros['ddl_usuarios']),
            array('campo' => 'th_ctp_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );

        if (count($this->modelo->where('usu_id', $parametros['ddl_usuarios'])->listar()) == 0) {
            $datos = $this->modelo->insertar($datos);
        } else {
            return -2;
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_ctp_id', 'dato' => $id),
        );

        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }
}
