<?php

require_once(dirname(__DIR__, 2) . '/db/db.php');

/**
  * 
  */
 class portalesM 
 {
 	private $db;
 	
 	function __construct()
 	{
 		$this->db = new db();
 	}

 	function listar($id=false)
 	{
 		$sql = "SELECT id_portal as id,ip_portal as ip,nombre_portal as nombre,puerto_portal as puerto,serie_portal as serie,comunicacion_portal as comunicacion
 		FROM ac_portales
 		WHERE 1=1";
 		if($id)
 		{
 			$sql.= " AND id_portal = '".$id."'";
 		}
 		// print_r($sql);die();
 		return $this->db->datos($sql);

 	}
 } 
?>