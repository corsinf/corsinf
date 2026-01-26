<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class sa_cat_cie_10M extends BaseModel
{
    protected $tabla = 'sa_cat_cie_10';

    protected $primaryKey = 'id AS _id';

    protected $camposPermitidos = [
        'codigo AS codigo',
        'descripcion AS descripcion',
        'estado AS estado',
        'fecha_creacion AS fecha_creacion',
        'fecha_modificacion AS fecha_modificacion'
    ];
}