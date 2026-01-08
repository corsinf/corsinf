<?php

require_once(dirname(__DIR__) . '/GENERAL/BaseModel.php');

class th_log_auditoriasM extends BaseModel
{
    protected $tabla = 'LOGS_AUDITORIA';
    protected $primaryKey = 'log_id';

    protected $camposPermitidos = [
        'usuario_id',
        'usuario_nombre',
        'usuario_no_concurrente',
        'accion',
        'descripcion',
        'modulo',
        'menu',
        'controlador',
        'metodo',
        'url_solicitud',
        'tabla_afectada',
        'registro_id',
        'datos_antes',
        'datos_despues',
        'ip_address',
        'user_agent',
        'fecha_creacion',
    ];

   
}
