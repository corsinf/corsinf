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
    if ($pla_id <= 0) return array();

    $sql = "
    SELECT
      p.*,
      (SELECT
         STUFF((
           SELECT ', ' + ISNULL(c.th_car_nombre,'<sin nombre>')
                        + ' x' + COALESCE(CONVERT(VARCHAR(10), pc2.th_pc_cantidad),'0')
                        + ISNULL(' (' + CONVERT(VARCHAR(50), pc2.th_pc_salario_ofertado) + ')','')
           FROM th_contr_plaza_cargo pc2
           LEFT JOIN th_contr_cargos c ON c.th_car_id = pc2.th_car_id
           WHERE pc2.th_pla_id = p.th_pla_id AND pc2.th_pc_estado = 1
           FOR XML PATH(''), TYPE).value('.','NVARCHAR(MAX)')
         ,1,2,'')
      ) AS cargos_resumen,
      (SELECT
         STUFF((
           SELECT ', ' + ISNULL(req.th_req_descripcion,'<sin descripcion>')
                        + CASE WHEN req.th_req_obligatorio = 1 THEN ' (Obligatorio)' ELSE '' END
           FROM th_contr_plaza_requisitos pr2
           LEFT JOIN th_contr_requisitos req ON req.th_req_id = pr2.th_req_id
           WHERE pr2.th_pla_id = p.th_pla_id
           FOR XML PATH(''), TYPE).value('.','NVARCHAR(MAX)')
         ,1,2,'')
      ) AS requisitos_resumen,
      (SELECT
         STUFF((
           SELECT ' -> ' + CONVERT(VARCHAR(10), et.th_etapa_orden) + '. ' + ISNULL(et.th_etapa_nombre,'<sin nombre>')
           FROM th_contr_plaza_etapas pe2
           LEFT JOIN th_contr_etapas_proceso et ON et.th_etapa_id = pe2.th_eta_id
           WHERE pe2.th_pla_id = p.th_pla_id AND (pe2.th_pla_eta_estado = 1 OR pe2.th_pla_eta_estado IS NULL)
           ORDER BY et.th_etapa_orden
           FOR XML PATH(''), TYPE).value('.','NVARCHAR(MAX)')
         ,1,4,'')
      ) AS etapas_resumen
    FROM th_contr_plazas p
    WHERE p.th_pla_id = {$pla_id}
      AND p.th_pla_estado = 1
    ";

    // Si tu db->datos() soporta un flag para NO transformar esquemas, úsalo:
    // return $this->db->datos($sql, true);

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