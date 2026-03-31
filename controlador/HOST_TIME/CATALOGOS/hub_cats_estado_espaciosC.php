<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/HOST_TIME/CATALOGOS/hub_cats_estado_espaciosM.php');

$controlador = new hub_cats_estado_espaciosC();

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
    $query = isset($_GET['q']) ? $_GET['q'] : '';
    echo json_encode($controlador->buscar(['query' => $query]));
}


class hub_cats_estado_espaciosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new hub_cats_estado_espaciosM();
    }

    function listar($id = '')
    {
        if ($id == '') {
            $datos = $this->modelo->where('activo', 1)->listar();
        } else {
            $datos = $this->modelo->where('id_estado_espacio', $id)->where('activo', 1)->listar();
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'codigo',      'dato' => trim($parametros['txt_codigo'])),
            array('campo' => 'nombre',      'dato' => trim($parametros['txt_nombre'])),
            array('campo' => 'categoria',   'dato' => trim($parametros['txt_categoria'])),
            array('campo' => 'descripcion', 'dato' => trim($parametros['txt_descripcion'])),
        );

        if ($parametros['_id'] == '') {
            // Verificar duplicado por nombre
            if (count($this->modelo->where('nombre', $parametros['txt_nombre'])->where('activo', 1)->listar()) == 0) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            if (count($this->modelo->where('nombre', $parametros['txt_nombre'])->where('id_estado_espacio !', $parametros['_id'])->where('activo', 1)->listar()) == 0) {
                $where[0]['campo'] = 'id_estado_espacio';
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
            array('campo' => 'activo', 'dato' => 0),
        );

        $where[0]['campo'] = 'id_estado_espacio';
        $where[0]['dato'] = $id;

        return $this->modelo->editar($datos, $where);
    }

    function buscar($parametros)
    {
        $lista  = array();
        $concat = "codigo, nombre, categoria";
        $datos  = $this->modelo->where('activo', 1)->like($concat, $parametros['query']);

        foreach ($datos as $value) {
            $lista[] = array(
                'id'   => $value['id_estado_espacio'],
                'text' => $value['nombre']
            );
        }

        return $lista;
    }
}
