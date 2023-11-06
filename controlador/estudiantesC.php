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

//echo json_encode($controlador->buscar_estudiantes('Ejemplo1'));

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
        $datos1[0]['campo'] = 'sa_par_id';
        $datos1[0]['dato'] = strval($parametros['sa_par_id']);
        $datos[1]['campo'] = 'sa_par_nombre';
        $datos[1]['dato'] = $parametros['sa_par_nombre'];
        $datos[2]['campo'] = 'sa_id_seccion';
        $datos[2]['dato'] = $parametros['sa_id_seccion'];
        $datos[3]['campo'] = 'sa_id_grado';
        $datos[3]['dato'] = $parametros['sa_id_grado'];

        if ($parametros['sa_par_id'] == '') {
            if (count($this->modelo->buscar_estudiantes_CODIGO($datos1[0]['dato'])) == 0) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            $where[0]['campo'] = 'sa_par_id';
            $where[0]['dato'] = $parametros['sa_par_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos[0]['campo'] = 'sa_par_id';
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

}
