<?php

date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/TALENTO_HUMANO/CONTRATACION/cn_postulacionM.php');

$controlador = new cn_postulacionC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar_editar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['_id']));
}

if (isset($_GET['crear_postulacion'])) {
    echo json_encode($controlador->crear_postulacion(
        $_POST['cn_pla_id'] ?? 0,
        $_POST['th_pos_id'] ?? 0
    ));
}

if (isset($_GET['crear_postulacion_bulk'])) {
    $ids = $_POST['th_pos_ids'] ?? [];
    if (is_string($ids)) $ids = json_decode($ids, true);
    echo json_encode($controlador->crear_postulacion_bulk($_POST['cn_pla_id'] ?? 0, $ids));
}

if (isset($_GET['listar_por_plaza'])) {
    echo json_encode($controlador->listar_por_plaza($_POST['cn_pla_id'] ?? 0));
}


if (isset($_GET['listar_por_etapa'])) {
    echo json_encode($controlador->listar_por_etapa($_POST['cn_plaet_id'] ?? 0));
}

class cn_postulacionC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new cn_postulacionM();
    }

    function listar($id = '')
    {
        if ($id == '') {
            return $this->modelo->where('cn_post_estado', 1)->listar();
        }
        return $this->modelo->where('cn_post_id', intval($id))->listar();
    }
    function listar_por_plaza($cn_pla_id)
    {
        if (empty($cn_pla_id)) return [];
        return $this->modelo->listar_postulantes_por_plaza($cn_pla_id);
    }
    function listar_por_etapa($cn_plaet_id)
    {
        if (empty($cn_plaet_id)) return [];
        return $this->modelo->listar_postulantes_por_etapa($cn_plaet_id);
    }

    function crear_postulacion($cn_pla_id, $th_pos_id)
    {
        if (empty($cn_pla_id) || empty($th_pos_id)) {
            return ['error' => 'Parámetros inválidos'];
        }
        return $this->modelo->ejecutar_crear_postulacion($cn_pla_id, $th_pos_id);
    }

    function crear_postulacion_bulk($cn_pla_id, $th_pos_ids)
    {
        if (empty($cn_pla_id) || empty($th_pos_ids)) return ['error' => 'Parámetros inválidos'];
        return $this->modelo->ejecutar_crear_postulacion_bulk($cn_pla_id, $th_pos_ids);
    }


    function insertar_editar($parametros)
    {
        $toInt = function ($v) {
            return ($v === '' || $v === null) ? null : (int)$v;
        };

        $datos = [
            ['campo' => 'cn_pla_id',                'dato' => $toInt($parametros['cn_pla_id'] ?? null)],
            ['campo' => 'th_pos_id',                'dato' => $toInt($parametros['th_pos_id'] ?? null)],
            ['campo' => 'cn_plaet_id_actual',       'dato' => $toInt($parametros['cn_plaet_id_actual'] ?? null)],
            ['campo' => 'cn_post_estado_proceso',   'dato' => $toInt($parametros['cn_post_estado_proceso'] ?? null)],
            ['campo' => 'cn_post_estado',           'dato' => 1],
            ['campo' => 'cn_post_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')],
        ];

        if (empty($parametros['_id'])) {
            $datos[] = ['campo' => 'cn_post_fecha_creacion', 'dato' => date('Y-m-d H:i:s')];
            $id = $this->modelo->insertar_id($datos);
            return $id ? $id : 0;
        } else {
            $where = [['campo' => 'cn_post_id', 'dato' => intval($parametros['_id'])]];
            $this->modelo->editar($datos, $where);
            return $parametros['_id'];
        }
    }

    function eliminar($id)
    {
        $datos = [
            ['campo' => 'cn_post_estado',          'dato' => 0],
            ['campo' => 'cn_post_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')]
        ];
        $where = [['campo' => 'cn_post_id', 'dato' => intval($id)]];
        return $this->modelo->editar($datos, $where);
    }
}
