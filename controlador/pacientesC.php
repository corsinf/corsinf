<?php
include('../modelo/pacientesM.php');

$controlador = new pacientesC();

if (isset($_GET['buscar'])) {

    $sa_pac_id_comunidad = '';
    $sa_pac_tabla = '';

    if (isset($_POST['sa_pac_id_comunidad'])) {
        $sa_pac_id_comunidad = $_POST['sa_pac_id_comunidad'];
    }

    if (isset($_POST['sa_pac_tabla'])) {
        $sa_pac_tabla = $_POST['sa_pac_tabla'];
    }

    echo json_encode($controlador->buscar_paciente($sa_pac_id_comunidad, $sa_pac_tabla));
}

//Para crear automaticamente paciente y ficha medica
if (isset($_GET['obtener_info_paciente'])) {

    $sa_pac_id = '';

    if (isset($_POST['sa_pac_id'])) {
        $sa_pac_id = $_POST['sa_pac_id'];
    }

    echo json_encode($controlador->obtener_informacion_pacienteC($sa_pac_id));
}

if (isset($_GET['listar_todo'])) {
    echo json_encode($controlador->lista_pacientes_todo());
}



class pacientesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new pacientesM();
    }

    function buscar_paciente($sa_pac_id_comunidad, $sa_pac_tabla)
    {
        $datos = $this->modelo->buscar_paciente($sa_pac_id_comunidad, $sa_pac_tabla);
        return $datos;
    }

    function obtener_informacion_pacienteC($sa_pac_id)
    {
        return $this->modelo->obtener_informacion_pacienteM($sa_pac_id);
    }

    function lista_pacientes_todo()
    {
        $datos = $this->modelo->lista_pacientes_todo();
        return $datos;
    }
}
