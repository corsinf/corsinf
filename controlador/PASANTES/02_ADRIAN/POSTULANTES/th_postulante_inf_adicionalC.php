<?php
require_once(dirname(__DIR__, 4) . '/modelo/PASANTES/02_ADRIAN/talento_humano/th_adicionalM.php');

$controlador = new th_adicionalC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}


class th_adicionalC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_adicionalM();
    }

    function listar($id)
    {
        $datos = $this->modelo->where('th_posa_id', $id)->listar($id);
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'th_posa_direccion_calle', 'dato' => $parametros['txt_direccion_calle']),
            array('campo' => 'th_posa_direccion_numero', 'dato' => $parametros['txt_direccion_numero']),
            array('campo' => 'th_posa_direccion_ciudad', 'dato' => $parametros['txt_direccion_ciudad']),
            array('campo' => 'th_posa_direccion_estado', 'dato' => $parametros['txt_direccion_estado']),
            array('campo' => 'th_posa_direccion_codpos', 'dato' => $parametros['txt_direccion_postal']),

        );

        if ($parametros['_id'] == '') {
            if (count($this->modelo->where('th_posa_direccion_numero', $parametros['txt_direccion_numero'])->listar()) == 0) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            $where[0]['campo'] = 'th_posa_id';
            $where[0]['dato'] = $parametros['_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_posa_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_posa_id';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->eliminar($datos, $where);
        return $datos;
    }
}
