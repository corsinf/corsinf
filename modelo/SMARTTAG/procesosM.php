<?php
@session_start();
if(!class_exists('db')){
if($_SESSION['INICIO']['TIPO_BASE'] == 'MYSQL')
   {
   	require_once(dirname(__DIR__, 2) .'/db/db_mysql.php');
   }else
   {
   	 require_once(dirname(__DIR__, 2) .'/db/db_sql.php'); 
   }
}
/**
 * 
 */
class procesosM
{
	private $db;
	function __construct()
	{		
		$this->db = new db();
	}

	function lista_procesos()
	{
		$sql = "SELECT  id_proceso as 'cod',detalle_proceso as 'detalle',color_proceso as 'color' FROM proceso WHERE estado_proceso = 'A'";
		$datos = $this->db->datos($sql);
		return $datos;
	}
}
?>