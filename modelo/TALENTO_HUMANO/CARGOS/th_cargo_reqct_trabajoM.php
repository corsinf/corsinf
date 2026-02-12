<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cargo_reqct_trabajoM extends BaseModel
{
    protected $tabla = 'th_cargo_reqct_trabajo';

    protected $primaryKey = 'th_reqct_id AS _id';

    protected $camposPermitidos = [
        'id_cargo',
        'id_req_trabajo',
        'th_reqct_estado AS estado',
        'th_reqct_fecha_creacion AS fecha_creacion',
        'th_reqct_fecha_modificacion AS fecha_modificacion'
    ];

    public function listar_cargo_trabajo($id_reqct = '', $id_cargo = '')
    {
        $sql = "SELECT 
                rt.th_reqct_id AS _id,
                rt.id_cargo,
                rt.id_req_trabajo,
                rt.th_reqct_estado,
                rt.th_reqct_fecha_creacion,
                rt.th_reqct_fecha_modificacion,
                rd.descripcion AS req_trabajo_descripcion
            FROM th_cargo_reqct_trabajo rt
            LEFT JOIN th_cat_reqct_trabajo_detalle rd
                ON rt.id_req_trabajo = rd.id_req_trabajo
            WHERE rt.th_reqct_estado = 1";

        if (!empty($id_reqct)) {
            $id_reqct = intval($id_reqct);
            $sql .= " AND rt.th_reqct_id = $id_reqct";
        }

        if (!empty($id_cargo)) {
            $id_cargo = intval($id_cargo);
            $sql .= " AND rt.id_cargo = $id_cargo";
        }

        $sql .= " ORDER BY rd.descripcion ASC";

        return $this->db->datos($sql);
    }

    public function listar_trabajo_no_asignado($id_cargo, $query = '')
    {
        $id_cargo = intval($id_cargo);

        $sql = "SELECT 
                    rd.id_req_trabajo,
                    rd.descripcion,
                    rd.estado
                FROM th_cat_reqct_trabajo_detalle rd
                LEFT JOIN th_cargo_reqct_trabajo rt
                    ON rt.id_req_trabajo = rd.id_req_trabajo
                    AND rt.id_cargo = $id_cargo
                    AND rt.th_reqct_estado = 1
                WHERE rd.estado = 1
                  AND rt.id_req_trabajo IS NULL";

        if ($query !== '') {
            $sql .= " AND rd.descripcion LIKE '%$query%'";
        }

        $sql .= " ORDER BY rd.descripcion ASC";

        return $this->db->datos($sql);
    }
}