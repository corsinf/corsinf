<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cat_nominaM extends BaseModel
{
    protected $tabla = 'th_cat_nomina';
    protected $primaryKey = 'id_nomina AS _id';
    protected $camposPermitidos = [
        'codigo AS codigo',
        'nombre AS nombre',
        'tipo AS tipo',
        'estado AS estado',
        'fecha_creacion AS fecha_creacion',
        'fecha_modificacion AS fecha_modificacion'
    ];
}
