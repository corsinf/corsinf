<?php 
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
	    // $this->usuario = "sa";
	    // $this->password = "Tango456";  // en mi caso tengo contraseña pero en casa caso introducidla aquí.
	    // $this->servidor = "186.4.219.172, 1487";
	    // $this->database = "PUCE_V3";
	    // $this->database = "PUCE 2.0";

	    // $this->usuario = "";
	    // $this->password = "";  // en mi caso tengo contraseña pero en casa caso introducidla aquí.
	    // $this->servidor = "DESKTOP-RSN9E39\SQLEXPRESS";
	    // $this->database = "LISTA_EMPRESAS";
	    // $this->tipo_base = '';
	    // $this->puerto = '';
		    
		$this->usuario =  '';
	    $this->password = '';

	}

	function parametros_conexion($master=false)
	{
		if(!$master)
		{
			if(isset($_SESSION['INICIO']['ID_EMPRESA']))
			{
				$_SESSION['INICIO']['ULTIMO_ACCESO'] = time();
			    $this->servidor = $_SESSION['INICIO']['IP_HOST'];
			    $this->database = $_SESSION['INICIO']['BASEDATO'];
			    if($_SESSION['INICIO']['USUARIO_DB']!='')
			    {		    
					$this->usuario =  $_SESSION['INICIO']['USUARIO_DB'];
				}
				if($_SESSION['INICIO']['PASSWORD_DB']!='')
				{
			    	$this->password = $_SESSION['INICIO']['PASSWORD_DB'];
			    }
			    $this->tipo_base= $_SESSION['INICIO']['TIPO_BASE'];
			    $this->puerto =   $_SESSION['INICIO']['PUERTO_DB'];

			    // print_r($_SESSION['INICIO']);die();
			}else
			{
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
		}else
		{
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
		$connectionInfo = array("Database"=>$this->database, "UID" => $this->usuario,"PWD" => $this->password,"CharacterSet" => "UTF-8");
		// print_r($this->servidor);
		// print_r($connectionInfo);die();
		$server = $this->servidor;
		if($this->puerto!='')
		{
			$server = $this->servidor.', '.$this->puerto;
		}
		$cid = sqlsrv_connect($server, $connectionInfo); //returns false
		if( $cid === false )
			{
				echo 'no se pudo conectar a la base de datos';
				die( print_r( sqlsrv_errors(), true));
			}
		return $cid;
	}


	function existente($sql,$master=false)
	{
		// print_r($sql);die();
		$this->parametros_conexion($master);
		$conn = $this->conexion();
  	    $stmt = sqlsrv_query($conn, $sql);
	    $result = array();	
	     while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	     	{
	     		$result[] = $row;
	     	}
	     if(count($result) == 0)
	     {
	     	return -1;
	     }else
	     {
	     	return 1;
	     }
		sqlsrv_close($conn);

	}
	function datos($sql,$master=false)
	{	
		$this->parametros_conexion($master);
		$conn = $this->conexion();
		$stmt = sqlsrv_query($conn,$sql);
		 // print_r($sql);die();
	    $result = array();	  
	    if( $stmt === false) {
			die( print_r( sqlsrv_errors(), true) );
		}
	     while($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) 
	     	{
	     		$result[] = $row;
	     	}
	     
		sqlsrv_close($conn);
	     return $result;


	}
	function inserts($tabla,$datos,$master=false)
	{
		// print_r($datos);die();		
		$this->parametros_conexion($master);
		$conn = $this->conexion();

		$valores = '';
 		$campos = '';
 		$sql = 'INSERT INTO '.$tabla;

 		foreach ($datos as $key => $value) {
 			$campos.=$value['campo'].',';
 			if(is_numeric($value['dato']))
 			{
 				if(isset($value['tipo']) && strtoupper($value['tipo'])=='STRING')
 				{
 					$valores.="'".$value['dato']."',";
 				}else
 				{
	 			  $valores.=$value['dato'].',';
 				}
 			}else
 			{
 				$valores.="'".$value['dato']."',";
 			}
 			 			
 		}
 		$campos = substr($campos, 0, -1);
 		$valores = substr($valores, 0, -1);
 		$sql.='('.$campos.')values('.$valores.');'; 

 		// print_r($sql);die();
		 $stmt = sqlsrv_query($conn, $sql);
		if(!$stmt)
		{
			echo "Error: " . $sql . "<br>" . sqlsrv_errors($conn);

		sqlsrv_close($conn);
			return -1;
		}

		sqlsrv_close($conn);
		return 1;
		

	}

	function update ($tabla,$datos,$where,$master=false)
	{		
		$this->parametros_conexion($master);
		$conn = $this->conexion();

		$valores = '';
 		$campos = '';
 		$sql = 'UPDATE '.$tabla.' SET ';

 		foreach ($datos as $key => $value) {
 			// if(is_numeric($value['dato']))
 			// {
 			   // $sql.=$value['campo'].'='.$value['dato'];
 			// }else
 			// {
 				$sql.=$value['campo']."='".$value['dato']."'";
 			// }
 			$sql.=',';
 			 			
 		}

 		$sql = substr($sql, 0, -1);

 		$sql.=" WHERE ";


 		foreach ($where as $key => $value) {
 			if(is_numeric($value['dato']))
 			{
 			  $sql.=$value['campo'].'='.$value['dato'];
 			}else
 			{
 			    $sql.=$value['campo'].'="'.$value['dato'].'"';
 			//	$valores.='"'.$value['dato'].'",';
 			}
 			$sql.= " AND ";
 			 			
 		} 		
 		$sql = substr($sql, 0, -5);	
 		// print_r($sql);
 		// die();		
		 $stmt = sqlsrv_query($conn, $sql);
		if(!$stmt)
		{
			echo "Error: ".$sql."<br>".sqlsrv_errors($conn);
			sqlsrv_close($conn);
			return -1;
		}

		sqlsrv_close($conn);
		return 1;
		

	}


	function delete($tabla,$datos,$master=false)
	{

		$this->parametros_conexion($master);
		$conn = $this->conexion();

		$valores = '';
 		$campos = '';
 		$sql = 'DELETE FROM '.$tabla;
 		if(count($datos)!=0)
 		{
 			$sql.= ' WHERE ';

 		foreach ($datos as $key => $value) {
 			$campos.=$value['campo'].',';
 			if(is_numeric($value['dato']))
 			{
 			  $sql.=$value['campo'].'='.$value['dato'];
 			}else
 			{
 			    $sql.=$value['campo'].'="'.$value['dato'].'"';
 			//	$valores.='"'.$value['dato'].'",';
 			}
 			$sql.= " AND ";
 			 			
 		}
 		
 		$sql = substr($sql, 0, -5);
 	  }
 		// print_r($sql);	die();			
		 $stmt = sqlsrv_query($conn, $sql);
		if(!$stmt)
		{
			echo "Error: " . $sql . "<br>" . sqlsrv_errors($conn);
			sqlsrv_close($conn);
			return -1;
		}
		sqlsrv_close($conn);
		return 1;
		

	}

	function sql_string($sql,$master=false)
	{

		$this->parametros_conexion($master);
		$conn = $this->conexion();
		// print_r($sql);
        $stmt = sqlsrv_query($conn, $sql);
		if(!$stmt)
		{
        // print_r($sql);die();
			echo "Error: " . $sql . "<br>" . sqlsrv_errors($conn);
			sqlsrv_close($conn);
			return -1;
		}

		sqlsrv_close($conn);
		return 1;
		

	}

	function sql_string_cod_error($sql,$master=false)
	{
		// print_r($sql);		
		$this->parametros_conexion($master);
		$conn = $this->conexion();
        $stmt = sqlsrv_query($conn, $sql);
		if(!$stmt)
		{
			$error = sqlsrv_errors();
			// print_r($error);die();
			return $error[0]['code'];
		}

		sqlsrv_close($conn);
		return 1;

	}

	function ejecutar_procesos_almacenados($sql,$parametros=false,$retorna=false,$master=false)
	{
		   $this->parametros_conexion($master);		
		   $conn = $this->conexion();
		   if($parametros)
		   {
           		$stmt = sqlsrv_prepare($conn, $sql, $parametros);
           }else{
		       $stmt = sqlsrv_prepare($conn, $sql);
           }
           $res = sqlsrv_execute($stmt);
           if ($res === false) 
           {
	           	// return "Error en consulta PA. -1 ";  
	           	die( print_r( sqlsrv_errors(), true));  
           }else
           {
			   sqlsrv_close($conn);
			   return 1;
			}
	}


	function conexion_db_terceros($database,$usuario,$password,$servidor,$puerto)
	{
		if($usuario==''){$usuario = ''; }
		if($password==''){$password = ''; }
		$connectionInfo = array("Database"=>$database, "UID" => $usuario,"PWD" => $password,"CharacterSet" => "UTF-8");
		// print_r($this->servidor);
		// print_r($connectionInfo);die();
		$server = $servidor;
		if($puerto!='')
		{
			$server = $servidor.', '.$puerto;
		}
		$cid = sqlsrv_connect($server, $connectionInfo); //returns false
		if( $cid === false )
			{
				echo 'no se pudo conectar a la base de datos';
				die( print_r( sqlsrv_errors(), true));
			}
		return $cid;
	}



	function sql_string_db_terceros($database,$usuario,$password,$servidor,$puerto,$sql)
	{
		
		$conn = $this->conexion_db_terceros($database,$usuario,$password,$servidor,$puerto);
		$stmt = sqlsrv_query($conn, $sql);
		if(!$stmt)
		{
        // print_r($sql);die();
			echo "Error: " . $sql . "<br>" . sqlsrv_errors($conn);
			sqlsrv_close($conn);
			return -1;
		}

		sqlsrv_close($conn);
		return 1;

	}

	function datos_db_terceros($database,$usuario,$password,$servidor,$puerto,$sql)
	{
		$conn = $this->conexion_db_terceros($database,$usuario,$password,$servidor,$puerto);
		$stmt = sqlsrv_query($conn,$sql);
		 // print_r($sql);die();
	    $result = array();	  
	    if( $stmt === false) {
			die( print_r( sqlsrv_errors(), true) );
		}
	     while($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) 
	     	{
	     		$result[] = $row;
	     	}
	     
		sqlsrv_close($conn);
	     return $result;

	}

// ------------------------------------------------------------------------------------------------------
	function conexion_pdo()
	{
		$contraseña =  $this->password;
		$usuario =  $this->usuario;
		$nombreBaseDeDatos = $this->database; 
		$rutaServidor = $this->servidor ;
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
		where c.name = '".$campo."'";
		// print_r($sql);
		return $this->datos($sql); 
	}

	function existe_tabla($base,$tabla)
	{
		$this->database = $base;
		$sql = "IF OBJECT_ID('$tabla', 'U') IS NOT NULL  SELECT 1 AS existe  ELSE  SELECT 0 AS existe";
		return $this->datos($sql);
	}

	function existe_campo_tabla($base,$tabla,$campo)
	{
		$this->database = $base;
		$sql = "SELECT 
				CASE
				   WHEN COUNT(*) > 0 THEN 1
				ELSE 0
    			END AS existe
				FROM INFORMATION_SCHEMA.COLUMNS
				WHERE TABLE_NAME = '".$tabla."'
				AND COLUMN_NAME = '".$campo."'";
		return $this->datos($sql);
	}

	function existe_dato_default($base,$tabla,$campo)
	{		
		$this->database = $base;
		$sql = "SELECT CASE
				   WHEN COUNT(*) > 0 THEN 1
				ELSE 0
    			END AS existe
			    FROM sys.default_constraints
			    WHERE parent_object_id = OBJECT_ID('".$tabla."') 
			    AND col_name(parent_object_id, parent_column_id) = '".$campo."'";
			    // print_r($sql);die();
		return $this->datos($sql);
	}		

	function alter_db($base,$tabla,$campo,$edit=false,$default=false)
	{
		$this->database = $base;
		$sql = "ALTER TABLE ".$tabla;
		if($edit)
		{			
			$sql.=" ALTER COLUMN  ".$campo;
		}else
		{
			$sql.=" ADD ".$campo;
		}
		if($default)
		{
			$campo_tabla = explode(' ',trim($campo[0]));
			$campo_tabla = $campo_tabla[0];
			$sql.=" CONSTRAINT DF_".str_replace(' ','_',$tabla)."_".str_replace(' ','_',trim($campo_tabla))." DEFAULT ".$campo[1]." FOR ".str_replace(' ','_',trim($campo_tabla));
		}

		// print_r($sql);die();

		return $this->sql_string($sql);
	}
	function sql_string_terceros($base,$valor)
	{
		$this->database = $base;
		return $this->sql_string($valor);
	}
}
?>