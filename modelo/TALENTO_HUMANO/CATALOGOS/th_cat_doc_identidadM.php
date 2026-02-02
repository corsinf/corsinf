<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cat_doc_identidadM extends BaseModel
{
    protected $tabla = 'th_cat_doc_identidad';
    protected $primaryKey = 'id_documento AS _id';
    protected $camposPermitidos = [
        'descripcion AS descripcion',
        'estado AS estado',
        'fecha_creacion AS fecha_creacion'
    ];
}
