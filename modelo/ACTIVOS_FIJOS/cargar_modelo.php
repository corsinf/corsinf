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
			$sql .= "(SELECT CASE WHEN EXISTS (SELECT 1 FROM ac_marcas WHERE CODIGO = '" . $marcas . "') 
			THEN (SELECT TOP 1 ID_MARCA FROM ac_marcas WHERE CODIGO = '" . $marcas . "') ELSE 0 END) AS ac_marcas,";
		}
		if ($genero) {
			$sql .= "(SELECT CASE WHEN EXISTS (SELECT 1 FROM ac_genero WHERE CODIGO = '" . $genero . "') 
			THEN (SELECT TOP 1 ID_GENERO FROM ac_genero WHERE CODIGO = '" . $genero . "') ELSE 0 END) AS ac_genero,";
		}
		if ($colores) {
			$sql .= "(SELECT CASE WHEN EXISTS (SELECT 1 FROM ac_colores WHERE CODIGO = '" . $colores . "') THEN 
			(SELECT TOP 1 ID_COLORES FROM ac_colores WHERE CODIGO = '" . $colores . "') ELSE 0 END) AS ac_colores,";
		}
		if ($estado) {
			$sql .= "(SELECT CASE WHEN EXISTS (SELECT 1 FROM ac_estado WHERE CODIGO = '" . $estado . "') THEN 
			(SELECT TOP 1 ID_ESTADO FROM ac_estado WHERE CODIGO = '" . $estado . "') ELSE 0 END) AS ac_estado,";
		}
		if ($custodio) {
			$sql .= "(SELECT CASE WHEN EXISTS (SELECT 1 FROM th_personas WHERE PERSON_NO = '" . $custodio . "') THEN 
			(SELECT TOP 1 ID_PERSON FROM th_personas WHERE PERSON_NO = '" . $custodio . "') ELSE 0 END) AS th_personas,";
		}
		if ($emplazamiento) {
			$sql .= "(SELECT CASE WHEN EXISTS (SELECT 1 FROM ac_localizacion WHERE EMPLAZAMIENTO = '" . $emplazamiento . "') THEN 
			 (SELECT TOP 1 ID_LOCATION FROM ac_localizacion WHERE EMPLAZAMIENTO = '" . $emplazamiento . "')  ELSE 0 END) AS ac_localizacion,";
		}
		if ($proyecto) {
			$sql .= "(SELECT CASE WHEN EXISTS (SELECT 1 FROM ac_proyecto WHERE programa_financiacion = '" . $proyecto . "') THEN 
			(SELECT TOP 1 ID_PROYECTO FROM ac_proyecto WHERE programa_financiacion = '" . $proyecto . "') ELSE 0 END) AS ac_proyecto,";
		}
		if ($clase_mov) {
			$sql .= "(SELECT CASE WHEN EXISTS (SELECT 1 FROM ac_clase_movimiento WHERE CODIGO = '" . $clase_mov . "') THEN 
			(SELECT TOP 1 CODIGO FROM ac_clase_movimiento WHERE CODIGO = '" . $clase_mov . "') ELSE 0 END) AS ac_clase_movimiento,";
		}

		$sql = substr($sql, 0, -1);
		$sql = $sql . ';';

		// print_r($sql);
		return $this->db->datos($sql);
	}
}
