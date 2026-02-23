<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class cn_plaza_reqct_riesgosM extends BaseModel
{
    protected $tabla = 'cn_plaza_reqct_riesgos';

    protected $primaryKey = 'cn_reqr_id AS _id';

    protected $camposPermitidos = [
        'cn_pla_id',
        'id_req_riesgo',
        'cn_reqr_estado AS estado',
        'cn_reqr_fecha_creacion AS fecha_creacion',
        'cn_reqr_fecha_modificacion AS fecha_modificacion'
    ];


    public function listar_plaza_riesgos($id_riesgo = '', $id_plaza = '')
    {
        $sql = "SELECT 
                pr.cn_reqr_id AS _id,
                pr.cn_pla_id,
                pr.id_req_riesgo,
                pr.cn_reqr_estado,
                pr.cn_reqr_fecha_creacion,
                pr.cn_reqr_fecha_modificacion,
                rr.descripcion AS riesgo_descripcion
            FROM cn_plaza_reqct_riesgos pr
            LEFT JOIN th_cat_reqct_riesgos_detalle rr 
                ON pr.id_req_riesgo = rr.id_req_riesgo
            WHERE pr.cn_reqr_estado = 1 ";

        if (!empty($id_riesgo)) {
            $id_riesgo = intval($id_riesgo);
            $sql .= " AND pr.cn_reqr_id = $id_riesgo";
        }

        if (!empty($id_plaza)) {
            $id_plaza = intval($id_plaza);
            $sql .= " AND pr.cn_pla_id = $id_plaza";
        }

        $sql .= " ORDER BY rr.descripcion ASC";

        return $this->db->datos($sql);
    }


    public function listar_riesgos_no_asignados($id_plaza)
    {
        $id = intval($id_plaza);

        $sql = "
        SELECT 
            rr.id_req_riesgo,
            rr.descripcion,
            rr.estado
        FROM th_cat_reqct_riesgos_detalle rr
        LEFT JOIN cn_plaza_reqct_riesgos pr
            ON pr.id_req_riesgo = rr.id_req_riesgo
            AND pr.cn_pla_id = $id
            AND pr.cn_reqr_estado = 1
        WHERE rr.estado = 1
          AND pr.id_req_riesgo IS NULL
        ORDER BY rr.id_req_riesgo;
    ";

        return $this->db->datos($sql);
    }
}