<?php
if (!class_exists('db')) {
	include(dirname(__DIR__, 2) . '/db/db.php');
}

/**
 * 
 **/

class localizacionM
{
	private $db;

	function __construct()
	{
		$this->db = new db();
	}

	function lista_localizacion($query, $ini = 0, $fin = 25)
	{
		// $sql = "SELECT ID_MARCA,CODIGO,DESCRIPCION FROM localizacion ";
		$sql = "SELECT ID_LOCATION,CENTRO,EMPLAZAMIENTO,DENOMINACION FROM LOCATION WHERE ESTADO='A' and DENOMINACION+''+EMPLAZAMIENTO LIKE '%" . $query . "%' ORDER BY ID_LOCATION DESC OFFSET " . $ini . " ROWS FETCH NEXT " . $fin . " ROWS ONLY;";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_localizacion_todo()
	{
		// $sql = "SELECT ID_MARCA,CODIGO,DESCRIPCION FROM localizacion ";
		$sql = "SELECT ID_LOCATION,CENTRO,EMPLAZAMIENTO,DENOMINACION,ESTADO FROM LOCATION";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_localizacion_count($query)
	{
		// $sql = "SELECT ID_MARCA,CODIGO,DESCRIPCION FROM localizacion ";
		$sql = "SELECT COUNT(ID_LOCATION) as 'cant' FROM LOCATION WHERE DENOMINACION LIKE '%" . $query . "%'";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_localizacion($buscar)
	{
		$sql = "SELECT ID_LOCATION,CENTRO,EMPLAZAMIENTO,DENOMINACION FROM LOCATION WHERE ESTADO='A' and ID_LOCATION ='" . $buscar . "'";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_localizacion_($buscar)
	{
		$sql = "SELECT ID_LOCATION,CENTRO,EMPLAZAMIENTO,DENOMINACION FROM LOCATION WHERE  EMPLAZAMIENTO LIKE '" . $buscar . "'";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_localizacion_cant()
	{
		$sql = "SELECT count(ID_LOCATION) as 'cant' FROM LOCATION WHERE 1=1 ";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_localizacion_codigo($buscar)
	{
		$sql = "SELECT ID_LOCATION,CENTRO,EMPLAZAMIENTO,DENOMINACION FROM LOCATION WHERE  EMPLAZAMIENTO='" . $buscar . "'";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function insertar($datos)
	{
		$rest = $this->db->inserts('LOCATION', $datos);
		return $rest;
	}

	function editar($datos, $where)
	{
		$rest = $this->db->update('LOCATION', $datos, $where);
		return $rest;
	}

	function eliminar($datos)
	{
		$sql = "UPDATE LOCATION SET ESTADO='I' WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
		$datos = $this->db->sql_string($sql);
		return $datos;

		//$rest = $this->db->delete('LOCATION',$datos);
		//return $rest;	   	   
	}
}
