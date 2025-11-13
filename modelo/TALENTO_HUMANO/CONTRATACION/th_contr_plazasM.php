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
            -- Campos del horario asociado
            h.th_hor_id AS hor_id,
            h.th_hor_nombre AS hor_nombre,
            h.th_hor_tipo AS hor_tipo,
            h.th_hor_ciclos AS hor_ciclos,
            h.th_hor_inicio AS hor_inicio,
            h.th_hor_estado AS hor_estado,
            h.th_hor_fecha_creacion AS hor_fecha_creacion,
            h.th_hor_fecha_modificacion AS hor_fecha_modificacion
        FROM th_contr_plazas p
        LEFT JOIN th_horarios h ON p.th_pla_jornada_id = h.th_hor_id
        WHERE p.th_pla_id = '$id_plaza' AND p.th_pla_estado = 1
        ORDER BY p.th_pla_fecha_creacion DESC;
    ";
    $datos = $this->db->datos($sql);
    return $datos;
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
}
