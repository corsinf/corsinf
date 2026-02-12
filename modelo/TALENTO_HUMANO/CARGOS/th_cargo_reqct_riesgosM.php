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

    public function listar_riesgos_no_asignados($id_cargo, $query = '')
    {
        $id_cargo = intval($id_cargo);

        $sql = "SELECT 
                    rd.id_req_riesgo,
                    rd.descripcion,
                    rd.estado
                FROM th_cat_reqct_riesgos_detalle rd
                LEFT JOIN th_cargo_reqct_riesgos rr
                    ON rr.id_req_riesgo = rd.id_req_riesgo
                    AND rr.id_cargo = $id_cargo
                    AND rr.th_reqr_estado = 1
                WHERE rd.estado = 1
                  AND rr.id_req_riesgo IS NULL";

        if ($query !== '') {
            $sql .= " AND rd.descripcion LIKE '%$query%'";
        }

        $sql .= " ORDER BY rd.descripcion ASC";

        return $this->db->datos($sql);
    }
}