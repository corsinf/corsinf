<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_solicitud_permisoM extends BaseModel
{
    protected $tabla = 'th_solicitud_permiso';
    protected $primaryKey = 'th_sol_per_id as _id';

    protected $camposPermitidos = [
        'th_per_id AS th_per_id',

        'th_sol_per_tipo_motivo AS tipo_motivo',
        'th_sol_per_motivo AS motivo',
        'th_sol_per_detalle AS detalle',

        'th_sol_per_fam_hijos_adultos AS fam_hijos_adultos',
        'th_sol_per_parentesco_fecha_nacimiento AS fecha_nacimiento',

        'th_sol_per_certificado_adjunto AS certificado_adjunto',

        'th_sol_per_tipo_atencion AS tipo_atencion',
        'th_sol_per_lugar AS lugar',
        'th_sol_per_especialidad AS especialidad',
        'th_sol_per_medico AS medico',

        'th_sol_per_fecha_atencion AS fecha_atencion',
        'th_sol_per_hora_desde AS hora_desde',
        'th_sol_per_hora_hasta AS hora_hasta',

        'th_sol_per_rango_edad AS rango_edad',
        'th_sol_per_tipo_cuidado AS tipo_cuidado',

        'th_sol_per_ruta_certificado AS ruta_certificado',
        'th_sol_per_ruta_act_defuncion AS ruta_act_defuncion',
        'th_sol_per_ruta_solicitud AS ruta_solicitud',

        'th_sol_per_estado AS estado',
        'th_sol_per_fecha_creacion AS fecha_creacion',
        'th_sol_per_fecha_modificacion AS fecha_modificacion',

        'th_ppa_id AS th_ppa_id',
        'th_sol_per_tipo_solicitud AS tipo_solicitud',
        'th_sol_per_planificacion AS planificacion',

        // Fechas del permiso (usuario)
        'th_sol_per_tipo_calculo AS tipo_calculo',
        'th_sol_per_fecha_principal_permiso AS fecha_principal_permiso',
        'th_sol_per_fecha_desde_permiso AS fecha_desde_permiso',
        'th_sol_per_fecha_hasta_permiso AS fecha_hasta_permiso',
        'th_sol_per_total_dias AS total_dias_permiso',
        'th_sol_per_total_horas AS total_horas_permiso',
    ];


    function listar_personas_con_total_solicitudes($th_per_id = null)
    {
        $filtro = '';

        if (!empty($th_per_id)) {
            $th_per_id = intval($th_per_id);
            $filtro = " AND p.th_per_id = {$th_per_id}";
        }

        $sql = "
        SELECT
            p.th_per_id AS id,
            p.th_per_cedula AS cedula,
            p.th_per_telefono_1 AS telefono,
            LTRIM(RTRIM(
                ISNULL(p.th_per_primer_apellido,'') + ' ' +
                ISNULL(p.th_per_segundo_apellido,'') + ' ' +
                ISNULL(p.th_per_primer_nombre,'') + ' ' +
                ISNULL(p.th_per_segundo_nombre,'')
            )) AS nombre_completo,
            COUNT(sp.th_sol_per_id) AS total_solicitudes
        FROM th_solicitud_permiso sp
        INNER JOIN th_personas p ON sp.th_per_id = p.th_per_id
        WHERE sp.th_sol_per_estado = 1 {$filtro}
        GROUP BY
            p.th_per_id,
            p.th_per_cedula,
            p.th_per_telefono_1,
            p.th_per_primer_nombre,
            p.th_per_segundo_nombre,
            p.th_per_primer_apellido,
            p.th_per_segundo_apellido
        ORDER BY total_solicitudes DESC";

        return $this->db->datos($sql);
    }


    function listar_solicitudes_persona_con_medico($th_per_id)
    {
        $th_per_id = intval($th_per_id);

        $sql = "
        SELECT
            sp.th_sol_per_id AS id_solicitud,
            sp.th_per_id AS id_persona,
            sp.th_sol_per_tipo_motivo AS tipo_motivo,
            sp.th_sol_per_motivo AS motivo,
            sp.th_sol_per_detalle AS detalle,
            sp.th_sol_per_estado AS estado_solicitud,
            sp.th_sol_per_fecha_creacion AS fecha_creacion,

            sm.th_sol_per_med_id AS id_solicitud_medica,
            sm.th_sol_per_med_estado_solicitud AS estado_medico,
            sm.th_sol_per_med_nombre_medico AS nombre_medico,

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
        ORDER BY sp.th_sol_per_fecha_creacion DESC";

        return $this->db->datos($sql);
    }


    function obtener_solicitudes_persona($th_sol_per_id = null, $th_per_id = null)
    {
        $where = " WHERE 1=1 "; // Base para concatenar filtros fácilmente

        if (!empty($th_sol_per_id)) {
            // Si existe el ID de solicitud, filtramos por ese específicamente
            $where .= " AND sp.th_sol_per_id = " . intval($th_sol_per_id);
        } elseif (!empty($th_per_id)) {
            // Si no hay ID de solicitud pero sí de persona, filtramos por la persona
            $where .= " AND sp.th_per_id = " . intval($th_per_id);
        }

        $sql = "
    SELECT
        sp.th_sol_per_id AS _id,
        sp.th_per_id AS id_persona,
        p.th_per_cedula AS cedula,
        p.th_per_telefono_1 AS telefono,
        LTRIM(RTRIM(
            ISNULL(p.th_per_primer_nombre,'') + ' ' +
            ISNULL(p.th_per_segundo_nombre,'') + ' ' +
            ISNULL(p.th_per_primer_apellido,'') + ' ' +
            ISNULL(p.th_per_segundo_apellido,'')
        )) AS nombre_completo,
        sp.th_sol_per_tipo_motivo AS tipo_motivo,
        sp.th_sol_per_motivo AS motivo,
        sp.th_sol_per_detalle AS detalle,
        sp.th_sol_per_fam_hijos_adultos AS fam_hijos_adultos,
        sp.th_sol_per_parentesco_fecha_nacimiento AS fecha_nacimiento,
        sp.th_sol_per_certificado_adjunto AS certificado_adjunto,
        sp.th_sol_per_tipo_atencion AS tipo_atencion,
        sp.th_sol_per_lugar AS lugar,
        sp.th_sol_per_especialidad AS especialidad,
        sp.th_sol_per_medico AS medico,
        sp.th_sol_per_fecha_atencion AS fecha_atencion,
        sp.th_sol_per_hora_desde AS hora_desde,
        sp.th_sol_per_hora_hasta AS hora_hasta,
        sp.th_sol_per_rango_edad AS rango_edad,
        sp.th_sol_per_tipo_cuidado AS tipo_cuidado,
        sp.th_sol_per_ruta_certificado AS ruta_certificado,
        sp.th_sol_per_ruta_act_defuncion AS ruta_act_defuncion,
        sp.th_sol_per_ruta_solicitud AS ruta_solicitud,
        sp.th_sol_per_estado AS estado,
        sp.th_sol_per_fecha_creacion AS fecha_creacion,
        sp.th_sol_per_fecha_modificacion AS fecha_modificacion,
        sp.th_ppa_id AS th_ppa_id,
        sp.th_sol_per_tipo_solicitud AS tipo_solicitud,
        sp.th_sol_per_planificacion AS planificacion,
        sp.th_sol_per_tipo_calculo AS tipo_calculo,
        sp.th_sol_per_fecha_principal_permiso AS fecha_principal_permiso,
        sp.th_sol_per_fecha_desde_permiso AS fecha_desde_permiso,
        sp.th_sol_per_fecha_hasta_permiso AS fecha_hasta_permiso,
        sp.th_sol_per_total_dias AS total_dias_permiso,
        sp.th_sol_per_total_horas AS total_horas_permiso
    FROM th_solicitud_permiso sp
    INNER JOIN th_personas p ON sp.th_per_id = p.th_per_id
    {$where}
    ORDER BY sp.th_sol_per_fecha_creacion DESC";

        return $this->db->datos($sql);
    }


    public function buscar_datos_completos_solicitud($id_solicitud)
    {
        $id_solicitud = intval($id_solicitud);

        $sql = "
        SELECT
            s.*,
            p.th_per_cedula AS cedula,
            p.th_per_sexo AS genero,
            p.th_per_estado_civil AS estado_civil,
            LTRIM(RTRIM(
                ISNULL(p.th_per_primer_nombre,'') + ' ' +
                ISNULL(p.th_per_segundo_nombre,'') + ' ' +
                ISNULL(p.th_per_primer_apellido,'') + ' ' +
                ISNULL(p.th_per_segundo_apellido,'')
            )) AS nombre_completo
        FROM th_solicitud_permiso s
        INNER JOIN th_personas p ON s.th_per_id = p.th_per_id
        WHERE s.th_sol_per_id = {$id_solicitud}";

        return $this->db->datos($sql);
    }
}
