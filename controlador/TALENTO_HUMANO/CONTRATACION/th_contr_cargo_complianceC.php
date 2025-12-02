<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_complianceM.php');

$controlador = new th_contr_cargo_complianceC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}
if (isset($_GET['listar_compliance_cargo'])) {
    echo json_encode($controlador->listar_cargo_compliance($_POST['id'] ?? ''));
}

if (isset($_GET['insertar_editar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros'] ?? []));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id'] ?? ''));
}

if (isset($_GET['buscar'])) {
    $query = $_GET['q'] ?? '';
    echo json_encode($controlador->buscar(['query' => $query]));
}

class th_contr_cargo_complianceC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_contr_cargo_complianceM();
    }

 
    function listar($id = '')
    {
        if ($id === '' || $id === null) {
            $datos = $this->modelo->listar();
        } else {
            $datos = $this->modelo->where('th_comp_id', $id)->listar();
        }
        return $datos;
    }

    function listar_cargo_compliance($id = '')
    {
        if ($id === '') {
            $datos = $this->modelo->listar();
        } else {
            $datos = $this->modelo->where('th_car_id', $id)->where('th_comp_estado',1)->listar();
        }
        return $datos;
    }


    function insertar_editar($parametros)
    {
        $toInt = function($v){ return ($v === '' || $v === null) ? 0 : intval($v); };
        $toFloat = function($v){ return ($v === '' || $v === null) ? 0.0 : floatval($v); };
        $now = date('Y-m-d H:i:s');

        $th_car_id = $toInt($parametros['th_car_id'] ?? $parametros['car_id'] ?? null);
        if (!$th_car_id) return 0; // falta id del cargo

        $totales = $toInt($parametros['th_comp_requisitos_totales'] ?? 0);
        $completados = $toInt($parametros['th_comp_requisitos_completados'] ?? 0);
        $faltantes = $toInt($parametros['th_comp_requisitos_faltantes'] ?? ($totales - $completados));
        if ($faltantes < 0) $faltantes = 0;

        $porcentaje = 0.0;
        if ($totales > 0) {
            $porcentaje = ($completados / $totales) * 100.0;
            // limitar a 2 decimales
            $porcentaje = round($porcentaje, 2);
        }

        $ultima_revision = $parametros['th_comp_ultima_revision'] ?? null;
        $observaciones = $parametros['th_comp_observaciones'] ?? null;

        $datos = [
            ['campo' => 'th_car_id', 'dato' => $th_car_id],
            ['campo' => 'th_comp_porcentaje_completado', 'dato' => $porcentaje],
            ['campo' => 'th_comp_requisitos_totales', 'dato' => $totales],
            ['campo' => 'th_comp_requisitos_completados', 'dato' => $completados],
            ['campo' => 'th_comp_requisitos_faltantes', 'dato' => $faltantes],
            ['campo' => 'th_comp_ultima_revision', 'dato' => $ultima_revision],
            ['campo' => 'th_comp_estado', 'dato' => 1],
            ['campo' => 'th_comp_observaciones', 'dato' => $observaciones],
            ['campo' => 'th_comp_fecha_modificacion', 'dato' => $now]
        ];

        // Inserción
        if (empty($parametros['_id'])) {
            // prevenir duplicado activo por th_car_id (opcional)
            $dup = $this->modelo->where('th_car_id', $th_car_id)->listar();
            if (count($dup) > 0) {
                // si ya existe un registro para ese cargo devolvemos -2 (coherente con otros controladores)
                return -2;
            }

            $datos[] = ['campo' => 'th_comp_fecha_creacion', 'dato' => $now];

            $id = $this->modelo->insertar_id($datos);
            if ($id) return 1;
            return 0;
        } else {
            // Edición
            $where = [];
            $where[0]['campo'] = 'th_comp_id';
            $where[0]['dato'] = $parametros['_id'];

            $res = $this->modelo->editar($datos, $where);
            return ($res) ? 1 : 0;
        }
    }

  
    function eliminar($id)
    {
        if (!$id) return 0;

        $datos = [
            ['campo' => 'th_comp_estado', 'dato' => 'INACTIVO'],
            ['campo' => 'th_comp_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')]
        ];

        $where[0]['campo'] = 'th_comp_id';
        $where[0]['dato'] = $id;

        $res = $this->modelo->editar($datos, $where);
        return ($res) ? 1 : 0;
    }

 
    function buscar($parametros)
    {
        $lista = [];
        $query = isset($parametros['query']) ? trim($parametros['query']) : '';
        $car_id = isset($parametros['car_id']) ? intval($parametros['car_id']) : 0;

        $q = $this->modelo;
        if ($car_id > 0) $q = $q->where('th_car_id', $car_id);
        if ($query !== '') $q = $q->like('th_comp_observaciones, th_comp_estado', $query);

        $datos = $q->listar();

        foreach ($datos as $d) {
            $lista[] = [
                'id' => $d['th_comp_id'],
                'text' => isset($d['th_comp_estado']) ? $d['th_comp_estado'] . ' - ' . ($d['th_comp_porcentaje_completado'] ?? '') . '%' : ('ID ' . $d['th_comp_id']),
                'data' => $d
            ];
        }

        return $lista;
    }
}