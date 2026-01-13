<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_per_informacion_adicionalM extends BaseModel
{
    protected $tabla = 'th_per_informacion_adicional';
    protected $primaryKey = 'th_inf_adi_id AS _id';

    protected $camposPermitidos = [
        'th_inf_adi_id AS th_inf_adi_id',
        'th_per_id AS th_per_id',
        'th_inf_adi_tiempo_trabajo AS tiempo_trabajo',
        'th_inf_adi_remuneracion_promedio AS remuneracion_promedio',
        'th_inf_adi_estado AS estado',
        'th_inf_adi_fecha_creacion AS fecha_creacion',
        'th_inf_adi_fecha_modificacion AS fecha_modificacion',
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
            expl.th_expl_responsabilidades_logros,
            expl.th_expl_estado,
            expl.th_expl_fecha_creacion,
            expl.th_expl_fecha_modificacion,
            expl.th_expl_sueldo
        FROM th_pos_experiencia_laboral expl
        WHERE expl.th_pos_id = '$th_pos_id'
    ";

    return $this->db->datos($sql);
}

}
