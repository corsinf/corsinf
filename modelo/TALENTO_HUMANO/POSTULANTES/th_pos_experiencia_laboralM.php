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
    ];

    function listar_experiencia_laboral_postulante($th_pos_id)
    {
        $sql = "
        SELECT
            expl.th_expl_id,
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
            expl.th_expl_sueldo
        FROM th_pos_experiencia_laboral expl
        WHERE expl.th_pos_id = '$th_pos_id'
        AND expl.th_expl_estado = 1
    ";

        return $this->db->datos($sql);
    }
}
