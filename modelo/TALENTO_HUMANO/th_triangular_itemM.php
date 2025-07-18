<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_triangular_itemM extends BaseModel
{
    protected $tabla = 'th_triangular_item';
    protected $primaryKey = 'th_itr_id AS _id';

    protected $camposPermitidos = [
        'th_tri_id AS tri_id',
        'th_itr_longitud AS longitud',
        'th_itr_latitud AS latitud',
        'th_itr_n_punto AS n_punto',
        'th_itr_fecha_creacion AS fecha_creacion'

    ];
}
