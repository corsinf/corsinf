<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cat_reqf_fisicosM extends BaseModel
{
    protected $tabla = 'th_cat_reqf_fisicos';

    protected $primaryKey = 'id_req_fisico AS _id';

    protected $camposPermitidos = [
        'descripcion AS descripcion',
        'estado AS estado',
        'fecha_creacion AS fecha_creacion'
    ];
}
