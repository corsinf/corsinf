<?php
if (!class_exists('db')) {
	include('../db/db.php');
}
/**
 * 
 */
class no_concurenteM
{
	private $db;

	function __construct()
	{
		$this->db = new db();
	}

	function tabla_no_concurente($query = false)
	{
		$sql = "SELECT TABLE_NAME
					FROM INFORMATION_SCHEMA.COLUMNS
					WHERE COLUMN_NAME IN ('PERFIL', 'PASS')
					GROUP BY TABLE_NAME
					HAVING COUNT(DISTINCT COLUMN_NAME) = 2;
				";

		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_no_concurente($query = false)
	{
		$sql = "SELECT count(*) as 'Total',Tabla,Campo_usuario,Campo_pass,TU.DESCRIPCION as 'perfil' 
			FROM TABLAS_NOCONCURENTE T
			INNER JOIN TIPO_USUARIO TU ON T.tipo_perfil = TU.ID_TIPO
				WHERE Id_Empresa = '" . $_SESSION['INICIO']['ID_EMPRESA'] . "'
				GROUP BY Tabla,Campo_usuario,Campo_pass,TU.DESCRIPCION";
		// print_r($sql);die();
		$datos = $this->db->datos($sql, 1);
		return $datos;
	}

	function datos_no_concurentes($tabla, $count = true, $primary_key = '', $celda_pass = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM $tabla;";

		if ($count) {
			$sql = "SELECT COUNT(*) AS total FROM $tabla;";
		} else {
			$sql = "SELECT $primary_key";

			if ($celda_pass != '') {
				$sql .= ", $celda_pass AS 'PASS_TEMP' ";
			}

			$sql .= " FROM $tabla;";
		}

		// print_r($sql); exit(); die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function id_tabla_no_concurentes($tabla)
	{

		// $sql2="SELECT COLUMN_NAME as 'ID'
		// 		FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
		// 		WHERE OBJECTPROPERTY(OBJECT_ID(CONSTRAINT_NAME), 'IsPrimaryKey') = 1
		// 		AND TABLE_NAME = '".$tabla."'";

		//Para que detecte los esquemas correctamente
		$sql = "SELECT kcu.COLUMN_NAME AS ID
				FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu
				JOIN INFORMATION_SCHEMA.TABLE_CONSTRAINTS tc
					ON kcu.CONSTRAINT_NAME = tc.CONSTRAINT_NAME
					AND kcu.CONSTRAINT_SCHEMA = tc.CONSTRAINT_SCHEMA
				WHERE tc.CONSTRAINT_TYPE = 'PRIMARY KEY'
				AND kcu.TABLE_NAME = '" . $tabla . "'";

		$datos2 = $this->db->datos($sql);
		// print_r($sql2);die();
		return $datos2;
	}

	function campos_tabla_no_concurentes($tabla)
	{
		$sql2 = "SELECT COLUMN_NAME, DATA_TYPE
			FROM INFORMATION_SCHEMA.COLUMNS
			WHERE TABLE_NAME = '" . $tabla . "'";
		$datos2 = $this->db->datos($sql2);
		return $datos2;
	}

	function existe_no_concurente($tabla)
	{
		$sql = "SELECT * FROM TABLAS_NOCONCURENTE WHERE Id_Empresa = '" . $_SESSION['INICIO']['ID_EMPRESA'] . "' AND Tabla = '" . $tabla . "'";
		// print_r($sql);die();
		return $this->db->datos($sql, 1);
	}

	function insertar($tabla, $datos, $master = false)
	{
		$rest = $this->db->inserts($tabla, $datos, $master);

		return $rest;
	}

	function editar($tabla, $datos, $where, $master = false, $sin_esquema = false)
	{
		$rest = $this->db->update($tabla, $datos, $where, $master, $sin_esquema);
		return $rest;
	}

	function eliminar_no_concurente($tabla)
	{
		$sql = "DELETE FROM TABLAS_NOCONCURENTE WHERE Id_Empresa = '" . $_SESSION['INICIO']['ID_EMPRESA'] . "' AND Tabla = '" . $tabla . "'";
		$datos = $this->db->sql_string($sql, 1);
		return $datos;
	}

	function esquema_modulo($tabla, $solotabla = 1)
	{
		return $this->db->esquema_modulo($tabla, $solotabla);
	}

	function actualizar_claves_merge_sin_tmp(array $claves)
	{
		if (empty($claves)) return false;

		$values = [];

		foreach ($claves as $id => $pass) {
			$id = (int)$id;
			$pass = str_replace("'", "''", $pass);
			$values[] = "($id, '$pass')";
		}

		$sql =
			"MERGE _talentoh.th_personas AS T
				USING (
					VALUES " . implode(',', $values) . "
				) AS S (th_per_id, PASS)
				ON T.th_per_id = S.th_per_id
				WHEN MATCHED THEN
					UPDATE SET T.PASS = S.PASS;";

		// print_r($sql);
		return $this->db->sql_string($sql, false, true);
	}
}
