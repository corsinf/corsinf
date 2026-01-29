<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_pos_formacion_academicaM extends BaseModel
{
    protected $tabla = 'th_pos_formacion_academica';
    protected $primaryKey = 'th_fora_id AS _id';

    protected $camposPermitidos = [
        'th_fora_titulo_obtenido',
        'th_fora_institución',
        'th_fora_fecha_inicio_formacion',
        'th_fora_fecha_fin_formacion',
        'th_fora_estado',
        'th_fora_fecha_creacion',
        'th_fora_fecha_modificacion',
        'th_fora_registro_senescyt',
        'id_nivel_academico',
        'id_pais',
    ];


    public function listar_formacion_academica_con_nivel($id = null)
    {
        $sql = "
        SELECT
            fa.th_fora_id AS _id,
            fa.th_fora_titulo_obtenido,
            fa.th_fora_titulo_obtenido,
            fa.[th_fora_institución],
            fa.th_fora_fecha_inicio_formacion,
            fa.th_fora_fecha_fin_formacion,
            fa.th_fora_registro_senescyt,
            fa.id_nivel_academico,
            fa.id_pais,
            na.descripcion AS nivel_academico_descripcion,
            pa.nombre AS pais_nombre,
            pa.codigo AS pais_codigo
        FROM th_pos_formacion_academica fa
        LEFT JOIN th_cat_pos_nivel_academico na
            ON fa.id_nivel_academico = na.id_nivel_academico
            LEFT JOIN th_cat_pais pa
            ON fa.id_pais = pa.id_pais
            AND na.estado = 1
    ";

        if (!empty($id)) {
            $id = intval($id);
            $sql .= " WHERE fa.th_fora_id = $id";
        }

        return $this->db->datos($sql);
    }


    public function listar_formacion_academica_con_nivel_id($th_pos_id = null)
    {
        $sql = "
        SELECT
            fa.th_fora_id AS _id,
            fa.th_fora_titulo_obtenido,
            fa.th_fora_institución,
            fa.th_fora_fecha_inicio_formacion,
            fa.th_fora_fecha_fin_formacion,
            fa.th_fora_registro_senescyt,
            fa.id_nivel_academico,
            na.descripcion AS nivel_academico_descripcion,
            pa.nombre AS pais_nombre,
            pa.codigo AS pais_codigo
        FROM th_pos_formacion_academica fa
        LEFT JOIN th_cat_pos_nivel_academico na
            ON fa.id_nivel_academico = na.id_nivel_academico
        LEFT JOIN th_cat_pais pa
            ON fa.id_pais = pa.id_pais
            AND na.estado = 1
        WHERE fa.th_fora_estado = 1
    ";

        if (!empty($th_pos_id)) {
            $th_pos_id = intval($th_pos_id);
            $sql .= " AND fa.th_pos_id = $th_pos_id";
        }

        $sql .= " ORDER BY fa.th_fora_titulo_obtenido ASC";

        return $this->db->datos($sql);
    }
}
