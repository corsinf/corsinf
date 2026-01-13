<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_solicitud_permisoM extends BaseModel
{
    protected $tabla = 'th_solicitud_permiso';
    protected $primaryKey = 'th_sol_per_id AS _id';

    protected $camposPermitidos = [
        'th_sol_per_id AS id_solicitud',
        'th_per_id AS id',
        'th_sol_per_motivo AS motivo',
        'th_sol_per_detalle AS detalle',
        'th_sol_per_fam_hijos_adultos AS fam_hijos_adultos',
        'th_sol_per_maternidad_paternidad AS maternidad_paternidad',
        'th_sol_per_cert_nacido_vivo AS cert_nacido_vivo',
        'th_sol_per_enfermedad AS enfermedad',
        'th_sol_per_cita_medica AS cita_medica',
        'th_sol_per_cert_medico AS cert_medico',
        'th_sol_per_tipo_atencion AS tipo_atencion',
        'th_sol_per_lugar AS lugar',
        'th_sol_per_especialidad AS especialidad',
        'th_sol_per_medico AS medico',
        'th_sol_per_fecha_atencion AS fecha_atencion',
        'th_sol_per_hora_desde AS hora_desde',
        'th_sol_per_hora_hasta AS hora_hasta',
        'th_sol_per_fecha_desde AS fecha_desde',
        'th_sol_per_fecha_hasta AS fecha_hasta',
        'th_sol_per_total_horas AS total_horas',
        'th_sol_per_total_dias AS total_dias',
        'th_sol_per_estado AS estado',
        'th_sol_per_parentesco_fecha_nacimiento AS fecha_nacimiento',
        'th_sol_per_fecha_creacion AS fecha_creacion',
        'th_sol_per_fecha_modificacion AS fecha_modificacion',
    ];

    
function listar_personas_con_total_solicitudes($th_per_id = null)
{
    $filtro = "";

    if ($th_per_id !== null && $th_per_id !== '') {
        $th_per_id = intval($th_per_id);
        $filtro = " AND p.th_per_id = {$th_per_id}";
    }

    $sql = "
        SELECT
            p.th_per_id AS id,
            p.th_per_cedula AS cedula,
            p.th_per_telefono_1 AS telefono,

            LTRIM(RTRIM(
                ISNULL(p.th_per_primer_apellido, '') + ' ' +
                ISNULL(p.th_per_segundo_apellido, '') + ' ' +
                ISNULL(p.th_per_primer_nombre, '') + ' ' +
                ISNULL(p.th_per_segundo_nombre, '')
            )) AS nombre_completo,

            COUNT(DISTINCT sp.th_sol_per_id) AS total_solicitudes

        FROM th_solicitud_permiso sp

        INNER JOIN th_personas p
            ON sp.th_per_id = p.th_per_id

        LEFT JOIN th_solicitud_permiso_medico sm
            ON sm.th_sol_per_id = sp.th_sol_per_id
            AND sm.th_sol_per_med_estado = 1
            AND sm.th_sol_per_med_estado_solicitud IN (0,1)

        WHERE
            sp.th_sol_per_estado = 1
            {$filtro}

        GROUP BY
            p.th_per_id,
            p.th_per_cedula,
            p.th_per_telefono_1,
            p.th_per_primer_nombre,
            p.th_per_segundo_nombre,
            p.th_per_primer_apellido,
            p.th_per_segundo_apellido

        ORDER BY total_solicitudes DESC
    ";

    return $this->db->datos($sql);
}


function listar_solicitudes_persona_con_medico($th_per_id)
{
    $th_per_id = intval($th_per_id);

    $sql = "
        SELECT
            sp.th_sol_per_id                         AS id_solicitud,
            sp.th_per_id                             AS id_persona,
            sp.th_sol_per_motivo                     AS motivo,
            sp.th_sol_per_detalle                    AS detalle,
            sp.th_sol_per_fecha_desde                AS fecha_desde,
            sp.th_sol_per_fecha_hasta                AS fecha_hasta,
            sp.th_sol_per_total_dias                 AS total_dias,
            sp.th_sol_per_estado                     AS estado_solicitud,
            sp.th_sol_per_fecha_creacion             AS fecha_creacion,
            sm.th_sol_per_med_id                     AS id_solicitud_medica,
            sm.th_sol_per_med_estado_solicitud       AS estado_medico,
            sm.th_sol_per_med_nombre_medico          AS nombre_medico,
            CASE 
                WHEN sm.th_sol_per_med_id IS NULL THEN 0
                ELSE 1
            END AS tiene_revision_medica
        FROM th_solicitud_permiso sp

        LEFT JOIN th_solicitud_permiso_medico sm
            ON sm.th_sol_per_id = sp.th_sol_per_id
            AND sm.th_sol_per_med_estado = 1
        WHERE
            sp.th_sol_per_estado = 1
            AND sp.th_per_id = {$th_per_id}

        ORDER BY sp.th_sol_per_fecha_creacion DESC
    ";

    return $this->db->datos($sql);
}


function obtener_solicitudes_persona($th_sol_per_id = null)
{
    $where = "";

    if (!empty($th_sol_per_id)) {
        $th_sol_per_id = intval($th_sol_per_id);
        $where = " WHERE sp.th_sol_per_id = {$th_sol_per_id}";
    }

    $sql = "
        SELECT
            sp.th_sol_per_id AS _id,
            sp.th_per_id AS id_persona,
            p.th_per_cedula AS cedula,
            p.th_per_telefono_1 AS telefono,
            LTRIM(RTRIM(
                ISNULL(p.th_per_primer_nombre, '') + ' ' +
                ISNULL(p.th_per_segundo_nombre, '') + ' ' +
                ISNULL(p.th_per_primer_apellido, '') + ' ' +
                ISNULL(p.th_per_segundo_apellido, '')
            )) AS nombre_completo,
            sp.th_sol_per_motivo AS motivo,
            sp.th_sol_per_detalle AS detalle,
            sp.th_sol_per_fam_hijos_adultos AS fam_hijos_adultos,
            sp.th_sol_per_parentesco_fecha_nacimiento AS fecha_nacimiento,
            sp.th_sol_per_maternidad_paternidad AS maternidad_paternidad,
            sp.th_sol_per_cert_nacido_vivo AS cert_nacido_vivo,
            sp.th_sol_per_enfermedad AS enfermedad,
            sp.th_sol_per_cita_medica AS cita_medica,
            sp.th_sol_per_cert_medico AS cert_medico,
            sp.th_sol_per_tipo_atencion AS tipo_atencion,
            sp.th_sol_per_lugar AS lugar,
            sp.th_sol_per_especialidad AS especialidad,
            sp.th_sol_per_medico AS medico,
            sp.th_sol_per_fecha_atencion AS fecha_atencion,
            sp.th_sol_per_hora_desde AS hora_desde,
            sp.th_sol_per_hora_hasta AS hora_hasta,
            sp.th_sol_per_fecha_desde AS fecha_desde,
            sp.th_sol_per_fecha_hasta AS fecha_hasta,
            sp.th_sol_per_total_horas AS total_horas,
            sp.th_sol_per_total_dias AS total_dias,
            sp.th_sol_per_estado AS estado,
            sp.th_sol_per_fecha_creacion AS fecha_creacion,
            sp.th_sol_per_fecha_modificacion AS fecha_modificacion
        FROM th_solicitud_permiso sp
        INNER JOIN th_personas p
            ON sp.th_per_id = p.th_per_id
        {$where}
        ORDER BY sp.th_sol_per_fecha_creacion DESC
    ";

    return $this->db->datos($sql);
}






}