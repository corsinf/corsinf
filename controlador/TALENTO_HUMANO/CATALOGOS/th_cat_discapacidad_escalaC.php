<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CATALOGOS/th_cat_discapacidad_escalaM.php');

$controlador = new th_cat_discapacidad_escalaC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['buscar_discapacidad_escala'])) {

    $parametros = array(
        'query'           => isset($_GET['q']) ? $_GET['q'] : '',
        'id_discapacidad' => isset($_GET['id_discapacidad']) ? intval($_GET['id_discapacidad']) : 0
    );

    $datos = $controlador->buscar_discapacidad_escala($parametros);
    echo json_encode($datos);
    exit;
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


class  th_cat_discapacidad_escalaC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_cat_discapacidad_escalaM();
    }

    function listar($id = '')
    {
        $datos = $this->modelo->where('', $id)->listar();
        return $datos;
    }

    function buscar_discapacidad_escala($parametros)
    {
          $lista = array();
        $concat = "descripcion, estado";
        $datos = $this->modelo->where('estado', 1)->where('id_discapacidad',$parametros['id_discapacidad'])->like($concat, $parametros['query']);

        foreach ($datos as $key => $value) {
            $text = $value['descripcion'];
            $lista[] = array('id' => ($value['id_escala_dis']), 'text' => ($text), /* 'data' => $value */);
        }

        return $lista;
    }


    function buscar($parametros)
    {
        $lista = array();
        $concat = "descripcion, estado";
        $datos = $this->modelo->where('estado', 1)->like($concat, $parametros['query']);

        foreach ($datos as $key => $value) {
            $text = $value['descripcion'];
            $lista[] = array('id' => ($value['id_escala_dis']), 'text' => ($text), /* 'data' => $value */);
        }

        return $lista;
    }
}
