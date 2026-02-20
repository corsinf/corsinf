<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class cn_plaza_reqi_instruccionM extends BaseModel
{
    protected $tabla = 'cn_plaza_reqi_instruccion';

    protected $primaryKey = 'cn_reqi_instruccion_id AS _id';

    protected $camposPermitidos = [
        'cn_pla_id',
        'id_nivel_academico',
        'cn_reqi_estado AS estado',
        'cn_reqi_fecha_creacion AS fecha_creacion',
        'cn_reqi_fecha_modificacion AS fecha_modificacion'
    ];


    public function listar_plaza_instruccion($id_instruccion = '', $id_plaza = '')
    {
        $sql = "SELECT 
                ri.cn_reqi_instruccion_id AS _id,
                ri.cn_pla_id,
                ri.id_nivel_academico,
                ri.cn_reqi_estado,
                ri.cn_reqi_fecha_creacion,
                ri.cn_reqi_fecha_modificacion,
                na.descripcion AS nivel_academico_descripcion
            FROM cn_plaza_reqi_instruccion ri
            LEFT JOIN th_cat_pos_nivel_academico na 
                ON ri.id_nivel_academico = na.id_nivel_academico
            WHERE ri.cn_reqi_estado = 1 ";

        if (!empty($id_instruccion)) {
            $id_instruccion = intval($id_instruccion);
            $sql .= " AND ri.cn_reqi_instruccion_id = $id_instruccion";
        }

        if (!empty($id_plaza)) {
            $id_plaza = intval($id_plaza);
            $sql .= " AND ri.cn_pla_id = $id_plaza";
        }

        $sql .= " ORDER BY na.descripcion ASC";

        return $this->db->datos($sql);
    }


    public function listar_niveles_no_asignados($id_plaza)
    {
        $id = intval($id_plaza);

        $sql = "
        SELECT 
            na.id_nivel_academico,
            na.descripcion,
            na.estado
        FROM th_cat_pos_nivel_academico na
        LEFT JOIN cn_plaza_reqi_instruccion ri
            ON ri.id_nivel_academico = na.id_nivel_academico
            AND ri.cn_pla_id = $id
            AND ri.cn_reqi_estado = 1
        WHERE na.estado = 1
          AND ri.id_nivel_academico IS NULL
        ORDER BY na.id_nivel_academico;
    ";

        return $this->db->datos($sql);
    }
}