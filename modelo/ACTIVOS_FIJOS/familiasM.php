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
		$sql = "SELECT id_familia,detalle_familia FROM ac_familias  WHERE familia='.' ";
		if ($query) {
			$sql .= " and detalle_familia= '" . $query . "'";
		}
		if ($id) {
			$sql .= ' and id_familia= ' . $id;
		}
		$sql .= " ORDER BY id_familia ";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_subfamilias($id = false, $query = false)
	{
		$sql = "SELECT F.id_familia,F.detalle_familia,F2.id_familia as 'idF',F2.detalle_familia as 'familia'  FROM ac_familias  F
			INNER JOIN ac_familias F2 ON F.familia = F2.id_familia
			WHERE F.familia<>'.' AND F.familia<>''";
		if ($query) {
			$sql .= " and F.detalle_familia like '%" . $query . "%'";
		}
		if ($id) {
			$sql .= ' and F.id_familia= ' . $id;
		}
		$sql .= " ORDER BY id_familia ";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_familias_todo($id = false)
	{
		$sql = "SELECT ID_familias,CODIGO,DESCRIPCION,ESTADO FROM familias  WHERE 1=1 ";
		if ($id) {
			$sql .= ' and ID_familias= ' . $id;
		}
		$sql .= " ORDER BY ID_familias ";
		$datos = $this->db->datos($sql);
		return $datos;
	}
	function buscar_familias($buscar)
	{
		$sql = "SELECT ID_familias,CODIGO,DESCRIPCION FROM familias WHERE ESTADO='A' and DESCRIPCION +' '+CODIGO LIKE '%" . $buscar . "%'";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_familias_codigo($buscar)
	{
		$sql = "SELECT ID_familias,CODIGO,DESCRIPCION FROM familias WHERE CODIGO='" . $buscar . "'";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function insertar($datos)
	{
		$rest = $this->db->inserts('familias', $datos);

		return $rest;
	}

	function editar($datos, $where)
	{

		$rest = $this->db->update('familias', $datos, $where);
		return $rest;
	}

	function eliminar_($datos)
	{
		$sql = "DELETE familias  WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
		$datos = $this->db->sql_string($sql);
		return $datos;

		//$rest = $this->db->delete('familias',$datos);
		//return $rest;
	}

	function eliminar($datos)
	{
		$sql = "UPDATE familias SET ESTADO='I' WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
		$datos = $this->db->sql_string($sql);
		return $datos;

		//$rest = $this->db->delete('familias',$datos);
		//return $rest;
	}
}
