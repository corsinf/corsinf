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
}
