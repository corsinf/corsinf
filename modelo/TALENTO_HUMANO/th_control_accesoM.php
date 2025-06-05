<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_control_accesoM extends BaseModel
{
    protected $tabla = 'th_control_acceso';
    protected $primaryKey = 'th_acc_id AS _id';

    protected $camposPermitidos = [
        'th_per_id',
    	'th_cardNo',
    	'th_dis_id',
    	'th_acc_tipo_registro',
    	'th_acc_hora',
    	'th_acc_fecha_hora',
    	'th_acc_fecha_creacion',
    	'th_acc_fecha_modificacion'
    ];

    function listarJoin()
    {
        // Construir la parte JOIN de la consulta
        $this->join('th_card_data', 'th_card_data.th_cardNo = th_control_acceso.th_cardNo');
        $this->join('th_personas', 'th_personas.th_per_id = th_card_data.th_per_id');      
        $datos = $this->listar();  
        return $datos;
    }
}
