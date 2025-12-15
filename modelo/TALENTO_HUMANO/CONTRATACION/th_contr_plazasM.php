<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_contr_plazasM extends BaseModel
{
    // Nombre real de la tabla en BD
    protected $tabla = 'th_contr_plazas';

    // Clave primaria (se expone como _id, igual que en tus otros modelos)
    protected $primaryKey = 'th_pla_id AS _id';

    // Campos que puedes insertar/actualizar
    protected $camposPermitidos = [
        'th_pla_titulo',
        'th_pla_descripcion',
        'th_pla_tipo',
        'th_pla_num_vacantes',
        'th_pla_fecha_publicacion',
        'th_pla_fecha_cierre',
        'th_pla_jornada_id',
        'th_pla_salario_min',
        'th_pla_salario_max',
        'th_pla_tiempo_contrato',
        'th_pla_prioridad_interna',
        'th_pla_requiere_documentos',
        'th_pla_responsable_persona_id',
        'th_pla_observaciones',
        'th_pla_estado',
        'th_pla_fecha_creacion',
        'th_pla_fecha_modificacion',
    ];


    function listar_plazas_con_horarios($id_plaza)
{
    $sql = "
        SELECT
            p.th_pla_id AS _id,
            p.th_pla_titulo,
            p.th_pla_descripcion,
            p.th_pla_tipo,
            p.th_pla_num_vacantes,
            p.th_pla_fecha_publicacion,
            p.th_pla_fecha_cierre,
            p.th_pla_jornada_id,
            p.th_pla_salario_min,
            p.th_pla_salario_max,
            p.th_pla_tiempo_contrato,
            p.th_pla_prioridad_interna,
            p.th_pla_requiere_documentos,
            p.th_pla_responsable_persona_id,
            p.th_pla_observaciones,
            p.th_pla_estado,
            p.th_pla_fecha_creacion,
            p.th_pla_fecha_modificacion,
            p.th_pla_completado,
            per.th_per_id AS per_id,
            per.th_per_cedula AS per_cedula,
            CONCAT(
                per.th_per_primer_nombre, ' ',
                per.th_per_segundo_nombre, ' ',
                per.th_per_primer_apellido, ' ',
                per.th_per_segundo_apellido
            ) AS per_nombre_completo,
            h.th_hor_id AS hor_id,
            h.th_hor_nombre AS hor_nombre,
            h.th_hor_tipo AS hor_tipo,
            h.th_hor_ciclos AS hor_ciclos,
            h.th_hor_inicio AS hor_inicio,
            h.th_hor_estado AS hor_estado,
            h.th_hor_fecha_creacion AS hor_fecha_creacion,
            h.th_hor_fecha_modificacion AS hor_fecha_modificacion
        FROM th_contr_plazas p
        LEFT JOIN th_horarios h 
            ON p.th_pla_jornada_id = h.th_hor_id
        LEFT JOIN th_personas per
            ON per.th_per_id = p.th_pla_responsable_persona_id
        WHERE p.th_pla_id = '$id_plaza'
          AND p.th_pla_estado = 1
        ORDER BY p.th_pla_fecha_creacion DESC;
    ";
    
    return $this->db->datos($sql);
}



    public function listar_plazas_no_asignadas()
{
    $sql = "
    SELECT p.*
    FROM th_contr_plazas p
    WHERE p.th_pla_estado = 1
      AND NOT EXISTS (
        SELECT 1
        FROM th_contr_plaza_cargo pc
        WHERE pc.th_pla_id = p.th_pla_id
          AND pc.th_pc_estado = 1
      )
    ORDER BY p.th_pla_titulo ASC
    ";

    return $this->db->datos($sql);
}



public function obtener_resumen_plaza($pla_id = 0)
{
    $pla_id = (int)$pla_id;
    $sql = "SELECT
  cp.th_pla_id,
  cp.th_pla_titulo,
  cp.th_pla_descripcion,
  cp.th_pla_tipo,
  cp.th_pla_fecha_publicacion,
  cp.th_pla_fecha_cierre,
  cp.th_pla_num_vacantes,
  cp.th_pla_salario_min,
  cp.th_pla_salario_max,
  cp.th_pla_observaciones,

  COALESCE(c.th_car_nombre, '') + '_' + COALESCE(CONVERT(VARCHAR(20), cpc.th_pc_salario_ofertado), '') AS cargo_resumen,
  COALESCE(STRING_AGG(
      COALESCE(cr.th_req_Descripcion, '') + '-' + COALESCE(cr.th_req_tipo, '') + '-' + COALESCE(CONVERT(VARCHAR(10), cr.th_req_obligatorio), ''),
      ', '
  ), '') AS requisitos_resumen,
  COALESCE(STRING_AGG(
      CONVERT(VARCHAR(10), cet.th_etapa_orden) + '. ' + COALESCE(cet.th_etapa_nombre, ''),
      '->'
  ), '') AS etapas_resumen 
FROM th_contr_plazas cp
 LEFT JOIN th_contr_plaza_cargo cpc ON cp.th_pla_id = cpc.th_pla_id
 LEFT JOIN th_contr_cargos c ON c.th_car_id = cpc.th_car_id
 LEFT JOIN th_contr_plaza_requisitos cpr ON cp.th_pla_id = cpr.th_pla_id
 LEFT JOIN th_contr_requisitos cr ON cpr.th_req_id = cr.th_req_id
 LEFT JOIN th_contr_plaza_etapas cpe ON cp.th_pla_id = cpe.th_pla_id
 LEFT JOIN th_contr_etapas_proceso cet ON cpe.th_eta_id = cet.th_etapa_id
 WHERE cp.th_pla_id = $pla_id
  AND cp.th_pla_estado = 1
 GROUP BY
  cp.th_pla_id, cp.th_pla_titulo, cp.th_pla_descripcion, cp.th_pla_tipo, cp.th_pla_num_vacantes,
  cp.th_pla_salario_min, cp.th_pla_salario_max, cp.th_pla_observaciones, cp.th_pla_fecha_publicacion,
  th_pla_fecha_cierre,
  cpc.th_pc_salario_ofertado, c.th_car_nombre";

    return $this->db->datos($sql);
}

public function listar_etapas_por_plaza($pla_id)
{
    $pla_id = intval($pla_id);

    $sql = "
        SELECT
            e.th_etapa_id,
            e.th_etapa_nombre,
            e.th_etapa_tipo,
            e.th_etapa_orden,
            e.th_etapa_obligatoria,
            e.th_etapa_descripcion,
            e.th_etapa_estado,
            e.th_etapa_fecha_creacion,
            pe.th_pla_eta_id,
            pe.th_pla_id,
            pe.th_eta_id,
            pe.th_pla_eta_estado,
            pe.th_pla_eta_fecha_creacion,
            pe.th_pla_eta_modificacion
        FROM th_contr_etapas_proceso e
        INNER JOIN th_contr_plaza_etapas pe
            ON pe.th_eta_id = e.th_etapa_id
        WHERE pe.th_pla_id = {$pla_id}
          AND pe.th_pla_eta_estado = 1     -- solo asignaciones activas
          AND e.th_etapa_estado = 1        -- solo etapas activas
        ORDER BY e.th_etapa_orden, e.th_etapa_nombre
    ";

    return $this->db->datos($sql);
}



}