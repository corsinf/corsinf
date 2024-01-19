<?php
if (!class_exists('db')) {
	include('../db/db.php');
}
/**
 * 
 */
class insumosM
{
	private $db;

	function __construct()
	{
		$this->db = new db();
	}

	function lista_insumos_todo()
	{
		$sql = "SELECT  
					sa_cins_id,
					sa_cins_codigo,
					sa_cins_presentacion,
					sa_cins_lote,
					sa_cins_caducidad,
					sa_cins_minimos,
					sa_cins_stock,
					sa_cins_movimiento,
					sa_cins_localizacion,
					sa_cins_tipo
				FROM cat_insumos WHERE 1 = 1 and sa_cins_estado = 1 ";

		$sql .= " ORDER BY sa_cins_id ";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_insumos($id = '')
	{
		$sql = "SELECT 
					sa_cins_id,
					sa_cins_codigo,
					sa_cins_presentacion,
					sa_cins_lote,
					sa_cins_caducidad,
					sa_cins_minimos,
					sa_cins_stock,
					sa_cins_movimiento,
					sa_cins_localizacion,
					sa_cins_uso,
					sa_cins_observaciones,
					sa_cins_estado,
					sa_cins_fecha_creacion,
					sa_cins_fecha_modificacion,
					sa_cins_tipo
				FROM cat_insumos 
				WHERE sa_cins_estado = 1 ";

		if ($id) {
			$sql .= ' and sa_cins_id = ' . $id;
		}

		$sql .= " ORDER BY sa_cins_id";
		$datos = $this->db->datos($sql);
		return $datos;
	}



	function buscar_insumos($buscar)
	{
		$sql = "SELECT sa_cins_id, sa_cins_concentracion, sa_cins_estado FROM cat_insumos WHERE sa_cins_estado = 1 and CONCAT(sa_cins_concentracion, ' ', sa_cins_id) LIKE '%" . $buscar . "%'";
		// print_r($sql);die();

		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_insumos_CODIGO($buscar)
	{
		$sql = "SELECT sa_cins_id, sa_cins_concentracion, sa_cins_estado FROM cat_insumos WHERE sa_cins_id = '" . $buscar . "'";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function insertar($datos)
	{
		$rest = $this->db->inserts('cat_insumos', $datos);
		return $rest;
	}

	function editar($datos, $where)
	{
		$rest = $this->db->update('cat_insumos', $datos, $where);
		return $rest;
	}

	function eliminar($datos)
	{
		$sql = "UPDATE cat_insumos SET sa_cins_estado = 0 WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
		$datos = $this->db->sql_string($sql);
		return $datos;
	}
}
