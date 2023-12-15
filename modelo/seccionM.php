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
		$sql = "SELECT sa_sec_id, sa_sec_nombre, sa_sec_estado FROM cat_seccion WHERE sa_sec_estado = 1 ";

		if ($id) {
			$sql .= ' and sa_sec_id = ' . $id;
		}

		$sql .= " ORDER BY sa_sec_id";
		$datos = $this->db_salud->datos($sql);
		return $datos;
	}

	function lista_seccion_todo($id = '')
	{
		$sql = "SELECT  sa_sec_id, sa_sec_nombre, sa_sec_estado FROM cat_seccion WHERE 1 = 1 ";

		if ($id) {
			$sql .= ' and sa_sec_id= ' . $id;
		}

		$sql .= " ORDER BY sa_sec_id ";
		$datos = $this->db_salud->datos($sql);
		return $datos;
	}

	function buscar_seccion($buscar)
	{
		$sql = "SELECT sa_sec_id, sa_sec_nombre, sa_sec_estado FROM cat_seccion WHERE sa_sec_estado = 1 and CONCAT(sa_sec_nombre, ' ', sa_sec_id) LIKE '%" . $buscar . "%'";

		$datos = $this->db_salud->datos($sql);
		return $datos;
	}

	function buscar_seccion_CODIGO($buscar)
	{
		$sql = "SELECT sa_sec_id, sa_sec_nombre, sa_sec_estado FROM cat_seccion WHERE sa_sec_id = '" . $buscar . "'";
		$datos = $this->db_salud->datos($sql);
		return $datos;
	}

	function insertar($datos)
	{
		$rest = $this->db_salud->inserts('cat_seccion', $datos);
		return $rest;
	}

	function editar($datos, $where)
	{
		$rest = $this->db_salud->update('cat_seccion', $datos, $where);
		return $rest;
	}

	function eliminar($datos)
	{
		$sql = "UPDATE cat_seccion SET sa_sec_estado = 0 WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
		$datos = $this->db_salud->sql_string($sql);
		return $datos;
	}
}
