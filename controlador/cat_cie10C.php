<?php
include('../modelo/cat_cie10M.php');

$controlador = new cat_cie10C();

//Para mostrar todos los registros con campos especificos para la vista principal
if (isset($_GET['listar_cie10'])) {
    echo json_encode($controlador->lista_cie10());
}

if (isset($_GET['buscar_cie10'])) {
    $query = '';
    $tipo = '';

    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }

    $parametros = array(
        'query' => $query,
    );

    echo json_encode($controlador->buscar_cie10($parametros));
}

class cat_cie10C
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new cat_cie10M();
    }

    function lista_cie10()
    {
        $datos = $this->modelo->lista_cie10();
        return $datos;
    }

    function buscar_cie10($parametros)
    {
        $lista = array();

        $datos = $this->modelo->buscar_cie10($parametros['query']);
        foreach ($datos as $key => $value) {
            $lista[] = array('id' => ($value['sa_cie10_codigo'] . ' - ' . $value['sa_cie10_descripcion']), 'text' => ($value['sa_cie10_codigo'] . ' - ' . $value['sa_cie10_descripcion']), 'data' => $value);
        }

        return $lista;
    }
}
