<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_plaza_reqf_fisicosM extends BaseModel
{
    protected $tabla = 'cn_plaza_reqf_fisicos';

    protected $primaryKey = 'cn_reqf_id AS _id';

    protected $camposPermitidos = [
        'cn_pla_id',
        'id_req_fisico_det',
        'cn_reqf_estado AS estado',
        'cn_reqf_fecha_creacion AS fecha_creacion',
        'cn_reqf_fecha_modificacion AS fecha_modificacion'
    ];

    public function listar_plaza_reqf_fisicos($id_reqf = '', $id_plaza = '')
    {
        $sql = "SELECT 
                rf.cn_reqf_id AS _id,
                rf.cn_pla_id,
                rf.id_req_fisico_det,
                rf.cn_reqf_estado,
                rf.cn_reqf_fecha_creacion,
                rf.cn_reqf_fecha_modificacion,
                rfd.descripcion AS req_fisico_det_descripcion,
                rfc.descripcion AS req_fisico_descripcion
            FROM cn_plaza_reqf_fisicos rf
            LEFT JOIN th_cat_reqf_fisicos_detalle rfd 
                ON rf.id_req_fisico_det = rfd.id_req_fisico_det
            LEFT JOIN th_cat_reqf_fisicos rfc
                ON rfd.id_req_fisico = rfc.id_req_fisico
            WHERE rf.cn_reqf_estado = 1";

        if (!empty($id_reqf)) {
            $id_reqf = intval($id_reqf);
            $sql .= " AND rf.cn_reqf_id = $id_reqf";
        }

        if (!empty($id_plaza)) {
            $id_plaza = intval($id_plaza);
            $sql .= " AND rf.cn_pla_id = $id_plaza";
        }

        $sql .= " ORDER BY rfc.descripcion ASC, rfd.descripcion ASC";

        return $this->db->datos($sql);
    }

    public function listar_req_fisicos_cabecera()
    {
        $sql = "SELECT 
                    id_req_fisico,
                    descripcion,
                    estado
                FROM th_cat_reqf_fisicos
                WHERE estado = 1
                ORDER BY descripcion ASC";

        return $this->db->datos($sql);
    }

    public function listar_detalles_no_asignados($id_plaza, $id_req_fisico)
    {
        $id_plaza      = intval($id_plaza);
        $id_req_fisico = intval($id_req_fisico);

        $sql = "SELECT 
                    rfd.id_req_fisico_det,
                    rfd.descripcion,
                    rfd.estado
                FROM th_cat_reqf_fisicos_detalle rfd
                LEFT JOIN cn_plaza_reqf_fisicos rf
                    ON rf.id_req_fisico_det = rfd.id_req_fisico_det
                    AND rf.cn_pla_id = $id_plaza
                    AND rf.cn_reqf_estado = 1
                WHERE rfd.estado = 1
                  AND rfd.id_req_fisico = $id_req_fisico
                  AND rf.id_req_fisico_det IS NULL
                ORDER BY rfd.descripcion ASC";

        return $this->db->datos($sql);
    }
}