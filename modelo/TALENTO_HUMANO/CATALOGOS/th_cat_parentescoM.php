<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cat_parentescoM extends BaseModel
{
    protected $tabla = 'th_cat_parentesco';
    protected $primaryKey = 'id_parentesco AS _id';

    protected $camposPermitidos = [
        'id_parentesco AS id_parentesco',
        'descripcion AS descripcion',
        'estado AS estado',
        'cantidad AS cantidad',
        'fecha_creacion AS fecha_creacion'
    ];
}
