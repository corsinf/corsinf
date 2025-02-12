<?php
if (!class_exists('db')) {
	include(dirname(__DIR__, 2) . '/db/db.php');
}

/**
 * 
 **/

class clase_movimientoM
{
	private $db;

	function __construct()
	{
		$this->db = new db();
	}

	function lista_clase_movimiento($id = '')
	{
		$sql = "SELECT ID_MOVIMIENTO,CODIGO,DESCRIPCION FROM CLASE_MOVIMIENTO WHERE 1=1";
		if ($id) {
			$sql .= ' AND ID_MOVIMIENTO= ' . $id;
		}
		$sql .= " ORDER BY ID_MOVIMIENTO DESC";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_clase_movimiento_todo($id = false)
	{
		$sql = "SELECT ID_MOVIMIENTO,CODIGO,DESCRIPCION FROM CLASE_MOVIMIENTO WHERE 1=1";
		if ($id) {
			$sql .= ' AND ID_MOVIMIENTO= ' . $id;
		}
		$sql .= " ORDER BY ID_MOVIMIENTO DESC";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_clase_movimiento($buscar)
	{
		$sql = "SELECT ID_MOVIMIENTO,CODIGO,DESCRIPCION FROM CLASE_MOVIMIENTO WHERE DESCRIPCION +' '+CODIGO LIKE '%" . $buscar . "%'";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_clase_movimiento_CODIGO($buscar)
	{
		$sql = "SELECT ID_MOVIMIENTO,CODIGO,DESCRIPCION FROM CLASE_MOVIMIENTO WHERE CODIGO = '" . $buscar . "'";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function insertar($datos)
	{
		$rest = $this->db->inserts('CLASE_MOVIMIENTO', $datos);

		return $rest;
	}

	function editar($datos, $where)
	{

		$rest = $this->db->update('CLASE_MOVIMIENTO', $datos, $where);
		return $rest;
	}
	
	function eliminar($id)
	{
		$sql = "DELETE FROM CLASE_MOVIMIENTO WHERE ID_MOVIMIENTO = '" . $id . "'";
		return $this->db->sql_string($sql);
		// $sql = "UPDATE clase_movimiento SET clase_movimiento='I' WHERE ".$datos[0]['campo']."='".$datos[0]['dato']."';";
		// $datos = $this->db->sql_string($sql);
		// return $datos;

		// $rest = $this->db->delete('clase_movimiento',$datos);
		//return $rest;
	}
}
