<?php 
$controlador = new validar_firmaC();
if(isset($_GET['validar_firma']))
{

	$parametros = $_POST;
	$p12 = $_FILES;
	echo json_encode($controlador->validar_firma($p12,$parametros));
	// print_r($_FILES);
	// print_r($_POST);die();
}

/**
 * 
 */
class validar_firmaC
{
	
	function __construct()
	{
		// code...
	}

	function validar_firma($file,$parametros)
	{
		$rutaTemp = dirname(__DIR__,2).'/TEMP/';
		$ruta_p12 = '';

        $uploadfile_temporal=$file['txt_cargar_imagen']['tmp_name'];
        $nombre = $file['txt_cargar_imagen']['name'];     
        $ruta_p12=$rutaTemp.str_replace(' ','_',$nombre);
        // print_r($ruta_p12);die();
        if (is_uploaded_file($uploadfile_temporal))
        {
           move_uploaded_file($uploadfile_temporal,$ruta_p12);
       	}

       	$rutaJar = dirname(__DIR__,2).'/lib/firmarPdf/FirmarPDF.jar';
       	// $param = array('2',$ruta_p12,$parametros['txt_ingresarClave']);

       	// $param = json_encode($param);

       	$comando = "java -jar $rutaJar 2 $ruta_p12 ".$parametros['txt_ingresarClave'];
       	$respuesta = shell_exec($comando);

       	$resp = json_decode($respuesta);
       	if(count($resp)>0)
       	{
       		return array('resp'=>$resp[0],'msj'=>$resp[1]);
       	}


	}
}
?>