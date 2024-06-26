<?php

require_once 'BaseModel.php';

class cat_examenesM extends BaseModel
{
	protected $tabla = 'cat_examenes';
    protected $primaryKey = 'ex_id AS ID_EXAMEN';


	protected $camposPermitidos =
	[
		'ex_descripcion AS DESCRIPCION_EXAMEN',
		'ex_estado AS ESTADO_EXAMEN',
		'ex_fecha_creacion AS FECHA_C_EXAMEN'
	];
}





