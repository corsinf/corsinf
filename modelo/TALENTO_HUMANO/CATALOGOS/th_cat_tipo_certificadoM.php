<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cat_tipo_certificadoM extends BaseModel
{
    protected $tabla = 'th_cat_tipo_certificado';

    protected $primaryKey = 'id_certificado AS _id';

    protected $camposPermitidos = [
        'descripcion AS descripcion',
        'estado AS estado',
        'fecha_creacion AS fecha_creacion'
    ];
}
