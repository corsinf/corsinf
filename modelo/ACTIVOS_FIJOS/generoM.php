<?php
if (!class_exists('db')) {
	include(dirname(__DIR__, 2) . '/db/db.php');
}

/**
 * 
 **/

class generoM
{
	private $db;

	function __construct()
	{
		$this->db = new db();
	}

	function lista_genero($id = '')
	{
		$sql = "SELECT ID_GENERO,CODIGO,DESCRIPCION FROM ac_genero WHERE ESTADO='A' ";
		if ($id) {
			$sql .= ' and ID_GENERO= ' . $id;
		}
		$sql .= " ORDER BY ID_GENERO ";
		$datos = $this->db->datos($sql);
		return $datos;
	}
	
	function lista_genero_todo($id = '')
	{
		$sql = "SELECT ID_GENERO,CODIGO,DESCRIPCION,ESTADO FROM ac_genero WHERE 1=1 ";
		if ($id) {
			$sql .= ' and ID_GENERO= ' . $id;
		}
		$sql .= " ORDER BY ID_GENERO ";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_genero($buscar)
	{
		$sql = "SELECT ID_GENERO,CODIGO,DESCRIPCION FROM ac_genero WHERE ESTADO='A' and DESCRIPCION +' '+CODIGO LIKE '%" . $buscar . "%'";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_genero_CODIGO($buscar)
	{
		$sql = "SELECT ID_GENERO,CODIGO,DESCRIPCION FROM ac_genero WHERE CODIGO = '" . $buscar . "'";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function insertar($datos)
	{
		$rest = $this->db->inserts('ac_genero', $datos);

		return $rest;
	}

	function editar($datos, $where)
	{

		$rest = $this->db->update('ac_genero', $datos, $where);
		return $rest;
	}

	function eliminar($datos)
	{

		$sql = "UPDATE ac_genero SET ESTADO='I' WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
		$datos = $this->db->sql_string($sql);
		return $datos;

		//$rest = $this->db->delete('ac_genero',$datos);
		//return $rest;
	}
}
