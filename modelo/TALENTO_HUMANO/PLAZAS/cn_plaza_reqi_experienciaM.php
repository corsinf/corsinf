<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class cn_plaza_reqi_experienciaM extends BaseModel
{
    protected $tabla = 'cn_plaza_reqi_experiencia';

    protected $primaryKey = 'cn_reqe_experiencia_id AS _id';

    protected $camposPermitidos = [
        'cn_pla_id',
        'id_rango_profesional',
        'cn_reqe_estado AS estado',
        'cn_reqe_fecha_creacion AS fecha_creacion',
        'cn_reqe_fecha_modificacion AS fecha_modificacion'
    ];

    public function listar_plaza_experiencia($id_experiencia = '', $cn_pla_id = '')
    {
        $sql = "
            SELECT 
                re.cn_reqe_experiencia_id AS _id,
                re.cn_pla_id,
                re.id_rango_profesional,
                re.cn_reqe_estado,
                re.cn_reqe_fecha_creacion,
                re.cn_reqe_fecha_modificacion,
                rp.nombre        AS rango_nombre,
                rp.descripcion   AS rango_descripcion,
                rp.min_anios_exp,
                rp.max_anios_exp
            FROM cn_plaza_reqi_experiencia re
            LEFT JOIN th_cat_rango_profesional rp 
                ON re.id_rango_profesional = rp.id_rango_profesional
            WHERE re.cn_reqe_estado = 1
        ";

        if (!empty($id_experiencia)) {
            $sql .= " AND re.cn_reqe_experiencia_id = " . intval($id_experiencia);
        }

        if (!empty($cn_pla_id)) {
            $sql .= " AND re.cn_pla_id = " . intval($cn_pla_id);
        }

        $sql .= " ORDER BY rp.min_anios_exp ASC";

        return $this->db->datos($sql);
    }
}
