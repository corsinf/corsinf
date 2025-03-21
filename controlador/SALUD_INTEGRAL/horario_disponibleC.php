<?php
require_once(dirname(__DIR__, 2) . '/modelo/SALUD_INTEGRAL/horario_disponibleM.php');
require_once(dirname(__DIR__, 2) . '/modelo/SALUD_INTEGRAL/reunionesM.php');

$controlador = new horario_disponibleC();

//Para mostrar todos los registros con campos especificos para la vista principal
if (isset($_GET['listar_todo'])) {
    echo json_encode($controlador->lista_todo_horario_disponible());
}

if (isset($_GET['listar'])) {
    echo json_encode($controlador->lista_horario_disponible($_GET['id_docente']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}


//echo json_encode($controlador->insertar_editar('Ejemplo1'));

class horario_disponibleC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new horario_disponibleM();
    }

    function lista_todo_horario_disponible()
    {
        $datos = $this->modelo->lista_horario_disponible_todo();
        return $datos;
    }

    function lista_horario_disponible($id)
    {
        $datos = $this->modelo->lista_horario_disponible($id);
        return $datos;
    }

    function insertar_editar($parametros)
    {

        $datos = array(
            array('campo' => 'ac_docente_id', 'dato' => strval($parametros['ac_docente_id'])),
            array('campo' => 'ac_horarioD_ubicacion', 'dato' => ($parametros['ac_horarioD_ubicacion'])),
            array('campo' => 'ac_horarioD_inicio', 'dato' => ($parametros['ac_horarioD_inicio'])),
            array('campo' => 'ac_horarioD_fin', 'dato' => ($parametros['ac_horarioD_fin'])),
            array('campo' => 'ac_horarioD_fecha_disponible', 'dato' => ($parametros['ac_horarioD_fecha_disponible'])),
            array('campo' => 'ac_horarioD_materia', 'dato' => ($parametros['ac_horarioD_materia'])),
        );

        //Validacion para insertar un turno con un representante

        $validacion_turno_rep = $parametros['parametros_turno_rep'] ?? false;
        if ($validacion_turno_rep) {
            $reunionesM = new reunionesM();

            $datos_id = $this->modelo->insertar_id($datos);

            $ac_representante_id = $parametros['parametros_turno_rep']['ac_representante_id'];
            $ac_reunion_motivo = $parametros['parametros_turno_rep']['ac_reunion_motivo'];
            $ac_estudiante_id = $parametros['parametros_turno_rep']['ac_estudiante_id'];
            $ac_nombre_est = $parametros['parametros_turno_rep']['ac_nombre_est'];
            $ac_reunion_descripcion = $parametros['parametros_turno_rep']['ac_reunion_descripcion'];

            $datos = array(
                array('campo' => 'ac_horarioD_id', 'dato' => strval($datos_id)),
                array('campo' => 'ac_representante_id', 'dato' => strval($ac_representante_id)),
                array('campo' => 'ac_reunion_motivo', 'dato' => ($ac_reunion_motivo)),
                array('campo' => 'ac_reunion_observacion', 'dato' => ('')),
                array('campo' => 'ac_estudiante_id', 'dato' => strval($ac_estudiante_id)),
                array('campo' => 'ac_nombre_est', 'dato' => ($ac_nombre_est)),
                array('campo' => 'ac_reunion_descripcion', 'dato' => ($ac_reunion_descripcion)),

            );

            $datos = $reunionesM->insertar($datos);
            $this->modelo->turno_representanteM(strval($datos_id));

            return $datos;

        } else {

            if ($parametros['ac_horarioD_id'] == '') {
                $datos = $this->modelo->insertar($datos);
            } else {
                $where[0]['campo'] = 'ac_horarioD_id';
                $where[0]['dato'] = $parametros['ac_horarioD_id'];
                $datos = $this->modelo->editar($datos, $where);
            }

            return $datos;
        }
    }

    function eliminar($id)
    {
        $datos = $this->modelo->eliminar($id);
        return $datos;
    }

    function turno_representante($id)
    {
        //$datos = $this->modelo->turno_representanteM($id);
        return '$datos';
    }
}
