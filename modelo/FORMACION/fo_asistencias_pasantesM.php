<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class fo_asistencias_pasantesM extends BaseModel
{
    protected $tabla = 'fo_asistencias_pasantes';
    protected $primaryKey = 'fo_pas_id AS _id';

    protected $camposPermitidos = [
        'fo_per_id AS id_persona',
        'fo_pas_hora_llegada AS hora_llegada',
        'fo_pas_hora_salida AS hora_salida',
        'fo_pas_horas_total AS horas_total',
        'fo_pas_observacion_pasante AS observacion_pasante',
        'fo_pas_observacion_tutor AS observacion_tutor',
        'fo_pas_usu_id_tutor AS usu_id_tutor',
        'fo_pas_tutor_estado AS tutor_estado',
        'fo_pas_estado AS estado',
        'fo_pas_fecha_creacion AS fecha_creacion',
        // 'fo_pas_fecha_modficacion',
    ];
}



