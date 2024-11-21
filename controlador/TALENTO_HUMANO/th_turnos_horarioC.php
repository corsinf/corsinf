<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_turnos_horarioM.php');

$controlador = new th_turnos_horarioC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['eliminar_todos'])) {
    echo json_encode($controlador->eliminar_todos($_POST['id_horario']));
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

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_tuh_id', 'dato' => $id),
        );

        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }

    function eliminar_todos($id_horario)
    {
        $datos = array(
            array('campo' => 'th_hor_id', 'dato' => $id_horario),
        );

        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }

}
