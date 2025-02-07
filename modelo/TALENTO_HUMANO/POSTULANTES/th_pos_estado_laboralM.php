<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_pos_estado_laboralM extends BaseModel
{
    protected $tabla = 'th_pos_estado_laboral';
    protected $primaryKey = 'th_est_id AS _id';

    protected $camposPermitidos = [
        'th_pos_id',        
        'th_est_estado_laboral',
        'th_est_fecha_contratacion',
        'th_est_fecha_salida',
        'th_est_fecha_creacion',
        'th_est_fecha_modificacion',
        'th_est_estado',
    ];
}
