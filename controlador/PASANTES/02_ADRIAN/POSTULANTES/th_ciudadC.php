<?php
require_once(dirname(__DIR__, 4) . '/modelo/PASANTES/02_ADRIAN/POSTULANTES/th_ciudadM.php');

$controlador = new th_ciudadC();

if (isset($_GET['buscar'])) {
    $query = '';
    $tipo = '';

    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }

    $parametros = array(
        'query' => $query,
    );

    echo json_encode($controlador->buscar($parametros));
}

class th_ciudadC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_ciudadM();
    }


    function buscar($parametros)
    {
        $lista = array();

        $datos = $this->modelo->buscar_ciudad($parametros['query']);
        foreach ($datos as $key => $value) {
            $lista[] = array('id' => ($value['th_ciu_id']), 'text' => ($value['th_ciu_nombre']), 'data' => '');
        }

        return $lista;
    }
}

