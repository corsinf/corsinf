<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class ac_cat_tipo_articuloM extends BaseModel
{
    protected $tabla = 'ac_cat_tipo_articulo';
    protected $primaryKey = 'ID_TIPO_ARTICULO AS _id';

    protected $camposPermitidos = [
        'CODIGO AS codigo',
        'DESCRIPCION AS descripcion',
        'ESTADO AS estado',
    ];
}