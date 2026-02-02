<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cat_idiomasM extends BaseModel
{
    protected $tabla = 'th_cat_idiomas';

    // Clave primaria
    protected $primaryKey = 'id_idiomas AS _id';

    protected $camposPermitidos = [
        'descripcion AS descripcion',
        'estado AS estado',
        'fecha_creacion AS fecha_creacion'
    ];
}
