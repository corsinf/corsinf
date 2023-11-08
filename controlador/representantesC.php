<?php
include('../modelo/representantesM.php');

$controlador = new representantesC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->lista_representantes($_POST['id']));
}

if (isset($_GET['buscar'])) {
    echo json_encode($controlador->buscar_representantes($_POST['buscar']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

//echo json_encode($controlador->insertar_editar('Ejemplo1'));

class representantesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new representantesM();
    }

    function lista_representantes($id)
    {
        $datos = $this->modelo->lista_representantes($id);
        return $datos;
    }

    function buscar_representantes($buscar)
    {
        $datos = $this->modelo->buscar_representantes($buscar);
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos1[0]['campo'] = 'sa_rep_id';
        $datos1[0]['dato'] = strval($parametros['sa_rep_id']);

        $datos[1]['campo'] = 'sa_rep_primer_apellido';
        $datos[1]['dato'] = $parametros['sa_rep_primer_apellido'];

        $datos[2]['campo'] = 'sa_rep_segundo_apellido';
        $datos[2]['dato'] = $parametros['sa_rep_segundo_apellido'];

        $datos[3]['campo'] = 'sa_rep_primer_nombre';
        $datos[3]['dato'] = $parametros['sa_rep_primer_nombre'];

        $datos[4]['campo'] = 'sa_rep_segundo_nombre';
        $datos[4]['dato'] = $parametros['sa_rep_segundo_nombre'];

        $datos[5]['campo'] = 'sa_rep_cedula';
        $datos[5]['dato'] = $parametros['sa_rep_cedula'];

        $datos[6]['campo'] = 'sa_rep_sexo';
        $datos[6]['dato'] = $parametros['sa_rep_sexo'];

        $datos[7]['campo'] = 'sa_rep_fecha_nacimiento';
        $datos[7]['dato'] = $parametros['sa_rep_fecha_nacimiento'];

        $datos[8]['campo'] = 'sa_id_seccion';
        $datos[8]['dato'] = $parametros['sa_id_seccion'];

        $datos[9]['campo'] = 'sa_id_grado';
        $datos[9]['dato'] = $parametros['sa_id_grado'];

        $datos[10]['campo'] = 'sa_id_paralelo';
        $datos[10]['dato'] = $parametros['sa_id_paralelo'];

        $datos[11]['campo'] = 'sa_rep_correo';
        $datos[11]['dato'] = $parametros['sa_rep_correo'];

        $datos[12]['campo'] = 'sa_id_representante';
        $datos[12]['dato'] = $parametros['sa_id_representante'];

        if ($parametros['sa_rep_id'] == '') {
            if (count($this->modelo->buscar_representantes_CODIGO($datos1[0]['dato'])) == 0) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            $where[0]['campo'] = 'sa_rep_id';
            $where[0]['dato'] = $parametros['sa_rep_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos[0]['campo'] = 'sa_rep_id';
        $datos[0]['dato'] = $id;
        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }

    function compara_datos($parametros)
    {
        $text = '';
        $marca = $this->modelo->lista_representantes($parametros['id']);

        if ($marca[0]['CODIGO'] != $parametros['cod']) {
            $text .= ' Se modifico CODIGO en SECCION de ' . $marca[0]['CODIGO'] . ' a ' . $parametros['cod'];
        }

        if ($marca[0]['DESCRIPCION'] != $parametros['des']) {
            $text .= ' Se modifico DESCRIPCION en SECCION DE ' . $marca[0]['DESCRIPCION'] . ' a ' . $parametros['des'];
        }

        return $text;
    }
}
