<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/HOST_TIME/CATALOGOS/hub_catn_numero_pisoM.php');

$controlador = new hub_catn_numero_pisoC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['buscar'])) {
    $query = '';

    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }

    $parametros = array(
        'query' => $query,
    );

    echo json_encode($controlador->buscar($parametros));
}


class hub_catn_numero_pisoC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new hub_catn_numero_pisoM();
    }

    function listar($id = '')
    {
        if ($id == '') {
            $datos = $this->modelo->where('estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('id_numero_piso', $id)->listar();
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'descripcion', 'dato' => $parametros['txt_descripcion']),
            array('campo' => 'estado',      'dato' => 1),
            array('campo' => 'fecha_creacion', 'dato' => date('Y-m-d H:i:s')),
        );

        if ($parametros['_id'] == '') {
            if (count($this->modelo->where('descripcion', $parametros['txt_descripcion'])->where('estado', 1)->listar()) == 0) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            if (count($this->modelo->where('descripcion', $parametros['txt_descripcion'])->where('id_numero_piso !', $parametros['_id'])->where('estado', 1)->listar()) == 0) {
                $where[0]['campo'] = 'id_numero_piso';
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
            array('campo' => 'estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'id_numero_piso';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }

    function buscar($parametros)
    {
        $lista = array();
        $concat = "descripcion, estado";
        $datos = $this->modelo->where('estado', 1)->like($concat, $parametros['query']);

        foreach ($datos as $key => $value) {
            $text = $value['descripcion'];
            $lista[] = array('id' => ($value['id_numero_piso']), 'text' => ($text));
        }

        return $lista;
    }
}
