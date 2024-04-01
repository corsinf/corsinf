<?php 
if(!class_exists('db'))
{
	include('../db/db.php');
}
/**
 * 
 */
class ligar_segurosM
{
	private $db;
	
	function __construct()
	{
		$this->db = new db();

	}

	function tabla_no_concurente($query=false)
	{
		$sql = "SELECT TABLE_NAME
				FROM INFORMATION_SCHEMA.TABLES
				WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA = 'dbo';";

		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}


	// function lista_tabla_seguros($query=false)
	// {
	// 	$sql = "SELECT count(*) as 'Total',Tabla,Campo_usuario,Campo_pass
	// 			FROM TABLAS_NOCONCURENTE 
	// 			WHERE Id_Empresa = '".$_SESSION['INICIO']['ID_EMPRESA']."'
	// 			GROUP BY Tabla,Campo_usuario,Campo_pass";
	// 	$datos = $this->db->datos($sql,1);
	// 	return $datos;
	// }


	function datos_no_concurentes($tabla)
	{
		$sql= "SELECT * FROM ".$tabla;
		$datos = $this->db->datos($sql);
		return $datos;
	}


	function id_tabla_no_concurentes($tabla)
	{

		$sql2="SELECT COLUMN_NAME as 'ID'
				FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
				WHERE OBJECTPROPERTY(OBJECT_ID(CONSTRAINT_NAME), 'IsPrimaryKey') = 1
				AND TABLE_NAME = '".$tabla."'";
		$datos2 = $this->db->datos($sql2);
		return $datos2;
	}

	function campos_tabla($tabla,$query=false)
	{
		$sql2="SELECT COLUMN_NAME, DATA_TYPE
			FROM INFORMATION_SCHEMA.COLUMNS
			WHERE TABLE_NAME = '".$tabla."'";
			if($query)
			{
				$sql2.=" AND COLUMN_NAME like '%".$query."%'";
			}
			// print_r($sql2);die();
		$datos2 = $this->db->datos($sql2);
		return $datos2;
	}



	function existe()
	 {
	 	$sql = "SELECT Tabla_seguros FROM EMPRESAS WHERE Id_Empresa = '".$_SESSION['INICIO']['ID_EMPRESA']."'";
		return $this->db->datos($sql,1);
	 }


	function insertar($tabla,$datos,$master=false)
	{
		 $rest = $this->db->inserts($tabla,$datos,$master);
	   
		return $rest;
	}
	function editar($tabla,$datos,$where,$master=false)
	{		
	    $rest = $this->db->update($tabla,$datos,$where,$master);
		return $rest;
	}
	// function eliminar_no_concurente($tabla)
	// {
	// 	$sql = "DELETE FROM TABLAS_NOCONCURENTE WHERE Id_Empresa = '".$_SESSION['INICIO']['ID_EMPRESA']."' AND Tabla = '".$tabla."'";
	// 	$datos = $this->db->sql_string($sql,1);
	// 	return $datos;
	// }

	
}

?>