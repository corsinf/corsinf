<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class hub_catn_numero_pisoM extends BaseModel
{
    protected $tabla = 'hub_catn_numero_piso';

    protected $primaryKey = 'id_numero_piso AS _id';

    protected $camposPermitidos = [
        'descripcion AS descripcion',
        'estado AS estado',
        'fecha_creacion AS fecha_creacion'
    ];
}
