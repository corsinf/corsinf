<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_triangular_departamento_personaM extends BaseModel
{
    protected $tabla = 'th_triangular_departamento_persona';
    protected $primaryKey = 'th_tdp_id AS _id';

    protected $camposPermitidos = [
        'th_tri_id AS tri_id',
        'th_dep_id AS dep_id',
        'th_per_id AS per_id',
        'th_tdp_estado AS estado',
        'th_tdp_fecha_creacion AS fecha_creacion',
        'th_tdp_fecha_modificacion AS fecha_modificacion'
    ];
}
