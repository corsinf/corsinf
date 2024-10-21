<?php

require_once(dirname(__DIR__, 2) . '/BaseModel.php');

class th_pos_habilidadesM extends BaseModel
{
    protected $tabla = 'th_pos_habilidades';
    protected $primaryKey = 'th_habp_id AS _id';

    protected $camposPermitidos = [
        'th_hab_id',
        'th_pos_id',
        'th_habp_estado',
        'th_habp_fecha_creacion',
        'th_habp_fecha_modificacion',
    ];

    function listarJoin()
	{
		// Construir la parte JOIN de la consulta
		$this->join('th_cat_habilidades', 'th_pos_habilidades.th_hab_id = th_cat_habilidades.th_hab_id');

        $datos = $this->where('th_habp_estado', '1')->listar();
		
		return $datos;
	}
}
