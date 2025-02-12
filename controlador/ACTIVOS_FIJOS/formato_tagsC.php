<?php
error_reporting(E_ALL ^ E_NOTICE);
require "../lib/phpqrcode/qrlib.php";  
require ("../lib/barcode/barcode.php"); 
require('../modelo/formato_tagsM.php'); 
/**
 * 
 */
$controlador = new formato_tagsC();
if(isset($_GET["generar"]))
{
	echo json_encode($controlador->generar_tag($_POST['parametros']));
}
if(isset($_GET["guardar"]))
{
	$parametros = array($_POST);

	echo json_encode($controlador->guardar_formato_etiqueta($parametros));
}
if(isset($_GET["formato"]))
{
	$parametros = $_POST['id'];
	echo json_encode($controlador->buscar_formato($parametros));
}
class formato_tagsC
{
	private $bar;
	private $modelo;
	
	function __construct()
	{
		$this->bar = new barcode_class(); 
		$this->modelo = new formato_tagsM();
	}

function generar_tag($parametros)
{
	// print_r($parametros);die();

	$contenido_tag = array();
	if($parametros['barra']!='false')
	{
		$codigo =  $this->generar_baras($parametros);
	    array_push($contenido_tag, array('imagen'=>$codigo));
	}
	if($parametros['qr']!='false')
	{	  
	  $codigo = $this->generar_QR($parametros['contenido_qr'],$parametros['tamano']);
	  array_push($contenido_tag, array('imagen'=>$codigo));
	}
	
	// print_r($contenido_tag);die();
	return $contenido_tag;

}

function generar_baras($parametros)
{	
			
		$barcodeText = $parametros['contenido_br'];
		$barcodeType=$parametros['tipo'];
		$barcodeDisplay=$parametros['orientacion'];
		$barcodeSize=$parametros['tamano_br'];
		$printText=true;
		$dir = 'temp/';
		if (!file_exists($dir))
        mkdir($dir);

		$this->bar->barcode($dir.$barcodeText.'_bar.png', $barcodeText, 50, $barcodeDisplay, $barcodeType, true);
		return '<img  id="img_br" src="../controlador/'.$dir.$barcodeText.'_bar.png'.'"  style="position:relative">';
		// return '<img  id="img_br" class="barcode" alt="'.$barcodeText.'" src="../lib/barcode.php?text='.$barcodeText.'&codetype='.$barcodeType.'&orientation='.$barcodeDisplay.'&size='.$barcodeSize.'&print='.$printText.'" style=" border:1px solid #d3d3d3; position:relative" />';

		
}

function generar_QR($contenido,$tamano=10)
{	
	//Declaramos una carpeta temporal para guardar la imagenes generadas
	$dir = 'temp/';
	
	//Si no existe la carpeta la creamos
	if (!file_exists($dir))
        mkdir($dir);
	
        //Declaramos la ruta y nombre del archivo a generar
	$filename = $dir.$contenido.'.png';
 
        //Parametros de Condiguración
	
	$tamaño = $tamano; //Tamaño de Pixel
	$level = 'L'; //Precisión Baja
	$framSize = 3; //Tamaño en blanco
	$contenido = $contenido; //Texto
	
        //Enviamos los parametros a la Función para generar código QR 
	QRcode::png($contenido, $filename, $level, $tamaño, $framSize); 

	return '<img id="img_qr" src="../controlador/'.$dir.basename($filename).'"  style="border:1px solid #d3d3d3; position:relative"/>';  
}
function guardar_formato_etiqueta($parametros)
{
//print_r($parametros);die();
	$datos[0]['campo']='barras';
	$datos[1]['campo']='br_y';
	$datos[2]['campo']='br_x';
	$datos[3]['campo']='br_h';
	$datos[4]['campo']='br_w';
	$datos[5]['campo']='qr';
	$datos[6]['campo']='qr_y';
	$datos[7]['campo']='qr_x';
	$datos[8]['campo']='qr_h';
	$datos[9]['campo']='qr_w';
	$datos[10]['campo']='texto';
	$datos[11]['campo']='tamano_texto';
	$datos[12]['campo']='texto_x';
	$datos[13]['campo']='texto_y';
	$datos[14]['campo']='texto_barras';
	$datos[15]['campo']='texto_qr';
	$datos[16]['campo'] = 'nombre_etiqueta';
	$datos[16]['dato'] = $parametros[0]['txt_nom_eti'];

	if(isset($parametros[0]['rbl_barras']))
	{
		$datos[0]['dato']=1;
		$datos[2]['dato']=$parametros[0]['txt_br_x'];
		$datos[1]['dato']=$parametros[0]['txt_br_y'];
		$datos[4]['dato']=$parametros[0]['txt_br_w'];
		$datos[3]['dato']=$parametros[0]['txt_br_h'];
        $datos[14]['dato']=$parametros[0]['txt_barcode'];

	}else
	{
		$datos[0]['dato']="";
		$datos[2]['dato']='';
		$datos[1]['dato']='';
		$datos[3]['dato']='';
		$datos[3]['dato']='';
        $datos[14]['dato']='';

	}
	if(isset($parametros[0]['rbl_qr']))
	{
		$datos[5]['dato']=1;
		$datos[9]['dato'] =$parametros[0]['txt_qr_w'];
        $datos[15]['dato']=$parametros[0]['txt_codeqr']; 
        $datos[7]['dato']=$parametros[0]['txt_qr_x'];
        $datos[6]['dato']=$parametros[0]['txt_qr_y'];
        $datos[8]['dato'] =$parametros[0]['txt_qr_h'];
	}else
	{
		$datos[5]['dato']=0;
		$datos[9]['dato'] ="";
        $datos[15]['dato']="";
        $datos[7]['dato']="";
        $datos[6]['dato']="";
        $datos[8]['dato'] ="";

	}
	if($parametros['txt_alternativo']=='')
	{       
		$datos[10]['dato']=$parametros[0]['txt_alternativo'];
	    $datos[11]['dato']=$parametros[0]['txt_alt_ta']; 
	    $datos[12]['dato']=$parametros[0]['txt_alt_x'];
	    $datos[13]['dato']=$parametros[0]['txt_alt_y'];
	}else
	{
		$datos[10]['dato']="";
	    $datos[11]['dato']=""; 
	    $datos[12]['dato']="";
	    $datos[13]['dato']="";
	}
	$resp = $this->modelo->insertar($datos);
	return $resp;

}
function buscar_formato($id)
{
	$resp = $this->modelo->lista_formato_tags($id);
	return $resp;

}

}
?>