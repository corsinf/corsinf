<?php

/**
 * @deprecated Archivo dado de baja el 02/04/2025.
 * @note Este archivo se mantiene como respaldo, pero ya no se utilizará en producción.
 * @warning No modificar este archivo. Para cambios, referirse a la nueva implementación.
 */

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_personasM extends BaseModel
{
    protected $tabla = 'th_personas';
    protected $primaryKey = 'th_per_id AS _id';

    protected $camposPermitidos = [
        'th_per_primer_nombre AS primer_nombre',
        'th_per_segundo_nombre AS segundo_nombre',
        'th_per_primer_apellido AS primer_apellido',
        'th_per_segundo_apellido AS segundo_apellido',
        'th_per_cedula AS cedula',
        'th_per_estado_civil AS estado_civil',
        'th_per_sexo AS sexo',
        'th_per_fecha_nacimiento AS fecha_nacimiento',
        'th_per_nacionalidad AS nacionalidad',
        'th_per_telefono_1 AS telefono_1',
        'th_per_telefono_2 AS telefono_2',
        'th_per_correo AS correo',
        'th_per_direccion AS direccion',
        'th_per_foto_url AS foto_url',
        'th_prov_id AS id_provincia',
        'th_ciu_id AS id_ciudad',
        'th_parr_id AS id_parroquia',
        'th_per_postal AS postal',
        'th_per_observaciones AS observaciones',
        //'th_per_tabla AS tabla',
        'th_per_id_comunidad AS id_comunidad',
        //'th_per_tabla_union AS tabla_union',
        'th_per_estado AS estado',
        'th_per_fecha_creacion AS fecha_creacion',
        //'th_per_fecha_modificacion AS fecha_modificacion',
        'PERFIL',
        'PASS',
        'th_pos_id',

        // Campos adicionales que pueden ser necesarios en el futuro
        // 'th_per_es_admin',
        // 'th_per_habiltado',
        // 'th_barr_id',
        // 'th_per_cargo',
        // 'th_per_fecha_admision',
        // 'th_per_fecha_aut_limite',
        // 'th_per_fecha_aut_inicio'
    ];

    public function listar_por_departamento($id_departamento = '')
    {
        // Normalizar entrada (puede ser número, texto o vacío)
        $valor = ($id_departamento !== '' && $id_departamento !== null) ? trim($id_departamento) : '';

        if ($valor === '') {
            // Si no se manda nada, devolvemos todas las personas activas que estén asignadas a algún departamento
            $sql = "
        SELECT DISTINCT p.th_per_id,
               p.th_per_cedula,
               p.th_per_primer_nombre,
               p.th_per_segundo_nombre,
               p.th_per_primer_apellido,
               p.th_per_segundo_apellido
        FROM th_personas p
        INNER JOIN th_personas_departamentos pd ON p.th_per_id = pd.th_per_id
        WHERE p.th_per_estado = 1
        ORDER BY p.th_per_primer_apellido, p.th_per_primer_nombre
        ";
        } else {
            // Si el valor es numérico lo tratamos como id
            if (is_numeric($valor)) {
                $id = (int)$valor;
                $sql = "
            SELECT p.th_per_id,
                   p.th_per_cedula,
                   p.th_per_primer_nombre,
                   p.th_per_segundo_nombre,
                   p.th_per_primer_apellido,
                   p.th_per_segundo_apellido
            FROM th_personas p
            INNER JOIN th_personas_departamentos pd ON p.th_per_id = pd.th_per_id
            WHERE pd.th_dep_id = {$id}
              AND p.th_per_estado = 1
            ORDER BY p.th_per_primer_apellido, p.th_per_primer_nombre
            ";
            } else {
                // Si no es numérico, lo tratamos como nombre (búsqueda parcial usando LIKE)
                // Escapamos comillas simples para evitar romper la query (mejor usar binding si está disponible)
                $nombre = addslashes($valor);
                $sql = "
            SELECT p.th_per_id,
                   p.th_per_cedula,
                   p.th_per_primer_nombre,
                   p.th_per_segundo_nombre,
                   p.th_per_primer_apellido,
                   p.th_per_segundo_apellido
            FROM th_personas p
            INNER JOIN th_personas_departamentos pd ON p.th_per_id = pd.th_per_id
            INNER JOIN th_departamentos d ON pd.th_dep_id = d.th_dep_id
            WHERE d.th_dep_nombre LIKE '%{$nombre}%'
              AND p.th_per_estado = 1
            ORDER BY p.th_per_primer_apellido, p.th_per_primer_nombre
            ";
            }
        }

        return $this->db->datos($sql);
    }


    public function listar_personas_con_departamento($id_departamento = '')
    {
        // Normalizar entrada (puede ser número, texto o vacío)
        $valor = ($id_departamento !== '' && $id_departamento !== null) ? trim($id_departamento) : '';

        if ($valor === '') {
            // Si no se manda nada, devolvemos todas las personas activas con o sin departamento
            $sql =
                "SELECT DISTINCT p.th_per_id,
                    p.th_per_cedula AS cedula,
                        p.th_per_correo AS correo,
                        p.th_per_telefono_1 AS telefono_1,
                    p.th_per_primer_nombre AS primer_nombre,
                        p.th_per_segundo_nombre AS segundo_nombre,
                        p.th_per_primer_apellido AS primer_apellido,
                        p.th_per_segundo_apellido AS segundo_apellido,
                        p.th_per_id_comunidad AS id_comunidad,
                    d.th_dep_id,
                    d.th_dep_nombre,
                    p.th_per_fecha_creacion AS fecha_creacion,
                    p.th_pos_id AS _id_postulante,
                    CASE 
                        WHEN d.th_dep_nombre IS NULL THEN 'Sin departamento'
                        ELSE d.th_dep_nombre 
                    END AS departamento_display
                FROM th_personas p
                LEFT JOIN th_personas_departamentos pd ON p.th_per_id = pd.th_per_id
                LEFT JOIN th_departamentos d ON pd.th_dep_id = d.th_dep_id
                WHERE p.th_per_estado = 1
                ORDER BY p.th_per_primer_apellido, p.th_per_primer_nombre, p.th_per_fecha_creacion;";
        }


        return $this->db->datos($sql);
    }


    public function listar_personas_no_asignadas($id_plaza = '', $coincidencias = false)
    {
        $id = ($id_plaza !== '' && $id_plaza !== null) ? (int)$id_plaza : '';

        $sql = "
        SELECT DISTINCT
            p.th_per_id                  AS _id,
            p.th_per_primer_nombre       AS primer_nombre,
            p.th_per_segundo_nombre      AS segundo_nombre,
            p.th_per_primer_apellido     AS primer_apellido,
            p.th_per_segundo_apellido    AS segundo_apellido,
            p.th_per_cedula              AS cedula,
            p.th_per_estado_civil        AS estado_civil,
            p.th_per_sexo                AS sexo,
            p.th_per_fecha_nacimiento    AS fecha_nacimiento,
            p.th_per_nacionalidad        AS nacionalidad,
            p.th_per_telefono_1          AS telefono_1,
            p.th_per_telefono_2          AS telefono_2,
            p.th_per_correo              AS correo,
            p.th_per_direccion           AS direccion,
            p.th_per_foto_url            AS foto_url,
            p.th_prov_id                 AS id_provincia,
            p.th_ciu_id                  AS id_ciudad,
            p.th_parr_id                 AS id_parroquia,
            p.th_per_postal              AS postal,
            p.th_per_observaciones       AS observaciones,
            p.th_per_id_comunidad        AS id_comunidad,
            p.th_per_tabla_union         AS tabla_union,
            p.th_per_estado              AS estado,
            p.th_per_fecha_creacion      AS fecha_creacion,
            p.PERFIL                     AS perfil,
            CONCAT(
                COALESCE(p.th_per_primer_nombre,''), ' ',
                COALESCE(p.th_per_segundo_nombre,''), ' ',
                COALESCE(p.th_per_primer_apellido,''), ' ',
                COALESCE(p.th_per_segundo_apellido,'')
            ) AS nombre_completo
        FROM th_personas p
        LEFT JOIN th_contr_postulaciones pos
            ON pos.th_persona_id = p.th_per_id
            AND pos.th_pla_id = $id
            AND pos.th_posu_estado = 1
        WHERE p.th_per_estado = 1
          AND pos.th_persona_id IS NULL
        ORDER BY p.th_per_primer_apellido, p.th_per_primer_nombre
    ";

        return $this->db->datos($sql);
    }


    public function listar_personas_correos($id_persona = null)
    {
        $id = ($id_persona !== '' && $id_persona !== null) ? (int)$id_persona : '';

        $where_id = ($id !== '') ? "AND p.th_per_id = {$id}" : "";

        $sql = "
        SELECT
            p.th_per_id AS th_per_id,
            p.th_per_correo AS th_per_correo,
            P.PASS AS PASS,
            CONCAT(
            ISNULL(p.th_per_primer_nombre, ''), ' ',
            ISNULL(p.th_per_segundo_nombre, ''), ' ',
            ISNULL(p.th_per_primer_apellido, ''), ' ',
            ISNULL(p.th_per_segundo_apellido, '')
            ) AS nombre_completo
        FROM th_personas p
        WHERE p.th_per_estado = 1
          AND p.th_per_correo IS NOT NULL
          {$where_id}
    ";

        return $this->db->datos($sql);
    }

    function listar_personas_departamentos($id_departamento, $per_id = '')
    {
        $sql =
            "SELECT
                    p.th_per_id AS th_per_id,
                    p.th_per_correo AS th_per_correo,
                    P.PASS AS PASS,
                    CONCAT(
                    ISNULL(p.th_per_primer_nombre, ''), ' ',
                    ISNULL(p.th_per_segundo_nombre, ''), ' ',
                    ISNULL(p.th_per_primer_apellido, ''), ' ',
                    ISNULL(p.th_per_segundo_apellido, '')
                    ) AS nombre_completo,
                    dep.th_dep_nombre AS nombre_departamento
                FROM
                th_personas_departamentos per_dep
                INNER JOIN th_personas p ON per_dep.th_per_id = p.th_per_id 
                INNER JOIN th_departamentos dep ON per_dep.th_dep_id = dep.th_dep_id
                WHERE p.th_per_estado = 1 ";

        if ($id_departamento != '' && $id_departamento != null) {
            $sql .= " AND per_dep.th_dep_id = '$id_departamento'";
        }
        if ($per_id != '' && $per_id != null) {
            $sql .= " AND p.th_per_id = '$per_id'";
        }

        $sql .= ";";

        $datos = $this->db->datos($sql);
        return $datos;
    }
    public function buscar_personas_con_departamento_unicamente($parametros)
    {
        $query = isset($parametros['query']) ? trim($parametros['query']) : '';
        $id_dep = isset($parametros['id_departamento']) ? $parametros['id_departamento'] : '';

        $sql = "
        SELECT DISTINCT 
            p.th_per_id AS id,
            p.th_per_cedula,
            p.th_per_primer_nombre,
            p.th_per_segundo_nombre,
            p.th_per_primer_apellido,
            p.th_per_segundo_apellido,
            d.th_dep_nombre,
            d.th_dep_id
        FROM th_personas p
        INNER JOIN th_personas_departamentos pd ON p.th_per_id = pd.th_per_id
        INNER JOIN th_departamentos d ON pd.th_dep_id = d.th_dep_id
        WHERE p.th_per_estado = 1
        AND p.th_per_cedula IS NOT NULL 
        AND p.th_per_cedula <> ''
    ";

        // Filtro por ID de departamento específico (opcional)
        if ($id_dep !== '' && $id_dep !== null) {
            $sql .= " AND d.th_dep_id = " . intval($id_dep);
        }

        // Filtro de búsqueda por texto (Cédula o Apellidos)
        if ($query !== '') {
            $sql .= " AND (
            p.th_per_cedula LIKE '%" . addslashes($query) . "%' OR 
            p.th_per_primer_apellido LIKE '%" . addslashes($query) . "%' OR
            p.th_per_primer_nombre LIKE '%" . addslashes($query) . "%'
        )";
        }

        $sql .= " ORDER BY p.th_per_primer_apellido ASC";

        return $this->db->datos($sql);
    }

    public function listar_personas_pdf($id_persona = null)
    {
        $id = ($id_persona !== '' && $id_persona !== null) ? (int)$id_persona : '';
        $where_id = ($id !== '') ? "AND p.th_per_id = {$id}" : "";

        $sql = "
    SELECT
        p.th_per_cedula AS cedula,
        p.th_per_sexo AS genero,
        p.th_per_estado_civil AS estado_civil,
        LTRIM(RTRIM(CONCAT(
            ISNULL(p.th_per_primer_nombre, ''), ' ',
            ISNULL(p.th_per_segundo_nombre, ''), ' ',
            ISNULL(p.th_per_primer_apellido, ''), ' ',
            ISNULL(p.th_per_segundo_apellido, '')
        ))) AS nombre_completo
    FROM th_personas p
    WHERE p.th_per_estado = 1
      {$where_id}
    ";

        return $this->db->datos($sql);
    }
}
