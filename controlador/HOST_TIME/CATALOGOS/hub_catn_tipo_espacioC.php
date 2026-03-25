<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/HOST_TIME/CATALOGOS/hub_catn_tipo_espacioM.php');


$controlador = new hub_catn_tipo_espacioC();

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


class hub_catn_tipo_espacioC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new hub_catn_tipo_espacioM();
    }

    function listar($id = '')
    {
        if ($id == '') {
            $datos = $this->modelo->where('estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('id_tipo_espacio', $id)->where('estado', 1)->listar();
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'nombre',      'dato' => $parametros['txt_nombre']),
            array('campo' => 'descripcion', 'dato' => $parametros['txt_descripcion']),
        );

        if ($parametros['_id'] == '') {
            if (count($this->modelo->where('nombre', $parametros['txt_nombre'])->where('estado', 1)->listar()) == 0) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            if (count($this->modelo->where('nombre', $parametros['txt_nombre'])->where('id_tipo_espacio !', $parametros['_id'])->where('estado', 1)->listar()) == 0) {
                $where[0]['campo'] = 'id_tipo_espacio';
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

        $where[0]['campo'] = 'id_tipo_espacio';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }

    function buscar($parametros)
    {
        $lista = array();
        $concat = "nombre, estado";
        $datos = $this->modelo->where('estado', 1)->like($concat, $parametros['query']);

        foreach ($datos as $key => $value) {
            $text = $value['nombre'];
            $lista[] = array('id' => ($value['id_tipo_espacio']), 'text' => ($text));
        }

        return $lista;
    }
}
