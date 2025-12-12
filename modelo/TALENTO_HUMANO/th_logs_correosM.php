<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_logs_correosM extends BaseModel
{
    protected $tabla = 'th_logs_correos';
    protected $primaryKey = 'th_log_id AS _id';

    protected $camposPermitidos = [
        'th_log_correo_destino AS correo_destino',
        'th_log_asunto AS asunto',
        'th_log_detalle AS detalle',
        'id_usuario AS id_usuario',
        'th_log_enviado AS enviado',
        'th_log_estado AS estado',
        'th_log_fecha_creada AS fecha_creada',
        'th_log_fecha_modificada AS fecha_modificada',
    ];
}