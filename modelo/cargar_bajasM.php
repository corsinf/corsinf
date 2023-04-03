<?php 
if(!class_exists('db'))
{
 include('../db/db.php');
}
/**
 * 
 */
class cargar_bajasM
{
	private $db;
	
	function __construct()
	{
		$this->db = new db();

	}

	function ejecutar_bajas()
	{	   
		
		set_time_limit(0);
		$conn = $this->db->conexion();

		$USUARIO = $_SESSION['INICIO']['USUARIO'];
		$parametros = array(
	     array(&$USUARIO, SQLSRV_PARAM_IN)
	    );		
	    $sql = "EXEC SP_ACTUALIZAR_BAJAS @USUARIO = ?";
		return $this->db->ejecutar_procesos_almacenados($sql,$parametros);
	}
	function ejecutar_terceros()
	{	  
	
		set_time_limit(0); 
		$conn = $this->db->conexion();

		$USUARIO = $_SESSION['INICIO']['USUARIO'];
		$parametros = array(
	     array(&$USUARIO, SQLSRV_PARAM_IN)
	    );		
	    $sql = "EXEC SP_ACTUALIZAR_TERCEROS @USUARIO = ?";
		return $this->db->ejecutar_procesos_almacenados($sql,$parametros);
	}

	function ejecutar_patrimoniales()
	{	   
		
		set_time_limit(0);
		$conn = $this->db->conexion();

		$USUARIO = $_SESSION['INICIO']['USUARIO'];
		$parametros = array(
	     array(&$USUARIO, SQLSRV_PARAM_IN)
	    );
		
	    $sql = "EXEC SP_ACTUALIZAR_PATRIMONIALES @USUARIO = ?";
		return $this->db->ejecutar_procesos_almacenados($sql,$parametros);
	}
}

?>