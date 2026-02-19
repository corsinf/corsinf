<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_plaza_reqct_trabajoM extends BaseModel
{
    protected $tabla = 'cn_plaza_reqct_trabajo';

    protected $primaryKey = 'cn_reqct_id AS _id';

    protected $camposPermitidos = [
        'cn_pla_id',
        'id_req_trabajo',
        'cn_reqct_estado AS estado',
        'cn_reqct_fecha_creacion AS fecha_creacion',
        'cn_reqct_fecha_modificacion AS fecha_modificacion'
    ];


    public function listar_plaza_trabajo($id_trabajo = '', $id_plaza = '')
    {
        $sql = "SELECT 
                pt.cn_reqct_id AS _id,
                pt.cn_pla_id,
                pt.id_req_trabajo,
                pt.cn_reqct_estado,
                pt.cn_reqct_fecha_creacion,
                pt.cn_reqct_fecha_modificacion,
                td.descripcion AS trabajo_descripcion
            FROM cn_plaza_reqct_trabajo pt
            LEFT JOIN th_cat_reqct_trabajo_detalle td 
                ON pt.id_req_trabajo = td.id_req_trabajo
            WHERE pt.cn_reqct_estado = 1 ";

        if (!empty($id_trabajo)) {
            $id_trabajo = intval($id_trabajo);
            $sql .= " AND pt.cn_reqct_id = $id_trabajo";
        }

        if (!empty($id_plaza)) {
            $id_plaza = intval($id_plaza);
            $sql .= " AND pt.cn_pla_id = $id_plaza";
        }

        $sql .= " ORDER BY td.descripcion ASC";

        return $this->db->datos($sql);
    }


    public function listar_trabajos_no_asignados($id_plaza)
    {
        $id = intval($id_plaza);

        $sql = "
        SELECT 
            td.id_req_trabajo,
            td.descripcion,
            td.estado
        FROM th_cat_reqct_trabajo_detalle td
        LEFT JOIN cn_plaza_reqct_trabajo pt
            ON pt.id_req_trabajo = td.id_req_trabajo
            AND pt.cn_pla_id = $id
            AND pt.cn_reqct_estado = 1
        WHERE td.estado = 1
          AND pt.id_req_trabajo IS NULL
        ORDER BY td.id_req_trabajo;
    ";

        return $this->db->datos($sql);
    }
}