<?php
if (!class_exists('db')) {
	include('../db/db.php');
}
/**
 * 
 */
class medicamentosM
{
	private $db;

	function __construct()
	{
		$this->db = new db();
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
					sa_cmed_dosis,
					sa_cmed_tipo
				FROM cat_medicamentos WHERE 1 = 1 and sa_cmed_estado = 1 ";

		$sql .= " ORDER BY sa_cmed_id ";
		$datos = $this->db->datos($sql);
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
					sa_cmed_fecha_modificacion,
					sa_cmed_tipo,
					sa_cmed_unidad,
					sa_cmed_reg_sanitario,
					sa_cmed_fecha_exp,
					sa_cmed_fecha_elab,
					sa_cmed_referencia
				FROM cat_medicamentos 
				WHERE sa_cmed_estado = 1 ";

		if ($id) {
			$sql .= ' and sa_cmed_id = ' . $id;
		}

		$sql .= " ORDER BY sa_cmed_id";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_medicamentos($buscar)
	{
		$sql = "SELECT * FROM cat_medicamentos WHERE sa_cmed_estado = 1 and CONCAT(sa_cmed_presentacion, ' ', sa_cmed_id) LIKE '%" . $buscar . "%'";

		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_medicamentos_CODIGO($buscar)
	{
		$sql = "SELECT sa_cmed_id, sa_cmed_concentracion, sa_cmed_estado FROM cat_medicamentos WHERE sa_cmed_id = '" . $buscar . "'";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function insertar($datos)
	{
		$rest = $this->db->inserts('cat_medicamentos', $datos);
		return $rest;
	}

	function editar($datos, $where)
	{
		$rest = $this->db->update('cat_medicamentos', $datos, $where);
		return $rest;
	}

	function eliminar($datos)
	{
		$sql = "UPDATE cat_medicamentos SET sa_cmed_estado = 0 WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
		$datos = $this->db->sql_string($sql);
		return $datos;
	}
}
