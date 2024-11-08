<?php

require_once(dirname(__DIR__, 4) . '/modelo/PASANTES/02_ADRIAN/POSTULANTES/th_pos_idiomasM.php');

$controlador = new th_pos_idiomasC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}



class th_pos_idiomasC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_pos_idiomasM();
    }

    function listar($id)
    {
        if ($id == '') {
            $datos = $this->modelo->where('th_pos_estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('th_pos_id', $id)->listar();
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
       //print_r($parametros); exit(); die();

        $datos = array(
            array('campo' => 'th_pos_primer_nombre', 'dato' => $parametros['txt_primer_nombre']),
            array('campo' => 'th_pos_segundo_nombre', 'dato' => $parametros['txt_segundo_nombre']),
            array('campo' => 'th_pos_primer_apellido', 'dato' => $parametros['txt_primer_apellido']),
            array('campo' => 'th_pos_segundo_apellido', 'dato' => $parametros['txt_segundo_apellido']),
            array('campo' => 'th_pos_cedula', 'dato' => $parametros['txt_numero_cedula']),
            array('campo' => 'th_pos_sexo', 'dato' => $parametros['ddl_sexo']),
            array('campo' => 'th_prov_id', 'dato' => $parametros['ddl_provincias']),
            array('campo' => 'th_ciu_id', 'dato' => $parametros['ddl_ciudad']),
            array('campo' => 'th_parr_id', 'dato' => $parametros['ddl_parroquia']),
            array('campo' => 'th_pos_direccion', 'dato' => $parametros['txt_direccion']),
            array('campo' => 'th_pos_postal', 'dato' => $parametros['txt_codigo_postal']),
            array('campo' => 'th_pos_fecha_nacimiento', 'dato' => $parametros['txt_fecha_nacimiento']),
            array('campo' => 'th_pos_nacionalidad', 'dato' => $parametros['ddl_nacionalidad']),
            array('campo' => 'th_pos_estado_civil', 'dato' => $parametros['ddl_estado_civil']),
            array('campo' => 'th_pos_telefono_1', 'dato' => $parametros['txt_telefono_1']),
            array('campo' => 'th_pos_telefono_2', 'dato' => $parametros['txt_telefono_2']),
            array('campo' => 'th_pos_correo', 'dato' => $parametros['txt_correo']),

        );

        if ($parametros['_id'] == '') {
            if (count($this->modelo->where('th_pos_cedula', $parametros['txt_numero_cedula'])->listar()) == 0) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            $where[0]['campo'] = 'th_pos_id';
            $where[0]['dato'] = $parametros['_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_pos_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_pos_id';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }

}
