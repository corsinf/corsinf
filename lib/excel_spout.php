<?php

date_default_timezone_set('America/Guayaquil');
require_once 'spout_excel/vendor/box/spout/src/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterInterface;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\CellAlignment;
use Box\Spout\Common\Entity\Style\Color;

if (!class_exists('reportesM')) {
	include('../modelo/ACTIVOS_FIJOS/reportesM.php');
}
if (!class_exists('ArticulosM')) {
	include('../modelo/ACTIVOS_FIJOS/ArticulosM.php');
}

require_once(dirname(__DIR__, 1) . '/funciones/funciones.php');
require_once(dirname(__DIR__, 1) . '/modelo/ACTIVOS_FIJOS/localizacionM.php');
require_once(dirname(__DIR__, 1) . '/modelo/ACTIVOS_FIJOS/marcasM.php');
require_once(dirname(__DIR__, 1) . '/modelo/ACTIVOS_FIJOS/custodioM.php');
require_once(dirname(__DIR__, 1) . '/modelo/ACTIVOS_FIJOS/proyectosM.php');
require_once(dirname(__DIR__, 1) . '/modelo/ACTIVOS_FIJOS/estadoM.php');
require_once(dirname(__DIR__, 1) . '/modelo/ACTIVOS_FIJOS/generoM.php');
require_once(dirname(__DIR__, 1) . '/modelo/ACTIVOS_FIJOS/coloresM.php');
require_once(dirname(__DIR__, 1) . '/modelo/ACTIVOS_FIJOS/clase_movimientoM.php');
require_once(dirname(__DIR__, 1) . '/modelo/ACTIVOS_FIJOS/detalle_articuloM.php');
require_once(dirname(__DIR__, 1) . '/modelo/ACTIVOS_FIJOS/cargar_datosM.php');
require_once(dirname(__DIR__, 1) . '/modelo/COWORKING/crear_mienbrosM.php');
require_once(dirname(__DIR__, 1) . '/modelo/COWORKING/crear_oficinaM.php');


/**
 * 
 **/

$reporte = new excel_spout();
if (isset($_GET['reporte_dinamico'])) {
	$datos = $_GET;
	$reporte->generar_excel($datos);
}
if (isset($_GET['reporte_marca'])) {
	$reporte->reporte_marca();
	// $reporte-> ejemplo();
}
if (isset($_GET['generarExcelEspacios'])) {
	$reporte->generarExcelEspacios();
	// $reporte-> ejemplo();
}
if (isset($_GET['generarExcelMobiliario'])) {
	$id_espacio = $_GET['id_espacio'];
	$reporte->generarExcelMobiliario($id_espacio);
	// $reporte-> ejemplo();
}



if (isset($_GET['generarExcelMiembros'])) {
	$reporte->generarExcelMiembros();
	// $reporte-> ejemplo();
}


if (isset($_GET['generarExcelCompras'])) {
	$reporte->generarExcelCompras();
	// $reporte-> ejemplo();
}




class excel_spout
{
	private $reportes;
	private $funciones;
	private $articulos;
	private $localizacion;
	private $marcas;
	private $custodio;
	private $proyectos;
	private $estado;
	private $genero;
	private $colores;
	private $mov;
	private $detalle_art;
	private $crear_miembro;
	private $carga_datos;
	private $crear_oficinas;

	function __construct()
	{
		$this->reportes = new reportesM();
		$this->funciones = new funciones();

		$this->articulos = new ArticulosM();
		$this->localizacion = new localizacionM();
		$this->marcas = new marcasM();
		$this->custodio = new custodioM();
		$this->proyectos = new proyectosM();

		$this->estado = new estadoM();
		$this->genero = new generoM();
		$this->colores = new coloresM();
		$this->mov = new clase_movimientoM();
		$this->detalle_art = new detalle_articuloM();

		$this->carga_datos = new cargar_datosM();
		$this->crear_miembro = new crear_mienbrosM();


		$this->carga_datos = new cargar_datosM();

		$this->crear_oficinas = new crear_oficinaM();
	}

	function generar_excel($parametros)
	{

		set_time_limit(0);
		unset($parametros['reporte_dinamico']);
		// print_r($parametros);die();

		$datos2 = $this->reportes->datos_reporte($parametros['id']);
		$sql = $datos2[0]['SQL'];
		$campos = $datos2[0]['CAMPOS'];
		// print_r($datos2);die();
		$sql_new = $this->funciones->generar_sql($parametros, $sql, $para_vista = false);

		// print_r($sql_new);die();

		$sql2 = explode('FROM', $sql_new['sql_normal']);
		$sql2 = "SELECT count(*) as total FROM " . $sql2[1];
		$sql2 = explode(' BY', $sql2);
		$sql2 = str_replace('ORDER', '', $sql2[0]);

		// print_r($sql2);die();


		$bloque = 5000;
		$inicio = 0;
		$total = $this->reportes->total_consulta($sql2);
		$total_act = ($total[0]['total'] / $bloque);

		// print_r($total);die();

		ini_set('memory_limit', '512M');
		$writer = WriterEntityFactory::createXLSXWriter();
		$filePath =  $datos2[0]['NOMBRE_REPORTE'] . '.xlsx';
		$writer->openToBrowser($filePath);

		$CABECERA1 = array();
		$campos = explode(',', $campos);
		foreach ($campos as $key => $value) {
			$cam = explode('-', $value);
			array_push($CABECERA1, $cam[1]);
		}

		$rowFromValues = WriterEntityFactory::createRowFromArray($CABECERA1);
		$writer->addRow($rowFromValues);

		while ($total_act > 0) {
			// $limite = $inicio.'-'.$bloque;
			$sql = $sql_new['sql_normal'] . ' OFFSET ' . $inicio . ' ROWS FETCH NEXT ' . $bloque . ' ROWS ONLY;';

			// print_r($sql);die();
			$datos = $this->reportes->realizar_consulta($sql);
			foreach ($datos as $key => $value) {
				$linea = array();
				// print_r($value);die();
				$arraysIndividuales = array_map(function ($elemento) {
					return array($elemento);
				}, $value);

				foreach ($arraysIndividuales as $key2 => $value2) {
					$dato1 = $value2[0];
					if (!is_object($dato1)) {
						array_push($linea, $dato1);
					} else {
						array_push($linea, $dato1->format('Y-m-d'));
					}
				}

				// print_r($linea);die();  			

				$rowFromValues = WriterEntityFactory::createRowFromArray($linea);
				$writer->addRow($rowFromValues);
			}

			$total_act--;
			$inicio = $inicio + $bloque;
			unset($datos);
		}


		$writer->close();
	}

	function Reporte_sap_total()
	{

		$bloque = 5000;
		$inicio = 0;
		$total = $this->articulos->total_activos();
		$total_act = ($total[0]['total'] / $bloque);

		$query = '';
		$loc = '';
		$cus = '';
		// set_time_limit(0);
		ini_set('memory_limit', '512M');

		$writer = WriterEntityFactory::createXLSXWriter();
		// $writer = WriterEntityFactory::createODSWriter();
		// $writer = WriterEntityFactory::createCSVWriter();
		$filePath = 'TOTAL_ACTIVOS.xlsx';


		// $writer->openToFile($filePath); // write data to a file or to a PHP stream
		$writer->openToBrowser($fileName); // stream data directly to the browser

		// $cells = [
		//     WriterEntityFactory::createCell('Carl'),
		//     WriterEntityFactory::createCell('is'),
		//     WriterEntityFactory::createCell('great!'),
		// ];

		// /** add a row at a time */
		// $singleRow = WriterEntityFactory::createRow($cells);
		// $writer->addRow($singleRow);

		// /** add multiple rows at a time */
		// $multipleRows = [
		//     WriterEntityFactory::createRow($cells),
		//     WriterEntityFactory::createRow($cells),
		// ];
		// $writer->addRows($multipleRows); 

		/** Shortcut: add a row from an array of values */
		// $values = ['Carl', 'is', 'great!'];
		// $rowFromValues = WriterEntityFactory::createRowFromArray($values);
		// $writer->addRow($rowFromValues);

		// $writer->close();




		$CABECERA1 = array(
			'COMPANYCODE/SOCIEDAD',
			'ASSET / ACTIVO FIJO PRINCIPAL',
			'SUBNUMERO ACTIVO FIJO',
			'DESCRIPCION',
			'DESCRIPCION 2',
			'MODELO',
			'SERIE',
			'RFID',
			'FECHA ULTIMO INVENTARIO',
			'CANTIDAD',
			'UNIDAD DE MEDIDA',
			'EMPLAZAMIENTO CODIGO',
			'EMPLAZAMIENTO',
			'CUSTODIO CODIGO',
			'CUSTODIO',
			'MARCA',
			'ESTADO',
			'GENERO',
			'COLORES',
			'PROYECTO',
			'SUPRA NUMERO',
			'TAG ANTIGUO / ACTIVO FIJO ORIGINAL',
			'FECHA COMPRA',
			'VALOR ACTUAL',
			'OBSEVACIONES',
			'BAJAS',
			'NOTE1',
			'IMAGEN',
			'ACTUALIZADO POR',
			'FECHA BAJA',
			'FECHA CONTABILIZACION',
			'FECHA REFERENCIA',
			'PERIODO'
		);
		$rowFromValues = WriterEntityFactory::createRowFromArray($CABECERA1);
		$writer->addRow($rowFromValues);

		$CABECERA2 = array(
			'BUKRS',
			'ANLN1',
			'ANLN2',
			'TXT50',
			'TXTA50',
			'ANLHTXT',
			'SERNR',
			' INVNR',
			'IVDAT',
			'MERGE',
			'MEINS',
			'STORT',
			'KTEXT',
			'PERNR',
			'PERNP_TXT',
			'ORD41',
			'ORD42',
			'ORD43',
			'ORD44',
			'GDLGRP',
			'ANLUE',
			'AIBN1',
			'AKTIV',
			'URWRT',
			'OBSERV',
			'BAJAS',
			'NOTE1',
			'IMAGEN',
			'ACTUALIZADO POR',
			'BLDAT',
			'BUDAT',
			'BZDAT',
			'MONAT'
		);
		$rowFromValues = WriterEntityFactory::createRowFromArray($CABECERA2);
		$writer->addRow($rowFromValues);


		// $bloque = 5000;
		// $inicio = 0;
		// $total = $this->articulos->total_activos();
		// $total_act = $total[0]['total']/$bloque;



		while ($total_act > 0) {
			$limite = $inicio . '-' . $bloque;
			$datos = $this->articulos->lista_articulos_sap_codigos($query, $loc, $cus, $limite, false, false, false, false);
			// print_r($datos);die();
			foreach ($datos as $key => $value) {
				// $fecha = $value['FECHA_INV_DATE']->format('Y-m-d');
				$fecha = '';
				if ($value['FECHA_INV_DATE'] != '') {
					$fecha = $value['FECHA_INV_DATE']->format('Y-m-d');
				}
				$fechaC = '';
				if ($value['ORIG_ACQ_YR'] != '') {
					$fechaC = $value['ORIG_ACQ_YR']->format('Y-m-d');
				}
				$fechaB = '';
				if ($value['FECHA_BAJA'] != '') {
					$fechaB = $value['FECHA_BAJA']->format('Y-m-d');
				}
				$fechaCON = '';
				if ($value['FECHA_CONTA'] != '') {
					$fechaCON = $value['FECHA_CONTA']->format('Y-m-d');
				}
				$fechaREF = '';
				if ($value['FECHA_REFERENCIA'] != '') {
					$fechaREF = $value['FECHA_REFERENCIA']->format('Y-m-d');
				}


				$SALIDA = array(
					$value['COMPANYCODE'],
					$value['TAG_SERIE'],
					$value['SUBNUMBER'],
					$value['DESCRIPT'],
					$value['DESCRIPT2'],
					$value['MODELO'],
					' ' . $value['SERIE'],
					$value['TAG_UNIQUE'],
					$fecha,
					$value['QUANTITY'],
					$value['BASE_UOM'],
					$value['EMPLAZAMIENTO'],
					$value['DENOMINACION'],
					$value['PERSON_NO'],
					$value['PERSON_NOM'],
					$value['marca'],
					$value['estado'],
					$value['genero'],
					$value['color'],
					$value['criterio'],
					$value['ASSETSUPNO'],
					$value['ORIG_ASSET'],
					$fechaC,
					$value['ORIG_VALUE'],
					$value['OBSERVACION'],
					$value['BAJAS'],
					$value['CARACTERISTICA'],
					$value['IMAGEN'],
					$value['ACTU_POR'],
					$fechaB,
					$fechaCON,
					$fechaREF,
					$value['PERIODO']
				);

				$rowFromValues = WriterEntityFactory::createRowFromArray($SALIDA);
				$writer->addRow($rowFromValues);
			}

			$total_act--;
			$inicio = $inicio + $bloque;
			unset($datos);
		}

		$writer->close();
	}


	function basic_excel($header, $datos, $titulo)
	{
		$writer = WriterEntityFactory::createXLSXWriter();
		$fileName = $titulo . '.xlsx';
		$writer->openToBrowser($fileName);

		//print_r($datos);die();
		$CABECERA2 = $header;
		$rowFromValues = WriterEntityFactory::createRowFromArray($CABECERA2);
		$writer->addRow($rowFromValues);

		foreach ($datos as $key => $value) {
			$rowFromValues = WriterEntityFactory::createRowFromArray($value);
			$writer->addRow($rowFromValues);
		}

		$writer->close();
	}


	function reporte_marca()
	{
		$datos = $this->marcas->lista_marcas_todo();

		$writer = WriterEntityFactory::createXLSXWriter();
		$fileName = 'TOTAL_MARCAS.xlsx';
		$writer->openToBrowser($fileName);

		//print_r($datos);die();
		$CABECERA2 = array('CODIGO SAP', 'DESCRIPCION SAP', 'ESTADO');
		$rowFromValues = WriterEntityFactory::createRowFromArray($CABECERA2);
		$writer->addRow($rowFromValues);

		foreach ($datos as $key => $value) {
			$SALIDA = array($value['CODIGO'], $value['DESCRIPCION'], $value['ESTADO']);
			$rowFromValues = WriterEntityFactory::createRowFromArray($SALIDA);
			$writer->addRow($rowFromValues);
		}

		$writer->close();
	}
	function generarExcelEspacios()
	{
		$datos = $this->crear_oficinas->listardebase();
		//print_r($datos);die();
		//$writer = WriterEntityFactory::createXLSXWriter();
		$writer = WriterEntityFactory::createCSVWriter();
		$fileName = 'Listado_de_oficinas.CSV';
		$writer->openToBrowser($fileName);

		//print_r($datos);die();
		$CABECERA2 = array('ID', 'Nombre', 'Aforo', 'Precio', 'Estado', 'Categoria');
		$rowFromValues = WriterEntityFactory::createRowFromArray($CABECERA2);
		$writer->addRow($rowFromValues);

		foreach ($datos as $key => $value) {
			$SALIDA = array($value['id_espacio'], $value['nombre_espacio'], $value['aforo_espacio'], $value['precio_espacio'], $value['estado_espacio'], $value['nombre_categoria']);
			$rowFromValues = WriterEntityFactory::createRowFromArray($SALIDA);
			$writer->addRow($rowFromValues);
		}

		$writer->close();
	}
	function generarExcelMobiliario($id_espacio)
	{

		$datos = $this->crear_oficinas->listarMobiliario($id_espacio);

		// Verificar que $datos no esté vacío
		if (empty($datos)) {
			echo "Error: No hay datos disponibles para el mobiliario.";
			return; // Salir de la función si no hay datos
		}
		//print_r($datos);die();

		$writer = WriterEntityFactory::createCSVWriter();
		$fileName = 'Listado_del_mobiliario.CSV';
		$writer->openToBrowser($fileName);


		$CABECERA2 = array('Id mobiliario', 'Id espacio', 'cantidad', 'detalle');
		$rowFromValues = WriterEntityFactory::createRowFromArray($CABECERA2);
		$writer->addRow($rowFromValues);


		foreach ($datos as $key => $value) {
			$SALIDA = array($value['id_mobiliario'], $value['id_espacio'], $value['cantidad'], $value['detalle_mobiliario']);
			$rowFromValues = WriterEntityFactory::createRowFromArray($SALIDA);
			$writer->addRow($rowFromValues);
		}

		// Cerrar el archivo
		$writer->close();
	}


	function generarExcelCompras()
	{
		// Obtener las compras por sala y la lista de compras con miembros
		$compras_sala = $this->crear_miembro->listacomprasala();
		$compras_lista = $this->crear_miembro->compraslista();

		// Crear un array asociativo para mapear id_compra a datos de compras_lista
		$compras_lista_map = array();
		foreach ($compras_lista as $compra) {
			$compras_lista_map[$compra['id_compra']] = $compra; // Relacionar id_compra con los detalles de miembro
		}


		$writer = WriterEntityFactory::createCSVWriter();
		$fileName = 'InformeDeCompras.csv';
		$writer->openToBrowser($fileName);


		$CABECERA2 = array('Sala', 'Compra', 'Miembro', 'Producto', 'Cantidad', 'Precio', 'Total');
		$rowFromValues = WriterEntityFactory::createRowFromArray($CABECERA2);
		$writer->addRow($rowFromValues);

		// Recorrer las compras por sala
		foreach ($compras_sala as $value) {
			// Verificar si existe un miembro asociado a esta compra
			$datos_adicionales = $compras_lista_map[$value['id_compra']] ?? null;

			// Si hay miembro, usar su nombre completo, si no, asignar "N/H"
			$nombre_completo = 'N/H';
			if ($datos_adicionales !== null) {
				$nombre_completo = $datos_adicionales['nombre_completo']; // Nombre completo del miembro
			}

			// Crear la fila para el CSV
			$SALIDA = array($value['id_sala'], $value['id_compra'], $nombre_completo, $value['id_producto'], $value['cantidad_compra'], $value['pvp_compra'], $value['total_compra']);


			$rowFromValues = WriterEntityFactory::createRowFromArray($SALIDA);
			$writer->addRow($rowFromValues);
		}

		$writer->close();
	}











	function generarExcelMiembros()
	{
		$datos = $this->crear_miembro->listardebase(2);
		//crear_mienbrosdosprint_r($datos);die();
		//$writer = WriterEntityFactory::createXLSXWriter();
		$writer = WriterEntityFactory::createCSVWriter();
		$fileName = 'InformeDeMiembros.csv';
		$writer->openToBrowser($fileName);


		$CABECERA2 = array('Nombre', 'Apellido', 'Telefono', 'Direccion', 'Espacio');
		$rowFromValues = WriterEntityFactory::createRowFromArray($CABECERA2);
		$writer->addRow($rowFromValues);

		foreach ($datos as $key => $value) {
			$SALIDA = array($value['nombre_miembro'], $value['apellido_miembro'], $value['telefono_miembro'], $value['direccion_miembro'], $value['id_espacio']);
			$rowFromValues = WriterEntityFactory::createRowFromArray($SALIDA);
			$writer->addRow($rowFromValues);
		}

		$writer->close();


		//print_r('datos');die();

	}
}
