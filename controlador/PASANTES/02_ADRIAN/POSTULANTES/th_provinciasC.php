<?php
require_once(dirname(__DIR__, 4) . '/modelo/PASANTES/02_ADRIAN/POSTULANTES/th_provinciasM.php');

$controlador = new th_provinciasC();

if (isset($_GET['listar_provincias'])) {
    echo json_encode($controlador->listar_provincias());
}

class th_provinciasC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_provinciasM();
    }

    function listar_provincias()
    {
        $datos = $this->modelo->where('th_prov_id', 1)->listar();

        $option = '';
        foreach ($datos as $key => $value) {
            $option .= "<option id='ddl_opcion_" . $value['th_prov_id'] . "' value='" . $value['th_prov_id'] . "'>" . $value['th_prov_nombre'] . "</option>";
        }
        return $option;
    }
}

