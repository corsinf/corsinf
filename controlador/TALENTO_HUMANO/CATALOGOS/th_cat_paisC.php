<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CATALOGOS/th_cat_paisM.php');

$controlador = new th_cat_paisC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['buscar'])) {
    $query = $_GET['q'] ?? '';
    $parametros = array('query' => $query);
    echo json_encode($controlador->buscar($parametros));
}

if (isset($_GET['buscar_nacionalidad'])) {
    $query = $_GET['q'] ?? '';
    $parametros = array('query' => $query);
    echo json_encode($controlador->buscar_nacionalidad($parametros));
}

class th_cat_paisC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_cat_paisM();
    }

    function listar()
    {
        $datos = $this->modelo->listar();
        return $datos;
    }

    function buscar($parametros)
    {
        $lista = array();
        $concat = "nombre,iso_alpha2";

        $datos = $this->modelo->where('estado', 1)->where('se_lista_pais', 1)->like($concat, $parametros['query']);

        foreach ($datos as $key => $value) {
            $text = $value['nombre'];
            $lista[] = array(
                'id' => $value['id_pais'],
                'text' => $text,
                'nacionalidad' => $value['nacionalidad']
            );
        }

        return $lista;
    }

    function buscar_nacionalidad($parametros)
    {
        $lista = array();
        $concat = "nombre,iso_alpha2";

        $datos = $this->modelo->where('estado', 1)->where('se_lista_pais', 1)->like($concat, $parametros['query']);

        foreach ($datos as $key => $value) {
            $text = $value['nacionalidad'];
            $lista[] = array(
                'id' => $value['id_pais'],
                'text' => $text
            );
        }

        return $lista;
    }
}
