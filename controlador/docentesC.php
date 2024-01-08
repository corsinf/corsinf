<?php
include('../modelo/docentesM.php');

$controlador = new docentesC();

//Para mostrar todos los registros con campos especificos para la vista principal
if (isset($_GET['listar_todo'])) {
    echo json_encode($controlador->lista_todo_docentes());
}

if (isset($_GET['listar'])) {
    echo json_encode($controlador->lista_docentes($_POST['id']));
}

if (isset($_GET['buscar'])) {
    echo json_encode($controlador->buscar_docentes($_POST['buscar']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['lista_docentes_select'])) {
    echo json_encode($controlador->lista_docentes_select(''));
}


//echo json_encode($controlador->insertar_editar('Ejemplo1'));

class docentesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new docentesM();
    }

    function lista_todo_docentes()
    {
        $datos = $this->modelo->lista_docentes_todo();
        return $datos;
    }

    function lista_docentes($id)
    {
        $datos = $this->modelo->lista_docentes($id);
        return $datos;
    }

    function buscar_docentes($buscar)
    {
        $datos = $this->modelo->buscar_docentes($buscar);
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos1[0]['campo'] = 'sa_doc_cedula';
        $datos1[0]['dato'] = strval($parametros['sa_doc_cedula']);

        $datos = array(
            array('campo' => 'sa_doc_primer_apellido', 'dato' => $parametros['sa_doc_primer_apellido']),
            array('campo' => 'sa_doc_segundo_apellido', 'dato' => $parametros['sa_doc_segundo_apellido']),
            array('campo' => 'sa_doc_primer_nombre', 'dato' => $parametros['sa_doc_primer_nombre']),
            array('campo' => 'sa_doc_segundo_nombre', 'dato' => $parametros['sa_doc_segundo_nombre']),
            array('campo' => 'sa_doc_cedula', 'dato' => $parametros['sa_doc_cedula']),
            array('campo' => 'sa_doc_sexo', 'dato' => $parametros['sa_doc_sexo']),
            array('campo' => 'sa_doc_fecha_nacimiento', 'dato' => $parametros['sa_doc_fecha_nacimiento']),
            array('campo' => 'sa_doc_correo', 'dato' => $parametros['sa_doc_correo']),
            array('campo' => 'sa_doc_telefono_1', 'dato' => $parametros['sa_doc_telefono_1']),
            array('campo' => 'sa_doc_telefono_2', 'dato' => $parametros['sa_doc_telefono_2']),
        );

        if ($parametros['sa_doc_id'] == '') {
            if (count($this->modelo->buscar_docentes_CEDULA($datos1[0]['dato'])) == 0) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            $where[0]['campo'] = 'sa_doc_id';
            $where[0]['dato'] = $parametros['sa_doc_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos[0]['campo'] = 'sa_doc_id';
        $datos[0]['dato'] = $id;
        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }

    ///////////////////////////////////
    //se utiliza para cargar un select2
    function lista_docentes_select($buscar)
    {
        $datos = $this->modelo->buscar_docentes($buscar);
        $lista = array();
        foreach ($datos as $key => $value) {
            $lista[] = array('id' => $value['sa_doc_id'], 'text' => $value['sa_doc_primer_apellido'] . ' ' . $value['sa_doc_primer_nombre'], 'data' => $value);
        }
        return $lista;
    }
}
