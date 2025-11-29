<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_contr_cargo_aspectos_intrinsecosM extends BaseModel
{
    // Nombre real de la tabla
    protected $tabla = 'th_contr_cargo_aspectos_intrinsecos';

    // Clave primaria
    protected $primaryKey = 'th_carasp_id AS _id';

    // Campos permitidos para INSERT o UPDATE
    protected $camposPermitidos = [
        'th_car_id AS car_id',
        'th_carasp_nivel_cargo AS nivel_cargo',
        'th_carasp_subordinacion AS subordinacion',
        'th_carasp_supervision AS supervision',
        'th_carasp_comunicaciones_colaterales AS comunicaciones_colaterales',
        'th_carasp_estado AS estado',
        'th_carasp_fecha_creacion AS fecha_creacion',
        'th_carasp_fecha_modificacion AS fecha_modificacion'
    ];
}