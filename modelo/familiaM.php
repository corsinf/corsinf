<?php 
if(!class_exists('db'))
{
 include('../db/db.php');
}
/**
 * 
 */
class familiaM
{
	private $db;
	
	function __construct()
	{
		$this->db = new db();

	}

	function lista_familia($id=false,$query=false)
	{
		$sql = "SELECT id_familia as 'id',detalle_familia as 'text' FROM FAMILIAS WHERE familia ='.'";
		if($id)
		{
			$sql.= ' AND id_familia= '.$id;
		}
		if($query)
		{
			$sql.=" AND detalle_familia like '%".$query."%'";
		}
		$sql.=" ORDER BY id_familia DESC";
		$datos = $this->db->datos($sql);
		return $datos;
	}
	function lista_sub($id=false,$query=false)
	{
		$sql = "SELECT id_familia as 'id',detalle_familia as 'text' FROM FAMILIAS WHERE familia <>'.' AND familia";
		if($id)
		{
			$sql.= ' AND id_familia= '.$id;
		}
		if($query)
		{
			$sql.=" AND detalle_familia like '%".$query."%'";
		}
		$sql.=" ORDER BY id_familia DESC";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_subfamilia($id=false,$fam=false,$query=false)
	{
		$sql = "SELECT id_familia as 'id',detalle_familia as 'text' FROM FAMILIAS 
		WHERE 1=1 ";
		if($fam)
		{
			$sql.=" AND familia ='".$fam."'";
		}
		if($id)
		{
			$sql.= ' AND id_familia= '.$id;
		}
		if($query)
		{
			$sql.=" AND detalle_familia like '%".$query."%'";
		}
		$sql.=" ORDER BY id_familia DESC";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	// function buscar_estado($buscar)
	// {
	// 	$sql = "SELECT ID_ESTADO,CODIGO,DESCRIPCION FROM estado WHERE ESTADO='A' AND DESCRIPCION +' '+CODIGO LIKE '%".$buscar."%'";
	// 	$datos = $this->db->datos($sql);
	// 	return $datos;
	// }


	// function buscar_estado_CODIGO($buscar)
	// {
	// 	$sql = "SELECT ID_ESTADO,CODIGO,DESCRIPCION FROM estado WHERE CODIGO = '".$buscar."'";
	// 	$datos = $this->db->datos($sql);
	// 	return $datos;
	// }

	function insertar($tabla,$datos)
	{
		 $rest = $this->db->inserts($tabla,$datos);
	   
		return $rest;
	}
	function editar($tabla,$datos,$where)
	{
		
	    $rest = $this->db->update($tabla,$datos,$where);
		return $rest;
	}
	function eliminar($datos)
	{

		$sql = "UPDATE FAMILIAS SET ESTADO='I' WHERE ".$datos[0]['campo']."='".$datos[0]['dato']."';";
		$datos = $this->db->sql_string($sql);
		return $datos;

	   // $rest = $this->db->delete('ESTADO',$datos);
		//return $rest;
	}
}

?>