<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_cat_tipo_justificacionM extends BaseModel
{
    protected $tabla = 'th_cat_tipo_justificacion';
    protected $primaryKey = 'th_tip_jus_id AS _id';

    protected $camposPermitidos = [
        'th_tip_jus_nombre AS nombre',
        'th_tip_jus_descripcion AS descripcion',
        'th_tip_jus_fecha_creacion AS fecha_creacion',
        'th_tip_jus_fecha_modificacion AS fecha_modificacion',
        'th_tip_jus_estado AS estado',
        'id_usuario AS id_usuario',
    ];
}
