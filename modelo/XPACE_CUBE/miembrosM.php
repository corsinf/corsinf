<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class miembrosM extends BaseModel
{
    // Nombre real de la tabla
    protected $tabla = 'co_miembro';

    // Clave primaria con alias
    protected $primaryKey = 'id_miembro AS _id';

    // Campos permitidos con alias amigables
    protected $camposPermitidos = [
        'nombre_miembro AS nombre',
        'apellido_miembro AS apellido',
        'telefono_miembro AS telefono',
        'direccion_miembro AS direccion',
        'id_espacio',
        'estado'
    ];
}
