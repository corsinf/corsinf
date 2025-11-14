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
        //'PASS',

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
            $sql = "
        SELECT DISTINCT p.th_per_id,
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
               CASE 
                   WHEN d.th_dep_nombre IS NULL THEN 'Sin departamento'
                   ELSE d.th_dep_nombre 
               END AS departamento_display
        FROM th_personas p
        LEFT JOIN th_personas_departamentos pd ON p.th_per_id = pd.th_per_id
        LEFT JOIN th_departamentos d ON pd.th_dep_id = d.th_dep_id
        WHERE p.th_per_estado = 1
        ORDER BY p.th_per_primer_apellido, p.th_per_primer_nombre
        ";
        }


        return $this->db->datos($sql);
    }


    public function listar_personas_no_asignadas($id_plaza = '', $coincidencias = false)
{
    // Normalizar id_plaza
    $id = ($id_plaza !== '' && $id_plaza !== null) ? (int)$id_plaza : '';

    // Base del SELECT con aliases
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
        WHERE p.th_per_estado = 1
    ";

    // Si nos pasan id de plaza, SIEMPRE excluimos personas que ya están en esa plaza
    if ($id !== '') {
        $sql .= "
            AND NOT EXISTS (
                SELECT 1
                FROM th_contr_postulaciones pos
                WHERE pos.th_persona_id = p.th_per_id
                  AND pos.th_pla_id = {$id}
                  AND pos.th_posu_estado = 1
            )
        ";

        // Si coincidencias = true, aplicamos filtros usando th_per_id_comunidad para acceder al CV
        if ($coincidencias === true || $coincidencias === 'true' || $coincidencias === 1 || $coincidencias === '1') {
            $sql .= "
                AND p.th_per_id_comunidad IS NOT NULL
                AND (
                    -- Buscar coincidencias con el TÍTULO de la plaza usando formación académica
                    EXISTS (
                        SELECT 1 
                        FROM th_contr_plazas pla
                        INNER JOIN th_pos_formacion_academica fa ON fa.th_pos_id = p.th_per_id_comunidad
                        WHERE pla.th_pla_id = {$id}
                          AND pla.th_pla_estado = 1
                          AND fa.th_fora_estado = 1
                          AND (
                              fa.th_fora_titulo_obtenido LIKE CONCAT('%', pla.th_pla_titulo, '%')
                              OR pla.th_pla_titulo LIKE CONCAT('%', fa.th_fora_titulo_obtenido, '%')
                          )
                    )
                    OR
                    -- Buscar coincidencias con el TÍTULO usando experiencia laboral
                    EXISTS (
                        SELECT 1 
                        FROM th_contr_plazas pla
                        INNER JOIN th_pos_experiencia_laboral el ON el.th_pos_id = p.th_per_id_comunidad
                        WHERE pla.th_pla_id = {$id}
                          AND pla.th_pla_estado = 1
                          AND el.th_expl_estado = 1
                          AND (
                              el.th_expl_cargos_ocupados LIKE CONCAT('%', pla.th_pla_titulo, '%')
                              OR pla.th_pla_titulo LIKE CONCAT('%', el.th_expl_cargos_ocupados, '%')
                              OR el.th_expl_responsabilidades_logros LIKE CONCAT('%', pla.th_pla_titulo, '%')
                          )
                    )
                    OR
                    -- Buscar coincidencias con el TÍTULO usando certificaciones
                    EXISTS (
                        SELECT 1 
                        FROM th_contr_plazas pla
                        INNER JOIN th_pos_certificaciones_capacitaciones cc ON cc.th_pos_id = p.th_per_id_comunidad
                        WHERE pla.th_pla_id = {$id}
                          AND pla.th_pla_estado = 1
                          AND cc.th_cert_estado = 1
                          AND (
                              cc.th_cert_nombre_curso LIKE CONCAT('%', pla.th_pla_titulo, '%')
                              OR pla.th_pla_titulo LIKE CONCAT('%', cc.th_cert_nombre_curso, '%')
                          )
                    )
                    OR
                    -- Buscar coincidencias con la DESCRIPCIÓN de la plaza usando formación académica
                    EXISTS (
                        SELECT 1 
                        FROM th_contr_plazas pla
                        INNER JOIN th_pos_formacion_academica fa ON fa.th_pos_id = p.th_per_id_comunidad
                        WHERE pla.th_pla_id = {$id}
                          AND pla.th_pla_estado = 1
                          AND fa.th_fora_estado = 1
                          AND pla.th_pla_descripcion IS NOT NULL
                          AND pla.th_pla_descripcion != ''
                          AND (
                              fa.th_fora_titulo_obtenido LIKE CONCAT('%', pla.th_pla_descripcion, '%')
                              OR pla.th_pla_descripcion LIKE CONCAT('%', fa.th_fora_titulo_obtenido, '%')
                          )
                    )
                    OR
                    -- Buscar coincidencias con la DESCRIPCIÓN usando experiencia laboral
                    EXISTS (
                        SELECT 1 
                        FROM th_contr_plazas pla
                        INNER JOIN th_pos_experiencia_laboral el ON el.th_pos_id = p.th_per_id_comunidad
                        WHERE pla.th_pla_id = {$id}
                          AND pla.th_pla_estado = 1
                          AND el.th_expl_estado = 1
                          AND pla.th_pla_descripcion IS NOT NULL
                          AND pla.th_pla_descripcion != ''
                          AND (
                              el.th_expl_cargos_ocupados LIKE CONCAT('%', pla.th_pla_descripcion, '%')
                              OR pla.th_pla_descripcion LIKE CONCAT('%', el.th_expl_cargos_ocupados, '%')
                              OR el.th_expl_responsabilidades_logros LIKE CONCAT('%', pla.th_pla_descripcion, '%')
                          )
                    )
                    OR
                    -- Buscar coincidencias con la DESCRIPCIÓN usando certificaciones
                    EXISTS (
                        SELECT 1 
                        FROM th_contr_plazas pla
                        INNER JOIN th_pos_certificaciones_capacitaciones cc ON cc.th_pos_id = p.th_per_id_comunidad
                        WHERE pla.th_pla_id = {$id}
                          AND pla.th_pla_estado = 1
                          AND cc.th_cert_estado = 1
                          AND pla.th_pla_descripcion IS NOT NULL
                          AND pla.th_pla_descripcion != ''
                          AND (
                              cc.th_cert_nombre_curso LIKE CONCAT('%', pla.th_pla_descripcion, '%')
                              OR pla.th_pla_descripcion LIKE CONCAT('%', cc.th_cert_nombre_curso, '%')
                          )
                    )
                    OR
                    -- Buscar coincidencias con las OBSERVACIONES de la plaza usando formación académica
                    EXISTS (
                        SELECT 1 
                        FROM th_contr_plazas pla
                        INNER JOIN th_pos_formacion_academica fa ON fa.th_pos_id = p.th_per_id_comunidad
                        WHERE pla.th_pla_id = {$id}
                          AND pla.th_pla_estado = 1
                          AND fa.th_fora_estado = 1
                          AND pla.th_pla_observaciones IS NOT NULL
                          AND pla.th_pla_observaciones != ''
                          AND (
                              fa.th_fora_titulo_obtenido LIKE CONCAT('%', pla.th_pla_observaciones, '%')
                              OR pla.th_pla_observaciones LIKE CONCAT('%', fa.th_fora_titulo_obtenido, '%')
                          )
                    )
                    OR
                    -- Buscar coincidencias con las OBSERVACIONES usando experiencia laboral
                    EXISTS (
                        SELECT 1 
                        FROM th_contr_plazas pla
                        INNER JOIN th_pos_experiencia_laboral el ON el.th_pos_id = p.th_per_id_comunidad
                        WHERE pla.th_pla_id = {$id}
                          AND pla.th_pla_estado = 1
                          AND el.th_expl_estado = 1
                          AND pla.th_pla_observaciones IS NOT NULL
                          AND pla.th_pla_observaciones != ''
                          AND (
                              el.th_expl_cargos_ocupados LIKE CONCAT('%', pla.th_pla_observaciones, '%')
                              OR pla.th_pla_observaciones LIKE CONCAT('%', el.th_expl_cargos_ocupados, '%')
                              OR el.th_expl_responsabilidades_logros LIKE CONCAT('%', pla.th_pla_observaciones, '%')
                          )
                    )
                    OR
                    -- Buscar coincidencias con las OBSERVACIONES usando certificaciones
                    EXISTS (
                        SELECT 1 
                        FROM th_contr_plazas pla
                        INNER JOIN th_pos_certificaciones_capacitaciones cc ON cc.th_pos_id = p.th_per_id_comunidad
                        WHERE pla.th_pla_id = {$id}
                          AND pla.th_pla_estado = 1
                          AND cc.th_cert_estado = 1
                          AND pla.th_pla_observaciones IS NOT NULL
                          AND pla.th_pla_observaciones != ''
                          AND (
                              cc.th_cert_nombre_curso LIKE CONCAT('%', pla.th_pla_observaciones, '%')
                              OR pla.th_pla_observaciones LIKE CONCAT('%', cc.th_cert_nombre_curso, '%')
                          )
                    )
                    OR
                    -- Buscar coincidencias con los REQUISITOS usando formación académica
                    EXISTS (
                        SELECT 1 
                        FROM th_contr_requisitos req
                        INNER JOIN th_pos_formacion_academica fa ON fa.th_pos_id = p.th_per_id_comunidad
                        WHERE req.th_pla_id = {$id}
                          AND req.th_req_estado = 1
                          AND fa.th_fora_estado = 1
                          AND (
                              fa.th_fora_titulo_obtenido LIKE CONCAT('%', req.th_req_descripcion, '%')
                              OR req.th_req_descripcion LIKE CONCAT('%', fa.th_fora_titulo_obtenido, '%')
                          )
                    )
                    OR
                    -- Buscar coincidencias con los REQUISITOS usando experiencia laboral
                    EXISTS (
                        SELECT 1 
                        FROM th_contr_requisitos req
                        INNER JOIN th_pos_experiencia_laboral el ON el.th_pos_id = p.th_per_id_comunidad
                        WHERE req.th_pla_id = {$id}
                          AND req.th_req_estado = 1
                          AND el.th_expl_estado = 1
                          AND (
                              el.th_expl_cargos_ocupados LIKE CONCAT('%', req.th_req_descripcion, '%')
                              OR req.th_req_descripcion LIKE CONCAT('%', el.th_expl_cargos_ocupados, '%')
                              OR el.th_expl_responsabilidades_logros LIKE CONCAT('%', req.th_req_descripcion, '%')
                          )
                    )
                    OR
                    -- Buscar coincidencias con los REQUISITOS usando certificaciones
                    EXISTS (
                        SELECT 1 
                        FROM th_contr_requisitos req
                        INNER JOIN th_pos_certificaciones_capacitaciones cc ON cc.th_pos_id = p.th_per_id_comunidad
                        WHERE req.th_pla_id = {$id}
                          AND req.th_req_estado = 1
                          AND cc.th_cert_estado = 1
                          AND (
                              cc.th_cert_nombre_curso LIKE CONCAT('%', req.th_req_descripcion, '%')
                              OR req.th_req_descripcion LIKE CONCAT('%', cc.th_cert_nombre_curso, '%')
                          )
                    )
                )
            ";
        }
    }

    $sql .= " ORDER BY p.th_per_primer_apellido, p.th_per_primer_nombre";

    $datos = $this->db->datos($sql);
    return $datos;
}

}