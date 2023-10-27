<?php
if (!class_exists('db_salud')) {
	include('../db/db_salud.php');
}
/**
 * 
 */
class seccionM
{
	private $db_salud;

	function __construct()
	{
		$this->db_salud = new db_salud();
	}

	function lista_seccion($id = '')
	{
		$sql = "SELECT * FROM cat_seccion WHERE estado = 1 ";
		
		if ($id) {
			$sql .= ' and sa_sec_id = ' . $id;
		}

		$sql .= " ORDER BY ID_SECCION ";
		$datos = $this->db_salud->datos($sql);
		return $datos;
	}

	function lista_seccion_todo($id = '')
	{
		$sql = "SELECT ID_SECCION, CODIGO, DESCRIPCION, ESTADO FROM seccion WHERE 1 = 1 ";
		
		if ($id) {
			$sql .= ' and ID_SECCION= ' . $id;
		}

		$sql .= " ORDER BY ID_SECCION ";
		$datos = $this->db_salud->datos($sql);
		return $datos;
	}

	function buscar_seccion($buscar)
	{
		$sql = "SELECT ID_SECCION, CODIGO, DESCRIPCION FROM seccion WHERE ESTADO='A' and DESCRIPCION + ' ' + CODIGO LIKE '%" . $buscar . "%'";
		$datos = $this->db_salud->datos($sql);
		return $datos;
	}

	function buscar_seccion_CODIGO($buscar)
	{
		$sql = "SELECT ID_SECCION,CODIGO,DESCRIPCION FROM seccion WHERE CODIGO = '" . $buscar . "'";
		$datos = $this->db_salud->datos($sql);
		return $datos;
	}

	function insertar($datos)
	{
		$rest = $this->db_salud->inserts('seccion', $datos);
		return $rest;
	}

	function editar($datos, $where)
	{
		$rest = $this->db_salud->update('seccion', $datos, $where);
		return $rest;
	}

	function eliminar($datos)
	{
		$sql = "UPDATE seccion SET ESTADO='I' WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
		$datos = $this->db_salud->sql_string($sql);
		return $datos;
	}
}
