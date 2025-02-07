<?php
require_once(dirname(__DIR__, 1) .  '/modelo/representantesM.php');

require_once(dirname(__DIR__, 1) . '/db/codigos_globales.php');


$controlador = new representantesC();

//Para mostrar todos los registros con campos especificos para la vista principal
if (isset($_GET['listar_todo'])) {
    echo json_encode($controlador->lista_todo_representantes());
}

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

if (isset($_GET['lista_representantes_select'])) {
    echo json_encode($controlador->lista_representantes_select(''));
}


//echo json_encode($controlador->insertar_editar('Ejemplo1'));

class representantesC
{
    private $modelo;
    private $cod_global;

    function __construct()
    {
        $this->modelo = new representantesM();
        $this->cod_global = new codigos_globales();
    }

    function lista_todo_representantes()
    {
        $datos = $this->modelo->lista_representantes_todo();
        return $datos;
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
            array('campo' => 'PASS', 'dato' => $this->cod_global->enciptar_clave($parametros['sa_rep_cedula'])),
        );

        if ($parametros['sa_rep_id'] == '') {
            if (count($this->modelo->buscar_representantes_CEDULA($datos1[0]['dato'])) == 0) {
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
    }
}
