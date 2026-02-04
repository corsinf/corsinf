<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_control_accesoM.php');

$controlador = new th_control_accesoC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['fecha_inicio'] ?? '', $_POST['fecha_fin'] ?? ''));
}



class th_reporte_generalC
{
    private $modelo;

    function __construct()
    {
        $this->controlador = new th_control_accesoM();
    }

    function listar($fecha_ini = '', $fecha_final = '')
    {
        if ($fecha_ini == '') {
            $datos = $this->modelo->listar_personalizado();
        } else {
            $datos = $this->modelo->listar_personalizado($fecha_ini, $fecha_final);
        }
        
        return $datos;
    }
}
