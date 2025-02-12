<?php

if (!class_exists('db')) {
	include(dirname(__DIR__, 2) . '/db/db.php');
}

/**
 * 
 */

class custodioM
{
	private $db;

	function __construct()
	{
		$this->db = new db();
	}

	function lista_custodio($query = false, $ini = 0, $fin = 25)
	{
		$sql = "SELECT ID_PERSON,PERSON_NO,PERSON_NOM,PERSON_CI,PERSON_CORREO,PUESTO,UNIDAD_ORG,DIRECCION,TELEFONO,FOTO FROM PERSON_NO WHERE ESTADO='A' ";
		if ($query) {
			$sql .= " AND PERSON_NOM+''+PERSON_NO LIKE '%" . $query . "%' ";
		}
		$sql .= " ORDER BY ID_PERSON DESC OFFSET " . $ini . " ROWS FETCH NEXT 25 ROWS ONLY;";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_custodio_count($query = false)
	{
		$sql = "SELECT count (ID_PERSON) as 'cant' FROM PERSON_NO WHERE 1=1";
		if ($query) {
			$sql .= " AND PERSON_NOM LIKE '%" . $query . "%';";
		}
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_custodio($buscar)
	{
		$sql = "SELECT ID_PERSON,PERSON_NO,PERSON_NOM,PERSON_CI,PERSON_CORREO,PUESTO,UNIDAD_ORG,DIRECCION,TELEFONO,FOTO FROM PERSON_NO WHERE ESTADO='A' and ID_PERSON ='" . $buscar . "'";
		// print_r($sql);die();		
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_custodio_todo($id = false, $person_no = false, $person_nom = false)
	{
		$sql = "SELECT ID_PERSON,PERSON_NO,PERSON_NOM,PERSON_CI,PERSON_CORREO,PUESTO,UNIDAD_ORG,ESTADO FROM PERSON_NO WHERE 1=1 ";
		if ($id) {
			$sql .= " and ID_PERSON = '" . $id . "'";
		}
		if ($person_no) {
			$sql .= " and PERSON_NO = '" . $person_no . "'";
		}
		// print_r($sql);die();		
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_custodio_($buscar)
	{
		$sql = "SELECT ID_PERSON,PERSON_NOM,PERSON_CI,PERSON_CORREO,PUESTO,UNIDAD_ORG FROM PERSON_NO WHERE PERSON_NO LIKE '" . $buscar . "'";
		// print_r($sql);die();		
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function insertar($datos)
	{
		$rest = $this->db->inserts('PERSON_NO', $datos);
		return $rest;
	}

	function editar($datos, $where)
	{
		$rest = $this->db->update('PERSON_NO', $datos, $where);
		return $rest;
	}

	function eliminar($datos)
	{
		$sql = "UPDATE PERSON_NO SET ESTADO='I' WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
		$datos = $this->db->sql_string($sql);
		return $datos;

		//$rest = $this->db->delete('PERSON_NO',$datos);
		//return $rest;	   
	}
}
