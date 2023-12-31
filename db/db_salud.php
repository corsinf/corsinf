<?php

/**
 * 
 */
//phpinfo();
$d = new db_salud();
$d->conexion();
class db_salud
{
	private $usuario;
	private $password;  // en mi caso tengo contraseña pero en casa caso introducidla aquí.
	private $servidor;
	private $database;
	function __construct()
	{
		$this->usuario = "RUBEN";
		$this->password = "1004951644RP*";  // en mi caso tengo contraseña pero en casa caso introducidla aquí.
		$this->servidor = "186.4.219.172, 1487";
		$this->database = "SALUD_DESARROLLO";
		// $this->database = "PUCE 2.0";

		/*$this->usuario = "";
		$this->password = "";  // en mi caso tengo contraseña pero en casa caso introducidla aquí.
		$this->servidor = "DESKTOP-3JRF9IS\SQLEXPRESS";
		$this->database = "prueba";*/
	}

	function conexion()
	{

		$connectionInfo = array("Database" => $this->database, "UID" => $this->usuario, "PWD" => $this->password, "CharacterSet" => "UTF-8");
		$cid = sqlsrv_connect($this->servidor, $connectionInfo); //returns false
		if ($cid === false) {
			echo 'no se pudo conectar a la base de datos';
			die(print_r(sqlsrv_errors(), true));
		}
		return $cid;
	}

	function existente($sql)
	{
		// print_r($sql);die();
		$conn = $this->conexion();
		$stmt = sqlsrv_query($conn, $sql);
		$result = array();
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$result[] = $row;
		}
		if (count($result) == 0) {
			return -1;
		} else {
			return 1;
		}
		sqlsrv_close($conn);
	}
	function datos($sql)
	{
		set_time_limit(0);

		// print_r($sql);
		// die();
		$conn = $this->conexion();
		$stmt = sqlsrv_query($conn, $sql);
		// print_r($sql);
		// die();
		$result = array();

		if ($stmt === false) {
			die(print_r(sqlsrv_errors(), true));
		}
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$result[] = $row;
		}

		sqlsrv_close($conn);
		return $result;
	}

	function inserts($tabla, $datos)
	{
		// print_r($datos);die();
		$conn = $this->conexion();

		$valores = '';
		$campos = '';
		$sql = 'INSERT INTO ' . $tabla;

		foreach ($datos as $key => $value) {
			$campos .= $value['campo'] . ',';
			if (is_numeric($value['dato'])) {
				if (isset($value['tipo']) && strtoupper($value['tipo']) == 'STRING') {
					$valores .= "'" . $value['dato'] . "',";
				} else {
					$valores .= $value['dato'] . ',';
				}
			} else {
				$valores .= "'" . $value['dato'] . "',";
			}
		}

		$campos = substr($campos, 0, -1);
		$valores = substr($valores, 0, -1);
		$sql .= '(' . $campos . ')values(' . $valores . ');';

		// print_r($sql);die();
		$stmt = sqlsrv_query($conn, $sql);
		if (!$stmt) {
			echo "Error: " . $sql . "<br>" . sqlsrv_errors($conn);

			sqlsrv_close($conn);
			return -1;
		}

		sqlsrv_close($conn);
		return 1;
	}

	function update($tabla, $datos, $where)
	{
		$conn = $this->conexion();

		$valores = '';
		$campos = '';
		$sql = 'UPDATE ' . $tabla . ' SET ';

		foreach ($datos as $key => $value) {
			// if(is_numeric($value['dato']))
			// {
			// $sql.=$value['campo'].'='.$value['dato'];
			// }else
			// {
			$sql .= $value['campo'] . "='" . $value['dato'] . "'";
			// }
			$sql .= ',';
		}

		$sql = substr($sql, 0, -1);
		$sql .= " WHERE ";

		foreach ($where as $key => $value) {
			if (is_numeric($value['dato'])) {
				$sql .= $value['campo'] . '=' . $value['dato'];
			} else {
				$sql .= $value['campo'] . '="' . $value['dato'] . '"';
				//	$valores.='"'.$value['dato'].'",';
			}
			$sql .= " AND ";
		}

		$sql = substr($sql, 0, -5);
		// print_r($sql);
		// die();		
		$stmt = sqlsrv_query($conn, $sql);
		if (!$stmt) {
			echo "Error: " . $sql . "<br>" . sqlsrv_errors($conn);
			sqlsrv_close($conn);
			return -1;
		}

		sqlsrv_close($conn);
		return 1;
	}

	function delete($tabla, $datos)
	{
		$conn = $this->conexion();

		$valores = '';
		$campos = '';
		$sql = 'DELETE FROM ' . $tabla;
		if (count($datos) != 0) {
			$sql .= ' WHERE ';

			foreach ($datos as $key => $value) {
				$campos .= $value['campo'] . ',';
				if (is_numeric($value['dato'])) {
					$sql .= $value['campo'] . '=' . $value['dato'];
				} else {
					$sql .= $value['campo'] . '="' . $value['dato'] . '"';
					//	$valores.='"'.$value['dato'].'",';
				}
				$sql .= " AND ";
			}

			$sql = substr($sql, 0, -5);
		}
		// print_r($sql);	die();			
		$stmt = sqlsrv_query($conn, $sql);
		if (!$stmt) {
			echo "Error: " . $sql . "<br>" . sqlsrv_errors($conn);
			sqlsrv_close($conn);
			return -1;
		}
		sqlsrv_close($conn);
		return 1;
	}

	function sql_string($sql)
	{
		$conn = $this->conexion();
		$stmt = sqlsrv_query($conn, $sql);
		if (!$stmt) {
			echo "Error: " . $sql . "<br>" . sqlsrv_errors($conn);
			sqlsrv_close($conn);
			return -1;
		}

		sqlsrv_close($conn);
		return 1;
	}

	function sql_string_cod_error($sql)
	{
		// print_r($sql);
		$conn = $this->conexion();
		$stmt = sqlsrv_query($conn, $sql);
		if (!$stmt) {
			$error = sqlsrv_errors();
			// print_r($error);die();
			return $error[0]['code'];
		}

		sqlsrv_close($conn);
		return 1;
	}

	function ejecutar_procesos_almacenados($sql, $parametros = false, $retorna = false)
	{

		$conn = $this->conexion();
		if ($parametros) {
			$stmt = sqlsrv_prepare($conn, $sql, $parametros);
		} else {
			$stmt = sqlsrv_prepare($conn, $sql);
		}
		$res = sqlsrv_execute($stmt);
		if ($res === false) {
			return "Error en consulta PA. -1 ";
			die(print_r(sqlsrv_errors(), true));
		} else {
			sqlsrv_close($conn);
			return 1;
		}
	}

	//Para retonar valores de la procedures datos especificos
	function ejecutar_procedimiento_con_retorno($sql, $parametros = false)
	{
		$conn = $this->conexion();


		if ($parametros) {
			$stmt = sqlsrv_prepare($conn, $sql, $parametros);
		} else {
			$stmt = sqlsrv_prepare($conn, $sql);
		}

		if ($stmt === false) {
			throw new Exception("Error al preparar la consulta PA. -1");
		}

		$res = sqlsrv_execute($stmt);

		if ($res === false) {
			throw new Exception("Error al ejecutar la consulta PA. -1");
		}

		// Obtener el valor de retorno directamente del conjunto de resultados
		$resultado = null;
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			//El valor que se retorna en este caso AS Resultado
			$resultado = $row['Resultado'];
		}

		sqlsrv_free_stmt($stmt);
		sqlsrv_close($conn);

		return $resultado;
	}

	//Para retonar valores de la procedures de todo un select
	function ejecutar_procedimiento_con_retorno_1($sql, $parametros = false)
	{
		$conn = $this->conexion();


		if ($parametros) {
			$stmt = sqlsrv_prepare($conn, $sql, $parametros);
		} else {
			$stmt = sqlsrv_prepare($conn, $sql);
		}

		if ($stmt === false) {
			throw new Exception("Error al preparar la consulta PA. -1");
		}

		$res = sqlsrv_execute($stmt);

		if ($res === false) {
			throw new Exception("Error al ejecutar la consulta PA. -1");
		}

		// Obtener el valor de retorno directamente del conjunto de resultados
		 // Obtener los resultados
		 $resultados = array();
		 while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			 $resultados[] = $row;
		 }

		sqlsrv_free_stmt($stmt);
		sqlsrv_close($conn);

		return $resultados;
	}

	//Procedure mas directa revisar hay algun error
	function ejecutar_procedimiento_con_retorno_2($nombre_procedimiento, $parametros = array())
	{
		$conn = $this->conexion();

		// Construir la cadena de parámetros para la llamada a la procedure
		$parametros_str = '';
		foreach ($parametros as $param) {
			$parametros_str .= "@" . $param['nombre'] . " = ?, ";
		}
		$parametros_str = rtrim($parametros_str, ', ');

		// Construir la llamada a la procedure
		$sql = "EXEC $nombre_procedimiento $parametros_str";

		// Preparar la llamada a la procedure
		$stmt = sqlsrv_prepare($conn, $sql, $parametros);

		if ($stmt === false) {
			throw new Exception("Error al preparar la consulta PA. -1");
		}

		// Ejecutar la procedure
		$res = sqlsrv_execute($stmt);

		if ($res === false) {
			throw new Exception("Error al ejecutar la consulta PA. -1");
		}

		// Obtener los resultados
		$resultados = array();
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$resultados[] = $row;
		}

		sqlsrv_free_stmt($stmt);
		sqlsrv_close($conn);

		return $resultados;
	}

	function conexion_pdo()
	{
		$contraseña =  $this->password;
		$usuario =  $this->usuario;
		$nombreBaseDeDatos = $this->database;
		$rutaServidor = $this->servidor;
		try {
			$base_de_datos = new PDO("sqlsrv:server=$rutaServidor;database=$nombreBaseDeDatos", $usuario, $contraseña);
			$base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $base_de_datos;
		} catch (Exception $e) {
			echo "Ocurrió un error con la base de datos: " . $e->getMessage();
		}
	}

	function en_tabla($campo)
	{
		$sql = "select distinct t.name
		from sys.tables t inner join sys.columns c
			on t.object_id = c.object_id
		where c.name = '" . $campo . "'";
		// print_r($sql);
		return $this->datos($sql);
	}
}
