<?php
if(!class_exists('db'))
{
	include('../db/db.php');
}
/**
 * 
 */
class seccionM
{
	private $db;
	
	function __construct()
	{
		$this->db = new db();

	}

	function lista_seccion($id='')
	{
		$sql = "SELECT ID_SECCION,CODIGO,DESCRIPCION FROM SECCION WHERE ESTADO='A' ";
		if($id)
		{
			$sql.= ' and ID_SECCION= '.$id;
		}
		$sql.=" ORDER BY ID_SECCION ";
		$datos = $this->db->datos($sql);
		return $datos;
	}
	function lista_seccion_todo($id='')
	{
		$sql = "SELECT ID_SECCION,CODIGO,DESCRIPCION,ESTADO FROM SECCION WHERE 1=1 ";
		if($id)
		{
			$sql.= ' and ID_SECCION= '.$id;
		}
		$sql.=" ORDER BY ID_SECCION ";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_seccion($buscar)
	{
		$sql = "SELECT ID_SECCION,CODIGO,DESCRIPCION FROM SECCION WHERE ESTADO='A' and DESCRIPCION +' '+CODIGO LIKE '%".$buscar."%'";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_seccion_CODIGO($buscar)
	{
		$sql = "SELECT ID_SECCION,CODIGO,DESCRIPCION FROM SECCION WHERE CODIGO = '".$buscar."'";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function insertar($datos)
	{
		 $rest = $this->db->inserts('SECCION',$datos);
	   
		return $rest;
	}
	function editar($datos,$where)
	{
		
	    $rest = $this->db->update('SECCION',$datos,$where);
		return $rest;
	}
	function eliminar($datos)
	{

		$sql = "UPDATE SECCION SET ESTADO='I' WHERE ".$datos[0]['campo']."='".$datos[0]['dato']."';";
		$datos = $this->db->sql_string($sql);
		return $datos;

	    //$rest = $this->db->delete('SECCION',$datos);
		//return $rest;
	}
}

?>