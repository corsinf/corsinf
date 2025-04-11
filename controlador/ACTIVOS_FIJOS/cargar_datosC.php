<?php

require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/cargar_datosM.php');
require_once(dirname(__DIR__, 2) . '/db/codigos_globales.php');

date_default_timezone_set('America/Guayaquil');

/**
 * 
 **/

$controlador = new cargar_datosC();

if (isset($_GET['subir_archivo_server'])) {
	echo json_encode($controlador->subir_archivo_server($_FILES, $_POST['txt_opcion']));
}

if (isset($_GET['ejecutar_sp'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->ejecutar_sp($parametros));
	//echo json_encode($controlador->ejecutar_sp($parametros));
}

if (isset($_GET['log_activos'])) {
	$identificador = $_GET['identificador'];
	echo json_encode($controlador->log_activos($identificador));
	//echo json_encode($controlador->ejecutar_sp($parametros));
}



class cargar_datosC
{
	private $modelo;
	private $cod_global;

	function __construct()
	{
		$this->modelo = new cargar_datosM();
		$this->cod_global = new codigos_globales();
	}

	function subir_archivo_server($file, $op)
	{
		if ($file['file']['type'] == 'text/csv') {
			$uploadfile_temporal = $file['file']['tmp_name'];
			// $ruta = 'C:/Apache24/htdocs/php81/corsinf/TEMPs/';
			// $ruta = 'Z:/htdocs/TEMP/'; //192.168.1.40
			//$ruta = '//192.168.1.5/Share/htdocs/TEMP/'; 
			$ruta = '//CORS001/Share/htdocs/TEMP/'; //192.168.1.40

			//print_r($ruta);exit();

			//if (!file_exists($ruta)) {
			//	mkdir($ruta, 0777, true);
			//}

			//$tipo = explode('/', $file['file']['type']);
			$nombre = '';
			if ($op == 1) {
				$nombre = 'datos.csv';
			} elseif ($op == 2) {
				$nombre = 'CUSTODIOS.csv';
			} elseif ($op == 3) {
				$nombre = 'EMPLAZAMIENTOS.csv';
			} elseif ($op == 4) {
				$nombre = 'MARCAS.csv';
			} elseif ($op == 5) {
				$nombre = 'ESTADOS.csv';
			} elseif ($op == 6) {
				$nombre = 'GENEROS.csv';
			} elseif ($op == 7) {
				$nombre = 'COLORES.csv';
			} elseif ($op == 8) {
				$nombre = 'PROYECTOS.csv';
			} elseif ($op == 9) {
				$nombre = 'CLASE_MOV.csv';
			} elseif ($op == 10) {
				$nombre = 'UPDATE_ACTIVOS.csv';
			}
			$nuevo_nom = $ruta . $nombre;
			//print_r($nuevo_nom); exit;

			if (is_uploaded_file($uploadfile_temporal)) {
				move_uploaded_file($uploadfile_temporal, $nuevo_nom);
				return 1;
			} else {
				return -1;
			}
		} else {
			return -2;
		}
	}

	function ejecutar_sp($parametros)
	{

		set_time_limit(0);
		if ($parametros['id'] == 1) {
			$resp = $this->modelo->ejecutar_activos();
			return $resp;
		} else if ($parametros['id'] == 2) {
			$op =  $parametros['tip'] == 'false' ? 0 : 1;
			$resp = $this->modelo->ejecutar_custodio($op);
			return $resp;
		} else if ($parametros['id'] == 3) {
			$op =  $parametros['tip'] == 'false' ? 0 : 1;
			$resp = $this->modelo->ejecutar_emplazamiento($op);
			return $resp;
		} else if ($parametros['id'] == 4) {
			$op =  $parametros['tip'] == 'false' ? 0 : 1;
			$resp = $this->modelo->ejecutar_marcas($op);
			return $resp;
		} else if ($parametros['id'] == 5) {
			$op =  $parametros['tip'] == 'false' ? 0 : 1;
			$resp = $this->modelo->ejecutar_estado($op);
			return $resp;
		} else if ($parametros['id'] == 6) {
			$op =  $parametros['tip'] == 'false' ? 0 : 1;
			$resp = $this->modelo->ejecutar_genero($op);
			return $resp;
		} else if ($parametros['id'] == 7) {
			$op =  $parametros['tip'] == 'false' ? 0 : 1;
			$resp = $this->modelo->ejecutar_colores($op);
			return $resp;
		} else if ($parametros['id'] == 8) {
			$op =  $parametros['tip'] == 'false' ? 0 : 1;
			$resp = $this->modelo->ejecutar_proyecto($op);
			return $resp;
		} else if ($parametros['id'] == 9) {
			$op =  $parametros['tip'] == 'false' ? 0 : 1;
			$resp = $this->modelo->ejecutar_clase_movimiento($op);
			return $resp;
		} else if ($parametros['id'] == 10) {
			$op =  $parametros['tip'] == 'false' ? 0 : 1;
			$resp = $this->modelo->ejecutar_update_activos($op);
			return $resp;
		}
	}

	function log_activos($identificador)
	{
		// print_r($parametros);die();
		$datos = $this->modelo->log_activo($identificador);
		return $datos;
	}

	/**
	 * @deprecated Funciones dadas de baja el 10/04/2025.
	 * @note Este archivo se mantiene como respaldo, pero ya no se utilizará en producción.
	 * @warning No modificar este archivo. Para cambios, referirse a la nueva implementación.
	 */

	function log_activos_anterior($parametros)
	{
		// print_r($parametros);die();
		$datos = $this->modelo->log_activo($parametros['fecha'], $parametros['intento'], $parametros['accion'], $parametros['estado']);
		$informe = '';
		foreach ($datos as $key => $value) {
			$informe .= '<tr>
				<td>' . $value['detalle'] . '</td>
				<td>' . $value['fecha'] . '</td>
				<td>' . $value['intento'] . '</td>
				<td>' . $value['accion'] . '</td>
				<td>' . $value['estado'] . '</td>
				<td>' . $value['usuario'] . '</td>
			</tr>';
		}

		return $informe;
	}

	/**
	 * Fin @deprecated
	 */
}
