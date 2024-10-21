<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/FORMACION/fo_personasM.php');

$controlador = new fo_personasC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}


class fo_personasC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new fo_personasM();
    }

    function listar($id = '')
    {
        if ($id == '') {
            $datos = $this->modelo->where('fo_per_estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('fo_per_id', $id)->listar();
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $txt_fecha_nacimiento = !empty($parametros['txt_fecha_nacimiento']) ? $parametros['txt_fecha_nacimiento'] : null;

        $datos = array(
            array('campo' => 'fo_per_primer_apellido', 'dato' => $parametros['txt_primer_apellido']),
            array('campo' => 'fo_per_segundo_apellido', 'dato' => $parametros['txt_segundo_apellido']),
            array('campo' => 'fo_per_primer_nombre', 'dato' => $parametros['txt_primer_nombre']),
            array('campo' => 'fo_per_segundo_nombre', 'dato' => $parametros['txt_segundo_nombre']),
            array('campo' => 'fo_per_cedula', 'dato' => $parametros['txt_cedula']),
            array('campo' => 'fo_per_sexo', 'dato' => $parametros['ddl_sexo']),
            array('campo' => 'fo_per_fecha_nacimiento', 'dato' => $txt_fecha_nacimiento),
            array('campo' => 'fo_per_telefono_1', 'dato' => $parametros['txt_telefono_1']),
            array('campo' => 'fo_per_telefono_2', 'dato' => $parametros['txt_telefono_2']),
            array('campo' => 'fo_per_correo', 'dato' => $parametros['txt_correo']),
            array('campo' => 'fo_per_direccion', 'dato' => $parametros['txt_direccion']),
            // array('campo' => 'fo_per_foto_url', 'dato' => $parametros['txt_foto_url']),
            array('campo' => 'fo_estado_civil', 'dato' => $parametros['ddl_estado_civil']),
            // array('campo' => 'fo_prov_id', 'dato' => $parametros['txt__id']),
            // array('campo' => 'fo_ciu_id', 'dato' => $parametros['txt_id']),
            // array('campo' => 'fo_barr_id', 'dato' => $parametros['txt__id']),
            array('campo' => 'fo_per_postal', 'dato' => $parametros['txt_postal']),
            array('campo' => 'fo_per_observaciones', 'dato' => $parametros['txt_observaciones']),
            //array('campo' => 'PASS', 'dato' => $parametros['PASS']),
            array('campo' => 'fo_per_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );

        if ($parametros['_id'] == '') {
            if (count($this->modelo->where('fo_per_cedula', $parametros['txt_cedula'])->listar()) == 0) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            if (count($this->modelo->where('fo_per_cedula', $parametros['txt_cedula'])->where('fo_per_id !', $parametros['_id'])->listar()) == 0) {
                $where[0]['campo'] = 'fo_per_id';
                $where[0]['dato'] = $parametros['_id'];
                $datos = $this->modelo->editar($datos, $where);
            } else {
                return -2;
            }
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'fo_per_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'fo_per_id';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }

    
}
