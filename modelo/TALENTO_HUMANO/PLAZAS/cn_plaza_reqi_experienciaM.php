<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class cn_plaza_reqi_experienciaM extends BaseModel
{
    protected $tabla = 'cn_plaza_reqi_experiencia';

    protected $primaryKey = 'cn_reqe_experiencia_id AS _id';

    protected $camposPermitidos = [
        'cn_pla_id',
        'cn_reqe_anios',
        'cn_reqe_estado AS estado',
        'cn_reqe_fecha_creacion AS fecha_creacion',
        'cn_reqe_fecha_modificacion AS fecha_modificacion'
    ];
}