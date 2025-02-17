<?php
include('../modelo/cargar_datos_saludM.php');
require_once('../db/codigos_globales.php');
date_default_timezone_set('America/Guayaquil');


/**
 * 
 */

$controlador = new cargar_datos_saludC();
if (isset($_GET['subir_archivo_server'])) {
	echo json_encode($controlador->subir_archivo_server($_FILES, $_POST['txt_opcion']));
}

if (isset($_GET['ejecutar_sp'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->ejecutar_sp($parametros));
	//echo json_encode($controlador->ejecutar_sp($parametros));
}

if (isset($_GET['log_activos'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->log_activos($parametros));
	//echo json_encode($controlador->ejecutar_sp($parametros));
}


class cargar_datos_saludC
{
	private $modelo;
	private $cod_global;

	function __construct()
	{
		$this->modelo = new cargar_datos_saludM();
		$this->cod_global = new codigos_globales();
	}


	function subir_archivo_server($file, $op)
	{
		if ($file['file']['type'] == 'text/csv') {
			$uploadfile_temporal = $file['file']['tmp_name'];
			$ruta = '../../TEMP/';
			//$tipo = explode('/', $file['file']['type']);
			$nombre = '';
			if ($op == 1) {
				$nombre = 'medicamentos.csv';
			} elseif ($op == 2) {
				$nombre = 'insumos.csv';
			}

			$nuevo_nom = $ruta . $nombre;
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
			$op =  $parametros['tip'] == 'false' ? 0 : 1;
			$resp = $this->modelo->ejecutar_medicamentos($op);
			return $resp;
		} else if ($parametros['id'] == 2) {
			$op =  $parametros['tip'] == 'false' ? 0 : 1;
			$resp = $this->modelo->ejecutar_insumos($op);
			return $resp;
		}
	}

	function log_activos($parametros)
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
}
