<?php

require_once(dirname(__DIR__, 2) . '/BaseModel.php');

class th_pos_contacto_emergenciaM extends BaseModel
{
    protected $tabla = 'th_contacto_emergencia';
    protected $primaryKey = 'th_coem_id AS _id';

    protected $camposPermitidos = [
        'th_pos_id',
        'th_coem_nombre_emergencia',
        'th_coem_telefono_emergencia',
        'th_coem_fecha_creacion',
    ];
}