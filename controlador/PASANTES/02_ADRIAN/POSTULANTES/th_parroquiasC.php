<?php
require_once(dirname(__DIR__, 4) . '/modelo/PASANTES/02_ADRIAN/POSTULANTES/th_parroquiasM.php');

$controlador = new th_parroquiasC();

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

class th_parroquiasC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_parroquiasM();
    }


    function buscar($parametros)
    {
        $lista = array();

        $datos = $this->modelo->buscar_parroquias($parametros['query']);
        foreach ($datos as $key => $value) {
            $lista[] = array('id' => ($value['th_parr_id']), 'text' => ($value['th_parr_nombre']), 'data' => '');
        }

        return $lista;
    }
}

