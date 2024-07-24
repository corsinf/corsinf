<?php
include('../modelo/tutores_paraleloM.php');

$controlador = new tutores_paraleloC();

//Para mostrar todos los registros con campos especificos para la vista principal
if (isset($_GET['listar_todo'])) {
    echo json_encode($controlador->lista_todo_tutor_paralelo());
}

if (isset($_GET['listar'])) {
    echo json_encode($controlador->lista_tutor_paralelo($_GET['id_tutor']));
}

if (isset($_GET['listar_estudiante_tutor_paralelo'])) {
    echo json_encode($controlador->listar_estudiante_tutor_paralelo($_POST['id_paralelo']));
}

if (isset($_GET['lista_est_rep_doc_par'])) {
    echo json_encode($controlador->lista_estudiantes_representantes_tutor_paralelo($_POST['id_tutor']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['listar_paralelos'])) {
    echo json_encode($controlador->lista_paralelos($_POST['id_grado']));
}

//echo json_encode($controlador->insertar_editar('Ejemplo1'));

class tutores_paraleloC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new tutores_paraleloM();
    }

    function lista_todo_tutor_paralelo()
    {
        $datos = $this->modelo->lista_tutor_paralelo_todo();
        return $datos;
    }

    function lista_tutor_paralelo($id)
    {
        $datos = $this->modelo->lista_tutor_paralelo($id);
        return $datos;
    }

    function lista_estudiantes_representantes_tutor_paralelo($id_tutor)
    {
        $datos = $this->modelo->lista_estudiantes_representantes_tutor_paralelo($id_tutor);
        return $datos;
    }

    //Para listar todos los paralelos disponibles
    function lista_paralelos($id_grado)
    {
        $datos = $this->modelo->lista_paralelo_todo_sin_paralelo_tutor($id_grado);
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $id_paralelo = strval($parametros['ac_paralelo_id']) ?? '';

        $datos = array(
            array('campo' => 'ac_tutor_id', 'dato' => strval($parametros['ac_tutor_id'])),
            array('campo' => 'ac_paralelo_id', 'dato' => strval($parametros['ac_paralelo_id'])),
        );

        if ($parametros['ac_tutor_paralelo_id'] == '') {
            if (count($this->modelo->buscar_PARALELO($id_paralelo)) == 0) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            $where[0]['campo'] = 'ac_tutor_paralelo_id';
            $where[0]['dato'] = $parametros['ac_tutor_paralelo_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos[0]['campo'] = 'ac_tutor_paralelo_id';
        $datos[0]['dato'] = $id;
        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }

    function listar_estudiante_tutor_paralelo($id_paralelo)
    {
        $datos = $this->modelo->lista_estudiante_tutor_paralelo($id_paralelo);
        return $datos;
    }
}
