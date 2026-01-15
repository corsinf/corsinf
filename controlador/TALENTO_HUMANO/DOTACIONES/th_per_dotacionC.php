<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/TALENTO_HUMANO/DOTACIONES/th_per_dotacionM.php');

$controlador = new th_per_dotacionC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

class th_per_dotacionC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_per_dotacionM();
    }

    function listar($id = '')
    {
        if ($id == '') {
            // th_dot_estado: 1 para Activo, 0 para Anulado/Inactivo
            $datos = $this->modelo->where('th_dot_estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('th_dot_id', $id)->listar();
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'th_per_id', 'dato' => $parametros['th_per_id']),
            array('campo' => 'th_dot_fecha_entrega', 'dato' => $parametros['txt_fecha_entrega']),
            array('campo' => 'th_dot_observacion', 'dato' => $parametros['txt_observacion']),
            array('campo' => 'id_usuario', 'dato' => $_SESSION['INICIO']['ID_USUARIO'] ?? $parametros['id_usuario']),
            array('campo' => 'th_dot_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );

        if ($parametros['_id'] == '') {
            // Insertar nueva dotación
            $datos[] = array('campo' => 'th_dot_fecha_creacion', 'dato' => date('Y-m-d H:i:s'));
            $datos[] = array('campo' => 'th_dot_estado', 'dato' => 1);
            $res = $this->modelo->insertar_id($datos);
            return $res; 
        } else {
            // Editar existente
            $where[0]['campo'] = 'th_dot_id';
            $where[0]['dato'] = $parametros['_id'];
            $res = $this->modelo->editar($datos, $where);
            return $res;
        }
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_dot_estado', 'dato' => 0),
        );
        $where[0]['campo'] = 'th_dot_id';
        $where[0]['dato'] = $id;
        return $this->modelo->editar($datos, $where);
    }
}