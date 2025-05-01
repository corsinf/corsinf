<?php 

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_fingerM  extends BaseModel
{
	
	protected $tabla = 'th_finger_data';
    protected $primaryKey = 'th_id_finger AS _id';

    protected $camposPermitidos = [
        'th_id_finger',
        'th_per_id',
        'th_finger_nombre',
        'th_finger_patch',
        'th_finger_numero',
        'th_cardNo',
        'th_finger_creacion',
    ];


}
?>