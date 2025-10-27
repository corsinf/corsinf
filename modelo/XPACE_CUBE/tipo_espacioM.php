<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class tipo_espacioM extends BaseModel
{
    // Nombre real de la tabla en BD
    protected $tabla = 'hub_tipos_espacios';

    // Clave primaria
    protected $primaryKey = 'id_tipo_espacio AS _id';

    // Campos que puedes insertar/actualizar
    protected $camposPermitidos = [
        'nombre',
        'descripcion',
        'estado',
    ];
}
