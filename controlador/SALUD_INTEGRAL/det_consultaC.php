<?php
require_once(dirname(__DIR__, 2) . '/modelo/SALUD_INTEGRAL/det_consultaM.php');

$controlador = new det_consultaC();

//Para mostrar todos los registros con campos especificos para la vista principal
if (isset($_GET['listar_todo'])) {
    echo json_encode($controlador->lista_todo_det_consulta());
}

if (isset($_GET['listar'])) {
    echo json_encode($controlador->lista_det_consulta($_POST['id']));
}

if (isset($_GET['listar_consulta'])) {
    echo json_encode($controlador->lista_det_consulta_consulta($_POST['id_consulta']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

class det_consultaC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new det_consultaM();
    }

    function lista_todo_det_consulta()
    {
        $datos = $this->modelo->lista_det_consulta_todo();
        return $datos;
    }

    function lista_det_consulta($id)
    {
        $datos = $this->modelo->lista_det_consulta($id);
        return $datos;
    }

    function lista_det_consulta_consulta($id_consulta)
    {
        $datos = $this->modelo->lista_det_consulta_consulta($id_consulta);
        return $datos;
    }

    function eliminar($id)
    {
        $datos = $this->modelo->eliminar($id);
        return $datos;
    }

}
