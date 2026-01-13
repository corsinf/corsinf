<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_per_comisionM extends BaseModel
{
    protected $tabla = 'th_per_comision';
    protected $primaryKey = 'th_per_com_id AS _id';

    protected $camposPermitidos = [
        'th_per_id',
        'id_comision',
        'th_per_com_estado',
        'th_per_com_fecha_creacion',
        'th_per_com_fecha_modificacion'
    ];

    public function listar_comision_por_persona($id)
    {
        $id = intval($id);

        $sql = "
            SELECT 
                pc.th_per_com_id AS _id,
                pc.th_per_id,
                pc.id_comision,
                c.codigo  AS comision_codigo,
                c.nombre  AS comision_nombre,
                c.descripcion AS comision_descripcion
            FROM th_per_comision pc
            LEFT JOIN th_cat_comision c 
                ON pc.id_comision = c.id_comision
            WHERE pc.th_per_id = $id
              AND pc.th_per_com_estado = 1
            ORDER BY pc.th_per_com_fecha_creacion DESC
        ";

        return $this->db->datos($sql);
    }

    public function listar_comision_por_id($id)
    {
        $id = intval($id);

        $sql = "
            SELECT 
                pc.th_per_com_id AS _id,
                pc.th_per_id,
                pc.id_comision,
                c.codigo  AS comision_codigo,
                c.nombre  AS comision_nombre,
                c.descripcion AS comision_descripcion
            FROM th_per_comision pc
            LEFT JOIN th_cat_comision c 
                ON pc.id_comision = c.id_comision
            WHERE pc.th_per_com_id = $id
              AND pc.th_per_com_estado = 1
        ";

        return $this->db->datos($sql);
    }

    function listar_personas_comisiones($id_comision = '')
    {
        $sql = "
        SELECT
            per_com.th_per_com_id AS _id,
            per.th_per_id AS id_persona,
            per.th_per_id_comunidad AS id_comunidad,
            per.th_per_primer_apellido AS primer_apellido,
            per.th_per_segundo_apellido AS segundo_apellido,
            per.th_per_primer_nombre AS primer_nombre,
            per.th_per_segundo_nombre AS segundo_nombre,
            per.th_per_cedula AS cedula,
            per.th_per_telefono_1 AS telefono_1,
            per.th_per_correo AS correo,
            per.th_pos_id AS _id_postulante,
            com.nombre AS nombre_comision
        FROM th_per_comision per_com
        INNER JOIN th_personas per 
            ON per_com.th_per_id = per.th_per_id
        INNER JOIN th_cat_comision com 
            ON per_com.id_comision = com.id_comision
        WHERE per.th_per_estado = 1
          AND per_com.th_per_com_estado = 1
    ";

        if ($id_comision !== '' && $id_comision !== null) {
            $sql .= " AND per_com.id_comision = '$id_comision'";
        }

        $sql .= ";";

        return $this->db->datos($sql);
    }


    function listar_personas_modal_comision($id_comision)
    {
        if (!empty($id_comision)) {

            $id_comision = intval($id_comision);

            $sql = "
            SELECT DISTINCT
                per.th_per_id AS _id,
                per.th_per_primer_apellido AS primer_apellido,
                per.th_per_segundo_apellido AS segundo_apellido,
                per.th_per_primer_nombre AS primer_nombre,
                per.th_per_segundo_nombre AS segundo_nombre,
                per.th_per_cedula AS cedula,
                per.th_per_telefono_1 AS telefono_1,
                per.th_per_correo AS correo
            FROM _talentoh.th_personas per
            INNER JOIN _talentoh.th_personas_departamentos pd
                ON pd.th_per_id = per.th_per_id
            WHERE
                per.th_per_estado = 1
                AND per.th_per_id NOT IN (
                    SELECT pc.th_per_id
                    FROM _talentoh.th_per_comision pc
                    WHERE pc.id_comision = $id_comision
                      AND pc.th_per_com_estado = 1
                );
        ";

            return $this->db->datos($sql, false, false, true);
        }

        return null;
    }
}
