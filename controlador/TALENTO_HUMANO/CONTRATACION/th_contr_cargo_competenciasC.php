<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_competenciasM.php');

$controlador = new th_contr_cargo_competenciasC();


if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar_editar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['buscar'])) {
    $query = $_GET['q'] ?? '';
    echo json_encode($controlador->buscar(['query' => $query]));
}


class th_contr_cargo_competenciasC
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new th_contr_cargo_competenciasM();
    }

   
    function listar($th_car_id = '')
    {
        return $this->modelo->where('th_car_id',$th_car_id)->where('th_carcomp_estado',1)->listar();
    }

    function insertar_editar($param)
    {
        $toInt = fn($v) => ($v === '' || $v === null) ? null : (int)$v;

        $datos = [
            ['campo' => 'th_car_id', 'dato' => $toInt($param['th_car_id'] ?? null)],
            ['campo' => 'th_comp_id', 'dato' => $toInt($param['th_comp_id'] ?? null)],

            ['campo' => 'th_carcomp_nivel_requerido', 'dato' => $param['th_carcomp_nivel_requerido'] ?? null],

            ['campo' => 'th_carcomp_disc_valor_d', 'dato' => $param['disc_d'] ?? null],
            ['campo' => 'th_carcomp_disc_valor_i', 'dato' => $param['disc_i'] ?? null],
            ['campo' => 'th_carcomp_disc_valor_s', 'dato' => $param['disc_s'] ?? null],
            ['campo' => 'th_carcomp_disc_valor_c', 'dato' => $param['disc_c'] ?? null],

            ['campo' => 'th_carcomp_disc_grafica_json', 'dato' => $param['grafica_json'] ?? null],

            ['campo' => 'th_carcomp_nivel_utilizacion', 'dato' => $param['nivel_utilizacion'] ?? null],
            ['campo' => 'th_carcomp_nivel_contribucion', 'dato' => $param['nivel_contribucion'] ?? null],
            ['campo' => 'th_carcomp_nivel_habilidad', 'dato' => $param['nivel_habilidad'] ?? null],
            ['campo' => 'th_carcomp_nivel_maestria', 'dato' => $param['nivel_maestria'] ?? null],

            ['campo' => 'th_carcomp_es_critica', 'dato' => isset($param['es_critica']) ? 1 : 0],
            ['campo' => 'th_carcomp_es_evaluable', 'dato' => isset($param['es_evaluable']) ? 1 : 0],

            ['campo' => 'th_carcomp_metodo_evaluacion', 'dato' => $param['metodo'] ?? null],
            ['campo' => 'th_carcomp_ponderacion', 'dato' => $param['ponderacion'] ?? null],
            ['campo' => 'th_carcomp_observaciones', 'dato' => $param['observaciones'] ?? null],

            ['campo' => 'th_carcomp_estado', 'dato' => isset($param['estado']) ? 1 : 0],
            ['campo' => 'th_carcomp_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')],
        ];

        // INSERTAR
        if (empty($param['_id'])) {

            // agregar fecha creación
            $datos[] = ['campo' => 'th_carcomp_fecha_creacion', 'dato' => date('Y-m-d H:i:s')];

            $this->modelo->insertar_id($datos);

            return 1;
        }

        // EDITAR
        $where[] = ['campo' => 'th_carcomp_id', 'dato' => $param['_id']];

        return $this->modelo->editar($datos, $where);
    }

    function eliminar($id)
    {
        $datos = [
            ['campo' => 'th_carcomp_estado', 'dato' => 0],
        ];

        $where[] = ['campo' => 'th_carcomp_id', 'dato' => $id];

        return $this->modelo->editar($datos, $where);
    }

    function buscar($param)
    {
        $lista = [];
        $concat = "th_comp_nombre, th_carcomp_estado";

        $datos = $this->modelo
            ->where('th_carcomp_estado', 1)
            ->like($concat, $param['query']);

        foreach ($datos as $v) {
            $lista[] = [
                'id' => $v['th_carcomp_id'],
                'text' => $v['th_comp_nombre']
            ];
        }

        return $lista;
    }
}