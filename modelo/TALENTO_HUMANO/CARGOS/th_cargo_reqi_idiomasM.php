<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cargo_reqi_idiomasM extends BaseModel
{
    protected $tabla = 'th_cargo_reqi_idiomas';

    protected $primaryKey = 'th_reqid_experiencia_id AS _id';

    protected $camposPermitidos = [
        'id_cargo',
        'id_idiomas',
        'id_idiomas_nivel',
        'th_reqid_estado AS estado',
        'th_reqid_fecha_creacion AS fecha_creacion',
        'th_reqid_fecha_modificacion AS fecha_modificacion'
    ];

    public function listar_cargo_idiomas($id_idioma = '', $id_cargo = '')
    {
        $sql = "SELECT 
                ri.th_reqid_experiencia_id AS _id,
                ri.id_cargo,
                ri.id_idiomas,
                ri.id_idiomas_nivel,
                ri.th_reqid_estado,
                ri.th_reqid_fecha_creacion,
                ri.th_reqid_fecha_modificacion,
                i.descripcion AS idioma_descripcion,
                n.descripcion AS nivel_descripcion
            FROM th_cargo_reqi_idiomas ri
            LEFT JOIN th_cat_idiomas i 
                ON ri.id_idiomas = i.id_idiomas
            LEFT JOIN th_cat_idiomas_nivel n
                ON ri.id_idiomas_nivel = n.id_idiomas_nivel
            WHERE ri.th_reqid_estado = 1 ";

        if (!empty($id_idioma)) {
            $id_idioma = intval($id_idioma);
            $sql .= " AND ri.th_reqid_experiencia_id = $id_idioma";
        }

        if (!empty($id_cargo)) {
            $id_cargo = intval($id_cargo);
            $sql .= " AND ri.id_cargo = $id_cargo";
        }

        $sql .= " ORDER BY i.descripcion ASC";

        return $this->db->datos($sql);
    }

    public function listar_idiomas_no_asignados($id_cargo)
    {
        $id = intval($id_cargo);

        $sql = "
        SELECT 
            i.id_idiomas,
            i.descripcion,
            i.estado
        FROM th_cat_idiomas i
        LEFT JOIN th_cargo_reqi_idiomas ri
            ON ri.id_idiomas = i.id_idiomas
            AND ri.id_cargo = $id
            AND ri.th_reqid_estado = 1
        WHERE i.estado = 1
          AND ri.id_idiomas IS NULL
        ORDER BY i.descripcion;
        ";

        return $this->db->datos($sql);
    }
}