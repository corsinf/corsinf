<?php
require_once(dirname(__DIR__, 4) . '/modelo/PASANTES/02_ADRIAN/POSTULANTES/th_cat_habilidadesM.php');

$controlador = new th_cat_habilidadesC();

if (isset($_GET['listar_tecnicas'])) {
    echo json_encode($controlador->listar_aptitudes_tecnicas());
}

if (isset($_GET['listar_blandas'])) {
    echo json_encode($controlador->listar_aptitudes_blandas());
}


class th_cat_habilidadesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_cat_habilidadesM();
    }

    function listar_aptitudes_tecnicas()
    {
        $datos = $this->modelo->where('th_tiph_id', 2)->listar();

        $option = '';
        foreach ($datos as $key => $value) {
            $option .= "<option id='ddl_opcion_" . $value['th_hab_id'] . "' value='" . $value['th_hab_id'] . "'>" . $value['th_hab_nombre'] . "</option>";
        }
        return $option;
    }

    function listar_aptitudes_blandas()
    {
        $datos = $this->modelo->where('th_tiph_id', 1)->listar();

        $option = '';
        foreach ($datos as $key => $value) {
            $option .= "<option value='" . $value['th_hab_id'] . "'>" . $value['th_hab_nombre'] . "</option>";
        }
        return $option;
    }
}
