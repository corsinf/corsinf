<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cargo_reqi_experienciaM extends BaseModel
{
    protected $tabla = 'th_cargo_reqi_experiencia';

    protected $primaryKey = 'th_reqe_experiencia_id AS _id';

    protected $camposPermitidos = [
        'id_cargo',
        'id_rango_profesional',
        'th_reqe_estado AS estado',
        'th_reqe_fecha_creacion AS fecha_creacion',
        'th_reqe_fecha_modificacion AS fecha_modificacion'
    ];

    public function listar_cargo_experiencia($id_experiencia = '', $id_cargo = '')
    {
        $sql = "
            SELECT 
                re.th_reqe_experiencia_id AS _id,
                re.id_cargo,
                re.id_rango_profesional,
                re.th_reqe_estado,
                re.th_reqe_fecha_creacion,
                re.th_reqe_fecha_modificacion,
                rp.nombre        AS rango_nombre,
                rp.descripcion   AS rango_descripcion,
                rp.min_anios_exp,
                rp.max_anios_exp
            FROM th_cargo_reqi_experiencia re
            LEFT JOIN th_cat_rango_profesional rp 
                ON re.id_rango_profesional = rp.id_rango_profesional
            WHERE re.th_reqe_estado = 1
        ";

        if (!empty($id_experiencia)) {
            $sql .= " AND re.th_reqe_experiencia_id = " . intval($id_experiencia);
        }

        if (!empty($id_cargo)) {
            $sql .= " AND re.id_cargo = " . intval($id_cargo);
        }

        $sql .= " ORDER BY rp.min_anios_exp ASC";

        return $this->db->datos($sql);
    }

    public function listar_rangos_no_asignados($id_cargo, $id_plaza = 0)
    {
        $id_c = intval($id_cargo);
        $id_p = intval($id_plaza);

        $sql = "
            SELECT 
                rp.id_rango_profesional,
                rp.nombre,
                rp.descripcion,
                rp.min_anios_exp,
                rp.max_anios_exp,
                rp.estado
            FROM _contratacion.th_cat_rango_profesional rp
            WHERE rp.estado = 1

            AND NOT EXISTS (
                SELECT 1
                FROM _contratacion.th_cargo_reqi_experiencia re_cargo
                WHERE re_cargo.id_rango_profesional = rp.id_rango_profesional
                  AND re_cargo.id_cargo = $id_c
                  AND re_cargo.th_reqe_estado = 1
            )

            AND (
                $id_p = 0
                OR NOT EXISTS (
                    SELECT 1
                    FROM _contratacion.cn_plaza_reqi_experiencia re_plaza
                    WHERE re_plaza.id_rango_profesional = rp.id_rango_profesional
                      AND re_plaza.cn_pla_id = $id_p
                      AND re_plaza.cn_reqe_estado = 1
                )
            )

            ORDER BY rp.min_anios_exp ASC
        ";

        return $this->db->datos($sql, false, false, true);
    }
}
