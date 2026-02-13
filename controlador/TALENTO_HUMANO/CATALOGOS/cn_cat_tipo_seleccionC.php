<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CATALOGOS/cn_cat_tipo_seleccionM.php');

$controlador = new cn_cat_tipo_seleccionC();

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


class  cn_cat_tipo_seleccionC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new cn_cat_tipo_seleccionM();
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
            $lista[] = array('id' => ($value['id_tipo_seleccion']), 'text' => ($text), /* 'data' => $value */);
        }

        return $lista;
       
    }
}