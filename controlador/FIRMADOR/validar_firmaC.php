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
if(isset($_GET['validar_documento']))
{

	$parametros = $_POST;
	$p12 = $_FILES;
	echo json_encode($controlador->validar_documento($p12,$parametros));
	// print_r($_FILES);
	// print_r($_POST);die();
}

/**
 * 
 */
class validar_firmaC
{
	private $rutaTemp;	
	function __construct()
	{
		$this->rutaTemp = dirname(__DIR__,2).'/TEMP/';
		// code...
	}

	function validar_firma($file,$parametros)
	{
		
		$ruta_p12 = '';

        $uploadfile_temporal=$file['txt_cargar_imagen']['tmp_name'];
        $nombre = $file['txt_cargar_imagen']['name'];     
        $ruta_p12=$this->rutaTemp.str_replace(' ','_',$nombre);
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

	function validar_documento($file,$parametros)
	{
		$ruta_doc = '';

      $uploadfile_temporal=$file['txt_cargar_imagen']['tmp_name'];
      $nombre = $file['txt_cargar_imagen']['name'];     
      $ruta_doc=$this->rutaTemp.str_replace(' ','_',$nombre);
      // print_r($ruta_p12);die();
      if (is_uploaded_file($uploadfile_temporal))
      {
         move_uploaded_file($uploadfile_temporal,$ruta_doc);
      }

      $rutaJar = dirname(__DIR__,2).'/lib/firmarPdf/FirmarPDF.jar';
    	// $param = array('2',$ruta_p12,$parametros['txt_ingresarClave']);

    	// $param = json_encode($param);

    	$comando = "java -jar $rutaJar 3 $ruta_doc ";
    	$respuesta = shell_exec($comando);

    	$resp = json_decode($respuesta);
    	if(count($resp)>0)
    	{
    		if($resp[0]==1)
    		{
    			$firmas = json_decode($resp[1],true);
    			// print_r($firmas);die();
    			$tr='';
    			foreach ($firmas as $key => $value) {
    				$ci = $value['SERIALNUMBER'];
    				$serie = explode('-',$value['SERIALNUMBER']); 
    				if(count($serie)>1){$ci = $serie[0];}
    				$tr.='<tr>
    							<td>'.$ci.'</td>
    							<td>'.$value['CN'].'</td>
    							<td>'.$value['O'].'</td>
    							<td>'.$value['FechaFirma'].'</td>
    						</tr>';
    			}
    			return array('resp'=>$resp[0],'msj'=>'','tr'=>$tr);
    		}else
    		{
    			return array('resp'=>$resp[0],'msj'=>$resp[1],'tr'=>'');
    		}
    	}

		print_r($ruta_p12);
		print_r($parametros);die();

	}
}
?>