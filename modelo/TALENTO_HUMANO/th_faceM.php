<?php 

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_faceM  extends BaseModel
{
	
	protected $tabla = 'th_face_data';
    protected $primaryKey = 'th_id_face AS _id';

    protected $camposPermitidos = [
        'th_id_face',
        'th_per_id',
        'th_cardNo',
        'th_face_nombre',
        'th_face_patch',
        'th_face_creacion',
    ];


}
?>