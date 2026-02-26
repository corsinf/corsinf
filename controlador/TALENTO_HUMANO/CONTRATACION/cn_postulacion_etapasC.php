<?php

date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/TALENTO_HUMANO/CONTRATACION/cn_postulacion_etapasM.php');

$controlador = new cn_postulacion_etapasC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar_editar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['_id']));
}

class cn_postulacion_etapasC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new cn_postulacion_etapasM();
    }

    function listar($id = '')
    {
        if ($id == '') {
            return $this->modelo->where('cn_pose_estado', 1)->listar();
        }
        return $this->modelo->where('cn_pose_id', intval($id))->listar();
    }

    function insertar_editar($parametros)
    {
        $toInt   = function ($v) {
            return ($v === '' || $v === null) ? null : (int)$v;
        };
        $toFloat = function ($v) {
            return ($v === '' || $v === null) ? null : (float)$v;
        };
        $toDate  = function ($v) {
            if ($v === '' || $v === null) return null;
            $v = str_replace('T', ' ', $v);
            if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $v)) $v .= ':00';
            $ts = strtotime($v);
            return $ts ? date('Y-m-d H:i:s', $ts) : null;
        };

        $datos = [
            ['campo' => 'cn_post_id',               'dato' => $toInt($parametros['cn_post_id']             ?? null)],
            ['campo' => 'cn_plaet_id',              'dato' => $toInt($parametros['cn_plaet_id']            ?? null)],
            ['campo' => 'cn_pose_estado_proceso',   'dato' => $toInt($parametros['cn_pose_estado_proceso'] ?? null)],
            ['campo' => 'cn_pose_puntuacion',       'dato' => $toFloat($parametros['cn_pose_puntuacion']   ?? null)],
            ['campo' => 'cn_pose_observacion',      'dato' => $parametros['cn_pose_observacion']           ?? null],
            ['campo' => 'usuario_evaluador',        'dato' => $parametros['usuario_evaluador']             ?? null],
            ['campo' => 'cn_pose_estado',           'dato' => 1],
            ['campo' => 'cn_pose_fecha_inicio',     'dato' => $toDate($parametros['cn_pose_fecha_inicio']  ?? null)],
            ['campo' => 'cn_pose_fecha_evaluacion', 'dato' => $toDate($parametros['cn_pose_fecha_evaluacion'] ?? null)],
            ['campo' => 'cn_pose_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')],
        ];

        if (empty($parametros['_id'])) {
            $datos[] = ['campo' => 'cn_pose_fecha_creacion', 'dato' => date('Y-m-d H:i:s')];
            $id = $this->modelo->insertar_id($datos);
            return $id ? $id : 0;
        } else {
            $where = [['campo' => 'cn_pose_id', 'dato' => intval($parametros['_id'])]];
            $this->modelo->editar($datos, $where);
            return $parametros['_id'];
        }
    }

    function eliminar($id)
    {
        $datos = [
            ['campo' => 'cn_pose_estado',              'dato' => 0],
            ['campo' => 'cn_pose_fecha_modificacion',  'dato' => date('Y-m-d H:i:s')],
        ];
        $where = [['campo' => 'cn_pose_id', 'dato' => intval($id)]];
        return $this->modelo->editar($datos, $where);
    }
}
