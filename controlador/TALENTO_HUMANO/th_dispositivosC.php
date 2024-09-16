<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_dispositivosM.php');

$controlador = new th_dispositivosC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}


class th_dispositivosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_dispositivosM();
    }

    function listar($id = '')
    {
        if ($id == '') {
            $datos = $this->modelo->where('th_dis_estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('th_dis_id', $id)->listar();
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'th_dis_nombre', 'dato' => $parametros['txt_nombre']),
            array('campo' => 'th_dis_host', 'dato' => $parametros['txt_host']),
            array('campo' => 'th_dis_port', 'dato' => $parametros['txt_puerto']),
            array('campo' => 'th_dis_ssl', 'dato' => $parametros['cbx_ssl']),
            array('campo' => 'th_dis_usuario', 'dato' => $parametros['txt_usuario']),
            array('campo' => 'th_dis_pass', 'dato' => $parametros['txt_pass']),
            array('campo' => 'th_dis_modelo', 'dato' => $parametros['ddl_modelo']),

            array('campo' => 'th_dis_serial', 'dato' => $parametros['txt_serial']),
            array('campo' => 'th_dis_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),

            /*array('campo' => 'th_dis_beep', 'dato' => $parametros['th_dis_beep']),
            array('campo' => 'th_dis_gateway_mode', 'dato' => $parametros['th_dis_gateway_mode']),
            array('campo' => 'th_dis_leds', 'dato' => $parametros['th_dis_leds']),
            array('campo' => 'th_dis_anti_pass_back', 'dato' => $parametros['th_dis_anti_pass_back']),
            array('campo' => 'th_dis_diario_reset', 'dato' => $parametros['th_dis_diario_reset']),
            array('campo' => 'th_dis_vehiculo_control', 'dato' => $parametros['th_dis_vehiculo_contro']),
            array('campo' => 'th_dis_alarma_relay', 'dato' => $parametros['th_dis_alarma_relay']),
            array('campo' => 'th_dis_urn', 'dato' => $parametros['th_dis_urn']),
            array('campo' => 'th_dis_version', 'dato' => $parametros['th_dis_version']),
            array('campo' => 'th_dis_camara', 'dato' => $parametros['th_dis_camara']),
            array('campo' => 'th_dis_ultima_fecha', 'dato' => $parametros['th_dis_ultima_fecha']),
            array('campo' => 'th_dis_estado_dis', 'dato' => $parametros['th_dis_estado_dis']),
            array('campo' => 'th_dis_contador_reset', 'dato' => $parametros['th_dis_contador_reset']),
            array('campo' => 'th_dis_lenguaje', 'dato' => $parametros['th_dis_lenguaje']),
            array('campo' => 'th_dis_ultimo_nsr', 'dato' => $parametros['th_dis_ultimo_nsr']),
            array('campo' => 'th_dis_modo_visitante', 'dato' => $parametros['th_dis_modo_visitante']),
            array('campo' => 'th_dis_id_modo_indet', 'dato' => $parametros['th_dis_id_modo_indet']),*/
        );

        if ($parametros['_id'] == '') {
            if (count($this->modelo->where('th_dis_nombre', $parametros['txt_nombre'])->listar()) == 0) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            if (count($this->modelo->where('th_dis_nombre', $parametros['txt_nombre'])->where('th_dis_id !', $parametros['_id'])->listar()) == 0) {
                $where[0]['campo'] = 'th_dis_id';
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
            array('campo' => 'th_dis_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_dis_id';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }
}
