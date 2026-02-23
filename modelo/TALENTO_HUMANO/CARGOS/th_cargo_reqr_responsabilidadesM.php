<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cargo_reqr_responsabilidadesM extends BaseModel
{
    protected $tabla = 'th_cargo_reqr_responsabilidades';

    protected $primaryKey = 'th_reqr_id AS _id';

    protected $camposPermitidos = [
        'id_cargo',
        'id_req_res_det',
        'th_reqr_estado AS estado',
        'th_reqr_fecha_creacion AS fecha_creacion',
        'th_reqr_fecha_modificacion AS fecha_modificacion'
    ];

    public function listar_cargo_responsabilidades($id_reqr = '', $id_cargo = '')
    {
        $sql = "SELECT 
                rr.th_reqr_id AS _id,
                rr.id_cargo,
                rr.id_req_res_det,
                rr.th_reqr_estado,
                rr.th_reqr_fecha_creacion,
                rr.th_reqr_fecha_modificacion,
                rrd.descripcion AS req_res_det_descripcion
            FROM th_cargo_reqr_responsabilidades rr
            LEFT JOIN th_cat_reqr_responsabilidades_detalle rrd 
                ON rr.id_req_res_det = rrd.id_req_res_det
            WHERE rr.th_reqr_estado = 1";

        if (!empty($id_reqr)) {
            $id_reqr = intval($id_reqr);
            $sql .= " AND rr.th_reqr_id = $id_reqr";
        }

        if (!empty($id_cargo)) {
            $id_cargo = intval($id_cargo);
            $sql .= " AND rr.id_cargo = $id_cargo";
        }

        $sql .= " ORDER BY rrd.descripcion ASC";

        return $this->db->datos($sql);
    }

    public function listar_catalogo_responsabilidades($id_cargo, $id_plaza = 0)
    {
        $id_c = intval($id_cargo);
        $id_p = intval($id_plaza);

        $sql = "
            SELECT 
                rrd.id_req_res_det,
                rrd.descripcion,
                rrd.estado
            FROM _contratacion.th_cat_reqr_responsabilidades_detalle rrd
            WHERE rrd.estado = 1

            AND NOT EXISTS (
                SELECT 1
                FROM _contratacion.th_cargo_reqr_responsabilidades rr
                WHERE rr.id_req_res_det = rrd.id_req_res_det
                AND rr.id_cargo = $id_c
                AND rr.th_reqr_estado = 1
            )

            AND (
                $id_p = 0
                OR NOT EXISTS (
                    SELECT 1
                    FROM _contratacion.cn_plaza_reqr_responsabilidades pr
                    WHERE pr.id_req_res_det = rrd.id_req_res_det
                    AND pr.cn_pla_id = $id_p
                    AND pr.cn_reqr_estado = 1
                )
            )

            ORDER BY rrd.descripcion ASC
        ";

         return $this->db->datos($sql, false, false, true);
    }
}