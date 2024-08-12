<?php

require_once 'BaseModel.php';

class asistencias_pasantesM extends BaseModel
{
    protected $tabla = 'asistencias_pasantes';
    protected $primaryKey = 'pas_id';

    protected $camposPermitidos = [
        'pas_usu_id',
        'pas_nombre',
        'pas_hora_llegada',
        'pas_hora_salida',
        'pas_horas_total',
        'pas_observacion_pasante',
        'pas_observacion_tutor',
        'pas_usu_id_tutor',
        'pas_tutor_estado',
        'pas_estado',
        'pas_fecha_creacion',
        // 'pas_fecha_modficacion',
    ];
}
