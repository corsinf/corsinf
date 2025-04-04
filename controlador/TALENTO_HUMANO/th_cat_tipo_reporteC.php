<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_cat_tipo_reporteM.php');

$controlador = new th_cat_tipo_reporteC();

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



class th_cat_tipo_reporteC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_cat_tipo_reporteM();
    }

    //Para usar en select2
    function buscar($parametros)
    {
        $lista = array();
        $concat = "th_tip_rep_nombre, ''";
        $datos = $this->modelo->like($concat, $parametros['query']);

        foreach ($datos as $key => $value) {
            $text = $value['th_tip_rep_nombre'];
            $lista[] = array('id' => ($value['th_tip_rep_id']), 'text' => ($text), /* 'data' => $value */);
        }

        return $lista;
    }
}
