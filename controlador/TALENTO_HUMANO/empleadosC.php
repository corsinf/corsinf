<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/empleadosM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_personas_departamentosM.php');

$controlador = new empleadosC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['restaurar'])) {
    $id_empleado     = $_POST['id_empleado']     ?? '';
    $id_persona      = $_POST['id_persona']      ?? '';
    $id_departamento = $_POST['id_departamento'] ?? '';
    echo json_encode($controlador->restaurar($id_empleado, $id_persona, $id_departamento));
}

class empleadosC
{
    private $modelo;
    private $th_personas_departamentos;

    function __construct()
    {
        $this->modelo = new empleadosM();
        $this->th_personas_departamentos = new th_personas_departamentosM();
    }

    function listar($id = '')
    {
        return $this->modelo->listar_empleados_eliminados($id);
    }

    function restaurar($id_empleado, $id_persona, $id_departamento)
    {
        if (empty($id_empleado) || empty($id_persona) || empty($id_departamento)) {
            return -1; // Faltan parámetros
        }

        $id_empleado     = intval($id_empleado);
        $id_persona      = intval($id_persona);
        $id_departamento = intval($id_departamento);

        // 1. Restaurar el registro del empleado (Eliminación lógica a 0)
        $datos_empleado = array(
            array('campo' => 'DELETE_LOGIC', 'dato' => 0),
        );

        $where[0]['campo'] = 'id_empleado';
        $where[0]['dato'] = $id_empleado;

        $res_emp = $this->modelo->editar($datos_empleado, $where);

        if ($res_emp == 1) {
            $datos_per_dep = array(
                array('campo' => 'th_per_id', 'dato' => $id_persona),
                array('campo' => 'th_dep_id', 'dato' => $id_departamento),
                array('campo' => 'th_perdep_visitor', 'dato' => 0),
            );

            return $this->th_personas_departamentos->insertar($datos_per_dep);
        }

        return $res_emp;
    }
}
