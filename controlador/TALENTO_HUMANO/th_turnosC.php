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

        $txt_checkin_registro_inicio = $this->hora_a_minutos($parametros['txt_checkin_registro_inicio']);
        $txt_hora_entrada = $this->hora_a_minutos($parametros['txt_hora_entrada']);
        $txt_checkin_registro_fin = $this->hora_a_minutos($parametros['txt_checkin_registro_fin']);

        $txt_checkout_salida_inicio = $this->hora_a_minutos($parametros['txt_checkout_salida_inicio']);
        $txt_hora_salida = $this->hora_a_minutos($parametros['txt_hora_salida']);
        $txt_checkout_salida_fin = $this->hora_a_minutos($parametros['txt_checkout_salida_fin']);

        $rbx_descanso = $parametros['rbx_descanso'];
        $rbx_aplicar_descanso =  $parametros['rbx_aplicar_descanso'];

        // Inicialización segura
        $txt_tiempo_descanso = 0;
        $txt_tiempo_descanso_rango = 0;
        $txt_hora_descanso_inicio = 0;
        $txt_hora_descanso_final = 0;
        $txt_limite_tardanza_descanso_in = 0;
        $txt_limite_tardanza_descanso_out = 0;

        // Si se activa descanso general
        if ($rbx_descanso == 1 || $rbx_aplicar_descanso == 1) {
            // Duración fija del descanso
            $txt_tiempo_descanso = $parametros['txt_tiempo_descanso'];
            // Si además se activa "usar rango"
            if ($rbx_aplicar_descanso == 1) {
                $txt_tiempo_descanso_rango = $parametros['txt_tiempo_descanso_rango'];
                $txt_hora_descanso_inicio = $this->hora_a_minutos($parametros['txt_hora_descanso_inicio']);
                $txt_hora_descanso_final = $this->hora_a_minutos($parametros['txt_hora_descanso_final']);
                $txt_limite_tardanza_descanso_in = $parametros['txt_limite_tardanza_descanso_in'];
                $txt_limite_tardanza_descanso_out = $parametros['txt_limite_tardanza_descanso_out'];
            }
        }

        $cbx_hora_suple_extra = $parametros['cbx_hora_suple_extra'];

        if ($cbx_hora_suple_extra == 1) {
            $txt_hora_extra_inicio = $this->hora_a_minutos($parametros['txt_hora_extra_inicio']);
            $txt_hora_extra_final = $this->hora_a_minutos($parametros['txt_hora_extra_final']);
            $txt_hora_suple_inicio = $this->hora_a_minutos($parametros['txt_hora_suple_inicio']);
            $txt_hora_suple_final = $this->hora_a_minutos($parametros['txt_hora_suple_final']);
        } else {
            $txt_hora_extra_inicio = 0;
            $txt_hora_extra_final = 0;
            $txt_hora_suple_inicio = 0;
            $txt_hora_suple_final = 0;
        }


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
            array('campo' => 'th_tur_descanso', 'dato' => $parametros['rbx_descanso']),
            array('campo' => 'th_tur_hora_descanso', 'dato' => $rbx_aplicar_descanso == 1 ? $txt_tiempo_descanso_rango : $txt_tiempo_descanso),
            array('campo' => 'th_tur_descanso_inicio', 'dato' => $txt_hora_descanso_inicio),
            array('campo' => 'th_tur_descanso_fin', 'dato' => $txt_hora_descanso_final),
            array('campo' => 'th_tur_tol_ini_descanso', 'dato' => $txt_limite_tardanza_descanso_in),
            array('campo' => 'th_tur_tol_fin_descanso', 'dato' => $txt_limite_tardanza_descanso_out),
            array('campo' => 'th_tur_usar_descanso', 'dato' => $parametros['rbx_aplicar_descanso']),
            //para las horas extra
            array('campo' => 'th_tur_calcular_horas_extra', 'dato' => $parametros['cbx_hora_suple_extra']),
            array('campo' => 'th_tur_supl_ini', 'dato' => $txt_hora_suple_inicio),
            array('campo' => 'th_tur_supl_fin', 'dato' => $txt_hora_suple_final),
            array('campo' => 'th_tur_extra_ini', 'dato' => $txt_hora_extra_inicio),
            array('campo' => 'th_tur_extra_fin', 'dato' => $txt_hora_extra_final),
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

    //Transforma
    function hora_a_minutos($time)
    {
        list($horas, $minutos) = explode(':', $time);
        return ($horas * 60) + $minutos;
    }
}
