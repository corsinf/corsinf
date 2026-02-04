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

    public function listarNoContratados($id_plaza = '')
    {
        $id_plaza = intval($id_plaza);

        $sql = "
        SELECT DISTINCT
            t.th_pos_id AS _id,
            t.th_pos_primer_nombre AS primer_nombre,
            t.th_pos_segundo_nombre AS segundo_nombre,
            t.th_pos_primer_apellido AS primer_apellido,
            t.th_pos_segundo_apellido AS segundo_apellido,
            t.th_pos_cedula AS cedula,
            t.th_pos_sexo AS sexo,
            t.th_pos_fecha_nacimiento AS fecha_nacimiento,
            t.th_pos_nacionalidad AS nacionalidad,
            t.th_pos_estado_civil AS estado_civil,
            t.th_pos_telefono_1 AS telefono_1,
            t.th_pos_telefono_2 AS telefono_2,
            t.th_pos_correo AS correo,
            t.th_prov_id AS id_provincia,
            t.th_ciu_id AS id_ciudad,
            t.th_parr_id AS id_parroquia,
            t.th_pos_direccion AS direccion,
            t.th_pos_postal AS codigo_postal,
            t.PERFIL AS perfil,
            t.th_pos_foto_url AS foto_url,
            t.th_pos_contratado AS contratado,
            CONCAT(
                COALESCE(t.th_pos_primer_apellido, ''), ' ',
                COALESCE(t.th_pos_segundo_apellido, ''), ' ',
                COALESCE(t.th_pos_primer_nombre, ''), ' ',
                COALESCE(t.th_pos_segundo_nombre, '')
            ) AS nombre_completo
        FROM th_postulantes t
        LEFT JOIN th_contr_postulaciones p
            ON p.th_postulante_id = t.th_pos_id
            AND p.th_pla_id = $id_plaza
            AND p.th_posu_estado = 1
        WHERE 
            t.th_pos_contratado = 0
            AND t.th_pos_estado = 1
            AND p.th_postulante_id IS NULL
        ORDER BY 
            primer_apellido,
            primer_nombre
    ";

        return $this->db->datos($sql);
    }

    public function vincular_persona_postulante($id_persona)
    {
        // print_r($id_persona);
        // die();

        $sql = "DECLARE @th_pos_id INT;
                    EXEC _talentoh.SP_VINCULAR_PERSONA_POSTULANTE
                    @p_th_per_id = $id_persona,
                    @o_th_pos_id = @th_pos_id OUTPUT;

                SELECT @th_pos_id AS th_pos_id;";

        return $this->db->datos($sql);
    }

    public function obtener_postulante_por_id($th_pos_id = null)
    {
        // Condición base: solo postulantes activos
        $condicion = "pos.th_pos_estado = 1";

        if (!empty($th_pos_id)) {
            $id = intval($th_pos_id);
            $condicion .= " AND pos.th_pos_id = {$id}";
        }

        $sql = "SELECT
            pos.th_pos_id,
            pos.th_pos_primer_nombre,
            pos.th_pos_segundo_nombre,
            pos.th_pos_primer_apellido,
            pos.th_pos_segundo_apellido,
            pos.th_pos_cedula,
            pos.th_pos_sexo,
            pos.th_pos_fecha_nacimiento,
            pos.th_pos_nacionalidad,
            pos.th_pos_estado_civil,
            pos.th_pos_telefono_1,
            pos.th_pos_telefono_2,
            pos.th_pos_correo,
            pos.th_pos_correo_personal_1,
            pos.th_pos_correo_personal_2,
            pos.th_pos_direccion,
            pos.th_pos_postal,
            pos.th_pos_observaciones,
            pos.th_pos_tipo_sangre,
            pos.th_pos_foto_url,
            pos.th_pos_contratado,
            pos.th_pos_tabla,
            pos.PERFIL,

            pos.th_prov_id,
            pos.th_ciu_id,
            pos.th_parr_id,

            prov.th_prov_nombre,
            ciu.th_ciu_nombre,
            parr.th_parr_nombre,

            pos.id_etnia,
            pos.id_orientacion_sexual,
            pos.id_identidad_genero,
            pos.id_religion,
            pos.id_origen_indigena,

            et.descripcion                       AS descripcion_etnia,
            ori_sex.descripcion                  AS descripcion_orientacion_sexual,
            rel.descripcion                      AS descripcion_religion,
            ide_gen.descripcion                  AS descripcion_identidad_genero,
            tip_sex.descripcion                  AS descripcion_sexo,
            est_civ.descripcion                  AS descripcion_estado_civil,
            tip_ori_ind.descripcion              AS descripcion_origen_indigena,
            pa.nacionalidad                      AS descripcion_nacionalidad,
            tip_sag.descripcion                  AS descripcion_tipo_sangre,

            RTRIM(
                CONCAT(
                    COALESCE(pos.th_pos_primer_apellido, ''), ' ',
                    COALESCE(pos.th_pos_segundo_apellido, ''), ' ',
                    COALESCE(pos.th_pos_primer_nombre, ''), ' ',
                    COALESCE(pos.th_pos_segundo_nombre, '')
                )
            ) nombres_completos,

            pos.th_pos_fecha_creacion,
            pos.th_pos_fecha_modificacion

        FROM th_postulantes pos
        LEFT JOIN th_provincias prov
            ON pos.th_prov_id = prov.th_prov_id
        LEFT JOIN th_ciudad ciu
            ON pos.th_ciu_id = ciu.th_ciu_id
        LEFT JOIN th_parroquias parr
            ON pos.th_parr_id = parr.th_parr_id
        LEFT JOIN th_cat_etnia et
            ON pos.id_etnia = et.id_etnia
        LEFT JOIN th_cat_orientacion_sexual ori_sex
            ON pos.id_orientacion_sexual = ori_sex.id_orientacion_sexual
        LEFT JOIN th_cat_religion rel
            ON pos.id_religion = rel.id_religion
        LEFT JOIN th_cat_identidad_genero ide_gen
            ON pos.id_identidad_genero = ide_gen.id_identidad_genero
        LEFT JOIN th_cat_tipo_sexo tip_sex
            ON pos.th_pos_sexo = tip_sex.id_sexo
        LEFT JOIN th_cat_tipo_estado_civil est_civ
            ON pos.th_pos_estado_civil = est_civ.id_tipo_estado_civil
        LEFT JOIN th_cat_tipo_origen_indigena tip_ori_ind
            ON pos.id_origen_indigena = tip_ori_ind.id_origen_indigena
        LEFT JOIN th_cat_pais pa
            ON pos.th_pos_nacionalidad = pa.id_pais
        LEFT JOIN th_cat_tipo_sangre tip_sag
            ON pos.th_pos_tipo_sangre = tip_sag.id_tipo_sangre
        WHERE {$condicion}
        ORDER BY pos.th_pos_primer_apellido, pos.th_pos_primer_nombre";

        return $this->db->datos($sql);
    }
}
