<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/XPACE_CUBE/miembrosM.php');

$controlador = new miembrosC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

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


class  miembrosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new miembrosM();
    }

    function listar($id = '')
    {
       
        $datos = $this->modelo->listar();

        return $datos; 

    }

    function insertar_editar($parametros)
    {

       
    }

    function eliminar($id)
    {
        
    }

    //Para usar en select2
    function buscar($parametros)
    {
        $lista = array();
        $concat = "nombre_miembro, estado";
        $datos = $this->modelo->where('estado', 1)->like($concat, $parametros['query']);

        foreach ($datos as $key => $value) {
            $text = $value['nombre_miembro'];
            $lista[] = array('id' => ($value['id_miembro']), 'text' => ($text), /* 'data' => $value */);
        }

        return $lista;
    }
}