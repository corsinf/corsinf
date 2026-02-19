<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_plaza_reqi_iniciativaM extends BaseModel
{
    protected $tabla = 'cn_plaza_reqi_iniciativa';

    protected $primaryKey = 'cn_pla_id AS _id';

    protected $camposPermitidos = [
        'cn_pla_id',
        'id_req_iniciativa',
        'cn_reqini_estado AS estado',
        'cn_reqini_fecha_creacion AS fecha_creacion',
        'cn_reqini_fecha_modificacion AS fecha_modificacion'
    ];

    public function listar_plaza_iniciativas($id_plaza = '')
    {
        $sql = "SELECT 
                ri.cn_pla_id,
                ri.id_req_iniciativa,
                ri.cn_reqini_estado,
                ri.cn_reqini_fecha_creacion,
                ri.cn_reqini_fecha_modificacion,
                ci.descripcion AS iniciativa_descripcion
            FROM cn_plaza_reqi_iniciativa ri
            LEFT JOIN th_cat_reqi_iniciativa ci 
                ON ri.id_req_iniciativa = ci.id_req_iniciativa
            WHERE ri.cn_reqini_estado = 1 ";

        if (!empty($id_plaza)) {
            $id_plaza = intval($id_plaza);
            $sql .= " AND ri.cn_pla_id = $id_plaza";
        }

        $sql .= " ORDER BY ci.descripcion ASC";

        return $this->db->datos($sql);
    }

    public function listar_iniciativas_no_asignadas($id_plaza)
    {
        $id = intval($id_plaza);

        $sql = "
        SELECT 
            ci.id_req_iniciativa,
            ci.descripcion,
            ci.estado
        FROM th_cat_reqi_iniciativa ci
        LEFT JOIN cn_plaza_reqi_iniciativa ri
            ON ri.id_req_iniciativa = ci.id_req_iniciativa
            AND ri.cn_pla_id = $id
            AND ri.cn_reqini_estado = 1
        WHERE ci.estado = 1
          AND ri.id_req_iniciativa IS NULL
        ORDER BY ci.descripcion;
        ";

        return $this->db->datos($sql);
    }

    public function eliminar_iniciativa($id_plaza, $id_iniciativa)
    {
        $id_plaza      = intval($id_plaza);
        $id_iniciativa = intval($id_iniciativa);

        $sql = "UPDATE cn_plaza_reqi_iniciativa 
                SET cn_reqini_estado = 0,
                    cn_reqini_fecha_modificacion = '" . date('Y-m-d H:i:s') . "'
                WHERE cn_pla_id = $id_plaza 
                AND id_req_iniciativa = $id_iniciativa";

        return $this->db->datos($sql);
    }
}