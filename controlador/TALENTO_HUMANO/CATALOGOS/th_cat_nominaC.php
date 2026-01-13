<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/TALENTO_HUMANO/CATALOGOS/th_cat_nominaM.php');

$controlador = new th_cat_nominaC();

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

class th_cat_nominaC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_cat_nominaM();
    }
    function listar($id = '')
    {
        if ($id != '') {
            return $this->modelo->where('id_nomina', $id)->listar();
        }

        return $this->modelo->listar();
    }
    function buscar($parametros)
    {
        $lista = [];
        $concat = "codigo, nombre, tipo";

        $datos = $this->modelo
            ->where('estado', 1)
            ->like($concat, $parametros['query']);

        foreach ($datos as $value) {
            $lista[] = [
                'id'   => $value['id_nomina'],
                'text' => $value['codigo'] . ' - ' . $value['nombre']
            ];
        }

        return $lista;
    }
}
