<?php

require_once(dirname(__DIR__, 2) . '/BaseModel.php');

class th_certificaciones_capacitacionesM extends BaseModel
{
    protected $tabla = 'th_pos_certificaciones_capacitaciones';
    protected $primaryKey = 'th_cert_id AS _id';

    protected $camposPermitidos = [
        'th_cert_id',
        'th_pos_id',
        'th_cert_nombre_curso',
        'th_cert_ruta_archivo',
        'th_cert_estado',
        'th_cert_fecha_creacion',
        'th_cert_fecha_modificacion',

    ];
}
