<?php
require_once(dirname(__DIR__, 4) . '/modelo/PASANTES/02_ADRIAN/POSTULANTES/th_provinciasM.php');

$controlador = new th_provinciasC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id']));
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

class th_provinciasC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_provinciasM();
    }

    function listar($id)
    {
        if ($id == '') {
            $datos = $this->modelo->where('th_prov_estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('th_prov_id', $id)->listar();
        }

        return $datos;
    }

    function buscar($parametros)
    {
        $lista = array();

        $datos = $this->modelo->buscar_provincia($parametros['query']);
        foreach ($datos as $key => $value) {
            $lista[] = array('id' => ($value['th_prov_id']), 'text' => ($value['th_prov_nombre']), 'data' => '');
        }

        return $lista;
    }
}
