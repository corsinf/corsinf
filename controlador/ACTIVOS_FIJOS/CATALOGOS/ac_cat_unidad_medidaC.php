<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/ACTIVOS_FIJOS/CATALOGOS/ac_cat_unidad_medidaM.php');

$controlador = new ac_cat_unidad_medidaC();

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
    $parametros = array('query' => $query);
    echo json_encode($controlador->buscar($parametros));
}


class ac_cat_unidad_medidaC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new ac_cat_unidad_medidaM();
    }

    function listar($id = '')
    {
        if ($id == '') {
            $datos = $this->modelo->where('ac_estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('ac_id_unidad', $id)->where('ac_estado', 1)->listar();
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'ac_nombre',      'dato' => $parametros['txt_nombre']),
            array('campo' => 'ac_simbolo',     'dato' => $parametros['txt_simbolo']),
            array('campo' => 'ac_tipo',        'dato' => $parametros['txt_tipo']),
            array('campo' => 'ac_descripcion', 'dato' => $parametros['txt_descripcion']),
        );

        if ($parametros['_id'] == '') {
            // Verificar nombre duplicado
            if (count($this->modelo->where('ac_nombre', $parametros['txt_nombre'])->where('ac_estado', 1)->listar()) == 0) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            if (count($this->modelo->where('ac_nombre', $parametros['txt_nombre'])->where('ac_id_unidad !', $parametros['_id'])->where('ac_estado', 1)->listar()) == 0) {
                $where[0]['campo'] = 'ac_id_unidad';
                $where[0]['dato']  = $parametros['_id'];
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
            array('campo' => 'ac_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'ac_id_unidad';
        $where[0]['dato']  = $id;

        return $this->modelo->editar($datos, $where);
    }

    function buscar($parametros)
    {
        $lista  = array();
        $concat = "ac_nombre, ac_simbolo, ac_tipo";
        $datos  = $this->modelo->where('ac_estado', 1)->like($concat, $parametros['query']);

        foreach ($datos as $value) {
            $text   = $value['ac_nombre'] . ' - ' . $value['ac_simbolo'];
            $lista[] = array(
                'id'   => $value['ac_id_unidad'],
                'text' => $text,
            );
        }

        return $lista;
    }
}
