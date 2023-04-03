<?php
if(!class_exists('db'))
{
	include('../db/db.php');
}
/**
 * 
 */
class generoM
{
	private $db;
	
	function __construct()
	{
		$this->db = new db();

	}

	function lista_genero($id='')
	{
		$sql = "SELECT ID_GENERO,CODIGO,DESCRIPCION FROM GENERO WHERE ESTADO='A' ";
		if($id)
		{
			$sql.= ' and ID_GENERO= '.$id;
		}
		$sql.=" ORDER BY ID_GENERO ";
		$datos = $this->db->datos($sql);
		return $datos;
	}
	function lista_genero_todo($id='')
	{
		$sql = "SELECT ID_GENERO,CODIGO,DESCRIPCION,ESTADO FROM GENERO WHERE 1=1 ";
		if($id)
		{
			$sql.= ' and ID_GENERO= '.$id;
		}
		$sql.=" ORDER BY ID_GENERO ";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_genero($buscar)
	{
		$sql = "SELECT ID_GENERO,CODIGO,DESCRIPCION FROM GENERO WHERE ESTADO='A' and DESCRIPCION +' '+CODIGO LIKE '%".$buscar."%'";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_genero_CODIGO($buscar)
	{
		$sql = "SELECT ID_GENERO,CODIGO,DESCRIPCION FROM GENERO WHERE CODIGO = '".$buscar."'";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function insertar($datos)
	{
		 $rest = $this->db->inserts('GENERO',$datos);
	   
		return $rest;
	}
	function editar($datos,$where)
	{
		
	    $rest = $this->db->update('GENERO',$datos,$where);
		return $rest;
	}
	function eliminar($datos)
	{

		$sql = "UPDATE GENERO SET ESTADO='I' WHERE ".$datos[0]['campo']."='".$datos[0]['dato']."';";
		$datos = $this->db->sql_string($sql);
		return $datos;

	    //$rest = $this->db->delete('GENERO',$datos);
		//return $rest;
	}
}

?>