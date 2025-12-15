<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_contr_niveles_cargoM extends BaseModel
{
    // Nombre real de la tabla en la BD
    protected $tabla = 'th_contr_niveles_cargo';

    // Primary key (con alias como usas en tu proyecto)
    protected $primaryKey = 'th_niv_id AS _id';

    // Campos permitidos para inserción o actualización
    protected $camposPermitidos = [
        'th_niv_nombre AS nombre',
        'th_niv_descripcion AS descripcion',
        'th_niv_estado AS estado',
        'th_niv_fecha_creacion AS fecha_creacion',
        'th_niv_fecha_modificacion AS fecha_modificacion'
    ];
}