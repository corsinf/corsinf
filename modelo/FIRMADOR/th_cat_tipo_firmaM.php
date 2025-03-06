<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_cat_tipo_firmaM extends BaseModel
{
    protected $tabla = 'th_cat_tipo_firma';
    protected $primaryKey = 'th_tipfir_id AS _id';

    protected $camposPermitidos = [
        'th_tipfir_descripcion AS descripcion',
        'th_tipfir_perfir_estado AS estado',
        'th_tipfir_fecha_creacion AS fecha'
    ];
    
}
