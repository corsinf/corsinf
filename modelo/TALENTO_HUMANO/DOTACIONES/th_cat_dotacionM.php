<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cat_dotacionM extends BaseModel
{
    protected $tabla = 'th_cat_dotacion';

    protected $primaryKey = 'id_dotacion AS _id';

    protected $camposPermitidos = [
        'nombre AS nombre',
        'descripcion AS descripcion',
        'es_reutilizable AS es_reutilizable',
        'estado AS estado',
        'fecha_creacion AS fecha_creacion'
    ];
}