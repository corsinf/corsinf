<?php
require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_pos_discapacidadM extends BaseModel
{
    protected $tabla = 'th_pos_discapacidad';
    protected $primaryKey = 'th_pos_dis_id AS _id';

    protected $camposPermitidos = [
        'th_pos_id',
        'id_discapacidad',
        'id_escala_dis',
        'th_pos_dis_porcentaje',
        'th_pos_dis_escala',
        'th_pos_dis_estado',
        'th_pos_dis_fecha_creacion',
        'th_pos_dis_fecha_modificacion'
    ];

    public function listar_por_persona($id)
    {
        $id = intval($id);

        $sql = "
        SELECT
            pd.th_pos_dis_id AS _id,
            pd.th_pos_id,
            pd.id_discapacidad,
            pd.id_escala_dis,
            e.descripcion AS escala_discapacidad,
            pd.th_pos_dis_porcentaje,
            pd.th_pos_dis_escala,
            d.descripcion AS discapacidad
        FROM th_pos_discapacidad pd
        INNER JOIN th_cat_discapacidad d
            ON pd.id_discapacidad = d.id_discapacidad
        INNER JOIN th_cat_discapacidad_escala e
            ON pd.id_escala_dis = e.id_escala_dis
        WHERE pd.th_pos_id = $id
    ";

        return $this->db->datos($sql);
    }

    public function listar_por_id($id)
    {
        $id = intval($id);

        $sql = "
        SELECT
            d.th_pos_dis_id AS _id,
            d.th_pos_id,
            d.id_discapacidad,
            d.id_escala_dis,
            e.descripcion AS escala_discapacidad,
            c.descripcion AS discapacidad,
            d.th_pos_dis_porcentaje,
            d.th_pos_dis_escala
        FROM th_pos_discapacidad d
        INNER JOIN th_cat_discapacidad c
            ON d.id_discapacidad = c.id_discapacidad
        INNER JOIN th_cat_discapacidad_escala e
            ON d.id_escala_dis = e.id_escala_dis
        WHERE d.th_pos_dis_id = $id
    ";

        return $this->db->datos($sql);
    }
}
