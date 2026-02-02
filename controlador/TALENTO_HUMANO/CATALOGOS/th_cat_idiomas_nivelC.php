<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CATALOGOS/th_cat_idiomas_nivelM.php');

$controlador = new th_cat_idiomas_nivelC();

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


class  th_cat_idiomas_nivelC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_cat_idiomas_nivelM();
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
            $lista[] = array('id' => ($value['id_idiomas_nivel']), 'text' => ($text), /* 'data' => $value */);
        }

        return $lista;
       
    }
}