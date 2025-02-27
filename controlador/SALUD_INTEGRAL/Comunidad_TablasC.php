<?php
require_once(dirname(__DIR__, 2) . '/modelo/SALUD_INTEGRAL/Comunidad_TablasM.php');

$controlador = new Comunidad_TablasC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->lista_comunidad_tablas());
}

class Comunidad_TablasC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new Comunidad_TablasM();
    }

    function lista_comunidad_tablas()
    {
        $id_rol = 1;
        if ($id_rol == 1) {
            $datos = $this->modelo->lista_comunidad_tablas();
            return $datos;
        }
    }
}
