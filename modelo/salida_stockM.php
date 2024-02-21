<?php 
if(!class_exists('db'))
{
	include_once('../db/db.php');
}
/**
 * 
 */
class salida_stockM
{
	private $db;
	
	function __construct()
	{
		$this->db = new db();

	}

	function insertar($table,$datos)
	{
		 $rest = $this->db->inserts($table,$datos);
	   
		return $rest;
	}
	function editar($table,$datos,$where)
	{
		
	    $rest = $this->db->update($table,$datos,$where);
		return $rest;
	}
	function eliminar($table,$datos)
	{
		$sql = "UPDATE MARCAS SET ESTADO='I' WHERE ".$datos[0]['campo']."='".$datos[0]['dato']."';";
		$datos = $this->db->sql_string($sql);
		return $datos;

	    //$rest = $this->db->delete('MARCAS',$datos);
		//return $rest;
	}
}

?>