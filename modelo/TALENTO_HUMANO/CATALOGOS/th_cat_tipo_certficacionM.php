<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cat_tipo_certficacionM extends BaseModel
{
    protected $tabla = 'th_cat_tipo_certficacion';

    // Clave primaria
    protected $primaryKey = 'id_certificacion AS _id';

    protected $camposPermitidos = [
        'descripcion AS descripcion',
        'estado AS estado',
        'fecha_creacion AS fecha_creacion'
    ];
}