<?php

require_once 'BaseModel.php';

class interm_t_examen_examenM extends BaseModel
{
	protected $tabla = 'interm_t_examen_examen';
	protected $primaryKey = 'itee_id';

	protected $camposPermitidos =
	[
		't_ex_id',
		'ex_id',
		'itee_estado',
		'itee_fecha_creacion',
	];


	function listarJoin()
	{
		// Construir la parte JOIN de la consulta
		$this->join('cat_examenes', 'interm_t_examen_examen.ex_id = cat_examenes.ex_id');
		$this->join('cat_t_examenes', 'interm_t_examen_examen.t_ex_id = cat_t_examenes.t_ex_id');

        $datos = $this->where('itee_estado', '1')->listar();
		
		return $datos;
	}
}
