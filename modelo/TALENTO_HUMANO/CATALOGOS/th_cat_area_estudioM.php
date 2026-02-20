<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cat_area_estudioM extends BaseModel
{
    protected $tabla = 'th_cat_area_estudio';
    protected $primaryKey = 'id_area_estudio AS _id';

    protected $camposPermitidos = [
        'descripcion',
        'estado',
        'fecha_creacion',
    ];

    

}
