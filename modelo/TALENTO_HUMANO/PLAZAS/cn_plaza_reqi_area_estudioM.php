<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class cn_plaza_reqi_area_estudioM extends BaseModel
{
    protected $tabla = 'cn_plaza_reqi_area_estudio';

    protected $primaryKey = 'cn_reqia_id_ae AS _id';

    protected $camposPermitidos = [
        'cn_pla_id',
        'id_area_estudio',
        'cn_reqia_estado AS estado',
        'cn_reqia_fecha_creacion AS fecha_creacion',
        'cn_reqia_fecha_modificacion AS fecha_modificacion'
    ];

    public function listar_plaza_area_estudio($id_area_estudio = '', $id_plaza = '')
    {
        $sql = "SELECT 
                ae.cn_reqia_id_ae AS _id,
                ae.cn_pla_id,
                ae.id_area_estudio,
                ae.cn_reqia_estado,
                ae.cn_reqia_fecha_creacion,
                ae.cn_reqia_fecha_modificacion,
                cat.descripcion AS area_estudio_descripcion
            FROM cn_plaza_reqi_area_estudio ae
            LEFT JOIN th_cat_area_estudio cat
                ON ae.id_area_estudio = cat.id_area_estudio
            WHERE ae.cn_reqia_estado = 1 ";

        if (!empty($id_area_estudio)) {
            $id_area_estudio = intval($id_area_estudio);
            $sql .= " AND ae.cn_reqia_id_ae = $id_area_estudio";
        }

        if (!empty($id_plaza)) {
            $id_plaza = intval($id_plaza);
            $sql .= " AND ae.cn_pla_id = $id_plaza";
        }

        $sql .= " ORDER BY cat.descripcion ASC";

        return $this->db->datos($sql);
    }

    public function listar_areas_no_asignadas($id_plaza)
    {
        $id = intval($id_plaza);

        $sql = "
        SELECT 
            cat.id_area_estudio,
            cat.descripcion,
            cat.estado
        FROM th_cat_area_estudio cat
        LEFT JOIN cn_plaza_reqi_area_estudio ae
            ON ae.id_area_estudio = cat.id_area_estudio
            AND ae.cn_pla_id = $id
            AND ae.cn_reqia_estado = 1
        WHERE cat.estado = 1
          AND ae.id_area_estudio IS NULL
        ORDER BY cat.id_area_estudio;
        ";

        return $this->db->datos($sql);
    }
}