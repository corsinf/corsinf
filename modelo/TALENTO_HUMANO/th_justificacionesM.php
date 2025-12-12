<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_justificacionesM extends BaseModel
{
    protected $tabla = 'th_justificaciones';
    protected $primaryKey = 'th_jus_id AS _id';

    protected $camposPermitidos = [
        'th_jus_fecha_inicio AS fecha_inicio',
        'th_jus_fecha_fin AS fecha_fin',
        'th_tip_jus_id AS id_tipo_justificacion',
        'th_jus_motivo AS motivo',
        'th_per_id AS id_persona',
        'th_dep_id AS id_departamento',
        'th_jus_fecha_creacion AS fecha_creacion',
        'th_jus_fecha_modificacion AS fecha_modificacion',
        'th_jus_estado AS estado',
        'id_usuario AS id_usuario',
        'th_jus_es_rango AS es_rango',
        'th_jus_minutos_justificados AS minutos_justificados'

    ];

    function listar_departamentos_justificaciones($id_departamento = '')
    {
        $sql =
            "SELECT
                    jus.th_jus_id AS _id,
                    jus.th_jus_fecha_inicio AS fecha_inicio,
                    jus.th_jus_fecha_fin AS fecha_fin,
                    jus.th_tip_jus_id AS id_tipo_justificacion,
                    tjus.th_tip_jus_nombre AS tipo_motivo,
                    jus.th_jus_motivo AS motivo,
                    jus.th_per_id AS id_persona,
                    jus.th_dep_id AS id_departamento,
                    jus.th_jus_fecha_creacion AS fecha_creacion,
                    jus.th_jus_fecha_modificacion AS fecha_modificacion,
                    jus.th_jus_estado AS estado,
                    jus.id_usuario AS id_usuario,
                    jus.th_jus_es_rango AS es_rango,
                    jus.th_jus_minutos_justificados AS minutos_justificados,
                    dep.th_dep_nombre AS nombre_departamento

                FROM
                    th_justificaciones jus
                INNER JOIN th_departamentos dep ON jus.th_dep_id = dep.th_dep_id
                LEFT JOIN th_cat_tipo_justificacion tjus ON jus.th_tip_jus_id = tjus.th_tip_jus_id
                WHERE
                    jus.th_dep_id <> 0 AND jus.th_jus_estado = 1";

        if ($id_departamento != '') {
            $sql .= " AND jus.th_dep_id = $id_departamento";
        }

        $datos = $this->db->datos($sql);
        return $datos;
    }

    function listar_personas_justificaciones($id_persona = '')
    {
        $sql =
            "SELECT
                    jus.th_jus_id AS _id,
                    jus.th_jus_fecha_inicio AS fecha_inicio,
                    jus.th_jus_fecha_fin AS fecha_fin,
                    jus.th_tip_jus_id AS id_tipo_justificacion,
                    tjus.th_tip_jus_nombre AS tipo_motivo,
                    jus.th_jus_motivo AS motivo,
                    jus.th_per_id AS id_persona,
                    jus.th_dep_id AS id_departamento,
                    jus.th_jus_fecha_creacion AS fecha_creacion,
                    jus.th_jus_fecha_modificacion AS fecha_modificacion,
                    jus.th_jus_estado AS estado,
                    jus.id_usuario AS id_usuario,
                    jus.th_jus_es_rango AS es_rango,
                    jus.th_jus_minutos_justificados AS minutos_justificados,
                    CONCAT(per.th_per_primer_apellido, ' ', per.th_per_segundo_apellido, ' ', 
                        per.th_per_primer_nombre, ' ', per.th_per_segundo_nombre) AS nombre_persona
                FROM
                    th_justificaciones jus
                INNER JOIN th_personas per ON jus.th_per_id = per.th_per_id
                LEFT JOIN th_cat_tipo_justificacion tjus ON jus.th_tip_jus_id = tjus.th_tip_jus_id
                WHERE
                    jus.th_per_id <> 0 AND jus.th_jus_estado = 1";

        if ($id_persona != '') {
            $sql .= " AND jus.th_per_id = $id_persona";
        }

        $datos = $this->db->datos($sql);
        return $datos;
    }

    function listar_justificaciones($id_justificacion = '', $id_departamento = '', $id_persona = '')
    {
        $sql = "SELECT
                jus.th_jus_id AS _id,
                jus.th_jus_fecha_inicio AS fecha_inicio,
                jus.th_jus_fecha_fin AS fecha_fin,
                jus.th_tip_jus_id AS id_tipo_justificacion,
                tjus.th_tip_jus_nombre AS tipo_motivo,
                jus.th_jus_motivo AS motivo,
                jus.th_per_id AS id_persona,
                jus.th_dep_id AS id_departamento,
                jus.th_jus_fecha_creacion AS fecha_creacion,
                jus.th_jus_fecha_modificacion AS fecha_modificacion,
                jus.th_jus_estado AS estado,
                jus.id_usuario AS id_usuario,
                dep.th_dep_nombre AS nombre_departamento,
                jus.th_jus_es_rango AS es_rango,
                jus.th_jus_minutos_justificados AS minutos_justificados,
                CONCAT(per.th_per_primer_apellido, ' ', per.th_per_segundo_apellido, ' ', 
                    per.th_per_primer_nombre, ' ', per.th_per_segundo_nombre) AS nombre_persona
            FROM th_justificaciones jus
            LEFT JOIN th_departamentos dep ON jus.th_dep_id = dep.th_dep_id
            LEFT JOIN th_personas per ON jus.th_per_id = per.th_per_id
            LEFT JOIN th_cat_tipo_justificacion tjus ON jus.th_tip_jus_id = tjus.th_tip_jus_id
            WHERE 1 = 1"; // Condición base para evitar problemas con AND dinámicos

        // Filtros dinámicos según los parámetros proporcionados
        if ($id_justificacion != '') {
            $sql .= " AND jus.th_jus_id = $id_justificacion";
        }
        if ($id_departamento != '') {
            $sql .= " AND jus.th_dep_id = $id_departamento";
        }
        if ($id_persona != '') {
            $sql .= " AND jus.th_per_id = $id_persona";
        }

        $datos = $this->db->datos($sql);
        return $datos;
    }
}