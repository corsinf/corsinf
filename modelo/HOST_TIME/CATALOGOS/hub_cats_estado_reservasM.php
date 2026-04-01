<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class hub_cats_estado_reservasM extends BaseModel
{
    protected $tabla = 'hub_cats_estado_reservas';

    protected $primaryKey = 'id_estado_reserva AS _id';

    protected $camposPermitidos = [
        'codigo AS codigo',
        'nombre AS nombre',
        'descripcion AS descripcion',
        'activo AS activo'
    ];
}