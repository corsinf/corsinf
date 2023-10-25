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


if(!class_exists('reportesM'))
{
	include('../modelo/reportesM.php');
}
if(!class_exists('ArticulosM'))
{
	include('../modelo/ArticulosM.php');
}


include('../funciones/funciones.php');
include('../modelo/localizacionM.php');
include('../modelo/marcasM.php');
include('../modelo/custodioM.php');
include('../modelo/proyectosM.php');
include('../modelo/estadoM.php');
include('../modelo/generoM.php');
include('../modelo/coloresM.php');
include('../modelo/clase_movimientoM.php');
include('../modelo/detalle_articuloM.php');
include('../modelo/cargar_datosM.php');


/**
 * 
 */
$reporte = new excel_spout();
if(isset($_GET['reporte_dinamico']))
{
	$datos = $_GET;
	$reporte->generar_excel($datos);
}
if(isset($_GET['reporte_marca']))
{
	$reporte->reporte_marca();
	// $reporte-> ejemplo();
}

class excel_spout
{
	private $reportes;
	private $funciones;
	
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
		
	}

	function generar_excel($parametros)
	{
		unset($parametros['reporte_dinamico']);
		// print_r($parametros);die();

		$datos2 = $this->reportes->datos_reporte($parametros['id']);
		$sql = $datos2[0]['SQL'];
		$campos = $datos2[0]['CAMPOS'];
		// print_r($sql);die();
		$sql_new = $this->funciones->generar_sql($parametros,$sql,$para_vista=false);

		// print_r($sql_new);die();

		$sql2 = explode('FROM', $sql_new['sql_normal']);
		$sql2 = "SELECT count(*) as total FROM ".$sql2[1];
		$sql2 = explode(' BY',$sql2);
		$sql2 = str_replace('ORDER','',$sql2[0]);

		// print_r($sql2);die();


		$bloque = 5000;
		$inicio = 0;
		$total = $this->reportes->total_consulta($sql2);
		$total_act = ($total[0]['total']/$bloque);

		ini_set('memory_limit', '512M');
		$writer = WriterEntityFactory::createXLSXWriter();
		$filePath = 'TOTAL_ACTIVOS.xlsx';
		$writer->openToBrowser($fileName);

		$CABECERA1 = array();
		$campos = explode(',',$campos);
		foreach ($campos as $key => $value) {
			$cam = explode('-',$value);
			array_push($CABECERA1, $cam[1]);
		}

	  $rowFromValues = WriterEntityFactory::createRowFromArray($CABECERA1);
	  $writer->addRow($rowFromValues);

	  while ($total_act>0) 
	  {
			// $limite = $inicio.'-'.$bloque;
			$sql = $sql_new['sql_normal'].' OFFSET '.$inicio.' ROWS FETCH NEXT '.$bloque.' ROWS ONLY;';

			// print_r($sql);die();
			$datos = $this->reportes-> realizar_consulta($sql);			
			foreach ($datos as $key => $value) 
			{
				$linea = array();			  		
				// print_r($value);die();
				$arraysIndividuales = array_map(function($elemento) {
					return array($elemento);
				}, $value);

				foreach ($arraysIndividuales as $key2 => $value2) {
					$dato1 = $value2[0];
		  			if(!is_object($dato1))
		  			{
		  				array_push($linea,$dato1);
		  			}else
		  			{
		  				array_push($linea,$dato1->format('Y-m-d'));
		  			}				
				}

				// print_r($linea);die();  			

			 	$rowFromValues = WriterEntityFactory::createRowFromArray($linea);
				$writer->addRow($rowFromValues);
			}

			$total_act--;
			$inicio = $inicio+$bloque;
			unset($datos);
		}


	   	$writer->close();
	}

  function Reporte_sap_total(){

		$bloque = 5000;
		$inicio = 0;
		$total = $this->articulos->total_activos();
		$total_act = ($total[0]['total']/$bloque);

			$query ='';		
			$loc ='';		
			$cus ='';
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




    $CABECERA1 = array('COMPANYCODE/SOCIEDAD',
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
	  'PERIODO');
	  $rowFromValues = WriterEntityFactory::createRowFromArray($CABECERA1);
		$writer->addRow($rowFromValues);

		$CABECERA2 = array('BUKRS',
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
		'MONAT');
		$rowFromValues = WriterEntityFactory::createRowFromArray($CABECERA2);
		$writer->addRow($rowFromValues);


		// $bloque = 5000;
		// $inicio = 0;
		// $total = $this->articulos->total_activos();
		// $total_act = $total[0]['total']/$bloque;

		

		while ($total_act>0) {
			$limite = $inicio.'-'.$bloque;
			 $datos = $this->articulos->lista_articulos_sap_codigos($query,$loc,$cus,$limite,false,false,false,false);
			  // print_r($datos);die();
			  foreach ($datos as $key => $value) {
				// $fecha = $value['FECHA_INV_DATE']->format('Y-m-d');
					  $fecha='';
						if($value['FECHA_INV_DATE'] !='')
						{
							$fecha =$value['FECHA_INV_DATE']->format('Y-m-d');
						}
						$fechaC='';
						if($value['ORIG_ACQ_YR'] !='')
						{
							$fechaC =$value['ORIG_ACQ_YR']->format('Y-m-d'); 
						}
						$fechaB='';
						if($value['FECHA_BAJA'] !='')
						{
							$fechaB =$value['FECHA_BAJA']->format('Y-m-d'); 
						}
						$fechaCON='';
						if($value['FECHA_CONTA'] !='')
						{
							$fechaCON =$value['FECHA_CONTA']->format('Y-m-d'); 
						}
						$fechaREF='';
						if($value['FECHA_REFERENCIA'] !='')
						{
							$fechaREF =$value['FECHA_REFERENCIA']->format('Y-m-d'); 
						}


						 $SALIDA =array(
								$value['COMPANYCODE'],
								$value['TAG_SERIE'],
								$value['SUBNUMBER'],
								$value['DESCRIPT'],
								$value['DESCRIPT2'],
								$value['MODELO'],		
								' '.$value['SERIE'],		
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
								$value['PERIODO']);

							 	$rowFromValues = WriterEntityFactory::createRowFromArray($SALIDA);
								$writer->addRow($rowFromValues);
				  }

					$total_act--;
					$inicio = $inicio+$bloque;
					unset($datos);
				}
	
	   	$writer->close();
    }


    function basic_excel($header,$datos,$titulo)
    {
    	$writer = WriterEntityFactory::createXLSXWriter();
		$fileName = $titulo.'.xlsx';
		$writer->openToBrowser($fileName);

    	//print_r($datos);die();
    	$CABECERA2= $header;
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
    	$CABECERA2= array('CODIGO SAP','DESCRIPCION SAP','ESTADO');
		$rowFromValues = WriterEntityFactory::createRowFromArray($CABECERA2);
		$writer->addRow($rowFromValues);
		
		foreach ($datos as $key => $value) {
			$SALIDA = array($value['CODIGO'],$value['DESCRIPCION'],$value['ESTADO']);
		    $rowFromValues = WriterEntityFactory::createRowFromArray($SALIDA);
		    $writer->addRow($rowFromValues);
		}
		
		$writer->close();
    }


}
?>
