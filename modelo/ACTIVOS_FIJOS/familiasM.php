<?php
if (!class_exists('db')) {
	include(dirname(__DIR__, 2) . '/db/db.php');
}

/**
 * 
 **/

class familiasM
{
	private $db;

	function __construct()
	{
		$this->db = new db();
	}

	function lista_familias($id = false, $query = false)
	{
		$sql = "SELECT
					id_familia,
					detalle_familia 
				FROM
					ac_familias 
				WHERE
					familia = '0'";

		if ($query) {
			$sql .= " and detalle_familia= '" . $query . "'";
		}
		if ($id) {
			$sql .= ' and id_familia= ' . $id;
		}
		$sql .= " ORDER BY id_familia;";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_subfamilias($id = '', $query = false)
	{
		$sql = "SELECT
					F.id_familia,
					F.detalle_familia,
					F2.id_familia AS 'idF',
					F2.detalle_familia AS 'detalle_familia_sub' 
				FROM
					ac_familias F
				INNER JOIN ac_familias F2 ON F.id_familia = F2.familia 
				WHERE
					1 = 1 ";

		if ($query) {
			$sql .= " AND F2.detalle_familia LIKE '%" . $query . "%' ";
		}

		if ($id != '') {
			$sql .= " AND F2.id_familia = " . $id;
		}

		$sql .= " ORDER BY F.id_familia";
		// print_r($sql);
		// die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_familias_todo($id = false)
	{
		$sql = "SELECT ID_familia,detalle_familia,ESTADO FROM ac_familias  WHERE 1=1 ";
		if ($id) {
			$sql .= ' and ID_familia= ' . $id;
		}
		$sql .= " ORDER BY ID_familia ";
		$datos = $this->db->datos($sql);
		return $datos;
	}
	function buscar_familias($buscar)
	{
		$sql = "SELECT ID_familia,detalle_familia FROM ac_familias WHERE ESTADO='A' and detalle_familia LIKE '%" . $buscar . "%'";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_familias_detalle($buscar)
	{
		$sql = "SELECT ID_familia,detalle_familia FROM ac_familias WHERE detalle_familia='" . $buscar . "'";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function insertar($datos)
	{
		$rest = $this->db->inserts('ac_familias', $datos);

		return $rest;
	}

	function editar($datos, $where)
	{

		$rest = $this->db->update('ac_familias', $datos, $where);
		return $rest;
	}

	function eliminar_($datos)
	{
		$sql = "DELETE ac_familias  WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
		$datos = $this->db->sql_string($sql);
		return $datos;

		//$rest = $this->db->delete('ac_familias',$datos);
		//return $rest;
	}

	function eliminar($datos)
	{
		$sql = "UPDATE ac_familias SET ESTADO='I' WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
		$datos = $this->db->sql_string($sql);
		return $datos;

		//$rest = $this->db->delete('ac_familias',$datos);
		//return $rest;
	}
}
