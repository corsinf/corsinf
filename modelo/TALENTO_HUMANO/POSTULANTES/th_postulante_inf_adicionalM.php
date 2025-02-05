<?php

require_once(dirname(__DIR__, 2) . '/BaseModel.php');

class th_postulante_inf_adicionalM extends BaseModel
{
    protected $tabla = 'th_postulante_inf_adicional';
    protected $primaryKey = 'th_posa_id AS _id';

    protected $camposPermitidos = [
        'th_posa_direccion_calle',
        'th_posa_direccion_numero',
        'th_posa_direccion_ciudad',
        'th_posa_direccion_estado',
        'th_posa_direccion_codpos',
        'th_posa_estado',
        'th_posa_fecha_creacion',
        'th_posa_fecha_modificacion',
    ];
}
