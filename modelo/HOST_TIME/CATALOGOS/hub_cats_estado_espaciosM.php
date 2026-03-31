<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class hub_cats_estado_espaciosM extends BaseModel
{
    protected $tabla = 'hub_cats_estado_espacios';

    protected $primaryKey = 'id_estado_espacio AS _id';

    protected $camposPermitidos = [
        'codigo',
        'nombre',
        'categoria',
        'descripcion',
        'activo',
    ];
}
