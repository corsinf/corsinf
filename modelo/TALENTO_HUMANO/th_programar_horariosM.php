<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_programar_horariosM extends BaseModel
{
    protected $tabla = 'th_programar_horarios';
    protected $primaryKey = 'th_pro_id AS _id';

    protected $camposPermitidos = [
        'th_hor_id AS id_horario',
        'th_per_id AS id_persona',
        'th_dep_id AS id_departamento',
        'th_pro_fecha_inicio AS fecha_inicio',
        'th_pro_fecha_fin AS fecha_fin',
        'th_pro_no_ciclo AS no_ciclo',
        'th_pro_tipo_ciclo AS tipo_ciclo',
        'th_pro_si_ciclo AS si_ciclo',
        //'th_pro_estado AS estado',
        //'th_pro_fecha_creacion AS fecha_creacion',
        //'th_pro_fecha_modificacion AS fecha_modificacion',
    ];

    function listar_departamentos_horarios($id_departamento = '')
    {
        $sql =
            "SELECT
                    pro_hor.th_pro_id AS _id,
                    pro_hor.th_hor_id AS id_horario,
                    pro_hor.th_dep_id AS id_departamento,
                    pro_hor.th_pro_fecha_inicio AS fecha_inicio,
                    pro_hor.th_pro_fecha_fin AS fecha_fin,
                    hor.th_hor_nombre AS nombre_horario,
                    dep.th_dep_nombre AS nombre_departamento 
                FROM
                    th_programar_horarios pro_hor
                LEFT JOIN th_horarios hor ON pro_hor.th_hor_id = hor.th_hor_id
                INNER JOIN th_departamentos dep ON pro_hor.th_dep_id = dep.th_dep_id 
                WHERE
                    pro_hor.th_dep_id <> 0 ";

        if ($id_departamento != '') {
            $sql .= " AND pro_hor.th_dep_id = $id_departamento;";
        }

        $datos = $this->db->datos($sql);
        return $datos;
    }

    function listar_personas_horarios($id_persona = '')
    {
        $sql =
            "SELECT
                    pro_hor.th_pro_id AS _id,
                    pro_hor.th_hor_id AS id_horario,
                    pro_hor.th_per_id AS id_persona,
                    pro_hor.th_pro_fecha_inicio AS fecha_inicio,
                    pro_hor.th_pro_fecha_fin AS fecha_fin,
                    hor.th_hor_nombre AS nombre_horario,
                    CONCAT ( per.th_per_primer_apellido, ' ', per.th_per_segundo_apellido , ' ', per.th_per_primer_nombre , ' ', per.th_per_segundo_nombre) AS nombre_persona

                FROM
                    th_programar_horarios pro_hor
                LEFT JOIN th_horarios hor ON pro_hor.th_hor_id = hor.th_hor_id
                INNER JOIN th_personas per ON pro_hor.th_per_id = per.th_per_id 
                WHERE
                    pro_hor.th_per_id <> 0 ";

        if ($id_persona != '') {
            $sql .= " AND pro_hor.th_per_id = $id_persona;";
        }
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function listar_programacion_horarios($id_programacion = '')
    {
        $sql = "SELECT
                pro_hor.th_pro_id AS _id,
                pro_hor.th_hor_id AS id_horario,
                pro_hor.th_dep_id AS id_departamento,
                pro_hor.th_per_id AS id_persona,
                pro_hor.th_pro_fecha_inicio AS fecha_inicio,
                pro_hor.th_pro_fecha_fin AS fecha_fin,
                pro_hor.th_pro_no_ciclo AS no_ciclo,
                pro_hor.th_pro_tipo_ciclo AS tipo_ciclo,
                pro_hor.th_pro_si_ciclo AS si_ciclo,
                hor.th_hor_nombre AS nombre_horario,
                dep.th_dep_nombre AS nombre_departamento,
                CONCAT(per.th_per_primer_apellido, ' ', per.th_per_segundo_apellido, ' ', per.th_per_primer_nombre, ' ', per.th_per_segundo_nombre) AS nombre_persona
            FROM
                th_programar_horarios pro_hor
            LEFT JOIN th_horarios hor ON pro_hor.th_hor_id = hor.th_hor_id
            LEFT JOIN th_departamentos dep ON pro_hor.th_dep_id = dep.th_dep_id
            LEFT JOIN th_personas per ON pro_hor.th_per_id = per.th_per_id
            WHERE pro_hor.th_pro_id <> 0";

        if ($id_programacion != '') {
            $sql .= " AND pro_hor.th_pro_id = $id_programacion";
        }

        $datos = $this->db->datos($sql);
        return $datos;
    }


    function listar_persona_departamentos($id = '', $tipo = 'dep')
    {
        $filtro = "";

        if (!empty($id)) {
            if ($tipo === 'dep') {
                $filtro = "WHERE pro_hor.th_dep_id = $id";
            } elseif ($tipo === 'per') {
                $filtro = "WHERE pro_hor.th_per_id = $id";
            }
        }

        $sql = "
        SELECT
            pro_hor.th_pro_id AS _id,
            pro_hor.th_hor_id AS id_horario,
            pro_hor.th_dep_id AS id_departamento,
            pro_hor.th_per_id AS id_persona,
            pro_hor.th_pro_fecha_inicio AS fecha_inicio,
            pro_hor.th_pro_fecha_fin AS fecha_fin,
            ISNULL(hor.th_hor_nombre, 'SIN HORARIO') AS nombre_horario,
            ISNULL(dep.th_dep_nombre, 'SIN DEPARTAMENTO') AS nombre_departamento
        FROM
            th_programar_horarios pro_hor
        LEFT JOIN th_horarios hor ON pro_hor.th_hor_id = hor.th_hor_id
        LEFT JOIN th_departamentos dep ON pro_hor.th_dep_id = dep.th_dep_id
        $filtro;";

        $datos = $this->db->datos($sql);

        return $datos;
    }
    
    public function listar_horarios_persona_completo($id_persona = '')
{
    $id_persona = intval($id_persona);
    if ($id_persona <= 0) {
        return [];
    }

    $sql = "
        SELECT
            pro_hor.th_pro_id                 AS _id,
            pro_hor.th_hor_id                 AS id_horario,
            pro_hor.th_dep_id                 AS id_departamento,
            pro_hor.th_per_id                 AS id_persona,
            pro_hor.th_pro_fecha_inicio       AS fecha_inicio,
            pro_hor.th_pro_fecha_fin          AS fecha_fin,
            pro_hor.th_pro_no_ciclo           AS no_ciclo,
            pro_hor.th_pro_tipo_ciclo         AS tipo_ciclo,
            pro_hor.th_pro_si_ciclo           AS si_ciclo,
            pro_hor.th_pro_estado             AS estado,
            hor.th_hor_nombre                 AS nombre_horario,
            dep.th_dep_nombre                 AS nombre_departamento,
            CONCAT(
                per.th_per_primer_apellido, ' ',
                per.th_per_segundo_apellido, ' ',
                per.th_per_primer_nombre, ' ',
                per.th_per_segundo_nombre
            )                                 AS nombre_persona,
            'departamento'                    AS fuente
        FROM th_programar_horarios pro_hor
        INNER JOIN th_personas_departamentos pd
            ON pd.th_dep_id = pro_hor.th_dep_id
            AND pd.th_per_id = {$id_persona}
        LEFT JOIN th_horarios hor
            ON pro_hor.th_hor_id = hor.th_hor_id
        LEFT JOIN th_departamentos dep
            ON pro_hor.th_dep_id = dep.th_dep_id
        LEFT JOIN th_personas per
            ON pro_hor.th_per_id = per.th_per_id
        WHERE ISNULL(pro_hor.th_dep_id, 0) <> 0
          AND pro_hor.th_pro_estado = 1

        UNION ALL

        SELECT
            pro_hor.th_pro_id                 AS _id,
            pro_hor.th_hor_id                 AS id_horario,
            pro_hor.th_dep_id                 AS id_departamento,
            pro_hor.th_per_id                 AS id_persona,
            pro_hor.th_pro_fecha_inicio       AS fecha_inicio,
            pro_hor.th_pro_fecha_fin          AS fecha_fin,
            pro_hor.th_pro_no_ciclo           AS no_ciclo,
            pro_hor.th_pro_tipo_ciclo         AS tipo_ciclo,
            pro_hor.th_pro_si_ciclo           AS si_ciclo,
            pro_hor.th_pro_estado             AS estado,
            hor.th_hor_nombre                 AS nombre_horario,
            dep.th_dep_nombre                 AS nombre_departamento,
            CONCAT(
                per.th_per_primer_apellido, ' ',
                per.th_per_segundo_apellido, ' ',
                per.th_per_primer_nombre, ' ',
                per.th_per_segundo_nombre
            )                                 AS nombre_persona,
            'persona'                         AS fuente
        FROM th_programar_horarios pro_hor
        LEFT JOIN th_horarios hor
            ON pro_hor.th_hor_id = hor.th_hor_id
        LEFT JOIN th_departamentos dep
            ON pro_hor.th_dep_id = dep.th_dep_id
        LEFT JOIN th_personas per
            ON pro_hor.th_per_id = per.th_per_id
        WHERE pro_hor.th_per_id = {$id_persona}
          AND ISNULL(pro_hor.th_per_id, 0) <> 0
          AND pro_hor.th_pro_estado = 1

        ORDER BY fecha_inicio ASC;
    ";

    $datos = $this->db->datos($sql);
    return $datos;
}

}