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

	private $api_usuario;
	private $api_password;
	private $api_servidor;
	private $api_database;
	private $api_tipo_base;
	private $api_puerto;
	private $api_existe = 0;

	function __construct()
	{
		
		$this->usuario =  '';
		$this->password = '';
		$this->puerto = '';
	}

	function modificar_parametros_db($codigo_empresa_api = false)
	{
		if($codigo_empresa_api){
			$sql = "SELECT
								Ip_host,
								Base_datos,
								Usuario_db,
								Password_db,
								Tipo_base,
								Puerto_db
							FROM EMPRESAS
							WHERE codigo_empresa_api = '$codigo_empresa_api'";

			$empresa = $this->datos($sql, true, 0)[0] ?? [];

			if($empresa){
				// Asignar los valores
				$this->api_servidor   = $empresa['Ip_host']     ?? '';
				$this->api_database   = $empresa['Base_datos']  ?? '';
				$this->api_usuario    = $empresa['Usuario_db']  ?? '';
				$this->api_password   = $empresa['Password_db'] ?? '';
				$this->api_tipo_base  = $empresa['Tipo_base']   ?? '';
				$this->api_puerto     = $empresa['Puerto_db']	?? '';
				
				$this->api_existe = 1;
			}
		}
	}

	function parametros_conexion($master = false)
	{

		$this->usuario =  '';
		$this->password = '';
		$this->puerto = '';

		if ($this->api_existe == 1) {
			$this->usuario = $this->api_usuario ;
			$this->password = $this->api_password;  // en mi caso tengo contraseña pero en casa caso introducidla aquí.
			$this->servidor = 	$this->api_servidor . ', ' . $this->api_puerto;
			$this->database = $this->api_database;
		}else {

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
				$this->servidor = "186.4.219.172,1487";
				$this->database = "LISTA_EMPRESAS";
			}
		}
	}

	function conexion()
	{		
		try{
		     $conn = new PDO("sqlsrv:Server=".$this->servidor .''. $this->puerto.";Database=".$this->database.";TrustServerCertificate=1", $this->usuario, $this->password);

		     // print_r("sqlsrv:Server=".$this->servidor .''. $this->puerto.";Database=".$this->database.";TrustServerCertificate=0");
		     // print_r($this->usuario.'-'.$this->password);die();
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

	function datos($sql, $master = false, $error = false)
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
			if($error){
				die("Error: " . $e->getMessage());
			}else{
				die(json_encode(["error" => "Error Consulte con: soporte@corsinf.com"]));
			}
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
					$valores .= "'" . $value['dato'] . "'^";
				} else {
					$valores .= str_replace(',','',$value['dato']). '^';
				}
			} else {			
				// $valores .= "'" . $value['dato'] . "',";
				// print_r($value);
				$valores.= $value['dato']."^";
				// $valores .= str_replace(',','',$value['dato']). '_';
			}
		}
		$campos = substr($campos, 0, -1);
		$valores = substr($valores, 0, -1);
		// print_r($datos);
		// print_r($valores);
		// die();
		$valores = explode('^',$valores);
		$incognitas = '';
		// print_r($valores);die();
		foreach ($valores as $value) {
			// print_r($value.'-');
			if ($this->validarFecha($value)==1) 
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
			echo "Error:<br>";
			echo "SQL: <pre>" . htmlspecialchars($sql) . "</pre><br>";
			echo "Detalles del error:<br>";
			echo "<pre>";
			echo($e);
			echo "</pre>";
			$conn = null;

			return -1;
		}
		
	}

	function validarFecha($fecha) 
	{
		// Fecha con hora
		if (DateTime::createFromFormat('Y-m-d H:i:s', $fecha) !== false) {
			return 'datetime';
		}

		// Solo fecha
		if (DateTime::createFromFormat('Y-m-d', $fecha) !== false) {
			return 'date';
		}

		return false;
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
			echo "Error:<br>";
			echo "SQL: <pre>" . htmlspecialchars($sql) . "</pre><br>";
			echo "Detalles del error:<br>";
			echo "<pre>";
			echo($e);
			echo "</pre>";
			
			return -1;
		}
	}

	function update($tabla, $datos, $where, $master = false)
	{
		// print_r($master);die();
		$this->parametros_conexion($master);
		$conn = $this->conexion();

		$valores = '';
		$campos = '';
		$sql = 'UPDATE ' . $tabla . ' SET ';

		$datos_update = array();
		foreach ($datos as $key => $value) {
			$tipo_fecha = $this->validarFecha($value['dato']);

			if ($tipo_fecha === 'date') {
				$sql .= $value['campo'] . " = CAST(? AS DATE)";
			} else {
				$sql .= $value['campo'] . " = ?";
			}
			// $sql .= $value['campo'] . "= ?";	
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

		// print_r($datos_update);die();
		// print_r($sql);die();

		try {
			$stmt = $conn->prepare($sql);
    		$stmt->execute($datos_update);
    		$conn=null;
    		return 1;
			
		} catch (Exception $e) {
			echo "Error:<br>";
			echo "SQL: <pre>" . htmlspecialchars($sql) . "</pre><br>";
			echo "Detalles del error:<br>";
			echo "<pre>";
			echo($e);
			echo "</pre>";
			
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
			//return -1;
			die(print_r($e, true));
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
		// print_r($sql);print_r($parametros);
		try {
			if (count($parametros)>0) {
				$stmt->execute($parametros);


				// print_r('dd');die();
				// sleep(10);
				$conn=null;
				return 1;
			} else {
				$stmt->execute();
				$conn=null;
				return 1;
			}

			// $conn=null;
			// return 1;
		} catch (Exception $e) {
			$conn=null;

		// print_r($sql);print_r($parametros); print_r($master);die();
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

	function comprobar_conexcion_terceros($database, $usuario, $password, $servidor, $puerto)
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
		      return 1;
		    }	 
		  catch(PDOException $e)
		    {
		    	return -1;
		    }
		 
		  $conn = null;

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


	function conexion_terceros($usuario, $password, $servidor, $puerto)
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
		     $conn = new PDO("sqlsrv:Server=".$servidor .''. $puerto, $usuario, $password);
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
	}



	function sql_string_db_terceros($database, $usuario, $password, $servidor, $puerto, $sql)
	{

		$conn = $this->conexion_db_terceros($database, $usuario, $password, $servidor, $puerto);
		// print_r($sql);
		// print_r($conn);die();
		try {
			$conn->exec($sql);
			// $stmt = $conn->prepare($sql);
			// $stmt->execute($valores);
    		// $stmt->execute();    		
		    $conn=null;
			return 1;
			
		} catch (Exception $e) {
			return -1;
			die(print_r(sqlsrv_errors(), true));
		}
	}

	function sql_string_sin_base_terceros($usuario, $password, $servidor, $puerto, $sql)
	{

		$conn = $this->conexion_terceros($usuario, $password, $servidor, $puerto);
		// print_r($sql);
		// print_r($conn);die();
		try {
			$conn->exec($sql);
			// $stmt = $conn->prepare($sql);
			// $stmt->execute($valores);
    		// $stmt->execute();    		
		    $conn=null;
			return 1;
			
		} catch (Exception $e) {
			print_r($e);
			return -1;
			die(print_r(sqlsrv_errors(), true));
		}
	}
	function sql_string_sin_base($sql,$master)
	{
		$this->parametros_conexion($master);
		$conn = $this->conexion_terceros($this->usuario,$this->password,$this->servidor,$this->puerto);
		// print_r($sql);
		// print_r($conn);die();
		try {
			$conn->exec($sql);
			// $stmt = $conn->prepare($sql);
			// $stmt->execute($valores);
    		// $stmt->execute();    		
		    $conn=null;
			return 1;
			
		} catch (Exception $e) {
			print_r($e);
			return -1;
			die(print_r(sqlsrv_errors(), true));
		}
	}

	function datos_sin_base_system($sql,$master)
	{

		$this->parametros_conexion($master);
		$conn = $this->conexion_terceros($this->usuario,$this->password,$this->servidor,$this->puerto);
		// print_r($sql);
		// print_r($conn);die();
		try {
			 $stmt = $conn->prepare($sql);
    		$stmt->execute();
    		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		        $result[] = $row;
		    }
		    $conn=null;
			return $result;

		} catch (Exception $e) {
			print_r($e);
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

		print_r($sql);print_r($parametros);die();
		$stmt = $conn->prepare($sql);
		$resultados = array();

		print_r($sql);print_r($parametros);die();
		
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

	function existe_tabla($tabla,$master=false,$terceros=false,$database=false, $usuario=false, $password=false, $servidor=false, $puerto=false)
	{		
		$this->parametros_conexion($master);
		if(!$terceros)
		{
			$conn = $this->conexion();
		}else{
			$conn = $this->conexion_db_terceros($database, $usuario, $password, $servidor, $puerto);
		}	

		$result = array();
		$sql = "IF OBJECT_ID('$tabla', 'U') IS NOT NULL  SELECT 1 AS existe  ELSE  SELECT 0 AS existe";
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
