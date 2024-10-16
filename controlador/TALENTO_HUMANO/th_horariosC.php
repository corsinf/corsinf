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
            array('campo' => 'th_dis_host', 'dato' => $parametros['txt_host']),
            array('campo' => 'th_dis_port', 'dato' => $parametros['txt_puerto']),
            array('campo' => 'th_dis_ssl', 'dato' => $parametros['cbx_ssl']),
            array('campo' => 'th_dis_usuario', 'dato' => $parametros['txt_usuario']),
            array('campo' => 'th_dis_pass', 'dato' => $parametros['txt_pass']),
            array('campo' => 'th_dis_modelo', 'dato' => $parametros['ddl_modelo']),

            array('campo' => 'th_dis_serial', 'dato' => $parametros['txt_serial']),
            array('campo' => 'th_dis_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
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
