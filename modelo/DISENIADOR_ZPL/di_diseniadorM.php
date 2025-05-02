<?php 
 require_once(dirname(__dir__,2).'/db/db.php');
/**
 * 
 */
class di_diseniadorM
{
	
	function __construct()
	{
		// code...
		$this->db = new db();
	}

	function buscar_etiquetas_anteriores($id)
	{
		$sql = "SELECT * 
				FROM IMPRIMIR_TAGS
				WHERE ID_USUARIO = '".$id."'";
		return  $this->db->datos($sql);
	}

	function eliminar_etiquetas_anteriores($id)
	{
		$sql = "DELETE 
				FROM IMPRIMIR_TAGS
				WHERE ID_USUARIO = '".$id."'";
		return  $this->db->sql_string($sql);
	}
	function tablasDb()
	{
		$sql = "SELECT TABLE_NAME
				FROM INFORMATION_SCHEMA.TABLES
				WHERE TABLE_TYPE = 'BASE TABLE'
				ORDER BY TABLE_NAME ASC;";
		return  $this->db->datos($sql);
	}

	function tablasDbTerceros($database, $usuario, $password, $servidor, $puerto)
	{
		$sql = "SELECT TABLE_NAME
				FROM INFORMATION_SCHEMA.TABLES
				WHERE TABLE_TYPE = 'BASE TABLE'
				ORDER BY TABLE_NAME ASC;";
		return $this->db->datos_db_terceros($database, $usuario, $password, $servidor, $puerto, $sql);
	}

	function CamposDb($tabla)
	{
		$sql = "SELECT COLUMN_NAME as 'col', DATA_TYPE, IS_NULLABLE, CHARACTER_MAXIMUM_LENGTH
				FROM INFORMATION_SCHEMA.COLUMNS
				WHERE TABLE_NAME = '".$tabla."';";
		return  $this->db->datos($sql);
	}
	function CamposDbTerceros($tabla,$database, $usuario, $password, $servidor, $puerto)
	{
		$sql = "SELECT COLUMN_NAME as 'col', DATA_TYPE, IS_NULLABLE, CHARACTER_MAXIMUM_LENGTH
				FROM INFORMATION_SCHEMA.COLUMNS
				WHERE TABLE_NAME = '".$tabla."';";
		return $this->db->datos_db_terceros($database, $usuario, $password, $servidor, $puerto, $sql);
	}

	function datosdb($campo_select,$tabla,$campo_rfid='',$tabla2='')
	{
		$datos = array();
		$datos2 = array();
		$sql = 'SELECT '.$campo_select.' FROM '.$tabla;
		$datos =  $this->db->datos($sql);
		if($campo_rfid!='' && $tabla2!='')
		{
			$sql2 = 'SELECT '.$campo_rfid.' FROM '.$tabla2;
			$datos2 =  $this->db->datos($sql2);
		}

		return array('principal'=>$datos,'rfid'=>$datos2);
	}

	function datosdbTerceros($campo_select,$tabla,$database, $usuario, $password, $servidor, $puerto)
	{
		$datos = array();
		$datos2 = array();
		$sql = 'SELECT '.$campo_select.' FROM '.$tabla;
		$datos =  $this->db->datos_db_terceros($database, $usuario, $password, $servidor, $puerto, $sql);
		

		return array('principal'=>$datos,'rfid'=>$datos2);
	}

	function inserts($tabla,$datos)
	{
		return $this->db->inserts($tabla, $datos);
	}
	function update($tabla,$datos,$where)
	{
		return $this->db->update($tabla, $datos, $where);
	}

	function lista_impresora()
	{
		$sql="SELECT id_impresora as id,nombre_impresora as nombre,tipo_impresora,ip_impresora,ruta_impresora,puerto_impresora
			  FROM ac_impresoras";
		return $this->db->datos($sql);
	}

	function ListaEtiquetas($id=false)
	{
		$sql="SELECT ac_disenio_tag_id,ac_disenio_tag_nombre,ac_disenio_tag_elementos,ac_disenio_tag_creacion,ac_disenio_tag_ancho,ac_disenio_tag_alto,ac_disenio_tag_dpi,ac_disenio_tag_unidad,ac_disenio_tag_rfid
			  FROM ac_disenio_tag
			  WHERE 1 = 1";
			  if($id)
			  {
			  	$sql.=" AND ac_disenio_tag_id = '".$id."'"; 
			  }
		return $this->db->datos($sql);
	}

	function deleteEtiquetas($id)
	{
		$sql = "DELETE FROM ac_disenio_tag WHERE ac_disenio_tag_id = '".$id."'";
		return $this->db->sql_string($sql);
	}

	function comprobar_conexcion_terceros($database, $usuario, $password, $servidor, $puerto)
	{
		return $this->db->comprobar_conexcion_terceros($database, $usuario, $password, $servidor, $puerto);
	}
}

?>