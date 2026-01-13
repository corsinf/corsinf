<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cat_cargoM extends BaseModel
{
    protected $tabla = 'th_cat_cargo';

    protected $primaryKey = 'id_cargo AS _id';

    protected $camposPermitidos = [
        'nombre AS nombre',
        'descripcion AS descripcion',
        'estado AS estado',
        'fecha_creacion AS fecha_creacion'
    ];
}
