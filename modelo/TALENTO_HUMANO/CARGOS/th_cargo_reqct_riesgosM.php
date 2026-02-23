<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cargo_reqct_riesgosM extends BaseModel
{
    protected $tabla = 'th_cargo_reqct_riesgos';

    protected $primaryKey = 'th_reqr_id AS _id';

    protected $camposPermitidos = [
        'id_cargo',
        'id_req_riesgo',
        'th_reqr_estado AS estado',
        'th_reqr_fecha_creacion AS fecha_creacion',
        'th_reqr_fecha_modificacion AS fecha_modificacion'
    ];

    public function listar_cargo_riesgos($id_reqr = '', $id_cargo = '')
    {
        $sql = "SELECT 
                rr.th_reqr_id AS _id,
                rr.id_cargo,
                rr.id_req_riesgo,
                rr.th_reqr_estado,
                rr.th_reqr_fecha_creacion,
                rr.th_reqr_fecha_modificacion,
                rd.descripcion AS req_riesgo_descripcion
            FROM th_cargo_reqct_riesgos rr
            LEFT JOIN th_cat_reqct_riesgos_detalle rd
                ON rr.id_req_riesgo = rd.id_req_riesgo
            WHERE rr.th_reqr_estado = 1";

        if (!empty($id_reqr)) {
            $id_reqr = intval($id_reqr);
            $sql .= " AND rr.th_reqr_id = $id_reqr";
        }

        if (!empty($id_cargo)) {
            $id_cargo = intval($id_cargo);
            $sql .= " AND rr.id_cargo = $id_cargo";
        }

        $sql .= " ORDER BY rd.descripcion ASC";

        return $this->db->datos($sql);
    }

    public function listar_catalogo_riesgos($id_cargo, $id_plaza = 0)
    {
        $id_c = intval($id_cargo);
        $id_p = intval($id_plaza);

        $sql = "
            SELECT 
                rd.id_req_riesgo,
                rd.descripcion,
                rd.estado
            FROM _contratacion.th_cat_reqct_riesgos_detalle rd
            WHERE rd.estado = 1

            AND NOT EXISTS (
                SELECT 1
                FROM _contratacion.th_cargo_reqct_riesgos rr
                WHERE rr.id_req_riesgo = rd.id_req_riesgo
                AND rr.id_cargo = $id_c
                AND rr.th_reqr_estado = 1
            )

            AND (
                $id_p = 0
                OR NOT EXISTS (
                    SELECT 1
                    FROM _contratacion.cn_plaza_reqct_riesgos pr
                    WHERE pr.id_req_riesgo = rd.id_req_riesgo
                    AND pr.cn_pla_id = $id_p
                    AND pr.cn_reqr_estado = 1
                )
            )

            ORDER BY rd.descripcion ASC
        ";

         return $this->db->datos($sql, false, false, true);
    }
}
