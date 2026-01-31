<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cat_idiomas_nivelM extends BaseModel
{
    protected $tabla = 'th_cat_idiomas_nivel';

    // Clave primaria
    protected $primaryKey = 'id_idiomas_nivel AS _id';

    protected $camposPermitidos = [
        'descripcion AS descripcion',
        'estado AS estado',
        'fecha_creacion AS fecha_creacion'
    ];
}
