<?php
if (!class_exists('db')) {
	include(dirname(__DIR__, 2) . '/db/db.php');
}

/**
 * 
 **/

class avaluo_articuloM
{
	private $db;

	function __construct()
	{
		$this->db = new db();
	}

	function listar($id = '')
	{
		$sql = "SELECT 
					id_avaluo,
					id_plantilla,
					valor,
					observacion,
					usu_id,
					fecha_creacion
				FROM AVALUOS_PLANILLA_MASIVA
				WHERE 1 = 1 ";

		if ($id) {
			$sql .= ' and id_plantilla = ' . $id;
		}

		$sql .= " ORDER BY id_avaluo";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function insertar($datos)
	{
		$rest = $this->db->inserts('AVALUOS_PLANILLA_MASIVA', $datos);
		return $rest;
	}

	function editar($datos, $where)
	{
		$rest = $this->db->update('AVALUOS_PLANILLA_MASIVA', $datos, $where);
		return $rest;
	}

	function eliminar($id)
	{
		$sql = "DELETE FROM AVALUOS_PLANILLA_MASIVA WHERE id_avaluo = $id";
		$datos = $this->db->sql_string($sql);
		return $datos;
	}
}
