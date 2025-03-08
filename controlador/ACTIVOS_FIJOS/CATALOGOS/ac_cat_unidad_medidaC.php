<?php
require_once(dirname(__DIR__, 3) . '/modelo/ACTIVOS_FIJOS/CATALOGOS/ac_cat_unidad_medidaM.php');

$controlador = new ac_cat_unidad_medidaC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['_id']));
}

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



class ac_cat_unidad_medidaC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new ac_cat_unidad_medidaM();
    }

    function listar($id)
    {
        if ($id == '') {
            $datos = $this->modelo->where('ac_estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('ac_id_unidad', $id)->listar();
        }

        return $datos;
    }

    function buscar($parametros)
    {
        $lista = array();
        $concat = "ac_nombre, ac_simbolo, ac_tipo";
        $datos = $this->modelo->where('ac_estado', 1)->like($concat, $parametros['query']);

        foreach ($datos as $key => $value) {
            $text = $value['ac_nombre'] . ' - ' . $value['ac_simbolo'];
            $lista[] = array('id' => ($value['ac_id_unidad']), 'text' => ($text), /* 'data' => $value */);
        }

        return $lista;
    }
}
