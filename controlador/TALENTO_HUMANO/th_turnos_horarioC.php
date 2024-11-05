<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_turnos_horarioM.php');

$controlador = new th_turnos_horarioC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}


class th_turnos_horarioC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_turnos_horarioM();
    }

    function listar($id = '')
    {
        $datos = null;
        
        if ($id != '') {
            $datos = $this->modelo->listar_turnos_horarios($id);
        }

        return $datos;
    }
}
