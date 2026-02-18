<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/TALENTO_HUMANO/CONTRATACION/cn_plazaM.php');

$controlador = new cn_plazaC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['listar_plaza'])) {
    echo json_encode($controlador->listar_plaza($_POST['id'] ?? ''));
}

if (isset($_GET['insertar_editar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['_id']));
}

if (isset($_GET['buscar'])) {
    $query = isset($_GET['q']) ? $_GET['q'] : '';
    echo json_encode($controlador->buscar(['query' => $query]));
}

class cn_plazaC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new cn_plazaM();
    }

    function listar($id = '')
    {
        if ($id == '') {
            return $this->modelo->where('cn_pla_estado', 1)->listar();
        }
        return $this->modelo->listar_plaza_por_id($id);
    }

    function listar_plaza($id = '')
    {
        return $this->modelo->where('cn_pla_id', $id)->where('cn_pla_estado', 1)->listar();
    }

    function insertar_editar($parametros)
    {
        $toDateTime = function ($val) {
            if ($val === null || $val === '') return null;
            $val = str_replace('T', ' ', $val);
            if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $val)) $val .= ':00';
            $ts = strtotime($val);
            return $ts ? date('Y-m-d H:i:s', $ts) : null;
        };
        $toInt   = function ($v) { return ($v === '' || $v === null) ? null : (int)$v; };
        $toFloat = function ($v) { return ($v === '' || $v === null) ? null : (float)$v; };
        $toBool  = function ($v) { return ($v === 1 || $v === '1' || $v === true || $v === 'true') ? 1 : 0; };

        $datos = [
            ['campo' => 'cn_pla_titulo',              'dato' => $parametros['txt_cn_pla_titulo'] ?? ''],
            ['campo' => 'cn_pla_descripcion',         'dato' => $parametros['txt_cn_pla_descripcion'] ?? ''],
            ['campo' => 'id_cargo',                   'dato' => $toInt($parametros['ddl_cargo'] ?? null)],
            ['campo' => 'th_dep_id',                  'dato' => $toInt($parametros['ddl_th_dep_id'] ?? null)],
            ['campo' => 'id_tipo_seleccion',          'dato' => $toInt($parametros['ddl_id_tipo_seleccion'] ?? null)],
            ['campo' => 'cn_pla_num_vacantes',        'dato' => $toInt($parametros['txt_cn_pla_num_vacantes'] ?? null)],
            ['campo' => 'id_nomina',                  'dato' => $toInt($parametros['ddl_id_nomina'] ?? null)],
            ['campo' => 'cn_pla_fecha_publicacion',   'dato' => $toDateTime($parametros['txt_cn_pla_fecha_publicacion'] ?? null)],
            ['campo' => 'cn_pla_fecha_cierre',        'dato' => $toDateTime($parametros['txt_cn_pla_fecha_cierre'] ?? null)],
            ['campo' => 'cn_pla_salario_min',         'dato' => $toFloat($parametros['txt_cn_pla_salario_min'] ?? null)],
            ['campo' => 'cn_pla_salario_max',         'dato' => $toFloat($parametros['txt_cn_pla_salario_max'] ?? null)],
            ['campo' => 'th_per_id_responsable',      'dato' => $toInt($parametros['ddl_cn_pla_responsable'] ?? null)],
            ['campo' => 'cn_pla_req_disponibilidad',  'dato' => $toBool($parametros['cbx_cn_pla_req_disponibilidad'] ?? 0)],
            ['campo' => 'cn_pla_req_prioridad_interna','dato' => $toBool($parametros['cbx_cn_pla_prioridad_interna'] ?? 0)],
            ['campo' => 'cn_pla_req_documentos',      'dato' => $toBool($parametros['cbx_cn_pla_req_documentos'] ?? 0)],
            ['campo' => 'cn_pla_observaciones',       'dato' => $parametros['txt_cn_pla_observaciones'] ?? null],
            ['campo' => 'cn_pla_estado',              'dato' => 1],
            ['campo' => 'cn_pla_fecha_modificacion',  'dato' => date('Y-m-d H:i:s')],
        ];

        if (empty($parametros['_id'])) {
            $datos[] = ['campo' => 'cn_pla_fecha_creacion', 'dato' => date('Y-m-d H:i:s')];
            $id = $this->modelo->insertar_id($datos);
            return $id ? $id : 0;
        } else {
            $where = [['campo' => 'cn_pla_id', 'dato' => $parametros['_id']]];
            $this->modelo->editar($datos, $where);
            return $parametros['_id'];
        }
    }

    function eliminar($id)
    {
        $datos = [['campo' => 'cn_pla_estado', 'dato' => 0]];
        $where = [['campo' => 'cn_pla_id',     'dato' => $id]];
        return $this->modelo->editar($datos, $where);
    }

    function buscar($parametros)
    {
        $lista = [];
        $query = trim($parametros['query'] ?? '');
        $datos = $this->modelo->where('cn_pla_estado', 1)->listar();

        foreach ($datos as $plaza) {
            $titulo = $plaza['cn_pla_titulo'] ?? '';
            if ($query === '' || stripos($titulo, $query) !== false) {
                $lista[] = [
                    'id'   => $plaza['cn_pla_id'],
                    'text' => $titulo,
                ];
            }
        }
        return $lista;
    }
}