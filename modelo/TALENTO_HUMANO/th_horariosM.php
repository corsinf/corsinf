<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_horariosM extends BaseModel
{
    protected $tabla = 'th_horarios';
    protected $primaryKey = 'th_hor_id AS _id';

    protected $camposPermitidos = [
        'th_hor_nombre AS nombre',
        'th_hor_tipo AS tipo',
        'th_hor_ciclos AS ciclos',
        'th_hor_inicio AS inicio',
        'th_hor_estado AS estado',
        'th_hor_fecha_creacion AS fecha_creacion',
        'th_hor_fecha_modificacion AS fecha_modificacion',
    ];
}
