<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_horariosM.php');

$controlador = new th_horariosC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}


class th_horariosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_horariosM();
    }

    function listar($id = '')
    {
        if ($id == '') {
            $datos = $this->modelo->where('th_hor_estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('th_hor_id', $id)->listar();
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'th_hor_nombre', 'dato' => $parametros['txt_nombre']),
            array('campo' => 'th_hor_tipo', 'dato' => $parametros['txt_tipo']),
            array('campo' => 'th_hor_ciclos', 'dato' => $parametros['txt_ciclos']),
            array('campo' => 'th_hor_inicio', 'dato' => $parametros['txt_inicio']),
            
            array('campo' => 'th_hor_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );

        if ($parametros['_id'] == '') {
            if (count($this->modelo->where('th_hor_nombre', $parametros['txt_nombre'])->listar()) == 0) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            if (count($this->modelo->where('th_hor_nombre', $parametros['txt_nombre'])->where('th_hor_id !', $parametros['_id'])->listar()) == 0) {
                $where[0]['campo'] = 'th_hor_id';
                $where[0]['dato'] = $parametros['_id'];
                $datos = $this->modelo->editar($datos, $where);
            } else {
                return -2;
            }
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_hor_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_hor_id';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }
}
