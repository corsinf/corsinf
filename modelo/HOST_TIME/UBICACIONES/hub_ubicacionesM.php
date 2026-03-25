<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class hub_ubicacionesM extends BaseModel
{
    protected $tabla = 'hub_ubicaciones';

    protected $primaryKey = 'id_ubicacion AS _id';

    protected $camposPermitidos = [
        'nombre',
        'direccion',
        'telefono',
        'is_deleted',
        'th_prov_id',
        'th_ciu_id',
        'th_parr_id',
    ];


    public function listar_por_id($id)
    {
        $id = intval($id);
        $sql = "
        SELECT
            u.id_ubicacion AS _id,
            u.nombre,
            u.direccion,
            u.telefono,
            u.th_prov_id,
            u.th_ciu_id,
            u.th_parr_id,
            p.th_prov_nombre  AS provincia,
            c.th_ciu_nombre  AS ciudad,
            pa.th_parr_nombre AS parroquia
        FROM hub_ubicaciones u
        LEFT JOIN th_provincias  p  ON u.th_prov_id  = p.th_prov_id
        LEFT JOIN th_ciudad    c  ON u.th_ciu_id   = c.th_ciu_id
        LEFT JOIN th_parroquias  pa ON u.th_parr_id  = pa.th_parr_id
        WHERE u.is_deleted = 0
          AND u.id_ubicacion = {$id}
    ";
        return  $this->db->datos($sql);
    }
}
