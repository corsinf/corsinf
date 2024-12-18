<?php

require_once(dirname(__DIR__) . '/GENERAL/BaseModel.php');

class th_parroquiasM extends BaseModel
{
    protected $tabla = 'th_parroquias';
    protected $primaryKey = 'th_parr_id';

    protected $camposPermitidos = [
        'th_parr_nombre',
        'th_ciu_id',
        'th_prov_id',
        'th_parr_estado',
        'th_parr_fecha_creacion',
        'th_parr_fecha_modificacion',
    ];

    function buscar_parroquias($buscar, $th_ciu_id)
    {
        $sql = "SELECT * FROM th_parroquias WHERE th_parr_estado = 1 AND th_parr_nombre LIKE '%" . $buscar . "%'";

        if ($th_ciu_id !== null) {
            $sql .= " AND th_ciu_id = " . intval($th_ciu_id);
        }

        $datos = $this->db->datos($sql);
        return $datos;
    }
}