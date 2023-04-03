<?php 
include('pdf/cabecera_pdf.php');
include('../modelo/ArticulosM.php');
/**
 * 
 */
$reporte = new Reporte_pdf();
if(isset($_GET['reporte_pdf']))
{
	$reporte->reporte_normal($_GET['query'],$_GET['loc'],$_GET['cus'],$_GET['desde'],$_GET['hasta']);
}
if(isset($_GET['reporte_pdf_total']))
{
	$reporte->reporte_normal_total();
}
if(isset($_GET['reporte_pdf_bajas']))
{
	$reporte->reporte_normal_bajas();
}
if(isset($_GET['reporte_pdf_terceros']))
{
	$reporte->reporte_normal_terceros();
}
if(isset($_GET['reporte_pdf_patrimoniales']))
{
	$reporte->reporte_normal_patrimoniales();
}

if(isset($_GET['reporte_pdf_sap']))
{
	$reporte->reporte_sap($_GET['query'],$_GET['loc'],$_GET['cus']);
}
if(isset($_GET['reporte_pdf_sap_bajas']))
{
	$reporte->reporte_sap_bajas();
}
if(isset($_GET['codigo_qr']))
{
	$reporte->codigo_qr($_GET['id']);
}

if(isset($_GET['reporte_cedula']))
{
	$parametros = $_GET;
	$reporte->reporte_cedula($parametros);
	// $reporte-> ejemplo();
}


class Reporte_pdf
{
	private $pdf;
	function __construct()
	{
		$this->pdf = new cabecera_pdf();
		$this->articulo = new ArticulosM();
	}

	function reporte_cedula($parametros)
	{
		$sizetable = 8;
		$titulo="Reporte inventario";

		$activos = $this->articulo->lista_articulos($query=false,$loc=false,$cus=false,$pag=false,$parametros['id'],$exacto=false,$asset=false,$bajas=false,$terceros=false,$patrimoniales=false,$desde=false,$hasta=false,$multiple=false);

		// print_r($activos);die();

		$image[0]['url'] = '../img/de_sistema/puce_logo.png';
		$image[0]['x'] = 10;
		$image[0]['y'] = 5;
		$image[0]['width'] = 30;
		$image[0]['height'] = 30;		

		$tablaHTML = array();
		$pos = 0;
		$tablaHTML[$pos]['medidas']=array(190);
		$tablaHTML[$pos]['alineado']=array('C');
		$tablaHTML[$pos]['datos']=array('PONTIFICIA UNIVERSIDAD CATOLICA DEL ECUADOR');
		$tablaHTML[$pos]['estilo']='BI';
		$pos+=1;

		$tablaHTML[$pos]['medidas']=array(190);
		$tablaHTML[$pos]['alineado']=array('C');
		$tablaHTML[$pos]['datos']=array('DIRECCION GENERAL FINANCIERA');
		$tablaHTML[$pos]['estilo']='BI';
		$pos+=1;

		$tablaHTML[$pos]['medidas']=array(190);
		$tablaHTML[$pos]['alineado']=array('C');
		$tablaHTML[$pos]['datos']=array('DIRECCION DE CONTROL DE ACTIVOS');
		$tablaHTML[$pos]['estilo']='BI';
		$pos+=1;

		$tablaHTML[$pos]['medidas']=array(190);
		$tablaHTML[$pos]['alineado']=array('C');
		$tablaHTML[$pos]['datos']=array('CEDULA DE INVENTARIOS');
		$tablaHTML[$pos]['estilo']='BI';
		$pos+=1;

		$tablaHTML[$pos]['medidas']=array(100,90);
		$tablaHTML[$pos]['alineado']=array('L','R');
		$tablaHTML[$pos]['datos']=array('',date('Y-m-d'));
		$tablaHTML[$pos]['estilo']='B';
		$pos+=1;

		$tablaHTML[$pos]['medidas']=array(30,30,130);
		$tablaHTML[$pos]['alineado']=array('L','L','L');
		$tablaHTML[$pos]['datos']=array('<b>Codigo de activo',$activos[0]['tag'],$activos[0]['nom']);
		$tablaHTML[$pos]['estilo']='';
		$tablaHTML[$pos]['borde']='T';
		$pos+=1;

		$tablaHTML[$pos]['medidas']=array(30,25,30,50,30,25);
		$tablaHTML[$pos]['alineado']=array('L','L','L','L','L','L');
		$tablaHTML[$pos]['datos']=array('<b>Secuencial:','','<b>Numero Etiqueta:','','<b>Estado:',$activos[0]['estado']);
		$tablaHTML[$pos]['estilo']='';
		// $tablaHTML[$pos]['borde']='1';
		$pos+=1;


		$tablaHTML[$pos]['medidas']=array(30,15,90,30,25);
		$tablaHTML[$pos]['alineado']=array('L','L','L','L','L');
		$tablaHTML[$pos]['datos']=array('<b>Categoria:','','','<b>Fecha Compra:',$activos[0]['ORIG_ACQ_YR']->format('Y-m-d'));
		$tablaHTML[$pos]['estilo']='';		
		// $tablaHTML[$pos]['borde']='1';
		$pos+=1;

		$tablaHTML[$pos]['medidas']=array(30,15,90,30,25);
		$tablaHTML[$pos]['alineado']=array('L','L','L','L','L');
		$tablaHTML[$pos]['datos']=array('<b>SubCategoria:','','','<b>Fecha servicio:','');
		$tablaHTML[$pos]['estilo']='';		
		// $tablaHTML[$pos]['borde']='1';
		$pos+=1;

		$tablaHTML[$pos]['medidas']=array(35,100,30,25);
		$tablaHTML[$pos]['alineado']=array('L','L','L','L');
		$tablaHTML[$pos]['datos']=array('<b>Cod. Grupo Activos:','','<b>Periodo contable:','');
		$tablaHTML[$pos]['estilo']='';		
		// $tablaHTML[$pos]['borde']='1';
		$pos+=1;

		$propi = 'PROPIO';
		if($activos[0]['TERCEROS']==1)
		{
			$propi = 'TERCEROS';
		}
		if($activos[0]['PATRIMONIALES']==1)
		{
			$propi = 'PATRIMONIALES';
		}


		$tablaHTML[$pos]['medidas']=array(30,105,25,30);
		$tablaHTML[$pos]['alineado']=array('L','L','L','L');
		$tablaHTML[$pos]['datos']=array('<b>Propietario:',$propi,'<b>Año:','');
		$tablaHTML[$pos]['estilo']='';
		// $tablaHTML[$pos]['borde']='1';
		$pos+=1;


		$tablaHTML[$pos]['medidas']=array(190);
		$tablaHTML[$pos]['alineado']=array('L');
		$tablaHTML[$pos]['datos']=array('');
		$tablaHTML[$pos]['estilo']='';
		$tablaHTML[$pos]['borde']='TB';
		$pos+=1;

		$tablaHTML[$pos]['medidas']=array(30,30,25,30,20,55);
		$tablaHTML[$pos]['alineado']=array('L','L','L','L','L','L');
		$tablaHTML[$pos]['datos']=array('<b>Marca:',$activos[0]['marca'],'<b>Modelo',$activos[0]['MODELO'],'<b>Serie',$activos[0]['serie']);
		$tablaHTML[$pos]['estilo']='';
		// $tablaHTML[$pos]['borde']='1';
		$pos+=1;

		$tablaHTML[$pos]['medidas']=array(30,30,25,30,20,55);
		$tablaHTML[$pos]['alineado']=array('L','L','L','L','L','L');
		$tablaHTML[$pos]['datos']=array('<b>Color:',$activos[0]['color'],'<b>Adjetivo',$activos[0]['genero'],'<b>Unidad', $activos[0]['localizacion']);
		$tablaHTML[$pos]['estilo']='';
		// $tablaHTML[$pos]['borde']='1';
		$pos+=1;

		$tablaHTML[$pos]['medidas']=array(30,30,25,30,20,55);
		$tablaHTML[$pos]['alineado']=array('L','L','L','L','L','L');
		$tablaHTML[$pos]['datos']=array('<b>Factura:','','<b>vida Activo:','','<b>Oficina','');
		$tablaHTML[$pos]['estilo']='';
		// $tablaHTML[$pos]['borde']='1';
		$pos+=1;

		$tablaHTML[$pos]['medidas']=array(30,30,25,30,20,55);
		$tablaHTML[$pos]['alineado']=array('L','L','L','L','L','L');
		$tablaHTML[$pos]['datos']=array('<b>Costo Original:','$'.$activos[0]['ORIG_VALUE'],'','','<b>Custodio',$activos[0]['custodio']);
		$tablaHTML[$pos]['estilo']='';
		// $tablaHTML[$pos]['borde']='1';
		$pos+=1;


		$tablaHTML[$pos]['medidas']=array(190);
		$tablaHTML[$pos]['alineado']=array('L');
		$tablaHTML[$pos]['datos']=array('');
		$tablaHTML[$pos]['borde']='T';
		$pos+=1;
		$tablaHTML[$pos]['medidas']=array(190);
		$tablaHTML[$pos]['alineado']=array('L');
		$tablaHTML[$pos]['datos']=array('');
		// $tablaHTML[$pos]['borde']='TB';
		$pos+=1;

		$tablaHTML[$pos]['medidas']=array(190);
		$tablaHTML[$pos]['alineado']=array('L');
		$tablaHTML[$pos]['datos']=array('<b>Anexos:');
		$pos+=1;
		$tablaHTML[$pos]['medidas']=array(190);
		$tablaHTML[$pos]['alineado']=array('L');
		$tablaHTML[$pos]['datos']=array('');
		$pos+=1;

		$tablaHTML[$pos]['medidas']=array(190);
		$tablaHTML[$pos]['alineado']=array('L');
		$tablaHTML[$pos]['datos']=array('<b>Caracteristicas:');
		$pos+=1;
		$tablaHTML[$pos]['medidas']=array(190);
		$tablaHTML[$pos]['alineado']=array('L');
		$tablaHTML[$pos]['datos']=array($activos[0]['CARACTERISTICA']);
		$pos+=1;

		$tablaHTML[$pos]['medidas']=array(190);
		$tablaHTML[$pos]['alineado']=array('L');
		$tablaHTML[$pos]['datos']=array('<b>Trayectoria:');
		$pos+=1;
		$tablaHTML[$pos]['medidas']=array(190);
		$tablaHTML[$pos]['alineado']=array('L');
		$tablaHTML[$pos]['datos']=array('');
		$pos+=1;

		$tablaHTML[$pos]['medidas']=array(40,40,35,40,35);
		$tablaHTML[$pos]['alineado']=array('C','C','C','C','C');
		$tablaHTML[$pos]['datos']=array('<b>Ultima depreciacion:','<b>Costo Original','<b>Depreciacion','<b>Depreciacion Acumulada','<b>Saldo en libro');
		$tablaHTML[$pos]['estilo']='';
		$tablaHTML[$pos]['borde']='1';
		$pos+=1;

		$contabilidad = '';
		if($activos[0]['FECHA_CONTA']!='' && $activos[0]['FECHA_CONTA']!=null){$contabilidad = $activos[0]['FECHA_CONTA']->format('Y-m-d');}
		$tablaHTML[$pos]['medidas']=array(40,40,35,40,35);
		$tablaHTML[$pos]['alineado']=array('C','C','C','C','C');
		$tablaHTML[$pos]['datos']=array($contabilidad,'$'.$activos[0]['ORIG_VALUE'],'','','');
		$tablaHTML[$pos]['estilo']='';
		$tablaHTML[$pos]['borde']='1';
		$pos+=1;






		
		// $datos = $this->articulo->lista_articulos($query,$loc,$cus,$pag=false,$whereid=false,$exacto=false,$asset=false,$bajas=false,$terceros=false,$patrimoniales=false,$desde,$hasta);

		// foreach ($datos as $key => $value) {
		// 	$fecha='';
		// 	if($value['fecha_in'] !='')
		// 	{
		// 		$fecha =$value['fecha_in']->format('Y-m-d'); 
		// 	}
		// 	$tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		//     $tablaHTML[$pos]['alineado']=$tablaHTML[0]['alineado'];
		//     $tablaHTML[$pos]['datos']=array($value['tag'],$value['nom'],$value['modelo'],$value['serie'],$value['localizacion'],$value['custodio'],$value['marca'],$value['estado'],$value['genero'],$value['color'],$value['OBSERVACION'],$fecha);
		//     $tablaHTML[$pos]['estilo']='I';
		//     $tablaHTML[$pos]['borde'] = 'T';  
		//     $pos+=1;  
		// }		

		$this->pdf->cedula_reporte($titulo,$tablaHTML,$contenido=false,$image,'fecha','fecha',$sizetable,true,$sal_hea_body=30);


	}

	function reporte_normal($query,$loc,$cus,$desde,$hasta)
	{
		$sizetable = 8;
		$titulo="Reporte inventario";
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
		$pos=1;

		$tablaHTML = array();
		$tablaHTML[0]['medidas']=array(20,49,22,20,28,28,23,16,16,16,20,20);
		$tablaHTML[0]['alineado']=array('L','L','L','L','L','L','L','L','L','L','L');
		$tablaHTML[0]['datos']=array('Tag','Decripcion','Modelo','Serie','Localizacion','Custodio','Marca','Estado','Genero','Color','Observacion','Fecha inventario');
		$tablaHTML[0]['estilo']='BI';
		$tablaHTML[0]['borde'] = '1';
		$datos = $this->articulo->lista_articulos($query,$loc,$cus,$pag=false,$whereid=false,$exacto=false,$asset=false,$bajas=false,$terceros=false,$patrimoniales=false,$desde,$hasta);

		foreach ($datos as $key => $value) {
			$fecha='';
			if($value['fecha_in'] !='')
			{
				$fecha =$value['fecha_in']->format('Y-m-d'); 
			}
			$tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		    $tablaHTML[$pos]['alineado']=$tablaHTML[0]['alineado'];
		    $tablaHTML[$pos]['datos']=array($value['tag'],$value['nom'],$value['modelo'],$value['serie'],$value['localizacion'],$value['custodio'],$value['marca'],$value['estado'],$value['genero'],$value['color'],$value['OBSERVACION'],$fecha);
		    $tablaHTML[$pos]['estilo']='I';
		    $tablaHTML[$pos]['borde'] = 'T';  
		    $pos+=1;  
		}		

		$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,'fecha','fecha',$sizetable,true,$sal_hea_body=30,$orientacion='H');


	}
	function reporte_normal_total()
	{
		$sizetable = 8;
		$titulo="R E P O R T E   D E   B A J A S";		
		$pos=1;

		$tablaHTML = array();
		$tablaHTML[0]['medidas']=array(20,49,22,20,28,28,23,16,16,16,20,20);
		$tablaHTML[0]['alineado']=array('L','L','L','L','L','L','L','L','L','L','L');
		$tablaHTML[0]['datos']=array('Tag','Decripcion','Modelo','Serie','Localizacion','Custodio','Marca','Estado','Genero','Color','Observacion','Fecha inventario');
		$tablaHTML[0]['estilo']='BI';
		$tablaHTML[0]['borde'] = '1';
		$datos = $this->articulo->lista_articulos();

		foreach ($datos as $key => $value) {
			$fecha='';
			if($value['fecha_in'] !='')
			{
				$fecha =$value['fecha_in']->format('Y-m-d'); 
			}
			$tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		    $tablaHTML[$pos]['alineado']=$tablaHTML[0]['alineado'];
		    $tablaHTML[$pos]['datos']=array($value['tag'],$value['nom'],$value['modelo'],$value['serie'],$value['localizacion'],$value['custodio'],$value['marca'],$value['estado'],$value['genero'],$value['color'],$value['OBSERVACION'],$fecha);
		    $tablaHTML[$pos]['estilo']='I';
		    $tablaHTML[$pos]['borde'] = 'T';  
		    $pos+=1;  
		}		

		$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,'fecha','fecha',$sizetable,true,$sal_hea_body=30,$orientacion='H');


	}

	function reporte_normal_bajas()
	{
		$sizetable = 8;
		$titulo="R E P O R T E   D E   B A J A S";		
		$pos=1;

		$tablaHTML = array();
		$tablaHTML[0]['medidas']=array(20,49,22,20,28,28,23,16,16,16,20,20);
		$tablaHTML[0]['alineado']=array('L','L','L','L','L','L','L','L','L','L','L');
		$tablaHTML[0]['datos']=array('Tag','Decripcion','Modelo','Serie','Localizacion','Custodio','Marca','Estado','Genero','Color','Observacion','Fecha inventario');
		$tablaHTML[0]['estilo']='BI';
		$tablaHTML[0]['borde'] = '1';
		$datos = $this->articulo->lista_articulos($query=false,$loc=false,$cus=false,$pag=false,$whereid=false,$exacto=false,$asset=false,$bajas=1,$terceros=false,$patrimoniales=false);

		foreach ($datos as $key => $value) {
			$fecha='';
			if($value['fecha_in'] !='')
			{
				$fecha =$value['fecha_in']->format('Y-m-d'); 
			}
			$tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		    $tablaHTML[$pos]['alineado']=$tablaHTML[0]['alineado'];
		    $tablaHTML[$pos]['datos']=array($value['tag'],$value['nom'],$value['modelo'],$value['serie'],$value['localizacion'],$value['custodio'],$value['marca'],$value['estado'],$value['genero'],$value['color'],$value['OBSERVACION'],$fecha);
		    $tablaHTML[$pos]['estilo']='I';
		    $tablaHTML[$pos]['borde'] = 'T';  
		    $pos+=1;  
		}		

		$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,'fecha','fecha',$sizetable,true,$sal_hea_body=30,$orientacion='H');


	}
	function reporte_normal_terceros()
	{
		$sizetable = 8;
		$titulo="R E P O R T E   D E   T E R C E R O S ";		
		$pos=1;

		$tablaHTML = array();
		$tablaHTML[0]['medidas']=array(20,49,22,20,28,28,23,16,16,16,20,20);
		$tablaHTML[0]['alineado']=array('L','L','L','L','L','L','L','L','L','L','L');
		$tablaHTML[0]['datos']=array('Tag','Decripcion','Modelo','Serie','Localizacion','Custodio','Marca','Estado','Genero','Color','Observacion','Fecha inventario');
		$tablaHTML[0]['estilo']='BI';
		$tablaHTML[0]['borde'] = '1';
		$datos = $this->articulo->lista_articulos($query=false,$loc=false,$cus=false,$pag=false,$whereid=false,$exacto=false,$asset=false,$bajas=false,$terceros=1,$patrimoniales=false);

		foreach ($datos as $key => $value) {
			$fecha='';
			if($value['fecha_in'] !='')
			{
				$fecha =$value['fecha_in']->format('Y-m-d'); 
			}
			$tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		    $tablaHTML[$pos]['alineado']=$tablaHTML[0]['alineado'];
		    $tablaHTML[$pos]['datos']=array($value['tag'],$value['nom'],$value['modelo'],$value['serie'],$value['localizacion'],$value['custodio'],$value['marca'],$value['estado'],$value['genero'],$value['color'],$value['OBSERVACION'],$fecha);
		    $tablaHTML[$pos]['estilo']='I';
		    $tablaHTML[$pos]['borde'] = 'T';  
		    $pos+=1;  
		}		

		$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,'fecha','fecha',$sizetable,true,$sal_hea_body=30,$orientacion='H');


	}
	function reporte_normal_patrimoniales()
	{
		$sizetable = 8;
		$titulo="R E P O S T E   D E   P A T R I M O N I A L E S";		
		$pos=1;

		$tablaHTML = array();
		$tablaHTML[0]['medidas']=array(20,49,22,20,28,28,23,16,16,16,20,20);
		$tablaHTML[0]['alineado']=array('L','L','L','L','L','L','L','L','L','L','L');
		$tablaHTML[0]['datos']=array('Tag','Decripcion','Modelo','Serie','Localizacion','Custodio','Marca','Estado','Genero','Color','Observacion','Fecha inventario');
		$tablaHTML[0]['estilo']='BI';
		$tablaHTML[0]['borde'] = '1';
		$datos = $this->articulo->lista_articulos($query=false,$loc=false,$cus=false,$pag=false,$whereid=false,$exacto=false,$asset=false,$bajas=false,$terceros=false,$patrimoniales=1);

		foreach ($datos as $key => $value) {
			$fecha='';
			if($value['fecha_in'] !='')
			{
				$fecha =$value['fecha_in']->format('Y-m-d'); 
			}
			$tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		    $tablaHTML[$pos]['alineado']=$tablaHTML[0]['alineado'];
		    $tablaHTML[$pos]['datos']=array($value['tag'],$value['nom'],$value['modelo'],$value['serie'],$value['localizacion'],$value['custodio'],$value['marca'],$value['estado'],$value['genero'],$value['color'],$value['OBSERVACION'],$fecha);
		    $tablaHTML[$pos]['estilo']='I';
		    $tablaHTML[$pos]['borde'] = 'T';  
		    $pos+=1;  
		}		

		$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,'fecha','fecha',$sizetable,true,$sal_hea_body=30,$orientacion='H');


	}

	function reporte_sap($query,$loc,$cus)
	{
		$sizetable = 6;
		$titulo="Reporte inventario";
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
		$pos=1;

		$tablaHTML = array();
		$tablaHTML[0]['medidas']=array(10,16,13,17,25,20,16,15,16,15,20,16,12,12,12,12,12,12,12);
		$tablaHTML[0]['alineado']=array('L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L');
		$tablaHTML[0]['datos']=array('COMPANYCODE','NUM. INVENTARIO','ASSET','SUBNUMBER','DESCRIPT','DESCRIPT2','MODELO','SERIE','date','location','FILE1','PERSON_NO','FILE2','EVALGROUP1','EVALGROUP2','EVALGROUP3','EVALGROUP4','ASSETSUPNO','FILE3');
		$tablaHTML[0]['estilo']='BI';
		$tablaHTML[0]['borde'] = '1';
		$datos = $this->articulo->lista_articulos_sap($query,$loc,$cus);

		foreach ($datos as $key => $value) {
		$fecha='';
			if($value['FECHA_INV_DATE'] !='')
			{
				$fecha =$value['FECHA_INV_DATE']->format('Y-m-d'); 
			}
			$tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		    $tablaHTML[$pos]['alineado']=$tablaHTML[0]['alineado'];
		    $tablaHTML[$pos]['datos']=array($value['COMPANYCODE'],'',$value['TAG_SERIE'],'',$value['DESCRIPT'],$value['DESCRIPT2'],$value['MODELO'],$value['SERIE'],$fecha,$value['EMPLAZAMIENTO'],$value['DENOMINACION'],$value['PERSON_NO'],$value['PERSON_NOM'],$value['marca'],$value['estado'],$value['genero'],$value['color'],$value['ASSETSUPNO'],$value['TAG_ANT']);
		    $tablaHTML[$pos]['estilo']='I';
		    $tablaHTML[$pos]['borde'] = 'T';  
		    $pos+=1;  
		}		

		$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,'fecha','fecha',$sizetable,true,$sal_hea_body=30,$orientacion='H');


	}


	function reporte_sap_bajas()
	{
		$sizetable = 6;
		$titulo="Reporte bajas";
		
		$pos=1;

		$tablaHTML = array();
		$tablaHTML[0]['medidas']=array(10,16,13,17,25,20,16,15,16,15,20,16,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12);
		$tablaHTML[0]['alineado']=array('L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L');
		$tablaHTML[0]['datos']=array('BUKRS','ANLN1','ANLN2','TXT50','TXTA50','ANLHTXT','SERNR','INVNR','IVDAT','MERGE','MEINS'
		,'STORT','KTEXT','PERNR','PERNP_TXT','ORD41','ORD42','ORD43','ORD44','GDLGRP','ANLUE','AIBN1','AKTIV','URWRT','','NOTE1','IMAGEN','ACTUALIZADO POR');
		$tablaHTML[0]['estilo']='BI';
		$tablaHTML[0]['borde'] = '1';
		// $datos = $this->articulo->lista_articulos_sap($query,$loc,$cus);

		// foreach ($datos as $key => $value) {
		// $fecha='';
		// 	if($value['FECHA_INV_DATE'] !='')
		// 	{
		// 		$fecha =$value['FECHA_INV_DATE']->format('Y-m-d'); 
		// 	}
		// 	$tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		//     $tablaHTML[$pos]['alineado']=$tablaHTML[0]['alineado'];
		//     $tablaHTML[$pos]['datos']=array($value['COMPANYCODE'],'',$value['TAG_SERIE'],'',$value['DESCRIPT'],$value['DESCRIPT2'],$value['MODELO'],$value['SERIE'],$fecha,$value['EMPLAZAMIENTO'],$value['DENOMINACION'],$value['PERSON_NO'],$value['PERSON_NOM'],$value['marca'],$value['estado'],$value['genero'],$value['color'],$value['ASSETSUPNO'],$value['TAG_ANT']);
		//     $tablaHTML[$pos]['estilo']='I';
		//     $tablaHTML[$pos]['borde'] = 'T';  
		//     $pos+=1;  
		// }		

		$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,'fecha','fecha',$sizetable,true,$sal_hea_body=30,$orientacion='H');
	}

	function codigo_qr($id)
	{
		$url = '../TEMP/QRCODE_'.$id.'.png';
		$image[0] = array('url'=>$url,'x'=>10,'y'=>10,'width'=>50,'height'=>50);
		$tablaHTML = array();
		$this->pdf->cabecera_reporte_MC($titulo=false,$tablaHTML,$contenido=false,$image,false,false,$sizetable=false,true);
	}
}
?>