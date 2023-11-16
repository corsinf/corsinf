<?php
include('../modelo/consultasM.php');

$controlador = new consultasC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->lista_consultas($_POST['id']));
}

if (isset($_GET['buscar'])) {
    echo json_encode($controlador->buscar_consultas($_POST['buscar']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['listar_solo_consulta'])) {
    echo json_encode($controlador->lista_solo_consultas($_POST['id']));
}

//print_r($controlador->lista_consultas(''));

/*$parametros = array(
    'sa_sec_id' => 1,
    'sa_sec_nombre' => 'hola'
);

print_r($controlador->insertar_editar($parametros));*/

/*$modelo = new consultasM();

print_r($modelo->buscar_consultas_CODIGO(1));*/

class consultasC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new consultasM();
    }

    function lista_consultas($id)
    {
        $datos = $this->modelo->lista_consultas($id);
        return $datos;
    }

    function lista_solo_consultas($id)
    {
        $datos = $this->modelo->lista_solo_consultas($id);
        return $datos;
    }

    function buscar_consultas($buscar)
    {
        $datos = $this->modelo->buscar_consultas($buscar);
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos1[0]['campo'] = 'sa_fice_id';
        $datos1[0]['dato'] = ($parametros['sa_fice_id']);

        $datos = array(
            array('campo' => 'sa_fice_est_id', 'dato' => strval($parametros['sa_fice_est_id'])),
            array('campo' => 'sa_fice_est_primer_apellido', 'dato' => $parametros['sa_fice_est_primer_apellido']),
            array('campo' => 'sa_fice_est_segundo_apellido', 'dato' => $parametros['sa_fice_est_segundo_apellido']),
            array('campo' => 'sa_fice_est_primer_nombre', 'dato' => $parametros['sa_fice_est_primer_nombre']),
            array('campo' => 'sa_fice_est_segundo_nombre', 'dato' => $parametros['sa_fice_est_segundo_nombre']),
            array('campo' => 'sa_fice_est_fecha_nacimiento', 'dato' => $parametros['sa_fice_est_fecha_nacimiento']),
            array('campo' => 'sa_fice_est_grupo_sangre', 'dato' => $parametros['sa_fice_est_grupo_sangre']),
            array('campo' => 'sa_fice_est_direccion_domicilio', 'dato' => $parametros['sa_fice_est_direccion_domicilio']),
            array('campo' => 'sa_fice_est_seguro_medico', 'dato' => $parametros['sa_fice_est_seguro_medico']),
            array('campo' => 'sa_fice_est_nombre_seguro', 'dato' => $parametros['sa_fice_est_nombre_seguro']),
            array('campo' => 'sa_fice_rep_1_id', 'dato' => strval($parametros['sa_fice_rep_1_id'])),
            array('campo' => 'sa_fice_rep_1_primer_apellido', 'dato' => $parametros['sa_fice_rep_1_primer_apellido']),
            array('campo' => 'sa_fice_rep_1_segundo_apellido', 'dato' => $parametros['sa_fice_rep_1_segundo_apellido']),
            array('campo' => 'sa_fice_rep_1_primer_nombre', 'dato' => $parametros['sa_fice_rep_1_primer_nombre']),
            array('campo' => 'sa_fice_rep_1_segundo_nombre', 'dato' => $parametros['sa_fice_rep_1_segundo_nombre']),
            array('campo' => 'sa_fice_rep_1_parentesco', 'dato' => $parametros['sa_fice_rep_1_parentesco']),
            array('campo' => 'sa_fice_rep_1_telefono_1', 'dato' => $parametros['sa_fice_rep_1_telefono_1']),
            array('campo' => 'sa_fice_rep_1_telefono_2', 'dato' => $parametros['sa_fice_rep_1_telefono_2']),
            array('campo' => 'sa_fice_rep_2_primer_apellido', 'dato' => $parametros['sa_fice_rep_2_primer_apellido']),
            array('campo' => 'sa_fice_rep_2_segundo_apellido', 'dato' => $parametros['sa_fice_rep_2_segundo_apellido']),
            array('campo' => 'sa_fice_rep_2_primer_nombre', 'dato' => $parametros['sa_fice_rep_2_primer_nombre']),
            array('campo' => 'sa_fice_rep_2_segundo_nombre', 'dato' => $parametros['sa_fice_rep_2_segundo_nombre']),
            array('campo' => 'sa_fice_rep_2_parentesco', 'dato' => $parametros['sa_fice_rep_2_parentesco']),
            array('campo' => 'sa_fice_rep_2_telefono_1', 'dato' => $parametros['sa_fice_rep_2_telefono_1']),
            array('campo' => 'sa_fice_rep_2_telefono_2', 'dato' => $parametros['sa_fice_rep_2_telefono_2']),
            array('campo' => 'sa_fice_pregunta_1', 'dato' => $parametros['sa_fice_pregunta_1']),
            array('campo' => 'sa_fice_pregunta_1_obs', 'dato' => $parametros['sa_fice_pregunta_1_obs']),
            array('campo' => 'sa_fice_pregunta_2', 'dato' => $parametros['sa_fice_pregunta_2']),
            array('campo' => 'sa_fice_pregunta_2_obs', 'dato' => $parametros['sa_fice_pregunta_2_obs']),
            array('campo' => 'sa_fice_pregunta_3', 'dato' => $parametros['sa_fice_pregunta_3']),
            array('campo' => 'sa_fice_pregunta_3_obs', 'dato' => $parametros['sa_fice_pregunta_3_obs']),
            array('campo' => 'sa_fice_pregunta_4', 'dato' => $parametros['sa_fice_pregunta_4']),
            array('campo' => 'sa_fice_pregunta_4_obs', 'dato' => $parametros['sa_fice_pregunta_4_obs']),
            array('campo' => 'sa_fice_pregunta_5_obs', 'dato' => $parametros['sa_fice_pregunta_5_obs']),
        );

        if ($parametros['sa_fice_id'] == '') {
            if (count($this->modelo->buscar_consultas_CODIGO($datos1[0]['dato'])) == 0) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            $where[0]['campo'] = 'sa_fice_id';
            $where[0]['dato'] = $parametros['sa_fice_id'];
            $datos = $this->modelo->editar($datos, $where);
        }
        return $datos;
    }

    function compara_datos($parametros)
    {
        $text = '';
        $marca = $this->modelo->lista_consultas($parametros['id']);

        if ($marca[0]['CODIGO'] != $parametros['cod']) {
            $text .= ' Se modifico CODIGO en SECCION de ' . $marca[0]['CODIGO'] . ' a ' . $parametros['cod'];
        }

        if ($marca[0]['DESCRIPCION'] != $parametros['des']) {
            $text .= ' Se modifico DESCRIPCION en SECCION DE ' . $marca[0]['DESCRIPCION'] . ' a ' . $parametros['des'];
        }

        return $text;
    }

    function eliminar($id)
    {
        $datos[0]['campo'] = 'sa_fice_id';
        $datos[0]['dato'] = $id;
        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }
}
