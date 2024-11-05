<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_horariosM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_turnos_horarioM.php');

$controlador = new th_horariosC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['buscar'])) {
    $query = '';

    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }

    $parametros = array(
        'query' => $query,
    );

    echo json_encode($controlador->buscar($parametros));
}

class th_horariosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_horariosM();
    }

    function listar($id = '')
    {
        if ($id == '') {
            $datos = $this->modelo->where('th_hor_estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('th_hor_id', $id)->listar();
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'th_hor_nombre', 'dato' => $parametros['txt_nombre']),
            array('campo' => 'th_hor_tipo', 'dato' => $parametros['txt_tipo']),
            array('campo' => 'th_hor_ciclos', 'dato' => $parametros['txt_ciclos']),
            array('campo' => 'th_hor_inicio', 'dato' => $parametros['txt_inicio']),

            array('campo' => 'th_hor_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );

        if ($parametros['_id'] == '') {
            if (count($this->modelo->where('th_hor_nombre', $parametros['txt_nombre'])->listar()) == 0) {
                $datos = $this->modelo->insertar_id($datos);

                if (!empty($parametros['arr_eventos_horario'])) {
                    $this->insertar_turnos_horarios($parametros['arr_eventos_horario'], $datos);
                }

                $datos = 1;
            } else {
                return -2;
            }
        } else {

            if (count($this->modelo->where('th_hor_nombre', $parametros['txt_nombre'])->where('th_hor_id !', $parametros['_id'])->listar()) == 0) {
                $where[0]['campo'] = 'th_hor_id';
                $where[0]['dato'] = $parametros['_id'];
                $datos = $this->modelo->editar($datos, $where);

                if (!empty($parametros['arr_eventos_horario'])) {
                    $this->insertar_turnos_horarios($parametros['arr_eventos_horario'], $parametros['_id']);
                }

            } else {
                return -2;
            }
        }

        return $datos;
    }

    private function insertar_turnos_horarios($arr_eventos_horario, $id_horario){
        $turnos_horarioM = new th_turnos_horarioM();
        foreach ($arr_eventos_horario as $dato) {
            $contador_turnos_horarios = count($turnos_horarioM->where('th_hor_id', $id_horario)->where('th_tur_id', $dato['id_turno'])->where('th_tuh_dia', $dato['dia'])->listar());
            if ($contador_turnos_horarios == 0) {
                $datos_eventos = array(
                    array('campo' => 'th_hor_id', 'dato' => $id_horario),
                    array('campo' => 'th_tur_id', 'dato' => $dato['id_turno']),
                    array('campo' => 'th_tuh_dia', 'dato' => $dato['dia']),
                    array('campo' => 'th_tuh_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
                );
                $datos_eventos = $turnos_horarioM->insertar($datos_eventos);
            }
            $turnos_horarioM->reset();
        }
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_hor_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_hor_id';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }

    //Para usar en select2
    function buscar($parametros)
    {
        $lista = array();
        $concat = "th_hor_nombre, th_hor_estado";
        $datos = $this->modelo->where('th_hor_estado', 1)->like($concat, $parametros['query']);

        foreach ($datos as $key => $value) {
            $text = $value['th_hor_nombre'];
            $lista[] = array('id' => ($value['th_hor_id']), 'text' => ($text), /* 'data' => $value */);
        }

        return $lista;
    }
}
