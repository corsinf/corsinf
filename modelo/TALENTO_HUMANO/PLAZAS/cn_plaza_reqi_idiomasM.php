<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class cn_plaza_reqi_idiomasM extends BaseModel
{
    protected $tabla = 'cn_plaza_reqi_idiomas';

    protected $primaryKey = 'cn_reqid_experiencia_id AS _id';

    protected $camposPermitidos = [
        'cn_pla_id',
        'id_idiomas',
        'id_idiomas_nivel',
        'cn_reqid_estado AS estado',
        'cn_reqid_fecha_creacion AS fecha_creacion',
        'cn_reqid_fecha_modificacion AS fecha_modificacion'
    ];

    public function listar_plaza_idiomas($id_idioma = '', $id_plaza = '')
    {
        $sql = "SELECT 
                ri.cn_reqid_experiencia_id AS _id,
                ri.cn_pla_id,
                ri.id_idiomas,
                ri.id_idiomas_nivel,
                ri.cn_reqid_estado,
                ri.cn_reqid_fecha_creacion,
                ri.cn_reqid_fecha_modificacion,
                i.descripcion AS idioma_descripcion,
                n.descripcion AS nivel_descripcion
            FROM cn_plaza_reqi_idiomas ri
            LEFT JOIN th_cat_idiomas i 
                ON ri.id_idiomas = i.id_idiomas
            LEFT JOIN th_cat_idiomas_nivel n
                ON ri.id_idiomas_nivel = n.id_idiomas_nivel
            WHERE ri.cn_reqid_estado = 1 ";

        if (!empty($id_idioma)) {
            $id_idioma = intval($id_idioma);
            $sql .= " AND ri.cn_reqid_experiencia_id = $id_idioma";
        }

        if (!empty($id_plaza)) {
            $id_plaza = intval($id_plaza);
            $sql .= " AND ri.cn_pla_id = $id_plaza";
        }

        $sql .= " ORDER BY i.descripcion ASC";

        return $this->db->datos($sql);
    }

    public function listar_idiomas_no_asignados($id_plaza)
    {
        $id = intval($id_plaza);

        $sql = "
        SELECT 
            i.id_idiomas,
            i.descripcion,
            i.estado
        FROM th_cat_idiomas i
        LEFT JOIN cn_plaza_reqi_idiomas ri
            ON ri.id_idiomas = i.id_idiomas
            AND ri.cn_pla_id = $id
            AND ri.cn_reqid_estado = 1
        WHERE i.estado = 1
          AND ri.id_idiomas IS NULL
        ORDER BY i.descripcion;
        ";

        return $this->db->datos($sql);
    }
}