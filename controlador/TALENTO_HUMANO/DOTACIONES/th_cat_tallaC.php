<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/DOTACIONES/th_cat_tallaM.php');

$controlador = new th_cat_tallaC();

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

if (isset($_GET['buscar_talla'])) {

    $query = '';
    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }

    $parametros = [
        'query' => $_GET['q'] ?? '',
        'talla' => $_GET['talla'] ?? 0,
    ];

    echo json_encode($controlador->buscar_talla($parametros));
}


class  th_cat_tallaC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_cat_tallaM();
    }

    function listar($id = '')
    {
        $datos = $this->modelo->where('',$id)->listar();
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
    function buscar_talla($parametros)
    {
        $lista = array();
        $concat = "descripcion, estado";
        $datos = $this->modelo->where('estado', 1)->where('tipo',$parametros['talla'])->like($concat, $parametros['query']);

        foreach ($datos as $key => $value) {
            $text = $value['descripcion'];
            $lista[] = array('id' => ($value['id_talla']), 'text' => ($text), /* 'data' => $value */);
        }

        return $lista;
       
    }
}