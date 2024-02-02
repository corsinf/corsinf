<?php
include('../modelo/horario_clasesM.php');

$controlador = new horario_clasesC();

//Para mostrar todos los registros con campos especificos para la vista principal
if (isset($_GET['listar_todo'])) {
    echo json_encode($controlador->lista_todo_horario_clases());
}

if (isset($_GET['listar'])) {
    echo json_encode($controlador->lista_horario_clases($_GET['id_docente']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}


//echo json_encode($controlador->insertar_editar('Ejemplo1'));

class horario_clasesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new horario_clasesM();
    }

    function lista_todo_horario_clases()
    {
        $datos = $this->modelo->lista_horario_clases_todo();
        return $datos;
    }

    function lista_horario_clases($id)
    {
        $datos = $this->modelo->lista_horario_clases($id);
        return $datos;
    }

    function insertar_editar($parametros)
    {

        $datos = array(
            array('campo' => 'ac_docente_id', 'dato' => strval($parametros['ac_docente_id'])),
            array('campo' => 'ac_horarioC_inicio', 'dato' => ($parametros['ac_horarioC_inicio'])),
            array('campo' => 'ac_horarioC_fin', 'dato' => ($parametros['ac_horarioC_fin'])),
            array('campo' => 'ac_horarioC_dia', 'dato' => ($parametros['ac_horarioC_dia'])),
            array('campo' => 'ac_horarioC_materia', 'dato' => ($parametros['ac_horarioC_materia'])),
        );

        if ($parametros['ac_horarioC_id'] == '') {
            $datos = $this->modelo->insertar($datos);
        } else {
            $where[0]['campo'] = 'ac_horarioC_id';
            $where[0]['dato'] = $parametros['ac_horarioC_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos[0]['campo'] = 'ac_horario_clases_id';
        $datos[0]['dato'] = $id;
        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }

}
