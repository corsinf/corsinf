<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/GENERAL/th_personasM.php');

$controlador = new th_personasC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}


class th_personasC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_personasM();
    }

    function listar($id = '')
    {
        if ($id == '') {
            $datos = $this->modelo->where('th_per_estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('th_per_id', $id)->listar();
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $txt_fecha_nacimiento = !empty($parametros['txt_fecha_nacimiento']) ? $parametros['txt_fecha_nacimiento'] : null;

        $datos = array(
            array('campo' => 'th_per_primer_apellido', 'dato' => $parametros['txt_primer_apellido']),
            array('campo' => 'th_per_segundo_apellido', 'dato' => $parametros['txt_segundo_apellido']),
            array('campo' => 'th_per_primer_nombre', 'dato' => $parametros['txt_primer_nombre']),
            array('campo' => 'th_per_segundo_nombre', 'dato' => $parametros['txt_segundo_nombre']),
            array('campo' => 'th_per_cedula', 'dato' => $parametros['txt_cedula']),
            array('campo' => 'th_per_sexo', 'dato' => $parametros['ddl_sexo']),
            array('campo' => 'th_per_fecha_nacimiento', 'dato' => $txt_fecha_nacimiento),
            array('campo' => 'th_per_nacionalidad', 'dato' => $parametros['ddl_nacionalidad']),
            array('campo' => 'th_per_telefono_1', 'dato' => $parametros['txt_telefono_1']),
            array('campo' => 'th_per_telefono_2', 'dato' => $parametros['txt_telefono_2']),
            array('campo' => 'th_per_correo', 'dato' => $parametros['txt_correo']),
            array('campo' => 'th_per_direccion', 'dato' => $parametros['txt_direccion']),
            array('campo' => 'th_per_estado_civil', 'dato' => $parametros['ddl_estado_civil']),
            array('campo' => 'th_prov_id', 'dato' => $parametros['ddl_provincias']),
            array('campo' => 'th_ciu_id', 'dato' => $parametros['ddl_ciudad']),
            array('campo' => 'th_parr_id', 'dato' => $parametros['ddl_parroquia']),
            array('campo' => 'th_per_postal', 'dato' => $parametros['txt_codigo_postal']),
            array('campo' => 'th_per_observaciones', 'dato' => $parametros['txt_observaciones']),
            // array('campo' => 'th_per_foto_url', 'dato' => $parametros['txt_foto_url']),
            //array('campo' => 'PASS', 'dato' => $this->cod_global->enciptar_clave($parametros['txt_cedula'])),
            array('campo' => 'th_per_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
            array('campo' => 'th_per_tipo_sangre', 'dato' => $parametros['ddl_tipo_sangre']),
        );

        if ($parametros['_id'] == '') {
            if (count($this->modelo->where('th_per_cedula', $parametros['txt_cedula'])->listar()) == 0) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            if (count($this->modelo->where('th_per_cedula', $parametros['txt_cedula'])->where('th_per_id !', $parametros['_id'])->listar()) == 0) {
                $where[0]['campo'] = 'th_per_id';
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
            array('campo' => 'th_per_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_per_id';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }
}
