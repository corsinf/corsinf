<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class hub_cats_unidad_tiempoM extends BaseModel
{
    protected $tabla = 'hub_cats_unidad_tiempo';

    // Primary Key
    protected $primaryKey = 'id_unidad_tiempo AS _id';

    protected $camposPermitidos = [
        'nombre',
        'prefijo',
        'is_deleted',
        'id_usuario_crea',
        'fecha_creacion',
        'id_usuario_modifica',
        'fecha_modificacion'
    ];
}
