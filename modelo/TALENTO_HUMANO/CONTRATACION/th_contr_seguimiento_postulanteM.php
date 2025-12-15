<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_contr_seguimiento_postulanteM extends BaseModel
{
    // Nombre de la tabla real
    protected $tabla = 'th_contr_seguimiento_postulante';

    // Clave primaria
    protected $primaryKey = 'th_seg_id AS _id';

    // Campos permitidos (con alias para que el front reciba nombres limpios)
    protected $camposPermitidos = [
        'th_posu_id AS postulante_id',
        'th_etapa_id AS etapa_id',
        'th_seg_fecha_programada AS fecha_programada',
        'th_seg_fecha_realizada AS fecha_realizada',
        'th_seg_calificacion AS calificacion',
        'th_seg_resultado AS resultado',
        'th_seg_responsable_persona_id AS responsable_id',
        'th_seg_observaciones AS observaciones',
        'th_seg_documentos_json AS documentos',
        'th_seg_estado AS estado',
        'th_seg_fecha_creacion AS fecha_creacion',
        'th_seg_fecha_modificacion AS fecha_modificacion'
    ];

   public function listar_seguimiento_postulante($id_plaza = '', $id_etapa = '', $id_pos = '')
{
    $sql = "
        SELECT 
            s.th_seg_id AS _id,
            s.th_posu_id,
            s.th_etapa_id,
            s.th_seg_fecha_programada,
            s.th_seg_fecha_realizada,
            s.th_seg_calificacion,
            s.th_seg_resultado,
            s.th_seg_responsable_persona_id,
            s.th_seg_observaciones,
            s.th_seg_documentos_json,
            s.th_seg_estado,
            s.th_seg_fecha_creacion,
            s.th_seg_fecha_modificacion,
            e.th_etapa_nombre,
            e.th_etapa_tipo,
            e.th_etapa_orden,
            e.th_etapa_obligatoria,
            e.th_pla_id,
            po.th_pla_id AS postulacion_plaza_id,
            po.th_persona_id,
            po.th_postulante_id,
            po.th_posu_fecha,
            po.th_posu_estado_descrip,
            po.th_posu_estado AS postulacion_estado,
            po.th_posu_fuente,
            po.th_posu_score,
            po.th_posu_prioridad,
            CASE 
                WHEN pos.th_pos_id IS NOT NULL THEN 
                    CONCAT(
                        ISNULL(pos.th_pos_primer_nombre, ''), ' ',
                        ISNULL(pos.th_pos_segundo_nombre, ''), ' ',
                        ISNULL(pos.th_pos_primer_apellido, ''), ' ',
                        ISNULL(pos.th_pos_segundo_apellido, '')
                    )
                ELSE 
                    CONCAT(
                        ISNULL(per.th_per_primer_nombre, ''), ' ',
                        ISNULL(per.th_per_segundo_nombre, ''), ' ',
                        ISNULL(per.th_per_primer_apellido, ''), ' ',
                        ISNULL(per.th_per_segundo_apellido, '')
                    )
            END AS nombre_completo,
            ISNULL(pos.th_pos_cedula, per.th_per_cedula) AS cedula,
            ISNULL(pos.th_pos_telefono_1, per.th_per_telefono_1) AS telefono,
            ISNULL(pos.th_pos_correo, per.th_per_correo) AS correo,
            ISNULL(pos.th_pos_telefono_2, per.th_per_telefono_2) AS telefono_2
        FROM th_contr_seguimiento_postulante s
        INNER JOIN th_contr_postulaciones po 
            ON s.th_posu_id = po.th_posu_id
        INNER JOIN th_contr_etapas_proceso e 
            ON s.th_etapa_id = e.th_etapa_id
        LEFT JOIN th_postulantes pos 
            ON po.th_postulante_id = pos.th_pos_id
        LEFT JOIN th_personas per 
            ON po.th_persona_id = per.th_per_id
        WHERE s.th_seg_estado = 1
    ";
    
    // Filtro por ID de etapa (opcional)
    if ($id_etapa !== '' && $id_etapa !== null) {
        $id = (int) $id_etapa;
        $sql .= " AND s.th_etapa_id = {$id}";
    }
    
    // Filtro por ID de plaza (opcional)
    if ($id_plaza !== '' && $id_plaza !== null) {
        $idp = (int) $id_plaza;
        $sql .= " AND e.th_pla_id = {$idp}";
    }
    
    // Filtro por ID de postulante (opcional)
    if ($id_pos !== '' && $id_pos !== null) {
        $idpos = (int) $id_pos;
        $sql .= " AND (pos.th_pos_id = {$idpos} OR per.th_per_id = {$idpos})";
    }
    
    $sql .= " ORDER BY e.th_etapa_orden ASC, s.th_seg_fecha_programada DESC, s.th_seg_fecha_creacion DESC";
    
    return $this->db->datos($sql);
}


public function listar_etapas_faltantes_postulantes($plaza_id, $postulantes_ids = [])
{
    if (empty($postulantes_ids)) {
        return [];
    }

    $plaza_id = intval($plaza_id);
    $ids = implode(',', array_map('intval', $postulantes_ids));

    $sql = "
        SELECT
            p.th_posu_id,
            e.th_etapa_id AS th_eta_id,
            e.th_etapa_nombre,
            e.th_etapa_orden
        FROM th_contr_postulaciones p
        INNER JOIN th_contr_plaza_etapas pe
            ON pe.th_pla_id = p.th_pla_id
            AND pe.th_pla_eta_estado = 1
        INNER JOIN th_contr_etapas_proceso e
            ON e.th_etapa_id = pe.th_eta_id
        LEFT JOIN th_contr_seguimiento_postulante sp
            ON sp.th_posu_id = p.th_posu_id
            AND sp.th_etapa_id = e.th_etapa_id
            AND sp.th_seg_estado = 1
        WHERE p.th_pla_id = $plaza_id
          AND p.th_posu_id IN ($ids)
          AND sp.th_seg_id IS NULL
        ORDER BY p.th_posu_id, e.th_etapa_orden
    ";

    return $this->db->datos($sql);
}



public function listar_seguimiento_postulante_plaza($id_plaza = '', $id_etapa = '', $id_pos = '')
{
    $sql = "
        SELECT 
            s.th_seg_id AS _id,
            s.th_posu_id,
            s.th_etapa_id,
            s.th_seg_fecha_programada,
            s.th_seg_fecha_realizada,
            s.th_seg_calificacion,
            s.th_seg_resultado,
            s.th_seg_responsable_persona_id,
            s.th_seg_observaciones,
            s.th_seg_documentos_json,
            s.th_seg_estado,
            s.th_seg_fecha_creacion,
            s.th_seg_fecha_modificacion,
            e.th_etapa_nombre,
            e.th_etapa_tipo,
            e.th_etapa_orden,
            e.th_etapa_obligatoria,
            e.th_etapa_descripcion,
            po.th_pla_id AS postulacion_plaza_id,
            po.th_pla_id,
            po.th_posu_id AS postulacion_id,
            po.th_persona_id,
            po.th_postulante_id,
            po.th_posu_fecha,
            po.th_posu_estado_descrip,
            po.th_posu_estado AS postulacion_estado,
            po.th_posu_fuente,
            po.th_posu_score,
            po.th_posu_prioridad,
            CASE 
                WHEN pos.th_pos_id IS NOT NULL THEN 
                    LTRIM(RTRIM(
                        ISNULL(pos.th_pos_primer_nombre, '') + ' ' +
                        ISNULL(pos.th_pos_segundo_nombre, '') + ' ' +
                        ISNULL(pos.th_pos_primer_apellido, '') + ' ' +
                        ISNULL(pos.th_pos_segundo_apellido, '')
                    ))
                ELSE 
                    LTRIM(RTRIM(
                        ISNULL(per.th_per_primer_nombre, '') + ' ' +
                        ISNULL(per.th_per_segundo_nombre, '') + ' ' +
                        ISNULL(per.th_per_primer_apellido, '') + ' ' +
                        ISNULL(per.th_per_segundo_apellido, '')
                    ))
            END AS nombre_completo,
            ISNULL(pos.th_pos_cedula, per.th_per_cedula) AS cedula,
            ISNULL(pos.th_pos_telefono_1, per.th_per_telefono_1) AS telefono,
            ISNULL(pos.th_pos_correo, per.th_per_correo) AS correo,
            ISNULL(pos.th_pos_telefono_2, per.th_per_telefono_2) AS telefono_2,
            per.th_per_foto_url AS foto_url,
            CASE 
                WHEN pos.th_pos_id IS NOT NULL THEN 'Postulante Externo'
                WHEN per.th_per_id IS NOT NULL THEN 'Empleado Interno'
                ELSE 'Sin Clasificar'
            END AS tipo_candidato
        FROM th_contr_seguimiento_postulante s
        INNER JOIN th_contr_postulaciones po 
            ON s.th_posu_id = po.th_posu_id
        INNER JOIN th_contr_etapas_proceso e 
            ON s.th_etapa_id = e.th_etapa_id
        LEFT JOIN th_postulantes pos 
            ON po.th_postulante_id = pos.th_pos_id
        LEFT JOIN th_personas per 
            ON po.th_persona_id = per.th_per_id
        WHERE s.th_seg_estado = 1
    ";

    if ($id_plaza !== '' && $id_plaza !== null) {
        $idp = (int) $id_plaza;
        $sql .= " AND po.th_pla_id = {$idp}";
    }

    if ($id_etapa !== '' && $id_etapa !== null) {
        $id = (int) $id_etapa;
        $sql .= " AND s.th_etapa_id = {$id}";
    }

    if ($id_pos !== '' && $id_pos !== null) {
        $idpos = (int) $id_pos;
        $sql .= " AND (pos.th_pos_id = {$idpos} OR per.th_per_id = {$idpos})";
    }

    $sql .= " 
        ORDER BY 
            e.th_etapa_orden ASC, 
            s.th_seg_fecha_programada DESC, 
            s.th_seg_fecha_creacion DESC
    ";

    return $this->db->datos($sql);
}


}