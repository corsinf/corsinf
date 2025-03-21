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
 		$sql = "SELECT id_portal as id,ip_portal as ip,nombre_portal as nombre,puerto_portal as puerto,serie_portal as serie,comunicacion_portal as comunicacion,com_portal as com,com2_portal as com2,adr as adr485
 		FROM ac_portales
 		WHERE 1=1";
 		if($id)
 		{
 			$sql.= " AND id_portal = '".$id."'";
 		}
 		// print_r($sql);die();
 		return $this->db->datos($sql);

 	}

  function listar_log($id=false)
  {
    $sql = "SELECT P.ac_plog_rfid as rfid,p.ac_plog_fecha_creacion as fecha,P.ac_plog_antena as antena,PO.nombre_portal as controladora,PO.ip_portal,A.descripcion
      FROM ac_portales_logs P
      LEFT JOIN ac_articulos A ON P.ac_plog_rfid = A.tag_unique
      INNER JOIN ac_portales PO ON P.ac_plog_controladora = PO.id_portal
      ORDER BY P.ac_plog_id DESC";
    return $this->db->datos($sql);

  }

  function guardar_antena($tabla,$datos)
  {
    return $this->db->inserts($tabla, $datos); 
  }

  function eliminar_portal_antena($id)
  {
    $sql ="DELETE FROM ac_portales WHERE id_portal = '".$id."' ";
    return $this->db->sql_string($sql); 
  }
 } 
?>