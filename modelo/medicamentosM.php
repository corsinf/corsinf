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

	function lista_medicamentos_todo()
	{
		$sql = "SELECT  
					sa_cmed_id,
					sa_cmed_concentracion,
					sa_cmed_presentacion,
					sa_cmed_serie,
					sa_cmed_lote,
					sa_cmed_caducidad,
					sa_cmed_minimos,
					sa_cmed_stock,
					sa_cmed_movimiento,
					sa_cmed_dosis
				FROM cat_medicamentos WHERE 1 = 1 and sa_cmed_estado = 1 ";

		$sql .= " ORDER BY sa_cmed_id ";
		$datos = $this->db_salud->datos($sql);
		return $datos;
	}

	function lista_medicamentos($id = '')
	{
		$sql = "SELECT 
					sa_cmed_id,
					sa_cmed_concentracion,
					sa_cmed_presentacion,
					sa_cmed_serie,
					sa_cmed_lote,
					sa_cmed_caducidad,
					sa_cmed_minimos,
					sa_cmed_stock,
					sa_cmed_movimiento,
					sa_cmed_contraindicacion,
					sa_cmed_dosis,
					sa_cmed_tratamientos,
					sa_cmed_uso,
					sa_cmed_observaciones,
					sa_cmed_estado,
					sa_cmed_fecha_creacion,
					sa_cmed_fecha_modificacion
				FROM cat_medicamentos 
				WHERE sa_cmed_estado = 1 ";

		if ($id) {
			$sql .= ' and sa_cmed_id = ' . $id;
		}

		$sql .= " ORDER BY sa_cmed_id";
		$datos = $this->db_salud->datos($sql);
		return $datos;
	}

	function buscar_medicamentos($buscar)
	{
		$sql = "SELECT sa_cmed_id, sa_cmed_concentracion, sa_cmed_estado FROM cat_medicamentos WHERE sa_cmed_estado = 1 and CONCAT(sa_cmed_concentracion, ' ', sa_cmed_id) LIKE '%" . $buscar . "%'";

		$datos = $this->db_salud->datos($sql);
		return $datos;
	}

	function buscar_medicamentos_CODIGO($buscar)
	{
		$sql = "SELECT sa_cmed_id, sa_cmed_concentracion, sa_cmed_estado FROM cat_medicamentos WHERE sa_cmed_id = '" . $buscar . "'";
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
		$sql = "UPDATE cat_medicamentos SET sa_cmed_estado = 0 WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
		$datos = $this->db_salud->sql_string($sql);
		return $datos;
	}
}
