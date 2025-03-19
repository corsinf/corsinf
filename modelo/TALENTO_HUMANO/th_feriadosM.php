<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_feriadosM extends BaseModel
{
    protected $tabla = 'th_feriados';
    protected $primaryKey = 'th_fer_id AS _id';

    protected $camposPermitidos = [
        'th_fer_fecha_inicio_feriado AS fecha_inicio_feriado',
        'th_fer_nombre AS nombre',
        'th_fer_dias AS dias',
        'th_fer_fecha_creacion AS fecha_creacion',
        'th_fer_fecha_modificacion AS fecha_modificacion',
        'th_fer_estado AS estado',
        'id_usuario AS id_usuario',
    ];
}
