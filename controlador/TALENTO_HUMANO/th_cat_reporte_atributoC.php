<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_cat_reporte_atributoM.php');

$controlador = new th_cat_reporte_atributoC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}



class th_cat_reporte_atributoC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_cat_reporte_atributoM();
    }

    function listar($id = '')
    {
        if ($id == '') {
            $datos = $this->modelo->listar();

        } else {
            $datos = $this->modelo->listar_cat_reporte_campos_disponibles($id, 'talento_humano');
        }
        return $datos;
    }
}
