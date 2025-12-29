<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/TALENTO_HUMANO/CATALOGOS/th_cat_seccionM.php');

$controlador = new th_cat_seccionC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['buscar'])) {

    $query = '';
    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }

    $parametros = [
        'query' => $query
    ];

    echo json_encode($controlador->buscar($parametros));
}

class th_cat_seccionC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_cat_seccionM();
    }
    function listar($id = '')
    {
        if ($id != '') {
            return $this->modelo->where('id_seccion', $id)->listar();
        }

        return $this->modelo->listar();
    }
    function buscar($parametros)
    {
        $lista = [];
        $concat = "descripcion, estado";

        $datos = $this->modelo
            ->where('estado', 1)
            ->like($concat, $parametros['query']);

        foreach ($datos as $value) {
            $lista[] = [
                'id'   => $value['id_seccion'],
                'text' => $value['descripcion']
            ];
        }

        return $lista;
    }
}
