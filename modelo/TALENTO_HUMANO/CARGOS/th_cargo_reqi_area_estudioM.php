<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cargo_reqi_area_estudioM extends BaseModel
{
    protected $tabla = 'th_cargo_reqi_area_estudio';

    protected $primaryKey = 'th_reqia_id_ae AS _id';

    protected $camposPermitidos = [
        'id_cargo AS id_cargo',
        'id_area_estudio AS id_area_estudio',
        'th_reqia_estado AS estado',
        'th_reqia_fecha_creacion AS fecha_creacion',
        'th_reqia_fecha_modificacion AS fecha_modificacion',
    ];


    public function lista_cargo_area_estudio($id_area_estudio = '', $id_cargo = '')
    {
        $sql =
            "SELECT 
                ra.th_reqia_id_ae AS _id,
                ra.id_cargo,
                ra.id_area_estudio,
                ra.th_reqia_estado,
                ra.th_reqia_fecha_creacion AS fecha_creacion,
                ra.th_reqia_fecha_modificacion AS fecha_modificacion,
                ae.descripcion AS area_estudio_descripcion
            FROM th_cargo_reqi_area_estudio ra
            LEFT JOIN th_cat_area_estudio ae 
                ON ra.id_area_estudio = ae.id_area_estudio
            WHERE ra.th_reqia_estado = 1 
              AND (ae.estado = 1 OR ae.estado IS NULL)";

        if (!empty($id_area_estudio)) {
            $id_area_estudio = intval($id_area_estudio);
            $sql .= " AND ra.id_area_estudio = $id_area_estudio";
        }

        if (!empty($id_cargo)) {
            $id_cargo = intval($id_cargo);
            $sql .= " AND ra.id_cargo = $id_cargo";
        }

        $sql .= " ORDER BY ae.descripcion ASC";

        return $this->db->datos($sql);
    }


    public function listar_catalogo_area_estudio($id_cargo, $id_plaza = 0)
    {
        $id_c = intval($id_cargo);
        $id_p = intval($id_plaza);

        $sql =
            "SELECT 
                ae.id_area_estudio,
                ae.descripcion,
                ae.estado
            FROM _talentoh.th_cat_area_estudio ae
            WHERE ae.estado = 1

            AND NOT EXISTS (
                SELECT 1
                FROM _contratacion.th_cargo_reqi_area_estudio ca
                WHERE ca.id_area_estudio = ae.id_area_estudio
                AND ca.id_cargo = $id_c
                AND ca.th_reqia_estado = 1
            )

            AND (
                $id_p = 0
                OR NOT EXISTS (
                    SELECT 1
                    FROM _contratacion.cn_plaza_reqi_area_estudio pa
                    WHERE pa.id_area_estudio = ae.id_area_estudio
                    AND pa.cn_pla_id = $id_p
                    AND pa.cn_reqia_estado = 1
                )
            )

            ORDER BY ae.descripcion
        ";

        return $this->db->datos($sql, false, false, true);
    }
}
