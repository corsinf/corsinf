<?php
if (!class_exists('db')) {
	include(dirname(__DIR__, 2) . '/db/db.php');
}

/**
 * 
 **/

class estadoM
{
	private $db;

	function __construct()
	{
		$this->db = new db();
	}

	function lista_estado($id = '')
	{
		$sql = "SELECT ID_ESTADO,CODIGO,DESCRIPCION FROM ac_estado WHERE ESTADO='A' ";
		if ($id) {
			$sql .= ' AND ID_ESTADO= ' . $id;
		}
		$sql .= " ORDER BY ID_ESTADO DESC";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_estado_drop($query)
	{
		$sql = "SELECT ID_ESTADO,CODIGO,DESCRIPCION FROM ac_estado WHERE ESTADO='A' ";
		if ($query) {
			$sql .= ' AND DESCRIPCION= ' . $query;
		}
		$sql .= " ORDER BY ID_ESTADO DESC";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_estado_todo($id = '')
	{
		$sql = "SELECT ID_ESTADO,CODIGO,DESCRIPCION,ESTADO FROM ac_estado WHERE 1=1";
		if ($id) {
			$sql .= ' AND ID_ESTADO= ' . $id;
		}
		$sql .= " ORDER BY ID_ESTADO DESC";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_estado($buscar)
	{
		$sql = "SELECT ID_ESTADO,CODIGO,DESCRIPCION FROM ac_estado WHERE ESTADO='A' AND DESCRIPCION +' '+CODIGO LIKE '%" . $buscar . "%'";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_estado_CODIGO($buscar)
	{
		$sql = "SELECT ID_ESTADO,CODIGO,DESCRIPCION FROM ac_estado WHERE CODIGO = '" . $buscar . "'";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function insertar($datos)
	{
		$rest = $this->db->inserts('ac_estado', $datos);

		return $rest;
	}

	function editar($datos, $where)
	{

		$rest = $this->db->update('ac_estado', $datos, $where);
		return $rest;
	}

	function eliminar($datos)
	{
		$sql = "UPDATE ac_estado SET ac_estado='I' WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
		$datos = $this->db->sql_string($sql);
		return $datos;

		// $rest = $this->db->delete('ac_estado',$datos);
		//return $rest;
	}
}
