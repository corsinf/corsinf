<?php
include('../modelo/medicamentosM.php');

$controlador = new medicamentosC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->lista_medicamentos($_POST['id']));
}

if (isset($_GET['buscar'])) {
    echo json_encode($controlador->buscar_medicamentos($_POST['buscar']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

class medicamentosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new medicamentosM();
    }

    function lista_medicamentos($id)
    {
        $datos = $this->modelo->lista_medicamentos($id);
        return $datos;
    }

    function buscar_medicamentos($buscar)
    {
        $datos = $this->modelo->buscar_medicamentos($buscar);
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos1[0]['campo'] = 'sa_med_id';
        $datos1[0]['dato'] = strval($parametros['sa_med_id']);
        $datos[1]['campo'] = 'sa_med_nombre';
        $datos[1]['dato'] = $parametros['sa_med_nombre'];

        if ($parametros['sa_med_id'] == '') {
            if (count($this->modelo->buscar_medicamentos_CODIGO($datos1[0]['dato'])) == 0) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            $where[0]['campo'] = 'sa_med_id';
            $where[0]['dato'] = $parametros['sa_med_id'];
            $datos = $this->modelo->editar($datos, $where);
        }
        return $datos;
    }

    function compara_datos($parametros)
    {
        $text = '';
        $marca = $this->modelo->lista_medicamentos($parametros['id']);

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
        $datos[0]['campo'] = 'sa_med_id';
        $datos[0]['dato'] = $id;
        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }
}
