<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cargo_reqf_fisicosM extends BaseModel
{
    protected $tabla = 'th_cargo_reqf_fisicos';

    protected $primaryKey = 'th_reqf_id AS _id';

    protected $camposPermitidos = [
        'id_cargo',
        'id_req_fisico_det',
        'th_reqf_estado AS estado',
        'th_reqf_fecha_creacion AS fecha_creacion',
        'th_reqf_fecha_modificacion AS fecha_modificacion'
    ];

    public function listar_cargo_reqf_fisicos($id_reqf = '', $id_cargo = '')
    {
        $sql = "SELECT 
                rf.th_reqf_id AS _id,
                rf.id_cargo,
                rf.id_req_fisico_det,
                rf.th_reqf_estado,
                rf.th_reqf_fecha_creacion,
                rf.th_reqf_fecha_modificacion,
                rfd.descripcion AS req_fisico_det_descripcion,
                rfc.descripcion AS req_fisico_descripcion
            FROM th_cargo_reqf_fisicos rf
            LEFT JOIN th_cat_reqf_fisicos_detalle rfd 
                ON rf.id_req_fisico_det = rfd.id_req_fisico_det
            LEFT JOIN th_cat_reqf_fisicos rfc
                ON rfd.id_req_fisico = rfc.id_req_fisico
            WHERE rf.th_reqf_estado = 1";

        if (!empty($id_reqf)) {
            $id_reqf = intval($id_reqf);
            $sql .= " AND rf.th_reqf_id = $id_reqf";
        }

        if (!empty($id_cargo)) {
            $id_cargo = intval($id_cargo);
            $sql .= " AND rf.id_cargo = $id_cargo";
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

    public function listar_detalles_no_asignados($id_cargo, $id_req_fisico, $id_plaza = 0)
    {
        $id_cargo      = intval($id_cargo);
        $id_req_fisico = intval($id_req_fisico);
        $id_p          = intval($id_plaza);

        $sql = "
        SELECT 
            rfd.id_req_fisico_det,
            rfd.descripcion,
            rfd.estado
        FROM _contratacion.th_cat_reqf_fisicos_detalle rfd
        WHERE rfd.estado = 1
          AND rfd.id_req_fisico = $id_req_fisico

        AND NOT EXISTS (
            SELECT 1
            FROM _contratacion.th_cargo_reqf_fisicos rf_cargo
            WHERE rf_cargo.id_req_fisico_det = rfd.id_req_fisico_det
            AND rf_cargo.id_cargo = $id_cargo
            AND rf_cargo.th_reqf_estado = 1
        )

        AND (
            $id_p = 0
            OR NOT EXISTS (
                SELECT 1
                FROM _contratacion.cn_plaza_reqf_fisicos rf_plaza
                WHERE rf_plaza.id_req_fisico_det = rfd.id_req_fisico_det
                AND rf_plaza.cn_pla_id = $id_p
                AND rf_plaza.cn_reqf_estado = 1
            )
        )

        ORDER BY rfd.descripcion ASC
    ";

         return $this->db->datos($sql, false, false, true);
    }
}
