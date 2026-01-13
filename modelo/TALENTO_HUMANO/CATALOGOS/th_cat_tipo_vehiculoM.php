<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cat_tipo_vehiculoM extends BaseModel
{
    protected $tabla = 'th_cat_tipo_vehiculo';
    protected $primaryKey = 'id_vehiculo AS _id';

    protected $camposPermitidos = [
        'descripcion',
        'estado',
        'fecha_creacion',
    ];

    

}
