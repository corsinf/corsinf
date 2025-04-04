<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_cat_tipo_reporteM extends BaseModel
{
    protected $tabla = 'th_cat_tipo_reporte';
    protected $primaryKey = 'th_tip_rep_id AS _id';

    protected $camposPermitidos = [
        'th_tip_rep_nombre AS nombre',
    ];
}
