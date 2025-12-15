<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_contr_cargo_complianceM extends BaseModel
{
    // Nombre real de la tabla en la BD
    protected $tabla = 'th_contr_cargo_compliance';

    // Primary key con alias _id (consistente con tu BaseModel)
    protected $primaryKey = 'th_comp_id AS _id';

    // Campos permitidos para INSERT / UPDATE (alias amigables)
    protected $camposPermitidos = [
        'th_car_id AS car_id',
        'th_comp_porcentaje_completado AS porcentaje_completado',
        'th_comp_requisitos_totales AS requisitos_totales',
        'th_comp_requisitos_completados AS requisitos_completados',
        'th_comp_requisitos_faltantes AS requisitos_faltantes',
        'th_comp_ultima_revision AS ultima_revision',
        'th_comp_estado AS estado',
        'th_comp_observaciones AS observaciones',
        'th_comp_fecha_creacion AS fecha_creacion',
        'th_comp_fecha_modificacion AS fecha_modificacion'
    ];
}