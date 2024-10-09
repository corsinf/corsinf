<?php
require_once(dirname(__DIR__, 4) . '/modelo/PASANTES/02_ADRIAN/POSTULANTES/th_parroquiasM.php');

$controlador = new th_parroquiasC();

if (isset($_GET['buscar'])) {
    $query = '';
    $th_ciu_id = $_GET['th_ciu_id'];

    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }

    if (isset($_GET['th_ciu_id'])) {
        $th_ciu_id = $_GET['th_ciu_id'];
    }

    $parametros = array(
        'query' => $query,
        'th_ciu_id' => $th_ciu_id,
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

        $datos = $this->modelo->buscar_parroquias($parametros['query'], $parametros['th_ciu_id']);
        foreach ($datos as $key => $value) {
            $lista[] = array('id' => ($value['th_parr_id']), 'text' => ($value['th_parr_nombre']), 'data' => '');
        }

        return $lista;
    }
}

