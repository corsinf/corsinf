<?php

require_once(dirname(__DIR__) . '/GENERAL/BaseModel.php');

class th_ciudadM extends BaseModel
{
    protected $tabla = 'th_ciudad';
    protected $primaryKey = 'th_ciu_id';

    protected $camposPermitidos = [
        'th_ciu_nombre',
        'th_prov_id',
        'th_ciu_estado',
        'th_ciu_fecha_creacion',
        'th_ciu_fecha_modificacion',
    ];

    function buscar_ciudad($buscar, $th_prov_id)
    {
        $sql = "SELECT * FROM th_ciudad WHERE th_ciu_estado = 1 AND th_ciu_nombre LIKE '%" . $buscar . "%'";

        if ($th_prov_id !== null) {
            $sql .= " AND th_prov_id = " . intval($th_prov_id);
        }

        $datos = $this->db->datos($sql);
        return $datos;
    }
}