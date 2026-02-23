<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class cn_plaza_reqr_responsabilidadesM extends BaseModel
{
    protected $tabla = 'cn_plaza_reqr_responsabilidades';

    protected $primaryKey = 'cn_reqr_id AS _id';

    protected $camposPermitidos = [
        'cn_pla_id',
        'id_req_res_det',
        'cn_reqr_estado AS estado',
        'cn_reqr_fecha_creacion AS fecha_creacion',
        'cn_reqr_fecha_modificacion AS fecha_modificacion'
    ];

    public function listar_plaza_responsabilidades($id_reqr = '', $id_plaza = '')
    {
        $sql = "SELECT 
                rr.cn_reqr_id AS _id,
                rr.cn_pla_id,
                rr.id_req_res_det,
                rr.cn_reqr_estado,
                rr.cn_reqr_fecha_creacion,
                rr.cn_reqr_fecha_modificacion,
                rrd.descripcion AS req_res_det_descripcion
            FROM cn_plaza_reqr_responsabilidades rr
            LEFT JOIN th_cat_reqr_responsabilidades_detalle rrd 
                ON rr.id_req_res_det = rrd.id_req_res_det
            WHERE rr.cn_reqr_estado = 1";

        if (!empty($id_reqr)) {
            $id_reqr = intval($id_reqr);
            $sql .= " AND rr.cn_reqr_id = $id_reqr";
        }

        if (!empty($id_plaza)) {
            $id_plaza = intval($id_plaza);
            $sql .= " AND rr.cn_pla_id = $id_plaza";
        }

        $sql .= " ORDER BY rrd.descripcion ASC";

        return $this->db->datos($sql);
    }

    public function listar_detalles_no_asignados($id_plaza, $query = '')
    {
        $id_plaza = intval($id_plaza);

        $sql = "SELECT 
                    rrd.id_req_res_det,
                    rrd.descripcion,
                    rrd.estado
                FROM th_cat_reqr_responsabilidades_detalle rrd
                LEFT JOIN cn_plaza_reqr_responsabilidades rr
                    ON rr.id_req_res_det = rrd.id_req_res_det
                    AND rr.cn_pla_id = $id_plaza
                    AND rr.cn_reqr_estado = 1
                WHERE rrd.estado = 1
                  AND rr.id_req_res_det IS NULL";

        if ($query !== '') {
            $sql .= " AND rrd.descripcion LIKE '%$query%'";
        }

        $sql .= " ORDER BY rrd.descripcion ASC";

        return $this->db->datos($sql);
    }
}