<?php
include('../modelo/fichas_EstudianteM.php');

$controlador = new fichas_EstudianteC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->lista_fichas_Estudiante($_POST['id']));
}

if (isset($_GET['buscar'])) {
    echo json_encode($controlador->buscar_fichas_Estudiante($_POST['buscar']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['listar_solo_ficha'])) {
    echo json_encode($controlador->lista_solo_ficha_Estudiante($_POST['id']));
}

//print_r($controlador->lista_fichas_Estudiante(''));

/*$parametros = array(
    'sa_sec_id' => 1,
    'sa_sec_nombre' => 'hola'
);

print_r($controlador->insertar_editar($parametros));*/

/*$modelo = new fichas_EstudianteM();

print_r($modelo->buscar_fichas_Estudiante_CODIGO(1));*/

class fichas_EstudianteC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new fichas_EstudianteM();
    }

    function lista_fichas_Estudiante($id)
    {
        $datos = $this->modelo->lista_fichas_Estudiante($id);
        return $datos;
    }

    function lista_solo_ficha_Estudiante($id)
    {
        $datos = $this->modelo->lista_solo_ficha_Estudiante($id);
        return $datos;
    }

    function buscar_fichas_Estudiante($buscar)
    {
        $datos = $this->modelo->buscar_fichas_Estudiante($buscar);
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos1[0]['campo'] = 'sa_sec_id';
        $datos1[0]['dato'] = strval($parametros['sa_sec_id']);
        $datos[1]['campo'] = 'sa_sec_nombre';
        $datos[1]['dato'] = $parametros['sa_sec_nombre'];

        if ($parametros['sa_sec_id'] == '') {
            if (count($this->modelo->buscar_fichas_Estudiante_CODIGO($datos1[0]['dato'])) == 0) {
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
        $marca = $this->modelo->lista_fichas_Estudiante($parametros['id']);

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
