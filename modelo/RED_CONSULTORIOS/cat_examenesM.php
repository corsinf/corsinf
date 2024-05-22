<?php

require_once 'BaseModel.php';

class cat_examenesM extends BaseModel
{

	protected $tabla = 'cat_examenes';
    protected $primaryKey = 'ex_id';

	protected $camposPermitidos =
	[
		'ex_descripcion',
		'ex_name_input',
		'ex_estado',
		'ex_fecha_creacion',
	];

}
