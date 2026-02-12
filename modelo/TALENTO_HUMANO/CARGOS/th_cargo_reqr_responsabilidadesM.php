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

    public function listar_detalles_no_asignados($id_cargo, $query = '')
    {
        $id_cargo = intval($id_cargo);

        $sql = "SELECT 
                    rrd.id_req_res_det,
                    rrd.descripcion,
                    rrd.estado
                FROM th_cat_reqr_responsabilidades_detalle rrd
                LEFT JOIN th_cargo_reqr_responsabilidades rr
                    ON rr.id_req_res_det = rrd.id_req_res_det
                    AND rr.id_cargo = $id_cargo
                    AND rr.th_reqr_estado = 1
                WHERE rrd.estado = 1
                  AND rr.id_req_res_det IS NULL";

        if ($query !== '') {
            $sql .= " AND rrd.descripcion LIKE '%$query%'";
        }

        $sql .= " ORDER BY rrd.descripcion ASC";

        return $this->db->datos($sql);
    }
}