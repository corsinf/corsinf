<?php

require_once(dirname(__DIR__) . '/GENERAL/BaseModel.php');

class th_personasM extends BaseModel
{
    protected $tabla = 'th_personas';
    protected $primaryKey = 'th_per_id AS _id';


    /*
        Modulo Firmas cuando el estado en 2 es porque finalizo de llenar la solicitud
    */

    protected $camposPermitidos = [
        'th_per_id AS id',
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
        'th_per_tabla AS tabla',
        'th_per_id_comunidad AS id_comunidad',
        'th_per_tabla_union AS tabla_union',
        'th_per_estado AS estado',
        'th_per_fecha_creacion AS fecha_creacion',
        'th_per_fecha_modificacion AS fecha_modificacion',
        'PERFIL',
        // 'PASS',
        'th_per_codigo_sap AS codigo_sap',
        'th_per_unidad_org_sap AS unidad_org_sap',
        'th_per_tipo_sangre AS tipo_sangre',
        'th_per_nombres_completos AS nombres_completos',
        'th_per_codigo_externo_1 AS codigo_externo_1',
        'th_per_codigo_externo_2 AS codigo_externo_2',
        'th_per_codigo_externo_3 AS codigo_externo_3',
        'id_etnia',
        'id_orientacion_sexual',
        'id_identidad_genero',
        'id_religion',
        'th_per_correo_personal_1',
        'th_per_correo_personal_2',
        // 'th_per_etnia AS etnia',
        // 'th_per_orientacion AS orientacion',
        // 'th_per_religion AS religion',
        // 'th_per_tipo_discapacidad AS tipo_discapacidad',
        // 'th_per_porcentaje_discapacidad AS porcentaje_discapacidad',
        // 'th_per_escala_discapacidad AS escala_discapacidad',
        // 'th_per_fecha_ingreso AS fecha_ingreso',
        // 'th_per_anios_trabajo AS anios_trabajo',
        // 'th_per_seccion AS seccion',
        // 'th_per_remuneracion AS remuneracion',
        // 'th_per_clase_auto AS clase_auto',
        // 'th_per_placa_original AS placa_original',
        // 'th_per_placa_sintesis AS placa_sintesis',
        // 'th_per_comision_asuntos_sociales AS comision_asuntos_sociales',
    ];

    public function  obtener_persona_con_nombres($th_per_id = null)
    {
        // Condición base: solo activos
        $condicion = "p.th_per_estado = 1";
        if (!empty($th_per_id)) {
            $id = intval($th_per_id);
            $condicion .= " AND p.th_per_id = {$id}";
        }

        $sql =
            "SELECT
                p.th_per_id                          AS th_per_id,
                p.th_per_primer_nombre               AS primer_nombre,
                p.th_per_segundo_nombre              AS segundo_nombre,
                p.th_per_primer_apellido             AS primer_apellido,
                p.th_per_segundo_apellido            AS segundo_apellido,
                p.th_per_cedula                      AS cedula,
                p.th_per_estado_civil                AS estado_civil,
                p.th_per_sexo                        AS sexo,
                p.th_per_fecha_nacimiento            AS fecha_nacimiento,
                p.th_per_nacionalidad                AS nacionalidad,
                p.th_per_telefono_1                  AS telefono_1,
                p.th_per_telefono_2                  AS telefono_2,
                p.th_per_correo                      AS correo,
                p.th_per_direccion                   AS direccion,
                p.th_per_foto_url                    AS foto_url,
                p.th_prov_id                         AS id_provincia,
                p.th_ciu_id                          AS id_ciudad,
                p.th_parr_id                         AS id_parroquia,
                p.th_per_postal                      AS postal,
                p.th_per_observaciones               AS observaciones,
                p.th_per_tabla                       AS th_per_tabla,          
                p.th_per_id_comunidad                AS id_comunidad,
                p.th_per_tabla_union                 AS th_per_tabla_union,  
                p.th_per_estado                      AS estado,
                p.th_per_fecha_creacion              AS fecha_creacion,
                p.th_per_fecha_modificacion          AS fecha_modificacion,   
                p.PERFIL                             AS PERFIL,
                p.th_per_codigo_sap                  AS th_per_codigo_sap,
                p.th_per_unidad_org_sap              AS th_per_unidad_org_sap,
                p.th_per_tipo_sangre                 AS tipo_sangre,
                p.th_per_nombres_completos           AS th_per_nombres_completos,
                p.th_per_codigo_externo_1            AS th_per_codigo_externo_1,
                p.th_per_codigo_externo_2            AS th_per_codigo_externo_2,
                p.th_per_codigo_externo_3            AS th_per_codigo_externo_3,
                prov.th_prov_nombre                  AS th_prov_nombre,
                ciu.th_ciu_nombre                    AS th_ciu_nombre,
                parr.th_parr_nombre                  AS th_parr_nombre,
                p.id_etnia,
                p.id_orientacion_sexual,
                p.id_identidad_genero,
                p.id_religion,
                p.th_per_correo_personal_1             AS correo_personal_1,
                p.th_per_correo_personal_2             AS correo_personal_2,
                et.descripcion                       AS descripcion_etnia,
                ori_sex.descripcion                  AS descripcion_orientacion_sexual,
                rel.descripcion                      AS descripcion_religion,
                ide_gen.descripcion                  AS descripcion_identidad_genero,
                RTRIM(
                    CONCAT(
                        COALESCE(p.th_per_primer_apellido, ''), ' ',
                        COALESCE(p.th_per_segundo_apellido, ''), ' ',
                        COALESCE(p.th_per_primer_nombre, ''), ' ',
                        COALESCE(p.th_per_segundo_nombre, '')
                    )
                ) AS nombres_completos
            FROM th_personas p
            LEFT JOIN th_provincias prov
                ON p.th_prov_id = prov.th_prov_id
            LEFT JOIN th_ciudad ciu
                ON p.th_ciu_id = ciu.th_ciu_id
            LEFT JOIN th_parroquias parr
                ON p.th_parr_id = parr.th_parr_id
            LEFT JOIN th_cat_etnia et
                ON p.id_etnia = et.id_etnia
            LEFT JOIN th_cat_orientacion_sexual ori_sex
                ON p.id_orientacion_sexual = ori_sex.id_orientacion_sexual
            LEFT JOIN th_cat_religion rel
                ON p.id_religion = rel.id_religion
            LEFT JOIN th_cat_identidad_genero ide_gen
                ON p.id_identidad_genero = ide_gen.id_identidad_genero
            WHERE {$condicion}
            ORDER BY p.th_per_primer_apellido, p.th_per_primer_nombre;";

        $datos = $this->db->datos($sql);

        return $datos;
    }

    public function  obtener_per_estado_clave($th_per_id = null)
    {
        // Condición base: solo activos
        $condicion = "p.th_per_estado = 1";
        if (!empty($th_per_id)) {
            $id = intval($th_per_id);
            $condicion .= " AND p.th_per_id = {$id}";
        }

        $sql =
            "SELECT
                p.th_per_id                          AS th_per_id,
                p.th_per_primer_nombre               AS primer_nombre,
                p.th_per_segundo_nombre              AS segundo_nombre,
                p.th_per_primer_apellido             AS primer_apellido,
                p.th_per_segundo_apellido            AS segundo_apellido,
                p.th_per_cedula                      AS cedula,
                p.th_per_correo                      AS correo,
                p.th_per_estado                      AS estado,
                p.PERFIL                             AS PERFIL,
                p.POLITICAS_ACEPTACION               AS POLITICAS_ACEPTACION,
                p.th_pos_id                          AS id_postulante
            FROM th_personas p
            WHERE {$condicion};";

        $datos = $this->db->datos($sql);

        return $datos;
    }
}
