<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_dispositivosM extends BaseModel
{
    protected $tabla = 'th_dispositivos';
    protected $primaryKey = 'th_dis_id AS _id';

    protected $camposPermitidos = [
        'th_dis_nombre AS nombre',
        'th_dis_host AS host',
        'th_dis_port AS port',
        'th_dis_ssl AS ssl',
        'th_dis_usuario AS usuario',
        'th_dis_pass AS pass',
        'th_dis_modelo AS modelo',
        'th_dis_beep AS beep',
        'th_dis_gateway_mode AS gateway_mode',
        'th_dis_leds AS leds',
        'th_dis_anti_pass_back AS anti_pass_back',
        'th_dis_diario_reset AS diario_reset',
        'th_dis_vehiculo_control AS vehiculo_control',
        'th_dis_alarma_relay AS alarma_relay',
        'th_dis_urn AS urn',
        'th_dis_serial AS serial',
        'th_dis_version AS version',
        'th_dis_camara AS camara',
        'th_dis_ultima_fecha AS ultima_fecha',
        'th_dis_estado_dis AS estado_dis',
        'th_dis_contador_reset AS contador_reset',
        'th_dis_lenguaje AS lenguaje',
        'th_dis_ultimo_nsr AS ultimo_nsr',
        'th_dis_modo_visitante AS modo_visitante',
        'th_dis_id_modo_indet AS id_modo_indet',
        'th_dis_estado AS estado',
        'th_dis_fecha_creacion AS fecha_creacion',
        'th_dis_fecha_modificacion AS fecha_modificacion'
    ];
}
