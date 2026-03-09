<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class cn_plazaM extends BaseModel
{
    protected $tabla = 'cn_plaza';
    protected $primaryKey = 'cn_pla_id AS _id';
    protected $camposPermitidos = [
        'cn_pla_titulo',
        'id_cargo',
        'cn_pla_descripcion',
        'id_tipo_seleccion',
        'cn_pla_num_vacantes',
        'cn_pla_fecha_publicacion',
        'cn_pla_fecha_cierre',
        'th_dep_id',
        'cn_pla_req_disponibilidad',
        'cn_pla_salario_min',
        'cn_pla_salario_max',
        'id_nomina',
        'cn_pla_req_prioridad_interna',
        'cn_pla_req_documentos',
        'th_per_id_responsable',
        'cn_pla_observaciones',
        'cn_pla_estado',
        'cn_pla_fecha_creacion',
        'cn_pla_fecha_modificacion',
        'id_plaza_estados',
        'id_seccion',
    ];

    public function listar_plaza_por_id($id = '', $estado = 1, $estados_plaza = '')
    {
        $id = intval($id);
        $sql =
            "SELECT
                p.cn_pla_id AS _id,
                p.cn_pla_titulo,
                p.id_cargo,
                p.cn_pla_descripcion,
                p.id_tipo_seleccion,
                p.cn_pla_num_vacantes,
                p.cn_pla_fecha_publicacion,
                p.cn_pla_fecha_cierre,
                p.th_dep_id,
                p.cn_pla_req_disponibilidad,
                p.cn_pla_salario_min,
                p.cn_pla_salario_max,
                p.id_nomina,
                p.cn_pla_req_prioridad_interna,
                p.cn_pla_req_documentos,
                p.th_per_id_responsable,
                p.cn_pla_observaciones,
                p.cn_pla_estado,
                p.cn_pla_fecha_creacion,
                p.cn_pla_fecha_modificacion,
                p.id_plaza_estados,
                p.id_seccion,
                pla_est.codigo AS codigo_plaza_estado,
                pla_est.descripcion AS descripcion_plaza_estado,
                pla_est.orden AS orden_plaza_estado,
                pla_est.editable AS editable_plaza,
                c.nombre AS descripcion_cargo,
                d.th_dep_nombre AS descripcion_departamento,
                ts.descripcion AS descripcion_tipo_seleccion,
                s.descripcion AS descripcion_seccion,
                n.nombre AS descripcion_nomina,
                per.th_per_cedula AS per_cedula,
                per.th_per_nombres_completos AS per_nombre_completo
            FROM cn_plaza p
            LEFT JOIN th_cat_cargo c ON p.id_cargo = c.id_cargo
            LEFT JOIN th_departamentos d ON p.th_dep_id = d.th_dep_id
            LEFT JOIN cn_cat_tipo_seleccion ts ON p.id_tipo_seleccion = ts.id_tipo_seleccion
            LEFT JOIN th_cat_nomina n ON p.id_nomina = n.id_nomina
            LEFT JOIN th_cat_seccion s ON p.id_seccion = s.id_seccion
            LEFT JOIN th_personas per ON p.th_per_id_responsable = per.th_per_id
            LEFT JOIN cn_cat_plaza_estados pla_est ON p.id_plaza_estados = pla_est.id_plaza_estados
            WHERE p.cn_pla_estado = $estado";

        if ($id !== 0) {
            $sql .= " AND p.cn_pla_id = $id ";
        }

        if ($estados_plaza !== '') {
            $sql .= " AND p.id_plaza_estados = $estados_plaza";
        }

        $sql .= " ORDER BY p.cn_pla_fecha_creacion DESC;";

        return $this->db->datos($sql);
    }

    public function listar_plaza_id_cargo($id_plaza)
    {
        $id_plaza = intval($id_plaza);
        $sql = "SELECT
                p.cn_pla_id AS _id,
                p.id_cargo
            FROM cn_plaza p
            WHERE p.cn_pla_id = $id_plaza;";

        $id_cargo = $this->db->datos($sql);
        if (empty($id_cargo)) {
            return -1;
        }

        $id_cargo = $id_cargo[0]['id_cargo'];

        return $id_cargo;
    }


    public function listar_plaza_detalle_completo(int $plaza_id)
    {
        if ($plaza_id <= 0) {
            return -2;
        }

        $sql = "EXEC _contratacion.SP_CN_PLAZA_DETALLE_JSON @cn_pla_id = ?;";
        $parametros = [$plaza_id];

        $resultado = $this->db->ejecutar_procedimiento_con_retorno_1(
            $sql,
            $parametros
        );

        if (empty($resultado)) {
            return -1;
        }

        // El SP devuelve json_result como string
        if (isset($resultado[0]['json_result'])) {

            $json = $resultado[0]['json_result'];

            $data = json_decode($json, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return -3;
            }

            return $data;
        }

        return $resultado;
    }

    private function listar_cargo_detalle_completo(int $cargo_id)
    {
        if ($cargo_id <= 0) {
            throw new InvalidArgumentException('ID de cargo inválido');
        }

        $sql = "EXEC _contratacion.SP_CN_CARGO_DETALLE_JSON @id_cargo = ?;";
        $parametros = [$cargo_id];

        $resultado = $this->db->ejecutar_procedimiento_con_retorno_1(
            $sql,
            $parametros
        );

        if (empty($resultado)) {
            return -1;
        }

        // El SP devuelve json_result como string
        if (isset($resultado[0]['json_result'])) {

            $json = $resultado[0]['json_result'];

            $data = json_decode($json, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new RuntimeException('Error al decodificar JSON: ' . json_last_error_msg());
            }

            return $data;
        }

        return $resultado;
    }

    function listar_plaza_cargo_detalle_completo(int $plaza_id)
    {
        // $plaza_id = 1;


        $id_cargo = $this->listar_plaza_id_cargo($plaza_id);

        if ($id_cargo <= 0) {
            return -1;
        }

        $plaza_json = $this->listar_plaza_detalle_completo($plaza_id);
        $cargo_json = $this->listar_cargo_detalle_completo($id_cargo);

        return [
            'plaza' => $plaza_json,
            'cargo' => $cargo_json
        ];
    }
}
