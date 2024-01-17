<?php
if (!class_exists('db')) {
	include('../db/db.php');
}
/**
 * 
 */
class det_consultaM
{
	private $db;

	function __construct()
	{
		$this->db = new db();
	}

	function lista_det_consulta_todo()
	{
		$sql = "SELECT  
					sa_det_conp_id,
					sa_id_conp,
					sa_det_conp_id_cmed_cins,
					sa_det_conp_tipo,
					sa_det_conp_nombre,
					sa_det_conp_cantidad,
					sa_det_conp_dosificacion,
					sa_det_conp_estado_entrega

				FROM det_consulta WHERE 1 = 1";

		$sql .= " ORDER BY sa_det_conp_id ";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_det_consulta($id = '')
	{
		$sql = "SELECT 
					sa_det_conp_id,
					sa_id_conp,
					sa_det_conp_id_cmed_cins,
					sa_det_conp_tipo,
					sa_det_conp_nombre,
					sa_det_conp_cantidad,
					sa_det_conp_dosificacion,
					sa_det_conp_estado_entrega
				FROM det_consulta
				WHERE 1 = 1 ";

		if ($id) {
			$sql .= ' and sa_det_conp_id = ' . $id;
		}

		$sql .= " ORDER BY sa_det_conp_id";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_det_consulta_consulta($id_consulta = '')
	{
		if ($id_consulta) {
			$sql = "SELECT 
					sa_det_conp_id,
					sa_id_conp,
					sa_det_conp_id_cmed_cins,
					sa_det_conp_tipo,
					sa_det_conp_nombre,
					sa_det_conp_cantidad,
					sa_det_conp_dosificacion,
					sa_det_conp_estado_entrega
				FROM det_consulta 
				WHERE 1 = 1 ";


			$sql .= ' and sa_id_conp = ' . $id_consulta;

			$sql .= " ORDER BY sa_det_conp_id";
			$datos = $this->db->datos($sql);
			return $datos;
		}
	}

	function insertar($datos)
	{
		$rest = $this->db->inserts('det_consulta', $datos);
		return $rest;

		//return $datos;
	}

	function editar($datos, $where)
	{
		$rest = $this->db->update('det_consulta', $datos, $where);
		return $rest;
	}

	function eliminar($id)
	{
		$sql = "DELETE FROM det_consulta WHERE sa_det_conp_id = $id";
		$datos = $this->db->sql_string($sql);
		return $datos;
	}
}
