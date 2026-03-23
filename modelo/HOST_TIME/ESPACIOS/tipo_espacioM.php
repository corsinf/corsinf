<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class tipo_espacioM extends BaseModel
{
    protected $tabla = 'hub_tipos_espacios';

    // Clave primaria
    protected $primaryKey = 'id_tipo_espacio AS _id';

    protected $camposPermitidos = [
        'nombre',
        'descripcion',
        'estado',
    ];
}
