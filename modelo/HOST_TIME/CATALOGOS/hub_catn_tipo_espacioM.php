<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class hub_catn_tipo_espacioM extends BaseModel
{
    protected $tabla = 'hub_catn_tipo_espacios';

    // Clave primaria
    protected $primaryKey = 'id_tipo_espacio AS _id';

    protected $camposPermitidos = [
        'nombre',
        'descripcion',
        'estado',
    ];
}
