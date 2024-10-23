<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_turnosM.php');

$controlador = new th_turnosC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}


class th_turnosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_turnosM();
    }

    function listar($id = '')
    {
        if ($id == '') {
            $datos = $this->modelo->where('th_tur_estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('th_tur_id', $id)->listar();
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $txt_checkin_registro_inicio = $this->time_formato_minutos($parametros['txt_checkin_registro_inicio']);
        $txt_hora_entrada = $this->time_formato_minutos($parametros['txt_hora_entrada']);
        $txt_checkin_registro_fin = $this->time_formato_minutos($parametros['txt_checkin_registro_fin']);

        $txt_checkout_salida_inicio = $this->time_formato_minutos($parametros['txt_checkout_salida_inicio']);
        $txt_hora_salida = $this->time_formato_minutos($parametros['txt_hora_salida']);
        $txt_checkout_salida_fin = $this->time_formato_minutos($parametros['txt_checkout_salida_fin']);

        $datos = array(
            array('campo' => 'th_tur_nombre', 'dato' => $parametros['txt_nombre']),
            array('campo' => 'th_tur_checkin_registro_inicio', 'dato' => $txt_checkin_registro_inicio),
            array('campo' => 'th_tur_hora_entrada', 'dato' => $txt_hora_entrada),
            array('campo' => 'th_tur_checkin_registro_fin', 'dato' => $txt_checkin_registro_fin),
            array('campo' => 'th_tur_limite_tardanza_in', 'dato' => $parametros['txt_limite_tardanza_in']),
            array('campo' => 'th_tur_checkout_salida_inicio', 'dato' => $txt_checkout_salida_inicio),
            array('campo' => 'th_tur_hora_salida', 'dato' => $txt_hora_salida),
            array('campo' => 'th_tur_checkout_salida_fin', 'dato' => $txt_checkout_salida_fin),
            array('campo' => 'th_tur_limite_tardanza_out', 'dato' => $parametros['txt_limite_tardanza_out']),
            array('campo' => 'th_tur_turno_nocturno', 'dato' => $parametros['cbx_turno_nocturno']),

            array('campo' => 'th_tur_valor_hora_trabajar', 'dato' => $parametros['txt_valor_trabajar_hora']),
            array('campo' => 'th_tur_valor_min_trabajar', 'dato' => $parametros['txt_valor_trabajar_min']),
            //array('campo' => 'th_tur_valor_trabajar', 'dato' => $parametros['txt_valor_trabajar']),
            array('campo' => 'th_tur_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
            array('campo' => 'th_tur_color', 'dato' => $parametros['txt_color']),
        );

        if ($parametros['_id'] == '') {
            if (count($this->modelo->where('th_tur_nombre', $parametros['txt_nombre'])->listar()) == 0) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            if (count($this->modelo->where('th_tur_nombre', $parametros['txt_nombre'])->where('th_tur_id !', $parametros['_id'])->listar()) == 0) {
                $where[0]['campo'] = 'th_tur_id';
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
            array('campo' => 'th_tur_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_tur_id';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }

    function time_formato_minutos($time)
    {
        list($horas, $minutos) = explode(':', $time);
        return ($horas * 60) + $minutos;
    }
}
