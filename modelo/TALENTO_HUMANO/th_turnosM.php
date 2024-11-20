<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_turnosM extends BaseModel
{
    protected $tabla = 'th_turnos';
    protected $primaryKey = 'th_tur_id AS _id';

    protected $camposPermitidos = [
        'th_tur_nombre AS nombre',
        'th_tur_checkin_registro_inicio AS checkin_registro_inicio',
        'th_tur_hora_entrada AS hora_entrada',
        'th_tur_checkin_registro_fin AS checkin_registro_fin',
        'th_tur_limite_tardanza_in AS limite_tardanza_in',
        'th_tur_checkout_salida_inicio AS checkout_salida_inicio',
        'th_tur_hora_salida AS hora_salida',
        'th_tur_checkout_salida_fin AS checkout_salida_fin',
        'th_tur_limite_tardanza_out AS limite_tardanza_out',
        'th_tur_turno_nocturno AS turno_nocturno',
        'th_tur_valor_trabajar AS valor_trabajar',
        'th_tur_valor_hora_trabajar AS valor_hora_trabajar',
        'th_tur_valor_min_trabajar AS valor_min_trabajar',
        'th_tur_color AS color',
        'th_tur_estado AS estado',
        //'th_tur_fecha_creacion AS fecha_creacion',
        //'th_tur_fecha_modificacion AS fecha_modificacion',
        'th_tur_color AS color',
        'th_tur_descanso AS descanso',
        'th_tur_hora_descanso AS hora_descanso',
        
    ];
}
