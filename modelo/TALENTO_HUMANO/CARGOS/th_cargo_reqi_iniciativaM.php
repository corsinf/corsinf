<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cargo_reqi_iniciativaM extends BaseModel
{
    protected $tabla = 'th_cargo_reqi_iniciativa';

    protected $primaryKey = 'id_cargo AS _id';

    protected $camposPermitidos = [
        'id_cargo',
        'id_req_iniciativa',
        'th_reqini_estado AS estado',
        'th_reqini_fecha_creacion AS fecha_creacion',
        'th_reqini_fecha_modificacion AS fecha_modificacion'
    ];

    public function listar_cargo_iniciativas($id_cargo = '')
    {
        $sql = "SELECT 
                ri.id_cargo,
                ri.id_req_iniciativa,
                ri.th_reqini_estado,
                ri.th_reqini_fecha_creacion,
                ri.th_reqini_fecha_modificacion,
                ci.descripcion AS iniciativa_descripcion
            FROM th_cargo_reqi_iniciativa ri
            LEFT JOIN th_cat_reqi_iniciativa ci 
                ON ri.id_req_iniciativa = ci.id_req_iniciativa
            WHERE ri.th_reqini_estado = 1 ";

        if (!empty($id_cargo)) {
            $id_cargo = intval($id_cargo);
            $sql .= " AND ri.id_cargo = $id_cargo";
        }

        $sql .= " ORDER BY ci.descripcion ASC";

        return $this->db->datos($sql);
    }

    public function listar_iniciativas_no_asignadas($id_cargo)
    {
        $id = intval($id_cargo);

        $sql = "
        SELECT 
            ci.id_req_iniciativa,
            ci.descripcion,
            ci.estado
        FROM th_cat_reqi_iniciativa ci
        LEFT JOIN th_cargo_reqi_iniciativa ri
            ON ri.id_req_iniciativa = ci.id_req_iniciativa
            AND ri.id_cargo = $id
            AND ri.th_reqini_estado = 1
        WHERE ci.estado = 1
          AND ri.id_req_iniciativa IS NULL
        ORDER BY ci.descripcion;
        ";

        return $this->db->datos($sql);
    }

    public function eliminar_iniciativa($id_cargo, $id_iniciativa)
    {
        $id_cargo = intval($id_cargo);
        $id_iniciativa = intval($id_iniciativa);

        $sql = "UPDATE th_cargo_reqi_iniciativa 
                SET th_reqini_estado = 0,
                    th_reqini_fecha_modificacion = '" . date('Y-m-d H:i:s') . "'
                WHERE id_cargo = $id_cargo 
                AND id_req_iniciativa = $id_iniciativa";

        return $this->db->datos($sql);
    }
}