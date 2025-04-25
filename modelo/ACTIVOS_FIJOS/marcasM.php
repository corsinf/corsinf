<?php
if (!class_exists('db')) {
	include(dirname(__DIR__, 2) . '/db/db.php');
}

/**
 * 
 **/

class marcasM
{
	private $db;

	function __construct()
	{
		$this->db = new db();
	}

	function lista_marcas($id = '', $pag = false)
	{
		// $sql = "SELECT ID_MARCA,CODIGO,DESCRIPCION FROM ac_marcas ";

		// print_r($pag);die();

		$sql = "SELECT ID_MARCA,CODIGO,DESCRIPCION FROM ac_marcas WHERE ESTADO='A' ";
		if ($id) {
			$sql .= ' AND ID_MARCA= ' . $id;
		}
		
		$sql .= " ORDER BY ID_MARCA ";

		if ($pag) {
			$pagi = explode('-', $pag);
			$ini = $pagi[0];
			$fin = $pagi[1];
			$sql .= "OFFSET " . $ini . " ROWS FETCH NEXT 25 ROWS ONLY;";
		}

		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}
	function lista_marcas_todo($id = '', $pag = false)
	{
		// $sql = "SELECT ID_MARCA,CODIGO,DESCRIPCION FROM ac_marcas ";

		// print_r($pag);die();

		$sql = "SELECT ID_MARCA,CODIGO,DESCRIPCION,ESTADO FROM ac_marcas WHERE 1=1 ";
		if ($id) {
			$sql .= ' AND ID_MARCA= ' . $id;
		}
		$sql .= " ORDER BY ID_MARCA ";
		if ($pag) {
			$pagi = explode('-', $pag);
			$ini = $pagi[0];
			$fin = $pagi[1];
			$sql .= "OFFSET " . $ini . " ROWS FETCH NEXT 25 ROWS ONLY;";
		}

		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_marcas_pag()
	{
		$sql = "SELECT ID_MARCA,CODIGO,DESCRIPCION FROM ac_marcas WHERE ESTADO='A'";
		// $sql = "SELECT TOP() ID_MARCA,CODIGO,DESCRIPCION FROM ac_marcas ";
		// if($id)
		// {
		// 	$sql.= ' WHERE ID_MARCA= '.$id;
		// }
		$sql .= " ORDER BY ID_MARCA DESC";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_marcas($buscar)
	{
		$sql = "SELECT ID_MARCA,CODIGO,DESCRIPCION FROM ac_marcas WHERE ESTADO='A' AND DESCRIPCION +' '+CODIGO LIKE '%" . $buscar . "%'  ORDER BY ID_MARCA  OFFSET 0 ROWS FETCH NEXT 25 ROWS ONLY;";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_marcas_excel($buscar)
	{
		$sql = "SELECT ID_MARCA,CODIGO,DESCRIPCION FROM ac_marcas WHERE UPPER(DESCRIPCION) = UPPER('" . $buscar . "');";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_marcas_codigo($buscar)
	{
		$sql = "SELECT ID_MARCA,CODIGO,DESCRIPCION FROM ac_marcas WHERE CODIGO ='" . $buscar . "';";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_marcas_all($buscar = false, $ID = false)
	{
		$sql = "SELECT ID_MARCA,CODIGO,DESCRIPCION FROM ac_marcas WHERE 1=1";
		if ($buscar) {
			$sql .= " AND CODIGO ='" . $buscar . "';";
		}
		if ($ID) {
			$sql .= "  AND ID_MARCA = '" . $ID . "'";
		}
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function insertar($datos)
	{
		$rest = $this->db->inserts('ac_marcas', $datos);

		return $rest;
	}

	function editar($datos, $where)
	{

		$rest = $this->db->update('ac_marcas', $datos, $where);
		return $rest;
	}

	function eliminar($datos)
	{
		$sql = "UPDATE ac_marcas SET ESTADO='I' WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
		$datos = $this->db->sql_string($sql);
		return $datos;

		//$rest = $this->db->delete('ac_marcas',$datos);
		//return $rest;
	}
}
