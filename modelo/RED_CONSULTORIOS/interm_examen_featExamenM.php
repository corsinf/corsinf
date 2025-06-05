<?php

require_once 'BaseModel.php';

class interm_examen_featExamenM extends BaseModel
{
	protected $tabla = 'interm_examen_featExamen';
	protected $primaryKey = 'itee_id';

	protected $camposPermitidos =
	[
		'ex_id',
		'fex_id',
		'itee_estado',
		'itee_fecha_creacion',
	];


	function listarJoin()
	{
		// Construir la parte JOIN de la consulta
		$this->join('cat_feat_examenes', 'interm_examen_featExamen.fex_id = cat_feat_examenes.fex_id');
		$this->join('cat_examenes', 'interm_examen_featExamen.ex_id = cat_examenes.ex_id');
        $datos = $this->where('itee_estado', '1')->listar();
		return $datos;
	}
}
