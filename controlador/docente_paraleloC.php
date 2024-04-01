<?php
include('../modelo/docente_paraleloM.php');

$controlador = new docente_paraleloC();

//Para mostrar todos los registros con campos especificos para la vista principal
if (isset($_GET['listar_todo'])) {
    echo json_encode($controlador->lista_todo_docente_paralelo());
}

if (isset($_GET['listar'])) {
    echo json_encode($controlador->lista_docente_paralelo($_GET['id_docente']));
}

if (isset($_GET['listar_estudiante_docente_paralelo'])) {
    echo json_encode($controlador->listar_estudiante_docente_paralelo($_POST['id_paralelo']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}


//echo json_encode($controlador->insertar_editar('Ejemplo1'));

class docente_paraleloC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new docente_paraleloM();
    }

    function lista_todo_docente_paralelo()
    {
        $datos = $this->modelo->lista_docente_paralelo_todo();
        return $datos;
    }

    function lista_docente_paralelo($id)
    {
        $datos = $this->modelo->lista_docente_paralelo($id);
        return $datos;
    }

    function insertar_editar($parametros)
    {

        $datos = array(
            array('campo' => 'ac_docente_id', 'dato' => strval($parametros['ac_docente_id'])),
            array('campo' => 'ac_paralelo_id', 'dato' => strval($parametros['ac_paralelo_id'])),
        );

        if ($parametros['ac_docente_paralelo_id'] == '') {
            $datos = $this->modelo->insertar($datos);
        } else {
            $where[0]['campo'] = 'ac_docente_paralelo_id';
            $where[0]['dato'] = $parametros['ac_docente_paralelo_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos[0]['campo'] = 'ac_docente_paralelo_id';
        $datos[0]['dato'] = $id;
        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }

    function listar_estudiante_docente_paralelo($id_paralelo)
    {
        $datos = $this->modelo->lista_estudiante_docente_paralelo($id_paralelo);
        return $datos;
    }

}
