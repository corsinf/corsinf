<?php
	require_once(dirname(__DIR__,2).'/db/db.php');
/**
 * 
 */
class herramientas_gpaM
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
				FROM ac_avaluos_articulos
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
		$rest = $this->db->inserts('ac_avaluos_articulos', $datos);
		return $rest;
	}

	function editar($datos, $where)
	{
		$rest = $this->db->update('ac_avaluos_articulos', $datos, $where);
		return $rest;
	}

	function eliminar($id)
	{
		$sql = "DELETE FROM ac_avaluos_articulos WHERE id_avaluo = $id";
		$datos = $this->db->sql_string($sql);
		return $datos;
	}

}
