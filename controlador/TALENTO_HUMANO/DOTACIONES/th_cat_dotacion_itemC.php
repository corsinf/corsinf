<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/DOTACIONES/th_cat_dotacion_itemM.php');

$controlador = new th_cat_dotacion_itemC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['buscar_dotacion_item'])) {

    $query = '';
    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }

    $parametros = [
        'query' => $_GET['q'] ?? '',
        'id_dotacion' => $_GET['id_dotacion'] ?? 0,
        'th_dot_id' => $_GET['th_dot_id'] ?? 0
    ];

    echo json_encode($controlador->buscar_dotacion_item($parametros));
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


class  th_cat_dotacion_itemC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_cat_dotacion_itemM();
    }

    function listar($id = '')
    {
        $datos = $this->modelo->where('', $id)->listar();
        return $datos;
    }

    function buscar($parametros)
    {
        $lista = array();
        $concat = "descripcion, estado";
        $datos = $this->modelo->where('estado', 1)->like($concat, $parametros['query']);

        foreach ($datos as $key => $value) {
            $text = $value['descripcion'];
            $lista[] = array('id' => ($value['id_talla']), 'text' => ($text), /* 'data' => $value */);
        }

        return $lista;
    }

      function buscar_dotacion_item($parametros)
    {
        $lista = array();
        $concat = "descripcion, estado";
        $datos = $this->modelo->buscar_items_dotacion($parametros);

        return $datos;

        foreach ($datos as $key => $value) {
            $text = $value['descripcion'];
            $lista[] = array('id' => ($value['id_parentesco']), 'text' => ($text), /* 'data' => $value */);
        }

        return $lista;
    }
}
