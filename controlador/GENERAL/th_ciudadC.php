<?php
require_once(dirname(__DIR__, 2) . '/modelo/GENERAL/th_ciudadM.php');

$controlador = new th_ciudadC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id']));
}

if (isset($_GET['buscar'])) {
    $query = '';
    $th_prov_id = $_GET['th_prov_id'];

    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }

    if (isset($_GET['th_prov_id'])) {
        $th_prov_id = $_GET['th_prov_id'];
    }

    $parametros = array(
        'query' => $query,
        'th_prov_id' => $th_prov_id,
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

    function listar($id)
    {
        if ($id == '') {
            $datos = $this->modelo->where('th_ciu_estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('th_ciu_id', $id)->listar();
        }

        return $datos;
    }

    function buscar($parametros)
    {
        $lista = array();

        $datos = $this->modelo->buscar_ciudad($parametros['query'], $parametros['th_prov_id']);
        foreach ($datos as $key => $value) {
            $lista[] = array('id' => ($value['th_ciu_id']), 'text' => ($value['th_ciu_nombre']), 'data' => '');
        }

        return $lista;
    }
}
