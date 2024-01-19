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

}

?>