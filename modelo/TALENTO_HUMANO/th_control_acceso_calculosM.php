<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_control_acceso_calculosM extends BaseModel
{
    protected $tabla = 'th_control_acceso_calculos';
    protected $primaryKey = 'th_asi_id AS _id';

    protected $camposPermitidos = [
        //Usados en reporte
        'th_asi_apellidos AS apellidos',
        'th_asi_nombres AS nombres',
        'th_asi_empleado AS empleado',
        'th_asi_cedula AS cedula',
        'th_asi_correo_institucional AS correo_institucional',
        'th_asi_fecha AS fecha',
        'th_asi_dia AS dia',
        'th_asi_departamento AS departamento',
        'th_asi_horario_contrato AS horario_contrato',
        'th_asi_turno_nombre AS turno_nombre',
        'th_asi_entrada_hora_inicio_turno AS entrada_hora_inicio_turno',
        'th_asi_entrada_hora_fin_turno AS entrada_hora_fin_turno',
        'th_asi_regentrada AS regentrada',
        'th_asi_hora_entrada AS hora_entrada',
        'th_asi_hora_ajustada AS hora_ajustada',
        'th_asi_atrasos AS atrasos',
        'th_asi_ausente AS ausente',
        'th_asi_salida_hora_inicio_turno AS salida_hora_inicio_turno',
        'th_asi_salida_hora_fin_turno AS salida_hora_fin_turno',
        'th_asi_regsalida AS regsalida',
        'th_asi_hora_salida AS hora_salida',
        'th_asi_salidas_temprano AS salidas_temprano',
        'th_asi_horas_suplementarias AS horas_suplementarias',
        'th_asi_horas_extraordinarias AS horas_extraordinarias',

        //Faltan aumentar
        'th_asi_fecha_inicio_programacion AS fecha_inicio_programacion',
        'th_asi_fecha_fin_programacion AS fecha_fin_programacion',
        'th_asi_prioridad_programacion AS prioridad_programacion',
        'th_asi_salida_marcacion_str AS salida_marcacion_str',
        'th_asi_salida_ausente AS salida_ausente',
        'th_asi_usa_descanso_formal AS usa_descanso_formal',
        'th_asi_descanso_inicio AS descanso_inicio',
        'th_asi_descanso_fin AS descanso_fin',
        'th_asi_reg_inicio_descanso AS reg_inicio_descanso',
        'th_asi_reg_fin_descanso AS reg_fin_descanso',
        'th_asi_descanso_simple AS descanso_simple',
        'th_asi_minutos_descanso_simple AS minutos_descanso_simple',
        'th_asi_minutos_descanso_calculado AS minutos_descanso_calculado',
        'th_asi_dia_justificado AS dia_justificado',
        'th_asi_motivo_justificacion AS motivo_justificacion',
        'th_asi_inicio_justificacion AS inicio_justificacion',
        'th_asi_fin_justificacion AS fin_justificacion',
        'th_asi_horas_justificadas AS horas_justificadas',
        'th_asi_justificacion_es_rango AS justificacion_es_rango',
        'th_asi_justificacion_asignado_a AS justificacion_asignado_a',
        'th_asi_tipo_justificacion_aplicada AS tipo_justificacion_aplicada',
        'th_asi_minutos_justificados_calculado AS minutos_justificados_calculado',
        'th_asi_dias_trabajados AS dias_trabajados',
        'th_asi_cumple_jornada AS cumple_jornada',
        'th_asi_horas_faltantes AS horas_faltantes',
        'th_asi_horas_excedentes AS horas_excedentes',
        'th_asi_horas_trabajo_hora AS horas_trabajo_hora',
        'th_asi_tiempo_entrada_salida AS tiempo_entrada_salida',
        'th_asi_tiempo_post_descanso AS tiempo_post_descanso',
        'th_asi_tiempo_post_justificacion AS tiempo_post_justificacion',
        'th_asi_horas_trabajadas_finales AS horas_trabajadas_finales',
        'th_asi_es_feriado AS es_feriado',
        'th_asi_trabajo_en_feriado AS trabajo_en_feriado',
        'th_asi_trabajo_con_justificacion AS trabajo_con_justificacion',
        'th_asi_calcula_horas_extra AS calcula_horas_extra',
        'th_asi_rango_suplementarias AS rango_suplementarias',
        'th_asi_rango_extras AS rango_extras',
        'th_asi_sin_turno AS sin_turno',
        'th_per_id AS per_id'
    ];

    
    function listar_asistencia_por_fecha_departamento(
    $fecha_inicio,
    $fecha_fin,
    $tipo_busqueda = 'departamento',
    $departamento = '',
    $persona = '',
    $tipo_ordenamiento = 'sin_ordenar'
) {
    // Base de la consulta
    $sql = "SELECT 
        th_asi_id AS _id,
        th_asi_apellidos AS apellidos,
        th_asi_nombres AS nombres,
        th_asi_empleado AS empleado,
        th_asi_cedula AS cedula,
        th_asi_correo_institucional AS correo_institucional,
        th_asi_departamento AS departamento,
        th_asi_dia AS dia,
        th_asi_fecha AS fecha,
        th_asi_horario_contrato AS horario_contrato,
        th_asi_turno_nombre AS turno_nombre,
        th_asi_entrada_hora_inicio_turno AS entrada_hora_inicio_turno,
        th_asi_entrada_hora_fin_turno AS entrada_hora_fin_turno,
        th_asi_regentrada AS regentrada,
        th_asi_hora_entrada AS hora_entrada,
        th_asi_hora_ajustada AS hora_ajustada,
        th_asi_atrasos AS atrasos,
        th_asi_ausente AS ausente,
        th_asi_salida_hora_inicio_turno AS salida_hora_inicio_turno,
        th_asi_salida_hora_fin_turno AS salida_hora_fin_turno,
        th_asi_regsalida AS regsalida,
        th_asi_hora_salida AS hora_salida,
        th_asi_salidas_temprano AS salidas_temprano,
        th_asi_dias_trabajados AS dias_trabajados,
        th_asi_cumple_jornada AS cumple_jornada,
        th_asi_horas_faltantes AS horas_faltantes,
        th_asi_horas_excedentes AS horas_excedentes,
        th_asi_salidas_temprano AS salidastemprano,
        th_asi_horas_suplementarias AS horas_suplementarias,
        th_asi_horas_extraordinarias AS horas_extraordinarias
    FROM 
        th_control_acceso_calculos
    WHERE 
        th_asi_fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";

    // Aplicar filtros según el tipo de búsqueda
    if ($tipo_busqueda === 'departamento') {
        // Filtrar por departamento si está especificado
        if (!empty($departamento) && $departamento !== 'todos') {
            $sql .= " AND th_asi_departamento = '$departamento'";
        }
        
        // Filtrar por persona si está especificada (independiente del departamento)
        if (!empty($persona)) {
            $sql .= " AND th_per_id = '$persona'"; // O el campo que identifique a la persona
        }
        
    } elseif ($tipo_busqueda === 'persona') {
        // Búsqueda directa por persona
        if (!empty($persona)) {
            $sql .= " AND th_per_id = '$persona'"; // O el campo que identifique a la persona
        }
    }

    // Agregar ordenamiento según el tipo seleccionado
    if ($tipo_ordenamiento === 'ordenado') {
        $sql .= " ORDER BY 
            th_asi_apellidos ASC, 
            th_asi_nombres ASC, 
            th_asi_fecha ASC";
    } else {
        $sql .= " ORDER BY 
            th_asi_fecha ASC";
    }

    $datos = $this->db->datos($sql);
    return $datos;
}
}
