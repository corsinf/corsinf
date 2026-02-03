<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CATALOGOS/th_cat_bancosM.php');

$controlador = new th_cat_bancosC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['buscar'])) {
    $query = $_GET['q'] ?? '';
    $parametros = array('query' => $query);
    echo json_encode($controlador->buscar($parametros));
}

if (isset($_GET['buscar_tt_hh'])) {
    $query = $_GET['q'] ?? '';
    $parametros = array('query' => $query, 'id_persona' => $_GET['id_persona'] ?? '');
    echo json_encode($controlador->buscar_talento_humano($parametros));
}

class th_cat_bancosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_cat_bancosM();
    }

    function listar()
    {
        $datos = $this->modelo->where('se_lista', 1)->where('estado', 1)->listar();
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
                'id' => $value['id_banco'],
                'text' => $text,
            );
        }

        return $lista;
    }

    function buscar_talento_humano($parametros)
    {
        $lista = array();

        $lista = $this->modelo->buscar_bancos_no_registrados($parametros);

        return $lista;
    }
}
