<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_postulantesM extends BaseModel
{
    protected $tabla = 'th_postulantes';
    protected $primaryKey = 'th_pos_id AS _id';

    protected $camposPermitidos = [
        'th_pos_primer_nombre',
        'th_pos_segundo_nombre',
        'th_pos_primer_apellido',
        'th_pos_segundo_apellido',
        'th_pos_cedula',
        'th_pos_sexo',
        'th_pos_fecha_nacimiento',
        'th_pos_nacionalidad',
        'th_pos_estado_civil',
        'th_pos_telefono_1',
        'th_pos_telefono_2',
        'th_pos_correo',
        'th_prov_id',
        'th_ciu_id',
        'th_parr_id',
        'th_pos_direccion',
        'th_pos_postal',
        'th_pos_tabla',
        'th_pos_estado',
        'th_pos_fecha_creacion',
        'th_pos_fecha_modificacion',
        'PERFIL',
        // 'PASS',
        'th_pos_foto_url',
        'th_pos_contratado',
    ];

    function listarJoin()
    {
        // Construir la parte JOIN de la consulta
        $this->join('th_ciudad', 'th_postulantes.th_ciu_id = th_ciudad.th_ciu_id');
        $this->join('th_provincias', 'th_ciudad.th_prov_id = th_provincias.th_prov_id');
        $this->join('th_parroquias', 'th_postulantes.th_parr_id = th_parroquias.th_parr_id');

        // Aplicar condiciones WHERE para cada tabla
        $this->where('th_provincias.th_prov_estado', '1');
        $this->where('th_ciudad.th_ciu_estado', '1');
        $this->where('th_parroquias.th_parr_estado', '1');

        // Ejecutar la consulta y obtener los datos
        $datos = $this->listar();

        return $datos;
    }

    function agregar_postulante_personaM($cedula)
    {
        $sql =
            "INSERT INTO th_personas (
                            th_per_primer_apellido,
                            th_per_segundo_apellido,
                            th_per_primer_nombre,
                            th_per_segundo_nombre,
                            th_per_cedula,
                            th_per_sexo,
                            th_per_fecha_nacimiento,
                            th_per_telefono_1,
                            th_per_telefono_2,
                            th_per_correo,
                            th_per_direccion,
                            th_per_estado_civil,
                            th_prov_id,
                            th_ciu_id,
                            th_parr_id,
                            th_per_postal,
                            -- th_per_fecha_admision,
                            th_per_id_comunidad,
                            th_per_tabla_union
                            ) 
                            SELECT
                            th_pos_primer_apellido,
                            th_pos_segundo_apellido,
                            th_pos_primer_nombre,
                            th_pos_segundo_nombre,
                            th_pos_cedula,
                            th_pos_sexo,
                            th_pos_fecha_nacimiento,
                            th_pos_telefono_1,
                            th_pos_telefono_2,
                            th_pos_correo,
                            th_pos_direccion,
                            th_pos_estado_civil,
                            th_prov_id,
                            th_ciu_id,
                            th_parr_id,
                            th_pos_postal,
                            -- GETDATE(),
                            th_pos_id,
                            'th_postulantes'
                            FROM th_postulantes p
                            WHERE th_pos_cedula = '$cedula'
                            AND NOT EXISTS (
                            SELECT 1 FROM th_personas pe WHERE pe.th_per_cedula = p.th_pos_cedula
                            );
                            ";

        //print_r($sql); exit(); die();
        $datos = $this->db->sql_string($sql);
        return $datos;
    }

   public function listarNoContratados($id_plaza = '', $coincidencias = false)
{
    // Normalizar id_plaza
    $id = ($id_plaza !== '' && $id_plaza !== null) ? (int)$id_plaza : '';

    // Base del SELECT con aliases
    $sql = "
        SELECT DISTINCT
            t.th_pos_id         AS _id,
            t.th_pos_primer_nombre   AS primer_nombre,
            t.th_pos_segundo_nombre  AS segundo_nombre,
            t.th_pos_primer_apellido AS primer_apellido,
            t.th_pos_segundo_apellido AS segundo_apellido,
            t.th_pos_cedula     AS cedula,
            t.th_pos_sexo       AS sexo,
            t.th_pos_fecha_nacimiento AS fecha_nacimiento,
            t.th_pos_nacionalidad     AS nacionalidad,
            t.th_pos_estado_civil     AS estado_civil,
            t.th_pos_telefono_1  AS telefono_1,
            t.th_pos_telefono_2  AS telefono_2,
            t.th_pos_correo     AS correo,
            t.th_prov_id        AS id_provincia,
            t.th_ciu_id         AS id_ciudad,
            t.th_parr_id        AS id_parroquia,
            t.th_pos_direccion  AS direccion,
            t.th_pos_postal     AS codigo_postal,
            t.th_pos_tabla      AS tabla_origen,
            t.th_pos_estado     AS estado,
            t.th_pos_fecha_creacion   AS fecha_creacion,
            t.th_pos_fecha_modificacion AS fecha_modificacion,
            t.PERFIL            AS perfil,
            t.th_pos_foto_url   AS foto_url,
            t.th_pos_contratado AS contratado,
            CONCAT(
                COALESCE(t.th_pos_primer_nombre,''), ' ',
                COALESCE(t.th_pos_segundo_nombre,''), ' ',
                COALESCE(t.th_pos_primer_apellido,''), ' ',
                COALESCE(t.th_pos_segundo_apellido,'')
            ) AS nombre_completo
        FROM th_postulantes t
        WHERE t.th_pos_contratado = 0
          AND t.th_pos_estado = 1
    ";

    // Si nos pasan id de plaza, SIEMPRE excluimos postulantes que ya están en esa plaza
    if ($id !== '') {
        $sql .= "
            AND NOT EXISTS (
                SELECT 1
                FROM th_contr_postulaciones p
                WHERE p.th_postulante_id = t.th_pos_id
                  AND p.th_pla_id = {$id}
                  AND p.th_posu_estado = 1
            )
        ";

        // Si coincidencias = true, aplicamos filtros
        if ($coincidencias === true || $coincidencias === 'true' || $coincidencias === 1 || $coincidencias === '1') {
            $sql .= "
                AND (
                    -- Buscar coincidencias con el TÍTULO de la plaza
                    EXISTS (
                        SELECT 1 
                        FROM th_contr_plazas pla
                        INNER JOIN th_pos_formacion_academica fa ON fa.th_pos_id = t.th_pos_id
                        WHERE pla.th_pla_id = {$id}
                          AND pla.th_pla_estado = 1
                          AND fa.th_fora_estado = 1
                          AND (
                              fa.th_fora_titulo_obtenido LIKE CONCAT('%', pla.th_pla_titulo, '%')
                              OR pla.th_pla_titulo LIKE CONCAT('%', fa.th_fora_titulo_obtenido, '%')
                          )
                    )
                    OR
                    EXISTS (
                        SELECT 1 
                        FROM th_contr_plazas pla
                        INNER JOIN th_pos_experiencia_laboral el ON el.th_pos_id = t.th_pos_id
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
                    EXISTS (
                        SELECT 1 
                        FROM th_contr_plazas pla
                        INNER JOIN th_pos_certificaciones_capacitaciones cc ON cc.th_pos_id = t.th_pos_id
                        WHERE pla.th_pla_id = {$id}
                          AND pla.th_pla_estado = 1
                          AND cc.th_cert_estado = 1
                          AND (
                              cc.th_cert_nombre_curso LIKE CONCAT('%', pla.th_pla_titulo, '%')
                              OR pla.th_pla_titulo LIKE CONCAT('%', cc.th_cert_nombre_curso, '%')
                          )
                    )
                    OR
                    -- Buscar coincidencias con la DESCRIPCIÓN de la plaza
                    EXISTS (
                        SELECT 1 
                        FROM th_contr_plazas pla
                        INNER JOIN th_pos_formacion_academica fa ON fa.th_pos_id = t.th_pos_id
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
                    EXISTS (
                        SELECT 1 
                        FROM th_contr_plazas pla
                        INNER JOIN th_pos_experiencia_laboral el ON el.th_pos_id = t.th_pos_id
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
                    EXISTS (
                        SELECT 1 
                        FROM th_contr_plazas pla
                        INNER JOIN th_pos_certificaciones_capacitaciones cc ON cc.th_pos_id = t.th_pos_id
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
                    -- Buscar coincidencias con las OBSERVACIONES de la plaza
                    EXISTS (
                        SELECT 1 
                        FROM th_contr_plazas pla
                        INNER JOIN th_pos_formacion_academica fa ON fa.th_pos_id = t.th_pos_id
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
                    EXISTS (
                        SELECT 1 
                        FROM th_contr_plazas pla
                        INNER JOIN th_pos_experiencia_laboral el ON el.th_pos_id = t.th_pos_id
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
                    EXISTS (
                        SELECT 1 
                        FROM th_contr_plazas pla
                        INNER JOIN th_pos_certificaciones_capacitaciones cc ON cc.th_pos_id = t.th_pos_id
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
                    -- Buscar coincidencias con los REQUISITOS
                    EXISTS (
                        SELECT 1 
                        FROM th_contr_requisitos req
                        INNER JOIN th_pos_formacion_academica fa ON fa.th_pos_id = t.th_pos_id
                        WHERE req.th_pla_id = {$id}
                          AND req.th_req_estado = 1
                          AND fa.th_fora_estado = 1
                          AND (
                              fa.th_fora_titulo_obtenido LIKE CONCAT('%', req.th_req_descripcion, '%')
                              OR req.th_req_descripcion LIKE CONCAT('%', fa.th_fora_titulo_obtenido, '%')
                          )
                    )
                    OR
                    EXISTS (
                        SELECT 1 
                        FROM th_contr_requisitos req
                        INNER JOIN th_pos_experiencia_laboral el ON el.th_pos_id = t.th_pos_id
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
                    EXISTS (
                        SELECT 1 
                        FROM th_contr_requisitos req
                        INNER JOIN th_pos_certificaciones_capacitaciones cc ON cc.th_pos_id = t.th_pos_id
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

    $sql .= " ORDER BY t.th_pos_primer_apellido, t.th_pos_primer_nombre";

    $datos = $this->db->datos($sql);
    return $datos;
}


}