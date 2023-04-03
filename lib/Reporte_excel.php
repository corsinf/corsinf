<?php 

date_default_timezone_set('America/Guayaquil'); 
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
include('../modelo/ArticulosM.php');
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
$reporte = new Reporte_excel();
if(isset($_GET['reporte_sap']))
{
	$reporte->Reporte_sap($_GET['query'],$_GET['loc'],$_GET['cus'],$_GET['desde'],$_GET['hasta']);
	// $reporte-> ejemplo();
}
if(isset($_GET['reporte_normal']))
{
	$reporte->Reporte_normal($_GET['query'],$_GET['loc'],$_GET['cus']);
	// $reporte-> ejemplo();
}
if(isset($_GET['reporte_total']))
{
	$reporte->Reporte_sap_total();
	// $reporte-> ejemplo();
}
if(isset($_GET['reporte_emplazamiento']))
{
	$reporte->reporte_localizacion();
	// $reporte-> ejemplo();
}
if(isset($_GET['reporte_marca']))
{
	$reporte->reporte_marca();
	// $reporte-> ejemplo();
}
if(isset($_GET['reporte_custodio']))
{
	$reporte->reporte_custodio();
	// $reporte-> ejemplo();
}
if(isset($_GET['reporte_proyecto']))
{
	$reporte->reporte_proyecto();
	// $reporte-> ejemplo();
}
if(isset($_GET['reporte_estado']))
{
	$reporte->reporte_estado();
	// $reporte-> ejemplo();
}
if(isset($_GET['reporte_genero']))
{
	$reporte->reporte_genero();
	// $reporte-> ejemplo();
}
if(isset($_GET['reporte_colores']))
{
	$reporte->reporte_color();
	// $reporte-> ejemplo();
}
if(isset($_GET['reporte_sap_bajas']))
{
	$reporte->Reporte_sap_total_bajas();
	// $reporte-> ejemplo();
}
if(isset($_GET['reporte_sap_bajas_rangos']))
{
	$parametros = $_GET;
	$reporte->Reporte_sap_rangos_bajas($parametros);
	// $reporte-> ejemplo();
}
if(isset($_GET['reporte_sap_terceros']))
{
	$reporte->reporte_sap_total_terceros();
	// $reporte-> ejemplo();
}
if(isset($_GET['reporte_sap_patrimoniales']))
{
	$reporte->reporte_sap_total_patrimoniales();
	// $reporte-> ejemplo();
}

if(isset($_GET['reporte_clase_movimientos']))
{
	$reporte->reporte_clase_movimientos();
	// $reporte-> ejemplo();
}

if(isset($_GET['reporte_log']))
{
	$parametros = $_GET;
	// print_r($_GET);die();
	$reporte->reporte_log($parametros);
}

if(isset($_GET['reporte_movimientos_art']))
{
	$parametros = $_GET;
	$reporte->reporte_movi($parametros);
	// $reporte-> ejemplo();
}

if(isset($_GET['reporte_cambios']))
{
	$parametros = $_GET;
	$reporte->reporte_cambios($parametros);
	// $reporte-> ejemplo();
}

if(isset($_GET['reporte_actual']))
{
	$parametros = $_GET;
	$reporte->reporte_actual($parametros);
	// $reporte-> ejemplo();
}

class Reporte_excel
{
	private $articulos;
	
	function __construct()
	{
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

	function Reporte_sap($query,$loc,$cus,$desde,$hasta)
	{
		//$tipoString = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;

		set_time_limit(0);
		$ruta='';
		if($query == 'null')
		{
			$query ='';
		}
		if($loc == 'null')
		{
			$loc ='';
		}
		if($cus == 'null')
		{
			$cus ='';
		}
		$mes=1;
		if($desde=='' && $hasta =='' || $desde!='' && $hasta =='' || $desde=='' && $hasta !='')
		{
			$mes = 0;
		}
		

		$datos = $this->articulos->lista_articulos_sap_codigos($query,$loc,$cus,false,false,$mes,$desde,$hasta);
    // print_r($datos);die();
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->getStyle('W')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
		$sheet->getStyle('I')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
		
		$sheet->setCellValue('A2','BUKRS');
		$sheet->setCellValue('B2','ANLN1');
		$sheet->setCellValue('C2','ANLN2');
		$sheet->setCellValue('D2','TXT50');
		$sheet->setCellValue('E2','TXTA50');
		$sheet->setCellValue('F2','ANLHTXT');		
		$sheet->setCellValue('G2','SERNR');		
		$sheet->setCellValue('H2','INVNR');
		$sheet->setCellValue('I2','IVDAT');
		$sheet->setCellValue('J2','MERGE');
		$sheet->setCellValue('K2','MEINS');
		$sheet->setCellValue('L2','STORT');
		$sheet->setCellValue('M2','KTEXT');
		$sheet->setCellValue('N2','PERNR');
		$sheet->setCellValue('O2','PERNP_TXT');
		$sheet->setCellValue('P2','ORD41');
		$sheet->setCellValue('Q2','ORD42');
		$sheet->setCellValue('R2','ORD43');
		$sheet->setCellValue('S2','ORD44');
		$sheet->setCellValue('T2','GDLGRP');
		$sheet->setCellValue('U2','ANLUE');
		$sheet->setCellValue('V2','AIBN1');
		$sheet->setCellValue('W2','AKTIV');
		$sheet->setCellValue('X2','URWRT');
		$sheet->setCellValue('Y2','OBSEV');	
		$sheet->setCellValue('Z2','BAJAS');		
		$sheet->setCellValue('AA2','NOTE1');
		$sheet->setCellValue('AB2','IMAGEN');
		$sheet->setCellValue('AC2','ACTUALIZADO POR');
		$sheet->setCellValue('AD2','BLDAT');
	  $sheet->setCellValue('AE2','BUDAT');
	  $sheet->setCellValue('AF2','BZDAT');
	  $sheet->setCellValue('AG2','MONAT');
	  $sheet->setCellValue('AGH2','BWASL');



		$sheet->setCellValue('A1','COMPANYCODE/SOCIEDAD');
		$sheet->setCellValue('B1','ASSET / ACTIVO FIJO PRINCIPAL');
		$sheet->setCellValue('C1','SUBNUMERO ACTIVO FIJO');
		$sheet->setCellValue('D1','DESCRIPCION');
		$sheet->setCellValue('E1','DESCRIPCION 2');
		$sheet->setCellValue('F1','MODELO');		
		$sheet->setCellValue('G1','SERIE');		
		$sheet->setCellValue('H1','RFID');
		$sheet->setCellValue('I1','FECHA ULTIMO INVENTARIO');
		$sheet->setCellValue('J1','CANTIDAD');
		$sheet->setCellValue('K1','UNIDAD DE MEDIDA');
		$sheet->setCellValue('L1','EMPLAZAMIENTO CODIGO');
		$sheet->setCellValue('M1','EMPLAZAMIENTO');
		$sheet->setCellValue('N1','CUSTODIO CODIGO');
		$sheet->setCellValue('O1','CUSTODIO');
		$sheet->setCellValue('P1','MARCA');
		$sheet->setCellValue('Q1','ESTADO');
		$sheet->setCellValue('R1','GENERO');
		$sheet->setCellValue('S1','COLORES');
		$sheet->setCellValue('T1','PROYECTO');
		$sheet->setCellValue('U1','SUPRA NUMERO');
		$sheet->setCellValue('V1','TAG ANTIGUO / ACTIVO FIJO ORIGINAL');
		$sheet->setCellValue('W1','FECHA COMPRA');
		$sheet->setCellValue('X1','VALOR ACTUAL');
		$sheet->setCellValue('Y1','OBSEVACIONES');	
		$sheet->setCellValue('Z1','BAJAS');		
		$sheet->setCellValue('AA1','NOTE1');
		$sheet->setCellValue('AB1','IMAGEN');
		$sheet->setCellValue('AC1','ACTUALIZADO POR');
		$sheet->setCellValue('AD1','FECHA BAJA');
	  $sheet->setCellValue('AE1','FECHA CONTABILIZACION');
	  $sheet->setCellValue('AF1','FECHA REFERENCIA');
	  $sheet->setCellValue('AG1','PERIODO');
	  $sheet->setCellValue('AGH1','CLASE DE MOVIMIENTO');

		$count = 3;

		foreach ($datos as $key => $value) {
			//print_r($value);die();
		// $fecha = $value['FECHA_INV_DATE']->format('Y-m-d');
		    $fecha='';
			if($value['FECHA_INV_DATE'] !='')
			{
				$fecha =$value['FECHA_INV_DATE']->format('Y-m-d');
				$fecha = new DateTime($fecha);
				$fecha = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($fecha);
			}
			$fechaC='';
			if($value['ORIG_ACQ_YR'] !='')
			{
				$fechaC =$value['ORIG_ACQ_YR']->format('Y-m-d'); 
				$fechaC = new DateTime($fechaC);
				$fechaC = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($fechaC);

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


		$sheet->setCellValue('A'.$count,$value['COMPANYCODE']);
		$sheet->setCellValue('B'.$count,$value['TAG_SERIE']);
		$sheet->setCellValue('C'.$count,$value['SUBNUMBER']);
		$sheet->setCellValue('D'.$count,utf8_decode($value['DESCRIPT']));
		$sheet->setCellValue('E'.$count,$value['DESCRIPT2']);
		$sheet->setCellValue('F'.$count,$value['MODELO']);		
		$sheet->setCellValue('G'.$count,' '.$value['SERIE']);		
		$sheet->setCellValue('H'.$count,$value['TAG_UNIQUE']);
		$sheet->setCellValue('I'.$count,$fecha);
		$sheet->setCellValue('J'.$count,$value['QUANTITY']);
		$sheet->setCellValue('K'.$count,$value['BASE_UOM']);
		$sheet->setCellValue('L'.$count,$value['EMPLAZAMIENTO']);
		$sheet->setCellValue('M'.$count,utf8_encode($value['DENOMINACION']));
		$sheet->setCellValue('N'.$count,$value['PERSON_NO']);
		$sheet->setCellValue('O'.$count,utf8_decode($value['PERSON_NOM']));
		$sheet->setCellValue('P'.$count,$value['marca']);
		$sheet->setCellValue('Q'.$count,$value['estado']);
		$sheet->setCellValue('R'.$count,$value['genero']);
		$sheet->setCellValue('S'.$count,$value['color']);
		$sheet->setCellValue('T'.$count,$value['criterio']);
		$sheet->setCellValue('U'.$count,$value['ASSETSUPNO']);
		$sheet->setCellValue('V'.$count,$value['ORIG_ASSET']);
		$sheet->setCellValue('W'.$count,$fechaC);
		$sheet->setCellValue('X'.$count,$value['ORIG_VALUE']);
		$sheet->setCellValue('Y'.$count,$value['OBSERVACION']);		
		$sheet->setCellValue('Z'.$count,$value['BAJAS']);
		$sheet->setCellValue('AA'.$count,utf8_decode($value['CARACTERISTICA']));
		$sheet->setCellValue('AB'.$count,$value['IMAGEN']);
		$sheet->setCellValue('AC'.$count,$value['ACTU_POR']);
		$sheet->setCellValue('AD'.$count,$fechaB);
		$sheet->setCellValue('AE'.$count,$fechaCON);
		$sheet->setCellValue('AF'.$count,$fechaREF);
		$sheet->setCellValue('AG'.$count,$value['PERIODO']);
		$sheet->setCellValue('AH'.$count,$value['CLASE_MOVIMIENTO']);
		$count = $count+1;
	  }


	    $write = new Xlsx($spreadsheet);
		$write->save('Reporte_activos.xlsx');
		echo "<meta http-equiv='refresh' content='0;url=Reporte_activos.xlsx'/>";
		exit;
		
		

	      // NOMBRE DEL ARCHIVO Y CHARSET
	      //header("Content-type: application/vnd.ms-excel");
         // header("Content-Disposition: attachment; filename=INVENTARIO.xls");
         // header("Pragma: no-cache");
          //header("Expires: 0");


          // $salida=fopen('php://output', 'w');

  }

  function Reporte_sap_rangos_bajas($parametros)
	{

		$desde = $parametros['desde'];
		$hasta = $parametros['hasta'];
		$query = $parametros['query'];
		$loc = $parametros['loc'];
		$cus = $parametros['cus'];
		//$tipoString = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;

		set_time_limit(0);
		$ruta='';
		if($query == 'null')
		{
			$query ='';
		}
		if($loc == 'null')
		{
			$loc ='';
		}
		if($cus == 'null')
		{
			$cus ='';
		}
		$mes=1;
		if($desde=='' && $hasta =='' || $desde!='' && $hasta =='' || $desde=='' && $hasta !='')
		{
			$mes = 0;
		}
		

		$datos = $this->articulos->lista_articulos_sap_codigos($query,$loc,$cus,false,false,$mes,$desde,$hasta,1);
    // NOMBRE DEL ARCHIVO Y CHARSET
	    header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Reporte_bajas_".$desde."_a_".$hasta.".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

          // $salida=fopen('php://output', 'w');

    $salida = '<table class="table table-striped" border="1">
	  <thead>
		<th>BUKRS</th>
		<th>ANLN1</th>
		<th>ANLN2</th>
		<th>TXT50</th>
		<th>TXTA50</th>
		<th>ANLHTXT</th>		
		<th>SERNR</th>		
		<th> INVNR</th>
		<th>IVDAT</th>
		<th>MERGE</th>
		<th>MEINS</th>
		<th>STORT</th>
		<th>KTEXT</th>
		<th>PERNR</th>
		<th>PERNP_TXT</th>
		<th>ORD41</th>
		<th>ORD42</th>
		<th>ORD43</th>
		<th>ORD44</th>
		<th>GDLGRP</th>
		<th>ANLUE</th>
		<th>AIBN1</th>
		<th>AKTIV</th>
		<th>URWRT</th>
		<th></th>
		<th>BAJAS</th>
		<th>NOTE1</th>
		<th>IMAGEN</th>
		<th>ACTUALIZADO POR</th>
		<th>BLDAT</th>
		<th>BUDAT</th>
		<th>BZDAT</th>
		<th>MONAT</th>
		<th>BWASL</th>
	  </thead>
	  <tbody>
	  <tr>
	  <td>COMPANYCODE/SOCIEDAD</td>
	  <td>ASSET / ACTIVO FIJO PRINCIPAL</td>
	  <td>SUBNUMERO ACTIVO FIJO</td>
	  <td>DESCRIPCION</td>
	  <td>DESCRIPCION 2</td>
	  <td>MODELO</td>
	  <td>SERIE</td>
	  <td>RFID</td>
	  <td>FECHA ULTIMO INVENTARIO</td>
	  <td>CANTIDAD</td>
	  <td>UNIDAD DE MEDIDA</td>
	  <td>EMPLAZAMIENTO CODIGO</td>
	  <td>EMPLAZAMIENTO</td>
	  <td>CUSTODIO CODIGO</td>
	  <td>CUSTODIO</td>
	  <td>MARCA</td>
	  <td>ESTADO</td>
	  <td>GENERO</td>
	  <td>COLORES</td>
	  <td>PROYECTO</td>
	  <td>SUPRA NUMERO</td>
	  <td>TAG ANTIGUO</td>
	  <td>FECHA COMPRA</td>
	  <td>VALOR ACTUAL</td>
	  <td>OBSEVACIONES</td>
	  <td>BAJAS</td>
	  <td>NOTE1</td>
	  <td>IMAGEN</td>
	  <td>ACTUALIZADO POR</td>
	  <td>FECHA BAJA</td>
	  <td>FECHA CONTABILIZACION</td>
	  <td>FECHA REFERENCIA</td>
	  <td>PERIODO</td>
	  <td>CLASE MOVIMIENTO</td>
	  <td>MOVIMIENTO</td>
	  </tr>';
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

		 $salida.='<tr>
		<td>'.$value['COMPANYCODE'].'</td>
		<td>'.$value['TAG_SERIE'].'</td>
		<td>'.$value['SUBNUMBER'].'</td>
		<td>'.utf8_decode($value['DESCRIPT']).'</td>
		<td>'.$value['DESCRIPT2'].'</td>
		<td>'.$value['MODELO'].'</td>		
		<td>'.' '.$value['SERIE'].'</td>		
		<td>'.$value['TAG_UNIQUE'].'&nbsp; </td>
		<td>'.$fecha.'</td>
		<td>'.$value['QUANTITY'].'</td>
		<td>'.$value['BASE_UOM'].'</td>
		<td>'.$value['EMPLAZAMIENTO'].'</td>
		<td>'.utf8_encode($value['DENOMINACION']).'</td>
		<td>'.$value['PERSON_NO'].'</td>
		<td>'.utf8_decode($value['PERSON_NOM']).'</td>
		<td>'.$value['marca'].'</td>
		<td>'.$value['estado'].'</td>
		<td>'.$value['genero'].'</td>
		<td>'.$value['color'].'</td>
		<td>'.$value['criterio'].'</td>
		<td>'.$value['ASSETSUPNO'].'</td>
		<td>'.$value['ORIG_ASSET'].'</td>
		<td>'.$fechaC.'</td>
		<td>'.$value['ORIG_VALUE'].'</td>
		<td>'.$value['OBSERVACION'].'</td>		
		<td>'.$value['BAJAS'].'</td>
		<td>'.utf8_decode($value['CARACTERISTICA']).'</td>
		<td>'.$value['IMAGEN'].'</td>
		<td>'.$value['ACTU_POR'].'</td>
		<td>'.$fechaB.'</td>
		<td>'.$fechaCON.'</td>
		<td>'.$fechaREF.'</td>
		<td>'.$value['PERIODO'].'</td>
		<td>'.$value['CLASE_MOVIMIENTO'].'</td>
		<td>'.$value['MOVIMIENTO'].'</td>
		</tr>';
	  }
	  $salida.='</tbody>
       </table>';
      echo $salida;

    }


  function Reporte_sap_total(){

			$query ='';		
			$loc ='';		
			$cus ='';
set_time_limit(0);
	  // NOMBRE DEL ARCHIVO Y CHARSET
	    header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=reporte_total_activos.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

          // $salida=fopen('php://output', 'w');

    $salida = '<table class="table table-striped">
    <thead>
    <th>COMPANYCODE/SOCIEDAD</th>
		<th>ASSET / ACTIVO FIJO PRINCIPAL</th>
		<th>SUBNUMERO ACTIVO FIJO</th>
		<th>DESCRIPCION</th>
		<th>DESCRIPCION 2</th>
		<th>MODELO</th>		
		<th>SERIE</th>		
		<th>RFID</th>
		<th>FECHA ULTIMO INVENTARIO</th>
		<th>CANTIDAD</th>
		<th>UNIDAD DE MEDIDA</th>
		<th>EMPLAZAMIENTO CODIGO</th>
		<th>EMPLAZAMIENTO</th>
		<th>CUSTODIO CODIGO</th>
		<th>CUSTODIO</th>
		<th>MARCA</th>
		<th>ESTADO</th>
		<th>GENERO</th>
		<th>COLORES</th>
		<th>PROYECTO</th>
		<th>SUPRA NUMERO</th>
		<th>TAG ANTIGUO / ACTIVO FIJO ORIGINAL</th>
		<th>FECHA COMPRA</th>
		<th>VALOR ACTUAL</th>
		<th>OBSEVACIONES</th>	
		<th>BAJAS</th>		
		<th>NOTE1</th>
		<th>IMAGEN</th>
		<th>ACTUALIZADO POR</th>
		<th>FECHA BAJA</th>
	  <th>FECHA CONTABILIZACION</th>
	  <th>FECHA REFERENCIA</th>
	  <th>PERIODO</th>
    </thead>
	  <tr>
		<td>BUKRS</td>
		<td>ANLN1</td>
		<td>ANLN2</td>
		<td>TXT50</td>
		<td>TXTA50</td>
		<td>ANLHTXT</td>		
		<td>SERNR</td>		
		<td> INVNR</td>
		<td>IVDAT</td>
		<td>MERGE</td>
		<td>MEINS</td>
		<td>STORT</td>
		<td>KTEXT</td>
		<td>PERNR</td>
		<td>PERNP_TXT</td>
		<td>ORD41</td>
		<td>ORD42</td>
		<td>ORD43</td>
		<td>ORD44</td>
		<td>GDLGRP</td>
		<td>ANLUE</td>
		<td>AIBN1</td>
		<td>AKTIV</td>
		<td>URWRT</td>
		<td>OBSERV</td>
		<td>BAJAS</td>
		<td>NOTE1</td>
		<td>IMAGEN</td>
		<td>ACTUALIZADO POR</td>
		<td>BLDAT</td>
		<td>BUDAT</td>
		<td>BZDAT</td>
		<td>MONAT</td>
	  </tr>
	  <tbody>';
	  $datos = $this->articulos->lista_articulos_sap_codigos($query,$loc,$cus,false,false,false,false,false);
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

			 $salida.='<tr>
			<td>'.$value['COMPANYCODE'].'</td>
			<td>'.$value['TAG_SERIE'].'</td>
			<td>'.$value['SUBNUMBER'].'</td>
			<td>'.utf8_decode($value['DESCRIPT']).'</td>
			<td>'.$value['DESCRIPT2'].'</td>
			<td>'.$value['MODELO'].'</td>		
			<td>'.' '.$value['SERIE'].'</td>		
			<td>'.$value['TAG_UNIQUE'].'&nbsp; </td>
			<td>'.$fecha.'</td>
			<td>'.$value['QUANTITY'].'</td>
			<td>'.$value['BASE_UOM'].'</td>
			<td>'.$value['EMPLAZAMIENTO'].'</td>
			<td>'.utf8_encode($value['DENOMINACION']).'</td>
			<td>'.$value['PERSON_NO'].'</td>
			<td>'.utf8_decode($value['PERSON_NOM']).'</td>
			<td>'.$value['marca'].'</td>
			<td>'.$value['estado'].'</td>
			<td>'.$value['genero'].'</td>
			<td>'.$value['color'].'</td>
			<td>'.$value['criterio'].'</td>
			<td>'.$value['ASSETSUPNO'].'</td>
			<td>'.$value['ORIG_ASSET'].'</td>
			<td>'.$fechaC.'</td>
			<td>'.$value['ORIG_VALUE'].'</td>
			<td>'.$value['OBSERVACION'].'</td>	
			<td>'.$value['BAJAS'].'</td>
			<td>'.utf8_decode($value['CARACTERISTICA']).'</td>
			<td>'.$value['IMAGEN'].'</td>
			<td>'.$value['ACTU_POR'].'</td>
			<td>'.$fechaB.'</td>
			<td>'.$fechaCON.'</td>
			<td>'.$fechaREF.'</td>
			<td>'.$value['PERIODO'].'</td>
			</tr>';
		  }
	  $salida.='</tbody>
       </table>';
      echo $salida;
    }


    function reporte_actual($parametros){

			$query ='';		
			$loc ='';		
			$cus ='';
set_time_limit(0);
	  // NOMBRE DEL ARCHIVO Y CHARSET
	    header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=reporte_actual.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

          // $salida=fopen('php://output', 'w');
        $query = $parametros['query'];
		$loc = $parametros['localizacion'];
		$cus = $parametros['custodio'];
		$pag = $parametros['pag'];
		$exacto=0;
		if(isset($parametros['exacto']) && $parametros['exacto']=='true')
		{
		 $exacto = 1;
		}
		$asset=0;
		if(isset($parametros['asset']) && $parametros['asset']=='true')
		{
			$asset = 1;
		}
		if(isset($parametros['asset_org']) && $parametros['asset_org']=='true')
		{
			$asset = 2;
		}
		if(isset($parametros['rfid']) && $parametros['rfid']=='true')
		{
			$asset = 0;
		}
		$multiple = 0;
		if(isset($parametros['multiple']) && $parametros['multiple']=='true' && $query!='')
		{
			$multiple = 1;
		}



    $salida = '<table class="table table-striped">
    <thead>
    <th>COMPANYCODE/SOCIEDAD</th>
		<th>ASSET / ACTIVO FIJO PRINCIPAL</th>
		<th>SUBNUMERO ACTIVO FIJO</th>
		<th>DESCRIPCION</th>
		<th>DESCRIPCION 2</th>
		<th>MODELO</th>		
		<th>SERIE</th>		
		<th>RFID</th>
		<th>FECHA ULTIMO INVENTARIO</th>
		<th>CANTIDAD</th>
		<th>UNIDAD DE MEDIDA</th>
		<th>EMPLAZAMIENTO CODIGO</th>
		<th>EMPLAZAMIENTO</th>
		<th>CUSTODIO CODIGO</th>
		<th>CUSTODIO</th>
		<th>MARCA</th>
		<th>ESTADO</th>
		<th>GENERO</th>
		<th>COLORES</th>
		<th>PROYECTO</th>
		<th>SUPRA NUMERO</th>
		<th>TAG ANTIGUO / ACTIVO FIJO ORIGINAL</th>
		<th>FECHA COMPRA</th>
		<th>VALOR ACTUAL</th>
		<th>OBSEVACIONES</th>	
		<th>BAJAS</th>		
		<th>NOTE1</th>
		<th>IMAGEN</th>
		<th>ACTUALIZADO POR</th>
		<th>FECHA BAJA</th>
	  <th>FECHA CONTABILIZACION</th>
	  <th>FECHA REFERENCIA</th>
	  <th>PERIODO</th>
    </thead>
	  <tr>
		<td>BUKRS</td>
		<td>ANLN1</td>
		<td>ANLN2</td>
		<td>TXT50</td>
		<td>TXTA50</td>
		<td>ANLHTXT</td>		
		<td>SERNR</td>		
		<td> INVNR</td>
		<td>IVDAT</td>
		<td>MERGE</td>
		<td>MEINS</td>
		<td>STORT</td>
		<td>KTEXT</td>
		<td>PERNR</td>
		<td>PERNP_TXT</td>
		<td>ORD41</td>
		<td>ORD42</td>
		<td>ORD43</td>
		<td>ORD44</td>
		<td>GDLGRP</td>
		<td>ANLUE</td>
		<td>AIBN1</td>
		<td>AKTIV</td>
		<td>URWRT</td>
		<td>OBSERV</td>
		<td>BAJAS</td>
		<td>NOTE1</td>
		<td>IMAGEN</td>
		<td>ACTUALIZADO POR</td>
		<td>BLDAT</td>
		<td>BUDAT</td>
		<td>BZDAT</td>
		<td>MONAT</td>
	  </tr>
	  <tbody>';
	  // $datos = $this->articulos->lista_articulos_sap_codigos($query,$loc,$cus,false,false,false,false,false);
	  $datos = $this->articulos->lista_articulos_sap_multiples($query,$loc,$cus,$pag,false,$exacto,$asset,false,false,false,false,false,$multiple);
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

			 $salida.='<tr>
			<td>'.$value['COMPANYCODE'].'</td>
			<td>'.$value['TAG_SERIE'].'</td>
			<td>'.$value['SUBNUMBER'].'</td>
			<td>'.utf8_decode($value['DESCRIPT']).'</td>
			<td>'.$value['DESCRIPT2'].'</td>
			<td>'.$value['MODELO'].'</td>		
			<td>'.' '.$value['SERIE'].'</td>		
			<td>'.$value['TAG_UNIQUE'].'&nbsp; </td>
			<td>'.$fecha.'</td>
			<td>'.$value['QUANTITY'].'</td>
			<td>'.$value['BASE_UOM'].'</td>
			<td>'.$value['EMPLAZAMIENTO'].'</td>
			<td>'.utf8_encode($value['DENOMINACION']).'</td>
			<td>'.$value['PERSON_NO'].'</td>
			<td>'.utf8_decode($value['PERSON_NOM']).'</td>
			<td>'.$value['marca'].'</td>
			<td>'.$value['estado'].'</td>
			<td>'.$value['genero'].'</td>
			<td>'.$value['color'].'</td>
			<td>'.$value['criterio'].'</td>
			<td>'.$value['ASSETSUPNO'].'</td>
			<td>'.$value['ORIG_ASSET'].'</td>
			<td>'.$fechaC.'</td>
			<td>'.$value['ORIG_VALUE'].'</td>
			<td>'.$value['OBSERVACION'].'</td>	
			<td>'.$value['BAJAS'].'</td>
			<td>'.utf8_decode($value['CARACTERISTICA']).'</td>
			<td>'.$value['IMAGEN'].'</td>
			<td>'.$value['ACTU_POR'].'</td>
			<td>'.$fechaB.'</td>
			<td>'.$fechaCON.'</td>
			<td>'.$fechaREF.'</td>
			<td>'.$value['PERIODO'].'</td>
			</tr>';
		  }
	  $salida.='</tbody>
       </table>';
      echo $salida;
    }



    function Reporte_sap_total_bajas(){

			$query ='';		
			$loc ='';		
			$cus ='';

	  // NOMBRE DEL ARCHIVO Y CHARSET
	    header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=reporte_total_bajas.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

          // $salida=fopen('php://output', 'w');

    $salida = '<table class="table table-striped" border="1">
	  <thead>
		<th>BUKRS</th>
		<th>ANLN1</th>
		<th>ANLN2</th>
		<th>TXT50</th>
		<th>TXTA50</th>
		<th>ANLHTXT</th>		
		<th>SERNR</th>		
		<th> INVNR</th>
		<th>IVDAT</th>
		<th>MERGE</th>
		<th>MEINS</th>
		<th>STORT</th>
		<th>KTEXT</th>
		<th>PERNR</th>
		<th>PERNP_TXT</th>
		<th>ORD41</th>
		<th>ORD42</th>
		<th>ORD43</th>
		<th>ORD44</th>
		<th>GDLGRP</th>
		<th>ANLUE</th>
		<th>AIBN1</th>
		<th>AKTIV</th>
		<th>URWRT</th>
		<th></th>
		<th>BAJAS</th>
		<th>NOTE1</th>
		<th>IMAGEN</th>
		<th>ACTUALIZADO POR</th>
		<th>BLDAT</th>
		<th>BUDAT</th>
		<th>BZDAT</th>
		<th>MONAT</th>
		<th>BWASL</th>
	  </thead>
	  <tbody>
	  <tr>
	  <td>COMPANYCODE/SOCIEDAD</td>
	  <td>ASSET / ACTIVO FIJO PRINCIPAL</td>
	  <td>SUBNUMERO ACTIVO FIJO</td>
	  <td>DESCRIPCION</td>
	  <td>DESCRIPCION 2</td>
	  <td>MODELO</td>
	  <td>SERIE</td>
	  <td>RFID</td>
	  <td>FECHA ULTIMO INVENTARIO</td>
	  <td>CANTIDAD</td>
	  <td>UNIDAD DE MEDIDA</td>
	  <td>EMPLAZAMIENTO CODIGO</td>
	  <td>EMPLAZAMIENTO</td>
	  <td>CUSTODIO CODIGO</td>
	  <td>CUSTODIO</td>
	  <td>MARCA</td>
	  <td>ESTADO</td>
	  <td>GENERO</td>
	  <td>COLORES</td>
	  <td>PROYECTO</td>
	  <td>SUPRA NUMERO</td>
	  <td>TAG ANTIGUO</td>
	  <td>FECHA COMPRA</td>
	  <td>VALOR ACTUAL</td>
	  <td>OBSEVACIONES</td>
	  <td>BAJAS</td>
	  <td>NOTE1</td>
	  <td>IMAGEN</td>
	  <td>ACTUALIZADO POR</td>
	  <td>FECHA BAJA</td>
	  <td>FECHA CONTABILIZACION</td>
	  <td>FECHA REFERENCIA</td>
	  <td>PERIODO</td>
	  <td>CLASE MOVIMIENTO</td>
	  <td>MOVIMIENTO</td>
	  </tr>';
	  $datos = $this->articulos->lista_articulos_sap_codigos($query,$loc,$cus,false,false,false,false,false,1,false,false);
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

		 $salida.='<tr>
		<td>'.$value['COMPANYCODE'].'</td>
		<td>'.$value['TAG_SERIE'].'</td>
		<td>'.$value['SUBNUMBER'].'</td>
		<td>'.utf8_decode($value['DESCRIPT']).'</td>
		<td>'.$value['DESCRIPT2'].'</td>
		<td>'.$value['MODELO'].'</td>		
		<td>'.' '.$value['SERIE'].'</td>		
		<td>'.$value['TAG_UNIQUE'].'&nbsp; </td>
		<td>'.$fecha.'</td>
		<td>'.$value['QUANTITY'].'</td>
		<td>'.$value['BASE_UOM'].'</td>
		<td>'.$value['EMPLAZAMIENTO'].'</td>
		<td>'.utf8_encode($value['DENOMINACION']).'</td>
		<td>'.$value['PERSON_NO'].'</td>
		<td>'.utf8_decode($value['PERSON_NOM']).'</td>
		<td>'.$value['marca'].'</td>
		<td>'.$value['estado'].'</td>
		<td>'.$value['genero'].'</td>
		<td>'.$value['color'].'</td>
		<td>'.$value['criterio'].'</td>
		<td>'.$value['ASSETSUPNO'].'</td>
		<td>'.$value['ORIG_ASSET'].'</td>
		<td>'.$fechaC.'</td>
		<td>'.$value['ORIG_VALUE'].'</td>
		<td>'.$value['OBSERVACION'].'</td>		
		<td>'.$value['BAJAS'].'</td>
		<td>'.utf8_decode($value['CARACTERISTICA']).'</td>
		<td>'.$value['IMAGEN'].'</td>
		<td>'.$value['ACTU_POR'].'</td>
		<td>'.$fechaB.'</td>
		<td>'.$fechaCON.'</td>
		<td>'.$fechaREF.'</td>
		<td>'.$value['PERIODO'].'</td>
		<td>'.$value['CLASE_MOVIMIENTO'].'</td>
		<td>'.$value['MOVIMIENTO'].'</td>
		</tr>';
	  }
	  $salida.='</tbody>
       </table>';
      echo $salida;
    }

     function Reporte_sap_total_terceros(){

			$query ='';		
			$loc ='';		
			$cus ='';

	  // NOMBRE DEL ARCHIVO Y CHARSET
	    header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=reporte_total_terceros.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

          // $salida=fopen('php://output', 'w');

    $salida = '<table class="table table-striped" border="1">
	  <thead>
		<th>BUKRS</th>
		<th>ANLN1</th>
		<th>ANLN2</th>
		<th>TXT50</th>
		<th>TXTA50</th>
		<th>ANLHTXT</th>		
		<th>SERNR</th>		
		<th> INVNR</th>
		<th>IVDAT</th>
		<th>MERGE</th>
		<th>MEINS</th>
		<th>STORT</th>
		<th>KTEXT</th>
		<th>PERNR</th>
		<th>PERNP_TXT</th>
		<th>ORD41</th>
		<th>ORD42</th>
		<th>ORD43</th>
		<th>ORD44</th>
		<th>GDLGRP</th>
		<th>ANLUE</th>
		<th>AIBN1</th>
		<th>AKTIV</th>
		<th>URWRT</th>
		<th></th>
		<th>NOTE1</th>
		<th>IMAGEN</th>
		<th>ACTUALIZADO POR</th>
	  </thead>
	  <tbody>';
	  $datos = $this->articulos->lista_articulos_sap_codigos($query,$loc,$cus,false,false,false,false,false,false,1,false);
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

		 $salida.='<tr>
		<td>'.$value['COMPANYCODE'].'</td>
		<td>'.$value['TAG_SERIE'].'</td>
		<td>'.$value['SUBNUMBER'].'</td>
		<td>'.utf8_decode($value['DESCRIPT']).'</td>
		<td>'.$value['DESCRIPT2'].'</td>
		<td>'.$value['MODELO'].'</td>		
		<td>'.' '.$value['SERIE'].'</td>		
		<td>'.$value['TAG_UNIQUE'].'&nbsp; </td>
		<td>'.$fecha.'</td>
		<td>'.$value['QUANTITY'].'</td>
		<td>'.$value['BASE_UOM'].'</td>
		<td>'.$value['EMPLAZAMIENTO'].'</td>
		<td>'.utf8_encode($value['DENOMINACION']).'</td>
		<td>'.$value['PERSON_NO'].'</td>
		<td>'.utf8_decode($value['PERSON_NOM']).'</td>
		<td>'.$value['marca'].'</td>
		<td>'.$value['estado'].'</td>
		<td>'.$value['genero'].'</td>
		<td>'.$value['color'].'</td>
		<td>'.$value['criterio'].'</td>
		<td>'.$value['ASSETSUPNO'].'</td>
		<td>'.$value['ORIG_ASSET'].'</td>
		<td>'.$fechaC.'</td>
		<td>'.$value['ORIG_VALUE'].'</td>
		<td>'.$value['OBSERVACION'].'</td>		
		<td>'.utf8_decode($value['CARACTERISTICA']).'</td>
		<td>'.$value['IMAGEN'].'</td>
		<td>'.$value['ACTU_POR'].'</td>
		</tr>';
	  }
	  $salida.='</tbody>
       </table>';
      echo $salida;
    }
     function Reporte_sap_total_patrimoniales(){

			$query ='';		
			$loc ='';		
			$cus ='';

	  // NOMBRE DEL ARCHIVO Y CHARSET
	    header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=reporte_total_patrimoniales.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

          // $salida=fopen('php://output', 'w');

    $salida = '<table class="table table-striped" border="1">
	  <thead>
		<th>BUKRS</th>
		<th>ANLN1</th>
		<th>ANLN2</th>
		<th>TXT50</th>
		<th>TXTA50</th>
		<th>ANLHTXT</th>		
		<th>SERNR</th>		
		<th> INVNR</th>
		<th>IVDAT</th>
		<th>MERGE</th>
		<th>MEINS</th>
		<th>STORT</th>
		<th>KTEXT</th>
		<th>PERNR</th>
		<th>PERNP_TXT</th>
		<th>ORD41</th>
		<th>ORD42</th>
		<th>ORD43</th>
		<th>ORD44</th>
		<th>GDLGRP</th>
		<th>ANLUE</th>
		<th>AIBN1</th>
		<th>AKTIV</th>
		<th>URWRT</th>
		<th></th>
		<th>NOTE1</th>
		<th>IMAGEN</th>
		<th>ACTUALIZADO POR</th>
	  </thead>
	  <tbody>';
	  $datos = $this->articulos->lista_articulos_sap_codigos($query,$loc,$cus,false,false,false,false,false,false,false,1);
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

		 $salida.='<tr>
		<td>'.$value['COMPANYCODE'].'</td>
		<td>'.$value['TAG_SERIE'].'</td>
		<td>'.$value['SUBNUMBER'].'</td>
		<td>'.utf8_decode($value['DESCRIPT']).'</td>
		<td>'.$value['DESCRIPT2'].'</td>
		<td>'.$value['MODELO'].'</td>		
		<td>'.' '.$value['SERIE'].'</td>		
		<td>'.$value['TAG_UNIQUE'].'&nbsp; </td>
		<td>'.$fecha.'</td>
		<td>'.$value['QUANTITY'].'</td>
		<td>'.$value['BASE_UOM'].'</td>
		<td>'.$value['EMPLAZAMIENTO'].'</td>
		<td>'.utf8_encode($value['DENOMINACION']).'</td>
		<td>'.$value['PERSON_NO'].'</td>
		<td>'.utf8_decode($value['PERSON_NOM']).'</td>
		<td>'.$value['marca'].'</td>
		<td>'.$value['estado'].'</td>
		<td>'.$value['genero'].'</td>
		<td>'.$value['color'].'</td>
		<td>'.$value['criterio'].'</td>
		<td>'.$value['ASSETSUPNO'].'</td>
		<td>'.$value['ORIG_ASSET'].'</td>
		<td>'.$fechaC.'</td>
		<td>'.$value['ORIG_VALUE'].'</td>
		<td>'.$value['OBSERVACION'].'</td>		
		<td>'.utf8_decode($value['CARACTERISTICA']).'</td>
		<td>'.$value['IMAGEN'].'</td>
		<td>'.$value['ACTU_POR'].'</td>
		</tr>';
	  }
	  $salida.='</tbody>
       </table>';
      echo $salida;
    }


	function Reporte_sap_total1()
	{

	ini_set('memory_limit', '-1');
	set_time_limit(2048);
		//$tipoString = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;
		$ruta='';
		
			$query ='';
		
			$loc ='';
		
			$cus ='';
		

		$datos = $this->articulos->lista_articulos_sap_codigos($query,$loc,$cus,false,false,false,false,false);
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->getStyle('W')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
		$sheet->getStyle('I')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
		
		$sheet->setCellValue('A1','BUKRS');
		$sheet->setCellValue('B1','ANLN1');
		$sheet->setCellValue('C1','ANLN2');
		$sheet->setCellValue('D1','TXT50');
		$sheet->setCellValue('E1','TXTA50');
		$sheet->setCellValue('F1','ANLHTXT');		
		$sheet->setCellValue('G1','SERNR');		
		$sheet->setCellValue('H1','INVNR');
		$sheet->setCellValue('I1','IVDAT');
		$sheet->setCellValue('J1','MERGE');
		$sheet->setCellValue('K1','MEINS');
		$sheet->setCellValue('L1','STORT');
		$sheet->setCellValue('M1','KTEXT');
		$sheet->setCellValue('N1','PERNR');
		$sheet->setCellValue('O1','PERNP_TXT');
		$sheet->setCellValue('P1','ORD41');
		$sheet->setCellValue('Q1','ORD42');
		$sheet->setCellValue('R1','ORD43');
		$sheet->setCellValue('S1','ORD44');
		$sheet->setCellValue('T1','GDLGRP');
		$sheet->setCellValue('U1','ANLUE');
		$sheet->setCellValue('V1','AIBN1');
		$sheet->setCellValue('W1','AKTIV');
		$sheet->setCellValue('X1','URWRT');
		$sheet->setCellValue('Y1','');		
		$sheet->setCellValue('Z1','NOTE1');
		$sheet->setCellValue('AA1','IMAGEN');
		$sheet->setCellValue('AB1','ACTUALIZADO POR');
		$count = 2;

		foreach ($datos as $key => $value) {
			//print_r($value);die();
		// $fecha = $value['FECHA_INV_DATE']->format('Y-m-d');
		    $fecha='';
			if($value['FECHA_INV_DATE'] !='')
			{
				$fecha =$value['FECHA_INV_DATE']->format('Y-m-d');
				$fecha = new DateTime($fecha);
				$fecha = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($fecha);
			}
			$fechaC='';
			if($value['ORIG_ACQ_YR'] !='')
			{
				$fechaC =$value['ORIG_ACQ_YR']->format('Y-m-d'); 
				$fechaC = new DateTime($fechaC);
				$fechaC = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($fechaC);

			}


		$sheet->setCellValue('A'.$count,$value['COMPANYCODE']);
		$sheet->setCellValue('B'.$count,$value['TAG_SERIE']);
		$sheet->setCellValue('C'.$count,$value['SUBNUMBER']);
		$sheet->setCellValue('D'.$count,utf8_decode($value['DESCRIPT']));
		$sheet->setCellValue('E'.$count,$value['DESCRIPT2']);
		$sheet->setCellValue('F'.$count,$value['MODELO']);		
		$sheet->setCellValue('G'.$count,' '.$value['SERIE']);		
		$sheet->setCellValue('H'.$count,$value['TAG_UNIQUE']);
		$sheet->setCellValue('I'.$count,$fecha);
		$sheet->setCellValue('J'.$count,$value['QUANTITY']);
		$sheet->setCellValue('K'.$count,$value['BASE_UOM']);
		$sheet->setCellValue('L'.$count,$value['EMPLAZAMIENTO']);
		$sheet->setCellValue('M'.$count,utf8_encode($value['DENOMINACION']));
		$sheet->setCellValue('N'.$count,$value['PERSON_NO']);
		$sheet->setCellValue('O'.$count,utf8_decode($value['PERSON_NOM']));
		$sheet->setCellValue('P'.$count,$value['marca']);
		$sheet->setCellValue('Q'.$count,$value['estado']);
		$sheet->setCellValue('R'.$count,$value['genero']);
		$sheet->setCellValue('S'.$count,$value['color']);
		$sheet->setCellValue('T'.$count,$value['criterio']);
		$sheet->setCellValue('U'.$count,$value['ASSETSUPNO']);
		$sheet->setCellValue('V'.$count,$value['ORIG_ASSET']);
		$sheet->setCellValue('W'.$count,$fechaC);
		$sheet->setCellValue('X'.$count,$value['ORIG_VALUE']);
		$sheet->setCellValue('Y'.$count,$value['OBSERVACION']);		
		$sheet->setCellValue('Z'.$count,utf8_decode($value['CARACTERISTICA']));
		$sheet->setCellValue('AA'.$count,$value['IMAGEN']);
		$sheet->setCellValue('AB'.$count,$value['ACTU_POR']);
		$count = $count+1;
	  }


	    $write = new Xlsx($spreadsheet);
		$write->save('Reporte_activos.xlsx');
		echo "<meta http-equiv='refresh' content='0;url=Reporte_activos.xlsx'/>";
		exit;
		
		

	      // NOMBRE DEL ARCHIVO Y CHARSET
	      //header("Content-type: application/vnd.ms-excel");
         // header("Content-Disposition: attachment; filename=INVENTARIO.xls");
         // header("Pragma: no-cache");
          //header("Expires: 0");


          // $salida=fopen('php://output', 'w');

    }

	function Reporte_normal($query,$loc,$cus)
	{
		if($query == 'null')
		{
			$query ='';
		}
		if($loc == 'null')
		{
			$loc ='';
		}
		if($cus == 'null')
		{
			$cus ='';
		}

	  // NOMBRE DEL ARCHIVO Y CHARSET
	    // header("Content-type: application/vnd.ms-excel");
		header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=reporte_normal.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

          // $salida=fopen('php://output', 'w');

          $salida = '<table class="table table-striped">
	  <thead>
		<th>Tag</th>
		<th>Decripcion</th>
		<th>Modelo</th>
		<th>Serie</th>
		<th>Localizacion</th>
		<th>Custodio</th>
		<th>Marca</th>
		<th>Estado</th>
		<th>Genero</th>
		<th>Color</th>
		<th>Observacion</th>
		<th>Fecha inventario</th>
	  </thead>
	  <tbody>';
	  $datos =  $this->articulos->lista_articulos($query,$loc,$cus);
	  // print_r($datos);die();
	  foreach ($datos as $key => $value) {
		// $fecha = $value['FECHA_INV_DATE']->format('Y-m-d');
		$fecha='';
			if($value['fecha_in'] !='')
			{
				$fecha =$value['fecha_in']->format('Y-m-d'); 
			}

	  $salida.='<tr><td>'.$value['tag'].'</td>'.
	  '<td>'.$value['nom'].'</td>'.
	  '<td>'.$value['modelo'].'</td>'.
	  '<td>'.$value['serie'].'</td>'.
	  '<td>'.$value['localizacion'].'</td>'.
	  '<td>'.$value['custodio'].'</td>'.
	  '<td>'.$value['marca'].'</td>'.
	  '<td>'.$value['estado'].'</td>'.
	  '<td>'.$value['genero'].'</td>'.
	  '<td>'.$value['color'].'</td>'.
	  '<td>'.$value['OBSERVACION'].'</td>'.
	  '<td>'.$fecha.'</td>';
	  }
	  $salida.='</tbody>
       </table>';
      echo $salida;
    }

    function reporte_localizacion()
    {
    	$datos = $this->localizacion->lista_localizacion_todo();
    	//print_r($datos);die();
    	$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1','Centro');
		$sheet->setCellValue('B1','Emplazamiento');
		$sheet->setCellValue('C1','Denominacion');
		$sheet->setCellValue('D1','ESTADO');
		$count = 2;
		foreach ($datos as $key => $value) {
			$sheet->setCellValue('A'.$count,$value['CENTRO']);
		    $sheet->setCellValue('B'.$count,$value['EMPLAZAMIENTO']);
		    $sheet->setCellValue('C'.$count,$value['DENOMINACION']);
		    $sheet->setCellValue('D'.$count,$value['ESTADO']);
		    $count = $count+1;
		}
		 $write = new Xlsx($spreadsheet);
		 $write->save('Reporte_emplazamiento.xlsx');
		 echo "<meta http-equiv='refresh' content='0;url=Reporte_emplazamiento.xlsx'/>";
		 exit;
		


    }

     function reporte_marca()
    {
    	$datos = $this->marcas->lista_marcas_todo();
    	//print_r($datos);die();
    	$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1','CODIGO SAP');
		$sheet->setCellValue('B1','DESCRIPCION SAP');
		$sheet->setCellValue('C1','ESTADO');
		$count = 2;
		foreach ($datos as $key => $value) {
			$sheet->setCellValue('A'.$count,$value['CODIGO']);
		    $sheet->setCellValue('B'.$count,$value['DESCRIPCION']);
		    $sheet->setCellValue('C'.$count,$value['ESTADO']);
		    $count = $count+1;
		}
		 $write = new Xlsx($spreadsheet);
		 $write->save('Reporte_MARCAS.xlsx');
		 echo "<meta http-equiv='refresh' content='0;url=Reporte_MARCAS.xlsx'/>";
		 exit;
		


    }

     function reporte_custodio()
    {
    	$datos = $this->custodio->buscar_custodio_todo();
    	//print_r($datos);die();
    	$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->getColumnDimension('B')->setAutoSize(true);
		$sheet->setCellValue('A1','ID Externo del Personal');
		$sheet->setCellValue('B1','Numero de Identificacion');
		$sheet->setCellValue('C1','Apellidos y Nombres');
		$sheet->setCellValue('D1','Codigo de Puesto (Label)');
		$sheet->setCellValue('E1','Unidad Organizacional (Label)');
		$sheet->setCellValue('F1','Correo Electronico');
		$sheet->setCellValue('G1','ESTADO');
		$count = 2;
		foreach ($datos as $key => $value) {
			$sheet->setCellValue('A'.$count,$value['PERSON_NO']);
		    $sheet->setCellValue('B'.$count,$value['PERSON_CI']);
		    $sheet->setCellValue('C'.$count,$value['PERSON_NOM']);
		    $sheet->setCellValue('D'.$count,$value['PUESTO']);
		    $sheet->setCellValue('E'.$count,$value['UNIDAD_ORG']);
		    $sheet->setCellValue('F'.$count,$value['PERSON_CORREO']);
		    $sheet->setCellValue('G'.$count,$value['ESTADO']);
		    $count = $count+1;
		}
		 $write = new Xlsx($spreadsheet);
		 $write->save('Reporte_CUSTODIO.xlsx');
		 echo "<meta http-equiv='refresh' content='0;url=Reporte_CUSTODIO.xlsx'/>";
		 exit;
		


    }

     function reporte_proyecto()
    {
    	$datos = $this->proyectos->lista_proyectos_todo();
    	//print_r($datos);die();
    	$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->getStyle('E')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
		$sheet->getStyle('F')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
		$sheet->getStyle('G')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
		$sheet->getColumnDimension('B')->setAutoSize(true);
		$sheet->setCellValue('A1','Programa de financiación');
		$sheet->setCellValue('B1','Entidad CP');
		$sheet->setCellValue('C1','Denominación');
		$sheet->setCellValue('D1','Descripción');
		$sheet->setCellValue('E1','Validez de');
		$sheet->setCellValue('F1','Validez a');
		$sheet->setCellValue('G1','Fecha de expiración');
		$sheet->setCellValue('H1','ESTADO');
		$count = 2;
		foreach ($datos as $key => $value) {
			$sheet->setCellValue('A'.$count,$value['pro']);
		    $sheet->setCellValue('B'.$count,$value['enti']);
		    $sheet->setCellValue('C'.$count,$value['deno']);
		    $sheet->setCellValue('D'.$count,$value['desc']);
		    $sheet->setCellValue('E'.$count,$value['valde']);
		    $sheet->setCellValue('F'.$count,$value['vala']);
		    $sheet->setCellValue('G'.$count,$value['exp']);
		    $sheet->setCellValue('H'.$count,$value['ESTADO']);
		    $count = $count+1;
		}
		 $write = new Xlsx($spreadsheet);
		 $write->save('Reporte_proyecto.xlsx');
		 echo "<meta http-equiv='refresh' content='0;url=Reporte_proyecto.xlsx'/>";
		 exit;
		


    }

     function reporte_estado()
    {
    	$datos = $this->estado->lista_estado_todo();
    	//print_r($datos);die();
    	$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->getColumnDimension('B')->setAutoSize(true);
		$sheet->setCellValue('A1','CODIGO');
		$sheet->setCellValue('B1','DESCRIPCION');
		$sheet->setCellValue('C1','ESTADO');
		$count = 2;
		foreach ($datos as $key => $value) {
			$sheet->setCellValue('A'.$count,$value['CODIGO']);
		    $sheet->setCellValue('B'.$count,$value['DESCRIPCION']);
		    $sheet->setCellValue('C'.$count,$value['ESTADO']);
		    $count = $count+1;
		}
		 $write = new Xlsx($spreadsheet);
		 $write->save('Reporte_estado.xlsx');
		 echo "<meta http-equiv='refresh' content='0;url=Reporte_estado.xlsx'/>";
		 exit;
		


    }

    function reporte_clase_movimientos()
    {
    	$datos = $this->mov->lista_clase_movimiento_todo();
    	//print_r($datos);die();
    	$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1','CODIGO');
		$sheet->setCellValue('B1','DESCRIPCION');
		$count = 2;
		foreach ($datos as $key => $value) {
			$sheet->setCellValue('A'.$count,$value['CODIGO']);
		    $sheet->setCellValue('B'.$count,$value['DESCRIPCION']);
		    $count = $count+1;
		}
		 $write = new Xlsx($spreadsheet);
		 $write->save('Reporte_clase_movimiento.xlsx');
		 echo "<meta http-equiv='refresh' content='0;url=Reporte_clase_movimiento.xlsx'/>";
		 exit;
    }


	function reporte_cambios($parametros)
	{
		// print_r($parametros);die();	

	  // NOMBRE DEL ARCHIVO Y CHARSET
	    // header("Content-type: application/vnd.ms-excel");
		header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=reporte_cambios.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

          // $salida=fopen('php://output', 'w');
    $desde = false;$hasta = false;
    if(isset($parametros['desde'])){ $desde = $parametros['desde'];}
    if(isset($parametros['hasta'])){ $hasta = $parametros['hasta'];}


    $salida = '<table class="table table-striped">
	  <thead>
	  <th>ASSET</th>
		<th>Activo / Descripcion</th>
		<th>FECHA</th>
		<th>Dato Anterior</th>
		<th>Cod Anterior</th>
		<th>Dato nuevo</th>
		<th>Cod Nuevo</th>
		<th>Responsable</th>
		<th>Observacion</th>'; 
	  $datos =  $this->articulos->cambios($desde,$hasta);
	  // print_r($datos);die();
	  foreach ($datos as $key => $value) {
		// $fecha = $value['FECHA_INV_DATE']->format('Y-m-d');
		$fecha='';
			if($value['fecha_movimiento'] !='')
			{
				$fecha =$value['fecha_movimiento']->format('Y-m-d'); 
			}

	  $salida.='<tr>
	  <td>'.$value['TAG_SERIE'].'</td>
	  <td>'.$value['DESCRIPT'].'</td>'.
	  '<td>'.$fecha.'</td>'.
	  '<td>'.$value['dato_anterior'].'</td>'.
	  '<td>'.$value['codigo_ant'].'</td>'.
	  '<td>'.$value['dato_nuevo'].'</td>'.
	  '<td>'.$value['codigo_nue'].'</td>'.
	  '<td>'.$value['responsable'].'</td>'.
	  '<td>'.$value['obs_movimiento'].'</td>';
	  }
	  $salida.='</tbody>
       </table>';
      echo $salida;
    }



    function reporte_movi($parametros)
    {
    	$datos = $this->detalle_art->movimientos($parametros['id'],$parametros['desde'],$parametros['hasta']);
    	// print_r($datos);die();
    	$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1','MOVIMIENTO');
		$sheet->setCellValue('B1','FECHA');
		$sheet->setCellValue('C1','COD ANTERIOR');
		$sheet->setCellValue('D1','DATO ANTERIOR');
		$sheet->setCellValue('E1','COD NUEVO');
		$sheet->setCellValue('F1','DATO NUEVO');
		$sheet->setCellValue('G1','RESPONSABLE');
		$count = 2;
		foreach ($datos as $key => $value) {
			$sheet->setCellValue('A'.$count,$value['ob']);
		  $sheet->setCellValue('B'.$count,$value['fe']->format('Y-m-d'));
		  $sheet->setCellValue('C'.$count,$value['codigo_ant']);
		  $sheet->setCellValue('D'.$count,$value['dante']);
		  $sheet->setCellValue('E'.$count,$value['codigo_nue']);
		  $sheet->setCellValue('F'.$count,$value['dnuevo']);
		  $sheet->setCellValue('G'.$count,$value['responsable']);
		    $count = $count+1;
		}
		 $write = new Xlsx($spreadsheet);
		 $write->save('Reporte_movimiento.xlsx');
		 echo "<meta http-equiv='refresh' content='0;url=Reporte_movimiento.xlsx'/>";
		 exit;
    }

     function reporte_color()
    {
    	$datos = $this->colores->lista_colores_todo();
    	//print_r($datos);die();
    	$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1','CODIGO');
		$sheet->setCellValue('B1','DESCRIPCION');
		$sheet->setCellValue('C1','ESTADO');
		$count = 2;
		foreach ($datos as $key => $value) {
			$sheet->setCellValue('A'.$count,$value['CODIGO']);
		    $sheet->setCellValue('B'.$count,$value['DESCRIPCION']);
		    $sheet->setCellValue('C'.$count,$value['ESTADO']);
		    $count = $count+1;
		}
		 $write = new Xlsx($spreadsheet);
		 $write->save('Reporte_color.xlsx');
		 echo "<meta http-equiv='refresh' content='0;url=Reporte_color.xlsx'/>";
		 exit;
		


    }

     function reporte_genero()
    {
    	$datos = $this->genero->lista_genero_todo();
    	//print_r($datos);die();
    	$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1','CODIGO');
		$sheet->setCellValue('B1','DESCRIPCION');
		$sheet->setCellValue('C1','ESTADO');
		$count = 2;
		foreach ($datos as $key => $value) {
			$sheet->setCellValue('A'.$count,$value['CODIGO']);
		    $sheet->setCellValue('B'.$count,$value['DESCRIPCION']);
		    $sheet->setCellValue('C'.$count,$value['ESTADO']);
		    $count = $count+1;
		}
		 $write = new Xlsx($spreadsheet);
		 $write->save('Reporte_genero.xlsx');
		 echo "<meta http-equiv='refresh' content='0;url=Reporte_genero.xlsx'/>";
		 exit;
    }

/*
  function Reporte_sap_total_bajas(){

			$query ='';		
			$loc ='';		
			$cus ='';

	  // NOMBRE DEL ARCHIVO Y CHARSET
	    header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=reporte_total_bajas.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

          // $salida=fopen('php://output', 'w');

    $salida = '<table class="table table-striped" border="1">
	  <thead>
		<th>BUKRS</th>
		<th>ANLN1</th>
		<th>ANLN2</th>
		<th>TXT50</th>
		<th>TXTA50</th>
		<th>ANLHTXT</th>		
		<th>SERNR</th>		
		<th> INVNR</th>
		<th>IVDAT</th>
		<th>MERGE</th>
		<th>MEINS</th>
		<th>STORT</th>
		<th>KTEXT</th>
		<th>PERNR</th>
		<th>PERNP_TXT</th>
		<th>ORD41</th>
		<th>ORD42</th>
		<th>ORD43</th>
		<th>ORD44</th>
		<th>GDLGRP</th>
		<th>ANLUE</th>
		<th>AIBN1</th>
		<th>AKTIV</th>
		<th>URWRT</th>
		<th></th>
		<th>BAJAS</th>
		<th>NOTE1</th>
		<th>IMAGEN</th>
		<th>ACTUALIZADO POR</th>
		<th>BLDAT</th>
		<th>BUDAT</th>
		<th>BZDAT</th>
		<th>MONAT</th>
		<th>BWASL</th>
	  </thead>
	  <tbody>
	  <tr>
	  <td>COMPANYCODE/SOCIEDAD</td>
	  <td>ASSET / ACTIVO FIJO PRINCIPAL</td>
	  <td>SUBNUMERO ACTIVO FIJO</td>
	  <td>DESCRIPCION</td>
	  <td>DESCRIPCION 2</td>
	  <td>MODELO</td>
	  <td>SERIE</td>
	  <td>RFID</td>
	  <td>FECHA ULTIMO INVENTARIO</td>
	  <td>CANTIDAD</td>
	  <td>UNIDAD DE MEDIDA</td>
	  <td>EMPLAZAMIENTO CODIGO</td>
	  <td>EMPLAZAMIENTO</td>
	  <td>CUSTODIO CODIGO</td>
	  <td>CUSTODIO</td>
	  <td>MARCA</td>
	  <td>ESTADO</td>
	  <td>GENERO</td>
	  <td>COLORES</td>
	  <td>PROYECTO</td>
	  <td>SUPRA NUMERO</td>
	  <td>TAG ANTIGUO</td>
	  <td>FECHA COMPRA</td>
	  <td>VALOR ACTUAL</td>
	  <td>OBSEVACIONES</td>
	  <td>BAJAS</td>
	  <td>NOTE1</td>
	  <td>IMAGEN</td>
	  <td>ACTUALIZADO POR</td>
	  <td>FECHA BAJA</td>
	  <td>FECHA CONTABILIZACION</td>
	  <td>FECHA REFERENCIA</td>
	  <td>PERIODO</td>
	  <td>CLASE MOVIMIENTO</td>
	  </tr>';
	  $datos = $this->articulos->lista_articulos_sap_codigos($query,$loc,$cus,false,false,false,false,false,1,false,false);
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

		 $salida.='<tr>
		<td>'.$value['COMPANYCODE'].'</td>
		<td>'.$value['TAG_SERIE'].'</td>
		<td>'.$value['SUBNUMBER'].'</td>
		<td>'.utf8_decode($value['DESCRIPT']).'</td>
		<td>'.$value['DESCRIPT2'].'</td>
		<td>'.$value['MODELO'].'</td>		
		<td>'.' '.$value['SERIE'].'</td>		
		<td>'.$value['TAG_UNIQUE'].'&nbsp; </td>
		<td>'.$fecha.'</td>
		<td>'.$value['QUANTITY'].'</td>
		<td>'.$value['BASE_UOM'].'</td>
		<td>'.$value['EMPLAZAMIENTO'].'</td>
		<td>'.utf8_encode($value['DENOMINACION']).'</td>
		<td>'.$value['PERSON_NO'].'</td>
		<td>'.utf8_decode($value['PERSON_NOM']).'</td>
		<td>'.$value['marca'].'</td>
		<td>'.$value['estado'].'</td>
		<td>'.$value['genero'].'</td>
		<td>'.$value['color'].'</td>
		<td>'.$value['criterio'].'</td>
		<td>'.$value['ASSETSUPNO'].'</td>
		<td>'.$value['ORIG_ASSET'].'</td>
		<td>'.$fechaC.'</td>
		<td>'.$value['ORIG_VALUE'].'</td>
		<td>'.$value['OBSERVACION'].'</td>		
		<td>'.$value['BAJAS'].'</td>
		<td>'.utf8_decode($value['CARACTERISTICA']).'</td>
		<td>'.$value['IMAGEN'].'</td>
		<td>'.$value['ACTU_POR'].'</td>
		<td>'.$fechaB.'</td>
		<td>'.$fechaCON.'</td>
		<td>'.$fechaREF.'</td>
		<td>'.$value['PERIODO'].'</td>
		<td>'.$value['CLASE_MOVIMIENTO'].'</td>
		</tr>';
	  }
	  $salida.='</tbody>
       </table>';
      echo $salida;
    }
*/
     function reporte_log($parametros){

			

	  // NOMBRE DEL ARCHIVO Y CHARSET
	    header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=reporte_log_activos.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

          // $salida=fopen('php://output', 'w');

    $fecha1 = date('Y-m-d');
    $salida = '<table class="table table-striped" border="1">
	  <thead>
		<th>DETALLE</th>
		<th>FECHA</th>
		<th>INTENTO</th>
		<th>ACCION</th>
		<th>ESTADO</th>		
		<th>ENCARGADO</th>		
	  </thead>
	  <tbody>';
	  $datos = $this->carga_datos->log_activo($parametros['txt_fecha'],$parametros['txt_intento'],$parametros['txt_accion'],$parametros['rbl_estado']);
	  // print_r($datos);die();
	  foreach ($datos as $key => $value) {
		// $fecha = $value['FECHA_INV_DATE']->format('Y-m-d');
		  $fecha='';
			if($value['fecha'] !='')
			{
				$fecha =$value['fecha']->format('Y-m-d');
			}		

		 $salida.='<tr>
		<td>'.$value['detalle'].'</td>		
		<td>'.$fecha.'</td>
		<td>'.$value['intento'].'</td>	
		<td>'.$value['accion'].'</td>	
		<td>'.$value['estado'].'</td>	
		<td>'.$value['usuario'].'</td>	
		</tr>';
	  }
	  $salida.='</tbody>
       </table>';
      echo $salida;
    }



 


}
?>
