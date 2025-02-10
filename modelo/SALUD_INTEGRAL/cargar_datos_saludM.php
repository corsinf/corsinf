<?php
if (!class_exists('db')) {
	include(dirname(__DIR__, 2) . '/db/db.php');
}
/**
 * 
 */
class cargar_datos_saludM
{
	private $db;

	function __construct()
	{
		$this->db = new db();
	}

	function ejecutar_medicamentos($tip)
	{
		set_time_limit(0);
		$OPCION = $tip;
		$USUARIO = $_SESSION['INICIO']['USUARIO'];
		$parametros = array(
			array(&$OPCION, SQLSRV_PARAM_IN),
			array(&$USUARIO, SQLSRV_PARAM_IN)
		);
		$sql = "EXEC SP_ACTUALIZAR_GENERO @OPCION=?,@USUARIO=?";
		$re = $this->db->ejecutar_procesos_almacenados($sql, $parametros);
		return $re;
	}

	function ejecutar_insumos($tip)
	{
		set_time_limit(0);
		$OPCION = $tip;
		$USUARIO = $_SESSION['INICIO']['USUARIO'];
		$parametros = array(
			array(&$OPCION, SQLSRV_PARAM_IN),
			array(&$USUARIO, SQLSRV_PARAM_IN)
		);
		$sql = "EXEC SP_ACTUALIZAR_GENERO @OPCION=?,@USUARIO=?";
		$re = $this->db->ejecutar_procesos_almacenados($sql, $parametros);
		return $re;
	}

	function log_activo($fecha = false, $intento = false, $accion = False, $estado = false)
	{

		$sql = "SELECT * FROM log_activos WHERE 1=1 ";
		if ($fecha) {
			$sql .= " AND fecha = '" . $fecha . "'";
		}
		if ($intento) {
			$sql .= " AND intento='" . $intento . "'";
		}
		if ($accion) {
			$sql .= " AND accion like '%" . $accion . "%'";
		}
		if ($estado) {
			if ($estado == '-1') {
				$sql .= " AND estado = '0'";
			} else {
				$sql .= " AND estado = '" . $estado . "'";
			}
		}
		$sql .= " ORDER by id_log desc ";
		// print_r($sql);die();
		$re = $this->db->datos($sql);
		return $re;
	}
}
