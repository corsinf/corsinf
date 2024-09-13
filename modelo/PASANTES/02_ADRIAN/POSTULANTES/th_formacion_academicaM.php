<?php

require_once(dirname(__DIR__, 2) . '/BaseModel.php');

class th_formacion_academicaM extends BaseModel
{
    protected $tabla = 'th_formacion_academica';
    protected $primaryKey = 'th_fora_id_formacion_academica AS _id';

    protected $camposPermitidos = [
        'th_fora_titulo_obtenido',
        'th_fora_institución',
        'th_fora_fecha_inicio_formacion',
        'th_fora_fecha_fin_formacion',
        'th_fora_estado',
        'th_fora_fecha_creacion',
        'th_fora_fecha_modificacion',

    ];
}
