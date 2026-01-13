<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_solicitud_permiso_medicoM extends BaseModel
{
    protected $tabla = 'th_solicitud_permiso_medico';
    protected $primaryKey = 'th_sol_per_med_id AS _id';

    protected $camposPermitidos = [
        'th_sol_per_med_id AS id_medico',
        'th_sol_per_id AS id_solicitud',
        'th_sol_per_med_reposo AS reposo',
        'th_sol_per_med_permiso_consulta AS permiso_consulta',
        'th_sol_per_med_codigo_idg AS codigo_idg',
        'th_sol_per_med_presenta_cert_medico AS presenta_cert_medico',
        'th_sol_per_med_presenta_cert_asistencia AS presenta_cert_asistencia',
        'th_sol_per_med_motivo AS motivo',
        'th_sol_per_med_observaciones AS observaciones',
        'th_sol_per_med_fecha AS fecha',
        'th_sol_per_med_desde AS desde',
        'th_sol_per_med_hasta AS hasta',
        'th_sol_per_med_nombre_medico AS nombre_medico',
        'th_sol_per_med_estado_solicitud AS estado_solicitud',
        'th_sol_per_med_estado AS estado',
        'th_sol_per_med_fecha_creacion AS fecha_creacion',
        'th_sol_per_med_fecha_modificacion AS fecha_modificacion',
    ];
}