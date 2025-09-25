<?php

require_once(dirname(__DIR__, 3) . '/db/db.php');

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

  function listar($id = false)
  {
    $sql = "SELECT id_portal as id,ip_portal as ip,nombre_portal as nombre,puerto_portal as puerto,serie_portal as serie,comunicacion_portal as comunicacion,com_portal as com,com2_portal as com2,adr as adr485
 		FROM ac_portales
 		WHERE 1=1";
    if ($id) {
      $sql .= " AND id_portal = '" . $id . "'";
    }
    // print_r($sql);die();
    return $this->db->datos($sql);
  }

  function listar_log($id = false, $fecha = '')
  {
    if (empty($fecha)) {
      $fecha = date('Y-m-d');
    }

    $sql = "
        SELECT 
            P.ac_plog_rfid AS rfid,
            P.ac_plog_fecha_creacion AS fecha,
            P.ac_plog_antena AS antena,
            PO.nombre_portal AS controladora,
            PO.id_portal,
            A.descripcion
        FROM ac_portales_logs P
        LEFT JOIN ac_articulos A ON P.ac_plog_rfid = A.tag_unique
        INNER JOIN ac_portales PO ON P.ac_plog_controladora = PO.id_portal
        WHERE P.ac_plog_fecha_creacion >= '{$fecha} 00:00:00'
          AND P.ac_plog_fecha_creacion <  '{$fecha} 23:59:59'
        ORDER BY P.ac_plog_id DESC;
    ";

    return $this->db->datos($sql);
  }
  

  function guardar_antena($tabla, $datos)
  {
    return $this->db->inserts($tabla, $datos);
  }

  function eliminar_portal_antena($id)
  {
    $sql = "DELETE FROM ac_portales WHERE id_portal = '" . $id . "' ";
    return $this->db->sql_string($sql);
  }

  function simulador()
  {

    $sql_portal =
      "SELECT TOP 1 id_portal AS 'ID_PORTAL'
      FROM ac_portales
      ORDER BY NEWID();";

    $sql_rfid =
      "SELECT TOP 1 tag_unique AS 'RFID'
      FROM ac_articulos
      ORDER BY NEWID();";

    $ID_PORTAL = $this->db->datos($sql_portal)[0]['ID_PORTAL'];
    $RFID = $this->db->datos($sql_rfid)[0]['RFID'] ?? '';
    $numero_antena = mt_rand(1, 4);
    $RFID_ran = $this->generar_RFID(16);


    $probabilidad = 70;

    if (mt_rand(1, 100) <= $probabilidad && $RFID != '') {
      $resultado = $RFID;
    } else {
      $resultado = $RFID_ran;
    }

    // echo $ID_PORTAL . "  RFID-> " . $resultado . "  numero_antena: " . $numero_antena;

    $datos = array(
      array('campo' => 'ac_plog_controladora', 'dato' => $ID_PORTAL),
      array('campo' => 'ac_plog_rfid', 'dato' => $resultado),
      array('campo' => 'ac_plog_antena', 'dato' => $numero_antena),
    );

    return $this->guardar_antena('ac_portales_logs', $datos);
  }

  function generar_RFID($longitud = 24)
  {
    return strtoupper(bin2hex(random_bytes($longitud / 2)));
  }
}
