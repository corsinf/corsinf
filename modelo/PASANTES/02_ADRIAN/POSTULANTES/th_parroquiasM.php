<?php

require_once(dirname(__DIR__, 2) . '/BaseModel.php');

class th_provinciasM extends BaseModel
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
}