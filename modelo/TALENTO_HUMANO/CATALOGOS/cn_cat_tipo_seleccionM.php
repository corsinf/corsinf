<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class cn_cat_tipo_seleccionM extends BaseModel
{
    protected $tabla = 'cn_cat_tipo_seleccion';

    protected $primaryKey = 'id_tipo_seleccion AS _id';

    protected $camposPermitidos = [
        'descripcion AS descripcion',
        'estado AS estado',
        'fecha_creacion AS fecha_creacion'
    ];
}
