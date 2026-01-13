<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/TALENTO_HUMANO/CATALOGOS/th_cat_parentescoM.php');

$controlador = new th_cat_parentescoC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['buscar'])) {

    $query = '';
    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }

    $parametros = [
        'query' => $query
    ];

    echo json_encode($controlador->buscar($parametros));
}
if (isset($_GET['buscar_parientes'])) {

    $query = '';
    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }

    $parametros = [
        'query' => $_GET['q'] ?? '',
        'th_per_id' => $_GET['th_per_id'] ?? 0
    ];

    echo json_encode($controlador->buscar_parientes($parametros));
}

class th_cat_parentescoC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_cat_parentescoM();
    }

    function listar($id = '')
    {
        if ($id != '') {
            return $this->modelo
                ->where('id_parentesco', $id)
                ->listar();
        }

        return $this->modelo->listar();
    }

    function buscar($parametros)
    {
        $lista = array();
        $concat = "descripcion, estado";
        $datos = $this->modelo->where('estado', 1)->like($concat, $parametros['query']);

        foreach ($datos as $key => $value) {
            $text = $value['descripcion'];
            $lista[] = array('id' => ($value['id_parentesco']), 'text' => ($text), /* 'data' => $value */);
        }

        return $lista;
    }

    function buscar_parientes($parametros)
    {
        $lista = array();
        $concat = "descripcion, estado";
        $datos = $this->modelo->buscar_parientes($parametros);

        return $datos;

        foreach ($datos as $key => $value) {
            $text = $value['descripcion'];
            $lista[] = array('id' => ($value['id_parentesco']), 'text' => ($text), /* 'data' => $value */);
        }

        return $lista;
    }
}
