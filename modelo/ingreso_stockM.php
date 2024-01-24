<?php
if(!class_exists('db'))
{
	include('../db/db.php');
}

/**
 * 
 */
class ingreso_stockM
{
	private $db;
	
	function __construct()
	{
		$this->db = new db();
	}

	function update($tabla,$datos,$where)
	{
		return $this->db->update($tabla,$datos,$where);
	}
	function guardar($tabla,$datos)
	{
		return $this->db->inserts($tabla,$datos);
	}

	function total_stock($id,$tipo)
	{
		$sql = "SELECT SUM(CONVERT(float, sa_kar_entrada))-SUM(CONVERT(float, sa_kar_salida)) as total 
		FROM kardex 
		WHERE sa_kar_id_articulo = ".$id." AND sa_kar_tipo = '".$tipo."'";

		return  $this->db->datos($sql);
	}

	function stock($id,$tipo)
	{
		$sql = "SELECT *
		FROM kardex 
		WHERE sa_kar_id_articulo = ".$id." AND sa_kar_tipo = '".$tipo."'
		ORDER BY sa_kar_id desc ";

		return  $this->db->datos($sql);
	}

}

?>