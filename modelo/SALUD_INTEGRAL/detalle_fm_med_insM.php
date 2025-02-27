<?php
if (!class_exists('db')) {
	include(dirname(__DIR__, 2) . '/db/db.php');
}
/**
 * 
 */
class detalle_fm_med_insM
{
	private $db;

	function __construct()
	{
		$this->db = new db();
	}

	function lista_det_fm($id = '')
	{

		$sql = "SELECT 
					sa_det_fm_id,
					sa_fice_id,
					sa_det_fice_id_cmed_cins,
					sa_det_fice_nombre,
					sa_det_fice_tipo
				FROM detalle_fm_med_ins
				WHERE 1 = 1 ";

		if ($id) {
			$sql .= ' and sa_fice_id = ' . $id;
		}

		$sql .= " ORDER BY sa_det_fm_id";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function insertar($datos)
	{
		$rest = $this->db->inserts('detalle_fm_med_ins', $datos);
		return $rest;

		//return $datos;
	}

	function editar($datos, $where)
	{
		$rest = $this->db->update('detalle_fm_med_ins', $datos, $where);
		return $rest;
	}

	function eliminar($id)
	{
		$sql = "DELETE FROM detalle_fm_med_ins WHERE sa_det_fm_id = $id";
		$datos = $this->db->sql_string($sql);
		return $datos;
	}

	//Buscar farmaco de una ficha medica y verificar si existe al farmaco que es alergico
	function farmaco_fm_alergico($fm, $id_farmaco, $tipo)
	{
		$sql = "SELECT 
					sa_det_fm_id,
					sa_fice_id,
					sa_det_fice_id_cmed_cins,
					sa_det_fice_nombre,
					sa_det_fice_tipo
				FROM detalle_fm_med_ins
				WHERE sa_fice_id = '$fm' 
				AND sa_det_fice_id_cmed_cins = '$id_farmaco'
				AND sa_det_fice_tipo = '$tipo';";

		$datos = $this->db->datos($sql);
		return $datos;
	}
}
