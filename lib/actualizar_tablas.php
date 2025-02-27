<?php
session_start();
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;

include('../db/db.php');
include('../modelo/marcasM.php');
include('../modelo/estadoM.php');
include('../modelo/generoM.php');
include('../modelo/coloresM.php');
include('../modelo/proyectosM.php');
include('../modelo/localizacionM.php');
include('../modelo/custodioM.php');
include('../modelo/articulosM.php');

/**
 * 
 */
$controlador = new actualizar_tablas();
if (isset($_GET['plantilla'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->actualizar_datos($parametros));

	//echo json_encode($controlador->actualizacion());
}

if (isset($_GET['plantilla_masiv'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->ejecutar_sp($parametros));

	//echo json_encode($controlador->actualizacion());
}

class actualizar_tablas
{
	private $marcas;
	private $estado;
	private $genero;
	private $color;
	private $proyecto;
	private $localizacion;
	private $custodio;
	private $activos;
	private $db;

	function __construct()
	{
		$this->marcas = new marcasM();
		$this->estado = new estadoM();
		$this->genero = new generoM();
		$this->color = new coloresM();
		$this->proyecto = new proyectosM();
		$this->localizacion = new  localizacionM();
		$this->custodio = new  custodioM();
		$this->activos = new articulosM();
		$this->db = new db();
	}

	function actualizar_datos($parametros)
	{
		// print_r($parametros);die();
		$nombreArchivo = '';
		if ($parametros['id'] == 1) {
			$nombreArchivo = 'plantilla_masiva.csv';
		} elseif ($parametros['id'] == 2) {
			$nombreArchivo = 'colores_act.xlsx';
		} elseif ($parametros['id'] == 3) {
			$nombreArchivo = 'custodio_act.xlsx';
		} elseif ($parametros['id'] == 4) {
			$nombreArchivo = 'estado_act.xlsx';
		} elseif ($parametros['id'] == 5) {
			$nombreArchivo = 'genero_act.xlsx';
		} elseif ($parametros['id'] == 6) {
			$nombreArchivo = 'emplazamiento_act.xlsx';
		} elseif ($parametros['id'] == 7) {
			$nombreArchivo = 'marcas_act.xlsx';
		} elseif ($parametros['id'] == 8) {
			$nombreArchivo = 'proyecto_act.xlsx';
		}



		if ($parametros['parte_actual'] == 1) {
			if ($parametros['id'] == 1) {
				return $resp = $this->ejecutar_sp($parametros);
			} elseif ($parametros['id'] == 2) {
				if ($parametros['primera_vez'] == 'true') {
					$sql = "DELETE FROM ac_colores; DBCC CHECKIDENT (ac_colores, RESEED, 0);";
					$this->db->sql_string($sql);
				}
				if (isset($_SESSION['COLORES'])) {
					unset($_SESSION["COLORES"]);
				}
				$res =  $this->colores($datos, $parametros);
				return $res;
			} elseif ($parametros['id'] == 3) {
				if ($parametros['primera_vez'] == 'true') {
					$sql = "DELETE FROM th_personas; DBCC CHECKIDENT (th_personas, RESEED, 0);";
					$this->db->sql_string($sql);
				}
				if (isset($_SESSION['CUSTODIO'])) {
					unset($_SESSION["CUSTODIO"]);
				}
				$res =  $this->custodio($datos, $parametros);

				return $res;
			} elseif ($parametros['id'] == 4) {
				if ($parametros['primera_vez'] == 'true') {
					$sql = "DELETE FROM ac_estado; DBCC CHECKIDENT (ac_estado, RESEED, 0);";
					$this->db->sql_string($sql);
				}
				if (isset($_SESSION['ESTADOS'])) {
					unset($_SESSION["ESTADOS"]);
				}
				$res =  $this->estado($datos, $parametros);
				return $res;
			} elseif ($parametros['id'] == 5) {
				if ($parametros['primera_vez'] == 'true') {
					$sql = "DELETE FROM ac_genero; DBCC CHECKIDENT (ac_genero, RESEED, 0);";
					$this->db->sql_string($sql);
				}
				if (isset($_SESSION['GENEROS'])) {
					unset($_SESSION["GENEROS"]);
				}
				$res =  $this->genero($datos, $parametros);
				return $res;
			} elseif ($parametros['id'] == 6) {
				if ($parametros['primera_vez'] == 'true') {
					$sql = "DELETE FROM ac_localizacion; DBCC CHECKIDENT (ac_localizacion, RESEED, 0);";
					$this->db->sql_string($sql);
				}
				if (isset($_SESSION['EMPLAZAMIENTO'])) {
					unset($_SESSION["EMPLAZAMIENTO"]);
				}
				$res =  $this->emplazamiento($datos, $parametros);
				return $res;
			} elseif ($parametros['id'] == 7) {
				if ($parametros['primera_vez'] == 'true') {
					$sql = "DELETE FROM ac_marcas; DBCC CHECKIDENT (ac_marcas, RESEED, 0);";
					$this->db->sql_string($sql);
				}
				if (isset($_SESSION['MARCAS'])) {
					unset($_SESSION["MARCAS"]);
				}
				$res =  $this->marcas($datos, $parametros);
				return $res;
			} elseif ($parametros['id'] == 8) {
				if ($parametros['primera_vez'] == 'true') {
					$sql = "DELETE FROM ac_proyecto; DBCC CHECKIDENT (ac_proyecto, RESEED, 0);";
					$this->db->sql_string($sql);
				}
				if (isset($_SESSION['PROYECTOS'])) {
					unset($_SESSION["PROYECTOS"]);
				}
				$res =  $this->proyectos($datos, $parametros);
				return $res;
			}
		} else {
			// print_r($_SESSION['CUSTODIO']);
			// print_r($parametros);die();
			$datos = array('parte_actual' => $parametros['parte_actual'], 'partes' => $parametros['partes'], 'partes_de' => $parametros['partes_de'], 'TotalReg' => $parametros['total']);

			if ($parametros['id'] == 1) {
				$res =  $this->plantilla_masiva($datos, $parametros);
				return $res;
			} elseif ($parametros['id'] == 2) {
				$res =  $this->colores($datos, $parametros);
				return $res;
			} elseif ($parametros['id'] == 3) {
				$res =  $this->custodio($datos, $parametros);
				return $res;
			} elseif ($parametros['id'] == 4) {
				$res =  $this->estado($datos, $parametros);
				return $res;
			} elseif ($parametros['id'] == 5) {
				$res =  $this->genero($datos, $parametros);
				return $res;
			} elseif ($parametros['id'] == 6) {
				$res =  $this->emplazamiento($datos, $parametros);
				return $res;
			} elseif ($parametros['id'] == 7) {
				$res =  $this->marcas($datos, $parametros);
				return $res;
			} elseif ($parametros['id'] == 8) {
				$res =  $this->proyectos($datos, $parametros);
				return $res;
			}
		}
	}

	function ejecutar_sp($parametros)
	{
		// $conn = new db();
		// $primera = 1;
		// if($parametros['primera_vez']=='false')
		// {
		// 	$primera = 0;
		// }
		// $CodigoDeInv = '';
		//  $parametros = array(
		//       array(&$primera, SQLSRV_PARAM_IN),
		//      array(&$CodigoDeInv, SQLSRV_PARAM_INOUT)
		//   );
		//   // $sql2 = "EXEC SP_CARGA_ACTIVOS @INICIO=?,@ERRORES=? ";
		//   $sql = "EXEC SP_CARGAR_PLANTILLA @INICIO=?,@ERRORES=? ";
		//   $resp = $this->db->ejecutar_procesos_almacenados($sql,$parametros,$tipo=false);

		//   // $resp = $this->db->ejecutar_procesos_almacenados($sql2,$parametros,$tipo=false);
		//   if($resp==1)
		//   {
		//   	return $CodigoDeInv;
		//   }
		$resp = $this->db->D();
		return $resp;
	}

	function validar_cantidad($archivo)
	{
		$limite = 950; //20000;
		$doc = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
		$objPHPExcel = $doc->load($archivo);
		$objPHPExcel->setActiveSheetIndex(0);
		$numRows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
		$partes = 1;
		$ini = 1;
		$fin = $numRows;
		if ($numRows > $limite) {
			$partes = ($numRows / $limite);
			$fin = $limite;
		}
		if (is_float($partes)) {
			$partes = intval($partes) + 1;
		}
		return  array('parte_actual' => 1, 'partes' => $partes, 'TotalReg' => $numRows, 'inicio' => $ini, 'fin' => $fin, 'partes_de' => $limite);
	}

	function custodio($datos, $parametros)
	{

		ini_set('memory_limit', '-1');
		$respuesta = '';
		if (!isset($_SESSION['CUSTODIO'])) {
			$_SESSION['CUSTODIO'] = array();
			$nombreArchivo = '../TEMP/custodio_act.xlsx';
			$doc = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
			$objPHPExcel = 	$doc->load($nombreArchivo);
			$objPHPExcel->setActiveSheetIndex(0);
			for ($i = $datos['inicio'] + 1; $i <= $datos['TotalReg']; $i++) {
				$NO = $objPHPExcel->getActiveSheet()->getCell('A' . $i)->getCalculatedValue();
				$CI = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('B' . $i)->getCalculatedValue());
				$NOM = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('C' . $i)->getCalculatedValue());
				$PUE = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('D' . $i)->getCalculatedValue());
				$UNI = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('E' . $i)->getCalculatedValue());
				$COR = $objPHPExcel->getActiveSheet()->getCell('F' . $i)->getCalculatedValue();
				$_SESSION['CUSTODIO'][] = array('no' => $NO, 'cedula' => $CI, 'nombre' => $NOM, 'puesto' => $PUE, 'unidad' => $UNI, 'correo' => $COR);
			}
			$datos['TotalReg'] = count($_SESSION['CUSTODIO']) - 1; // se resta uno por que el array custodio comienza desde 0
		}

		// print_r($_SESSION['CUSTODIO']);die();
		// unset($_SESSION["CUSTODIO"]);


		$campos = "";
		$insert = '';

		$ini = $datos['partes_de'] * ($datos['parte_actual'] - 1);
		$fin = ($datos['parte_actual']) * $datos['partes_de'];
		if ($datos['parte_actual'] == $datos['partes']) {
			// print_r($ini.'-'.$fin);			
			// print_r($_SESSION['CUSTODIO']);
			// die();
			$fin = $datos['TotalReg'];
		}

		// print_r($datos);
		// print_r($ini.'-'.$fin);die();
		$insert = '';
		$j = 1;

		for ($i = $ini; $i <= $fin; $i++) {
			$existe = $this->custodio->buscar_custodio_($_SESSION['CUSTODIO'][$i]['no']);
			if (count($existe) > 0) {
				$campos .= "UPDATE th_personas SET PERSON_NOM='" . $_SESSION['CUSTODIO'][$i]['nombre'] . "',PERSON_CI='" . $_SESSION['CUSTODIO'][$i]['cedula'] . "',PERSON_CORREO='" . $_SESSION['CUSTODIO'][$i]['correo'] . "',PUESTO='" . $_SESSION['CUSTODIO'][$i]['puesto'] . "',UNIDAD_ORG='" . $_SESSION['CUSTODIO'][$i]['unidad'] . "',ESTADO='A' WHERE PERSON_NO ='" . $_SESSION['CUSTODIO'][$i]['no'] . "';";
				if ($j == 200) {
					$this->db->sql_string($campos);
					$campos = '';

					$j = 0;
				}
				$j += 1;
			} else {
				if ($insert == '') {
					$insert .= "INSERT INTO th_personas (PERSON_NO,PERSON_NOM,PERSON_CI,PERSON_CORREO,PUESTO,UNIDAD_ORG) VALUES ('" . $_SESSION['CUSTODIO'][$i]['no'] . "','" . $_SESSION['CUSTODIO'][$i]['nombre'] . "','" . $_SESSION['CUSTODIO'][$i]['cedula'] . "','" . $_SESSION['CUSTODIO'][$i]['correo'] . "','" . $_SESSION['CUSTODIO'][$i]['puesto'] . "','" . $_SESSION['CUSTODIO'][$i]['correo'] . "')";
				} else {
					$insert .= ",('" . $_SESSION['CUSTODIO'][$i]['no'] . "','" . $_SESSION['CUSTODIO'][$i]['nombre'] . "','" . $_SESSION['CUSTODIO'][$i]['cedula'] . "','" . $_SESSION['CUSTODIO'][$i]['correo'] . "','" . $_SESSION['CUSTODIO'][$i]['puesto'] . "','" . $_SESSION['CUSTODIO'][$i]['correo'] . "')";
				}
			}
		}

		if ($insert != '') {
			$campos = $campos . $insert;
		}

		$ret = $this->db->sql_string($campos);
		$res = array('parte_actual' => $datos['parte_actual'], 'partes' => $datos['partes'], 'TotalReg' => $datos['TotalReg'], 'respuesta' => $ret, 'observaciones' => $respuesta, 'partes_de' => $datos['partes_de']);
		return $res;
	}

	function proyectos($datos, $parametros)
	{
		ini_set('memory_limit', '-1');
		$respuesta = '';
		if (!isset($_SESSION['PROYECTOS'])) {
			$_SESSION['PROYECTOS'] = array();
			$nombreArchivo = '../TEMP/proyecto_act.xlsx';
			$doc = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
			$objPHPExcel = 	$doc->load($nombreArchivo);
			$objPHPExcel->setActiveSheetIndex(0);
			for ($i = $datos['inicio'] + 1; $i <= $datos['TotalReg']; $i++) {
				$codigo = $objPHPExcel->getActiveSheet()->getCell('A' . $i)->getCalculatedValue();
				if ($codigo != '') {
					$entidad = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('B' . $i)->getCalculatedValue());
					$denominacion = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('C' . $i)->getCalculatedValue());
					$descripcion = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('D' . $i)->getCalculatedValue());
					$f1 =  $objPHPExcel->getActiveSheet()->getCell('E' . $i)->getCalculatedValue();
					if ($f1 == "") {
						$f1 = 'NULL';
					} else {
						$f1 = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($f1);
						$f1 = "'" . $f1->format('Y-m-d') . "'";
					}

					$f2 =  $objPHPExcel->getActiveSheet()->getCell('F' . $i)->getCalculatedValue();
					if ($f2 == "") {
						$f2 = 'NULL';
					} else {
						$f2 = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($f2);
						$f2 = "'" . $f2->format('Y-m-d') . "'";
					}

					$f3 =  $objPHPExcel->getActiveSheet()->getCell('G' . $i)->getCalculatedValue();
					if ($f3 == "") {
						$f3 = 'NULL';
					} else {
						$f3 = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($f3);
						$f3 = "'" . $f3->format('Y-m-d') . "'";
					}


					$_SESSION['PROYECTOS'][] = array('codigo' => $codigo, 'entidad' => $entidad, 'deno' => $denominacion, 'desc' => $descripcion, 'f1' => $f1, 'f2' => $f2, 'f3' => $f3);
				}
			}
			$datos['TotalReg'] = count($_SESSION['PROYECTOS']) - 1; // se resta uno por que el array custodio comienza desde 0
		}

		// print_r($_SESSION['GENEROS']);die();
		// unset($_SESSION["CUSTODIO"]);
		$campos = "";
		$insert = '';

		$ini = $datos['partes_de'] * ($datos['parte_actual'] - 1);
		$fin = ($datos['parte_actual']) * $datos['partes_de'];
		if ($datos['parte_actual'] == $datos['partes']) {
			// print_r($ini.'-'.$fin);			
			// print_r($_SESSION['CUSTODIO']);
			// die();
			$fin = $datos['TotalReg'];
		}

		// print_r($datos);
		// print_r($ini.'-'.$fin);die();
		$insert = '';
		$j = 1;

		for ($i = $ini; $i <= $fin; $i++) {
			$existe = $this->proyecto->buscar_proyecto_programa($_SESSION['PROYECTOS'][$i]['codigo']);
			if (count($existe) > 0) {
				$campos .= "UPDATE ac_proyecto SET entidad_cp='" . $_SESSION['PROYECTOS'][$i]['entidad'] . "',denominacion='" . $_SESSION['PROYECTOS'][$i]['deno'] . "',descripcion='" . $_SESSION['PROYECTOS'][$i]['desc'] . "',validez_de=" . $_SESSION['PROYECTOS'][$i]['f1'] . ",validez_a=" . $_SESSION['PROYECTOS'][$i]['f2'] . ",expiracion=" . $_SESSION['PROYECTOS'][$i]['f3'] . ",ESTADO='A' WHERE programa_financiacion ='" . $_SESSION['PROYECTOS'][$i]['codigo'] . "';";
				if ($j == 200) {
					$this->db->sql_string($campos);
					$campos = '';
					$j = 0;
				}
				$j += 1;
			} else {
				if ($insert == '') {
					$insert .= "INSERT INTO ac_proyecto (programa_financiacion,entidad_cp,denominacion,descripcion,validez_de,validez_a,expiracion) VALUES ('" . $_SESSION['PROYECTOS'][$i]['codigo'] . "','" . $_SESSION['PROYECTOS'][$i]['entidad'] . "','" . $_SESSION['PROYECTOS'][$i]['deno'] . "','" . $_SESSION['PROYECTOS'][$i]['desc'] . "'," . $_SESSION['PROYECTOS'][$i]['f1'] . "," . $_SESSION['PROYECTOS'][$i]['f2'] . "," . $_SESSION['PROYECTOS'][$i]['f3'] . ")";
				} else {
					$insert .= ",('" . $_SESSION['PROYECTOS'][$i]['codigo'] . "','" . $_SESSION['PROYECTOS'][$i]['entidad'] . "','" . $_SESSION['PROYECTOS'][$i]['deno'] . "','" . $_SESSION['PROYECTOS'][$i]['desc'] . "'," . $_SESSION['PROYECTOS'][$i]['f1'] . "," . $_SESSION['PROYECTOS'][$i]['f2'] . "," . $_SESSION['PROYECTOS'][$i]['f3'] . ")";
				}
			}
		}

		if ($insert != '') {
			$campos = $campos . $insert;
		}

		// print_r($campos);die();	
		$ret = $this->db->sql_string($campos);
		$res = array('parte_actual' => $datos['parte_actual'], 'partes' => $datos['partes'], 'TotalReg' => $datos['TotalReg'], 'respuesta' => $ret, 'observaciones' => $respuesta, 'partes_de' => $datos['partes_de']);
		return $res;
	}

	function emplazamiento($datos, $parametros)
	{
		ini_set('memory_limit', '-1');
		$respuesta = '';
		if (!isset($_SESSION['EMPLAZAMIENTO'])) {
			$_SESSION['EMPLAZAMIENTO'] = array();
			$nombreArchivo = '../TEMP/emplazamiento_act.xlsx';
			$doc = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
			$objPHPExcel = 	$doc->load($nombreArchivo);
			$objPHPExcel->setActiveSheetIndex(0);
			for ($i = $datos['inicio'] + 1; $i <= $datos['TotalReg']; $i++) {
				$emplazamiento = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('B' . $i)->getCalculatedValue());
				if ($emplazamiento != '') {
					$centro = $objPHPExcel->getActiveSheet()->getCell('A' . $i)->getCalculatedValue();
					$denominacion = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('C' . $i)->getCalculatedValue());
					$fami = substr($emplazamiento, 0, 5);
					$subfami = substr($emplazamiento, 5);
					$_SESSION['EMPLAZAMIENTO'][] = array('centro' => $centro, 'empla' => $emplazamiento, 'deno' => $denominacion, 'fami' => $fami, 'sub' => $subfami);
				}
			}
			$datos['TotalReg'] = count($_SESSION['EMPLAZAMIENTO']) - 1; // se resta uno por que el array custodio comienza desde 0
		}

		// print_r($_SESSION['GENEROS']);die();
		// unset($_SESSION["CUSTODIO"]);
		$campos = "";
		$insert = '';

		$ini = $datos['partes_de'] * ($datos['parte_actual'] - 1);
		$fin = ($datos['parte_actual']) * $datos['partes_de'];
		if ($datos['parte_actual'] == $datos['partes']) {
			// print_r($ini.'-'.$fin);			
			// print_r($_SESSION['CUSTODIO']);
			// die();
			$fin = $datos['TotalReg'];
		}

		// print_r($datos);
		// print_r($ini.'-'.$fin);die();
		$insert = '';
		$j = 1;

		for ($i = $ini; $i <= $fin; $i++) {
			$existe = $this->localizacion->buscar_localizacion_codigo($_SESSION['EMPLAZAMIENTO'][$i]['empla']);
			if (count($existe) > 0) {
				$campos .= "UPDATE ac_localizacion SET CENTRO='" . $_SESSION['EMPLAZAMIENTO'][$i]['centro'] . "',DENOMINACION='" . $_SESSION['EMPLAZAMIENTO'][$i]['deno'] . "',FAMILIA='" . $_SESSION['EMPLAZAMIENTO'][$i]['fami'] . "',SUBFAMILIA='" . $_SESSION['EMPLAZAMIENTO'][$i]['sub'] . "',ESTADO = 'A' WHERE EMPLAZAMIENTO = '" . $_SESSION['EMPLAZAMIENTO'][$i]['empla'] . "';";
				if ($j == 200) {
					$this->db->sql_string($campos);
					$campos = '';

					$j = 0;
				}
				$j += 1;
			} else {
				if ($insert == '') {
					$insert .= " INSERT  INTO ac_localizacion (CENTRO,EMPLAZAMIENTO,DENOMINACION,FAMILIA,SUBFAMILIA) VALUES ('" . $_SESSION['EMPLAZAMIENTO'][$i]['centro'] . "','" . $_SESSION['EMPLAZAMIENTO'][$i]['empla'] . "','" . $_SESSION['EMPLAZAMIENTO'][$i]['deno'] . "','" . $_SESSION['EMPLAZAMIENTO'][$i]['fami'] . "','" . $_SESSION['EMPLAZAMIENTO'][$i]['sub'] . "')";
				} else {
					$insert .= ",('" . $_SESSION['EMPLAZAMIENTO'][$i]['centro'] . "','" . $_SESSION['EMPLAZAMIENTO'][$i]['empla'] . "','" . $_SESSION['EMPLAZAMIENTO'][$i]['deno'] . "','" . $_SESSION['EMPLAZAMIENTO'][$i]['fami'] . "','" . $_SESSION['EMPLAZAMIENTO'][$i]['sub'] . "')";
				}
			}
		}

		if ($insert != '') {
			$campos = $campos . $insert;
		}

		// print_r($campos);die();	
		$ret = $this->db->sql_string($campos);
		$res = array('parte_actual' => $datos['parte_actual'], 'partes' => $datos['partes'], 'TotalReg' => $datos['TotalReg'], 'respuesta' => $ret, 'observaciones' => $respuesta, 'partes_de' => $datos['partes_de']);
		return $res;
	}

	function genero($datos, $parametros)
	{
		ini_set('memory_limit', '-1');
		$respuesta = '';
		if (!isset($_SESSION['GENEROS'])) {
			$_SESSION['GENEROS'] = array();
			$nombreArchivo = '../TEMP/genero_act.xlsx';
			$doc = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
			$objPHPExcel = 	$doc->load($nombreArchivo);
			$objPHPExcel->setActiveSheetIndex(0);
			for ($i = $datos['inicio'] + 1; $i <= $datos['TotalReg']; $i++) {
				$codigo = $objPHPExcel->getActiveSheet()->getCell('A' . $i)->getCalculatedValue();
				if ($codigo != '') {
					$desc = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('B' . $i)->getCalculatedValue());
					$_SESSION['GENEROS'][] = array('Codigo' => $codigo, 'Descrip' => trim($desc));
				}
			}
			$datos['TotalReg'] = count($_SESSION['GENEROS']) - 1; // se resta uno por que el array custodio comienza desde 0
		}

		// print_r($_SESSION['GENEROS']);die();
		// unset($_SESSION["CUSTODIO"]);


		$campos = "";
		$insert = '';

		$ini = $datos['partes_de'] * ($datos['parte_actual'] - 1);
		$fin = ($datos['parte_actual']) * $datos['partes_de'];
		if ($datos['parte_actual'] == $datos['partes']) {
			// print_r($ini.'-'.$fin);			
			// print_r($_SESSION['CUSTODIO']);
			// die();
			$fin = $datos['TotalReg'];
		}

		// print_r($datos);
		// print_r($ini.'-'.$fin);die();
		$insert = '';
		$j = 1;

		for ($i = $ini; $i <= $fin; $i++) {
			$existe = $this->genero->buscar_genero_CODIGO($_SESSION['GENEROS'][$i]['Codigo']);
			if (count($existe) > 0) {
				$campos .= " UPDATE ac_genero SET ESTADO = 'A', DESCRIPCION = '" . $_SESSION['GENEROS'][$i]['Descrip'] . "' WHERE CODIGO = '" . $_SESSION['GENEROS'][$i]['Codigo'] . "'";
				if ($j == 200) {
					$this->db->sql_string($campos);
					$campos = '';

					$j = 0;
				}
				$j += 1;
			} else {
				if ($insert == '') {
					$insert .= " INSERT INTO ac_genero (CODIGO,DESCRIPCION)VALUES('" . $_SESSION['GENEROS'][$i]['Codigo'] . "','" . $_SESSION['GENEROS'][$i]['Descrip'] . "')";
				} else {
					$insert .= " ,('" . $_SESSION['GENEROS'][$i]['Codigo'] . "','" . $_SESSION['GENEROS'][$i]['Descrip'] . "')";
				}
			}
		}

		if ($insert != '') {
			$campos = $campos . $insert;
		}

		// if($datos['parte_actual']==2)
		// {
		// 	print_r($campos);die();
		// }
		// print_r($campos);die();	
		$ret = $this->db->sql_string($campos);
		$res = array('parte_actual' => $datos['parte_actual'], 'partes' => $datos['partes'], 'TotalReg' => $datos['TotalReg'], 'respuesta' => $ret, 'observaciones' => $respuesta, 'partes_de' => $datos['partes_de']);
		return $res;
	}

	function marcas($datos, $parametros)
	{
		ini_set('memory_limit', '-1');
		$respuesta = '';
		if (!isset($_SESSION['MARCAS'])) {
			$_SESSION['MARCAS'] = array();
			$nombreArchivo = '../TEMP/marcas_act.xlsx';
			$doc = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
			$objPHPExcel = 	$doc->load($nombreArchivo);
			$objPHPExcel->setActiveSheetIndex(0);
			for ($i = $datos['inicio'] + 1; $i <= $datos['TotalReg']; $i++) {
				$codigo = $objPHPExcel->getActiveSheet()->getCell('A' . $i)->getCalculatedValue();
				if ($codigo != '') {
					$desc = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('B' . $i)->getCalculatedValue());
					$_SESSION['MARCAS'][] = array('Codigo' => $codigo, 'Descrip' => trim($desc));
				}
			}
			$datos['TotalReg'] = count($_SESSION['MARCAS']) - 1; // se resta uno por que el array custodio comienza desde 0
		}

		// print_r($_SESSION['CUSTODIO']);die();
		// unset($_SESSION["CUSTODIO"]);


		$campos = "";
		$insert = '';

		$ini = $datos['partes_de'] * ($datos['parte_actual'] - 1);
		$fin = ($datos['parte_actual']) * $datos['partes_de'];
		if ($datos['parte_actual'] == $datos['partes']) {
			// print_r($ini.'-'.$fin);			
			// print_r($_SESSION['CUSTODIO']);
			// die();
			$fin = $datos['TotalReg'];
		}

		// print_r($datos);
		// print_r($ini.'-'.$fin);die();
		$insert = '';
		$j = 1;

		for ($i = $ini; $i <= $fin; $i++) {
			$existe = $this->marcas->buscar_marcas_codigo($_SESSION['MARCAS'][$i]['Codigo']);
			if (count($existe) > 0) {
				$campos .= " UPDATE ac_marcas SET ESTADO = 'A', DESCRIPCION = '" . $_SESSION['MARCAS'][$i]['Descrip'] . "' WHERE CODIGO = " . $_SESSION['MARCAS'][$i]['Codigo'] . ";";
				if ($j == 200) {
					$this->db->sql_string($campos);
					$campos = '';

					$j = 0;
				}
				$j += 1;
			} else {
				if ($insert == '') {
					$insert .= " INSERT INTO ac_marcas (CODIGO,DESCRIPCION)VALUES('" . $_SESSION['MARCAS'][$i]['Codigo'] . "','" . $_SESSION['MARCAS'][$i]['Descrip'] . "')";
				} else {
					$insert .= ",('" . $_SESSION['MARCAS'][$i]['Codigo'] . "','" . $_SESSION['MARCAS'][$i]['Descrip'] . "')";
				}
			}
		}

		if ($insert != '') {
			$campos = $campos . $insert;
		}

		// if($datos['parte_actual']==2)
		// {
		// 	print_r($campos);die();
		// }
		// print_r($campos);die();	
		$ret = $this->db->sql_string($campos);
		$res = array('parte_actual' => $datos['parte_actual'], 'partes' => $datos['partes'], 'TotalReg' => $datos['TotalReg'], 'respuesta' => $ret, 'observaciones' => $respuesta, 'partes_de' => $datos['partes_de']);
		return $res;
	}

	function colores($datos, $parametros)
	{

		ini_set('memory_limit', '-1');
		$respuesta = '';
		if (!isset($_SESSION['COLORES'])) {
			$_SESSION['COLORES'] = array();
			$respuesta = '';
			$nombreArchivo = '../TEMP/colores_act.xlsx';
			$doc = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
			$objPHPExcel = 	$doc->load($nombreArchivo);
			$objPHPExcel->setActiveSheetIndex(0);
			for ($i = $datos['inicio'] + 1; $i <= $datos['TotalReg']; $i++) {
				$codigo = $objPHPExcel->getActiveSheet()->getCell('A' . $i)->getCalculatedValue();
				if ($codigo != '') {
					$desc = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('B' . $i)->getCalculatedValue());
					$_SESSION['COLORES'][] = array('Codigo' => $codigo, 'Descrip' => $desc);
				}
			}
			$datos['TotalReg'] = count($_SESSION['COLORES']) - 1; // se resta uno por que el array custodio comienza desde 0
		}

		// print_r($_SESSION['CUSTODIO']);die();
		// unset($_SESSION["CUSTODIO"]);


		$campos = "";
		$insert = '';

		$ini = $datos['partes_de'] * ($datos['parte_actual'] - 1);
		$fin = ($datos['parte_actual']) * $datos['partes_de'];
		if ($datos['parte_actual'] == $datos['partes']) {
			// print_r($ini.'-'.$fin);			
			// print_r($_SESSION['CUSTODIO']);
			// die();
			$fin = $datos['TotalReg'];
		}

		// print_r($datos);
		// print_r($ini.'-'.$fin);die();
		$insert = '';

		for ($i = $ini; $i <= $fin; $i++) {
			$existe = $this->color->buscar_colores_codigo($_SESSION['COLORES'][$i]['Codigo']);
			if (count($existe) > 0) {
				$campos .= " UPDATE ac_colores SET ESTADO = 'A', DESCRIPCION = '" . $_SESSION['COLORES'][$i]['Descrip'] . "' WHERE CODIGO = '" . $_SESSION['COLORES'][$i]['Codigo'] . "';";
			} else {
				if ($insert == '') {
					$insert .= " INSERT INTO ac_colores (CODIGO,DESCRIPCION)VALUES('" . $_SESSION['COLORES'][$i]['Codigo'] . "','" . $_SESSION['COLORES'][$i]['Descrip'] . "')";
				} else {
					$insert .= ",('" . $_SESSION['COLORES'][$i]['Codigo'] . "','" . $_SESSION['COLORES'][$i]['Descrip'] . "')";
				}
			}
		}

		if ($insert != '') {
			$campos = $campos . $insert;
		}

		$ret = $this->db->sql_string($campos);
		$res = array('parte_actual' => $datos['parte_actual'], 'partes' => $datos['partes'], 'TotalReg' => $datos['TotalReg'], 'respuesta' => $ret, 'observaciones' => $respuesta, 'partes_de' => $datos['partes_de']);
		return $res;
	}

	function estado($datos, $parametros)
	{
		ini_set('memory_limit', '-1');
		$respuesta = '';
		if (!isset($_SESSION['ESTADOS'])) {
			$_SESSION['ESTADOS'] = array();
			$nombreArchivo = '../TEMP/estado_act.xlsx';
			$doc = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
			$objPHPExcel = 	$doc->load($nombreArchivo);
			$objPHPExcel->setActiveSheetIndex(0);
			for ($i = $datos['inicio'] + 1; $i <= $datos['TotalReg']; $i++) {
				$codigo = $objPHPExcel->getActiveSheet()->getCell('A' . $i)->getCalculatedValue();
				if ($codigo != '') {
					$desc = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('B' . $i)->getCalculatedValue());
					$_SESSION['ESTADOS'][] = array('Codigo' => $codigo, 'Descrip' => $desc);
				}
			}
			$datos['TotalReg'] = count($_SESSION['ESTADOS']) - 1; // se resta uno por que el array custodio comienza desde 0
		}

		// print_r($_SESSION['CUSTODIO']);die();
		// unset($_SESSION["CUSTODIO"]);


		$campos = "";
		$insert = '';

		$ini = $datos['partes_de'] * ($datos['parte_actual'] - 1);
		$fin = ($datos['parte_actual']) * $datos['partes_de'];
		if ($datos['parte_actual'] == $datos['partes']) {
			// print_r($ini.'-'.$fin);			
			// print_r($_SESSION['CUSTODIO']);
			// die();
			$fin = $datos['TotalReg'];
		}

		// print_r($datos);
		// print_r($ini.'-'.$fin);die();
		$insert = '';

		for ($i = $ini; $i <= $fin; $i++) {
			$existe = $this->estado->buscar_estado_CODIGO($_SESSION['ESTADOS'][$i]['Codigo']);
			if (count($existe) > 0) {
				$campos .= " UPDATE ac_estado SET ESTADO = 'A', DESCRIPCION = '" . $_SESSION['ESTADOS'][$i]['Descrip'] . "' WHERE CODIGO = '" . $_SESSION['ESTADOS'][$i]['Codigo'] . "';";
			} else {
				if ($insert == '') {
					$insert .= " INSERT INTO ac_estado (CODIGO,DESCRIPCION)VALUES('" . $_SESSION['ESTADOS'][$i]['Codigo'] . "','" . $_SESSION['ESTADOS'][$i]['Descrip'] . "')";
				} else {
					$insert .= ",('" . $_SESSION['ESTADOS'][$i]['Codigo'] . "','" . $_SESSION['ESTADOS'][$i]['Descrip'] . "')";
				}
			}
		}

		if ($insert != '') {
			$campos = $campos . $insert;
		}

		$ret = $this->db->sql_string($campos);
		$res = array('parte_actual' => $datos['parte_actual'], 'partes' => $datos['partes'], 'TotalReg' => $datos['TotalReg'], 'respuesta' => $ret, 'observaciones' => $respuesta, 'partes_de' => $datos['partes_de']);
		return $res;
	}

	function validar_datos($texto)
	{
		$buscar = array('á', 'é', 'í', 'ó', 'ú', 'ñ', 'Ñ', 'Á', 'É', 'Í', 'Ó', 'Ú', ',', ':', ';', "'", '"');
		$remplazar = array('a', 'e', 'i', 'o', 'u', 'n', 'N', 'A', 'E', 'I', 'O', 'U', '', '', '', '', '');
		$texto_new = str_replace($buscar, $remplazar, $texto);
		return $texto_new;
	}



	//---------------------------------------------------------funcion para carga de activos---------------------------------------



	function plantilla_masiva($datos, $parametros)
	{
		// print_r($datos);die();
		ini_set('memory_limit', '-1');
		$datos_localizacion = $this->localizacion->lista_localizacion_todo();
		$datos_custodio = $this->custodio->buscar_custodio_todo();
		$datos_eval1 = $this->marcas->lista_marcas_todo();
		$datos_eval2 = $this->estado->lista_estado_todo();
		$datos_eval3 = $this->genero->lista_genero_todo();
		$datos_eval4 = $this->color->lista_colores_todo();
		$datos_eval5 = $this->proyecto->lista_proyectos_todo();

		$respuesta = '';
		if (!isset($_SESSION['ACTIVOS'])) {
			$_SESSION['ACTIVOS'] = array();
			$nombreArchivo = '../TEMP/plantilla_masiva.xlsx';
			$doc = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
			$objPHPExcel = 	$doc->load($nombreArchivo);
			$objPHPExcel->setActiveSheetIndex(0);
			for ($i = $datos['inicio'] + 2; $i <= $datos['TotalReg']; $i++) {
				//TODOS LOS ACTIVOS SE TRABAJA POR SU ASSET
				$codigo = $objPHPExcel->getActiveSheet()->getCell('B' . $i)->getCalculatedValue();
				if ($codigo != '') {
					$compa = $objPHPExcel->getActiveSheet()->getCell('A' . $i)->getCalculatedValue();
					$asset = $objPHPExcel->getActiveSheet()->getCell('B' . $i)->getCalculatedValue();
					$subnu = $objPHPExcel->getActiveSheet()->getCell('C' . $i)->getCalculatedValue();
					$descr = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('D' . $i)->getCalculatedValue());
					$desc2 = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('E' . $i)->getCalculatedValue());
					$model = $objPHPExcel->getActiveSheet()->getCell('F' . $i)->getCalculatedValue();
					$serie = $objPHPExcel->getActiveSheet()->getCell('G' . $i)->getCalculatedValue();
					$rfid_ = $objPHPExcel->getActiveSheet()->getCell('H' . $i)->getCalculatedValue();
					//--------------------POSICION I ----------------
					$fe_In =  $objPHPExcel->getActiveSheet()->getCell('I' . $i)->getCalculatedValue();
					if ($fe_In == "") {
						$fe_In = 'NULL';
					} else {
						$fe_In = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($fe_In);
						$fe_In = "'" . $fe_In->format('Y-m-d') . "'";
					}
					//--------------------FIN POSICION I ----------------

					$canti = $objPHPExcel->getActiveSheet()->getCell('J' . $i)->getCalculatedValue();
					$ba_uo = $objPHPExcel->getActiveSheet()->getCell('K' . $i)->getCalculatedValue();

					//--------------------LOCALIZACION ----------------	
					$empla = $objPHPExcel->getActiveSheet()->getCell('M' . $i)->getCalculatedValue();
					if ($objPHPExcel->getActiveSheet()->getCell('L' . $i)->getCalculatedValue() == "") {
						$locat = "''";
					} else {
						$lo_vali = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('L' . $i)->getCalculatedValue());
						foreach ($datos_localizacion as $key => $value) {
							if ($value['EMPLAZAMIENTO'] == $lo_vali) {
								$locat = $value['ID_LOCATION'];
								break;
							} else {
								$locat = "''";
							}
						}
						if ($locat == "''") {
							$respuesta .= 'Localizacion no registrada en base: ' . $empla . ' en el articulo con asset:' . $asset . ' <br>';
						}
					}
					if ($locat == "''") {
						$respuesta .= 'Localizacion no registrada en base: ' . $empla . ' en el articulo con asset:' . $asset . ' <br>';
					}
					//--------------------FIN LOCALIZACION ----------------


					//-------------------CUSTODIO-------------------------
					$custo = $objPHPExcel->getActiveSheet()->getCell('O' . $i)->getCalculatedValue();
					if ($objPHPExcel->getActiveSheet()->getCell('N' . $i)->getCalculatedValue() == "") {
						$perso = "''";
					} else {
						$cus_val = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('N' . $i)->getCalculatedValue());
						foreach ($datos_custodio as $key => $value) {
							if ($value['PERSON_NO'] == $cus_val) {
								$perso = $value['ID_PERSON'];
								break;
							} else {
								$perso = "''";
							}
						}
						if ($perso == "''") {
							$respuesta .= 'Custodio no encontrado en base: ' . $custo . ' en el articulo:' . $asset . ' <br>';
						}
					}
					if ($perso == "''") {
						$respuesta .= 'Custodio no encontrado en base: ' . $custo . ' en el articulo:' . $asset . ' <br>';
					}

					//------------------ FIN CUSTODIO------------------

					//------------------------inicio marca---------------
					if ($objPHPExcel->getActiveSheet()->getCell('P' . $i)->getCalculatedValue() == "") {
						$eval1 = "''";
					} else {
						$eva1 = $objPHPExcel->getActiveSheet()->getCell('P' . $i)->getCalculatedValue();
						foreach ($datos_eval1 as $key => $value) {
							if ($value['CODIGO'] == $eva1) {
								$eval1 = $value['ID_MARCA'];
								break;
							} else {
								$eval1 = "''";
							}
						}
						if ($eval1 == "''") {
							$respuesta .= 'Marca con codigo:' . $eva1 . ' no registrado en base en el articulo:' . $asset . ' <br>';
						}
					}
					if ($eval1 == "''") {
						$respuesta .= 'Marca con codigo:' . $eval1 . ' no registrado en base en el articulo:' . $asset . ' <br>';
					}
					//-----------------------------------fin marca-----------------
					//--------------------------------inicio estado-----------------
					if ($objPHPExcel->getActiveSheet()->getCell('Q' . $i)->getCalculatedValue() == "") {
						$eval2 = "''";
					} else {
						$eva2 = $objPHPExcel->getActiveSheet()->getCell('Q' . $i)->getCalculatedValue();
						foreach ($datos_eval2 as $key => $value) {
							if (strtoupper($value['CODIGO']) == strtoupper($eva2)) {
								$eval2 = $value['ID_ESTADO'];
								break;
							} else {
								$eval2 = "''";
							}
						}
						if ($eval2 == "''") {
							$respuesta .= 'Estado con codigo: ' . $eva2 . ' no registrada en base en el articulo:' . $asset . ' <br>';
						}
					}
					if ($eval2 == "''") {
						$respuesta .= 'Estado con codigo: ' . $eval2 . ' no registrada en base en el articulo:' . $asset . ' <br>';
					}
					//-----------------------------fin de estado--------------------------------------

					//-----------------------------inicio de genero-----------------------------------
					if ($objPHPExcel->getActiveSheet()->getCell('R' . $i)->getCalculatedValue() == "") {
						$eval3 = "''";
					} else {
						$eva3 = $objPHPExcel->getActiveSheet()->getCell('R' . $i)->getCalculatedValue();
						foreach ($datos_eval3 as $key => $value) {
							if ($value['CODIGO'] == $eva3) {
								$eval3 = $value['ID_GENERO'];
								break;
							} else {
								$eval3 = "''";
							}
						}
						if ($eval3 == "''") {
							$respuesta .= 'Genero con codigo: ' . $eva3 . ' no registrada en base en el articulo:' . $asset . ' <br>';
						}
					}
					if ($eval3 == "''") {
						$respuesta .= 'Genero con codigo: ' . $eval3 . ' no registrada en base en el articulo:' . $asset . ' <br>';
					}
					//------------------------------fin de genero----------------------------------------

					//-------------------------------inicio de colores-----------------------------------
					if ($objPHPExcel->getActiveSheet()->getCell('S' . $i)->getCalculatedValue() == "") {
						$eval4 = "''";
					} else {
						$eva4 = $objPHPExcel->getActiveSheet()->getCell('S' . $i)->getCalculatedValue();
						foreach ($datos_eval4 as $key => $value) {
							if ($value['CODIGO'] == $eva4) {
								$eval4 = $value['ID_COLORES'];
								break;
							} else {
								$eval4 = "''";
							}
						}

						if ($eval4 == "''") {
							$respuesta .= 'Color con codigo: ' . $eva4 . ' no registrada en base en el articulo:' . $asset . ' <br>';
						}
					}
					if ($eval4 == "''") {
						$respuesta .= 'Color con codigo: ' . $eval4 . ' no registrada en base en el articulo:' . $asset . ' <br>';
					}
					//--------------------------------fin de colores------------------------------------

					//---------------------------------´inicio proyecto------------------------------- 
					if ($objPHPExcel->getActiveSheet()->getCell('T' . $i)->getCalculatedValue() == "") {
						$eval5 = "''";
					} else {
						$eva5 = $objPHPExcel->getActiveSheet()->getCell('T' . $i)->getCalculatedValue();
						foreach ($datos_eval5 as $key => $value) {
							if ($value['pro'] == $eva5) {
								$eval5 = $value['id'];
								break;
							} else {
								$eval5 = "''";
							}
						}

						if ($eval5 == "''") {
							$respuesta .= 'Proyecto no registrada en base: ' . $eva5 . ' en el articulo:' . $asset . ' <br>';
						}
					}
					if ($eval5 == "''") {
						$respuesta .= 'Proyecto no registrada en base: ' . $eval5 . ' en el articulo:' . $asset . ' <br>';
					}
					//--------------------------------fin proyecto -------------------------------------- 

					$ass_n = $objPHPExcel->getActiveSheet()->getCell('U' . $i)->getCalculatedValue();
					$org_a = $objPHPExcel->getActiveSheet()->getCell('V' . $i)->getCalculatedValue();
					$f_com = $objPHPExcel->getActiveSheet()->getCell('W' . $i)->getCalculatedValue();
					if ($f_com == "") {
						$f_com = 'NULL';
					} else {
						$f_com = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($f_com);
						//print_r($f_com->format('Y-m-d'));die();
						$f_com = "'" . $f_com->format('Y-m-d') . "'";
					}

					$org_v = $objPHPExcel->getActiveSheet()->getCell('X' . $i)->getCalculatedValue();
					$obse = $objPHPExcel->getActiveSheet()->getCell('Y' . $i)->getCalculatedValue();
					$bajas = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('Z' . $i)->getCalculatedValue());
					$carac = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('AA' . $i)->getCalculatedValue());
					$img = $objPHPExcel->getActiveSheet()->getCell('AB' . $i)->getCalculatedValue();
					$Act_por = $objPHPExcel->getActiveSheet()->getCell('AC' . $i)->getCalculatedValue();

					$f_baj =  $objPHPExcel->getActiveSheet()->getCell('AD' . $i)->getCalculatedValue();
					if ($f_baj == "") {
						$f_baj = NULL;
					} else {
						$f_baj = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($f_baj);
						// print_r($f_baj->format('Y-m-d'));
						$f_baj = $f_baj->format('Y-m-d');
					}



					$_SESSION['ACTIVOS'][] = array(
						'COMPANYCODE' => $compa,
						'ASSET' => $asset,
						'SUBNUMERO' => $subnu,
						'DESCRIPCION' => $descr,
						'DESCRIPCION2' => $desc2,
						'MODELO' => $model,
						'SERIE' => $serie,
						'RFID' => $rfid_,
						'FECHA' => $fe_In,
						'CANTIDAD' => $canti,
						'UNIDAD_MED' => $ba_uo,
						'ID_LOCATION' => $locat,
						'LOCATION' => $empla,
						'ID_CUSTODIO' => $perso,
						'CUSTODIO' => $custo,
						'MARCA' => $eval1,
						'ESTADO' => $eval2,
						'GENERO' => $eval3,
						'COLOR' => $eval4,
						'PROYECTO' => $eval5,
						'SUPRA_NUM' => $ass_n,
						'TAG_ANTIGUO' => $org_a,
						'FECHA_COMPRA' => $f_com,
						'VALOR' => $org_v,
						'OBSERVACION' => $obse,
						'BAJAS' => $bajas,
						'CARACTERISTICAS' => $carac,
						'IMG' => $img,
						'ACTUALIZADO_POR' => $Act_por,
						'FECHA_BAJA' => $f_baj
					);
				}
			}
			$datos['TotalReg'] = count($_SESSION['ACTIVOS']) - 1; // se resta uno por que el array custodio comienza desde 0
		}

		// print_r($_SESSION['ACTIVOS']);
		// print_r($respuesta);die();
		// unset($_SESSION["CUSTODIO"]);


		$campos = "";
		$campos2 = "";
		$insert = '';

		$ini = $datos['partes_de'] * ($datos['parte_actual'] - 1);
		$fin = ($datos['parte_actual']) * $datos['partes_de'];
		if ($datos['parte_actual'] == $datos['partes']) {
			// print_r($ini.'-'.$fin);			
			// print_r($_SESSION['CUSTODIO']);
			// die();
			$fin = $datos['TotalReg'];
		}

		// print_r($datos);
		// print_r($ini.'-'.$fin);die();
		$insert = '';

		$j = 1;
		for ($i = $ini; $i <= $fin; $i++) {
			$existe = $this->activos->buscar_acticulos_existente($_SESSION['ACTIVOS'][$i]['ASSET']);
			if (count($existe) > 0) {
				$campos2 .= "UPDATE ac_asset SET TAG_UNIQUE='" . $_SESSION['ACTIVOS'][$i]['RFID'] . "',TAG_ANT = '" . $_SESSION['ACTIVOS'][$i]['TAG_ANTIGUO'] . "' WHERE ID_ASSET = '" . $existe[0]['ID_ASSET'] . "';";
				$campos .= "UPDATE ac_articulos SET COMPANYCODE='" . $_SESSION['ACTIVOS'][$i]['COMPANYCODE'] . "',SUBNUMBER='" . $_SESSION['ACTIVOS'][$i]['SUBNUMERO'] . "',DESCRIPT='" . $_SESSION['ACTIVOS'][$i]['DESCRIPCION'] . "',DESCRIPT2='" . $_SESSION['ACTIVOS'][$i]['DESCRIPCION2'] . "',MODELO='" . $_SESSION['ACTIVOS'][$i]['MODELO'] . "',SERIE='" . $_SESSION['ACTIVOS'][$i]['SERIE'] . "',FECHA_INV_DATE=" . $_SESSION['ACTIVOS'][$i]['FECHA'] . ",QUANTITY='" . $_SESSION['ACTIVOS'][$i]['CANTIDAD'] . "',BASE_UOM='" . $_SESSION['ACTIVOS'][$i]['UNIDAD_MED'] . "',LOCATION=" . $_SESSION['ACTIVOS'][$i]['ID_LOCATION'] . ",PERSON_NO=" . $_SESSION['ACTIVOS'][$i]['ID_CUSTODIO'] . ",EVALGROUP1=" . $_SESSION['ACTIVOS'][$i]['MARCA'] . ",EVALGROUP2=" . $_SESSION['ACTIVOS'][$i]['ESTADO'] . ",EVALGROUP3=" . $_SESSION['ACTIVOS'][$i]['GENERO'] . ",EVALGROUP4=" . $_SESSION['ACTIVOS'][$i]['COLOR'] . ",EVALGROUP5=" . $_SESSION['ACTIVOS'][$i]['PROYECTO'] . ",ASSETSUPNO='" . $_SESSION['ACTIVOS'][$i]['SUPRA_NUM'] . "',ORIG_ASSET='" . $_SESSION['ACTIVOS'][$i]['TAG_ANTIGUO'] . "',ORIG_ACQ_YR=" . $_SESSION['ACTIVOS'][$i]['FECHA_COMPRA'] . ",ORIG_VALUE='" . $_SESSION['ACTIVOS'][$i]['VALOR'] . "',OBSERVACION='" . $_SESSION['ACTIVOS'][$i]['OBSERVACION'] . "',BAJAS='" . $_SESSION['ACTIVOS'][$i]['BAJAS'] . "',CARACTERISTICA='" . $_SESSION['ACTIVOS'][$i]['CARACTERISTICAS'] . "',IMAGEN='',ACTU_POR='" . $_SESSION['ACTIVOS'][$i]['ACTUALIZADO_POR'] . "',FECHA_BAJA='" . $_SESSION['ACTIVOS'][$i]['FECHA_BAJA'] . "' WHERE id_plantilla = '" . $existe[0]['id_plantilla'] . "'";
				// print_r($campos);die();
				if ($j == 200) {
					$this->db->sql_string($campos2);
					$this->db->sql_string($campos);
					$campos = '';
					$campos2 = '';
					$j = 0;
				}
				$j += 1;
			} else {
				$insert_asset = " INSERT INTO ac_asset (TAG_UNIQUE,TAG_ANT,TAG_SERIE)VALUES('" . $_SESSION['ACTIVOS'][$i]['RFID'] . "','" . $_SESSION['ACTIVOS'][$i]['TAG_ANTIGUO'] . "','" . $_SESSION['ACTIVOS'][$i]['ASSET'] . "');";
				$this->db->sql_string($insert_asset);
				if ($insert == '') {
					$ASSET = $this->activos->asset($_SESSION['ACTIVOS'][$i]['ASSET']);

					$insert .= "INSERT INTO ac_articulos (COMPANYCODE,ID_ASSET,SUBNUMBER,DESCRIPT,DESCRIPT2,MODELO,SERIE,FECHA_INV_DATE,QUANTITY,BASE_UOM,LOCATION,PERSON_NO,EVALGROUP1,EVALGROUP2,EVALGROUP3,EVALGROUP4,EVALGROUP5,ASSETSUPNO,ORIG_ASSET,ORIG_ACQ_YR,ORIG_VALUE,OBSERVACION,BAJAS,CARACTERISTICA,IMAGEN,ACTU_POR,FECHA_BAJA)VALUES(
		    		'" . $_SESSION['ACTIVOS'][$i]['COMPANYCODE'] . "','" . $ASSET[0]['ID_ASSET'] . "','" . $_SESSION['ACTIVOS'][$i]['SUBNUMERO'] . "','" . $_SESSION['ACTIVOS'][$i]['DESCRIPCION'] . "','" . $_SESSION['ACTIVOS'][$i]['DESCRIPCION2'] . "','" . $_SESSION['ACTIVOS'][$i]['MODELO'] . "','" . $_SESSION['ACTIVOS'][$i]['SERIE'] . "'," . $_SESSION['ACTIVOS'][$i]['FECHA'] . ",'" . $_SESSION['ACTIVOS'][$i]['CANTIDAD'] . "','" . $_SESSION['ACTIVOS'][$i]['UNIDAD_MED'] . "'," . $_SESSION['ACTIVOS'][$i]['ID_LOCATION'] . "," . $_SESSION['ACTIVOS'][$i]['ID_CUSTODIO'] . "," . $_SESSION['ACTIVOS'][$i]['MARCA'] . "," . $_SESSION['ACTIVOS'][$i]['ESTADO'] . "," . $_SESSION['ACTIVOS'][$i]['GENERO'] . "," . $_SESSION['ACTIVOS'][$i]['COLOR'] . "," . $_SESSION['ACTIVOS'][$i]['PROYECTO'] . ",'" . $_SESSION['ACTIVOS'][$i]['SUPRA_NUM'] . "','" . $_SESSION['ACTIVOS'][$i]['TAG_ANTIGUO'] . "'," . $_SESSION['ACTIVOS'][$i]['FECHA_COMPRA'] . ",'" . $_SESSION['ACTIVOS'][$i]['VALOR'] . "','" . $_SESSION['ACTIVOS'][$i]['OBSERVACION'] . "','" . $_SESSION['ACTIVOS'][$i]['BAJAS'] . "','" . $_SESSION['ACTIVOS'][$i]['CARACTERISTICAS'] . "','','" . $_SESSION['ACTIVOS'][$i]['ACTUALIZADO_POR'] . "','" . $_SESSION['ACTIVOS'][$i]['FECHA_BAJA'] . "')";
				} else {
					$ASSET = $this->activos->asset($_SESSION['ACTIVOS'][$i]['ASSET']);

					$insert .= ",(
		    		'" . $_SESSION['ACTIVOS'][$i]['COMPANYCODE'] . "','" . $ASSET[0]['ID_ASSET'] . "','" . $_SESSION['ACTIVOS'][$i]['SUBNUMERO'] . "','" . $_SESSION['ACTIVOS'][$i]['DESCRIPCION'] . "','" . $_SESSION['ACTIVOS'][$i]['DESCRIPCION2'] . "','" . $_SESSION['ACTIVOS'][$i]['MODELO'] . "','" . $_SESSION['ACTIVOS'][$i]['SERIE'] . "'," . $_SESSION['ACTIVOS'][$i]['FECHA'] . ",'" . $_SESSION['ACTIVOS'][$i]['CANTIDAD'] . "','" . $_SESSION['ACTIVOS'][$i]['UNIDAD_MED'] . "'," . $_SESSION['ACTIVOS'][$i]['ID_LOCATION'] . "," . $_SESSION['ACTIVOS'][$i]['ID_CUSTODIO'] . "," . $_SESSION['ACTIVOS'][$i]['MARCA'] . "," . $_SESSION['ACTIVOS'][$i]['ESTADO'] . "," . $_SESSION['ACTIVOS'][$i]['GENERO'] . "," . $_SESSION['ACTIVOS'][$i]['COLOR'] . "," . $_SESSION['ACTIVOS'][$i]['PROYECTO'] . ",'" . $_SESSION['ACTIVOS'][$i]['SUPRA_NUM'] . "','" . $_SESSION['ACTIVOS'][$i]['TAG_ANTIGUO'] . "'," . $_SESSION['ACTIVOS'][$i]['FECHA_COMPRA'] . ",'" . $_SESSION['ACTIVOS'][$i]['VALOR'] . "','" . $_SESSION['ACTIVOS'][$i]['OBSERVACION'] . "','" . $_SESSION['ACTIVOS'][$i]['BAJAS'] . "','" . $_SESSION['ACTIVOS'][$i]['CARACTERISTICAS'] . "','','" . $_SESSION['ACTIVOS'][$i]['ACTUALIZADO_POR'] . "','" . $_SESSION['ACTIVOS'][$i]['FECHA_BAJA'] . "')";
				}
			}
		}

		if ($insert != '') {
			$campos = $campos . $insert;
		}
		// print_r($campos);die();	
		$ret = $this->db->sql_string($campos2);
		$ret = $this->db->sql_string($campos);
		$res = array('parte_actual' => $datos['parte_actual'], 'partes' => $datos['partes'], 'TotalReg' => $datos['TotalReg'], 'respuesta' => $ret, 'observaciones' => $respuesta, 'partes_de' => $datos['partes_de']);
		return $res;
	}



	// -----------------------------------------------------------------------------------------------------------------------------


	function plantilla_masiva1($part, $partes, $totalReg, $ini, $fin)
	{
		ini_set('memory_limit', '-1');
		set_time_limit(2048);
		//ini_set('memory_limit', '44M');
		$datos_localizacion = $this->localizacion->lista_localizacion_todo();
		$datos_custodio = $this->custodio->buscar_custodio_todo();
		$datos_eval1 = $this->marcas->lista_marcas_todo();
		$datos_eval2 = $this->estado->lista_estado_todo();
		$datos_eval3 = $this->genero->lista_genero_todo();
		$datos_eval4 = $this->color->lista_colores_todo();
		$datos_eval5 = $this->proyecto->lista_proyectos_todo();

		$respuesta = '';
		$nombreArchivo = 'plantilla_masiva.xlsx';
		$doc = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
		$objPHPExcel = 	$doc->load($nombreArchivo);
		$objPHPExcel->setActiveSheetIndex(0);
		//$numRows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
		//$numRows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();

		// print_r($numRows);die();
		$campos = " INSERT INTO ac_articulos  (COMPANYCODE,ID_ASSET,SUBNUMBER,DESCRIPT,DESCRIPT2,MODELO,SERIE,FECHA_INV_DATE,COSTCENTER,RESP_CCTR,LOCATION,PERSON_NO,FUNDS_CTR_APC,PROFIT_CTR,EVALGROUP1,EVALGROUP2,EVALGROUP3,EVALGROUP4,EVALGROUP5,ASSETSUPNO,IMAGEN,RETIRADO,OBSERVACION,QUANTITY,BASE_UOM,ORIG_ASSET,ORIG_ACQ_YR,ORIG_VALUE,CARACTERISTICA) VALUES ";

		$campos2 = "INSERT INTO ac_asset (TAG_UNIQUE,TAG_SERIE,TAG_ANT) VALUES";
		$dato = '';
		$dato2 = '';
		$count = 0;
		$insert = '';
		$insert2 = '';
		for ($i = $ini; $i < $fin; $i++) {
			$count = $count + 1;

			$compa = $objPHPExcel->getActiveSheet()->getCell('A' . $i)->getCalculatedValue();
			$asset = $objPHPExcel->getActiveSheet()->getCell('B' . $i)->getCalculatedValue();
			$subnu = $objPHPExcel->getActiveSheet()->getCell('C' . $i)->getCalculatedValue();
			$descr = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('D' . $i)->getCalculatedValue());
			$desc2 = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('E' . $i)->getCalculatedValue());
			$model = $objPHPExcel->getActiveSheet()->getCell('F' . $i)->getCalculatedValue();
			$serie = $objPHPExcel->getActiveSheet()->getCell('G' . $i)->getCalculatedValue();
			$rfid_ = $objPHPExcel->getActiveSheet()->getCell('H' . $i)->getCalculatedValue();
			//$fe_In =  $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
			$fe_In =  $objPHPExcel->getActiveSheet()->getCell('I' . $i)->getCalculatedValue();
			//print_r($fe_In);die();
			if ($fe_In == "") {
				$fe_In = 'NULL';
			} else {
				$fe_In = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($fe_In);
				$fe_In = "'" . $fe_In->format('Y-m-d') . "'";
				//	print_r($fe_In);die();
			}
			//$fe_In= \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($fe_In);


			// $fe_In = $objPHPExcel->getActiveSheet()->getCell('I'.$i)->getCalculatedValue();
			$canti = $objPHPExcel->getActiveSheet()->getCell('J' . $i)->getCalculatedValue();
			$ba_uo = $objPHPExcel->getActiveSheet()->getCell('K' . $i)->getCalculatedValue();
			// $locat = $objPHPExcel->getActiveSheet()->getCell('L'.$i)->getCalculatedValue();
			$empla = $objPHPExcel->getActiveSheet()->getCell('M' . $i)->getCalculatedValue();

			$org_a = $objPHPExcel->getActiveSheet()->getCell('V' . $i)->getCalculatedValue();
			if ($objPHPExcel->getActiveSheet()->getCell('L' . $i)->getCalculatedValue() == "") {
				$locat = "''";
			} else {
				$lo_vali = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('L' . $i)->getCalculatedValue());
				foreach ($datos_localizacion as $key => $value) {
					if ($value['EMPLAZAMIENTO'] == $lo_vali) {
						$locat = $value['ID_LOCATION'];
						break;
					} else {
						$locat = "''";
					}
				}
				if ($locat == "''") {
					$respuesta .= 'Localizacion no registrada en base: ' . $empla . ' en el articulo:' . $org_a . ' /';
				}
			}

			// $perso = $objPHPExcel->getActiveSheet()->getCell('N'.$i)->getCalculatedValue();		 
			$custo = $objPHPExcel->getActiveSheet()->getCell('O' . $i)->getCalculatedValue();
			if ($objPHPExcel->getActiveSheet()->getCell('N' . $i)->getCalculatedValue() == "") {
				$perso = "''";
			} else {

				$cus_val = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('N' . $i)->getCalculatedValue());
				foreach ($datos_custodio as $key => $value) {
					if ($value['PERSON_NO'] == $cus_val) {
						$perso = $value['ID_PERSON'];
						break;
					} else {
						$perso = "''";
					}
				}
				if ($perso == "''") {
					$respuesta .= 'Custodio no encontrado en base: ' . $custo . ' en el articulo:' . $org_a . ' /';
				}
			}
			if ($objPHPExcel->getActiveSheet()->getCell('P' . $i)->getCalculatedValue() == "") {
				$eval1 = "''";
			} else {
				$eva1 = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('P' . $i)->getCalculatedValue());
				foreach ($datos_eval1 as $key => $value) {
					if ($value['DESCRIPCION'] == $eva1) {
						$eval1 = $value['ID_MARCA'];
						break;
					} else {
						$eval1 = "''";
					}
				}
				if ($eval1 == "''") {
					$respuesta .= 'Marca no registrada en base: ' . $eva1 . ' en el articulo:' . $org_a . ' /';
				}
			}

			if ($objPHPExcel->getActiveSheet()->getCell('Q' . $i)->getCalculatedValue() == "") {
				$eval2 = "''";
			} else {
				$eva2 = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('Q' . $i)->getCalculatedValue());
				//print_r($eva2);
				//print_r($datos_eval2);die();
				foreach ($datos_eval2 as $key => $value) {
					if (strnatcasecmp($value['DESCRIPCION'], $eva2) === 0) {
						$eval2 = $value['ID_ESTADO'];
						break;
					} else {
						$eval2 = "''";
					}
				}

				if ($eval2 == "''") {
					$respuesta .= 'Estado no registrada en base: ' . $eva2 . ' en el articulo:' . $org_a . ' /';
				}
			}

			if ($objPHPExcel->getActiveSheet()->getCell('R' . $i)->getCalculatedValue() == "") {
				$eval3 = "''";
			} else {

				$eva3 = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('R' . $i)->getCalculatedValue());
				foreach ($datos_eval3 as $key => $value) {
					if ($value['DESCRIPCION'] == $eva3) {
						$eval3 = $value['ID_GENERO'];
						break;
					} else {
						$eval3 = "''";
					}
				}

				if ($eval3 == "''") {
					$respuesta .= 'Genero no registrada en base: ' . $eva3 . 'en el articulo:' . $org_a . ' /';
				}
			}

			if ($objPHPExcel->getActiveSheet()->getCell('S' . $i)->getCalculatedValue() == "") {
				$eval4 = "''";
			} else {

				$eva4 = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('S' . $i)->getCalculatedValue());
				foreach ($datos_eval4 as $key => $value) {
					if ($value['DESCRIPCION'] == $eva4) {
						$eval4 = $value['ID_COLORES'];
						break;
					} else {
						$eval4 = "''";
					}
				}

				if ($eval4 == "''") {
					$respuesta .= 'Color no registrada en base: ' . $eva4 . 'en el articulo:' . $org_a . ' /';
				}
			}

			if ($objPHPExcel->getActiveSheet()->getCell('T' . $i)->getCalculatedValue() == "") {
				$eval5 = "''";
			} else {
				$eva5 = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('T' . $i)->getCalculatedValue());
				foreach ($datos_eval5 as $key => $value) {
					if ($value['pro'] == $eva5) {
						$eval5 = $value['id'];
						break;
					} else {
						$eval5 = "''";
					}
				}

				if ($eval5 == "''") {
					$respuesta .= 'Proyecto no registrada en base: ' . $eva5 . ' en el articulo:' . $org_a . ' /';
				}
			}
			// print_r($eval1);die();		
			$ass_n = $objPHPExcel->getActiveSheet()->getCell('U' . $i)->getCalculatedValue();

			//$f_com =  $objPHPExcel->getActiveSheet()->getStyle('W'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
			$f_com =  $objPHPExcel->getActiveSheet()->getCell('W' . $i)->getCalculatedValue();
			if ($f_com == "") {
				$f_com = 'NULL';
			} else {
				$f_com = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($f_com);
				//print_r($f_com->format('Y-m-d'));die();
				$f_com = "'" . $f_com->format('Y-m-d') . "'";
			}




			$org_v = $objPHPExcel->getActiveSheet()->getCell('X' . $i)->getCalculatedValue();
			$carac = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('Z' . $i)->getCalculatedValue());
			$img = $objPHPExcel->getActiveSheet()->getCell('AA' . $i)->getCalculatedValue();


			$dato .= "('" . $compa . "'," . ($i - 1) . ",'" . $subnu . "','" . $descr . "','" . $desc2 . "','" . $model . "','" . $serie . "'," . $fe_In . ",'',''," . $locat . "," . $perso . ",'',''," . $eval1 . "," . $eval2 . "," . $eval3 . "," . $eval4 . "," . $eval5 . ",'" . $ass_n . "','" . $img . "',0,''," . $canti . ",'" . $ba_uo . "','" . $org_a . "'," . $f_com . ",'" . $org_v . "','" . $carac . "'),";

			$dato2 .= "('" . $rfid_ . "','" . $asset . "','" . $org_a . "'),";
			if ($count == 1000) {
				$dato = substr($dato, 0, -1);
				$insert .= $campos . ' ' . $dato . ';';
				$dato2 = substr($dato2, 0, -1);
				$insert2 .= $campos2 . ' ' . $dato2 . ';';
				$count = 0;
				$dato = '';
				$dato2 = '';
			}
		}
		if ($dato != '') {
			$dato = substr($dato, 0, -1);
			$insert .= $campos . ' ' . $dato . ';';

			$dato2 = substr($dato2, 0, -1);
			$insert2 .= $campos2 . ' ' . $dato2 . ';';
		}

		if ($part == 1) {
			$sql = " DELETE FROM ac_articulos; DBCC CHECKIDENT (ac_articulos, RESEED, 0); " . $insert;
			$sql2 = " DELETE FROM ac_asset; DBCC CHECKIDENT (ac_asset, RESEED, 0); " . $insert2;

			//print_r($sql);die();
			$ret = $this->db->sql_string($sql);
			$ret2 = $this->db->sql_string($sql2);
			$ret = 1;
			$res = array('parte' => $part, 'partes' => $partes, 'TotalReg' => $totalReg, 'respuesta' => $ret, 'observaciones' => $respuesta, 'fin' => $fin);
			return $res;
		} else {
			//print_r($insert);die();
			$ret = $this->db->sql_string($insert);
			$ret2 = $this->db->sql_string($insert2);
			$res = array('parte' => $part, 'partes' => $partes, 'TotalReg' => $totalReg, 'respuesta' => $ret, 'observaciones' => $respuesta, 'fin' => $fin);
			return $res;
		}
	}



	// -----------------------------------------------------------------------------------------------------------------------------
	
}
