<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class ac_cat_unidad_medidaM extends BaseModel
{
    protected $tabla = 'ac_cat_unidad_medida';
    protected $primaryKey = 'ac_id_unidad AS _id';

    protected $camposPermitidos = [
        'ac_nombre AS nombre',
        'ac_simbolo AS simbolo',
        'ac_tipo AS tipo',
        'ac_descripcion AS descripcion',
        'ac_estado AS estado',
    ];
}
