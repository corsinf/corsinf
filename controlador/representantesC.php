<?php
include('../modelo/representantesM.php');

$controlador = new representantesC();

<<<<<<< HEAD
=======
//Para mostrar todos los registros con campos especificos para la vista principal
if (isset($_GET['listar_todo'])) {
    echo json_encode($controlador->lista_todo_representantes());
}

>>>>>>> f975ff57302e9fcddee9c8879ae90e7325aab8d1
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

<<<<<<< HEAD
=======
if (isset($_GET['lista_representantes_select'])) {
    echo json_encode($controlador->lista_representantes_select(''));
}
>>>>>>> f975ff57302e9fcddee9c8879ae90e7325aab8d1


//echo json_encode($controlador->insertar_editar('Ejemplo1'));

class representantesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new representantesM();
    }

<<<<<<< HEAD
=======
    function lista_todo_representantes()
    {
        $datos = $this->modelo->lista_representantes_todo();
        return $datos;
    }

>>>>>>> f975ff57302e9fcddee9c8879ae90e7325aab8d1
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
<<<<<<< HEAD
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

        $datos[12]['campo'] = 'sa_rep_parentesco';
        $datos[12]['dato'] = $parametros['sa_rep_parentesco'];

        $datos[13]['campo'] = 'sa_rep_telefono_1';
        $datos[13]['dato'] = $parametros['sa_rep_telefono_1'];

        $datos[14]['campo'] = 'sa_rep_telefono_2';
        $datos[14]['dato'] = $parametros['sa_rep_telefono_2'];

        if ($parametros['sa_rep_id'] == '') {
            if (count($this->modelo->buscar_representantes_CODIGO($datos1[0]['dato'])) == 0) {
=======
        $datos1[0]['campo'] = 'sa_rep_cedula';
        $datos1[0]['dato'] = strval($parametros['sa_rep_cedula']);

        $datos = array(
            array('campo' => 'sa_rep_primer_apellido', 'dato' => $parametros['sa_rep_primer_apellido']),
            array('campo' => 'sa_rep_segundo_apellido', 'dato' => $parametros['sa_rep_segundo_apellido']),
            array('campo' => 'sa_rep_primer_nombre', 'dato' => $parametros['sa_rep_primer_nombre']),
            array('campo' => 'sa_rep_segundo_nombre', 'dato' => $parametros['sa_rep_segundo_nombre']),
            array('campo' => 'sa_rep_cedula', 'dato' => $parametros['sa_rep_cedula']),
            array('campo' => 'sa_rep_sexo', 'dato' => $parametros['sa_rep_sexo']),
            array('campo' => 'sa_rep_fecha_nacimiento', 'dato' => $parametros['sa_rep_fecha_nacimiento']),
            array('campo' => 'sa_rep_correo', 'dato' => $parametros['sa_rep_correo']),
            array('campo' => 'sa_rep_telefono_1', 'dato' => $parametros['sa_rep_telefono_1']),
            array('campo' => 'sa_rep_telefono_2', 'dato' => $parametros['sa_rep_telefono_2']),
        );

        if ($parametros['sa_rep_id'] == '') {
            if (count($this->modelo->buscar_representantes_CEDULA($datos1[0]['dato'])) == 0) {
>>>>>>> f975ff57302e9fcddee9c8879ae90e7325aab8d1
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

<<<<<<< HEAD
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
=======
    ///////////////////////////////////
    //No se utiliza para cargar un select2
    function lista_representantes_select($buscar)
    {
        $datos = $this->modelo->buscar_representantes($buscar);
        $lista = array();
        foreach ($datos as $key => $value) {
            $lista[] = array('id' => $value['sa_rep_id'], 'text' => $value['sa_rep_primer_apellido'] . ' ' . $value['sa_rep_primer_nombre'], 'data' => $value);
        }
        return $lista;
>>>>>>> f975ff57302e9fcddee9c8879ae90e7325aab8d1
    }
}
