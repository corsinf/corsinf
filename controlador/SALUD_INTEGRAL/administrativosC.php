<?php
include('../modelo/administrativosM.php');

$controlador = new administrativosC();

//Para mostrar todos los registros con campos especificos para la vista principal
if (isset($_GET['listar_todo'])) {
    echo json_encode($controlador->lista_todo_administrativos());
}

if (isset($_GET['listar'])) {
    echo json_encode($controlador->lista_administrativos($_POST['id']));
}

if (isset($_GET['buscar'])) {
    echo json_encode($controlador->buscar_administrativos($_POST['buscar']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['lista_administrativos_select'])) {
    echo json_encode($controlador->lista_administrativos_select(''));
}


//echo json_encode($controlador->insertar_editar('Ejemplo1'));

class administrativosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new administrativosM();
    }

    function lista_todo_administrativos()
    {
        $datos = $this->modelo->lista_administrativos_todo();
        return $datos;
    }

    function lista_administrativos($id)
    {
        $datos = $this->modelo->lista_administrativos($id);
        return $datos;
    }

    function buscar_administrativos($buscar)
    {
        $datos = $this->modelo->buscar_administrativos($buscar);
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos1[0]['campo'] = 'sa_adm_cedula';
        $datos1[0]['dato'] = strval($parametros['sa_adm_cedula']);

        $datos = array(
            array('campo' => 'sa_adm_primer_apellido', 'dato' => $parametros['sa_adm_primer_apellido']),
            array('campo' => 'sa_adm_segundo_apellido', 'dato' => $parametros['sa_adm_segundo_apellido']),
            array('campo' => 'sa_adm_primer_nombre', 'dato' => $parametros['sa_adm_primer_nombre']),
            array('campo' => 'sa_adm_segundo_nombre', 'dato' => $parametros['sa_adm_segundo_nombre']),
            array('campo' => 'sa_adm_cedula', 'dato' => $parametros['sa_adm_cedula']),
            array('campo' => 'sa_adm_sexo', 'dato' => $parametros['sa_adm_sexo']),
            array('campo' => 'sa_adm_fecha_nacimiento', 'dato' => $parametros['sa_adm_fecha_nacimiento']),
            array('campo' => 'sa_adm_correo', 'dato' => $parametros['sa_adm_correo']),
            array('campo' => 'sa_adm_telefono_1', 'dato' => $parametros['sa_adm_telefono_1']),
            array('campo' => 'sa_adm_telefono_2', 'dato' => $parametros['sa_adm_telefono_2']),
        );

        if ($parametros['sa_adm_id'] == '') {
            if (count($this->modelo->buscar_administrativos_CEDULA($datos1[0]['dato'])) == 0) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            $where[0]['campo'] = 'sa_adm_id';
            $where[0]['dato'] = $parametros['sa_adm_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos[0]['campo'] = 'sa_adm_id';
        $datos[0]['dato'] = $id;
        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }

    ///////////////////////////////////
    //se utiliza para cargar un select2
    function lista_administrativos_select($buscar)
    {
        $datos = $this->modelo->buscar_administrativos($buscar);
        $lista = array();
        foreach ($datos as $key => $value) {
            $lista[] = array('id' => $value['sa_adm_id'], 'text' => $value['sa_adm_primer_apellido'] . ' ' . $value['sa_adm_primer_nombre'], 'data' => $value);
        }
        return $lista;
    }
}
