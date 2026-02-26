<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class cn_postulacionM extends BaseModel
{
    protected $tabla = '_contratacion.cn_postulacion';
    protected $primaryKey = 'cn_post_id AS _id';
    protected $camposPermitidos = [
        'cn_pla_id',
        'th_pos_id',
        'cn_plaet_id_actual',
        'cn_post_estado_proceso',
        'cn_post_estado',
        'cn_post_fecha_creacion',
        'cn_post_fecha_modificacion',
    ];
}