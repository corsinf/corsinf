<?php

require_once(dirname(__DIR__, 2) . '/BaseModel.php');

class th_experiencia_laboralM extends BaseModel
{
    protected $tabla = 'th_experiencia_laboral';
    protected $primaryKey = 'th_expl_id AS _id';

    protected $camposPermitidos = [
        'th_expl_nombre_empresa',
        'th_expl_cargos_ocupados',
        'th_expl_fecha_inicio_experiencia',
        'th_expl_fecha_fin_experiencia',
        'th_expl_cbx_fecha_fin_experiencia',
        'th_expl_responsabilidades_logros',
        'th_expl_estado',
        'th_expl_fecha_creacion',
        'th_expl_fecha_modificacion',
    ];
}