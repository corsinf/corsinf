<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_per_dotacionM extends BaseModel
{
    protected $tabla = 'th_per_dotacion';

    protected $primaryKey = 'th_dot_id AS _id';

    protected $camposPermitidos = [
        'th_per_id AS th_per_id',
        'th_dot_fecha_entrega AS th_dot_fecha_entrega',
        'th_dot_observacion AS th_dot_observacion',
        'id_usuario AS id_usuario',
        'th_dot_estado AS th_dot_estado',
        'th_dot_fecha_creacion AS th_dot_fecha_creacion',
        'th_dot_fecha_modificacion AS th_dot_fecha_modificacion'
    ];
}