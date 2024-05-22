<?php

require_once 'BaseModel.php';

class cat_t_consultasM extends BaseModel
{
	protected $tabla = 'cat_t_consultas';
    protected $primaryKey = 't_con_id';


	protected $camposPermitidos =
	[
		't_con_descripcion',
		't_con_fecha_creacion',
		't_con_estado'
	];
}
