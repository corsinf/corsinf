<?php 
if(!class_exists('db'))
{
 include('../db/db.php');
}
/**
 * 
 */
class impresion_tagM
{
	private $db;
	
	function __construct()
	{
		$this->db = new db();

	}

	// function lista_impresion_tag($id='')
	// {
	// 	$sql = "SELECT ID_impresion_tag,CODIGO,DESCRIPCION FROM impresion_tag ";
	// 	if($id)
	// 	{
	// 		$sql.= ' WHERE ID_impresion_tag= '.$id;
	// 	}
	// 	$sql.=" ORDER BY ID_impresion_tag DESC";
	// 	$datos = $this->db->datos($sql);
	// 	return $datos;
	// }
	// function buscar_impresion_tag($buscar)
	// {
	// 	$sql = "SELECT ID_impresion_tag,CODIGO,DESCRIPCION FROM impresion_tag WHERE DESCRIPCION +' '+CODIGO LIKE '%".$buscar."%'";
	// 	$datos = $this->db->datos($sql);
	// 	return $datos;
	// }

	function insertar($datos)
	{
		$rest = $this->db->inserts('formato_tags',$datos);	   
		return $rest;
	}
	function editar($datos,$where)
	{
		
	    $rest = $this->db->update('impresion_tag',$datos,$where);
		return $rest;
	}
	function eliminar($datos)
	{
	    $rest = $this->db->delete('formato_tags',$datos);
		return $rest;
	}
}

?>