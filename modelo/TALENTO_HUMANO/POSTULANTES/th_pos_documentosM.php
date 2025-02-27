<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_pos_documentosM extends BaseModel
{
    protected $tabla = 'th_pos_documentos';
    protected $primaryKey = 'th_poi_id AS _id';

    protected $camposPermitidos = [
        'th_poi_id',
        'th_pos_id',
        'th_poi_tipo',
        'th_poi_ruta_archivo',
        'th_poi_fecha_creacion',
        'th_poi_fecha_modificacion',
        'th_poi_estado',
    ];
   
}

