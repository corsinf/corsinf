<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cargo_reqi_aptitudM extends BaseModel
{
    protected $tabla = 'th_cargo_reqi_aptitud';

    protected $primaryKey = 'th_reqa_experiencia_id AS _id';

    protected $camposPermitidos = [
        'id_cargo',
        'th_hab_id',
        'th_reqa_estado AS estado',
        'th_reqa_fecha_creacion AS fecha_creacion',
        'th_reqa_fecha_modificacion AS fecha_modificacion'
    ];

    public function listar_cargo_aptitudes($id_aptitud = '', $id_cargo = '')
    {
        $sql = "SELECT 
                ra.th_reqa_experiencia_id AS _id,
                ra.id_cargo,
                ra.th_hab_id,
                ra.th_reqa_estado,
                ra.th_reqa_fecha_creacion,
                ra.th_reqa_fecha_modificacion,
                h.th_hab_nombre AS habilidad_nombre
            FROM th_cargo_reqi_aptitud ra
            LEFT JOIN th_cat_habilidades h 
                ON ra.th_hab_id = h.th_hab_id
            WHERE ra.th_reqa_estado = 1 ";

        if (!empty($id_aptitud)) {
            $id_aptitud = intval($id_aptitud);
            $sql .= " AND ra.th_reqa_experiencia_id = $id_aptitud";
        }

        if (!empty($id_cargo)) {
            $id_cargo = intval($id_cargo);
            $sql .= " AND ra.id_cargo = $id_cargo";
        }

        $sql .= " ORDER BY h.th_hab_nombre ASC";

        return $this->db->datos($sql);
    }

    public function listar_habilidades_no_asignadas($id_cargo)
    {
        $id = intval($id_cargo);

        $sql = "
        SELECT 
            h.th_hab_id,
            h.th_hab_nombre,
            h.th_hab_estado
        FROM th_cat_habilidades h
        LEFT JOIN th_cargo_reqi_aptitud ra
            ON ra.th_hab_id = h.th_hab_id
            AND ra.id_cargo = $id
            AND ra.th_reqa_estado = 1
        WHERE h.th_hab_estado = 1
          AND ra.th_hab_id IS NULL
        ORDER BY h.th_hab_nombre;
        ";

        return $this->db->datos($sql);
    }
}