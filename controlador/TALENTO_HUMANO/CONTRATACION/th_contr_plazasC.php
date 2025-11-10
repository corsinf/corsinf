<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CONTRATACION/th_contr_plazasM.php');

$controlador = new th_contr_plazasC();

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


class  th_contr_plazasC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_contr_plazasM();
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

        
       
    }
}