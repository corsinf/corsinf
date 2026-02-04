<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_pos_referencias_laboralesM extends BaseModel
{
    protected $tabla = 'th_pos_referencias_laborales';
    protected $primaryKey = 'th_refl_id AS _id';

    protected $camposPermitidos = [
        'th_refl_nombre_referencia',
        'th_refl_telefono_referencia',
        'th_refl_carta_recomendacion',
        'th_pos_id',
        'th_refl_estado',
        'th_refl_fecha_creacion',
        'th_refl_fecha_modificacion',
        'th_refl_correo',
        'th_refl_nombre_empresa',
        'th_expl_id',
    ];

    public function listar_referencias_completo($th_pos_id = null, $th_refl_id = null)
    {
        $sql = "
    SELECT 
        r.th_refl_id AS _id,
        r.th_refl_nombre_referencia,
        r.th_refl_telefono_referencia,
        r.th_refl_carta_recomendacion,
        r.th_refl_correo,
        r.th_refl_estado,
        r.th_pos_id,
        r.th_expl_id,
        ISNULL(exp.th_expl_nombre_empresa, r.th_refl_nombre_empresa) AS nombre_empresa_final,
        exp.th_expl_nombre_empresa AS empresa_experiencia
    FROM th_pos_referencias_laborales r
    LEFT JOIN th_pos_experiencia_laboral exp 
        ON r.th_expl_id = exp.th_expl_id
    WHERE r.th_refl_estado = 1
    ";

        if (!empty($th_pos_id)) {
            $th_pos_id = intval($th_pos_id);
            $sql .= " AND r.th_pos_id = $th_pos_id";
        }

        if (!empty($th_refl_id)) {
            $th_refl_id = intval($th_refl_id);
            $sql .= " AND r.th_refl_id = $th_refl_id";
        }

        $sql .= " ORDER BY r.th_refl_nombre_referencia ASC";

        return $this->db->datos($sql);
    }
}
