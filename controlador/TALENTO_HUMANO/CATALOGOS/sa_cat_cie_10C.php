<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CATALOGOS/sa_cat_cie_10M.php');

$controlador = new sa_cat_cie_10C();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
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


class  sa_cat_cie_10C
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new sa_cat_cie_10M();
    }

    function listar($id = '')
    {
        if ($id == "") {
        } else {
        }
        $datos = $this->modelo->listar();
        return $datos;
    }

    function buscar($parametros)
    {
        $lista = array();
        $concat = "descripcion , codigo , estado";
        $datos = $this->modelo->where('estado', 1)->like($concat, $parametros['query']);

        foreach ($datos as $key => $value) {
            $text = $value['codigo']." - ".$value['descripcion'];
            $lista[] = array('id' => ($value['id']), 'text' => ($text), /* 'data' => $value */);
        }

        return $lista;
    }
}
