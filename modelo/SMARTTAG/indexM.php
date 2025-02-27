<?php
@session_start();
if($_SESSION['INICIO']['TIPO_BASE'] == 'MYSQL')
   {
   	require_once(dirname(__DIR__, 2) .'/db/db_mysql.php');
   }else
   {
   	 require_once(dirname(__DIR__, 2) .'/db/db_sql.php'); 
   }
/**
 * 
 */

class indexM
{
	private $db;
	function __construct()
	{		
		$this->db = new db();
	}


	function generar_base_mysql()
	{

		$exise_host = $this->db->conexion_server();
		if($exise_host == false)
		{
			return 3;
		}else
		{
		   $sql = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '".$_SESSION['INICIO']['DB']."' ";
		    $crear = $this->db->existente_db($sql,$exise_host);
		    if($crear==0)
		    {
		    	// print_r('expressionsss');die();
		    	$sql = "CREATE DATABASE ".$_SESSION['INICIO']['DB'];
			    $crear = $this->db->crear_db($sql,$exise_host);
			    if($crear==1)
			    {
			    	if(file_exists('../db/session.txt')==false)
			    	{
			    		$file = fopen('../db/session.txt', "w");
	                     fwrite($file,$_SESSION['INICIO']['TIPO_BASE'].PHP_EOL);
			    		 fwrite($file,$_SESSION['INICIO']['HOST'].PHP_EOL);
	                     fwrite($file,$_SESSION['INICIO']['USU'].PHP_EOL);
	                     fwrite($file,$_SESSION['INICIO']['PORT'].PHP_EOL);
	                     fwrite($file,$_SESSION['INICIO']['PASS'].PHP_EOL);
	                     fwrite($file,$_SESSION['INICIO']['DB']);
			    		fclose($file);
			    	}
			 	   return 1;
			    }else
			    {
			 	   return -1;
			    }

		    }else
		    {
		    	return -2;
		    }
		}
		
	}
}

?>