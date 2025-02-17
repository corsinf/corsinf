<?php
if (!class_exists('db')) {
	include('../db/db.php');
}
/**
 * 
 */
class ingresar_procesoM
{
	private $db;

	function __construct()
	{
		$this->db = new db();
	}

	function insertar($table, $datos)
	{
		$rest = $this->db->inserts($table, $datos);

		return $rest;
	}

	function editar($table, $datos, $where)
	{

		$rest = $this->db->update($table, $datos, $where);
		return $rest;
	}

	function eliminar($table, $datos)
	{
		$sql = "UPDATE ac_marcas SET ESTADO='I' WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
		$datos = $this->db->sql_string($sql);
		return $datos;

		//$rest = $this->db->delete('ac_marcas',$datos);
		//return $rest;
	}

	function cargar_lineas($id)
	{
		$sql = "SELECT LS.id_linea_salida as idls,P.id_plantilla as id,A.TAG_SERIE as codigo,A.TAG_ANT AS ori ,A.TAG_UNIQUE as 'rfid',M.DESCRIPCION as marca,P.DESCRIPT as item,P.SERIE as serie,P.MODELO as modelo,observacion_salida as 'salida',observacion_entrada as 'entrada' FROM ac_lineas_solicitud LS 
		INNER JOIN ac_articulos P ON LS.id_activo = P.id_plantilla
		INNER JOIN ac_asset A ON P.ID_ASSET = A.ID_ASSET 
		INNER JOIN ac_marcas M ON P.EVALGROUP1 = M.ID_MARCA
		WHERE id_solicitud = '" . $id . "'";

		// print_r($sql);die();
		return $this->db->datos($sql);
	}
	
	function solicitud_pdf($id)
	{
		$sql = "SELECT * FROM ac_solicitud_salida SS 
		INNER JOIN th_personas P ON SS.solicitante = P.PERSON_NO
		WHERE id_solicitud = '" . $id . "'";
		return $this->db->datos($sql);
	}
}
