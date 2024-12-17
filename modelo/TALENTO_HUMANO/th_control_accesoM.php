<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_control_accesoM extends BaseModel
{
    protected $tabla = 'th_control_acceso';
    protected $primaryKey = 'th_acc_id AS _id';

    protected $camposPermitidos = [
    	'th_per_id',
    	'th_dis_id',
    	'th_acc_tipo_registro',
    	'th_acc_hora',
    	'th_acc_fecha_hora',
    	'th_acc_fecha_creacion',
    	'th_acc_fecha_modificacion'
    ];
}
