<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CATALOGOS/th_cat_tipo_estado_civilM.php');

$controlador = new th_cat_tipo_estado_civilC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['buscar'])) {
    $query = $_GET['q'] ?? '';
    $parametros = array('query' => $query);
    echo json_encode($controlador->buscar($parametros));
}

class th_cat_tipo_estado_civilC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_cat_tipo_estado_civilM();
    }

    function listar()
    {
        $datos = $this->modelo->listar();
        return $datos;
    }

    function buscar($parametros)
    {
        $lista = array();
        $concat = "descripcion, estado";

        $datos = $this->modelo->where('estado', 1)->where('estado', 1)->like($concat, $parametros['query']);

        foreach ($datos as $key => $value) {
            $text = $value['descripcion'];
            $lista[] = array(
                'id' => $value['id_tipo_estado_civil'],
                'text' => $text,
            );
        }

        return $lista;
    }
}
