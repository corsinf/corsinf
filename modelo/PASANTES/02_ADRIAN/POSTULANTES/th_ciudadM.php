<?php

require_once(dirname(__DIR__, 2) . '/BaseModel.php');

class th_provinciasM extends BaseModel
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
}