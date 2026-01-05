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
}
