<?php 

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_cardM  extends BaseModel
{
	
	protected $tabla = 'th_card_data';
    protected $primaryKey = 'th_card_id AS _id';

    protected $camposPermitidos = [
        'th_card_id',
        'th_per_id',
        'th_card_nombre',
        'th_cardNo',
        'th_card_creacion',
    ];


}
?>