<?php
include('../modelo/estudiantesM.php');

$controlador = new estudiantesC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->lista_estudiantes($_POST['id']));
}

if (isset($_GET['buscar'])) {
    echo json_encode($controlador->buscar_estudiantes($_POST['buscar']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['listar_paralelo_representante'])) {
    echo json_encode($controlador->listar_paralelo_representante($_POST['id_paralelo']));
}

if (isset($_GET['listar_estudiante_representante'])) {
    echo json_encode($controlador->lista_estudiantes_representante($_POST['id_representante']));
}

//echo json_encode($controlador->listar_paralelo_representante(17));

class estudiantesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new estudiantesM();
    }

    function lista_estudiantes($id)
    {
        $datos = $this->modelo->lista_estudiantes($id);
        return $datos;
    }

    function buscar_estudiantes($buscar)
    {
        $datos = $this->modelo->buscar_estudiantes($buscar);
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos1[0]['campo'] = 'sa_est_id';
        $datos1[0]['dato'] = strval($parametros['sa_est_id']);

        $datos[1]['campo'] = 'sa_est_primer_apellido';
        $datos[1]['dato'] = $parametros['sa_est_primer_apellido'];

        $datos[2]['campo'] = 'sa_est_segundo_apellido';
        $datos[2]['dato'] = $parametros['sa_est_segundo_apellido'];

        $datos[3]['campo'] = 'sa_est_primer_nombre';
        $datos[3]['dato'] = $parametros['sa_est_primer_nombre'];

        $datos[4]['campo'] = 'sa_est_segundo_nombre';
        $datos[4]['dato'] = $parametros['sa_est_segundo_nombre'];

        $datos[5]['campo'] = 'sa_est_cedula';
        $datos[5]['dato'] = $parametros['sa_est_cedula'];

        $datos[6]['campo'] = 'sa_est_sexo';
        $datos[6]['dato'] = $parametros['sa_est_sexo'];

        $datos[7]['campo'] = 'sa_est_fecha_nacimiento';
        $datos[7]['dato'] = $parametros['sa_est_fecha_nacimiento'];

        $datos[8]['campo'] = 'sa_id_seccion';
        $datos[8]['dato'] = $parametros['sa_id_seccion'];

        $datos[9]['campo'] = 'sa_id_grado';
        $datos[9]['dato'] = $parametros['sa_id_grado'];

        $datos[10]['campo'] = 'sa_id_paralelo';
        $datos[10]['dato'] = $parametros['sa_id_paralelo'];

        $datos[11]['campo'] = 'sa_est_correo';
        $datos[11]['dato'] = $parametros['sa_est_correo'];

        $datos[12]['campo'] = 'sa_id_representante';
        $datos[12]['dato'] = $parametros['sa_id_representante'];

        if ($parametros['sa_est_id'] == '') {
            if (count($this->modelo->buscar_estudiantes_CODIGO($datos1[0]['dato'])) == 0) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            $where[0]['campo'] = 'sa_est_id';
            $where[0]['dato'] = $parametros['sa_est_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos[0]['campo'] = 'sa_est_id';
        $datos[0]['dato'] = $id;
        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }

    function compara_datos($parametros)
    {
        $text = '';
        $marca = $this->modelo->lista_estudiantes($parametros['id']);

        if ($marca[0]['CODIGO'] != $parametros['cod']) {
            $text .= ' Se modifico CODIGO en SECCION de ' . $marca[0]['CODIGO'] . ' a ' . $parametros['cod'];
        }

        if ($marca[0]['DESCRIPCION'] != $parametros['des']) {
            $text .= ' Se modifico DESCRIPCION en SECCION DE ' . $marca[0]['DESCRIPCION'] . ' a ' . $parametros['des'];
        }

        return $text;
    }

    function listar_paralelo_representante($buscar)
    {
        $datos = $this->modelo->buscar_paralelo_representante($buscar);
        return $datos;
    }

    function lista_estudiantes_representante($id)
    {
        $datos = $this->modelo->buscar_estudiantes_representante($id);
        return $datos;
    }


}
