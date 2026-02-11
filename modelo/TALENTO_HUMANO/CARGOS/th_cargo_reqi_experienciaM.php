<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cargo_reqi_experienciaM extends BaseModel
{
    protected $tabla = 'th_cargo_reqi_experiencia';

    protected $primaryKey = 'th_reqe_experiencia_id AS _id';

    protected $camposPermitidos = [
        'id_cargo',
        'th_reqe_anios',
        'th_reqe_estado AS estado',
        'th_reqe_fecha_creacion AS fecha_creacion',
        'th_reqe_fecha_modificacion AS fecha_modificacion'
    ];
}
