<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_pos_experiencia_laboralM extends BaseModel
{
    protected $tabla = 'th_pos_experiencia_laboral';
    protected $primaryKey = 'th_expl_id AS _id';

    protected $camposPermitidos = [
        'th_expl_nombre_empresa',
        'th_expl_cargos_ocupados',
        'th_expl_fecha_inicio_experiencia',
        'th_expl_fecha_fin_experiencia',
        'th_expl_cbx_fecha_fin_experiencia',
        'th_expl_responsabilidades',
        'th_expl_logros',
        'th_expl_estado',
        'th_expl_fecha_creacion',
        'th_expl_fecha_modificacion',
        'th_expl_sueldo',
        'id_nomina',
    ];

    public function listar_experiencia_laboral_postulante($th_pos_id = null, $th_expl_id = null)
    {
        $sql = "
    SELECT
        expl.th_expl_id AS _id,
        expl.th_pos_id,
        expl.th_expl_nombre_empresa,
        expl.th_expl_cargos_ocupados,
        expl.th_expl_fecha_inicio_experiencia,
        expl.th_expl_fecha_fin_experiencia,
        expl.th_expl_cbx_fecha_fin_experiencia,
        expl.th_expl_responsabilidades,
        expl.th_expl_logros,
        expl.th_expl_estado,
        expl.th_expl_fecha_creacion,
        expl.th_expl_fecha_modificacion,
        expl.th_expl_sueldo,
        expl.id_nomina,
        nom.nombre AS descripcion_nomina,
        nom.tipo AS tipo_nomina
    FROM th_pos_experiencia_laboral expl
    LEFT JOIN th_cat_nomina nom ON expl.id_nomina = nom.id_nomina
    WHERE expl.th_expl_estado = 1
    ";

        // Filtro por ID de Postulante (Listado general)
        if (!empty($th_pos_id)) {
            $th_pos_id = intval($th_pos_id);
            $sql .= " AND expl.th_pos_id = $th_pos_id";
        }

        // Filtro por ID de Registro específico (Edición)
        if (!empty($th_expl_id)) {
            $th_expl_id = intval($th_expl_id);
            $sql .= " AND expl.th_expl_id = $th_expl_id";
        }

        // Ordenamiento: Prioridad a empleos actuales, luego por fecha fin e inicio
        $sql .= " ORDER BY 
                expl.th_expl_cbx_fecha_fin_experiencia DESC, 
                expl.th_expl_fecha_fin_experiencia DESC,     
                expl.th_expl_fecha_inicio_experiencia DESC";

        return $this->db->datos($sql);
    }
}
