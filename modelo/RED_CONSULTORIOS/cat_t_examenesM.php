<?php

require_once 'BaseModel.php';

class cat_t_examenesM extends BaseModel
{
	protected $tabla = 'cat_t_examenes';
    protected $primaryKey = 't_ex_id';


	protected $camposPermitidos =
	[
		't_ex_descripcion',
		't_ex_fecha_creacion',
		't_ex_estado'
	];
}
