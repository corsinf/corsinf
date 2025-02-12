<?php

if (!class_exists('db')) {
	include(dirname(__DIR__, 2) . '/db/db.php');
}

/**
 * 
 **/

class cargar_modelo
{
	private $db;
	function __construct()
	{
		$this->db = new db();
	}

	function validar_exsitencia($marcas = false, $genero = false, $colores = false, $estado = false, $custodio = false, $emplazamiento = false, $proyecto = false, $clase_mov = false)
	{

		$sql = "SELECT ";
		if ($marcas) {
			$sql .= "(SELECT CASE WHEN EXISTS (SELECT 1 FROM MARCAS WHERE CODIGO = '" . $marcas . "') 
			THEN (SELECT TOP 1 ID_MARCA FROM MARCAS WHERE CODIGO = '" . $marcas . "') ELSE 0 END) AS MARCAS,";
		}
		if ($genero) {
			$sql .= "(SELECT CASE WHEN EXISTS (SELECT 1 FROM GENERO WHERE CODIGO = '" . $genero . "') 
			THEN (SELECT TOP 1 ID_GENERO FROM GENERO WHERE CODIGO = '" . $genero . "') ELSE 0 END) AS GENERO,";
		}
		if ($colores) {
			$sql .= "(SELECT CASE WHEN EXISTS (SELECT 1 FROM COLORES WHERE CODIGO = '" . $colores . "') THEN 
			(SELECT TOP 1 ID_COLORES FROM COLORES WHERE CODIGO = '" . $colores . "') ELSE 0 END) AS COLORES,";
		}
		if ($estado) {
			$sql .= "(SELECT CASE WHEN EXISTS (SELECT 1 FROM ESTADO WHERE CODIGO = '" . $estado . "') THEN 
			(SELECT TOP 1 ID_ESTADO FROM ESTADO WHERE CODIGO = '" . $estado . "') ELSE 0 END) AS ESTADO,";
		}
		if ($custodio) {
			$sql .= "(SELECT CASE WHEN EXISTS (SELECT 1 FROM PERSON_NO WHERE PERSON_NO = '" . $custodio . "') THEN 
			(SELECT TOP 1 ID_PERSON FROM PERSON_NO WHERE PERSON_NO = '" . $custodio . "') ELSE 0 END) AS CUSTODIO,";
		}
		if ($emplazamiento) {
			$sql .= "(SELECT CASE WHEN EXISTS (SELECT 1 FROM LOCATION WHERE EMPLAZAMIENTO = '" . $emplazamiento . "') THEN 
			 (SELECT TOP 1 ID_LOCATION FROM LOCATION WHERE EMPLAZAMIENTO = '" . $emplazamiento . "')  ELSE 0 END) AS EMPLAZAMIENTO,";
		}
		if ($proyecto) {
			$sql .= "(SELECT CASE WHEN EXISTS (SELECT 1 FROM PROYECTO WHERE programa_financiacion = '" . $proyecto . "') THEN 
			(SELECT TOP 1 ID_PROYECTO FROM PROYECTO WHERE programa_financiacion = '" . $proyecto . "') ELSE 0 END) AS PROYECTO,";
		}
		if ($clase_mov) {
			$sql .= "(SELECT CASE WHEN EXISTS (SELECT 1 FROM CLASE_MOVIMIENTO WHERE CODIGO = '" . $clase_mov . "') THEN 
			(SELECT TOP 1 CODIGO FROM CLASE_MOVIMIENTO WHERE CODIGO = '" . $clase_mov . "') ELSE 0 END) AS CLASE_MOVIMIENTO,";
		}

		$sql = substr($sql, 0, -1);
		$sql = $sql . ';';

		// print_r($sql);
		return $this->db->datos($sql);
	}
}
