<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cat_rango_profesionalM extends BaseModel
{
    protected $tabla = 'th_cat_rango_profesional';

    protected $primaryKey = 'id_rango_profesional AS _id';

    protected $camposPermitidos = [
        'nombre AS nombre',
        'descripcion AS descripcion',
        'estado AS estado',
        'fecha_creacion AS fecha_creacion',
        'fecha_modificacion AS fecha_modificacion'
    ];
}