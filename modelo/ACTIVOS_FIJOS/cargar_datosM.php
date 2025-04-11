<?php
if (!class_exists('db')) {
	include(dirname(__DIR__, 2) . '/db/db.php');
}

/**
 * 
 **/

class cargar_datosM
{
	private $db;

	function __construct()
	{
		$this->db = new db();
	}

	function actualizar_datos($parametros)
	{
		if ($parametros['parte_actual'] == 1) {
			return	$this->ejecutar_primera_parte($parametros);
		} else {
			die();
			set_time_limit(0);
			$limite = 900;
			$resp = 0;
			if ($parametros['parte_actual'] == $parametros['partes']) {
				$resp = 1;
			}
			$parte_no = $parametros['parte_actual'] + 1;
			$ini = (($limite * $parametros['parte_actual']) - $limite);
			$fin = $limite * $parametros['parte_actual'];

			$ASSETS = '';
			$archivo = '../TEMP/datos.csv';
			$fp = fopen($archivo, "r");
			$numRows = 0;
			while ($data = fgetcsv($fp, 1000, ";")) {
				$num = count($data);
				if (is_numeric($data[1])) {
					if ($numRows > $ini && $numRows <= $fin) {
						$ASSETS .= $data[1] . ',';
					}
					if ($numRows == $fin) {
						break;
					}
					$numRows++;
				}
			}
			$ASSETS = substr($ASSETS, 0, -1);
			$sql = "UPDATE P
				SET datos = 1
				FROM ac_articulos P
				INNER JOIN ac_asset A
				ON A.ID_ASSET = P.ID_ASSET
				WHERE A.TAG_SERIE 
				IN (" . $ASSETS . ")";

			// print_r($sql);die();
			$this->db->sql_string($sql);

			return  array('parte_actual' => $parte_no, 'partes' => $parametros['partes'], 'TotalReg' => $parametros['total'], 'fin' => $resp);
		}
	}

	function ejecutar_primera_parte($parametros)
	{
		set_time_limit(0);
		$limite = 900;
		$partes = 1;
		$ini = 1;
		$ASSETS = '';
		$archivo = '../TEMP/datos.csv';
		$fp = fopen($archivo, "r");
		$numRows = 0;
		$sql = '';
		while ($data = fgetcsv($fp, 1000, ";")) {
			$num = count($data);
			if (is_numeric($data[1])) {
				if ($numRows == $limite) {
					$ASSETS = substr($ASSETS, 0, -1);
					$sql = "UPDATE P
						SET datos = 1
						FROM ac_articulos P
						INNER JOIN ac_asset A
						ON A.ID_ASSET = P.ID_ASSET
						WHERE A.TAG_SERIE 
						IN (" . $ASSETS . ")";
					$this->db->sql_string($sql);
				}
				$ASSETS .= $data[1] . ',';
				$numRows++;
			}
		}

		// $this->db->sql_string($sql);		
		$fin = $numRows;
		if ($numRows > $limite) {
			$partes = ($numRows / $limite);
			$fin = $limite;
		}
		if (is_float($partes)) {
			$partes = intval($partes) + 1;
		}
		return  array('parte_actual' => 2, 'partes' => $partes, 'TotalReg' => $numRows, 'fin' => 0);
	}

	function BUSCAR_ID_PLANTILLA($ac_asset)
	{
		$sql = "SELECT id_plantilla as 'ID' FROM ac_articulos P
				INNER JOIN ac_asset A ON P.ID_ASSET =  A.ID_ASSET
				WHERE A.TAG_SERIE = '" . $ac_asset . "'";
		$datos = $this->db->datos($sql);
		if (count($datos) > 0) {
			return $datos[0]['ID'];
		} else {
			return '-1';
		}
	}

	/************************************************************************************/

	function ejecutar_activos()
	{
		set_time_limit(0);
		$USUARIO = $_SESSION['INICIO']['USUARIO'];
		$parametros = array(
			$USUARIO
		);
		$sql = "EXEC SP_CARGA_MASIVA_NEW @USUARIO=?";
		$re = $this->db->ejecutar_procedimiento_con_retorno_1($sql, $parametros);
		return $re;
	}

	function ejecutar_custodio($tip)
	{
		set_time_limit(0);
		$OPCION = $tip;
		$USUARIO = $_SESSION['INICIO']['USUARIO'];
		$parametros = array($OPCION, $USUARIO);
		$sql = "EXEC SP_ACTUALIZAR_CUSTODIOS @OPCION=?,@USUARIO=?";
		$re = $this->db->ejecutar_procedimiento_con_retorno_1($sql, $parametros);
		return $re;
	}

	function ejecutar_localizacion($tip)
	{
		set_time_limit(0);
		$OPCION = $tip;
		$USUARIO = $_SESSION['INICIO']['USUARIO'];
		$parametros = array($OPCION, $USUARIO);
		$sql = "EXEC SP_ACTUALIZAR_LOCALIZACION @OPCION=?,@USUARIO=?";
		$re = $this->db->ejecutar_procedimiento_con_retorno_1($sql, $parametros);
		return $re;
	}

	function ejecutar_marcas($tip)
	{
		set_time_limit(0);
		$OPCION = $tip;
		$USUARIO = $_SESSION['INICIO']['USUARIO'];
		$parametros = array($OPCION, $USUARIO);
		$sql = "EXEC SP_ACTUALIZAR_MARCAS @OPCION=?,@USUARIO=?";
		$re = $this->db->ejecutar_procedimiento_con_retorno_1($sql, $parametros);
		return $re;
	}

	function ejecutar_estado($tip)
	{
		set_time_limit(0);
		$OPCION = $tip;
		$USUARIO = $_SESSION['INICIO']['USUARIO'];
		$parametros = array($OPCION, $USUARIO);
		$sql = "EXEC SP_ACTUALIZAR_ESTADO @OPCION=?,@USUARIO=?";
		$re = $this->db->ejecutar_procedimiento_con_retorno_1($sql, $parametros);
		return $re;
	}

	function ejecutar_genero($tip)
	{
		set_time_limit(0);
		$OPCION = $tip;
		$USUARIO = $_SESSION['INICIO']['USUARIO'];
		$parametros = array($OPCION, $USUARIO);
		$sql = "EXEC SP_ACTUALIZAR_GENERO @OPCION=?,@USUARIO=?";
		$re = $this->db->ejecutar_procedimiento_con_retorno_1($sql, $parametros);
		return $re;
	}

	function ejecutar_colores($tip)
	{
		set_time_limit(0);
		$OPCION = $tip;
		$USUARIO = $_SESSION['INICIO']['USUARIO'];
		$parametros = array($OPCION, $USUARIO);
		$sql = "EXEC SP_ACTUALIZAR_COLORES @OPCION=?,@USUARIO=?";
		$re = $this->db->ejecutar_procedimiento_con_retorno_1($sql, $parametros);
		return $re;
	}

	function ejecutar_proyecto($tip)
	{

		// print_r($tip);die();
		set_time_limit(0);
		$OPCION = $tip;
		$USUARIO = $_SESSION['INICIO']['USUARIO'];
		$parametros = array($OPCION, $USUARIO);
		$sql = "EXEC SP_ACTUALIZAR_PROYECTOS @OPCION=?,@USUARIO=?";
		$re = $this->db->ejecutar_procedimiento_con_retorno_1($sql, $parametros);
		return $re;
	}

	/************************************************************************************/

	function validar_datos()
	{
		$sql = "EXEC SP_CARGAR_PLANTILLA";
		$re = $this->db->ejecutar_procedimiento_con_retorno_1($sql);
		return $re;
	}

	function ejecutar_datos2()
	{
		$conn = $this->db->conexion();
		$sql = "EXEC SP_CARGA_MASIVA";
		$stmt = sqlsrv_prepare($conn, $sql);
		$res = sqlsrv_execute($stmt);
		if ($res === false) {
			echo "Error en consulta PA.\n";
			$respuesta = -1;
			die(print_r(sqlsrv_errors(), true));
		} else {
			sqlsrv_close($conn);
		}
	}

	function ejecutar_clase_movimiento($tip)
	{

		// print_r($tip);die();
		set_time_limit(0);
		$OPCION = $tip;
		$USUARIO = $_SESSION['INICIO']['USUARIO'];
		$parametros = array(
			array(&$OPCION, SQLSRV_PARAM_IN),
			array(&$USUARIO, SQLSRV_PARAM_IN)
		);
		$sql = "EXEC SP_ACTUALIZAR_CLASES_MOVIMIENTO @OPCION=?,@USUARIO=?";
		$re = $this->db->ejecutar_procedimiento_con_retorno_1($sql, $parametros);
		return $re;
	}

	function ejecutar_update_activos($tip)
	{

		// print_r($tip);die();
		set_time_limit(0);
		$USUARIO = $_SESSION['INICIO']['USUARIO'];
		$parametros = array(
			array(&$USUARIO, SQLSRV_PARAM_IN)
		);
		$sql = "EXEC SP_ACTUALIZACION_ACTIVOS @USUARIO=?";
		$re = $this->db->ejecutar_procedimiento_con_retorno_1($sql, $parametros);
		return $re;
	}

	function log_activo($identificador = '')
	{
		if ($identificador != '') {
			$sql = "SELECT * FROM ac_log_activos WHERE identificador = '$identificador'; ";

			// print_r($sql);die();
			$datos = $this->db->datos($sql);
			return $datos;
		} else {
			return [];
		}
	}

	function log_activo_contador()
	{
		$sql = "SELECT COUNT(ac_cont_id) AS 'contador' FROM ac_log_activos_contador;";

		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos[0]['contador'];
	}

	function log_activo_contador_insert($identificador)
	{
		$sql = "INSERT INTO ac_log_activos_contador (ac_cont_nombre) VALUES ('$identificador');";
		// print_r($sql);die();
		$this->db->datos($sql);
	}


	/**
	 * @deprecated Funciones dadas de baja el 10/04/2025.
	 * @note Este archivo se mantiene como respaldo, pero ya no se utilizará en producción.
	 * @warning No modificar este archivo. Para cambios, referirse a la nueva implementación.
	 */

	function log_activo_anterior($fecha = false, $intento = false, $accion = False, $estado = false)
	{

		$sql = "SELECT * FROM ac_log_activos WHERE 1=1 ";
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

	/**
	 * Fin @deprecated
	 */
}
