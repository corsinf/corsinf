<?php
if (!class_exists('db_salud')) {
	include('../db/db_salud.php');
}
/**
 * 
 */
class medicamentosM
{
	private $db_salud;

	function __construct()
	{
		$this->db_salud = new db_salud();
	}

	function lista_medicamentos($id = '')
	{
		$sql = "SELECT sa_med_id, sa_med_nombre, sa_med_estado FROM cat_medicamentos WHERE sa_med_estado = 1 ";

		if ($id) {
			$sql .= ' and sa_med_id = ' . $id;
		}

		$sql .= " ORDER BY sa_med_id";
		$datos = $this->db_salud->datos($sql);
		return $datos;
	}

	function lista_medicamentos_todo($id = '')
	{
		$sql = "SELECT  sa_med_id, sa_med_nombre, sa_med_estado FROM cat_medicamentos WHERE 1 = 1 ";

		if ($id) {
			$sql .= ' and sa_med_id= ' . $id;
		}

		$sql .= " ORDER BY sa_med_id ";
		$datos = $this->db_salud->datos($sql);
		return $datos;
	}

	function buscar_medicamentos($buscar)
	{
		$sql = "SELECT sa_med_id, sa_med_nombre, sa_med_estado FROM cat_medicamentos WHERE sa_med_estado = 1 and CONCAT(sa_med_nombre, ' ', sa_med_id) LIKE '%" . $buscar . "%'";

		$datos = $this->db_salud->datos($sql);
		return $datos;
	}

	function buscar_medicamentos_CODIGO($buscar)
	{
		$sql = "SELECT sa_med_id, sa_med_nombre, sa_med_estado FROM cat_medicamentos WHERE sa_med_id = '" . $buscar . "'";
		$datos = $this->db_salud->datos($sql);
		return $datos;
	}

	function insertar($datos)
	{
		$rest = $this->db_salud->inserts('cat_medicamentos', $datos);
		return $rest;
	}

	function editar($datos, $where)
	{
		$rest = $this->db_salud->update('cat_medicamentos', $datos, $where);
		return $rest;
	}

	function eliminar($datos)
	{
		$sql = "UPDATE cat_medicamentos SET sa_med_estado = 0 WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
		$datos = $this->db_salud->sql_string($sql);
		return $datos;
	}
}
