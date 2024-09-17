<?php
require_once(dirname(__DIR__, 4) . '/modelo/PASANTES/02_ADRIAN/POSTULANTES/th_experiencia_laboralM.php');

$controlador = new th_experiencia_laboralC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}


class th_experiencia_laboralC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_experiencia_laboralM();
    }

    function listar($id)
    {
        $datos = $this->modelo->where('th_pos_id', $id)->listar();
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'th_expl_nombre_empresa', 'dato' => $parametros['txt_nombre_empresa']),
            array('campo' => 'th_expl_cargos_ocupados', 'dato' => $parametros['txt_cargos_ocupados']),
            array('campo' => 'th_expl_fecha_inicio_experiencia', 'dato' => $parametros['txt_fecha_inicio_laboral']),
            array('campo' => 'th_expl_fecha_fin_experiencia', 'dato' => $parametros['txt_fecha_final_laboral']),
            array('campo' => 'th_expl_cbx_fecha_fin_experiencia', 'dato' => $parametros['cbx_fecha_final_laboral']),
            array('campo' => 'th_expl_responsabilidades_logros', 'dato' => $parametros['txt_responsabilidades_logros']),
            array('campo' => 'th_pos_id', 'dato' => $parametros['txt_id_postulante']),
            
        );

        if ($parametros['txt_id'] == '') {
            $datos = $this->modelo->insertar($datos);
        } else {
            $where[0]['campo'] = 'th_posa_id';
            $where[0]['dato'] = $parametros['_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_expl_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_expl_id';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->eliminar($datos, $where);
        return $datos;
    }
}
