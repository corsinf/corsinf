<?php
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

$controlador = new carga_datos();
if (isset($_GET['plantilla'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->subir_datos($parametros));


	//echo json_encode($controlador->actualizacion());
}
if (isset($_GET['cargar_csv'])) {
	echo json_encode($controlador->cargar_csv_masivo());
}
/**
 * 
 */
class carga_datos
{
	private $marcas;
	private $estado;
	private $genero;
	private $color;
	private $proyecto;
	private $localizacion;
	private $custodio;
	private $db;
	private $articulo;

	function __construct()
	{
		$this->marcas = new marcasM();
		$this->estado = new estadoM();
		$this->genero = new generoM();
		$this->color = new coloresM();
		$this->proyecto = new proyectosM();
		$this->localizacion = new  localizacionM();
		$this->custodio = new  custodioM();
		$this->articulo = new articulosM();
		$this->db = new db();
	}

	function actualizacion()
	{


		$nombreArchivo = 'Actualiza.xlsx';
		$objPHPExcel = PHPEXCEL_IOFactory::load($nombreArchivo);
		$objPHPExcel->setActiveSheetIndex(0);
		$numRows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
		$sql = '';
		$sql2 = '';
		for ($j = 2; $j <= $numRows; $j++) {

			$asse = $objPHPExcel->getActiveSheet()->getCell('A' . $j)->getCalculatedValue();
			$orri = $objPHPExcel->getActiveSheet()->getCell('B' . $j)->getCalculatedValue();
			$sql .= "UPDATE ac_asset SET TAG_ANT = '" . $orri . "' WHERE TAG_SERIE ='" . $asse . "';";
			$id = $this->marcas->plantilla($asse);
			//print_r($id);die();
			$sql2 .= "UPDATE ac_articulos SET ORIG_ASSET = '" . $orri . "' WHERE ID_ASSET ='" . $id[0]['ID_ASSET'] . "';";
		}
		print_r($sql);
		print_r('-');
		print_r($sql2);
		die();
	}


	// function traer_de_FTP()
	// {
	// 	$ftp_server  ='186.4.219.172';
	// 	$ftp_user = 'efarinango';
	// 	$ftp_pass = 'EF1722214507*';
	// 	$ftp_port = '31';
	// 	// open an FTP connection

	// 	$connection = ssh2_connect($ftp_server, 31) or die("Couldn't connect to $ftp_server");;
	// 	if(ssh2_auth_password($connection, $ftp_user,$ftp_pass))
	// 	{
	// 		$sftp = ssh2_sftp($connection);
	// 		$rd = "ssh2.sftp://{$sftp}/PUCE/";
	// 		copy($rd.'/Prueba1.csv', '1Prueba.csv');

	// 	}else
	// 	{
	// 		echo "no se pudo iniciar sesion";
	// 	}
	// }


	// function eviar_por_FTP()
	// {
	// 	$ftp_server  ='186.4.219.172';
	// 	$ftp_user = 'efarinango';
	// 	$ftp_pass = 'EF1722214507*';
	// 	$ftp_port = '31';
	// 	// open an FTP connection

	// 	$connection = ssh2_connect($ftp_server, 31) or die("Couldn't connect to $ftp_server");;
	// 	if(ssh2_auth_password($connection, $ftp_user,$ftp_pass))
	// 	{
	// 		$sftp = ssh2_sftp($connection);
	// 		$rd = "ssh2.sftp://{$sftp}/PUCE/";
	// 		copy($rd.'/Prueba1.csv', '1Prueba.csv');

	// 	}else
	// 	{
	// 		echo "no se pudo iniciar sesion";
	// 	}
	// }



	function buscar_all_archivo_FTP()
	{
		$ftp_server  = '186.4.219.172';
		$ftp_user = 'efarinango';
		$ftp_pass = 'EF1722214507*';
		$ftp_port = '31';
		$to = '../descargas/de_sap';
		if (!is_dir($to)) {
			mkdir($to, 7777);
		}
		// open an FTP connection

		$connection = ssh2_connect($ftp_server, 31) or die("Couldn't connect to $ftp_server");;
		if (ssh2_auth_password($connection, $ftp_user, $ftp_pass)) {
			$sftp = ssh2_sftp($connection);
			$rd = "ssh2.sftp://{$sftp}/PUCE/";
			$handle = opendir($rd);
			if (!is_resource($handle)) {
				throw new SFTPException("Could not open directory.");
			}
			while (($file = readdir($handle)) !== false) {
				//Leo todos los archivos excepto . y ..
				if (strpos($file, '.') !== 0) {
					// Copio el archivo manteniendo el mismo nombre en la nueva carpeta
					copy($rd . $file, $to . '/1' . $file);
				}
			}
			closedir($handle);
		} else {
			echo "no se pudo iniciar sesion";
		}
	}

	function enviar_all_archivo_FTP()
	{
		$ftp_server  = '186.4.219.172';
		$ftp_user = 'efarinango';
		$ftp_pass = 'EF1722214507*';
		$ftp_port = '31';
		$to = '../descargas/para_sap';
		if (!is_dir($to)) {
			mkdir($to, 7777);
		}
		// open an FTP connection

		$connection = ssh2_connect($ftp_server, 31) or die("Couldn't connect to $ftp_server");;
		if (ssh2_auth_password($connection, $ftp_user, $ftp_pass)) {
			$sftp = ssh2_sftp($connection);
			$rd = "ssh2.sftp://{$sftp}/PUCE/";
			$handle = opendir($to);
			if (!is_resource($handle)) {
				throw new SFTPException("Could not open directory.");
			}
			while (($file = readdir($handle)) !== false) {
				//Leo todos los archivos excepto . y ..
				if (strpos($file, '.') !== 0) {
					// Copio el archivo manteniendo el mismo nombre en la nueva carpeta
					copy($to . '/' . $file, $rd . '1' . $file);
				}
			}
			closedir($handle);
		} else {
			echo "no se pudo iniciar sesion";
		}
	}

	function cargar_csv_masivo()
	{
		// $this->buscar_all_archivo_FTP();
		// $this->enviar_all_archivo_FTP();
		// $this->traer_de_FTP();
		// return false;

		ini_set('memory_limit', '-1');
		$fi = 'Prueba1.csv';
		$file = fopen($fi, "r");
		$data = array();

		$datos_localizacion = $this->localizacion->lista_localizacion_todo();
		$datos_custodio = $this->custodio->buscar_custodio_todo();
		$datos_eval1 = $this->marcas->lista_marcas_todo();
		$datos_eval2 = $this->estado->lista_estado_todo();
		$datos_eval3 = $this->genero->lista_genero_todo();
		$datos_eval4 = $this->color->lista_colores_todo();
		$datos_eval5 = $this->proyecto->lista_proyectos_todo();

		$sql = 'DELETE FROM ac_asset;DELETE FROM ac_articulos;DBCC CHECKIDENT (ac_asset, RESEED, 0);DBCC CHECKIDENT (ac_articulos, RESEED, 0);';
		$this->db->sql_string($sql);


		$campos = " INSERT INTO ac_articulos  (COMPANYCODE,ID_ASSET,SUBNUMBER,DESCRIPT,DESCRIPT2,MODELO,SERIE,FECHA_INV_DATE,COSTCENTER,RESP_CCTR,LOCATION,PERSON_NO,FUNDS_CTR_APC,PROFIT_CTR,EVALGROUP1,EVALGROUP2,EVALGROUP3,EVALGROUP4,EVALGROUP5,ASSETSUPNO,IMAGEN,RETIRADO,OBSERVACION,QUANTITY,BASE_UOM,ORIG_ASSET,ORIG_ACQ_YR,ORIG_VALUE,CARACTERISTICA) VALUES ";
		$campos2 = "INSERT INTO ac_asset (TAG_UNIQUE,TAG_SERIE,TAG_ANT) VALUES";

		$NUM_REG = 1;
		$respuesta = '';
		$valores = '';
		$valores2 = '';
		$sql = '';
		$sql2 = '';
		while (!feof($file)) {
			$data = fgetcsv($file, null, ';');

			$BUKRS = $data[0];
			if ($BUKRS == '') {
				break;
			}
			$ANLN1 = $data[1];
			$ASSET = $this->articulo->asset($ANLN1);

			$ANLN2 = $data[2];
			$TXT50 = "'" . $data[3] . "'";
			$TXA50 = "'" . $data[4] . "'";
			$ANLHTXT = "'" . $data[5] . "'";
			$SERNR = "'" . $data[6] . "'";
			$INVENR = "'" . $data[7] . "'";
			$IVDAT =  date('Y-m-d', strtotime(str_replace('/', '-', $data[8]))); //fecha
			$MENGE = "'" . $data[9] . "'";
			$MEINS = "'" . $data[10] . "'";
			$STORT = $data[11];
			if ($STORT != "") {
				foreach ($datos_localizacion as $key => $value) {
					if ($value['EMPLAZAMIENTO'] == $STORT) {
						$STORT = $value['ID_LOCATION'];
						break;
					}
				}
			} else {
				$STORT = "''";
				$respuesta .= 'Custodio no encontrado en base: ' . $STORT . ' en el articulo con asset:' . $ANLN1 . ' /';
			}
			$KTEXT = $data[12];
			$PERNR =  $data[13];
			if ($PERNR != "") {
				foreach ($datos_custodio as $key => $value) {
					if ($value['PERSON_NO'] == $PERNR) {
						$PERNR = $value['ID_PERSON'];
						break;
					} else {
						$PERNR = "''";
					}
				}
			} else {
				$PERNR = "''";
				$respuesta .= 'Custodio no encontrado en base: ' . $PERNR . ' en el articulo con asset:' . $ANLN1 . ' /';
			}

			$PERNR_TXT = $data[14];
			$ORD41 = $data[15];
			if ($ORD41 != "") {
				foreach ($datos_eval1 as $key => $value) {
					if (strtoupper($value['DESCRIPCION']) == strtoupper($ORD41)) {
						$ORD41 = $value['ID_MARCA'];
						break;
					}
				}
				if ($ORD41 == '') {
					$ORD41 = "''";
				}
			} else {
				$ORD41 = "''";
				$respuesta .= 'Marca no registrada en base: ' . $ORD41 . ' en el articulo:' . $ANLN1 . ' /';
			}

			$ORD42 = $data[16];
			if ($ORD42 != "") {
				foreach ($datos_eval2 as $key => $value) {
					if (strtoupper($value['CODIGO']) == strtoupper($ORD42)) {
						$ORD42 = $value['ID_ESTADO'];
						break;
					}
				}
				if ($ORD42 == '' || !is_numeric($ORD42)) {
					$ORD42 = "''";
				}
			} else {
				$ORD42 = "''";
				$respuesta .= 'Estado no registrada en base: ' . $ORD42 . ' en el articulo:' . $ANLN1 . ' /';
			}

			$ORD43 = $data[17];
			if ($ORD43 != "") {
				foreach ($datos_eval3 as $key => $value) {
					if (strtoupper($value['DESCRIPCION']) == strtoupper($ORD43)) {
						$ORD43 = $value['ID_GENERO'];
						break;
					}
				}
				if ($ORD43 == '') {
					$ORD43 = "''";
				}
			} else {
				$ORD43 = "''";
				$respuesta .= 'Genero no registrada en base: ' . $ORD43 . 'en el articulo:' . $ANLN1 . ' /';
			}

			$ORD44 = $data[18];
			if ($ORD44 != "") {
				foreach ($datos_eval4 as $key => $value) {
					if (strtoupper(trim($value['DESCRIPCION'])) == strtoupper($ORD44)) {
						$ORD44 = $value['ID_COLORES'];
						break;
					}
				}
				if ($ORD44 == '') {
					$ORD44 = "''";
				}
			} else {
				$ORD44 = "''";
				$respuesta .= 'Color no registrada en base: ' . $ORD44 . 'en el articulo:' . $ANLN1 . ' /';
			}


			$GDLGRP = $data[19];
			if ($GDLGRP != "") {
				$temp = false;
				foreach ($datos_eval5 as $key => $value) {
					if (strtoupper($value['pro']) == strtoupper($GDLGRP)) {
						$temp = false;
						$GDLGRP = $value['id'];
						break;
					} else {
						$temp = true;
					}
				}
				if ($GDLGRP == '' || $temp == true) {
					$GDLGRP = "''";
				}
			} else {
				$GDLGRP = "''";
				$respuesta .= 'Proyecto no registrada en base: ' . $GDLGRP . ' en el articulo:' . $ANLN1 . ' /';
			}
			// // print_r($eval1);die();		
			$ANLUE = "'" . $data[20] . "'";

			$AIBN1 = "'" . $data[21] . "'";

			$AKTIV  = date('Y-m-d', strtotime(str_replace('/', '-', $data[22]))); //fecha

			$URWRT = "'" . $data[23] . "'";
			$OBSER = "'" . $data[24] . "'";
			$NOTE1 = "'" . $data[25] . "'";
			$FOTO = "'" . $data[26] . "'";
			$valores .= "(" . $BUKRS . "," . $NUM_REG . "," . $ANLN2 . "," . $TXT50 . "," . $TXA50 . "," . $ANLHTXT . "," . $SERNR . ",'" . $IVDAT . "','COSTCENTER','RESP_CCTR'," . $STORT . "," . $PERNR . ",'FUNDS_CTR_APC','PROFIT_CTR'," . $ORD41 . "," . $ORD42 . "," . $ORD43 . "," . $ORD44 . "," . $GDLGRP . "," . $ANLUE . "," . $FOTO . ",0," . $OBSER . "," . $MENGE . "," . $MEINS . "," . $AIBN1 . ",'" . $AKTIV . "'," . $URWRT . "," . $NOTE1 . "),";

			$valores2 .= "(" . $INVENR . ",'" . $ANLN1 . "'," . $AIBN1 . "),";


			$NUM_REG += 1;
		}

		$sql2 = $campos2 . substr($valores2, 0, -1);
		$sql = $campos . substr($valores, 0, -1);

		$this->db->sql_string($sql2);
		$this->db->sql_string($sql);

		// print_r($sql2);die();

		fclose($file);


		// print_r('sdasd');die();
	}

	function subir_datos($parametros)
	{


		// print_r($parametros);die();
		ini_set('memory_limit', '-1');
		set_time_limit(2048);

		if ($parametros['parte'] == 1) {
			$nombreArchivo = '';
			if ($parametros['id'] == 1) {
				$nombreArchivo = 'plantilla_masiva.csv';
			} elseif ($parametros['id'] == 2) {
				$nombreArchivo = 'colores.xlsx';
			} elseif ($parametros['id'] == 3) {
				$nombreArchivo = 'custodio.xlsx';
			} elseif ($parametros['id'] == 4) {
				$nombreArchivo = 'estado.xlsx';
			} elseif ($parametros['id'] == 5) {
				$nombreArchivo = 'genero.xlsx';
			} elseif ($parametros['id'] == 6) {
				$nombreArchivo = 'emplazamiento.xlsx';
			} elseif ($parametros['id'] == 7) {
				$nombreArchivo = 'marcas.xlsx';
			} elseif ($parametros['id'] == 8) {
				$nombreArchivo = 'proyecto.xlsx';
			}

			// $nombreArchivo = 'ejemplo.xlsx';
			$doc = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
			$objPHPExcel = $doc->load($nombreArchivo);
			$objPHPExcel->setActiveSheetIndex(0);
			$numRows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
			$partes = 0;
			$ini = 0;
			$fin = 0;
			if ($numRows > 20000) {
				$partes = ($numRows / 20000);
			} else {
				$partes = 1;
			}
			if (is_float($partes)) {
				$partes = intval($partes) + 1;
				$ini = 2;
				$fin = 20002;
			} else {
				$ini = 2;
				$fin = $numRows;
			}
			if ($parametros['id'] == 1) {
				$res =  $this->plantilla_masiva($parametros['parte'], $partes, $numRows, $ini, $fin);
				return $res;
			} elseif ($parametros['id'] == 2) {
				$res =  $this->colores($parametros['parte'], $partes, $numRows, $ini, $fin + 1);
				return $res;
			} elseif ($parametros['id'] == 3) {
				$res =  $this->custodio($parametros['parte'], $partes, $numRows, $ini, $fin + 1);
				return $res;
			} elseif ($parametros['id'] == 4) {
				$res =  $this->estado($parametros['parte'], $partes, $numRows, $ini, $fin + 1);
				return $res;
			} elseif ($parametros['id'] == 5) {
				$res =  $this->genero($parametros['parte'], $partes, $numRows, $ini, $fin + 1);
				return $res;
			} elseif ($parametros['id'] == 6) {
				$res =  $this->emplazamiento($parametros['parte'], $partes, $numRows, $ini, $fin + 1);
				return $res;
			} elseif ($parametros['id'] == 7) {
				$res =  $this->marcas($parametros['parte'], $partes, $numRows, $ini, $fin + 1);
				return $res;
			} elseif ($parametros['id'] == 8) {
				$res =  $this->proyectos($parametros['parte'], $partes, $numRows, $ini, $fin + 1);
				return $res;
			}
		} else {
			if ($parametros['parte'] == $parametros['partes']) {
				$ini = $parametros['fin'];
				$fin = $parametros['regis'];
				if ($parametros['id'] == 1) {
					$res =  $this->plantilla_masiva($parametros['parte'], $parametros['partes'], $parametros['regis'], $ini, $fin + 1);
					return $res;
				} elseif ($parametros['id'] == 2) {
					$res =  $this->colores($parametros['parte'], $parametros['partes'], $parametros['regis'], $ini, $fin + 1);
					return $res;
				} elseif ($parametros['id'] == 3) {
					$res =  $this->custodio($parametros['parte'], $parametros['partes'], $parametros['regis'], $ini, $fin + 1);
					return $res;
				} elseif ($parametros['id'] == 4) {
					$res =  $this->estado($parametros['parte'], $parametros['partes'], $parametros['regis'], $ini, $fin + 1);
					return $res;
				} elseif ($parametros['id'] == 5) {
					$res =  $this->genero($parametros['parte'], $parametros['partes'], $parametros['regis'], $ini, $fin + 1);
					return $res;
				} elseif ($parametros['id'] == 6) {
					$res =  $this->emplazamiento($parametros['parte'], $parametros['partes'], $parametros['regis'], $ini, $fin + 1);
					return $res;
				} elseif ($parametros['id'] == 7) {
					$res =  $this->marcas($parametros['parte'], $parametros['partes'], $parametros['regis'], $ini, $fin + 1);
					return $res;
				} elseif ($parametros['id'] == 8) {
					$res =  $this->proyectos($parametros['parte'], $parametros['partes'], $parametros['regis'], $ini, $fin + 1);
					return $res;
				}
			} else {
				$ini = intval($parametros['fin']);
				$fin = intval($parametros['fin']) + 20000;
				if ($parametros['id'] == 1) {
					$res =  $this->plantilla_masiva($parametros['parte'], $parametros['partes'], $parametros['regis'], $ini, $fin);
					return $res;
				} elseif ($parametros['id'] == 2) {
					$res =  $this->colores($parametros['parte'], $parametros['partes'], $parametros['regis'], $ini, $fin);
					return $res;
				} elseif ($parametros['id'] == 3) {
					$res =  $this->custodio($parametros['parte'], $partes, $numRows, $ini, $fin);
					return $res;
				} elseif ($parametros['id'] == 4) {
					$res =  $this->estado($parametros['parte'], $partes, $numRows, $ini, $fin);
					return $res;
				} elseif ($parametros['id'] == 5) {
					$res =  $this->genero($parametros['parte'], $partes, $numRows, $ini, $fin);
					return $res;
				} elseif ($parametros['id'] == 6) {
					$res =  $this->emplazamiento($parametros['parte'], $partes, $numRows, $ini, $fin);
					return $res;
				} elseif ($parametros['id'] == 7) {
					$res =  $this->marcas($parametros['parte'], $partes, $numRows, $ini, $fin);
					return $res;
				} elseif ($parametros['id'] == 8) {
					$res =  $this->proyectos($parametros['parte'], $partes, $numRows, $ini, $fin);
					return $res;
				}
			}
		}
	}

	// function actualizar_plantilla_masiva($part,$partes,$totalReg,$ini,$fin)
	// {

	// ini_set('memory_limit', '-1');	
	// $datos_localizacion = $this->localizacion->lista_localizacion_todo();
	// $datos_custodio = $this->custodio->buscar_custodio_todo();
	// $datos_eval1 = $this->marcas->lista_marcas_todo();
	// $datos_eval2 = $this->estado->lista_estado_todo();
	// $datos_eval3 = $this->genero->lista_genero_todo();
	// $datos_eval4 = $this->color->lista_colores_todo();
	// $datos_eval5 = $this->proyecto->lista_proyectos_todo();


	// $respuesta = '';
	// $nombreArchivo = 'plantilla_masiva.xlsx';	
	// $doc = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
	// $objPHPExcel = 	$doc->load($nombreArchivo);
	// $objPHPExcel->setActiveSheetIndex(0);


	// $dato = array();
	// $datos2 = array();
	// $count = 0;
	// $insert ='';

	// for($i = $ini; $i <= $fin; $i++)
	// {
	//     $BUKRS = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
	// 	$ANLN1 = $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue();
	// 	$ASSET = $this->articulo->asset($ANLN1);
	// 	//se validaras todo por el asset 
	// 	if($ASSET!=-1)
	// 	{
	// 	$ANLN2 = $objPHPExcel->getActiveSheet()->getCell('C'.$i)->getCalculatedValue();
	// 	$TXT50 = "'".$this->validar_datos($objPHPExcel->getActiveSheet()->getCell('D'.$i)->getCalculatedValue())."'";
	// 	$TXA50 = "'".$this->validar_datos($objPHPExcel->getActiveSheet()->getCell('E'.$i)->getCalculatedValue())."'";
	// 	$ANLHTXT = "'".$objPHPExcel->getActiveSheet()->getCell('F'.$i)->getCalculatedValue()."'";
	// 	$SERNR = "'".$objPHPExcel->getActiveSheet()->getCell('G'.$i)->getCalculatedValue()."'";
	// 	$INVENR = "'".$objPHPExcel->getActiveSheet()->getCell('H'.$i)->getCalculatedValue()."'";
	// 	//$fe_In =  $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
	// 	$IVDAT =  $objPHPExcel->getActiveSheet()->getCell('I'.$i)->getCalculatedValue();
	// 	//print_r($fe_In);die();
	// 	if($IVDAT == ""){	$IVDAT= 'NULL';	} else { $IVDAT= \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($IVDAT); $IVDAT = "'".$IVDAT->format('Y-m-d')."'"; }

	// 	$MENGE = $objPHPExcel->getActiveSheet()->getCell('J'.$i)->getCalculatedValue();
	// 	$MEINS = $objPHPExcel->getActiveSheet()->getCell('K'.$i)->getCalculatedValue();		
	// 	$STORT = $objPHPExcel->getActiveSheet()->getCell('L'.$i)->getCalculatedValue();
	// 	if($STORT!="")
	// 	 {
	// 	 	foreach ($datos_localizacion as $key => $value) {
	// 	 		if ($value['EMPLAZAMIENTO']==$STORT) {
	// 	 			$STORT= $value['ID_LOCATION'];
	// 	 			break;	
	// 	 		}
	// 	 	}
	// 	 }else
	// 	 {
	// 	 	$STORT = "''";	 	
	// 	 	$respuesta .= 'Custodio no encontrado en base: '.$STORT.' en el articulo con asset:'.$ANLN1.' /';		 	
	// 	 }
	// 	$KTEXT = $objPHPExcel->getActiveSheet()->getCell('M'.$i)->getCalculatedValue();

	// 	$PERNR =  $objPHPExcel->getActiveSheet()->getCell('N'.$i)->getCalculatedValue();
	// 	if($PERNR!="")
	// 	 {
	// 	 	foreach ($datos_custodio as $key => $value) {
	// 	 		if ($value['PERSON_NO']==$PERNR) {
	// 	 			$PERNR= $value['ID_PERSON'];
	// 	 			break;	
	// 	 		}else {$PERNR = "''"; }
	// 	 	}
	// 	 }else
	// 	 {
	// 	 	$PERNR = "''";	 	
	// 	 	$respuesta .= 'Custodio no encontrado en base: '.$PERNR.' en el articulo con asset:'.$ANLN1.' /';		 	
	// 	 }

	// 	  $PERNR_TXT = $objPHPExcel->getActiveSheet()->getCell('O'.$i)->getCalculatedValue();
	// 	  $ORD41 = $objPHPExcel->getActiveSheet()->getCell('P'.$i)->getCalculatedValue();		
	// 	 if($ORD41!="")
	// 	 {		 	
	// 	 	foreach ($datos_eval1 as $key => $value) {
	// 	 		if (strtoupper($value['DESCRIPCION'])==strtoupper($ORD41)) {
	// 	 			$ORD41= $value['ID_MARCA'];
	// 	 			break;
	// 	 		}
	// 	 	}if($ORD41==''){$ORD41= "''"; }
	// 	 }else
	// 	 {
	// 	 	$ORD41= "''";
	// 	 	$respuesta .= 'Marca no registrada en base: '.$ORD41.' en el articulo:'.$ANLN1.' /';		 		
	// 	 }

	// 	 $ORD42 = $objPHPExcel->getActiveSheet()->getCell('Q'.$i)->getCalculatedValue();
	// 	 if($ORD42!="")
	// 	 {
	// 	 	foreach ($datos_eval2 as $key => $value) {
	// 	 		if (strtoupper($value['DESCRIPCION'])==strtoupper($ORD42)) {
	// 	 			$ORD42= $value['ID_ESTADO'];	
	// 	 			break;
	// 	 		}
	// 	 	}if($ORD42==''){$ORD42= "''"; }		 
	// 	 }else
	// 	 {
	// 	 	$ORD42= "''";
	// 	 	$respuesta .= 'Estado no registrada en base: '.$ORD42.' en el articulo:'.$ANLN1.' /';
	// 	 }

	// 	 $ORD43 = $objPHPExcel->getActiveSheet()->getCell('R'.$i)->getCalculatedValue();
	// 	 if($ORD43!="")
	// 	 {
	// 	 	foreach ($datos_eval3 as $key => $value) {
	// 	 		if (strtoupper($value['DESCRIPCION'])==strtoupper($ORD43)) {
	// 	 			$ORD43= $value['ID_GENERO'];		
	// 	 			break;
	// 	 		}
	// 	 	}if($ORD43==''){$ORD43= "''"; }
	// 	 }else
	// 	 {
	// 	 	$ORD43 = "''";
	// 	 	$respuesta .= 'Genero no registrada en base: '.$ORD43.'en el articulo:'.$ANLN1.' /';
	// 	 }

	// 	 $ORD44 = trim($objPHPExcel->getActiveSheet()->getCell('S'.$i)->getCalculatedValue());
	// 	 if($ORD44!="")
	// 	 {		 
	// 	 	foreach ($datos_eval4 as $key => $value) {
	// 	 		if (strtoupper(trim($value['DESCRIPCION']))==strtoupper($ORD44)) {
	// 	 			$ORD44= $value['ID_COLORES'];
	// 	 			break;
	// 	 		}
	// 	 	}if($ORD44==''){$ORD44= "''"; }
	// 	 }else
	// 	 {
	// 	 	$ORD44 = "''";
	// 	 	$respuesta .= 'Color no registrada en base: '.$ORD44.'en el articulo:'.$ANLN1.' /';	
	// 	 }


	// 	 $GDLGRP = $objPHPExcel->getActiveSheet()->getCell('T'.$i)->getCalculatedValue();
	// 	 if($GDLGRP!="")
	// 	 {
	// 	 	$temp = false;
	// 	 	foreach ($datos_eval5 as $key => $value) {
	// 	 		if (strtoupper($value['pro'])==strtoupper($GDLGRP)) {
	// 	 			$temp = false;
	// 	 			$GDLGRP= $value['id'];
	// 	 			break;
	// 	 		}else{ $temp = true; }
	// 	 	}if($GDLGRP=='' || $temp==true){$GDLGRP= "''"; }	
	// 	 }else
	// 	 {
	// 	 	$GDLGRP= "''";
	// 	 	$respuesta .= 'Proyecto no registrada en base: '.$GDLGRP.' en el articulo:'.$ANLN1.' /';
	// 	 }
	// 	// // print_r($eval1);die();		
	// 	$ANLUE = "'".$objPHPExcel->getActiveSheet()->getCell('U'.$i)->getCalculatedValue()."'";

	// 	$AIBN1 = "'".$objPHPExcel->getActiveSheet()->getCell('V'.$i)->getCalculatedValue()."'";

	// 	$AKTIV  =  $objPHPExcel->getActiveSheet()->getCell('W'.$i)->getCalculatedValue();
	// 	if($AKTIV!= "")
	// 	{			
	// 		$AKTIV = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($AKTIV);
	// 		$AKTIV = "'".$AKTIV->format('Y-m-d')."'";
	// 	}

	// 	$URWRT = "'".$objPHPExcel->getActiveSheet()->getCell('X'.$i)->getCalculatedValue()."'";
	// 	$NOTE1 = "'".$this->validar_datos($objPHPExcel->getActiveSheet()->getCell('Z'.$i)->getCalculatedValue())."'";
	// 	$FOTO = "'".$objPHPExcel->getActiveSheet()->getCell('AA'.$i)->getCalculatedValue()."'";

	//        //ARRAY ASOCIATIVO DE COMO ESTA EN EL EXCEL Y HACIA QUE CAMPOS DE LA BASE VAN
	//        //    $KTEXT -> nombre de la localizacion
	//        //    $PERNR_TXT -> nombre del custodio
	//        //    campo y -> esta vacio

	// 	$datos2[] = array('COMPANYCODE'=>$BUKRS,'ANLN1'=>$ANLN1,'SUBNUMBER'=>$ANLN2,'DESCRIPT'=>$TXT50,'DESCRIPT2'=>$TXA50,'MODELO'=>$ANLHTXT,'SERIE'=>$SERNR,'TAG_UNIQUE'=>$INVENR,'FECHA_INV_DATE'=>$IVDAT,'QUANTITY'=>$MENGE,'BASE_UOM'=>$MEINS,'LOCATION'=>$STORT,'PERSON_NO'=>$PERNR,'EVALGROUP1'=>$ORD41,'EVALGROUP2'=>$ORD42,'EVALGROUP3'=>$ORD43,'EVALGROUP4'=>$ORD44,'EVALGROUP5'=>$GDLGRP,'ASSETSUPNO'=>$ANLUE,'ORIG_ASSET'=>$AIBN1,'ORIG_ACQ_YR'=>$AKTIV,'ORIG_VALUE'=>$URWRT,'CARACTERISTICA'=>$NOTE1,'IMAGEN'=>$FOTO);
	// 	}
	// 	$update = '';
	// 	if(count($datos2)>0)
	// 	{
	// 		foreach ($datos2 as $key => $value) {

	// 			foreach ($value as $key2 => $value2) {
	// 				$update.= $key2.'='.$value2.',';
	// 			}

	// 			print_r($update);die();
	// 		}
	// 	}
	// }



	// }



	// function plantilla_masiva1($part,$partes,$totalReg,$ini,$fin)
	// {
	// 	ini_set('memory_limit', '-1');
	// 	set_time_limit(2048);
	// 	//ini_set('memory_limit', '44M');
	// 	$datos_localizacion = $this->localizacion->lista_localizacion_todo();
	// 	$datos_custodio = $this->custodio->buscar_custodio_todo();
	// 	$datos_eval1 = $this->marcas->lista_marcas_todo();
	// 	$datos_eval2 = $this->estado->lista_estado_todo();
	// 	$datos_eval3 = $this->genero->lista_genero_todo();
	// 	$datos_eval4 = $this->color->lista_colores_todo();
	// 	$datos_eval5 = $this->proyecto->lista_proyectos_todo();

	// 	$respuesta = '';
	// 	$nombreArchivo = 'plantilla_masiva.xlsx';	
	// 	$doc = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
	// 	$objPHPExcel = 	$doc->load($nombreArchivo);
	// 	$objPHPExcel->setActiveSheetIndex(0);
	// //$numRows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
	// //$numRows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();

	// // print_r($numRows);die();
	// 	$campos = " INSERT INTO ac_articulos  (COMPANYCODE,ID_ASSET,SUBNUMBER,DESCRIPT,DESCRIPT2,MODELO,SERIE,FECHA_INV_DATE,COSTCENTER,RESP_CCTR,LOCATION,PERSON_NO,FUNDS_CTR_APC,PROFIT_CTR,EVALGROUP1,EVALGROUP2,EVALGROUP3,EVALGROUP4,EVALGROUP5,ASSETSUPNO,IMAGEN,RETIRADO,OBSERVACION,QUANTITY,BASE_UOM,ORIG_ASSET,ORIG_ACQ_YR,ORIG_VALUE,CARACTERISTICA) VALUES ";

	// 	$campos2="INSERT INTO ac_asset (TAG_UNIQUE,TAG_SERIE,TAG_ANT) VALUES";
	// 	$dato = '';
	// 	$dato2='';
	// 	$count = 0;
	// 	$insert ='';
	// 	$insert2 ='';

	// 	print_r($campos);die();
	// // for($i = $ini; $i < $fin; $i++)
	// // {

	// 	$i = 1;
	// 	$count = $count+1;

	// 	$compa = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
	// 	$asset = $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue();
	// 	$subnu = $objPHPExcel->getActiveSheet()->getCell('C'.$i)->getCalculatedValue();
	// 	$descr = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('D'.$i)->getCalculatedValue());
	// 	$desc2 = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('E'.$i)->getCalculatedValue());
	// 	$model = $objPHPExcel->getActiveSheet()->getCell('F'.$i)->getCalculatedValue();
	// 	$serie = $objPHPExcel->getActiveSheet()->getCell('G'.$i)->getCalculatedValue();
	// 	$rfid_ = $objPHPExcel->getActiveSheet()->getCell('H'.$i)->getCalculatedValue();
	// 	//$fe_In =  $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
	// 	$fe_In =  $objPHPExcel->getActiveSheet()->getCell('I'.$i)->getCalculatedValue();
	// 	//print_r($fe_In);die();
	// 	if($fe_In == "")
	// 	{
	// 		$fe_In= 'NULL';
	// 	}else
	// 	{
	// 		$fe_In= \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($fe_In);
	// 		$fe_In = "'".$fe_In->format('Y-m-d')."'";
	// 	}

	// 	$canti = $objPHPExcel->getActiveSheet()->getCell('J'.$i)->getCalculatedValue();
	// 	$ba_uo = $objPHPExcel->getActiveSheet()->getCell('K'.$i)->getCalculatedValue();		
	// 	// $locat = $objPHPExcel->getActiveSheet()->getCell('L'.$i)->getCalculatedValue();
	// 	    $empla = $objPHPExcel->getActiveSheet()->getCell('M'.$i)->getCalculatedValue();

	// 	$org_a = $objPHPExcel->getActiveSheet()->getCell('V'.$i)->getCalculatedValue();
	// 	 if($objPHPExcel->getActiveSheet()->getCell('L'.$i)->getCalculatedValue()=="")
	// 	 {
	// 	 	$locat = "''";
	// 	 }else
	// 	 {
	// 	 	$lo_vali = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('L'.$i)->getCalculatedValue());
	// 	 	foreach ($datos_localizacion as $key => $value) {
	// 	 		if ($value['EMPLAZAMIENTO']== $lo_vali) {
	// 	 			$locat= $value['ID_LOCATION'];
	// 	 			break;
	// 	 		}else
	// 	 		{
	// 	 			$locat= "''";
	// 	 		}
	// 	 	}
	// 	 	if($locat== "''")
	// 	 	{
	// 	 		$respuesta .= 'Localizacion no registrada en base: '.$empla.' en el articulo:'.$org_a.' /';
	// 	 	}

	// 	 }

	// 	// $perso = $objPHPExcel->getActiveSheet()->getCell('N'.$i)->getCalculatedValue();		 
	// 	  $custo = $objPHPExcel->getActiveSheet()->getCell('O'.$i)->getCalculatedValue();
	// 	if($objPHPExcel->getActiveSheet()->getCell('N'.$i)->getCalculatedValue()=="")
	// 	 {
	// 	 	$perso = "''";
	// 	 }else
	// 	 {

	// 	 	$cus_val = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('N'.$i)->getCalculatedValue());
	// 	 	foreach ($datos_custodio as $key => $value) {
	// 	 		if ($value['PERSON_NO']==$cus_val) {
	// 	 			$perso= $value['ID_PERSON'];
	// 	 			break;	
	// 	 		}else
	// 	 		{
	// 	 			$perso= "''";
	// 	 		}
	// 	 	}
	// 	 	if($perso== "''")
	// 	 	{
	// 	 		$respuesta .= 'Custodio no encontrado en base: '.$custo.' en el articulo:'.$org_a.' /';
	// 	 	}

	// 	 }
	// 	 if($objPHPExcel->getActiveSheet()->getCell('P'.$i)->getCalculatedValue()=="")
	// 	 {
	// 	 	$eval1 = "''";
	// 	 }else
	// 	 {
	// 	 	$eva1 = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('P'.$i)->getCalculatedValue());
	// 	 	foreach ($datos_eval1 as $key => $value) {
	// 	 		if ($value['DESCRIPCION']==$eva1) {
	// 	 			$eval1= $value['ID_MARCA'];
	// 	 			break;
	// 	 		}else
	// 	 		{
	// 	 			$eval1= "''";
	// 	 		}
	// 	 	}
	// 	 	if ($eval1 =="''") {
	// 	 		$respuesta .= 'Marca no registrada en base: '.$eva1.' en el articulo:'.$org_a.' /';		 		
	// 	 	}

	// 	 }

	// 	 if($objPHPExcel->getActiveSheet()->getCell('Q'.$i)->getCalculatedValue()=="")
	// 	 {
	// 	 	$eval2 = "''";
	// 	 }else
	// 	 {
	// 	 	$eva2 = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('Q'.$i)->getCalculatedValue());
	// 	 	//print_r($eva2);
	// 	 	//print_r($datos_eval2);die();
	// 	 	foreach ($datos_eval2 as $key => $value) {
	// 	 		if (strnatcasecmp($value['DESCRIPCION'], $eva2) === 0) {
	// 	 			$eval2= $value['ID_ESTADO'];	
	// 	 			break;
	// 	 		}else
	// 	 		{
	// 	 			$eval2= "''";
	// 	 		}
	// 	 	}

	// 	 	if($eval2== "''")
	// 	 	{
	// 	 		$respuesta .= 'Estado no registrada en base: '.$eva2.' en el articulo:'.$org_a.' /';
	// 	 	}

	// 	 }

	// 	 if($objPHPExcel->getActiveSheet()->getCell('R'.$i)->getCalculatedValue()=="")
	// 	 {
	// 	 	$eval3 = "''";
	// 	 }else
	// 	 {

	// 	 	$eva3 =$this->validar_datos($objPHPExcel->getActiveSheet()->getCell('R'.$i)->getCalculatedValue());
	// 	 	foreach ($datos_eval3 as $key => $value) {
	// 	 		if ($value['DESCRIPCION']==$eva3) {
	// 	 			$eval3= $value['ID_GENERO'];		
	// 	 			break;
	// 	 		}else
	// 	 		{
	// 	 			$eval3= "''";
	// 	 		}
	// 	 	}

	// 	 	if($eval3== "''")
	// 	 	{
	// 	 		$respuesta .= 'Genero no registrada en base: '.$eva3.'en el articulo:'.$org_a.' /';
	// 	 	}


	// 	 }

	// 	 if($objPHPExcel->getActiveSheet()->getCell('S'.$i)->getCalculatedValue()=="")
	// 	 {
	// 	 	$eval4 = "''";
	// 	 }else
	// 	 {

	// 	 	$eva4 =$this->validar_datos($objPHPExcel->getActiveSheet()->getCell('S'.$i)->getCalculatedValue());
	// 	 	foreach ($datos_eval4 as $key => $value) {
	// 	 		if ($value['DESCRIPCION']==$eva4) {
	// 	 			$eval4= $value['ID_COLORES'];
	// 	 			break;
	// 	 		}else
	// 	 		{
	// 	 			$eval4= "''";
	// 	 		}
	// 	 	}

	// 	 	if($eval4== "''")
	// 	 	{
	// 	 		$respuesta .= 'Color no registrada en base: '.$eva4.'en el articulo:'.$org_a.' /';
	// 	 	}


	// 	 }

	// 	 if($objPHPExcel->getActiveSheet()->getCell('T'.$i)->getCalculatedValue()=="")
	// 	 {
	// 	 	$eval5 = "''";
	// 	 }else
	// 	 {
	// 	 	$eva5 =$this->validar_datos($objPHPExcel->getActiveSheet()->getCell('T'.$i)->getCalculatedValue());
	// 	 	foreach ($datos_eval5 as $key => $value) {
	// 	 		if ($value['pro']==$eva5) {
	// 	 			$eval5= $value['id'];
	// 	 			break;
	// 	 		}else
	// 	 		{
	// 	 			$eval5= "''";
	// 	 		}
	// 	 	}

	// 	 	if($eval5== "''")
	// 	 	{
	// 	 		$respuesta .= 'Proyecto no registrada en base: '.$eva5.' en el articulo:'.$org_a.' /';
	// 	 	}

	// 	 }
	// 	// print_r($eval1);die();		
	// 	$ass_n = $objPHPExcel->getActiveSheet()->getCell('U'.$i)->getCalculatedValue();

	// 	//$f_com =  $objPHPExcel->getActiveSheet()->getStyle('W'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
	// 	$f_com =  $objPHPExcel->getActiveSheet()->getCell('W'.$i)->getCalculatedValue();
	// 	if($f_com == "")
	// 	{
	// 		$f_com= 'NULL';
	// 	}else
	// 	{
	// 		$f_com= \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($f_com);
	// 		//print_r($f_com->format('Y-m-d'));die();
	// 		$f_com = "'".$f_com->format('Y-m-d')."'";

	// 	}




	// 	$org_v = $objPHPExcel->getActiveSheet()->getCell('X'.$i)->getCalculatedValue();
	// 	$carac = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('Z'.$i)->getCalculatedValue());
	// 	$img = $objPHPExcel->getActiveSheet()->getCell('AA'.$i)->getCalculatedValue();


	// 	$dato.= "('".$compa."',".($i-1).",'".$subnu."','".$descr."','".$desc2."','".$model."','".$serie."',".$fe_In.",'','',".$locat.",".$perso.",'','',".$eval1.",".$eval2.",".$eval3.",".$eval4.",".$eval5.",'".$ass_n."','".$img."',0,'',".$canti.",'".$ba_uo."','".$org_a."',".$f_com.",'".$org_v."','".$carac."'),";

	// 	$dato2.="('".$rfid_."','".$asset."','".$org_a."'),";
	// 	if($count == 1000)
	// 	{
	// 		$dato = substr($dato,0,-1);
	// 		$insert .= $campos.' '.$dato.';';
	// 		$dato2 = substr($dato2,0,-1);
	// 		$insert2 .= $campos2.' '.$dato2.';';
	// 		$count = 0;
	// 		$dato = '';
	// 		$dato2 = '';
	// 	}

	// // }
	//    if($dato !='')
	//    {
	//    	  $dato = substr($dato,0,-1);
	//       $insert.=$campos.' '.$dato.';';

	//       $dato2 = substr($dato2,0,-1);
	//       $insert2.=$campos2.' '.$dato2.';';
	//    }

	//    print_r($sql);die();

	// 		   $ret = $this->db->sql_string($sql);
	//           $ret2 = $this->db->sql_string($sql2);

	// }

	function plantilla_masiva($part, $partes, $totalReg, $ini, $fin)
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
			$sql2 = " DELETE FROM ASSET; DBCC CHECKIDENT (ASSET, RESEED, 0); " . $insert2;

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

	function colores($part, $partes, $totalReg, $ini, $fin)
	{
		ini_set('memory_limit', '-1');
		$respuesta = '';
		$nombreArchivo = 'colores.xlsx';
		$doc = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
		$objPHPExcel = 	$doc->load($nombreArchivo);
		$objPHPExcel->setActiveSheetIndex(0);

		$campos = " INSERT INTO ac_colores  (CODIGO,DESCRIPCION) VALUES ";
		$dato = '';
		$count = 0;
		$insert = '';
		for ($i = $ini; $i < $fin; $i++) {
			$count = $count + 1;

			$codigo = $objPHPExcel->getActiveSheet()->getCell('A' . $i)->getCalculatedValue();
			$desc = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('B' . $i)->getCalculatedValue());


			$dato .= "('" . $codigo . "','" . $desc . "'),";
			if ($count == 1000) {
				$dato = substr($dato, 0, -1);
				$insert .= $campos . ' ' . $dato . ';';
				$count = 0;
				$dato = '';
			}
		}
		if ($dato != '') {
			$dato = substr($dato, 0, -1);
			$insert .= $campos . ' ' . $dato . ';';
		}

		if ($part == 1) {
			$sql = " DELETE FROM ac_colores; DBCC CHECKIDENT (ac_colores, RESEED, 0); " . $insert;
			//print_r($sql);die();
			$ret = $this->db->sql_string($sql);
			$ret = 1;
			$res = array('parte' => $part, 'partes' => $partes, 'TotalReg' => $totalReg, 'respuesta' => $ret, 'observaciones' => $respuesta, 'fin' => $fin);
			return $res;
		} else {
			//print_r($insert);die();
			$ret = $this->db->sql_string($insert);
			$res = array('parte' => $part, 'partes' => $partes, 'TotalReg' => $totalReg, 'respuesta' => $ret, 'observaciones' => $respuesta, 'fin' => $fin);
			return $res;
		}
	}

	function genero($part, $partes, $totalReg, $ini, $fin)
	{
		ini_set('memory_limit', '-1');
		$respuesta = '';
		$nombreArchivo = 'genero.xlsx';
		$doc = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
		$objPHPExcel = 	$doc->load($nombreArchivo);
		$objPHPExcel->setActiveSheetIndex(0);

		$campos = " INSERT INTO ac_genero  (CODIGO,DESCRIPCION) VALUES ";
		$dato = '';
		$count = 0;
		$insert = '';
		for ($i = $ini; $i < $fin; $i++) {
			$count = $count + 1;

			$codigo = $objPHPExcel->getActiveSheet()->getCell('A' . $i)->getCalculatedValue();
			$desc = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('B' . $i)->getCalculatedValue());


			$dato .= "('" . $codigo . "','" . $desc . "'),";
			if ($count == 1000) {
				$dato = substr($dato, 0, -1);
				$insert .= $campos . ' ' . $dato . ';';
				$count = 0;
				$dato = '';
			}
		}
		if ($dato != '') {
			$dato = substr($dato, 0, -1);
			$insert .= $campos . ' ' . $dato . ';';
		}

		if ($part == 1) {
			$sql = " DELETE FROM ac_genero; DBCC CHECKIDENT (ac_genero, RESEED, 0); " . $insert;
			//print_r($sql);die();
			$ret = $this->db->sql_string($sql);
			$ret = 1;
			$res = array('parte' => $part, 'partes' => $partes, 'TotalReg' => $totalReg, 'respuesta' => $ret, 'observaciones' => $respuesta, 'fin' => $fin);
			return $res;
		} else {
			//print_r($insert);die();
			$ret = $this->db->sql_string($insert);
			$res = array('parte' => $part, 'partes' => $partes, 'TotalReg' => $totalReg, 'respuesta' => $ret, 'observaciones' => $respuesta, 'fin' => $fin);
			return $res;
		}
	}

	function estado($part, $partes, $totalReg, $ini, $fin)
	{
		ini_set('memory_limit', '-1');
		$respuesta = '';
		$nombreArchivo = 'estado.xlsx';
		$doc = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
		$objPHPExcel = 	$doc->load($nombreArchivo);
		$objPHPExcel->setActiveSheetIndex(0);

		$campos = " INSERT INTO ac_estado (CODIGO,DESCRIPCION) VALUES ";
		$dato = '';
		$count = 0;
		$insert = '';
		for ($i = $ini; $i < $fin; $i++) {
			$count = $count + 1;

			$codigo = $objPHPExcel->getActiveSheet()->getCell('A' . $i)->getCalculatedValue();
			$desc = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('B' . $i)->getCalculatedValue());


			$dato .= "('" . $codigo . "','" . $desc . "'),";
			if ($count == 1000) {
				$dato = substr($dato, 0, -1);
				$insert .= $campos . ' ' . $dato . ';';
				$count = 0;
				$dato = '';
			}
		}
		if ($dato != '') {
			$dato = substr($dato, 0, -1);
			$insert .= $campos . ' ' . $dato . ';';
		}

		if ($part == 1) {
			$sql = " DELETE FROM ac_estado; DBCC CHECKIDENT (ac_estado, RESEED, 0); " . $insert;
			//print_r($sql);die();
			$ret = $this->db->sql_string($sql);
			$ret = 1;
			$res = array('parte' => $part, 'partes' => $partes, 'TotalReg' => $totalReg, 'respuesta' => $ret, 'observaciones' => $respuesta, 'fin' => $fin);
			return $res;
		} else {
			//print_r($insert);die();
			$ret = $this->db->sql_string($insert);
			$res = array('parte' => $part, 'partes' => $partes, 'TotalReg' => $totalReg, 'respuesta' => $ret, 'observaciones' => $respuesta, 'fin' => $fin);
			return $res;
		}
	}

	function marcas($part, $partes, $totalReg, $ini, $fin)
	{
		ini_set('memory_limit', '-1');
		$respuesta = '';
		$nombreArchivo = 'marcas.xlsx';
		$doc = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
		$objPHPExcel = 	$doc->load($nombreArchivo);
		$objPHPExcel->setActiveSheetIndex(0);

		$campos = " INSERT INTO ac_marcas (CODIGO,DESCRIPCION) VALUES ";
		$dato = '';
		$count = 0;
		$insert = '';
		for ($i = $ini; $i < $fin; $i++) {
			$count = $count + 1;

			$codigo = $objPHPExcel->getActiveSheet()->getCell('A' . $i)->getCalculatedValue();
			$desc = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('B' . $i)->getCalculatedValue());


			$dato .= "('" . $codigo . "','" . $desc . "'),";
			if ($count == 1000) {
				$dato = substr($dato, 0, -1);
				$insert .= $campos . ' ' . $dato . ';';
				$count = 0;
				$dato = '';
			}
		}
		if ($dato != '') {
			$dato = substr($dato, 0, -1);
			$insert .= $campos . ' ' . $dato . ';';
		}

		if ($part == 1) {
			$sql = " DELETE FROM ac_marcas; DBCC CHECKIDENT (ac_marcas, RESEED, 0); " . $insert;
			//print_r($sql);die();
			$ret = $this->db->sql_string($sql);
			$ret = 1;
			$res = array('parte' => $part, 'partes' => $partes, 'TotalReg' => $totalReg, 'respuesta' => $ret, 'observaciones' => $respuesta, 'fin' => $fin);
			return $res;
		} else {
			//print_r($insert);die();
			$ret = $this->db->sql_string($insert);
			$res = array('parte' => $part, 'partes' => $partes, 'TotalReg' => $totalReg, 'respuesta' => $ret, 'observaciones' => $respuesta, 'fin' => $fin);
			return $res;
		}
	}

	function proyectos($part, $partes, $totalReg, $ini, $fin)
	{
		ini_set('memory_limit', '-1');
		$respuesta = '';
		$nombreArchivo = 'proyecto.xlsx';
		$doc = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
		$objPHPExcel = 	$doc->load($nombreArchivo);
		$objPHPExcel->setActiveSheetIndex(0);

		$campos = " INSERT INTO ac_proyecto (programa_financiacion,entidad_cp,denominacion,descripcion,validez_de,validez_a,expiracion) VALUES ";
		$dato = '';
		$count = 0;
		$insert = '';
		for ($i = $ini; $i < $fin; $i++) {
			$count = $count + 1;

			$codigo = $objPHPExcel->getActiveSheet()->getCell('A' . $i)->getCalculatedValue();
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

			$dato .= "('" . $codigo . "','" . $entidad . "','" . $denominacion . "','" . $descripcion . "'," . $f1 . "," . $f2 . "," . $f3 . "),";
			if ($count == 1000) {
				$dato = substr($dato, 0, -1);
				$insert .= $campos . ' ' . $dato . ';';
				$count = 0;
				$dato = '';
			}
		}
		if ($dato != '') {
			$dato = substr($dato, 0, -1);
			$insert .= $campos . ' ' . $dato . ';';
		}

		if ($part == 1) {
			$sql = " DELETE FROM ac_proyecto; DBCC CHECKIDENT (ac_proyecto, RESEED, 0); " . $insert;
			//print_r($sql);die();
			$ret = $this->db->sql_string($sql);
			$ret = 1;
			$res = array('parte' => $part, 'partes' => $partes, 'TotalReg' => $totalReg, 'respuesta' => $ret, 'observaciones' => $respuesta, 'fin' => $fin);
			return $res;
		} else {
			//print_r($insert);die();
			$ret = $this->db->sql_string($insert);
			$res = array('parte' => $part, 'partes' => $partes, 'TotalReg' => $totalReg, 'respuesta' => $ret, 'observaciones' => $respuesta, 'fin' => $fin);
			return $res;
		}
	}

	function emplazamiento($part, $partes, $totalReg, $ini, $fin)
	{
		ini_set('memory_limit', '-1');
		$respuesta = '';
		$nombreArchivo = 'emplazamiento.xlsx';
		$doc = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
		$objPHPExcel = 	$doc->load($nombreArchivo);
		$objPHPExcel->setActiveSheetIndex(0);

		$campos = " INSERT INTO ac_localizacion (CENTRO,EMPLAZAMIENTO,DENOMINACION,FAMILIA,SUBFAMILIA) VALUES ";
		$dato = '';
		$count = 0;
		$insert = '';
		for ($i = $ini; $i < $fin; $i++) {
			$count = $count + 1;

			$centro = $objPHPExcel->getActiveSheet()->getCell('A' . $i)->getCalculatedValue();
			$emplazamiento = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('B' . $i)->getCalculatedValue());
			$denominacion = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('C' . $i)->getCalculatedValue());
			$fami = substr($emplazamiento, 0, 5);
			$subfami = substr($emplazamiento, 5);


			$dato .= "('" . $centro . "','" . $emplazamiento . "','" . $denominacion . "','" . $fami . "','" . $subfami . "'),";
			if ($count == 1000) {
				$dato = substr($dato, 0, -1);
				$insert .= $campos . ' ' . $dato . ';';
				$count = 0;
				$dato = '';
			}
		}
		if ($dato != '') {
			$dato = substr($dato, 0, -1);
			$insert .= $campos . ' ' . $dato . ';';
		}

		if ($part == 1) {
			$sql = " DELETE FROM ac_localizacion; DBCC CHECKIDENT (ac_localizacion, RESEED, 0); " . $insert;
			//print_r($sql);die();
			$ret = $this->db->sql_string($sql);
			$ret = 1;
			$res = array('parte' => $part, 'partes' => $partes, 'TotalReg' => $totalReg, 'respuesta' => $ret, 'observaciones' => $respuesta, 'fin' => $fin);
			return $res;
		} else {
			//print_r($insert);die();
			$ret = $this->db->sql_string($insert);
			$res = array('parte' => $part, 'partes' => $partes, 'TotalReg' => $totalReg, 'respuesta' => $ret, 'observaciones' => $respuesta, 'fin' => $fin);
			return $res;
		}
	}

	function custodio($part, $partes, $totalReg, $ini, $fin)
	{
		ini_set('memory_limit', '-1');
		$respuesta = '';
		$nombreArchivo = 'custodio.xlsx';
		$doc = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
		$objPHPExcel = 	$doc->load($nombreArchivo);
		$objPHPExcel->setActiveSheetIndex(0);

		$campos = " INSERT INTO th_personas (PERSON_NO,PERSON_NOM,PERSON_CI,PERSON_CORREO,PUESTO,UNIDAD_ORG) VALUES ";
		$dato = '';
		$count = 0;
		$insert = '';
		for ($i = $ini; $i < $fin; $i++) {
			$count = $count + 1;

			$NO = $objPHPExcel->getActiveSheet()->getCell('A' . $i)->getCalculatedValue();
			$CI = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('B' . $i)->getCalculatedValue());
			$NOM = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('C' . $i)->getCalculatedValue());
			$PUE = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('D' . $i)->getCalculatedValue());
			$UNI = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('E' . $i)->getCalculatedValue());
			$COR = $objPHPExcel->getActiveSheet()->getCell('F' . $i)->getCalculatedValue();

			$dato .= "('" . $NO . "','" . $NOM . "','" . $CI . "','" . $COR . "','" . $PUE . "','" . $UNI . "'),";
			if ($count == 1000) {
				$dato = substr($dato, 0, -1);
				$insert .= $campos . ' ' . $dato . ';';
				$count = 0;
				$dato = '';
			}
		}
		if ($dato != '') {
			$dato = substr($dato, 0, -1);
			$insert .= $campos . ' ' . $dato . ';';
		}

		if ($part == 1) {
			$sql = " DELETE FROM th_personas; DBCC CHECKIDENT (PERSON_NO, RESEED, 0); " . $insert;
			//print_r($sql);die();
			$ret = $this->db->sql_string($sql);
			$ret = 1;
			$res = array('parte' => $part, 'partes' => $partes, 'TotalReg' => $totalReg, 'respuesta' => $ret, 'observaciones' => $respuesta, 'fin' => $fin);
			return $res;
		} else {
			//print_r($insert);die();
			$ret = $this->db->sql_string($insert);
			$res = array('parte' => $part, 'partes' => $partes, 'TotalReg' => $totalReg, 'respuesta' => $ret, 'observaciones' => $respuesta, 'fin' => $fin);
			return $res;
		}
	}

	function validar_datos($texto)
	{
		$buscar = array('á', 'é', 'í', 'ó', 'ú', 'ñ', 'Ñ', 'Á', 'É', 'Í', 'Ó', 'Ú', ',', ':', ';', "'", '"');
		$remplazar = array('a', 'e', 'i', 'o', 'u', 'n', 'N', 'A', 'E', 'I', 'O', 'U', '', '', '', '', '');
		$texto_new = str_replace($buscar, $remplazar, $texto);
		return $texto_new;
	}
}
