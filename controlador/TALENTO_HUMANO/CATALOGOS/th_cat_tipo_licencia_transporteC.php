<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CATALOGOS/th_cat_tipo_licencia_transporteM.php');

$controlador = new th_cat_tipo_licencia_transporteC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['buscar_tt_hh'])) {
    $query = $_GET['q'] ?? '';
    $parametros = array('query' => $query, 'id_persona' => $_GET['id_persona'] ?? '');
    echo json_encode($controlador->buscar_talento_humano($parametros));
}

class th_cat_tipo_licencia_transporteC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_cat_tipo_licencia_transporteM();
    }

    function listar()
    {
        $datos = $this->modelo->where('estado', 1)->listar();
        return $datos;
    }

    function buscar_talento_humano($parametros)
    {
        $lista = array();

        $lista = $this->modelo->buscar_tipo_licencias_no_registradas($parametros);

        return $lista;
    }
}
