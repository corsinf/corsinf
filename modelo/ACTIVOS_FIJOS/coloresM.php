<?php
if (!class_exists('db')) {
	include(dirname(__DIR__, 2) . '/db/db.php');
}

/**
 * 
 **/

class coloresM
{
	private $db;

	function __construct()
	{
		$this->db = new db();
	}

	function lista_colores($id = '')
	{
		$sql = "SELECT ID_COLORES,CODIGO,DESCRIPCION FROM ac_colores  WHERE ESTADO='A' ";
		if ($id) {
			$sql .= ' and ID_COLORES= ' . $id;
		}
		$sql .= " ORDER BY ID_COLORES ";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_colores_todo($id = '')
	{
		$sql = "SELECT ID_COLORES,CODIGO,DESCRIPCION,ESTADO FROM ac_colores  WHERE 1=1 ";
		if ($id) {
			$sql .= ' and ID_COLORES= ' . $id;
		}
		$sql .= " ORDER BY ID_COLORES ";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_colores($buscar)
	{
		$sql = "SELECT ID_COLORES,CODIGO,DESCRIPCION FROM ac_colores WHERE ESTADO='A' and DESCRIPCION +' '+CODIGO LIKE '%" . $buscar . "%'";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_colores_codigo($buscar)
	{
		$sql = "SELECT ID_COLORES,CODIGO,DESCRIPCION FROM ac_colores WHERE CODIGO='" . $buscar . "'";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function insertar($datos)
	{
		$rest = $this->db->inserts('ac_colores', $datos);

		return $rest;
	}

	function editar($datos, $where)
	{

		$rest = $this->db->update('ac_colores', $datos, $where);
		return $rest;
	}
	
	function eliminar($datos)
	{
		$sql = "UPDATE ac_colores SET ESTADO='I' WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
		$datos = $this->db->sql_string($sql);
		return $datos;

		//$rest = $this->db->delete('ac_colores',$datos);
		//return $rest;
	}
}
