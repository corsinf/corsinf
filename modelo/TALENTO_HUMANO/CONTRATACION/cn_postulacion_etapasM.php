<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class cn_postulacion_etapasM extends BaseModel
{
    protected $tabla = '_contratacion.cn_postulacion_etapas';
    protected $primaryKey = 'cn_pose_id AS _id';
    protected $camposPermitidos = [
        'cn_post_id',
        'cn_plaet_id',
        'cn_pose_estado_proceso',
        'cn_pose_puntuacion',
        'cn_pose_observacion',
        'usuario_evaluador',
        'cn_pose_estado',
        'cn_pose_fecha_inicio',
        'cn_pose_fecha_evaluacion',
        'cn_pose_fecha_creacion',
        'cn_pose_fecha_modificacion',
    ];
}