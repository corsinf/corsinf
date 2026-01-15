<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cat_tallaM extends BaseModel
{
    protected $tabla = 'th_cat_talla';

    protected $primaryKey = 'id_talla AS _id';

    protected $camposPermitidos = [
        'codigo AS codigo',
        'descripcion AS descripcion',
        'tipo AS tipo',
        'estado AS estado',
        'fecha_creacion AS fecha_creacion'
    ];
}