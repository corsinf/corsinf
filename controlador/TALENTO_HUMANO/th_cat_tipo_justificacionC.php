<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_cat_tipo_justificacionM.php');

$controlador = new th_cat_tipo_justificacionC();

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


class th_cat_tipo_justificacionC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_cat_tipo_justificacionM();
    }

    function listar($id = '')
    {
        if ($id == '') {
            $datos = $this->modelo->where('th_tip_jus_estado', 1)->listar();

        } else {
            $datos = $this->modelo->where('th_tip_jus_id', $id)->where('th_tip_jus_estado',1)->listar();
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'th_tip_jus_nombre', 'dato' => $parametros['txt_nombre']),
            array('campo' => 'th_tip_jus_descripcion', 'dato' => $parametros['txt_descripcion']),
            array('campo' => 'id_usuario', 'dato' => $_SESSION['INICIO']['ID_USUARIO']),
            array('campo' => 'th_tip_jus_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );

        if ($parametros['_id'] == '') {
            if (count($this->modelo->where('th_tip_jus_nombre', $parametros['txt_nombre'])->where('th_tip_jus_estado', 1)->listar()) == 0) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            if (count($this->modelo->where('th_tip_jus_nombre', $parametros['txt_nombre'])->where('th_tip_jus_id !', $parametros['_id'])->where('th_tip_jus_estado', 1)->listar()) == 0) {
                $where[0]['campo'] = 'th_tip_jus_id';
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
            array('campo' => 'th_tip_jus_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_tip_jus_id';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }

    //Para usar en select2
    function buscar($parametros)
    {
        $lista = array();
        $concat = "th_tip_jus_nombre, th_tip_jus_estado";
        $datos = $this->modelo->where('th_tip_jus_estado', 1)->like($concat, $parametros['query']);

        foreach ($datos as $key => $value) {
            $text = $value['th_tip_jus_nombre'];
            $lista[] = array('id' => ($value['th_tip_jus_id']), 'text' => ($text), /* 'data' => $value */);
        }

        return $lista;
    }
}