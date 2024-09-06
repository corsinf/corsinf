<?php
require_once(dirname(__DIR__, 4) . '/modelo/PASANTES/02_ADRIAN/talento_humano/th_postulantesM.php');

$controlador = new th_postulantesC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}


class th_postulantesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_postulantesM();
    }

    function listar($id)
    {
        $datos = $this->modelo->where('th_pos_id', $id)->listar($id);
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos1[0]['campo'] = 'th_pos_id';
        $datos1[0]['dato'] = strval($parametros['txt_primer_nombre']);

        $datos = array(
            array('campo' => 'th_pos_primer_nombre', 'dato' => $parametros['txt_primer_nombre']),
            array('campo' => 'th_pos_segundo_nombre', 'dato' => $parametros['txt_segundo_nombre']),
            array('campo' => 'th_pos_primer_apellido', 'dato' => $parametros['txt_primer_apellido']),
            array('campo' => 'th_pos_segundo_apellido', 'dato' => $parametros['txt_segundo_apellido']),
            array('campo' => 'th_pos_cedula', 'dato' => $parametros['txt_numero_cedula']),
            array('campo' => 'th_pos_sexo', 'dato' => $parametros['ddl_sexo']),
            array('campo' => 'th_pos_fecha_nacimiento', 'dato' => $parametros['txt_fecha_nacimiento']),
            array('campo' => 'th_pos_nacionalidad', 'dato' => $parametros['ddl_nacionalidad']),
            array('campo' => 'th_pos_estado_civil', 'dato' => $parametros['ddl_estado_civil']),
            array('campo' => 'th_pos_telefono_1', 'dato' => $parametros['txt_telefono_1']),
            array('campo' => 'th_pos_telefono_2', 'dato' => $parametros['txt_telefono_2']),
            array('campo' => 'th_pos_correo', 'dato' => $parametros['txt_correo']),

        );
        $datos = $this->modelo->insertar($datos);
        return $datos;
        return ($parametros);
    }
}
