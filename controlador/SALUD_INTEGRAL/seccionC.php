<?php
require_once(dirname(__DIR__, 2) . '/modelo/SALUD_INTEGRAL/seccionM.php');

$controlador = new seccionC();

if (isset($_GET['listar'])) {
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

/*$parametros = array(
    'sa_sec_id' => 1,
    'sa_sec_nombre' => 'hola'
);

print_r($controlador->insertar_editar($parametros));*/

/*$modelo = new seccionM();

print_r($modelo->buscar_seccion_CODIGO(1));*/

class seccionC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new seccionM();
    }

    function lista_seccion($id)
    {
        $id_rol = 1;
        if ($id_rol == 1) {
            $datos = $this->modelo->lista_seccion($id);
            return $datos;
        }
    }

    function buscar_seccion($buscar)
    {
        $datos = $this->modelo->buscar_seccion($buscar);
        return $datos;
    }

    function insertar_editar($parametros)
    {

        $datos1[0]['campo'] = 'sa_sec_id';
        $datos1[0]['dato'] = strval($parametros['sa_sec_id']);
        $datos[1]['campo'] = 'sa_sec_nombre';
        $datos[1]['dato'] = $parametros['sa_sec_nombre'];

        if ($parametros['sa_sec_id'] == '') {
            if (count($this->modelo->buscar_seccion_CODIGO($datos1[0]['dato'])) == 0) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            $where[0]['campo'] = 'sa_sec_id';
            $where[0]['dato'] = $parametros['sa_sec_id'];
            $datos = $this->modelo->editar($datos, $where);
        }
        //$datos = $this->modelo->insertar($datos);
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
        $datos[0]['campo'] = 'sa_sec_id';
        $datos[0]['dato'] = $id;
        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }
}
