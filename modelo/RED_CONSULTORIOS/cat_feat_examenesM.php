<?php

require_once 'BaseModel.php';

class cat_feat_examenesM extends BaseModel
{

	protected $tabla = 'cat_feat_examenes';
    protected $primaryKey = 'fex_id AS ID_FEAT_EXAMEN';

	protected $camposPermitidos =
	[
		'fex_descripcion AS DESCRIPCION_FEAT_EXAMEN',
		'fex_name_input AS INPUT_FEAT_EXAMEN',
		'fex_estado AS ESTADO_FEAT_EXAMEN',
		'fex_fecha_creacion AS FECHA_C_FEAT_EXAMEN',
	];


	
}
