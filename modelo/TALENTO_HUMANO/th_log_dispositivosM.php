<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_log_dispositivosM extends BaseModel
{
    protected $tabla = 'th_log_dispositivos_masivos';
    protected $primaryKey = 'id AS _id';

    protected $camposPermitidos = [
        'LOG_DEVICE AS data',
        'estado_procesado AS estado',
    ];
}
