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

	function CamposDb($tabla)
	{
		$sql = "SELECT COLUMN_NAME as 'col', DATA_TYPE, IS_NULLABLE, CHARACTER_MAXIMUM_LENGTH
				FROM INFORMATION_SCHEMA.COLUMNS
				WHERE TABLE_NAME = '".$tabla."';";
		return  $this->db->datos($sql);
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

	function inserts($tabla,$datos)
	{
		return $this->db->inserts($tabla, $datos);
	}

	function lista_impresora()
	{
		$sql="SELECT id_impresora as id,nombre_impresora as nombre
			  FROM ac_impresoras";
		return $this->db->datos($sql);
	}
}

?>