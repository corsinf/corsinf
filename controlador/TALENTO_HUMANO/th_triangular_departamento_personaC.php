<?php

date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_triangular_departamento_personaM.php');

$controlador = new th_triangular_departamento_personaC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}


class th_triangular_departamento_personaC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_triangular_departamento_personaM();
    }

    function listar($id = '')
    {
        if ($id == '') {

            $datos = $this->modelo->listarJoin();

        } else {
            $datos = $this->modelo->where('th_tri_id', $id)->listar();
        }
        return $datos;
    }
}