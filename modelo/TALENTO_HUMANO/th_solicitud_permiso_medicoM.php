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
        'th_sol_per_med_tipo_enfermedad AS tipo_enfermedad',
        'th_sol_per_med_codigo_idg AS codigo_idg',
        'th_sol_per_med_presenta_cert_medico AS presenta_cert_medico',
        'th_sol_per_med_presenta_cert_asistencia AS presenta_cert_asistencia',
        'th_sol_per_med_motivo AS motivo',
        'th_sol_per_med_observaciones AS observaciones',
        
        // Fechas del departamento médico
        'th_sol_per_med_fecha AS fecha',
        'th_sol_per_med_desde AS desde',
        'th_sol_per_med_hasta AS hasta',
        'th_sol_per_med_nombre_medico AS nombre_medico',
        
        // Fechas del permiso (usuario)
        'th_sol_per_med_fecha_principal_permiso AS fecha_principal_permiso',
        'th_sol_per_med_fecha_desde_permiso AS fecha_desde_permiso',
        'th_sol_per_med_fecha_hasta_permiso AS fecha_hasta_permiso',
        'th_sol_per_med_total_dias AS total_dias_permiso',
        'th_sol_per_med_total_horas AS total_horas_permiso',
        
        'th_sol_per_med_tipo_calculo AS tipo_calculo',
        'th_sol_per_med_estado_solicitud AS estado_solicitud',
        'th_sol_per_med_ruta_solicitud AS ruta_solicitud',
        'th_sol_per_med_estado AS estado',
        'th_sol_per_med_fecha_creacion AS fecha_creacion',
        'th_sol_per_med_fecha_modificacion AS fecha_modificacion',
        'id_idg',
    ];


    public function obtener_detalle_completo_solicitud($id)
{
    $id = intval($id);
    $sql = "
        SELECT 
            sm.th_sol_per_med_id AS id_medico,
            sm.th_sol_per_id AS id_solicitud,
            sm.th_sol_per_med_reposo AS reposo,
            sm.th_sol_per_med_permiso_consulta AS permiso_consulta,
            sm.th_sol_per_med_tipo_enfermedad AS tipo_enfermedad,
            sm.id_idg AS id_idg,
            ISNULL(cie.codigo, 'Sin codigo') AS codigo_idg,
            ISNULL(cie.descripcion, 'SIN DIAGNÓSTICO EN CATÁLOGO') AS descripcion_idg,
            sm.th_sol_per_med_presenta_cert_medico AS presenta_cert_medico,
            sm.th_sol_per_med_presenta_cert_asistencia AS presenta_cert_asistencia,
            sm.th_sol_per_med_motivo AS motivo,
            sm.th_sol_per_med_observaciones AS observaciones,
            sm.th_sol_per_med_fecha AS fecha,
            sm.th_sol_per_med_desde AS desde,
            sm.th_sol_per_med_hasta AS hasta,
            sm.th_sol_per_med_nombre_medico AS nombre_medico,
            sm.th_sol_per_med_fecha_principal_permiso AS fecha_principal_permiso,
            sm.th_sol_per_med_fecha_desde_permiso AS fecha_desde_permiso,
            sm.th_sol_per_med_fecha_hasta_permiso AS fecha_hasta_permiso,
            sm.th_sol_per_med_total_dias AS total_dias_permiso,
            sm.th_sol_per_med_total_horas AS total_horas_permiso,
            sm.th_sol_per_med_tipo_calculo AS tipo_calculo,
            sm.th_sol_per_med_estado_solicitud AS estado_solicitud,
            sm.th_sol_per_med_ruta_solicitud AS ruta_solicitud
        FROM th_solicitud_permiso_medico sm
        LEFT JOIN sa_cat_cie_10 cie 
            ON sm.id_idg = cie.id
        WHERE sm.th_sol_per_med_id = {$id}";

    return $this->db->datos($sql);
}
}