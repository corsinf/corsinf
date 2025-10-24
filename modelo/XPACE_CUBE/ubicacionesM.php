<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class ubicacionesM extends BaseModel
{
    // Nombre real de la tabla en BD
    protected $tabla = 'hub_ubicaciones';

    // Clave primaria
    protected $primaryKey = 'id_ubicacion AS _id';

    // Campos que puedes insertar/actualizar
    protected $camposPermitidos = [
        'nombre',
        'direccion',
        'ciudad',
        'telefono',
        'zona_horaria',
        'estado',
    ];
}
