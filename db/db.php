<?php
@session_start();
require_once('VARIABLES_GLOBALES.php');
/**
 * 
 */
//phpinfo();
$d = new db();
class db
{
	private $usuario;
	private $password;  // en mi caso tengo contraseña pero en casa caso introducidla aquí.
	private $servidor;
	private $database;
	private $tipo_base;
	private $puerto;
	function __construct()
	{
		
		$this->usuario =  '';
		$this->password = '';
		$this->puerto = '';
	}

	function parametros_conexion($master = false)
	{

		$this->usuario =  '';
		$this->password = '';
		$this->puerto = '';
		if (!$master) {
			if (isset($_SESSION['INICIO']['ID_EMPRESA'])) {

				$_SESSION['INICIO']['ULTIMO_ACCESO'] = time();
				$this->servidor = $_SESSION['INICIO']['IP_HOST'];
				$this->database = $_SESSION['INICIO']['BASEDATO'];
				if ($_SESSION['INICIO']['USUARIO_DB'] != '' && $_SESSION['INICIO']['USUARIO_DB']!=null) {

					// print_r($_SESSION['INICIO']['USUARIO_DB']);die();
					$this->usuario =  $_SESSION['INICIO']['USUARIO_DB'];
				}
				if ($_SESSION['INICIO']['PASSWORD_DB'] != '' && $_SESSION['INICIO']['PASSWORD_DB']!=null) {
					$this->password = $_SESSION['INICIO']['PASSWORD_DB'];
				}
				$this->tipo_base = $_SESSION['INICIO']['TIPO_BASE'];
				if($_SESSION['INICIO']['PUERTO_DB']!='' && $_SESSION['INICIO']['PUERTO_DB']!=null)
				{
					$this->puerto =   ', '.$_SESSION['INICIO']['PUERTO_DB'];
				}

				// print_r($_SESSION['INICIO']);die();
			} else {
				// $this->usuario = "";
				// $this->password = "";  // en mi caso tengo contraseña pero en casa caso introducidla aquí.
				// $this->servidor = "DESKTOP-RSN9E39\SQLEXPRESS";
				// $this->database = "LISTA_EMPRESAS";
				// $this->tipo_base = '';
				// $this->puerto = '';

				$this->usuario = "sa";
				$this->password = "Tango456";  // en mi caso tengo contraseña pero en casa caso introducidla aquí.
				$this->servidor = "186.4.219.172, 1487";
				$this->database = "LISTA_EMPRESAS";
			}
		} else {
			// $this->usuario = "";
			// $this->password = "";  // en mi caso tengo contraseña pero en casa caso introducidla aquí.
			// $this->servidor = "DESKTOP-RSN9E39\SQLEXPRESS";
			// $this->database = "LISTA_EMPRESAS";
			// $this->tipo_base = '';
			// $this->puerto = '';

			$this->usuario = "sa";
			$this->password = "Tango456";  // en mi caso tengo contraseña pero en casa caso introducidla aquí.
			$this->servidor = "186.4.219.172, 1487";
			$this->database = "LISTA_EMPRESAS";
		}
	}

	function conexion()
	{		
		try{
		     $conn = new PDO("sqlsrv:Server=".$this->servidor .''. $this->puerto.";Database=".$this->database, $this->usuario, $this->password);
		     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		      return $conn;
		    }	 
		  catch(PDOException $e)
		    {
		      echo "La conexión ha fallado: " . $e->getMessage();
		    }
		 
		  $conn = null;
	}


	function existente($sql, $master = false)
	{
	    $this->parametros_conexion($master);
	    $conn = $this->conexion();
	    $result = array();
	    $rsp = '-1';

	    // print_r($this->database);die();
	    try {
	        $stmt = $conn->prepare($sql);
	        $stmt->execute();
	        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	        
	        if (count($result) > 0) {
	            $rsp = '1';
	        }

	        $conn = null;
	    } catch (PDOException $e) {
	        // Manejo de errores PDO
	        die("Error: " . $e->getMessage());
	    }

	    return $rsp;
	}

	function datos($sql, $master = false)
	{

		$this->parametros_conexion($master);
		$conn = $this->conexion();
		$result = array();		

		// print_r($sql);
		try {
			$stmt = $conn->prepare($sql);
    		$stmt->execute();
    		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		        $result[] = $row;
		    }
		    $conn=null;
			return $result;
			
		} catch (Exception $e) {
			 die("Error: " . $e->getMessage());
		}
		
	}
	function inserts($tabla, $datos, $master = false)
	{
		// print_r($datos);die();		
		$this->parametros_conexion($master);
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
					$valores .= str_replace(',','',$value['dato']). ',';
				}
			} else {			
				// $valores .= "'" . $value['dato'] . "',";
				$valores .= str_replace(',','',$value['dato']). ',';
			}
		}
		$campos = substr($campos, 0, -1);
		$valores = substr($valores, 0, -1);
		// print_r($datos);
		// print_r($valores);
		// die();
		$valores = explode(',',$valores);
		$incognitas = '';
		// print_r($valores);die();
		foreach ($valores as $value) {
			// print_r($value.'-');
			if (strlen($value)==12 && strtotime($value) !== false) 
				{
					// print_r($value);die();
    			    $incognitas.='CAST(? AS DATE),';
				}else
				{
					$incognitas.='?,';
				}
		}
		$incognitas = substr($incognitas, 0, -1);
		
		$sql .= '(' . $campos . ')values(' . $incognitas . ');';

		// print_r($sql);die();
		// print_r($valores);die();
		// $stmt = sqlsrv_query($conn, $sql);
		$stmt = $conn->prepare($sql);
		try {
			$stmt->execute($valores);
			$conn = null;
			return 1;
			
		} catch (Exception $e) {
			echo "Error: " . $sql . "<br>" . $e;			
			$conn = null;
			return -1;			
		}
		
	}

	function inserts_id($tabla, $datos, $master = false)
	{
		$this->parametros_conexion($master);
		$conn = $this->conexion();

		$valores = '';
		$campos = '';
		$sql = 'INSERT INTO ' . $tabla;

		foreach ($datos as $key => $value) {
			$campos .= $value['campo'] . ',';
			$valores .= '?,';
		}

		$campos = rtrim($campos, ',');
		$valores = rtrim($valores, ',');

		$sql .= '(' . $campos . ') VALUES (' . $valores . ')';

		$stmt = $conn->prepare($sql);

		try {
			foreach ($datos as $key => $value) {
				$tipo = is_numeric($value['dato']) ? PDO::PARAM_INT : PDO::PARAM_STR;
				$stmt->bindValue(($key + 1), $value['dato'], $tipo);
			}

			$stmt->execute();

			// Obtener el último ID
			$ultimoID = $conn->lastInsertId();

			$conn = null;

			return $ultimoID;
		} catch (PDOException $e) {
			echo "Error: " . $e->getMessage();
			return -1;
		}
	}

	function update($tabla, $datos, $where, $master = false)
	{
		$this->parametros_conexion($master);
		$conn = $this->conexion();

		$valores = '';
		$campos = '';
		$sql = 'UPDATE ' . $tabla . ' SET ';

		$datos_update = array();
		foreach ($datos as $key => $value) {
			
			$sql .= $value['campo'] . "= ?";		
			$sql .= ',';
			array_push($datos_update, $value['dato']);
		}

		$sql = substr($sql, 0, -1);

		$sql .= " WHERE ";

		foreach ($where as $key => $value) {
			array_push($datos_update, $value['dato']);
			// if (is_numeric($value['dato'])) {
				$sql .= $value['campo'] . '= ? '; // . $value['dato'];
			// } else {
			// 	$sql .= $value['campo'] . '="' . $value['dato'] . '"';
			// 	//	$valores.='"'.$value['dato'].'",';
			// }
			$sql .= " AND ";
		}
		$sql = substr($sql, 0, -5);		

		try {
			$stmt = $conn->prepare($sql);
    		$stmt->execute($datos_update);
    		$conn=null;
    		return 1;
			
		} catch (Exception $e) {
			echo "Error: " . $sql . "<br>".$e;
			return -1;
		}		
	}


	function delete($tabla, $datos, $master = false)
	{

		$this->parametros_conexion($master);
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
		return $this->sql_string($sql);
	}

	function sql_string($sql, $master = false)
	{

		$this->parametros_conexion($master);
		$conn = $this->conexion();
		// print_r($sql);

		try {
			$stmt = $conn->prepare($sql);
    		$stmt->execute();    		
		    $conn=null;
			return 1;
			
		} catch (Exception $e) {
			return -1;
			die(print_r(sqlsrv_errors(), true));
		}
	}

	function sql_string_cod_error($sql, $master = false)
	{
		// print_r($sql);		
		$this->parametros_conexion($master);
		$conn = $this->conexion();
		// print_r("sqlsrv:Server=".$this->servidor .''. $this->puerto.";Database=".$this->database.' '.$this->usuario.' '.$this->password);die();
		try {
			$stmt = $conn->prepare($sql);
    		$stmt->execute();    		
		    $conn=null;
			return 1;			
		} catch (Exception $e) {
			$errorInfo = $conn->errorInfo();
            $errorCode = $errorInfo[1]; // Código de error específico de la base de datos
            return $errorCode;
		}
	}

	function ejecutar_procesos_almacenados($sql, $parametros = false, $retorna = false, $master = false)
	{
		$this->parametros_conexion($master);
		$conn = $this->conexion();
		$stmt = $conn->prepare($sql);
		
		try {
			if ($parametros) {
				$stmt->execute($parametros);
			} else {
				$stmt->execute();
			}

			$conn=null;
			return 1;
		} catch (Exception $e) {
			$conn=null;
			print_r($e);die();
			return -1;			
		}
		

	}

	//Para retonar valores de la procedures de todo un select
	function ejecutar_procedimiento_con_retorno_1($sql, $parametros = false, $master = false)
	{
		$this->parametros_conexion($master);
		$conn = $this->conexion();
		$stmt = $conn->prepare($sql);

		if ($parametros) {
			$stmt->execute($parametros);
		} else {
			$stmt->execute();
		}
		$resultados = array();
		do {
		    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		        $resultados[] = $row;
		    }
		} while ($stmt->nextRowset());

		// Cerrar la conexión
		$stmt = null;
		$conn = null;

		// Retornar los resultados
		return $resultados;
	}


	function conexion_db_terceros($database, $usuario, $password, $servidor, $puerto)
	{
		if ($usuario == '') {
			$usuario = '';
		}
		if ($password == '') {
			$password = '';
		}
		if($puerto !='')
		{
			$puerto = ', '.$puerto;
		}else
		{
			$puerto = '';
		}

		 // print_r("sqlsrv:Server=".$servidor .''. $puerto.";Database=".$database.','.$usuario.','.$password);die();

		try{
		     $conn = new PDO("sqlsrv:Server=".$servidor .''. $puerto.";Database=".$database, $usuario, $password);
		     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		     // $conn->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_UTF8);
		     // print_r("conectado");die();
		      return $conn;
		    }	 
		  catch(PDOException $e)
		    {
		      echo "La conexión ha fallado: " . $e->getMessage();
		    }
		 
		  $conn = null;


		// $connectionInfo = array("Database" => $database, "UID" => $usuario, "PWD" => $password, "CharacterSet" => "UTF-8");
		// // print_r($this->servidor);
		// // print_r($connectionInfo);die();
		// $server = $servidor;
		// if ($puerto != '') {
		// 	$server = $servidor . ', ' . $puerto;
		// }
		// $cid = sqlsrv_connect($server, $connectionInfo); //returns false

		// if ($cid === false) {
		// 	echo 'no se pudo conectar a la base de datos';
		// 	die(print_r(sqlsrv_errors(), true));
		// }

		// if ($cid === false) {
		// 	return -1;
		// 	// echo 'no se pudo conectar a la base de datos';
		// 	// die( print_r( sqlsrv_errors(), true));
		// }

		// return $cid;
	}



	function sql_string_db_terceros($database, $usuario, $password, $servidor, $puerto, $sql)
	{

		$conn = $this->conexion_db_terceros($database, $usuario, $password, $servidor, $puerto);
		// print_r($sql);

		try {
			$stmt = $conn->prepare($sql);
    		$stmt->execute();    		
		    $conn=null;
			return 1;
			
		} catch (Exception $e) {
			return -1;
			die(print_r(sqlsrv_errors(), true));
		}
	}

	function Generar_sp_db_terceros($database, $usuario, $password, $servidor, $puerto, $sql)
	{
		
		$conn = $this->conexion_db_terceros($database, $usuario, $password, $servidor, $puerto);
		try{
			$conn->exec($sql);
		   return 1;
		} catch(Exception $ex) {
		    return -1;
			die(print_r(sqlsrv_errors(), true));
		}

		$conn->close();
	}

	function ejecutar_sp_db_terceros($database, $usuario, $password, $servidor, $puerto,$sql, $parametros = false, $retorna = false)
	{
		$conn = $this->conexion_db_terceros($database, $usuario, $password, $servidor, $puerto);
		$stmt = $conn->prepare($sql);
		$resultados = array();
		
		try {
			if ($parametros) {
				$stmt->execute($parametros);
			} else {
				$stmt->execute();
			}

			if($retorna)
			{
				do {
				    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				        $resultados[] = $row;
				    }
				} while ($stmt->nextRowset());
			}

			$conn=null;
			return 1;
		} catch (Exception $e) {
			$conn=null;
			print_r($e);die();
			return -1;			
		}
		

	}

	function datos_db_terceros($database, $usuario, $password, $servidor, $puerto, $sql)
	{


		$conn = $this->conexion_db_terceros($database, $usuario, $password, $servidor, $puerto);
		$result = array();

		try {
			$stmt = $conn->prepare($sql);
    		$stmt->execute();
    		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		        $result[] = $row;
		    }
		    $conn=null;
			return $result;
			
		} catch (Exception $e) {
			die(print_r(sqlsrv_errors(), true));
		}

	}

	// ------------------------------------------------------------------------------------------------------
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

	function existe_tabla($base, $tabla)
	{
		$this->database = $base;
		$sql = "IF OBJECT_ID('$tabla', 'U') IS NOT NULL  SELECT 1 AS existe  ELSE  SELECT 0 AS existe";
		return $this->datos($sql);
	}

	function existe_campo_tabla($base, $tabla, $campo)
	{
		$this->database = $base;
		$sql = "SELECT 
				CASE
				   WHEN COUNT(*) > 0 THEN 1
				ELSE 0
    			END AS existe
				FROM INFORMATION_SCHEMA.COLUMNS
				WHERE TABLE_NAME = '" . $tabla . "'
				AND COLUMN_NAME = '" . $campo . "'";
		return $this->datos($sql);
	}

	function existe_dato_default($base, $tabla, $campo)
	{
		$this->database = $base;
		$sql = "SELECT CASE
				   WHEN COUNT(*) > 0 THEN 1
				ELSE 0
    			END AS existe
			    FROM sys.default_constraints
			    WHERE parent_object_id = OBJECT_ID('" . $tabla . "') 
			    AND col_name(parent_object_id, parent_column_id) = '" . $campo . "'";
		// print_r($sql);die();
		return $this->datos($sql);
	}

	function alter_db($base, $tabla, $campo, $edit = false, $default = false)
	{
		$this->database = $base;
		$sql = "ALTER TABLE " . $tabla;
		if ($edit) {
			$sql .= " ALTER COLUMN  " . $campo;
		} else {
			$sql .= " ADD " . $campo;
		}
		if ($default) {
			$campo_tabla = explode(' ', trim($campo[0]));
			$campo_tabla = $campo_tabla[0];
			$sql .= " CONSTRAINT DF_" . str_replace(' ', '_', $tabla) . "_" . str_replace(' ', '_', trim($campo_tabla)) . " DEFAULT " . $campo[1] . " FOR " . str_replace(' ', '_', trim($campo_tabla));
		}

		// print_r($sql);die();

		return $this->sql_string($sql);
	}
	function sql_string_terceros($base, $valor)
	{
		$this->database = $base;
		return $this->sql_string($valor);
	}
}
