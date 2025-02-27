<?php

error_reporting(E_ALL ^ E_NOTICE);

require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/impresion_tagM.php');
require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/articulosM.php');
require_once('formato_tagsC.php');


//require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/formato_tagsM.php');

/**
 * 
 **/

$controlador = new impresion_tagC();

if (isset($_GET["generarT"])) {
	echo json_encode($controlador->generar_tag($_POST['parametros']));
}

if (isset($_GET["guardar"])) {
	$parametros = array($_POST);

	echo json_encode($controlador->guardar_formato_etiqueta($parametros));
}

if (isset($_GET["abrir_impresora"])) {
	echo json_encode($controlador->abrir_impresora());
}

if (isset($_GET["delete"])) {
	echo json_encode($controlador->delete($_POST['id']));
}

if (isset($_GET["guardar_camvas"])) {
	$parametros = $_POST['parametros'];
	$controlador->guardar_camvas($parametros);
}



class impresion_tagC
{
	private $articulo;
	private $modelo;
	private $formato;

	function __construct()
	{
		$this->formato = new formato_tagsM();
		$this->formatoC = new formato_tagsC();
		$this->modelo = new impresion_tagM();
		$this->articulo = new articulosM();
	}

	function generar_tag($parametros)
	{
		$formato = $this->formato->lista_formato_tags($parametros['id_formato']);
		$datos = $this->articulo->lista_articulos('', '', '', false, $parametros['id_art']);
		$param = array();
		$respuesa = array();
		if ($formato[0]['barras'] == 1) {
			$param['barras'] = 'true';
			$param['contenido_br'] = $datos[0]['tag'];
			$param['tipo'] = 'codabar';
			$param['orientacion'] = 'horizontal';
		} else {
			$param['barras'] = 'false';
		}

		if ($formato[0]['qr'] == 1) {
			$param['qr'] = 'true';
			$param['contenido_qr'] = $datos[0]['tag'];
			$param['tamano'] = 50;
		} else {
			$param['qr'] = 'false';
		}
		if ($formato[0]['texto'] != '') {
			array_push($respuesa, array('texto' => $datos[0]['tag']));
		}
		$codigos = $this->formatoC->generar_tag($param);
		array_push($respuesa, array('IMAGEN' => $codigos));
		array_push($respuesa, array('DATOS' => $formato[0]));

		// print_r($respuesa); die();
		return $respuesa;
	}

	function generar_baras($parametros)
	{

		$barcodeText = $parametros['contenido_br'];
		$barcodeType = $parametros['tipo'];
		$barcodeDisplay = $parametros['orientacion'];
		$dir = 'temp/';
		if (!file_exists($dir))
			mkdir($dir);

		$this->bar->barcode($dir . $barcodeText . '_bar.png', $barcodeText, 50, $barcodeDisplay, $barcodeType, true);
		return '<img  id="img_br" src="../controlador/' . $dir . $barcodeText . '_bar.png' . '"  style="border:1px solid #d3d3d3; position:relative">';
		// return '<img  id="img_br" class="barcode" alt="'.$barcodeText.'" src="../lib/barcode.php?text='.$barcodeText.'&codetype='.$barcodeType.'&orientation='.$barcodeDisplay.'&size='.$barcodeSize.'&print='.$printText.'" style=" border:1px solid #d3d3d3; position:relative" />';


	}

	function generar_QR($contenido, $tamano = 10)
	{
		//Declaramos una carpeta temporal para guardar la imagenes generadas
		$dir = 'temp/';

		//Si no existe la carpeta la creamos
		if (!file_exists($dir))
			mkdir($dir);

		//Declaramos la ruta y nombre del archivo a generar
		$filename = $dir . $contenido . '.png';

		//Parametros de Condiguración

		$tamaño = $tamano; //Tamaño de Pixel
		$level = 'L'; //Precisión Baja
		$framSize = 3; //Tamaño en blanco
		$contenido = $contenido; //Texto

		//Enviamos los parametros a la Función para generar código QR 
		QRcode::png($contenido, $filename, $level, $tamaño, $framSize);

		return '<img id="img_qr" src="../controlador/' . $dir . basename($filename) . '"  style="border:1px solid #d3d3d3; position:relative"/>';
	}

	function guardar_formato_etiqueta($parametros)
	{
		$datos[0]['campo'] = 'barras';
		$datos[1]['campo'] = 'br_y';
		$datos[2]['campo'] = 'br_x';
		$datos[3]['campo'] = 'br_h';
		$datos[4]['campo'] = 'br_w';
		$datos[5]['campo'] = 'qr';
		$datos[6]['campo'] = 'qr_y';
		$datos[7]['campo'] = 'qr_x';
		$datos[8]['campo'] = 'qr_h';
		$datos[9]['campo'] = 'qr_w';
		$datos[10]['campo'] = 'texto';
		$datos[11]['campo'] = 'tamano_texto';
		$datos[12]['campo'] = 'texto_x';
		$datos[13]['campo'] = 'texto_y';
		$datos[14]['campo'] = 'texto_barras';
		$datos[15]['campo'] = 'texto_qr';

		if (isset($parametros[0]['rbl_barras'])) {
			$datos[0]['dato'] = 1;
			$datos[2]['dato'] = $parametros[0]['txt_br_x'];
			$datos[1]['dato'] = $parametros[0]['txt_br_y'];
			$datos[3]['dato'] = $parametros[0]['txt_br_w'];
			$datos[3]['dato'] = $parametros[0]['txt_br_h'];
			$datos[14]['dato'] = $parametros[0]['txt_barcode'];
		} else {
			$datos[0]['dato'] = "";
			$datos[2]['dato'] = '';
			$datos[1]['dato'] = '';
			$datos[3]['dato'] = '';
			$datos[3]['dato'] = '';
			$datos[14]['dato'] = '';
		}
		if (isset($parametros[0]['rbl_qr'])) {
			$datos[9]['dato'] = $parametros[0]['txt_qr_w'];
			$datos[15]['dato'] = $parametros[0]['txt_codeqr'];
			$datos[7]['dato'] = $parametros[0]['txt_qr_x'];
			$datos[6]['dato'] = $parametros[0]['txt_qr_y'];
			$datos[8]['dato'] = $parametros[0]['txt_qr_h'];
		} else {
			$datos[9]['dato'] = "";
			$datos[15]['dato'] = "";
			$datos[7]['dato'] = "";
			$datos[6]['dato'] = "";
			$datos[8]['dato'] = "";
		}
		if ($parametros['txt_alternativo'] == '') {
			$datos[10]['dato'] = $parametros[0]['txt_alternativo'];
			$datos[11]['dato'] = $parametros[0]['txt_alt_ta'];
			$datos[12]['dato'] = $parametros[0]['txt_alt_x'];
			$datos[13]['dato'] = $parametros[0]['txt_alt_y'];
		} else {
			$datos[10]['dato'] = "";
			$datos[11]['dato'] = "";
			$datos[12]['dato'] = "";
			$datos[13]['dato'] = "";
		}
		$resp = $this->modelo->insertar($datos);
		return $resp;
	}

	function abrir_impresora()
	{
		system('c:\\printer.exe', $retorno);
		return $retorno;
	}

	function delete($id)
	{
		$datos[0]['campo'] = 'id_formato_eti';
		$datos[0]['dato'] = $id;
		$res = $this->modelo->eliminar($datos);
		return $res;
	}

	function guardar_cam($parametros)
	{
		$datosBase64 = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $parametros['blod']));
		// definimos la ruta donde se guardara en el server
		$path = 'temp/' . $parametros['nom'] . "_fn";
		// guardamos la imagen en el server
		if (!file_put_contents($path, $datosBase64)) {
			// retorno si falla
			return false;
		} else {
			// retorno si todo fue bien
			return true;
		}
	}
}
