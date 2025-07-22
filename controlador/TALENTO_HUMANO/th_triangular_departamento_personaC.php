<?php

date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_triangular_departamento_personaM.php');

$controlador = new th_triangular_departamento_personaC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}


if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['buscar'])) {
    $parametros = $_POST['parametros'] ?? [];

    // Si es una petición de filtrado, devolver directamente los datos
    if (isset($_GET['filtrar'])) {
        $datos = $controlador->buscar($parametros);
        echo json_encode($datos);
    } else {
        // Si es una operación de guardado o actualización
        $resultado = $controlador->buscar($parametros);
        echo json_encode($resultado);
    }
}


class th_triangular_departamento_personaC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_triangular_departamento_personaM();
    }

    function listar($id = '')
    {
        if ($id == '') {
            $datos = $this->modelo->Listar_Departamento_Triangulacion();
        } else {
            $datos = $this->modelo->where('th_tri_id', $id)->where('th_tdp_estado', 1)->listar();
        }
        return $datos;
    }
    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'th_tri_id', 'dato' => $parametros['ddl_triangulacion']),
            array('campo' => 'th_dep_id', 'dato' => $parametros['ddl_departamentos']),
            array('campo' => 'th_per_id', 'dato' => 0),
            array('campo' => 'th_tdp_estado', 'dato' => 1),

            array('campo' => 'th_tdp_fecha_creacion', 'dato' => date('Y-m-d H:i:s') ?? null),
            array('campo' => 'th_tdp_fecha_modificacion', 'dato' => date('Y-m-d H:i:s') ?? null),
            // Agrega más campos si es necesario, como ID de zona, usuario, fecha, etc.
        );

        $datos = $this->modelo->insertar($datos);
        return $datos;
    }

    function buscar($parametros)
    {
        $datos = $this->modelo->buscar($parametros);

        return $datos;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_tdp_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_tdp_id';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }
}
