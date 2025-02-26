<?php

if (!class_exists('db')) {
	include(dirname(__DIR__, 2) . '/db/db.php');
}

/**
 * 
 **/

class reportesM
{

	private $db;

	function __construct()
	{
		$this->db = new db();
	}
	function add($tabla, $datos)
	{
		return $this->db->inserts($tabla, $datos);
	}

	function update($tabla, $datos, $Where)
	{
		return $this->db->update($tabla, $datos, $Where);
	}

	function buscar_reporte($tipo = false, $nombre = false)
	{
		$sql = "SELECT * FROM ac_reporte WHERE 1=1 ";
		if ($tipo) {
			$sql .= " AND TIPO_REPORTE = '" . $tipo . "' ";
		}
		if ($nombre) {
			$sql .= " AND NOMBRE_REPORTE = '" . $nombre . "' ";
		}
		return $this->db->datos($sql);
	}

	function tipo_reporte()
	{
		$sql = "SELECT ID_TIPO_REPORTE as 'ID',DESCRIPCION as 'NOMBRE' FROM ac_tipo_reporte";
		return $this->db->datos($sql);
	}

	function datos_reporte($id = false)
	{
		$sql = "SELECT R.ID_REPORTE,TR.TABLAS_ASOCIADAS,TABLA_PRINCIPAL,NOMBRE_REPORTE,CAMPOS,SQL,FILTROS_HTML,DETALLE FROM ac_reporte R
		INNER JOIN ac_tipo_reporte TR ON R.TIPO_REPORTE = TR.ID_TIPO_REPORTE 
		WHERE 1=1 ";
		if ($id) {
			$sql .= " AND R.ID_REPORTE = '" . $id . "'";
		}
		//$this->sql_reporte = $sql; 
		// print_r($sql);die();
		return $this->db->datos($sql);
	}

	function campos_tabla($tabla, $columna = false)
	{
		$sql = "SELECT COLUMN_NAME as 'campo', DATA_TYPE as 'tipo'
		FROM information_schema.columns
		WHERE TABLE_NAME = '" . $tabla . "'";
		if ($columna) {
			$sql .= " AND COLUMN_NAME = '" . $columna . "'";
		}
		return $this->db->datos($sql);
	}

	function PK($tabla)
	{
		$sql = "SELECT column_name AS PRIMARYKEYCOLUMN
		FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS AS TC
		INNER JOIN
		INFORMATION_SCHEMA.KEY_COLUMN_USAGE AS KU
		ON TC.CONSTRAINT_TYPE = 'PRIMARY KEY' AND
		TC.CONSTRAINT_NAME = KU.CONSTRAINT_NAME AND
		KU.table_name='" . $tabla . "'
		ORDER BY KU.TABLE_NAME, KU.ORDINAL_POSITION;";
		return $this->db->datos($sql);
	}

	function realizar_consulta($sql)
	{
		return $this->db->datos($sql);
	}

	function total_consulta($sql)
	{
		return $this->db->datos($sql);
	}

	function eliminar_reportes($id)
	{
		$sql = "DELETE FROM ac_reporte WHERE ID_REPORTE = '" . $id . "'";
		return $this->db->sql_string($sql);
	}
}
