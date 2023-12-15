<?php
include('../modelo/estudiantesM.php');

$controlador = new estudiantesC();

if (isset($_GET['listar_todo'])) {
    echo json_encode($controlador->lista_estudiantes_todo());
}

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

<<<<<<< HEAD
if (isset($_GET['listar_paralelo_representante'])) {
    echo json_encode($controlador->listar_paralelo_representante($_POST['id_paralelo']));
}

if (isset($_GET['listar_estudiante_representante'])) {
    echo json_encode($controlador->lista_estudiantes_representante($_POST['id_representante']));
}


if (isset($_GET['buscar_estudiante_ficha_medica'])) {
    echo json_encode($controlador->buscar_estudiante_ficha_medica($_POST['id_estudiante']));
}

//echo json_encode($controlador->buscar_estudiante_ficha_medica(5));
=======
if (isset($_GET['listar_estudiante_representante'])) {
    echo json_encode($controlador->lista_estudiante_representante($_POST['id_representante']));
}

//echo json_encode($controlador->buscar_estudiantes_ficha_medica(5));
>>>>>>> f975ff57302e9fcddee9c8879ae90e7325aab8d1

class estudiantesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new estudiantesM();
    }

    function lista_estudiantes_todo()
    {
        $datos = $this->modelo->lista_estudiantes_todo();
        return $datos;
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
        $datos1[0]['campo'] = 'sa_est_cedula';
        $datos1[0]['dato'] = strval($parametros['sa_est_cedula']);

        $datos = array(
            array('campo' => 'sa_est_primer_apellido', 'dato' => $parametros['sa_est_primer_apellido']),
            array('campo' => 'sa_est_segundo_apellido', 'dato' => $parametros['sa_est_segundo_apellido']),
            array('campo' => 'sa_est_primer_nombre', 'dato' => $parametros['sa_est_primer_nombre']),
            array('campo' => 'sa_est_segundo_nombre', 'dato' => $parametros['sa_est_segundo_nombre']),
            array('campo' => 'sa_est_cedula', 'dato' => $parametros['sa_est_cedula']),
            array('campo' => 'sa_est_sexo', 'dato' => $parametros['sa_est_sexo']),
            array('campo' => 'sa_est_fecha_nacimiento', 'dato' => $parametros['sa_est_fecha_nacimiento']),
            array('campo' => 'sa_id_seccion', 'dato' => $parametros['sa_id_seccion']),
            array('campo' => 'sa_id_grado', 'dato' => $parametros['sa_id_grado']),
            array('campo' => 'sa_id_paralelo', 'dato' => $parametros['sa_id_paralelo']),
            array('campo' => 'sa_id_representante', 'dato' => $parametros['sa_id_representante']),
            array('campo' => 'sa_est_rep_parentesco', 'dato' => $parametros['sa_est_rep_parentesco']),
            array('campo' => 'sa_est_correo', 'dato' => $parametros['sa_est_correo']),
        );

        if ($parametros['sa_est_id'] == '') {
            if (count($this->modelo->buscar_estudiantes_CEDULA($datos1[0]['dato'])) == 0) {
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

    function lista_estudiante_representante($id)
    {
        $datos = $this->modelo->buscar_estudiantes_representante($id);
        return $datos;
    }

<<<<<<< HEAD
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

    function buscar_estudiante_ficha_medica($id_estudiante)
    {
        if (count($this->modelo->buscar_estudiante_ficha_medica($id_estudiante)) == 1) {
            return $this->modelo->buscar_estudiante_ficha_medica($id_estudiante);
        } else if (count($this->modelo->buscar_estudiante_ficha_medica($id_estudiante)) == 0) {
            return -1;
        } else if (count($this->modelo->buscar_estudiante_ficha_medica($id_estudiante)) > 0) {
            return -2;
        }
    }
=======
    //Validacion para determinar si tiene una ficha medica
>>>>>>> f975ff57302e9fcddee9c8879ae90e7325aab8d1
}
