<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cat_reqf_fisicos_detalleM extends BaseModel
{
    protected $tabla = 'th_cat_reqf_fisicos_detalle';

    protected $primaryKey = 'id_req_fisico_det AS _id';

    protected $camposPermitidos = [
        'descripcion AS descripcion',
        'estado AS estado',
        'fecha_creacion AS fecha_creacion'
    ];
}
