<?php

require_once 'BaseModel.php';

class th_dispositivosM extends BaseModel
{
    protected $tabla = 'th_dispositivos';
    protected $primaryKey = 'th_dis_id AS _id';

    protected $camposPermitidos = [
        'th_dis_nombre AS nombre', 
        'th_dis_host AS host', 
        'th_dis_port', 
        'th_dis_ssl', 
        'th_dis_usuario', 
        'th_dis_pass', 
        'th_dis_modelo', 
        'th_dis_beep', 
        'th_dis_gateway_mode', 
        'th_dis_leds', 
        'th_dis_anti_pass_back', 
        'th_dis_diario_reset', 
        'th_dis_vehiculo_control', 
        'th_dis_alarma_relay', 
        'th_dis_urn', 
        'th_dis_serial', 
        'th_dis_version', 
        'th_dis_camara', 
        'th_dis_ultima_fecha', 
        'th_dis_estado_dis', 
        'th_dis_contador_reset', 
        'th_dis_lenguaje', 
        'th_dis_ultimo_nsr', 
        'th_dis_modo_visitante', 
        'th_dis_id_modo_indet', 
        'th_dis_estado', 
        'th_dis_fecha_creacion', 
        'th_dis_fecha_modificacion'
    ];
}
