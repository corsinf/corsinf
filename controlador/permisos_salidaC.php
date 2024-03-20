<?php
include('../modelo/permisos_salidaM.php');

$controlador = new permisos_salidaC();

//Para mostrar todos los registros con campos especificos para la vista principal
if (isset($_GET['listar_todo'])) {
    echo json_encode($controlador->lista_todo_permisos_salida());
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['llegada'])) {
    echo json_encode($controlador->llegada($_POST['id']));
}


class permisos_salidaC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new permisos_salidaM();
    }

    function lista_todo_permisos_salida()
    {
        $datos = $this->modelo->lista_permisos_salida_todo();
        return $datos;
    }

    function insertar($parametros)
    {
        date_default_timezone_set('America/Guayaquil');
        $hora_actual = date('H:i:s');

        $datos = array(
            array('campo' => 'ac_ps_id_autoriza', 'dato' => $parametros['ac_ps_id_autoriza']),
            array('campo' => 'ac_ps_tabla', 'dato' => 'estudiantes'),
            array('campo' => 'ac_ps_id_tabla', 'dato' => $parametros['ac_ps_id_tabla']),
            array('campo' => 'ac_ps_nombre', 'dato' => $parametros['ac_ps_nombre']),
            array('campo' => 'ac_ps_hora_salida', 'dato' => $hora_actual),
            //array('campo' => 'ac_ps_hora_entrada', 'dato' => $parametros['ac_ps_hora_entrada']),
            array('campo' => 'ac_ps_estado_salida', 'dato' => $parametros['ac_ps_estado_salida']),
            //array('campo' => 'ac_ps_codigo_TCP_HIK', 'dato' => 'prueba'),
            array('campo' => 'ac_ps_prioridad', 'dato' => $parametros['ac_ps_prioridad']),
            array('campo' => 'ac_ps_observacion', 'dato' => $parametros['ac_ps_observacion']),
        );

        //Ingresa los datos
        $id = $this->modelo->insertar_id($datos);

        //Permiso del inspector
        $mensaje_HV = 'per_ins_' . $id;

        $datos_edit = array(
            //array('campo' => 'ac_ps_hora_entrada', 'dato' => $parametros['ac_ps_hora_entrada']),
            array('campo' => 'ac_ps_codigo_TCP_HIK', 'dato' => $mensaje_HV),
        );

        $where[0]['campo'] = 'ac_ps_id';
        $where[0]['dato'] = $id;
        $datos = $this->modelo->editar($datos_edit, $where);


        return $datos;
    }

    function llegada($id)
    {
        date_default_timezone_set('America/Guayaquil');
        $hora_actual = date('H:i:s');

        $datos_edit = array(
            array('campo' => 'ac_ps_hora_entrada', 'dato' => $hora_actual),

        );

        $where[0]['campo'] = 'ac_ps_id';
        $where[0]['dato'] = $id;
        $datos = $this->modelo->editar($datos_edit, $where);

        return $datos;
    }

    function eliminar($id)
    {
        $datos[0]['campo'] = 'ac_ps_id';
        $datos[0]['dato'] = $id;
        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }
}
