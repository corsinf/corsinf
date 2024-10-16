<?php

require_once(dirname(__DIR__, 2) . '/BaseModel.php');

class th_provinciasM extends BaseModel
{
    protected $tabla = 'th_provincias';
    protected $primaryKey = 'th_prov_id';

    protected $camposPermitidos = [
        'th_prov_nombre',
        'th_prov_estado',
        'th_prov_fecha_creacion',
        'th_prov_fecha_modificacion',
    ];

    function buscar_provincia($buscar)
    {
        $sql = "SELECT * FROM th_provincias WHERE th_prov_estado = 1 AND th_prov_nombre LIKE '%" . $buscar . "%'";

        $datos = $this->db->datos($sql);
        return $datos;
    }
}
