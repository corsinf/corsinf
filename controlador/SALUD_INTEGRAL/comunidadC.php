<?php
require_once(dirname(__DIR__, 2) . '/modelo/SALUD_INTEGRAL/comunidadM.php');

$controlador = new comunidadC();

//Para mostrar todos los registros con campos especificos para la vista principal
if (isset($_GET['listar_todo'])) {
    echo json_encode($controlador->lista_todo_comunidad());
}

if (isset($_GET['listar'])) {
    echo json_encode($controlador->lista_comunidad($_POST['id']));
}

if (isset($_GET['buscar'])) {
    echo json_encode($controlador->buscar_comunidad($_POST['buscar']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['lista_comunidad_select'])) {
    echo json_encode($controlador->lista_comunidad_select(''));
}


//echo json_encode($controlador->insertar_editar('Ejemplo1'));

class comunidadC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new comunidadM();
    }

    function lista_todo_comunidad()
    {
        $datos = $this->modelo->lista_comunidad_todo();
        return $datos;
    }

    function lista_comunidad($id)
    {
        $datos = $this->modelo->lista_comunidad($id);
        return $datos;
    }

    function buscar_comunidad($buscar)
    {
        $datos = $this->modelo->buscar_comunidad($buscar);
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos1[0]['campo'] = 'sa_com_cedula';
        $datos1[0]['dato'] = strval($parametros['sa_com_cedula']);

        $datos = array(
            array('campo' => 'sa_com_primer_apellido', 'dato' => $parametros['sa_com_primer_apellido']),
            array('campo' => 'sa_com_segundo_apellido', 'dato' => $parametros['sa_com_segundo_apellido']),
            array('campo' => 'sa_com_primer_nombre', 'dato' => $parametros['sa_com_primer_nombre']),
            array('campo' => 'sa_com_segundo_nombre', 'dato' => $parametros['sa_com_segundo_nombre']),
            array('campo' => 'sa_com_cedula', 'dato' => $parametros['sa_com_cedula']),
            array('campo' => 'sa_com_sexo', 'dato' => $parametros['sa_com_sexo']),
            array('campo' => 'sa_com_fecha_nacimiento', 'dato' => $parametros['sa_com_fecha_nacimiento']),
            array('campo' => 'sa_com_correo', 'dato' => $parametros['sa_com_correo']),
            array('campo' => 'sa_com_telefono_1', 'dato' => $parametros['sa_com_telefono_1']),
            array('campo' => 'sa_com_telefono_2', 'dato' => $parametros['sa_com_telefono_2']),
        );

        if ($parametros['sa_com_id'] == '') {
            if (count($this->modelo->buscar_comunidad_CEDULA($datos1[0]['dato'])) == 0) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            $where[0]['campo'] = 'sa_com_id';
            $where[0]['dato'] = $parametros['sa_com_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos[0]['campo'] = 'sa_com_id';
        $datos[0]['dato'] = $id;
        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }

    ///////////////////////////////////
    //se utiliza para cargar un select2
    function lista_comunidad_select($buscar)
    {
        $datos = $this->modelo->buscar_comunidad($buscar);
        $lista = array();
        foreach ($datos as $key => $value) {
            $lista[] = array('id' => $value['sa_com_id'], 'text' => $value['sa_com_primer_apellido'] . ' ' . $value['sa_com_primer_nombre'], 'data' => $value);
        }
        return $lista;
    }
}
