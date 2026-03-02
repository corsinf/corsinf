<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class cn_plaza_historialM extends BaseModel
{
    protected $tabla = 'cn_plaza_historial';
    protected $primaryKey = 'id_plaza_historial AS _id';
    protected $camposPermitidos = [
        'cn_pla_id',
        'id_plaza_estados',
        'id_usuario',
        'accion',
        'fecha_creacion',
    ];
}