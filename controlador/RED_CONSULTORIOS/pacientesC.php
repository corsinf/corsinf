<?php
require_once(dirname(__DIR__, 2) . '/modelo/RED_CONSULTORIOS/pacientesM.php');

$controlador = new pacientesC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar());
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}


class pacientesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new pacientesM();
    }

    function listar()
    {
        $datos = $this->modelo->listar();

        
        return $datos;
    }

    function insertar_editar($parametros)
    {

        $datos1[0]['campo'] = 'pac_id';
        $datos1[0]['dato'] = strval($parametros['txt_id']);

        $datos = array(
            array('campo' => 'pac_primer_apellido', 'dato' => $parametros['txt_primer_apellido']),
            array('campo' => 'pac_segundo_apellido', 'dato' => $parametros['txt_segundo_apellido']),
            array('campo' => 'pac_primer_nombre', 'dato' => $parametros['txt_primer_nombre']),
            array('campo' => 'pac_segundo_nombre', 'dato' => $parametros['txt_segundo_nombre']),
            array('campo' => 'pac_cedula', 'dato' => $parametros['txt_cedula']),
            array('campo' => 'pac_sexo', 'dato' => $parametros['ddl_sexo']),
            array('campo' => 'pac_tipo_sangre', 'dato' => $parametros['txt_tipo_sangre']),
            array('campo' => 'pac_fecha_nacimiento', 'dato' => $parametros['txt_fecha_nacimiento']),
            array('campo' => 'pac_telefono_1', 'dato' => $parametros['txt_telefono_1']),
            array('campo' => 'pac_telefono_2', 'dato' => $parametros['txt_telefono_2']),
            array('campo' => 'pac_correo', 'dato' => $parametros['txt_correo']),
            array('campo' => 'pac_direccion', 'dato' => $parametros['txt_direccion']),
        );

        if ($parametros['txt_id'] == '') {
            if (count($this->modelo->where('pac_cedula', $parametros['txt_cedula'])->listar()) == 0) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            $where[0]['campo'] = 'pac_id';
            $where[0]['dato'] = $parametros['pac_id'];
            $datos = $this->modelo->editar($datos, $where);
        }
        //$datos = $this->modelo->insertar($datos);
        return $datos;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'pac_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'pac_id';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }
}
