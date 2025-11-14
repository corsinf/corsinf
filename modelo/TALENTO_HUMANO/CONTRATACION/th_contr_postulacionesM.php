<?php
require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_contr_postulacionesM extends BaseModel
{
    protected $tabla = 'th_contr_postulaciones';
    protected $primaryKey = 'th_posu_id AS _id';

    protected $camposPermitidos = [
        'th_pla_id AS id_plaza',
        'th_persona_id AS id_persona',
        'th_postulante_id AS id_postulante',
        'th_posu_fecha AS fecha_postulacion',
        'th_posu_estado_descrip AS estado_descripcion',
        'th_posu_estado AS estado',
        'th_posu_fuente AS fuente',
        'th_posu_curriculum_url AS curriculum_url',
        'th_posu_documentos_json AS documentos_json',
        'th_posu_score AS score',
        'th_posu_prioridad AS prioridad',
        'th_posu_observaciones AS observaciones',
        'th_posu_fecha_creacion AS fecha_creacion',
        'th_posu_fecha_modificacion AS fecha_modificacion'
    ];

    function listar_postulaciones_por_plaza($id_plaza)
{
    $sql = "
        SELECT
            -- Datos de la postulación
            po.th_posu_id AS _id,
            po.th_pla_id,
            po.th_persona_id,
            po.th_postulante_id,
            po.th_posu_fecha AS fecha_postulacion,
            po.th_posu_estado_descrip AS estado_descripcion,
            po.th_posu_estado,
            po.th_posu_fuente AS fuente,
            po.th_posu_curriculum_url,
            po.th_posu_documentos_json,
            po.th_posu_score AS score,
            po.th_posu_prioridad,
            po.th_posu_observaciones,
            po.th_posu_fecha_creacion,
            po.th_posu_fecha_modificacion,
            
            -- Datos de la persona (si existe)
            per.th_per_id AS persona_id,
            per.th_per_primer_nombre AS per_primer_nombre,
            per.th_per_segundo_nombre AS per_segundo_nombre,
            per.th_per_primer_apellido AS per_primer_apellido,
            per.th_per_segundo_apellido AS per_segundo_apellido,
            per.th_per_nombres_completos AS per_nombres_completos,
            per.th_per_cedula AS per_cedula,
            per.th_per_correo AS per_correo,
            per.th_per_telefono_1 AS per_telefono,
            per.th_per_foto_url AS per_foto_url,
            per.th_per_id_comunidad,
            per.th_per_tabla_union,
            
            -- Datos del postulante (si existe)
            pos.th_pos_id AS postulante_id,
            pos.th_pos_primer_nombre AS pos_primer_nombre,
            pos.th_pos_segundo_nombre AS pos_segundo_nombre,
            pos.th_pos_primer_apellido AS pos_primer_apellido,
            pos.th_pos_segundo_apellido AS pos_segundo_apellido,
            pos.th_pos_cedula AS pos_cedula,
            pos.th_pos_correo AS pos_correo,
            pos.th_pos_telefono_1 AS pos_telefono,
            pos.th_pos_foto_url AS pos_foto_url,
            pos.th_pos_contratado,
            
            -- Campo calculado: nombre completo del candidato
            CASE 
                WHEN per.th_per_id IS NOT NULL THEN 
                    COALESCE(per.th_per_nombres_completos, 
                        CONCAT(
                            RTRIM(COALESCE(per.th_per_primer_nombre, '')), ' ',
                            RTRIM(COALESCE(per.th_per_segundo_nombre, '')), ' ',
                            RTRIM(COALESCE(per.th_per_primer_apellido, '')), ' ',
                            RTRIM(COALESCE(per.th_per_segundo_apellido, ''))
                        )
                    )
                WHEN pos.th_pos_id IS NOT NULL THEN 
                    CONCAT(
                        RTRIM(COALESCE(pos.th_pos_primer_nombre, '')), ' ',
                        RTRIM(COALESCE(pos.th_pos_segundo_nombre, '')), ' ',
                        RTRIM(COALESCE(pos.th_pos_primer_apellido, '')), ' ',
                        RTRIM(COALESCE(pos.th_pos_segundo_apellido, ''))
                    )
                ELSE 'Sin nombre registrado'
            END AS nombre_completo,
            
            -- Campo calculado: tipo de candidato
            CASE 
                WHEN per.th_per_id IS NOT NULL AND per.th_per_id_comunidad IS NOT NULL AND per.th_per_tabla_union IS NOT NULL THEN 'Empleado Contratado'
                WHEN per.th_per_id IS NOT NULL THEN 'Empleado Interno'
                WHEN pos.th_pos_id IS NOT NULL AND pos.th_pos_contratado = 1 THEN 'Postulante Contratado'
                WHEN pos.th_pos_id IS NOT NULL THEN 'Postulante Externo'
                ELSE 'Desconocido'
            END AS tipo_candidato,
            
            -- Campo calculado: origen
            CASE 
                WHEN per.th_per_id IS NOT NULL THEN 'Interno'
                WHEN pos.th_pos_id IS NOT NULL THEN 'Externo'
                ELSE 'N/A'
            END AS origen,
            
            -- Campo calculado: cédula (de cualquier fuente)
            COALESCE(per.th_per_cedula, pos.th_pos_cedula, 'Sin cédula') AS cedula,
            
            -- Campo calculado: correo (de cualquier fuente)
            COALESCE(per.th_per_correo, pos.th_pos_correo, 'Sin correo') AS correo,
            
            -- Campo calculado: teléfono (de cualquier fuente)
            COALESCE(per.th_per_telefono_1, pos.th_pos_telefono_1, 'Sin teléfono') AS telefono,
            
            -- Campo calculado: foto (de cualquier fuente)
            COALESCE(per.th_per_foto_url, pos.th_pos_foto_url, '') AS foto_url,
            
            -- Campo calculado: estado de contratación
            CASE 
                WHEN per.th_per_id_comunidad IS NOT NULL AND per.th_per_tabla_union IS NOT NULL THEN 1
                WHEN pos.th_pos_contratado = 1 THEN 1
                ELSE 0
            END AS esta_contratado

        FROM th_contr_postulaciones po
        LEFT JOIN th_personas per ON po.th_persona_id = per.th_per_id
        LEFT JOIN th_postulantes pos ON po.th_postulante_id = pos.th_pos_id
        WHERE po.th_pla_id = '$id_plaza' 
          AND po.th_posu_estado = 1
        ORDER BY po.th_posu_fecha DESC;
    ";
    
    $datos = $this->db->datos($sql);
    return $datos;
}

function listar_postulaciones()
{
    $sql = "
        SELECT
            -- Datos de la postulación
            po.th_posu_id AS _id,
            po.th_pla_id,
            po.th_persona_id,
            po.th_postulante_id,
            po.th_posu_fecha AS fecha_postulacion,
            po.th_posu_estado_descrip AS estado_descripcion,
            po.th_posu_estado,
            po.th_posu_fuente AS fuente,
            po.th_posu_curriculum_url,
            po.th_posu_documentos_json,
            po.th_posu_score AS score,
            po.th_posu_prioridad,
            po.th_posu_observaciones,
            po.th_posu_fecha_creacion,
            po.th_posu_fecha_modificacion,
            
            -- Datos de la plaza
            pla.th_pla_titulo AS plaza_titulo,
            pla.th_pla_tipo AS plaza_tipo,
            pla.th_pla_id AS plaza_id,
            
            -- Datos de la persona (si existe)
            per.th_per_id AS persona_id,
            per.th_per_primer_nombre AS per_primer_nombre,
            per.th_per_segundo_nombre AS per_segundo_nombre,
            per.th_per_primer_apellido AS per_primer_apellido,
            per.th_per_segundo_apellido AS per_segundo_apellido,
            per.th_per_nombres_completos AS per_nombres_completos,
            per.th_per_cedula AS per_cedula,
            per.th_per_correo AS per_correo,
            per.th_per_telefono_1 AS per_telefono,
            per.th_per_foto_url AS per_foto_url,
            per.th_per_id_comunidad,
            per.th_per_tabla_union,
            
            -- Datos del postulante (si existe)
            pos.th_pos_id AS postulante_id,
            pos.th_pos_primer_nombre AS pos_primer_nombre,
            pos.th_pos_segundo_nombre AS pos_segundo_nombre,
            pos.th_pos_primer_apellido AS pos_primer_apellido,
            pos.th_pos_segundo_apellido AS pos_segundo_apellido,
            pos.th_pos_cedula AS pos_cedula,
            pos.th_pos_correo AS pos_correo,
            pos.th_pos_telefono_1 AS pos_telefono,
            pos.th_pos_foto_url AS pos_foto_url,
            pos.th_pos_contratado,
            
            -- Campo calculado: nombre completo del candidato
            CASE 
                WHEN per.th_per_id IS NOT NULL THEN 
                    COALESCE(per.th_per_nombres_completos, 
                        CONCAT(
                            RTRIM(COALESCE(per.th_per_primer_nombre, '')), ' ',
                            RTRIM(COALESCE(per.th_per_segundo_nombre, '')), ' ',
                            RTRIM(COALESCE(per.th_per_primer_apellido, '')), ' ',
                            RTRIM(COALESCE(per.th_per_segundo_apellido, ''))
                        )
                    )
                WHEN pos.th_pos_id IS NOT NULL THEN 
                    CONCAT(
                        RTRIM(COALESCE(pos.th_pos_primer_nombre, '')), ' ',
                        RTRIM(COALESCE(pos.th_pos_segundo_nombre, '')), ' ',
                        RTRIM(COALESCE(pos.th_pos_primer_apellido, '')), ' ',
                        RTRIM(COALESCE(pos.th_pos_segundo_apellido, ''))
                    )
                ELSE 'Sin nombre registrado'
            END AS nombre_completo,
            
            -- Campo calculado: tipo de candidato
            CASE 
                WHEN per.th_per_id IS NOT NULL AND per.th_per_id_comunidad IS NOT NULL AND per.th_per_tabla_union IS NOT NULL THEN 'Empleado Contratado'
                WHEN per.th_per_id IS NOT NULL THEN 'Empleado Interno'
                WHEN pos.th_pos_id IS NOT NULL AND pos.th_pos_contratado = 1 THEN 'Postulante Contratado'
                WHEN pos.th_pos_id IS NOT NULL THEN 'Postulante Externo'
                ELSE 'Desconocido'
            END AS tipo_candidato,
            
            -- Campo calculado: origen
            CASE 
                WHEN per.th_per_id IS NOT NULL THEN 'Interno'
                WHEN pos.th_pos_id IS NOT NULL THEN 'Externo'
                ELSE 'N/A'
            END AS origen,
            
            -- Campo calculado: cédula (de cualquier fuente)
            COALESCE(per.th_per_cedula, pos.th_pos_cedula, 'Sin cédula') AS cedula,
            
            -- Campo calculado: correo (de cualquier fuente)
            COALESCE(per.th_per_correo, pos.th_pos_correo, 'Sin correo') AS correo,
            
            -- Campo calculado: teléfono (de cualquier fuente)
            COALESCE(per.th_per_telefono_1, pos.th_pos_telefono_1, 'Sin teléfono') AS telefono,
            
            -- Campo calculado: foto (de cualquier fuente)
            COALESCE(per.th_per_foto_url, pos.th_pos_foto_url, '') AS foto_url,
            
            -- Campo calculado: estado de contratación
            CASE 
                WHEN per.th_per_id_comunidad IS NOT NULL AND per.th_per_tabla_union IS NOT NULL THEN 1
                WHEN pos.th_pos_contratado = 1 THEN 1
                ELSE 0
            END AS esta_contratado

        FROM th_contr_postulaciones po
        LEFT JOIN th_contr_plazas pla ON po.th_pla_id = pla.th_pla_id
        LEFT JOIN th_personas per ON po.th_persona_id = per.th_per_id
        LEFT JOIN th_postulantes pos ON po.th_postulante_id = pos.th_pos_id
        WHERE po.th_posu_estado = 1
        ORDER BY po.th_posu_fecha DESC;
    ";
    
    $datos = $this->db->datos($sql);
    return $datos;
}
}
?>