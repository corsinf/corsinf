<?php
include('../modelo/reunionesM.php');
include('../modelo/horario_disponibleM.php');

$controlador = new reunionesC();

//Para mostrar todos los registros con campos especificos para la vista principal
if (isset($_GET['listar_todo'])) {
    echo json_encode($controlador->lista_todo_reuniones());
}

if (isset($_GET['listar_todo_docentes'])) {
    echo json_encode($controlador->lista_todo_reuniones_docentes($_GET['id_docente']));
}

if (isset($_GET['listar'])) {
    echo json_encode($controlador->lista_reuniones($_POST['id_reunion']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}


//echo json_encode($controlador->insertar_editar('Ejemplo1'));

class reunionesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new reunionesM();
    }

    function lista_todo_reuniones()
    {
        $datos = $this->modelo->lista_reuniones_todo();
        return $datos;
    }

    function lista_todo_reuniones_docentes($ac_docente_id)
    {
        $datos = $this->modelo->lista_reuniones_todo_docente($ac_docente_id);
        return $datos;
    }

    function lista_reuniones($id)
    {
        $datos = $this->modelo->lista_reuniones($id);
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $horario_disponibleM = new horario_disponibleM();

        $datos = array(
            array('campo' => 'ac_horarioD_id', 'dato' => strval($parametros['ac_horarioD_id'])),
            array('campo' => 'ac_representante_id', 'dato' => strval($parametros['ac_representante_id'])),
            array('campo' => 'ac_reunion_motivo', 'dato' => ($parametros['ac_reunion_motivo'])),
            array('campo' => 'ac_reunion_observacion', 'dato' => ($parametros['ac_reunion_observacion'])),
            array('campo' => 'ac_estudiante_id', 'dato' => strval($parametros['ac_estudiante_id'])),
            array('campo' => 'ac_nombre_est', 'dato' => ($parametros['ac_nombre_est'])),
            
        );

        if ($parametros['ac_reunion_id'] == '') {
            $datos = $this->modelo->insertar($datos);
            $horario_disponibleM->turno_representanteM(strval($parametros['ac_horarioD_id']));
        } else {
            $datos = array(
                array('campo' => 'ac_reunion_observacion', 'dato' => ($parametros['ac_reunion_observacion'])),
                array('campo' => 'ac_reunion_estado', 'dato' => strval($parametros['ac_reunion_estado'])),

            );

            $where[0]['campo'] = 'ac_reunion_id';
            $where[0]['dato'] = $parametros['ac_reunion_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos = $this->modelo->eliminar($id);
        return $datos;
    }
}
