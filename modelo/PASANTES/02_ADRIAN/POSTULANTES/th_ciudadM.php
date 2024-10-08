<?php

require_once(dirname(__DIR__, 2) . '/BaseModel.php');

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

    function buscar_ciudad($buscar)
    {
        $sql = "SELECT * FROM th_ciudad WHERE th_ciu_estado = 1 AND th_ciu_nombre LIKE '%" . $buscar . "%'";

        $datos = $this->db->datos($sql);
        return $datos;
    }
}