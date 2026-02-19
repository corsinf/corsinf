<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_plaza_reqi_aptitudM extends BaseModel
{
    protected $tabla = 'cn_plaza_reqi_aptitud';

    protected $primaryKey = 'cn_reqa_experiencia_id AS _id';

    protected $camposPermitidos = [
        'cn_pla_id',
        'cn_hab_id',
        'cn_reqa_estado AS estado',
        'cn_reqa_fecha_creacion AS fecha_creacion',
        'cn_reqa_fecha_modificacion AS fecha_modificacion'
    ];

    public function listar_plaza_aptitudes($id_aptitud = '', $id_plaza = '')
    {
        $sql = "SELECT 
                ra.cn_reqa_experiencia_id AS _id,
                ra.cn_pla_id,
                ra.cn_hab_id,
                ra.cn_reqa_estado,
                ra.cn_reqa_fecha_creacion,
                ra.cn_reqa_fecha_modificacion,
                h.th_hab_nombre AS habilidad_nombre,
                h.th_tiph_id 
            FROM cn_plaza_reqi_aptitud ra
            LEFT JOIN th_cat_habilidades h 
                ON ra.cn_hab_id = h.th_hab_id
            WHERE ra.cn_reqa_estado = 1 ";

        if (!empty($id_aptitud)) {
            $id_aptitud = intval($id_aptitud);
            $sql .= " AND ra.cn_reqa_experiencia_id = $id_aptitud";
        }

        if (!empty($id_plaza)) {
            $id_plaza = intval($id_plaza);
            $sql .= " AND ra.cn_pla_id = $id_plaza";
        }

        $sql .= " ORDER BY h.th_hab_nombre ASC";

        return $this->db->datos($sql);
    }

    public function listar_habilidades_tenicas_no_asignadas($id_plaza)
    {
        $id = intval($id_plaza);

        $sql = "
        SELECT 
            h.th_hab_id,
            h.th_hab_nombre,
            h.th_hab_estado,
            h.th_tiph_id
        FROM th_cat_habilidades h
        LEFT JOIN cn_plaza_reqi_aptitud ra
            ON ra.cn_hab_id = h.th_hab_id
            AND ra.cn_pla_id = $id
            AND ra.cn_reqa_estado = 1
        WHERE h.th_hab_estado = 1 AND h.th_tiph_id = 1
          AND ra.cn_hab_id IS NULL
        ORDER BY h.th_hab_nombre;
        ";

        return $this->db->datos($sql);
    }

    public function listar_habilidades_blandas_no_asignadas($id_plaza)
    {
        $id = intval($id_plaza);

        $sql = "
        SELECT 
            h.th_hab_id,
            h.th_hab_nombre,
            h.th_hab_estado,
            h.th_tiph_id
        FROM th_cat_habilidades h
        LEFT JOIN cn_plaza_reqi_aptitud ra
            ON ra.cn_hab_id = h.th_hab_id
            AND ra.cn_pla_id = $id
            AND ra.cn_reqa_estado = 1
        WHERE h.th_hab_estado = 1 AND h.th_tiph_id = 2
          AND ra.cn_hab_id IS NULL
        ORDER BY h.th_hab_nombre;
        ";

        return $this->db->datos($sql);
    }
}