<?php
include('../modelo/seccionM.php');

$controlador = new seccionC();

if (isset($_GET['lista'])) {
    echo json_encode($controlador->lista_seccion($_POST['id']));
}

if (isset($_GET['buscar'])) {
    echo json_encode($controlador->buscar_seccion($_POST['buscar']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

//print_r($controlador->lista_seccion(''));

class seccionC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new seccionM();
   
    }

    function lista_seccion($id)
    {
        $datos = $this->modelo->lista_seccion($id);
        return $datos;
    }

    function buscar_seccion($buscar)
    {
        $datos = $this->modelo->buscar_seccion($buscar);
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos[0]['campo'] = 'CODIGO';
        $datos[0]['dato'] = strval($parametros['cod']);
        $datos[1]['campo'] = 'DESCRIPCION';
        $datos[1]['dato'] = $parametros['des'];
        if ($parametros['id'] == '') {
            if (count($this->modelo->buscar_seccion_CODIGO($datos[0]['dato'])) == 0) {
                $datos = $this->modelo->insertar($datos);
                $movimiento = 'Insertado nuevo registro en SECCION (' . $parametros['des'] . ')';
            } else {
                return -2;
            }
        } else {
            $movimiento = $this->compara_datos($parametros);
            $where[0]['campo'] = 'ID_seccion';
            $where[0]['dato'] = $parametros['id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function compara_datos($parametros)
    {
        $text = '';
        $marca = $this->modelo->lista_seccion($parametros['id']);

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
        $datos[0]['campo'] = 'ID_seccion';
        $datos[0]['dato'] = $id;
        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }
}
