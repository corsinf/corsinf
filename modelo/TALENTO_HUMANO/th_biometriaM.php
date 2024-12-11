<?php 

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_biometriaM  extends BaseModel
{
	
	protected $tabla = 'th_biometria';
    protected $primaryKey = 'th_bio_id AS _id';

    protected $camposPermitidos = [
        'th_per_id',
        'th_bio_nombre',
        'th_bio_patch',
        'th_bio_card',
        'th_bio_facial'
    ];


}
?>