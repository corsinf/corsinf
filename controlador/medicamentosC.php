<?php
include('../modelo/medicamentosM.php');

$controlador = new medicamentosC();

if (isset($_GET['listar_todo'])) {
    echo json_encode($controlador->lista_todo_medicamentos());
}

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

    function lista_todo_medicamentos()
    {
        $datos = $this->modelo->lista_medicamentos_todo();
        return $datos;
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
        $datos1[0]['campo'] = 'sa_cmed_id';
        $datos1[0]['dato'] = strval($parametros['sa_cmed_id']);


        $datos = array(
            array('campo' => 'sa_cmed_concentracion', 'dato' => $parametros['sa_cmed_concentracion']),
            array('campo' => 'sa_cmed_presentacion', 'dato' => $parametros['sa_cmed_presentacion']),
            array('campo' => 'sa_cmed_serie', 'dato' => $parametros['sa_cmed_serie']),
            array('campo' => 'sa_cmed_lote', 'dato' => $parametros['sa_cmed_lote']),
            array('campo' => 'sa_cmed_caducidad', 'dato' => $parametros['sa_cmed_caducidad']),
            array('campo' => 'sa_cmed_minimos', 'dato' => $parametros['sa_cmed_minimos']),
            array('campo' => 'sa_cmed_stock', 'dato' => $parametros['sa_cmed_stock']),
            array('campo' => 'sa_cmed_movimiento', 'dato' => $parametros['sa_cmed_movimiento']),
            array('campo' => 'sa_cmed_contraindicacion', 'dato' => $parametros['sa_cmed_contraindicacion']),
            array('campo' => 'sa_cmed_dosis', 'dato' => $parametros['sa_cmed_dosis']),
            array('campo' => 'sa_cmed_tratamientos', 'dato' => $parametros['sa_cmed_tratamientos']),
            array('campo' => 'sa_cmed_uso', 'dato' => $parametros['sa_cmed_uso']),
            array('campo' => 'sa_cmed_observaciones', 'dato' => $parametros['sa_cmed_observaciones']),
        );


        if ($parametros['sa_cmed_id'] == '') {
            if (count($this->modelo->buscar_medicamentos_CODIGO($datos1[0]['dato'])) == 0) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            $where[0]['campo'] = 'sa_cmed_id';
            $where[0]['dato'] = $parametros['sa_cmed_id'];
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
        $datos[0]['campo'] = 'sa_cmed_id';
        $datos[0]['dato'] = $id;
        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }
}
