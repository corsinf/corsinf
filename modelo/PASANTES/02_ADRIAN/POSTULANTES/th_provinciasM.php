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
}